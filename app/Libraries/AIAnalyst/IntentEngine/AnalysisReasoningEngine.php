<?php

namespace App\Libraries\AIAnalyst\IntentEngine;

/**
 * AnalysisReasoningEngine
 * 
 * NEXT 5 — Analysis/Reasoning Engine:
 * Converts raw validated database metrics into analytical business findings,
 * driver shares, Pareto friction points, and transparent data quality caveats.
 * 
 * Never blindly passes contradictory numbers to the narrative synthesizer.
 */
class AnalysisReasoningEngine
{
    /**
     * Analyze executed metrics and extract structured findings and root causes.
     * 
     * @param array $metricsCalled Array of executed metrics with their data
     * @param array $state         Active conversation state if available
     * @return array Analytical findings package
     */
    public static function analyze(array $metricsCalled, ?array $state = null): array
    {
        $findings = [];
        $frictionPoints = [];
        $caveat = null;
        $headline = '';
        $dominantDriver = '';
        $shares = [];

        foreach ($metricsCalled as $mc) {
            $mName = $mc['name'] ?? '';
            $mData = $mc['data'] ?? [];

            // ── CANCELLATION & DROPOFF REASONING ──
            if ($mName === 'cancellation_rate' || $mName === 'dropoff_reasons_analysis') {
                $totalBookings = (int)($mData['total_bookings'] ?? 0);
                $cancelled     = (int)($mData['cancelled_bookings'] ?? $mData['total_halted'] ?? 0);
                $rate          = $mData['cancellation_rate'] ?? ($totalBookings > 0 ? round(($cancelled / $totalBookings) * 100, 1) . '%' : '0%');

                $headline = "Cancellation rate is {$rate} ({$cancelled} cancelled bookings out of {$totalBookings} total requests).";

                // Service breakdown reasoning
                $svcBreakdown = $mData['service_cancellation_breakdown'] ?? [];
                if (!empty($svcBreakdown)) {
                    $topSvc = $svcBreakdown[0] ?? null;
                    if ($topSvc) {
                        $sName = ucfirst($topSvc['service_type'] ?? 'Service');
                        $sCanc = (int)($topSvc['cancelled_bookings'] ?? 0);
                        $sPct  = $cancelled > 0 ? round(($sCanc / $cancelled) * 100, 1) : 0;
                        $dominantDriver = "{$sName} is the primary cancellation driver, accounting for {$sPct}% of all cancellations ({$sCanc} bookings).";
                        $findings[] = $dominantDriver;
                    }
                }

                // Cancellation reasons reasoning & discrepancy audit
                $reasons = $mData['cancellation_reasons'] ?? $mData['breakdown'] ?? [];
                if (!empty($reasons)) {
                    $reasonSum = 0;
                    $reasonEntries = [];

                    foreach ($reasons as $r) {
                        $rName = $r['reason'] ?? $r['code'] ?? 'Reason Not Specified';
                        $rCount = (int)($r['count'] ?? $r['dropoffs'] ?? 0);
                        $reasonSum += $rCount;
                        $reasonEntries[$rName] = $rCount;
                    }

                    // DATA QUALITY & CONTRADICTION AUDIT (The 153 vs 327 Issue)
                    if ($reasonSum > $cancelled) {
                        $caveat = "Data Inconsistency Audit: There are {$cancelled} unique cancelled bookings, but {$reasonSum} status log entries recorded across cancellation reason events. "
                            . "This variance occurs because the database records multiple status history events per booking attempt, and historical records often default to 'Reason Not Specified'.";
                        $findings[] = $caveat;
                    }

                    // Extract top actionable friction points (excluding generic unrecorded)
                    foreach ($reasonEntries as $rName => $rCount) {
                        if (stripos($rName, 'not specified') === false && stripos($rName, 'unrecorded') === false && $rCount > 0) {
                            $frictionPoints[] = [
                                'reason' => $rName,
                                'count'  => $rCount,
                                'impact' => "High operational friction"
                            ];
                        }
                    }

                    // Record unrecorded friction as a process gap
                    $unrecordedCount = $reasonEntries['Reason Not Specified / Unrecorded'] ?? $reasonEntries['Reason Not Specified'] ?? 0;
                    if ($unrecordedCount > 0) {
                        $findings[] = "Process Gap: {$unrecordedCount} cancellations have no specific reason recorded, indicating users/providers exit without mandatory reason feedback.";
                    }
                }
            }

            // ── REVENUE & COMMISSION REASONING ──
            if ($mName === 'revenue_overview') {
                $cleanComm = (float)($mData['clean_platform_commission'] ?? 0);
                $orphanComm = (float)($mData['orphaned_records_commission'] ?? 0);
                $headline = "Net Valid Platform Commission is ₹" . number_format($cleanComm, 2);
                if ($orphanComm > 0) {
                    $findings[] = "Ledger Audit: ₹" . number_format($orphanComm, 2) . " platform commission sits in orphaned historical ledger entries without matching booking records.";
                }
            }

            // ── PROVIDER PIPELINE REASONING ──
            if ($mName === 'provider_stats') {
                $totalProviders = (int)($mData['total_providers'] ?? $mData['total_service_providers'] ?? 0);
                $statusFilter   = $mData['status_filter'] ?? 'all';
                $headline = "Current provider pipeline count for [{$statusFilter}] is {$totalProviders} providers.";
            }
        }

        // If no metrics ran but state exists (e.g. solution follow-up)
        if (empty($findings) && !empty($state)) {
            $findings       = $state['findings'] ?? [];
            $caveat         = !empty($state['data_quality_warnings']) ? implode(' ', $state['data_quality_warnings']) : null;
            $dominantDriver = $state['findings'][0] ?? '';
            $headline       = "Analyzing verified findings for " . str_replace('_', ' ', $state['topic'] ?? 'business performance') . ".";
        }

        return [
            'headline'            => $headline,
            'dominant_driver'     => $dominantDriver,
            'findings'            => $findings,
            'friction_points'     => $frictionPoints,
            'data_quality_caveat' => $caveat,
            'shares'              => $shares
        ];
    }
}
