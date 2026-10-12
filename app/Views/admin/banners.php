<<<<<<< HEAD
<?php echo $this->extend ( 'admin/includes/header' ); ?>
<!--start content-->
<?php echo $this->section ( 'content' ); ?>
<main class="main-content">
  <div class="position-relative iq-banner">
    <!--Nav Start-->
    <?php echo $this->include ( 'admin/nav_bar' ) ?>
  </div>

  <div class="container-fluid content-inner pb-0" id="page_layout">
    <!-- Page Header & Stat Summary -->
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4 gap-3">
      <div>
        <h4 class="mb-1 fw-bold text-dark"><i class="icon-image me-2 text-primary"></i>App Banners Management</h4>
        <p class="text-muted mb-0">Configure promotional image & video banners, target specific screens, and manage in-app popups.</p>
      </div>
      <div class="d-flex gap-2">
        <button type="button" class="btn btn-primary d-flex align-items-center gap-2 shadow-sm px-3 py-2 btn-new-banner" data-bs-toggle="offcanvas" data-bs-target="#form-offcanvas">
          <i class="icon-add-new fs-5"></i>
          <span class="fw-semibold">New Banner</span>
        </button>
      </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
      <div class="card-header bg-white py-3 border-bottom">
        <div class="row g-3 align-items-center justify-content-between">
          <!-- Search Input -->
          <div class="col-12 col-md-4 col-lg-3">
            <div class="input-group">
              <span class="input-group-text bg-light border-end-0 text-muted" id="search-addon"><i class="icon-Search"></i></span>
              <input type="text" class="form-control bg-light border-start-0 ps-0 dt-search" id="banner-table-search" placeholder="Search banner name, URL, type..." aria-describedby="search-addon">
            </div>
          </div>

          <!-- Filters -->
          <div class="col-12 col-md-8 col-lg-9">
            <div class="d-flex flex-wrap gap-2 justify-content-md-end align-items-center">
              <!-- Media Type Filter -->
              <div class="d-flex align-items-center gap-1">
                <span class="small text-muted fw-semibold text-nowrap"><i class="me-1">🎬</i> Media:</span>
                <select class="form-select form-select-sm" id="filter-media-type" style="min-width: 140px;">
                  <option value="">All Media Types</option>
                  <option value="image">🖼️ Images Only</option>
                  <option value="video">🎥 Videos Only</option>
                </select>
              </div>

              <!-- Screen Filter -->
              <div class="d-flex align-items-center gap-1">
                <span class="small text-muted fw-semibold text-nowrap"><i class="me-1">📱</i> Screen:</span>
                <select class="form-select form-select-sm" id="filter-screen" style="min-width: 160px;">
                  <option value="">All Screen Targets</option>
                  <?php if (!empty($available_screens)): ?>
                    <?php foreach ($available_screens as $sKey => $sVal): ?>
                      <option value="<?= esc($sKey) ?>"><?= $sVal['icon'] ?> <?= esc($sVal['label']) ?></option>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </select>
              </div>

              <!-- Popup Filter -->
              <div class="d-flex align-items-center gap-1">
                <span class="small text-muted fw-semibold text-nowrap"><i class="me-1">✨</i> Popup:</span>
                <select class="form-select form-select-sm" id="filter-popup" style="min-width: 120px;">
                  <option value="">All</option>
                  <option value="yes">With Popup</option>
                  <option value="no">No Popup</option>
                </select>
              </div>

              <!-- Reset Filters -->
              <button type="button" class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1" id="btn-reset-filters" title="Reset Filters">
                <i class="icon-refresh"></i> Reset
              </button>
            </div>
=======
<?php echo $this->extend('admin/includes/header'); ?>
<!--start content-->
<?php echo $this->section('content'); ?>
<?php
// Build unified screens mapping
$screensMap = [
  'all'        => ['label' => 'All Screens', 'badge' => 'bg-primary', 'icon' => 'icon-category'],
  'home'       => ['label' => 'Home Screen', 'badge' => 'bg-info text-dark', 'icon' => 'icon-home'],
];

if (!empty($services)) {
  foreach ($services as $srv) {
    $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '_', trim($srv->name)));
    $screensMap[$slug] = [
      'label' => esc($srv->name),
      'badge' => 'bg-success',
      'icon'  => 'icon-work',
    ];
  }
}

$screensMap['community'] = ['label' => 'Community', 'badge' => 'bg-warning text-dark', 'icon' => 'icon-chat'];
$screensMap['bookings']  = ['label' => 'Bookings', 'badge' => 'bg-secondary', 'icon' => 'icon-calendar'];
$screensMap['profile']   = ['label' => 'Profile', 'badge' => 'bg-dark', 'icon' => 'icon-user'];
?>

<style>
  .banner-thumb-img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #e9ecef;
    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    transition: transform 0.2s ease;
  }
  .banner-thumb-img:hover {
    transform: scale(1.05);
  }
  .video-thumb-container {
    position: relative;
    width: 70px;
    height: 60px;
    border-radius: 8px;
    overflow: hidden;
    background: #1e1e2d;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    border: 1px solid #3a3b4c;
    box-shadow: 0 2px 6px rgba(0,0,0,0.15);
  }
  .video-thumb-container video,
  .video-thumb-container img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  .video-play-badge {
    position: absolute;
    width: 28px;
    height: 28px;
    background: rgba(220, 53, 69, 0.9);
    color: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.4);
    pointer-events: none;
    transition: transform 0.2s ease, background 0.2s ease;
  }
  .video-thumb-container:hover .video-play-badge {
    transform: scale(1.15);
    background: #dc3545;
  }
  .screen-badge {
    font-size: 0.72rem;
    padding: 0.28rem 0.55rem;
    font-weight: 500;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    gap: 3px;
    margin: 1px 2px;
  }
  .media-type-pill {
    cursor: pointer;
    user-select: none;
    transition: all 0.2s ease;
  }
  .media-type-btn-group input[type="radio"]:checked + label {
    background-color: var(--bs-primary) !important;
    color: #fff !important;
    border-color: var(--bs-primary) !important;
    box-shadow: 0 4px 10px rgba(58, 87, 232, 0.3);
  }
  .screen-checkbox-card {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 0.5rem 0.75rem;
    background: #fdfdfd;
    cursor: pointer;
    transition: all 0.2s ease;
  }
  .screen-checkbox-card:hover {
    border-color: var(--bs-primary);
    background: rgba(58, 87, 232, 0.03);
  }
  .screen-checkbox-card.selected {
    border-color: var(--bs-primary);
    background: rgba(58, 87, 232, 0.08);
  }
  .preview-box {
    border: 2px dashed #dee2e6;
    border-radius: 10px;
    padding: 10px;
    background: #f8f9fa;
    text-align: center;
    min-height: 90px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
  }
  .preview-box img,
  .preview-box video {
    max-height: 140px;
    max-width: 100%;
    border-radius: 6px;
    object-fit: cover;
  }
  .offcanvas-booking {
    width: 480px !important;
  }
  @media (max-width: 576px) {
    .offcanvas-booking {
      width: 100% !important;
    }
  }
</style>

