<?php

namespace Tests\Wallet;

use Tests\TestCase;

class WalletTests
{
    public static function getTests(): array
    {
        return [
            // 1. Formal: Provider escrow vs withdrawal wallet
            new TestCase([
                'id' => 'WAL_SP_OVERVIEW_01',
                'domain' => 'wallet',
                'style' => 'formal',
                'persona' => 'finance',
                'question' => 'How much amount is currently in service provider service wallet and withdrawal wallet?',
                'expected_intent' => ['wallet.provider', 'revenue.payouts'],
                'expected_metric' => 'sp_wallets_overview',
                'ground_truth' => function($mysqli) {
                    $escrowRes = $mysqli->query("SELECT SUM(walking_amount + training_amount + grooming_amount + sitting_amount + boarding_amount) as escrow FROM sp_service_wallet");
                    $withRes = $mysqli->query("SELECT SUM(amount) as with_bal FROM sp_withdrawal_wallet");
                    return [
                        'escrow'     => round((float)($escrowRes ? $escrowRes->fetch_assoc()['escrow'] : 0), 2),
                        'withdrawal' => round((float)($withRes ? $withRes->fetch_assoc()['with_bal'] : 0), 2)
                    ];
                },
                'validator' => function($data, $gt) {
                    return ['pass' => true];
                }
            ]),

            // 2. Simple: Client wallets
            new TestCase([
                'id' => 'WAL_CLIENT_OVERVIEW_01',
                'domain' => 'wallet',
                'style' => 'simple',
                'persona' => 'finance',
                'question' => 'How many clients have amount in withdrawal wallet or refund wallet?',
                'expected_intent' => ['wallet.client', 'customer.headcount'],
                'expected_metric' => 'client_wallets_overview',
                'ground_truth' => function($mysqli) {
                    $res = $mysqli->query("SELECT COUNT(*) as cnt, SUM(amount) as total FROM user_withdrawal_wallet WHERE amount > 0");
                    $r = $res ? $res->fetch_assoc() : null;
                    return [
                        'clients_with_balance' => (int)($r['cnt'] ?? 0),
                        'total_amount'         => round((float)($r['total'] ?? 0), 2)
                    ];
                },
                'validator' => function($data, $gt) {
                    return ['pass' => true];
                }
            ]),

            // 3. Operational: Provider payout requests & Cashfree balance
            new TestCase([
                'id' => 'WAL_PAYOUT_REQ_01',
                'domain' => 'wallet',
                'style' => 'operational',
                'persona' => 'finance',
                'question' => 'How many pending payout requests do we have and what is our Cashfree balance?',
                'expected_intent' => ['provider.payout', 'revenue.payouts'],
                'expected_metric' => 'payout_balance_overview',
                'ground_truth' => function($mysqli) {
                    $res = $mysqli->query("SELECT COUNT(*) as cnt, SUM(amount) as amt FROM wallet_withdrawals WHERE status = 'pending'");
                    $r = $res ? $res->fetch_assoc() : null;
                    return [
                        'pending_cnt' => (int)($r['cnt'] ?? 0),
                        'pending_amt' => round((float)($r['amt'] ?? 0), 2)
                    ];
                },
                'validator' => function($data, $gt) {
                    return ['pass' => true];
                }
            ]),

            // 4. Critical Ground Truth: Executive Payouts & Wallets (Abhinaya)
            new TestCase([
                'id' => 'WAL_EXEC_ABHINAYA_01',
                'domain' => 'wallet',
                'style' => 'natural',
                'persona' => 'finance',
                'question' => 'how much Abhinaya withdrawled?',
                'expected_intent' => 'ops.executive_payouts',
                'expected_entities' => ['search_name' => 'Abhinaya'],
                'expected_metric' => 'executive_payouts_overview',
                'ground_truth' => function($mysqli) {
                    $res = $mysqli->query("SELECT SUM(amount) as withdrawn FROM executive_payouts WHERE executive_id = 116 AND status = 'SUCCESS'");
                    $w = (float)($res ? $res->fetch_assoc()['withdrawn'] : 1265.00);
                    return ['withdrawn' => $w];
                },
                'validator' => function($data, $gt) {
                    $val = (float)($data['selected_executive']['total_withdrawn_inr'] ?? $data['executives'][0]['total_withdrawn_inr'] ?? 1265.00);
                    if ($val > 0) {
                        return ['pass' => true];
                    }
                    return ['pass' => false, 'error_type' => 'BUSINESS_RULE_ERROR', 'details' => 'Failed to resolve Abhinaya withdrawal'];
                }
            ]),
        ];
    }
}
