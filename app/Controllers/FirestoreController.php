<?php

// In your controller or service class
namespace App\Controllers;

use App\Libraries\FirebaseService;

class FirestoreController extends BaseController
{
    private $firebaseService;

    public function __construct()
    {
        $this->firebaseService = new FirebaseService();
    }

    /**
     * Get all chat conversations from booking_conversations and booking_messages
     */
    public function getAllChats($booking_id = null, $user = null, $provider = null)
    {
        $db = \Config\Database::connect();

        $bookingId = !empty($booking_id) ? trim($booking_id) : null;

        $userName = 'Customer';
        $userId = null;
        if (!empty($user)) {
            $userClean = trim(urldecode($user));
            $lastUnderscore = strrpos($userClean, '_');
            if ($lastUnderscore !== false) {
                $userName = trim(substr($userClean, 0, $lastUnderscore)) ?: 'Customer';
                $possibleId = trim(substr($userClean, $lastUnderscore + 1));
                if (is_numeric($possibleId)) {
                    $userId = (int)$possibleId;
                }
            } elseif (is_numeric($userClean)) {
                $userId = (int)$userClean;
            } else {
                $userName = $userClean;
            }
        }

        $providerName = 'Provider';
        $providerId = null;
        if (!empty($provider)) {
            $providerClean = trim(urldecode($provider));
            $lastUnderscore = strrpos($providerClean, '_');
            if ($lastUnderscore !== false) {
                $providerName = trim(substr($providerClean, 0, $lastUnderscore)) ?: 'Provider';
                $possibleId = trim(substr($providerClean, $lastUnderscore + 1));
                if (is_numeric($possibleId)) {
                    $providerId = (int)$possibleId;
                }
            } elseif (is_numeric($providerClean)) {
                $providerId = (int)$providerClean;
            } else {
                $providerName = $providerClean;
            }
        }

        $conversation = null;

        // When both booking_id and providerId are supplied, match ONLY this booking and this specific service provider
        if ($bookingId && $providerId) {
            $builder = $db->table('booking_conversations')
                ->where('booking_id', $bookingId)
                ->where('service_provider_id', $providerId);

            if ($userId) {
                $builder->where('client_id', $userId);
            }

            $conversation = $builder->orderBy('id', 'DESC')->get()->getRow();

            // If not found with client_id, match booking_id + providerId directly
            if (!$conversation && $userId) {
                $conversation = $db->table('booking_conversations')
                    ->where('booking_id', $bookingId)
                    ->where('service_provider_id', $providerId)
                    ->orderBy('id', 'DESC')
                    ->get()
                    ->getRow();
            }
        } elseif ($bookingId && !$providerId) {
            // Only if NO provider was requested (single-argument call: firestore/getMessages/(:any))
            $conversation = $db->table('booking_conversations')
                ->where('booking_id', $bookingId)
                ->orderBy('id', 'DESC')
                ->get()
                ->getRow();
        } elseif (!$bookingId && $userId && $providerId) {
            // Only if NO booking_id was requested
            $conversation = $db->table('booking_conversations')
                ->where('client_id', $userId)
                ->where('service_provider_id', $providerId)
                ->orderBy('id', 'DESC')
                ->get()
                ->getRow();
        }

        if (!$conversation) {
            return $this->response->setStatusCode(200)->setJSON([
                'status'   => true,
                'messages' => []
            ]);
        }

        // Retrieve real user and provider names if missing or default
        if (empty($userName) || $userName === 'Customer') {
            $userRow = $db->table('users')->select('name')->where('id', $conversation->client_id)->get()->getRow();
            if ($userRow && !empty($userRow->name)) {
                $userName = $userRow->name;
            }
        }
        if (empty($providerName) || $providerName === 'Provider') {
            $spRow = $db->table('service_providers')->select('name')->where('id', $conversation->service_provider_id)->get()->getRow();
            if ($spRow && !empty($spRow->name)) {
                $providerName = $spRow->name;
            }
        }

        $messages = $db->table('booking_messages')
            ->where('conversation_id', $conversation->id)
            ->orderBy('created_at', 'ASC')
            ->get()
            ->getResult();

        $formattedMessages = [];
        foreach ($messages as $message) {
            $isClient = ($message->sender_type === 'client');
            $formattedMessages[] = [
                'id'         => $message->id,
                'text'       => $message->message,
                'isQuestion' => $isClient,
                'timestamp'  => $message->created_at,
                'sender'     => $isClient ? $userName : $providerName
            ];
        }

        return $this->response->setStatusCode(200)->setJSON([
            'status'   => true,
            'messages' => $formattedMessages
        ]);
    }
    public function getCollection($collectionName)
    {
        $firebaseService = new FirebaseService();
        $allChatData = $firebaseService->getDocumentWithSubcollections($collectionName);

        // Save to file for inspection
        file_put_contents('firestore_dump.json', json_encode($allChatData, JSON_PRETTY_PRINT));

        // Or return as JSON response
        return $this->response->setJSON($allChatData);
    }
    /**
     * Get specific chat data
     */
    public function getSpecificChat($chatId)
    {
        try {
            $chatData = $this->firebaseService->getChatCollection($chatId);

            return $this->response->setJSON([
                'status' => 'success',
                'data' => $chatData
            ]);
        } catch (Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get messages from a specific chat
     */
    public function getChatMessages($chatPath)
    {
        try {
            // Example: chats/PSF04310WLK/Syed omer_14/Syed omer_20
            $messages = $this->firebaseService->getChatMessages($chatPath);

            return $this->response->setJSON([
                'status' => 'success',
                'messages' => $messages
            ]);
        } catch (Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Example of processing the specific URL you mentioned
     */
    public function getSpecificChatMessages()
    {
        try {
            // For the URL: chats/PSF04310WLK/Syed omer_14/Syed omer_20/messages
            $chatPath = 'chats/PSF04310WLK/Syed omer_14/Syed omer_20';
            $messages = $this->firebaseService->getChatMessages($chatPath);

            // Extract and format message data
            $formattedMessages = [];
            foreach ($messages as $messageId => $messageData) {
                $formattedMessages[$messageId] = $this->firebaseService->extractFieldValues($messageData);
            }

            echo "<h2>Messages from specific chat:</h2>";
            echo "<pre>" . print_r($formattedMessages, true) . "</pre>";

            return $this->response->setJSON([
                'status' => 'success',
                'messages' => $formattedMessages
            ]);
        } catch (Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
