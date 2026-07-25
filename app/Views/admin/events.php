<?= $this->extend('admin/includes/header'); ?>
<?= $this->section('content'); ?>

<main class="main-content">
  <div class="position-relative iq-banner">
    <?= $this->include('admin/nav_bar') ?>
  </div>

  <div class="conatiner-fluid content-inner pb-0 px-4" id="page_layout">
    <!-- Modern Header Section -->
    <div class="container-fluid p-0 mb-4 bg-gradient-primary rounded-3">
      <div class="text-white p-5 shadow position-relative overflow-hidden">
        <div class="position-relative z-1">
          <h1 class="h2 fw-bold mb-2 text-white">Events Management</h1>
          <p class="text-white-75 mb-0">Manage all your events and announcements</p>
        </div>
        <i class="bi bi-calendar-event position-absolute end-0 bottom-0 opacity-10"
          style="font-size: 12rem; transform: rotate(12deg); margin-right: -2rem; margin-bottom: -2rem;"></i>
      </div>
     
    </div>

    <div class="row g-4 mb-4">
      <div class="col-md-4 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div
                class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center"
                style="width: 48px; height: 48px;">
                <i class="bi bi-calendar4 fs-4"></i>
              </div>
              <div class="ms-3">
                <p class="text-muted small mb-1">Total Events</p>
                <h3 class="mb-0"><?= count($events) ?></h3>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-4 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div
                class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center"
                style="width: 48px; height: 48px;">
                <i class="bi bi-check-circle fs-4"></i>
              </div>
              <div class="ms-3">
                <p class="text-muted small mb-1">Published</p>
                <h3 class="mb-0"><?= count($events) ?></h3>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-4 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div
                class="rounded-circle bg-info bg-opacity-10 text-info d-flex align-items-center justify-content-center"
                style="width: 48px; height: 48px;">
                <i class="bi bi-images fs-4"></i>
              </div>
              <div class="ms-3">
                <p class="text-muted small mb-1">With Images</p>
                <h3 class="mb-0">
                  <?= count(array_filter($events, function($event) {
                    return !empty($event->post_image);
                  })) ?>
                </h3>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-4 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div
                class="rounded-circle bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center"
                style="width: 48px; height: 48px;">
                <i class="bi bi-link fs-4"></i>
              </div>
              <div class="ms-3">
                <p class="text-muted small mb-1">With Links</p>
                <h3 class="mb-0">
                  <?= count(array_filter($events, function($event) {
                    return !empty($event->post_url);
                  })) ?>
                </h3>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Events Table -->
    <div class="card border-0 shadow-sm overflow-hidden">
      <div class="table-responsive">
        <table class="table table-hover mb-0" id="eventsTable" style="table-layout: fixed; width: 100%;">
          <thead class="" style="background-color: #c6d2ff!important;">
            <tr>
              <th width="60" class="ps-4 py-3 text-black fs-6 fw-semibold border-bottom" style="background: #c6d2ff!important;">
                ID
              </th>
              <th width="300" class="py-3 text-black fs-6 fw-semibold border-bottom" style="background: #c6d2ff!important;">
                Event Details
              </th>
              <th width="100" class="py-3 text-black fs-6 fw-semibold border-bottom text-center" style="background: #c6d2ff!important;">
                Image
              </th>
              <th width="150" class="py-3 text-black fs-6 fw-semibold border-bottom" style="background: #c6d2ff!important;">
                URL
              </th>
              <th width="150" class="py-3 text-black fs-6 fw-semibold border-bottom" style="background: #c6d2ff!important;">
                Address
              </th>
              <th width="200" class="pe-4 py-3 text-black fs-6 fw-semibold border-bottom text-center" style="background: #c6d2ff!important;">
                Actions
              </th>
            </tr>
          </thead>
          <tbody class="border-top-0">
            <?php foreach ($events as $event) : ?>
              <tr>
                <td class="ps-4 py-3 fw-medium" style="width: 60px;"><?= $event->id ?></td>
                <td class="py-3" style="width: 300px;">
                  <div class="d-flex flex-column gap-1" style="max-width: 290px;">
                    <div class="fw-bold text-dark mb-1 text-truncate"><?= esc($event->post_title) ?></div>
                    <div class="small text-muted" style="
                      max-height: 48px;
                      overflow: hidden;
                      text-overflow: ellipsis;
                      display: -webkit-box;
                      -webkit-line-clamp: 2;
                      -webkit-box-orient: vertical;
                      line-height: 1.4;
                    ">
                      <?= strip_tags($event->post_content) ?>
                    </div>
                  </div>
                </td>
                <td class="py-3 text-center" style="width: 100px;">
                  <?php if (!empty($event->post_image)) : ?>
                    <img src="<?= esc($event->post_image) ?>" 
                         alt="Event Image" 
                         class="img-thumbnail rounded"
                         style="width: 60px; height: 60px; object-fit: cover; border: 1px solid #e5dbff;">
                  <?php else : ?>
                    <span class="badge border" style="background: rgba(156, 163, 175, 0.1) !important; color: #6b7280 !important; border: 1px solid rgba(156, 163, 175, 0.2) !important ; border-radius:50px; font-weight: 500; padding: 0.25rem 0.5rem; font-size: 0.75rem;">
                      No Image
                    </span>
                  <?php endif; ?>
                </td>
                <td class="py-3" style="width: 150px;">
                  <?php if (!empty($event->post_url)) : ?>
                    <a href="<?= esc($event->post_url) ?>" 
                       target="_blank" 
                       class="text-decoration-none text-primary d-flex align-items-center gap-1" 
                       style="font-size: 0.875rem;">
                      <i class="bi bi-link-45deg flex-shrink-0"></i>
                      <span class="text-truncate">View Link</span>
                    </a>
                  <?php else : ?>
                    <span class="text-muted small">-</span>
                  <?php endif; ?>
                </td>
                <td class="py-3" style="width: 150px;">
                  <?php if (!empty($event->address)) : ?>
                    <div class="d-flex align-items-center gap-1" style="font-size: 0.875rem;">
                      <i class="bi bi-geo-alt text-muted flex-shrink-0"></i>
                      <span class="text-truncate"><?= esc($event->address) ?></span>
                    </div>
                  <?php else : ?>
                    <span class="text-muted small">-</span>
                  <?php endif; ?>
                </td>
                <td class="pe-4 py-3 text-center" style="width: 140px;">
                  <div class="d-flex justify-content-center gap-1">
                    <button class="btn btn-sm btn-outline-info viewBtn d-flex align-items-center gap-1 px-2"
                      data-id="<?= $event->id ?>"
                      data-title="<?= htmlspecialchars($event->post_title, ENT_QUOTES, 'UTF-8') ?>"
                      data-content="<?= htmlspecialchars($event->post_content, ENT_QUOTES, 'UTF-8') ?>"
                      data-image="<?= htmlspecialchars($event->post_image ?? '', ENT_QUOTES, 'UTF-8') ?>"
                      data-url="<?= htmlspecialchars($event->post_url ?? '', ENT_QUOTES, 'UTF-8') ?>"
                      data-address="<?= htmlspecialchars($event->address ?? '', ENT_QUOTES, 'UTF-8') ?>"
                      data-bs-toggle="modal" 
                      data-bs-target="#viewEventModal">
                      <i class="bi bi-eye"></i>
                      <span class="d-none d-md-inline">View</span>
                    </button>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Pagination -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 px-2 gap-3">
      <div class="text-muted small" id="pageInfo">
        Showing <?= count($events) ?> entries
      </div>
      <div class="btn-group">
        <button class="btn btn-outline-primary btn-sm">Previous</button>
        <button class="btn btn-primary btn-sm">1</button>
        <button class="btn btn-outline-primary btn-sm">Next</button>
      </div>
    </div>
  </div>
