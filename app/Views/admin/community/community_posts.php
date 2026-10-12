<?= $this->extend('admin/includes/header'); ?>
<?= $this->section('content'); ?>

<<<<<<< HEAD
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

                    <div class="pill-nav-container d-inline-flex bg-white p-1 rounded-pill shadow-sm mt-4 flex-wrap gap-1">
                        <a href="<?= base_url('community/posts?status=admin') ?>" class="pill-item px-3 px-md-4 py-2 text-decoration-none d-flex align-items-center gap-2 <?= $status == 'admin' ? 'active' : '' ?>">
                            <span class="material-symbols-outlined fs-5">admin_panel_settings</span>
                            <span>Admin Posts</span>
                            <span class="pill-badge"><?= $admin_posts_count ?></span>
                        </a>
                        <a href="<?= base_url('community/posts?status=user') ?>" class="pill-item px-3 px-md-4 py-2 text-decoration-none d-flex align-items-center gap-2 <?= ($status == 'user' || $status == 'client') ? 'active' : '' ?>">
                            <span class="material-symbols-outlined fs-5">person</span>
                            <span>Client Posts</span>
                            <span class="pill-badge"><?= $user_posts_count ?></span>
                        </a>
                        <a href="<?= base_url('community/posts?status=provider') ?>" class="pill-item px-3 px-md-4 py-2 text-decoration-none d-flex align-items-center gap-2 <?= $status == 'provider' ? 'active' : '' ?>">
                            <span class="material-symbols-outlined fs-5">badge</span>
                            <span>Provider Posts</span>
                            <span class="pill-badge"><?= $provider_posts_count ?></span>
                        </a>
                        <a href="<?= base_url('community/posts?status=scheduled') ?>" class="pill-item px-3 px-md-4 py-2 text-decoration-none d-flex align-items-center gap-2 <?= $status == 'scheduled' ? 'active' : '' ?>">
                            <span class="material-symbols-outlined fs-5">schedule</span>
                            <span>Scheduled</span>
                            <span class="pill-badge"><?= $scheduled_posts_count ?></span>
                        </a>
                        <a href="<?= base_url('community/posts?status=questions') ?>" class="pill-item px-3 px-md-4 py-2 text-decoration-none d-flex align-items-center gap-2 <?= $status == 'questions' ? 'active' : '' ?>">
                            <span class="material-symbols-outlined fs-5">question_mark</span>
                            <span>Questions</span>
                            <span class="pill-badge"><?= $questions_count ?></span>
                        </a>
                    </div>
                </div>

                <div class="col-lg-5 mt-4 mt-lg-0">
                    <div class="d-flex flex-column flex-sm-row justify-content-lg-end gap-3">
                        <button class="btn btn-white text-primary bg-white rounded-pill px-4 py-3 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2 border-0" onclick="window.location.href='<?= base_url('community/addPost') ?>'">
                            <span class="material-symbols-outlined">add</span> Create New Post
                        </button>
                    </div>
=======
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
>>>>>>> origin/main
                </div>
            </div>
        </div>

<<<<<<< HEAD
        <div class="row g-4 mb-3">
            <div class="col-sm-6 col-lg-3">
                <div class="card border-0 p-0 rounded-4xl stat-card shadow-lg h-100 position-relative overflow-hidden" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);">
                    <div class="position-absolute" style="width: 100px; height: 100px; background: rgba(255,255,255,0.1); border-radius: 50%; top: -30px; right: -30px;"></div>
                    <div class="card-body p-4 text-white position-relative">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="icon-glass">
                                <span class="material-symbols-outlined fs-2">dynamic_feed</span>
                            </div>
                            <span class="badge bg-white/20 text-white rounded-pill px-3 py-1 fw-bold">Live</span>
                        </div>
                        <h5 class="text-white small fw-bold text-uppercase tracking-wider mb-2">Total Posts</h5>
                        <h2 class="display-6 text-white fw-bold mb-0"><?= number_format($stats['total_posts'] ?? 0) ?></h2>
=======
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
>>>>>>> origin/main
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
<<<<<<< HEAD
                <div class="card border-0 p-0 rounded-4xl stat-card shadow-lg h-100 position-relative overflow-hidden" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                    <div class="position-absolute" style="width: 100px; height: 100px; background: rgba(255,255,255,0.1); border-radius: 50%; bottom: -30px; left: -30px;"></div>
                    <div class="card-body p-4 text-white position-relative">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="icon-glass">
                                <span class="material-symbols-outlined fs-2">pets</span>
                            </div>
                            <span class="badge bg-white/20 text-white rounded-pill px-3 py-1 fw-bold">Clients</span>
                        </div>
                        <h5 class="text-white small fw-bold text-uppercase tracking-wider mb-2">Active Clients</h5>
                        <h2 class="display-6 text-white fw-bold mb-0"><?= number_format($stats['active_users'] ?? 0) ?></h2>
