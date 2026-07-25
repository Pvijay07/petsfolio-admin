<?php

namespace App\Models;

use CodeIgniter\Model;

class CallKeywordModel extends Model
{
    protected $table            = 'call_keywords';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'analysis_id',
        'keyword',
        'frequency'
    ];

    public function saveKeywords(int $analysisId, array $keywords): bool
    {
        if (empty($keywords)) {
            return true;
        }

        $this->where('analysis_id', $analysisId)->delete();

        $rows = [];
        foreach ($keywords as $kw => $freq) {
            if (is_numeric($kw)) {
                $word = $freq;
                $count = 1;
            } else {
                $word = $kw;
                $count = (int)$freq;
            }

            $rows[] = [
                'analysis_id' => $analysisId,
                'keyword'     => strtolower(trim($word)),
                'frequency'   => $count
            ];
        }

        return (bool)$this->insertBatch($rows);
    }
}
