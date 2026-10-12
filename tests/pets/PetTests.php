<?php

namespace Tests\Pets;

use Tests\TestCase;

class PetTests
{
    public static function getTests(): array
    {
        return [
            // 1. Formal: Total registered pets
            new TestCase([
                'id' => 'PET_FORMAL_01',
                'domain' => 'pets',
                'style' => 'formal',
                'persona' => 'ceo',
                'question' => 'What is the total number of registered pets on Petsfolio?',
                'expected_intent' => 'pets.demographics',
                'expected_metric' => 'pet_demographics',
                'ground_truth' => function($mysqli) {
                    $cnt = (int)$mysqli->query("SELECT COUNT(*) FROM user_pets")->fetch_row()[0];
                    return ['total_pets' => $cnt];
                },
                'validator' => function($data, $gt) {
                    $val = $data['total_registered_pets'] ?? 0;
                    if ($val === $gt['total_pets']) {
                        return ['pass' => true];
                    }
                    return ['pass' => false, 'error_type' => 'RESULT_INTERPRETATION_ERROR', 'details' => "Expected {$gt['total_pets']}, got {$val}"];
                }
            ]),

            // 2. Simple: How many pets?
            new TestCase([
                'id' => 'PET_SIMPLE_01',
                'domain' => 'pets',
                'style' => 'simple',
                'persona' => 'admin',
                'question' => 'How many pets are registered?',
                'expected_intent' => 'pets.demographics',
                'expected_metric' => 'pet_demographics',
                'ground_truth' => function($mysqli) {
                    $cnt = (int)$mysqli->query("SELECT COUNT(*) FROM user_pets")->fetch_row()[0];
                    return ['total_pets' => $cnt];
                },
                'validator' => function($data, $gt) {
                    $val = $data['total_registered_pets'] ?? 0;
                    if ($val === $gt['total_pets']) {
                        return ['pass' => true];
                    }
                    return ['pass' => false, 'error_type' => 'RESULT_INTERPRETATION_ERROR', 'details' => "Expected {$gt['total_pets']}, got {$val}"];
                }
            ]),

            // 3. Comparative: Dogs vs cats
            new TestCase([
                'id' => 'PET_COMP_01',
                'domain' => 'pets',
                'style' => 'comparative',
                'persona' => 'marketing',
                'question' => 'What is the breakdown of dogs versus cats?',
                'expected_intent' => 'pets.demographics',
                'expected_metric' => 'pet_demographics',
                'ground_truth' => function($mysqli) {
                    $res = $mysqli->query("SELECT type, COUNT(*) as cnt FROM user_pets GROUP BY type");
                    $counts = [];
                    while ($r = $res->fetch_assoc()) {
                        $counts[strtolower($r['type'])] = (int)$r['cnt'];
                    }
                    return ['species' => $counts];
                },
                'validator' => function($data, $gt) {
                    if (!empty($data['species_breakdown'])) {
                        return ['pass' => true];
                    }
                    return ['pass' => false, 'error_type' => 'RESULT_INTERPRETATION_ERROR', 'details' => 'No species breakdown found'];
                }
            ]),

            // 4. Ranking: Popular dog breeds
            new TestCase([
                'id' => 'PET_BREEDS_01',
                'domain' => 'pets',
                'style' => 'ranking',
                'persona' => 'marketing',
                'question' => 'What are the most popular dog breeds on the platform?',
                'expected_intent' => 'pets.breeds',
                'expected_metric' => 'dog_breeds_overview',
                'ground_truth' => function($mysqli) {
                    $res = $mysqli->query("SELECT COUNT(*) FROM dog_breeds");
                    return ['total_breeds' => (int)$res->fetch_row()[0]];
                },
                'validator' => function($data, $gt) {
                    if (!empty($data['top_breeds_by_pets']) || ($data['total_breeds'] ?? 0) > 0) {
                        return ['pass' => true];
                    }
                    return ['pass' => false, 'error_type' => 'RESULT_INTERPRETATION_ERROR', 'details' => 'Failed to resolve dog breeds list'];
                }
            ]),
        ];
    }
}
