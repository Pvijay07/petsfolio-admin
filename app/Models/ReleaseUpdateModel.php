<?php

namespace App\Models;

use CodeIgniter\Model;

class ReleaseUpdateModel extends Model
{
    protected $table            = 'release_updates';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'version_id',
        'category',
        'title',
        'description',
        'ticket_id',
        'developer_id',
        'status',
        'comments',
        'sort_order',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public static function getCategories()
    {
        return [
            'new_features' => [
                'label' => 'New Features',
                'badge' => 'bg-success',
                'icon'  => 'bi-plus-circle-fill'
            ],
            'enhancements' => [
                'label' => 'Feature Enhancements',
                'badge' => 'bg-info',
                'icon'  => 'bi-arrow-up-right-circle-fill'
            ],
            'bug_fixes' => [
                'label' => 'Bug Fixes',
                'badge' => 'bg-danger',
                'icon'  => 'bi-bug-fill'
            ],
            'ui_ux' => [
                'label' => 'UI/UX Changes',
                'badge' => 'bg-purple',
                'icon'  => 'bi-palette-fill'
            ],
            'api_backend' => [
                'label' => 'API/Backend Changes',
                'badge' => 'bg-primary',
                'icon'  => 'bi-hdd-network-fill'
            ],
            'database' => [
                'label' => 'Database Changes',
                'badge' => 'bg-warning text-dark',
                'icon'  => 'bi-server'
            ],
            'security' => [
                'label' => 'Security Changes',
                'badge' => 'bg-dark',
                'icon'  => 'bi-shield-lock-fill'
            ],
            'third_party' => [
                'label' => 'AI / Third-party Integrations',
                'badge' => 'bg-teal',
                'icon'  => 'bi-robot'
            ]
        ];
    }
}
