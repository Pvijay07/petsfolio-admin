<?= $this->extend('admin/includes/header'); ?>
<?= $this->section('content'); ?>

<<<<<<< HEAD
=======
<link rel="stylesheet" href="<?= base_url('public/assets/css/community.css?v=2.1') ?>">

>>>>>>> origin/main
<script>
  function reportComment(commentId) {
    const reason = prompt("Please provide a reason for reporting this comment:", "Inappropriate content");
    if (reason !== null && reason.trim() !== "") {
      const url = '<?= base_url('community/reportComment/') ?>/' + commentId + '?reason=' + encodeURIComponent(reason);
      window.location.href = url;
    }
  }
</script>

<<<<<<< HEAD
<!-- Google Font & Material Icons -->
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">



<div class="">
  <div class="position-relative iq-banner">
    <?php echo $this->include('admin/nav_bar') ?>
  </div>
  <div class="container-fluid py-4 px-4" style="margin-top:-20px;">
    <div class="banner-card rounded-4xl p-3 px-5 mb-4 text-white shadow-lg position-relative overflow-hidden" style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); margin-top:-60px ;">
      <!-- Decorative Blobs -->
      <div class="position-absolute" style="width: 300px; height: 300px; background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%); border-radius: 50%; top: -150px; right: -100px;"></div>
      <div class="position-absolute" style="width: 200px; height: 200px; background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, rgba(255,255,255,0) 70%); border-radius: 50%; bottom: -100px; left: -50px;"></div>

      <div class="position-relative">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4">
          <div>
            <!-- <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
              <li class="breadcrumb-item"><a href="<?= base_url('community/posts') ?>" class="text-decoration-none text-white-50 fw-medium">Community</a></li>
              <li class="breadcrumb-item active text-white fw-bold" aria-current="page">Post Details</li>
            </ol>
          </nav> -->
            <h1 class="display-6 fw-800 tracking-tight text-white mb-0">Review Community Content</h1>
            <p class="text-white-50 mt-2 mb-0 fw-medium">Deep dive into post specifics and engagement</p>
          </div>

          <div class="d-flex gap-3">
            <a href="<?= base_url('community/posts') ?>" class="btn glass-pill border-0 text-white px-4 py-3 rounded-pill fw-bold d-flex align-items-center gap-2">
              <span class="material-symbols-outlined">arrow_back</span> Back to Feed
            </a>
          </div>
=======
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
            <span class="material-symbols-outlined" style="font-size: 28px;">rate_review</span>
          </div>
          <div>
            <h1 class="pf-hero-title mb-1">Post Details & Moderation</h1>
            <p class="pf-hero-subtitle">Deep dive into post performance, engagement metrics, media assets, and discussion thread.</p>
          </div>
        </div>

        <div class="d-flex align-items-center gap-3 flex-wrap">
          <a href="<?= base_url('community/posts') ?>" class="pf-btn-glass">
            <span class="material-symbols-outlined">arrow_back</span> Back to Feed
          </a>
          <?php if (($post['user_type'] ?? '') == 'admin'): ?>
            <a href="<?= base_url('community/editPost/' . $post['id']) ?>" class="pf-btn-hero-cta">
              <span class="material-symbols-outlined">edit</span> Edit Content
            </a>
          <?php endif; ?>
>>>>>>> origin/main
        </div>
      </div>
    </div>

    <div class="row g-4">
<<<<<<< HEAD
      <div class="col-lg-8">
        <div class="glass-card mb-4 p-4 p-md-5">
          <div class="d-flex align-items-center justify-content-between mb-4">
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
            <div class="author-badge">
              <img src="<?= $postAuthorAvatar ?>"
                class="author-avatar" alt="<?= esc($post['name']) ?>" onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($post['name'] ?? 'User') ?>&background=random'">
              <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                  <span class="fw-800 text-dark d-block leading-none"><?= esc($post['name']) ?></span>
                  <?php if (($post['user_type'] ?? '') == 'admin' || ($post['user_type'] ?? '') == '3'): ?>
                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2 py-0.5 small" style="font-size: 0.65rem; font-weight: 800;">ADMIN</span>
                  <?php elseif (($post['user_type'] ?? '') == 'provider' || ($post['user_type'] ?? '') == '2'): ?>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 py-0.5 small" style="font-size: 0.65rem; font-weight: 800;">PROVIDER</span>
                  <?php else: ?>
                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-0.5 small" style="font-size: 0.65rem; font-weight: 800;">CLIENT</span>
                  <?php endif; ?>
                </div>
                <small class="text-muted fw-bold">ID: #<?= strtoupper(substr($post['user_type'] ?? 'U', 0, 1)) ?>-<?= $post['user_id'] ?></small>
              </div>
            </div>
            <div class="d-flex align-items-center gap-2">
              <span class="channel-tag"><?= esc($post['channel_name'] ?? 'General') ?></span>
              <span class="status-active"><?= strtoupper(esc($post['status'])) ?></span>
            </div>
          </div>
          <div class="post-body">

            <div class="post-content-area mb-5">
              <?= nl2br(esc($post['post_text'])) ?>
            </div>
            <?php if (!empty($media)): ?>
              <div class="media-gallery mb-5">
                <?php foreach ($media as $item): ?>
                  <div class="media-item">
                    <a href="<?= $item['media_url'] ?>" download target="_blank" class="download-overlay btn btn-dark btn-sm rounded-circle d-flex align-items-center justify-content-center p-0" style="width: 36px; height: 36px; background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(4px); border: 1px solid rgba(255,255,255,0.2);" title="Download Media">
                      <span class="material-symbols-outlined text-white fs-6">download</span>
                    </a>
                    <?php if ($item['media_type'] === 'video'): ?>
                      <video controls style="width: 100%; height: 100%; object-fit: cover;">
                        <source src="<?= $item['media_url'] ?>" type="video/mp4">
                      </video>
                    <?php else: ?>
                      <img src="<?= $item['media_url'] ?>" alt="Post Media" loading="lazy" style="width: 100%; height: 100%; object-fit: cover;">
