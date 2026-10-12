<?= $this->extend('admin/includes/header'); ?>
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
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
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
            </div>
        </div>
    </div>
</main>

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
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer pf-modal-footer px-0 pb-0 mt-4">
                        <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" id="submitBtn" class="pf-btn-primary px-4">
                            <span class="material-symbols-outlined" style="font-size: 18px;">save</span> Save Channel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->include('admin/footer') ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var channelModalEl = document.getElementById('channelModal');
        if (channelModalEl && typeof bootstrap !== 'undefined') {
            window.channelModal = new bootstrap.Modal(channelModalEl);
        }
    });

    function openCreateModal() {
        document.getElementById('channelForm').reset();
        document.getElementById('channel_id').value = '';
        document.getElementById('channelModalLabel').innerText = 'Create Channel';
        document.getElementById('submitBtn').innerHTML = '<span class="material-symbols-outlined" style="font-size: 18px;">add_circle</span> Create Channel';
        document.getElementById('channelForm').action = '<?= base_url('community/addChannel') ?>';
        document.getElementById('iconPreview').classList.add('d-none');
        document.getElementById('uploadPlaceholder').classList.remove('d-none');
        document.getElementById('iconPreview').src = '';
        document.getElementById('modal_status').checked = true;
        document.getElementById('modal_is_private').checked = false;
        if (window.channelModal) window.channelModal.show();
    }

    function openEditModal(channel) {
        document.getElementById('channelForm').reset();
        document.getElementById('channel_id').value = channel.id;
        document.getElementById('modal_name').value = channel.name;
        document.getElementById('modal_handle').value = channel.handle || '';
        document.getElementById('modal_description').value = channel.description || '';
        document.getElementById('modal_status').checked = (channel.status == 1);
        document.getElementById('modal_is_private').checked = (channel.is_private == 1);
        document.getElementById('channelModalLabel').innerText = 'Edit Channel';
        document.getElementById('submitBtn').innerHTML = '<span class="material-symbols-outlined" style="font-size: 18px;">save</span> Update Channel';
        document.getElementById('channelForm').action = '<?= base_url('community/updateChannel') ?>/' + channel.id;

        var previewImg = document.getElementById('iconPreview');
        var placeholder = document.getElementById('uploadPlaceholder');
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
            var reader = new FileReader();
            reader.onload = function(e) {
                var preview = document.getElementById('iconPreview');
                preview.src = e.target.result;
                preview.classList.remove('d-none');
                document.getElementById('uploadPlaceholder').classList.add('d-none');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<?= $this->endSection(); ?>