<?= $this->extend('admin/includes/header'); ?>
<?= $this->section('content'); ?>

<link rel="stylesheet" href="<?= base_url('public/assets/css/community.css?v=2.1') ?>">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<main class="main-content community-wrapper">
    <div class="position-relative iq-banner">
        <?= $this->include('admin/nav_bar') ?>
    </div>

    <div class="container-fluid px-4 content-inner pb-0" id="page_layout" style="margin-top: -50px;">
        <form action="<?= isset($post) ? base_url('community/updatePost/' . $post['id']) : base_url('community/addPost') ?>" method="POST" enctype="multipart/form-data" id="postForm">
            <!-- Hero Banner -->
            <div class="pf-hero-banner">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4 pf-hero-content">
                    <div class="d-flex align-items-center gap-3">
                        <div class="pf-hero-icon-pill">
                            <span class="material-symbols-outlined" style="font-size: 28px;">edit_note</span>
                        </div>
                        <div>
                            <h1 class="pf-hero-title mb-1"><?= isset($post) ? 'Refine Community Post' : 'Compose Community Post' ?></h1>
                            <p class="pf-hero-subtitle">Publish verified announcements, advice, and updates across the Petsfolio community network.</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <a href="<?= base_url('community/posts') ?>" class="pf-btn-glass">
                            <span class="material-symbols-outlined">arrow_back</span> Cancel & Feed
                        </a>
                        <button type="button" id="previewBtn" class="pf-btn-glass">
                            <span class="material-symbols-outlined">visibility</span> Preview Card
                        </button>
                        <button type="submit" class="pf-btn-hero-cta">
                            <span class="material-symbols-outlined">send</span> <?= isset($post) ? 'Update Post' : 'Publish Now' ?>
                        </button>
                    </div>
                </div>
            </div>

            <div class="row g-4 pb-4">
                <!-- Left Column: Content & Media Assets -->
                <div class="col-lg-8">
                    <!-- Post Body Card -->
                    <div class="pf-form-card mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="material-symbols-outlined text-primary">subject</span>
                                <label class="pf-form-label mb-0 fs-6">Post Content <span class="text-danger">*</span></label>
                            </div>
                            <span id="charCounter" class="badge bg-light text-muted border rounded-pill px-3 py-1 fw-bold small">0 / 5000</span>
                        </div>

                        <div class="mb-2">
                            <textarea name="post_text" id="postText" rows="7" required
                                placeholder="Share updates, advice, pet care tips, or announcements with the community..."
                                class="pf-form-control pf-textarea"><?= htmlspecialchars($post['post_text'] ?? '') ?></textarea>
                        </div>
                        <small class="text-muted d-flex align-items-center gap-1">
                            <span class="material-symbols-outlined" style="font-size: 15px;">info</span>
                            Write engaging, clear copy. Line breaks and URLs will be automatically formatted for parents and partners.
                        </small>
                    </div>

                    <!-- Visual Assets Card -->
                    <div class="pf-form-card">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="material-symbols-outlined text-primary">photo_library</span>
                                <label class="pf-form-label mb-0 fs-6">Visual Media Assets</label>
                            </div>
                            <span class="badge bg-primary-subtle text-primary border rounded-pill px-3 py-1 fw-bold small">Images & Videos (Up to 10)</span>
                        </div>

                        <div class="d-flex flex-wrap gap-3 align-items-start mb-3" id="mediaPreviewArea">
                            <?php if (isset($media) && is_array($media)): ?>
                                <?php foreach ($media as $index => $m): ?>
                                    <div class="media-item existing-media position-relative rounded-3 overflow-hidden border shadow-sm" data-media-id="<?= $m['id'] ?? $index ?>" style="width: 110px; height: 110px; background: #0f172a;">
                                        <?php if (strpos($m['media_url'], '.mp4') !== false || strpos($m['media_url'], '.mov') !== false): ?>
                                            <video class="w-100 h-100 object-fit-cover" muted playsinline>
                                                <source src="<?= $m['media_url'] ?>" type="video/mp4">
                                            </video>
                                            <div class="position-absolute top-50 start-50 translate-middle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: rgba(0,0,0,0.5); border-radius: 50%;">
                                                <span class="material-symbols-outlined text-white" style="font-size: 18px;">play_arrow</span>
                                            </div>
                                        <?php else: ?>
                                            <img src="<?= $m['media_url'] ?>" class="w-100 h-100 object-fit-cover" alt="Media preview">
                                        <?php endif; ?>
                                        <button type="button" class="delete-media-btn position-absolute top-0 end-0 m-1 btn btn-danger btn-sm rounded-circle d-flex align-items-center justify-content-center p-0" style="width: 24px; height: 24px;" data-media-id="<?= $m['id'] ?>" title="Remove attachment">
                                            <span class="material-symbols-outlined text-white" style="font-size: 14px;">close</span>
                                        </button>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>

                            <!-- Upload Trigger Area -->
                            <label class="pf-dropzone d-flex flex-column align-items-center justify-content-center m-0" style="width: 110px; height: 110px; padding: 0.75rem;">
                                <input type="file" multiple accept="image/*,video/*" class="d-none" name="media[]" onchange="handleMediaSelect(this)">
                                <span class="material-symbols-outlined text-primary mb-1" style="font-size: 28px;">add_photo_alternate</span>
                                <span class="text-uppercase fw-bold text-muted" style="font-size: 0.68rem; letter-spacing: 0.05em;">Add Media</span>
                            </label>
                        </div>
                        <input type="hidden" name="deleted_media_ids" id="deletedMediaIds" value="">
                        <small class="text-muted">Supports JPG, PNG, WEBP, MP4, MOV. Max 50MB per video, 10MB per image.</small>
                    </div>
                </div>

                <!-- Right Column: Settings & Distribution -->
                <div class="col-lg-4">
                    <!-- Channel & Distribution Card -->
                    <div class="pf-form-card mb-4">
                        <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom fs-6">Distribution & Taxonomy</h6>

                        <div class="mb-3">
                            <label class="pf-form-label">Target Channel <span class="text-danger">*</span></label>
                            <select name="channel_id" id="channelSelect" class="pf-select w-100" required>
                                <?php foreach ($channels as $channel): ?>
                                    <option value="<?= $channel['id'] ?>" <?= (isset($post) && $post['channel_id'] == $channel['id']) ? 'selected' : '' ?>>
                                        #<?= htmlspecialchars($channel['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="pf-form-label">Target Audience</label>
                            <select name="visibility" id="visibilitySelect" class="pf-select w-100">
                                <option value="public" <?= (isset($post) && ($post['visibility'] ?? '') == 'public') ? 'selected' : '' ?>>🌐 Entire Community (All Users & Partners)</option>
                                <option value="user" <?= (isset($post) && in_array($post['visibility'] ?? '', ['user', 'client'])) ? 'selected' : '' ?>>🐾 Pet Parents Only</option>
                                <option value="provider" <?= (isset($post) && ($post['visibility'] ?? '') == 'provider') ? 'selected' : '' ?>>💼 Service Providers Only</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="pf-form-label">Post Category</label>
                            <select name="category" id="categorySelect" class="pf-select w-100">
                                <option value="post" <?= (isset($post) && ($post['category'] ?? '') == 'post') ? 'selected' : '' ?>>📢 General Post / Update</option>
                                <option value="announcement" <?= (isset($post) && ($post['category'] ?? '') == 'announcement') ? 'selected' : '' ?>>⚡ Official Announcement</option>
                                <option value="question" <?= (isset($post) && ($post['category'] ?? '') == 'question') ? 'selected' : '' ?>>❓ Question / Discussion Topic</option>
                            </select>
                        </div>
                    </div>

                    <!-- Engagement & Moderation Controls -->
                    <div class="pf-form-card mb-4">
                        <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom fs-6">Engagement & Moderation</h6>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <label class="fw-bold text-dark mb-0 d-block fs-6">Allow Comments</label>
                                <small class="text-muted">Users can reply and discuss</small>
                            </div>
                            <label class="pf-toggle-switch">
                                <input type="checkbox" id="comment_section" name="comment_section" value="1" <?= (isset($post['comment_section']) && $post['comment_section'] == 0) ? '' : 'checked' ?>>
                                <span class="pf-toggle-slider"></span>
                            </label>
                        </div>

                        <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                            <div>
                                <label class="fw-bold text-dark mb-0 d-block fs-6">Pin to Top</label>
                                <small class="text-muted">Highlights post at top of feed</small>
                            </div>
                            <label class="pf-toggle-switch">
                                <input type="checkbox" name="is_pinned" id="pinSwitch" value="1" <?= (isset($post) && $post['is_pinned']) ? 'checked' : '' ?>>
                                <span class="pf-toggle-slider"></span>
                            </label>
                        </div>
                    </div>

                    <!-- Publication Schedule Card -->
                    <div class="pf-form-card">
                        <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom fs-6">Publication Timing</h6>

                        <div class="d-flex flex-column gap-2 mb-3">
                            <label class="p-3 border rounded-3 d-flex align-items-center justify-content-between cursor-pointer bg-light">
                                <div class="d-flex align-items-center gap-2">
                                    <input type="radio" name="schedule_type" value="immediate" class="form-check-input mt-0" <?= (!isset($post) || $post['status'] != 'scheduled') ? 'checked' : '' ?>>
                                    <span class="fw-bold text-dark small">Publish Immediately</span>
                                </div>
                                <span class="material-symbols-outlined text-primary" style="font-size: 18px;">bolt</span>
                            </label>

                            <label class="p-3 border rounded-3 d-flex align-items-center justify-content-between cursor-pointer bg-light">
                                <div class="d-flex align-items-center gap-2">
                                    <input type="radio" name="schedule_type" value="scheduled" class="form-check-input mt-0" <?= (isset($post) && $post['status'] == 'scheduled') ? 'checked' : '' ?>>
                                    <span class="fw-bold text-dark small">Schedule for Later Date</span>
                                </div>
                                <span class="material-symbols-outlined text-secondary" style="font-size: 18px;">schedule</span>
                            </label>
                        </div>

                        <div id="scheduleDatePicker" class="<?= (isset($post) && $post['status'] == 'scheduled') ? '' : 'd-none' ?>">
                            <label class="pf-form-label">Select Date & Time</label>
                            <input type="text" name="scheduled_time" id="scheduledTimeInput" placeholder="Choose release date & time..." value="<?= isset($post['scheduled_time']) ? date('Y-m-d H:i', strtotime($post['scheduled_time'])) : '' ?>" class="pf-form-control">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>