=======
      <!-- Main Content Column -->
      <div class="col-lg-8">
        <!-- Post Card -->
        <div class="pf-post-card p-4 p-md-5 mb-4">
          <!-- Author Header -->
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
          <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start gap-3 mb-4 pb-3 border-bottom">
            <div class="d-flex align-items-center gap-3">
              <div class="pf-avatar-wrapper">
                <img src="<?= $postAuthorAvatar ?>" class="pf-avatar" style="width: 56px; height: 56px;" alt="<?= esc($post['name']) ?>" onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($post['name'] ?? 'User') ?>&background=random'">
                <span class="pf-avatar-indicator"></span>
              </div>
              <div>
                <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                  <h5 class="pf-post-author-name fs-5"><?= esc($post['name']) ?></h5>
                  <?php if (($post['user_type'] ?? '') == 'admin' || ($post['user_type'] ?? '') == '3'): ?>
                    <span class="pf-badge-role admin"><span class="material-symbols-outlined" style="font-size: 13px;">shield_person</span> Admin</span>
                  <?php elseif (($post['user_type'] ?? '') == 'provider' || ($post['user_type'] ?? '') == '2'): ?>
                    <span class="pf-badge-role provider"><span class="material-symbols-outlined" style="font-size: 13px;">badge</span> Provider</span>
                  <?php else: ?>
                    <span class="pf-badge-role client"><span class="material-symbols-outlined" style="font-size: 13px;">pets</span> Client</span>
                  <?php endif; ?>
                </div>
                <div class="pf-post-meta">
                  <span class="material-symbols-outlined" style="font-size: 14px;">badge</span>
                  <span>ID: #<?= strtoupper(substr($post['user_type'] ?? 'U', 0, 1)) ?>-<?= $post['user_id'] ?></span>
                  <span class="mx-1">•</span>
                  <span class="material-symbols-outlined" style="font-size: 14px;">schedule</span>
                  <span><?= date('M d, Y • h:i A', strtotime($post['created_at'])) ?></span>
                </div>
              </div>
            </div>

            <div class="d-flex align-items-center gap-2">
              <?php if (!empty($post['channel_name'])): ?>
                <a href="<?= base_url('community/channel/' . $post['channel_id']) ?>" class="pf-badge-channel">
                  #<?= esc($post['channel_name']) ?>
                </a>
              <?php endif; ?>
              <span class="pf-badge-status <?= strtolower($post['status'] ?? 'active') == 'active' ? 'active' : 'blocked' ?>">
                <?= strtoupper(esc($post['status'])) ?>
              </span>
            </div>
          </div>

          <!-- Post Content -->
          <div class="pf-post-text fs-5 mb-4" style="line-height: 1.7; color: #1e293b;">
            <?= nl2br(esc($post['post_text'])) ?>
          </div>

          <!-- Media Assets Gallery -->
          <?php if (!empty($media)): ?>
            <div class="mb-4">
              <label class="pf-form-label mb-2">Attached Visual Assets (<?= count($media) ?>)</label>
              <div class="pf-media-grid">
                <?php foreach ($media as $item): ?>
                  <?php
                    $mediaUrl = $item['media_url'] ?? '';
                    $isVideo = (!empty($item['media_type']) && $item['media_type'] === 'video')
                        || preg_match('/\.(mp4|mov|webm|ogg|m4v|avi|mkv)$/i', (string) $mediaUrl);
                    $isMov = str_ends_with(strtolower($mediaUrl), '.mov');
                  ?>
                  <div class="pf-media-grid-item" style="cursor: pointer;" onclick="openMediaModal('<?= esc($mediaUrl, 'js') ?>', '<?= $isVideo ? 'video' : 'image' ?>', '<?= esc($post['name'] ?? 'Media Preview', 'js') ?>')">
                    <a href="<?= esc($mediaUrl) ?>" download target="_blank" class="pf-media-download-btn" onclick="event.stopPropagation()" title="Download Media">
                      <span class="material-symbols-outlined" style="font-size: 16px;">download</span>
                    </a>

                    <?php if ($isVideo): ?>
                      <video class="w-100 h-100 object-fit-cover" preload="metadata" muted playsinline src="<?= esc($mediaUrl) ?>"></video>
                      <div class="position-absolute top-50 start-50 translate-middle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background: rgba(0,0,0,0.6); border-radius: 50%; backdrop-filter: blur(4px);">
                        <span class="material-symbols-outlined text-white fs-3">play_arrow</span>
                      </div>
                    <?php else: ?>
                      <img src="<?= esc($mediaUrl) ?>" alt="Post Media" loading="lazy">
>>>>>>> origin/main
                    <?php endif; ?>
                  </div>
                <?php endforeach; ?>
              </div>
