<?php

namespace App\Controllers\admin;

use App\Controllers\BaseController;

class AiAnalysisController extends BaseController
{
    public function index()
    {
        $db      = \Config\Database::connect ();
        $builder = $db->table ( 'call_logs cl' );
        $builder->select ( 'cl.*, ca.interaction_count, ca.confidence_score, ca.clarity_score, ca.professionalism_score, ca.risk_flag, ca.extracted_data, ps.final_score, ps.sentiment_score, sp.name as provider_name, sp.phone as provider_phone' );

        // Link with scores, analysis and providers
        $builder->join ( 'call_analysis ca', 'ca.call_id = cl.call_id', 'left' );
        $builder->join ( 'provider_scores ps', 'ps.call_id = cl.call_id', 'left' );
        $builder->join ( 'service_providers sp', 'sp.phone = cl.to_number OR sp.phone = SUBSTRING(cl.to_number, 4)', 'left' );
        $builder->orderBy ( 'cl.call_date', 'DESC' );

        $calls = $builder->get ()->getResult ();

        // Fetch interactions for each call to display as chat
        foreach ( $calls as $call ) {
            $call->interactions = $db->table ( 'call_interactions' )
                ->where ( 'call_id', $call->call_id )
                ->orderBy ( 'sequence', 'ASC' )
                ->get ()
                ->getResult ();
        }

        $data = [
            'title' => 'AI Call Analysis',
            'calls' => $calls
        ];
        return view ( 'admin/voice-agent/ai_analysis_view', $data );
    }


    public function chatHistory()
    {
        $db      = \Config\Database::connect ();
        $builder = $db->table ( 'conversations c' );
        $builder->select ( 'c.*, u.name as user_name, u.phone as user_phone, cl.to_number as call_phone, m.message as last_message, m.created_at as last_message_at' );
        $builder->join ( 'users u', 'u.id = c.user_id', 'left' );
        $builder->join ( 'call_logs cl', 'cl.call_id = c.call_id', 'left' );
        
        // Joining with the last message per conversation
        $builder->join ( '(SELECT m1.conversation_id, m1.message, m1.created_at FROM messages m1 INNER JOIN (SELECT MAX(id) as max_id FROM messages GROUP BY conversation_id) m2 ON m1.id = m2.max_id) m', 'm.conversation_id = c.id', 'left' );
        
        $builder->orderBy ( 'c.updated_at', 'DESC' );
        $conversations = $builder->get ()->getResultArray();

        // Map data to ensure name and phone are always present
        foreach ($conversations as &$conv) {
            if (empty($conv['user_name'])) {
                $conv['user_name'] = $conv['call_phone'] ?: 'Unknown User';
            }
            if (empty($conv['user_phone'])) {
                $conv['user_phone'] = $conv['call_phone'] ?: 'N/A';
            }
        }

        return view ( 'admin/voice-agent/chat_history_view', [
            'title'         => 'Chat History',
            'conversations' => $conversations
        ] );
    }

    public function getMessages($conversationId)
    {
        $db = \Config\Database::connect();
        
        $conv = $db->table('conversations c')
            ->select('c.*, u.name as user_name, u.phone as user_phone, cl.to_number as call_phone')
            ->join('users u', 'u.id = c.user_id', 'left')
            ->join('call_logs cl', 'cl.call_id = c.call_id', 'left')
            ->where('c.id', $conversationId)
            ->get()
            ->getRow();
            
        if (!$conv) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Conversation not found'])->setStatusCode(404);
        }

        $messages = $db->table('messages')
            ->where('conversation_id', $conversationId)
            ->orderBy('id', 'ASC')
            ->get()
            ->getResultArray();

        return $this->response->setJSON([
            'status' => 'success',
            'data' => [
                'messages' => $messages,
                'user'     => [
                    'user_name'  => $conv->user_name ?: ($conv->call_phone ?: 'Unknown User'),
                    'user_phone' => $conv->user_phone ?: ($conv->call_phone ?: 'N/A')
                ]
            ]
        ]);
    }
}