<main class="main-content">
  <div class="position-relative iq-banner">
    <!--Nav Start-->
    <?php echo $this->include('admin/nav_bar') ?>
  </div>

  <div class="conatiner-fluid content-inner pb-0" id="page_layout">
    <div class="card">
      <div class="card-header border-bottom">
        <div class="d-flex justify-content-between flex-column flex-md-row gap-3 align-items-md-center">
          <div>
            <h4 class="card-title mb-1">Banners Management</h4>
            <p class="text-muted mb-0 small">Configure images, video banners, and target screens for mobile app and web</p>
          </div>

          <div class="btn-toolbar gap-2 align-items-stretch flex-column flex-md-row" role="toolbar" aria-label="Toolbar with buttons">
            <!-- Screen filter -->
            <div class="input-group input-group-sm" style="min-width: 170px;">
              <span class="input-group-text bg-white"><i class="icon-Filter"></i></span>
              <select id="filter-screen" class="form-select form-select-sm">
                <option value="">All Screens</option>
                <?php foreach ($screensMap as $slug => $meta): ?>
                  <option value="<?= esc($slug) ?>"><?= esc($meta['label']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <!-- Media type filter -->
            <div class="input-group input-group-sm" style="min-width: 150px;">
              <span class="input-group-text bg-white"><i class="icon-category"></i></span>
              <select id="filter-media-type" class="form-select form-select-sm">
                <option value="">All Types</option>
                <option value="image">Image Banners</option>
                <option value="video">Video Banners</option>
              </select>
            </div>

            <!-- Search box -->
            <div class="input-group flex-nowrap">
              <span class="input-group-text" id="addon-wrapping"><i class="icon-Search"></i></span>
              <input type="text" class="form-control form-control-sm dt-search" placeholder="Search banners..." aria-label="Search" aria-describedby="addon-wrapping">
            </div>

            <button type="button" class="btn btn-primary btn-sm d-flex align-items-center gap-1 add" data-bs-toggle="offcanvas" data-bs-target="#form-offcanvas">
              <i class="icon-add-new"></i>
              New Banner
            </button>
>>>>>>> origin/main
          </div>
        </div>
      </div>

      <div class="card-body p-0">
<<<<<<< HEAD
        <div class="table-responsive">
          <table id="banners-datatable" class="table table-hover align-middle mb-0">
            <thead class="table-light text-uppercase small text-muted">
              <tr>
                <th style="width: 70px;" class="text-center">Media</th>
                <th style="min-width: 180px;">Banner Info</th>
                <th style="min-width: 110px;">Media Type</th>
                <th style="min-width: 180px;">Target Screen(s)</th>
                <th style="min-width: 130px;">Action / Link</th>
                <th style="width: 110px;" class="text-center">Popup</th>
                <th style="width: 90px;" class="text-center">Status</th>
                <th style="width: 100px;" class="text-center">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($banners)) : ?>
                <?php foreach ($banners as $banner) : ?>
                  <?php 
                    $mediaType = !empty($banner->media_type) ? $banner->media_type : 'image';
                    $hasVideo = ($mediaType === 'video');
                    $videoSrc = !empty($banner->banner_video) ? base_url('public/uploads/banners/' . $banner->banner_video) : '';
                    $videoUrl = !empty($banner->video_url) ? $banner->video_url : '';
                    $hasVideoPlayback = !empty($videoSrc) || !empty($videoUrl);

                    $imgFile = !empty($banner->banner_image) ? $banner->banner_image : (!empty($banner->image) ? $banner->image : '');
                    $imgSrc = !empty($imgFile) ? base_url('public/uploads/banners/' . $imgFile) : '';

                    $screensRaw = !empty($banner->screen) ? $banner->screen : 'all';
                    $screenList = array_map('trim', explode(',', $screensRaw));
                    $isAllScreens = in_array('all', $screenList);

                    $hasPopup = !empty($banner->show_popup) && ($banner->show_popup == 1 || $banner->show_popup === '1');
                    $popupImg = !empty($banner->popup_image) ? base_url('public/uploads/banners/' . $banner->popup_image) : '';
                    $popupLink = !empty($banner->popup_link) ? $banner->popup_link : '';
                  ?>
                  <tr class="banner-row" 
                      data-id="<?= $banner->id ?>" 
                      data-media-type="<?= esc($mediaType) ?>"
                      data-screens="<?= esc($screensRaw) ?>"
                      data-popup="<?= $hasPopup ? 'yes' : 'no' ?>">
                    <!-- Media Preview Column -->
                    <td class="text-center py-3">
                      <div class="position-relative d-inline-block banner-thumbnail-wrapper" style="width: 58px; height: 58px;">
                        <?php if ($hasVideo): ?>
                          <?php if (!empty($imgSrc)): ?>
                            <img src="<?= $imgSrc ?>" alt="Poster" class="rounded-3 shadow-sm w-100 h-100 object-fit-cover border">
                          <?php else: ?>
                            <div class="w-100 h-100 rounded-3 bg-dark d-flex align-items-center justify-content-center text-white border shadow-sm">
                              <i class="fs-4">🎬</i>
                            </div>
                          <?php endif; ?>

                          <?php if ($hasVideoPlayback): ?>
                            <a href="javascript:void(0);" class="btn-play-video position-absolute top-50 start-50 translate-middle rounded-circle bg-primary bg-opacity-75 text-white d-flex align-items-center justify-content-center shadow"
                               style="width: 28px; height: 28px; transition: transform 0.2s;"
                               data-video-src="<?= esc($videoSrc) ?>"
                               data-video-url="<?= esc($videoUrl) ?>"
                               data-title="<?= esc($banner->name) ?>"
                               title="Preview Video">
                              <span style="font-size: 11px; margin-left: 2px;">▶</span>
                            </a>
                          <?php endif; ?>
                        <?php else: ?>
                          <?php if (!empty($imgSrc)): ?>
                            <a href="javascript:void(0);" class="btn-preview-image d-block w-100 h-100" data-img-src="<?= $imgSrc ?>" data-title="<?= esc($banner->name) ?>" title="Click to view image">
                              <img src="<?= $imgSrc ?>" alt="<?= esc($banner->name) ?>" class="rounded-3 shadow-sm w-100 h-100 object-fit-cover border">
                            </a>
                          <?php else: ?>
                            <div class="w-100 h-100 rounded-3 bg-light d-flex align-items-center justify-content-center text-muted border">
                              <span class="small">No Img</span>
                            </div>
                          <?php endif; ?>
                        <?php endif; ?>
                      </div>
                    </td>

                    <!-- Banner Info Column -->
                    <td>
                      <div class="fw-bold text-dark mb-1 banner-name-text"><?= esc($banner->name) ?></div>
                      <div class="d-flex flex-wrap align-items-center gap-2 small">
                        <?php if (!empty($banner->url)): ?>
                          <a href="<?= esc($banner->url) ?>" target="_blank" class="text-primary text-decoration-none text-truncate d-inline-block" style="max-width: 180px;" title="<?= esc($banner->url) ?>">
                            <i class="me-1">🔗</i><?= esc($banner->url) ?>
                          </a>
                        <?php else: ?>
                          <span class="text-muted"><i class="me-1">🔗</i>No link URL</span>
                        <?php endif; ?>

                        <?php if (!empty($banner->banner_type)): ?>
                          <span class="badge bg-light text-secondary border"><?= esc(ucfirst($banner->banner_type)) ?></span>
                        <?php endif; ?>
                      </div>
                    </td>

                    <!-- Media Type Badge Column -->
                    <td>
                      <?php if ($hasVideo): ?>
                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 rounded-pill fw-medium d-inline-flex align-items-center gap-1">
                          <span>🎥</span> Video Banner
                        </span>
                        <?php if (!empty($videoUrl)): ?>
                          <div class="small text-muted mt-1" title="External URL"><i class="icon-link me-1"></i>URL Stream</div>
                        <?php elseif (!empty($videoSrc)): ?>
                          <div class="small text-muted mt-1" title="Uploaded file"><i class="icon-download me-1"></i>File Upload</div>
                        <?php endif; ?>
                      <?php else: ?>
                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill fw-medium d-inline-flex align-items-center gap-1">
                          <span>🖼️</span> Image Banner
                        </span>
                      <?php endif; ?>
                    </td>

                    <!-- Screen(s) Column -->
                    <td>
                      <div class="d-flex flex-wrap gap-1">
                        <?php if ($isAllScreens): ?>
                          <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 rounded-pill fw-semibold">
                            📱 All Screens
                          </span>
                        <?php else: ?>
                          <?php foreach ($screenList as $scrKey): ?>
                            <?php 
                              $scrMeta = $available_screens[$scrKey] ?? ['label' => ucfirst(str_replace('_', ' ', $scrKey)), 'icon' => '📌', 'color' => 'secondary'];
                            ?>
                            <span class="badge bg-light text-dark border px-2 py-1 rounded-pill small" title="Screen: <?= esc($scrMeta['label']) ?>">
                              <span class="me-1"><?= $scrMeta['icon'] ?></span><?= esc($scrMeta['label']) ?>
                            </span>
                          <?php endforeach; ?>
                        <?php endif; ?>
                      </div>
                    </td>

                    <!-- Type & Link ID Column -->
                    <td>
                      <?php if (!empty($banner->type)): ?>
                        <div class="small fw-semibold text-dark text-capitalize">
                          <span class="badge bg-secondary-subtle text-secondary border me-1"><?= esc($banner->type) ?></span>
                        </div>
                        <div class="small text-muted text-truncate" style="max-width: 130px;">
                          <?php 
                            $matchedServiceName = null;
                            if ($banner->type === 'service' && !empty($services)) {
                              foreach ($services as $srv) {
                                if ((string)$srv->id === (string)$banner->link_id) {
                                  $matchedServiceName = $srv->name;
                                  break;
                                }
                              }
                            }
                          ?>
                          ID: <?= esc($matchedServiceName ? $matchedServiceName : ($banner->link_id ?: '-')) ?>
                        </div>
                      <?php else: ?>
                        <span class="text-muted small">None</span>
                      <?php endif; ?>
                    </td>

                    <!-- Popup Column -->
                    <td class="text-center">
                      <?php if ($hasPopup): ?>
                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1 rounded-pill fw-semibold" title="In-App Popup Active">
                          ✨ Yes
                        </span>
                        <?php if (!empty($popupImg)): ?>
                          <div class="mt-1">
                            <a href="javascript:void(0);" class="btn-preview-image small text-primary text-decoration-none" data-img-src="<?= $popupImg ?>" data-title="Popup: <?= esc($banner->name) ?>">
                              <i class="icon-image me-1"></i>View
                            </a>
                          </div>
                        <?php endif; ?>
                      <?php else: ?>
                        <span class="badge bg-light text-muted border px-2 py-1 rounded-pill">No</span>
                      <?php endif; ?>
                    </td>

                    <!-- Status Switch Column -->
                    <td class="text-center">
                      <div class="form-check form-switch d-inline-block">
                        <input type="checkbox" class="form-check-input switch-status cursor-pointer" 
                               data-id="<?= $banner->id ?>" 
                               role="switch"
                               title="Toggle banner active status"
                               <?= (!empty($banner->is_active) && $banner->is_active == 1) ? 'checked' : '' ?>>
                      </div>
                    </td>

                    <!-- Actions Column -->
                    <td class="text-center">
                      <div class="d-flex justify-content-center align-items-center gap-1">
                        <!-- Edit Button -->
                        <button type="button" class="btn btn-sm btn-icon btn-soft-primary edit-banner-btn"
                                data-id="<?= $banner->id ?>"
                                data-name="<?= esc($banner->name) ?>"
                                data-url="<?= esc($banner->url) ?>"
                                data-type="<?= esc($banner->type) ?>"
                                data-link-id="<?= esc($banner->link_id) ?>"
                                data-media-type="<?= esc($mediaType) ?>"
                                data-banner-video="<?= esc($banner->banner_video ?? '') ?>"
                                data-video-url="<?= esc($banner->video_url ?? '') ?>"
                                data-screens="<?= esc($screensRaw) ?>"
                                data-banner-image="<?= esc($imgFile) ?>"
                                data-image-url="<?= esc($imgSrc) ?>"
                                data-show-popup="<?= $hasPopup ? '1' : '0' ?>"
                                data-popup-image="<?= esc($banner->popup_image ?? '') ?>"
                                data-popup-img-url="<?= esc($popupImg) ?>"
                                data-popup-link="<?= esc($popupLink) ?>"
                                data-banner-type="<?= esc($banner->banner_type ?? 'promo') ?>"
                                title="Edit Banner">
                          <i class="icon-edit fs-6"></i>
                        </button>

                        <!-- Delete Button -->
                        <button type="button" class="btn btn-sm btn-icon btn-soft-danger delete-btn" 
                                data-id="<?= $banner->id ?>" 
                                data-name="<?= esc($banner->name) ?>"
                                title="Delete Banner">
                          <i class="icon-delete fs-6"></i>
                        </button>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else : ?>
                <tr id="empty-state-row">
                  <td colspan="8" class="text-center py-5">
                    <div class="text-muted">
                      <i class="fs-1 mb-2 d-block">🖼️</i>
                      <h6 class="fw-bold">No Banners Found</h6>
                      <p class="small text-muted mb-3">Get started by creating your first promotional app banner.</p>
                      <button type="button" class="btn btn-primary btn-sm btn-new-banner" data-bs-toggle="offcanvas" data-bs-target="#form-offcanvas">
                        <i class="icon-add-new me-1"></i> Create Banner
                      </button>
                    </div>
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
=======
        <div id="datatable_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
          <div class="table-responsive my-2">
            <div id="datatable_processing" class="dataTables_processing card" style="display: none;">Processing...</div>
            <table id="datatable" class="table table-hover align-middle border mb-0">
              <thead class="table-light">
                <tr>
                  <th style="width: 5%;">#</th>
                  <th style="width: 10%;">Type</th>
                  <th style="width: 15%;">Banner Preview</th>
                  <th style="width: 25%;">Target Screen(s)</th>
                  <th style="width: 15%;">Popup Config</th>
                  <th style="width: 12%;">Status</th>
                  <th class="text-end" style="width: 12%;">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($banners)): ?>
                  <?php foreach ($banners as $banner): ?>
                    <?php
                      $isMediaVideo = (isset($banner->media_type) && $banner->media_type === 'video');
                      $screens = !empty($banner->screen) ? explode(',', $banner->screen) : ['all'];
                    ?>
                    <tr class="banner-row" data-media-type="<?= esc($banner->media_type ?? 'image') ?>" data-screens="<?= esc($banner->screen ?? 'all') ?>">
                      <td><span class="fw-bold text-secondary">#<?= esc($banner->id) ?></span></td>
                      
                      <!-- Media Type Badge -->
                      <td>
                        <?php if ($isMediaVideo): ?>
                          <span class="badge bg-danger d-inline-flex align-items-center gap-1 px-2 py-1">
                            <i class="icon-play"></i> Video
                          </span>
                        <?php else: ?>
                          <span class="badge bg-primary d-inline-flex align-items-center gap-1 px-2 py-1">
                            <i class="icon-image"></i> Image
                          </span>
                        <?php endif; ?>
                      </td>

                      <!-- Media Preview -->
                      <td>
                        <?php if ($isMediaVideo): ?>
                          <?php
                            $videoSrc = '';
                            if (!empty($banner->banner_video)) {
                              $videoSrc = base_url('public/uploads/banners/' . $banner->banner_video);
                            } elseif (!empty($banner->video_url)) {
                              $videoSrc = $banner->video_url;
                            }
                            $posterSrc = !empty($banner->banner_image) ? base_url('public/uploads/banners/' . $banner->banner_image) : '';
                          ?>
                          <div class="video-thumb-container preview-video-btn"
                               data-video-src="<?= esc($videoSrc) ?>"
                               data-poster-src="<?= esc($posterSrc) ?>"
                               data-title="Banner #<?= esc($banner->id) ?> Video"
                               title="Click to play video">
                            <?php if (!empty($posterSrc)): ?>
                              <img src="<?= $posterSrc ?>" alt="video poster">
                            <?php elseif (!empty($videoSrc)): ?>
                              <video src="<?= $videoSrc ?>#t=0.5" preload="metadata"></video>
                            <?php else: ?>
                              <i class="icon-play text-white fs-4"></i>
                            <?php endif; ?>
                            <div class="video-play-badge"><i class="icon-play"></i></div>
                          </div>
                          <?php if (!empty($banner->video_url)): ?>
                            <div class="mt-1">
                              <a href="<?= esc($banner->video_url) ?>" target="_blank" class="small text-muted text-truncate d-inline-block" style="max-width: 140px;">
                                <i class="icon-link me-1"></i>External link
                              </a>
                            </div>
                          <?php endif; ?>
                        <?php else: ?>
                          <?php if (!empty($banner->banner_image)): ?>
                            <a href="<?= base_url('public/uploads/banners/' . $banner->banner_image) ?>" target="_blank" title="View full image">
                              <img src="<?= base_url('public/uploads/banners/' . $banner->banner_image) ?>" alt="banner" class="banner-thumb-img">
                            </a>
                          <?php else: ?>
                            <span class="text-muted small italic">No image</span>
                          <?php endif; ?>
                        <?php endif; ?>
                      </td>

                      <!-- Target Screens -->
                      <td>
                        <div class="d-flex flex-wrap gap-1 align-items-center">
                          <?php if (in_array('all', $screens) || empty($banner->screen)): ?>
                            <span class="screen-badge bg-primary text-white">
                              <i class="icon-category"></i> All Screens
                            </span>
                          <?php else: ?>
                            <?php foreach ($screens as $scr): ?>
                              <?php
                                $scrKey = trim($scr);
                                $badgeClass = $screensMap[$scrKey]['badge'] ?? 'bg-light text-dark border';
                                $label = $screensMap[$scrKey]['label'] ?? ucfirst(str_replace('_', ' ', $scrKey));
                              ?>
                              <span class="screen-badge <?= $badgeClass ?>">
                                <?= esc($label) ?>
                              </span>
                            <?php endforeach; ?>
                          <?php endif; ?>
                        </div>
                      </td>

                      <!-- Popup Config -->
                      <td>
                        <div class="d-flex align-items-center gap-2">
                          <?php if ($banner->show_popup == '1' || $banner->show_popup === 1): ?>
                            <span class="badge bg-success-subtle text-success border border-success-subtle">Popup Enabled</span>
                            <?php if (!empty($banner->popup_image)): ?>
                              <a href="<?= base_url('public/uploads/banners/' . $banner->popup_image) ?>" target="_blank" title="Popup Image">
                                <img src="<?= base_url('public/uploads/banners/' . $banner->popup_image) ?>" alt="popup" class="rounded" style="width: 32px; height: 32px; object-fit: cover;">
                              </a>
                            <?php endif; ?>
                            <?php if (!empty($banner->popup_link)): ?>
                              <a href="<?= esc($banner->popup_link) ?>" target="_blank" class="text-primary fs-6" title="<?= esc($banner->popup_link) ?>">
                                <i class="icon-link"></i>
                              </a>
                            <?php endif; ?>
                          <?php else: ?>
                            <span class="badge bg-light text-muted border">Disabled</span>
                          <?php endif; ?>
                        </div>
                      </td>

                      <!-- Status -->
                      <td>
                        <div class="form-check form-switch d-inline-block">
                          <input type="checkbox" class="form-check-input switch-status" data-id="<?= $banner->id ?>" <?= $banner->is_active == '1' ? 'checked' : '' ?>>
                          <span class="status-label small ms-1 <?= $banner->is_active == '1' ? 'text-success fw-bold' : 'text-muted' ?>">
                            <?= $banner->is_active == '1' ? 'Active' : 'Inactive' ?>
                          </span>
                        </div>
                      </td>

                      <!-- Actions -->
                      <td class="text-end">
                        <div class="d-flex gap-2 justify-content-end align-items-center">
                          <button type="button" class="btn btn-sm btn-outline-primary edit-btn"
                            data-id="<?= $banner->id ?>"
                            data-media-type="<?= esc($banner->media_type ?? 'image') ?>"
                            data-screens="<?= esc($banner->screen ?? 'all') ?>"
                            data-banner-image="<?= esc($banner->banner_image ?? '') ?>"
                            data-banner-video="<?= esc($banner->banner_video ?? '') ?>"
                            data-video-url="<?= esc($banner->video_url ?? '') ?>"
                            data-show-popup="<?= esc($banner->show_popup ?? '0') ?>"
                            data-popup-image="<?= esc($banner->popup_image ?? '') ?>"
                            data-popup-link="<?= esc($banner->popup_link ?? '') ?>"
                            data-bs-toggle="tooltip" title="Edit Banner">
                            <i class="icon-edit"></i> Edit
                          </button>
                          <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-id="<?= $banner->id ?>" data-bs-toggle="tooltip" title="Delete Banner">
                            <i class="icon-delete"></i>
                          </button>
                        </div>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="7" class="text-center py-4 text-muted">
                      <i class="icon-image fs-1 d-block mb-2 opacity-50"></i>
                      No banners created yet. Click "New Banner" to create one.
                    </td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