=======
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
>>>>>>> origin/main
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
<<<<<<< HEAD
                <div class="card border-0 p-0 rounded-4xl stat-card shadow-lg h-100 position-relative overflow-hidden" style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);">
                    <div class="position-absolute" style="width:120px; height: 120px; background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0) 70%); top: -40px; right: -20px;"></div>
                    <div class="card-body p-4 text-white position-relative">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="icon-glass">
                                <span class="material-symbols-outlined fs-2">badge</span>
                            </div>
                            <span class="badge bg-white/20 text-white rounded-pill px-3 py-1 fw-bold">Providers</span>
                        </div>
                        <h5 class="text-white small fw-bold text-uppercase tracking-wider mb-2">Active Providers</h5>
                        <h2 class="display-6 text-white fw-bold mb-0"><?= number_format($stats['active_providers'] ?? 0) ?></h2>
=======
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
>>>>>>> origin/main
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
<<<<<<< HEAD
                <div class="card border-0 p-0 rounded-4xl stat-card shadow-lg h-100 position-relative overflow-hidden" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                    <div class="position-absolute shadow-lg" style="width: 80px; height: 80px; background: rgba(255,255,255,0.05); border-radius: 20px; bottom: 10px; right: -20px; transform: rotate(30deg);"></div>
                    <div class="card-body p-4 text-white position-relative">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="icon-glass">
                                <span class="material-symbols-outlined fs-2">favorite</span>
                            </div>
                            <span class="badge bg-white/20 text-white rounded-pill px-3 py-1 fw-bold">Hot</span>
                        </div>
                        <h5 class="text-white small fw-bold text-uppercase tracking-wider mb-2">Total Interactions</h5>
                        <h2 class="display-6 text-white fw-bold mb-0">
                            <?php
                            $interactions = $stats['total_interactions'] ?? 0;
                            echo ($interactions >= 1000) ? number_format($interactions / 1000, 1) . 'k' : $interactions;
                            ?>
                        </h2>
=======
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
>>>>>>> origin/main
                    </div>
                </div>
            </div>
        </div>
