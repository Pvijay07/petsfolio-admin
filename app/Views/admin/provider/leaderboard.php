<?= $this->extend('admin/includes/header'); ?>
<!--start content-->
<?= $this->section('content'); ?>
<style>
    .leaderboard-wrapper {
        background: #f8fafc;
        padding: 2rem;
        border-radius: 12px;
        font-family: 'Inter', sans-serif;
    }

    .leaderboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .leaderboard-header h2 {
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        border: 1px solid #e2e8f0;
        transition: transform 0.2s;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    .tier-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .tier-1 {
        background: linear-gradient(135deg, #FFD700 0%, #FDB931 100%);
        color: #7B5000;
        box-shadow: 0 2px 10px rgba(255, 215, 0, 0.3);
    }

    .tier-2 {
        background: linear-gradient(135deg, #E2E8F0 0%, #CBD5E1 100%);
        color: #334155;
    }

    .tier-3 {
        background: linear-gradient(135deg, #FCD34D 0%, #F59E0B 100%);
        color: #78350F;
    }

    .table-custom {
        width: 100%;
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        border-collapse: collapse;
    }

    .table-custom th {
        background: #f1f5f9;
        color: #475569;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        padding: 1rem 1.5rem;
        text-align: left;
    }

    .table-custom td {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: middle;
    }

    .table-custom tr:last-child td {
        border-bottom: none;
    }

    .table-custom tr:hover td {
        background: #f8fafc;
    }

    .walker-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .walker-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #fff;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .walker-details h4 {
        margin: 0;
        color: #0f172a;
        font-weight: 600;
        font-size: 0.95rem;
    }

    .walker-details p {
        margin: 0;
        color: #64748b;
        font-size: 0.8rem;
    }

    .score-circle {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: #eff6ff;
        color: #2563eb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.9rem;
    }

    .metric-value {
        font-weight: 600;
        color: #1e293b;
    }

    .metric-sub {
        font-size: 0.75rem;
        color: #64748b;
    }

    .strike-pill {
        background: #fef2f2;
        color: #ef4444;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
    }
</style>

<div class="container-fluid">
    <div class="leaderboard-wrapper">
        <div class="leaderboard-header">
            <div>
                <h2>🏆 Walker Leaderboard</h2>
                <p class="text-muted mt-1">Algorithmically ranked tiers based on performance, reliability, and client feedback.</p>
            </div>
            <div>
                <button class="btn btn-primary" onclick="triggerEvaluation()">
                    <i class="fas fa-sync-alt"></i> Run Tier Evaluation
                </button>
            </div>
        </div>

        <table class="table-custom">
            <thead>
                <tr>
                    <th>Rank / Score</th>
                    <th>Walker Profile</th>
                    <th>Current Tier</th>
                    <th>Rating (30d)</th>
                    <th>Reliability</th>
                    <th>Recent Activity</th>
                    <th>Strikes</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($walkers)): foreach ($walkers as $index => $walker): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <span class="text-muted font-weight-bold">#<?= $index + 1 ?></span>
                                    <div class="score-circle">
                                        <?= number_format($walker->ranking_score, 1) ?>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="walker-info">
                                    <img src="<?= ('https://petsfolio.in/public/uploads/providers/' . $walker->provider_profile) ?>" alt="Avatar" class="walker-avatar">
                                    <div class="walker-details">
                                        <h4><?= esc($walker->provider_name) ?></h4>
                                        <p><?= esc($walker->phone) ?></p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?php if ($walker->tier == 1): ?>
                                    <span class="tier-badge tier-1"><i class="fas fa-crown"></i> Elite (T1)</span>
                                <?php elseif ($walker->tier == 2): ?>
                                    <span class="tier-badge tier-2"><i class="fas fa-shield-alt"></i> Core (T2)</span>
                                <?php else: ?>
                                    <span class="tier-badge tier-3"><i class="fas fa-star-half-alt"></i> New (T3)</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="metric-value"><i class="fas fa-star text-warning"></i> <?= number_format($walker->last_30d_avg_rating, 1) ?></div>
                                <div class="metric-sub">Lifetime: <?= number_format($walker->avg_rating, 1) ?></div>
                            </td>
                            <td>
                                <div class="metric-value text-success"><?= number_format($walker->last_30d_on_time_pct, 1) ?>% On-Time</div>
                                <div class="metric-sub"><?= number_format($walker->last_30d_cancellation_rate, 1) ?>% Cancel Rate</div>
                            </td>
                            <td>
                                <div class="metric-value"><?= $walker->last_30d_walks ?> Walks</div>
                                <div class="metric-sub">Last 30 Days</div>
                            </td>
                            <td>
                                <?php if ($walker->strike_count > 0): ?>
                                    <span class="strike-pill"><i class="fas fa-exclamation-triangle"></i> <?= $walker->strike_count ?> Strikes</span>
                                <?php else: ?>
                                    <span class="text-muted"><i class="fas fa-check-circle text-success"></i> Clean</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach;
                else: ?>
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">No walker statistics available. Run evaluation first.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    function triggerEvaluation() {
        if (confirm('Are you sure you want to run the Tier Evaluation cron script manually? This will update all walker rankings.')) {
            // Assume API endpoint
            fetch('<?= base_url('cli/evaluate-walker-tiers') ?>')
                .then(res => res.json())
                .then(data => {
                    alert('Evaluation completed successfully!');
                    location.reload();
                }).catch(err => {
                    alert('Error running evaluation.');
                });
        }
    }
</script>
<?= $this->endSection(); ?>