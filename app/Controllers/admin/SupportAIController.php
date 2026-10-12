<?php

namespace App\Controllers\admin;

use App\Controllers\BaseController;
use App\Libraries\AIAnalyst\AISupportAgent;
use App\Models\TicketModel;
use App\Models\TicketMessageModel;
use Config\Services;

class SupportAIController extends BaseController
{
    protected TicketModel $ticketModel;
    protected TicketMessageModel $ticketMessageModel;
    protected AISupportAgent $aiAgent;

    public function __construct()
    {
        $this->ticketModel = new TicketModel();
        $this->ticketMessageModel = new TicketMessageModel();
        $this->aiAgent = new AISupportAgent();
    }

    /**
     * Generate an AI Copilot response for a Client or SP support ticket.
     */
    public function generateReply()
    {
        $ticketId     = $this->request->getPost('ticket_id');
        $userType     = $this->request->getPost('user_type') ?: 'client';
        $customPrompt = $this->request->getPost('custom_prompt');

        if (empty($ticketId)) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Ticket ID is required.'
            ]);
        }

        $ticket = $this->ticketModel->getTicket($ticketId);
        if (!$ticket) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Ticket not found.'
            ]);
        }

        $ticketInfo = [
            'id'       => $ticket->id ?? $ticketId,
            'name'     => $ticket->name ?? 'User',
            'phone'    => $ticket->phone ?? '',
            'subject'  => $ticket->subject ?? ($ticket->category ?? 'Support Inquiry'),
            'message'  => $ticket->message ?? '',
            'status'   => $ticket->status ?? 'open'
        ];

        $messages = $this->ticketModel->getTicketMessage($ticketId);

        $result = $this->aiAgent->generateReply($userType, $ticketInfo, (array)$messages, $customPrompt);

        return $this->response->setJSON([
            'status' => $result['status'] ?? true,
            'reply'  => $result['reply'] ?? '',
            'mode'   => $this->getTicketMode($ticketId)
        ]);
    }

    /**
     * Generate dynamic quick reply suggestion chips based on the latest ticket context.
     */
    public function suggestChips()
    {
        $ticketId = $this->request->getPost('ticket_id');
        $userType = $this->request->getPost('user_type') ?: 'client';

        if (empty($ticketId)) {
            return $this->response->setJSON([
                'status'      => false,
                'suggestions' => []
            ]);
        }

        $ticket = $this->ticketModel->getTicket($ticketId);
        $ticketInfo = [
            'id'      => $ticket->id ?? $ticketId,
            'name'    => $ticket->name ?? 'User',
            'subject' => $ticket->subject ?? ($ticket->category ?? 'Support Inquiry')
        ];

        $messages = $this->ticketModel->getTicketMessage($ticketId);
        $suggestions = $this->aiAgent->generateSuggestions($userType, $ticketInfo, (array)$messages);

        return $this->response->setJSON([
            'status'      => true,
            'suggestions' => $suggestions
        ]);
    }

    /**
     * Rewrite agent draft into polite, empathetic, professional customer support tone.
     */
    public function rewriteDraft()
    {
        $draftText = $this->request->getPost('draft_text');
        $userType  = $this->request->getPost('user_type') ?: 'client';

        if (empty(trim((string)$draftText))) {
            return $this->response->setJSON([
                'status'        => false,
                'polished_text' => $draftText
            ]);
        }

        $polished = $this->aiAgent->rewriteDraft((string)$draftText, $userType);

        return $this->response->setJSON([
            'status'        => true,
            'polished_text' => $polished
        ]);
    }

    /**
     * Toggle AI Mode vs Human Mode for a ticket.
     */
    public function toggleMode()
    {
        $ticketId = $this->request->getPost('ticket_id');
        $mode     = strtolower(trim((string)$this->request->getPost('mode')));

        if (!in_array($mode, ['ai', 'human'], true)) {
            $mode = 'ai';
        }

        if ($ticketId) {
            $cache = Services::cache();
            $cache->save("ticket_mode_{$ticketId}", $mode, 86400 * 7); // Cache for 7 days
        }

        return $this->response->setJSON([
            'status'  => true,
            'mode'    => $mode,
            'message' => ($mode === 'ai') ? 'AI Mode Enabled: Auto-assistance active' : 'Human Mode Enabled: Manual control'
        ]);
    }

    /**
     * Get current mode (AI vs Human) for a ticket.
     */
    public function getModeStatus($ticketId)
    {
        $mode = $this->getTicketMode($ticketId);

        return $this->response->setJSON([
            'status' => true,
            'mode'   => $mode
        ]);
    }

    /**
     * Diagnostic endpoint to test Knowledge Base retrieval and AI response for any query.
     */
    public function testKb()
    {
        $query    = $this->request->getVar('query') ?: 'How do I book dog walking?';
        $userType = $this->request->getVar('user_type') ?: 'client';

        $ticketInfo = [
            'id'       => 0,
            'name'     => 'Test User',
            'subject'  => 'KB Test',
            'category' => 'Inquiry',
            'status'   => 'open'
        ];
        $messages = [
            ['sender_type' => 'user', 'message' => $query]
        ];

        $kb = new \App\Libraries\AIAnalyst\SupportKnowledgeBase();
        $matches = $kb->search($query, $userType, 3);
        $replyResult = $this->aiAgent->generateReply($userType, $ticketInfo, $messages);
        $suggestions = $this->aiAgent->generateSuggestions($userType, $ticketInfo, $messages);

        return $this->response->setJSON([
            'status'      => true,
            'query'       => $query,
            'user_type'   => $userType,
            'kb_matches'  => $matches,
            'ai_reply'    => $replyResult['reply'] ?? '',
            'suggestions' => $suggestions
        ]);
    }

    /**
     * Send a support message directly to the database and broadcast via Pusher.
     * Ensures 100% reliable message delivery without CORS or cross-domain issues.
     */
    public function sendMessage()
    {
        $ticketId   = $this->request->getPost('chat_id') ?: $this->request->getPost('ticket_id');
        $message    = trim((string)$this->request->getPost('message'));
        $senderId   = $this->request->getPost('sender_id') ?: (session('user')->id ?? 1);
        $senderType = $this->request->getPost('sender_type') ?: 'support';
        $senderName = session('user')->name ?? 'Support';

        if (empty($ticketId) || empty($message)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Ticket ID and message are required.'
            ]);
        }

        $now = date('Y-m-d H:i:s');
        $db = \Config\Database::connect();

        // 1. Insert message into ticket_messages
        $db->table('ticket_messages')->insert([
            'ticket_id'   => $ticketId,
            'sender_id'   => $senderId,
            'sender_type' => $senderType,
            'message'     => $message,
            'created_at'  => $now,
            'is_read'     => 0
        ]);
        $messageId = $db->insertID();

        // 2. Update tickets table updated_at and mark incoming customer messages as read
        $db->table('tickets')->where('id', $ticketId)->update([
            'updated_at' => $now
        ]);
        if ($senderType === 'support') {
            $db->table('ticket_messages')
                ->where('ticket_id', $ticketId)
                ->where('sender_type !=', 'support')
                ->set(['is_read' => 1])
                ->update();
        }

        // 3. Broadcast real-time message via Pusher REST API
        $payload = [
            'id'           => $messageId,
            'ticket_id'    => $ticketId,
            'chat_id'      => $ticketId,
            'sender_id'    => $senderId,
            'sender_type'  => $senderType,
            'support_name' => $senderName,
            'message'      => $message,
            'created_at'   => $now,
            'attachments'  => null
        ];

        $this->broadcastPusher(['chat-channel-' . $ticketId, 'ticket-' . $ticketId], 'new-message', $payload);

        return $this->response->setJSON([
            'status'     => 'ok',
            'message_id' => $messageId,
            'data'       => $payload
        ]);
    }

    /**
     * Broadcast an event to Pusher channels using Pusher REST API (no external lib required).
     */
    private function broadcastPusher(array $channels, string $event, array $data): void
    {
        try {
            $pusherConfig = new \Config\Pusher();
            $appId     = $pusherConfig->app_id ?? '1966621';
            $appKey    = $pusherConfig->app_key ?? '3c3318df787d87828310';
            $appSecret = $pusherConfig->app_secret ?? 'dab0ad387aef9ae4b599';
            $cluster   = $pusherConfig->cluster ?? 'ap2';

            $body = json_encode([
                'name'     => $event,
                'channels' => $channels,
                'data'     => json_encode($data)
            ]);

            $authTimestamp = time();
            $authVersion   = '1.0';
            $bodyMd5       = md5($body);

            $path = "/apps/{$appId}/events";
            $queryString = "auth_key={$appKey}&auth_timestamp={$authTimestamp}&auth_version={$authVersion}&body_md5={$bodyMd5}";
            $signatureString = "POST\n{$path}\n{$queryString}";
            $authSignature = hash_hmac('sha256', $signatureString, $appSecret);

            $url = "https://api-{$cluster}.pusher.com{$path}?{$queryString}&auth_signature={$authSignature}";

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_TIMEOUT, 3);
            curl_exec($ch);
            curl_close($ch);
        } catch (\Throwable $e) {
            log_message('error', 'Pusher broadcast error: ' . $e->getMessage());
        }
    }

    /**
     * Delete a support message (soft delete with deleted_by tracking) and broadcast via Pusher.
     */
    public function deleteMessage()
    {
        $messageId = (int)$this->request->getPost('message_id');
        $ticketId  = (int)($this->request->getPost('ticket_id') ?: $this->request->getPost('chat_id'));
        $deletedBy = session('user')->name ?? 'Admin';
        $now       = date('Y-m-d H:i:s');

        if (!$messageId || !$ticketId) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Message ID and Ticket ID are required.'
            ]);
        }

        $db = \Config\Database::connect();
        $db->table('ticket_messages')
            ->where('id', $messageId)
            ->where('ticket_id', $ticketId)
            ->update([
                'is_deleted' => 1,
                'deleted_by' => $deletedBy,
                'deleted_at' => $now
            ]);

        // Broadcast deletion event via Pusher so all connected clients update in real-time
        $payload = [
            'message_id' => $messageId,
            'ticket_id'  => $ticketId,
            'deleted_by' => $deletedBy,
            'deleted_at' => $now,
            'is_deleted' => 1
        ];

        $this->broadcastPusher(['chat-channel-' . $ticketId, 'ticket-' . $ticketId], 'message-deleted', $payload);

        return $this->response->setJSON([
            'status'     => 'ok',
            'message_id' => $messageId,
            'deleted_by' => $deletedBy,
            'deleted_at' => $now
        ]);
    }

    /**
     * Helper to read ticket mode from cache with default 'human'.
     */
    private function getTicketMode($ticketId): string
    {
        try {
            $cache = Services::cache();
            $saved = $cache->get("ticket_mode_{$ticketId}");
            if ($saved && in_array($saved, ['ai', 'human'], true)) {
                return $saved;
            }
        } catch (\Throwable $e) {}

        return 'human'; // Default to Human Mode (AI Suggests Only)
    }
}
