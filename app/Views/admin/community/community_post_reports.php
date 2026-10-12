<?= $this->extend('admin/includes/header'); ?>
<?= $this->section('content'); ?>

<<<<<<< HEAD
<!-- Google Font & Material Icons -->
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>

<script>
    tailwind.config = {
        theme: {
            extend: {
                fontFamily: {
                    sans: ['Plus Jakarta Sans', 'sans-serif'],
                },
                colors: {
                    brand: {
                        primary: '#4f46e5',
                        secondary: '#6366f1',
                        accent: '#8b5cf6',
                        dark: '#1e293b',
                    }
                }
            }
        }
    }
</script>


<div class="">
    <div class="position-relative iq-banner">
        <?php echo $this->include('admin/nav_bar') ?>
    </div>
    <div class="p-4" style="margin-top: -60px;">
        <a href="<?= base_url('community/reports') ?>" class="back-link">
            <span class="material-symbols-outlined">arrow_back</span> Back to Queue
        </a>

        <div class="post-detail-card">
            <?php if (!empty($media)): ?>
                <div class="position-relative group">
                    <a href="<?= $media['media_url'] ?>" download target="_blank" class="position-absolute top-0 end-0 m-2 btn btn-dark btn-sm rounded-circle d-flex align-items-center justify-content-center p-0" style="width: 32px; height: 32px; background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(4px); border: 1px solid rgba(255,255,255,0.2); z-index: 10;" title="Download Media">
                        <span class="material-symbols-outlined text-white fs-6">download</span>
                    </a>
                    <img src="<?= $media['media_url'] ?>" class="post-image">
                </div>
            <?php endif; ?>
            <div class="flex-grow-1">
                <h1 class="post-title"><?= esc($post['subject'] ?? 'Post Investigation') ?></h1>
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="post-handle mb-0">@<?= esc($post['name']) ?></span>
                    <?php if (($post['user_type'] ?? '') == 'admin'): ?>
                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2.5 py-0.5" style="font-size: 0.7rem; font-weight: 800;">ADMIN</span>
                    <?php elseif (($post['user_type'] ?? '') == 'provider'): ?>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-0.5" style="font-size: 0.7rem; font-weight: 800;">PROVIDER</span>
                    <?php else: ?>
                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-0.5" style="font-size: 0.7rem; font-weight: 800;">CLIENT</span>
                    <?php endif; ?>
                </div>

                <p class="post-summary">
                    "<?= esc($post['post_text']) ?>"
                </p>

                <div class="flex flex-row md:flex-row items-center gap-1.5 w-full md:w-auto md:self-center">
                    <a href="<?= base_url('community/post/' . $post['id']) ?>" class="flex-1 md:flex-none px-4 py-2 bg-slate-900 text-white rounded-lg text-[13px] font-900 uppercase tracking-widest hover:bg-brand-primary transition-all text-center">View</a>

                    <?php if (($post['status'] ?? 'active') != 'blocked'): ?>
                        <a href="<?= base_url('community/blockPost/' . $post['id']) ?>" class="flex-1 md:flex-none px-4 py-2 bg-slate-100 text-slate-700 rounded-lg text-[13px] font-900 uppercase tracking-widest hover:bg-slate-900 hover:text-white transition-all text-center">Block</a>
                    <?php else: ?>
                        <a href="<?= base_url('community/unblockPost/' . $post['id']) ?>" class="flex-1 md:flex-none px-4 py-2 bg-amber-50 text-amber-600 border border-amber-100 rounded-lg text-[13px] font-900 uppercase tracking-widest hover:bg-amber-600 hover:text-white transition-all text-center">Unblock</a>
                    <?php endif; ?>

                    <a href="<?= base_url('community/deletePost/' . $post['id']) ?>" class="flex-1 md:flex-none px-4 py-2 bg-rose-50 text-rose-600 rounded-lg text-[13px] font-900 uppercase tracking-widest hover:bg-rose-500 hover:text-white transition-all text-center border border-rose-100">Delete</a>