>>>>>>> origin/main
        </div>
      </div>
    </div>

<<<<<<< HEAD
    <!-- Create / Edit Offcanvas Form -->
    <div id="banner-form-container">
      <form id="banner-form" enctype="multipart/form-data">
        <?= csrf_field () ?>
        <input type="hidden" name="id" id="banner_id" value="">

        <div class="offcanvas offcanvas-end offcanvas-booking shadow" tabindex="-1" id="form-offcanvas" aria-labelledby="form-offcanvasLabel" style="width: 540px; max-width: 95vw;">
          <div class="offcanvas-header bg-light border-bottom py-3">
            <div>
              <h5 class="offcanvas-title fw-bold text-dark" id="form-offcanvasLabel">
                <span id="offcanvas-title-text">Create App Banner</span>
              </h5>
              <p class="text-muted small mb-0" id="offcanvas-subtitle-text">Fill in banner content, media, target screens, and popups.</p>
            </div>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
          </div>

          <div class="offcanvas-body p-4">
            <!-- 1. Media Type Selector (Image vs Video) -->
            <div class="mb-4">
              <label class="form-label fw-bold small text-uppercase text-muted">Media Type <span class="text-danger">*</span></label>
              <div class="row g-2">
                <div class="col-6">
                  <input type="radio" class="btn-check" name="media_type" id="media_type_image" value="image" checked>
                  <label class="btn btn-outline-primary w-100 py-2 d-flex flex-column align-items-center justify-content-center h-100" for="media_type_image">
                    <span class="fs-4 mb-1">🖼️</span>
                    <span class="fw-semibold">Image Banner</span>
                    <small class="text-muted" style="font-size: 11px;">Standard graphic banner</small>
                  </label>
                </div>
                <div class="col-6">
                  <input type="radio" class="btn-check" name="media_type" id="media_type_video" value="video">
                  <label class="btn btn-outline-primary w-100 py-2 d-flex flex-column align-items-center justify-content-center h-100" for="media_type_video">
                    <span class="fs-4 mb-1">🎥</span>
                    <span class="fw-semibold">Video Banner</span>
                    <small class="text-muted" style="font-size: 11px;">MP4 upload or video URL</small>
                  </label>
                </div>
              </div>
            </div>

            <!-- 2. Basic Information -->
            <div class="card border mb-4 shadow-none bg-light-subtle">
              <div class="card-body p-3">
                <h6 class="fw-bold text-dark mb-3"><i class="me-1">📝</i> Banner Details</h6>

                <div class="mb-3">
                  <label class="form-label small fw-semibold" for="banner_name">Banner Title / Name <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="name" id="banner_name" placeholder="e.g. Summer Dog Grooming Special" required>
                </div>

                <div class="mb-3">
                  <label class="form-label small fw-semibold" for="banner_url">Click Destination URL</label>
                  <input type="url" class="form-control" name="url" id="banner_url" placeholder="https://petsfolio.in/offers/summer">
                  <div class="form-text small">Optional external or deep link destination when tapped.</div>
                </div>

                <div class="row g-2">
                  <div class="col-md-6 mb-2">
                    <label class="form-label small fw-semibold" for="banner_nav_type">App Action Type</label>
                    <select class="form-select" name="type" id="banner_nav_type">
                      <option value="">None / Custom URL</option>
                      <option value="category">Category</option>
                      <option value="service">Service</option>
                    </select>
                  </div>

                  <div class="col-md-6 mb-2" id="link-id-container">
                    <label class="form-label small fw-semibold" for="link_id">Select Service / Item</label>
                    <select class="form-select" name="link_id" id="link_id">
                      <option value="">Select Target</option>
                      <?php if (!empty($services)): ?>
                        <?php foreach ($services as $srv): ?>
                          <option value="<?= $srv->id ?>"><?= esc($srv->name) ?></option>
                        <?php endforeach; ?>
                      <?php endif; ?>
                    </select>
                    <input type="text" class="form-control d-none mt-1" name="link_id_text" id="link_id_text" placeholder="Enter custom identifier">
                  </div>
                </div>

                <div class="mt-2">
                  <label class="form-label small fw-semibold" for="banner_type">Banner Category Tag</label>
                  <select class="form-select form-select-sm" name="banner_type" id="banner_type">
                    <option value="promo" selected>Promo</option>
                    <option value="service">Service</option>
                    <option value="seasonal">Seasonal</option>
                    <option value="emergency">Emergency / Alert</option>
                  </select>
                </div>
              </div>
            </div>

            <!-- 3. Image Banner Configuration Section -->
            <div id="image-config-section" class="card border mb-4 shadow-none">
              <div class="card-body p-3">
                <h6 class="fw-bold text-dark mb-3"><i class="me-1">🖼️</i> Image Banner Asset</h6>

                <div class="mb-3">
                  <label class="form-label small fw-semibold" for="feature_image">
                    Upload Banner Image <span class="text-danger" id="feature-image-required-asterisk">*</span>
                  </label>
                  <input type="file" class="form-control" name="feature_image" id="feature_image" accept=".jpeg, .jpg, .png, .webp, .gif">
                  <div class="form-text small">Recommended: 1200x600 px (2:1 ratio) or 1080x1080 px, up to 5MB. Formats: JPG, PNG, WEBP.</div>
                </div>

                <!-- Live / Current Image Preview -->
                <div id="image-preview-box" class="p-2 border rounded-3 bg-light text-center d-none">
                  <div class="small text-muted mb-2 fw-semibold" id="image-preview-label">Image Preview:</div>
                  <img id="img-preview-tag" src="" alt="Preview" class="img-fluid rounded-2 shadow-sm border" style="max-height: 180px; object-fit: contain;">
                </div>
              </div>
            </div>

            <!-- 4. Video Banner Configuration Section -->
            <div id="video-config-section" class="card border mb-4 shadow-none d-none">
              <div class="card-body p-3">
                <h6 class="fw-bold text-dark mb-2"><i class="me-1">🎥</i> Video Banner Assets</h6>
                <p class="text-muted small mb-3">Provide an uploaded video file or an external streaming video URL.</p>

                <!-- Video File Upload -->
                <div class="mb-3">
                  <label class="form-label small fw-semibold" for="banner_video">
                    Upload Video File
                  </label>
                  <input type="file" class="form-control" name="banner_video" id="banner_video" accept=".mp4, .webm, .ogg, .mov, .mkv">
                  <div class="form-text small">Supported: MP4, WebM, MOV. Max recommended: 25MB for quick loading.</div>
                </div>

                <!-- Video URL Option -->
                <div class="mb-3">
                  <label class="form-label small fw-semibold" for="video_url">
                    Or Video URL (YouTube, Vimeo, or Direct Link)
                  </label>
                  <input type="url" class="form-control" name="video_url" id="video_url" placeholder="https://example.com/videos/promo.mp4 or YouTube link">
                  <div class="form-text small">Direct MP4 URL or embeddable video link.</div>
                </div>

                <!-- Video Poster / Thumbnail -->
                <div class="mb-3">
                  <label class="form-label small fw-semibold" for="banner_image_poster">
                    Video Poster / Fallback Image
                  </label>
                  <input type="file" class="form-control" name="banner_image_poster" id="banner_image_poster" accept=".jpeg, .jpg, .png, .webp, .gif">
                  <div class="form-text small">Displayed while the video buffers or on devices with autoplay restricted.</div>
                </div>

                <!-- Current Video Asset Indicator / Preview -->
                <div id="video-preview-box" class="p-2 border rounded-3 bg-light d-none mt-2">
                  <div class="small text-muted mb-2 fw-semibold">Current Video Asset:</div>
                  <video id="video-preview-tag" controls class="w-100 rounded-2 shadow-sm border" style="max-height: 200px; display: none;"></video>
                  <div id="video-link-preview" class="small text-truncate mt-1 text-primary"></div>
