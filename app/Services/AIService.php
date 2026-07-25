<?php

namespace App\Services;

class AIService
{
    protected string $pythonPath;
    protected string $scriptPath;

    public function __construct(?string $pythonPath = null, ?string $scriptPath = null)
    {
        $this->pythonPath = $pythonPath ?? (getenv('PYTHON_BIN') ?: 'python3');
        $this->scriptPath = $scriptPath ?? (APPPATH . 'Python/analyze.py');
    }

    /**
     * Run Python analyze.py CLI tool on the audio file path and parse returned JSON result.
     *
     * @param string $audioFilePath Absolute path to the audio file
     * @return array Decoded JSON result from Python script
     */
    public function analyzeRecording(string $audioFilePath): array
    {
        if (!file_exists($audioFilePath)) {
            throw new \InvalidArgumentException("Audio file does not exist: {$audioFilePath}");
        }

        if (!file_exists($this->scriptPath)) {
            throw new \RuntimeException("Python analysis script not found at: {$this->scriptPath}");
        }

        $command = sprintf(
            '%s %s %s 2>&1',
            escapeshellcmd($this->pythonPath),
            escapeshellarg($this->scriptPath),
            escapeshellarg($audioFilePath)
        );

        $descriptorSpec = [
            0 => ['pipe', 'r'], // stdin
            1 => ['pipe', 'w'], // stdout
            2 => ['pipe', 'w'], // stderr
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

        // Parse JSON output from stdout
        $trimmedStdout = trim($stdout);
        
        // Isolate JSON block if log lines are present
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
