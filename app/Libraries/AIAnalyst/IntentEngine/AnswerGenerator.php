<?php

namespace App\Libraries\AIAnalyst\IntentEngine;

/**
 * AnswerGenerator
 * 
 * NEXT 7 — Answer Generator:
 * Generates natural language responses for analytical queries and conversational follow-ups.
 * 
 * Key Principles:
 *  1. Zero Blind Guessing: Explicitly includes data caveats when breakdown counts exceed base metrics (153 vs 327).
 *  2. Conversational Continuity: Seamlessly connects follow-up solutions ("how to solve this issue?")
 *     to previously established findings without resetting the thread.
 *  3. Executive Actionability: Presents structured, professional action plans with estimated impacts.
 */
class AnswerGenerator
{
    /**
     * Generate an executive solution response for a follow-up query.
     * 
     * @param array $state Active conversation state
     * @param array $recommendations Planned recommendations
     * @param array $analysis Analytical findings
     * @param string $replyMode 'quick' or 'detailed'
     * @return string Markdown response
     */
    public static function generateSolutionResponse(
        array $state,
        array $recommendations,
        array $analysis,
        string $replyMode = 'detailed'
    ): string {
        $topicName = match ($state['topic'] ?? '') {
            'booking_cancellations'        => 'Booking Cancellations',
            'provider_onboarding_pipeline' => 'Provider Onboarding Pipeline Bottlenecks',
            'conversion_funnel'            => 'Conversion Funnel Dropoffs',
            default                        => 'Business Performance'
        };

        $baseVal = $state['base_metric_value'] ?? null;
        $metricLabel = str_replace('_', ' ', $state['metric'] ?? 'identified issues');

        $out = "### Strategic Action Plan to Solve {$topicName}\n\n";

        if ($baseVal !== null) {
            $out .= "Based on the previously analyzed **" . number_format((float)$baseVal) . " {$metricLabel}**";
            if (!empty($state['service'])) {
                $out .= " in **" . ucfirst($state['service']) . "**";
            }
            if (!empty($state['city'])) {
                $out .= " across **" . ucfirst($state['city']) . "**";
            }
            $out .= ", here is the prioritized operational roadmap to resolve root causes and reduce dropoffs:\n\n";
        } else {
            $out .= "Here is the prioritized operational roadmap to address the root causes identified in our previous analysis:\n\n";
        }

        // Include data quality transparency notice if applicable
        if (!empty($analysis['data_quality_caveat'])) {
            $out .= "> ℹ️ **Data Transparency Notice**: {$analysis['data_quality_caveat']}\n\n";
        }

        // Render Action Pillars
        foreach ($recommendations as $idx => $rec) {
            $num = $idx + 1;
            $pillar = $rec['pillar'] ?? 'Initiative';
            $title  = $rec['title'] ?? 'Solution';
            $desc   = $rec['description'] ?? '';
            $impact = $rec['expected_impact'] ?? '';

            $out .= "#### {$num}. {$title} *({$pillar})*\n";
            $out .= "• **Action**: {$desc}\n";
            if (!empty($impact)) {
                $out .= "• **Expected Impact**: **{$impact}**\n";
            }
            $out .= "\n";
        }

        // Summary closing statement
        $out .= "---\n";
        $out .= "💡 **Next Step**: Would you like to inspect provider-level metrics for this service, or drill down into specific pin codes to see where these initiatives should be piloted first?";

        return $out;
    }

    /**
     * Build the LLM grounding context text with strict consistency assertions.
     * Injects data quality caveats so Gemini never presents conflicting numbers.
     * 
     * @param string $userMessage
     * @param array  $metricsCalled
     * @param array  $analysis
     * @return string
     */
    public static function buildGroundingPromptContext(string $userMessage, array $metricsCalled, array $analysis): string
    {
        $context = "User Question: " . $userMessage . "\n\n";
        $context .= "--- LIVE PRODUCTION DATABASE METRICS (Ground Truth) ---\n";

        foreach ($metricsCalled as $mc) {
            $mName = $mc['name'];
            $mData = $mc['data'] ?? [];
            $context .= "[Metric: {$mName}]\n" . json_encode($mData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n\n";
        }

        $context .= "--- ANALYTICAL REASONING & CONSISTENCY ASSERTIONS ---\n";
        if (!empty($analysis['headline'])) {
            $context .= "- Baseline: {$analysis['headline']}\n";
        }
        if (!empty($analysis['dominant_driver'])) {
            $context .= "- Key Driver: {$analysis['dominant_driver']}\n";
        }
        if (!empty($analysis['data_quality_caveat'])) {
            $context .= "- CRITICAL DATA CAVEAT: {$analysis['data_quality_caveat']}\n"
                . "  YOU MUST NEVER blindly report that there are more cancellation reasons than cancelled bookings without explicitly stating that the reasons reflect multi-event history logs per booking.\n";
        }

        $context .= "\nPlease synthesize the above live production database metrics into a clear, accurate, and professional executive response answering the user's question directly.";

        return $context;
    }
}
