<?php

namespace App\Models;

use CodeIgniter\Model;

class PremiumRequestModel extends Model
{
    protected $table = 'premium_requests';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'booking_id',
        'user_id',
        'package_name',
        'package_price',
        'status',
        'assigned_provider_id',
        'admin_price',
        'requested_at',
        'assigned_at',
        'admin_notes'
    ];

    public function getRequests()
    {
        $db = \Config\Database::connect();
        $builder = $db->table($this->table);
        $builder->select('premium_requests.*, users.name as user_name, users.phone as user_phone');
        $builder->select('walking_service_bookings.service_start_date, walking_service_bookings.service_end_date, walking_service_bookings.service_days, walking_service_bookings.service_frequency, walking_service_bookings.walk_duration, walking_service_bookings.preferable_time, walking_service_bookings.total_price');
        $builder->select('user_addresses.address, user_addresses.landmark_areaname as area, user_addresses.city, user_addresses.state, user_addresses.houseno_floor, user_addresses.building_blockno');
        $builder->select("GROUP_CONCAT(DISTINCT user_pets.name SEPARATOR ', ') as pet_names");
        $builder->select("GROUP_CONCAT(DISTINCT user_pets.breed SEPARATOR ', ') as pet_breeds");
        $builder->select('services.name as service_name');

        $builder->join('users', 'users.id = premium_requests.user_id', 'left');
        $builder->join('walking_service_bookings', 'walking_service_bookings.id = premium_requests.booking_id', 'left');
        $builder->join('services', 'services.id = walking_service_bookings.service_id', 'left');
        $builder->join('user_addresses', 'user_addresses.id = walking_service_bookings.address_id', 'left');
        $builder->join('booking_pets', 'booking_pets.booking_id = premium_requests.booking_id', 'left');
        $builder->join('user_pets', 'user_pets.id = booking_pets.pet_id', 'left');
        $builder->where('walking_service_bookings.status', 'New');
        $builder->where('walking_service_bookings.type', 'premium');

        $builder->groupBy('premium_requests.id');
        $builder->orderBy('premium_requests.requested_at', 'DESC');
        return $builder->get()->getResult();
    }

    public function getByBookingId($booking_id)
    {
        return $this->where('booking_id', $booking_id)->first();
    }

    public function updateStatus($booking_id, $status, $data = [])
    {
        $data['status'] = $status;
        return $this->where('booking_id', $booking_id)->set($data)->update();
    }
}