<!-- Live Feed Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content pf-modal-content">
            <div class="modal-header pf-modal-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <div class="pf-stat-icon-wrapper indigo" style="width: 36px; height: 36px;">
                        <span class="material-symbols-outlined" style="font-size: 20px;">visibility</span>
                    </div>
                    <h5 class="modal-title fw-bold text-dark mb-0 fs-6">Live Feed Preview</h5>
                </div>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pf-modal-body p-4 bg-light">
                <div class="pf-post-card mb-0 shadow-sm bg-white" id="previewCardTarget">
                    <!-- Populated dynamically by JS -->
                </div>
            </div>
            <div class="modal-footer pf-modal-footer">
                <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Close Preview</button>
                <button type="submit" form="postForm" class="pf-btn-primary px-4">
                    <span class="material-symbols-outlined" style="font-size: 18px;">send</span> Release Post
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->include('admin/footer') ?>

<script>
    var deletedMediaIds = [];
    var newMediaFilesList = [];

    function updateMainMediaInput() {
        var fileInput = document.querySelector('input[name="media[]"]');
        if (!fileInput) return;
        var dt = new DataTransfer();
        newMediaFilesList.forEach(function(item) {
            dt.items.add(item.file);
        });
        fileInput.files = dt.files;
    }

    var postTextarea = document.getElementById('postText');
    var charCounter = document.getElementById('charCounter');
    var maxChars = 5000;

    function updateCharCount() {
        if (!postTextarea || !charCounter) return;
        var length = postTextarea.value.length;
        charCounter.textContent = length + ' / ' + maxChars;
        if (length > maxChars) {
            charCounter.classList.remove('bg-light', 'text-muted');
            charCounter.classList.add('bg-danger', 'text-white');
        } else {
            charCounter.classList.remove('bg-danger', 'text-white');
            charCounter.classList.add('bg-light', 'text-muted');
        }
    }

    if (postTextarea) {
        postTextarea.addEventListener('input', updateCharCount);
        updateCharCount();
    }

    function handleMediaSelect(input) {
        if (input.files && input.files.length > 0) {
            var previewArea = document.getElementById('mediaPreviewArea');
            var maxFiles = 10;
            var currentCount = previewArea.querySelectorAll('.media-item').length;

            if (currentCount + input.files.length > maxFiles) {
                alert('You can only attach up to ' + maxFiles + ' media files total.');
                input.value = '';
                return;
            }

            Array.from(input.files).forEach(function(file) {
                var isVideo = file.type.startsWith('video/');
                var maxSize = isVideo ? 50 * 1024 * 1024 : 10 * 1024 * 1024;
                if (file.size > maxSize) {
                    alert('File ' + file.name + ' exceeds maximum size (' + (isVideo ? '50MB' : '10MB') + ').');
                    return;
                }

                var fileId = 'file_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
                newMediaFilesList.push({ id: fileId, file: file });

                var reader = new FileReader();
                reader.onload = function(e) {
                    var div = document.createElement('div');
                    div.className = "media-item position-relative rounded-3 overflow-hidden border shadow-sm";
                    div.style.width = '110px';
                    div.style.height = '110px';
                    div.style.background = '#0f172a';
                    div.dataset.fileId = fileId;

                    if (isVideo) {
                        div.innerHTML = '<video class="w-100 h-100 object-fit-cover" muted playsinline><source src="' + e.target.result + '" type="' + file.type + '"></video>' +
                            '<div class="position-absolute top-50 start-50 translate-middle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: rgba(0,0,0,0.5); border-radius: 50%;">' +
                            '<span class="material-symbols-outlined text-white" style="font-size: 18px;">play_arrow</span></div>' +
                            '<button type="button" class="delete-new-media-btn position-absolute top-0 end-0 m-1 btn btn-danger btn-sm rounded-circle d-flex align-items-center justify-content-center p-0" style="width: 24px; height: 24px;" title="Remove">' +
                            '<span class="material-symbols-outlined text-white" style="font-size: 14px;">close</span></button>';
                    } else {
                        div.innerHTML = '<img src="' + e.target.result + '" class="w-100 h-100 object-fit-cover" alt="Preview">' +
                            '<button type="button" class="delete-new-media-btn position-absolute top-0 end-0 m-1 btn btn-danger btn-sm rounded-circle d-flex align-items-center justify-content-center p-0" style="width: 24px; height: 24px;" title="Remove">' +
                            '<span class="material-symbols-outlined text-white" style="font-size: 14px;">close</span></button>';
                    }

                    var deleteBtn = div.querySelector('.delete-new-media-btn');
                    deleteBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        var fid = div.dataset.fileId;
                        newMediaFilesList = newMediaFilesList.filter(function(f) { return f.id !== fid; });
                        updateMainMediaInput();
                        div.remove();
                    });

                    previewArea.insertBefore(div, previewArea.lastElementChild);
                };
                reader.readAsDataURL(file);
            });

            updateMainMediaInput();
        }
    }

    // Handle existing media deletion
    document.querySelectorAll('.delete-media-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            var mediaId = this.getAttribute('data-media-id');
            if (mediaId) {
                deletedMediaIds.push(mediaId);
                document.getElementById('deletedMediaIds').value = deletedMediaIds.join(',');
                this.closest('.media-item').remove();
            }
        });
    });

    // Schedule radio listener
    var scheduleRadios = document.querySelectorAll('input[name="schedule_type"]');
    var schedulePicker = document.getElementById('scheduleDatePicker');

    scheduleRadios.forEach(function(radio) {
        radio.addEventListener('change', function() {
            if (this.value === 'scheduled') {
                schedulePicker.classList.remove('d-none');
                initDatePicker();
            } else {
                schedulePicker.classList.add('d-none');
            }
        });
    });

    function initDatePicker() {
        if (typeof flatpickr !== 'undefined') {
            flatpickr("#scheduledTimeInput", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                minDate: "today",
                disableMobile: "true"
            });
        }
    }

    if (document.querySelector('input[name="schedule_type"]:checked') && document.querySelector('input[name="schedule_type"]:checked').value === 'scheduled') {
        initDatePicker();
    }

    // Live Feed Preview Modal Trigger
    var previewBtn = document.getElementById('previewBtn');
    if (previewBtn) {
        previewBtn.addEventListener('click', function() {
            var postText = document.getElementById('postText').value;
            var channelSelect = document.getElementById('channelSelect');
            var channelName = channelSelect.options[channelSelect.selectedIndex]?.text || 'General';
            var isPinned = document.getElementById('pinSwitch')?.checked;

            var target = document.getElementById('previewCardTarget');
            target.innerHTML = 
                (isPinned ? '<div class="pf-pinned-banner"><span class="material-symbols-outlined fill" style="font-size: 14px;">push_pin</span><span>Pinned by Community Staff</span></div>' : '') +
                '<div class="d-flex align-items-center gap-3 mb-2">' +
                    '<div class="pf-avatar-wrapper">' +
                        '<div class="pf-avatar d-flex align-items-center justify-content-center fw-bold text-white bg-primary">PF</div>' +
                        '<span class="pf-avatar-indicator"></span>' +
                    '</div>' +
                    '<div>' +
                        '<div class="d-flex align-items-center gap-2">' +
                            '<h6 class="pf-post-author-name mb-0"><?= esc($user['name'] ?? 'Admin Persona') ?></h6>' +
                            '<span class="pf-badge-role admin">Admin</span>' +
                            '<span class="pf-badge-channel">' + channelName + '</span>' +
                        '</div>' +
                        '<small class="text-muted">Preview • Live in Community Feed</small>' +
                    '</div>' +
                '</div>' +
                '<p class="pf-post-text fs-6">' + (postText ? postText.replace(/\n/g, '<br>') : '<span class="text-muted fst-italic">No post content written yet...</span>') + '</p>' +
                '<div class="pf-interaction-toolbar">' +
                    '<div class="d-flex align-items-center gap-2">' +
                        '<span class="pf-stat-counter"><span class="material-symbols-outlined text-danger fill" style="font-size: 16px;">favorite</span> 0</span>' +
                        '<span class="pf-stat-counter"><span class="material-symbols-outlined text-primary fill" style="font-size: 16px;">chat_bubble</span> 0</span>' +
                    '</div>' +
                    '<span class="badge bg-light text-muted border rounded-pill px-3 py-1">Ready to publish</span>' +
                '</div>';

            var previewModalEl = document.getElementById('previewModal');
            if (previewModalEl && typeof bootstrap !== 'undefined') {
                var previewModal = new bootstrap.Modal(previewModalEl);
                previewModal.show();
            }
        });
    }

    var form = document.getElementById('postForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            var postText = document.getElementById('postText').value.trim();
            if (!postText) {
                e.preventDefault();
                alert('Please enter post text before submitting.');
                return false;
            }
            if (postText.length > maxChars) {
                e.preventDefault();
                alert('Post content exceeds character limit of ' + maxChars + '.');
                return false;
            }
        });
    }
</script>

<?= $this->endSection(); ?>