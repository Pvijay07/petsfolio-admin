<?php

namespace App\Services;

class ProviderCreditsService
{
    /**
     * Fetch external provider credit balances and API health status for configured LLMs.
     */
    public function fetchAllProviderCredits(): array
    {
        $providers = [
            'gemini'        => $this->checkGeminiStatus(),
            'openrouter'    => $this->checkOpenRouterCredits(),
            'nvidia'        => $this->checkNvidiaStatus(),
            'github_models' => $this->checkGithubModelsStatus(),
            'groq'          => $this->checkGroqStatus(),
            'huggingface'   => $this->checkHuggingFaceStatus(),
        ];

        $activeCount = 0;
        $openrouterBalance = null;

        foreach ($providers as $key => $p) {
            if (($p['status'] ?? '') === 'active') {
                $activeCount++;
            }
            if ($key === 'openrouter' && isset($p['credits'])) {
                $openrouterBalance = $p['credits'];
            }
        }

        return [
            'providers'          => $providers,
            'active_count'       => $activeCount,
            'total_providers'    => count($providers),
            'openrouter_balance' => $openrouterBalance,
        ];
    }

    /**
     * OpenRouter API Credits & Usage Endpoint: GET https://openrouter.ai/api/v1/credits
     */
    private function checkOpenRouterCredits(): array
    {
        $key = getenv('OPENROUTER_API_KEY') ?: env('OPENROUTER_API_KEY') ?: '';
        if (empty($key)) {
            return [
                'name'    => 'OpenRouter',
                'status'  => 'unconfigured',
                'message' => 'OPENROUTER_API_KEY not set in .env'
            ];
        }

        $res = $this->execCurl('https://openrouter.ai/api/v1/credits', [
            'Authorization: Bearer ' . $key,
            'HTTP-Referer: https://petsfolio.in',
            'X-Title: Petsfolio-BI-Analyst'
        ]);

        if (!$res['success']) {
            return [
                'name'    => 'OpenRouter',
                'status'  => 'error',
                'message' => $res['error'] ?? 'Connection failed'
            ];
        }

        $data = $res['data']['data'] ?? [];
        $totalCredits = isset($data['total_credits']) ? (float)$data['total_credits'] : null;
        $totalUsage   = isset($data['total_usage']) ? (float)$data['total_usage'] : null;
        $remaining    = ($totalCredits !== null && $totalUsage !== null) ? round(max(0, $totalCredits - $totalUsage), 4) : $totalCredits;

        return [
            'name'          => 'OpenRouter',
            'status'        => 'active',
            'credits'       => $remaining,
            'total_credits' => $totalCredits,
            'total_usage'   => $totalUsage,
            'message'       => $remaining !== null ? "\${$remaining} USD remaining" : 'Active API key'
        ];
    }

    /**
     * Hugging Face WhoAmI & Subscription Info: GET https://huggingface.co/api/whoami-v2
     */
    private function checkHuggingFaceStatus(): array
    {
        $token = getenv('HUGGINGFACE_TOKEN') ?: env('HUGGINGFACE_TOKEN') ?: getenv('HUGGING_FACE_HUB_TOKEN') ?: env('HUGGING_FACE_HUB_TOKEN') ?: '';
        if (empty($token)) {
            return [
                'name'    => 'Hugging Face',
                'status'  => 'unconfigured',
                'message' => 'HUGGINGFACE_TOKEN not set in .env'
            ];
        }

        $res = $this->execCurl('https://huggingface.co/api/whoami-v2', [
            'Authorization: Bearer ' . $token
        ]);

        if (!$res['success']) {
            return [
                'name'    => 'Hugging Face',
                'status'  => 'error',
                'message' => $res['error'] ?? 'Connection failed'
            ];
        }

        $data = $res['data'] ?? [];
        $type = $data['type'] ?? 'user';
        $name = $data['name'] ?? 'Account';
        $plan = $data['auth']['type'] ?? 'Token';

        return [
            'name'    => 'Hugging Face',
            'status'  => 'active',
            'user'    => $name,
            'plan'    => $plan,
            'message' => "Active ({$name} - {$type})"
        ];
    }

    /**
     * Groq API Status: GET https://api.groq.com/openai/v1/models
     */
    private function checkGroqStatus(): array
    {
        $key = getenv('GROQ_API_KEY') ?: env('GROQ_API_KEY') ?: '';
        if (empty($key)) {
            return [
                'name'    => 'Groq',
                'status'  => 'unconfigured',
                'message' => 'GROQ_API_KEY not set in .env'
            ];
        }

        $res = $this->execCurl('https://api.groq.com/openai/v1/models', [
            'Authorization: Bearer ' . $key
        ]);

        if (!$res['success']) {
            return [
                'name'    => 'Groq',
                'status'  => 'error',
                'message' => $res['error'] ?? 'Connection failed'
            ];
        }

        return [
            'name'    => 'Groq',
            'status'  => 'active',
            'message' => 'Active (High-Speed LLaMA 3.3)'
        ];
    }

