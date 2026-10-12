<?= $this->extend('admin/includes/header'); ?>
<<<<<<< HEAD
<!--start content-->
<?= $this->section('content'); ?>

<!-- Google Font & Material Icons (already in header but included for standalone) -->
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

    /* Custom styles to match image aesthetics */
    .glass-pill {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .status-active {
        background-color: #e6f7f9;
        color: #26a69a;
        font-weight: 800;
        font-size: 0.7rem;
        letter-spacing: 0.05em;
        padding: 0.35rem 0.85rem;
        border-radius: 2rem;
        display: inline-block;
    }

    .status-inactive {
        background-color: #f0f3f6;
        color: #575c60;
        font-weight: 800;
        font-size: 0.7rem;
        letter-spacing: 0.05em;
        padding: 0.35rem 0.85rem;
        border-radius: 2rem;
        display: inline-block;
    }

    .rounded-4xl {
        border-radius: 2.5rem !important;
    }

    .rounded-3xl {
        border-radius: 1.5rem !important;
    }

    .icon-bg {
        width: 56px;
        height: 56px;
        background-color: #eef2ff;
        border-radius: 1.25rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.2s ease;
    }

    .table-hover-row:hover .icon-bg {
        transform: scale(1.05);
    }

    .btn-outline-icon {
        background: transparent;
        border: none;
        padding: 0.5rem;
        color: #9ca3af;
        transition: all 0.2s;
        border-radius: 0.75rem;
    }

    .btn-outline-icon:hover {
        background-color: #f8fafc;
        color: #1f2937;
    }

    .btn-outline-icon.danger:hover {
        color: #ef4444;
        background-color: #fee2e2;
    }

    .gradient-card {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    }

    .upload-area {
        width: 96px;
        height: 96px;
        border: 2px dashed #e5e7eb;
        border-radius: 1rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        overflow: hidden;
        position: relative;
    }

    .upload-area:hover {
        background-color: #f9fafb;
        border-color: #493ee6;
    }

    .upload-area img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        position: absolute;
        top: 0;
        left: 0;
    }
</style>

