<?= $this->extend('admin/includes/header'); ?>
<?= $this->section('content'); ?>

<<<<<<< HEAD
<!-- Google Font & Material Icons -->
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

<style>
    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: #f3f7fb;
    }

    .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        vertical-align: middle;
    }

    .rounded-4xl {
        border-radius: 2.5rem !important;
    }

    .rounded-3xl {
        border-radius: 1.5rem !important;
    }

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .gradient-card {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    }

    .glass-pill {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .media-preview-container {
        display: flex;
        gap: 8px;
        overflow-x: auto;
        padding-bottom: 8px;
        scrollbar-width: thin;
    }

    .media-preview-item {
        width: 100px;
        height: 100px;
        flex-shrink: 0;
        border-radius: 16px;
        overflow: hidden;
        position: relative;
        background: #eee;
    }

    .media-preview-item img,
    .media-preview-item video {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .media-preview-item .download-btn {
        position: absolute;
        top: 6px;
        right: 6px;
        width: 28px;
        height: 28px;
        background: rgba(0, 0, 0, 0.6);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.2s;
        text-decoration: none;
        z-index: 10;
    }

    .media-preview-item:hover .download-btn {
        opacity: 1;
    }
</style>

<main class="bg-surface " style="background-color: #ffffffff; margin-top:-80px">
    <div class="position-relative iq-banner">
        <!--Nav Start-->
        <?= $this->include('admin/nav_bar') ?>
    </div>
    <div class="container-fluid py-4 px-4 px-xl-5">
        <div class="mb-5">
            <div class="gradient-card rounded-4xl p-4 p-xl-5 text-white position-relative overflow-hidden shadow-lg">
                <div class="position-relative z-1">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-4">
                        <div>
                            <h3 class="fw-black text-white mb-3" style="font-size: 2.5rem;">
                                <?= $channel['handle'] ?? '#' . str_replace(' ', '', $channel['name']) ?>
                            </h3>
                            <p class="fs-5 text-white-50 mb-0"><?= esc(substr($channel['description'], 0, 50)) . "....." ?></p>
                        </div>
                        <button class="btn glass-pill text-white rounded-pill px-4 py-3 d-flex align-items-center gap-2 border-0">
                            <span class="material-symbols-outlined">share</span> Share Channel
                        </button>
                    </div>
                    <div class="mt-4">
                        <div class="glass-pill d-inline-flex align-items-center gap-2 px-4 py-2 rounded-pill fw-bold">
                            <span class="material-symbols-outlined fs-6">info</span>
                            <span>Moderated by Petfolio Team</span>
                        </div>
                    </div>
                </div>
                <div class="position-absolute bottom-0 end-0 opacity-25">
                    <div class="bg-white rounded-circle" style="width: 200px; height: 200px; filter: blur(40px);"></div>
                    <div class="bg-white rounded-circle" style="width: 280px; height: 280px; filter: blur(60px); margin-top: -100px;"></div>
=======
<link rel="stylesheet" href="<?= base_url('public/assets/css/community.css?v=2.1') ?>">

<main class="main-content community-wrapper">
    <div class="position-relative iq-banner">
        <?= $this->include('admin/nav_bar') ?>
    </div>

    <div class="container-fluid px-4 content-inner pb-0" id="page_layout" style="margin-top: -50px;">
        <!-- Hero Banner for Channel -->
        <div class="pf-hero-banner">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4 pf-hero-content">
                <div class="d-flex align-items-center gap-3">
                    <div class="pf-channel-icon" style="width: 64px; height: 64px; border-radius: 18px; border: 2px solid rgba(255,255,255,0.4); background: rgba(255,255,255,0.2);">
                        <?php if (!empty($channel['icon']) && $channel['icon'] !== 'default_icon.png'): ?>
                            <img src="<?= base_url('public/uploads/community/icons/' . $channel['icon']) ?>" alt="<?= esc($channel['name']) ?>">
                        <?php else: ?>
                            <span class="material-symbols-outlined text-white" style="font-size: 32px;">forum</span>
                        <?php endif; ?>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <h1 class="pf-hero-title mb-0"><?= esc($channel['name']) ?></h1>
                            <span class="badge bg-white/20 text-white rounded-pill px-3 py-1 fw-bold fs-7">
                                <?= esc($channel['handle'] ?? '#' . str_replace(' ', '', strtolower($channel['name']))) ?>
                            </span>
                        </div>
                        <p class="pf-hero-subtitle text-white-75 mb-0"><?= esc($channel['description']) ?: 'Official Pet Community Space' ?></p>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <a href="<?= base_url('community/channels') ?>" class="pf-btn-glass">
                        <span class="material-symbols-outlined">arrow_back</span> Channels Directory
                    </a>
                    <a href="<?= base_url('community/addPost') ?>" class="pf-btn-hero-cta">
                        <span class="material-symbols-outlined">add_circle</span> New Channel Post
                    </a>
>>>>>>> origin/main
                </div>
            </div>
        </div>

<<<<<<< HEAD
        <div class="row g-5">
            <div class="col-lg-5">
                <div class="row g-4 mb-4">
                    <div class="col-6">
                        <div class="card border-0 rounded-4xl shadow-sm h-100">
                            <div class="card-body p-4">
                                <span class="material-symbols-outlined text-primary mb-3 fs-1">groups</span>
                                <p class="text-uppercase fw-bold text-muted mb-1 small" style="letter-spacing: 0.1em;">Total Members</p>
                                <h3 class="display-6 fw-black text-dark mb-0"><?= number_format($member_count) ?></h3>
=======
        <div class="row g-4">
            <!-- Left Column: Channel Overview & Details -->
            <div class="col-lg-4">
                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <div class="pf-stat-card">
                            <div class="pf-stat-header">
                                <div class="pf-stat-icon-wrapper indigo">
                                    <span class="material-symbols-outlined">group</span>
                                </div>
                                <span class="pf-stat-badge indigo">Members</span>
                            </div>
                            <div>
                                <div class="pf-stat-label">Total Joined</div>
                                <div class="pf-stat-value"><?= number_format($member_count) ?></div>
>>>>>>> origin/main
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
<<<<<<< HEAD
                        <div class="card border-0 rounded-4xl shadow-sm h-100">
                            <div class="card-body p-4">
                                <span class="material-symbols-outlined text-primary mb-3 fs-1">chat_bubble</span>
                                <p class="text-uppercase fw-bold text-muted mb-1 small" style="letter-spacing: 0.1em;">Total Posts</p>
                                <h3 class="display-6 fw-black text-dark mb-0"><?= count($posts) ?></h3>
=======
                        <div class="pf-stat-card">
                            <div class="pf-stat-header">
                                <div class="pf-stat-icon-wrapper emerald">
                                    <span class="material-symbols-outlined">article</span>
                                </div>
                                <span class="pf-stat-badge emerald">Feed</span>
                            </div>
                            <div>
                                <div class="pf-stat-label">Total Posts</div>
                                <div class="pf-stat-value"><?= number_format(count($posts)) ?></div>
>>>>>>> origin/main
                            </div>
                        </div>
                    </div>
                </div>

<<<<<<< HEAD
                <div class="card border-0 rounded-4xl shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="text-uppercase fw-bold text-muted small mb-3" style="letter-spacing: 0.1em;">About This Channel</h5>
                        <p class="text-muted">
                            Created on <?= date('M d, Y', strtotime($channel['created_at'])) ?><br><br><?= esc(trim($channel['description'])) ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="text-uppercase fw-bold text-muted small mb-0" style="letter-spacing: 0.2em;">Latest Activity</h5>
                    <form action="<?= base_url('community/channel/' . $channel['id']) ?>" method="GET" class="d-flex gap-2 align-items-center">
                        <div class="input-group shadow-sm" style="max-width: 250px;">
                            <span class="input-group-text bg-white border-0"><span class="material-symbols-outlined text-muted" style="font-size: 1.2rem;">search</span></span>
                            <input type="text" name="search" class="form-control border-0 py-2 small" placeholder="Search in channel..." value="<?= esc($request->getVar('search')) ?>" style="font-size: 0.85rem;">
                        </div>
                    </form>
                </div>

                <div class="d-flex flex-column gap-4">
                    <?php foreach ($posts as $post): ?>
                        <div class="card border-0 rounded-4xl shadow-sm">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <?php
                                    $authorAvatar = 'https://ui-avatars.com/api/?name=' . urlencode($post['name'] ?? 'User') . '&background=random';
                                    if (!empty($post['profile'])) {
                                        if (($post['user_type'] ?? '') == 'provider') {
                                            $authorAvatar = 'https://beta.petsfolio.in/public/uploads/providers/' . $post['profile'];
                                        } elseif (($post['user_type'] ?? '') == 'admin') {
                                            $authorAvatar = base_url('public/uploads/employees/' . $post['profile']);
                                        } else {
                                            $authorAvatar = 'https://beta.petsfolio.in/public/uploads/users/' . $post['profile'];
                                        }
                                    }
                                    ?>
                                    <div class="d-flex gap-3 align-items-center">
                                        <img src="<?= $authorAvatar ?>" class="rounded-circle shadow-sm" width="56" height="56" style="object-fit: cover;" onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($post['name'] ?? 'User') ?>&background=random'">
                                        <div>
                                            <div class="d-flex align-items-center gap-2">
                                                <h6 class="fw-bold mb-0"><?= esc($post['name']) ?></h6>
                                                <?php if (($post['user_type'] ?? '') == 'admin'): ?>
                                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2 py-0.5" style="font-size: 0.6rem; font-weight: 800;">ADMIN</span>
                                                <?php elseif (($post['user_type'] ?? '') == 'provider'): ?>
                                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 py-0.5" style="font-size: 0.6rem; font-weight: 800;">PROVIDER</span>
                                                <?php else: ?>
                                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-0.5" style="font-size: 0.6rem; font-weight: 800;">CLIENT</span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="d-flex align-items-center gap-2 small text-muted mt-1">
                                                <span class="material-symbols-outlined fs-6">schedule</span>
                                                <?= date('M d, Y', strtotime($post['created_at'])) ?>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="<?= base_url('community/post/' . $post['id']) ?>" class="btn btn-light border-0 rounded-circle p-2">
                                        <span class="material-symbols-outlined">visibility</span>
                                    </a>
                                </div>

                                <a href="<?= base_url('community/post/' . $post['id']) ?>" class="text-decoration-none">
                                    <h3 class="fw-black text-dark fs-3 mb-3"><?= esc($post['subject'] ?? 'Post Discussion') ?></h3>
                                    <p class="text-muted line-clamp-3"><?= esc($post['post_text']) ?></p>
                                </a>

                                <?php if (!empty($post['media'])): ?>
                                    <div class="media-preview-container mt-3 mb-1">
                                        <?php foreach ($post['media'] as $m): ?>
                                            <div class="media-preview-item">
                                                <a href="<?= $m['media_url'] ?>" download target="_blank" class="download-btn">
                                                    <span class="material-symbols-outlined" style="font-size: 16px;">download</span>
                                                </a>
                                                <?php if ($m['media_type'] === 'video'): ?>
                                                    <video>
                                                        <source src="<?= $m['media_url'] ?>" type="video/mp4">
                                                    </video>
                                                <?php else: ?>
                                                    <img src="<?= $m['media_url'] ?>" alt="Media">
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                                <div class="d-flex justify-content-between align-items-center mt-4 pt-2 border-top">
                                    <div class="d-flex gap-4">
                                        <button class="btn btn-link text-decoration-none p-0 d-flex align-items-center gap-1 text-dark">
                                            <span class="material-symbols-outlined text-primary">favorite</span>
                                            <span class="fw-bold"><?= $post['likes_count'] ?></span>
                                        </button>
                                        <button class="btn btn-link text-decoration-none p-0 d-flex align-items-center gap-1 text-dark">
                                            <span class="material-symbols-outlined">chat_bubble</span>
                                            <span class="fw-bold"><?= $post['comments_count'] ?> Comments</span>
                                        </button>
                                    </div>
                                    <?php if ($post['likes_count'] > 0): ?>
                                        <div class="d-flex gap-1">
                                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Total Likes">
                                                <span class="small fw-bold text-muted">+<?= $post['likes_count'] ?></span>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <?php if (empty($posts)): ?>
                        <div class="card border-0 rounded-4xl shadow-sm text-center py-5">
                            <div class="card-body">
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px;">
                                    <span class="material-symbols-outlined fs-1 text-muted">inventory_2</span>
                                </div>
                                <h5 class="fw-bold">No Activity Yet</h5>
                                <p class="text-muted">Be the first to share something with this community!</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
=======
                <!-- Channel Metadata Card -->
                <div class="pf-form-card p-4 mb-4">
                    <div class="d-flex align-items-center justify-content-between mb-3 pb-3 border-bottom">
                        <h6 class="fw-bold text-dark mb-0 fs-6">Channel Overview</h6>
                        <?php if (!empty($channel['is_private']) && $channel['is_private'] == 1): ?>
                            <span class="badge bg-secondary-subtle text-secondary border rounded-pill px-2.5 py-1 fw-bold" style="font-size: 0.72rem;">
                                <span class="material-symbols-outlined align-middle" style="font-size: 14px;">lock</span> Private
                            </span>
                        <?php else: ?>
                            <span class="badge bg-success-subtle text-success border rounded-pill px-2.5 py-1 fw-bold" style="font-size: 0.72rem;">
                                <span class="material-symbols-outlined align-middle" style="font-size: 14px;">public</span> Public
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label class="pf-form-label mb-1">About & Purpose</label>
                        <p class="text-secondary small mb-0 lh-base">
                            <?= esc($channel['description']) ?: 'No extended description provided.' ?>
                        </p>
                    </div>

                    <div class="d-flex justify-content-between align-items-center py-2 border-top">
                        <span class="text-muted small fw-bold">Created Date</span>
                        <span class="text-dark small fw-bold"><?= date('M d, Y', strtotime($channel['created_at'])) ?></span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center py-2 border-top">
                        <span class="text-muted small fw-bold">Channel Status</span>
                        <span class="pf-badge-status <?= ($channel['status'] ?? 1) == 1 ? 'active' : 'inactive' ?>">
                            <?= ($channel['status'] ?? 1) == 1 ? 'Active' : 'Inactive' ?>
                        </span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center py-2 border-top">
                        <span class="text-muted small fw-bold">Moderation Team</span>
                        <span class="text-primary small fw-bold d-flex align-items-center gap-1">
                            <span class="material-symbols-outlined" style="font-size: 16px;">verified_user</span> Petsfolio Staff
                        </span>
                    </div>

                    <div class="mt-4 pt-2 border-top">
                        <a href="<?= base_url('community/posts?channel_id=' . $channel['id']) ?>" class="btn btn-outline-primary rounded-pill w-100 fw-bold py-2 d-flex align-items-center justify-content-center gap-2">
                            <span class="material-symbols-outlined" style="font-size: 18px;">tune</span> Filter Global Feed by this Channel
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Column: Channel Feed -->
            <div class="col-lg-8">
                <!-- Feed Filter & Search -->
                <div class="pf-filter-bar mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined text-primary">dynamic_feed</span>
                        <h5 class="fw-bold text-dark mb-0 fs-6">Channel Feed</h5>
                        <span class="pf-pill-badge ms-1"><?= count($posts) ?></span>
                    </div>

                    <form action="<?= base_url('community/channel/' . $channel['id']) ?>" method="GET" class="ms-auto d-flex align-items-center gap-2">
                        <div class="pf-search-box" style="min-width: 220px;">
                            <span class="material-symbols-outlined">search</span>
                            <input type="text" name="search" class="pf-search-input" placeholder="Search in this channel..." value="<?= esc($request->getVar('search')) ?>">
                        </div>
                        <?php if ($request->getVar('search')): ?>
                            <a href="<?= base_url('community/channel/' . $channel['id']) ?>" class="pf-btn-secondary" title="Clear search">
                                <span class="material-symbols-outlined" style="font-size: 18px;">clear</span>
                            </a>
                        <?php endif; ?>
                    </form>
                </div>

                <!-- Posts List -->
                <?php if (!empty($posts)): ?>
                    <?php foreach ($posts as $post): ?>
                        <?php
                        $authorAvatar = 'https://ui-avatars.com/api/?name=' . urlencode($post['name'] ?? 'User') . '&background=random';
                        if (!empty($post['profile'])) {
                            if (($post['user_type'] ?? '') == 'provider') {
                                $authorAvatar = 'https://beta.petsfolio.in/public/uploads/providers/' . $post['profile'];
                            } elseif (($post['user_type'] ?? '') == 'admin') {
                                $authorAvatar = base_url('public/uploads/employees/' . $post['profile']);
                            } else {
                                $authorAvatar = 'https://beta.petsfolio.in/public/uploads/users/' . $post['profile'];
                            }
                        }
                        ?>
                        <div class="pf-post-card mb-3">
                            <div class="d-flex justify-content-between align-items-start gap-3 mb-2">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="pf-avatar-wrapper">
                                        <img src="<?= $authorAvatar ?>" class="pf-avatar" alt="<?= esc($post['name']) ?>" onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($post['name'] ?? 'User') ?>&background=random'">
                                        <span class="pf-avatar-indicator"></span>
                                    </div>
                                    <div>
                                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                            <h6 class="pf-post-author-name"><?= esc($post['name']) ?></h6>
                                            <?php if (($post['user_type'] ?? '') == 'admin'): ?>
                                                <span class="pf-badge-role admin"><span class="material-symbols-outlined" style="font-size: 13px;">shield_person</span> Admin</span>
                                            <?php elseif (($post['user_type'] ?? '') == 'provider'): ?>
                                                <span class="pf-badge-role provider"><span class="material-symbols-outlined" style="font-size: 13px;">badge</span> Provider</span>
                                            <?php else: ?>
                                                <span class="pf-badge-role client"><span class="material-symbols-outlined" style="font-size: 13px;">pets</span> Client</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="pf-post-meta">
                                            <span class="material-symbols-outlined" style="font-size: 14px;">schedule</span>
                                            <?= date('M d, Y • h:i A', strtotime($post['created_at'])) ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="pf-action-btn-group">
                                    <a href="<?= base_url('community/post/' . $post['id']) ?>" class="pf-btn-link-action" title="Full Post View">
                                        <span>Inspect</span>
                                        <span class="material-symbols-outlined" style="font-size: 16px;">arrow_forward</span>
                                    </a>
                                </div>
                            </div>

                            <p class="pf-post-text">
                                <?= nl2br(esc($post['post_text'])) ?>
                            </p>

                            <!-- Media Gallery Preview -->
                            <?php if (!empty($post['media'])): ?>
                                <div class="d-flex gap-2 overflow-auto pb-2 mb-2">
                                    <?php foreach ($post['media'] as $m): ?>
                                        <?php
                                        $mUrl = $m['media_url'] ?? '';
                                        $isMVideo = (!empty($m['media_type']) && $m['media_type'] === 'video')
                                            || preg_match('/\.(mp4|mov|webm|ogg|m4v|avi|mkv)$/i', (string) $mUrl);
                                        ?>
                                        <div class="position-relative rounded-3 overflow-hidden bg-dark flex-shrink-0" style="width: 120px; height: 120px; cursor: pointer;" onclick="openMediaModal('<?= esc($mUrl, 'js') ?>', '<?= $isMVideo ? 'video' : 'image' ?>', '<?= esc($post['name'] ?? 'Media Preview', 'js') ?>')">
                                            <?php if ($isMVideo): ?>
                                                <video class="w-100 h-100 object-fit-cover" preload="metadata" muted playsinline src="<?= esc($mUrl) ?>"></video>
                                                <div class="position-absolute top-50 start-50 translate-middle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: rgba(0,0,0,0.6); border-radius: 50%;">
                                                    <span class="material-symbols-outlined text-white" style="font-size: 18px;">play_arrow</span>
                                                </div>
                                            <?php else: ?>
                                                <img src="<?= esc($mUrl) ?>" class="w-100 h-100 object-fit-cover" alt="Media thumbnail">
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <!-- Interaction Stats -->
                            <div class="pf-interaction-toolbar">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="pf-stat-counter">
                                        <span class="material-symbols-outlined text-danger" style="font-size: 16px;">favorite</span>
                                        <?= number_format($post['likes_count'] ?? 0) ?>
                                    </span>
                                    <span class="pf-stat-counter">
                                        <span class="material-symbols-outlined text-primary" style="font-size: 16px;">chat_bubble</span>
                                        <?= number_format($post['comments_count'] ?? 0) ?>
                                    </span>
                                </div>
                                <a href="<?= base_url('community/post/' . $post['id']) ?>" class="small text-muted fw-bold text-decoration-none hover-text-primary">
                                    View full discussion thread →
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="pf-empty-state">
                        <div class="pf-empty-icon">
                            <span class="material-symbols-outlined">forum</span>
                        </div>
                        <h5 class="fw-bold text-dark mb-1">No Posts in this Channel Yet</h5>
                        <p class="text-muted mb-3">Be the first to create an engaging announcement or story in #<?= esc($channel['name']) ?>.</p>
                        <a href="<?= base_url('community/addPost') ?>" class="pf-btn-primary">
                            <span class="material-symbols-outlined" style="font-size: 18px;">add</span> Post in this Channel
                        </a>
                    </div>
                <?php endif; ?>
>>>>>>> origin/main
            </div>
        </div>
    </div>
</main>

<<<<<<< HEAD
<div class="position-fixed bottom-0 start-0 end-0 text-center p-4" style="pointer-events: none;">
    <button class="btn btn-primary rounded-pill px-5 py-3 shadow-lg d-inline-flex align-items-center gap-2 fw-bold" style="background: linear-gradient(135deg, #6366f1, #8b5cf6); border: none; pointer-events: auto;" onclick="window.location.href='<?= base_url('community/addPost') ?>'">
        <span class="material-symbols-outlined">add</span> Create New Post
    </button>
</div>

=======
<!-- Media Lightbox Modal -->
<div class="modal fade" id="communityMediaModal" tabindex="-1" aria-labelledby="communityMediaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content pf-modal-content" style="background: #0f172a;">
            <div class="modal-header border-0 pb-0 pt-3 px-4 d-flex justify-content-between align-items-center">
                <h6 class="modal-title text-white fw-bold d-flex align-items-center gap-2 mb-0" id="communityMediaModalLabel">
                    <span class="material-symbols-outlined text-primary fs-5">play_circle</span>
                    <span id="communityMediaModalTitle">Media Preview</span>
                </h6>
                <div class="d-flex align-items-center gap-2">
                    <a id="communityMediaModalDownload" href="#" download target="_blank" class="btn btn-dark btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 34px; height: 34px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.15);" title="Download">
                        <span class="material-symbols-outlined text-white fs-6">download</span>
                    </a>
                    <button type="button" class="btn btn-dark btn-sm rounded-circle d-flex align-items-center justify-content-center" data-bs-dismiss="modal" aria-label="Close" style="width: 34px; height: 34px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.15);">
                        <span class="material-symbols-outlined text-white fs-6">close</span>
                    </button>
                </div>
            </div>
            <div class="modal-body p-3 text-center d-flex align-items-center justify-content-center" style="min-height: 380px;">
                <div id="communityMediaContainer" class="w-100 d-flex align-items-center justify-content-center"></div>
            </div>
        </div>
    </div>
</div>

<?= $this->include('admin/footer') ?>

<script>
function openMediaModal(url, type, title) {
    var modalEl = document.getElementById('communityMediaModal');
    if (!modalEl) return;
    
    var container = document.getElementById('communityMediaContainer');
    var titleEl = document.getElementById('communityMediaModalTitle');
    var downloadBtn = document.getElementById('communityMediaModalDownload');
    
    if (titleEl) titleEl.textContent = title || 'Media Preview';
    if (downloadBtn) downloadBtn.href = url;
    
    if (type === 'video') {
        var isMov = /\.mov$/i.test(url);
        var sourceHtml = isMov ? '<source src="' + url + '" type="video/quicktime">' : '';
        sourceHtml += '<source src="' + url + '" type="video/mp4"><source src="' + url + '">';
        container.innerHTML = '<video id="activeModalVideo" controls autoplay playsinline style="max-width: 100%; max-height: 75vh; border-radius: 12px; background: #000; box-shadow: 0 10px 30px rgba(0,0,0,0.5);" src="' + url + '">' + sourceHtml + '</video>';
    } else {
        container.innerHTML = '<img src="' + url + '" style="max-width: 100%; max-height: 75vh; border-radius: 12px; object-fit: contain;" alt="Preview">';
    }
    
    var modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
    modal.show();
}

document.addEventListener('DOMContentLoaded', function() {
    var modalEl = document.getElementById('communityMediaModal');
    if (modalEl) {
        modalEl.addEventListener('hidden.bs.modal', function() {
            var video = document.getElementById('activeModalVideo');
            if (video) {
                video.pause();
                video.removeAttribute('src');
                video.load();
            }
            var container = document.getElementById('communityMediaContainer');
            if (container) container.innerHTML = '';
        });
    }
});
</script>

>>>>>>> origin/main
<?= $this->endSection(); ?>