<<<<<<< HEAD
            <?php endif; ?>
            <div class="d-flex align-items-center gap-3 pt-4 border-top border-light">
              <div class="interaction-pill">
                <span class="material-symbols-outlined fill text-danger" style="font-size: 1.1rem;">favorite</span>
                <?= number_format($post['likes_count'] ?? 0) ?> <span class="d-none d-sm-inline">Likes</span>
              </div>
              <div class="interaction-pill">
                <span class="material-symbols-outlined fill text-muted" style="font-size: 1.1rem;">thumb_down</span>
                <?= number_format($post['dislikes_count'] ?? 0) ?> <span class="d-none d-sm-inline">Dislikes</span>
              </div>
              <div class="interaction-pill">
                <span class="material-symbols-outlined fill text-primary" style="font-size: 1.1rem;">chat_bubble</span>
                <?= number_format($comments_total_count ?? 0) ?> <span class="d-none d-sm-inline">Comments</span>
              </div>
              <div class="interaction-pill ms-auto">
                <span class="material-symbols-outlined">schedule</span>
                <?= date('M d, Y • h:i A', strtotime($post['created_at'])) ?>
              </div>
=======
            </div>
          <?php endif; ?>

          <!-- Interaction Pill Row -->
          <div class="pf-interaction-toolbar pt-3 mt-3 border-top">
            <div class="d-flex align-items-center gap-2 flex-wrap">
              <span class="pf-stat-counter active-like">
                <span class="material-symbols-outlined fill text-danger" style="font-size: 16px;">favorite</span>
                <?= number_format($post['likes_count'] ?? 0) ?> Likes
              </span>
              <span class="pf-stat-counter">
                <span class="material-symbols-outlined fill text-muted" style="font-size: 16px;">thumb_down</span>
                <?= number_format($post['dislikes_count'] ?? 0) ?> Dislikes
              </span>
              <span class="pf-stat-counter">
                <span class="material-symbols-outlined fill text-primary" style="font-size: 16px;">chat_bubble</span>
                <?= number_format($comments_total_count ?? 0) ?> Comments
              </span>
            </div>

            <div class="text-muted small fw-bold">
              Updated: <?= $post['updated_at'] ? date('M d, Y', strtotime($post['updated_at'])) : 'Never' ?>
>>>>>>> origin/main
            </div>
          </div>
        </div>

<<<<<<< HEAD
        <!-- Comments Section -->
        <div class="glass-card overflow-hidden">
          <div class="p-4 border-bottom d-flex align-items-center justify-content-between bg-light/30">
            <h5 class="fw-800 mb-0">Discussion (<?= $comments_total_count ?? 0 ?>)</h5>
            <div class="d-flex gap-2">
              <button class="btn btn-sm fw-bold border-0 text-primary" style="font-size: 0.75rem;">Sort by Newest</button>
            </div>
          </div>
=======
        <!-- Discussion / Comments Section -->
        <div class="pf-comments-card mb-4">
          <div class="p-4 border-bottom bg-light d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
              <span class="material-symbols-outlined text-primary" style="font-size: 22px;">forum</span>
              <h5 class="fw-bold text-dark mb-0 fs-6">Discussion Thread (<?= $comments_total_count ?? 0 ?>)</h5>
            </div>
            <span class="badge bg-white text-secondary border rounded-pill px-3 py-1 fw-bold small">Latest first</span>
          </div>

          <!-- Add Direct Comment Form -->
>>>>>>> origin/main
          <?php if (!isset($post['comment_section']) || $post['comment_section'] == 1): ?>
            <div class="p-4 border-bottom bg-white">
              <form action="<?= base_url('community/addDirectComment') ?>" method="POST" class="d-flex align-items-center gap-3">
                <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
<<<<<<< HEAD
                <div class="flex-grow-1 position-relative">
                  <input type="text" name="comment" class="form-control rounded-4 border-light bg-light ps-3 pe-5" placeholder="Join the discussion as admin..." required style="height: 50px; font-weight: 500; border: 1px solid #eee !important;">
                  <button type="submit" class="btn btn-primary rounded-circle position-absolute end-0 top-50 translate-middle-y me-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; padding: 0; border: none;">
                    <span class="material-symbols-outlined fs-5">send</span>
=======
                <div class="position-relative flex-grow-1">
                  <input type="text" name="comment" class="pf-comment-input py-2.5" placeholder="Join the discussion as an authorized admin..." required>
                  <button type="submit" class="pf-comment-send-btn" title="Post Comment">
                    <span class="material-symbols-outlined" style="font-size: 18px;">send</span>
>>>>>>> origin/main
                  </button>
                </div>
              </form>
            </div>
<<<<<<< HEAD
          <?php endif; ?>
=======
          <?php else: ?>
            <div class="p-3 bg-danger-subtle text-danger small fw-bold text-center border-bottom">
              <span class="material-symbols-outlined align-middle" style="font-size: 16px;">comments_disabled</span> Comments are currently disabled for this post.
            </div>
          <?php endif; ?>

          <!-- Comments Feed -->
>>>>>>> origin/main
          <div class="comments-container">
            <?php if (!empty($comments)): ?>
              <?php foreach ($comments as $comment): ?>
                <?php
                $commentAvatar = 'https://ui-avatars.com/api/?name=' . urlencode($comment['name'] ?? 'User') . '&background=random';
                if (!empty($comment['profile'])) {
                    if (($comment['user_type'] ?? '') == 'provider') {
                        $commentAvatar = 'https://beta.petsfolio.in/public/uploads/providers/' . $comment['profile'];
                    } elseif (($comment['user_type'] ?? '') == 'admin') {
                        $commentAvatar = base_url('public/uploads/employees/' . $comment['profile']);
                    } else {
                        $commentAvatar = 'https://beta.petsfolio.in/public/uploads/users/' . $comment['profile'];
                    }
                }
                ?>