<main class="bg-surface" style="background-color: #f3f7fb;">
    <div class="position-relative iq-banner">
        <?= $this->include('admin/nav_bar') ?>
    </div>
    <div class="container-fluid py-4 px-4 " style="margin-top:-80px ;">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-4 mb-2">
            <div>
                <span class="text-primary text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 0.2em;">Management</span>
                <h3 class=" fw-bold text-dark mt-2" style="font-weight: 800;">Channel Directory</h3>
                <p class="text-secondary fs-5">Curate and manage your pet community spaces.</p>
            </div>
            <button onclick="openCreateModal()" class="btn btn-light rounded-pill px-5 py-3 shadow-sm d-flex align-items-center gap-2" style="font-weight: 700; background: white; color: #493ee6;">
                <span class="material-symbols-outlined">add</span> Create New Channel
            </button>
        </div>

        <div class="row g-4 mb-3">
            <div class="col-lg-8">
                <div class="gradient-card rounded-4xl p-3 px-5 text-white position-relative overflow-hidden shadow-lg h-100">
                    <div class="position-relative z-1">
                        <p class="text-white- fs-5 fw-medium mb-2">Total Engagement</p>
                        <h4 class="fw-black text-white mb-4" style="font-size: 2rem;"><?= number_format($total_engagement ?? 0) ?></h4>
                        <div class="glass-pill d-inline-flex align-items-center gap-2 px-4 py-2 rounded-pill fw-bold">
                            <span class="material-symbols-outlined fs-6">trending_up</span>
                            <span>+12% this month</span>
                        </div>
                    </div>
                    <div class="position-absolute bottom-0 end-0 opacity-25">
                        <div class="bg-white rounded-circle" style="width: 200px; height: 200px; filter: blur(40px);"></div>
                        <div class="bg-white rounded-circle" style="width: 280px; height: 280px; filter: blur(60px); margin-top: -100px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="bg-white rounded-4xl p-3 px-5 shadow-sm border-0 h-100 d-flex flex-column justify-content-between">
                    <div>
                        <p class="text-muted fs-5 fw-medium mb-1">Active Channels</p>
                        <h2 class=" fw-black text-dark" style="font-size: 2rem;"><?= count($channels) ?></h2>
                    </div>
                    <div class="mt-4">
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 rounded-4xl shadow-sm overflow-hidden">
            <div class="card-header bg-white border-b border-gray-50 px-4 py-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="w-1.5 h-8 bg-primary rounded-pill shadow-sm shadow-primary/20"></div>
                    <div>
                        <h4 class="card-title fw-black text-dark mb-0 fs-4 tracking-tighter">Active Channels</h4>
                        <p class="text-muted small fw-bold text-uppercase tracking-widest mb-0" style="font-size: 0.65rem; opacity: 0.6;">Community Hub • Live Status</p>
                    </div>
                </div>

                <div class="d-flex flex-wrap align-items-center gap-3">
                    <form action="<?= base_url('community/channels') ?>" method="GET" class="flex-grow-1 flex-md-grow-0">
                        <div class="input-group  border border-gray-100 rounded-pill overflow-hidden bg-white group transition-all" style="max-width: 320px;">
                            <span class="input-group-text bg-white border-0 ps-4">
                                <span class="material-symbols-outlined text-muted fs-5">search</span>
                            </span>
                            <input type="text" name="search"
                                class="form-control border-0 py-2 text-sm font-medium focus:ring-0 placeholder:text-gray-300"
                                placeholder="Search by name or handle..."
                                value="<?= esc($request->getVar('search')) ?>">
                            <button class="btn btn-primary px-4 fw-bold text-xs uppercase tracking-widest m-0" type="submit">Seek</button>
                        </div>
                    </form>

                    <div class="d-flex gap-2">
                        <button class="btn btn-white bg-white border border-gray-100 shadow-sm rounded-2xl p-2.5 text-muted hover:text-primary hover:border-primary/20 transition-all group" title="Filter List">
                            <span class="material-symbols-outlined fs-5 group-hover:scale-110 transition-transform">filter_list</span>
                        </button>
                        <button class="btn btn-white bg-white border border-gray-100 shadow-sm rounded-2xl p-2.5 text-muted hover:text-primary hover:border-primary/20 transition-all group" title="Sort Channels">
                            <span class="material-symbols-outlined fs-5 group-hover:scale-110 transition-transform">sort</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="p-3 fw-bold border-0 fs-6 text-black" style="background: #c6d2ff !important;">Channel Name</th>
                                <th class="p-3 fw-bold border-0 fs-6 text-black" style="background: #c6d2ff !important;">Description</th>
                                <th class="p-3 fw-bold border-0 fs-6 text-black" style="background: #c6d2ff !important;">Member Count</th>
                                <th class="p-3 fw-bold border-0 text-center fs-6 text-black" style="background: #c6d2ff !important;">Status</th>
                                <th class="p-3 fw-bold border-0 text-center fs-6 text-black" style="background: #c6d2ff !important;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($channels as $channel): ?>
                                <tr class="table-hover-row">
                                    <td class="px-3 py-2">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="icon-bg">
                                                <?php if (!empty($channel['icon']) && $channel['icon'] !== 'default_icon.png'): ?>
                                                    <img src="<?= base_url('public/uploads/community/icons/' . $channel['icon']) ?>" class="w-100 h-100 object-fit-cover rounded-3">
                                                <?php else: ?>
                                                    <span class="material-symbols-outlined fs-2" style="color: #493ee6;">pets</span>
                                                <?php endif; ?>
                                            </div>
                                            <div>
                                                <p class="fw-bold text-dark fs-5 mb-0"><?= esc($channel['name']) ?></p>
                                                <small class="text-muted"><?= esc($channel['handle'] ?? '#channel') ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-3 py-2">
                                        <p class="text-secondary mb-0" style="max-width: 280px;"><?= esc(substr($channel['description'], 0, 50)) . "....." ?></p>
                                    </td>
                                    <td class="px-3 py-2">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="fw-bold fs-5 text-dark"><?= number_format($channel['member_count'] ?? 0) ?></span>
                                            <span class="text-primary small fw-bold">+0%</span>
                                        </div>
                                    </td>
                                    <td class="px-3 py-2 text-center">
                                        <span class="<?= ($channel['status'] ?? 1) == 1 ? 'status-active' : 'status-inactive' ?>">
                                            <?= ($channel['status'] ?? 1) == 1 ? 'Active' : 'Inactive' ?>
                                        </span>
                                    </td>
                                    <td class="px-3 py-2">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="<?= base_url('community/channel/' . $channel['id']) ?>" class="btn-outline-icon" title="View">
                                                <span class="material-symbols-outlined">visibility</span>
                                            </a>
                                            <button onclick="openEditModal(<?= htmlspecialchars(json_encode($channel), ENT_QUOTES, 'UTF-8') ?>)" class="btn-outline-icon" title="Edit">
                                                <span class="material-symbols-outlined">edit</span>
                                            </button>
                                            <a href="<?= base_url('community/deleteChannel/' . $channel['id']) ?>" onclick="return confirm('Are you sure?')" class="btn-outline-icon danger" title="Delete">
                                                <span class="material-symbols-outlined">delete</span>
