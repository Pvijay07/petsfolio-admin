<?php

namespace App\Controllers\admin;

use App\Controllers\BaseController;
use App\Libraries\AIAnalyst\GeminiAnalyst;
use Config\Database;

/**
 * AiAnalysisController
 * 
 * Controller for AI Business Analyst Chat Interface & APIs.
 */
class AiAnalysisController extends BaseController
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    /**
     * Helper to retrieve authenticated Admin User ID safely regardless of session storage format.
     */
    private function getAdminId(): int
    {
        $user = session()->get('user');
        if (is_object($user)) {
            return (int) ($user->id ?? $user->user_id ?? 1);
        }
        if (is_array($user)) {
            return (int) ($user['id'] ?? $user['user_id'] ?? 1);
        }
        return (int) (session()->get('user_id') ?? 1);
    }

    /**
     * Render the main AI Analyst Chat View.
     */
    public function index()
    {
        $adminId = $this->getAdminId();

        // Ensure database migration tables exist
        $this->ensureTablesExist();

        $conversations = $this->db->table('ai_analyst_conversations')
            ->where('is_deleted', 0)
            ->groupStart()
            ->where('admin_id', $adminId)
            ->orWhere('admin_id', 1)
            ->groupEnd()
            ->orderBy('id', 'DESC')
            ->get()
            ->getResultArray();

        $data = [
            'conversations' => $conversations,
            'title'         => 'AI Business Analyst'
        ];

        return $this->loadView('admin/ai_analyst', $data);
    }

    /**
     * Send a user query to Gemini Analyst and return response JSON.
     */
    public function sendMessage()
    {
        $adminId        = $this->getAdminId();
        $conversationId = (int) $this->request->getPost('conversation_id');
        $userMessage    = trim((string) $this->request->getPost('message'));

        if (empty($userMessage)) {
            return $this->response->setJSON(['status' => false, 'message' => 'Please provide a question.']);
        }

        $this->ensureTablesExist();

        // Create new conversation if ID not provided
        if (!$conversationId) {
            $title = mb_substr($userMessage, 0, 40) . (mb_strlen($userMessage) > 40 ? '...' : '');
            $this->db->table('ai_analyst_conversations')->insert([
                'admin_id'   => $adminId,
                'title'      => $title,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            $conversationId = $this->db->insertID();
        }

        // 1. Log User Message
        $this->db->table('ai_analyst_messages')->insert([
            'conversation_id' => $conversationId,
            'role'            => 'user',
            'content'         => $userMessage,
            'created_at'      => date('Y-m-d H:i:s')
        ]);
        $userMsgId = $this->db->insertID();

        // 2. Load conversation history (last 10 messages)
        $rawHistory = $this->db->table('ai_analyst_messages')
            ->where('conversation_id', $conversationId)
            ->orderBy('id', 'ASC')
            ->limit(10)
            ->get()
            ->getResultArray();

        // 3. Invoke Gemini Analyst
        $analyst = new GeminiAnalyst();
        $res     = $analyst->ask($userMessage, $rawHistory, $userMsgId);

        $assistantReply  = $res['reply'] ?? 'Unable to process request.';
        $metricsCalled   = $res['metrics'] ?? [];
        $chartConfig     = $res['chart'] ?? null;

        // 4. Log Assistant Response (Ensure connection active after long LLM cURL wait)
        $this->ensureDbConnection();
        $this->db->table('ai_analyst_messages')->insert([
            'conversation_id' => $conversationId,
            'role'            => 'assistant',
            'content'         => $assistantReply,
            'metrics_called'  => json_encode($metricsCalled),
            'chart_config'    => json_encode($chartConfig),
            'created_at'      => date('Y-m-d H:i:s')
        ]);

        return $this->response->setJSON([
            'status'          => true,
            'conversation_id' => $conversationId,
            'reply'           => $assistantReply,
            'metrics_called'  => $metricsCalled,
            'chart_config'    => $chartConfig
        ]);
    }

    /**
     * Stream real-time Server-Sent Events (SSE) chat tokens to frontend.
     */
    public function streamMessage()
    {
        if (function_exists('apache_setenv')) {
            @apache_setenv('no-gzip', 1);
        }
        @ini_set('zlib.output_compression', 'Off');
        @ini_set('implicit_flush', 1);
        while (ob_get_level() > 0) {
            ob_end_flush();
        }
        flush();

        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header('Connection: keep-alive');
        header('X-Accel-Buffering: no');

        $adminId        = $this->getAdminId();
        $conversationId = (int) ($this->request->getGet('conversation_id') ?: $this->request->getPost('conversation_id'));
        $userMessage    = trim((string) ($this->request->getGet('message') ?: $this->request->getPost('message')));

        if (empty($userMessage)) {
            echo "event: error\ndata: " . json_encode(['message' => 'Please provide a question.']) . "\n\n";
            flush();
            exit;
        }

        $this->ensureTablesExist();

        if (!$conversationId) {
            $title = mb_substr($userMessage, 0, 40) . (mb_strlen($userMessage) > 40 ? '...' : '');
            $this->db->table('ai_analyst_conversations')->insert([
                'admin_id'   => $adminId,
                'title'      => $title,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            $conversationId = $this->db->insertID();
        }

        echo "event: meta\ndata: " . json_encode(['conversation_id' => $conversationId]) . "\n\n";
        flush();

        // 1. Log User Message
        $this->db->table('ai_analyst_messages')->insert([
            'conversation_id' => $conversationId,
            'role'            => 'user',
            'content'         => $userMessage,
            'created_at'      => date('Y-m-d H:i:s')
        ]);
        $userMsgId = $this->db->insertID();

        // 2. Load conversation history
        $rawHistory = $this->db->table('ai_analyst_messages')
            ->where('conversation_id', $conversationId)
            ->orderBy('id', 'ASC')
            ->limit(10)
            ->get()
            ->getResultArray();

        // 3. Stream from Gemini Analyst
        $analyst = new GeminiAnalyst();
        $res     = $analyst->askStream($userMessage, $rawHistory, $userMsgId, function ($chunk) {
            echo "event: token\ndata: " . json_encode(['text' => $chunk]) . "\n\n";
            flush();
        });

        $assistantReply = $res['reply'] ?? 'Unable to process request.';
        $metricsCalled  = $res['metrics'] ?? [];
        $chartConfig    = $res['chart'] ?? null;

        // 4. Log Assistant Response
        $this->ensureDbConnection();
        $this->db->table('ai_analyst_messages')->insert([
            'conversation_id' => $conversationId,
            'role'            => 'assistant',
            'content'         => $assistantReply,
            'metrics_called'  => !empty($metricsCalled) ? json_encode($metricsCalled) : null,
            'chart_config'    => !empty($chartConfig) ? json_encode($chartConfig) : null,
            'created_at'      => date('Y-m-d H:i:s')
        ]);

        echo "event: done\ndata: " . json_encode([
            'conversation_id' => $conversationId,
            'metrics_called'  => $metricsCalled,
            'chart_config'    => $chartConfig
        ]) . "\n\n";
        flush();
        exit;
    }

    /**
     * Get list of conversations for current admin.
     */
    public function getConversations()
    {
        $adminId = $this->getAdminId();
        $this->ensureTablesExist();

        $conversations = $this->db->table('ai_analyst_conversations')
            ->where('is_deleted', 0)
            ->groupStart()
            ->where('admin_id', $adminId)
            ->orWhere('admin_id', 1)
            ->groupEnd()
            ->orderBy('id', 'DESC')
            ->get()
            ->getResultArray();

        return $this->response->setJSON(['status' => true, 'conversations' => $conversations]);
    }

    /**
     * Get messages for a given conversation ID.
     */
    public function getMessages($conversationId)
    {
        $this->ensureTablesExist();

        $messages = $this->db->table('ai_analyst_messages')
            ->where('conversation_id', (int) $conversationId)
            ->orderBy('id', 'ASC')
            ->get()
            ->getResultArray();

        // Decode JSON fields
        foreach ($messages as &$msg) {
            $msg['metrics_called'] = json_decode($msg['metrics_called'] ?? '[]', true);
            $msg['chart_config']   = json_decode($msg['chart_config'] ?? 'null', true);
        }

        return $this->response->setJSON(['status' => true, 'messages' => $messages]);
    }

    /**
     * Start a new conversation session.
     */
    public function newConversation()
    {
        $adminId = $this->getAdminId();
        $this->ensureTablesExist();

        $this->db->table('ai_analyst_conversations')->insert([
            'admin_id'   => $adminId,
            'title'      => 'New Business Inquiry',
            'created_at' => date('Y-m-d H:i:s')
        ]);
        $id = $this->db->insertID();

        return $this->response->setJSON(['status' => true, 'conversation_id' => $id]);
    }

    /**
     * Soft delete a conversation session.
     */
    public function deleteConversation($id)
    {
        $adminId = $this->getAdminId();
        $this->ensureTablesExist();

        $this->db->table('ai_analyst_conversations')
            ->where('id', (int) $id)
            ->update(['is_deleted' => 1]);

        return $this->response->setJSON(['status' => true, 'message' => 'Conversation deleted.']);
    }

    /**
     * Reconnect MySQL if idle connection timed out during external API calls.
     */
    private function ensureDbConnection(): void
    {
        try {
            if (empty($this->db->connID) || !@mysqli_ping($this->db->connID)) {
                $this->db->reconnect();
            }
        } catch (\Throwable $e) {
            $this->db->reconnect();
        }
    }

    /**
     * Auto-migrate tables if missing.
     */
    private function ensureTablesExist()
    {
        $this->ensureDbConnection();
        if (!$this->db->tableExists('ai_analyst_conversations')) {
            $sql = file_get_contents(APPPATH . 'Database/ai_analyst_migration.sql');
            if ($sql) {
                $statements = array_filter(array_map('trim', explode(';', $sql)));
                foreach ($statements as $stmt) {
                    if (!empty($stmt)) {
                        try {
                            $this->db->query($stmt);
                        } catch (\Throwable $e) {
                            log_message('error', 'Auto migration query error: ' . $e->getMessage());
                        }
                    }
                }
            }
        }

        // Always ensure v_bookings_overview uses explicit utf8mb4_unicode_ci collation to prevent MySQL 9+ collation mismatch errors
        try {
            $this->db->query("CREATE OR REPLACE VIEW v_bookings_overview AS
            SELECT 
              CAST('walking' AS CHAR) COLLATE utf8mb4_unicode_ci AS service_type,
              CAST(wsb.id AS CHAR) COLLATE utf8mb4_unicode_ci AS booking_id,
              wsb.user_id,
              CAST(CONCAT('***', RIGHT(COALESCE(u.phone, '0000'), 4)) AS CHAR) COLLATE utf8mb4_unicode_ci AS user_phone_masked,
              CAST(u.name AS CHAR) COLLATE utf8mb4_unicode_ci AS user_name,
              CAST(wsb.status AS CHAR) COLLATE utf8mb4_unicode_ci AS status,
              CAST(wsb.type AS CHAR) COLLATE utf8mb4_unicode_ci AS booking_type,
              wsb.service_start_date,
              wsb.service_end_date,
              COALESCE(wsb.final_payable, wsb.total_price, 0) AS total_amount,
              CAST(wsb.payment_status AS CHAR) COLLATE utf8mb4_unicode_ci AS payment_status,
              NULL AS payment_mode,
              CAST(ua.city AS CHAR) COLLATE utf8mb4_unicode_ci AS city,
              CAST(ua.state AS CHAR) COLLATE utf8mb4_unicode_ci AS state,
              CAST(s.name AS CHAR) COLLATE utf8mb4_unicode_ci AS service_name,
              wsb.created_at,
              wsb.updated_at,
              CASE WHEN wsb.is_deleted = 1 THEN 1 ELSE 0 END AS is_deleted
            FROM walking_service_bookings wsb
            LEFT JOIN users u ON u.id = wsb.user_id
            LEFT JOIN user_addresses ua ON ua.id = wsb.address_id
            LEFT JOIN services s ON s.id = wsb.service_id

            UNION ALL

            SELECT 
              CAST('grooming' AS CHAR) COLLATE utf8mb4_unicode_ci AS service_type,
              CAST(gsb.id AS CHAR) COLLATE utf8mb4_unicode_ci AS booking_id,
              gsb.user_id,
              CAST(CONCAT('***', RIGHT(COALESCE(u.phone, '0000'), 4)) AS CHAR) COLLATE utf8mb4_unicode_ci AS user_phone_masked,
              CAST(u.name AS CHAR) COLLATE utf8mb4_unicode_ci AS user_name,
              CAST(gsb.status AS CHAR) COLLATE utf8mb4_unicode_ci AS status,
              CAST(gsb.service_mode AS CHAR) COLLATE utf8mb4_unicode_ci AS booking_type,
              gsb.service_start_date,
              NULL AS service_end_date,
              COALESCE(gsb.final_payable, gsb.total_price, 0) AS total_amount,
              CAST(gsb.payment_status AS CHAR) COLLATE utf8mb4_unicode_ci AS payment_status,
              NULL AS payment_mode,
              CAST(ua.city AS CHAR) COLLATE utf8mb4_unicode_ci AS city,
              CAST(ua.state AS CHAR) COLLATE utf8mb4_unicode_ci AS state,
              CAST(s.name AS CHAR) COLLATE utf8mb4_unicode_ci AS service_name,
              gsb.created_at,
              gsb.updated_at,
              CASE WHEN gsb.is_deleted = 1 THEN 1 ELSE 0 END AS is_deleted
            FROM grooming_service_bookings gsb
            LEFT JOIN users u ON u.id = gsb.user_id
            LEFT JOIN user_addresses ua ON ua.id = gsb.address_id
            LEFT JOIN services s ON s.id = gsb.service_id

            UNION ALL

            SELECT 
              CAST('training' AS CHAR) COLLATE utf8mb4_unicode_ci AS service_type,
              CAST(tsb.id AS CHAR) COLLATE utf8mb4_unicode_ci AS booking_id,
              tsb.user_id,
              CAST(CONCAT('***', RIGHT(COALESCE(u.phone, '0000'), 4)) AS CHAR) COLLATE utf8mb4_unicode_ci AS user_phone_masked,
              CAST(u.name AS CHAR) COLLATE utf8mb4_unicode_ci AS user_name,
              CAST(tsb.status AS CHAR) COLLATE utf8mb4_unicode_ci AS status,
              CAST(tsb.type AS CHAR) COLLATE utf8mb4_unicode_ci AS booking_type,
              tsb.service_start_date,
              NULL AS service_end_date,
              COALESCE(tsb.final_payable, tsb.total_price, 0) AS total_amount,
              CAST(tsb.payment_status AS CHAR) COLLATE utf8mb4_unicode_ci AS payment_status,
              NULL AS payment_mode,
              CAST(ua.city AS CHAR) COLLATE utf8mb4_unicode_ci AS city,
              CAST(ua.state AS CHAR) COLLATE utf8mb4_unicode_ci AS state,
              CAST(s.name AS CHAR) COLLATE utf8mb4_unicode_ci AS service_name,
              tsb.created_at,
              tsb.updated_at,
              CASE WHEN tsb.is_deleted = 1 THEN 1 ELSE 0 END AS is_deleted
            FROM training_service_bookings tsb
            LEFT JOIN users u ON u.id = tsb.user_id
            LEFT JOIN user_addresses ua ON ua.id = tsb.address_id
            LEFT JOIN services s ON s.id = tsb.service_id

            UNION ALL

            SELECT 
              CAST('boarding' AS CHAR) COLLATE utf8mb4_unicode_ci AS service_type,
              CAST(bsb.id AS CHAR) COLLATE utf8mb4_unicode_ci AS booking_id,
              bsb.user_id,
              CAST(CONCAT('***', RIGHT(COALESCE(u.phone, '0000'), 4)) AS CHAR) COLLATE utf8mb4_unicode_ci AS user_phone_masked,
              CAST(u.name AS CHAR) COLLATE utf8mb4_unicode_ci AS user_name,
              CAST(bsb.status AS CHAR) COLLATE utf8mb4_unicode_ci AS status,
              CAST('boarding' AS CHAR) COLLATE utf8mb4_unicode_ci AS booking_type,
              bsb.service_start_date,
              bsb.service_end_date,
              COALESCE(bsb.total_price, 0) AS total_amount,
              NULL AS payment_status,
              NULL AS payment_mode,
              CAST(ua.city AS CHAR) COLLATE utf8mb4_unicode_ci AS city,
              CAST(ua.state AS CHAR) COLLATE utf8mb4_unicode_ci AS state,
              CAST(s.name AS CHAR) COLLATE utf8mb4_unicode_ci AS service_name,
              bsb.created_at,
              bsb.updated_at,
              CASE WHEN bsb.is_deleted IS NOT NULL THEN 1 ELSE 0 END AS is_deleted
            FROM boarding_service_bookings bsb
            LEFT JOIN users u ON u.id = bsb.user_id
            LEFT JOIN user_addresses ua ON ua.id = bsb.address_id
            LEFT JOIN services s ON s.id = bsb.service_id

            UNION ALL

            SELECT 
              CAST('sitting' AS CHAR) COLLATE utf8mb4_unicode_ci AS service_type,
              CAST(ssb.id AS CHAR) COLLATE utf8mb4_unicode_ci AS booking_id,
              ssb.user_id,
              CAST(CONCAT('***', RIGHT(COALESCE(u.phone, '0000'), 4)) AS CHAR) COLLATE utf8mb4_unicode_ci AS user_phone_masked,
              CAST(u.name AS CHAR) COLLATE utf8mb4_unicode_ci AS user_name,
              CAST(ssb.status AS CHAR) COLLATE utf8mb4_unicode_ci AS status,
              CAST(ssb.type AS CHAR) COLLATE utf8mb4_unicode_ci AS booking_type,
              ssb.service_start_date,
              NULL AS service_end_date,
              COALESCE(ssb.final_payable, ssb.total_price, 0) AS total_amount,
              CAST(ssb.payment_status AS CHAR) COLLATE utf8mb4_unicode_ci AS payment_status,
              NULL AS payment_mode,
              CAST(ua.city AS CHAR) COLLATE utf8mb4_unicode_ci AS city,
              CAST(ua.state AS CHAR) COLLATE utf8mb4_unicode_ci AS state,
              CAST(s.name AS CHAR) COLLATE utf8mb4_unicode_ci AS service_name,
              ssb.created_at,
              ssb.updated_at,
              CASE WHEN ssb.is_deleted = 1 THEN 1 ELSE 0 END AS is_deleted
            FROM sitting_service_bookings ssb
            LEFT JOIN users u ON u.id = ssb.user_id
            LEFT JOIN user_addresses ua ON ua.id = ssb.address_id
            LEFT JOIN services s ON s.id = ssb.service_id");
        } catch (\Throwable $e) {
            log_message('error', 'Failed to update v_bookings_overview view collation: ' . $e->getMessage());
        }
    }

    /**
     * Render the Chat History view for chatbot conversations.
     */
    public function chatHistory()
    {
        $this->ensureDbConnection();

        $conversations = [];
        if ($this->db->tableExists('chatbot_logs')) {
            $sessions = $this->db->table('chatbot_logs')
                ->select('chatbot_logs.session_id as id, MAX(chatbot_logs.created_at) as last_message_at, MAX(users.name) as user_name, MAX(users.phone) as phone')
                ->join('users', 'users.id = chatbot_logs.user_id', 'left')
                ->groupBy('chatbot_logs.session_id')
                ->orderBy('last_message_at', 'DESC')
                ->get()
                ->getResultArray();

            foreach ($sessions as $session) {
                $latest = $this->db->table('chatbot_logs')
                    ->where('session_id', $session['id'])
                    ->orderBy('created_at', 'DESC')
                    ->limit(1)
                    ->get()
                    ->getRowArray();

                $conversations[] = [
                    'id'              => $session['id'],
                    'user_name'       => $session['user_name'] ?: 'Guest User',
                    'phone'           => $session['phone'] ?: '',
                    'last_message_at' => $session['last_message_at'],
                    'last_message'    => $latest ? $latest['message'] : '',
                    'call_id'         => null
                ];
            }
        }

        $data['conversations']   = $conversations;
        $data['theme']           = 'Insurance';
        $data['current_section'] = 'chat_history';
        $data['title']           = 'Chat History';

        return $this->loadView('admin/chat_history_view', $data);
    }

    /**
     * Fetch messages for a chatbot session ID.
     */
    public function chatMessages($sessionId)
    {
        $this->ensureDbConnection();

        if (!$this->db->tableExists('chatbot_logs')) {
            return $this->response->setJSON([
                'status' => 'success',
                'data'   => [
                    'user'     => ['user_name' => 'Guest User', 'user_phone' => ''],
                    'messages' => []
                ]
            ]);
        }

        $messagesData = $this->db->table('chatbot_logs')
            ->where('session_id', $sessionId)
            ->orderBy('created_at', 'ASC')
            ->get()
            ->getResultArray();

        $sessionUser = $this->db->table('chatbot_logs')
            ->select('users.name as user_name, users.phone')
            ->join('users', 'users.id = chatbot_logs.user_id', 'left')
            ->where('chatbot_logs.session_id', $sessionId)
            ->where('chatbot_logs.user_id IS NOT NULL', null, false)
            ->limit(1)
            ->get()
            ->getRowArray();

        $messages = [];
        foreach ($messagesData as $msg) {
            $messages[] = [
                'sender'     => $msg['role'] === 'model' ? 'bot' : 'user',
                'message'    => $msg['message'],
                'created_at' => $msg['created_at']
            ];
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => [
                'user' => [
                    'user_name'  => (!empty($sessionUser['user_name'])) ? $sessionUser['user_name'] : 'Guest User',
                    'user_phone' => (!empty($sessionUser['phone'])) ? $sessionUser['phone'] : ''
                ],
                'messages' => $messages
            ]
        ]);
    }

    /**
     * Unprotected diagnostic endpoint to verify AI Analyst metrics.
     */
    public function mongoDiagnostic()
    {
        $mongo = new \App\Libraries\AIAnalyst\MongoDBClient();
        $mysql = new \App\Libraries\AIAnalyst\MySQLMetrics();

        return $this->response->setJSON([
            'status'            => 'ok',
            'mongo_connected'   => $mongo->isConnected(),
            'executive_summary' => $mysql->executive_summary('30_days')
        ]);
    }
}