<<<<<<< HEAD
                <div class="comment-card shadow-sm mb-3">
                  <div class="d-flex gap-3">
                    <img src="<?= $commentAvatar ?>"
                      class="rounded-circle" width="42" height="42" style="object-fit: cover;" onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($comment['name'] ?? 'User') ?>&background=random'">
                    <div class="flex-grow-1">
                      <div class="d-flex align-items-center justify-content-between mb-1">
                        <span class="fw-800 text-dark"><?= esc($comment['name']) ?></span>
                        <small class="text-muted fw-bold"><?= date('M d, Y', strtotime($comment['created_at'])) ?></small>
                      </div>
                      <p class="text-secondary fw-semibold mb-2" style="font-size: 0.95rem;">
                        <?= esc($comment['comment']) ?>
                      </p>
                      <div class="d-flex gap-2 mt-1">
                        <?php if (($comment['user_type'] ?? '') == 'admin' || ($comment['user_type'] ?? '') == '3'): ?>
                          <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2 py-0.5 small" style="font-size: 0.65rem; font-weight: 800; text-transform: uppercase;">Admin</span>
                        <?php elseif (($comment['user_type'] ?? '') == 'provider' || ($comment['user_type'] ?? '') == '2'): ?>
                          <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 py-0.5 small" style="font-size: 0.65rem; font-weight: 800; text-transform: uppercase;">Provider</span>
                        <?php else: ?>
                          <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-0.5 small" style="font-size: 0.65rem; font-weight: 800; text-transform: uppercase;">Client</span>
                        <?php endif; ?>
                      </div>
                      <div class="d-flex align-items-center gap-4 mt-3">
                        <div class="d-flex align-items-center gap-3">
                          <div class="d-flex align-items-center gap-1.5 text-muted fw-bold" style="font-size: 0.75rem;">
                            <span class="material-symbols-outlined fs-6 text-primary" style="font-variation-settings: 'FILL' 1">thumb_up</span>
                            <?= number_format($comment['likes_count'] ?? 0) ?>
                          </div>
                          <div class="d-flex align-items-center gap-1.5 text-muted fw-bold" style="font-size: 0.75rem;">
                            <span class="material-symbols-outlined fs-6 text-danger" style="font-variation-settings: 'FILL' 1">thumb_down</span>
                            <?= number_format($comment['dislikes_count'] ?? 0) ?>
                          </div>
                        </div>
                        <div class="d-flex gap-3 align-items-center">
                          <button class="btn p-0 text-muted fw-bold small text-uppercase" style="font-size: 0.7rem;" onclick="document.getElementById('reply-form-<?= $comment['id'] ?>').classList.toggle('d-none')">Reply (<?= count($comment['replies'] ?? []) ?>)</button>
                          <button class="btn p-0 text-danger fw-bold small text-uppercase" style="font-size: 0.7rem;" onclick="if(confirm('Remove this comment?')) window.location.href='<?= base_url('community/deleteComment/' . $comment['id']) ?>'">Remove</button>
                          <button class="btn p-0 text-muted fw-bold small text-uppercase ms-auto" style="font-size: 0.7rem;" onclick="reportComment(<?= $comment['id'] ?>)">Report</button>
                        </div>
                      </div>

                      <!-- Add Reply Form -->
                      <div id="reply-form-<?= $comment['id'] ?>" class="d-none mt-3">
                        <form action="<?= base_url('community/addReply/' . $comment['id']) ?>" method="POST">
                          <div class="input-group">
                            <input type="text" name="comment" class="form-control form-control-sm shadow-none" placeholder="Write a reply..." required>
                            <button class="btn btn-primary btn-sm px-3 fw-bold" type="submit">Send</button>
                          </div>
                        </form>
                      </div>

                      <!-- Replies -->
                      <?php if (!empty($comment['replies'])): ?>
                        <div class="ms-5 mt-4 border-start ps-4">