=======
<?= $this->section('content'); ?>

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
                        <span class="material-symbols-outlined" style="font-size: 28px;">forum</span>
                    </div>
                    <div>
                        <h1 class="pf-hero-title mb-1">Channel Directory</h1>
                        <p class="pf-hero-subtitle">Curate, organize and manage active pet community spaces across the network.</p>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <a href="<?= base_url('community/posts') ?>" class="pf-btn-glass">
                        <span class="material-symbols-outlined">dynamic_feed</span> Community Feed
                    </a>
                    <button onclick="openCreateModal()" class="pf-btn-hero-cta">
                        <span class="material-symbols-outlined">add_circle</span> Create New Channel
                    </button>
                </div>
            </div>
        </div>

        <!-- Metric Cards -->
        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-lg-3">
                <div class="pf-stat-card">
                    <div class="pf-stat-header">
                        <div class="pf-stat-icon-wrapper indigo">
                            <span class="material-symbols-outlined">groups</span>
                        </div>
                        <span class="pf-stat-badge emerald">Live</span>
                    </div>
                    <div>
                        <div class="pf-stat-label">Active Channels</div>
                        <div class="pf-stat-value"><?= count($channels) ?></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="pf-stat-card">
                    <div class="pf-stat-header">
                        <div class="pf-stat-icon-wrapper emerald">
                            <span class="material-symbols-outlined">trending_up</span>
                        </div>
                        <span class="pf-stat-badge emerald">+12% MoM</span>
                    </div>
                    <div>
                        <div class="pf-stat-label">Total Engagement</div>
                        <div class="pf-stat-value"><?= number_format($total_engagement ?? 0) ?></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="pf-stat-card">
                    <div class="pf-stat-header">
                        <div class="pf-stat-icon-wrapper sky">
                            <span class="material-symbols-outlined">public</span>
                        </div>
                        <span class="pf-stat-badge sky">Audience</span>
                    </div>
                    <div>
                        <div class="pf-stat-label">Total Members</div>
                        <div class="pf-stat-value">
                            <?php
                            $sumMembers = 0;
                            foreach ($channels as $c) {
                                $sumMembers += ($c['member_count'] ?? 0);
                            }
                            echo number_format($sumMembers);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="pf-stat-card">
                    <div class="pf-stat-header">
                        <div class="pf-stat-icon-wrapper amber">
                            <span class="material-symbols-outlined">verified</span>
                        </div>
                        <span class="pf-stat-badge amber">Official</span>
                    </div>
                    <div>
                        <div class="pf-stat-label">Moderation Hub</div>
                        <div class="pf-stat-value">100% Secure</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="pf-filter-bar">
            <form action="<?= base_url('community/channels') ?>" method="GET" class="d-flex align-items-center gap-2 flex-grow-1 flex-wrap">
                <div class="pf-search-box">
                    <span class="material-symbols-outlined">search</span>
                    <input type="text" name="search" class="pf-search-input" placeholder="Search channels by name, handle, or description..." value="<?= esc($request->getVar('search')) ?>">
                </div>
                <button type="submit" class="pf-btn-primary">
                    <span class="material-symbols-outlined" style="font-size: 18px;">tune</span> Filter
                </button>
                <?php if ($request->getVar('search')): ?>
                    <a href="<?= base_url('community/channels') ?>" class="pf-btn-secondary">
                        <span class="material-symbols-outlined" style="font-size: 18px;">clear</span> Reset
                    </a>
                <?php endif; ?>
            </form>

            <div class="d-flex align-items-center gap-2 ms-auto">
                <span class="text-muted small fw-bold">Total: <span class="text-dark"><?= count($channels) ?> channels</span></span>
            </div>
        </div>

        <!-- Channel Directory List -->
        <div class="pf-table-card mb-4">
            <div class="table-responsive">
                <table class="table pf-table align-middle">
                    <thead>
                        <tr>
                            <th style="min-width: 260px;">Channel Space</th>
                            <th>Description</th>
                            <th class="text-center" style="min-width: 140px;">Community Size</th>
                            <th class="text-center">Visibility</th>
                            <th class="text-center">Status</th>
                            <th class="text-end" style="min-width: 130px; padding-right: 1.5rem;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($channels)): ?>
                            <?php foreach ($channels as $channel): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="pf-channel-icon">
                                                <?php if (!empty($channel['icon']) && $channel['icon'] !== 'default_icon.png'): ?>
                                                    <img src="<?= base_url('public/uploads/community/icons/' . $channel['icon']) ?>" alt="<?= esc($channel['name']) ?>">
                                                <?php else: ?>
                                                    <span class="material-symbols-outlined" style="font-size: 26px;">pets</span>
                                                <?php endif; ?>
                                            </div>
                                            <div>
                                                <a href="<?= base_url('community/channel/' . $channel['id']) ?>" class="fw-bold text-dark text-decoration-none d-block fs-6 mb-0 hover-text-primary">
                                                    <?= esc($channel['name']) ?>
                                                </a>
                                                <small class="text-muted fw-semibold"><?= esc($channel['handle'] ?? '#channel') ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-secondary mb-0 text-truncate" style="max-width: 320px;" title="<?= esc($channel['description']) ?>">
                                            <?= esc($channel['description']) ?: '<span class="text-muted fst-italic">No description provided</span>' ?>
                                        </p>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-inline-flex align-items-center gap-1.5 px-3 py-1 bg-light rounded-pill border">
                                            <span class="material-symbols-outlined text-primary" style="font-size: 16px;">group</span>
                                            <span class="fw-bold text-dark fs-6"><?= number_format($channel['member_count'] ?? 0) ?></span>
                                            <small class="text-muted">members</small>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <?php if (!empty($channel['is_private']) && $channel['is_private'] == 1): ?>
                                            <span class="badge bg-secondary-subtle text-secondary border rounded-pill px-2.5 py-1 fw-bold" style="font-size: 0.72rem;">
                                                <span class="material-symbols-outlined align-middle" style="font-size: 14px;">lock</span> Private
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-success-subtle text-success border rounded-pill px-2.5 py-1 fw-bold" style="font-size: 0.72rem;">
                                                <span class="material-symbols-outlined align-middle" style="font-size: 14px;">public</span> Public
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <span class="pf-badge-status <?= ($channel['status'] ?? 1) == 1 ? 'active' : 'inactive' ?>">
                                            <span class="material-symbols-outlined" style="font-size: 14px;">
                                                <?= ($channel['status'] ?? 1) == 1 ? 'check_circle' : 'pause_circle' ?>
                                            </span>
                                            <?= ($channel['status'] ?? 1) == 1 ? 'Active' : 'Inactive' ?>
                                        </span>
                                    </td>
                                    <td class="text-end" style="padding-right: 1.5rem;">
                                        <div class="pf-action-btn-group justify-content-end">
                                            <a href="<?= base_url('community/channel/' . $channel['id']) ?>" class="pf-btn-icon" title="View Channel Feed">
                                                <span class="material-symbols-outlined" style="font-size: 18px;">visibility</span>
                                            </a>
                                            <button onclick="openEditModal(<?= htmlspecialchars(json_encode($channel), ENT_QUOTES, 'UTF-8') ?>)" class="pf-btn-icon" title="Edit Channel Settings">
                                                <span class="material-symbols-outlined" style="font-size: 18px;">edit</span>
                                            </button>
                                            <a href="<?= base_url('community/deleteChannel/' . $channel['id']) ?>" onclick="return confirm('Are you sure you want to permanently delete this channel?')" class="pf-btn-icon danger" title="Delete Channel">
                                                <span class="material-symbols-outlined" style="font-size: 18px;">delete</span>
