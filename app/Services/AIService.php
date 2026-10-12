<?php

namespace App\Services;

class AIService
{
    protected string $pythonPath;
    protected string $scriptPath;

    public function __construct(?string $pythonPath = null, ?string $scriptPath = null)
    {
        $this->scriptPath = $scriptPath ?? (APPPATH . 'Python/analyze.py');

        if ($pythonPath !== null) {
            $this->pythonPath = $pythonPath;
        } else {
            $envBin = (function_exists('env') ? env('PYTHON_BIN') : null) ?: getenv('PYTHON_BIN');
            if ($envBin && (file_exists($envBin) || $envBin === 'python3' || $envBin === 'python')) {
                $this->pythonPath = $envBin;
            } else {
                // Auto-detect project virtual environment first
                $venvBin = APPPATH . 'Python/venv/bin/python3';
                $venvBinAlt = APPPATH . 'Python/venv/bin/python';
                if (file_exists($venvBin) && is_executable($venvBin)) {
                    $this->pythonPath = $venvBin;
                } elseif (file_exists($venvBinAlt) && is_executable($venvBinAlt)) {
                    $this->pythonPath = $venvBinAlt;
                } else {
                    $this->pythonPath = 'python3';
                }
            }
        }
    }

    /**
     * Run Python analyze.py CLI tool on the audio file path and parse returned JSON result.
     *
     * @param string $audioFilePath Absolute path to the audio file
     * @param string|null $service Service type or ID (walking, grooming, training, sitting)
     * @param int|null $providerId Provider ID to fetch specific training types
     * @param array|null $questions Optional pre-filtered verification questions array from VerificationQuestionModel
     * @return array Decoded JSON result from Python script
     */
    public function analyzeRecording(string $audioFilePath, ?string $service = null, ?int $providerId = null, ?array $questions = null): array
    {
        $args = [$audioFilePath];
        if ($service !== null && $service !== '') {
            $args[] = $service;
        }
        if ($providerId !== null && $providerId > 0) {
            $args[] = (string)$providerId;
        }

        $tempQFile = null;
        if (!empty($questions)) {
            $tempQFile = sys_get_temp_dir() . '/pf_q_' . md5(uniqid('pf', true)) . '.json';
            file_put_contents($tempQFile, json_encode(array_values($questions), JSON_UNESCAPED_UNICODE));
            $args[] = '--questions-file';
            $args[] = $tempQFile;
        }

        try {
            return $this->executePythonScript($this->scriptPath, $args);
        } finally {
            if ($tempQFile && file_exists($tempQFile)) {
                @unlink($tempQFile);
            }
        }
    }

    /**
     * Run specialized analyze_client_call.py on Client <-> SP audio recording.
     *
     * @param string $audioFilePath Absolute path to the audio file
     * @param string $bookedService Booked service (walking, grooming, training, sitting)
     * @return array Decoded JSON analysis result
     */
    public function analyzeClientRecording(string $audioFilePath, string $bookedService = 'walking'): array
    {
        $clientScript = APPPATH . 'Python/analyze_client_call.py';
        if (!file_exists($clientScript)) {
            $clientScript = $this->scriptPath;
        }

        return $this->executePythonScript($clientScript, [$audioFilePath, $bookedService]);
    }

    /**
     * Executes given Python script with arguments and safely parses JSON output.
     */
    protected function executePythonScript(string $scriptPath, array $arguments): array
    {
        if (!file_exists($scriptPath)) {
            throw new \RuntimeException("Python analysis script not found at: {$scriptPath}");
        }

        $argStrings = array_map('escapeshellarg', $arguments);
        $command = sprintf(
            '%s %s %s',
            escapeshellcmd($this->pythonPath),
            escapeshellarg($scriptPath),
            implode(' ', $argStrings)
        );

        $descriptorSpec = [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];

        @mkdir('/tmp/huggingface', 0777, true);
        @mkdir('/tmp/torch', 0777, true);

        $env = array_merge($_ENV, [
            'HOME' => '/tmp',
            'HF_HOME' => '/tmp/huggingface',
            'TORCH_HOME' => '/tmp/torch',
            'PYTHONUNBUFFERED' => '1',
            'PATH' => getenv('PATH') ?: '/usr/local/bin:/usr/bin:/bin',
        ]);

        $process = proc_open($command, $descriptorSpec, $pipes, null, $env);

        if (!is_resource($process)) {
            throw new \RuntimeException("Failed to execute process: {$command}");
        }

        fclose($pipes[0]);

        $stdout = stream_get_contents($pipes[1]);
        fclose($pipes[1]);

        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[2]);

        $exitCode = proc_close($process);

        if ($exitCode !== 0 && empty($stdout)) {
            throw new \RuntimeException("Python script exited with error code ({$exitCode}): {$stderr}");
        }

        $trimmedStdout = trim($stdout);

        // 1. Try direct decode
        $result = json_decode($trimmedStdout, true);

        // 2. Try extracting from first to last brace
        if (!is_array($result)) {
            $firstBrace = strpos($trimmedStdout, '{');
            $lastBrace  = strrpos($trimmedStdout, '}');
            if ($firstBrace !== false && $lastBrace !== false && $lastBrace > $firstBrace) {
                $sub = substr($trimmedStdout, $firstBrace, ($lastBrace - $firstBrace + 1));
                $result = json_decode($sub, true);
            }
        }

        // 3. Try parsing line-by-line from bottom (where print(json.dumps) outputs)
        if (!is_array($result)) {
            $lines = explode("\n", $trimmedStdout);
            for ($i = count($lines) - 1; $i >= 0; $i--) {
                $line = trim($lines[$i]);
                if (strpos($line, '{') === 0 && substr($line, -1) === '}') {
                    $test = json_decode($line, true);
                    if (is_array($test)) {
                        $result = $test;
                        break;
                    }
                }
            }
        }

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($result)) {
            throw new \RuntimeException("Invalid JSON output from Python script. Output was: " . substr($stdout, 0, 500) . "... Error: " . json_last_error_msg());
        }

        return $result;
    }
}
