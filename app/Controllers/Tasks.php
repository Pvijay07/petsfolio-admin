<?php

namespace App\Controllers\admin;

use App\Controllers\BaseController;

class Tasks extends BaseController
{
    public function index()
    {
        $taskModel = new \App\Models\TaskModel();
        
        $data = [
            'tasks' => $taskModel->orderBy('start_date', 'ASC')->findAll(),
            'statistics' => $taskModel->getTaskStatistics(),
            'pager' => $taskModel->pager,
            'currentPage' => $this->request->getGet('page') ?? 1,
            'perPage' => 10,
            'total' => $taskModel->countAll()
        ];

        return view('admin/system_tasks', $data);
    }

    public function saveTask()
    {
        $taskModel = new \App\Models\TaskModel();

        $data = [
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'start_date' => $this->request->getPost('start_date'),
            'task_type' => $this->request->getPost('task_type'),
            'repeat_interval' => $this->request->getPost('repeat_interval'),
            'alert_before_days' => $this->request->getPost('alert_before_days'),
            'status' => $this->request->getPost('status'),
        ];
        
        $id = $this->request->getPost('id');

        // Validate required fields
        if (empty($data['title']) || empty($data['start_date']) || empty($data['task_type'])) {
            return redirect()->back()->with('error', 'Title, start date, and task type are required.');
        }

        // For one-time tasks, set repeat_interval to null
        if ($data['task_type'] === 'one_time') {
            $data['repeat_interval'] = null;
        }

        // Validate repeat_interval for repeated tasks
        if ($data['task_type'] === 'repeated' && empty($data['repeat_interval'])) {
            return redirect()->back()->with('error', 'Repeat interval is required for repeated tasks.');
        }

        try {
            if ($id) {
                $taskModel->update($id, $data);
                $message = 'Task updated successfully';
            } else {
                $taskModel->insert($data);
                $message = 'Task created successfully';
            }

            return redirect()->to('/admin/tasks')->with('success', $message);
            
        } catch (\Exception $e) {
            log_message('error', 'Task save error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to save task: ' . $e->getMessage());
        }
    }

    public function resolveTask()
    {
        $taskModel = new \App\Models\TaskModel();
        
        $id = $this->request->getPost('id');
        $resolved_comment = $this->request->getPost('resolved_comment');
        
        if (empty($resolved_comment)) {
            return redirect()->back()->with('error', 'Completion comments are required.');
        }

        try {
            $taskModel->resolveTask($id, $resolved_comment);
            return redirect()->to('/admin/tasks')->with('success', 'Task marked as completed successfully');
            
        } catch (\Exception $e) {
            log_message('error', 'Task resolve error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to resolve task: ' . $e->getMessage());
        }
    }

    public function deleteTask($id)
    {
        $taskModel = new \App\Models\TaskModel();
        
        try {
            $taskModel->delete($id);
            return redirect()->to('/admin/tasks')->with('success', 'Task deleted successfully');
            
        } catch (\Exception $e) {
            log_message('error', 'Task delete error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete task: ' . $e->getMessage());
        }
    }

    public function updateStatus($id, $status)
    {
        $taskModel = new \App\Models\TaskModel();
        $allowedStatuses = ['pending', 'in_progress', 'completed', 'cancelled'];
        
        if (!in_array($status, $allowedStatuses)) {
            return redirect()->back()->with('error', 'Invalid status.');
        }

        try {
            $taskModel->updateTaskStatus($id, $status);
            return redirect()->to('/admin/tasks')->with('success', 'Task status updated successfully');
            
        } catch (\Exception $e) {
            log_message('error', 'Task status update error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update task status: ' . $e->getMessage());
        }
    }

    public function getAlerts()
    {
        $taskModel = new \App\Models\TaskModel();
        $alerts = $taskModel->getTasksWithAlerts();
        
        return $this->response->setJSON($alerts);
    }

    public function getOverdue()
    {
        $taskModel = new \App\Models\TaskModel();
        $overdue = $taskModel->getOverdueTasks();
        
        return $this->response->setJSON($overdue);
    }

    public function getStatistics()
    {
        $taskModel = new \App\Models\TaskModel();
        $statistics = $taskModel->getTaskStatistics();
        
        return $this->response->setJSON($statistics);
    }

    public function search()
    {
        $taskModel = new \App\Models\TaskModel();
        $keyword = $this->request->getGet('q');
        
        $tasks = $taskModel->searchTasks($keyword);
        
        return $this->response->setJSON($tasks);
    }
}