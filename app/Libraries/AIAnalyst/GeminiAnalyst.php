<?php

namespace App\Libraries\AIAnalyst;

/**
 * GeminiAnalyst
 * 
 * Orchestrates Gemini LLM interaction with function calling for AI Business Analyst.
 */
class GeminiAnalyst
{
    private QueryValidator $validator;
    private string $apiKey;
    private string $modelUrl;

    public function __construct()
    {
        $this->validator = new QueryValidator();
        $this->apiKey   = getenv('x-goog-api-key') ?: env('x-goog-api-key') ?: '';
        $model = getenv('GEMINI_MODEL') ?: env('GEMINI_MODEL') ?: 'gemini-2.5-flash-lite';
        $this->modelUrl = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent";
    }

    /**
     * Main conversation runner.
     *
     * @param string $userMessage
     * @param array $history Previous conversation messages
     * @param int|null $messageId DB message ID for query audit
     * @return array Response array containing reply, metrics_called, and optional chart_config
     */
    public function ask(string $userMessage, array $history = [], ?int $messageId = null): array
    {
        if (empty($this->apiKey) && empty(getenv('OPENROUTER_API_KEY')) && empty(getenv('NVIDIA_API_KEY')) && empty(getenv('GITHUB_MODELS_KEY')) && empty(getenv('GROQ_API_KEY')) && empty(getenv('HUGGINGFACE_TOKEN'))) {
            return [
                'status'  => false,
                'reply'   => 'Error: No active AI LLM provider keys (Gemini, OpenRouter, NVIDIA, GitHub Models, Groq, Hugging Face) are configured in .env',
                'metrics' => [],
                'chart'   => null
            ];
        }

        $systemInstruction = [
            'parts' => [
                ['text' => SchemaContext::getSystemInstruction()]
            ]
        ];

        $tools = SchemaContext::getToolDeclarations();

        // Build contents history payload
        $contents = [];

        foreach ($history as $msg) {
            $role    = strtolower($msg['role'] ?? 'user');
            $roleStr = ($role === 'user') ? 'user' : 'model';
            $text    = $msg['content'] ?? '';

            if (!empty($text)) {
                $contents[] = [
                    'role'  => $roleStr,
                    'parts' => [['text' => $text]]
                ];
            }
        }

        // Append latest user message
        $contents[] = [
            'role'  => 'user',
            'parts' => [['text' => $userMessage]]
        ];

        // Step 1: Initial call to Gemini with tools
        $requestPayload = [
            'systemInstruction' => $systemInstruction,
            'tools'             => $tools,
            'contents'          => $contents
        ];

        $geminiResponse = $this->callGemini($requestPayload);

        if (isset($geminiResponse['error'])) {
            return [
                'status'  => false,
                'reply'   => 'Gemini API Error: ' . ($geminiResponse['error']['message'] ?? 'Unknown error'),
                'metrics' => [],
                'chart'   => null
            ];
        }

        $candidate = $geminiResponse['candidates'][0] ?? null;
        $parts     = $candidate['content']['parts'] ?? [];

        $metricsCalled = [];
        $functionResults = [];

        // Check if Gemini invoked function calls
        foreach ($parts as $part) {
            if (isset($part['functionCall'])) {
                $functionCall = $part['functionCall'];
                $funcName     = $functionCall['name'];
                $funcArgs     = $functionCall['args'] ?? [];

                // Convert stdClass/assoc args
                if (is_object($funcArgs)) {
                    $funcArgs = json_decode(json_encode($funcArgs), true);
                }

                // Execute metric
                $metricResult = $this->validator->executeMetric($funcName, $funcArgs, $messageId);
                $metricsCalled[] = [
                    'name'  => $funcName,
                    'args'  => $funcArgs,
                    'data'  => $metricResult['data'] ?? []
                ];

                $functionResults[] = [
                    'functionResponse' => [
                        'name'     => $funcName,
                        'response' => [
                            'name'    => $funcName,
                            'content' => $metricResult['data'] ?? []
                        ]
                    ]
                ];
            }
        }

        // Auto-recovery: If no function calls were made by the model, infer intent from user message
        if (empty($functionResults)) {
            $recovered = $this->autoRecoverMetrics($userMessage, $messageId);
            if (!empty($recovered['functionResults'])) {
                $metricsCalled   = $recovered['metrics'];
                $functionResults = $recovered['functionResults'];
                $parts           = $recovered['parts'];
            }
        }

        // Step 2: If functions were called, send results back to Gemini for natural language summary
        if (!empty($functionResults)) {
            // Append assistant model turn with functionCall
            $contents[] = [
                'role'  => 'model',
                'parts' => $parts
            ];

            // Append function response turn
            $contents[] = [
                'role'  => 'user',
                'parts' => $functionResults
            ];

            $secondPayload = [
                'systemInstruction' => $systemInstruction,
                'contents'          => $contents
            ];

            $secondResponse = $this->callGemini($secondPayload);
            $secondCandidate = $secondResponse['candidates'][0] ?? null;
            $secondParts     = $secondCandidate['content']['parts'] ?? [];

            $finalText = '';
            foreach ($secondParts as $p) {
                if (isset($p['text'])) {
                    $finalText .= $p['text'];
                }
            }

            if (empty(trim($finalText)) || preg_match('/cannot directly compare|values are not provided|not provided in the query|cannot offer data-driven|without the specific/i', $finalText)) {
                $reply = $this->buildExecutiveComparisonSummary($metricsCalled);
            } else {
                $reply = trim($finalText);
            }
        } else {
            // No function call was needed, get text directly
            $finalText = '';
            foreach ($parts as $p) {
                if (isset($p['text'])) {
                    $finalText .= $p['text'];
                }
            }
            $reply = trim($finalText) ?: 'I am ready to analyze your business metrics. Ask me about lead conversion, resolution times, revenue, or bookings.';
        }

        // Determine if chart visual is recommended based on metric results
        $chartConfig = $this->generateChartConfigIfApplicable($metricsCalled);

        return [
            'status'  => true,
            'reply'   => $reply,
            'metrics' => $metricsCalled,
            'chart'   => $chartConfig
        ];
    }