=======
    <!-- Offcanvas Form Container -->
    <div id="banner-form-container">
      <form id="add-banner-form" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <input type="hidden" name="id" id="banner_id">
        <div class="offcanvas offcanvas-end offcanvas-booking shadow" tabindex="-1" id="form-offcanvas" aria-labelledby="form-offcanvasLabel">
          <div class="offcanvas-header border-bottom bg-light">
            <h5 class="offcanvas-title d-flex align-items-center gap-2" id="form-offcanvasLabel">
              <i class="icon-image text-primary"></i>
              <span id="form-title">Create App Banner</span>
            </h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
          </div>

          <div class="offcanvas-body">
            <!-- 1. Media Type Selector -->
            <div class="mb-4">
              <label class="form-label fw-bold text-dark">Banner Media Type <span class="text-danger">*</span></label>
              <div class="btn-group w-100 media-type-btn-group" role="group" aria-label="Media Type">
                <input type="radio" class="btn-check" name="media_type" id="media_type_image" value="image" checked>
                <label class="btn btn-outline-primary py-2 d-flex align-items-center justify-content-center gap-2" for="media_type_image">
                  <i class="icon-image fs-5"></i> Image Banner
                </label>

                <input type="radio" class="btn-check" name="media_type" id="media_type_video" value="video">
                <label class="btn btn-outline-primary py-2 d-flex align-items-center justify-content-center gap-2" for="media_type_video">
                  <i class="icon-play fs-5"></i> Video Banner
                </label>
              </div>
            </div>

            <!-- 2. Image Banner Section -->
            <div id="image-banner-section" class="mb-3">
              <div class="form-group mb-2">
                <label class="form-label fw-bold" for="banner_image">
                  Banner Image <span class="text-danger" id="banner_image_required_star">*</span>
                </label>
                <input type="file" class="form-control" name="banner_image" id="banner_image" accept="image/jpeg,image/png,image/gif,image/webp">
                <div class="form-text">Recommended size: 1200x500px (JPG, PNG, WebP)</div>
              </div>
              <div id="image-preview-container" class="preview-box d-none mt-2">
                <div class="small text-muted mb-1 fw-bold">Image Preview:</div>
                <img id="image-preview-element" src="" alt="preview">
                <div id="image-current-label" class="badge bg-secondary mt-1 d-none">Current Image</div>
              </div>
            </div>

            <!-- 3. Video Banner Section -->
            <div id="video-banner-section" class="mb-3 d-none">
              <div class="p-3 bg-light rounded-3 border mb-3">
                <h6 class="fw-bold text-dark mb-2"><i class="icon-play text-danger me-1"></i> Video Options</h6>
                
                <!-- Video File Upload -->
                <div class="form-group mb-3">
                  <label class="form-label fw-bold" for="banner_video">Upload Video File</label>
                  <input type="file" class="form-control" name="banner_video" id="banner_video" accept="video/mp4,video/webm,video/ogg,video/quicktime,video/x-matroska">
                  <div class="form-text">Supported formats: MP4, WebM, MOV, OGG (Max: 50MB)</div>
                </div>

                <div class="text-center my-2 text-muted fw-bold small">— OR —</div>

                <!-- Video URL -->
                <div class="form-group mb-2">
                  <label class="form-label fw-bold" for="video_url">Video Stream / External URL</label>
                  <input type="url" class="form-control" name="video_url" id="video_url" placeholder="https://example.com/video.mp4 or YouTube / Vimeo URL">
                  <div class="form-text">Direct MP4 link, AWS S3/CloudFront URL, or video stream</div>
                </div>

                <!-- Optional Video Poster -->
                <div class="form-group mt-3 pt-2 border-top">
                  <label class="form-label fw-bold" for="video_poster_image">
                    Video Thumbnail / Poster Image <span class="text-muted fw-normal">(Optional)</span>
                  </label>
                  <input type="file" class="form-control" name="video_poster_image" id="video_poster_image" accept="image/*">
                  <div class="form-text">Preview image displayed before the video begins playback</div>
                </div>

                <!-- Video Preview -->
                <div id="video-preview-container" class="preview-box d-none mt-3">
                  <div class="small text-muted mb-1 fw-bold">Video Preview:</div>
                  <video id="video-preview-element" controls style="max-height: 140px; max-width: 100%;"></video>
                  <div id="video-current-label" class="badge bg-info text-dark mt-1 d-none">Current Video</div>
>>>>>>> origin/main
                </div>
              </div>
            </div>

<<<<<<< HEAD
            <!-- 5. Screen Configuration Section -->
            <div class="card border mb-4 shadow-none">
              <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <h6 class="fw-bold text-dark mb-0"><i class="me-1">📱</i> Target Screen Configuration <span class="text-danger">*</span></h6>
                  <button type="button" class="btn btn-xs btn-outline-primary py-0 px-2 small" id="btn-toggle-all-screens">Select All</button>
                </div>
                <p class="text-muted small mb-3">Choose the app screen(s) where this banner will appear.</p>

                <div class="row g-2" id="screen-checkboxes-grid">
                  <!-- All Screens Pill Card -->
                  <div class="col-12">
                    <div class="form-check p-2 border rounded-3 bg-primary-subtle border-primary-subtle d-flex align-items-center">
                      <input class="form-check-input ms-1 me-2 screen-checkbox" type="checkbox" name="screens[]" value="all" id="screen_all" checked>
                      <label class="form-check-label fw-bold text-primary flex-grow-1 cursor-pointer" for="screen_all">
                        📱 All Screens (Display Everywhere)
                      </label>
                    </div>
                  </div>

                  <!-- Specific Screens -->
                  <?php if (!empty($available_screens)): ?>
                    <?php foreach ($available_screens as $scrKey => $scrMeta): ?>
                      <?php if ($scrKey === 'all') continue; ?>
                      <div class="col-6">
                        <div class="form-check p-2 border rounded-3 bg-white h-100 d-flex align-items-center">
                          <input class="form-check-input ms-1 me-2 screen-checkbox specific-screen-checkbox" type="checkbox" name="screens[]" value="<?= esc($scrKey) ?>" id="screen_<?= esc($scrKey) ?>">
                          <label class="form-check-label small fw-semibold text-dark flex-grow-1 cursor-pointer" for="screen_<?= esc($scrKey) ?>">
                            <span class="me-1"><?= $scrMeta['icon'] ?></span><?= esc($scrMeta['label']) ?>
                          </label>
                        </div>
                      </div>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </div>
              </div>
            </div>

            <!-- 6. In-App Popup Configuration -->
            <div class="card border mb-3 shadow-none">
              <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                  <div>
                    <h6 class="fw-bold text-dark mb-1"><i class="me-1">✨</i> Show as In-App Modal / Popup</h6>
                    <div class="text-muted small">Display this banner as an interactive takeover popup on screen entry.</div>
                  </div>
                  <div class="form-check form-switch ms-3">
                    <input class="form-check-input fs-5 cursor-pointer" type="checkbox" name="show_popup" id="show_popup_toggle" value="1">
                  </div>
                </div>

                <!-- Collapsible Popup Settings -->
                <div id="popup-settings-collapse" class="mt-3 pt-3 border-top d-none">
                  <div class="mb-3">
                    <label class="form-label small fw-semibold" for="popup_image">Popup Graphic Image (Optional)</label>
                    <input type="file" class="form-control form-control-sm" name="popup_image" id="popup_image" accept=".jpeg, .jpg, .png, .webp">
                    <div class="form-text small">Leave blank to automatically use the main banner graphic.</div>
                  </div>

                  <div id="popup-img-preview-box" class="p-2 border rounded-2 bg-light text-center d-none mb-3">
                    <div class="small text-muted mb-1">Current Popup Graphic:</div>
                    <img id="popup-img-tag" src="" alt="Popup" class="img-fluid rounded-1 border" style="max-height: 120px;">
                  </div>

                  <div class="mb-2">
                    <label class="form-label small fw-semibold" for="popup_link">Popup Button Link / Deep Link</label>
                    <input type="text" class="form-control form-control-sm" name="popup_link" id="popup_link" placeholder="e.g. app://book-now or https://...">
                  </div>