</main>

<!-- View Event Modal -->
<div class="modal fade" id="viewEventModal" tabindex="-1" aria-labelledby="viewEventModalLabel" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content border-0 shadow-lg p-0">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title text-white" id="viewEventModalLabel">Event Details</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-0 ">
        <div class="row g-4 w-100">
          <!-- Left Column: Event Image -->
          <div class="col-md-5">
            <div class="card border-0  h-100">
              <div class="card-body d-flex flex-column align-items-center justify-content-center p-4">
                <div id="eventImageContainer" class="text-center mb-3 w-100">
                  <!-- Image will be loaded here -->
                </div>
                <div class="mt-auto w-100">
                  <div class="d-grid gap-2">
                    <a href="#" id="eventUrlLink" target="_blank" class="btn btn-outline-primary d-flex align-items-center justify-content-center gap-2" style="display: none;">
                      <i class="bi bi-link-45deg"></i>
                      <span>Visit Event URL</span>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Right Column: Event Details -->
          <div class="col-md-7">
            <div class="card border-0  h-100">
              <div class="card-body p-4">
                <h4 id="eventTitle" class="fw-bold text-dark mb-3 border-bottom pb-2"></h4>
                
                <div class="mb-4">
                  <h6 class="text-muted text-uppercase small fw-semibold mb-2">
                    <i class="bi bi-file-text me-1"></i>Description
                  </h6>
                  <div id="eventContent" class="text-dark bg-white p-3 rounded" style="max-height: 200px; overflow-y: auto; line-height: 1.6; font-size: 0.95rem;">
                    <!-- Content will be loaded here -->
                  </div>
                </div>
                
                <div class="mb-3">
                  <h6 class="text-muted text-uppercase small fw-semibold mb-2">
                    <i class="bi bi-geo-alt me-1"></i>Location
                  </h6>
                  <div id="eventAddress" class="d-flex align-items-start gap-2 bg-white p-2 rounded">
                    <i class="bi bi-geo-alt-fill text-primary mt-1"></i>
                    <span class="text-dark"></span>
                  </div>
                </div>
                
                <div class="row g-2 mt-3">
                  <div class="col-6">
                    <div class="bg-white p-2 rounded">
                      <h6 class="text-muted text-uppercase small fw-semibold mb-1">Event ID</h6>
                      <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-hash text-primary"></i>
                        <span id="eventId" class="fw-medium text-dark"></span>
                      </div>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="bg-white p-2 rounded">
                      <h6 class="text-muted text-uppercase small fw-semibold mb-1">Status</h6>
                      <div class="d-inline-flex align-items-center gap-2">
                        <span class="badge bg-success bg-opacity-10 text-white border border-success border-opacity-25 px-3 py-2 rounded-pill">
                          <i class="bi bi-check-circle me-1"></i>
                          Published
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer border-top bg-light">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
          <i class="bi bi-x-lg me-1"></i>Close
        </button>
        <!-- <a href="#" id="editEventLink" class="btn btn-primary d-flex align-items-center gap-2">
          <i class="bi bi-pencil"></i>
          Edit Event
        </a> -->
      </div>
    </div>
  </div>
