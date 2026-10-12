<?php

namespace Tests;

class TestCase
{
    public string $id;
    public string $domain;
    public string $style;
    public string $persona;
    public string $question;
    public ?string $historyText;
    public ?array $conversationState;
    public $expectedIntentId; // string or array of strings
    public array $expectedEntities;
    public $expectedMetric;   // string or array of strings or null
    public array $expectedMetricArgs;
    public bool $isAmbiguous;
    public $groundTruthClosure; // callable ($mysqli): array
    public $validationClosure;  // callable ($metricResult, $groundTruth): array

    public function __construct(array $params)
    {
        $this->id                  = $params['id'] ?? uniqid('TEST_');
        $this->domain              = $params['domain'] ?? 'general';
        $this->style               = $params['style'] ?? 'formal';
        $this->persona             = $params['persona'] ?? 'admin';
        $this->question            = $params['question'];
        $this->historyText         = $params['history'] ?? null;
        $this->conversationState   = $params['state'] ?? null;
        $this->expectedIntentId    = $params['expected_intent'] ?? null;
        $this->expectedEntities    = $params['expected_entities'] ?? [];
        $this->expectedMetric      = $params['expected_metric'] ?? null;
        $this->expectedMetricArgs  = $params['expected_metric_args'] ?? [];
        $this->isAmbiguous         = (bool) ($params['is_ambiguous'] ?? false);
        $this->groundTruthClosure  = $params['ground_truth'] ?? null;
        $this->validationClosure   = $params['validator'] ?? null;
    }
}
