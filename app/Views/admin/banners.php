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
          </div>
        </div>
      </div>

      <div class="card-body p-0">
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
        </div>
      </div>
    </div>

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
                </div>
              </div>
            </div>

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
                </div>
              </div>
            </div>
          </div>

          <div class="offcanvas-footer border-top bg-light p-3">
            <div class="d-flex gap-2 justify-content-end">
              <button class="btn btn-soft-secondary d-flex align-items-center gap-1" type="button" data-bs-dismiss="offcanvas">
                Cancel
              </button>
              <button class="btn btn-primary d-flex align-items-center gap-1 px-4" type="submit" id="save-button">
                <i class="icon-disk me-1"></i> Save Banner
              </button>
            </div>
          </div>
        </div>
      </form>
    </div>

    <!-- Video Preview Modal -->
    <div class="modal fade" id="videoPreviewModal" tabindex="-1" aria-labelledby="videoPreviewModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
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
        </div>
      </div>
    </div>

  </div>
</main>
<?php echo $this->include('admin/footer') ?>

<script>
  $(document).ready(function() {
    var BASE_UPLOAD_URL = '<?= base_url("public/uploads/banners/") ?>/';

    // CSRF Token helper
    function getCsrfData() {
      var csrfName = '<?= csrf_token() ?>';
      var csrfHash = $('input[name="' + csrfName + '"]').val();
      var data = {};
      data[csrfName] = csrfHash;
      return data;
    }

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
        }
      }
    });

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
        };
        reader.readAsDataURL(file);
      }
    });

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
        };
        reader.readAsDataURL(file);
      }
    });

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
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
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
        }
      });
    });

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
        }
      }
    });

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
        } else {
          row.hide();
        }
      });
    }

    $('#filter-screen, #filter-media-type').change(applyFilters);
    $('.dt-search').on('keyup input', applyFilters);
  });
</script>

<?php echo $this->endSection() ?>