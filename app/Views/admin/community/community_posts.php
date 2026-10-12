<?= $this->extend('admin/includes/header'); ?>
<?= $this->section('content'); ?>

<link rel="stylesheet" href="<?= base_url('public/assets/css/community.css?v=2.1') ?>">

<main class="main-content community-wrapper">
    <div class="position-relative iq-banner">
        <?= $this->include('admin/nav_bar') ?>
    </div>

    <div class="container-fluid px-4 content-inner pb-0" id="page_layout" style="margin-top: -50px;">
        <!-- Hero Banner -->
        <div class="pf-hero-banner">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4 pf-hero-content">
                <div class="d-flex align-items-center gap-3">
                    <div class="pf-hero-icon-pill">
                        <span class="material-symbols-outlined" style="font-size: 28px;">pets</span>
                    </div>
                    <div>
                        <h1 class="pf-hero-title mb-1">Community Feed & Moderation</h1>
                        <p class="pf-hero-subtitle">Oversee all community conversations, official announcements, questions, and provider updates.</p>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <a href="<?= base_url('community/channels') ?>" class="pf-btn-glass">
                        <span class="material-symbols-outlined">forum</span> Channels Directory
                    </a>
                    <a href="<?= base_url('community/reports') ?>" class="pf-btn-glass">
                        <span class="material-symbols-outlined">shield</span> Reported Items
                    </a>
                    <a href="<?= base_url('community/addPost') ?>" class="pf-btn-hero-cta">
                        <span class="material-symbols-outlined">add_circle</span> Compose New Post
                    </a>
                </div>
            </div>
        </div>

        <!-- Metric Statistics Cards -->
        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-lg-3">
                <div class="pf-stat-card">
                    <div class="pf-stat-header">
                        <div class="pf-stat-icon-wrapper indigo">
                            <span class="material-symbols-outlined">dynamic_feed</span>
                        </div>
                        <span class="pf-stat-badge indigo">Live Feed</span>
                    </div>
                    <div>
                        <div class="pf-stat-label">Total Posts</div>
                        <div class="pf-stat-value"><?= number_format($stats['total_posts'] ?? 0) ?></div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="pf-stat-card">
                    <div class="pf-stat-header">
                        <div class="pf-stat-icon-wrapper emerald">
                            <span class="material-symbols-outlined">pets</span>
                        </div>
                        <span class="pf-stat-badge emerald">Parents</span>
                    </div>
                    <div>
                        <div class="pf-stat-label">Active Pet Parents</div>
                        <div class="pf-stat-value"><?= number_format($stats['active_users'] ?? 0) ?></div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="pf-stat-card">
                    <div class="pf-stat-header">
                        <div class="pf-stat-icon-wrapper sky">
                            <span class="material-symbols-outlined">badge</span>
                        </div>
                        <span class="pf-stat-badge sky">Partners</span>
                    </div>
                    <div>
                        <div class="pf-stat-label">Active Providers</div>
                        <div class="pf-stat-value"><?= number_format($stats['active_providers'] ?? 0) ?></div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="pf-stat-card">
                    <div class="pf-stat-header">
                        <div class="pf-stat-icon-wrapper amber">
                            <span class="material-symbols-outlined">favorite</span>
                        </div>
                        <span class="pf-stat-badge amber">Engagement</span>
                    </div>
                    <div>
                        <div class="pf-stat-label">Interactions</div>
                        <div class="pf-stat-value">
                            <?php
                            $interactions = $stats['total_interactions'] ?? 0;
                            echo ($interactions >= 1000) ? number_format($interactions / 1000, 1) . 'k' : number_format($interactions);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Feed Filter Navigation Tabs -->
        <div class="pf-pill-nav-wrapper">
            <a href="<?= base_url('community/posts?status=admin' . ($request->getVar('channel_id') ? '&channel_id=' . $request->getVar('channel_id') : '')) ?>" 
               class="pf-pill-nav-item <?= $status == 'admin' ? 'active' : '' ?>">
                <span class="material-symbols-outlined" style="font-size: 18px;">shield_person</span>
                <span>Admin Announcements</span>
                <span class="pf-pill-badge"><?= $admin_posts_count ?></span>
            </a>

            <a href="<?= base_url('community/posts?status=user' . ($request->getVar('channel_id') ? '&channel_id=' . $request->getVar('channel_id') : '')) ?>" 
               class="pf-pill-nav-item <?= ($status == 'user' || $status == 'client') ? 'active' : '' ?>">
                <span class="material-symbols-outlined" style="font-size: 18px;">person</span>
                <span>Pet Parent Feed</span>
                <span class="pf-pill-badge"><?= $user_posts_count ?></span>
            </a>

            <a href="<?= base_url('community/posts?status=provider' . ($request->getVar('channel_id') ? '&channel_id=' . $request->getVar('channel_id') : '')) ?>" 
               class="pf-pill-nav-item <?= $status == 'provider' ? 'active' : '' ?>">
                <span class="material-symbols-outlined" style="font-size: 18px;">badge</span>
                <span>Service Providers</span>
                <span class="pf-pill-badge"><?= $provider_posts_count ?></span>
            </a>

            <a href="<?= base_url('community/posts?status=questions' . ($request->getVar('channel_id') ? '&channel_id=' . $request->getVar('channel_id') : '')) ?>" 
               class="pf-pill-nav-item <?= $status == 'questions' ? 'active' : '' ?>">
                <span class="material-symbols-outlined" style="font-size: 18px;">help</span>
                <span>Questions & Advice</span>
                <span class="pf-pill-badge"><?= $questions_count ?></span>
            </a>

            <a href="<?= base_url('community/posts?status=scheduled' . ($request->getVar('channel_id') ? '&channel_id=' . $request->getVar('channel_id') : '')) ?>" 
               class="pf-pill-nav-item <?= $status == 'scheduled' ? 'active' : '' ?>">
                <span class="material-symbols-outlined" style="font-size: 18px;">schedule</span>
                <span>Scheduled Queue</span>
                <span class="pf-pill-badge"><?= $scheduled_posts_count ?></span>
            </a>
        </div>

        <!-- Filter & Search Toolbar -->
        <form action="<?= base_url('community/posts') ?>" method="GET" class="pf-filter-bar">
            <input type="hidden" name="status" value="<?= esc($status) ?>">

            <div class="pf-search-box">
                <span class="material-symbols-outlined">search</span>
                <input type="text" name="search" class="pf-search-input" placeholder="Search posts, topics, keywords or author..." value="<?= esc($request->getVar('search')) ?>">
            </div>

            <div class="d-flex align-items-center gap-2 flex-wrap">
                <select name="channel_id" class="pf-select" onchange="this.form.submit()">
                    <option value="">All Community Channels</option>
                    <?php foreach ($channels as $channel): ?>
                        <option value="<?= $channel['id'] ?>" <?= $request->getVar('channel_id') == $channel['id'] ? 'selected' : '' ?>>
                            #<?= esc($channel['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <button type="submit" class="pf-btn-primary">
                    <span class="material-symbols-outlined" style="font-size: 18px;">tune</span> Apply
                </button>

                <?php if ($request->getVar('search') || $request->getVar('channel_id')): ?>
                    <a href="<?= base_url('community/posts?status=' . esc($status)) ?>" class="pf-btn-secondary">
                        <span class="material-symbols-outlined" style="font-size: 18px;">clear</span> Reset
                    </a>
                <?php endif; ?>
            </div>

            <div class="ms-auto text-muted small fw-bold d-none d-md-block">
                Showing <?= count($posts) ?> active items
            </div>
        </form>

        <div class="row g-4">
            <!-- Left Column: Posts Feed -->
            <div class="col-lg-8">
                <?php if (!empty($posts)): ?>
                    <?php foreach ($posts as $post): ?>
                        <?php
                        $avatarUrl = 'https://ui-avatars.com/api/?name=' . urlencode($post['name'] ?? 'User') . '&background=random';
                        if (!empty($post['profile'])) {
                            if (($post['user_type'] ?? '') == 'provider') {
                                $avatarUrl = 'https://beta.petsfolio.in/public/uploads/providers/' . $post['profile'];
                            } elseif (($post['user_type'] ?? '') == 'admin') {
                                $avatarUrl = base_url('public/uploads/employees/' . $post['profile']);
                            } else {
                                $avatarUrl = 'https://beta.petsfolio.in/public/uploads/users/' . $post['profile'];
                            }
                        }
                        ?>
                        <div class="pf-post-card <?= !empty($post['is_pinned']) ? 'pinned' : '' ?>">
                            <?php if (!empty($post['is_pinned'])): ?>
                                <div class="pf-pinned-banner">
                                    <span class="material-symbols-outlined fill" style="font-size: 14px;">push_pin</span>
                                    <span>Pinned by Community Staff</span>
                                </div>
                            <?php endif; ?>

                            <!-- Author Header -->
                            <div class="d-flex justify-content-between align-items-start gap-3 mb-2">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="pf-avatar-wrapper">
                                        <img src="<?= $avatarUrl ?>" class="pf-avatar" alt="<?= esc($post['name']) ?>" onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($post['name'] ?? 'User') ?>&background=random'">
                                        <span class="pf-avatar-indicator"></span>
                                    </div>
                                    <div>
                                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                            <h5 class="pf-post-author-name"><?= esc($post['name']) ?></h5>

                                            <?php if (($post['user_type'] ?? '') == 'admin'): ?>
                                                <span class="pf-badge-role admin">
                                                    <span class="material-symbols-outlined" style="font-size: 13px;">shield_person</span> Admin
                                                </span>
                                            <?php elseif (($post['user_type'] ?? '') == 'provider'): ?>
                                                <span class="pf-badge-role provider">
                                                    <span class="material-symbols-outlined" style="font-size: 13px;">badge</span> Provider
                                                </span>
                                            <?php else: ?>
                                                <span class="pf-badge-role client">
                                                    <span class="material-symbols-outlined" style="font-size: 13px;">pets</span> Client
                                                </span>
                                            <?php endif; ?>

                                            <?php if (!empty($post['channel_name'])): ?>
                                                <a href="<?= base_url('community/channel/' . $post['channel_id']) ?>" class="pf-badge-channel">
                                                    #<?= esc($post['channel_name']) ?>
                                                </a>
                                            <?php endif; ?>
                                        </div>

                                        <div class="pf-post-meta mt-0.5">
                                            <span class="material-symbols-outlined" style="font-size: 14px;">schedule</span>
                                            <span><?= date('M d, Y • h:i A', strtotime($post['created_at'])) ?></span>
                                            <?php if (!empty($post['status']) && $post['status'] == 'scheduled'): ?>
                                                <span class="badge bg-info-subtle text-info border rounded-pill px-2 py-0.5 ms-2">Scheduled</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Moderator Menu -->
                                <div class="pf-action-btn-group">
                                    <?php if (($post['user_type'] ?? '') == 'admin'): ?>
                                        <a href="<?= base_url('community/editPost/' . $post['id']) ?>" class="pf-btn-icon" title="Edit Content">
                                            <span class="material-symbols-outlined" style="font-size: 18px;">edit</span>
                                        </a>
                                    <?php endif; ?>

                                    <a href="<?= base_url('community/togglePin/' . $post['id']) ?>" class="pf-btn-icon" title="<?= !empty($post['is_pinned']) ? 'Unpin' : 'Pin to Top' ?>">
                                        <span class="material-symbols-outlined <?= !empty($post['is_pinned']) ? 'fill text-warning' : '' ?>" style="font-size: 18px;">push_pin</span>
                                    </a>

                                    <a href="<?= base_url('community/deletePost/' . $post['id']) ?>" onclick="return confirm('Are you sure you want to permanently delete this post?')" class="pf-btn-icon danger" title="Delete Post">
                                        <span class="material-symbols-outlined" style="font-size: 18px;">delete</span>
                                    </a>
                                </div>
                            </div>

                            <!-- Post Body -->
                            <p class="pf-post-text">
                                <?= nl2br(esc($post['post_text'] ?? '')) ?>
                            </p>

                            <!-- Media Display -->
                            <?php if (!empty($post['media'])): ?>
                                <?php
                                $firstMedia = $post['media'][0];
                                $mediaUrl = $firstMedia['media_url'] ?? '';
                                $isVideo = (!empty($firstMedia['media_type']) && $firstMedia['media_type'] === 'video')
                                    || preg_match('/\.(mp4|mov|webm|ogg|m4v|avi|mkv)$/i', (string) $mediaUrl);
                                $isMov = str_ends_with(strtolower($mediaUrl), '.mov');
                                ?>
                                <div class="pf-media-container" style="max-height: 420px; cursor: pointer;" onclick="openMediaModal('<?= esc($mediaUrl, 'js') ?>', '<?= $isVideo ? 'video' : 'image' ?>', '<?= esc($post['name'] ?? 'Media Preview', 'js') ?>')">
                                    <a href="<?= esc($mediaUrl) ?>" download target="_blank" class="pf-media-download-btn" onclick="event.stopPropagation()" title="Download Media">
                                        <span class="material-symbols-outlined" style="font-size: 18px;">download</span>
                                    </a>

                                    <?php if ($isVideo): ?>
                                        <div class="pf-media-single">
                                            <video controls playsinline preload="metadata" src="<?= esc($mediaUrl) ?>" style="max-height: 420px;">
                                                <?php if ($isMov): ?>
                                                    <source src="<?= esc($mediaUrl) ?>" type="video/quicktime">
                                                <?php endif; ?>
                                                <source src="<?= esc($mediaUrl) ?>" type="video/mp4">
                                                <source src="<?= esc($mediaUrl) ?>">
                                            </video>
                                        </div>
                                    <?php else: ?>
                                        <div class="pf-media-single">
                                            <img src="<?= esc($mediaUrl) ?>" alt="Post image" loading="lazy">
                                        </div>
                                    <?php endif; ?>

                                    <?php if (count($post['media']) > 1): ?>
                                        <div class="position-absolute bottom-0 end-0 m-3 px-3 py-1.5 rounded-pill bg-dark text-white fw-bold shadow-lg" style="font-size: 0.75rem; background: rgba(15,23,42,0.85); backdrop-filter: blur(4px);">
                                            +<?= count($post['media']) - 1 ?> more files
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <!-- Interaction Bar -->
                            <div class="pf-interaction-toolbar">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="pf-stat-counter">
                                        <span class="material-symbols-outlined text-danger fill" style="font-size: 16px;">favorite</span>
                                        <span><?= number_format($post['likes_count'] ?? 0) ?></span>
                                    </div>

                                    <div class="pf-stat-counter">
                                        <span class="material-symbols-outlined <?= (isset($post['comment_section']) && $post['comment_section'] == 0) ? 'text-danger' : 'text-primary fill' ?>" style="font-size: 16px;">
                                            <?= (isset($post['comment_section']) && $post['comment_section'] == 0) ? 'comments_disabled' : 'chat_bubble' ?>
                                        </span>
                                        <span><?= number_format($post['comments_count'] ?? 0) ?></span>
                                    </div>
                                </div>

                                <a href="<?= base_url('community/post/' . $post['id']) ?>" class="pf-btn-link-action">
                                    <span>Full View & Moderation</span>
                                    <span class="material-symbols-outlined" style="font-size: 16px;">arrow_forward</span>
                                </a>
                            </div>

                            <!-- Inline Quick Comment Form -->
                            <?php if (!isset($post['comment_section']) || $post['comment_section'] == 1): ?>
                                <div class="pf-quick-comment-box">
                                    <form action="<?= base_url('community/addDirectComment') ?>" method="POST">
                                        <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                                        <div class="pf-comment-input-wrapper">
                                            <input type="text" name="comment" class="pf-comment-input" placeholder="Write an admin response or moderation note..." required>
                                            <button type="submit" class="pf-comment-send-btn" title="Post comment">
                                                <span class="material-symbols-outlined" style="font-size: 16px;">send</span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="pf-empty-state">
                        <div class="pf-empty-icon">
                            <span class="material-symbols-outlined">filter_list_off</span>
                        </div>
                        <h4 class="fw-bold text-dark mb-1">No Posts in this Feed</h4>
                        <p class="text-muted mb-3">No contributions match the current filter or search criteria.</p>
                        <a href="<?= base_url('community/addPost') ?>" class="pf-btn-primary">
                            <span class="material-symbols-outlined" style="font-size: 18px;">add</span> Create Post
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Right Column: Live Activity Feed & Quick Shortcuts -->
            <div class="col-lg-4">
                <div class="sticky-top" style="top: 2rem;">
                    <!-- Quick Channel Directory Shortcut -->
                    <div class="pf-form-card p-4 mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold text-dark mb-0 fs-6">Popular Channels</h6>
                            <a href="<?= base_url('community/channels') ?>" class="small text-primary fw-bold text-decoration-none">View All</a>
                        </div>
                        <div class="d-flex flex-column gap-2">
                            <?php foreach (array_slice($channels, 0, 5) as $c): ?>
                                <a href="<?= base_url('community/posts?channel_id=' . $c['id']) ?>" class="d-flex align-items-center justify-content-between p-2 rounded-3 text-decoration-none hover-bg-light border border-light">
                                    <div class="d-flex align-items-center gap-2.5">
                                        <div class="pf-channel-icon" style="width: 34px; height: 34px; border-radius: 10px;">
                                            <span class="material-symbols-outlined" style="font-size: 18px;">forum</span>
                                        </div>
                                        <span class="fw-bold text-dark small"><?= esc($c['name']) ?></span>
                                    </div>
                                    <span class="badge bg-light text-muted border rounded-pill px-2 py-0.5" style="font-size: 0.7rem;">Select</span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Last Hour Activity Stream -->
                    <div class="pf-form-card p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                            <div class="d-flex align-items-center gap-2">
                                <span class="material-symbols-outlined text-primary" style="font-size: 20px;">timer</span>
                                <h6 class="fw-bold text-dark mb-0 fs-6">Last Hour Activity</h6>
                            </div>
                            <span class="badge bg-success-subtle text-success border rounded-pill px-2 py-0.5 small fw-bold">Live</span>
                        </div>

                        <div class="d-flex flex-column gap-3">
                            <?php if (!empty($lastHourPosts)): ?>
                                <?php foreach ($lastHourPosts as $hourPost): ?>
                                    <?php
                                    $hAvatar = 'https://ui-avatars.com/api/?name=' . urlencode($hourPost['name'] ?? 'User') . '&background=random';
                                    if (!empty($hourPost['profile'])) {
                                        if (($hourPost['user_type'] ?? '') == 'provider') {
                                            $hAvatar = 'https://beta.petsfolio.in/public/uploads/providers/' . $hourPost['profile'];
                                        } elseif (($hourPost['user_type'] ?? '') == 'admin') {
                                            $hAvatar = base_url('public/uploads/employees/' . $hourPost['profile']);
                                        } else {
                                            $hAvatar = 'https://beta.petsfolio.in/public/uploads/users/' . $hourPost['profile'];
                                        }
                                    }
                                    ?>
                                    <a href="<?= base_url('community/post/' . $hourPost['id']) ?>" class="p-2.5 rounded-3 border border-light text-decoration-none d-block transition-all hover-shadow-sm">
                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                            <div class="d-flex align-items-center gap-2">
                                                <img src="<?= $hAvatar ?>" class="rounded-circle" width="24" height="24" style="object-fit: cover;">
                                                <span class="fw-bold text-dark small"><?= esc($hourPost['name']) ?></span>
                                            </div>
                                            <small class="text-muted" style="font-size: 0.7rem;"><?= date('h:i A', strtotime($hourPost['created_at'])) ?></small>
                                        </div>
                                        <p class="text-secondary small mb-0 text-truncate">
                                            <?= esc($hourPost['post_text'] ?? '') ?>
                                        </p>
                                    </a>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-muted small text-center py-3 mb-0">No new contributions in the last hour.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
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