=======
            <hr class="my-3">

            <!-- 4. Target Screen(s) Configuration -->
            <div class="mb-4">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <label class="form-label fw-bold text-dark mb-0">
                  Target Screen(s) <span class="text-danger">*</span>
                </label>
                <div class="btn-group btn-group-sm">
                  <button type="button" class="btn btn-outline-secondary py-0 px-2" id="select-all-screens">Select All</button>
                  <button type="button" class="btn btn-outline-secondary py-0 px-2" id="clear-screens">Clear</button>
                </div>
              </div>
              <p class="text-muted small mb-2">Specify on which screen(s) this banner should be presented:</p>

              <div class="row g-2" id="screens-checkbox-group">
                <!-- All Screens Option -->
                <div class="col-12">
                  <div class="screen-checkbox-card selected" id="card-screen-all">
                    <div class="form-check m-0">
                      <input class="form-check-input screen-checkbox" type="checkbox" name="screens[]" value="all" id="screen_all" checked>
                      <label class="form-check-label fw-bold text-primary w-100" for="screen_all">
                        <i class="icon-category me-1"></i> All Screens (Display Globally)
                      </label>
                    </div>
                  </div>
                </div>

                <!-- Individual Specific Screens -->
                <?php foreach ($screensMap as $slug => $meta): ?>
                  <?php if ($slug === 'all') continue; ?>
                  <div class="col-6">
                    <div class="screen-checkbox-card" id="card-screen-<?= esc($slug) ?>">
                      <div class="form-check m-0">
                        <input class="form-check-input screen-checkbox specific-screen-checkbox" type="checkbox" name="screens[]" value="<?= esc($slug) ?>" id="screen_<?= esc($slug) ?>">
                        <label class="form-check-label small w-100" for="screen_<?= esc($slug) ?>">
                          <?= esc($meta['label']) ?>
                        </label>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>

            <hr class="my-3">

            <!-- 5. Popup Configuration -->
            <div class="mb-3">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <label class="form-label fw-bold text-dark mb-0">Show Clickable Popup Modal</label>
                <div class="form-check form-switch m-0">
                  <input type="checkbox" class="form-check-input" id="toggle_show_popup" value="1">
                </div>
              </div>
              <input type="hidden" name="show_popup" id="show_popup" value="0">

              <div id="popup-details-container" class="p-3 bg-light rounded-3 border d-none mt-2">
                <div class="form-group mb-3">
                  <label class="form-label fw-bold" for="popup_image">Popup Image</label>
                  <input type="file" class="form-control" name="popup_image" id="popup_image" accept=".jpeg, .jpg, .png, .gif, .webp">
                  <div class="form-text">Image displayed inside the expanded popup modal</div>
                  <div id="popup-preview-container" class="preview-box d-none mt-2">
                    <img id="popup-preview-element" src="" alt="popup preview">
                    <div id="popup-current-label" class="badge bg-secondary mt-1 d-none">Current Popup Image</div>
                  </div>
                </div>

                <div class="form-group mb-0">
                  <label class="form-label fw-bold" for="popup_link">Popup Redirect Link</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="icon-link"></i></span>
                    <input type="url" class="form-control" name="popup_link" id="popup_link" placeholder="https://www.petsfolio.com/...">
                  </div>
                  <div class="form-text">Optional URL destination when user clicks the popup banner</div>
>>>>>>> origin/main
                </div>
              </div>
            </div>
          </div>

<<<<<<< HEAD
          <!-- Offcanvas Footer -->
          <div class="offcanvas-footer border-top bg-white p-3">
            <div class="d-flex gap-2 justify-content-end">
              <button class="btn btn-outline-secondary px-4" type="button" data-bs-dismiss="offcanvas">
                Cancel
              </button>
              <button class="btn btn-primary d-flex align-items-center gap-2 px-4 shadow-sm" type="submit" id="save-banner-button">
                <span class="spinner-border spinner-border-sm d-none" id="save-spinner" role="status" aria-hidden="true"></span>
                <span id="save-button-text">Save Banner</span>
                <i class="icon-disk" id="save-button-icon"></i>
=======
          <div class="offcanvas-footer border-top bg-light p-3">
            <div class="d-flex gap-2 justify-content-end">
              <button class="btn btn-soft-secondary d-flex align-items-center gap-1" type="button" data-bs-dismiss="offcanvas">
                Cancel
              </button>
              <button class="btn btn-primary d-flex align-items-center gap-1 px-4" type="submit" id="save-button">
                <i class="icon-disk me-1"></i> Save Banner
>>>>>>> origin/main
              </button>
            </div>
          </div>
        </div>
      </form>
    </div>

    <!-- Video Preview Modal -->
    <div class="modal fade" id="videoPreviewModal" tabindex="-1" aria-labelledby="videoPreviewModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
<<<<<<< HEAD
        <div class="modal-content bg-dark text-white border-0 shadow-lg overflow-hidden">
          <div class="modal-header border-secondary py-2 px-3">
            <h6 class="modal-title fw-bold text-white d-flex align-items-center gap-2" id="videoPreviewModalLabel">
              <span>🎥</span> <span id="modal-video-title">Video Preview</span>
            </h6>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body p-0 bg-black text-center position-relative d-flex align-items-center justify-content-center" style="min-height: 380px;">
            <!-- HTML5 Video Player -->
            <video id="modal-video-player" controls class="w-100" style="max-height: 70vh; display: none;">
              <source id="modal-video-source" src="" type="video/mp4">
              Your browser does not support HTML video.
            </video>

            <!-- Video Iframe (For YouTube / Vimeo embeds) -->
            <iframe id="modal-video-iframe" class="w-100" style="height: 450px; display: none;" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>

            <!-- Loading Spinner -->
            <div id="modal-video-loading" class="text-white py-5">
              <div class="spinner-border text-primary mb-2" role="status"></div>
              <div class="small">Loading Video...</div>
            </div>
          </div>
          <div class="modal-footer border-secondary py-2 px-3 justify-content-between">
            <span class="small text-muted" id="modal-video-meta"></span>
            <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Image Lightbox Modal -->
    <div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-labelledby="imagePreviewModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
          <div class="modal-header py-2 px-3 border-bottom">
            <h6 class="modal-title fw-bold" id="imagePreviewModalLabel">
              <span>🖼️</span> <span id="modal-image-title">Image Preview</span>
            </h6>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body p-3 text-center bg-light">
            <img id="modal-image-tag" src="" alt="Banner Preview" class="img-fluid rounded shadow-sm" style="max-height: 75vh; object-fit: contain;">
          </div>
=======
        <div class="modal-content border-0 shadow-lg bg-dark text-white">
          <div class="modal-header border-secondary">
            <h5 class="modal-title text-white" id="videoPreviewModalLabel">Banner Video Preview</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body p-0 text-center bg-black" style="min-height: 300px;">
            <video id="modal-video-player" controls autoplay style="max-height: 70vh; width: 100%; outline: none;" poster="">
              <source id="modal-video-source" src="" type="video/mp4">
              Your browser does not support the video tag.
            </video>
            <div id="modal-external-link-container" class="p-3 d-none">
              <p class="text-white-50 mb-2">This is an external video URL:</p>
              <a href="#" id="modal-external-link-btn" target="_blank" class="btn btn-primary">
                <i class="icon-link me-1"></i> Open Video Link in New Tab
              </a>
            </div>
          </div>
>>>>>>> origin/main
        </div>
      </div>
    </div>

  </div>
</main>
<<<<<<< HEAD
<?php echo $this->include ( 'admin/footer' ) ?>

<style>
  .cursor-pointer { cursor: pointer; }
  .banner-thumbnail-wrapper { transition: transform 0.2s ease; }
  .banner-thumbnail-wrapper:hover { transform: scale(1.05); }
  .btn-soft-primary {
    background-color: rgba(13, 110, 253, 0.1);
    color: #0d6efd;
    border: none;
  }
  .btn-soft-primary:hover {
    background-color: #0d6efd;
    color: #fff;
  }
  .btn-soft-danger {
    background-color: rgba(220, 53, 69, 0.1);
    color: #dc3545;
    border: none;
  }
  .btn-soft-danger:hover {
    background-color: #dc3545;
    color: #fff;
  }
  .screen-checkbox:checked + label {
    font-weight: 700;
  }
</style>