</div>

<style>
  /* Custom styles to match the exact design */
  .bg-gradient-primary {
    background: linear-gradient(135deg, #3f30e3 0%, #5555fa 100%) !important;
  }

  .card {
    border-radius: 16px !important;
  }

  .table thead th {
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.05em;
    padding-top: 1rem;
    padding-bottom: 1rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .table tbody td {
    vertical-align: middle;
    padding-top: 1rem;
    padding-bottom: 1rem;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .table tbody tr {
    transition: all 0.2s ease;
  }

  .table tbody tr:hover {
    background-color: rgba(99, 102, 241, 0.03) !important;
  }

  .badge {
    font-weight: 500 !important;
    white-space: nowrap;
  }

  .btn-outline-info {
    border-color: #dee2e6;
    color: #6c757d;
    padding: 0.25rem 0.5rem;
    white-space: nowrap;
  }

  .btn-outline-info:hover {
    border-color: #0dcaf0;
    color: #0dcaf0;
    background-color: transparent;
  }

  .btn-outline-primary {
    border-color: #dee2e6;
    color: #6c757d;
    padding: 0.25rem 0.5rem;
    white-space: nowrap;
  }

  .btn-outline-primary:hover {
    border-color: #6366f1;
    color: #6366f1;
    background-color: transparent;
  }

  .btn-outline-danger {
    border-color: #dee2e6;
    color: #6c757d;
    padding: 0.25rem 0.5rem;
    white-space: nowrap;
  }

  .btn-outline-danger:hover {
    border-color: #dc3545;
    color: #dc3545;
    background-color: transparent;
  }

  .text-truncate {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }

  /* Fixed table layout */
  #eventsTable {
    table-layout: fixed !important;
    width: 100% !important;
  }

  /* Modal Styles - Enhanced for scroll locking */
  .modal-content {
    border-radius: 16px !important;
    overflow: hidden;
  }

  .modal-header {
    border-bottom: none !important;
  }

  .modal-footer {
    border-top: 1px solid rgba(0,0,0,0.1) !important;
  }

  /* Prevent body scroll when modal is open */
  body.modal-open {
    overflow: hidden !important;
    padding-right: 0 !important;
  }
  
  /* Ensure modal backdrop covers everything */
  .modal-backdrop {
    background-color: rgba(0, 0, 0, 0.5);
  }
  
  /* Make modal content scrollable if needed */
  .modal-dialog-scrollable .modal-body {
    max-height: calc(100vh - 200px);
    overflow-y: auto;
  }
  
  /* Fix for iOS devices */
  .modal-open {
    position: fixed;
    width: 100%;
    height: 100%;
  }

  /* Responsive adjustments */
  @media (max-width: 1200px) {
    #eventsTable {
      min-width: 800px;
    }
    
    .table-responsive {
      overflow-x: auto;
    }
  }

  @media (max-width: 768px) {
    .table td,
    .table th {
      padding: 0.75rem 0.5rem;
    }

    .row.g-4 .col-md-4 {
      margin-bottom: 1rem;
    }

    .btn-group .btn {
      padding: 0.375rem 0.75rem;
      font-size: 0.875rem;
    }

    /* Modal responsive */
    .modal-dialog {
      margin: 0.5rem;
    }
    
    .modal-dialog-scrollable .modal-body {
      max-height: calc(100vh - 150px);
    }
    
    .modal-body .row {
      flex-direction: column;
    }

    .modal-body .col-md-5,
    .modal-body .col-md-7 {
      width: 100%;
    }
  }

  @media (max-width: 576px) {
    .btn {
      padding: 0.25rem 0.5rem;
      font-size: 0.75rem;
    }
    
    .btn span.d-none.d-md-inline {
      display: none !important;
    }
    
    .btn i {
      margin-right: 0 !important;
    }
  }

  /* Summary card hover effects */
  .card:hover {
    transform: translateY(-2px);
    transition: transform 0.2s ease;
  }

  /* Loading state for modal */
  .modal-loading {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255,255,255,0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1060;
    border-radius: 16px;
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    // View Event Modal functionality
    const viewButtons = document.querySelectorAll('.viewBtn');
    const viewModal = document.getElementById('viewEventModal');
    
    if (viewButtons.length > 0 && viewModal) {
      viewButtons.forEach(button => {
        button.addEventListener('click', function(e) {
          e.preventDefault();
          
          // Get data attributes from the clicked button
          const eventData = {
            id: this.dataset.id,
            title: this.dataset.title,
            content: this.dataset.content,
            image: this.dataset.image,
            url: this.dataset.url,
            address: this.dataset.address
          };
          
          // Show loading state
          const modalBody = viewModal.querySelector('.modal-body');
          const loadingDiv = document.createElement('div');
          loadingDiv.className = 'modal-loading';
          loadingDiv.innerHTML = '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>';
          modalBody.appendChild(loadingDiv);
          
          // Use setTimeout to ensure UI updates before processing
          setTimeout(() => {
            try {
              // Set modal title
              document.getElementById('eventTitle').textContent = eventData.title || 'No Title';
              document.getElementById('eventId').textContent = eventData.id || 'N/A';
              
              // Set event content
              const contentElement = document.getElementById('eventContent');
              if (contentElement) {
                if (eventData.content && eventData.content.trim() !== '') {
                  // Create a temporary div to decode HTML entities
                  const tempDiv = document.createElement('div');
                  tempDiv.innerHTML = eventData.content;
                  contentElement.innerHTML = tempDiv.textContent || tempDiv.innerText || eventData.content;
                  contentElement.innerHTML = contentElement.innerHTML.replace(/\n/g, '<br>');
                } else {
                  contentElement.innerHTML = '<em class="text-muted">No description available</em>';
                }
              }
              
              // Set address
              const addressSpan = document.querySelector('#eventAddress span');
              if (addressSpan) {
                addressSpan.textContent = eventData.address || 'No address provided';
              }
              
              // Set image
              const imageContainer = document.getElementById('eventImageContainer');
              if (imageContainer) {
                if (eventData.image && eventData.image.trim() !== '') {
                  imageContainer.innerHTML = `
                    <img src="${eventData.image}" 
                         alt="${eventData.title || 'Event Image'}" 
                         class="img-fluid rounded-3 shadow-sm" 
                         style="max-width: 100%; max-height: 200px; object-fit: contain;"
                         onerror="this.onerror=null; this.parentElement.innerHTML = \`
                           <div class='d-flex flex-column align-items-center justify-content-center text-muted' 
                                style='width: 100%; height: 180px; background: rgba(0,0,0,0.05); border-radius: 12px;'>
                             <i class='bi bi-image fs-1 mb-2'></i>
                             <span>Image failed to load</span>
                           </div>
                         \`;">
                  `;
                } else {
                  imageContainer.innerHTML = `
                    <div class="d-flex flex-column align-items-center justify-content-center text-muted" 
                         style="width: 100%; height: 180px; background: rgba(0,0,0,0.05); border-radius: 12px;">
                      <i class="bi bi-image fs-1 mb-2"></i>
                      <span>No Image Available</span>
                    </div>
                  `;
                }
              }
              
              // Set URL link
              const urlLink = document.getElementById('eventUrlLink');
              if (urlLink) {
                if (eventData.url && eventData.url.trim() !== '') {
                  urlLink.href = eventData.url;
                  urlLink.style.display = 'flex';
                } else {
                  urlLink.style.display = 'none';
                }
              }
              
              // Set edit link
              const editLink = document.getElementById('editEventLink');
              if (editLink) {
                // editLink.href = `<?= base_url('admin/events/edit/') ?>${eventData.id}`;
              }
            } catch (error) {
              console.error('Error loading modal content:', error);
            } finally {
              // Remove loading state
              if (loadingDiv && loadingDiv.parentNode) {
                loadingDiv.remove();
              }
            }
          }, 50);
        });
      });
    }

    // Modal event listeners for body scroll locking
    if (viewModal) {
      // When modal is about to show
      viewModal.addEventListener('show.bs.modal', function () {
        document.body.classList.add('modal-open');
        document.body.style.overflow = 'hidden';
        document.body.style.paddingRight = '0';
      });
      
      // When modal is hidden
      viewModal.addEventListener('hidden.bs.modal', function () {
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
        
        // Optional: Clear content when modal is closed
        document.getElementById('eventTitle').textContent = '';
        document.getElementById('eventId').textContent = '';
        document.getElementById('eventContent').innerHTML = '';
        
        const addressSpan = document.querySelector('#eventAddress span');
        if (addressSpan) addressSpan.textContent = '';
        
        const imageContainer = document.getElementById('eventImageContainer');
        if (imageContainer) {
          imageContainer.innerHTML = '';
        }
        
        const urlLink = document.getElementById('eventUrlLink');
        if (urlLink) {
          urlLink.style.display = 'none';
        }
        
        const editLink = document.getElementById('editEventLink');
        if (editLink) {
          editLink.href = '#';
        }
      });
    }

    // Search functionality (if search input exists)
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
      searchInput.addEventListener('input', function (e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('#eventsTable tbody tr');

        rows.forEach(row => {
          const text = row.textContent.toLowerCase();
          row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
        
        // Update page info
        const visibleRows = Array.from(rows).filter(row => row.style.display !== 'none');
        const pageInfo = document.getElementById('pageInfo');
        if (pageInfo) {
          pageInfo.textContent = `Showing ${visibleRows.length} entries`;
        }
      });
    }

    // Initialize tooltips if using Bootstrap 5
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
      const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
      tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
      });
    }
  });
</script>

<?= $this->include('admin/footer') ?>
<?= $this->endSection(); ?>