=======
                <div class="pf-comment-item">
                  <div class="d-flex gap-3 align-items-start">
                    <img src="<?= $commentAvatar ?>" class="pf-avatar-sm flex-shrink-0" alt="<?= esc($comment['name']) ?>" onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($comment['name'] ?? 'User') ?>&background=random'">
                    <div class="flex-grow-1">
                      <div class="d-flex align-items-center justify-content-between mb-1">
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                          <span class="fw-bold text-dark fs-6"><?= esc($comment['name']) ?></span>
                          <?php if (($comment['user_type'] ?? '') == 'admin' || ($comment['user_type'] ?? '') == '3'): ?>
                            <span class="pf-badge-role admin"><span class="material-symbols-outlined" style="font-size: 11px;">shield_person</span> Admin</span>
                          <?php elseif (($comment['user_type'] ?? '') == 'provider' || ($comment['user_type'] ?? '') == '2'): ?>
                            <span class="pf-badge-role provider"><span class="material-symbols-outlined" style="font-size: 11px;">badge</span> Provider</span>
                          <?php else: ?>
                            <span class="pf-badge-role client"><span class="material-symbols-outlined" style="font-size: 11px;">pets</span> Client</span>
                          <?php endif; ?>
                        </div>
                        <small class="text-muted fw-semibold"><?= date('M d, Y • h:i A', strtotime($comment['created_at'])) ?></small>
                      </div>

                      <div class="pf-comment-bubble">
                        <p class="pf-comment-text"><?= esc($comment['comment']) ?></p>
                      </div>

                      <!-- Comment Toolbar -->
                      <div class="d-flex align-items-center justify-content-between mt-2 pt-1">
                        <div class="d-flex align-items-center gap-3">
                          <span class="small text-muted fw-bold d-flex align-items-center gap-1">
                            <span class="material-symbols-outlined fill text-primary" style="font-size: 15px;">thumb_up</span>
                            <?= number_format($comment['likes_count'] ?? 0) ?>
                          </span>
                          <span class="small text-muted fw-bold d-flex align-items-center gap-1">
                            <span class="material-symbols-outlined fill text-danger" style="font-size: 15px;">thumb_down</span>
                            <?= number_format($comment['dislikes_count'] ?? 0) ?>
                          </span>
                        </div>

                        <div class="d-flex align-items-center gap-3">
                          <button class="btn btn-sm btn-link text-primary fw-bold p-0 text-decoration-none" onclick="document.getElementById('reply-form-<?= $comment['id'] ?>').classList.toggle('d-none')">
                            Reply (<?= count($comment['replies'] ?? []) ?>)
                          </button>
                          <button class="btn btn-sm btn-link text-muted fw-bold p-0 text-decoration-none" onclick="reportComment(<?= $comment['id'] ?>)">
                            Report
                          </button>
                          <button class="btn btn-sm btn-link text-danger fw-bold p-0 text-decoration-none" onclick="if(confirm('Permanently remove this comment?')) window.location.href='<?= base_url('community/deleteComment/' . $comment['id']) ?>'">
                            Delete
                          </button>
                        </div>
                      </div>

                      <!-- Reply Input Form -->
                      <div id="reply-form-<?= $comment['id'] ?>" class="d-none mt-3 p-3 bg-light rounded-3 border">
                        <form action="<?= base_url('community/addReply/' . $comment['id']) ?>" method="POST" class="d-flex gap-2">
                          <input type="text" name="comment" class="pf-comment-input bg-white" placeholder="Write a response to @<?= esc($comment['name']) ?>..." required>
                          <button class="pf-btn-primary px-3" type="submit">
                            <span class="material-symbols-outlined" style="font-size: 16px;">reply</span> Send
                          </button>
                        </form>
                      </div>

                      <!-- Nested Replies Thread -->
                      <?php if (!empty($comment['replies'])): ?>
                        <div class="pf-reply-thread">
>>>>>>> origin/main
                          <?php foreach ($comment['replies'] as $reply): ?>
                            <?php
                            $replyAvatar = 'https://ui-avatars.com/api/?name=' . urlencode($reply['name'] ?? 'User') . '&background=random';
                            if (!empty($reply['profile'])) {
                                if (($reply['user_type'] ?? '') == 'provider') {
                                    $replyAvatar = 'https://beta.petsfolio.in/public/uploads/providers/' . $reply['profile'];
                                } elseif (($reply['user_type'] ?? '') == 'admin') {
                                    $replyAvatar = base_url('public/uploads/employees/' . $reply['profile']);
                                } else {
                                    $replyAvatar = 'https://beta.petsfolio.in/public/uploads/users/' . $reply['profile'];
                                }
                            }
                            ?>
<<<<<<< HEAD
                            <div class="mb-4">
                              <div class="d-flex gap-3">
                                <img src="<?= $replyAvatar ?>"
                                  class="rounded-circle" width="32" height="32" style="object-fit: cover;" onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($reply['name'] ?? 'User') ?>&background=random'">
                                <div class="flex-grow-1">
                                  <div class="d-flex align-items-center justify-content-between mb-1">
                                    <div class="d-flex align-items-center gap-2">
                                      <span class="fw-800 text-dark small"><?= esc($reply['name']) ?></span>
                                      <?php if (($reply['user_type'] ?? '') == 'admin' || ($reply['user_type'] ?? '') == '3'): ?>
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-1.5 py-0" style="font-size: 0.6rem; font-weight: 800;">ADMIN</span>
                                      <?php elseif (($reply['user_type'] ?? '') == 'provider' || ($reply['user_type'] ?? '') == '2'): ?>
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-1.5 py-0" style="font-size: 0.6rem; font-weight: 800;">PROVIDER</span>
                                      <?php else: ?>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-1.5 py-0" style="font-size: 0.6rem; font-weight: 800;">CLIENT</span>
                                      <?php endif; ?>
                                    </div>
                                    <small class="text-muted fw-bold" style="font-size: 0.7rem;"><?= date('M d, Y', strtotime($reply['created_at'])) ?></small>
                                  </div>
                                  <p class="text-secondary fw-semibold mb-1" style="font-size: 0.9rem;">
                                    <?= esc($reply['comment']) ?>
                                  </p>
                                  <div class="d-flex align-items-center gap-4 mt-2">
                                    <div class="d-flex align-items-center gap-3">
                                      <div class="d-flex align-items-center gap-1 text-muted fw-bold" style="font-size: 0.65rem;">
                                        <span class="material-symbols-outlined text-primary" style="font-size: 14px; font-variation-settings: 'FILL' 1">thumb_up</span>
                                        <?= number_format($reply['likes_count'] ?? 0) ?>
                                      </div>
                                      <div class="d-flex align-items-center gap-1 text-muted fw-bold" style="font-size: 0.65rem;">
                                        <span class="material-symbols-outlined text-danger" style="font-size: 14px; font-variation-settings: 'FILL' 1">thumb_down</span>
                                        <?= number_format($reply['dislikes_count'] ?? 0) ?>
                                      </div>
                                    </div>
                                    <div class="d-flex gap-3 align-items-center flex-grow-1">
                                      <button class="btn p-0 text-danger fw-bold" style="font-size: 0.65rem; text-transform: uppercase;" onclick="if(confirm('Remove this reply?')) window.location.href='<?= base_url('community/deleteComment/' . $reply['id']) ?>'">Remove</button>
                                      <button class="btn p-0 text-muted fw-bold ms-auto" style="font-size: 0.65rem; text-transform: uppercase;" onclick="reportComment(<?= $reply['id'] ?>)">Report</button>
