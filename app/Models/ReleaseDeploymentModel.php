<?php

namespace App\Models;

use CodeIgniter\Model;

class ReleaseDeploymentModel extends Model
{
    protected $table            = 'release_deployments';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'version_id', 'deployment_status', 'started_at', 'deployed_at',
        'health_check_at', 'monitoring_started', 'completed_at',
        'deployed_by', 'notes'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Deployment step labels
     */
    protected $stepLabels = [
        'pending'       => 'Pending',
        'started'       => 'Release Started',
        'deploying'     => 'Deploying',
        'deployed'      => 'Deployment Completed',
        'health_check'  => 'Health Check',
        'monitoring'    => 'Monitoring',
        'successful'    => 'Release Successful',
        'failed'        => 'Deployment Failed',
    ];

    /**
     * Deployment step icons
     */
    protected $stepIcons = [
        'pending'       => 'bi-hourglass',
        'started'       => 'bi-play-circle',
        'deploying'     => 'bi-arrow-repeat',
        'deployed'      => 'bi-check-circle',
        'health_check'  => 'bi-heart-pulse',
        'monitoring'    => 'bi-activity',
        'successful'    => 'bi-trophy',
        'failed'        => 'bi-x-circle',
    ];

    public function getStepLabel(string $step): string
    {
        return $this->stepLabels[$step] ?? ucfirst(str_replace('_', ' ', $step));
    }

    public function getStepIcon(string $step): string
    {
        return $this->stepIcons[$step] ?? 'bi-circle';
    }

    /**
     * Get deployment record for a version
     */
    public function getDeploymentByVersion(int $versionId): ?array
    {
        return $this->db->table('release_deployments rd')
            ->select('rd.*, e.name as deployer_name')
            ->join('employees e', 'e.id = rd.deployed_by', 'left')
            ->where('rd.version_id', $versionId)
            ->orderBy('rd.created_at', 'DESC')
            ->get()
            ->getRowArray();
    }

    /**
     * Start a new deployment
     */
    public function startDeployment(int $versionId, int $userId): int
    {
        $this->insert([
            'version_id'        => $versionId,
            'deployment_status' => 'started',
            'started_at'        => date('Y-m-d H:i:s'),
            'deployed_by'       => $userId,
        ]);
        return $this->getInsertID();
    }

    /**
     * Update deployment step with timestamp
     */
    public function updateDeploymentStep(int $id, string $step): bool
    {
        $data = ['deployment_status' => $step];

        switch ($step) {
            case 'deployed':
                $data['deployed_at'] = date('Y-m-d H:i:s');
                break;
            case 'health_check':
                $data['health_check_at'] = date('Y-m-d H:i:s');
                break;
            case 'monitoring':
                $data['monitoring_started'] = date('Y-m-d H:i:s');
                break;
            case 'successful':
            case 'failed':
                $data['completed_at'] = date('Y-m-d H:i:s');
                break;
        }

        return $this->update($id, $data);
    }

    /**
     * Get all deployment steps with their completion status
     */
    public function getDeploymentProgress(int $versionId): array
    {
        $deployment = $this->getDeploymentByVersion($versionId);
        $steps = ['started', 'deploying', 'deployed', 'health_check', 'monitoring', 'successful'];

        $progress = [];
        $currentIndex = -1;

        if ($deployment) {
            $currentStatus = $deployment['deployment_status'];
            $currentIndex = array_search($currentStatus, $steps);
            if ($currentIndex === false) $currentIndex = -1;
        }

        foreach ($steps as $idx => $step) {
            $progress[] = [
                'step'      => $step,
                'label'     => $this->stepLabels[$step] ?? $step,
                'icon'      => $this->stepIcons[$step] ?? 'bi-circle',
                'completed' => $deployment && $idx <= $currentIndex,
                'current'   => $deployment && $idx === $currentIndex,
            ];
        }

        return $progress;
    }
}
