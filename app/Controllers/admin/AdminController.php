<?php

namespace App\Controllers\admin;

use App\Controllers\BaseController;
use App\Models\Booking;
use App\Models\User;
use App\Models\TicketModel;
use App\Models\TicketMessageModel;
use App\Models\InsurancePolicy;
use App\Libraries\CashfreePayoutService;
use Config\Services;
use Config\Database;

class AdminController extends BaseController
{
    protected $model;
    protected $session;
    protected $booking;
    protected $ticketModel;
    protected $ticketMessageModel;
    protected $insuranceModel;


    public function __construct()
    {
        $this->model              = new User();
        $this->booking            = new Booking();
        $this->session            = session();
        $this->ticketModel        = new TicketModel();
        $this->ticketMessageModel = new TicketMessageModel();
        $this->insuranceModel     = new InsurancePolicy();
    }

    public function index()
    {
        $data['users'] = $this->model->getUsers();
        $data['role']  = $this->model->getRoles();
        return $this->loadView('admin/employees', $data);
    }

    public function addEmployee()
    {
        $rules = [
            'name'    => 'required',
            'email'   => 'required|valid_email',
            'status'  => 'required',
            'role_id' => 'required',
        ];
        if (!$this->validate($rules)) {
            return $this->response->setJSON(['status' => false, 'errors', $this->validator->getErrors()]);
        }
        $data   = [
            'name'    => $this->request->getPost('name'),
            'email'   => $this->request->getPost('email'),
            'status'  => $this->request->getPost('status') ? 1 : 0,
            'role_id' => $this->request->getPost('role_id'),
        ];
        $result = $this->model->insertEmployee($data);
        if ($result) {
            return $this->response->setJSON(['status' => true, 'message' => 'created successfully']);
        } else {
            return $this->response->setJSON(['status' => false, 'message' => 'Please try again']);
        }
    }

    public function updateEmployee()
    {
        $rules = [
            'name'    => 'required',
            'email'   => 'required|valid_email',
            'status'  => 'required',
            'role_id' => 'required',
            'id'        => 'required'
        ];
        if (!$this->validate($rules)) {
            return $this->response->setJSON(['status' => false, 'errors', $this->validator->getErrors()]);
        }
        $data   = [
            'name'    => $this->request->getPost('name'),
            'email'   => $this->request->getPost('email'),
            'status'  => $this->request->getPost('status') ? 1 : 0,
            'role_id' => $this->request->getPost('role_id'),
            'id' => $this->request->getPost('id'),

        ];
        $result = $this->model->updateEmployee($data);
        if ($result) {
            return $this->response->setJSON(['status' => true, 'message' => 'created successfully']);
        } else {
            return $this->response->setJSON(['status' => false, 'message' => 'Please try again']);
        }
    }
    public function deleteEmployee()
    {
        $rules = [
            'id' => 'required',
        ];
        if (!$this->validate($rules)) {
            return $this->response->setJSON(['status' => false, 'errors', $this->validator->getErrors()]);
        }
        $userId = $this->request->getPost('id');

        $result = $this->model->deleteEmployee($userId);

        if ($result) {
            return $this->response->setJSON(['status' => true, 'message' => 'created successfully']);
        } else {
            return $this->response->setJSON(['status' => false, 'message' => 'Please try again']);
        }
    }
    public function profile()
    {
        $data['user'] = $this->model->getUser($this->session->get('user')->id);
        return $this->loadView('admin/profile', $data);
    }

    public function updateProfile()
    {
        $rules = [
            'name'   => 'required',
            'email'  => 'required',
            'gender' => 'required',
        ];
        if (!$this->validate($rules)) {
            return $this->response->setJSON(['status' => false, 'errors' => $this->validator->getErrors()]);
        }
        $data    = [
            'name'   => $this->request->getPost('name'),
            'email'  => $this->request->getPost('email'),
            'phone'  => $this->request->getPost('phone'),
            'gender' => $this->request->getPost('gender'),
        ];
        $profile = $this->request->getFile('profile');

        if ($profile && $profile->isValid() && !$profile->hasMoved()) {
            $newName = $profile->getRandomName();
            $profile->move('public/uploads', $newName);
            $data['profile'] = $newName;
        } else {
            // return $this->response->setJSON(['status' => false, 'message' => 'File upload failed']);
            $data['profile'] = '';
        }

        $id = $this->session->get('user')->id;
        $this->model->updateProfile($data, $id);
        return $this->response->setJSON(['status' => true, 'message' => 'Profile updated successfully']);
    }
    public function updatePassword()
    {
        $rules = [
            'old_password'     => 'required',
            'new_password'     => 'required|min_length[6]',
            'confirm_password' => 'required|matches[new_password]',
        ];
        if (!$this->validate($rules)) {
            return $this->response->setJSON(['status' => false, 'errors' => $this->validator->getErrors()]);
        }
        $data = [
            // 'old_password' => $this->request->getPost('old_password'),
            'password' => password_hash($this->request->getPost('new_password'), PASSWORD_DEFAULT),
            // 'confirm_password' => $this->request->getPost('confirm_password'),
        ];
        $id   = $this->session->get('user')->id;
        $this->model->updatePassword($data, $id);
        return $this->response->setJSON(['status' => true, 'message' => 'Password updated successfully']);
    }
    public function dashboard()
    {
        $data['title']                    = 'Dashboard';
        $walking                          = $this->booking->getCompletedWalkings();
        $boarding                         = $this->booking->getCompletedBoardings();
        $grooming                         = $this->booking->getCompletedGroomings();
        $training                         = $this->booking->getCompletedTrainings();
        $pendingwalking                   = $this->booking->getPendingWalkings();
        $pendingboarding                  = $this->booking->getPendingBoardings();
        $pendinggrooming                  = $this->booking->getPendingGroomings();
        $pendingtraining                  = $this->booking->getPendingTrainings();
        $data['total_completed_bookings'] = $walking + $boarding + $grooming + $training;
        $data['total_pending_bookings']   = $pendingwalking + $pendingboarding + $pendinggrooming + $pendingtraining;
        $data['revenue']                  = $this->booking->getRevenue();
        $data['profit']                   = $this->booking->getProfit();
        $data['recent_bookings']          = $this->booking->getrecentBookings();
        $data['top_customers']            = $this->booking->getTopCustomers();
        $data['totalCancelled']           = $this->booking->getCancelledBookingsCount('Cancelled');
        $data['noBidsCount']             = $this->booking->getNoBidsBookingsCount('New');
        $data['noActionBookingsCount']   = $this->booking->getNoActionBookingsCount('New');
        $data['noShowsCount']             = $this->booking->getNoShowsTodayCount();
        $data['servicesEndingSoonCount']  = $this->booking->getServicesEndingSoonCount();
        $data['haltedBookingsCount']      = $this->booking->getHaltedBookingsCount();
        $data['reviews']                  = $this->booking->getReviews();
        $data['disputes']                 = count($this->booking->getGroomingDisputes());
        $data['sus_walkers']              = $this->booking->getSuSWalkers();
        $data['request_amount']           = $this->booking->getSPRequestAmount()->total_amount;
        $data['released_amount']          = $this->booking->getSPRealsedAmount()->total_amount;
        $data['payout_request']           = $this->booking->getPaymentRequests();
        $data['most_requests']            = $this->booking->getMostRequests();
        $data['providers']                = $this->booking->getProvidersCount();
        $smsCredits = $this->getSMSCredits();
        $data['sms_credits'] = $smsCredits;

        $payoutBalance = $this->getPayoutBalance();

        $data['payout_balance'] = $payoutBalance;
        $data['insurance_stats'] = $this->insuranceModel->getInsuranceStats();

        $db = \Config\Database::connect();
        $data['suspicious_visits_count'] = $db->tableExists('suspicious_visits') ? $db->table('suspicious_visits')->countAllResults() : 0;
        $data['recent_suspicious_visits'] = $db->tableExists('suspicious_visits') ? $db->table('suspicious_visits')->orderBy('created_at', 'DESC')->limit(5)->get()->getResult() : [];
        $verification_executives = $db->table('employees')
            // ->whereIn('role_id', [7, 4,10])
            ->where('status', 1)
            ->get()->getResult();

        foreach ($verification_executives as $exec) {
            $exec->call_verification_count = $db->table('sp_reviews')
                ->where('admin_id', $exec->id)
                ->where('reviewed_by', 'admin')
                ->countAllResults();

            $exec->manager_approval_count = $db->table('sp_services')
                ->join('sp_reviews', 'sp_reviews.provider_id = sp_services.provider_id AND sp_reviews.service_id = sp_services.service_id')
                ->where('sp_reviews.admin_id', $exec->id)
                ->where('sp_reviews.reviewed_by', 'admin')
                ->whereIn('sp_services.status', ['approved', 'active'])
                ->countAllResults();

            // Total distinct SPs handled by this executive
            $exec->total_sp_count = $db->table('sp_reviews')
                ->select('provider_id')
                ->distinct()
                ->where('admin_id', $exec->id)
                ->where('reviewed_by', 'admin')
                ->countAllResults();
        }
        $data['verification_executives'] = $verification_executives;
        $data['active_customers_count'] = $this->booking->getActiveCustomersCount();
        $revenue = max($data['revenue'] ?? 0, 1);
        $data['margin'] = round(($data['profit'] / $revenue) * 100, 1);
        $data['revenue_by_service'] = $this->booking->getRevenueByService();
        $data['monthly_revenue'] = $this->booking->getMonthlyRevenue();

        return $this->loadView('admin/dashboard', $data);
    }
    public function getMonthlyRevenue()
    {
        $query = $this->booking->getMonthlyRevenue();
        return $this->response->setJSON($query);
    }

    public function getSidebarCounts()
    {
        $this->booking = new \App\Models\Booking();

        $supports = $this->booking->getSupport('service_provider');
        $supportCount = 0;
        foreach ($supports as $support) {
            if ($support->status != 'completed') {
                $supportCount++;
            }
        }

        $clsupports = $this->booking->getSupport('client');
        $clsupportCount = 0;
        foreach ($clsupports as $clsupport) {
            if ($clsupport->status == 'open') {
                $clsupportCount++;
            }
        }

        $counts = [
            'bookingRequests'   => $this->booking->getBookingStatusCount('New'),
            'Confirmedbookings' => $this->booking->getBookingStatusCount('Confirmed'),
            'Haltedbookings'    => $this->booking->getBookingStatusCount('Halted'),
            'Cancelledbookings' => $this->booking->getBookingStatusCount('Cancelled'),
            'Deletedbookings'   => $this->booking->getBookingStatusCount('deleted'),
            'Completedbookings' => $this->booking->getBookingStatusCount('Completed'),
            'missedwalks'       => count($this->booking->getUntrackedWalks()),
            'providerPayouts'   => $this->booking->getPaymentRequests(),
            'refundRequests'    => $this->booking->getRefundRequestsCount(),
            'clsupports'        => $clsupportCount,
            'spsupports'        => $supportCount,
            'pending_rewards' => $this->booking->getPendingRewardOrdersCount(),
            'delivered_rewards' => $this->booking->getDeliveredRewardOrdersCount()
        ];

        return $this->response->setJSON($counts);
    }
    public function tax()
    {
        $data['taxes'] = $this->model->getTaxes();
        return $this->loadView('admin/tax', $data);
    }
    public function clientSpendings()
    {
        $data['spendings'] = $this->booking->getClientSpendings();

        return $this->loadView('admin/user/spendings', $data);
    }
    public function companyEarnings()
    {
        $data['earnings'] = $this->booking->getCompletedBookingEarnings();
        return $this->loadView('admin/company_earnings', $data);
    }
    public function dailyBookings()
    {
        $data['bookings'] = $this->booking->getDailyBookings();
        return $this->loadView('admin/daily_bookings', $data);
    }
    public function addTax()
    {
        $data = [
            'title'       => $this->request->getPost('title'),
            'value'       => $this->request->getPost('value'),
            'type'        => $this->request->getPost('type'),
            'module_type' => $this->request->getPost('module_type'),
            'status'      => $this->request->getPost('status'),
        ];

        if ($this->model->addTax($data)) {
            return $this->response->setJSON(['status' => true, 'message' => 'Tax added successfully']);
        } else {
            return $this->response->setJSON(['status' => false, 'message' => 'Failed to add tax']);
        }
    }
    public function permissions()
    {
        $permModel = new \App\Models\PermissionModel();
        $data['permissions']        = $permModel->getPermissions();
        $data['groupedPermissions'] = $permModel->getPermissionsGroupedByModule();
        $data['roles']              = $permModel->getRoles();
        $data['employees']          = $permModel->getEmployeesWithRoles();

        return $this->loadView('admin/services/permissions', $data);
    }

