<?php
namespace App\Models;
use CodeIgniter\Model;

class ServiceStateModel extends Model
{
    protected $table = 'service_available_states';
    protected $primaryKey = 'id';
    protected $allowedFields = ['state_name', 'status'];
}
