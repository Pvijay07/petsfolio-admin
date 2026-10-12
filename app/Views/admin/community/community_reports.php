<?= $this->extend('admin/includes/header'); ?>
<?= $this->section('content'); ?>

<link rel="stylesheet" href="<?= base_url('public/assets/css/community.css?v=2.1') ?>">

<main class="main-content community-wrapper">
    <div class="position-relative iq-banner">
        <?= $this->include('admin/nav_bar') ?>
    </div>

    <div class="container-fluid px-4 content-inner pb-0" id="page_layout" style="margin-top: -50px;">
        <!-- Hero Header -->
        <div class="pf-hero-banner">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4 pf-hero-content">
                <div class="d-flex align-items-center gap-3">
                    <div class="pf-hero-icon-pill">
                        <span class="material-symbols-outlined" style="font-size: 28px;">shield</span>
                    </div>
                    <div>
                        <h1 class="pf-hero-title mb-1">Community Moderation Queue</h1>
                        <p class="pf-hero-subtitle">Review flagged user contributions, inappropriate content reports, spam, and safety policy violations.</p>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <a href="<?= base_url('community/posts') ?>" class="pf-btn-glass">
                        <span class="material-symbols-outlined">dynamic_feed</span> Live Feed
                    </a>
                    <a href="<?= base_url('community/channels') ?>" class="pf-btn-glass">
                        <span class="material-symbols-outlined">forum</span> Channels Directory
                    </a>
                </div>
            </div>
        </div>

        <!-- Metric Statistics Cards -->
        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-lg-4">
                <div class="pf-stat-card">
                    <div class="pf-stat-header">
                        <div class="pf-stat-icon-wrapper rose">
                            <span class="material-symbols-outlined">flag</span>
                        </div>
                        <span class="pf-stat-badge emerald">Queue</span>
                    </div>
                    <div>
                        <div class="pf-stat-label">Active Flags Total</div>
                        <div class="pf-stat-value"><?= number_format($active_reports_count ?? count($reports)) ?></div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-4">
                <div class="pf-stat-card">
                    <div class="pf-stat-header">
                        <div class="pf-stat-icon-wrapper amber">
                            <span class="material-symbols-outlined">warning</span>
                        </div>
                        <span class="pf-stat-badge amber">High Priority</span>
                    </div>
                    <div>
                        <div class="pf-stat-label">Safety & Harassment</div>
                        <div class="pf-stat-value"><?= number_format($high_priority_count ?? 0) ?></div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-4">
                <div class="pf-stat-card">
                    <div class="pf-stat-header">
                        <div class="pf-stat-icon-wrapper emerald">
                            <span class="material-symbols-outlined">task_alt</span>
                        </div>
                        <span class="pf-stat-badge emerald">Resolved</span>
                    </div>
                    <div>
                        <div class="pf-stat-label">Actions Taken Today</div>
                        <div class="pf-stat-value"><?= number_format($resolved_today_count ?? 0) ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Filter Tabs -->
        <div class="pf-pill-nav-wrapper">
            <a href="<?= base_url('community/reports?status=all' . ($request->getVar('search') ? '&search=' . $request->getVar('search') : '')) ?>" 
               class="pf-pill-nav-item <?= (!isset($status) || $status == 'all' || $status == '') ? 'active' : '' ?>">
                <span class="material-symbols-outlined" style="font-size: 18px;">all_inbox</span>
                <span>All Reports</span>
            </a>

            <a href="<?= base_url('community/reports?status=pending' . ($request->getVar('search') ? '&search=' . $request->getVar('search') : '')) ?>" 
               class="pf-pill-nav-item <?= (($status ?? '') == 'pending') ? 'active' : '' ?>">
                <span class="material-symbols-outlined" style="font-size: 18px;">pending_actions</span>
                <span>Pending Investigation</span>
            </a>

            <a href="<?= base_url('community/reports?status=resolved' . ($request->getVar('search') ? '&search=' . $request->getVar('search') : '')) ?>" 
               class="pf-pill-nav-item <?= (($status ?? '') == 'resolved') ? 'active' : '' ?>">
                <span class="material-symbols-outlined" style="font-size: 18px;">block</span>
                <span>Blocked Posts</span>
            </a>
        </div>

        <!-- Search & Filter Bar -->
        <form action="<?= base_url('community/reports') ?>" method="GET" class="pf-filter-bar mb-4">
            <input type="hidden" name="status" value="<?= esc($status ?? 'all') ?>">

            <div class="pf-search-box">
                <span class="material-symbols-outlined">search</span>
                <input type="text" name="search" class="pf-search-input" placeholder="Search reports by flagged text, reporter, or reason..." value="<?= esc($request->getVar('search')) ?>">
            </div>

            <button type="submit" class="pf-btn-primary">
                <span class="material-symbols-outlined" style="font-size: 18px;">tune</span> Apply Filter
            </button>

            <?php if ($request->getVar('search')): ?>
                <a href="<?= base_url('community/reports?status=' . esc($status ?? 'all')) ?>" class="pf-btn-secondary">
                    <span class="material-symbols-outlined" style="font-size: 18px;">clear</span> Reset
                </a>
            <?php endif; ?>

            <div class="ms-auto text-muted small fw-bold d-none d-md-block">
                Showing <?= count($reports) ?> report items
            </div>
        </form>

        <!-- Reports Feed -->
        <div class="d-flex flex-column gap-3 mb-5">
            <?php if (!empty($reports)): ?>
                <?php foreach ($reports as $report): ?>
                    <?php
                    $reasonLower = strtolower($report['reason'] ?? '');
                    $reasonClass = 'general';
                    $reasonIcon = 'flag';

                    if (str_contains($reasonLower, 'spam')) {
                        $reasonClass = 'spam';
                        $reasonIcon = 'report';
                    } elseif (str_contains($reasonLower, 'hate') || str_contains($reasonLower, 'harass') || str_contains($reasonLower, 'danger') || str_contains($reasonLower, 'violence')) {
                        $reasonClass = 'hate';
                        $reasonIcon = 'gavel';
                    } elseif (str_contains($reasonLower, 'misinfo') || str_contains($reasonLower, 'medical')) {
                        $reasonClass = 'misinfo';
                        $reasonIcon = 'medical_services';
                    }

                    $diff = time() - strtotime($report['created_at']);
                    if ($diff < 60) $timeText = "Just now";
                    elseif ($diff < 3600) $timeText = round($diff / 60) . " mins ago";
                    elseif ($diff < 86400) $timeText = round($diff / 3600) . " hrs ago";
                    else $timeText = round($diff / 86400) . " days ago";
                    ?>
                    <div class="pf-report-card">
                        <!-- Top Row: Reason Pill & Meta -->
                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-2 pb-2 border-bottom">
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <span class="pf-reason-pill <?= $reasonClass ?>">
                                    <span class="material-symbols-outlined" style="font-size: 15px;"><?= $reasonIcon ?></span>
                                    <?= esc(ucwords($report['title'] ?? ($report['reason'] ?? 'Reported Issue'))) ?>
                                </span>

                                <?php if (!empty($report['channel_name'])): ?>
                                    <span class="pf-badge-channel">
                                        #<?= esc($report['channel_name']) ?>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <div class="d-flex align-items-center gap-2 small text-muted">
                                <span class="material-symbols-outlined" style="font-size: 15px;">schedule</span>
                                <span><?= $timeText ?></span>
                                <span class="mx-1">•</span>
                                <span><?= date('M d, Y', strtotime($report['created_at'])) ?></span>
                            </div>
                        </div>

                        <!-- Flagged Post Snippet -->
                        <div class="pf-report-quote-box">
                            <div class="text-muted small fw-bold mb-1 d-flex align-items-center gap-1">
                                <span class="material-symbols-outlined" style="font-size: 14px;">format_quote</span>
                                Flagged Content Snippet:
                            </div>
                            <div class="fw-semibold text-dark">
                                "<?= nl2br(esc(mb_strimwidth($report['post_text'] ?? '', 0, 240, '...'))) ?>"
                            </div>
                        </div>

                        <!-- Reporter & Reason Context -->
                        <div class="row g-2 align-items-center mb-3 p-2.5 rounded-3 bg-light border">
                            <div class="col-md-6 d-flex align-items-center gap-2">
                                <span class="material-symbols-outlined text-muted" style="font-size: 18px;">account_circle</span>
                                <span class="small text-muted fw-bold">Reported By:</span>
                                <span class="small fw-bold text-dark"><?= esc($report['name']) ?></span>
                                <?php if (($report['user_type'] ?? '') == 'admin'): ?>
                                    <span class="pf-badge-role admin" style="font-size: 0.6rem; padding: 0.15rem 0.5rem;">Admin</span>
                                <?php elseif (($report['user_type'] ?? '') == 'provider'): ?>
                                    <span class="pf-badge-role provider" style="font-size: 0.6rem; padding: 0.15rem 0.5rem;">Provider</span>
                                <?php else: ?>
                                    <span class="pf-badge-role client" style="font-size: 0.6rem; padding: 0.15rem 0.5rem;">Client</span>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-6 d-flex align-items-center justify-content-md-end gap-2">
                                <span class="small text-muted fw-bold">Post Status:</span>
                                <span class="pf-badge-status <?= ($report['post_status'] ?? 'active') == 'active' ? 'active' : 'blocked' ?>">
                                    <?= ucfirst($report['post_status'] ?? 'Active') ?>
                                </span>
                            </div>
                        </div>

                        <!-- Action Toolbar -->
                        <div class="d-flex flex-wrap align-items-center gap-2 pt-2 border-top">
                            <a href="<?= base_url('community/post/' . $report['post_id']) ?>" class="btn btn-sm btn-dark rounded-pill px-3 py-1.5 fw-bold d-inline-flex align-items-center gap-1">
                                <span class="material-symbols-outlined" style="font-size: 16px;">visibility</span> Inspect Post
                            </a>

                            <a href="<?= base_url('community/post-reports/' . $report['post_id']) ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1.5 fw-bold d-inline-flex align-items-center gap-1">
                                <span class="material-symbols-outlined" style="font-size: 16px;">ballot</span> View All Flags
                            </a>

                            <?php if (($report['post_status'] ?? 'active') != 'blocked'): ?>
                                <a href="<?= base_url('community/blockPost/' . $report['post_id']) ?>" class="btn btn-sm btn-outline-warning text-dark rounded-pill px-3 py-1.5 fw-bold d-inline-flex align-items-center gap-1" onclick="return confirm('Block this post from the public community?')">
                                    <span class="material-symbols-outlined" style="font-size: 16px;">block</span> Block Post
                                </a>
                            <?php else: ?>
                                <a href="<?= base_url('community/unblockPost/' . $report['post_id']) ?>" class="btn btn-sm btn-outline-success rounded-pill px-3 py-1.5 fw-bold d-inline-flex align-items-center gap-1">
                                    <span class="material-symbols-outlined" style="font-size: 16px;">check_circle</span> Unblock Post
                                </a>
                            <?php endif; ?>

                            <a href="<?= base_url('community/dismissReport/' . $report['id']) ?>" class="btn btn-sm btn-light border rounded-pill px-3 py-1.5 fw-bold text-muted d-inline-flex align-items-center gap-1 ms-sm-auto" onclick="return confirm('Dismiss this report?')">
                                <span class="material-symbols-outlined" style="font-size: 16px;">done</span> Dismiss Flag
                            </a>

                            <a href="<?= base_url('community/deletePost/' . $report['post_id']) ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3 py-1.5 fw-bold d-inline-flex align-items-center gap-1" onclick="return confirm('Permanently delete this entire post and its discussion?')">
                                <span class="material-symbols-outlined" style="font-size: 16px;">delete</span> Delete Post
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="pf-empty-state py-5">
                    <div class="pf-empty-icon text-success" style="background: #ecfdf5;">
                        <span class="material-symbols-outlined fill text-success" style="font-size: 36px;">task_alt</span>
                    </div>
                    <h4 class="fw-bold text-dark mb-1">Moderation Queue is Clear</h4>
                    <p class="text-muted mb-0">No active flags or reports currently need moderation review.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?= $this->include('admin/footer') ?>
<?= $this->endSection(); ?>