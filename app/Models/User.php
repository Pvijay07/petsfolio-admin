<?php

namespace App\Models;

use CodeIgniter\Model;

class User extends Model
{
<<<<<<< HEAD
=======
  protected $table      = 'users';
  protected $primaryKey = 'id';
>>>>>>> origin/main
  protected $db;
  protected $admin;

  public function __construct()
  {
<<<<<<< HEAD
    $this->db    = \Config\Database::connect ();
=======
    $this->db    = \Config\Database::connect();
>>>>>>> origin/main
    $this->admin = 'employees';
  }
  protected $protectFields = false;
  protected $allowedFields = [
    'name',
    'email',
    'password',
    'gender'
  ];
<<<<<<< HEAD
  public function getUserByUsername( $email )
  {
    return $this->db->table ( $this->admin )
      ->select ( 'id, name, email, password' )
      ->where ( 'email', $email )
      ->get ()
      ->getRow ();
  }
  public function getClient( $phone )
  {
    return $this->db->table ( 'users' )
      ->select ( '*' )
      ->where ( 'phone', $phone )
      ->get ()
      ->getRow ();
  }
  public function getUserPets( $user_id )
  {
    $query = $this->db->table ( 'user_pets' )
      ->select ( '*' )
      ->where ( 'user_id', $user_id )
      ->orderBy ( 'id', 'DESC' )
      ->get ();
    return $query->getResult ();
  }
  public function getUser( $id )
  {
    return $this->db->table ( $this->admin )
      ->select ( '*' )
      ->where ( 'id', $id )
      ->get ()
      ->getRow ();
  }
  public function getUserById( $id )
  {
    return $this->db->table ( 'users' )
      ->select ( '*' )
      ->where ( 'id', $id )
      ->get ()
      ->getRow ();
  }
  public function getAll( $status )
  {
    $users = $this->db->table ( 'users' )
      ->select ( 'users.*' )
      ->select ( 'user_withdrawal_wallet.amount as wallet_amount' )
      ->join ( 'user_withdrawal_wallet', 'users.id = user_withdrawal_wallet.user_id', 'left' )

      ->where ( 'users.status', $status )
      ->where ( 'users.name!=', '' )
      ->where ( 'users.email!=', '' )
      ->where ( 'users.gender!=', '' )

      ->orderBy ( 'users.id', 'DESC' )
      ->get ()
      ->getResult ();
    foreach ( $users as $user ) {
      $user->pets      = $this->db->table ( 'user_pets' )
        ->select ( '*' )
        ->where ( 'user_id', $user->id )
        ->get ()
        ->getResult ();
      $user->addresses = $this->db->table ( 'user_addresses' )
        ->select ( 'state,address' )
        ->where ( 'user_id', $user->id )
        ->get ()
        ->getResult ();
      $user->devices   = $this->db->table ( 'user_devices' )
        ->where ( 'user_id', $user->id )
        ->where ( 'user_type', 'client' )
        ->orderBy ( 'last_active_at', 'DESC' )
        ->get ()
        ->getResult ();
=======
  public function getUserByUsername($email)
  {
    return $this->db->table($this->admin)
      ->select('employees.id, employees.name, employees.email, employees.password, employees.role_id, roles.name as role_name, roles.slug as role_slug')
      ->join('roles', 'roles.id = employees.role_id', 'left')
      ->where('employees.email', $email)
      ->get()
      ->getRow();
  }
  public function getClient($phone)
  {
    return $this->db->table('users')
      ->select('*')
      ->where('phone', $phone)
      ->get()
      ->getRow();
  }
  public function getUserPets($user_id)
  {
    $query = $this->db->table('user_pets')
      ->select('*')
      ->where('user_id', $user_id)
      ->orderBy('id', 'DESC')
      ->get();
    return $query->getResult();
  }
  public function getUser($id)
  {
    return $this->db->table($this->admin)
      ->select('employees.*, roles.name as role_name, roles.slug as role_slug')
      ->join('roles', 'roles.id = employees.role_id', 'left')
      ->where('employees.id', $id)
      ->get()
      ->getRow();
  }
  public function getAll($status = 'active')
  {
    $builder = $this->db->table('users')
      ->select('users.*')
      ->select('user_withdrawal_wallet.amount as wallet_amount')
      ->join('user_withdrawal_wallet', 'users.id = user_withdrawal_wallet.user_id', 'left');

    if ($status === 'incomplete') {
      $builder->groupStart()
        ->where('users.name', '')
        ->orWhere('users.name IS NULL', null, false)
        ->orWhere('users.email', '')
        ->orWhere('users.email IS NULL', null, false)
        ->orWhere('users.gender', '')
        ->orWhere('users.gender IS NULL', null, false)
        ->groupEnd();
    } elseif ($status === 'all') {
      // Return all users
    } elseif (!empty($status)) {
      $builder->where('users.status', $status)
        ->where('users.name!=', '')
        ->where('users.name IS NOT NULL', null, false)
        ->where('users.email!=', '')
        ->where('users.email IS NOT NULL', null, false)
        ->where('users.gender!=', '')
        ->where('users.gender IS NOT NULL', null, false);
    } else {
      $builder->where('users.status', 'active')
        ->where('users.name!=', '')
        ->where('users.name IS NOT NULL', null, false)
        ->where('users.email!=', '')
        ->where('users.email IS NOT NULL', null, false)
        ->where('users.gender!=', '')
        ->where('users.gender IS NOT NULL', null, false);
    }

    $users = $builder
      ->orderBy('users.id', 'DESC')
      ->get()
      ->getResult();
    foreach ($users as $user) {
      $user->pets      = $this->db->table('user_pets')
        ->select('*')
        ->where('user_id', $user->id)
        ->get()
        ->getResult();
      $user->addresses = $this->db->table('user_addresses')
        ->select('state,address')
        ->where('user_id', $user->id)
        ->get()
        ->getResult();
      $user->devices   = $this->db->table('user_devices')
        ->where('user_id', $user->id)
        ->where('user_type', 'client')
        ->orderBy('last_active_at', 'DESC')
        ->get()
        ->getResult();
>>>>>>> origin/main
      $user->latest_device = !empty($user->devices) ? $user->devices[0] : null;
    }
    return $users;
  }
<<<<<<< HEAD
  public function addTax( $data )
  {
    return $this->db->table ( 'taxes' )->insert ( $data );
  }
  public function insertEmployee( $data )
  {
    return $this->db->table ( 'employees' )->insert ( $data );
  }
  public function deleteEmployee( $userId )
  {

    return $this->db->table ( 'employees' )->delete ( ['id' => $userId] );
  }
  public function updateEmployee( $data )
  {
    return $this->db->table ( 'employees' )->where ( 'id', $data['id'] )->update ( $data );
  }
  public function getTaxes()
  {
    return $this->db->table ( 'taxes' )->get ()->getResult ();
  }
  public function getRoles()
  {
    return $this->db->table ( 'roles' )->get ()->getResult ();
  }
  public function getUsers()
  {
    return $this->db->table ( 'employees' )
      ->select ( 'employees.id, employees.name,employees.email,employees. password, employees.role_id,employees.status' )
      ->select ( 'roles.name as role_name,' )

      ->join ( 'roles', 'roles.id=employees.role_id', 'left' )
      ->orderBy ( 'employees.id', 'DESC' )
      ->where ( 'employees.role_id!=', 1 )->get ()->getResult ();
  }
  public function getPermissionsByUserId( $userId )
  {
    // Get the user's role
    $query = $this->db->table ( 'employees' )
      ->select ( 'roles.id as role_id' )
      ->join ( 'roles', 'roles.id = employees.role_id', 'left' )
      ->where ( 'employees.id', $userId )
      ->get ()
      ->getRow ();

    if ( !$query ) {
=======

  public function getClientCounts()
  {
    $total = $this->db->table('users')
      ->countAllResults();

    $active = $this->db->table('users')
      ->where('status', 'active')
      ->where('name!=', '')
      ->where('name IS NOT NULL', null, false)
      ->where('email!=', '')
      ->where('email IS NOT NULL', null, false)
      ->where('gender!=', '')
      ->where('gender IS NOT NULL', null, false)
      ->countAllResults();

    $inactive = $this->db->table('users')
      ->where('status', 'inactive')
      ->where('name!=', '')
      ->where('name IS NOT NULL', null, false)
      ->where('email!=', '')
      ->where('email IS NOT NULL', null, false)
      ->where('gender!=', '')
      ->where('gender IS NOT NULL', null, false)
      ->countAllResults();

    $incomplete = $this->db->table('users')
      ->groupStart()
        ->where('name', '')
        ->orWhere('name IS NULL', null, false)
        ->orWhere('email', '')
        ->orWhere('email IS NULL', null, false)
        ->orWhere('gender', '')
        ->orWhere('gender IS NULL', null, false)
      ->groupEnd()
      ->countAllResults();

    return [
      'total'      => $total,
      'active'     => $active,
      'inactive'   => $inactive,
      'incomplete' => $incomplete,
    ];
  }

  public function addTax($data)
  {
    return $this->db->table('taxes')->insert($data);
  }
  public function insertEmployee($data)
  {
    return $this->db->table('employees')->insert($data);
  }
  public function deleteEmployee($userId)
  {

    return $this->db->table('employees')->delete(['id' => $userId]);
  }
  public function updateEmployee($data)
  {
    return $this->db->table('employees')->where('id', $data['id'])->update($data);
  }
  public function getTaxes()
  {
    return $this->db->table('taxes')->get()->getResult();
  }
  public function getRoles()
  {
    return $this->db->table('roles')->orderBy('id', 'ASC')->get()->getResult();
  }
  public function getUsers()
  {
    return $this->db->table('employees')
      ->select('employees.id, employees.name, employees.email, employees.password, employees.role_id, employees.status, roles.name as role_name, roles.slug as role_slug')
      ->join('roles', 'roles.id = employees.role_id', 'left')
      ->orderBy('employees.id', 'DESC')
      ->where('employees.role_id!=', 1)->get()->getResult();
  }
  public function getPermissionsByUserId($userId)
  {
    // Get the user's role
    $query = $this->db->table('employees')
      ->select('roles.id as role_id')
      ->join('roles', 'roles.id = employees.role_id', 'left')
      ->where('employees.id', $userId)
      ->get()
      ->getRow();

    if (!$query) {
>>>>>>> origin/main
      return []; // No role found
    }

    $roleId      = $query->role_id;
<<<<<<< HEAD
    $permissions = $this->db->table ( 'role_permissions' )
      ->select ( 'permissions.id,permissions.module,permissions.name' )
      ->join ( 'permissions', 'permissions.id = role_permissions.permission_id', 'left' )
      ->where ( 'role_permissions.role_id', $roleId )
      ->get ()
      ->getResultArray ();
=======
    $permissions = $this->db->table('role_permissions')
      ->select('permissions.id,permissions.module,permissions.name')
      ->join('permissions', 'permissions.id = role_permissions.permission_id', 'left')
      ->where('role_permissions.role_id', $roleId)
      ->get()
      ->getResultArray();
>>>>>>> origin/main

    return $permissions;
  }
  public function getPermissions()
  {
<<<<<<< HEAD
    $query = $this->db->table ( 'permissions' )->get ()->getResultArray ();
    return $query;
  }
  public function insertPermission( $data )
  {
    $this->db->table ( 'permissions' )->insert ( $data );
  }
  public function assignPermissions( $roleId, $permissions )
  {
    // Delete existing permissions for this role
    $this->db->table ( 'role_permissions' )
      ->where ( 'role_id', $roleId )
      ->delete ();

    if ( !empty ( $permissions ) ) {
      foreach ( $permissions as $permissionId ) {
        $this->db->table ( 'role_permissions' )->insert ( [
          'role_id'       => $roleId,
          'permission_id' => $permissionId
        ] );
=======
    $query = $this->db->table('permissions')->get()->getResultArray();
    return $query;
  }
  public function insertPermission($data)
  {
    $this->db->table('permissions')->insert($data);
  }
  public function assignPermissions($roleId, $permissions)
  {
    // Delete existing permissions for this role
    $this->db->table('role_permissions')
      ->where('role_id', $roleId)
      ->delete();

    if (!empty($permissions)) {
      foreach ($permissions as $permissionId) {
        $this->db->table('role_permissions')->insert([
          'role_id'       => $roleId,
          'permission_id' => $permissionId
        ]);
>>>>>>> origin/main
      }
    }
  }
  public function getPets()
  {
<<<<<<< HEAD
    $query = $this->db->table ( 'pet_types' )
      ->select ( 'pet_types.id, pet_types.name, pet_types.status' )
      ->selectCount ( 'dog_breeds.id', 'totalBreeds' )
      ->join ( 'dog_breeds', 'dog_breeds.pet_type = pet_types.id', 'left' )
      ->groupBy ( 'pet_types.id' )
      ->get ()
      ->getResult ();
    return $query;
  }
  public function insertPetType( $data )
  {
    return $this->db->table ( 'pet_types' )->insert ( $data );
  }
  public function getUserByEmail( $email )
  {
    $query = $this->db->table ( 'employees' )->select ( 'id, name, email,otp,role_id' )->where ( 'email', $email )->get ()->getRow ();
    return $query;
  }
  public function updateUserToken( $id, $token )
  {
    $this->db->table ( 'employees' )->update ( ['token' => $token], ['id ' => $id] );
  }
  public function getBreeds( $type )
  {
    $query = $this->db->table ( 'dog_breeds' )
      ->select ( 'dog_breeds.id, dog_breeds.label, dog_breeds.value,dog_breeds.status' )
      ->select ( 'pet_types.name' )
      ->join ( 'pet_types', 'pet_types.id=dog_breeds.pet_type', 'left' )
      ->where ( 'dog_breeds.pet_type', $type )->get ()->getResult ();
    return $query;
  }
  public function deletePetType( $id )
  {
    $this->db->table ( 'pet_types' )->delete ( ['id' => $id] );
  }
  public function insertBreed( $data )
  {
    $this->db->table ( 'dog_breeds' )->insert ( $data );
  }
  public function getPetType( $id )
  {
    $query = $this->db->table ( 'pet_types' )->select ( 'id, name, status' )->where ( 'id', $id )->get ()->getRow ();
    return $query;
  }
  public function updatePetType( $data )
  {
    return $this->db->table ( 'pet_types' )->update ( $data, ['id' => $data['id']] );
  }
  public function getNotificationByTitleAndType( $title, $type )
  {
    return $this->db->table ( 'notification_templates' )
      ->where ( 'title', $title )
      ->where ( 'type', $type )
      ->get ()
      ->getRowArray ();
  }

  public function addNotification( $data )
  {
    $this->db->table ( 'notification_templates' )->insert ( $data );
  }

  public function updateNotification( $id, $data )
  {
    $this->db->table ( 'notification_templates' )->where ( 'id', $id )->update ( $data );
=======
    $query = $this->db->table('pet_types')
      ->select('pet_types.id, pet_types.name, pet_types.status')
      ->selectCount('dog_breeds.id', 'totalBreeds')
      ->join('dog_breeds', 'dog_breeds.pet_type = pet_types.id', 'left')
      ->groupBy('pet_types.id')
      ->get()
      ->getResult();
    return $query;
  }
  public function insertPetType($data)
  {
    return $this->db->table('pet_types')->insert($data);
  }
  public function getUserByEmail($email)
  {
    $query = $this->db->table('employees')->select('id, name, email,otp,role_id')->where('email', $email)->get()->getRow();
    return $query;
  }
  public function updateUserToken($id, $token)
  {
    $this->db->table('employees')->update(['token' => $token], ['id ' => $id]);
  }
  public function getBreeds($type)
  {
    $query = $this->db->table('dog_breeds')
      ->select('dog_breeds.id, dog_breeds.label, dog_breeds.value,dog_breeds.status')
      ->select('pet_types.name')
      ->join('pet_types', 'pet_types.id=dog_breeds.pet_type', 'left')
      ->where('dog_breeds.pet_type', $type)->get()->getResult();
    return $query;
  }
  public function deletePetType($id)
  {
    $this->db->table('pet_types')->delete(['id' => $id]);
  }
  public function insertBreed($data)
  {
    $this->db->table('dog_breeds')->insert($data);
  }
  public function getPetType($id)
  {
    $query = $this->db->table('pet_types')->select('id, name, status')->where('id', $id)->get()->getRow();
    return $query;
  }
  public function updatePetType($data)
  {
    return $this->db->table('pet_types')->update($data, ['id' => $data['id']]);
  }
  public function getNotificationByTitleAndType($title, $type)
  {
    return $this->db->table('notification_templates')
      ->where('title', $title)
      ->where('type', $type)
      ->get()
      ->getRowArray();
  }

  public function addNotification($data)
  {
    $this->db->table('notification_templates')->insert($data);
  }

  public function updateNotification($id, $data)
  {
    $this->db->table('notification_templates')->where('id', $id)->update($data);
>>>>>>> origin/main
  }

  public function getNotifications()
  {
<<<<<<< HEAD
    $query = $this->db->table ( 'notification_templates' )->get ()->getResult ();
    return $query;
  }
  public function getNotification( $id )
  {
    $query = $this->db->table ( 'notification_templates' )->where ( 'id', $id )->get ()->getRow ();
    return $query;
  }
  public function deleteNotification( $id )
  {
    $this->db->table ( 'notification_templates' )->delete ( ['id' => $id] );
  }
  public function updateProfile( $data, $id )
  {
    $this->db->table ( 'employees' )->update ( $data, ['id' => $id] );
  }
  public function updatePassword( $data, $id )
  {
    $this->db->table ( 'employees' )->update ( $data, ['id' => $id] );
  }
  public function updateUserOtp( $email, $otp )
  {
    $this->db->table ( 'employees' )
      ->set ( 'otp', $otp )
      ->where ( 'email', $email )
      ->update ();
  }
  public function removeUserOtp( $email, $fcm_token )
  {
    $this->db->table ( 'employees' )
      ->set ( 'otp', '' )
      ->set ( 'fcm_token', $fcm_token )
      ->where ( 'email', $email )
      ->update ();
  }

  public function refundAmount( $id, $refundAmount )
  {
    return $this->db->table ( 'user_withdrawal_wallet' )->where ( 'user_id', $id )->where ( 'amount', $refundAmount )->update ( ['amount' => 0] );
  }
  public function getAllAddresses()
  {
    return $this->db->table ( 'user_addresses' )
      ->select ( 'id,address' )
      ->get ()
      ->getResultArray ();
  }
  public function updateArea( $id, $area )
  {
    return $this->db->table ( 'user_addresses' )
      ->set ( 'area', $area )
      ->where ( 'id', $id )
      ->update ();
  }
  public function addRefundHistory( $id, $refundAmount, $newName )
  {
    return $this->db->table ( 'user_refunds' )

      ->set ( 'user_id', $id )
      ->set ( 'amount', $refundAmount )
      ->set ( 'receipt', $newName )
      ->set ( 'status', 'completed' )
      ->insert ();
  }

  public function getDownloadedOnly( $start = null, $end = null, $filter = false )
  {
    $dateCondition = "";
    if ( $filter ) {
      $dateCondition = " AND DATE(u.created_at) BETWEEN '$start' AND '$end'";
    }

    return $this->db->query ( "
=======
    $query = $this->db->table('notification_templates')->get()->getResult();
    return $query;
  }
  public function getNotification($id)
  {
    $query = $this->db->table('notification_templates')->where('id', $id)->get()->getRow();
    return $query;
  }
  public function deleteNotification($id)
  {
    $this->db->table('notification_templates')->delete(['id' => $id]);
  }
  public function updateProfile($data, $id)
  {
    $this->db->table('employees')->update($data, ['id' => $id]);
  }
  public function updatePassword($data, $id)
  {
    $this->db->table('employees')->update($data, ['id' => $id]);
  }
  public function updateUserOtp($email, $otp)
  {
    $this->db->table('employees')
      ->set('otp', $otp)
      ->where('email', $email)
      ->update();
  }
  public function removeUserOtp($email, $fcm_token)
  {
    $this->db->table('employees')
      ->set('otp', '')
      ->set('fcm_token', $fcm_token)
      ->where('email', $email)
      ->update();
  }

  public function refundAmount($id, $refundAmount)
  {
    return $this->db->table('user_withdrawal_wallet')->where('user_id', $id)->where('amount', $refundAmount)->update(['amount' => 0]);
  }
  public function getAllAddresses()
  {
    return $this->db->table('user_addresses')
      ->select('id,address')
      ->get()
      ->getResultArray();
  }
  public function updateArea($id, $area)
  {
    return $this->db->table('user_addresses')
      ->set('area', $area)
      ->where('id', $id)
      ->update();
  }
  public function addRefundHistory($id, $refundAmount, $newName)
  {
    return $this->db->table('user_refunds')

      ->set('user_id', $id)
      ->set('amount', $refundAmount)
      ->set('receipt', $newName)
      ->set('status', 'completed')
      ->insert();
  }

  public function getDownloadedOnly($start = null, $end = null, $filter = false)
  {
    $dateCondition = "";
    if ($filter) {
      $dateCondition = " AND DATE(u.created_at) BETWEEN '$start' AND '$end'";
    }

    return $this->db->query("
>>>>>>> origin/main
        SELECT 
            u.id, u.name, u.sales_comment AS comment, u.phone, u.created_at AS download_date
        FROM users u
        LEFT JOIN walking_service_bookings w ON w.user_id = u.id
        LEFT JOIN training_service_bookings t ON t.user_id = u.id
        LEFT JOIN grooming_service_bookings g ON g.user_id = u.id
        WHERE w.id IS NULL
          AND t.id IS NULL
          AND g.id IS NULL
          $dateCondition
        ORDER BY u.id DESC
<<<<<<< HEAD
    " )->getResult ();
=======
    ")->getResult();
>>>>>>> origin/main
  }



  // 2️⃣ Jobs Posted (Not Assigned)
<<<<<<< HEAD
  public function getPostedButNotAssigned( $start, $end, $filter = false )
  {
    $dateCondition = "";

    if ( $filter ) {
      $dateCondition = " AND DATE(u.created_at) BETWEEN '$start' AND '$end'";
    }
    return $this->db->query ( "
=======
  public function getPostedButNotAssigned($start, $end, $filter = false)
  {
    $dateCondition = "";

    if ($filter) {
      $dateCondition = " AND DATE(u.created_at) BETWEEN '$start' AND '$end'";
    }
    return $this->db->query("
>>>>>>> origin/main
        SELECT 
            u.id, 
            u.name, 
            u.phone,
            u.sales_comment as comment,
            COUNT(*) AS jobs_count,
            MAX(j.created_at) AS last_post_date
        FROM users u
        
        JOIN (
            SELECT id, user_id, status, created_at 
            FROM walking_service_bookings
            WHERE status = 'New'
            
            UNION ALL
            
            SELECT id, user_id, status, created_at 
            FROM training_service_bookings
            WHERE status = 'New'
            
            UNION ALL
            
            SELECT id, user_id, status, created_at 
            FROM grooming_service_bookings
            WHERE status = 'New'
        ) j ON j.user_id = u.id
        
        LEFT JOIN quotations q
            ON q.booking_id = j.id
            AND q.status != 'Accepted'
        $dateCondition
        
        GROUP BY u.id
<<<<<<< HEAD
    " )->getResult ();
=======
    ")->getResult();
>>>>>>> origin/main
  }



  // 3️⃣ Jobs Assigned
<<<<<<< HEAD
  public function getAssignedClients( $start, $end, $filter = false )
  {
    $dateCondition = "";

    if ( $filter ) {
      $dateCondition = " AND DATE(u.created_at) BETWEEN '$start' AND '$end'";
    }
    return $this->db->query ( "
=======
  public function getAssignedClients($start, $end, $filter = false)
  {
    $dateCondition = "";

    if ($filter) {
      $dateCondition = " AND DATE(u.created_at) BETWEEN '$start' AND '$end'";
    }
    return $this->db->query("
>>>>>>> origin/main
        SELECT 
            u.id,
            u.name,
            u.phone,
            u.sales_comment as comment,
            COUNT(*) AS assigned_count,
            MAX(j.created_at) AS last_assigned_date
        FROM users u
        
        JOIN (
            SELECT id, user_id, created_at
            FROM walking_service_bookings
            WHERE status = 'Confirmed'
            
            UNION ALL
            
            SELECT id, user_id, created_at
            FROM training_service_bookings
            WHERE status = 'Confirmed'
            
            UNION ALL
            
            SELECT id, user_id, created_at
            FROM grooming_service_bookings
            WHERE status = 'Confirmed'
        ) j ON j.user_id = u.id

        LEFT JOIN quotations q
            ON q.booking_id = j.id
            AND q.status = 'Accepted'  $dateCondition

        GROUP BY u.id
<<<<<<< HEAD
    " )->getResult ();
  }
  public function logTransaction( $user_id, $serviceType, $booking_id, $type, $amount, $balanceAfter, $refundAmount, $category, $desc, $table )
  {
    $idField     = ( $table == 'user_wallet_histories' ) ? 'user_id' : 'provider_id';
    $walletField = ( $table == 'user_wallet_histories' ) ? 'refund_balance' : 'earned_balance';

    // Fetch previous checksum for this user
    $prev = $this->db->table ( $table )
      ->select ( 'checksum' )
      ->where ( $idField, $user_id )
      ->orderBy ( 'id', 'DESC' )
      ->get ()
      ->getRow ( 'checksum' );

    // Prepare the core data string
    $dataString = implode ( '|', [
=======
    ")->getResult();
  }
  public function logTransaction($user_id, $serviceType, $booking_id, $type, $amount, $balanceAfter, $refundAmount, $category, $desc, $table)
  {
    $idField = ($table == 'user_wallet_histories') ? 'user_id' : 'provider_id';
    $walletField = ($table == 'user_wallet_histories') ? 'refund_balance' : 'earned_balance';

    // Fetch previous checksum for this user
    $prev = $this->db->table($table)
      ->select('checksum')
      ->where($idField, $user_id)
      ->orderBy('id', 'DESC')
      ->get()
      ->getRow('checksum');

    // Prepare the core data string
    $dataString = implode('|', [
>>>>>>> origin/main
      $user_id,
      $serviceType,
      $booking_id,
      $type,
<<<<<<< HEAD
      number_format ( $amount, 2, '.', '' ),
      number_format ( $balanceAfter, 2, '.', '' ),
      number_format ( $refundAmount, 2, '.', '' ),
      $category,
      $desc,
      $prev ?? ''
    ] );

    // Generate new checksum using SHA256
    $checksum = hash ( 'sha256', $dataString );

    // Insert with checksum chain
    return $this->db->table ( $table )->insert ( [
      $idField           => $user_id,
      'booking_id'       => $booking_id,
      'service_id'       => $serviceType,
      'transaction_type' => $type,
      'amount'           => $amount,
      'balance_after'    => $balanceAfter,
      $walletField       => $refundAmount,
      'category'         => $category,
      'description'      => $desc,
      'prev_checksum'    => $prev,
      'checksum'         => $checksum
    ] );
  }
  public function getServiceWalletSum( $user_id )
  {
    $serviceAmountResult = $this->db->table ( $this->user_service_wallet )
      ->selectSum ( 'walking_amount', 'walking_amount' )
      ->selectSum ( 'training_amount', 'training_amount' )
      ->selectSum ( 'boarding_amount', 'boarding_amount' )
      ->selectSum ( 'grooming_amount', 'grooming_amount' )
      ->where ( 'user_id', $user_id )
      ->get ()
      ->getRow ();

    $totalServiceAmount = [
      'service_amount'  => ( $serviceAmountResult->walking_amount ?? 0 ) +
        ( $serviceAmountResult->training_amount ?? 0 ) +
        ( $serviceAmountResult->boarding_amount ?? 0 ) +
        ( $serviceAmountResult->grooming_amount ?? 0 ),
=======
      number_format($amount, 2, '.', ''),
      number_format($balanceAfter, 2, '.', ''),
      number_format($refundAmount, 2, '.', ''),
      $category,
      $desc,
      $prev ?? ''
    ]);

    // Generate new checksum using SHA256
    $checksum = hash('sha256', $dataString);

    // Insert with checksum chain
    return $this->db->table($table)->insert([
      $idField        => $user_id,
      'booking_id'    => $booking_id,
      'service_id'    => $serviceType,
      'transaction_type' => $type,
      'amount'        => $amount,
      'balance_after' => $balanceAfter,
      $walletField => $refundAmount,
      'category'      => $category,
      'description'   => $desc,
      'prev_checksum' => $prev,
      'checksum'      => $checksum
    ]);
  }
  public function getServiceWalletSum($user_id)
  {
    $serviceAmountResult = $this->db->table($this->user_service_wallet)
      ->selectSum('walking_amount', 'walking_amount')
      ->selectSum('training_amount', 'training_amount')
      ->selectSum('boarding_amount', 'boarding_amount')
      ->selectSum('grooming_amount', 'grooming_amount')
      ->where('user_id', $user_id)
      ->get()
      ->getRow();

    $totalServiceAmount = [
      'service_amount'  => ($serviceAmountResult->walking_amount ?? 0) +
        ($serviceAmountResult->training_amount ?? 0) +
        ($serviceAmountResult->boarding_amount ?? 0) +
        ($serviceAmountResult->grooming_amount ?? 0),
>>>>>>> origin/main
      'walking_amount'  => $serviceAmountResult->walking_amount ?? 0,
      'training_amount' => $serviceAmountResult->training_amount ?? 0,
      'boarding_amount' => $serviceAmountResult->boarding_amount ?? 0,
      'grooming_amount' => $serviceAmountResult->grooming_amount ?? 0
    ];

    return $totalServiceAmount;
  }


  // Function to get withdrawal wallet sum
<<<<<<< HEAD
  public function getWithdrawalWalletSum( $user_id )
  {
    $withdrawalAmountResult = $this->db->table ( 'user_withdrawal_wallet' )
      ->selectSum ( 'amount', 'withdrawal_amount' )
      ->where ( 'user_id', $user_id )
      ->get ()
      ->getRow ();

    return $withdrawalAmountResult->withdrawal_amount ?? 0;
  }
=======
  public function getWithdrawalWalletSum($user_id)
  {
    $withdrawalAmountResult = $this->db->table('user_withdrawal_wallet')
      ->selectSum('amount', 'withdrawal_amount')
      ->where('user_id', $user_id)
      ->get()
      ->getRow();

    return $withdrawalAmountResult->withdrawal_amount ?? 0;
  }
  public function getUserById($id)
  {
    return $this->db->table('users')
      ->select('*')
      ->where('id', $id)
      ->get()
      ->getRow();
  }
>>>>>>> origin/main
}