    /**
     * Streaming conversation runner for SSE token streaming.
     */
    public function askStream(string $userMessage, array $history = [], ?int $messageId = null, ?callable $onChunk = null): array
    {
        $systemInstruction = [
            'parts' => [
                ['text' => SchemaContext::getSystemInstruction()]
            ]
        ];

        $tools = SchemaContext::getToolDeclarations();

        $contents = [];
        foreach ($history as $msg) {
            $role    = strtolower($msg['role'] ?? 'user');
            $roleStr = ($role === 'user') ? 'user' : 'model';
            $text    = $msg['content'] ?? '';

            if (!empty($text)) {
                $contents[] = [
                    'role'  => $roleStr,
                    'parts' => [['text' => $text]]
                ];
            }
        }

        $contents[] = [
            'role'  => 'user',
            'parts' => [['text' => $userMessage]]
        ];

        $requestPayload = [
            'systemInstruction' => $systemInstruction,
            'tools'             => $tools,
            'contents'          => $contents
        ];

        $geminiResponse = $this->callGemini($requestPayload);

        $candidate = $geminiResponse['candidates'][0] ?? null;
        $parts     = $candidate['content']['parts'] ?? [];

        $metricsCalled   = [];
        $functionResults = [];

        foreach ($parts as $part) {
            if (isset($part['functionCall'])) {
                $functionCall = $part['functionCall'];
                $funcName     = $functionCall['name'];
                $funcArgs     = $functionCall['args'] ?? [];

                if (is_object($funcArgs)) {
                    $funcArgs = json_decode(json_encode($funcArgs), true);
                }

                $metricResult = $this->validator->executeMetric($funcName, $funcArgs, $messageId);
                $metricsCalled[] = [
                    'name'  => $funcName,
                    'args'  => $funcArgs,
                    'data'  => $metricResult['data'] ?? []
                ];

                $functionResults[] = [
                    'functionResponse' => [
                        'name'     => $funcName,
                        'response' => [
                            'name'    => $funcName,
                            'content' => $metricResult['data'] ?? []
                        ]
                    ]
                ];
            }
        }

        if (empty($functionResults)) {
            $recovered = $this->autoRecoverMetrics($userMessage, $messageId);
            if (!empty($recovered['functionResults'])) {
                $metricsCalled   = $recovered['metrics'];
                $functionResults = $recovered['functionResults'];
                $parts           = $recovered['parts'];
            }
        }

        if (!empty($functionResults)) {
            $contents[] = [
                'role'  => 'model',
                'parts' => $parts
            ];
            $contents[] = [
                'role'  => 'user',
                'parts' => $functionResults
            ];

            $secondPayload = [
                'systemInstruction' => $systemInstruction,
                'contents'          => $contents
            ];

            $reply = $this->callGeminiStream($secondPayload, $onChunk);
            if (empty(trim($reply)) || preg_match('/cannot directly compare|values are not provided|not provided in the query|cannot offer data-driven|without the specific/i', $reply)) {
                $reply = $this->buildExecutiveComparisonSummary($metricsCalled);
                if ($onChunk) {
                    $onChunk($reply);
                }
            }
        } else {
            $finalText = '';
            foreach ($parts as $p) {
                if (isset($p['text'])) {
                    $finalText .= $p['text'];
                }
            }
            $reply = trim($finalText) ?: 'I am ready to analyze your business metrics.';
            if ($onChunk) {
                $onChunk($reply);
            }
        }

        $chartConfig = $this->generateChartConfigIfApplicable($metricsCalled);

        return [
            'status'  => true,
            'reply'   => $reply,
            'metrics' => $metricsCalled,
            'chart'   => $chartConfig
        ];
    }

    /**
     * Stream response tokens from Gemini API via Server-Sent Events.
     */
    private function callGeminiStream(array $payload, ?callable $onChunk = null): string
    {
        if (empty($this->apiKey)) {
            $res = $this->callGemini($payload);
            $text = $res['candidates'][0]['content']['parts'][0]['text'] ?? '';
            if ($onChunk && !empty($text)) {
                $onChunk($text);
            }
            return $text;
        }

        $model = getenv('GEMINI_MODEL') ?: env('GEMINI_MODEL') ?: 'gemini-2.5-flash-lite';
        $url   = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:streamGenerateContent?key=" . $this->apiKey . "&alt=sse";

        $fullText = '';
        $ch       = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 40);

        curl_setopt($ch, CURLOPT_WRITEFUNCTION, function ($ch, $chunk) use (&$fullText, $onChunk) {
            $lines = explode("\n", $chunk);
            foreach ($lines as $line) {
                $line = trim($line);
                if (str_starts_with($line, 'data: ')) {
                    $jsonStr = substr($line, 6);
                    if ($jsonStr === '[DONE]') continue;
                    $decoded = json_decode($jsonStr, true);
                    $parts   = $decoded['candidates'][0]['content']['parts'] ?? [];
                    foreach ($parts as $p) {
                        $t = $p['text'] ?? '';
                        if ($t !== '') {
                            $fullText .= $t;
                            if ($onChunk) {
                                $onChunk($t);
                            }
                        }
                    }
                }
            }
            return strlen($chunk);
        });

        curl_exec($ch);
        curl_close($ch);

        if (empty($fullText)) {
            $res      = $this->callGemini($payload);
            $fullText = $res['candidates'][0]['content']['parts'][0]['text'] ?? '';
            if ($onChunk && !empty($fullText)) {
                $onChunk($fullText);
            }
        }