<script>
  // Services data passed from backend
  var servicesList = <?= json_encode ( $services ?? [] ) ?>;
  var availableScreens = <?= json_encode ( $available_screens ?? [] ) ?>;

  $(document).ready(function () {
    // CSRF Management Helper
    var csrfName = '<?= csrf_token () ?>';
    var csrfHash = '<?= csrf_hash () ?>';

    function getCsrfData() {
=======
<?php echo $this->include('admin/footer') ?>

<script>
  $(document).ready(function() {
    var BASE_UPLOAD_URL = '<?= base_url("public/uploads/banners/") ?>/';

    // CSRF Token helper
    function getCsrfData() {
      var csrfName = '<?= csrf_token() ?>';
      var csrfHash = $('input[name="' + csrfName + '"]').val();
>>>>>>> origin/main
      var data = {};
      data[csrfName] = csrfHash;
      return data;
    }

<<<<<<< HEAD
    function updateCsrf(newHash) {
      if (newHash) {
        csrfHash = newHash;
        $('input[name="' + csrfName + '"]').val(newHash);
      }
    }

    // -------------------------------------------------------------
    // Offcanvas: New Banner Handler
    // -------------------------------------------------------------
    $('.btn-new-banner').on('click', function () {
      resetBannerForm();
    });

    function resetBannerForm() {
      $('#banner-form')[0].reset();
      $('#banner_id').val('');
      $('#offcanvas-title-text').text('Create App Banner');
      $('#offcanvas-subtitle-text').text('Configure banner content, media, target screens, and popups.');
      $('#save-button-text').text('Save Banner');

      // Reset Media Type to Image
      $('#media_type_image').prop('checked', true).trigger('change');

      // Feature image is required on create
      $('#feature-image-required-asterisk').removeClass('d-none');
      $('#feature_image').prop('required', true);

      // Clear previews
      $('#image-preview-box').addClass('d-none');
      $('#img-preview-tag').attr('src', '');
      $('#video-preview-box').addClass('d-none');
      $('#video-preview-tag').attr('src', '').hide();
      $('#video-link-preview').text('');
      $('#popup-img-preview-box').addClass('d-none');
      $('#popup-img-tag').attr('src', '');

      // Reset Screens: check "all" by default, uncheck others
      $('.screen-checkbox').prop('checked', false);
      $('#screen_all').prop('checked', true);

      // Reset Popup
      $('#show_popup_toggle').prop('checked', false).trigger('change');

      // Reset Type/Link
      $('#banner_nav_type').val('').trigger('change');
      $('#banner_type').val('promo');
    }

    // -------------------------------------------------------------
    // Media Type Toggle Handler
    // -------------------------------------------------------------
    $('input[name="media_type"]').on('change', function () {
      var selectedType = $('input[name="media_type"]:checked').val();
      if (selectedType === 'video') {
        $('#video-config-section').removeClass('d-none');
        $('#feature-image-required-asterisk').addClass('d-none');
        $('#feature_image').prop('required', false);
      } else {
        $('#video-config-section').addClass('d-none');
        if (!$('#banner_id').val()) {
          $('#feature-image-required-asterisk').removeClass('d-none');
          $('#feature_image').prop('required', true);
        }
      }
    });

    // -------------------------------------------------------------
    // Action Type Selector (Service vs Category)
    // -------------------------------------------------------------
    $('#banner_nav_type').on('change', function () {
      var typeVal = $(this).val();
      var linkSelect = $('#link_id');
      var linkText = $('#link_id_text');

      if (typeVal === 'service') {
        linkSelect.removeClass('d-none');
        linkText.addClass('d-none');
      } else if (typeVal === 'category') {
        linkSelect.addClass('d-none');
        linkText.removeClass('d-none');
      } else {
        linkSelect.removeClass('d-none');
        linkText.addClass('d-none');
      }
    });

    // -------------------------------------------------------------
    // Screen Checkbox Logic
    // -------------------------------------------------------------
    // If "All Screens" is checked, uncheck specific screens. If a specific screen is checked, uncheck "All".
    $('#screen_all').on('change', function () {
      if ($(this).is(':checked')) {
        $('.specific-screen-checkbox').prop('checked', false);
      }
    });

    $('.specific-screen-checkbox').on('change', function () {
      if ($(this).is(':checked')) {
        $('#screen_all').prop('checked', false);
      } else {
        // If all specific checkboxes become unchecked, re-check "All"
        if ($('.specific-screen-checkbox:checked').length === 0) {
          $('#screen_all').prop('checked', true);
=======
    // Toggle Image vs Video form sections
    function updateMediaTypeSections(mediaType) {
      if (mediaType === 'video') {
        $('#image-banner-section').addClass('d-none');
        $('#video-banner-section').removeClass('d-none');
        $('#banner_image_required_star').addClass('d-none');
        $('#banner_image').prop('required', false);
      } else {
        $('#video-banner-section').addClass('d-none');
        $('#image-banner-section').removeClass('d-none');
        $('#banner_image_required_star').removeClass('d-none');
        if (!$('#banner_id').val()) {
          $('#banner_image').prop('required', true);
        }
      }
    }

    $('input[name="media_type"]').change(function() {
      updateMediaTypeSections($(this).val());
    });

    // Screen selection logic
    $('#screen_all').change(function() {
      if ($(this).is(':checked')) {
        $('.specific-screen-checkbox').prop('checked', false);
        $('.screen-checkbox-card').removeClass('selected');
        $('#card-screen-all').addClass('selected');
      } else {
        $('#card-screen-all').removeClass('selected');
      }
    });

    $('.specific-screen-checkbox').change(function() {
      var card = $(this).closest('.screen-checkbox-card');
      if ($(this).is(':checked')) {
        card.addClass('selected');
        $('#screen_all').prop('checked', false);
        $('#card-screen-all').removeClass('selected');
      } else {
        card.removeClass('selected');
        // If none checked, check 'all' by default
        if ($('.specific-screen-checkbox:checked').length === 0) {
          $('#screen_all').prop('checked', true);
          $('#card-screen-all').addClass('selected');
>>>>>>> origin/main
        }
      }
    });

<<<<<<< HEAD
    // "Select All" toggle button
    $('#btn-toggle-all-screens').on('click', function () {
      var anyUnchecked = $('.specific-screen-checkbox:not(:checked)').length > 0;
      if (anyUnchecked) {
        $('.specific-screen-checkbox').prop('checked', true);
        $('#screen_all').prop('checked', false);
        $(this).text('Deselect All');
      } else {
        $('.specific-screen-checkbox').prop('checked', false);
        $('#screen_all').prop('checked', true);
        $(this).text('Select All');
      }
    });

    // -------------------------------------------------------------
    // Popup Toggle Handler
    // -------------------------------------------------------------
    $('#show_popup_toggle').on('change', function () {
      if ($(this).is(':checked')) {
        $('#popup-settings-collapse').removeClass('d-none');
      } else {
        $('#popup-settings-collapse').addClass('d-none');
      }
    });

    // -------------------------------------------------------------
    // Client-side File Previews
    // -------------------------------------------------------------
    $('#feature_image, #banner_image_poster').on('change', function () {
      var file = this.files[0];
      if (file) {
        var reader = new FileReader();
        reader.onload = function (e) {
          $('#img-preview-tag').attr('src', e.target.result);
          $('#image-preview-label').text('New Selected Image Preview:');
          $('#image-preview-box').removeClass('d-none');
=======
    $('#select-all-screens').click(function() {
      $('#screen_all').prop('checked', true);
      $('#card-screen-all').addClass('selected');
      $('.specific-screen-checkbox').prop('checked', false);
      $('.specific-screen-checkbox').closest('.screen-checkbox-card').removeClass('selected');
    });

    $('#clear-screens').click(function() {
      $('.screen-checkbox').prop('checked', false);
      $('.screen-checkbox-card').removeClass('selected');
    });

    // Popup switch toggle
    $('#toggle_show_popup').change(function() {
      var isChecked = $(this).is(':checked');
      $('#show_popup').val(isChecked ? '1' : '0');
      if (isChecked) {
        $('#popup-details-container').removeClass('d-none');
      } else {
        $('#popup-details-container').addClass('d-none');
      }
    });

    // Client-side image preview
    $('#banner_image').change(function() {
      var file = this.files[0];
      if (file) {
        var reader = new FileReader();
        reader.onload = function(e) {
          $('#image-preview-element').attr('src', e.target.result);
          $('#image-current-label').addClass('d-none');
          $('#image-preview-container').removeClass('d-none');
>>>>>>> origin/main
        };
        reader.readAsDataURL(file);
      }
    });

<<<<<<< HEAD
    $('#banner_video').on('change', function () {
      var file = this.files[0];
      if (file) {
        var blobUrl = URL.createObjectURL(file);
        $('#video-preview-tag').attr('src', blobUrl).show();
        $('#video-link-preview').text('Selected File: ' + file.name + ' (' + (file.size / (1024*1024)).toFixed(2) + ' MB)');
        $('#video-preview-box').removeClass('d-none');
      }
    });

    $('#video_url').on('input', function () {
      var val = $(this).val().trim();
      if (val) {
        $('#video-link-preview').text('Video Link: ' + val);
        $('#video-preview-box').removeClass('d-none');
      }
    });

    $('#popup_image').on('change', function () {
      var file = this.files[0];
      if (file) {
        var reader = new FileReader();
        reader.onload = function (e) {
          $('#popup-img-tag').attr('src', e.target.result);
          $('#popup-img-preview-box').removeClass('d-none');
=======
    // Video poster preview
    $('#video_poster_image').change(function() {
      var file = this.files[0];
      if (file) {
        // Also map to banner_image file for submission if video banner
        // Create DataTransfer to assign to #banner_image
        var dt = new DataTransfer();
        dt.items.add(file);
        document.getElementById('banner_image').files = dt.files;
      }
    });

    // Client-side video file preview
    $('#banner_video').change(function() {
      var file = this.files[0];
      if (file) {
        var videoUrl = URL.createObjectURL(file);
        var videoElem = document.getElementById('video-preview-element');
        videoElem.src = videoUrl;
        $('#video-current-label').addClass('d-none');
        $('#video-preview-container').removeClass('d-none');
      }
    });

    // Client-side popup image preview
    $('#popup_image').change(function() {
      var file = this.files[0];
      if (file) {
        var reader = new FileReader();
        reader.onload = function(e) {
          $('#popup-preview-element').attr('src', e.target.result);
          $('#popup-current-label').addClass('d-none');
          $('#popup-preview-container').removeClass('d-none');
>>>>>>> origin/main
        };
        reader.readAsDataURL(file);
      }
    });

<<<<<<< HEAD
    // -------------------------------------------------------------
    // Edit Banner Handler
    // -------------------------------------------------------------
    $(document).on('click', '.edit-banner-btn', function () {
      var btn = $(this);
      resetBannerForm();

      var id = btn.data('id');
      $('#banner_id').val(id);
      $('#offcanvas-title-text').text('Edit App Banner (#' + id + ')');
      $('#offcanvas-subtitle-text').text('Update banner details, media, screen assignments, and popup.');
      $('#save-button-text').text('Update Banner');

      // Populate basic info
      $('#banner_name').val(btn.data('name') || '');
      $('#banner_url').val(btn.data('url') || '');
      $('#banner_type').val(btn.data('banner-type') || 'promo');

      // Action Type & Link ID
      var typeVal = btn.data('type') || '';
      var linkId = btn.data('link-id') || '';
      $('#banner_nav_type').val(typeVal).trigger('change');
      if (typeVal === 'category') {
        $('#link_id_text').val(linkId);
      } else {
        $('#link_id').val(linkId);
      }

      // Media Type
      var mediaType = btn.data('media-type') || 'image';
      if (mediaType === 'video') {
        $('#media_type_video').prop('checked', true).trigger('change');
      } else {
        $('#media_type_image').prop('checked', true).trigger('change');
      }

      // Existing feature image
      var existingImgUrl = btn.data('image-url');
      if (existingImgUrl) {
        $('#img-preview-tag').attr('src', existingImgUrl);
        $('#image-preview-label').text('Current Image Asset:');
        $('#image-preview-box').removeClass('d-none');
      }

      // Feature image not required if already uploaded
      $('#feature-image-required-asterisk').addClass('d-none');
      $('#feature_image').prop('required', false);

      // Video Assets
      var bannerVideo = btn.data('banner-video');
      var videoUrl = btn.data('video-url');
      $('#video_url').val(videoUrl || '');

      if (bannerVideo || videoUrl) {
        $('#video-preview-box').removeClass('d-none');
        if (bannerVideo) {
          var videoFileUrl = '<?= base_url("public/uploads/banners/") ?>' + '/' + bannerVideo;
          $('#video-preview-tag').attr('src', videoFileUrl).show();
          $('#video-link-preview').text('Current File: ' + bannerVideo);
        } else if (videoUrl) {
          $('#video-preview-tag').hide();
          $('#video-link-preview').text('Current Link: ' + videoUrl);
        }
      }

      // Target Screens
      var screensRaw = btn.data('screens') || 'all';
      var screensArr = screensRaw.split(',').map(function (s) { return s.trim(); });
      $('.screen-checkbox').prop('checked', false);

      if (screensArr.indexOf('all') !== -1 || screensArr.length === 0) {
        $('#screen_all').prop('checked', true);
      } else {
        screensArr.forEach(function (scr) {
          $('#screen_' + scr).prop('checked', true);
        });
      }

      // Popup
      var showPopup = btn.data('show-popup');
      if (showPopup == '1' || showPopup === 1) {
        $('#show_popup_toggle').prop('checked', true).trigger('change');
      } else {
        $('#show_popup_toggle').prop('checked', false).trigger('change');
      }

      var popupImgUrl = btn.data('popup-img-url');
      if (popupImgUrl) {
        $('#popup-img-tag').attr('src', popupImgUrl);
        $('#popup-img-preview-box').removeClass('d-none');
      }
      $('#popup_link').val(btn.data('popup-link') || '');

      // Open offcanvas
      var offcanvasElement = document.getElementById('form-offcanvas');
      var bsOffcanvas = bootstrap.Offcanvas.getInstance(offcanvasElement) || new bootstrap.Offcanvas(offcanvasElement);
      bsOffcanvas.show();
    });

    // -------------------------------------------------------------
    // Form Submission (Add / Edit Banner)
    // -------------------------------------------------------------
    $('#banner-form').on('submit', function (e) {
      e.preventDefault();

      var submitBtn = $('#save-banner-button');
      var spinner = $('#save-spinner');
      var btnIcon = $('#save-button-icon');

      submitBtn.prop('disabled', true);
      spinner.removeClass('d-none');
      btnIcon.addClass('d-none');

      var formData = new FormData(this);

      // Handle category text vs service select for link_id
      var navType = $('#banner_nav_type').val();
      if (navType === 'category') {
        formData.set('link_id', $('#link_id_text').val());
      } else {
        formData.set('link_id', $('#link_id').val());
      }

      // If video poster uploaded via separate field, map to feature_image if feature_image is empty
      var posterFile = $('#banner_image_poster')[0].files[0];
      if (posterFile && (!formData.get('feature_image') || formData.get('feature_image').size === 0)) {
        formData.set('feature_image', posterFile);
      }

      // Ensure CSRF token is present in FormData
      formData.set(csrfName, csrfHash);

      $.ajax({
        url: '<?= site_url ( "addBanner" ) ?>',
=======
    // Reset form for "New Banner"
    $('.add').click(function() {
      $('#add-banner-form')[0].reset();
      $('#banner_id').val('');
      $('#form-title').text('Create App Banner');
      $('#media_type_image').prop('checked', true);
      updateMediaTypeSections('image');

      // Default to All Screens
      $('.screen-checkbox').prop('checked', false);
      $('.screen-checkbox-card').removeClass('selected');
      $('#screen_all').prop('checked', true);
      $('#card-screen-all').addClass('selected');

      // Clear previews
      $('#image-preview-container').addClass('d-none');
      $('#video-preview-container').addClass('d-none');
      $('#popup-preview-container').addClass('d-none');
      $('#image-preview-element').attr('src', '');
      $('#video-preview-element').attr('src', '');
      $('#popup-preview-element').attr('src', '');

      $('#toggle_show_popup').prop('checked', false).trigger('change');
      $('#banner_image').attr('required', true);
    });

    // Populate form for "Edit Banner"
    $(document).on('click', '.edit-btn', function() {
      var btn = $(this);
      var id = btn.data('id');
      var mediaType = btn.data('media-type') || 'image';
      var screensStr = btn.data('screens') || 'all';
      var bannerImage = btn.data('banner-image') || '';
      var bannerVideo = btn.data('banner-video') || '';
      var videoUrl = btn.data('video-url') || '';
      var showPopup = btn.data('show-popup') || '0';
      var popupImage = btn.data('popup-image') || '';
      var popupLink = btn.data('popup-link') || '';

      $('#banner_id').val(id);
      $('#form-title').text('Update App Banner #' + id);
      $('#banner_image').attr('required', false);

      // Media Type
      if (mediaType === 'video') {
        $('#media_type_video').prop('checked', true);
      } else {
        $('#media_type_image').prop('checked', true);
      }
      updateMediaTypeSections(mediaType);

      // Screens
      $('.screen-checkbox').prop('checked', false);
      $('.screen-checkbox-card').removeClass('selected');
      var screenList = screensStr.split(',').map(function(s) { return s.trim(); });

      if (screenList.indexOf('all') !== -1 || screenList.length === 0) {
        $('#screen_all').prop('checked', true);
        $('#card-screen-all').addClass('selected');
      } else {
        screenList.forEach(function(scr) {
          var chk = $('#screen_' + scr);
          if (chk.length) {
            chk.prop('checked', true);
            chk.closest('.screen-checkbox-card').addClass('selected');
          }
        });
      }

      // Previews
      if (bannerImage) {
        $('#image-preview-element').attr('src', BASE_UPLOAD_URL + bannerImage);
        $('#image-current-label').removeClass('d-none');
        $('#image-preview-container').removeClass('d-none');
      } else {
        $('#image-preview-container').addClass('d-none');
      }

      $('#video_url').val(videoUrl);
      if (bannerVideo) {
        var videoElem = document.getElementById('video-preview-element');
        videoElem.src = BASE_UPLOAD_URL + bannerVideo;
        $('#video-current-label').removeClass('d-none');
        $('#video-preview-container').removeClass('d-none');
      } else {
        $('#video-preview-container').addClass('d-none');
      }

      // Popup
      var isPopupOn = (showPopup === '1' || showPopup === 1 || showPopup === 'yes');
      $('#toggle_show_popup').prop('checked', isPopupOn).trigger('change');
      $('#popup_link').val(popupLink);

      if (popupImage) {
        $('#popup-preview-element').attr('src', BASE_UPLOAD_URL + popupImage);
        $('#popup-current-label').removeClass('d-none');
        $('#popup-preview-container').removeClass('d-none');
      } else {
        $('#popup-preview-container').addClass('d-none');
      }

      var offcanvas = new bootstrap.Offcanvas(document.getElementById('form-offcanvas'));
      offcanvas.show();
    });

    // Video Player Preview in Modal
    $(document).on('click', '.preview-video-btn', function() {
      var videoSrc = $(this).data('video-src');
      var posterSrc = $(this).data('poster-src') || '';
      var title = $(this).data('title') || 'Video Preview';

      $('#videoPreviewModalLabel').text(title);
      var player = document.getElementById('modal-video-player');

      // Check if direct video file or external link
      if (videoSrc && (videoSrc.endsWith('.mp4') || videoSrc.endsWith('.webm') || videoSrc.endsWith('.ogg') || videoSrc.indexOf('/public/uploads/banners/') !== -1)) {
        $('#modal-video-player').removeClass('d-none');
        $('#modal-external-link-container').addClass('d-none');
        player.poster = posterSrc;
        player.src = videoSrc;
        player.load();
        player.play().catch(function() {});
      } else if (videoSrc) {
        $('#modal-video-player').addClass('d-none');
        player.pause();
        $('#modal-external-link-btn').attr('href', videoSrc);
        $('#modal-external-link-container').removeClass('d-none');
      }

      var modal = new bootstrap.Modal(document.getElementById('videoPreviewModal'));
      modal.show();
    });

    // Stop video when modal closes
    $('#videoPreviewModal').on('hidden.bs.modal', function() {
      var player = document.getElementById('modal-video-player');
      if (player) {
        player.pause();
        player.src = '';
      }
    });

    // Handle Form Submit via AJAX
    $('#add-banner-form').on('submit', function(e) {
      e.preventDefault();
      var submitBtn = $('#save-button');
      var originalBtnHtml = submitBtn.html();

      // Ensure at least one screen is selected
      if ($('.screen-checkbox:checked').length === 0) {
        alert('Please select at least one target screen for the banner.');
        return;
      }

      submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Saving...');

      var formData = new FormData(this);

      $.ajax({
        url: '<?= site_url("addBanner") ?>',
>>>>>>> origin/main
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
<<<<<<< HEAD
        success: function (res) {
          if (res.csrf_hash) {
            updateCsrf(res.csrf_hash);
          }
          if (res.status) {
            alert(res.message);
            location.reload();
          } else {
            alert(res.message || 'Failed to save banner');
            submitBtn.prop('disabled', false);
            spinner.addClass('d-none');
            btnIcon.removeClass('d-none');
          }
        },
        error: function (err) {
          console.error('Save Banner Error:', err);
          alert('A network or server error occurred while saving the banner.');
          submitBtn.prop('disabled', false);
          spinner.addClass('d-none');
          btnIcon.removeClass('d-none');
=======
        success: function(res) {
          submitBtn.prop('disabled', false).html(originalBtnHtml);
          if (res.status) {
            if (typeof Swal !== 'undefined') {
              Swal.fire({
                icon: 'success',
                title: 'Success',
                text: res.message,
                timer: 1500,
                showConfirmButton: false
              }).then(function() {
                location.reload();
              });
            } else {
              alert(res.message);
              location.reload();
            }
          } else {
            if (typeof Swal !== 'undefined') {
              Swal.fire({ icon: 'error', title: 'Error', text: res.message });
            } else {
              alert(res.message);
            }
          }
        },
        error: function(err) {
          submitBtn.prop('disabled', false).html(originalBtnHtml);
          console.error(err);
          var msg = (err.responseJSON && err.responseJSON.message) ? err.responseJSON.message : 'Error processing banner. Please check file size and format.';
          if (typeof Swal !== 'undefined') {
            Swal.fire({ icon: 'error', title: 'Error', text: msg });
          } else {
            alert(msg);
          }
>>>>>>> origin/main
        }
      });
    });

<<<<<<< HEAD
    // -------------------------------------------------------------
    // Delete Banner Handler
    // -------------------------------------------------------------
    $(document).on('click', '.delete-btn', function () {
      var id = $(this).data('id');
      var name = $(this).data('name') || 'this banner';

      if (!confirm('Are you sure you want to delete "' + name + '"? This will permanently remove its media assets.')) {
        return;
      }

      var postData = getCsrfData();
      postData.id = id;

      $.post('<?= site_url ( "deleteBanner" ) ?>', postData, function (res) {
        if (res.csrf_hash) {
          updateCsrf(res.csrf_hash);
        }
        if (res.status) {
          location.reload();
        } else {
          alert(res.message || 'Failed to delete banner');
        }
      }, 'json').fail(function () {
        alert('An error occurred while communicating with the server.');
      });
    });

    // -------------------------------------------------------------
    // Status Switch AJAX Handler
    // -------------------------------------------------------------
    $(document).on('change', '.switch-status', function () {
      var switchEl = $(this);
      var id = switchEl.data('id');
      var isChecked = switchEl.is(':checked');
      var statusVal = isChecked ? 'active' : 'inactive';

      var postData = getCsrfData();
      postData.id = id;
      postData.status = statusVal;

      $.post('<?= site_url ( "updateBannerStatus" ) ?>', postData, function (res) {
        if (res.csrf_hash) {
          updateCsrf(res.csrf_hash);
        }
        if (!res.status) {
          alert(res.message || 'Failed to update status');
          switchEl.prop('checked', !isChecked);
        }
      }, 'json').fail(function () {
        alert('An error occurred while updating status.');
        switchEl.prop('checked', !isChecked);
      });
    });

    // -------------------------------------------------------------
    // Video Preview Modal
    // -------------------------------------------------------------
    $(document).on('click', '.btn-play-video', function () {
      var videoSrc = $(this).data('video-src') || '';
      var videoUrl = $(this).data('video-url') || '';
      var title = $(this).data('title') || 'Video Preview';

      $('#modal-video-title').text(title);
      $('#modal-video-player').hide();
      $('#modal-video-iframe').hide();
      $('#modal-video-loading').show();

      var player = document.getElementById('modal-video-player');
      player.pause();

      var modal = new bootstrap.Modal(document.getElementById('videoPreviewModal'));
      modal.show();

      if (videoSrc) {
        // Direct uploaded video file
        $('#modal-video-source').attr('src', videoSrc);
        player.load();
        $('#modal-video-loading').hide();
        $('#modal-video-player').show();
        $('#modal-video-meta').text('Source: Uploaded video file');
        player.play().catch(function (e) {
          console.log('Autoplay prevented:', e);
        });
      } else if (videoUrl) {
        // YouTube, Vimeo, or external stream
        $('#modal-video-meta').text('Source: ' + videoUrl);
        var embedUrl = getEmbedUrl(videoUrl);
        if (embedUrl) {
          $('#modal-video-iframe').attr('src', embedUrl);
          $('#modal-video-loading').hide();
          $('#modal-video-iframe').show();
        } else {
          // Direct URL video playback
          $('#modal-video-source').attr('src', videoUrl);
          player.load();
          $('#modal-video-loading').hide();
          $('#modal-video-player').show();
          player.play().catch(function (e) {});
=======
    // Handle Delete Banner
    $(document).on('click', '.delete-btn', function() {
      var id = $(this).data('id');
      var data = getCsrfData();
      data.id = id;

      var doDelete = function() {
        $.post('<?= site_url("deleteBanner") ?>', data, function(res) {
          if (res.status) {
            if (typeof Swal !== 'undefined') {
              Swal.fire({
                icon: 'success',
                title: 'Deleted',
                text: res.message,
                timer: 1200,
                showConfirmButton: false
              }).then(function() {
                location.reload();
              });
            } else {
              alert(res.message);
              location.reload();
            }
          } else {
            alert(res.message);
          }
        }, 'json');
      };

      if (typeof Swal !== 'undefined') {
        Swal.fire({
          title: 'Delete Banner #' + id + '?',
          text: "Are you sure? All associated banner media will be permanently deleted.",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'Yes, delete it!'
        }).then(function(result) {
          if (result.isConfirmed) {
            doDelete();
          }
        });
      } else {
        if (confirm('Are you sure you want to delete banner #' + id + '?')) {
          doDelete();
>>>>>>> origin/main
        }
      }
    });

<<<<<<< HEAD
    // Pause / reset video when modal closes
    $('#videoPreviewModal').on('hidden.bs.modal', function () {
      var player = document.getElementById('modal-video-player');
      if (player) {
        player.pause();
        $('#modal-video-source').attr('src', '');
      }
      $('#modal-video-iframe').attr('src', '');
    });

    function getEmbedUrl(url) {
      if (!url) return null;
      // YouTube
      var ytMatch = url.match(/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))([\w-]{11})/);
      if (ytMatch && ytMatch[1]) {
        return 'https://www.youtube.com/embed/' + ytMatch[1] + '?autoplay=1';
      }
      // Vimeo
      var vimeoMatch = url.match(/vimeo\.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|)(\d+)/);
      if (vimeoMatch && vimeoMatch[3]) {
        return 'https://player.vimeo.com/video/' + vimeoMatch[3] + '?autoplay=1';
      }
      return null;
    }

    // -------------------------------------------------------------
    // Image Preview Modal
    // -------------------------------------------------------------
    $(document).on('click', '.btn-preview-image', function () {
      var imgSrc = $(this).data('img-src');
      var title = $(this).data('title') || 'Image Preview';
      if (imgSrc) {
        $('#modal-image-title').text(title);
        $('#modal-image-tag').attr('src', imgSrc);
        var modal = new bootstrap.Modal(document.getElementById('imagePreviewModal'));
        modal.show();
      }
    });

    // -------------------------------------------------------------
    // Filters & Search Logic
    // -------------------------------------------------------------
    function filterTableRows() {
      var searchVal = $('#banner-table-search').val().toLowerCase().trim();
      var mediaTypeFilter = $('#filter-media-type').val();
      var screenFilter = $('#filter-screen').val();
      var popupFilter = $('#filter-popup').val();

      var visibleCount = 0;

      $('.banner-row').each(function () {
        var row = $(this);
        var rowText = row.text().toLowerCase();
        var rowMediaType = row.data('media-type');
        var rowScreens = (row.data('screens') || '').toString().split(',');
        var rowPopup = row.data('popup');

        var matchesSearch = !searchVal || rowText.indexOf(searchVal) !== -1;
        var matchesMedia = !mediaTypeFilter || rowMediaType === mediaTypeFilter;
        var matchesPopup = !popupFilter || rowPopup === popupFilter;

        var matchesScreen = true;
        if (screenFilter) {
          if (screenFilter === 'all') {
            matchesScreen = rowScreens.indexOf('all') !== -1;
          } else {
            matchesScreen = rowScreens.indexOf('all') !== -1 || rowScreens.indexOf(screenFilter) !== -1;
          }
        }

        if (matchesSearch && matchesMedia && matchesPopup && matchesScreen) {
          row.show();
          visibleCount++;
=======
    // Handle Status Change Switch
    $(document).on('change', '.switch-status', function() {
      var checkbox = $(this);
      var id = checkbox.data('id');
      var status = checkbox.is(':checked') ? 'active' : 'inactive';
      var label = checkbox.siblings('.status-label');
      var data = getCsrfData();
      data.id = id;
      data.status = status;

      $.post('<?= site_url("updateBannerStatus") ?>', data, function(res) {
        if (res.status) {
          if (checkbox.is(':checked')) {
            label.text('Active').removeClass('text-muted').addClass('text-success fw-bold');
          } else {
            label.text('Inactive').removeClass('text-success fw-bold').addClass('text-muted');
          }
        } else {
          alert(res.message);
          checkbox.prop('checked', !checkbox.is(':checked'));
        }
      }, 'json');
    });

    // Live filtering in datatable
    function applyFilters() {
      var screenVal = $('#filter-screen').val().toLowerCase();
      var mediaVal = $('#filter-media-type').val().toLowerCase();
      var searchVal = $('.dt-search').val().toLowerCase();

      $('.banner-row').each(function() {
        var row = $(this);
        var rowMediaType = (row.data('media-type') || '').toLowerCase();
        var rowScreens = (row.data('screens') || '').toLowerCase();
        var rowText = row.text().toLowerCase();

        var matchesScreen = true;
        if (screenVal) {
          if (screenVal === 'all') {
            matchesScreen = (rowScreens === 'all' || rowScreens.indexOf('all') !== -1);
          } else {
            matchesScreen = (rowScreens === 'all' || rowScreens.indexOf(screenVal) !== -1);
          }
        }

        var matchesMedia = true;
        if (mediaVal) {
          matchesMedia = (rowMediaType === mediaVal);
        }

        var matchesSearch = true;
        if (searchVal) {
          matchesSearch = (rowText.indexOf(searchVal) !== -1);
        }

        if (matchesScreen && matchesMedia && matchesSearch) {
          row.show();
>>>>>>> origin/main
        } else {
          row.hide();
        }
      });
<<<<<<< HEAD

      if (visibleCount === 0 && $('.banner-row').length > 0) {
        if ($('#no-filter-match-row').length === 0) {
          $('#banners-datatable tbody').append(
            '<tr id="no-filter-match-row"><td colspan="8" class="text-center py-4 text-muted">' +
            '<i class="icon-Search fs-3 mb-2 d-block"></i>No banners match the selected filters.</td></tr>'
          );
        }
      } else {
        $('#no-filter-match-row').remove();
      }
    }

    $('#banner-table-search').on('keyup input', filterTableRows);
    $('#filter-media-type, #filter-screen, #filter-popup').on('change', filterTableRows);

    $('#btn-reset-filters').on('click', function () {
      $('#banner-table-search').val('');
      $('#filter-media-type').val('');
      $('#filter-screen').val('');
      $('#filter-popup').val('');
      filterTableRows();
    });
  });
</script>

<?php echo $this->endSection () ?>
=======
    }

    $('#filter-screen, #filter-media-type').change(applyFilters);
    $('.dt-search').on('keyup input', applyFilters);
  });
</script>

<?php echo $this->endSection() ?>
>>>>>>> origin/main