<<<<<<< HEAD
        <form action="<?= base_url('community/posts') ?>" method="GET" class="mb-4 px-1">
            <input type="hidden" name="status" value="<?= $status ?>">
            <div class="filter-toolbar p-2 bg-white rounded-pill shadow-sm d-flex align-items-center gap-2">
                <!-- Search Input Group -->
                <div class="flex-grow-1 position-relative d-none d-md-block">
                    <span class="material-symbols-outlined position-absolute top-50 translate-middle-y ms-4 text-muted" style="z-index: 5;">search</span>
                    <input type="text" name="search" class="form-control border-0 bg-light rounded-pill ps-5 py-3 fw-medium" placeholder="Search posts, authors, topics..." value="<?= esc($request->getVar('search')) ?>" style="padding-left: 3.5rem !important;">
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
                    <?php
                    if ($status == 'admin') echo 'Official Admin Contributions';
                    elseif ($status == 'provider') echo 'Service Provider Community Feed';
                    elseif ($status == 'user' || $status == 'client') echo 'Pet Parent / Client Community Feed';
                    elseif ($status == 'questions') echo 'Community Questions & Advice';
                    elseif ($status == 'scheduled') echo 'Scheduled Posts Queue';
                    else echo 'Community Activity Feed';
                    ?>
                </h4>
                <div class="ms-2 badge bg-primary-subtle text-primary rounded-pill px-3 py-2 fw-black shadow-sm" style="font-size: 0.75rem;">
                    <?= number_format(count($posts)) ?> Posts Total
                </div>
            </div>
            <div class="d-none d-md-flex align-items-center gap-2 bg-white px-3 py-2 rounded-pill shadow-sm">
                <span class="material-symbols-outlined text-primary fs-5">
                    <?= ($status == 'admin') ? 'shield_person' : (($status == 'provider') ? 'badge' : 'pets') ?>
                </span>
                <small class="text-muted fw-bold text-uppercase tracking-wider" style="font-size: 0.65rem; letter-spacing: 0.1em;">
                    <?= ($status == 'admin') ? 'Verified Staff Feed' : (($status == 'provider') ? 'Verified Provider Feed' : 'Client Community Feed') ?>
                </small>
            </div>
        </div>

        <div class="row g-3">
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
                <div class="col-12">
                    <div class="card border-0 rounded-4xl shadow-sm overflow-hidden bg-white mb-0">
                        <div class="card-body p-3">
                            <div class="d-flex gap-4">
                                <!-- User Avatar Column -->
                                <div class="d-none d-sm-block">
                                    <div class="position-relative">
                                        <img src="<?= $avatarUrl ?>" class="rounded-circle shadow-sm border border-4 border-light" width="80" height="80" style="object-fit: cover;" onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($post['name'] ?? 'User') ?>&background=random'">
                                        <div class="position-absolute bottom-0 end-0 bg-success border border-2 border-white rounded-circle" style="width: 18px; height: 18px;"></div>
                                    </div>
                                </div>

                                <!-- Post Content Column -->
                                <div class="flex-grow-1">
                                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-2">
                                        <div class="d-flex align-items-center gap-3">
                                            <!-- Mobile Avatar -->
                                            <img src="<?= $avatarUrl ?>" class="d-sm-none rounded-circle border border-2 border-light" width="48" height="48" style="object-fit: cover;" onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($post['name'] ?? 'User') ?>&background=random'">
                                            <div>
                                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                                    <h5 class="fw-bold text-dark mb-0"><?= esc($post['name']) ?></h5>
                                                    <?php if (($post['user_type'] ?? '') == 'admin'): ?>
                                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2.5 py-1 d-inline-flex align-items-center gap-1" style="font-size: 0.65rem; font-weight: 800;">
                                                            <span class="material-symbols-outlined fs-6" style="font-size: 13px;">shield_person</span> ADMIN
                                                        </span>
                                                    <?php elseif (($post['user_type'] ?? '') == 'provider'): ?>
                                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-1 d-inline-flex align-items-center gap-1" style="font-size: 0.65rem; font-weight: 800;">
                                                            <span class="material-symbols-outlined fs-6" style="font-size: 13px;">badge</span> PROVIDER
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1 d-inline-flex align-items-center gap-1" style="font-size: 0.65rem; font-weight: 800;">
                                                            <span class="material-symbols-outlined fs-6" style="font-size: 13px;">pets</span> CLIENT
                                                        </span>
                                                    <?php endif; ?>

                                                    <?php if (!empty($post['channel_name'])): ?>
                                                        <span class="badge bg-light text-muted border rounded-pill px-2 py-0.5" style="font-size: 0.65rem; font-weight: 600;">
                                                            #<?= esc($post['channel_name']) ?>
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                                <small class="text-muted d-flex align-items-center gap-1 mt-1 fw-medium">
                                                    <span class="material-symbols-outlined fs-6">schedule</span>
                                                    Posted: <?= date('M d, Y • h:i A', strtotime($post['created_at'])) ?>
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
                                                <?= (strlen($post['post_text'] ?? '') > 450) ? substr($post['post_text'], 0, 450) . '...' : ($post['post_text'] ?? '') ?>
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
            <h4 class="fw-bold mb-3">Last Hour Activity</h4>
            <div class="row g-3">
                <?php if (!empty($lastHourPosts)): ?>
                    <?php foreach ($lastHourPosts as $post): ?>
                        <?php
                        $hourAvatar = 'https://ui-avatars.com/api/?name=' . urlencode($post['name'] ?? 'User') . '&background=random';
                        if (!empty($post['profile'])) {
                            if (($post['user_type'] ?? '') == 'provider') {
                                $hourAvatar = 'https://beta.petsfolio.in/public/uploads/providers/' . $post['profile'];
                            } elseif (($post['user_type'] ?? '') == 'admin') {
                                $hourAvatar = base_url('public/uploads/employees/' . $post['profile']);
                            } else {
                                $hourAvatar = 'https://beta.petsfolio.in/public/uploads/users/' . $post['profile'];
                            }
                        }
                        ?>
                        <div class="col-12">
                            <div class="bg-white rounded-3xl p-3 shadow-sm border">
                                <div class="d-flex gap-3 align-items-center">
                                    <img src="<?= $hourAvatar ?>" class="rounded-circle" width="40" height="40" style="object-fit: cover;" onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($post['name'] ?? 'User') ?>&background=random'">
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center gap-2">
                                                <strong><?= esc($post['name']) ?></strong>
                                                <?php if (($post['user_type'] ?? '') == 'admin'): ?>
                                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2 py-0.5" style="font-size: 0.6rem; font-weight: 800;">ADMIN</span>
                                                <?php elseif (($post['user_type'] ?? '') == 'provider'): ?>
                                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 py-0.5" style="font-size: 0.6rem; font-weight: 800;">PROVIDER</span>
                                                <?php else: ?>
                                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-0.5" style="font-size: 0.6rem; font-weight: 800;">CLIENT</span>
                                                <?php endif; ?>
                                            </div>
                                            <small class="text-muted"><?= date('h:i A', strtotime($post['created_at'])) ?></small>
                                        </div>
                                        <p class="text-muted small mb-0"><?= esc(substr($post['post_text'] ?? '', 0, 100)) ?>...</p>
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
=======

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
>>>>>>> origin/main
        </div>
    </div>
</main>

<<<<<<< HEAD
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