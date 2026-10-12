<?= $this->extend('admin/includes/header'); ?>
<?= $this->section('content'); ?>

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
                </div>
            </div>
        </div>

        <div class="row g-5">
            <div class="col-lg-5">
                <div class="row g-4 mb-4">
                    <div class="col-6">
                        <div class="card border-0 rounded-4xl shadow-sm h-100">
                            <div class="card-body p-4">
                                <span class="material-symbols-outlined text-primary mb-3 fs-1">groups</span>
                                <p class="text-uppercase fw-bold text-muted mb-1 small" style="letter-spacing: 0.1em;">Total Members</p>
                                <h3 class="display-6 fw-black text-dark mb-0"><?= number_format($member_count) ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card border-0 rounded-4xl shadow-sm h-100">
                            <div class="card-body p-4">
                                <span class="material-symbols-outlined text-primary mb-3 fs-1">chat_bubble</span>
                                <p class="text-uppercase fw-bold text-muted mb-1 small" style="letter-spacing: 0.1em;">Total Posts</p>
                                <h3 class="display-6 fw-black text-dark mb-0"><?= count($posts) ?></h3>
                            </div>
                        </div>
                    </div>
                </div>

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
            </div>
        </div>
    </div>
</main>

<div class="position-fixed bottom-0 start-0 end-0 text-center p-4" style="pointer-events: none;">
    <button class="btn btn-primary rounded-pill px-5 py-3 shadow-lg d-inline-flex align-items-center gap-2 fw-bold" style="background: linear-gradient(135deg, #6366f1, #8b5cf6); border: none; pointer-events: auto;" onclick="window.location.href='<?= base_url('community/addPost') ?>'">
        <span class="material-symbols-outlined">add</span> Create New Post
    </button>
</div>

<?= $this->endSection(); ?>