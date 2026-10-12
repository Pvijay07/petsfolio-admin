<?php

namespace App\Libraries\AIAnalyst\IntentEngine;

/**
 * QueryNormalizer
 * 
 * Stage 1 of the Intent Engine pipeline.
 * Normalizes raw user input into a clean, standardized form:
 *   1. Lowercase
 *   2. Fix typos
 *   3. Strip filler phrases ("can you tell me about", "I want to know", etc.)
 *   4. Normalize whitespace
 *   5. Normalize intent-signal synonyms (colloquial → canonical terms)
 * 
 * The ORIGINAL message is preserved for entity extraction (names, phone numbers).
 * The NORMALIZED message is used for intent classification.
 */
class QueryNormalizer
{
    /**
     * Normalize a user message.
     * 
     * @param string $rawMessage  The original user input
     * @return array  ['original' => string, 'normalized' => string, 'lowered' => string]
     */
    public static function normalize(string $rawMessage): array
    {
        $original = trim($rawMessage);
        $lowered  = strtolower($original);

        // Step 1: Fix common typos
        $normalized = SynonymDictionary::fixTypos($lowered);

        // Step 2: Strip filler phrases
        $normalized = SynonymDictionary::stripFillerPhrases($normalized);

        // Step 3: Normalize intent-signal synonyms
        $normalized = SynonymDictionary::normalizeIntentSignals($normalized);

        // Step 4: Clean up whitespace
        $normalized = trim(preg_replace('/\s+/', ' ', $normalized));

        return [
            'original'   => $original,
            'normalized' => $normalized,
            'lowered'    => $lowered,
        ];
    }

    /**
     * Normalize conversation history text.
     * Lighter normalization — just lowercase, fix typos, normalize signals.
     */
    public static function normalizeHistory(?string $historyText): string
    {
        if (empty($historyText)) {
            return '';
        }
        $text = strtolower(trim($historyText));
        $text = SynonymDictionary::fixTypos($text);
        $text = SynonymDictionary::normalizeIntentSignals($text);
        return trim(preg_replace('/\s+/', ' ', $text));
    }
}
