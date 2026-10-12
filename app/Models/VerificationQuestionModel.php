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
<<<<<<< HEAD
=======
        'options',
>>>>>>> origin/main
        'weight',
        'is_trigger',
        'is_dependent',
        'sort_order',
        'status',
<<<<<<< HEAD
        'field_name'
=======
        'field_name',
        'training_type',
        'group_key'
>>>>>>> origin/main
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
<<<<<<< HEAD
=======

    public function isFieldActive($value)
    {
        if ($value === null) {
            return false;
        }
        $val = trim(strtolower((string)$value));
        return !($val === '' || $val === '0' || $val === 'no' || $val === 'false' || $val === 'none');
    }

    public function getSelectedGroomingTypes($providerId, $details = null)
    {
        if (!$details) {
            $db = \Config\Database::connect();
            $details = $db->table('sp_grooming_details')->where('provider_id', $providerId)->get()->getRow();
        }

        $selected_types = [];
        if ($details) {
            $d = (array)$details;

            if (!empty($d['grooming_type'])) {
                $g = trim(strtolower((string)$d['grooming_type']));
                if ($g === 'grooming_trimming' || $g === 'grooming_and_trimming') {
                    return ['grooming_and_trimming'];
                } elseif ($g === 'only_grooming' || $g === 'grooming') {
                    return ['grooming'];
                } elseif ($g === 'grooming_haircut' || $g === 'grooming_and_cut') {
                    return ['grooming_and_cut'];
                }
            }

            // Check boolean columns if any
            foreach (['grooming_trimming', 'trimming', 'grooming_and_trimming'] as $k) {
                if (isset($d[$k]) && $this->isFieldActive($d[$k])) {
                    $selected_types[] = 'grooming_and_trimming';
                    break;
                }
            }
            foreach (['only_grooming', 'basic_grooming', 'grooming'] as $k) {
                if (isset($d[$k]) && $this->isFieldActive($d[$k])) {
                    $selected_types[] = 'grooming';
                    break;
                }
            }
            foreach (['grooming_haircut', 'grooming_styling', 'haircut', 'stylish_hair_cut', 'styling', 'grooming_and_cut'] as $k) {
                if (isset($d[$k]) && $this->isFieldActive($d[$k])) {
                    $selected_types[] = 'grooming_and_cut';
                    break;
                }
            }
        }

        if (empty($selected_types)) {
            $selected_types = ['grooming_and_trimming', 'grooming', 'grooming_and_cut'];
        }

        return array_values(array_unique($selected_types));
    }

    public function getSelectedTrainingTypes($providerId, $details = null)
    {
        if (!$details) {
            $db = \Config\Database::connect();
            $details = $db->table('sp_training_details')->where('provider_id', $providerId)->get()->getRow();
        }

        $selected_types = [];
        if ($details) {
            $d = (array)$details;
            foreach (['puppy_obedience', 'puppy_obedience_training', 'puppy_training', 'puppy', 'intermediate_training', 'obedience_training'] as $k) {
                if (isset($d[$k]) && $this->isFieldActive($d[$k])) {
                    $selected_types[] = 'obedience_training';
                    break;
                }
            }
            foreach (['behaviour', 'behaviour_training', 'behavior', 'behavior_training', 'behavioral_training'] as $k) {
                if (isset($d[$k]) && $this->isFieldActive($d[$k])) {
                    $selected_types[] = 'behavioral_training';
                    break;
                }
            }
            foreach (['advanced', 'advanced_training'] as $k) {
                if (isset($d[$k]) && $this->isFieldActive($d[$k])) {
                    $selected_types[] = 'advanced_training';
                    break;
                }
            }
        }

        if (empty($selected_types)) {
            $selected_types = ['obedience_training', 'behavioral_training', 'advanced_training'];
        }

        return array_values(array_unique($selected_types));
    }

    /**
     * Return filtered, deduplicated questions for a specific provider & service.
     * Matches the exact logic on https://admin.petsfolio.in/provider/verify/{provider_id}/{service_id}
     */
    public function getFilteredQuestionsForProvider(int $serviceId, ?int $providerId = null): array
    {
        $allQuestions = $this->getQuestionsByService($serviceId);

        $selectedTypes = [];
        if ($providerId && $serviceId == 2) {
            $selectedTypes = $this->getSelectedGroomingTypes($providerId);
        } elseif ($providerId && $serviceId == 3) {
            $selectedTypes = $this->getSelectedTrainingTypes($providerId);
        }

        $seenGroups = [];
        $seenNormalized = [];
        $filtered = [];

        foreach ($allQuestions as $q) {
            $q_type = !empty($q['training_type']) ? trim($q['training_type']) : '';
            if ($q_type !== '' && !empty($selectedTypes) && !in_array($q_type, $selectedTypes, true)) {
                continue;
            }

            $groupKey = !empty($q['group_key']) ? trim($q['group_key']) : '';
            $normText = strtolower(trim(preg_replace('/[^a-zA-Z0-9]/', '', (string)($q['question_text'] ?? ''))));

            if ($groupKey !== '') {
                if (isset($seenGroups[$groupKey])) {
                    continue;
                }
                $seenGroups[$groupKey] = true;
            } elseif ($normText !== '') {
                if (isset($seenNormalized[$normText])) {
                    continue;
                }
                $seenNormalized[$normText] = true;
            }

            $w = (float)($q['weight'] ?? 0);
            $isTrig = (int)($q['is_trigger'] ?? 0);
            $qText = (string)($q['question_text'] ?? '');
            $catName = (string)($q['category_name'] ?? 'General');

            $isCritical = (
                $w >= 5 ||
                $isTrig === 1 ||
                stripos($qText, 'offline') !== false ||
                stripos($catName, 'experience') !== false ||
                stripos($catName, 'safety') !== false ||
                stripos($catName, 'ethics') !== false
            );

            $filtered[] = [
                'id'            => !empty($q['field_name']) ? $q['field_name'] : ('q_' . $q['id']),
                'question_id'   => (int)$q['id'],
                'service_id'    => (int)$q['service_id'],
                'category'      => $catName,
                'category_name' => $catName,
                'question'      => $qText,
                'question_text' => $qText,
                'hint_text'     => (string)($q['hint_text'] ?? ''),
                'weight'        => $w,
                'training_type' => $q['training_type'] ?? null,
                'group_key'     => $q['group_key'] ?? null,
                'critical'      => $isCritical,
            ];
        }

        return $filtered;
    }
>>>>>>> origin/main
}
