<?= $this->extend('admin/includes/header'); ?>
<?= $this->section('content'); ?>

<!-- Google Font & Material Icons -->
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">



<main style="background-color: #f8f9fc;">
    <div class="position-relative iq-banner">
        <?php echo $this->include('admin/nav_bar') ?>
    </div>
    <div class="container-fluid py-4 px-4" style="margin-top:-80px ;">
        <div class="banner-card rounded-4xl p-4 p-md-5 mb-3 text-white shadow-lg position-relative overflow-hidden" style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);">
            <div class="position-absolute" style="width: 300px; height: 300px; background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%); border-radius: 50%; top: -150px; right: -100px;"></div>
            <div class="position-absolute" style="width: 200px; height: 200px; background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, rgba(255,255,255,0) 70%); border-radius: 50%; bottom: -100px; left: -50px;"></div>

            <div class="row position-relative">
                <div class="col-lg-7">
                    <div class="d-flex align-items-center gap-3 ">
                        <div class="glass-pill p-3 d-flex align-items-center justify-content-center" style="width: 64px; height: 64px; border-radius: 20px;">
                            <span class="material-symbols-outlined fs-1">pets</span>
                        </div>
                        <div>
                            <h1 class="display-6 fw-bold mb-1 text-white">Pet Community</h1>
                            <p class="text-white mb-0 fw-medium">Manage and explore community contributions</p>
                        </div>
                    </div>

                    <div class="pill-nav-container d-inline-flex bg-white p-1 rounded-pill shadow-sm mt-4">
                        <a href="<?= base_url('community/posts?status=admin') ?>" class="pill-item px-4 py-2 text-decoration-none d-flex align-items-center gap-2 <?= $status == 'admin' ? 'active' : '' ?>">
                            <span class="material-symbols-outlined fs-5">admin_panel_settings</span>
                            <span>Admin Posts</span>
                            <span class="pill-badge"><?= $admin_posts_count ?></span>
                        </a>
                        <a href="<?= base_url('community/posts?status=user') ?>" class="pill-item px-4 py-2 text-decoration-none d-flex align-items-center gap-2 <?= $status == 'user' ? 'active' : '' ?>">
                            <span class="material-symbols-outlined fs-5">person</span>
                            <span>User Posts</span>
                            <span class="pill-badge"><?= $user_posts_count ?></span>
                        </a>
                        <a href="<?= base_url('community/posts?status=scheduled') ?>" class="pill-item px-4 py-2 text-decoration-none d-flex align-items-center gap-2 <?= $status == 'scheduled' ? 'active' : '' ?>">
                            <span class="material-symbols-outlined fs-5">schedule</span>
                            <span>Scheduled</span>
                            <span class="pill-badge"><?= $scheduled_posts_count ?></span>
                        </a>
                        <a href="<?= base_url('community/posts?status=questions') ?>" class="pill-item px-4 py-2 text-decoration-none d-flex align-items-center gap-2 <?= $status == 'questions' ? 'active' : '' ?>">
                            <span class="material-symbols-outlined fs-5">question_mark</span>
                            <span>Questions</span>
                            <span class="pill-badge"><?= $questions_count ?></span>
                        </a>
                    </div>
                </div>

                <div class="col-lg-5 mt-4 mt-lg-0">
                    <div class="d-flex flex-column flex-sm-row justify-content-lg-end gap-3">
                        <!-- <button class="btn glass-pill border-0 text-white px-4 rounded-pill py-3 fw-bold d-flex align-items-center justify-content-center gap-2" onclick="window.location.href='<?= base_url('community/reports') ?>'">
                            <span class="material-symbols-outlined">report</span> Moderation Queue
                        </button> -->
                        <button class="btn btn-white text-primary bg-white rounded-pill px-4 py-3 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2 border-0" onclick="window.location.href='<?= base_url('community/addPost') ?>'">
                            <span class="material-symbols-outlined">add</span> Create New Post
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-3">
            <div class="col-sm-6 col-lg-3">
                <div class="card border-0 p-0 rounded-4xl stat-card shadow-lg h-100 position-relative overflow-hidden" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);">
                    <div class="position-absolute" style="width: 100px; height: 100px; background: rgba(255,255,255,0.1); border-radius: 50%; top: -30px; right: -30px;"></div>
                    <div class="card-body p-4 text-white position-relative">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="icon-glass">
                                <span class="material-symbols-outlined fs-2">dynamic_feed</span>
                            </div>
                            <span class="badge bg-white/20 text-white rounded-pill px-3 py-1  fw-bold">+12%</span>
                        </div>
                        <h5 class="text-white small fw-bold text-uppercase tracking-wider mb-2">Total Posts</h5>
                        <h2 class="display-6 text-white fw-bold mb-0"><?= number_format($stats['total_posts'] ?? 0) ?></h2>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card border-0 p-0 rounded-4xl stat-card shadow-lg h-100 position-relative overflow-hidden" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                    <div class="position-absolute" style="width: 100px; height: 100px; background: rgba(255,255,255,0.1); border-radius: 50%; bottom: -30px; left: -30px;"></div>
                    <div class="card-body p-4 text-white position-relative">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="icon-glass">
                                <span class="material-symbols-outlined fs-2">groups</span>
                            </div>
                            <span class="badge bg-white/20 text-white rounded-pill px-3 py-1  fw-bold">Active</span>
                        </div>
                        <h5 class="text-white small fw-bold text-uppercase tracking-wider mb-2">Active Users</h5>
                        <h2 class="display-6 text-white fw-bold mb-0"><?= number_format($stats['active_users'] ?? 0) ?></h2>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card border-0 p-0 rounded-4xl stat-card shadow-lg h-100 position-relative overflow-hidden" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                    <div class="position-absolute" style="width:120px; height: 120px; background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0) 70%); top: -40px; right: -20px;"></div>
                    <div class="card-body p-4 text-white position-relative">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="icon-glass">
                                <span class="material-symbols-outlined fs-2">favorite</span>
                            </div>
                            <span class="badge bg-white/20 text-white rounded-pill px-3 py-1  fw-bold">Hot</span>
                        </div>
                        <h5 class="text-white small fw-bold text-uppercase tracking-wider mb-2">Total Interactions</h5>
                        <h2 class="display-6 text-white fw-bold mb-0">
                            <?php
                            $interactions = $stats['total_interactions'] ?? 0;
                            echo ($interactions >= 1000) ? number_format($interactions / 1000, 1) . 'k' : $interactions;
                            ?>
                        </h2>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card border-0 p-0 rounded-4xl stat-card shadow-lg h-100 position-relative overflow-hidden" style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);">
                    <div class="position-absolute shadow-lg" style="width: 80px; height: 80px; background: rgba(255,255,255,0.05); border-radius: 20px; bottom: 10px; right: -20px; transform: rotate(30deg);"></div>
                    <div class="card-body p-4 text-white position-relative">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="icon-glass">
                                <span class="material-symbols-outlined fs-2">query_stats</span>
                            </div>
                            <span class="badge bg-white/20 text-white rounded-pill px-3 py-1  fw-bold">Live</span>
                        </div>
                        <h5 class="text-white small fw-bold text-uppercase tracking-wider mb-2">Engagement Rate</h5>
                        <h2 class="display-6 text-white fw-bold mb-0"><?= $stats['reporting_rate'] ?? '0.0%' ?></h2>
                    </div>
                </div>
            </div>
        </div>
        <form action="<?= base_url('community/posts') ?>" method="GET" class="mb-4 px-1">
            <input type="hidden" name="status" value="<?= $status ?>">
            <div class="filter-toolbar p-2 bg-white rounded-pill shadow-sm d-flex align-items-center gap-2">
                <!-- Search Input Group -->
                <div class="flex-grow-1 position-relative d-none d-md-block">
                    <span class="material-symbols-outlined position-absolute top-50 translate-middle-y ms-4 text-muted" style="z-index: 5;">search</span>
                    <input type="text" name="search" class="form-control border-0 bg-light rounded-pill ps-5 py-3 fw-medium" placeholder="Search posts, topics..." value="<?= esc($request->getVar('search')) ?>" style="padding-left: 3.5rem !important;">
                </div>

                <!-- Channel Select -->
                <div class="position-relative">
                    <span class="material-symbols-outlined position-absolute top-50 translate-middle-y ms-3 text-muted d-none d-sm-block" style="z-index: 5;">category</span>
                    <select name="channel_id" class="form-select border-0 bg-light rounded-pill py-3 fw-bold text-dark px-4 <?= count($channels) > 0 ? 'ps-sm-5' : '' ?>" style="min-width: 180px; appearance: none; -webkit-appearance: none;" onchange="this.form.submit()">
                        <option value="">All Channels</option>
                        <?php foreach ($channels as $channel): ?>
                            <option value="<?= $channel['id'] ?>" <?= $request->getVar('channel_id') == $channel['id'] ? 'selected' : '' ?>><?= esc($channel['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Apply/Search Button -->
                <button type="submit" class="btn btn-primary rounded-pill px-4 py-3 fw-bold d-flex align-items-center gap-2 shadow-sm border-0 transition-all hover-scale-sm">
                    <span class="material-symbols-outlined">filter_list</span>
                    <span class="d-none d-sm-inline">Apply Filters</span>
                </button>
            </div>

            <div class="d-md-none mt-3 position-relative">
                <span class="material-symbols-outlined position-absolute top-50 translate-middle-y ms-4 text-muted">search</span>
                <input type="text" name="search" class="form-control border-0 bg-white shadow-sm rounded-pill ps-5 py-3" placeholder="Search posts..." value="<?= esc($request->getVar('search')) ?>" style="padding-left: 3.5rem !important;">
            </div>
        </form>

        <!-- Dynamic Post List Header -->
        <div class="d-flex align-items-center justify-content-between mb-4 mt-2 px-2">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-primary rounded-pill shadow-sm" style="width: 5px; height: 32px; opacity: 0.8;"></div>
                <h4 class="fw-bold text-dark mb-0">
                    <?= ($status == 'admin') ? 'Official Admin Contributions' : (($status == 'questions') ? 'Community Questions' : 'Community User Activity') ?>
                </h4>
                <div class="ms-2 badge bg-primary-subtle text-primary rounded-pill px-3 py-2 fw-black shadow-sm" style="font-size: 0.75rem;">
                    <?= number_format(count($posts)) ?> Posts Total
                </div>
            </div>
            <div class="d-none d-md-flex align-items-center gap-2 bg-white px-3 py-2 rounded-pill shadow-sm">
                <span class="material-symbols-outlined text-primary fs-5">verified_user</span>
                <small class="text-muted fw-bold text-uppercase tracking-wider" style="font-size: 0.65rem; letter-spacing: 0.1em;">
                    <?= $status == 'admin' ? 'Verified Staff Feed' : 'General Community Feed' ?>
                </small>
            </div>
        </div>

        <div class="row g-3">
            <?php foreach ($posts as $post): ?>
                <div class="col-12">
                    <div class="card border-0 rounded-4xl shadow-sm  overflow-hidden bg-white mb-0">
                        <div class="card-body p-3">
                            <div class="d-flex gap-4">
                                <!-- User Avatar Column -->
                                <div class="d-none d-sm-block">
                                    <div class="position-relative">
                                        <img src="https://i.pravatar.cc/100?u=<?= $post['user_id'] ?>" class="rounded-circle shadow-sm border border-4 border-light" width="80" height="80" style="object-fit: cover;">
                                        <div class="position-absolute bottom-0 end-0 bg-success border border-2 border-white rounded-circle" style="width: 18px; height: 18px;"></div>
                                    </div>
                                </div>

                                <!-- Post Content Column -->
                                <div class="flex-grow-1">
                                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-2">
                                        <div class="d-flex align-items-center gap-3">
                                            <!-- Mobile Avatar -->
                                            <img src="https://i.pravatar.cc/100?u=<?= $post['user_id'] ?>" class="d-sm-none rounded-circle border border-2 border-light" width="48" height="48">
                                            <div>
                                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                                    <h5 class="fw-bold text-dark mb-0"><?= esc($post['name']) ?></h5>
                                                    <div class="status-pill px-3 py-1 rounded-pill d-flex align-items-center gap-1.5" style="background: <?= ($post['user_type'] ?? '') == 'admin' ? 'rgba(255, 59, 48, 0.08)' : (($post['user_type'] ?? '') == 'provider' ? 'rgba(0, 122, 255, 0.08)' : 'rgba(52, 199, 89, 0.08)') ?>; border: 1px solid <?= ($post['user_type'] ?? '') == 'admin' ? 'rgba(255, 59, 48, 0.15)' : (($post['user_type'] ?? '') == 'provider' ? 'rgba(0, 122, 255, 0.15)' : 'rgba(52, 199, 89, 0.15)') ?>;">
                                                        <div class="w-1.5 h-1.5 rounded-full <?= ($post['user_type'] ?? '') == 'admin' ? 'bg-[#ff3b30]' : (($post['user_type'] ?? '') == 'provider' ? 'bg-[#007aff]' : 'bg-[#34c759]') ?>"></div>
                                                        <span class="text-[10px] uppercase font-black tracking-wider" style="color: <?= ($post['user_type'] ?? '') == 'admin' ? '#ff3b30' : (($post['user_type'] ?? '') == 'provider' ? '#007aff' : '#34c759') ?>;">
                                                            <?= ($post['user_type'] ?? '') == 'admin' ? 'Admin' : (($post['user_type'] ?? '') == 'provider' ? 'Provider' : 'Client') ?>
                                                        </span>
                                                    </div>
                                                </div>
                                                <small class="text-black  d-flex align-items-center gap-1 mt-2 fw-medium">
                                                    <span class="material-symbols-outlined fs-6">schedule</span>
                                                    Post at: <?= date('M d, Y', strtotime($post['created_at'])) ?>
                                                </small>
                                            </div>
                                        </div>

                                        <div class="d-flex gap-2">
                                            <?php if (($post['user_type'] ?? '') == 'admin'): ?>
                                                <a href="<?= base_url('community/editPost/' . $post['id']) ?>" class="btn btn-sm glass-pill-dark rounded-pill px-4 text-primary fw-bold d-flex align-items-center gap-1 py-2">
                                                    <span class="material-symbols-outlined fs-6">edit</span> Edit
                                                </a>
                                            <?php endif; ?>
                                            <button onclick="if(confirm('Are you sure you want to delete this post?')) window.location.href='<?= base_url('community/deletePost/' . $post['id']) ?>'" class="btn btn-sm glass-pill-dark rounded-pill px-4 text-danger fw-bold d-flex align-items-center gap-1 py-2">
                                                <span class="material-symbols-outlined fs-6">delete</span> Delete
                                            </button>
                                        </div>
                                    </div>

                                    <div class="d-flex flex-column flex-md-row gap-4">
                                        <div class="flex-grow-1">
                                            <p class="text-dark fs-6 lh-base mb-0" style="opacity: 0.85; font-weight: 500;">
                                                <?= (strlen($post['post_text']) > 450) ? substr($post['post_text'], 0, 450) . '...' : $post['post_text'] ?>
                                            </p>
                                            <div class="d-flex align-items-center justify-content-between pt-2 border-light">
                                                <div class="d-flex align-items-center gap-4">
                                                    <div class="d-flex align-items-center gap-1 cursor-pointer hover-text-primary transition-all">
                                                        <span class="material-symbols-outlined fs-5 text-muted">favorite</span>
                                                        <span class="small fw-bold text-muted"><?= number_format($post['likes_count'] ?? 0) ?></span>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-1 cursor-pointer hover-text-primary transition-all" title="<?= (isset($post['comment_section']) && $post['comment_section'] == 0) ? 'Comments Disabled' : 'Comments' ?>">
                                                        <span class="material-symbols-outlined fs-5 <?= (isset($post['comment_section']) && $post['comment_section'] == 0) ? 'text-danger' : 'text-muted' ?>">
                                                            <?= (isset($post['comment_section']) && $post['comment_section'] == 0) ? 'comments_disabled' : 'chat_bubble' ?>
                                                        </span>
                                                        <span class="small fw-bold text-muted"><?= number_format($post['comments_count'] ?? 0) ?></span>
                                                    </div>
                                                </div>
                                                <a href="<?= base_url('community/post/' . $post['id']) ?>" class="btn btn-link text-primary text-decoration-none p-0 fw-black d-flex align-items-center gap-2 px-4 py-2 bg-primary-subtle rounded-pill hover-scale-sm transition-all">
                                                    <span>Full View</span>
                                                    <span class="material-symbols-outlined fs-5">arrow_right_alt</span>
                                                </a>
                                            </div>

                                            <?php if (!isset($post['comment_section']) || $post['comment_section'] == 1): ?>
                                                <div class="mt-3 pt-3 border-top">
                                                    <form action="<?= base_url('community/addDirectComment') ?>" method="POST" class="d-flex align-items-center gap-2">
                                                        <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                                                        <div class="flex-grow-1 position-relative">
                                                            <input type="text" name="comment" class="form-control form-control-sm rounded-pill border-light bg-light ps-3 pe-5" placeholder="Write a quick comment as admin..." required style="font-size: 0.85rem; height: 38px; border: 1px solid #eee !important;">
                                                            <button type="submit" class="btn btn-link text-primary position-absolute end-0 top-50 translate-middle-y p-0 me-3 d-flex align-items-center hover-scale-sm transition-all" style="text-decoration: none;">
                                                                <span class="material-symbols-outlined fs-5">send</span>
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <?php if (!empty($post['media'])): ?>
                                            <?php $firstMedia = $post['media'][0]; ?>
                                            <div class="media-preview-item position-relative overflow-hidden flex-shrink-0 shadow-sm mx-auto mx-md-0" style="width: 100px; height: 100px; border-radius: 24px; border: 1px solid rgba(0,0,0,0.05);">
                                                <a href="<?= $firstMedia['media_url'] ?>" download target="_blank" class="download-btn z-5">
                                                    <span class="material-symbols-outlined fs-6">download</span>
                                                </a>
                                                <?php if ($firstMedia['media_type'] === 'video'): ?>
                                                    <video class="w-100 h-100 object-fit-cover">
                                                        <source src="<?= $firstMedia['media_url'] ?>" type="video/mp4">
                                                    </video>
                                                    <div class="position-absolute top-50 start-50 translate-middle">
                                                        <span class="material-symbols-outlined text-white fs-1 opacity-75">play_circle</span>
                                                    </div>
                                                <?php else: ?>
                                                    <img src="<?= $firstMedia['media_url'] ?>" class="w-100 h-100 object-fit-cover" alt="Media Preview">
                                                <?php endif; ?>

                                                <?php if (count($post['media']) > 1): ?>
                                                    <div class="position-absolute bottom-0 end-0 m-2 px-2 py-1 rounded-pill bg-dark text-white fw-black shadow-lg" style="font-size: 10px; --bs-bg-opacity: .75;">
                                                        +<?= count($post['media']) - 1 ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mt-5">
            <h4 class="fw-bold mb-3">Last Hour User Posts</h4>
            <div class="row g-3">
                <?php if (!empty($lastHourPosts)): ?>
                    <?php foreach ($lastHourPosts as $post): ?>
                        <div class="col-12">
                            <div class="bg-white rounded-3xl p-3 shadow-sm border">
                                <div class="d-flex gap-3">
                                    <img src="https://i.pravatar.cc/40?u=<?= $post['user_id'] ?>" class="rounded-circle" width="40" height="40">
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between">
                                            <strong><?= esc($post['name']) ?></strong>
                                            <small class="text-muted"><?= date('h:i A', strtotime($post['created_at'])) ?></small>
                                        </div>
                                        <p class="text-muted small mb-0"><?= esc(substr($post['post_text'], 0, 100)) ?>...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="bg-white rounded-3xl p-4 text-center text-muted">No recent posts in the last hour.</div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<style>
    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: #f8f9fc;
        /* Light shaded gray background */
    }

    .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        vertical-align: middle;
    }

    .rounded-4xl {
        border-radius: 2.5rem !important;
    }

    .glass-pill {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.07);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .glass-pill:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: translateY(-2px);
    }

    .glass-pill-active {
        background: #ffffff !important;
        color: #6366f1 !important;
        border-color: #ffffff !important;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .glass-pill-active .material-symbols-outlined {
        color: #6366f1 !important;
    }

    .btn-white {
        background: #ffffff;
        color: #6366f1;
        transition: all 0.3s ease;
    }

    .btn-white:hover {
        background: #f8f9fa;
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
    }

    .banner-card {
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .pill-nav-container {
        padding: 5px;
        gap: 5px;
    }

    .pill-item {
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.85rem;
        color: #718096;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        padding: 10px 24px !important;
    }

    .pill-item:hover {
        background: #f1f5f9;
        color: #4a5568;
    }

    .pill-item.active {
        background: #6366f1;
        color: #ffffff !important;
        box-shadow: 0 10px 20px -5px rgba(99, 102, 241, 0.4);
    }

    .pill-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 28px;
        height: 20px;
        padding: 0 8px;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 800;
        background: #edf2f7;
        color: #6366f1;
        margin-left: 10px;
        transition: all 0.3s ease;
    }

    .pill-item.active .pill-badge {
        background: #ffffff;
        color: #6366f1;
    }

    .stat-card {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .stat-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.2) !important;
    }

    .icon-glass {
        width: 52px;
        height: 52px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.1);
    }

    .filter-toolbar {
        border: 1px solid rgba(0, 0, 0, 0.03);
    }

    .filter-toolbar .form-control:focus,
    .filter-toolbar .form-select:focus {
        background: #ffffff !important;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1) !important;
        border: 1px solid rgba(99, 102, 241, 0.2) !important;
    }

    .hover-scale-sm {
        transition: all 0.3s ease;
    }

    .hover-scale-sm:hover {
        transform: scale(1.03);
    }

    .status-badge {
        font-size: 0.7rem;
        font-weight: 800;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }

    .hover-scale {
        transition: transform 0.2s ease;
    }

    .hover-scale:hover {
        transform: scale(1.02);
    }

    .media-preview-container {
        display: flex;
        gap: 12px;
        overflow-x: auto;
        padding-bottom: 8px;
    }

    .scrollbar-none::-webkit-scrollbar {
        display: none;
    }

    .scrollbar-none {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .media-preview-item {
        flex-shrink: 0;
        overflow: hidden;
        position: relative;
        background: #f8f9fa;
        transition: all 0.3s ease;
    }

    .media-preview-item:hover {
        transform: scale(1.02);
        z-index: 10;
    }

    .media-preview-item img,
    .media-preview-item video {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .glass-pill-dark {
        background: rgba(0, 0, 0, 0.03);
        border: 1px solid rgba(0, 0, 0, 0.05);
        backdrop-filter: blur(5px);
        transition: all 0.2s ease;
    }

    .glass-pill-dark:hover {
        background: rgba(0, 0, 0, 0.06);
        color: inherit;
    }

    .bg-primary-subtle {
        background-color: rgba(99, 102, 241, 0.08) !important;
    }

    .hover-text-primary:hover {
        color: #6366f1 !important;
    }

    .hover-text-primary:hover span {
        color: #6366f1 !important;
    }

    .media-preview-item .download-btn {
        position: absolute;
        top: 8px;
        right: 8px;
        width: 28px;
        height: 28px;
        background: rgba(0, 0, 0, 0.5);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: all 0.2s ease;
        text-decoration: none;
        backdrop-filter: blur(4px);
    }

    .media-preview-item:hover .download-btn {
        opacity: 1;
    }
</style>

<?= $this->endSection(); ?>