=======
                            <div class="pf-reply-item">
                              <div class="d-flex gap-2.5 align-items-start">
                                <img src="<?= $replyAvatar ?>" class="rounded-circle" width="30" height="30" style="object-fit: cover;">
                                <div class="flex-grow-1">
                                  <div class="d-flex align-items-center justify-content-between mb-1">
                                    <div class="d-flex align-items-center gap-1.5 flex-wrap">
                                      <span class="fw-bold text-dark small"><?= esc($reply['name']) ?></span>
                                      <?php if (($reply['user_type'] ?? '') == 'admin' || ($reply['user_type'] ?? '') == '3'): ?>
                                        <span class="pf-badge-role admin" style="font-size: 0.6rem; padding: 0.15rem 0.5rem;">Admin</span>
                                      <?php elseif (($reply['user_type'] ?? '') == 'provider' || ($reply['user_type'] ?? '') == '2'): ?>
                                        <span class="pf-badge-role provider" style="font-size: 0.6rem; padding: 0.15rem 0.5rem;">Provider</span>
                                      <?php else: ?>
                                        <span class="pf-badge-role client" style="font-size: 0.6rem; padding: 0.15rem 0.5rem;">Client</span>
                                      <?php endif; ?>
                                    </div>
                                    <small class="text-muted" style="font-size: 0.7rem;"><?= date('M d, Y', strtotime($reply['created_at'])) ?></small>
                                  </div>

                                  <div class="p-2.5 rounded-3 bg-light border">
                                    <p class="small text-secondary mb-0"><?= esc($reply['comment']) ?></p>
                                  </div>

                                  <div class="d-flex align-items-center justify-content-between mt-1">
                                    <div class="d-flex align-items-center gap-2">
                                      <span class="text-muted fw-bold" style="font-size: 0.7rem;">
                                        <span class="material-symbols-outlined fill text-primary" style="font-size: 13px;">thumb_up</span> <?= $reply['likes_count'] ?? 0 ?>
                                      </span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                      <button class="btn btn-sm btn-link text-muted p-0 text-decoration-none" style="font-size: 0.7rem;" onclick="reportComment(<?= $reply['id'] ?>)">Report</button>
                                      <button class="btn btn-sm btn-link text-danger p-0 text-decoration-none" style="font-size: 0.7rem;" onclick="if(confirm('Remove this reply?')) window.location.href='<?= base_url('community/deleteComment/' . $reply['id']) ?>'">Delete</button>
>>>>>>> origin/main
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          <?php endforeach; ?>
                        </div>
                      <?php endif; ?>
<<<<<<< HEAD

=======
>>>>>>> origin/main
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
<<<<<<< HEAD
              <div class="text-center py-5">
                <span class="material-symbols-outlined display-3 text-muted opacity-25 mb-3">chat_bubble_outline</span>
                <p class="text-muted fw-bold">Be the first to moderate the discussion</p>
=======
              <div class="pf-empty-state py-5">
                <div class="pf-empty-icon" style="width: 56px; height: 56px; font-size: 1.75rem;">
                  <span class="material-symbols-outlined">chat_bubble_outline</span>
                </div>
                <h6 class="fw-bold text-dark mb-1">No Discussion Yet</h6>
                <p class="text-muted small mb-0">Use the admin input box above to start the conversation.</p>
>>>>>>> origin/main
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>

<<<<<<< HEAD
      <!-- Sidebar Actions Column -->
      <div class="col-lg-4">
        <div class="sticky-top" style="top: 2rem;">
          <!-- Post Health & Metadata -->
          <div class="glass-card p-4 mb-4">
            <h6 class="text-uppercase text-muted fw-800 small tracking-widest mb-4">Content Insights</h6>
            <div class="row g-3 mb-4">
              <div class="col-6">
                <div class="sidebar-stat shadow-sm border border-light-subtle">
                  <small class="text-muted fw-bold d-block mb-1" style="font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.05em;">Engagement</small>
                  <span class="h4 fw-800 text-dark"><?= ($post['likes_count'] ?? 0) + ($post['dislikes_count'] ?? 0) ?></span>
                </div>
              </div>
              <div class="col-6">
                <div class="sidebar-stat shadow-sm border border-light-subtle">
                  <small class="text-muted fw-bold d-block mb-1" style="font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.05em;">Reports</small>
                  <span class="h4 fw-800 <?= $reports_count > 0 ? 'text-danger' : 'text-dark' ?>"><?= $reports_count ?></span>
=======
      <!-- Right Column: Sidebar Actions & Insights -->
      <div class="col-lg-4">
        <div class="sticky-top" style="top: 2rem;">
          <!-- Content Insights Card -->
          <div class="pf-form-card p-4 mb-4">
            <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom fs-6">Content Insights</h6>

            <div class="row g-3 mb-4">
              <div class="col-6">
                <div class="pf-stat-card p-3 text-center">
                  <div class="pf-stat-label text-center">Total Reactions</div>
                  <div class="pf-stat-value fs-3 text-center"><?= ($post['likes_count'] ?? 0) + ($post['dislikes_count'] ?? 0) ?></div>
                </div>
              </div>
              <div class="col-6">
                <div class="pf-stat-card p-3 text-center">
                  <div class="pf-stat-label text-center">Reports</div>
                  <div class="pf-stat-value fs-3 text-center <?= $reports_count > 0 ? 'text-danger' : 'text-dark' ?>">
                    <?= $reports_count ?>
                  </div>
