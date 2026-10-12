<?php

namespace App\Models;

use CodeIgniter\Model;

class PermissionModel extends Model
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    // -------------------------------------------------------------
    // ROLES MANAGEMENT
    // -------------------------------------------------------------
    public function getRoles(): array
    {
        return $this->db->table('roles')
            ->orderBy('id', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function getRole(int $id): ?array
    {
        return $this->db->table('roles')
            ->where('id', $id)
            ->get()
            ->getRowArray();
    }

    public function createRole(array $data): int
    {
        $this->db->table('roles')->insert([
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
            'created_at'  => date('Y-m-d H:i:s')
        ]);
        return (int)$this->db->insertID();
    }

    public function updateRole(int $id, array $data): bool
    {
        return $this->db->table('roles')
            ->where('id', $id)
            ->update($data);
    }

    public function deleteRole(int $id): bool
    {
        // Don't allow deleting Super Admin role (id=1)
        if ($id === 1) {
            return false;
        }

        $this->db->transStart();
        $this->db->table('role_permissions')->where('role_id', $id)->delete();
        $this->db->table('roles')->where('id', $id)->delete();
        $this->db->transComplete();

        return $this->db->transStatus();
    }

    // -------------------------------------------------------------
    // PERMISSIONS MASTER MANAGEMENT
    // -------------------------------------------------------------
    public function getPermissions(): array
    {
        return $this->db->table('permissions')
            ->orderBy('module', 'ASC')
            ->orderBy('id', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function getPermissionsGroupedByModule(): array
    {
        $permissions = $this->getPermissions();
        $grouped = [];

        foreach ($permissions as $perm) {
            $module = $perm['module'] ?? 'General';
            $grouped[$module][] = $perm;
        }

        ksort($grouped);
        return $grouped;
    }

    public function createPermission(array $data): int
    {
        $this->db->table('permissions')->insert([
            'name'   => $data['name'],
            'module' => $data['module']
        ]);
        return (int)$this->db->insertID();
    }

    public function updatePermission(int $id, array $data): bool
    {
        return $this->db->table('permissions')
            ->where('id', $id)
            ->update($data);
    }

    public function deletePermission(int $id): bool
    {
        $this->db->transStart();
        $this->db->table('role_permissions')->where('permission_id', $id)->delete();
        if ($this->db->tableExists('user_permissions')) {
            $this->db->table('user_permissions')->where('permission_id', $id)->delete();
        }
        $this->db->table('permissions')->where('id', $id)->delete();
        $this->db->transComplete();

        return $this->db->transStatus();
    }

    // -------------------------------------------------------------
    // ROLE-WISE PERMISSIONS MATRIX
    // -------------------------------------------------------------
    public function getRolePermissionIds(int $roleId): array
    {
        $rows = $this->db->table('role_permissions')
            ->select('permission_id')
            ->where('role_id', $roleId)
            ->get()
            ->getResultArray();

        return array_column($rows, 'permission_id');
    }

    public function assignRolePermissions(int $roleId, array $permissionIds): bool
    {
        $this->db->transStart();

        // Clear existing permissions for role
        $this->db->table('role_permissions')->where('role_id', $roleId)->delete();

        // Insert selected permissions
        if (!empty($permissionIds)) {
            $batch = [];
            foreach ($permissionIds as $permId) {
                if (!empty($permId)) {
                    $batch[] = [
                        'role_id'       => $roleId,
                        'permission_id' => (int)$permId
                    ];
                }
            }
            if (!empty($batch)) {
                $this->db->table('role_permissions')->insertBatch($batch);
            }
        }

        $this->db->transComplete();
        return $this->db->transStatus();
    }

    public function toggleRolePermission(int $roleId, int $permissionId, bool $enable): bool
    {
        if ($enable) {
            $exists = $this->db->table('role_permissions')
                ->where('role_id', $roleId)
                ->where('permission_id', $permissionId)
                ->countAllResults();

            if ($exists === 0) {
                return $this->db->table('role_permissions')->insert([
                    'role_id'       => $roleId,
                    'permission_id' => $permissionId
                ]);
            }
            return true;
        } else {
            return $this->db->table('role_permissions')
                ->where('role_id', $roleId)
                ->where('permission_id', $permissionId)
                ->delete();
        }
    }

    // -------------------------------------------------------------
    // USER-WISE DIRECT PERMISSIONS & OVERRIDES
    // -------------------------------------------------------------
    public function getUserDirectPermissionIds(int $userId): array
    {
        if (!$this->db->tableExists('user_permissions')) {
            return [];
        }

        $rows = $this->db->table('user_permissions')
            ->select('permission_id')
            ->where('user_id', $userId)
            ->get()
            ->getResultArray();

        return array_column($rows, 'permission_id');
    }

    public function getUserEffectivePermissions(int $userId): array
    {
        // Fetch user's assigned role
        $user = $this->db->table('employees')
            ->select('id, name, email, role_id')
            ->where('id', $userId)
            ->get()
            ->getRowArray();

        if (!$user) {
            return [];
        }

        $roleId = (int)$user['role_id'];
        $rolePermissionIds = $this->getRolePermissionIds($roleId);
        $directPermissionIds = $this->getUserDirectPermissionIds($userId);

        $mergedIds = array_unique(array_merge($rolePermissionIds, $directPermissionIds));

        return [
            'user'                 => $user,
            'role_permission_ids'  => $rolePermissionIds,
            'direct_permission_ids'=> $directPermissionIds,
            'effective_ids'        => array_values($mergedIds)
        ];
    }

    public function getPermissionsByUserId(int $userId): array
    {
        $effectiveData = $this->getUserEffectivePermissions($userId);
        $effectiveIds = $effectiveData['effective_ids'] ?? [];

        if (empty($effectiveIds)) {
            return [];
        }

        return $this->db->table('permissions')
            ->whereIn('id', $effectiveIds)
            ->orderBy('module', 'ASC')
            ->orderBy('name', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function assignUserPermissions(int $userId, array $permissionIds): bool
    {
        if (!$this->db->tableExists('user_permissions')) {
            return false;
        }

        $this->db->transStart();
        $this->db->table('user_permissions')->where('user_id', $userId)->delete();

        if (!empty($permissionIds)) {
            $batch = [];
            foreach ($permissionIds as $permId) {
                if (!empty($permId)) {
                    $batch[] = [
                        'user_id'       => $userId,
                        'permission_id' => (int)$permId,
                        'created_at'    => date('Y-m-d H:i:s')
                    ];
                }
            }
            if (!empty($batch)) {
                $this->db->table('user_permissions')->insertBatch($batch);
            }
        }

        $this->db->transComplete();
        return $this->db->transStatus();
    }

    public function toggleUserPermission(int $userId, int $permissionId, bool $enable): bool
    {
        if (!$this->db->tableExists('user_permissions')) {
            return false;
        }

        if ($enable) {
            $exists = $this->db->table('user_permissions')
                ->where('user_id', $userId)
                ->where('permission_id', $permissionId)
                ->countAllResults();

            if ($exists === 0) {
                return $this->db->table('user_permissions')->insert([
                    'user_id'       => $userId,
                    'permission_id' => $permissionId,
                    'created_at'    => date('Y-m-d H:i:s')
                ]);
            }
            return true;
        } else {
            return $this->db->table('user_permissions')
                ->where('user_id', $userId)
                ->where('permission_id', $permissionId)
                ->delete();
        }
    }

    // -------------------------------------------------------------
    // USER ROLE ASSIGNMENT
    // -------------------------------------------------------------
    public function getEmployeesWithRoles(): array
    {
        return $this->db->table('employees e')
            ->select('e.id, e.name, e.email, e.phone, e.role_id, e.status, r.name as role_name')
            ->join('roles r', 'r.id = e.role_id', 'left')
            ->orderBy('e.id', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function assignUserRole(int $userId, int $roleId): bool
    {
        return $this->db->table('employees')
            ->where('id', $userId)
            ->update(['role_id' => $roleId]);
    }
}
