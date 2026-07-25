<?php

namespace App\Libraries;

use GuzzleHttp\Client;
use Exception;

require_once APPPATH . 'ThirdParty/firebasephp-jwt/vendor/autoload.php';

class FirebaseService
{
    protected $client;
    protected $projectId;
    protected $accessToken;

    public function __construct()
    {
        $serviceAccountPath = ROOTPATH . 'petsfolio-2204f-65162b859efb.json';
        $this->projectId = getenv('FIREBASE_PROJECT_ID');

        $this->accessToken = $this->getAccessToken($serviceAccountPath);
        $this->client = new Client([
            'base_uri' => "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/",
            'headers' => [
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Content-Type' => 'application/json'
            ]
        ]);
    }

    protected function getAccessToken($path)
    {
        $json = json_decode(file_get_contents($path), true);
        $jwtClient = new \Google\Auth\OAuth2([
            'audience' => 'https://oauth2.googleapis.com/token',
            'issuer' => $json['client_email'],
            'signingAlgorithm' => 'RS256',
            'signingKey' => $json['private_key'],
            'scope' => 'https://www.googleapis.com/auth/datastore',
            'tokenCredentialUri' => 'https://oauth2.googleapis.com/token'
        ]);

        $authToken = $jwtClient->fetchAuthToken();
        return $authToken['access_token'];
    }