>>>>>>> origin/main
                </div>
              </div>
            </div>

<<<<<<< HEAD
            <div class="space-y-3 d-grid gap-3">
              <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-light">
                <span class="text-muted fw-bold small">Pin Status</span>
                <span class="fw-800 text-dark small"><?= $post['is_pinned'] ? 'Pinned' : 'Normal' ?></span>
              </div>
              <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-light">
                <span class="text-muted fw-bold small">Modified</span>
                <span class="fw-800 text-dark small"><?= $post['updated_at'] ? date('M d, Y', strtotime($post['updated_at'])) : 'Never' ?></span>
=======
            <div class="d-flex flex-column gap-2.5">
              <div class="d-flex justify-content-between align-items-center py-2 border-top">
                <span class="text-muted small fw-bold">Pin Status</span>
                <span class="badge <?= $post['is_pinned'] ? 'bg-warning-subtle text-warning border border-warning' : 'bg-light text-muted border' ?> rounded-pill px-2.5 py-1 fw-bold">
                  <?= $post['is_pinned'] ? '📌 Pinned to Top' : 'Standard Feed' ?>
                </span>
              </div>

              <div class="d-flex justify-content-between align-items-center py-2 border-top">
                <span class="text-muted small fw-bold">Comments Setting</span>
                <span class="badge <?= (!isset($post['comment_section']) || $post['comment_section'] == 1) ? 'bg-success-subtle text-success border border-success' : 'bg-danger-subtle text-danger border border-danger' ?> rounded-pill px-2.5 py-1 fw-bold">
                  <?= (!isset($post['comment_section']) || $post['comment_section'] == 1) ? 'Enabled' : 'Disabled' ?>
                </span>
              </div>

              <div class="d-flex justify-content-between align-items-center py-2 border-top">
                <span class="text-muted small fw-bold">Visibility</span>
                <span class="text-dark small fw-bold text-capitalize"><?= esc($post['visibility'] ?? 'Public') ?></span>
>>>>>>> origin/main
              </div>
            </div>
          </div>

<<<<<<< HEAD
          <!-- Admin Toolbox -->
          <div class="glass-card p-4 mb-4">
            <h6 class="text-uppercase text-muted fw-800 small tracking-widest mb-4">Moderator Toolbox</h6>
            <div class="d-grid gap-3">
              <a href="<?= base_url('community/editPost/' . $post['id']) ?>" class="btn action-button btn-success text-decoration-none">
                <span class="material-symbols-outlined">edit</span>
                Edit Content
              </a>

              <a href="<?= base_url('community/togglePin/' . $post['id']) ?>" class="btn action-button btn-pin text-decoration-none">
                <span class="material-symbols-outlined">push_pin</span>
                <?= $post['is_pinned'] ? 'Unpin Content' : 'Pin Post' ?>
              </a>

              <?php if ($reports_count > 0): ?>
                <a href="<?= base_url('community/post-reports/' . $post['id']) ?>" class="btn action-button btn-light border fw-bold text-dark text-decoration-none">
                  <span class="material-symbols-outlined text-danger">flag</span>
                  View Report Log
                </a>
              <?php endif; ?>

              <div class="my-2 border-top border-light"></div>

              <button class="btn action-button btn-danger-soft"
                onclick="if(confirm('Archive this content permanently?')) window.location.href='<?= base_url('community/deletePost/' . $post['id']) ?>'">
                <span class="material-symbols-outlined">delete_sweep</span>
                Delete Content
              </button>
            </div>
          </div>


=======
          <!-- Moderator Toolbox Card -->
          <div class="pf-form-card p-4">
            <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom fs-6">Moderator Toolbox</h6>

            <div class="d-flex flex-column gap-2.5">
              <?php if (($post['user_type'] ?? '') == 'admin'): ?>
                <a href="<?= base_url('community/editPost/' . $post['id']) ?>" class="btn btn-outline-primary rounded-pill fw-bold py-2.5 d-flex align-items-center justify-content-center gap-2">
                  <span class="material-symbols-outlined" style="font-size: 18px;">edit</span> Edit Content
                </a>
              <?php endif; ?>

              <a href="<?= base_url('community/togglePin/' . $post['id']) ?>" class="btn btn-outline-warning text-dark rounded-pill fw-bold py-2.5 d-flex align-items-center justify-content-center gap-2">
                <span class="material-symbols-outlined" style="font-size: 18px;">push_pin</span>
                <?= $post['is_pinned'] ? 'Unpin Post' : 'Pin to Top' ?>
              </a>

              <?php if ($reports_count > 0): ?>
                <a href="<?= base_url('community/post-reports/' . $post['id']) ?>" class="btn btn-danger-subtle text-danger border border-danger rounded-pill fw-bold py-2.5 d-flex align-items-center justify-content-center gap-2">
                  <span class="material-symbols-outlined" style="font-size: 18px;">flag</span>
                  View Report Log (<?= $reports_count ?>)
                </a>
              <?php endif; ?>

              <?php if (($post['status'] ?? 'active') != 'blocked'): ?>
                <a href="<?= base_url('community/blockPost/' . $post['id']) ?>" class="btn btn-outline-secondary rounded-pill fw-bold py-2.5 d-flex align-items-center justify-content-center gap-2" onclick="return confirm('Block this post? It will be hidden from all app feeds.')">
                  <span class="material-symbols-outlined" style="font-size: 18px;">block</span> Block Post
                </a>
              <?php else: ?>
                <a href="<?= base_url('community/unblockPost/' . $post['id']) ?>" class="btn btn-outline-success rounded-pill fw-bold py-2.5 d-flex align-items-center justify-content-center gap-2">
                  <span class="material-symbols-outlined" style="font-size: 18px;">check_circle</span> Unblock & Restore Post
                </a>
              <?php endif; ?>

              <div class="my-2 border-top"></div>

              <button class="btn btn-danger rounded-pill fw-bold py-2.5 d-flex align-items-center justify-content-center gap-2"
                onclick="if(confirm('Are you certain you want to permanently delete this post? This cannot be undone.')) window.location.href='<?= base_url('community/deletePost/' . $post['id']) ?>'">
                <span class="material-symbols-outlined" style="font-size: 18px;">delete</span> Delete Post
              </button>
            </div>
          </div>
