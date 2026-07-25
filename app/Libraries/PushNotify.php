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
        $client    = new Client();
        $responses = [];

        // Split tokens into batches
        $tokenBatches = array_chunk($tokens, $batchSize);

        foreach ($tokenBatches as $batch) {
            $promises = [];

            foreach ($batch as $token) {
                $messagePayload = [
                    "token"        => $token,
                    "notification" => [
                        "title" => $title,
                        "body"  => $message,
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
            unset($promises);
            gc_collect_cycles();
        }

        return $responses;
    }
    /**
     * Send multicast message using v1 API (simulated via parallel)
     * The legacy /fcm/send is deprecated and requires a server key which is missing.
     */
    public function sendMulticast(array $tokens, $title, $message, $routeName = null, array $data = [])
    {
        if (empty($tokens)) return [];
        return $this->notifyMultipleParallel($tokens, $title, $message, $routeName, $data);
    }
}
