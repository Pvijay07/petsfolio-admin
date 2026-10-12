<?php

namespace App\Libraries;

use GuzzleHttp\Client;
use Google\Auth\ApplicationDefaultCredentials;
use Google\Auth\Credentials\ServiceAccountCredentials;
use GuzzleHttp\Promise;

require_once APPPATH . 'ThirdParty/firebasephp-jwt/vendor/autoload.php';
class PushNotify
{
    protected $client;
    protected $accessToken;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://fcm.googleapis.com/v1/projects/petsfolio-2204f/messages:send',
            'headers'  => [
                'Content-Type' => 'application/json',
            ],
        ]);

        $this->accessToken = $this->getAccessToken();
    }

    protected function getAccessToken()
    {
        $jsonPath = ROOTPATH . 'petsfolio-2204f-65162b859efb.json';

        $scopes      = ['https://www.googleapis.com/auth/firebase.messaging'];
        $credentials = new ServiceAccountCredentials($scopes, $jsonPath);

        $credentials->fetchAuthToken();
        return $credentials->getLastReceivedToken()['access_token'];
    }
    public function notify($token, $title, $message)
    {
        $body = [
            "message" => [
                "token"        => $token,
                "notification" => [
                    "title" => $title,
                    "body"  => $message,
                ],
                "android"      => [
                    "priority" => "HIGH"
                ],
                "apns"         => [
                    "headers" => [
                        "apns-priority" => "10"
                    ],
                    "payload" => [
                        "aps" => [
                            "sound" => "default"
                        ]
                    ]
                ]
            ]
        ];

        // Convert the payload to JSON
        $bodyJson = json_encode($body);
        return $this->sendNotification($bodyJson);
    }

    public function notifyMultiple($tokens, $title, $message)
    {
        $responses = [];

        foreach ($tokens as $token) {
            $body = [
                "message" => [
                    "token"        => $token,
                    "notification" => [
                        "title" => $title,
                        "body"  => $message,
                    ],
                    "android"      => [
                        "priority" => "HIGH"
                    ],
                    "apns"         => [
                        "headers" => [
                            "apns-priority" => "10"
                        ],
                        "payload" => [
                            "aps" => [
                                "sound" => "default"
                            ]
                        ]
                    ]
                ]
            ];

            $bodyJson    = json_encode($body);
            $responses[] = $this->sendNotification($bodyJson);
        }

        return $responses;
    }

    public function sendNotification($body)
    {

        try {
            $response     = $this->client->post('', [
                'json'    => json_decode($body, true),
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->accessToken,
                    'Content-Type'  => 'application/json'
                ],
            ]);
            $statusCode   = $response->getStatusCode();
            $responseData = $response->getBody()->getContents();
            return [
                'status_code' => $statusCode,
                'response'    => $responseData
            ];
        } catch (\Exception $e) {
            return [
                'status_code' => 500,
                'message'     => $e->getMessage()
            ];
        }
    }

    public function notifyMultipleParallel($tokens, $title, $message, $routeName = null, array $data = [], $batchSize = 50)
    {
<<<<<<< HEAD
        $client    = new Client();
        $responses = [];

        // Split tokens into batches
=======
        $client  = new Client();
        $results = [];

>>>>>>> origin/main
        $tokenBatches = array_chunk($tokens, $batchSize);

        foreach ($tokenBatches as $batch) {
            $promises = [];

            foreach ($batch as $token) {
                $messagePayload = [
                    "token"        => $token,
                    "notification" => [
                        "title" => $title,
                        "body"  => $message,
<<<<<<< HEAD
                    ],
                    "android"      => [
                        "priority" => "HIGH"
                    ],
                    "apns"         => [
                        "headers" => ["apns-priority" => "10"],
                        "payload" => ["aps" => ["sound" => "default"]]
                    ]
                ];

                // Add data payload if available
                $dataPayload = array_map('strval', $data);
                if ($routeName) {
                    $dataPayload['route_name'] = (string) $routeName;
                }

                if (!empty($dataPayload)) {
                    $messagePayload["data"] = $dataPayload;
                }

                $promises[] = $client->postAsync('https://fcm.googleapis.com/v1/projects/petsfolio-2204f/messages:send', [
                    'json'    => ["message" => $messagePayload],
                    'headers' => [
                        'Authorization' => 'Bearer ' . $this->accessToken,
                        'Content-Type'  => 'application/json'
                    ],
                ]);
            }

            // Wait for the batch to complete before processing the next
            $batchResponses = Promise\Utils::settle($promises)->wait();
            $responses      = array_merge($responses, $batchResponses);

            // Free up memory
=======
                        "image" => "https://staging-api.petsfolio.in/public/uploads/icons/ic_launcher.png"
                    ],
                    "android"      => [
                        "priority" => "HIGH",
                        "notification" => [
                            "sound"      => "default",
                            "icon"       => "ic_launcher",
                            "color"      => "#FF6F00",
                            "channel_id" => "custom_sound_channel",
                        ],
                    ],
                    "apns"         => [
                        "headers" => ["apns-priority" => "10"],
                        "payload" => ["aps" => ["sound" => "notification_sound"]]
                    ]
                ];

                if (!empty($routeName)) {
                    $messagePayload["android"]["data"] = [
                        "route_name" => (string) $routeName
                    ];
                }

                if (!empty($data)) {
                    $messagePayload["data"] = array_map('strval', $data);
                }

                $body = ["message" => $messagePayload];

                $promises[$token] = $client->postAsync(
                    'https://fcm.googleapis.com/v1/projects/petsfolio-2204f/messages:send',
                    [
                        'json'    => $body,
                        'headers' => [
                            'Authorization' => 'Bearer ' . $this->accessToken,
                            'Content-Type'  => 'application/json'
                        ],
                    ]
                );
            }

            $responses = Promise\Utils::settle($promises)->wait();

            foreach ($responses as $token => $response) {
                if ($response['state'] === 'fulfilled') {
                    $results[] = [
                        'token'  => $token,
                        'status' => 'sent',
                        'body'   => json_decode((string) $response['value']->getBody(), true)
                    ];
                } else {
                    $reason = $response['reason'];
                    $errorDetails = $reason->getMessage();

                    if ($reason instanceof \GuzzleHttp\Exception\RequestException && $reason->hasResponse()) {
                        $resBody = (string) $reason->getResponse()->getBody();
                        $decoded = json_decode($resBody, true);
                        if (!empty($decoded['error']['message'])) {
                            $code   = $decoded['error']['code'] ?? $reason->getResponse()->getStatusCode();
                            $fcmErr = $decoded['error']['details'][0]['errorCode'] ?? '';
                            $errorDetails = "FCM [{$code}" . ($fcmErr ? " / {$fcmErr}" : "") . "]: " . $decoded['error']['message'];
                        }
                    }

                    $results[] = [
                        'token'  => $token,
                        'status' => 'failed',
                        'error'  => $errorDetails,
                    ];
                }
            }

>>>>>>> origin/main
            unset($promises);
            gc_collect_cycles();
        }

<<<<<<< HEAD
        return $responses;
    }
    /**
     * Send multicast message using v1 API (simulated via parallel)
     * The legacy /fcm/send is deprecated and requires a server key which is missing.
     */
=======
        return $results;
    }
>>>>>>> origin/main
    public function sendMulticast(array $tokens, $title, $message, $routeName = null, array $data = [])
    {
        if (empty($tokens)) return [];
        return $this->notifyMultipleParallel($tokens, $title, $message, $routeName, $data);
    }
}
