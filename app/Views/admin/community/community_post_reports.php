<?= $this->extend('admin/includes/header'); ?>
<?= $this->section('content'); ?>

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
                <span class="post-handle">@<?= esc($post['name']) ?></span>

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
                </div>
            </div>
        </div>

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
                                <?= strtoupper(substr($report['name'], 0, 2)) ?>
                            </div>
                            <div>
                                <span class="fw-800 text-dark d-block">Reported by @<?= esc($report['name']) ?></span>
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

<?= $this->endSection(); ?>