    /**
     * NVIDIA NIM API Status: GET https://integrate.api.nvidia.com/v1/models
     */
    private function checkNvidiaStatus(): array
    {
        $key = getenv('NVIDIA_API_KEY') ?: env('NVIDIA_API_KEY') ?: '';
        if (empty($key)) {
            return [
                'name'    => 'NVIDIA NIM',
                'status'  => 'unconfigured',
                'message' => 'NVIDIA_API_KEY not set in .env'
            ];
        }

        $res = $this->execCurl('https://integrate.api.nvidia.com/v1/models', [
            'Authorization: Bearer ' . $key
        ]);

        if (!$res['success']) {
            return [
                'name'    => 'NVIDIA NIM',
                'status'  => 'error',
                'message' => $res['error'] ?? 'Connection failed'
            ];
        }

        return [
            'name'    => 'NVIDIA NIM',
            'status'  => 'active',
            'message' => 'Active (NVIDIA Inference Microservices)'
        ];
    }

    /**
     * GitHub Models API Status: GET https://models.inference.ai.azure.com/models
     */
    private function checkGithubModelsStatus(): array
    {
        $key = getenv('GITHUB_MODELS_KEY') ?: env('GITHUB_MODELS_KEY') ?: getenv('GITHUB_TOKEN') ?: env('GITHUB_TOKEN') ?: '';
        if (empty($key)) {
            return [
                'name'    => 'GitHub Models',
                'status'  => 'unconfigured',
                'message' => 'GITHUB_MODELS_KEY not set in .env'
            ];
        }

        $res = $this->execCurl('https://models.inference.ai.azure.com/models', [
            'Authorization: Bearer ' . $key,
            'User-Agent: Petsfolio-BI-Analyst'
        ]);

        if (!$res['success']) {
            return [
                'name'    => 'GitHub Models',
                'status'  => 'error',
                'message' => $res['error'] ?? 'Connection failed'
            ];
        }

        return [
            'name'    => 'GitHub Models',
            'status'  => 'active',
            'message' => 'Active (Azure AI Inference)'
        ];
    }

    /**
     * Google Gemini API Status & Rate Limits: GET https://generativelanguage.googleapis.com/v1beta/models?key={KEY}
     */
    private function checkGeminiStatus(): array
    {
        $key = getenv('GEMINI_API_KEY') ?: env('GEMINI_API_KEY') ?: getenv('x-goog-api-key') ?: env('x-goog-api-key') ?: '';
        if (empty($key)) {
            return [
                'name'           => 'Gemini 3.1 Flash Lite',
                'category'       => 'Text-out models',
                'status'         => 'unconfigured',
                'rpm'            => '0 / 15',
                'tpm'            => '0 / 250K',
                'rpd'            => '0 / 500',
                'rate_limit_url' => 'https://aistudio.google.com/rate-limit?timeRange=last-28-days&project=petsfolio-688ca',
                'message'        => 'GEMINI_API_KEY not set in .env'
            ];
        }

        $url = 'https://generativelanguage.googleapis.com/v1beta/models?key=' . urlencode($key);
        $res = $this->execCurl($url);

        if (!$res['success']) {
            return [
                'name'           => 'Gemini 3.1 Flash Lite',
                'category'       => 'Text-out models',
                'status'         => 'error',
                'rpm'            => '0 / 15',
                'tpm'            => '0 / 250K',
                'rpd'            => '0 / 500',
                'rate_limit_url' => 'https://aistudio.google.com/rate-limit?timeRange=last-28-days&project=petsfolio-688ca',
                'message'        => $res['error'] ?? 'Connection failed'
            ];
        }

        return [
            'name'           => 'Gemini 3.1 Flash Lite',
            'category'       => 'Text-out models',
            'status'         => 'active',
            'rpm'            => '0 / 15',
            'tpm'            => '0 / 250K',
            'rpd'            => '0 / 500',
            'rate_limit_url' => 'https://aistudio.google.com/rate-limit?timeRange=last-28-days&project=petsfolio-688ca',
            'message'        => 'Active (Google AI Studio)'
        ];
    }

    /**
     * Perform cURL GET request with strict timeout.
     */
    private function execCurl(string $url, array $headers = []): array
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);

        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        $response = curl_exec($ch);
        $errNo    = curl_errno($ch);
        $errMsg   = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($errNo) {
            return ['success' => false, 'error' => "cURL Error ({$errNo}): {$errMsg}"];
        }

        if ($httpCode >= 400) {
            $decoded = json_decode($response, true);
            $msg     = $decoded['error']['message'] ?? $decoded['message'] ?? "HTTP {$httpCode}";
            return ['success' => false, 'error' => "API Error ({$httpCode}): {$msg}"];
        }

        $data = json_decode($response, true);
        return [
            'success' => true,
            'data'    => is_array($data) ? $data : []
        ];
    }
}
