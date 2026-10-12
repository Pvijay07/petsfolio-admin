<?= $this->extend('admin/includes/header'); ?>
<?= $this->section('content'); ?>

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
                </div>
            </div>
        </div>

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
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
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
                            </div>
                        </div>
                    </div>
                </div>

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
            </div>
        </div>
    </div>
</main>

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

<?= $this->endSection(); ?>