>>>>>>> origin/main
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
<<<<<<< HEAD
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white border-0 px-5 py-4 d-flex flex-wrap justify-content-between align-items-center">
                <p class="text-muted small mb-0">Showing <span class="fw-bold text-dark"><?= count($channels) ?></span> of <span class="fw-bold text-dark"><?= count($channels) ?></span> channels</p>
                <nav aria-label="Page navigation">
                    <ul class="pagination mb-0">
                        <li class="page-item disabled">
                            <span class="page-link rounded-circle border-0 text-muted"><span class="material-symbols-outlined">chevron_left</span></span>
                        </li>
                        <li class="page-item active"><span class="page-link rounded-circle bg-primary border-0 shadow-sm">1</span></li>
                    </ul>
                </nav>
=======
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="p-0">
                                    <div class="pf-empty-state">
                                        <div class="pf-empty-icon">
                                            <span class="material-symbols-outlined">forum</span>
                                        </div>
                                        <h4 class="fw-bold text-dark mb-1">No Channels Found</h4>
                                        <p class="text-muted mb-3">Try adjusting your search criteria or create your first channel.</p>
                                        <button onclick="openCreateModal()" class="pf-btn-primary">
                                            <span class="material-symbols-outlined" style="font-size: 18px;">add</span> Create Channel
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
>>>>>>> origin/main
            </div>
        </div>
    </div>
