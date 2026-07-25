<?php

namespace App\Models;

use CodeIgniter\Model;

class ChatModel extends Model
{
    protected $table = 'chat_messages';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'username',
        'message',
        'message_type',
        'file_path',
        'room_id',
        'created_at',
        'updated_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'username' => 'required|min_length[2]|max_length[50]',
        'message' => 'required|min_length[1]|max_length[1000]'
    ];

    protected $validationMessages = [
        'username' => [
            'required' => 'Username is required',
            'min_length' => 'Username must be at least 2 characters long',
            'max_length' => 'Username cannot exceed 50 characters'
        ],
        'message' => [
            'required' => 'Message is required',
            'min_length' => 'Message cannot be empty',
            'max_length' => 'Message cannot exceed 1000 characters'
        ]
    ];

    public function getRecentMessages($limit = 50, $roomId = null)
    {
        $builder = $this->builder();

        if ($roomId) {
            $builder->where('room_id', $roomId);
        }

        return $builder->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    public function getMessagesByUser($username, $limit = 20)
    {
        return $this->where('username', $username)
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    public function getActiveUsers($timeLimit = 300) // 5 minutes
    {
        $builder = $this->builder();
        $cutoff = date('Y-m-d H:i:s', time() - $timeLimit);

        return $builder->select('username, MAX(created_at) as last_activity')
            ->where('created_at >=', $cutoff)
            ->groupBy('username')
            ->orderBy('last_activity', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function searchMessages($query, $limit = 20)
    {
        return $this->like('message', $query)
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    public function deleteOldMessages($days = 30)
    {
        $cutoff = date('Y-m-d H:i:s', time() - ($days * 24 * 60 * 60));
        return $this->where('created_at <', $cutoff)->delete();
    }

    public function getMessageStats()
    {
        $builder = $this->builder();

        return [
            'total_messages' => $builder->countAllResults(false),
            'unique_users' => $builder->select('username')->distinct()->countAllResults(false),
            'messages_today' => $builder->where('DATE(created_at)', date('Y-m-d'))->countAllResults(false),
            'most_active_user' => $this->getMostActiveUser()
        ];
    }

    private function getMostActiveUser()
    {
        $builder = $this->builder();

        $result = $builder->select('username, COUNT(*) as message_count')
            ->groupBy('username')
            ->orderBy('message_count', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray();

        return $result ?: ['username' => 'None', 'message_count' => 0];
    }
}
