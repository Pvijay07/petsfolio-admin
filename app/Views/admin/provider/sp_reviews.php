<?php echo $this->extend('admin/includes/header'); ?>
<!--start content-->


<?php echo $this->section('content'); ?>

<main class="main-content">
  <div class="position-relative iq-banner">
    <?php echo $this->include('admin/nav_bar') ?>
  </div>

  <div class="conatiner-fluid content-inner pb-0" id="page_layout">
    <!-- Modern Header Section -->
    <div class="container-fluid p-0 mb-4">
      <div class="bg-gradient-primary text-white p-5 rounded-3 shadow position-relative overflow-hidden">
        <div class="position-relative z-1">
          <h1 class="h2 fw-bold mb-2 text-white">Reviews Management</h1>
          <p class="text-white-75 mb-0">View and manage customer feedback for
            <?php echo $provider_name ?? 'Service Provider'; ?>
          </p>
        </div>
        <i class="bi bi-chat-square-text position-absolute end-0 bottom-0 opacity-10"
          style="font-size: 14rem; transform: rotate(12deg); margin-right: -3rem; margin-bottom: -3rem;"></i>
      </div>
    </div>

    <!-- Reviews Stats Card -->
    <div class="row mb-4">
      <div class="col-md-3 col-sm-6">
        <div class="card border-0 shadow-sm mb-0">
          <div class="card-body p-2">
            <div class="d-flex align-items-center">
              <div
                class="rounded-circle bg-primary bg-opacity-10 text-white d-flex align-items-center justify-content-center me-3"
                style="width: 50px; height: 50px;">
                <i class="bi bi-chat-text fs-4"></i>
              </div>
              <div>
                <h6 class="text-muted mb-1">Total Reviews</h6>
                <h4 class="mb-0 fw-bold"><?= count($reviews) ?></h4>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="card border-0 shadow-sm mb-0">
          <div class="card-body  p-2">
            <div class="d-flex align-items-center">
              <div
                class="rounded-circle bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center me-3"
                style="width: 50px; height: 50px;">
                <i class="bi bi-star-fill fs-4"></i>
              </div>
              <div>
                <h6 class="text-muted mb-1">Average Rating</h6>
                <?php
                $avgRating = 0;
                if (!empty($reviews)) {
                  $totalRating = 0;
                  foreach ($reviews as $review) {
                    $totalRating += $review->rating;
                  }
                  $avgRating = $totalRating / count($reviews);
                }
                ?>
                <h4 class="mb-0 fw-bold"><?= number_format($avgRating, 1) ?>/5</h4>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="card border-0 shadow-sm mb-0">
          <div class="card-body  p-2">
            <div class="d-flex align-items-center">
              <div
                class="rounded-circle bg-success bg-opacity-10 text-white d-flex align-items-center justify-content-center me-3"
                style="width: 50px; height: 50px;">
                <i class="bi bi-person-check fs-4"></i>
              </div>
              <div>
                <h6 class="text-muted mb-1">Customer Reviews</h6>
                <?php
                $customerReviews = 0;
                foreach ($reviews as $review) {
                  if ($review->reviewed_by !== 'admin') {
                    $customerReviews++;
                  }
                }
                ?>
                <h4 class="mb-0 fw-bold"><?= $customerReviews ?></h4>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="card border-0 shadow-sm mb-0">
          <div class="card-body  p-2">
            <div class="d-flex align-items-center">
              <div
                class="rounded-circle bg-info bg-opacity-10 text-white d-flex align-items-center justify-content-center me-3"
                style="width: 50px; height: 50px;">
                <i class="bi bi-shield-check fs-4"></i>
              </div>
              <div>
                <h6 class="text-muted mb-1">Admin Reviews</h6>
                <?php
                $adminReviews = 0;
                foreach ($reviews as $review) {
                  if ($review->reviewed_by === 'admin') {
                    $adminReviews++;
                  }
                }
                ?>
                <h4 class="mb-0 fw-bold"><?= $adminReviews ?></h4>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Reviews Table -->
    <div class="card border-0 shadow-sm mb-0 overflow-hidden rounded-3">
      <div class="card-header card-header-custom">
        <h5 class="card-title mb-0">
          <i class="bi bi-chat-square-text me-2"></i>All Reviews
          <span class="badge bg-primary ms-2"><?= count($reviews) ?></span>
        </h5>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0 review-table" id="reviewsTable">
            <thead class="table-header-bg">
              <tr>
                <th class=" py-3 text-black  fw-semibold border-bottom text-center fs-6"
                  style="background: #c6d2ff !important;">ID</th>
                <th class=" py-3 text-black  fw-semibold border-bottom text-center fs-6"
                  style="background: #c6d2ff !important;">Customer</th>
                <th class=" py-3 text-black  fw-semibold border-bottom text-center fs-6"
                  style="background: #c6d2ff !important;">Rating</th>
                <th class=" py-3 text-black  fw-semibold border-bottom text-center fs-6"
                  style="background: #c6d2ff !important;">Review</th>
                <th class=" py-3 text-black  fw-semibold border-bottom text-center fs-6"
                  style="background: #c6d2ff !important;">Service</th>
                <th class=" py-3 text-black  fw-semibold border-bottom text-center fs-6"
                  style="background: #c6d2ff !important;">Date</th>
                <th class=" py-3 text-black  fw-semibold border-bottom text-center fs-6"
                  style="background: #c6d2ff !important;">Type</th>
              </tr>
            </thead>
            <tbody>
              <?php $i = 1;
              foreach ($reviews as $review): ?>
                <tr class="review-row">
                  <!-- ID Column -->
                  <td class="ps-4 py-3">
                    <span class="fw-semibold text-dark">#<?= $review->id ?></span>
                  </td>

                  <!-- Customer Column -->
                  <td class="py-3">
                    <div class="d-flex align-items-center">
                      <div class="rounded-circle customer-avatar d-flex align-items-center justify-content-center me-3">
                        <?= strtoupper(substr($review->client_name, 0, 1)) ?>
                      </div>
                      <div>
                        <div class="fw-bold text-dark mb-1"><?= esc($review->client_name) ?></div>
                        <div class="text-muted small">
                          <i class="bi bi-telephone me-1"></i> <?= $review->phone ?>
                        </div>
                      </div>
                    </div>
                  </td>

                  <!-- Rating Column -->
                  <td class="py-3">
                    <div class="d-flex align-items-center">
                      <span class="rating-value me-2"><?= $review->rating ?></span>
                      <div class="rating-stars">
                        <?php for ($j = 0; $j < 5; $j++): ?>
                          <?php if ($j < floor($review->rating)): ?>
                            <i class="bi bi-star-fill"></i>
                          <?php elseif ($j < $review->rating): ?>
                            <i class="bi bi-star-half"></i>
                          <?php else: ?>
                            <i class="bi bi-star"></i>
                          <?php endif; ?>
                        <?php endfor; ?>
                      </div>
                    </div>
                  </td>

                  <!-- Review Column -->
                  <td class="py-3">
                    <div class="text-dark mb-1"><?= esc($review->comment) ?></div>
                    <?php if ($review->reviewed_by === 'admin'): ?>
                      <div class="admin-note">
                        <i class="bi bi-info-circle me-1"></i>
                        <small>Added by administrator</small>
                      </div>
                    <?php endif; ?>
                  </td>

                  <!-- Service Column -->
                  <td class="py-3">
                    <span class="badge service-badge">
                      <?= $review->service_name ?>
                    </span>
                  </td>

                  <!-- Date Column -->
                  <td class="py-3">
                    <div class="text-dark small">
                      <i class="bi bi-calendar me-1"></i>
                      <?= date('d M Y', strtotime($review->created_at)) ?>
                    </div>
                    <div class="text-muted small">
                      <i class="bi bi-clock me-1"></i>
                      <?= date('h:i A', strtotime($review->created_at)) ?>
                    </div>
                  </td>

                  <!-- Type Column -->
                  <td class="py-3 text-center">
                    <?php if ($review->reviewed_by === 'admin'): ?>
                      <span class="badge review-badge-admin" data-bs-toggle="tooltip" title="Review added by administrator">
                        <i class="bi bi-shield-check me-1"></i>Admin
                      </span>
                    <?php else: ?>
                      <span class="badge review-badge-customer" data-bs-toggle="tooltip" title="Customer submitted review">
                        <i class="bi bi-person-check me-1"></i>Customer
                      </span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Empty State -->
      <?php if (empty($reviews)): ?>
        <div class="text-center py-5">
          <div class="mb-3">
            <i class="bi bi-chat-square-text display-1 text-muted"></i>
          </div>
          <h5 class="mb-3 text-dark">No reviews found</h5>
          <p class="text-muted mb-4">There are no reviews for this service provider yet.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</main>

