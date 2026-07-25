<?php

namespace App\Models;

use CodeIgniter\Model;

class TaskModel extends Model
{
    protected $table            = 'tasks';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'title',
        'description', 
        'task_type', 
        'start_date',
        'repeat_interval', 
        'alert_before_days',
        'status', 
        'resolved_comment',
        'created_at',
        'updated_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'title' => 'required|max_length[255]',
        'task_type' => 'required|in_list[one_time,repeated]',
        'start_date' => 'required|valid_date',
        'status' => 'required|in_list[pending,in_progress,completed,cancelled]',
        'alert_before_days' => 'permit_empty|is_natural'
    ];
    
    protected $validationMessages   = [
        'title' => [
            'required' => 'Task title is required',
            'max_length' => 'Task title cannot exceed 255 characters'
        ],
        'start_date' => [
            'required' => 'Start date is required',
            'valid_date' => 'Please enter a valid date'
        ]
    ];
    
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['setDefaultValues'];
    protected $beforeUpdate   = ['updateTimestamps'];
    protected $afterInsert    = [];
    protected $afterUpdate    = ['handleCompletedRecurringTask'];

    /**
     * Set default values before insert
     */
    protected function setDefaultValues(array $data)
    {
        // Set default alert days if not provided
        if (empty($data['data']['alert_before_days'])) {
            $data['data']['alert_before_days'] = 2;
        }
        
        // Set default status if not provided
        if (empty($data['data']['status'])) {
            $data['data']['status'] = 'pending';
        }
        
        return $data;
    }

    /**
     * Update timestamps before update
     */
    protected function updateTimestamps(array $data)
    {
        $data['data']['updated_at'] = date('Y-m-d H:i:s');
        return $data;
    }

    /**
     * Handle completed recurring tasks - create next occurrence
     */
    protected function handleCompletedRecurringTask(array $data)
    {
        if (isset($data['data']['status']) && $data['data']['status'] === 'completed' && 
            isset($data['data']['task_type']) && $data['data']['task_type'] === 'repeated') {
            
            $taskId = is_array($data['id']) ? $data['id'][0] : $data['id'];
            $this->createNextRecurringTask($taskId);
        }
        
        return $data;
    }

    /**
     * Get tasks with alerts (based on alert_before_days)
     */
    public function getTasksWithAlerts()
    {
        $builder = $this->builder();
        
        return $builder->select('*')
                      ->where('status !=', 'completed')
                      ->where('status !=', 'cancelled')
                      ->where("DATE_ADD(start_date, INTERVAL -alert_before_days DAY) <= CURDATE()")
                      ->where('start_date >= CURDATE()')
                      ->orderBy('start_date', 'ASC')
                      ->get()
                      ->getResultArray();
    }

    /**
     * Get overdue tasks
     */
    public function getOverdueTasks()
    {
        return $this->where('start_date < CURDATE()')
                    ->where('status !=', 'completed')
                    ->where('status !=', 'cancelled')
                    ->orderBy('start_date', 'ASC')
                    ->findAll();
    }

    /**
     * Get tasks that should show alerts today
     */
    public function getTodaysAlertTasks()
    {
        $builder = $this->builder();
        
        return $builder->select('*')
                      ->where('status !=', 'completed')
                      ->where('status !=', 'cancelled')
                      ->where("DATE_ADD(start_date, INTERVAL -alert_before_days DAY) = CURDATE()")
                      ->orderBy('start_date', 'ASC')
                      ->get()
                      ->getResultArray();
    }

    /**
     * Get tasks by status
     */
    public function getTasksByStatus($status)
    {
        $allowedStatuses = ['pending', 'in_progress', 'completed', 'cancelled'];
        
        if (!in_array($status, $allowedStatuses)) {
            return [];
        }

        return $this->where('status', $status)
                    ->orderBy('start_date', 'ASC')
                    ->findAll();
    }

    /**
     * Get tasks by type
     */
    public function getTasksByType($type)
    {
        $allowedTypes = ['one_time', 'repeated'];
        
        if (!in_array($type, $allowedTypes)) {
            return [];
        }

        return $this->where('task_type', $type)
                    ->orderBy('start_date', 'ASC')
                    ->findAll();
    }

    /**
     * Create next occurrence for recurring tasks
     */
    public function createNextRecurringTask($completedTaskId)
    {
        $completedTask = $this->find($completedTaskId);
        
        if (!$completedTask || $completedTask['task_type'] !== 'repeated') {
            return false;
        }

        $nextStartDate = $this->calculateNextStartDate(
            $completedTask['start_date'], 
            $completedTask['repeat_interval']
        );

        $newTaskData = [
            'title' => $completedTask['title'],
            'description' => $completedTask['description'],
            'start_date' => $nextStartDate,
            'task_type' => 'repeated',
            'repeat_interval' => $completedTask['repeat_interval'],
            'alert_before_days' => $completedTask['alert_before_days'],
            'status' => 'pending'
        ];

        return $this->insert($newTaskData);
    }

    /**
     * Calculate next start date based on interval
     */
    private function calculateNextStartDate($currentStartDate, $interval)
    {
        $date = new \DateTime($currentStartDate);
        
        switch ($interval) {
            case 'daily':
                $date->modify('+1 day');
                break;
            case 'weekly':
                $date->modify('+1 week');
                break;
            case 'monthly':
                $date->modify('+1 month');
                break;
            case 'yearly':
                $date->modify('+1 year');
                break;
            default:
                $date->modify('+1 day');
        }

        return $date->format('Y-m-d');
    }

    /**
     * Resolve task with comment
     */
    public function resolveTask($taskId, $comment)
    {
        return $this->update($taskId, [
            'status' => 'completed',
            'resolved_comment' => $comment,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Search tasks
     */
    public function searchTasks($keyword)
    {
        return $this->like('title', $keyword)
                    ->orLike('description', $keyword)
                    ->orLike('resolved_comment', $keyword)
                    ->orderBy('start_date', 'ASC')
                    ->findAll();
    }

    /**
     * Get tasks with pagination
     */
    public function getPaginatedTasks($perPage = 10)
    {
        return $this->orderBy('start_date', 'ASC')
                    ->paginate($perPage);
    }

    /**
     * Update task status
     */
    public function updateTaskStatus($taskId, $status, $resolvedComment = null)
    {
        $updateData = [
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        if ($resolvedComment) {
            $updateData['resolved_comment'] = $resolvedComment;
        }

        return $this->update($taskId, $updateData);
    }

    /**
     * Get task statistics
     */
    public function getTaskStatistics()
    {
        $total = $this->countAll();
        $completed = $this->where('status', 'completed')->countAllResults();
        $pending = $this->where('status', 'pending')->countAllResults();
        $inProgress = $this->where('status', 'in_progress')->countAllResults();
        $overdue = $this->getOverdueTasks();
        $alerts = $this->getTasksWithAlerts();

        return [
            'total' => $total,
            'completed' => $completed,
            'pending' => $pending,
            'in_progress' => $inProgress,
            'overdue_count' => count($overdue),
            'alert_count' => count($alerts),
            'completion_rate' => $total > 0 ? round(($completed / $total) * 100, 2) : 0
        ];
    }

    /**
     * Get upcoming tasks (within next 7 days)
     */
    public function getUpcomingTasks($days = 7)
    {
        $futureDate = date('Y-m-d', strtotime("+$days days"));
        
        return $this->where('start_date >=', date('Y-m-d'))
                    ->where('start_date <=', $futureDate)
                    ->where('status !=', 'completed')
                    ->where('status !=', 'cancelled')
                    ->orderBy('start_date', 'ASC')
                    ->findAll();
    }

    /**
 * Get tasks for reminder alerts (due within alert period)
 */
public function getReminderTasks()
{
    $builder = $this->builder();
    
    return $builder->select('*')
                  ->where('status !=', 'completed')
                  ->where('status !=', 'cancelled')
                  ->where("DATE_ADD(start_date, INTERVAL -alert_before_days DAY) <= CURDATE()")
                  ->where('start_date >= CURDATE()')
                  ->orderBy('start_date', 'ASC')
                  ->get()
                  ->getResultArray();
}
}