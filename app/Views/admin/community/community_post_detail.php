<?= $this->extend('admin/includes/header'); ?>
<?= $this->section('content'); ?>

<script>
  function reportComment(commentId) {
    const reason = prompt("Please provide a reason for reporting this comment:", "Inappropriate content");
    if (reason !== null && reason.trim() !== "") {
      const url = '<?= base_url('community/reportComment/') ?>/' + commentId + '?reason=' + encodeURIComponent(reason);
      window.location.href = url;
    }
  }
</script>

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
        </div>
      </div>
    </div>

    <div class="row g-4">
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
                    <?php endif; ?>
                  </div>
                <?php endforeach; ?>
              </div>
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
            </div>
          </div>
        </div>

        <!-- Comments Section -->
        <div class="glass-card overflow-hidden">
          <div class="p-4 border-bottom d-flex align-items-center justify-content-between bg-light/30">
            <h5 class="fw-800 mb-0">Discussion (<?= $comments_total_count ?? 0 ?>)</h5>
            <div class="d-flex gap-2">
              <button class="btn btn-sm fw-bold border-0 text-primary" style="font-size: 0.75rem;">Sort by Newest</button>
            </div>
          </div>
          <?php if (!isset($post['comment_section']) || $post['comment_section'] == 1): ?>
            <div class="p-4 border-bottom bg-white">
              <form action="<?= base_url('community/addDirectComment') ?>" method="POST" class="d-flex align-items-center gap-3">
                <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                <div class="flex-grow-1 position-relative">
                  <input type="text" name="comment" class="form-control rounded-4 border-light bg-light ps-3 pe-5" placeholder="Join the discussion as admin..." required style="height: 50px; font-weight: 500; border: 1px solid #eee !important;">
                  <button type="submit" class="btn btn-primary rounded-circle position-absolute end-0 top-50 translate-middle-y me-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; padding: 0; border: none;">
                    <span class="material-symbols-outlined fs-5">send</span>
                  </button>
                </div>
              </form>
            </div>
          <?php endif; ?>
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
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          <?php endforeach; ?>
                        </div>
                      <?php endif; ?>

                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <div class="text-center py-5">
                <span class="material-symbols-outlined display-3 text-muted opacity-25 mb-3">chat_bubble_outline</span>
                <p class="text-muted fw-bold">Be the first to moderate the discussion</p>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>

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
                </div>
              </div>
            </div>

            <div class="space-y-3 d-grid gap-3">
              <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-light">
                <span class="text-muted fw-bold small">Pin Status</span>
                <span class="fw-800 text-dark small"><?= $post['is_pinned'] ? 'Pinned' : 'Normal' ?></span>
              </div>
              <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-light">
                <span class="text-muted fw-bold small">Modified</span>
                <span class="fw-800 text-dark small"><?= $post['updated_at'] ? date('M d, Y', strtotime($post['updated_at'])) : 'Never' ?></span>
              </div>
            </div>
          </div>

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


        </div>
      </div>
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
<?= $this->endSection(); ?>