=======
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
                        <span class="material-symbols-outlined" style="font-size: 28px;">policy</span>
                    </div>
                    <div>
                        <h1 class="pf-hero-title mb-1">Content Flag Investigation</h1>
                        <p class="pf-hero-subtitle">Comprehensive breakdown of all community flags submitted against Post #<?= $post['id'] ?>.</p>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <a href="<?= base_url('community/reports') ?>" class="pf-btn-glass">
                        <span class="material-symbols-outlined">arrow_back</span> Back to Reports Queue
                    </a>
                    <a href="<?= base_url('community/post/' . $post['id']) ?>" class="pf-btn-glass">
                        <span class="material-symbols-outlined">visibility</span> View Post Thread
                    </a>
                    <a href="<?= base_url('community/dismissAllReports/' . $post['id']) ?>" class="pf-btn-hero-cta" onclick="return confirm('Dismiss all reports for this post?')">
                        <span class="material-symbols-outlined">done_all</span> Dismiss All Reports
                    </a>
>>>>>>> origin/main
                </div>
            </div>
        </div>

<<<<<<< HEAD
        <div class="reports-heading">
            Reported Comments (<?= count($reports) ?>)
            <button class="btn btn-sm text-muted fw-bold d-flex align-items-center gap-1 border-0">
                <span class="material-symbols-outlined" style="font-size: 1.2rem;">sort</span> Sort by Most Recent
            </button>
        </div>

        <div class="reports-container">
            <?php foreach ($reports as $index => $report): ?>
                <div class="report-card <?= $index % 2 == 0 ? 'report-border-blue' : 'report-border-red' ?>">
                    <div class="report-meta">
                        <div class="reporter-info">
                            <div class="reporter-avatar">
                                <?= strtoupper(substr($report['name'] ?? 'U', 0, 2)) ?>
                            </div>
                            <div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fw-800 text-dark d-block">Reported by @<?= esc($report['name']) ?></span>
                                    <?php if (($report['user_type'] ?? '') == 'admin'): ?>
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-1.5 py-0" style="font-size: 0.6rem; font-weight: 800;">ADMIN</span>
                                    <?php elseif (($report['user_type'] ?? '') == 'provider'): ?>
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-1.5 py-0" style="font-size: 0.6rem; font-weight: 800;">PROVIDER</span>
                                    <?php else: ?>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-1.5 py-0" style="font-size: 0.6rem; font-weight: 800;">CLIENT</span>
                                    <?php endif; ?>
                                </div>
                                <small class="text-muted fw-bold"><?php
                                                                    $diff = time() - strtotime($report['created_at']);
                                                                    if ($diff < 60) echo "Just now";
                                                                    elseif ($diff < 3600) echo round($diff / 60) . "m ago";
                                                                    elseif ($diff < 86400) echo round($diff / 3600) . "h ago";
                                                                    else echo round($diff / 86400) . "d ago";
                                                                    ?></small>
                            </div>
                        </div>
                        <span class="reason-badge <?= strpos(strtolower($report['title'] ?? ''), 'dangerous') !== false ? 'reason-dangerous' : 'reason-misinfo' ?>">
                            <?= strtoupper(esc($report['title'] ?? 'Medical Misinformation')) ?>
                        </span>
                    </div>
                    <div class="report-text">
                        "<?= esc($report['reason'] ?? 'No additional description provided.') ?>"
                    </div>
                </div>
            <?php endforeach; ?>
        </div>


    </div>
</div>