</main>

<<<<<<< HEAD
<!-- Bootstrap Modal for Channel Create/Edit -->
<div class="modal fade" id="channelModal" tabindex="-1" aria-labelledby="channelModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4xl border-0 shadow-lg" style="margin: 0px; !important">
            <div class="modal-header border-0 p-3 position-relative">
                <h5 class="modal-title fw-black fs-2 text-dark" id="channelModalLabel">Create Channel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-3">
                <form id="channelForm" action="<?= base_url('community/addChannel') ?>" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="channel_id">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label text-uppercase fw-bold small text-muted mb-2">Channel Name</label>
                                <input type="text" name="name" id="modal_name" class="form-control bg-light border-0 rounded-3 py-3 px-4" placeholder="Puppy Training 101" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label text-uppercase fw-bold small text-muted mb-2">Channel Handle</label>
                                <input type="text" name="handle" id="modal_handle" class="form-control bg-light border-0 rounded-3 py-3 px-4" placeholder="#puppy-basics">
                            </div>
                            <div class="mb-4">
                                <label class="form-label text-uppercase fw-bold small text-muted mb-2">Description</label>
                                <textarea name="description" id="modal_description" rows="4" class="form-control bg-light border-0 rounded-3 py-3 px-4" placeholder="Briefly describe the channel focus..."></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label text-uppercase fw-bold small text-muted mb-2">Channel Icon</label>
                                <div class="d-flex align-items-start gap-3">
                                    <div class="upload-area" onclick="document.getElementById('iconFile').click()">
                                        <input type="file" name="icon" id="iconFile" class="d-none" onchange="previewIcon(this)">
                                        <img id="iconPreview" class="d-none w-100 h-100 object-fit-cover">
                                        <div id="uploadPlaceholder" class="d-flex flex-column align-items-center">
                                            <span class="material-symbols-outlined text-secondary">upload</span>
                                            <small class="text-muted text-uppercase fw-bold">Upload</small>
                                        </div>
                                    </div>
                                    <small class="text-muted" style="max-width: 160px;">Recommended size: 512x512px. SVG or PNG preferred.</small>
                                </div>
                            </div>
                            <div class="bg-light p-4 rounded-3">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <p class="fw-bold text-dark mb-0">Active Status</p>
                                        <small class="text-muted">Visible to all users immediately</small>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="status" id="modal_status" value="1" checked style="background-color: #493ee6; border-color: #493ee6;">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="fw-bold text-dark mb-0">Private Access</p>
                                        <small class="text-muted">Invite only channel</small>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_private" id="modal_is_private" value="1">
                                    </div>
