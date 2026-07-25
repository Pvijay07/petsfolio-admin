<?= $this->extend('admin/includes/header'); ?>
<!--start content-->
<?= $this->section('content'); ?>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&amp;display=swap" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
<!-- DatePicker -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<style>
    .flatpickr-calendar {
        background: #ffffff;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        border: none;
        border-radius: 1.5rem;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .flatpickr-day.selected,
    .flatpickr-day.selected:hover {
        background: #493ee6;
        border-color: #493ee6;
    }
</style>
<script id="tailwind-config">
    tailwind.config = {
        darkMode: "class",
        theme: {
            extend: {
                colors: {
                    primary: "#493ee6",
                    secondary: "#702ae1",
                    surface: "#f3f7fb",
                },
                fontFamily: {
                    sans: ["Plus Jakarta Sans", "sans-serif"],
                },
            },
        },
    }
</script>


<main>
    <div class="iq-banner position-relative">
        <?php echo $this->include('admin/nav_bar') ?>
    </div>

    <div class="px-4 mx-auto" style="margin-top:-50px;">
        <form action="<?= isset($post) ? base_url('community/updatePost/' . $post['id']) : base_url('community/addPost') ?>" method="POST" enctype="multipart/form-data" id="postForm">
            <div class="banner-card rounded-[2.5rem] p-6 md:p-12 mb-8 text-white shadow-lg position-relative overflow-hidden" style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);">
                <!-- Decorative Blobs -->
                <div class="position-absolute" style="width: 300px; height: 300px; background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%); border-radius: 50%; top: -150px; right: -100px;"></div>
                <div class="position-absolute" style="width: 200px; height: 200px; background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, rgba(255,255,255,0) 70%); border-radius: 50%; bottom: -100px; left: -50px;"></div>

                <div class="position-relative">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-8">
                        <div class="flex-grow-1">
                            <h1 class="display-4 font-black text-white tracking-tighter mb-4 italic leading-tight drop-shadow-sm">
                                <?= isset($post) ? 'Refine Your Post' : 'Compose Activity' ?>
                            </h1>
                            <p class="text-white/70 text-base md:text-lg font-medium max-w-xl leading-relaxed mb-0">
                                Share updates and success stories across the Petsfolio network.
                            </p>
                        </div>

                        <div class="flex flex-col gap-4">
                            <div class="flex flex-wrap items-center gap-3">
                                <button type="button" id="previewBtn" class="px-6 py-3 glass-pill text-white font-black text-[11px] uppercase tracking-widest rounded-full shadow-lg hover:shadow-xl transition-all flex items-center justify-center gap-2 border-0 min-w-[150px]">
                                    <span class="material-symbols-outlined text-lg">visibility</span>
                                    Preview
                                </button>
                                <button type="submit" class="px-6 py-3 bg-white text-primary font-black text-[11px] uppercase tracking-widest rounded-full shadow-xl shadow-white/10 hover:bg-gray-50 transition-all flex items-center justify-center gap-2 border-0 min-w-[150px]">
                                    <span><?= isset($post) ? 'Update' : 'Publish' ?></span>
                                    <span class="material-symbols-outlined text-lg">send</span>
                                </button>
                            </div>

                            <div class="flex items-center gap-4 bg-white/10 w-fit px-5 py-3 rounded-2xl border border-white/20 backdrop-blur-md shadow-sm">
                                <!-- <img src="<?= ($user['profile'] ?? '') ? base_url('public/uploads/profiles/' . $user['profile']) : 'https://ui-avatars.com/api/?name=' . urlencode($user['name'] ?? 'Admin') . '&background=random' ?>"
                                    class="w-10 h-10 rounded-full border-2 border-white shadow-sm object-cover"> -->
                                <div>
                                    <p class="text-[9px] font-black text-white/50 uppercase tracking-widest leading-none mb-1.5">Authorized Identity</p>
                                    <div class="flex items-center gap-2">
                                        <p class="text-base font-bold text-white leading-none"><?= esc($user['name'] ?? 'Admin') ?></p>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 pb-12">
                <!-- Left Column: Content -->
                <div class="lg:col-span-8 space-y-8 lg:space-y-10">
                    <!-- Content Body Card -->
                    <div class="bg-white p-3 md:p-10 rounded-[2rem] shadow-sm border border-white/60">
                        <div class="flex flex-wrap items-center justify-between gap-4 mb-2">
                            <div class="flex items-center gap-3">
                                <div class="w-1.5 h-6 bg-primary rounded-full"></div>
                                <label class="font-black uppercase tracking-[0.2em] text-gray-400 italic mb-0">Perspective Content</label>
                            </div>
                            <div class="flex items-center gap-4 bg-gray-50/50 px-4 py-2 rounded-full">
                                <span class="text-[13px] font-black text-primary/40 uppercase tracking-widest">Markdown Enabled</span>
                                <span id="charCounter" class="text-[13px] font-black text-gray-400 tracking-widest">0 / 5000</span>
                            </div>
                        </div>
                        <div class="relative group">
                            <textarea name="post_text" id="postText" rows="10" required
                                placeholder="Share your update..."
                                class="w-full bg-gray-50/30 border-2 border-transparent rounded-[2rem] p-3 font-medium text-gray-700 placeholder:text-gray-300 focus:bg-white focus:border-primary/5 focus:ring-primary/5 transition-all outline-none resize-none leading-relaxed"><?= htmlspecialchars($post['post_text'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <!-- Media Gallery Card -->
                    <div class="bg-white p-3 rounded-[2rem] shadow-sm border border-white/60 mt-3">
                        <div class="flex flex-wrap items-center justify-between gap-4 mb-10">
                            <div class="flex items-center gap-3">
                                <div class="w-1.5 h-6 bg-secondary rounded-full"></div>
                                <label class="text-[11px] font-black uppercase tracking-[0.2em] text-gray-400 italic mb-0">Visual Assets</label>
                            </div>
                            <div class="bg-secondary/5 px-4 py-2 rounded-full text-[9px] font-black text-secondary uppercase tracking-[0.3em]">
                                High Resolution Media
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-5 items-start" id="mediaPreviewArea">
                            <?php if (isset($media) && is_array($media)): ?>
                                <?php foreach ($media as $index => $m): ?>
                                    <div class="media-item existing-media w-28 h-28 md:w-32 md:h-32 rounded-[2.5rem] border-4 border-white shadow-lg overflow-hidden relative group cursor-pointer" data-media-id="<?= $m['id'] ?? $index ?>" data-media-url="<?= $m['media_url'] ?>">
                                        <?php if (strpos($m['media_url'], '.mp4') !== false || strpos($m['media_url'], '.mov') !== false): ?>
                                            <video class="w-full h-full object-cover">
                                                <source src="<?= $m['media_url'] ?>" type="video/mp4">
                                            </video>
                                            <div class="absolute inset-0 flex items-center justify-center bg-black/40 backdrop-blur-[1px]">
                                                <span class="material-symbols-outlined text-white text-xl fill">play_arrow</span>
                                            </div>
                                        <?php else: ?>
                                            <img src="<?= $m['media_url'] ?>" class="w-full h-full object-cover grayscale-[10%] group-hover:grayscale-0 transition-all" alt="Media preview">
                                        <?php endif; ?>
                                        <button type="button" class="delete-media-btn absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-8 h-8 bg-red-500 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all rounded-full hover:bg-red-600 shadow-xl z-20">
                                            <span class="material-symbols-outlined text-base">close</span>
                                        </button>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>

                            <label class="file-input-label w-28 h-28 md:w-32 md:h-32 rounded-[2.5rem] border-2 border-dashed border-gray-100 flex flex-col items-center justify-center cursor-pointer hover:bg-primary/5 hover:border-primary/20 transition-all group">
                                <input type="file" multiple accept="image/*,video/*" class="hidden" name="media[]" onchange="handleMediaSelect(this)">
                                <span class="material-symbols-outlined text-gray-300 text-2xl group-hover:text-primary transition-all">add_a_photo</span>
                                <span class="text-[8px] font-black text-gray-400 mt-1 uppercase group-hover:text-primary">Attach</span>
                            </label>
                        </div>
                        <input type="hidden" name="deleted_media_ids" id="deletedMediaIds" value="">
                    </div>
                </div>

                <!-- Right Column: Settings -->
                <div class="lg:col-span-4 space-y-6 lg:space-y-8  "> <!-- Channel Assignment -->
                    <div class="bg-white p-3 md:p-10 rounded-[2rem] shadow-sm border border-white/60">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-8 h-8 rounded-xl bg-primary/10 flex items-center justify-center text-primary">
                                <span class="material-symbols-outlined text-lg">hub</span>
                            </div>
                            <label class="text-[11px] font-black uppercase tracking-[0.2em] text-gray-400 italic mb-0">Channel</label>
                        </div>
                        <div class="relative">
                            <select name="channel_id" id="channelSelect" class="w-full appearance-none px-4 py-4 bg-gray-50 border-0 rounded-2xl text-sm font-black text-[#1a1a1a] focus:bg-white focus:ring-8 focus:ring-primary/5 transition-all pr-12 cursor-pointer shadow-sm">
                                <?php foreach ($channels as $channel): ?>
                                    <option value="<?= $channel['id'] ?>" <?= (isset($post) && $post['channel_id'] == $channel['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($channel['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-primary/40 pointer-events-none">expand_more</span>
                        </div>
                    </div>
                    <div class="p-4 bg-white rounded-[2rem] border border-white/60 shadow-sm transition-all hover:shadow-md mt-2">

                        <div class="flex items-center justify-between p-2">

                            <!-- Left Side -->
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-gray-50 rounded-2xl flex items-center justify-center text-primary">
                                    <span class="material-symbols-outlined text-2xl" style="font-variation-settings: 'FILL' 1;">
                                        comment
                                    </span>
                                </div>

                                <div>
                                    <p class="text-[14px] font-black text-[#1a1a1a] leading-none">
                                        Enable Comments
                                    </p>
                                    <small class="text-muted text-[12px]">
                                        If disabled, users will not be able to comment on this post.
                                    </small>
                                </div>
                            </div>

                            <!-- Toggle Switch -->
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input
                                    type="checkbox"
                                    id="comment_section"
                                    name="comment_section"
                                    value="1"
                                    class="sr-only peer"
                                    <?= (isset($post['comment_section']) && $post['comment_section'] == 0) ? '' : 'checked' ?>>

                                <div class="w-12 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer
                        peer-checked:after:translate-x-full 
                        peer-checked:after:border-white 
                        after:content-[''] after:absolute after:top-[2px] after:left-[2px]
                        after:bg-white after:border-gray-300 after:border after:rounded-full 
                        after:h-5 after:w-5 after:transition-all 
                        peer-checked:bg-primary shadow-inner">
                                </div>
                            </label>

                        </div>

                    </div>

                    <!-- Priority Card -->
                    <div class="p-4 bg-white rounded-[2rem] border border-white/60 shadow-sm transition-all hover:shadow-md mt-2">
                        <div class="flex items-center justify-between p-2">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-gray-50 rounded-2xl flex items-center justify-center text-primary">
                                    <span class="material-symbols-outlined text-2xl" style="font-variation-settings: 'FILL' 1;">push_pin</span>
                                </div>
                                <p class="text-[14px] font-black text-[#1a1a1a] leading-none">Pin Post</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_pinned" value="1" class="sr-only peer" id="pinSwitch" <?= (isset($post) && $post['is_pinned']) ? 'checked' : '' ?>>
                                <div class="w-12 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary shadow-inner"></div>
                            </label>
                        </div>
                    </div>

                    <!-- Schedule Settings -->
                    <!-- <div class="bg-white p-3 md:p-10 rounded-[2rem] shadow-sm border border-white/60 mt-2">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-8 h-8 rounded-xl bg-secondary/10 flex items-center justify-center text-secondary">
                                <span class="material-symbols-outlined text-lg">event</span>
                            </div>
                            <label class="text-[11px] font-black uppercase tracking-[0.2em] text-gray-400 italic mb-0">Publication</label>
                        </div>

                        <div class="space-y-4">
                            <label class="flex items-center justify-between px-4 py-3 rounded-2xl bg-gray-50/50 cursor-pointer hover:bg-white hover:border-primary/10 transition-all border border-transparent">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="schedule_type" value="immediate" class="w-4 h-4 text-primary" <?= (!isset($post) || $post['status'] != 'scheduled') ? 'checked' : '' ?>>
                                    <span class="text-sm font-bold text-gray-700">Live Now</span>
                                </div>
                                <span class="material-symbols-outlined text-gray-300 text-lg">bolt</span>
                            </label>

                            <label class="flex items-center justify-between px-4 py-3 rounded-2xl bg-gray-50/50 cursor-pointer hover:bg-white hover:border-primary/10 transition-all border border-transparent">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="schedule_type" value="scheduled" class="w-4 h-4 text-primary" <?= (isset($post) && $post['status'] == 'scheduled') ? 'checked' : '' ?>>
                                    <span class="text-sm font-bold text-gray-700">Future Drop</span>
                                </div>
                                <span class="material-symbols-outlined text-gray-300 text-lg">schedule</span>
                            </label>

                            <div id="scheduleDatePicker" class="<?= (isset($post) && $post['status'] == 'scheduled') ? '' : 'hidden' ?> mt-6 animate-in fade-in slide-in-from-top-1">
                                <input type="text" name="scheduled_time" id="scheduledTimeInput" placeholder="Select release date & time..." value="<?= isset($post['scheduled_time']) ? date('Y-m-d H:i', strtotime($post['scheduled_time'])) : '' ?>"
                                    class="w-full px-5 py-4 bg-gray-100 border-none rounded-xl text-sm font-bold focus:ring-8 focus:ring-primary/5">
                            </div>
                        </div>
                    </div> -->

                </div>
            </div>
    </div>


    </form>
    </div>

</main>

<style>
    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: #f3f7fb;
    }

    .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }

    .banner-card {
        position: relative;
        overflow: hidden;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
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
    }

    .custom-switch:checked+.switch-bg {
        background-color: #493ee6;
    }

    .custom-switch:checked+.switch-bg .switch-dot {
        transform: translateX(1.25rem);
    }

    /* Smooth transitions for media items */
    .media-item {
        transition: all 0.2s ease;
    }

    .media-item:hover {
        transform: scale(1.02);
    }

    /* Custom file input styling */
    .file-input-label:hover {
        background-color: #f9fafb;
    }

    /* Rich text area focus effect */
    .rich-textarea:focus {
        box-shadow: 0 0 0 4px rgba(73, 62, 230, 0.1);
    }

    /* Character counter styling */
    .char-count {
        transition: all 0.2s ease;
    }
</style>

<script>
    // Track media state
    let deletedMediaIds = [];
    let newMediaFilesList = [];

    // Helper to sync file input with our custom list
    function updateMainMediaInput() {
        const fileInput = document.querySelector('input[name="media[]"]');
        const dt = new DataTransfer();
        newMediaFilesList.forEach(item => dt.items.add(item.file));
        fileInput.files = dt.files;
    }

    // Character counter functionality
    const postTextarea = document.getElementById('postText');
    const charCounter = document.getElementById('charCounter');
    const maxChars = 5000;

    function updateCharCount() {
        const length = postTextarea.value.length;
        charCounter.textContent = `${length} / ${maxChars}`;
        if (length > maxChars) {
            charCounter.classList.add('text-red-500');
            charCounter.classList.remove('text-gray-400');
        } else {
            charCounter.classList.remove('text-red-500');
            charCounter.classList.add('text-gray-400');
        }
    }

    if (postTextarea) {
        postTextarea.addEventListener('input', updateCharCount);
        updateCharCount();
    }



    // Handle media selection with preview
    function handleMediaSelect(input) {
        if (input.files && input.files.length > 0) {
            const previewArea = document.getElementById('mediaPreviewArea');
            const maxFiles = 10;
            const existingCount = previewArea.querySelectorAll('.media-item:not(.existing-media)').length;
            const newFilesCount = input.files.length;

            if (existingCount + newFilesCount > maxFiles) {
                alert(`You can only upload up to ${maxFiles} media files.`);
                input.value = '';
                return;
            }

            Array.from(input.files).forEach((file) => {
                // Validate file size (max 50MB for video, 10MB for images)
                const maxSize = file.type.startsWith('video/') ? 50 * 1024 * 1024 : 10 * 1024 * 1024;
                if (file.size > maxSize) {
                    alert(`File ${file.name} is too large. Maximum size for ${file.type.startsWith('video/') ? 'video' : 'image'} is ${maxSize / (1024 * 1024)}MB.`);
                    return;
                }

                const fileId = 'file_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
                newMediaFilesList.push({
                    id: fileId,
                    file: file
                });

                const reader = new FileReader();
                const isVideo = file.type.startsWith('video/');

                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = "media-item w-28 h-28 md:w-32 md:h-32 rounded-[2.5rem] border-[6px] border-white shadow-xl overflow-hidden bg-gray-50 ring-1 ring-gray-100 relative group";
                    div.dataset.fileId = fileId;

                    if (isVideo) {
                        div.innerHTML = `
                            <video class="w-full h-full object-cover">
                                <source src="${e.target.result}" type="${file.type}">
                            </video>
                            <div class="absolute inset-0 flex items-center justify-center bg-black/30 pointer-events-none">
                                <span class="material-symbols-outlined text-white text-3xl">play_circle</span>
                            </div>
                            <button type="button" class="delete-new-media-btn absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-12 h-12 bg-red-500/90 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all rounded-full hover:bg-red-600 shadow-xl z-20">
                                <span class="material-symbols-outlined text-2xl">delete</span>
                            </button>
                        `;
                    } else {
                        div.innerHTML = `
                            <img src="${e.target.result}" class="w-full h-full object-cover" alt="Media preview">
                            <button type="button" class="delete-new-media-btn absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-12 h-12 bg-red-500/90 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all rounded-full hover:bg-red-600 shadow-xl z-20">
                                <span class="material-symbols-outlined text-2xl">delete</span>
                            </button>
                        `;
                    }

                    const deleteBtn = div.querySelector('.delete-new-media-btn');
                    deleteBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        const fid = div.dataset.fileId;
                        newMediaFilesList = newMediaFilesList.filter(f => f.id !== fid);
                        updateMainMediaInput();
                        div.remove();
                    });

                    previewArea.insertBefore(div, previewArea.lastElementChild);
                }
                reader.readAsDataURL(file);
            });

            // Sync the input even if we're still loading previews
            updateMainMediaInput();
            // input.value = ''; // Don't clear it here, it clears the files we just set!
        }
    }

    // Handle deletion of existing media
    document.querySelectorAll('.delete-media-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const mediaId = this.getAttribute('data-media-id');
            if (mediaId) {
                deletedMediaIds.push(mediaId);
                document.getElementById('deletedMediaIds').value = deletedMediaIds.join(',');
                this.closest('.media-item').remove();
            }
        });
    });

    // Schedule functionality
    const scheduleRadios = document.querySelectorAll('input[name="schedule_type"]');
    const schedulePicker = document.getElementById('scheduleDatePicker');

    scheduleRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'scheduled') {
                schedulePicker.classList.remove('hidden');
                // Ensure datepicker is initialized when visible
                initDatePicker();
            } else {
                schedulePicker.classList.add('hidden');
            }
        });
    });

    // Initialize DatePicker
    function initDatePicker() {
        flatpickr("#scheduledTimeInput", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            minDate: "today",
            theme: "material_blue",
            disableMobile: "true"
        });
    }

    // Call init if already visible (e.g. edit mode)
    if (document.querySelector('input[name="schedule_type"]:checked').value === 'scheduled') {
        initDatePicker();
    }

    // Preview functionality
    const previewBtn = document.getElementById('previewBtn');
    if (previewBtn) {
        previewBtn.addEventListener('click', function() {
            const postText = document.getElementById('postText').value;
            const channelSelect = document.getElementById('channelSelect');
            const channelName = channelSelect.options[channelSelect.selectedIndex]?.text || 'General';
            const isPinned = document.getElementById('pinSwitch')?.checked;

            // Collect media for preview
            const mediaItems = Array.from(document.querySelectorAll('#mediaPreviewArea .media-item')).map(item => {
                const img = item.querySelector('img');
                const video = item.querySelector('video');
                const source = video ? video.querySelector('source') : null;
                return {
                    url: img ? img.src : (source ? source.src : ''),
                    type: img ? 'image' : 'video'
                };
            }).filter(m => m.url);

            let mediaHtml = '';
            if (mediaItems.length > 0) {
                const gridClass = mediaItems.length === 1 ? 'grid-cols-1' : (mediaItems.length === 2 ? 'grid-cols-2' : 'grid-cols-2 grid-rows-2');
                mediaHtml = `<div class="grid ${gridClass} gap-2 mt-6 rounded-3xl overflow-hidden border border-gray-100 shadow-inner bg-gray-50">
                    ${mediaItems.slice(0, 4).map((m, idx) => `
                        <div class="relative group aspect-square bg-gray-200">
                            ${m.type === 'image' 
                                ? `<img src="${m.url}" class="w-full h-full object-cover">` 
                                : `<video class="w-full h-full object-cover"><source src="${m.url}"></video>
                                   <div class="absolute inset-0 flex items-center justify-center bg-black/20 backdrop-blur-[2px]">
                                       <span class="material-symbols-outlined text-white text-3xl fill">play_arrow</span>
                                   </div>`
                            }
                            ${idx === 3 && mediaItems.length > 4 ? `
                                <div class="absolute inset-0 bg-black/60 backdrop-blur-md flex items-center justify-center">
                                    <span class="text-white font-black text-2xl">+${mediaItems.length - 3}</span>
                                </div>
                            ` : ''}
                        </div>
                    `).join('')}
                </div>`;
            }

            // Create preview modal with glassmorphism
            const modal = document.createElement('div');
            modal.style.zIndex = '10000';
            modal.className = 'fixed inset-0 bg-[#0a0a0b]/80 backdrop-blur-xl flex items-center justify-center z-[100] p-4 md:p-8 animate-in fade-in duration-300';
            modal.innerHTML = `
                <div class="bg-white rounded-md max-w-2xl w-full max-h-[90vh] overflow-hidden shadow-[0_40px_100px_-20px_rgba(0,0,0,0.5)] border border-white/20 flex flex-col scale-in-center">
                    <div class="py-4 px-5 flex justify-between items-center border-b border-gray-50">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-2xl bg-primary/10 flex items-center justify-center text-primary">
                                <span class="material-symbols-outlined text-2xl">visibility</span>
                            </div>
                            <div>
                                <span class="text-[10px] font-black text-primary/40 uppercase tracking-[0.3em] leading-none block mb-1">Live Feed Preview</span>
                                <h3 class="text-xl font-black text-[#1a1a1a] tracking-tight">Perspective Snapshot</h3>
                            </div>
                        </div>
                        <button onclick="this.closest('.fixed').remove()" class="group w-12 h-12 rounded-2xl bg-gray-50 flex items-center justify-center hover:bg-red-50 hover:text-red-500 transition-all">
                            <span class="material-symbols-outlined group-hover:rotate-90 transition-transform">close</span>
                        </button>
                    </div>

                    <div class="flex-grow overflow-y-auto p-0 custom-scrollbar">
                        <div class="bg-gray-50/50 rounded-[2rem] p-6 md:p-8 border border-gray-100 hover:bg-white hover:shadow-xl transition-all duration-500">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-14 h-14 rounded-full p-1 bg-gradient-to-br from-primary to-secondary shadow-lg">
                                    <div class="w-full h-full rounded-full bg-white flex items-center justify-center font-black text-lg text-primary">PF</div>
                                </div>
                                <div class="flex-grow">
                                    <div class="flex items-center justify-between">
                                        <p class="font-black text-lg text-[#1a1a1a] leading-tight">Admin Persona</p>
                                        <div class="flex gap-2">
                                            <span class="text-[9px] font-black bg-primary/5 text-primary px-3 py-1 rounded-full uppercase tracking-widest">${channelName}</span>
                                            ${isPinned ? '<span class="text-[9px] font-black bg-secondary/5 text-secondary px-3 py-1 rounded-full uppercase tracking-widest flex items-center gap-1"><span class="material-symbols-outlined text-[12px]" style="font-variation-settings:\'FILL\' 1">push_pin</span>Pinned</span>' : ''}
                                        </div>
                                    </div>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Authorized Feed Release • Just Now</p>
                                </div>
                            </div>
                            
                            <div class="text-lg md:text-xl font-medium text-gray-700 leading-relaxed whitespace-pre-wrap break-words italic">
                                ${postText || '<span class="text-gray-300 italic">No content perspective shared yet...</span>'}
                            </div>
                            
                            ${mediaHtml}
                        </div>
                    </div>

                    <div class="p-5 py-4 bg-gray-50/50 border-t border-gray-50 flex gap-4">
                        <button onclick="this.closest('.fixed').remove()" class="flex-grow py-3 bg-white border border-gray-200 text-[#1a1a1a] font-black text-xs uppercase tracking-widest rounded-2xl hover:bg-gray-100 transition-all shadow-sm">
                            Exit Inspection
                        </button>
                        <button type="submit" form="postForm" class="flex-grow py-3 bg-primary text-white font-black text-xs uppercase tracking-widest rounded-2xl hover:bg-secondary transition-all shadow-xl shadow-primary/20 flex items-center justify-center gap-2 group">
                            Release Post <span class="material-symbols-outlined text-sm group-hover:translate-x-1 transition-transform">send</span>
                        </button>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
        });
    }

    // Form validation before submit
    const form = document.getElementById('postForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            const postText = document.getElementById('postText').value.trim();
            if (!postText) {
                e.preventDefault();
                alert('Please enter some content for your post.');
                return false;
            }

            if (postText.length > maxChars) {
                e.preventDefault();
                alert(`Post content exceeds ${maxChars} characters. Please shorten your post.`);
                return false;
            }
        });
    }
</script>

<?php echo $this->include('admin/footer') ?>
<?= $this->endSection(); ?>