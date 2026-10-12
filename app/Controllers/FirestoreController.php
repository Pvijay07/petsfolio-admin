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
     * Get all chat collections with subcollections and messages
     */
    public function getAllChats($booking_id, $user, $provider)
    {
        $firebase = new FirebaseService();
         list($userName, $userId) = explode('_', $user);
        list($providerName, $providerId) = explode('_', $provider);
        $messages = $firebase->getUserMessages($booking_id, $userId, $providerId);
       
        $formattedMessages = [];
        foreach ($messages as $message) {
            $formattedMessages[] = [
                'text' => $message['text'],
                'isQuestion' => $message['isQuestion'],
                'timestamp' => date('h:i A, M d', strtotime($message['timestamp'])),
                'sender' => $message['isQuestion'] ? $userName : $providerName
            ];
        }
        return $this->response->setStatusCode(200)->setJSON(['status' => true, 'messages' => $formattedMessages]);
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
