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
     * @return array Decoded JSON result from Python script
     */
    public function analyzeRecording(string $audioFilePath): array
    {
        return $this->executePythonScript($this->scriptPath, [$audioFilePath]);
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
            '%s %s %s 2>&1',
            escapeshellcmd($this->pythonPath),
            escapeshellarg($scriptPath),
            implode(' ', $argStrings)
        );

        $descriptorSpec = [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];

        $process = proc_open($command, $descriptorSpec, $pipes);

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

        if (strspn($trimmedStdout, '{') !== 0 || strrpos($trimmedStdout, '}') !== strlen($trimmedStdout) - 1) {
            $firstBrace = strpos($trimmedStdout, '{');
            $lastBrace  = strrpos($trimmedStdout, '}');
            if ($firstBrace !== false && $lastBrace !== false && $lastBrace > $firstBrace) {
                $trimmedStdout = substr($trimmedStdout, $firstBrace, ($lastBrace - $firstBrace + 1));
            }
        }

        $result = json_decode($trimmedStdout, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($result)) {
            throw new \RuntimeException("Invalid JSON output from Python script. Output was: " . substr($stdout, 0, 500) . "... Error: " . json_last_error_msg());
        }

        return $result;
    }
}