=======
<!-- Bootstrap Modal for Channel Create / Edit -->
<div class="modal fade" id="channelModal" tabindex="-1" aria-labelledby="channelModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content pf-modal-content">
            <div class="modal-header pf-modal-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <div class="pf-stat-icon-wrapper indigo" style="width: 38px; height: 38px;">
                        <span class="material-symbols-outlined" style="font-size: 20px;">tag</span>
                    </div>
                    <h5 class="modal-title fw-bold text-dark mb-0 fs-5" id="channelModalLabel">Create Channel</h5>
                </div>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pf-modal-body">
                <form id="channelForm" action="<?= base_url('community/addChannel') ?>" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="channel_id">
                    <div class="row g-4">
                        <div class="col-md-7">
                            <div class="mb-3">
                                <label class="pf-form-label">Channel Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="modal_name" class="pf-form-control" placeholder="e.g. Puppy Training 101" required>
                            </div>
                            <div class="mb-3">
                                <label class="pf-form-label">Handle Identifier</label>
                                <div class="position-relative">
                                    <input type="text" name="handle" id="modal_handle" class="pf-form-control" placeholder="e.g. #puppy-basics">
                                </div>
                                <small class="text-muted">Users can search or tag this channel using the handle.</small>
                            </div>
                            <div class="mb-3">
                                <label class="pf-form-label">Channel Description</label>
                                <textarea name="description" id="modal_description" rows="3" class="pf-form-control" placeholder="Describe the topics, purpose and rules for this channel..."></textarea>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="mb-4">
                                <label class="pf-form-label">Channel Icon</label>
                                <div class="d-flex flex-column align-items-center p-3 border rounded-3 bg-light text-center">
                                    <div class="pf-channel-icon mb-2" style="width: 72px; height: 72px; border-radius: 20px; cursor: pointer;" onclick="document.getElementById('iconFile').click()" title="Click to upload icon">
                                        <img id="iconPreview" class="d-none w-100 h-100 object-fit-cover">
                                        <div id="uploadPlaceholder" class="d-flex flex-column align-items-center justify-content-center">
                                            <span class="material-symbols-outlined text-primary" style="font-size: 32px;">add_photo_alternate</span>
                                        </div>
                                    </div>
                                    <input type="file" name="icon" id="iconFile" class="d-none" accept="image/*" onchange="previewIcon(this)">
                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 mt-1" onclick="document.getElementById('iconFile').click()">
                                        Upload Icon
                                    </button>
                                    <small class="text-muted mt-2" style="font-size: 0.75rem;">Square PNG, JPG, or SVG. Max 2MB.</small>
                                </div>
                            </div>

                            <div class="p-3 border rounded-3 bg-light">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <label class="fw-bold text-dark mb-0 d-block fs-6">Active Status</label>
                                        <small class="text-muted">Live and visible in community feeds</small>
                                    </div>
                                    <label class="pf-toggle-switch">
                                        <input type="checkbox" name="status" id="modal_status" value="1" checked>
                                        <span class="pf-toggle-slider"></span>
                                    </label>
                                </div>
                                <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                    <div>
                                        <label class="fw-bold text-dark mb-0 d-block fs-6">Private Channel</label>
                                        <small class="text-muted">Invite or approval required</small>
                                    </div>
                                    <label class="pf-toggle-switch">
                                        <input type="checkbox" name="is_private" id="modal_is_private" value="1">
                                        <span class="pf-toggle-slider"></span>
                                    </label>
>>>>>>> origin/main
                                </div>
                            </div>
                        </div>
                    </div>
<<<<<<< HEAD
                    <div class="d-flex gap-3 mt-5 pt-3">
                        <button type="button" class="btn btn-light flex-fill py-3 rounded-pill fw-bold" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" id="submitBtn" class="btn btn-primary flex-fill py-3 rounded-pill fw-bold shadow-sm">Create Channel</button>
=======
                    <div class="modal-footer pf-modal-footer px-0 pb-0 mt-4">
                        <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" id="submitBtn" class="pf-btn-primary px-4">
                            <span class="material-symbols-outlined" style="font-size: 18px;">save</span> Save Channel
                        </button>
>>>>>>> origin/main
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<<<<<<< HEAD
=======