        return $fullText;
    }

    /**
     * Send cURL payload to Gemini API with automatic model fallback chain.
     */
    private function callGemini(array $payload): array
    {
        $primaryModel = getenv('GEMINI_MODEL') ?: env('GEMINI_MODEL') ?: 'gemini-2.5-flash-lite';
        $candidateModels = array_unique([
            $primaryModel,
            'gemini-2.0-flash',
            'gemini-1.5-flash-latest',
            'gemini-1.5-flash',
            'gemini-1.5-pro'
        ]);

        $lastResult = ['error' => ['message' => 'No candidate models could be called.']];

        foreach ($candidateModels as $model) {
            if (empty($this->apiKey)) break;

            $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key=" . $this->apiKey;
            $ch  = curl_init($url);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
            curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_TIMEOUT, 25);

            $response = curl_exec($ch);
            $err      = curl_error($ch);
            curl_close($ch);

            if ($err) {
                $lastResult = ['error' => ['message' => "cURL Error ({$model}): " . $err]];
                continue;
            }

            $decoded = json_decode($response, true);

            if (is_array($decoded)) {
                $errMsg = $decoded['error']['message'] ?? '';
                if (!empty($errMsg) && (str_contains($errMsg, 'no longer available') || str_contains($errMsg, 'not found') || str_contains($errMsg, '404') || str_contains($errMsg, 'API key'))) {
                    log_message('warning', "Gemini model [{$model}] failed: {$errMsg}. Trying next fallback candidate...");
                    $lastResult = $decoded;
                    continue;
                }
                if (isset($decoded['candidates'][0])) {
                    return $decoded;
                }
                $lastResult = $decoded;
            }
        }

        // If all Gemini models failed, attempt OpenRouter fallback
        log_message('warning', 'Gemini API failed/unavailable. Executing OpenRouter API fallback...');
        $openRouterRes = $this->callOpenRouter($payload);
        if (isset($openRouterRes['candidates'][0])) {
            return $openRouterRes;
        }

        // If OpenRouter also failed, attempt direct NVIDIA API fallback
        log_message('warning', 'OpenRouter API failed/unavailable. Executing NVIDIA API fallback...');
        $nvidiaRes = $this->callNvidia($payload);
        if (isset($nvidiaRes['candidates'][0])) {
            return $nvidiaRes;
        }

        // If GitHub Models also failed, attempt Groq API fallback
        log_message('warning', 'GitHub Models API failed/unavailable. Executing Groq API fallback...');
        $groqRes = $this->callGroq($payload);
        if (isset($groqRes['candidates'][0])) {
            return $groqRes;
        }

        // If Groq also failed, attempt Hugging Face API fallback
        log_message('warning', 'Groq API failed/unavailable. Executing Hugging Face API fallback...');
        $hfRes = $this->callHuggingFace($payload);
        if (isset($hfRes['candidates'][0])) {
            return $hfRes;
        }

        return !empty($hfRes['error']) ? $hfRes : (!empty($groqRes['error']) ? $groqRes : (!empty($githubRes['error']) ? $githubRes : (!empty($nvidiaRes['error']) ? $nvidiaRes : (!empty($openRouterRes['error']) ? $openRouterRes : $lastResult))));
    }

    /**
     * Fallback execution via OpenRouter API when Google Gemini models fail or rate limit.
     */
    private function callOpenRouter(array $geminiPayload): array
    {
        $openRouterKey = getenv('OPENROUTER_API_KEY') ?: env('OPENROUTER_API_KEY') ?: getenv('OPEN_ROUTER_KEY') ?: env('OPEN_ROUTER_KEY') ?: '';
        if (empty($openRouterKey)) {
            return ['error' => ['message' => 'Gemini models failed, and OPENROUTER_API_KEY is not configured in .env']];
        }

        $primaryModel = getenv('OPENROUTER_MODEL') ?: env('OPENROUTER_MODEL') ?: 'nvidia/llama-3.1-nemotron-70b-instruct';
        $openRouterModels = array_unique([
            $primaryModel,
            'thudm/glm-4-9b-chat',
            'nvidia/nemotron-4-340b-instruct',
            'google/gemini-2.0-flash-001',
            'meta-llama/llama-3.3-70b-instruct'
        ]);

        $messages    = $this->formatOpenAiMessages($geminiPayload);
        $openAiTools = $this->formatOpenAiTools($geminiPayload);

        $lastOpenRouterError = ['error' => ['message' => 'All OpenRouter models failed.']];

        foreach ($openRouterModels as $model) {
            $body = [
                'model'    => $model,
                'messages' => $messages
            ];

            if (!empty($openAiTools)) {
                $body['tools'] = $openAiTools;
            }

            $url = "https://openrouter.ai/api/v1/chat/completions";
            $ch  = curl_init($url);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $openRouterKey,
                'Content-Type: application/json',
                'HTTP-Referer: https://petsfolio.in',
                'X-Title: Petsfolio BI Analyst'
            ]);
            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
            curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_TIMEOUT, 25);

            $response = curl_exec($ch);
            $err      = curl_error($ch);
            curl_close($ch);

            if ($err) {
                $lastOpenRouterError = ['error' => ['message' => "OpenRouter cURL Error ({$model}): " . $err]];
                continue;
            }

            $decoded = json_decode($response, true);
            if (!is_array($decoded) || isset($decoded['error'])) {
                $msg = $decoded['error']['message'] ?? 'Invalid response from OpenRouter';
                log_message('warning', "OpenRouter model [{$model}] failed: {$msg}. Trying next candidate...");
                $lastOpenRouterError = ['error' => ['message' => "OpenRouter [{$model}] Error: {$msg}"]];
                continue;
            }

            $choice = $decoded['choices'][0]['message'] ?? [];
            $parts  = [];

            if (!empty($choice['tool_calls'])) {
                foreach ($choice['tool_calls'] as $tc) {
                    $fnName  = $tc['function']['name'] ?? '';
                    $fnArgs  = json_decode($tc['function']['arguments'] ?? '{}', true) ?: [];
                    $parts[] = [
                        'functionCall' => [
                            'name' => $fnName,
                            'args' => $fnArgs
                        ]
                    ];
                }
            } elseif (!empty($choice['content'])) {
                $parts[] = ['text' => $choice['content']];
            }

            if (!empty($parts)) {
                return [
                    'candidates' => [
                        [
                            'content' => [
                                'parts' => $parts
                            ]
                        ]
                    ]
                ];
            }
        }

        return $lastOpenRouterError;
    }

    /**
     * Fallback execution via NVIDIA API when Gemini and OpenRouter fail.
     */
    private function callNvidia(array $geminiPayload): array
    {
        $nvidiaKey = getenv('NVIDIA_API_KEY') ?: env('NVIDIA_API_KEY') ?: '';
        if (empty($nvidiaKey)) {
            return ['error' => ['message' => 'NVIDIA_API_KEY is not configured in .env']];
        }

        $model = getenv('NVIDIA_MODEL') ?: env('NVIDIA_MODEL') ?: 'z-ai/glm-5.2';

        $messages    = $this->formatOpenAiMessages($geminiPayload);
        $openAiTools = $this->formatOpenAiTools($geminiPayload);

        $body = [
            'model'    => $model,
            'messages' => $messages
        ];

        if (!empty($openAiTools)) {
            $body['tools'] = $openAiTools;
        }

        $url = "https://integrate.api.nvidia.com/v1/chat/completions";
        $ch  = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $nvidiaKey,
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 25);

        $response = curl_exec($ch);
        $err      = curl_error($ch);
        curl_close($ch);

        if ($err) {
            return ['error' => ['message' => 'NVIDIA API cURL Error: ' . $err]];
        }

        $decoded = json_decode($response, true);
        if (!is_array($decoded) || isset($decoded['error'])) {
            $msg = $decoded['error']['message'] ?? 'Invalid response from NVIDIA API';
            return ['error' => ['message' => 'NVIDIA API Error: ' . $msg]];
        }

        $choice = $decoded['choices'][0]['message'] ?? [];
        $parts  = [];

        if (!empty($choice['tool_calls'])) {
            foreach ($choice['tool_calls'] as $tc) {
                $fnName  = $tc['function']['name'] ?? '';
                $fnArgs  = json_decode($tc['function']['arguments'] ?? '{}', true) ?: [];
                $parts[] = [
                    'functionCall' => [
                        'name' => $fnName,
                        'args' => $fnArgs
                    ]
                ];
            }
        } elseif (!empty($choice['content'])) {
            $parts[] = ['text' => $choice['content']];
        }

        return [
            'candidates' => [
                [
                    'content' => [
                        'parts' => $parts
                    ]
                ]
            ]
        ];
    }

    /**
     * Fallback execution via GitHub Models API (Azure AI Inference).
     */
    private function callGithubModels(array $geminiPayload): array
    {
        $githubKey = getenv('GITHUB_MODELS_KEY') ?: env('GITHUB_MODELS_KEY') ?: getenv('GITHUB_TOKEN') ?: env('GITHUB_TOKEN') ?: '';
        if (empty($githubKey)) {
            return ['error' => ['message' => 'NVIDIA API failed, and GITHUB_MODELS_KEY / GITHUB_TOKEN is not configured in .env']];
        }

        $primaryModel = getenv('GITHUB_MODEL') ?: env('GITHUB_MODEL') ?: 'gpt-4o-mini';
        $githubModels = array_unique([
            $primaryModel,
            'gpt-4o',
            'gpt-4o-mini',
            'Phi-3.5-mini-instruct'
        ]);

        $messages    = $this->formatOpenAiMessages($geminiPayload);
        $openAiTools = $this->formatOpenAiTools($geminiPayload);

        $lastError = ['error' => ['message' => 'All GitHub Models failed.']];

        foreach ($githubModels as $model) {
            $body = [
                'model'    => $model,
                'messages' => $messages
            ];

            if (!empty($openAiTools)) {
                $body['tools'] = $openAiTools;
            }

            $url = "https://models.inference.ai.azure.com/chat/completions";
            $ch  = curl_init($url);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $githubKey,
                'Content-Type: application/json',
                'User-Agent: Petsfolio-BI-Analyst'
            ]);
            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
            curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_TIMEOUT, 25);

            $response = curl_exec($ch);
            $err      = curl_error($ch);
            curl_close($ch);

            if ($err) {
                $lastError = ['error' => ['message' => "GitHub Models cURL Error ({$model}): " . $err]];
                continue;
            }

            $decoded = json_decode($response, true);
            if (!is_array($decoded) || isset($decoded['error'])) {
                $msg = $decoded['error']['message'] ?? 'Invalid response from GitHub Models API';
                log_message('warning', "GitHub Model [{$model}] failed: {$msg}. Trying next candidate...");
                $lastError = ['error' => ['message' => "GitHub Model [{$model}] Error: {$msg}"]];
                continue;
            }

            $choice = $decoded['choices'][0]['message'] ?? [];
            $parts  = [];

            if (!empty($choice['tool_calls'])) {
                foreach ($choice['tool_calls'] as $tc) {
                    $fnName  = $tc['function']['name'] ?? '';
                    $fnArgs  = json_decode($tc['function']['arguments'] ?? '{}', true) ?: [];
                    $parts[] = [
                        'functionCall' => [
                            'name' => $fnName,
                            'args' => $fnArgs
                        ]
                    ];
                }
            } elseif (!empty($choice['content'])) {
                $parts[] = ['text' => $choice['content']];
            }

            if (!empty($parts)) {
                return [
                    'candidates' => [
                        [
                            'content' => [
                                'parts' => $parts
                            ]
                        ]
                    ]
                ];
            }
        }

        return $lastError;
    }

    /**
     * Fallback execution via Groq API (High Speed Free Tier).
     */
    private function callGroq(array $geminiPayload): array
    {
        $groqKey = getenv('GROQ_API_KEY') ?: env('GROQ_API_KEY') ?: '';
        if (empty($groqKey)) {
            return ['error' => ['message' => 'GROQ_API_KEY is not configured in .env']];
        }

        $primaryModel = getenv('GROQ_MODEL') ?: env('GROQ_MODEL') ?: 'llama-3.3-70b-versatile';
        $groqModels = array_unique([
            $primaryModel,
            'llama-3.3-70b-versatile',
            'llama-3.1-8b-instant',
            'mixtral-8x7b-32768'
        ]);

        $messages    = $this->formatOpenAiMessages($geminiPayload);
        $openAiTools = $this->formatOpenAiTools($geminiPayload);
        $lastError   = ['error' => ['message' => 'All Groq models failed.']];

        foreach ($groqModels as $model) {
            $body = [
                'model'    => $model,
                'messages' => $messages
            ];

            if (!empty($openAiTools)) {
                $body['tools'] = $openAiTools;
            }

            $url = "https://api.groq.com/openai/v1/chat/completions";
            $ch  = curl_init($url);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $groqKey,
                'Content-Type: application/json'
            ]);
            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
            curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_TIMEOUT, 25);

            $response = curl_exec($ch);
            $err      = curl_error($ch);
            curl_close($ch);

            if ($err) {
                $lastError = ['error' => ['message' => "Groq cURL Error ({$model}): " . $err]];
                continue;
            }

            $decoded = json_decode($response, true);
            if (!is_array($decoded) || isset($decoded['error'])) {
                $msg = $decoded['error']['message'] ?? 'Invalid response from Groq API';
                log_message('warning', "Groq Model [{$model}] failed: {$msg}. Trying next candidate...");
                $lastError = ['error' => ['message' => "Groq Model [{$model}] Error: {$msg}"]];
                continue;
            }

            $choice = $decoded['choices'][0]['message'] ?? [];
            $parts  = [];

            if (!empty($choice['tool_calls'])) {
                foreach ($choice['tool_calls'] as $tc) {
                    $fnName  = $tc['function']['name'] ?? '';
                    $fnArgs  = json_decode($tc['function']['arguments'] ?? '{}', true) ?: [];
                    $parts[] = [
                        'functionCall' => [
                            'name' => $fnName,
                            'args' => $fnArgs
                        ]
                    ];
                }
            } elseif (!empty($choice['content'])) {
                $parts[] = ['text' => $choice['content']];
            }

            if (!empty($parts)) {
                return [
                    'candidates' => [
                        [
                            'content' => [
                                'parts' => $parts
                            ]
                        ]
                    ]
                ];
            }
        }

        return $lastError;
    }

    /**
     * Converts Gemini contents payload to valid OpenAI / Azure / HF Chat Completion messages.
     * Guarantees every 'tool' message is preceded by an 'assistant' message with matching 'tool_calls'.
     */
    private function formatOpenAiMessages(array $geminiPayload): array
    {
        $messages = [];

        // 1. System Instruction
        if (!empty($geminiPayload['systemInstruction']['parts'][0]['text'])) {
            $messages[] = [
                'role'    => 'system',
                'content' => $geminiPayload['systemInstruction']['parts'][0]['text']
            ];
        }

        // 2. Chat history & tool turns
        if (!empty($geminiPayload['contents'])) {
            foreach ($geminiPayload['contents'] as $contentItem) {
                $role    = strtolower($contentItem['role'] ?? 'user');
                $roleStr = ($role === 'user') ? 'user' : 'assistant';
                $parts   = $contentItem['parts'] ?? [];

                foreach ($parts as $part) {
                    if (isset($part['text'])) {
                        $messages[] = [
                            'role'    => $roleStr,
                            'content' => $part['text']
                        ];
                    } elseif (isset($part['functionCall'])) {
                        $fc         = $part['functionCall'];
                        $funcName   = $fc['name'] ?? 'metric_function';
                        $funcArgs   = $fc['args'] ?? [];
                        $toolCallId = 'call_' . md5($funcName . json_encode($funcArgs));

                        $messages[] = [
                            'role'       => 'assistant',
                            'content'    => null,
                            'tool_calls' => [
                                [
                                    'id'       => $toolCallId,
                                    'type'     => 'function',
                                    'function' => [
                                        'name'      => $funcName,
                                        'arguments' => json_encode($funcArgs)
                                    ]
                                ]
                            ]
                        ];
                    } elseif (isset($part['functionResponse'])) {
                        $funcResp   = $part['functionResponse'];
                        $funcName   = $funcResp['name'] ?? 'metric_function';
                        $funcContent= $funcResp['response']['content'] ?? [];

                        // Ensure a preceding assistant tool_calls message exists
                        $lastMsg = end($messages);
                        if (!$lastMsg || $lastMsg['role'] !== 'assistant' || empty($lastMsg['tool_calls'])) {
                            $toolCallId = 'call_' . md5($funcName . json_encode($funcContent));
                            $messages[] = [
                                'role'       => 'assistant',
                                'content'    => null,
                                'tool_calls' => [
                                    [
                                        'id'       => $toolCallId,
                                        'type'     => 'function',
                                        'function' => [
                                            'name'      => $funcName,
                                            'arguments' => '{}'
                                        ]
                                    ]
                                ]
                            ];
                        } else {
                            $toolCallId = $lastMsg['tool_calls'][0]['id'] ?? ('call_' . md5($funcName));
                        }

                        $messages[] = [
                            'role'         => 'tool',
                            'tool_call_id' => $toolCallId,
                            'content'      => json_encode($funcContent)
                        ];
                    }
                }
            }
        }

        return $messages;
    }

    /**
     * Fallback execution via Hugging Face Inference API.
     */
    private function callHuggingFace(array $geminiPayload): array
    {
        $hfToken = getenv('HUGGINGFACE_TOKEN') ?: env('HUGGINGFACE_TOKEN') ?: '';
        $primaryModel = getenv('HUGGINGFACE_MODEL') ?: env('HUGGINGFACE_MODEL') ?: 'Qwen/Qwen2.5-Coder-32B-Instruct';

        $hfModels = array_unique([
            $primaryModel,
            'Qwen/Qwen2.5-72B-Instruct',
            'meta-llama/Llama-3.1-70B-Instruct',
            'deepseek-ai/DeepSeek-R1-Distill-Qwen-32B'
        ]);

        $messages    = $this->formatOpenAiMessages($geminiPayload);
        $openAiTools = $this->formatOpenAiTools($geminiPayload);
        $lastError   = ['error' => ['message' => 'All Hugging Face models failed.']];

        foreach ($hfModels as $model) {
            $body = [
                'model'    => $model,
                'messages' => $messages
            ];

            if (!empty($openAiTools)) {
                $body['tools'] = $openAiTools;
            }

            $jsonBody = json_encode($body);
            $url      = "https://router.huggingface.co/hf-inference/v1/chat/completions";
            $headers  = [
                'Content-Type: application/json',
                'User-Agent: Petsfolio-BI-Analyst'
            ];
            if (!empty($hfToken)) {
                $headers[] = 'Authorization: Bearer ' . $hfToken;
            }

            $response = false;
            $err      = null;

            if (function_exists('curl_init')) {
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonBody);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
                curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
                curl_setopt($ch, CURLOPT_TIMEOUT, 30);

                $response = curl_exec($ch);
                $err      = curl_error($ch);
                curl_close($ch);
            }

            // Fallback to PHP streams if cURL fails or throws getaddrinfo thread error
            if ($response === false || !empty($err)) {
                $opts = [
                    'http' => [
                        'method'  => 'POST',
                        'header'  => implode("\r\n", $headers),
                        'content' => $jsonBody,
                        'timeout' => 30
                    ]
                ];
                $context  = stream_context_create($opts);
                $streamRes = @file_get_contents($url, false, $context);
                if ($streamRes !== false) {
                    $response = $streamRes;
                    $err      = null;
                }
            }

            if ($response === false && !empty($err)) {
                $lastError = ['error' => ['message' => "Hugging Face Error ({$model}): " . $err]];
                continue;
            }

            $decoded = json_decode($response, true);
            if (!is_array($decoded) || isset($decoded['error'])) {
                $msg = is_string($decoded['error'] ?? null) ? $decoded['error'] : ($decoded['error']['message'] ?? 'Invalid response from Hugging Face API');
                log_message('warning', "Hugging Face Model [{$model}] failed: {$msg}. Trying next candidate...");
                $lastError = ['error' => ['message' => "Hugging Face [{$model}] Error: {$msg}"]];
                continue;
            }

            $choice = $decoded['choices'][0]['message'] ?? [];
            $parts  = [];

            if (!empty($choice['tool_calls'])) {
                foreach ($choice['tool_calls'] as $tc) {
                    $fnName  = $tc['function']['name'] ?? '';
                    $fnArgs  = json_decode($tc['function']['arguments'] ?? '{}', true) ?: [];
                    $parts[] = [
                        'functionCall' => [
                            'name' => $fnName,
                            'args' => $fnArgs
                        ]
                    ];
                }
            } elseif (!empty($choice['content'])) {
                $parts[] = ['text' => $choice['content']];
            }

            if (!empty($parts)) {
                return [
                    'candidates' => [
                        [
                            'content' => [
                                'parts' => $parts
                            ]
                        ]
                    ]
                ];
            }
        }

        return $lastError;
    }

    /**
     * Converts Gemini tool declarations to OpenAI-compliant tool schema (with lowercase types).
     */
    private function formatOpenAiTools(array $geminiPayload): array
    {
        $openAiTools = [];
        if (!empty($geminiPayload['tools'][0]['functionDeclarations'])) {
            foreach ($geminiPayload['tools'][0]['functionDeclarations'] as $fd) {
                $params = json_decode(json_encode($fd['parameters'] ?? []), true);
                $sanitizedParams = $this->sanitizeOpenAiSchema($params);

                $openAiTools[] = [
                    'type'     => 'function',
                    'function' => [
                        'name'        => $fd['name'],
                        'description' => $fd['description'] ?? '',
                        'parameters'  => !empty($sanitizedParams) ? $sanitizedParams : new \stdClass()
                    ]
                ];
            }
        }
        return $openAiTools;
    }

    /**
     * Recursively lowers type values to conform to standard OpenAI / JSON Schema specs.
     */
    private function sanitizeOpenAiSchema(array $schema): array
    {
        foreach ($schema as $key => &$value) {
            if ($key === 'type' && is_string($value)) {
                $value = strtolower($value);
            } elseif (is_array($value)) {
                $value = $this->sanitizeOpenAiSchema($value);
            }
        }
        return $schema;
    }

    /**
     * Fallback text summary generation if LLM text is empty.
     */
    private function buildFallbackTextSummary(array $metricsCalled): string
    {
        $summary = "### Business Analytics Summary\n\n";

        foreach ($metricsCalled as $item) {
            $name = ucwords(str_replace('_', ' ', $item['name']));
            $data = json_encode($item['data'], JSON_PRETTY_PRINT);
            $summary .= "#### {$name}\n```json\n{$data}\n```\n\n";
        }

        return $summary;
    }

    /**
     * Auto-generate Chart.js specification if metric results contain plottable series.
     */
    private function generateChartConfigIfApplicable(array $metricsCalled): ?array
    {
        if (empty($metricsCalled)) {
            return null;
        }

        foreach ($metricsCalled as $item) {
            $name = $item['name'];
            $data = $item['data'];

            if ($name === 'lead_source_performance' && !empty($data['sources'])) {
                $labels = [];
                $values = [];
                foreach ($data['sources'] as $src) {
                    $labels[] = $src['source'];
                    $values[] = $src['raw_rate'] ?? 0;
                }
                return [
                    'type' => 'bar',
                    'title' => 'Conversion Rate by Lead Source (%)',
                    'data' => [
                        'labels' => $labels,
                        'datasets' => [[
                            'label' => 'Conversion Rate %',
                            'data'  => $values,
                            'backgroundColor' => ['#4f46e5', '#10b981', '#f59e0b', '#ef4444']
                        ]]
                    ]
                ];
            }

            if ($name === 'daily_booking_trend' && !empty($data['daily_trends'])) {
                $labels = [];
                $values = [];
                foreach ($data['daily_trends'] as $row) {
                    $labels[] = date('M d', strtotime($row['booking_date']));
                    $values[] = (int) $row['bookings'];
                }
                return [
                    'type' => 'line',
                    'title' => 'Daily Booking Trend',
                    'data' => [
                        'labels' => $labels,
                        'datasets' => [[
                            'label' => 'Bookings',
                            'data'  => $values,
                            'borderColor' => '#3b82f6',
                            'fill'  => false
                        ]]
                    ]
                ];
            }
        }

        return null;
    }

    /**
     * Infer intent and auto-recover metric function calls when LLM outputs text instead of functionCall.
     */
    private function autoRecoverMetrics(string $userMessage, ?int $messageId): array
    {
        $metricsCalled   = [];
        $functionResults = [];
        $parts           = [];

        $msgLower = strtolower($userMessage);
        $isComparison = (bool) preg_match('/vs|compare|last month|this month|previous|difference|change/i', $userMessage);

        if (str_contains($msgLower, 'conversion')) {
            if ($isComparison) {
                $m1 = $this->validator->executeMetric('conversion_rate', ['date_range' => 'this_month'], $messageId);
                $m2 = $this->validator->executeMetric('conversion_rate', ['date_range' => 'last_month'], $messageId);

                $metricsCalled[] = ['name' => 'conversion_rate', 'args' => ['date_range' => 'this_month'], 'data' => $m1['data'] ?? []];
                $metricsCalled[] = ['name' => 'conversion_rate', 'args' => ['date_range' => 'last_month'], 'data' => $m2['data'] ?? []];

                $functionResults[] = ['functionResponse' => ['name' => 'conversion_rate', 'response' => ['name' => 'conversion_rate', 'content' => $m1['data'] ?? []]]];
                $functionResults[] = ['functionResponse' => ['name' => 'conversion_rate', 'response' => ['name' => 'conversion_rate', 'content' => $m2['data'] ?? []]]];

                $parts = [
                    ['functionCall' => ['name' => 'conversion_rate', 'args' => ['date_range' => 'this_month']]],
                    ['functionCall' => ['name' => 'conversion_rate', 'args' => ['date_range' => 'last_month']]]
                ];
            } else {
                $m1 = $this->validator->executeMetric('conversion_rate', ['date_range' => 'this_month'], $messageId);
                $metricsCalled[] = ['name' => 'conversion_rate', 'args' => ['date_range' => 'this_month'], 'data' => $m1['data'] ?? []];
                $functionResults[] = ['functionResponse' => ['name' => 'conversion_rate', 'response' => ['name' => 'conversion_rate', 'content' => $m1['data'] ?? []]]];
                $parts = [
                    ['functionCall' => ['name' => 'conversion_rate', 'args' => ['date_range' => 'this_month']]]
                ];
            }
        } elseif (str_contains($msgLower, 'revenue') || str_contains($msgLower, 'earning') || str_contains($msgLower, 'profit')) {
            if ($isComparison) {
                $m1 = $this->validator->executeMetric('revenue_overview', ['date_range' => 'this_month'], $messageId);
                $m2 = $this->validator->executeMetric('revenue_overview', ['date_range' => 'last_month'], $messageId);

                $metricsCalled[] = ['name' => 'revenue_overview', 'args' => ['date_range' => 'this_month'], 'data' => $m1['data'] ?? []];
                $metricsCalled[] = ['name' => 'revenue_overview', 'args' => ['date_range' => 'last_month'], 'data' => $m2['data'] ?? []];

                $functionResults[] = ['functionResponse' => ['name' => 'revenue_overview', 'response' => ['name' => 'revenue_overview', 'content' => $m1['data'] ?? []]]];
                $functionResults[] = ['functionResponse' => ['name' => 'revenue_overview', 'response' => ['name' => 'revenue_overview', 'content' => $m2['data'] ?? []]]];

                $parts = [
                    ['functionCall' => ['name' => 'revenue_overview', 'args' => ['date_range' => 'this_month']]],
                    ['functionCall' => ['name' => 'revenue_overview', 'args' => ['date_range' => 'last_month']]]
                ];
            } else {
                $m1 = $this->validator->executeMetric('revenue_overview', ['date_range' => '30_days'], $messageId);
                $metricsCalled[] = ['name' => 'revenue_overview', 'args' => ['date_range' => '30_days'], 'data' => $m1['data'] ?? []];
                $functionResults[] = ['functionResponse' => ['name' => 'revenue_overview', 'response' => ['name' => 'revenue_overview', 'content' => $m1['data'] ?? []]]];
                $parts = [
                    ['functionCall' => ['name' => 'revenue_overview', 'args' => ['date_range' => '30_days']]]
                ];
            }
        } elseif (str_contains($msgLower, 'booking') || str_contains($msgLower, 'order')) {
            if ($isComparison) {
                $m1 = $this->validator->executeMetric('bookings_summary', ['date_range' => 'this_month'], $messageId);
                $m2 = $this->validator->executeMetric('bookings_summary', ['date_range' => 'last_month'], $messageId);

                $metricsCalled[] = ['name' => 'bookings_summary', 'args' => ['date_range' => 'this_month'], 'data' => $m1['data'] ?? []];
                $metricsCalled[] = ['name' => 'bookings_summary', 'args' => ['date_range' => 'last_month'], 'data' => $m2['data'] ?? []];

                $functionResults[] = ['functionResponse' => ['name' => 'bookings_summary', 'response' => ['name' => 'bookings_summary', 'content' => $m1['data'] ?? []]]];
                $functionResults[] = ['functionResponse' => ['name' => 'bookings_summary', 'response' => ['name' => 'bookings_summary', 'content' => $m2['data'] ?? []]]];

                $parts = [
                    ['functionCall' => ['name' => 'bookings_summary', 'args' => ['date_range' => 'this_month']]],
                    ['functionCall' => ['name' => 'bookings_summary', 'args' => ['date_range' => 'last_month']]]
                ];
            } else {
                $m1 = $this->validator->executeMetric('bookings_summary', ['date_range' => '30_days'], $messageId);
                $metricsCalled[] = ['name' => 'bookings_summary', 'args' => ['date_range' => '30_days'], 'data' => $m1['data'] ?? []];
                $functionResults[] = ['functionResponse' => ['name' => 'bookings_summary', 'response' => ['name' => 'bookings_summary', 'content' => $m1['data'] ?? []]]];
                $parts = [
                    ['functionCall' => ['name' => 'bookings_summary', 'args' => ['date_range' => '30_days']]]
                ];
            }
        } elseif (str_contains($msgLower, 'cancel')) {
            $m1 = $this->validator->executeMetric('cancellation_rate', ['date_range' => '30_days'], $messageId);
            $metricsCalled[] = ['name' => 'cancellation_rate', 'args' => ['date_range' => '30_days'], 'data' => $m1['data'] ?? []];
            $functionResults[] = ['functionResponse' => ['name' => 'cancellation_rate', 'response' => ['name' => 'cancellation_rate', 'content' => $m1['data'] ?? []]]];
            $parts = [
                ['functionCall' => ['name' => 'cancellation_rate', 'args' => ['date_range' => '30_days']]]
            ];
        } elseif (str_contains($msgLower, 'source')) {
            $m1 = $this->validator->executeMetric('lead_source_performance', ['date_range' => '30_days'], $messageId);
            $metricsCalled[] = ['name' => 'lead_source_performance', 'args' => ['date_range' => '30_days'], 'data' => $m1['data'] ?? []];
            $functionResults[] = ['functionResponse' => ['name' => 'lead_source_performance', 'response' => ['name' => 'lead_source_performance', 'content' => $m1['data'] ?? []]]];
            $parts = [
                ['functionCall' => ['name' => 'lead_source_performance', 'args' => ['date_range' => '30_days']]]
            ];
        } elseif (str_contains($msgLower, 'funnel') || str_contains($msgLower, 'dropoff')) {
            $m1 = $this->validator->executeMetric('funnel_dropoff', ['date_range' => '30_days'], $messageId);
            $metricsCalled[] = ['name' => 'funnel_dropoff', 'args' => ['date_range' => '30_days'], 'data' => $m1['data'] ?? []];
            $functionResults[] = ['functionResponse' => ['name' => 'funnel_dropoff', 'response' => ['name' => 'funnel_dropoff', 'content' => $m1['data'] ?? []]]];
            $parts = [
                ['functionCall' => ['name' => 'funnel_dropoff', 'args' => ['date_range' => '30_days']]]
            ];
        } elseif (preg_match('/\b[6-9]\d{9}\b/', $userMessage, $phoneMatches)) {
            $phone = $phoneMatches[0];
            $m1 = $this->validator->executeMetric('provider_performance_score', ['provider_id' => $phone], $messageId);
            $m2 = $this->validator->executeMetric('provider_stats', ['provider_search' => $phone], $messageId);

            $metricsCalled[] = ['name' => 'provider_performance_score', 'args' => ['provider_id' => $phone], 'data' => $m1['data'] ?? []];
            $metricsCalled[] = ['name' => 'provider_stats', 'args' => ['provider_search' => $phone], 'data' => $m2['data'] ?? []];

            $functionResults[] = ['functionResponse' => ['name' => 'provider_performance_score', 'response' => ['name' => 'provider_performance_score', 'content' => $m1['data'] ?? []]]];
            $functionResults[] = ['functionResponse' => ['name' => 'provider_stats', 'response' => ['name' => 'provider_stats', 'content' => $m2['data'] ?? []]]];

            $parts = [
                ['functionCall' => ['name' => 'provider_performance_score', 'args' => ['provider_id' => $phone]]],
                ['functionCall' => ['name' => 'provider_stats', 'args' => ['provider_search' => $phone]]]
            ];
        }

        return [
            'metrics'         => $metricsCalled,
            'functionResults' => $functionResults,
            'parts'           => $parts
        ];
    }

    /**
     * Synthesize clean executive comparison summary directly from metrics data.
     */
    private function buildExecutiveComparisonSummary(array $metricsCalled): string
    {
        $out = "### 📊 Business Intelligence Performance Analysis\n\n";

        $byMetric = [];
        foreach ($metricsCalled as $mc) {
            $name = $mc['name'];
            $byMetric[$name][] = $mc;
        }

        foreach ($byMetric as $name => $items) {
            $title = ucwords(str_replace('_', ' ', $name));
            $out .= "#### {$title} Comparison\n\n";

            if ($name === 'lead_conversion_rate' || $name === 'conversion_rate') {
                $out .= "| Period | Total CRM Leads | Converted Leads | Conversion Rate |\n";
                $out .= "| :--- | :---: | :---: | :---: |\n";
                foreach ($items as $it) {
                    $dr = ucwords(str_replace('_', ' ', $it['args']['date_range'] ?? '30 Days'));
                    $d  = $it['data'] ?? [];
                    $tot = $d['total_leads'] ?? 0;
                    $conv = $d['converted_leads'] ?? 0;
                    $rate = $d['conversion_rate'] ?? '0%';
                    $out .= "| **{$dr}** | {$tot} | {$conv} | **{$rate}** |\n";
                }
                $out .= "\n";
            } elseif ($name === 'revenue_overview') {
                $out .= "| Period | Gross Revenue | Platform Profit | Provider Payouts |\n";
                $out .= "| :--- | :---: | :---: | :---: |\n";
                foreach ($items as $it) {
                    $dr = ucwords(str_replace('_', ' ', $it['args']['date_range'] ?? '30 Days'));
                    $d  = $it['data'] ?? [];
                    $gross = "₹" . number_format($d['gross_revenue_inr'] ?? 0, 2);
                    $comm  = "₹" . number_format($d['platform_commission'] ?? 0, 2);
                    $pay   = "₹" . number_format($d['provider_payouts_inr'] ?? 0, 2);
                    $out .= "| **{$dr}** | **{$gross}** | {$comm} | {$pay} |\n";
                }
                $out .= "\n";
            } elseif ($name === 'bookings_summary') {
                $out .= "| Period | Total Bookings | Total Revenue |\n";
                $out .= "| :--- | :---: | :---: |\n";
                foreach ($items as $it) {
                    $dr = ucwords(str_replace('_', ' ', $it['args']['date_range'] ?? '30 Days'));
                    $d  = $it['data'] ?? [];
                    $tot = $d['total_bookings'] ?? 0;
                    $rev = "₹" . number_format($d['total_revenue_inr'] ?? 0, 2);
                    $out .= "| **{$dr}** | **{$tot}** | **{$rev}** |\n";
                }
                $out .= "\n";
            } else {
                foreach ($items as $it) {
                    $dr = ucwords(str_replace('_', ' ', $it['args']['date_range'] ?? '30 Days'));
                    $dataJson = json_encode($it['data'], JSON_PRETTY_PRINT);
                    $out .= "##### Period: {$dr}\n```json\n{$dataJson}\n```\n\n";
                }
            }
        }

        $out .= "### 💡 Key Insights & Recommendations\n";
        $out .= "• All metrics reflect live business data directly queried from Petsfolio databases.\n";
        $out .= "• Use these period-over-period figures to evaluate campaign efficiency and operational performance.\n";

        return $out;
    }
}
