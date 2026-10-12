<?php

namespace App\Libraries\AIAnalyst;

/**
 * DateRangeHelper
 * 
 * Provides flexible date range resolution for AI Business Analyst queries across MySQL & MongoDB.
 */
class DateRangeHelper
{
    /**
     * Resolves a date_range string, start_date, and end_date into start/end datetime strings ('Y-m-d H:i:s').
     *
     * @param string $dateRange E.g. '30_days', 'this_month', 'last_month', 'today', 'yesterday', 'this_week', 'last_week', 'this_quarter', 'last_quarter', 'this_year', 'last_year', 'all_time', 'custom'
     * @param string|null $startDate E.g. '2026-06-01'
     * @param string|null $endDate E.g. '2026-06-30'
     * @return array [?string $startDateTime, ?string $endDateTime]
     */
    public static function resolve(string $dateRange = '30_days', ?string $startDate = null, ?string $endDate = null): array
    {
<<<<<<< HEAD
=======
        date_default_timezone_set('Asia/Kolkata');

>>>>>>> origin/main
        // 1. Direct start_date and end_date arguments override dateRange if provided
        if (!empty($startDate) || !empty($endDate)) {
            $s = !empty($startDate) ? self::formatStart($startDate) : null;
            $e = !empty($endDate) ? self::formatEnd($endDate) : null;
            return [$s, $e];
        }

        $clean = strtolower(trim($dateRange));
        $cleanNormalized = str_replace(['-', ' '], '_', $clean);

        // 2. Check for explicit date range inside string e.g. "2026-06-01 to 2026-06-30" or "2026_06_01_2026_06_30"
        if (preg_match('/(\d{4}[-_\.]\d{2}[-_\.]\d{2}).*?(\d{4}[-_\.]\d{2}[-_\.]\d{2})/', $clean, $m)) {
            $sDate = preg_replace('/[-_\.]/', '-', $m[1]);
            $eDate = preg_replace('/[-_\.]/', '-', $m[2]);
            return [self::formatStart($sDate), self::formatEnd($eDate)];
        }

        // Single date string e.g. "2026-06-15"
        if (preg_match('/^\d{4}[-_\.]\d{2}[-_\.]\d{2}$/', $clean)) {
            $single = preg_replace('/[-_\.]/', '-', $clean);
            return [self::formatStart($single), self::formatEnd($single)];
        }

<<<<<<< HEAD
=======
        // Named month and optional year e.g. "september 2026", "september_2026", "september month 2026"
        if (preg_match('/(january|february|march|april|may|june|july|august|september|october|november|december|jan|feb|mar|apr|may|jun|jul|aug|sep|sept|oct|nov|dec)(?:[_\s]+month)?[_\s]*(\d{4})?/i', $clean, $m)) {
            $monthStr = $m[1];
            $yearStr  = !empty($m[2]) ? $m[2] : date('Y');
            $timestamp = strtotime("first day of {$monthStr} {$yearStr}");
            if ($timestamp !== false) {
                return [
                    date('Y-m-01 00:00:00', $timestamp),
                    date('Y-m-t 23:59:59', $timestamp)
                ];
            }
        }

>>>>>>> origin/main
        // 3. Named date range presets
        switch ($cleanNormalized) {
            case 'today':
                return [
                    date('Y-m-d 00:00:00'),
                    date('Y-m-d 23:59:59')
                ];

            case 'yesterday':
                $y = date('Y-m-d', strtotime('-1 day'));
                return [
                    "{$y} 00:00:00",
                    "{$y} 23:59:59"
                ];

            case 'this_week':
            case 'week':
            case 'current_week':
                $startOfWeek = date('Y-m-d', strtotime('monday this week'));
                return [
                    "{$startOfWeek} 00:00:00",
                    date('Y-m-d 23:59:59')
                ];

            case 'last_week':
            case 'previous_week':
                $startOfLastWeek = date('Y-m-d', strtotime('monday last week'));
                $endOfLastWeek   = date('Y-m-d', strtotime('sunday last week'));
                return [
                    "{$startOfLastWeek} 00:00:00",
                    "{$endOfLastWeek} 23:59:59"
                ];

            case 'this_month':
            case 'month':
            case 'current_month':
                return [
                    date('Y-m-01 00:00:00'),
                    date('Y-m-t 23:59:59')
                ];

            case 'last_month':
            case 'previous_month':
                $firstDayLastMonth = date('Y-m-01 00:00:00', strtotime('first day of last month'));
                $lastDayLastMonth  = date('Y-m-t 23:59:59', strtotime('last day of last month'));
                return [
                    $firstDayLastMonth,
                    $lastDayLastMonth
                ];

            case '7_days':
            case 'last_7_days':
            case '7days':
            case '7':
                return [
                    date('Y-m-d 00:00:00', strtotime('-7 days')),
                    date('Y-m-d 23:59:59')
                ];

            case '30_days':
            case 'last_30_days':
            case '30days':
            case '30':
                return [
                    date('Y-m-d 00:00:00', strtotime('-30 days')),
                    date('Y-m-d 23:59:59')
                ];

            case '90_days':
            case 'last_90_days':
            case '90days':
            case '90':
                return [
                    date('Y-m-d 00:00:00', strtotime('-90 days')),
                    date('Y-m-d 23:59:59')
                ];

            case 'this_quarter':
            case 'quarter':
            case 'current_quarter':
                $curMonth = (int) date('n');
                $curYear  = date('Y');
                if ($curMonth <= 3) {
                    return ["{$curYear}-01-01 00:00:00", "{$curYear}-03-31 23:59:59"];
                } elseif ($curMonth <= 6) {
                    return ["{$curYear}-04-01 00:00:00", "{$curYear}-06-30 23:59:59"];
                } elseif ($curMonth <= 9) {
                    return ["{$curYear}-07-01 00:00:00", "{$curYear}-09-30 23:59:59"];
                } else {
                    return ["{$curYear}-10-01 00:00:00", "{$curYear}-12-31 23:59:59"];
                }

            case 'last_quarter':
            case 'previous_quarter':
                $curMonth = (int) date('n');
                $curYear  = (int) date('Y');
                if ($curMonth <= 3) {
                    $prevYear = $curYear - 1;
                    return ["{$prevYear}-10-01 00:00:00", "{$prevYear}-12-31 23:59:59"];
                } elseif ($curMonth <= 6) {
                    return ["{$curYear}-01-01 00:00:00", "{$curYear}-03-31 23:59:59"];
                } elseif ($curMonth <= 9) {
                    return ["{$curYear}-04-01 00:00:00", "{$curYear}-06-30 23:59:59"];
                } else {
                    return ["{$curYear}-07-01 00:00:00", "{$curYear}-09-30 23:59:59"];
                }

            case 'this_year':
            case 'year':
            case 'current_year':
            case '365':
            case '365_days':
                $curYear = date('Y');
                return [
                    "{$curYear}-01-01 00:00:00",
                    "{$curYear}-12-31 23:59:59"
                ];

            case 'last_year':
            case 'previous_year':
                $prevYear = (int) date('Y') - 1;
                return [
                    "{$prevYear}-01-01 00:00:00",
                    "{$prevYear}-12-31 23:59:59"
                ];

            case 'all':
            case 'all_time':
            case 'lifetime':
            case 'overall':
<<<<<<< HEAD
=======
            case 'till_today':
            case 'until_today':
            case 'to_date':
            case 'as_of_today':
>>>>>>> origin/main
                return [null, null];
        }

        // 4. Fallback for relative numeric day count e.g. "60_days" or "14"
        if (preg_match('/(\d+)/', $cleanNormalized, $matches)) {
            $days = (int) $matches[1];
            if ($days >= 9999) {
                return [null, null];
            }
            return [
                date('Y-m-d 00:00:00', strtotime("-{$days} days")),
                date('Y-m-d 23:59:59')
            ];
        }

        // Default to last 30 days
        return [
            date('Y-m-d 00:00:00', strtotime('-30 days')),
            date('Y-m-d 23:59:59')
        ];
    }

    private static function formatStart(string $d): string
    {
        $d = trim($d);
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $d)) {
            return "{$d} 00:00:00";
        }
        return date('Y-m-d H:i:s', strtotime($d));
    }

    private static function formatEnd(string $d): string
    {
        $d = trim($d);
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $d)) {
            return "{$d} 23:59:59";
        }
        return date('Y-m-d H:i:s', strtotime($d));
    }
}