    /**
     * Get all chat data including subcollections and messages
     */
    public function getAllChatData()
    {
        $basePath = 'chats';
        $result = [];
        try {
            // Get all chat documents
            $chatDocs = $this->listDocuments($basePath);

            foreach ($chatDocs as $docId) {
                $docPath = "$basePath/$docId";
                $result[$docId] = $this->getDocumentWithAllSubcollections($docPath);
            }

            return $result;
        } catch (Exception $e) {
            log_message('error', 'Firestore getAllChatData Error: ' . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Recursively get document with all subcollections
     */
    /**
     * Recursively get document with all nested subcollections
     */
    private function getDocumentWithAllSubcollections($path, $depth = 0, $maxDepth = 10)
    {
        // Prevent infinite recursion
        if ($depth > $maxDepth) {
            log_message('warning', "Maximum depth ($maxDepth) reached at path: $path");
            return ['warning' => 'Maximum depth reached'];
        }

        $data = [];

        try {
            // Get document fields
            $doc = $this->getDocument($path);
            if ($doc) {
                $data['fields'] = $doc;
            }

            // Get subcollections
            $subcollections = $this->listSubcollections($path);
            foreach ($subcollections as $subcollection) {
                $subPath = "$path/$subcollection";

                // Get all documents in this subcollection
                $subDocs = $this->listDocuments($subPath);
                $subcollectionData = [];

                foreach ($subDocs as $docId) {
                    $docPath = "$subPath/$docId";
                    // Recursive call for each document
                    $subcollectionData[$docId] = $this->getDocumentWithAllSubcollections($docPath, $depth + 1, $maxDepth);
                }

                $data['subcollections'][$subcollection] = $subcollectionData;
            }

            return $data;
        } catch (Exception $e) {
            log_message('error', "Error fetching nested data at $path: " . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }
    /**
     * Get complete chat structure with all nested collections
     */
    public function getCompleteChatStructure($chatId)
    {
        $path = "chats/$chatId";
        return $this->getDocumentWithAllSubcollections($path);
    }

    /**
     * Get all chats with complete nested structure
     */
    public function getAllChatsWithFullStructure()
    {
        $result = [];
        try {
            $chatDocs = $this->listDocuments('chats');

            foreach ($chatDocs as $docId) {
                $result[$docId] = $this->getCompleteChatStructure($docId);
            }

            return $result;
        } catch (Exception $e) {
            log_message('error', 'Error fetching all chats: ' . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Get all documents in a collection
     */
    private function getAllDocumentsInCollection($collectionPath)
    {
        $documents = [];
        $pageToken = null;

        do {
            $queryParams = ['pageSize' => 100];
            if ($pageToken) {
                $queryParams['pageToken'] = $pageToken;
            }

            $url = $collectionPath . '?' . http_build_query($queryParams);

            try {
                $response = $this->client->get($url);
                $data = json_decode($response->getBody(), true);

                if (isset($data['documents'])) {
                    foreach ($data['documents'] as $doc) {
                        $parts = explode('/', $doc['name']);
                        $docId = end($parts);
                        $documents[$docId] = $doc['fields'] ?? [];
                    }
                }

                $pageToken = $data['nextPageToken'] ?? null;
            } catch (Exception $e) {
                log_message('error', "Firestore Error [{$collectionPath}]: " . $e->getMessage());
                break;
            }
        } while ($pageToken);

        return $documents;
    }

    /**
     * List documents in a collection
     */
    private function listDocuments($collectionPath)
    {
        log_message('debug', "Attempting to list documents in: {$collectionPath}");

        try {
            $url = $collectionPath . '?pageSize=100';
            log_message('debug', "Full request URL: {$url}");

            $response = $this->client->get($url);
            $status = $response->getStatusCode();
            $body = $response->getBody()->getContents();

            log_message('debug', "Response status: {$status}");
            log_message('debug', "Raw response: {$body}");

            $data = json_decode($body, true);

            if (!isset($data['documents'])) {
                log_message('error', "No 'documents' key in response");
                return [];
            }

            $documents = [];
            foreach ($data['documents'] as $doc) {
                $parts = explode('/', $doc['name']);
                $docId = end($parts);
                $documents[] = $docId;
            }

            log_message('debug', "Found documents: " . implode(', ', $documents));
            return $documents;
        } catch (Exception $e) {
            log_message('error', "Firestore listDocuments ERROR: " . $e->getMessage());
            log_message('error', "Request details: " . $e->getRequest()->getUri());
            if ($e->hasResponse()) {
                log_message('error', "Error response: " . $e->getResponse()->getBody());
            }
            return [];
        }
    }

    /**
     * List subcollections of a document
     */
    private function listSubcollections($documentPath)
    {
        $url = "{$documentPath}:listCollectionIds";

        try {
            $response = $this->client->post($url, [
                'json' => ['pageSize' => 100]
            ]);

            $data = json_decode($response->getBody(), true);
            return $data['collectionIds'] ?? [];
        } catch (Exception $e) {
            log_message('error', "Failed to list subcollections at $documentPath: " . $e->getMessage());
            return [];
        }
    }
/**
 * Get all messages between two users in a specific chat
 */
public function getUserMessages($chatId, $user1, $user2) {
    $path = "chats/{$chatId}/{$user1}/{$user2}/messages";
    
    try {
        $response = $this->client->get($path);
        $data = json_decode($response->getBody(), true);
        $messages = [];
        if (isset($data['documents'])) {
            foreach ($data['documents'] as $doc) {
                $parts = explode('/', $doc['name']);
                $messageId = end($parts);
                
                $messages[$messageId] = [
                    'id' => $messageId,
                    'text' => $doc['fields']['text']['stringValue'] ?? null,
                    'sender' => $doc['fields']['senderId']['stringValue'] ?? null,
                    'timestamp' => $doc['fields']['timestamp']['timestampValue'] ?? null,
                    'isQuestion' => $doc['fields']['isQuestion']['booleanValue'] ?? null
                ];
            }
        }
        
        return $messages;
    } catch (Exception $e) {
        log_message('error', "Error getting messages: " . $e->getMessage());
        return [];
    }
}
    /**
     * Get a single document
     */
    private function getDocument($documentPath)
    {
        try {
            $response = $this->client->get($documentPath);
            $data = json_decode($response->getBody(), true);

            if (!isset($data['fields'])) {
                log_message('debug', "Document at $documentPath has no fields: " . json_encode($data));
                return null;
            }

            // Convert Firestore field types to PHP values
            return $this->convertFirestoreFields($data['fields']);
        } catch (Exception $e) {
            // Handle 404 (document not found) differently
            if ($e->getCode() === 404) {
                log_message('debug', "Document not found at $documentPath");
                return null;
            }
            throw $e;
        }
    }

    /**
     * Convert Firestore field values to PHP types
     */
    private function convertFirestoreFields($fields)
    {
        $result = [];
        foreach ($fields as $key => $field) {
            $valueType = array_keys($field)[0];
            $result[$key] = $field[$valueType];

            // Handle special types if needed
            if ($valueType === 'timestampValue') {
                $result[$key] = new DateTime($result[$key]);
            }
        }
        return $result;
    }

    /**
     * Create a new document
     */
    public function createDocument($collectionPath, $fields)
    {
        try {
            $response = $this->client->post($collectionPath, [
                'json' => ['fields' => $fields]
            ]);

            return json_decode($response->getBody(), true);
        } catch (Exception $e) {
            log_message('error', 'Firestore createDocument Error: ' . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }
    public function getDocumentWithSubcollections($path)
    {
        $data = [];

        // Get the current document's fields
        $doc = $this->getDocument($path);
        if ($doc) {
            $data['fields'] = $doc;
        }

        // Get subcollections
        $subcollections = $this->listSubcollections($path);
        foreach ($subcollections as $subcollection) {
            $subPath = "$path/$subcollection";

            // Fetch all documents in the subcollection
            $documents = $this->listDocuments($subPath);
            $subData = [];

            foreach ($documents as $docId) {
                $fullDocPath = "$subPath/$docId";
                $subData[$docId] = $this->getDocumentWithSubcollections($fullDocPath);
            }

            $data['subcollections'][$subcollection] = $subData;
        }

        return $data;
    }
    /**
     * Get all messages for a specific chat document
     */
    public function getChatMessages($chatId, $senderId1, $senderId2)
    {
        $basePath = "chats/$chatId/$senderId1/$senderId2/messages";

        try {
            return $this->getAllDocumentsInCollection($basePath);
        } catch (Exception $e) {
            log_message('error', 'Firestore getChatMessages Error: ' . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }
    /**
     * Get all chats with their complete structure including messages
     */
    public function getAllChatsWithMessages()
    {
        $result = [];
        try {
            // Get all chat documents
            $chatDocs = $this->listDocuments('chats');

            foreach ($chatDocs as $docId) {
                $docPath = "chats/$docId";

                // Get all subcollections (sender pairs)
                $subcollections = $this->listSubcollections($docPath);

                foreach ($subcollections as $sender1) {
                    $sender1Path = "$docPath/$sender1";
                    $sender2Collections = $this->listSubcollections($sender1Path);

                    foreach ($sender2Collections as $sender2) {
                        $messagesPath = "$sender1Path/$sender2/messages";
                        $messages = $this->getAllDocumentsInCollection($messagesPath);

                        $result[$docId][$sender1][$sender2]['messages'] = $messages;
                    }
                }
            }

            return $result;
        } catch (Exception $e) {
            log_message('error', 'Firestore getAllChatsWithMessages Error: ' . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }
}
