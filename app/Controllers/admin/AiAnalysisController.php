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
<<<<<<< HEAD
=======
    private static bool $tablesEnsured = false;
>>>>>>> origin/main

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
<<<<<<< HEAD
=======
            'credits'       => $this->getCreditsData($adminId),
>>>>>>> origin/main
            'title'         => 'AI Business Analyst'
        ];

        return $this->loadView('admin/ai_analyst', $data);
    }

    /**
<<<<<<< HEAD
=======
     * Render the Insurance Chat History View.
     * Loads live insurance conversations from `conversations` (Voice / AI Call Agent)
     * and `chatbot_logs` (Web / Mobile Insurance Chatbot).
     */
    public function chatHistory()
    {
        $allConversations = [];

        // 1. Fetch AI Voice / Call Agent Conversations from `conversations` & `messages`
        if ($this->db->tableExists('conversations')) {
            $voiceConvs = $this->db->table('conversations c')
                ->select("
                    c.id,
                    c.call_id,
                    c.user_id,
                    c.type,
                    c.status,
                    c.created_at,
                    c.updated_at,
                    u.name as u_name,
                    u.phone as u_phone,
                    u.email as u_email,
                    iu.full_name as iu_name,
                    iu.phone as iu_phone
                ")
                ->join('users u', 'u.id = c.user_id', 'left')
                ->join('insurance_users iu', 'iu.user_id = c.user_id OR iu.id = c.user_id', 'left')
                ->orderBy('c.id', 'DESC')
                ->get()
                ->getResultArray();

            foreach ($voiceConvs as $vc) {
                $rawMessages = [];
                if ($this->db->tableExists('messages')) {
                    $rawMessages = $this->db->table('messages')
                        ->where('conversation_id', $vc['id'])
                        ->orderBy('id', 'ASC')
                        ->get()
                        ->getResultArray();
                }

                $lastMsg = !empty($rawMessages) ? end($rawMessages) : null;
                $details = $this->extractInsuranceDetails($vc, $rawMessages);

                // Check for linked insurance policy
                $policy = null;
                if ($this->db->tableExists('insurance') && !empty($vc['user_id'])) {
                    $policy = $this->db->table('insurance')
                        ->where('insurance_user_id', (int) $vc['user_id'])
                        ->orderBy('id', 'DESC')
                        ->get()
                        ->getRowArray();
                }

                $allConversations[] = [
                    'id'              => (string) $vc['id'],
                    'numeric_id'      => (int) $vc['id'],
                    'call_id'         => $vc['call_id'],
                    'user_name'       => $details['title'],
                    'user_phone'      => $vc['u_phone'] ?: ($vc['iu_phone'] ?: (!empty($vc['call_id']) ? "Call ID: {$vc['call_id']}" : 'Insurance Voice Agent')),
                    'pet_name'        => $details['pet_name'] ?: null,
                    'pet_type'        => $details['pet_type'] ?: null,
                    'coverage_type'   => $details['plan'] ?: ($policy['coverage_type'] ?? null),
                    'policy_number'   => $policy['policy_number'] ?? null,
                    'last_message'    => $lastMsg['message'] ?? 'Pet Insurance Voice Consultation',
                    'last_message_at' => $lastMsg['created_at'] ?? $vc['created_at'],
                    'created_at'      => $vc['created_at'],
                    'status'          => $vc['status'] ?? 'completed',
                    'channel'         => 'voice',
                    'channel_badge'   => 'Voice Agent',
                    'avatar_bg'       => '#ede9fe',
                    'avatar_color'    => '#6366f1'
                ];
            }
        }

        // 2. Fetch Web / Mobile Chatbot Sessions from `chatbot_logs`
        if ($this->db->tableExists('chatbot_logs')) {
            $sessions = $this->db->table('chatbot_logs')
                ->select('session_id, MAX(user_id) as user_id, MIN(created_at) as started_at, MAX(created_at) as last_message_at, COUNT(*) as msg_count')
                ->where('session_id IS NOT NULL')
                ->where('session_id !=', '')
                ->groupBy('session_id')
                ->orderBy('last_message_at', 'DESC')
                ->get()
                ->getResultArray();

            foreach ($sessions as $s) {
                $lastLog = $this->db->table('chatbot_logs')
                    ->where('session_id', $s['session_id'])
                    ->orderBy('id', 'DESC')
                    ->limit(1)
                    ->get()
                    ->getRowArray();

                // Look up user info from users table if user_id is available
                $userRow = null;
                if (!empty($s['user_id']) && $this->db->tableExists('users')) {
                    $userRow = $this->db->table('users')
                        ->select('name, phone, email')
                        ->where('id', (int) $s['user_id'])
                        ->get()
                        ->getRowArray();
                }

                // Check session state for collected insurance parameters
                $stateRow = null;
                if ($this->db->tableExists('chatbot_session_state')) {
                    $stateRow = $this->db->table('chatbot_session_state')
                        ->where('session_id', $s['session_id'])
                        ->get()
                        ->getRowArray();
                }

                $petName       = $stateRow['pet_name'] ?? null;
                $coverageType  = $stateRow['coverage_type'] ?? null;
                $durationYears = $stateRow['duration_years'] ?? null;
                $flow          = $stateRow['flow'] ?? null;

                // Check for linked insurance policy
                $policy = null;
                if ($this->db->tableExists('insurance') && !empty($s['user_id'])) {
                    $policy = $this->db->table('insurance')
                        ->where('insurance_user_id', (int) $s['user_id'])
                        ->orderBy('id', 'DESC')
                        ->get()
                        ->getRowArray();
                }

                $clientName = !empty($userRow['name']) ? trim($userRow['name']) : '';
                if (!empty($clientName) && !empty($petName)) {
                    $displayName = $clientName . ' • ' . $petName;
                } elseif (!empty($clientName)) {
                    $displayName = $clientName . ' • Insurance Chat';
                } elseif (!empty($petName)) {
                    $displayName = 'Pet: ' . $petName . ($coverageType ? " ({$coverageType})" : '');
                } else {
                    $displayName = 'Online Visitor #' . substr(abs(crc32($s['session_id'])), 0, 4);
                }

                $phone = !empty($userRow['phone']) ? $userRow['phone'] : 'Web / App Chatbot';

                $allConversations[] = [
                    'id'              => 'cb_' . $s['session_id'],
                    'numeric_id'      => 0,
                    'call_id'         => null,
                    'session_id'      => $s['session_id'],
                    'user_name'       => $displayName,
                    'user_phone'      => $phone,
                    'pet_name'        => $petName,
                    'coverage_type'   => $coverageType ?: ($policy['coverage_type'] ?? null),
                    'duration_years'  => $durationYears ?: ($policy['duration_years'] ?? null),
                    'policy_number'   => $policy['policy_number'] ?? null,
                    'flow'            => $flow,
                    'last_message'    => $lastLog['message'] ?? 'Pet Insurance Chat Inquiry',
                    'last_message_at' => $s['last_message_at'] ?? $s['started_at'],
                    'created_at'      => $s['started_at'],
                    'status'          => 'completed',
                    'channel'         => 'chat',
                    'channel_badge'   => 'Web Chat',
                    'avatar_bg'       => '#dcfce7',
                    'avatar_color'    => '#16a34a'
                ];
            }
        }

        // Deduplicate conversations strictly by unique 'id'
        $uniqueConvs = [];
        foreach ($allConversations as $c) {
            $cId = $c['id'];
            if (!isset($uniqueConvs[$cId])) {
                $uniqueConvs[$cId] = $c;
            } else {
                // If existing was anonymous and current has identified user, prioritize the identified one
                if (str_contains($uniqueConvs[$cId]['user_name'], 'Online Visitor') && !str_contains($c['user_name'], 'Online Visitor')) {
                    $uniqueConvs[$cId] = $c;
                }
            }
        }
        $allConversations = array_values($uniqueConvs);

        // Sort all conversations descending by latest message timestamp
        usort($allConversations, function ($a, $b) {
            $tA = strtotime($a['last_message_at'] ?? $a['created_at'] ?? '2000-01-01');
            $tB = strtotime($b['last_message_at'] ?? $b['created_at'] ?? '2000-01-01');
            return $tB <=> $tA;
        });

        $data = [
            'conversations' => $allConversations,
            'title'         => 'Insurance Chat History'
        ];

        return $this->loadView('admin/voice-agent/chat_history_view', $data);
    }

    /**
     * Fetch messages for a given insurance conversation session.
     * Supports both `conversations.id` (Voice Agent) and `chatbot_logs.session_id` (Web Chatbot).
     */
    public function getInsuranceMessages($id)
    {
        $id = trim((string) $id);
        $messages = [];
        $user = [
            'user_name'  => 'Insurance Lead',
            'user_phone' => 'Pet Insurance Inquiry',
            'channel'    => 'Chat'
        ];

        if (str_starts_with($id, 'cb_')) {
            $sessionId = substr($id, 3);
            if ($this->db->tableExists('chatbot_logs')) {
                $logs = $this->db->table('chatbot_logs l')
                    ->select('l.*, u.name as u_name, u.phone as u_phone, u.email as u_email')
                    ->join('users u', 'u.id = l.user_id', 'left')
                    ->where('l.session_id', $sessionId)
                    ->orderBy('l.id', 'ASC')
                    ->get()
                    ->getResultArray();

                // Locate user info from any message log in this session
                $effectiveUserId = null;
                $effectiveUserName = null;
                $effectiveUserPhone = null;

                foreach ($logs as $l) {
                    if (!empty($l['user_id'])) {
                        $effectiveUserId = (int) $l['user_id'];
                    }
                    if (!empty($l['u_name']) && !$effectiveUserName) {
                        $effectiveUserName = trim($l['u_name']);
                    }
                    if (!empty($l['u_phone']) && !$effectiveUserPhone) {
                        $effectiveUserPhone = trim($l['u_phone']);
                    }
                }

                if ($effectiveUserId && (!$effectiveUserName || !$effectiveUserPhone) && $this->db->tableExists('users')) {
                    $uRow = $this->db->table('users')->select('name, phone')->where('id', $effectiveUserId)->get()->getRowArray();
                    if ($uRow) {
                        if (!$effectiveUserName && !empty($uRow['name'])) $effectiveUserName = trim($uRow['name']);
                        if (!$effectiveUserPhone && !empty($uRow['phone'])) $effectiveUserPhone = trim($uRow['phone']);
                    }
                }

                $firstLog  = $logs[0] ?? [];
                $stateRow  = null;
                if ($this->db->tableExists('chatbot_session_state')) {
                    $stateRow = $this->db->table('chatbot_session_state')
                        ->where('session_id', $sessionId)
                        ->get()
                        ->getRowArray();
                }

                $petName       = $stateRow['pet_name'] ?? null;
                $coverageType  = $stateRow['coverage_type'] ?? null;
                $durationYears = $stateRow['duration_years'] ?? null;

                $policy = null;
                if ($this->db->tableExists('insurance') && !empty($effectiveUserId)) {
                    $policy = $this->db->table('insurance')
                        ->where('insurance_user_id', $effectiveUserId)
                        ->orderBy('id', 'DESC')
                        ->get()
                        ->getRowArray();
                }

                $clientName = $effectiveUserName ?: '';
                if (!empty($clientName) && !empty($petName)) {
                    $userName = $clientName . ' • ' . $petName;
                } elseif (!empty($clientName)) {
                    $userName = $clientName . ' • Web Chat';
                } elseif (!empty($petName)) {
                    $userName = 'Pet: ' . $petName;
                } else {
                    $userName = 'Online Visitor #' . substr(abs(crc32($sessionId)), 0, 4);
                }

                $userPhone = $effectiveUserPhone ?: 'Web / Mobile Chatbot';

                foreach ($logs as $log) {
                    $isBot = in_array(strtolower($log['role']), ['model', 'assistant', 'bot']);
                    $messages[] = [
                        'id'         => $log['id'],
                        'sender'     => $isBot ? 'bot' : 'user',
                        'role'       => $isBot ? 'assistant' : 'user',
                        'message'    => $log['message'],
                        'content'    => $log['message'],
                        'created_at' => $log['created_at']
                    ];
                }

                $lastLog = !empty($logs) ? end($logs) : null;
                $user = [
                    'user_name'       => $userName,
                    'user_phone'      => $userPhone,
                    'channel'         => 'Web Chatbot',
                    'session_id'      => $sessionId,
                    'pet_name'        => $petName,
                    'coverage_type'   => $coverageType ?: ($policy['coverage_type'] ?? null),
                    'duration_years'  => $durationYears ?: ($policy['duration_years'] ?? null),
                    'policy_number'   => $policy['policy_number'] ?? null,
                    'policy_status'   => $policy['status'] ?? null,
                    'created_at'      => $firstLog['created_at'] ?? null,
                    'last_message_at' => $lastLog['created_at'] ?? ($firstLog['created_at'] ?? null)
                ];
            }
        } else {
            $convId = (int) $id;
            if ($this->db->tableExists('conversations')) {
                $conv = $this->db->table('conversations c')
                    ->select("
                        c.*,
                        u.name as u_name,
                        u.phone as u_phone,
                        u.email as u_email,
                        iu.full_name as iu_name,
                        iu.phone as iu_phone
                    ")
                    ->join('users u', 'u.id = c.user_id', 'left')
                    ->join('insurance_users iu', 'iu.user_id = c.user_id OR iu.id = c.user_id', 'left')
                    ->where('c.id', $convId)
                    ->get()
                    ->getRowArray();

                $rawMessages = [];
                if ($this->db->tableExists('messages')) {
                    $rawMessages = $this->db->table('messages')
                        ->where('conversation_id', $convId)
                        ->orderBy('id', 'ASC')
                        ->get()
                        ->getResultArray();
                }

                foreach ($rawMessages as $m) {
                    $isBot = in_array(strtolower($m['sender']), ['bot', 'agent', 'assistant']);
                    $messages[] = [
                        'id'         => $m['id'],
                        'sender'     => $isBot ? 'bot' : 'user',
                        'role'       => $isBot ? 'assistant' : 'user',
                        'message'    => $m['message'],
                        'content'    => $m['message'],
                        'created_at' => $m['created_at']
                    ];
                }

                $details = $this->extractInsuranceDetails($conv, $rawMessages);
                $phone = $conv['u_phone'] ?? ($conv['iu_phone'] ?? (!empty($conv['call_id']) ? "Call ID: {$conv['call_id']}" : 'Insurance Voice Agent'));

                $policy = null;
                if ($this->db->tableExists('insurance') && !empty($conv['user_id'])) {
                    $policy = $this->db->table('insurance')
                        ->where('insurance_user_id', (int) $conv['user_id'])
                        ->orderBy('id', 'DESC')
                        ->get()
                        ->getRowArray();
                }

                $lastRaw = !empty($rawMessages) ? end($rawMessages) : null;
                $user = [
                    'user_name'       => $details['title'],
                    'user_phone'      => $phone,
                    'call_id'         => $conv['call_id'] ?? null,
                    'channel'         => 'Voice Agent',
                    'pet_name'        => $details['pet_name'] ?: null,
                    'pet_type'        => $details['pet_type'] ?: null,
                    'coverage_type'   => $details['plan'] ?: ($policy['coverage_type'] ?? null),
                    'policy_number'   => $policy['policy_number'] ?? null,
                    'policy_status'   => $policy['status'] ?? null,
                    'created_at'      => $conv['created_at'] ?? null,
                    'last_message_at' => $lastRaw['created_at'] ?? ($conv['created_at'] ?? null)
                ];
            }
        }

        return $this->response->setJSON([
            'status'   => 'success',
            'success'  => true,
            'data'     => [
                'user'     => $user,
                'messages' => $messages
            ],
            'messages' => $messages
        ]);
    }

    /**
     * Smart title and metadata extractor for insurance conversations based on pet and client answers.
     */
    private function extractInsuranceDetails(?array $conv, array $messages): array
    {
        $userName = trim($conv['u_name'] ?? ($conv['iu_name'] ?? ''));
        $petName  = trim($conv['pet_name'] ?? '');
        $petType  = '';
        $plan     = '';

        foreach ($messages as $m) {
            $text = $m['message'] ?? '';
            if (empty($userName) && preg_match('/I am\s+([a-zA-Z]+)/i', $text, $matches)) {
                $userName = ucfirst($matches[1]);
            }
            if (empty($petName) && preg_match('/pet name is\s+([a-zA-Z0-9\s]+)/i', $text, $matches)) {
                $petName = trim($matches[1]);
            }
            if (stripos($text, 'Standard') !== false) {
                $plan = 'Standard';
            } elseif (stripos($text, 'Premium') !== false) {
                $plan = 'Premium';
            }
        }

        for ($i = 0; $i < count($messages) - 1; $i++) {
            $cur = $messages[$i]['message'] ?? '';
            $nxt = $messages[$i + 1]['message'] ?? '';
            if (empty($petName) && (stripos($cur, "What is your pet's name") !== false || stripos($cur, "pet's name") !== false) && ($messages[$i]['sender'] ?? '') === 'bot') {
                $candidate = trim(preg_replace('/[^a-zA-Z0-9\s]/', '', $nxt));
                if (!empty($candidate) && strlen($candidate) < 25) {
                    $petName = ucwords($candidate);
                }
            }
            if (stripos($cur, "dog, cat") !== false || stripos($cur, "dog or cat") !== false) {
                if (stripos($nxt, 'dog') !== false) $petType = 'Dog';
                elseif (stripos($nxt, 'cat') !== false) $petType = 'Cat';
            }
        }

        $title = 'Insurance Consultation';
        if (!empty($userName) && !empty($petName)) {
            $title = $userName . ' • ' . $petName . ($petType ? " ({$petType})" : '');
        } elseif (!empty($userName)) {
            $title = $userName . ' • Insurance Consultation';
        } elseif (!empty($petName)) {
            $title = 'Pet: ' . $petName . ($petType ? " ({$petType})" : '');
        } elseif (!empty($conv['call_id'])) {
            $title = 'Insurance Call #' . substr($conv['call_id'], -6);
        } elseif (!empty($conv['id'])) {
            $title = 'Insurance Consultation #' . $conv['id'];
        }

        return [
            'title'     => $title,
            'user_name' => $userName,
            'pet_name'  => $petName,
            'pet_type'  => $petType,
            'plan'      => $plan
        ];
    }

    private function detectInsuranceConvTitle(?array $conv, array $messages): string
    {
        $details = $this->extractInsuranceDetails($conv, $messages);
        return $details['title'];
    }

    /**
>>>>>>> origin/main
     * Send a user query to Gemini Analyst and return response JSON.
     */
    public function sendMessage()
    {
        $adminId        = $this->getAdminId();
        $conversationId = (int) $this->request->getPost('conversation_id');
        $userMessage    = trim((string) $this->request->getPost('message'));
<<<<<<< HEAD
=======
        $userMessage    = $this->sanitizeUserPrompt($userMessage);
>>>>>>> origin/main

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

<<<<<<< HEAD
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
=======
        // 2. Load previous conversation history (latest 50 messages excluding current user message for deep context)
        $rawHistory = $this->db->table('ai_analyst_messages')
            ->where('conversation_id', $conversationId)
            ->where('id !=', $userMsgId)
            ->orderBy('id', 'DESC')
            ->limit(50)
            ->get()
            ->getResultArray();
        $rawHistory = array_reverse($rawHistory);

        // 3. Invoke Gemini Analyst
        $replyMode = trim((string)($this->request->getPost('reply_mode') ?: 'detailed'));
        $analyst   = new GeminiAnalyst();
        $res       = $analyst->ask($userMessage, $rawHistory, $userMsgId, $replyMode, $conversationId);
>>>>>>> origin/main

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

<<<<<<< HEAD
=======
        // Deduct 1 AI Credit for prompt invocation
        $updatedCredits = $this->consumeCredit($adminId);

>>>>>>> origin/main
        return $this->response->setJSON([
            'status'          => true,
            'conversation_id' => $conversationId,
            'reply'           => $assistantReply,
            'metrics_called'  => $metricsCalled,
<<<<<<< HEAD
            'chart_config'    => $chartConfig
=======
            'chart_config'    => $chartConfig,
            'credits'         => $updatedCredits
>>>>>>> origin/main
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
<<<<<<< HEAD
=======
        $userMessage    = $this->sanitizeUserPrompt($userMessage);
>>>>>>> origin/main

        if (empty($userMessage)) {
            echo "event: error\ndata: " . json_encode(['message' => 'Please provide a question.']) . "\n\n";
            flush();
            exit;
        }

        $this->ensureTablesExist();

<<<<<<< HEAD
        if (!$conversationId) {
=======
        if (empty($conversationId) || (int)$conversationId === 0) {
>>>>>>> origin/main
            $title = mb_substr($userMessage, 0, 40) . (mb_strlen($userMessage) > 40 ? '...' : '');
            $this->db->table('ai_analyst_conversations')->insert([
                'admin_id'   => $adminId,
                'title'      => $title,
                'created_at' => date('Y-m-d H:i:s')
            ]);
<<<<<<< HEAD
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
=======
            $conversationId = (int) $this->db->insertID();
            if ($conversationId === 0) {
                $row = $this->db->query("SELECT MAX(id) as max_id FROM ai_analyst_conversations")->getRow();
                $conversationId = (int) ($row->max_id ?? 1);
            }
        }

        // Deduct 1 AI Credit for prompt invocation
        $updatedCredits = $this->consumeCredit($adminId);

        echo "event: meta\ndata: " . json_encode(['conversation_id' => $conversationId, 'credits' => $updatedCredits]) . "\n\n";
        flush();

        // 1. Log or Resolve User Message
        $isRetry = (bool) ($this->request->getGet('is_retry') ?: $this->request->getPost('is_retry'));
        $userMsgId = null;

        if ($isRetry && $conversationId > 0) {
            $lastMsg = $this->db->table('ai_analyst_messages')
                ->where('conversation_id', $conversationId)
                ->orderBy('id', 'DESC')
                ->limit(1)
                ->get()
                ->getRowArray();
            if ($lastMsg) {
                if ($lastMsg['role'] === 'user') {
                    // Previous attempt failed before assistant replied: reuse existing user message
                    $userMsgId = (int) $lastMsg['id'];
                } elseif ($lastMsg['role'] === 'assistant') {
                    // Find the preceding user message to reuse
                    $lastUserMsg = $this->db->table('ai_analyst_messages')
                        ->where('conversation_id', $conversationId)
                        ->where('role', 'user')
                        ->orderBy('id', 'DESC')
                        ->limit(1)
                        ->get()
                        ->getRowArray();
                    if ($lastUserMsg) {
                        $userMsgId = (int) $lastUserMsg['id'];
                        // Delete the old assistant response that is being regenerated
                        $this->db->table('ai_analyst_messages')->where('id', (int) $lastMsg['id'])->delete();
                    }
                }
            }
        }

        if (empty($userMsgId)) {
            $this->db->table('ai_analyst_messages')->insert([
                'conversation_id' => $conversationId,
                'role'            => 'user',
                'content'         => $userMessage,
                'created_at'      => date('Y-m-d H:i:s')
            ]);
            $userMsgId = (int) $this->db->insertID();
            if ($userMsgId === 0) {
                $row = $this->db->query("SELECT MAX(id) as max_id FROM ai_analyst_messages")->getRow();
                $userMsgId = (int) ($row->max_id ?? 1);
            }
        }

        // 2. Load previous conversation history (latest 50 messages excluding current user message for deep context)
        $rawHistory = $this->db->table('ai_analyst_messages')
            ->where('conversation_id', $conversationId)
            ->where('id !=', $userMsgId)
            ->orderBy('id', 'DESC')
            ->limit(50)
            ->get()
            ->getResultArray();
        $rawHistory = array_reverse($rawHistory);

        // 3. Stream from Gemini Analyst
        $replyMode = trim((string)($this->request->getGet('reply_mode') ?: $this->request->getPost('reply_mode') ?: 'detailed'));
        try {
            $analyst = new GeminiAnalyst();
            $res     = $analyst->askStream($userMessage, $rawHistory, $userMsgId, function ($chunk) {
                echo "event: token\ndata: " . json_encode(['text' => $chunk]) . "\n\n";
                flush();
            }, $replyMode, $conversationId);

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
        } catch (\Throwable $e) {
            log_message('error', 'AiAnalysisController Stream Error: ' . $e->getMessage());
            $errorMsg = "\n\n⚠️ **Notice**: " . $e->getMessage();
            echo "event: token\ndata: " . json_encode(['text' => $errorMsg]) . "\n\n";
            echo "event: done\ndata: " . json_encode([
                'conversation_id' => $conversationId,
                'metrics_called'  => [],
                'chart_config'    => null,
                'error'           => $e->getMessage()
            ]) . "\n\n";
            flush();
        }
>>>>>>> origin/main
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

<<<<<<< HEAD
=======
        $conversation = $this->db->table('ai_analyst_conversations')
            ->where('id', (int) $conversationId)
            ->get()
            ->getRowArray();

>>>>>>> origin/main
        $messages = $this->db->table('ai_analyst_messages')
            ->where('conversation_id', (int) $conversationId)
            ->orderBy('id', 'ASC')
            ->get()
            ->getResultArray();

<<<<<<< HEAD
        // Decode JSON fields
        foreach ($messages as &$msg) {
            $msg['metrics_called'] = json_decode($msg['metrics_called'] ?? '[]', true);
            $msg['chart_config']   = json_decode($msg['chart_config'] ?? 'null', true);
        }

        return $this->response->setJSON(['status' => true, 'messages' => $messages]);
=======
        $user = [
            'user_name'  => $conversation['title'] ?? 'AI Analyst Conversation',
            'user_phone' => 'AI Business Analyst',
            'title'      => $conversation['title'] ?? 'AI Analyst Conversation'
        ];

        if (!empty($conversation['admin_id'])) {
            $admin = $this->db->table('employees')
                ->select('name, phone, email')
                ->where('id', (int) $conversation['admin_id'])
                ->get()
                ->getRowArray();
            if ($admin) {
                $user['user_name']  = $conversation['title'] ?? $admin['name'];
                $user['user_phone'] = $admin['phone'] ?: ($admin['email'] ?: 'Staff Admin');
                $user['admin_name'] = $admin['name'];
            }
        }

        // Decode JSON fields and provide sender & message aliases for chat history view
        foreach ($messages as &$msg) {
            $msg['metrics_called'] = json_decode($msg['metrics_called'] ?? '[]', true);
            $msg['chart_config']   = json_decode($msg['chart_config'] ?? 'null', true);
            $msg['sender']         = ($msg['role'] === 'assistant' || $msg['role'] === 'bot') ? 'bot' : 'user';
            $msg['message']        = $msg['content'] ?? '';
        }

        return $this->response->setJSON([
            'status'   => 'success',
            'success'  => true,
            'messages' => $messages,
            'data'     => [
                'user'     => $user,
                'messages' => $messages
            ]
        ]);
>>>>>>> origin/main
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
<<<<<<< HEAD
=======
     * Branch an existing inquiry exchange into a brand new conversation thread.
     */
    public function branchConversation()
    {
        $adminId       = $this->getAdminId();
        $userPrompt    = trim((string) $this->request->getPost('user_prompt'));
        $assistantText = trim((string) $this->request->getPost('assistant_text'));
        $metricsCalled = $this->request->getPost('metrics_called');
        $chartConfig   = $this->request->getPost('chart_config');

        if (empty($userPrompt)) {
            return $this->response->setJSON(['status' => false, 'message' => 'Cannot branch empty inquiry.']);
        }

        $this->ensureTablesExist();

        $title = 'Branch: ' . mb_substr($userPrompt, 0, 35) . (mb_strlen($userPrompt) > 35 ? '...' : '');

        $this->db->table('ai_analyst_conversations')->insert([
            'admin_id'   => $adminId,
            'title'      => $title,
            'created_at' => date('Y-m-d H:i:s')
        ]);
        $newConvId = (int) $this->db->insertID();

        // 1. Insert user question
        $this->db->table('ai_analyst_messages')->insert([
            'conversation_id' => $newConvId,
            'role'            => 'user',
            'content'         => $userPrompt,
            'created_at'      => date('Y-m-d H:i:s')
        ]);

        // 2. Insert assistant response if present
        if (!empty($assistantText)) {
            $this->db->table('ai_analyst_messages')->insert([
                'conversation_id' => $newConvId,
                'role'            => 'assistant',
                'content'         => $assistantText,
                'metrics_called'  => !empty($metricsCalled) ? (is_string($metricsCalled) ? $metricsCalled : json_encode($metricsCalled)) : null,
                'chart_config'    => !empty($chartConfig) ? (is_string($chartConfig) ? $chartConfig : json_encode($chartConfig)) : null,
                'created_at'      => date('Y-m-d H:i:s')
            ]);
        }

        return $this->response->setJSON([
            'status'          => true,
            'conversation_id' => $newConvId,
            'title'           => $title
        ]);
    }

    /**
>>>>>>> origin/main
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
<<<<<<< HEAD
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
=======
     * Auto-migrate and ensure tables exist with proper AUTO_INCREMENT primary keys.
     */
    private function ensureTablesExist()
    {
        if (self::$tablesEnsured) {
            return;
        }

        $this->ensureDbConnection();

        $tableSchemas = [
            'ai_analyst_conversations' => "CREATE TABLE IF NOT EXISTS ai_analyst_conversations (
                id INT AUTO_INCREMENT PRIMARY KEY,
                admin_id INT NULL,
                title VARCHAR(255) NOT NULL,
                is_deleted TINYINT(1) DEFAULT 0,
                created_at DATETIME NULL,
                updated_at DATETIME NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

            'ai_analyst_messages' => "CREATE TABLE IF NOT EXISTS ai_analyst_messages (
                id INT AUTO_INCREMENT PRIMARY KEY,
                conversation_id INT NOT NULL,
                role VARCHAR(50) NOT NULL,
                content LONGTEXT NOT NULL,
                metrics_called JSON NULL,
                chart_config JSON NULL,
                created_at DATETIME NULL,
                INDEX (conversation_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

            'ai_analyst_reports' => "CREATE TABLE IF NOT EXISTS ai_analyst_reports (
                id INT AUTO_INCREMENT PRIMARY KEY,
                report_type VARCHAR(100) NOT NULL,
                period_start DATE NULL,
                period_end DATE NULL,
                summary LONGTEXT NOT NULL,
                raw_metrics JSON NULL,
                delivered_via VARCHAR(50) DEFAULT 'web',
                created_at DATETIME NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

            'ai_analyst_credits' => "CREATE TABLE IF NOT EXISTS ai_analyst_credits (
                id INT AUTO_INCREMENT PRIMARY KEY,
                admin_id INT NOT NULL UNIQUE,
                total_credits INT NOT NULL DEFAULT 500,
                credits_used INT NOT NULL DEFAULT 0,
                created_at DATETIME NULL,
                updated_at DATETIME NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        ];

        foreach ($tableSchemas as $tbl => $sql) {
            if (!$this->db->tableExists($tbl)) {
                try {
                    $this->db->query($sql);
                } catch (\Throwable $e) {
                    log_message('error', "Failed to create table {$tbl}: " . $e->getMessage());
                }
            } else {
                // Ensure id column has AUTO_INCREMENT enabled
                try {
                    $zeroRows = $this->db->query("SELECT COUNT(*) as cnt FROM {$tbl} WHERE id = 0")->getRow();
                    if (!empty($zeroRows) && (int)$zeroRows->cnt > 0) {
                        $maxRow = $this->db->query("SELECT COALESCE(MAX(id), 0) as max_id FROM {$tbl}")->getRow();
                        $nextId = ((int)($maxRow->max_id ?? 0)) + 1;
                        $this->db->query("UPDATE {$tbl} SET id = {$nextId} WHERE id = 0 LIMIT 1");
                    }
                    $this->db->query("ALTER TABLE {$tbl} MODIFY COLUMN id INT AUTO_INCREMENT");
                } catch (\Throwable $e) {
                    log_message('error', "AUTO_INCREMENT alter check for {$tbl}: " . $e->getMessage());
>>>>>>> origin/main
                }
            }
        }

        // Always ensure v_bookings_overview uses explicit utf8mb4_unicode_ci collation to prevent MySQL 9+ collation mismatch errors
        try {
<<<<<<< HEAD
=======
            // If v_bookings_overview exists as a BASE TABLE, CREATE OR REPLACE VIEW fails with "'v_bookings_overview' is not VIEW".
            $tableCheck = $this->db->query("SHOW FULL TABLES LIKE 'v_bookings_overview'")->getRowArray();
            if (!empty($tableCheck)) {
                $tableType = array_values($tableCheck)[1] ?? '';
                if (strtoupper($tableType) === 'VIEW') {
                    $colCheck = $this->db->query("SHOW COLUMNS FROM v_bookings_overview LIKE 'cancel_reason'")->getRowArray();
                    if (!empty($colCheck)) {
                        self::$tablesEnsured = true;
                        return;
                    }
                }
                if (strtoupper($tableType) === 'BASE TABLE') {
                    $this->db->query("DROP TABLE IF EXISTS v_bookings_overview");
                }
            }

>>>>>>> origin/main
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
<<<<<<< HEAD
=======
              CAST(wsb.cancel_reason AS CHAR) COLLATE utf8mb4_unicode_ci AS cancel_reason,
>>>>>>> origin/main
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
<<<<<<< HEAD
=======
              CAST(gsb.cancel_reason AS CHAR) COLLATE utf8mb4_unicode_ci AS cancel_reason,
>>>>>>> origin/main
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
<<<<<<< HEAD
=======
              CAST(tsb.cancel_reason AS CHAR) COLLATE utf8mb4_unicode_ci AS cancel_reason,
>>>>>>> origin/main
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
<<<<<<< HEAD
              bsb.created_at,
              bsb.updated_at,
              CASE WHEN bsb.is_deleted IS NOT NULL THEN 1 ELSE 0 END AS is_deleted
=======
              CAST(NULL AS CHAR) COLLATE utf8mb4_unicode_ci AS cancel_reason,
              bsb.created_at,
              bsb.updated_at,
              CASE WHEN bsb.is_deleted = 1 THEN 1 ELSE 0 END AS is_deleted
>>>>>>> origin/main
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
<<<<<<< HEAD
=======
              CAST(ssb.cancel_reason AS CHAR) COLLATE utf8mb4_unicode_ci AS cancel_reason,
>>>>>>> origin/main
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
<<<<<<< HEAD
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
=======

        self::$tablesEnsured = true;
>>>>>>> origin/main
    }

    /**
     * Unprotected diagnostic endpoint to verify AI Analyst metrics.
     */
    public function mongoDiagnostic()
    {
<<<<<<< HEAD
        $mongo = new \App\Libraries\AIAnalyst\MongoDBClient();
        $mysql = new \App\Libraries\AIAnalyst\MySQLMetrics();

        return $this->response->setJSON([
            'status'            => 'ok',
            'mongo_connected'   => $mongo->isConnected(),
            'executive_summary' => $mysql->executive_summary('30_days')
        ]);
    }
=======
        $breakdownQuery = function ($whereClause) {
            return $this->db->query("
                SELECT 
                    COUNT(*) as total_requests,
                    COALESCE(SUM(total_amount), 0) as total_requested_value,
                    SUM(CASE WHEN LOWER(COALESCE(payment_status, '')) IN ('paid', 'success', 'completed', 'successful', '1') 
                               OR LOWER(status) IN ('completed', 'confirmed', 'in progress', 'active') 
                             THEN 1 ELSE 0 END) as confirmed_paid_bookings,
                    COALESCE(SUM(CASE WHEN LOWER(COALESCE(payment_status, '')) IN ('paid', 'success', 'completed', 'successful', '1') 
                                           OR LOWER(status) IN ('completed', 'confirmed', 'in progress', 'active') 
                                      THEN total_amount ELSE 0 END), 0) as confirmed_paid_value,
                    SUM(CASE WHEN LOWER(status) = 'new' AND (payment_status IS NULL OR LOWER(payment_status) NOT IN ('paid', 'success', 'completed'))
                             THEN 1 ELSE 0 END) as unpaid_new_requests,
                    COALESCE(SUM(CASE WHEN LOWER(status) = 'new' AND (payment_status IS NULL OR LOWER(payment_status) NOT IN ('paid', 'success', 'completed'))
                                      THEN total_amount ELSE 0 END), 0) as unpaid_new_value,
                    SUM(CASE WHEN LOWER(status) IN ('cancelled', 'canceled') THEN 1 ELSE 0 END) as cancelled_count,
                    COALESCE(SUM(CASE WHEN LOWER(status) IN ('cancelled', 'canceled') THEN total_amount ELSE 0 END), 0) as cancelled_value
                FROM v_bookings_overview
                WHERE is_deleted = 0 AND {$whereClause}
            ")->getRowArray();
        };

        $today = $breakdownQuery("DATE(created_at) = CURDATE()");
        $thisWeek = $breakdownQuery("created_at >= DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY)");
        $thisMonth = $breakdownQuery("created_at >= DATE_FORMAT(CURDATE(), '%Y-%m-01 00:00:00')");

        $serviceBreakdownFn = function ($whereClause) {
            return $this->db->query("
                SELECT service_type, COUNT(*) as cnt, COALESCE(SUM(total_amount), 0) as rev
                FROM v_bookings_overview
                WHERE is_deleted = 0 AND {$whereClause}
                GROUP BY service_type
            ")->getResultArray();
        };

        $todayServices = $serviceBreakdownFn("DATE(created_at) = CURDATE()");
        $weekServices  = $serviceBreakdownFn("created_at >= DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY)");
        $monthServices = $serviceBreakdownFn("created_at >= DATE_FORMAT(CURDATE(), '%Y-%m-01 00:00:00')");

        // Today's actual bookings list
        $todayBookings = $this->db->query("
            SELECT booking_id, service_type, user_name, user_phone_masked, status, payment_status, total_amount, city, created_at, updated_at
            FROM v_bookings_overview
            WHERE DATE(created_at) = CURDATE()
            ORDER BY created_at DESC
        ")->getResultArray();

        $topAreas = $this->db->query("
            SELECT 
                COALESCE(NULLIF(TRIM(area), ''), 'General') as area, 
                COALESCE(NULLIF(TRIM(city), ''), 'Hyderabad') as city, 
                COUNT(*) as total_requests, 
                ROUND(COALESCE(SUM(total_amount), 0), 2) as total_value
            FROM v_bookings_overview
            WHERE is_deleted = 0
            GROUP BY area, city
            ORDER BY total_requests DESC
            LIMIT 10
        ")->getResultArray();

        // Also check if individual booking tables have address or area
        $clientPayments = function ($whereClause) {
            return $this->db->query("
                SELECT 
                    COUNT(*) as total_attempts,
                    SUM(CASE WHEN LOWER(status) IN ('success', 'completed', 'paid', 'successful', '1') THEN 1 ELSE 0 END) as successful_txns,
                    COALESCE(SUM(CASE WHEN LOWER(status) IN ('success', 'completed', 'paid', 'successful', '1') THEN amount ELSE 0 END), 0) as actual_cash_collected
                FROM transactions
                WHERE (transaction_type = 'booking' OR id IS NOT NULL) AND {$whereClause}
            ")->getRowArray();
        };

        $todayCash = $clientPayments("DATE(created_at) = CURDATE()");
        $weekCash = $clientPayments("created_at >= DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY)");
        $monthCash = $clientPayments("created_at >= DATE_FORMAT(CURDATE(), '%Y-%m-01 00:00:00')");

        return $this->response->setJSON([
            'status'         => 'ok',
            'today'          => $today,
            'this_week'      => $thisWeek,
            'this_month'     => $thisMonth,
            'today_services' => $todayServices,
            'week_services'  => $weekServices,
            'month_services' => $monthServices,
            'today_bookings' => $todayBookings,
            'top_areas'      => $topAreas,
            'today_cash'     => $todayCash,
            'week_cash'      => $weekCash,
            'month_cash'     => $monthCash,
        ]);
    }

    /**
     * Retrieve real credit balance & usage data for the current authenticated admin.
     * Computes actual real-time query count and Gemini 3.1 Flash Lite rate limits.
     */
    public function getCreditsData(?int $adminId = null): array
    {
        $adminId = $adminId ?? $this->getAdminId();
        $this->ensureTablesExist();

        // Count real user queries logged in database for this admin
        $realQueriesAll    = 0;
        $realQueriesToday  = 0;
        $realQueriesMinute = 0;
        try {
            $allResult = $this->db->query("
                SELECT COUNT(*) as cnt 
                FROM ai_analyst_messages m 
                JOIN ai_analyst_conversations c ON c.id = m.conversation_id 
                WHERE (c.admin_id = ? OR c.admin_id = 1) AND m.role = 'user'
            ", [$adminId])->getRow();
            $realQueriesAll = (int) ($allResult->cnt ?? 0);

            $todayResult = $this->db->query("
                SELECT COUNT(*) as cnt 
                FROM ai_analyst_messages m 
                JOIN ai_analyst_conversations c ON c.id = m.conversation_id 
                WHERE (c.admin_id = ? OR c.admin_id = 1) AND m.role = 'user' AND DATE(m.created_at) = CURDATE()
            ", [$adminId])->getRow();
            $realQueriesToday = (int) ($todayResult->cnt ?? 0);

            $minuteResult = $this->db->query("
                SELECT COUNT(*) as cnt 
                FROM ai_analyst_messages m 
                JOIN ai_analyst_conversations c ON c.id = m.conversation_id 
                WHERE (c.admin_id = ? OR c.admin_id = 1) AND m.role = 'user' AND m.created_at >= NOW() - INTERVAL 1 MINUTE
            ", [$adminId])->getRow();
            $realQueriesMinute = (int) ($minuteResult->cnt ?? 0);
        } catch (\Throwable $e) {
            log_message('error', 'Failed to calculate real query count: ' . $e->getMessage());
        }

        $rpdLimit = 500;
        $rpmLimit = 15;
        $tpmLimit = '250K';

        $total     = $rpdLimit;
        $usedToday = $realQueriesToday;
        $available = max(0, $total - $usedToday);

        // Fetch external AI provider credits & health status
        $providerCreditsService = new \App\Services\ProviderCreditsService();
        $providerData           = $providerCreditsService->fetchAllProviderCredits();

        return [
            'model_name'         => 'Gemini 3.1 Flash Lite',
            'category'           => 'Text-out models',
            'rpm'                => "{$realQueriesMinute} / {$rpmLimit}",
            'rpm_used'           => $realQueriesMinute,
            'rpm_limit'          => $rpmLimit,
            'tpm'                => "0 / {$tpmLimit}",
            'tpm_used'           => 0,
            'tpm_limit'          => $tpmLimit,
            'rpd'                => "{$usedToday} / {$rpdLimit}",
            'rpd_used'           => $usedToday,
            'rpd_limit'          => $rpdLimit,
            'rpd_available'      => $available,
            'rate_limit_url'     => 'https://aistudio.google.com/rate-limit?timeRange=last-28-days&project=petsfolio-688ca',
            'total_credits'      => $total,
            'credits_used'       => $usedToday,
            'available_credits'  => $available,
            'real_queries_all'   => $realQueriesAll,
            'real_queries_today' => $realQueriesToday,
            'external_providers' => $providerData['providers'] ?? [],
            'active_providers'   => $providerData['active_count'] ?? 0,
            'total_providers'    => $providerData['total_providers'] ?? 6,
            'openrouter_balance' => $providerData['openrouter_balance'] ?? null,
        ];
    }

    /**
     * Deduct 1 credit per query prompt invocation.
     */
    private function consumeCredit(int $adminId): array
    {
        $credits = $this->getCreditsData($adminId);
        $newUsed = $credits['credits_used'] + 1;

        $this->db->table('ai_analyst_credits')
            ->where('admin_id', $adminId)
            ->update([
                'credits_used' => $newUsed,
                'updated_at'   => date('Y-m-d H:i:s')
            ]);

        return $this->getCreditsData($adminId);
    }

    /**
     * Public JSON endpoint to fetch AI Credits balance.
     */
    public function getCredits()
    {
        $credits = $this->getCreditsData();
        return $this->response->setJSON([
            'status' => true,
            'data'   => $credits
        ]);
    }

    /**
     * Sanitize user prompt to remove UI button text and deduplicate repeated query phrases.
     */
    private function sanitizeUserPrompt(string $msg): string
    {
        $cleaned = preg_replace('/\b(?:Cancel|Send)\b/i', ' ', $msg);
        $cleaned = preg_replace('/\s+/', ' ', (string) $cleaned);
        $parts = array_filter(array_map('trim', explode('?', $cleaned)));
        if (count($parts) > 1) {
            $uniqueParts = [];
            foreach ($parts as $p) {
                $found = false;
                foreach ($uniqueParts as $u) {
                    if (strcasecmp($u, $p) === 0) {
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    $uniqueParts[] = $p;
                }
            }
            $cleaned = implode('? ', $uniqueParts) . (str_ends_with(trim($msg), '?') ? '?' : '');
        }
        return trim($cleaned);
    }
>>>>>>> origin/main
}