<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        --surface-color: #f8fafc;
        --card-bg: #ffffff;
        --text-main: #1e293b;
        --text-muted: #64748b;
        --border-color: rgba(226, 232, 240, 0.8);
    }

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: var(--surface-color);
        color: var(--text-main);
    }

    .premium-container {
        max-width: 1100px;
        margin: 0 auto;
        padding: 2rem;
    }

    .back-link {
        color: var(--text-muted);
        text-decoration: none;
        font-weight: 700;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 2rem;
    }

    .post-detail-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 32px;
        padding: 40px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.04);
        margin-bottom: 3rem;
        display: flex;
        gap: 32px;
        align-items: start;
    }

    .post-image {
        width: 240px;
        height: 180px;
        border-radius: 12px;
        object-fit: cover;
    }

    .post-title {
        font-weight: 800;
        font-size: 1.5rem;
        color: #1e293b;
        margin-bottom: 4px;
    }

    .post-handle {
        color: #6366f1;
        font-weight: 700;
        font-size: 0.95rem;
        margin-bottom: 24px;
        display: block;
    }

    .post-summary {
        color: #64748b;
        line-height: 1.6;
        font-weight: 600;
        margin-bottom: 32px;
        font-size: 1.05rem;
    }

    .action-btns {
        display: flex;
        gap: 12px;
    }

    .btn-action {
        padding: 10px 24px;
        border-radius: 20px;
        font-weight: 800;
        font-size: 0.85rem;
        border: none;
        transition: all 0.2s ease;
    }

    .btn-dismiss {
        background: #4f46e5;
        color: white;
    }

    .btn-delete {
        background: #f1f5f9;
        color: #1e293b;
    }

    .btn-ban {
        border: 1.5px solid #ef4444;
        color: #ef4444;
        background: transparent;
    }

    .reports-heading {
        font-weight: 800;
        font-size: 1.25rem;
        margin-bottom: 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .report-card {
        background: white;
        border-radius: 24px;
        padding: 24px;
        margin-bottom: 16px;
        border: 1px solid #f1f5f9;
        position: relative;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
    }

    .report-border-blue {
        border-left: 4px solid #4f46e5;
    }

    .report-border-red {
        border-left: 4px solid #ef4444;
    }

    .report-meta {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 16px;
    }

    .reporter-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .reporter-avatar {
        width: 32px;
        height: 32px;
        background: #e0e7ff;
        color: #4f46e5;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 0.75rem;
    }

    .reason-badge {
        font-weight: 800;
        font-size: 0.7rem;
        text-transform: uppercase;
        padding: 4px 12px;
        border-radius: 12px;
        background: #fee2e2;
        color: #ef4444;
    }

    .reason-dangerous {
        background: #fee2e2;
        color: #ef4444;
    }

    .reason-misinfo {
        background: #fee2e2;
        color: #ef4444;
    }

    .report-text {
        color: #64748b;
        font-weight: 600;
        font-size: 0.95rem;
        padding-left: 44px;
        line-height: 1.6;
    }

    .load-more {
        text-align: center;
        margin-top: 32px;
        color: #4f46e5;
        font-weight: 800;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
</style>

=======
        <div class="row g-4">
            <!-- Left Column: Investigated Post Overview -->
            <div class="col-lg-5">
                <div class="pf-form-card p-4 sticky-top" style="top: 2rem;">
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                        <h6 class="fw-bold text-dark mb-0 fs-6">Investigated Post</h6>
                        <span class="pf-badge-status <?= ($post['status'] ?? 'active') == 'active' ? 'active' : 'blocked' ?>">
                            <?= ucfirst($post['status'] ?? 'Active') ?>
                        </span>
                    </div>

                    <!-- Author Details -->
                    <?php
                    $postAuthorAvatar = 'https://ui-avatars.com/api/?name=' . urlencode($post['name'] ?? 'User') . '&background=random';
                    if (!empty($post['profile'])) {
                        if (($post['user_type'] ?? '') == 'provider') {
                            $postAuthorAvatar = 'https://beta.petsfolio.in/public/uploads/providers/' . $post['profile'];
                        } elseif (($post['user_type'] ?? '') == 'admin') {
                            $postAuthorAvatar = base_url('public/uploads/employees/' . $post['profile']);
                        } else {
                            $postAuthorAvatar = 'https://beta.petsfolio.in/public/uploads/users/' . $post['profile'];
                        }
                    }
                    ?>
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <img src="<?= $postAuthorAvatar ?>" class="pf-avatar" alt="<?= esc($post['name']) ?>" onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($post['name'] ?? 'User') ?>&background=random'">
                        <div>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <h6 class="fw-bold text-dark mb-0"><?= esc($post['name']) ?></h6>
                                <?php if (($post['user_type'] ?? '') == 'admin'): ?>
                                    <span class="pf-badge-role admin"><span class="material-symbols-outlined" style="font-size: 11px;">shield_person</span> Admin</span>
                                <?php elseif (($post['user_type'] ?? '') == 'provider'): ?>
                                    <span class="pf-badge-role provider"><span class="material-symbols-outlined" style="font-size: 11px;">badge</span> Provider</span>
                                <?php else: ?>
                                    <span class="pf-badge-role client"><span class="material-symbols-outlined" style="font-size: 11px;">pets</span> Client</span>
                                <?php endif; ?>
                            </div>
                            <small class="text-muted"><?= date('M d, Y • h:i A', strtotime($post['created_at'])) ?></small>
                        </div>
                    </div>

                    <!-- Attached Media if available -->
                    <?php if (!empty($media)): ?>
                        <?php
                            $mUrl = $media['media_url'] ?? '';
                            $isVideo = (!empty($media['media_type']) && $media['media_type'] === 'video')
                                || preg_match('/\.(mp4|mov|webm|ogg|m4v|avi|mkv)$/i', (string) $mUrl);
                        ?>
                        <div class="position-relative rounded-3 overflow-hidden bg-dark mb-3" style="max-height: 220px;">
                            <?php if ($isVideo): ?>
                                <video controls playsinline class="w-100 h-100 object-fit-contain" src="<?= esc($mUrl) ?>" style="max-height: 220px;"></video>
                            <?php else: ?>
                                <img src="<?= esc($mUrl) ?>" class="w-100 h-100 object-fit-cover" style="max-height: 220px;" alt="Post media">
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Content Snippet -->
                    <div class="pf-report-quote-box mb-4">
                        <div class="text-muted small fw-bold mb-1">Post Text:</div>
                        <p class="text-dark small mb-0 lh-base">
                            <?= nl2br(esc($post['post_text'])) ?>
                        </p>
                    </div>

                    <!-- Post Moderation Buttons -->
                    <div class="d-flex flex-column gap-2">
                        <a href="<?= base_url('community/post/' . $post['id']) ?>" class="btn btn-outline-primary rounded-pill fw-bold py-2 d-flex align-items-center justify-content-center gap-2">
                            <span class="material-symbols-outlined" style="font-size: 18px;">open_in_new</span> Open Full Post & Comments
                        </a>

                        <?php if (($post['status'] ?? 'active') != 'blocked'): ?>
                            <a href="<?= base_url('community/blockPost/' . $post['id']) ?>" class="btn btn-outline-warning text-dark rounded-pill fw-bold py-2 d-flex align-items-center justify-content-center gap-2" onclick="return confirm('Block this post from all community feeds?')">
                                <span class="material-symbols-outlined" style="font-size: 18px;">block</span> Block Post
                            </a>
                        <?php else: ?>
                            <a href="<?= base_url('community/unblockPost/' . $post['id']) ?>" class="btn btn-outline-success rounded-pill fw-bold py-2 d-flex align-items-center justify-content-center gap-2">
                                <span class="material-symbols-outlined" style="font-size: 18px;">check_circle</span> Unblock Post
                            </a>
                        <?php endif; ?>

                        <a href="<?= base_url('community/deletePost/' . $post['id']) ?>" class="btn btn-danger rounded-pill fw-bold py-2 d-flex align-items-center justify-content-center gap-2" onclick="return confirm('Permanently remove this post and all its reports?')">
                            <span class="material-symbols-outlined" style="font-size: 18px;">delete</span> Delete Post Permanently
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Column: List of Specific Reports -->
            <div class="col-lg-7">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined text-danger">report</span>
                        <h5 class="fw-bold text-dark mb-0 fs-6">User Reports Submitted</h5>
                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2.5 py-0.5 fw-bold"><?= count($reports) ?></span>
                    </div>

                    <a href="<?= base_url('community/dismissAllReports/' . $post['id']) ?>" class="btn btn-sm btn-outline-secondary rounded-pill fw-bold" onclick="return confirm('Dismiss all reports for this post?')">
                        Dismiss All
                    </a>
                </div>

                <div class="d-flex flex-column gap-3 mb-5">
                    <?php if (!empty($reports)): ?>
                        <?php foreach ($reports as $index => $report): ?>
                            <?php
                            $diff = time() - strtotime($report['created_at']);
                            if ($diff < 60) $rTime = "Just now";
                            elseif ($diff < 3600) $rTime = round($diff / 60) . " mins ago";
                            elseif ($diff < 86400) $rTime = round($diff / 3600) . " hrs ago";
                            else $rTime = round($diff / 86400) . " days ago";
                            ?>
                            <div class="pf-report-card">
                                <div class="d-flex justify-content-between align-items-start gap-2 mb-2 pb-2 border-bottom">
                                    <div class="d-flex align-items-center gap-2.5">
                                        <div class="pf-avatar-sm d-flex align-items-center justify-content-center bg-light text-primary fw-bold" style="width: 32px; height: 32px; font-size: 0.75rem;">
                                            <?= strtoupper(substr($report['name'] ?? 'U', 0, 2)) ?>
                                        </div>
                                        <div>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="fw-bold text-dark small">Reported by @<?= esc($report['name']) ?></span>
                                                <?php if (($report['user_type'] ?? '') == 'admin'): ?>
                                                    <span class="pf-badge-role admin" style="font-size: 0.6rem; padding: 0.15rem 0.5rem;">Admin</span>
                                                <?php elseif (($report['user_type'] ?? '') == 'provider'): ?>
                                                    <span class="pf-badge-role provider" style="font-size: 0.6rem; padding: 0.15rem 0.5rem;">Provider</span>
                                                <?php else: ?>
                                                    <span class="pf-badge-role client" style="font-size: 0.6rem; padding: 0.15rem 0.5rem;">Client</span>
                                                <?php endif; ?>
                                            </div>
                                            <small class="text-muted" style="font-size: 0.7rem;"><?= $rTime ?> • <?= date('M d, Y', strtotime($report['created_at'])) ?></small>
                                        </div>
                                    </div>

                                    <span class="pf-reason-pill hate">
                                        <?= strtoupper(esc($report['title'] ?? ($report['reason'] ?? 'Content Violation'))) ?>
                                    </span>
                                </div>

                                <div class="p-3 bg-light rounded-3 mb-3 border">
                                    <span class="text-muted small fw-bold d-block mb-1">Reporter Statement / Reason:</span>
                                    <p class="text-dark small mb-0 fw-medium">
                                        "<?= esc($report['reason'] ?? 'No additional description provided.') ?>"
                                    </p>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <a href="<?= base_url('community/dismissReport/' . $report['id']) ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 fw-bold d-flex align-items-center gap-1" onclick="return confirm('Dismiss this individual report?')">
                                        <span class="material-symbols-outlined" style="font-size: 15px;">check</span> Dismiss This Report
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="pf-empty-state py-5">
                            <div class="pf-empty-icon text-success" style="background: #ecfdf5;">
                                <span class="material-symbols-outlined fill text-success" style="font-size: 36px;">task_alt</span>
                            </div>
                            <h5 class="fw-bold text-dark mb-1">No Active Reports for this Post</h5>
                            <p class="text-muted small mb-0">All previous reports have been dismissed or resolved.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<?= $this->include('admin/footer') ?>
>>>>>>> origin/main
<?= $this->endSection(); ?>