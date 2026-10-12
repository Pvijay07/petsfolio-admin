<?php

namespace App\Models;

use CodeIgniter\Model;

class ServiceModel extends Model
{
    protected $table = 'services';
<<<<<<< HEAD
    protected $returnType = 'array';
    protected $db;
    public function __construct()
    {
        parent::__construct();
=======
    protected $db;
    public function __construct()
    {
>>>>>>> origin/main
        $this->db = \Config\Database::connect();
    }
    public function getServices()
    {
        return $this->db->table('services')
            ->select('services.id, services.name, services.description, services.icon,services.status
    ')
            ->get()->getResult();
    }
    public function getPackages()
    {
        return $this->db->table('packages')
            ->select('packages.*')
            ->orderBy('packages.id', 'ASC')

            ->get()->getResult();
    }
    public function getWalkingAddons()
    {
        return $this->db->table('addons')
            ->select('addons.*')
            ->where('addons.service_id', '4')
            ->orderBy('addons.id', 'ASC')

            ->get()->getResult();
    }
    public function updateAddon($id, $name, $price, $status)
    {
        return $this->db->table('addons')
            ->where('id', $id)
            ->update(['name' => $name, 'price' => $price, 'status' => $status]);
    }
    public function updatePackage($id, $name, $price, $status, $addons)
    {
        return $this->db->table('packages')
            ->where('id', $id)
            ->update(['package_name' => $name, 'price' => $price, 'status' => $status,  'included_addons' => ($addons)]);
    }
    public function getWalkingPackages()
    {
        return $this->db->table('packages')
            ->select('packages.*')
            ->where('packages.service_id', '4')
            ->get()->getResult();
    }
    public function getServicePackages($service_id)
    {
        return $this->db->table('packages')
            ->select('packages.*')
            ->where('packages.service_id', $service_id)
            ->get()->getResult();
    }
    public function getServiceAddons($service_id)
    {
        return $this->db->table('addons')
            ->select('addons.*')
            ->where('addons.service_id', $service_id)
            ->orderBy('addons.id', 'ASC')

            ->get()->getResult();
    }
    public function updateStatus($id, $status, $table)
    {
        return $this->db->table($table)
            ->where('id', $id)
            ->update(['status' => $status]);
    }
    public function addAddon($data)
    {
        $this->db->table('addons')
            ->insert($data);
        return $this->db->insertID();
    }
    public function deleteAddon($id)
    {
        return $this->db->table('addons')
            ->where('id', $id)
            ->delete();
    }
    public function addPackageAddons($data)
    {
        return $this->db->table('package_addons')
            ->insert($data);
    }
    public function addPackage($service_id, $name, $price, $duration, $status, $addons)
    {
        return $this->db->table('packages')->insert([
            'package_name'     => $name,
            'price'           => $price,
            'duration_days'   => $duration,
            'status'          => $status,
            'service_id'      => $service_id,
            'included_addons' => ($addons)
        ]);
    }

    public function deletePackage($id)
    {
        return $this->db->table('packages')
            ->where('id', $id)
            ->delete();
    }
    public function getUser($id, $table)
    {
        return $this->db->table($table)
            ->where('id', $id)
            ->get()->getRow();
    }
    public function getUserId($id, $table)
    {
        return $this->db->table($table)
            ->select('device_token')
            ->where('id', $id)
            ->get()->getRow();
    }
    public function updateUser($id, $data)
    {
        $builder = $this->db->table($data['module']);

        $builder->set('name', $data['name']);
        $builder->set('email', $data['email']);
        $builder->set('phone', $data['phone']);
        $builder->set('gender', $data['gender']);
        if (isset($data['dob'])) {
            $builder->set('dob', $data['dob']);
        }
        $builder->set('status', $data['status']);

        $builder->where('id', $id);

        return $builder->update();
    }
    public function deleteUser($id, $module)
    {
        return $this->db->table($module)
            ->where('id', $id)
            ->delete();
    }
    public function getUsers()
    {
        $users = $this->db->table('users')
            ->select('users.id, users.name, users.phone, users.email')
            ->orderBy('id', 'DESC')
            ->get()
            ->getResultArray();

        foreach ($users as &$user) {
            // Get addresses for the user and join them with a newline
            $addresses = $this->db->table('user_addresses')
                ->select('address')
                ->where('user_id', $user['id'])
                ->get()
                ->getResultArray();

            // Concatenate addresses with a newline
            $user['addresses'] = implode("\n", array_column($addresses, 'address'));
            $pets = $this->db->table('user_pets')
<<<<<<< HEAD
            ->select('name, type')
            ->where('user_id', $user['id'])
            ->get()
            ->getResultArray();

        $petStrings = [];
        foreach ($pets as $pet) {
            $petStrings[] = "{$pet['name']} ({$pet['type']})";
        }
        $user['pets'] = implode("\n", $petStrings);
=======
                ->select('name, type')
                ->where('user_id', $user['id'])
                ->get()
                ->getResultArray();

            $petStrings = [];
            foreach ($pets as $pet) {
                $petStrings[] = "{$pet['name']} ({$pet['type']})";
            }
            $user['pets'] = implode("\n", $petStrings);
>>>>>>> origin/main
        }

        return $users;
    }
    public function getWalkingQuotes($booking_id)
    {
        return $this->db->table('quotations')
            ->select('quotations.id')
            ->select('service_providers.name,service_providers.phone,service_providers.area')

            ->join('service_providers', 'service_providers.id = quotations.provider_id', 'left')
            ->join('walking_service_bookings', 'walking_service_bookings.id = quotations.booking_id', 'left')
            ->where('quotations.booking_id', $booking_id)
            ->orderBy('quotations.id', 'DESC')
            ->get()
            ->getResultArray();
    }
    public function getProviders()
    {
        return $this->db->table('service_providers')
            ->select('service_providers.name,service_providers.phone,service_providers.area,service_providers.city')
            ->orderBy('service_providers.id', 'DESC')
            ->get()
            ->getResultArray();
    }
    public function updateQueryStatus($queryId)
    {
        return $this->db->table('queries')
            ->where('id', $queryId)
            ->update(['status' => 'completed']);
    }
<<<<<<< HEAD
    public function getAllProviders($selectedIds, $status)
    {
        $builder = $this->db->table('service_providers');
        $builder->select('service_providers.name,service_providers.phone,service_providers.area,service_providers.city');
        $data = $builder->whereIn('id', $selectedIds)->where('status', $status)->orderBy('service_providers.id', 'DESC')->get()->getResultArray();
        return $data;
    }
    public function getAllUsers($selectedIds, $status)
    {
        $users = $this->db->table('users')
            ->select('users.id, users.name, users.phone, users.email')
            ->whereIn('id', $selectedIds)
            ->where('status', $status)
            ->orderBy('id', 'DESC')
=======
    public function getAllProviders($selectedIds, $status = null)
    {
        $builder = $this->db->table('service_providers');
        $builder->select('service_providers.name,service_providers.phone,service_providers.area,service_providers.city');
        $builder->whereIn('id', $selectedIds);
        if (!empty($status)) {
            $builder->where('status', $status);
        }
        $data = $builder->orderBy('service_providers.id', 'DESC')->get()->getResultArray();
        return $data;
    }
    public function getAllUsers($selectedIds, $status = null)
    {
        $builder = $this->db->table('users')
            ->select('users.id, users.name, users.phone, users.email')
            ->whereIn('id', $selectedIds);
        if (!empty($status)) {
            $builder->where('status', $status);
        }
        $users = $builder->orderBy('id', 'DESC')
>>>>>>> origin/main
            ->get()
            ->getResultArray();

        foreach ($users as &$user) {
            // Get addresses for the user and join them with a newline
            $addresses = $this->db->table('user_addresses')
                ->select('address')
                ->where('user_id', $user['id'])
                ->get()
                ->getResultArray();

            $user['addresses'] = implode("\n", array_column($addresses, 'address'));

            $pets = $this->db->table('user_pets')
                ->select('name, type')
                ->where('user_id', $user['id'])
                ->get()
                ->getResultArray();

            $petStrings = [];
            foreach ($pets as $pet) {
                $petStrings[] = "{$pet['name']} ({$pet['type']})";
            }
            $user['pets'] = implode("\n", $petStrings);
        }

        return $users;
    }

    public function getBanners()
    {
        return $this->db->table('banners')->orderBy('id', 'DESC')->get()->getResult();
    }

<<<<<<< HEAD
    public function getBanner($id)
=======
    public function getBannerById($id)
>>>>>>> origin/main
    {
        return $this->db->table('banners')->where('id', $id)->get()->getRow();
    }

    public function addBanner($data)
    {
<<<<<<< HEAD
        $this->db->table('banners')->insert($data);
        return $this->db->insertID();
    }

    public function updateBanner($id, $data)
    {
        return $this->db->table('banners')->where('id', $id)->update($data);
=======
        return $this->db->table('banners')->insert($data);
>>>>>>> origin/main
    }

    public function deleteBanner($id)
    {
        return $this->db->table('banners')->where('id', $id)->delete();
    }

    public function updateBannerStatus($id, $status)
    {
<<<<<<< HEAD
        $isActive = ($status === 'active' || $status === '1' || $status === 1) ? 1 : 0;
        $updateData = ['is_active' => $isActive];
        if ($this->db->fieldExists('status', 'banners')) {
            $updateData['status'] = $isActive ? 'active' : 'inactive';
        }
        return $this->db->table('banners')->where('id', $id)->update($updateData);
    }
       public function checkProviderJobStatus($provider_id){
        $data = $this->db->table('quotations')->where('provider_id', $provider_id)->where('status', 'Accepted')->get()->getRow();
        if($data){
            return true;
        }else{
=======
        $isActive = ($status === 'active' || $status == 1 || $status === '1') ? 1 : 0;
        return $this->db->table('banners')->where('id', $id)->update(['is_active' => $isActive]);
    }
    public function checkProviderJobStatus($provider_id)
    {
        $data = $this->db->table('quotations')->where('provider_id', $provider_id)->where('status', 'Accepted')->get()->getRow();
        if ($data) {
            return true;
        } else {
>>>>>>> origin/main
            return false;
        }
    }
}