    public function getPermissions()
    {
        $userId    = (int)($this->request->getPost('user_id') ?: $this->request->getGet('user_id'));
        $permModel = new \App\Models\PermissionModel();
        $permissions = $permModel->getPermissionsByUserId($userId);

        return $this->response->setJSON($permissions);
    }

    public function getAllPermissions()
    {
        $permModel   = new \App\Models\PermissionModel();
        $permissions = $permModel->getPermissions();

        return $this->response->setJSON($permissions);
    }

    public function getRolePermissions()
    {
        $roleId = (int)$this->request->getPost('role_id');
        $permModel = new \App\Models\PermissionModel();
        $assignedIds = $permModel->getRolePermissionIds($roleId);

        return $this->response->setJSON([
            'status'          => true,
            'role_id'         => $roleId,
            'permission_ids'  => $assignedIds
        ]);
    }

    public function getUserPermissions()
    {
        $userId = (int)$this->request->getPost('user_id');
        $permModel = new \App\Models\PermissionModel();
        $data = $permModel->getUserEffectivePermissions($userId);

        return $this->response->setJSON([
            'status' => true,
            'data'   => $data
        ]);
    }

    public function saveRolePermissions()
    {
        $roleId      = (int)$this->request->getPost('role_id');
        $permissions = $this->request->getPost('permissions') ?: [];

        if (!$roleId) {
            return $this->response->setJSON(['status' => false, 'message' => 'Please select a valid role.']);
        }

        $permModel = new \App\Models\PermissionModel();
        $success   = $permModel->assignRolePermissions($roleId, $permissions);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'status'  => $success,
                'message' => $success ? 'Role permissions updated successfully.' : 'Failed to update role permissions.'
            ]);
        }

        return redirect()->back()->with($success ? 'success' : 'error', $success ? 'Role permissions updated successfully.' : 'Failed to update role permissions.');
    }

    public function toggleRolePermission()
    {
        $roleId       = (int)$this->request->getPost('role_id');
        $permissionId = (int)$this->request->getPost('permission_id');
        $enable       = (bool)$this->request->getPost('enable');

        if (!$roleId || !$permissionId) {
            return $this->response->setJSON(['status' => false, 'message' => 'Invalid parameters.']);
        }

        $permModel = new \App\Models\PermissionModel();
        $success   = $permModel->toggleRolePermission($roleId, $permissionId, $enable);

        return $this->response->setJSON([
            'status'  => $success,
            'message' => $success ? 'Permission toggled.' : 'Failed to toggle permission.'
        ]);
    }

    public function saveUserPermissions()
    {
        $userId      = (int)$this->request->getPost('user_id');
        $permissions = $this->request->getPost('permissions') ?: [];

        if (!$userId) {
            return $this->response->setJSON(['status' => false, 'message' => 'Please select a valid user.']);
        }

        $permModel = new \App\Models\PermissionModel();
        $success   = $permModel->assignUserPermissions($userId, $permissions);

        return $this->response->setJSON([
            'status'  => $success,
            'message' => $success ? 'Direct user permissions & overrides updated successfully.' : 'Failed to update user permissions.'
        ]);
    }

    public function toggleUserPermission()
    {
        $userId       = (int)$this->request->getPost('user_id');
        $permissionId = (int)$this->request->getPost('permission_id');
        $enable       = (bool)$this->request->getPost('enable');

        if (!$userId || !$permissionId) {
            return $this->response->setJSON(['status' => false, 'message' => 'Invalid parameters.']);
        }

        $permModel = new \App\Models\PermissionModel();
        $success   = $permModel->toggleUserPermission($userId, $permissionId, $enable);

        return $this->response->setJSON([
            'status'  => $success,
            'message' => $success ? 'User permission override toggled.' : 'Failed to toggle user permission.'
        ]);
    }

    public function saveRole()
    {
        $roleId      = (int)$this->request->getPost('role_id');
        $name        = trim($this->request->getPost('name'));
        $description = trim($this->request->getPost('description'));

        if (empty($name)) {
            return $this->response->setJSON(['status' => false, 'message' => 'Role name is required.']);
        }

        $permModel = new \App\Models\PermissionModel();

        if ($roleId > 0) {
            $success = $permModel->updateRole($roleId, ['name' => $name, 'description' => $description]);
            $msg = 'Role updated successfully.';
        } else {
            $newId = $permModel->createRole(['name' => $name, 'description' => $description]);
            $success = $newId > 0;
            $msg = 'New role created successfully.';
        }

        return $this->response->setJSON(['status' => $success, 'message' => $msg]);
    }

    public function deleteRole()
    {
        $roleId = (int)$this->request->getPost('role_id');
        if ($roleId === 1) {
            return $this->response->setJSON(['status' => false, 'message' => 'Super Admin role cannot be deleted.']);
        }

        $permModel = new \App\Models\PermissionModel();
        $success   = $permModel->deleteRole($roleId);

        return $this->response->setJSON([
            'status'  => $success,
            'message' => $success ? 'Role deleted successfully.' : 'Failed to delete role.'
        ]);
    }

    public function addPermission()
    {
        $name   = trim($this->request->getPost('name'));
        $module = trim($this->request->getPost('module'));

        if (empty($name) || empty($module)) {
            return redirect()->back()->with('error', 'Module and permission type are required.');
        }

        $permModel = new \App\Models\PermissionModel();
        $permModel->createPermission(['name' => $name, 'module' => $module]);

        return redirect()->back()->with('success', 'New permission added successfully.');
    }

    public function deletePermission()
    {
        $permissionId = (int)$this->request->getPost('permission_id');
        $permModel    = new \App\Models\PermissionModel();
        $success      = $permModel->deletePermission($permissionId);

        return $this->response->setJSON([
            'status'  => $success,
            'message' => $success ? 'Permission deleted successfully.' : 'Failed to delete permission.'
        ]);
    }

    public function assignUserRole()
    {
        $userId = (int)$this->request->getPost('user_id');
        $roleId = (int)$this->request->getPost('role_id');

        if (!$userId || !$roleId) {
            return $this->response->setJSON(['status' => false, 'message' => 'User ID and Role ID are required.']);
        }

        $permModel = new \App\Models\PermissionModel();
        $success   = $permModel->assignUserRole($userId, $roleId);

        return $this->response->setJSON([
            'status'  => $success,
            'message' => $success ? 'User role updated successfully.' : 'Failed to update user role.'
        ]);
    }
    public function pets()
    {
        $data['pets'] = $this->model->getPets();
        return $this->loadView('admin/services/pets', $data);
    }
    public function breeds($type)
    {
        $data['breeds']   = $this->model->getBreeds($type);
        $data['petTypes'] = $this->model->getPets();

        return $this->loadView('admin/services/breeds', $data);
    }
    public function addBreed()
    {
        $data = [
            'value'    => strtolower($this->request->getPost('breed')),
            'label'    => strtoupper($this->request->getPost('breed')),
            'status'   => $this->request->getPost('status'),
            'pet_type' => $this->request->getPost('pet_type_id'),
        ];
        $db = \Config\Database::connect();
        $db->table('dog_breeds')->insert($data);
        return $this->response->setJSON(['status' => true, 'message' => 'Breed added successfully']);
    }

    public function getBreed()
    {
        $id = $this->request->getPost('id');
        $db = \Config\Database::connect();
        $breed = $db->table('dog_breeds')->where('id', $id)->get()->getRow();
        if ($breed) {
            return $this->response->setJSON(['status' => true, 'data' => $breed]);
        } else {
            return $this->response->setJSON(['status' => false, 'message' => 'Breed not found']);
        }
    }

    public function updateBreed()
    {
        $id = $this->request->getPost('id');
        $data = [
            'pet_type' => $this->request->getPost('pet_type_id'),
            'label'    => strtoupper($this->request->getPost('breed')),
            'value'    => strtolower($this->request->getPost('breed')),
            'status'   => $this->request->getPost('status') == 'on' ? '1' : '0',
        ];
        $db = \Config\Database::connect();
        $db->table('dog_breeds')->where('id', $id)->update($data);
        return $this->response->setJSON(['status' => true, 'message' => 'Breed updated successfully']);
    }

    public function deleteBreed()
    {
        $id = $this->request->getPost('id');
        $db = \Config\Database::connect();
        $db->table('dog_breeds')->where('id', $id)->delete();
        return $this->response->setJSON(['status' => true, 'message' => 'Breed deleted successfully']);
    }

    public function bulkDeleteBreeds()
    {
        $ids = $this->request->getPost('ids');
        if (empty($ids)) {
            return $this->response->setJSON(['status' => false, 'message' => 'No breeds selected']);
        }
        $db = \Config\Database::connect();
        $db->table('dog_breeds')->whereIn('id', $ids)->delete();
        return $this->response->setJSON(['status' => true, 'message' => 'Selected breeds deleted successfully']);
    }
    public function addPetType()
    {
        $data = [
            'name'   => $this->request->getPost('name'),
            'status' => $this->request->getPost('status'),
        ];
        if ($this->model->insertPetType($data)) {
            return $this->response->setJSON(['status' => true, 'message' => 'Pet type added successfully']);
        } else {
            return $this->response->setJSON(['status' => false, 'message' => 'Failed to add Pet type']);
        }
    }
    public function updatePetType()
    {
        $data = [
            'name'   => $this->request->getPost('name'),
            'status' => $this->request->getPost('status'),
            'id'     => $this->request->getPost('id'),
        ];
        if ($this->model->updatePetType($data)) {
            return $this->response->setJSON(['status' => true, 'message' => 'Pet type added successfully']);
        } else {
            return $this->response->setJSON(['status' => false, 'message' => 'Failed to add Pet type']);
        }
    }
    public function deletePetType()
    {
        $id = $this->request->getPost('id');
        $this->model->deletePetType($id);
        return $this->response->setJSON(['status' => true, 'message' => ' Pet type deleted successfully']);
    }
    public function Pet()
    {
        $id  = $this->request->getPost('id');
        $pet = $this->model->getPetType($id);
        return $this->response->setJSON(['status' => true, 'data' => $pet]);
    }

    public function notifications()
    {
        $data['notifications'] = $this->model->getNotifications();

        return $this->loadView('admin/services/notifications', $data);
    }
    public function addNotification()
    {
        $title  = $this->request->getPost('label');
        $type   = $this->request->getPost('type');
        $status = $this->request->getPost('status');

        $data = [
            'title'  => $title,
            'type'   => $type,
            'status' => $status,
        ];

        $this->model->addNotification($data);
        return $this->response->setJSON(['status' => true, 'message' => 'Notification added successfully']);
    }

    public function getNotification()
    {
        $id           = $this->request->getPost('id');
        $notification = $this->model->getNotification($id);
        return $this->response->setJSON(['status' => true, 'data' => $notification]);
    }
    public function deleteNotification()
    {
        $id = $this->request->getPost('id');
        $this->model->deleteNotification($id);
        return $this->response->setJSON(['status' => true, 'message' => ' Notification deleted successfully']);
    }
    public function updateNotification()
    {
        $id     = $this->request->getPost('id');
        $title  = $this->request->getPost('label');
        $type   = $this->request->getPost('type');
        $status = $this->request->getPost('status');
        $data   = [
            'title'  => $title,
            'type'   => $type,
            'status' => $status,
        ];
        $this->model->updateNotification($id, $data);
        return $this->response->setJSON(['status' => true, 'message' => ' Notification deleted successfully']);
    }


    public function getMonthlyBookings()
    {
        $results = $this->booking->getMonthlyBookings();

        $labels = [];
        $data   = [];

        foreach ($results as $row) {
            $labels[] = $row->category;
            $data[]   = $row->total_bookings;
        }
        return $this->response->setJSON([
            'labels' => $labels,
            'data'   => $data,
        ]);
    }
    public function getProfitData()
    {
        $timeframe = $this->request->getGet('timeframe'); // 'week', 'month', 'year'
        $db        = \Config\Database::connect();

        if ($timeframe == 'week') {
            // Get profits for the last 7 days

            $query   = $db->query("
          SELECT DATE_FORMAT(walking_service_bookings.created_at, '%Y-%m') AS month, 
                 SUM(quotations.bid_amount) AS revenue,
                 SUM(quotations.cost_amount) AS cost,  -- Assuming you store service costs
                 (SUM(quotations.bid_amount) - SUM(quotations.cost_amount)) AS profit
          FROM quotations
          LEFT JOIN walking_service_bookings ON walking_service_bookings.id = quotations.booking_id
          WHERE walking_service_bookings.status = 'Completed'
          AND quotations.status = 'Accepted'
          AND
          WHERE created_at >= DATE_SUB(CURRENT_DATE(), INTERVAL 6 DAY)
  
          GROUP BY month
          ORDER BY month ASC
      ");
            $results = $query->getResult();
            $labels  = array_map(fn($row) => $row->date, $results);
            $profits = array_map(fn($row) => (float) $row->total_profit, $results);
        } elseif ($timeframe == 'month') {
            // Get profits for the current month

            $query   = $db->query("
          SELECT DATE_FORMAT(walking_service_bookings.created_at, '%Y-%m') AS month, 
                 SUM(quotations.bid_amount) AS revenue,
                 SUM(quotations.platform_charges) AS cost,
                 (SUM(quotations.bid_amount) - SUM(quotations.cost_amount)) AS profit
          FROM quotations
          LEFT JOIN walking_service_bookings ON walking_service_bookings.id = quotations.booking_id
          WHERE walking_service_bookings.status = 'Completed'
          AND quotations.status = 'Accepted'
          AND
          WHERE MONTH(created_at) = MONTH(CURRENT_DATE())
          AND YEAR(created_at) = YEAR(CURRENT_DATE())
  
          GROUP BY month

        UNION ALL
        SELECT DATE_FORMAT(grooming_service_bookings.created_at, '%Y-%m') AS month, 
                 SUM(quotations.bid_amount) AS revenue,
                 SUM(quotations.platform_charges) AS cost,
                 (SUM(quotations.bid_amount) - SUM(quotations.cost_amount)) AS profit
          FROM quotations
          LEFT JOIN grooming_service_bookings ON grooming_service_bookings.id = quotations.booking_id
          WHERE grooming_service_bookings.status = 'Completed'
          AND quotations.status = 'Accepted'
          AND
          WHERE MONTH(created_at) = MONTH(CURRENT_DATE())
          AND YEAR(created_at) = YEAR(CURRENT_DATE())
  
          GROUP BY month

        UNION ALL

      SELECT DATE_FORMAT(training_service_bookings.created_at, '%Y-%m') AS month, 
                 SUM(quotations.bid_amount) AS revenue,
                 SUM(quotations.platform_charges) AS cost,
                 (SUM(quotations.bid_amount) - SUM(quotations.cost_amount)) AS profit
          FROM quotations
          LEFT JOIN training_service_bookings ON training_service_bookings.id = quotations.booking_id
          WHERE training_service_bookings.status = 'Completed'
          AND quotations.status = 'Accepted'
          AND
          WHERE MONTH(created_at) = MONTH(CURRENT_DATE())
          AND YEAR(created_at) = YEAR(CURRENT_DATE())
  
          GROUP BY month


          ORDER BY month ASC
      ");
            $results = $query->getResult();
            $labels  = array_map(fn($row) => "Day " . $row->day, $results);
            $profits = array_map(fn($row) => (float) $row->total_profit, $results);
        } else {
            // Get profits for the last 12 months

            $query   = $db->query("
          SELECT DATE_FORMAT(walking_service_bookings.created_at, '%Y-%m') AS month, 
                 SUM(quotations.bid_amount) AS revenue,
                 SUM(quotations.platform_charges) AS cost,  -- Assuming you store service costs
                 (SUM(quotations.bid_amount) - SUM(quotations.cost_amount)) AS profit
          FROM quotations
          LEFT JOIN walking_service_bookings ON walking_service_bookings.id = quotations.booking_id
          WHERE walking_service_bookings.status = 'Completed'
          AND quotations.status = 'Accepted'
          AND
                     WHERE YEAR(created_at) = YEAR(CURRENT_DATE())
  
  
          GROUP BY month

      UNION ALL
        SELECT DATE_FORMAT(grooming_service_bookings.created_at, '%Y-%m') AS month, 
                 SUM(quotations.bid_amount) AS revenue,
                 SUM(quotations.platform_charges) AS cost,
                 (SUM(quotations.bid_amount) - SUM(quotations.cost_amount)) AS profit
          FROM quotations
          LEFT JOIN grooming_service_bookings ON grooming_service_bookings.id = quotations.booking_id
          WHERE grooming_service_bookings.status = 'Completed'
          AND quotations.status = 'Accepted'
          AND 
          YEAR(created_at) = YEAR(CURRENT_DATE())
  
          GROUP BY month

        UNION ALL

      SELECT DATE_FORMAT(training_service_bookings.created_at, '%Y-%m') AS month, 
                 SUM(quotations.bid_amount) AS revenue,
                 SUM(quotations.platform_charges) AS cost,
                 (SUM(quotations.bid_amount) - SUM(quotations.cost_amount)) AS profit
          FROM quotations
          LEFT JOIN training_service_bookings ON training_service_bookings.id = quotations.booking_id
          WHERE training_service_bookings.status = 'Completed'
          AND quotations.status = 'Accepted'
          AND YEAR(created_at) = YEAR(CURRENT_DATE())
  
          GROUP BY month


          ORDER BY month ASC
      ");
            $results = $query->getResult();
            $labels  = array_map(fn($row) => $row->month, $results);
            $profits = array_map(fn($row) => (float) $row->total_profit, $results);
        }

        return $this->response->setJSON(['labels' => $labels, 'profits' => $profits]);
    }

    public function getBookingStats($period)
    {
        $db = \Config\Database::connect();

        $dateFilter = "";
        if ($period == 'week') {
            $dateFilter = "YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1)";
        } elseif ($period == 'month') {
            $dateFilter = "MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())";
        } elseif ($period == 'year') {
            $dateFilter = "YEAR(created_at) = YEAR(CURDATE())";
        }

        $query = $db->query("
        SELECT DATE(created_at) AS date, COUNT(id) AS total_bookings
        FROM walking_service_bookings
        WHERE $dateFilter
        GROUP BY date

        UNION ALL

        SELECT DATE(created_at) AS date,
        COUNT(id) AS total_bookings
        FROM training_service_bookings
        WHERE $dateFilter 
        GROUP BY date

        UNION ALL

        SELECT DATE(created_at) AS date,
        COUNT(id) AS total_bookings 
        FROM grooming_service_bookings 
        WHERE $dateFilter
        GROUP BY date

        ORDER BY date ASC
    ");

        $results = $query->getResult();

        // Extract data for chart
        $labels = array_map(fn($row) => $row->date, $results);
        $data   = array_map(fn($row) => (int) $row->total_bookings, $results);

        return $this->response->setJSON(['labels' => $labels, 'data' => $data]);
    }

    public function getBookingStatusData($period)
    {
        $db = \Config\Database::connect();

        // Define time filters based on the period
        $dateFilter = "";
        if ($period == 'week') {
            $dateFilter = "YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1)";
        } elseif ($period == 'month') {
            $dateFilter = "MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())";
        } elseif ($period == 'year') {
            $dateFilter = "YEAR(created_at) = YEAR(CURDATE())";
        }

        $query = $db->query("
        SELECT status, COUNT(id) AS total_bookings 
        FROM walking_service_bookings 
        WHERE $dateFilter 
        GROUP BY status

        UNION ALL
        SELECT status, COUNT(id) AS total_bookings
        FROM training_service_bookings
        WHERE $dateFilter
        GROUP BY status

        UNION ALL
        SELECT status, COUNT(id) AS total_bookings 
        FROM grooming_service_bookings 
        WHERE $dateFilter
        GROUP BY status
    ");

        $results = $query->getResult();

        // Map statuses with their respective counts
        $statuses = ['Pending' => 0, 'Confirmed' => 0, 'Cancelled' => 0, 'Rejected' => 0, 'Completed' => 0];

        foreach ($results as $row) {
            $statuses[$row->status] = (int) $row->total_bookings;
        }

        return $this->response->setJSON($statuses);
    }

    public function getTopServices($timeframe = 'month')
    {
        $db = \Config\Database::connect();

        // Get date condition for each table
        $walkingCondition  = $this->getDateCondition($timeframe, 'walking_service_bookings');
        $trainingCondition = $this->getDateCondition($timeframe, 'training_service_bookings');
        $groomingCondition = $this->getDateCondition($timeframe, 'grooming_service_bookings');

        // First get total bookings across all services
        $totalQuery = $db->query("
        SELECT SUM(total) as grand_total FROM (
            SELECT COUNT(*) as total FROM walking_service_bookings 
            WHERE status = 'Completed' {$walkingCondition}
            UNION ALL
            SELECT COUNT(*) FROM training_service_bookings 
            WHERE status = 'Completed' {$trainingCondition}
            UNION ALL
            SELECT COUNT(*) FROM grooming_service_bookings 
            WHERE status = 'Completed' {$groomingCondition}
        ) as totals
    ");
        $grandTotal = $totalQuery->getRow()->grand_total ?? 1;

        // Then get top services
        $query = $db->query("
        SELECT 
            service_name, 
            SUM(total_bookings) as total_bookings
        FROM (
            SELECT 
                services.name as service_name, 
                COUNT(walking_service_bookings.id) AS total_bookings
            FROM walking_service_bookings
            LEFT JOIN services ON services.id = walking_service_bookings.service_id
            WHERE walking_service_bookings.status = 'Completed'
            {$walkingCondition}
            GROUP BY services.name

            UNION ALL

            SELECT 
                services.name as service_name,
                COUNT(training_service_bookings.id) AS total_bookings
            FROM training_service_bookings
            LEFT JOIN services ON services.id = training_service_bookings.service_id
            WHERE training_service_bookings.status = 'Completed'
            {$trainingCondition}
            GROUP BY services.name

            UNION ALL

            SELECT
                services.name as service_name,
                COUNT(grooming_service_bookings.id) AS total_bookings
            FROM grooming_service_bookings
            LEFT JOIN services ON services.id = grooming_service_bookings.service_id
            WHERE grooming_service_bookings.status = 'Completed'
            {$groomingCondition}
            GROUP BY services.name
        ) as combined_results
        GROUP BY service_name
        ORDER BY total_bookings DESC
        LIMIT 5
    ");

        $results = $query->getResult();

        foreach ($results as $result) {
            $result->percentage = round(($result->total_bookings / $grandTotal) * 100, 1);
        }

        return $this->response->setJSON($results);
    }

    protected function getDateCondition($timeframe, $tableName)
    {
        switch ($timeframe) {
            case 'week':
                return "AND YEARWEEK($tableName.created_at, 1) = YEARWEEK(CURDATE(), 1)";
            case 'month':
                return "AND MONTH($tableName.created_at) = MONTH(CURDATE()) 
                   AND YEAR($tableName.created_at) = YEAR(CURDATE())";
            case 'year':
                return "AND YEAR($tableName.created_at) = YEAR(CURDATE())";
            default:
                return "";
        }
    }
    public function clientWallets()
    {
        $data['wallets'] = $this->booking->getClientWallets();
        $data['refunds'] = $this->booking->getClientRefunds();


        return $this->loadView('admin/user/client-wallets', $data);
    }

    public function tutorials()
    {
        $data['tutorials'] = $this->booking->getTutorials();
        foreach ($data['tutorials'] as $tutorial) {
            $tutorial->embedUrl = $this->getEmbedUrl($tutorial->link);
        }
        return $this->loadView('admin/tutorials', $data);
    }
    public function saveTutorial()
    {
        $db = \Config\Database::connect();

        if ($this->request->getMethod() === 'POST') {
            $id = $this->request->getPost('id');

            $data = [
                'user_type'    => $this->request->getPost('user_type'),
                'service_type' => $this->request->getPost('service_type'),
                'link'         => $this->request->getPost('link'),
                'description'  => $this->request->getPost('description'),
                'title'        => $this->request->getPost('title'),
            ];

            $rules = [
                'user_type'    => 'required|in_list[pet_owner,service_provider]',
                'service_type' => 'required|in_list[walking,grooming,training,boarding]',
                'link'         => 'required|valid_url',
                'description'  => 'required|min_length[3]',
                'title'        => 'required|min_length[3]',
            ];

            // Handle thumbnail
            $thumbnail = $this->request->getFile('thumbnail');
            if ($thumbnail && $thumbnail->isValid() && !$thumbnail->hasMoved()) {
                $newName = $thumbnail->getRandomName();
                $thumbnail->move('public/uploads', $newName);
                $data['thumbnail'] = $newName;
            } else {
                // keep old thumbnail if updating
                if ($id) {
                    $existing = $db->table('tutorials')->where('id', $id)->get()->getRow();
                    if ($existing) {
                        $data['thumbnail'] = $existing->thumbnail;
                    }
                } else {
                    $data['thumbnail'] = ''; // new record without file
                }
            }

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            if ($id) {
                // update case
                $data['updated_at'] = date('Y-m-d H:i:s');
                $db->table('tutorials')->where('id', $id)->update($data);
                return redirect()->back()->with('success', 'Tutorial updated successfully.');
            } else {
                // insert case
                $data['created_at'] = date('Y-m-d H:i:s');
                $db->table('tutorials')->insert($data);
                return redirect()->back()->with('success', 'Tutorial added successfully.');
            }
        }

        return redirect()->back()->with('error', 'Invalid request method.');
    }


    private function getEmbedUrl($url)
    {
        if (strpos($url, 'youtube.com/watch') !== false) {
            parse_str(parse_url($url, PHP_URL_QUERY), $query);
            return 'https://www.youtube.com/embed/' . $query['v'];
        } elseif (strpos($url, 'youtu.be/') !== false) {
            $parts = explode('/', parse_url($url, PHP_URL_PATH));
            return 'https://www.youtube.com/embed/' . end($parts);
        } else {
            return $url;
        }
    }
    public function supports()
    {
        $supports         = $this->booking->getSupport('client');
        $filteredSupports = [];

        foreach ($supports as $support) {
            if ($support->status == 'completed') {
                continue;
            }
            $filteredSupports[] = $support;
        }

        return $this->response->setJSON($filteredSupports);
    }
    public function updateProviderFeedback()
    {
        $providerId = $this->request->getPost('provider_id');
        $rating     = $this->request->getPost('rating');
        $comment    = $this->request->getPost('comment');
        $adminId    = 25;

        $builder = $this->booking->db->table('sp_reviews');

        $existing = $builder->where([
            'provider_id' => $providerId,
            'reviewed_by' => 'admin',
            'admin_id'    => $adminId
        ])->get()->getRow();

        if ($existing) {
            // Update existing review
            $builder->where('id', $existing->id)
                ->update([
                    'rating'    => $rating,
                    'comment'   => $comment,
                    'update_at' => date('Y-m-d H:i:s'),
                ]);
            $message = 'Feedback updated successfully';
        } else {
            // Insert new review
            $builder->insert([
                'provider_id' => $providerId,
                'rating'      => $rating,
                'comment'     => $comment,
                'admin_id'    => $adminId,
                'reviewed_by' => 'admin',
                'created_at'  => date('Y-m-d H:i:s'),
            ]);
            $message = 'Feedback added successfully';
        }

        return $this->response->setJSON([
            'status'  => true,
            'message' => $message
        ]);
    }

    public function updateVerification()
    {
        $id          = $this->request->getPost('id');
        $is_verified = $this->request->getPost('is_verified');

        $builder = $this->booking->db->table('service_providers');
        $builder->where('id', $id)->update([
            'is_verified' => $is_verified,
        ]);

        return $this->response->setJSON(['status' => true, 'message' => 'Updated successfully']);
    }

    public function deleteTutorial($id)
    {
        $db = \Config\Database::connect();

        // find tutorial
        $tutorial = $db->table('tutorials')->where('id', $id)->get()->getRow();

        if (!$tutorial) {
            return redirect()->back()->with('error', 'Tutorial not found.');
        }

        // delete thumbnail file if exists
        if (!empty($tutorial->thumbnail)) {
            $filePath = FCPATH . 'public/uploads/' . $tutorial->thumbnail;
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        // delete record
        $db->table('tutorials')->where('id', $id)->delete();

        return redirect()->back()->with('success', 'Tutorial deleted successfully.');
    }

    public function newBookings($type)
    {
        $bookings = $this->booking->getBookingStatusData(strtolower($type));
        return $this->response->setJSON($bookings);
    }
    public function supportTickets()
    {
        $support = $this->booking->getSupport('client');
        $last10  = array_slice($support, -10);
        return $this->response->setJSON($support);
    }
    public function failedTransactions()
    {
        $transactions = $this->booking->getfailedTransactions();
        $last10       = array_slice($transactions, -10);
        $payments     = [];
        foreach ($last10 as $payment) {
            if ($payment->status == 'FAILED') {
                $payment->reason = phonepeResponseDescription($payment->response_code);
            } else {
                $payment->reason = '';
            }
            $payments[] = $payment;
        }
        return $this->response->setJSON($payments);
    }

    public function support()
    {
        $data['supports'] = $this->booking->getSupport('client');

        return $this->loadView('admin/support', $data);
    }
    public function spSupport()
    {
        $supports = $this->booking->getSupport('service_provider');
        foreach ($supports as $support) {
            $support->unread_count = $this->ticketMessageModel->getMessagesToUpdate($support->id, 'service_provider');
        }
        $data['supports'] = $supports;
        return $this->loadView('admin/sp_support', $data);
    }
    public function supportChat($ticketId)
    {
        $ticket   = $this->ticketModel->getTicket($ticketId);
        $messages = $this->ticketModel->getTicketMessage($ticketId);

        $messagesToUpdate = $this->ticketMessageModel->getMessagesToUpdate($ticketId, 'client');
        if ($messagesToUpdate > 0) {
            $this->ticketMessageModel->markAsRead($ticketId, 'client');
        }
        $data['chat']     = (object) $ticket;
        $data['messages'] = $messages;

        return $this->loadView('admin/support_chat', $data);
    }

    public function spSupportChat($ticketId)
    {
        $ticket   = $this->ticketModel->getTicket($ticketId);
        $messages = $this->ticketModel->getTicketMessage($ticketId);

        $messagesToUpdate = $this->ticketMessageModel->getMessagesToUpdate($ticketId, 'service_provider');
        if ($messagesToUpdate > 0) {
            $this->ticketMessageModel->markAsRead($ticketId, 'service_provider');
        }
        $data['chat']     = (object) $ticket;
        $data['messages'] = $messages;

        return $this->loadView('admin/sp_support_chat', $data);
    }


    public function unupdatedPaidBookings()
    {
        $data['unupdatedJobs'] = $this->booking->getUnupdatedPaidBookings();

        return $this->loadView('admin/bookings/unupdated_bookings', $data);
    }
    public function markActive()
    {
        $bookingId   = $this->request->getPost('booking_id');
        $quotationId = $this->request->getPost('quotation_id');
        $serviceType = $this->request->getPost('service_type');

        if (!$bookingId || !$quotationId || !$serviceType) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Missing required parameters.'
            ]);
        }
        if ($serviceType == '4') {
            $table = 'walking_service_bookings';
        } elseif ($serviceType == '3') {
            $table = 'training_service_bookings';
        } elseif ($serviceType == '2') {
            $table = 'grooming_service_bookings';
        }
        // Fetch booking details
        $booking = $this->booking->getBooking($bookingId, $table);
        if (!$booking) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Booking not found.'
            ]);
        }

        $userId = $booking['user_id'];

        // Fetch pets linked to this booking
        $petIds = $this->booking->getPetIdsByBooking($bookingId);
        if (empty($petIds)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'No pets linked to this booking.'
            ]);
        }

        // Fetch quotation details
        $quotation = $this->booking->getQuotationById($quotationId);
        if (!$quotation) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Quotation not found.'
            ]);
        }

        $totalPrice       = (float) $quotation['total_price'];
        $receivableAmount = (float) $quotation['receivable_amount'];
        $petCount         = count($petIds);
        $providerId       = $quotation['provider_id'];

        // Get user's current withdrawal wallet amount
        $withdrawalData   = $this->booking->getWithdrawlAmount($userId);
        $withdrawalAmount = $withdrawalData->amount ?? 0;

        // Wrap in transaction for data safety
        $this->db->transStart();

        $this->processPetWallet(
            $userId,
            $providerId,
            $petIds,
            $withdrawalAmount,
            $totalPrice,
            $receivableAmount,
            $petCount,
            $serviceType
        );

        $this->booking->updateBooking($bookingId, $quotationId, $table);

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Transaction failed. Please try again.'
            ]);
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Booking marked as Active successfully.'
        ]);
    }



    protected function processPetWallet($userId, $providerId, $petIds, $withdrawalAmount, $totalPrice, $receivableAmount, $petCount, $serviceType)
    {
        // -------------------------
        // Handle refund/withdrawal adjustment
        // -------------------------
        if (!is_null($withdrawalAmount) && is_numeric($withdrawalAmount)) {
            $currentWithdraw = (float) $this->booking->getWithdrawlAmount($userId)->amount ?? 0;
            $refundUsed      = $currentWithdraw - $withdrawalAmount;

            // Update wallet with remaining withdrawal balance
            $this->booking->updateWithdrawalWallet($userId, $withdrawalAmount);

            // Log refund usage
            if ($refundUsed > 0) {
                $this->booking->logTransaction(
                    $userId,
                    'debit',
                    $refundUsed,
                    'Refund amount used',
                    'user_wallet_histories'
                );
            }
        }

        // -------------------------
        // Distribute wallet per pet
        // -------------------------
        $userAmount     = $totalPrice / $petCount;
        $providerAmount = $receivableAmount / $petCount;

        foreach ($petIds as $petId) {
            // ---- User Wallet ----
            $userWallet = [
                'user_id'                => $userId,
                'pet_id'                 => $petId,
                $serviceType . '_amount' => $userAmount
            ];

            $this->booking->logTransaction(
                $userId,
                'credit',
                $userAmount,
                ucfirst($serviceType) . " service amount for Pet ID: {$petId}",
                'user_wallet_histories'
            );

            $this->booking->updateWalletAmount($userWallet, $serviceType, 'user_service_wallet');

            // ---- Provider Wallet ----
            $providerWallet = [
                'provider_id'            => $providerId,
                'pet_id'                 => $petId,
                $serviceType . '_amount' => $providerAmount
            ];

            $this->booking->logTransaction(
                $providerId,
                'credit',
                $providerAmount,
                ucfirst($serviceType) . " service amount for Pet ID: {$petId}",
                'sp_wallet_histories'
            );

            $this->booking->updateWalletAmount($providerWallet, $serviceType, 'sp_service_wallet');
        }
    }
    public function userDetails($type, $id)
    {
        $db = \Config\Database::connect();
        $this->booking = new \App\Models\Booking();
        $data['id'] = $id;

        if ($type === 'user') {
            $user = $db->table('users')->where('id', $id)->get()->getRow();
            if (!$user) {
                $this->session->setFlashdata('error', 'Client not found');
                return \redirect()->to(\base_url('dashboard'));
            }

            $data['user'] = $user;
            $data['user_type'] = 'client';
            $data['title'] = 'Client Details';

            // Stats
            $bookingCounts = $db->query("
                SELECT 
                    SUM(cnt) as total_bookings,
                    SUM(CASE WHEN status = 'Completed' THEN cnt ELSE 0 END) as completed_bookings,
                    SUM(CASE WHEN status = 'Cancelled' THEN cnt ELSE 0 END) as cancelled_bookings,
                    SUM(total_spent) as total_spent
                FROM (
                    SELECT b.status, COUNT(*) as cnt, SUM(q.bid_amount) as total_spent 
                    FROM walking_service_bookings b 
                    LEFT JOIN quotations q ON b.id = q.booking_id AND q.status IN ('Accepted', 'Completed') 
                    WHERE b.user_id = ? GROUP BY b.status
                    UNION ALL
                    SELECT b.status, COUNT(*) as cnt, SUM(q.bid_amount) as total_spent 
                    FROM grooming_service_bookings b 
                    LEFT JOIN quotations q ON b.id = q.booking_id AND q.status IN ('Accepted', 'Completed') 
                    WHERE b.user_id = ? GROUP BY b.status
                    UNION ALL
                    SELECT b.status, COUNT(*) as cnt, SUM(q.bid_amount) as total_spent 
                    FROM training_service_bookings b 
                    LEFT JOIN quotations q ON b.id = q.booking_id AND q.status IN ('Accepted', 'Completed') 
                    WHERE b.user_id = ? GROUP BY b.status
                    UNION ALL
                    SELECT b.status, COUNT(*) as cnt, SUM(q.bid_amount) as total_spent 
                    FROM boarding_service_bookings b 
                    LEFT JOIN quotations q ON b.id = q.booking_id AND q.status IN ('Accepted', 'Completed') 
                    WHERE b.user_id = ? GROUP BY b.status
                ) as combined
            ", [$id, $id, $id, $id])->getRow();

            $data['stats'] = [
                'total_bookings'   => $bookingCounts->total_bookings ?? 0,
                'completed_bookings' => $bookingCounts->completed_bookings ?? 0,
                'cancelled_bookings' => $bookingCounts->cancelled_bookings ?? 0,
                'total_spent'      => $bookingCounts->total_spent ?? 0,
                'wallet_balance'   => $this->booking->getWithdrawlAmount($id)->amount ?? 0,
                'service_balance'  => $this->booking->getActualServiceWalletBalance($id) ?? 0,
            ];

            $data['history'] = $this->booking->getWalletSummary2('client', $id);
        } elseif ($type === 'provider') {
            $provider = $db->table('service_providers')->where('id', $id)->get()->getRow();
            if (!$provider) {
                $this->session->setFlashdata('error', 'Service Provider not found');
                return \redirect()->to(\base_url('dashboard'));
            }

            $data['user'] = $provider;
            $data['user_type'] = 'provider';
            $data['title'] = 'Provider Details';

            // Stats
            $jobCounts = $db->query("
                SELECT 
                    COUNT(*) as total_jobs,
                    SUM(CASE WHEN q.status = 'Completed' THEN 1 ELSE 0 END) as completed_jobs,
                    SUM(CASE WHEN q.status = 'Cancelled' THEN 1 ELSE 0 END) as cancelled_jobs,
                    SUM(q.receivable_amount) as total_earned
                FROM quotations q
                WHERE q.provider_id = ? AND q.status IN ('Accepted', 'Completed')
            ", [$id])->getRow();

            $data['stats'] = [
                'total_jobs'       => $jobCounts->total_jobs ?? 0,
                'completed_jobs'   => $jobCounts->completed_jobs ?? 0,
                'cancelled_jobs'   => $jobCounts->cancelled_jobs ?? 0,
                'total_earned'     => $jobCounts->total_earned ?? 0,
                'wallet_balance'   => $this->booking->getTotalWithdrawalAmount($id)->amount ?? 0,
                'pending_balance'  => $this->booking->getSPActualServiceWalletBalance($id) ?? 0,
            ];

            $data['history'] = $this->booking->getWalletSummary2('walker', $id);
        } else {
            $this->session->setFlashdata('error', 'Invalid type');
            return \redirect()->to(\base_url('dashboard'));
        }

        return $this->loadView('admin/user_details', $data);
    }
    public function recoinciliation()
    {
        $data['summary']               = $this->booking->getServiceWalletSummary();
        $client_transactions           = $this->booking->getWalletSummary2('client');
        $walker_transactions           = $this->booking->getWalletSummary2('walker');
        $data['platform_transactions'] = $this->booking->getWalletSummary2('platform');
        $mismatch_alerts               = [];

        $data['total_bookings']    = $this->booking->getTotalBookings()->total_amount;
        $data['walker_payouts']    = $this->booking->getTotalWalkerPayouts()->total_earnings;
        $data['client_refunds']    = $this->booking->getTotalClientRefunds()->total_refunds;
        $data['platform_earnings'] = $this->booking->getTotalPlatformEarings()->total_platform_earnings;

        /**
         * 1. Service Booking mismatches (already implemented)
         */
        foreach ($data['summary'] as $sum) {
            if ($sum['status'] === 'Mismatch') {
                $expected_amount = ($sum['net_booking_amt']);
                $actual_amount   = $sum['walker_payouts'];
                $difference      = $actual_amount - $expected_amount;

                $mismatch_alerts[] = [
                    'type'            => 'booking',
                    'service_id'      => $sum['booking_id'],
                    'expected_amount' => $expected_amount,
                    'actual_amount'   => $actual_amount,
                    'difference'      => $difference,
                    'date'            => date('Y-m-d', strtotime($sum['created_at'] ?? 'now')),
                    'status'          => $sum['status'],
                    'user_type'       => 'Client',
                    'user_name'       => $sum['client_name'],


                ];
            }
        }

        /**
         * 2. Client Wallet mismatches (grouped by client_id)
         */
        foreach ($client_transactions as $txn) {
            $clientId = $txn['client_id'];

            if (!isset($checkedClients[$clientId])) {
                $checkedClients[$clientId] = true;

                // --- Refund Wallet ---
                $expected_refund = $this->booking->getBalanceFromHistory($clientId);
                $actual_refund   = $this->booking->getRefundAmount($clientId);

                if (round($expected_refund, 2) != round($actual_refund, 2)) {
                    $mismatch_alerts[] = [
                        'type'            => 'refund_wallet',
                        'client_id'       => $clientId,
                        'client_name'     => $txn['client_name'],
                        'expected_amount' => $expected_refund,
                        'actual_amount'   => $actual_refund,
                        'difference'      => $actual_refund - $expected_refund,
                        'date'            => date('Y-m-d'),
                        'status'          => 'Mismatch',
                        'user_type'       => 'Client',
                        'user_name'       => $txn['client_name'],
                        'service_id'      => ''
                    ];
                }

                // --- Service Wallet ---
                $expected_service = $this->booking->getExpectedServiceWalletBalance($clientId);
                $actual_service = $this->booking->getActualServiceWalletBalance($clientId);
                if (round($expected_service, 2) != round($actual_service, 2)) {
                    $mismatch_alerts[] = [
                        'type'            => 'service_wallet',
                        'client_id'       => $clientId,
                        'client_name'     => $txn['client_name'],
                        'expected_amount' => $expected_service,
                        'actual_amount'   => $actual_service,
                        'difference'      => $actual_service - $expected_service,
                        'date'            => date('Y-m-d'),
                        'status'          => 'Mismatch',
                        'user_type'       => 'Client',
                        'user_name'       => $txn['client_name'],
                        'service_id'      => ''

                    ];
                }
            }
        }


        /**
         * 3. Walker Wallet mismatches (grouped by walker_id)
         */
        foreach ($walker_transactions as $txn) {
            $walkerId = $txn['walker_id'];

            if (!isset($checkedWalkers[$walkerId])) {  // prevent duplicates
                $checkedWalkers[$walkerId] = true;

                $expected_balance = $this->booking->getSPBalanceFromHistory($walkerId);
                $actual_balance   = $this->booking->getTotalWithdrawalAmount($walkerId)->amount ?? 0;

                if (round($expected_balance, 2) != round($actual_balance, 2)) {
                    $mismatch_alerts[] = [
                        'type'            => 'walker_wallet',
                        'walker_id'       => $walkerId,
                        'walker_name'     => $txn['walker_name'],
                        'expected_amount' => $expected_balance,
                        'actual_amount'   => $actual_balance,
                        'difference'      => $actual_balance - $expected_balance,
                        'date'            => date('Y-m-d'),
                        'status'          => 'Mismatch',
                        'service_id'      => 0,
                        'user_type'       => 'Service Provider',
                        'user_name'       => $txn['walker_name'],
                    ];
                }

                // --- Service Wallet ---
                $expected_service = $this->booking->getSPExpectedServiceWalletBalance($walkerId);
                $actual_service = $this->booking->getSPActualServiceWalletBalance($walkerId);

                $earned_amount = $this->booking->getSPEarnedAmount($walkerId) ?? 0;

                $total_current_value = $actual_service + $earned_amount;
                if (round($expected_service, 2) != round($total_current_value, 2)) {
                    $mismatch_alerts[] = [
                        'type'            => 'walker_wallet',
                        'walker_id'       => $walkerId,
                        'walker_name'     => $txn['walker_name'],
                        'expected_amount' => $expected_service,
                        'actual_amount'   => $actual_service,
                        'difference'      => $total_current_value - $expected_service,
                        'date'            => date('Y-m-d'),
                        'status'          => 'Mismatch',
                        'service_id'      => 0,
                        'user_type'       => 'Service Provider',
                        'user_name'       => $txn['walker_name'],
                    ];
                }
            }
        }


        $bookingPetMap = $this->booking->getBookingPetDetailsMap();

        // Helper to categorize service
        $detectService = function ($row) {
            $rawId     = $row['raw_service_id'] ?? '';
            $bookingId = strtoupper($row['service_id'] ?? $row['booking_id'] ?? '');
            $desc      = strtolower($row['description'] ?? $row['action'] ?? '');

            if (strpos($bookingId, 'WLK') !== false || strpos($desc, 'walk') !== false || $rawId == 4) {
                return 'Walking';
            }
            if (strpos($bookingId, 'GRM') !== false || strpos($desc, 'groom') !== false || $rawId == 2) {
                return 'Grooming';
            }
            if (strpos($bookingId, 'TRN') !== false || strpos($desc, 'train') !== false || $rawId == 3) {
                return 'Training';
            }
            if (strpos($bookingId, 'BRD') !== false || strpos($desc, 'board') !== false || $rawId == 1) {
                return 'Boarding';
            }
            if (strpos($bookingId, 'VET') !== false || strpos($desc, 'vet') !== false || $rawId == 5) {
                return 'Veterinary';
            }
            return 'Walking';
        };

        $defaultServices = ['Walking', 'Grooming', 'Training', 'Boarding', 'Veterinary'];

        // Group clients
        $clients = [];
        foreach ($client_transactions as $txn) {
            $clientId = $txn['client_id'] ?? 0;
            if (!$clientId) continue;

            $serviceName = $detectService($txn);
            $bookingId   = !empty($txn['service_id']) ? $txn['service_id'] : 'GEN_TXN_' . $txn['txn_id'];
            $petInfo     = $bookingPetMap[$bookingId] ?? null;

            $enrichedTxn = [
                'txn_id'           => $txn['txn_id'],
                'booking_id'       => $bookingId,
                'service'          => $serviceName,
                'date'             => $txn['date'],
                'date_formatted'   => date('d M, h:i A', strtotime($txn['date'])),
                'type'             => ucfirst($txn['transaction_type'] ?? ($txn['debit'] > 0 ? 'Debit' : 'Credit')),
                'description'      => $txn['action'],
                'debit'            => (float)($txn['debit'] ?? 0),
                'credit'           => (float)($txn['credit'] ?? 0),
                'service_balance'  => (float)($txn['balance'] ?? 0),
                'refund_balance'   => (float)($txn['refundBalance'] ?? 0),
                'category'         => $txn['category'] ?? '',
                'pet_name'         => $petInfo['pet_name'] ?? 'Pet',
                'pet_breed'        => $petInfo['pet_breed'] ?? 'Dog',
                'job_status'       => $petInfo['status'] ?? ($txn['category'] === 'cancelled_amount' ? 'Cancelled' : 'Completed'),
            ];

            if (!isset($clients[$clientId])) {
                $clients[$clientId] = [
                    'id'                 => $clientId,
                    'client_name'        => $txn['client_name'] ?? 'Client #' . $clientId,
                    'client_phone'       => $txn['client_phone'] ?? '—',
                    'client_email'       => $txn['client_email'] ?? '',
                    'area'               => 'Hyderabad',
                    'joined_date'        => !empty($txn['joined_date']) ? date('M Y', strtotime($txn['joined_date'])) : 'Jun 2026',
                    'total_credited'     => 0,
                    'total_debited'      => 0,
                    'net_balance'        => 0,
                    'refund_amount'      => 0,
                    'transactions_count' => 0,
                    'services'           => [],
                    'transactions'       => []
                ];

                foreach ($defaultServices as $svc) {
                    $clients[$clientId]['services'][$svc] = [
                        'name'          => $svc,
                        'credited'      => 0,
                        'debited'       => 0,
                        'net_balance'   => 0,
                        'refund_amount' => 0,
                        'txns_count'    => 0,
                        'jobs'          => []
                    ];
                }
            }

            $clients[$clientId]['transactions'][] = $enrichedTxn;
            $clients[$clientId]['total_credited'] += $enrichedTxn['credit'];
            $clients[$clientId]['total_debited']  += $enrichedTxn['debit'];
            $clients[$clientId]['net_balance']    = $enrichedTxn['service_balance'];
            $clients[$clientId]['refund_amount']   = $enrichedTxn['refund_balance'];
            $clients[$clientId]['transactions_count']++;

            // Service grouping
            $clients[$clientId]['services'][$serviceName]['credited'] += $enrichedTxn['credit'];
            $clients[$clientId]['services'][$serviceName]['debited']  += $enrichedTxn['debit'];
            $clients[$clientId]['services'][$serviceName]['net_balance'] = $clients[$clientId]['services'][$serviceName]['credited'] - $clients[$clientId]['services'][$serviceName]['debited'];
            if ($clients[$clientId]['services'][$serviceName]['net_balance'] < 0) {
                $clients[$clientId]['services'][$serviceName]['net_balance'] = max(0, $enrichedTxn['service_balance']);
            }
            $clients[$clientId]['services'][$serviceName]['refund_amount'] = max($clients[$clientId]['services'][$serviceName]['refund_amount'], $enrichedTxn['refund_balance']);
            $clients[$clientId]['services'][$serviceName]['txns_count']++;

            // Job grouping
            if (!isset($clients[$clientId]['services'][$serviceName]['jobs'][$bookingId])) {
                $clients[$clientId]['services'][$serviceName]['jobs'][$bookingId] = [
                    'job_id'         => $bookingId,
                    'booked_date'    => date('d M Y', strtotime($txn['date'])),
                    'pet_name'       => $enrichedTxn['pet_name'],
                    'pet_breed'      => $enrichedTxn['pet_breed'],
                    'status'         => $enrichedTxn['job_status'],
                    'credited'       => 0,
                    'debited'        => 0,
                    'refund_amount'  => 0,
                    'net_balance'    => 0,
                    'transactions'   => []
                ];
            }
            $clients[$clientId]['services'][$serviceName]['jobs'][$bookingId]['transactions'][] = $enrichedTxn;
            $clients[$clientId]['services'][$serviceName]['jobs'][$bookingId]['credited'] += $enrichedTxn['credit'];
            $clients[$clientId]['services'][$serviceName]['jobs'][$bookingId]['debited']  += $enrichedTxn['debit'];
            $clients[$clientId]['services'][$serviceName]['jobs'][$bookingId]['refund_amount'] = max($clients[$clientId]['services'][$serviceName]['jobs'][$bookingId]['refund_amount'], $enrichedTxn['refund_balance']);
            $clients[$clientId]['services'][$serviceName]['jobs'][$bookingId]['net_balance'] = $clients[$clientId]['services'][$serviceName]['jobs'][$bookingId]['credited'] - $clients[$clientId]['services'][$serviceName]['jobs'][$bookingId]['debited'];
        }

        // Group walkers
        $walkers = [];
        foreach ($walker_transactions as $txn) {
            $walkerId = $txn['walker_id'] ?? 0;
            if (!$walkerId) continue;

            $serviceName = $detectService($txn);
            $bookingId   = !empty($txn['service_id']) ? $txn['service_id'] : 'GEN_TXN_' . $txn['txn_id'];
            $petInfo     = $bookingPetMap[$bookingId] ?? null;

            $enrichedTxn = [
                'txn_id'           => $txn['txn_id'],
                'booking_id'       => $bookingId,
                'service'          => $serviceName,
                'date'             => $txn['date'],
                'date_formatted'   => date('d M, h:i A', strtotime($txn['date'])),
                'type'             => ucfirst($txn['transaction_type'] ?? ($txn['debit'] > 0 ? 'Debit' : 'Credit')),
                'description'      => $txn['action'],
                'debit'            => (float)($txn['debit'] ?? 0),
                'credit'           => (float)($txn['credit'] ?? 0),
                'service_balance'  => (float)($txn['spbalance_after'] ?? 0),
                'earned_balance'   => (float)($txn['earned_balance'] ?? 0),
                'category'         => $txn['category'] ?? '',
                'pet_name'         => $petInfo['pet_name'] ?? 'Buddy',
                'pet_breed'        => $petInfo['pet_breed'] ?? 'Golden Retriever',
                'job_status'       => $petInfo['status'] ?? ($txn['category'] === 'cancelled_amount' ? 'Cancelled' : 'Completed'),
            ];

            if (!isset($walkers[$walkerId])) {
                $walkers[$walkerId] = [
                    'id'                 => $walkerId,
                    'walker_name'        => $txn['walker_name'] ?? 'Provider #' . $walkerId,
                    'walker_phone'       => $txn['walker_phone'] ?? '—',
                    'walker_email'       => $txn['walker_email'] ?? '',
                    'area'               => !empty($txn['walker_area']) ? $txn['walker_area'] : (!empty($txn['walker_city']) ? $txn['walker_city'] : 'Chanda Nagar'),
                    'joined_date'        => !empty($txn['joined_date']) ? date('M Y', strtotime($txn['joined_date'])) : 'Jun 2026',
                    'total_credited'     => 0,
                    'total_debited'      => 0,
                    'net_balance'        => 0,
                    'earned_amount'      => 0,
                    'transactions_count' => 0,
                    'services'           => [],
                    'transactions'       => []
                ];

                foreach ($defaultServices as $svc) {
                    $walkers[$walkerId]['services'][$svc] = [
                        'name'          => $svc,
                        'credited'      => 0,
                        'debited'       => 0,
                        'net_balance'   => 0,
                        'earned_amount' => 0,
                        'txns_count'    => 0,
                        'jobs'          => []
                    ];
                }
            }

            $walkers[$walkerId]['transactions'][] = $enrichedTxn;
            $walkers[$walkerId]['total_credited'] += $enrichedTxn['credit'];
            $walkers[$walkerId]['total_debited']  += $enrichedTxn['debit'];
            $walkers[$walkerId]['net_balance']    = $enrichedTxn['service_balance'];
            $walkers[$walkerId]['earned_amount']  = max($walkers[$walkerId]['earned_amount'], $enrichedTxn['earned_balance']);
            $walkers[$walkerId]['transactions_count']++;

            // Service grouping
            $walkers[$walkerId]['services'][$serviceName]['credited'] += $enrichedTxn['credit'];
            $walkers[$walkerId]['services'][$serviceName]['debited']  += $enrichedTxn['debit'];
            $walkers[$walkerId]['services'][$serviceName]['net_balance'] = $walkers[$walkerId]['services'][$serviceName]['credited'] - $walkers[$walkerId]['services'][$serviceName]['debited'];
            if ($walkers[$walkerId]['services'][$serviceName]['net_balance'] < 0) {
                $walkers[$walkerId]['services'][$serviceName]['net_balance'] = max(0, $enrichedTxn['service_balance']);
            }
            $walkers[$walkerId]['services'][$serviceName]['earned_amount'] = max($walkers[$walkerId]['services'][$serviceName]['earned_amount'], $enrichedTxn['earned_balance']);
            $walkers[$walkerId]['services'][$serviceName]['txns_count']++;

            // Job grouping
            if (!isset($walkers[$walkerId]['services'][$serviceName]['jobs'][$bookingId])) {
                $walkers[$walkerId]['services'][$serviceName]['jobs'][$bookingId] = [
                    'job_id'         => $bookingId,
                    'booked_date'    => date('d M Y', strtotime($txn['date'])),
                    'pet_name'       => $enrichedTxn['pet_name'],
                    'pet_breed'      => $enrichedTxn['pet_breed'],
                    'status'         => $enrichedTxn['job_status'],
                    'credited'       => 0,
                    'debited'        => 0,
                    'earned_amount'  => 0,
                    'net_balance'    => 0,
                    'transactions'   => []
                ];
            }
            $walkers[$walkerId]['services'][$serviceName]['jobs'][$bookingId]['transactions'][] = $enrichedTxn;
            $walkers[$walkerId]['services'][$serviceName]['jobs'][$bookingId]['credited'] += $enrichedTxn['credit'];
            $walkers[$walkerId]['services'][$serviceName]['jobs'][$bookingId]['debited']  += $enrichedTxn['debit'];
            $walkers[$walkerId]['services'][$serviceName]['jobs'][$bookingId]['earned_amount'] = max($walkers[$walkerId]['services'][$serviceName]['jobs'][$bookingId]['earned_amount'], $enrichedTxn['earned_balance']);
            $walkers[$walkerId]['services'][$serviceName]['jobs'][$bookingId]['net_balance'] = $walkers[$walkerId]['services'][$serviceName]['jobs'][$bookingId]['credited'] - $walkers[$walkerId]['services'][$serviceName]['jobs'][$bookingId]['debited'];
        }

        // Group platform transactions
        $platform = [
            'id'                 => 'platform',
            'name'               => 'Petsfolio Platform',
            'area'               => 'Headquarters',
            'joined_date'        => 'Jan 2024',
            'total_credited'     => 0,
            'total_debited'      => 0,
            'net_balance'        => 0,
            'transactions_count' => 0,
            'services'           => [],
            'transactions'       => []
        ];

        foreach ($defaultServices as $svc) {
            $platform['services'][$svc] = [
                'name'          => $svc,
                'credited'      => 0,
                'debited'       => 0,
                'net_balance'   => 0,
                'txns_count'    => 0,
                'jobs'          => []
            ];
        }

        foreach ($data['platform_transactions'] as $txn) {
            $serviceName = $detectService($txn);
            $bookingId   = !empty($txn['service_id']) ? $txn['service_id'] : (!empty($txn['booking_id']) ? $txn['booking_id'] : 'PLT_TXN_' . ($txn['txn_id'] ?? '1'));
            $petInfo     = $bookingPetMap[$bookingId] ?? null;

            $amt         = (float)($txn['amount'] ?? 0);
            $type        = ucfirst($txn['transaction_type'] ?? 'Credit');
            $creditAmt   = ($type === 'Credit') ? $amt : 0;
            $debitAmt    = ($type === 'Debit') ? $amt : 0;

            $enrichedTxn = [
                'txn_id'           => $txn['txn_id'] ?? ('PLT_' . uniqid()),
                'booking_id'       => $bookingId,
                'service'          => $serviceName,
                'date'             => $txn['date'] ?? date('Y-m-d H:i:s'),
                'date_formatted'   => isset($txn['date']) ? date('d M, h:i A', strtotime($txn['date'])) : date('d M, h:i A'),
                'type'             => $type,
                'description'      => $txn['description'] ?? 'Platform commission',
                'debit'            => $debitAmt,
                'credit'           => $creditAmt,
                'service_balance'  => $amt,
                'category'         => 'commission',
                'pet_name'         => $petInfo['pet_name'] ?? 'Pet',
                'pet_breed'        => $petInfo['pet_breed'] ?? 'Dog',
                'job_status'       => 'Completed',
            ];

            $platform['transactions'][] = $enrichedTxn;
            $platform['total_credited'] += $creditAmt;
            $platform['total_debited']  += $debitAmt;
            $platform['net_balance']    += ($creditAmt - $debitAmt);
            $platform['transactions_count']++;

            $platform['services'][$serviceName]['credited'] += $creditAmt;
            $platform['services'][$serviceName]['debited']  += $debitAmt;
            $platform['services'][$serviceName]['net_balance'] += ($creditAmt - $debitAmt);
            $platform['services'][$serviceName]['txns_count']++;

            if (!isset($platform['services'][$serviceName]['jobs'][$bookingId])) {
                $platform['services'][$serviceName]['jobs'][$bookingId] = [
                    'job_id'         => $bookingId,
                    'booked_date'    => isset($txn['date']) ? date('d M Y', strtotime($txn['date'])) : date('d M Y'),
                    'pet_name'       => $enrichedTxn['pet_name'],
                    'pet_breed'      => $enrichedTxn['pet_breed'],
                    'status'         => 'Completed',
                    'credited'       => 0,
                    'debited'        => 0,
                    'net_balance'    => 0,
                    'transactions'   => []
                ];
            }
            $platform['services'][$serviceName]['jobs'][$bookingId]['transactions'][] = $enrichedTxn;
            $platform['services'][$serviceName]['jobs'][$bookingId]['credited'] += $creditAmt;
            $platform['services'][$serviceName]['jobs'][$bookingId]['debited']  += $debitAmt;
            $platform['services'][$serviceName]['jobs'][$bookingId]['net_balance'] += ($creditAmt - $debitAmt);
        }

        // Send to view
        $data['clients']         = $clients;
        $data['walkers']         = $walkers;
        $data['platform']        = $platform;
        $data['mismatch_alerts'] = $mismatch_alerts;

        return $this->loadView('admin/recoinciliation', $data);
    }

    public function walletHistory()
    {
        $userId = $this->request->getGet('user_id');
        $type   = $this->request->getGet('type');

        if ($type === 'client_wallet') {
            $history          = $this->booking->getWalletSummary('client', $userId);
            $expected_balance = $this->booking->getBalanceFromHistory($userId);
            $actual_balance   = $this->booking->getRefundAmount($userId);
        } elseif ($type === 'walker_wallet') {
            $history = $this->booking->getWalletSummary('walker', $userId);

            $expected_balance = $this->booking->getSPBalanceFromHistory($userId);
            $actual_balance   = $this->booking->getTotalWithdrawalAmount($userId)->amount;
        } else {
            $history = []; // later: $this->booking->getBookingHistory($userId);
        }
        foreach ($history as &$hist) {
            $hist['expected_balance'] = $expected_balance;
            $hist['actual_balance']   = $actual_balance;
            $hist['difference']       = $actual_balance - $expected_balance;
        }
        return $this->response->setJSON($history);
    }
    private function getSMSCredits()
    {
        $cache = \Config\Services::cache();
        // Check cache first
        $cacheKey = 'sms_credits_cache';
        $cachedCredits = $cache->get($cacheKey);

        if ($cachedCredits) {
            return $cachedCredits;
        }

        // Fetch from API if not cached
        $credits = $this->fetchSMSCreditsFromAPI();

        // Cache for 1 hour
        $cache->save($cacheKey, $credits, 60);

        return $credits;
    }
    /**
     * Get SMS credits from bulk SMS gateway
     * @return array SMS credit information
     */
    private function fetchSMSCreditsFromAPI()
    {
        $user = 'infasta';
        $password = '7660966660';
        $type = '3';

        $url = "https://login.bulksmsgateway.in/userbalance.php?user={$user}&password={$password}&type={$type}";

        try {
            // Using cURL to fetch data
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200 && !empty($response)) {
                $data = json_decode($response, true);

                return [
                    'remaining' => isset($data['remainingcredits']) ? (int)$data['remainingcredits'] : 0,
                    'validity' => $data['validity'] ?? null,
                    'route' => $data['selectedRoute'] ?? null,
                    'status' => 'success'
                ];
            }

            return [
                'remaining' => 0,
                'validity' => null,
                'route' => null,
                'status' => 'error',
                'message' => 'Failed to fetch SMS credits'
            ];
        } catch (Exception $e) {
            return [
                'remaining' => 0,
                'validity' => null,
                'route' => null,
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }
    /**
     * Send low credit alert email
     * @param array $smsCredits SMS credit information
     */
    private function sendLowCreditAlert($smsCredits)
    {
        try {
            // Get email service
            $email = \Config\Services::email();

            // SMTP Configuration from environment
            $config = [
                'protocol'   => getenv('email.protocol'),
                'SMTPHost'   => getenv('email.SMTPHost'),
                'SMTPPort'   => getenv('email.SMTPPort'),
                'SMTPUser'   => getenv('email.SMTPUser'),
                'SMTPPass'   => getenv('email.SMTPPass'),
                'SMTPCrypto' => getenv('email.SMTPCrypto'),
                'mailType'   => 'html',
                'charset'    => 'utf-8',
                'newline'    => "\r\n",
                'wordWrap'   => true
            ];

            // Initialize with config
            $email->initialize($config);

            // Set email details
            $email->setFrom('support@petsfolio.in', 'System Alert - Petsfolio');
            $email->setTo('vijay.infasta@gmail.com');
            $email->setCC('sahil.infasta@gmail.com');
            $email->setCC('mahesh.acedezines@gmail.com');
            $email->setCC('shiva@acedezines.com');

            $email->setSubject('⚠️ Low SMS Credits Alert - Petsfolio');

            // Create HTML email template
            $htmlMessage = view('emails/low_sms_alert', [
                'remaining' => $smsCredits['remaining'],
                'validity'  => $smsCredits['validity'],
                'route'     => $smsCredits['route'],
                'status'    => $smsCredits['status'] ?? 'active'
            ]);

            $email->setMessage($htmlMessage);

            // Send email
            if ($email->send()) {
                log_message('info', 'Low SMS credit alert sent successfully. Remaining: ' . $smsCredits['remaining']);
                return true;
            } else {
                log_message('error', 'Failed to send low SMS credit alert: ' . $email->printDebugger(['headers']));
                return false;
            }
        } catch (\Exception $e) {
            log_message('error', 'Error sending low credit alert: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get Payout balance with caching
     */
    private function getPayoutBalance()
    {
        $cache = Services::cache();
        $cacheKey = 'cf_payout_balance_cache';
        $cachedBalance = $cache->get($cacheKey);

        if ($cachedBalance !== null) {
            return $cachedBalance;
        }

        $balanceData = $this->fetchPayoutBalanceFromAPI();

        // Cache for 15 minutes
        $cache->save($cacheKey, $balanceData, 900);

        return $balanceData;
    }

    /**
     * Fetch balance from Cashfree API
     */
    private function fetchPayoutBalanceFromAPI()
    {
        try {
            $payoutService = new CashfreePayoutService();
            $result = $payoutService->getBalance();

            if ($result['success']) {
                $data = $result['data'] ?? [];

                // Format 1: Nested data object (V1 Style)
                if (isset($data['status']) && $data['status'] === 'SUCCESS' && isset($data['data'])) {
                    $balanceInfo = $data['data'];
                    return [
                        'available' => (float)($balanceInfo['availableBalance'] ?? ($balanceInfo['available_balance'] ?? 0)),
                        'ledger'    => (float)($balanceInfo['balance'] ?? ($balanceInfo['ledger_balance'] ?? 0)),
                        'status'    => 'success'
                    ];
                }

                // Format 2: Direct properties (V2 Style)
                if (isset($data['available_balance']) || isset($data['availableBalance'])) {
                    return [
                        'available' => (float)($data['availableBalance'] ?? ($data['available_balance'] ?? 0)),
                        'ledger'    => (float)($data['balance'] ?? ($data['ledger_balance'] ?? ($data['balance'] ?? 0))),
                        'status'    => 'success'
                    ];
                }

                // Special case for some V1 responses where data is top level
                if (isset($data['status']) && $data['status'] === 'SUCCESS') {
                    return [
                        'available' => (float)($data['availableBalance'] ?? ($data['available_balance'] ?? 0)),
                        'ledger'    => (float)($data['balance'] ?? ($data['ledger_balance'] ?? 0)),
                        'status'    => 'success'
                    ];
                }
            }

            return [
                'available' => 0,
                'ledger' => 0,
                'status' => 'error',
                'message' => $result['error'] ?? 'Failed to fetch balance'
            ];
        } catch (\Exception $e) {
            return [
                'available' => 0,
                'ledger' => 0,
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    public function suspiciousVisits()
    {
        $db = \Config\Database::connect();
        $data['title'] = 'Suspicious Visits & Geofence Alerts';

        if ($db->tableExists('suspicious_visits')) {
            $builder = $db->table('suspicious_visits sv')
                ->select('sv.*')
                ->select('sp.name as provider_name, sp.phone as provider_phone, sp.email as provider_email, sp.profile as provider_profile, sp.city as provider_city, sp.status as provider_status, sp.is_verified as provider_verified')
                ->select('u.name as customer_name, u.phone as customer_phone, u.email as customer_email, u.city as customer_city')
                ->join('service_providers sp', 'sp.id = sv.provider_id', 'left')
                ->join('users u', 'u.id = sv.customer_id', 'left')
                ->orderBy('sv.created_at', 'DESC');

            $visits = $builder->get()->getResult();

            $validStatuses = ['Completed', 'Cancelled', 'completed', 'cancelled'];
            $bookingByIdMap = [];
            $bookingByPairMap = [];

            $registerBooking = function ($b) use (&$bookingByIdMap, &$bookingByPairMap) {
                if (!empty($b->booking_id)) {
                    $statusNormalized = ucfirst(strtolower(trim($b->booking_status ?? $b->status ?? '')));
                    $b->booking_status = $statusNormalized;
                    $bookingByIdMap[(string)$b->booking_id] = $b;

                    if (!empty($b->provider_id) && !empty($b->customer_id)) {
                        $pairKey = (string)$b->provider_id . '_' . (string)$b->customer_id;
                        if (!isset($bookingByPairMap[$pairKey])) {
                            $bookingByPairMap[$pairKey] = [];
                        }
                        $bookingByPairMap[$pairKey][] = $b;
                    }
                }
            };

            // Query walking_service_bookings with Completed/Cancelled status
            if ($db->tableExists('walking_service_bookings')) {
                try {
                    $walkingRows = $db->table('walking_service_bookings w')
                        ->select("'walking' as service_type, w.id as booking_id, w.user_id as customer_id, q.provider_id, w.status as booking_status, COALESCE(w.final_payable, w.total_price, 0) as total_amount, w.service_start_date, w.service_end_date, s.name as service_name, ua.city, w.created_at")
                        ->join('quotations q', 'q.booking_id = w.id', 'left')
                        ->join('services s', 's.id = w.service_id', 'left')
                        ->join('user_addresses ua', 'ua.id = w.address_id', 'left')
                        ->whereIn('w.status', $validStatuses)
                        ->orderBy('w.id', 'DESC')
                        ->get()->getResult();
                    foreach ($walkingRows as $row) {
                        $registerBooking($row);
                    }
                } catch (\Exception $e) {
                    log_message('notice', 'Query walking_service_bookings in suspiciousVisits: ' . $e->getMessage());
                }
            }

            // Query grooming_service_bookings with Completed/Cancelled status
            if ($db->tableExists('grooming_service_bookings')) {
                try {
                    $groomingRows = $db->table('grooming_service_bookings g')
                        ->select("'grooming' as service_type, g.id as booking_id, g.user_id as customer_id, q.provider_id, g.status as booking_status, COALESCE(g.final_payable, g.total_price, 0) as total_amount, g.service_start_date, NULL as service_end_date, s.name as service_name, ua.city, g.created_at")
                        ->join('quotations q', 'q.booking_id = g.id', 'left')
                        ->join('services s', 's.id = g.service_id', 'left')
                        ->join('user_addresses ua', 'ua.id = g.address_id', 'left')
                        ->whereIn('g.status', $validStatuses)
                        ->orderBy('g.id', 'DESC')
                        ->get()->getResult();
                    foreach ($groomingRows as $row) {
                        $registerBooking($row);
                    }
                } catch (\Exception $e) {
                    log_message('notice', 'Query grooming_service_bookings in suspiciousVisits: ' . $e->getMessage());
                }
            }

            // Query training_service_bookings with Completed/Cancelled status
            if ($db->tableExists('training_service_bookings')) {
                try {
                    $trainingRows = $db->table('training_service_bookings t')
                        ->select("'training' as service_type, t.id as booking_id, t.user_id as customer_id, q.provider_id, t.status as booking_status, COALESCE(t.final_payable, t.total_price, 0) as total_amount, t.service_start_date, NULL as service_end_date, s.name as service_name, ua.city, t.created_at")
                        ->join('quotations q', 'q.booking_id = t.id', 'left')
                        ->join('services s', 's.id = t.service_id', 'left')
                        ->join('user_addresses ua', 'ua.id = t.address_id', 'left')
                        ->whereIn('t.status', $validStatuses)
                        ->orderBy('t.id', 'DESC')
                        ->get()->getResult();
                    foreach ($trainingRows as $row) {
                        $registerBooking($row);
                    }
                } catch (\Exception $e) {
                    log_message('notice', 'Query training_service_bookings in suspiciousVisits: ' . $e->getMessage());
                }
            }

            // Also supplement from v_bookings_overview for any other services
            if ($db->tableExists('v_bookings_overview')) {
                try {
                    $vRows = $db->table('v_bookings_overview')
                        ->select('booking_id, service_type, user_id as customer_id, status as booking_status, total_amount, service_start_date, service_end_date, service_name, city')
                        ->whereIn('status', $validStatuses)
                        ->get()->getResult();
                    foreach ($vRows as $vRow) {
                        if (!isset($bookingByIdMap[(string)$vRow->booking_id])) {
                            $registerBooking($vRow);
                        }
                    }
                } catch (\Exception $e) {
                    log_message('notice', 'Query v_bookings_overview in suspiciousVisits: ' . $e->getMessage());
                }
            }

            // Process and enrich visit objects
            $enrichedVisits = [];
            foreach ($visits as $visit) {
                $telemetry = [];
                if (!empty($visit->raw_telemetry)) {
                    if (is_string($visit->raw_telemetry)) {
                        $decoded = json_decode($visit->raw_telemetry, true);
                        if (is_array($decoded)) {
                            $telemetry = $decoded;
                        }
                    } elseif (is_array($visit->raw_telemetry)) {
                        $telemetry = $visit->raw_telemetry;
                    }
                }
                $visit->telemetry_data = $telemetry;
                $visit->lat = $telemetry['latitude'] ?? $telemetry['lat'] ?? $telemetry['location']['latitude'] ?? $telemetry['location']['lat'] ?? ($telemetry['coords']['latitude'] ?? null);
                $visit->lng = $telemetry['longitude'] ?? $telemetry['lng'] ?? $telemetry['lon'] ?? $telemetry['location']['longitude'] ?? $telemetry['location']['lng'] ?? ($telemetry['coords']['longitude'] ?? null);
                $visit->accuracy = $telemetry['accuracy'] ?? $telemetry['acc'] ?? ($telemetry['coords']['accuracy'] ?? null);
                $visit->dwell_time = $telemetry['dwell_duration_seconds'] ?? $telemetry['dwell_time'] ?? $telemetry['duration_minutes'] ?? $telemetry['stay_duration'] ?? $telemetry['duration'] ?? null;
                $visit->distance = $telemetry['distance_meters'] ?? $telemetry['distance'] ?? null;
                $visit->speed = $telemetry['speed'] ?? ($telemetry['coords']['speed'] ?? null);
                $visit->battery = $telemetry['battery'] ?? $telemetry['battery_level'] ?? ($telemetry['device']['battery'] ?? null);
                $visit->device_info = $telemetry['device_info'] ?? $telemetry['device'] ?? $telemetry['model'] ?? null;

                $matchedBooking = null;
                if (!empty($visit->associated_booking_id) && isset($bookingByIdMap[(string)$visit->associated_booking_id])) {
                    $matchedBooking = $bookingByIdMap[(string)$visit->associated_booking_id];
                }

                if (!$matchedBooking && !empty($visit->provider_id) && !empty($visit->customer_id)) {
                    $pairKey = (string)$visit->provider_id . '_' . (string)$visit->customer_id;
                    if (!empty($bookingByPairMap[$pairKey])) {
                        $matchedBooking = $bookingByPairMap[$pairKey][0];
                        $visit->associated_booking_id = $matchedBooking->booking_id;
                    }
                }

                $visit->booking_details = $matchedBooking;
                $enrichedVisits[] = $visit;
            }

            $data['visits'] = $enrichedVisits;
            $data['total']  = count($enrichedVisits);
        } else {
            $data['visits'] = [];
            $data['total']  = 0;
        }

        return $this->loadView('admin/suspicious_visits', $data);
    }
}