<style>
  /* Fixed gradient header */
  .bg-gradient-primary {
    background: linear-gradient(135deg, #3f30e3 0%, #5555fa 100%) !important;
  }

  /* Custom styles matching your theme */
  .review-table tbody tr {
    transition: all 0.2s ease;
  }

  .review-table tbody tr:hover {
    background-color: rgba(198, 210, 255, 0.1) !important;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
  }

  .review-badge-admin {
    background-color: rgba(23, 162, 184, 0.15) !important;
    color: #0dcaf0 !important;
    border: 1px solid rgba(23, 162, 184, 0.25) !important;
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
  }

  .review-badge-customer {
    background-color: rgba(40, 167, 69, 0.15) !important;
    color: #28a745 !important;
    border: 1px solid rgba(40, 167, 69, 0.25) !important;
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
  }

  .rating-stars {
    color: #ffc107;
    font-size: 0.9rem;
  }

  .rating-value {
    min-width: 30px;
    font-weight: 600;
    color: #ffc107;
  }

  .customer-avatar {
    width: 36px;
    height: 36px;
    background: linear-gradient(135deg, #6366f1, #4f46e5);
    color: white;
    font-weight: bold;
    font-size: 0.875rem;
    border: 2px solid rgba(255, 255, 255, 0.2);
  }

  .admin-note {
    background-color: rgba(13, 110, 253, 0.08);
    border-left: 3px solid #0d6efd;
    padding: 8px 12px;
    border-radius: 4px;
    font-size: 0.85rem;
    margin-top: 4px;
  }

  .service-badge {
    background-color: rgba(59, 130, 246, 0.1) !important;
    color: #2563eb !important;
    border: 1px solid rgba(59, 130, 246, 0.2) !important;
    padding: 0.25rem 0.75rem;
    font-size: 0.85rem;
  }

  .table-header-bg {
    background: #c6d2ff !important;
  }

  .table-header-bg th {
    color: #1e293b !important;
    font-weight: 600 !important;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding-top: 1rem !important;
    padding-bottom: 1rem !important;
    border-bottom: 2px solid #a3b4ff !important;
  }

  .card-header-custom {
    background: #c6d2ff !important;
    border-bottom: 2px solid #a3b4ff !important;
    padding: 1.25rem 1.5rem !important;
  }

  .card-header-custom h4 {
    color: #1e293b;
    font-weight: 600;
    margin: 0;
  }

  /* Loading animation */
  @keyframes fadeIn {
    from {
      opacity: 0;
      transform: translateY(10px);
    }

    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .review-row {
    animation: fadeIn 0.3s ease-out;
  }

  /* Responsive adjustments */
  @media (max-width: 768px) {
    .table-responsive {
      border: 1px solid #e9ecef;
      border-radius: 8px;
    }

    .card-header-custom {
      padding: 1rem !important;
    }

    .card-header-custom h4 {
      font-size: 1.25rem;
    }
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Filter functionality
    function filterReviews(type) {
      const rows = document.querySelectorAll('#reviewsTable tbody tr');
      rows.forEach(row => {
        const isAdminReview = row.querySelector('.review-badge-admin') !== null;

        if (type === 'all') {
          row.style.display = '';
        } else if (type === 'admin' && isAdminReview) {
          row.style.display = '';
        } else if (type === 'customer' && !isAdminReview) {
          row.style.display = '';
        } else {
          row.style.display = 'none';
        }
      });
    }

    // Add filter buttons if needed
    // const filterContainer = document.createElement('div');
    // filterContainer.className = 'd-flex gap-2 mb-3';
    // filterContainer.innerHTML = `
    //         <button class="btn btn-sm btn-outline-primary active" onclick="filterReviews('all')">All Reviews</button>
    //         <button class="btn btn-sm btn-outline-primary" onclick="filterReviews('customer')">Customer Reviews</button>
    //         <button class="btn btn-sm btn-outline-primary" onclick="filterReviews('admin')">Admin Reviews</button>
    //     `;

    // Insert filter buttons after the header
    const cardHeader = document.querySelector('.card-header-custom');
    if (cardHeader) {
      cardHeader.parentNode.insertBefore(cardHeader.nextSibling);
    }

    // Make filter function globally available
    window.filterReviews = filterReviews;
  });
</script>

<?php echo $this->include('admin/footer') ?>
<?php echo $this->endSection() ?>