>>>>>>> origin/main
<?= $this->include('admin/footer') ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
<<<<<<< HEAD
        // Ensure Bootstrap JS is loaded
        var channelModalEl = document.getElementById('channelModal');
        if (channelModalEl) {
            var channelModal = new bootstrap.Modal(channelModalEl);
            window.channelModal = channelModal; // Make it globally accessible for the onclick handlers
=======
        var channelModalEl = document.getElementById('channelModal');
        if (channelModalEl && typeof bootstrap !== 'undefined') {
            window.channelModal = new bootstrap.Modal(channelModalEl);
>>>>>>> origin/main
        }
    });

    function openCreateModal() {
<<<<<<< HEAD
        // Reset form
        document.getElementById('channelForm').reset();
        document.getElementById('channel_id').value = '';
        document.getElementById('channelModalLabel').innerText = 'Create Channel';
        document.getElementById('submitBtn').innerText = 'Create Channel';
        document.getElementById('channelForm').action = '<?= base_url('community/addChannel') ?>';
        // Reset icon preview
        document.getElementById('iconPreview').classList.add('d-none');
        document.getElementById('uploadPlaceholder').classList.remove('d-none');
        document.getElementById('iconPreview').src = '';
        // Reset switches
=======
        document.getElementById('channelForm').reset();
        document.getElementById('channel_id').value = '';
        document.getElementById('channelModalLabel').innerText = 'Create Channel';
        document.getElementById('submitBtn').innerHTML = '<span class="material-symbols-outlined" style="font-size: 18px;">add_circle</span> Create Channel';
        document.getElementById('channelForm').action = '<?= base_url('community/addChannel') ?>';
        document.getElementById('iconPreview').classList.add('d-none');
        document.getElementById('uploadPlaceholder').classList.remove('d-none');
        document.getElementById('iconPreview').src = '';
>>>>>>> origin/main
        document.getElementById('modal_status').checked = true;
        document.getElementById('modal_is_private').checked = false;
        if (window.channelModal) window.channelModal.show();
    }

    function openEditModal(channel) {
        document.getElementById('channelForm').reset();
        document.getElementById('channel_id').value = channel.id;
        document.getElementById('modal_name').value = channel.name;
        document.getElementById('modal_handle').value = channel.handle || '';
<<<<<<< HEAD
        document.getElementById('modal_description').value = channel.description;
        document.getElementById('modal_status').checked = (channel.status == 1);
        document.getElementById('modal_is_private').checked = (channel.is_private == 1);
        document.getElementById('channelModalLabel').innerText = 'Edit Channel';
        document.getElementById('submitBtn').innerText = 'Update Channel';
        document.getElementById('channelForm').action = '<?= base_url('community/updateChannel') ?>/' + channel.id;

        // Handle icon preview
        const previewImg = document.getElementById('iconPreview');
        const placeholder = document.getElementById('uploadPlaceholder');
=======
        document.getElementById('modal_description').value = channel.description || '';
        document.getElementById('modal_status').checked = (channel.status == 1);
        document.getElementById('modal_is_private').checked = (channel.is_private == 1);
        document.getElementById('channelModalLabel').innerText = 'Edit Channel';
        document.getElementById('submitBtn').innerHTML = '<span class="material-symbols-outlined" style="font-size: 18px;">save</span> Update Channel';
        document.getElementById('channelForm').action = '<?= base_url('community/updateChannel') ?>/' + channel.id;

        var previewImg = document.getElementById('iconPreview');
        var placeholder = document.getElementById('uploadPlaceholder');
>>>>>>> origin/main
        if (channel.icon && channel.icon !== 'default_icon.png') {
            previewImg.src = '<?= base_url('public/uploads/community/icons') ?>/' + channel.icon;
            previewImg.classList.remove('d-none');
            placeholder.classList.add('d-none');
        } else {
            previewImg.classList.add('d-none');
            placeholder.classList.remove('d-none');
        }
        if (window.channelModal) window.channelModal.show();
    }

    function previewIcon(input) {
        if (input.files && input.files[0]) {
<<<<<<< HEAD
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('iconPreview');
=======
            var reader = new FileReader();
            reader.onload = function(e) {
                var preview = document.getElementById('iconPreview');
>>>>>>> origin/main
                preview.src = e.target.result;
                preview.classList.remove('d-none');
                document.getElementById('uploadPlaceholder').classList.add('d-none');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<?= $this->endSection(); ?>