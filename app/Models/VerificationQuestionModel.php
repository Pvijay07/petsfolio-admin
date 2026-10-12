<?php

namespace App\Models;

use CodeIgniter\Model;

class VerificationQuestionModel extends Model
{
    protected $table            = 'verification_questions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'service_id',
        'category_name',
        'category_weight',
        'question_text',
        'hint_text',
        'weight',
        'is_trigger',
        'is_dependent',
        'sort_order',
        'status',
        'field_name'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getQuestionsByService($serviceId)
    {
        return $this->where('service_id', $serviceId)
                    ->where('status', 'active')
                    ->orderBy('sort_order', 'ASC')
                    ->findAll();
    }

    public function getOrganizedQuestions($serviceId)
    {
        $questions = $this->getQuestionsByService($serviceId);
        $organized = [];

        $iconMapping = [
            'Behaviour' => 'fa-brain',
            'Practical' => 'fa-walking',
            'Knowledge' => 'fa-book',
            'Safety' => 'fa-shield-alt',
            'Commitment' => 'fa-calendar-check',
            'Client' => 'fa-user-tie',
            'Trust' => 'fa-handshake',
            'Background' => 'fa-history',
            'Logistics' => 'fa-truck'
        ];

        foreach ($questions as $q) {
            $catName = $q['category_name'];
            if (!isset($organized[$catName])) {
                $icon = 'fa-info-circle';
                foreach ($iconMapping as $key => $iconClass) {
                    if (stripos($catName, $key) !== false) {
                        $icon = $iconClass;
                        break;
                    }
                }

                $organized[$catName] = [
                    'id' => str_replace(' ', '', $catName),
                    'label' => $catName,
                    'icon' => $icon,
                    'weight' => (float)$q['category_weight'],
                    'questions' => []
                ];
            }
            $organized[$catName]['questions'][] = $q;
        }

        return array_values($organized);
    }
}