>>>>>>> origin/main
        </div>
      </div>
    </div>
  </div>
<<<<<<< HEAD
</div>


<style>
  :root {
    --primary-gradient: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
    --surface-color: #f8fafc;
    --card-bg: #ffffff;
    --text-main: #1e293b;
    --text-muted: #64748b;
    --border-color: rgba(226, 232, 240, 0.8);
    --glass-bg: rgba(255, 255, 255, 0.7);
  }

  body {
    font-family: 'Plus Jakarta Sans', sans-serif;
    background-color: var(--surface-color);
    color: var(--text-main);
  }

  .glass-card {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 24px;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.04), 0 8px 10px -6px rgba(0, 0, 0, 0.04);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }

  .banner-card {
    position: relative;
    overflow: hidden;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
  }

  .rounded-4xl {
    border-radius: 2rem !important;
  }

  .glass-pill {
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: all 0.3s ease;
  }

  .glass-pill:hover {
    background: rgba(255, 255, 255, 0.25);
    transform: translateY(-2px);
    color: white;
  }

  .author-badge {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 8px 16px 8px 8px;
    background: #f1f5f9;
    border-radius: 50px;
    width: fit-content;
  }

  .author-avatar {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #fff;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
  }

  .post-content-area {
    font-size: 1.125rem;
    line-height: 1.7;
    color: #334155;
  }

  .media-gallery {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 16px;
    margin-top: 24px;
  }

  .media-item {
    border-radius: 20px;
    overflow: hidden;
    border: 1px solid var(--border-color);
    background: #000;
    cursor: pointer;
    position: relative;
  }

  .media-item .download-overlay {
    position: absolute;
    top: 12px;
    right: 12px;
    z-index: 20;
    opacity: 0;
    transform: translateY(-8px);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  }

  .media-item:hover .download-overlay {
    opacity: 1;
    transform: translateY(0);
  }

  .media-item:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
  }

  .media-item img,
  .media-item video {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
  }

  .media-item:hover img {
    transform: scale(1.05);
  }

  .interaction-pill {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background: #f8fafc;
    border-radius: 12px;
    color: var(--text-muted);
    font-weight: 600;
    font-size: 0.9rem;
  }

  .comment-card {
    padding: 20px;
    border-bottom: 1px solid #f1f5f9;
    transition: background 0.2s ease;
  }

  .comment-card:last-child {
    border-bottom: none;
  }

  .comment-card:hover {
    background: #fcfcfd;
  }

  .action-button {
    padding: 12px 24px;
    border-radius: 14px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.025em;
    font-size: 0.8rem;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
  }

  .btn-pin {
    background: var(--primary-gradient);
    color: white;
    border: none;
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
  }

  .btn-pin:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(99, 102, 241, 0.4);
    color: white;
  }

  .btn-danger-soft {
    background: #fff1f2;
    color: #e11d48;
    border: 1px solid #ffe4e6;
  }

  .btn-danger-soft:hover {
    background: #ffe4e6;
    color: #be123c;
  }

  .sidebar-stat {
    padding: 16px;
    background: #f1f5f9;
    border-radius: 16px;
    text-align: center;
  }

  .channel-tag {
    background: #e0e7ff;
    color: #4338ca;
    padding: 4px 12px;
    border-radius: 8px;
    font-weight: 800;
    font-size: 0.75rem;
    text-transform: uppercase;
  }

  .status-active {
    background: #dcfce7;
    color: #15803d;
    padding: 4px 12px;
    border-radius: 8px;
    font-weight: 800;
    font-size: 0.75rem;
  }

  .material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
  }

  .fill {
    font-variation-settings: 'FILL' 1;
  }
</style>
=======
</main>

<!-- Media Lightbox Modal -->
<div class="modal fade" id="communityMediaModal" tabindex="-1" aria-labelledby="communityMediaModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content pf-modal-content" style="background: #0f172a;">
      <div class="modal-header border-0 pb-0 pt-3 px-4 d-flex justify-content-between align-items-center">
        <h6 class="modal-title text-white fw-bold d-flex align-items-center gap-2 mb-0" id="communityMediaModalLabel">
          <span class="material-symbols-outlined text-primary fs-5">play_circle</span>
          <span id="communityMediaModalTitle">Media Asset Preview</span>
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