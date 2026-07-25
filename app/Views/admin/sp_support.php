<?php echo $this->extend('admin/includes/header'); ?>

<?php echo $this->section('content'); ?>
<main class="main-content">
  <div class="position-relative iq-banner">
    <?php echo $this->include('admin/nav_bar') ?>
  </div>

  <div class="conatiner-fluid content-inner pb-0" id="page_layout">
    <!-- Modern Header Section -->
    <div class="container-fluid p-0 mb-4 bg-gradient-primary rounded-3">
      <div class=" text-white p-5 rounded-3 shadow position-relative overflow-hidden">
        <div class="position-relative z-1">
          <h1 class="h2 fw-bold mb-2 text-white">Support Tickets</h1>
          <p class="text-white-75 mb-0">Manage customer and provider support requests, track issues, and provide timely
            responses</p>
        </div>
        <i class="bi bi-headset position-absolute end-0 bottom-0 opacity-10"
          style="font-size: 14rem; transform: rotate(12deg); margin-right: -3rem; margin-bottom: -3rem;"></i>
      </div>
      <div class="p-3">
        <ul class="nav nav-tabs gap-2" id="supportTabs" role="tablist"
          style="background: #fff !important; padding: 0.5rem; border-radius: 50px; width: max-content;">
          <li class="nav-item mb-0" role="presentation">
            <button class="nav-link active" id="unsolved-tab" data-bs-toggle="tab" data-bs-target="#unsolved"
              type="button" role="tab" aria-controls="unsolved" aria-selected="true">
              <i class="bi bi-clock-history me-1"></i> Unsolved Tickets
              <span class="badge bg-white text-primary py-1 rounded ms-1">
                <?php
                $unsolvedCount = 0;
                foreach ($supports as $support) {
                  if ($support->status != 'resolved')
                    $unsolvedCount++;
                }
                echo $unsolvedCount;
                ?>
              </span>
            </button>
          </li>
          <li class="nav-item mb-0" role="presentation">
            <button class="nav-link" id="solved-tab" data-bs-toggle="tab" data-bs-target="#solved" type="button"
              role="tab" aria-controls="solved" aria-selected="false">
              <i class="bi bi-check-circle me-1"></i> Solved Tickets
              <span class="badge bg-white text-primary ms-1 rounded-5">
                <?= count($supports) - $unsolvedCount ?>
              </span>
            </button>
          </li>
        </ul>
      </div>
    </div>

    <!-- Support Tickets Card -->
    <div class="card border-0 shadow-sm overflow-hidden rounded-3">
      <div class="card-body p-0">
        <div class="tab-content" id="supportTabsContent">
          <div class="tab-pane fade show active" id="unsolved" role="tabpanel" aria-labelledby="unsolved-tab">
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead style="background: #c6d2ff !important;">
                  <tr>
                    <!-- <th style="background: #c6d2ff !important;" class="py-3 text-black fw-semibold border-bottom">Ticket ID</th> -->
                    <th style="background: #c6d2ff !important;"
                      class="py-3 fs-6   text-black fw-semibold border-bottom">User
                      Details</th>
                    <th style="background: #c6d2ff !important;" class="py-3 fs-6  text-black fw-semibold border-bottom">
                      Message</th>
                    <th style="background: #c6d2ff !important;" class="py-3 fs-6 text-black fw-semibold border-bottom">
                      Date
                    </th>
                    <th style="background: #c6d2ff !important;" class="py-3 fs-6  text-black fw-semibold border-bottom">
                      Status
                    </th>
                    <th style="background: #c6d2ff !important; "
                      class="py-3 text-black fw-semibold border-bottom text-center fs-6">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($supports as $support):
                  if ($support->status != 'resolved'){
                      $path = ($support->user_type == 'user' || $support->user_type == 'client') ? 'users' : 'providers';
                      $message = htmlspecialchars($support->message);
                      $isReactivateRequest = $support->message == 'Please reactivate my account';
                      ?>
                      <tr>
                        <!-- Ticket ID -->
                        <!-- <td class="fw-bold text-dark">#<?= $support->id ?></td> -->

                        <!-- User Details (merged Profile + Name + Phone + User Type badge) -->
                        <td class="p-2 px-3">
                          <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle overflow-hidden" style="width: 45px; height: 45px;">
                              <img class="w-100 h-100 object-fit-cover"
                                src="<?= $support->profile ? 'https://beta.petsfolio.in/public/uploads/' . $path . '/' . $support->profile : 'https://fitinex.com/public/img/avatar/avatar.png'; ?>"
                                alt="Profile">
                            </div>
                            <div>
                              <div class="fw-medium text-dark mb-1">
                                <?= $support->name ?>
                                <?php if (isset($support->unread_count) && $support->unread_count > 0): ?>
                                  <span class="badge rounded-pill px-2 py-1 fw-bold ms-1" style="background: rgba(239, 68, 68, 0.12); color: #dc2626; border: 1px solid rgba(239, 68, 68, 0.25);">
                                    <?= $support->unread_count ?> unread
                                  </span>
                                <?php endif; ?>
                              </div>
                              <div class="d-flex flex-wrap flex-column align-items-start gap-2">
                                <small class="text-muted">
                                  <i class="bi bi-telephone me-1"></i><?= $support->phone ?>
                                </small>
                                <small class="text-muted">
                                  <i class="bi bi-envelope me-1"></i><?= $support->email ?>
                                </small>

                              </div>
                            </div>
                          </div>
                        </td>

                        <!-- Message -->
                        <td class="p-2 px-3">
                          <div class="d-flex align-items-start">
                            <i class="bi bi-chat-left-text me-2 text-muted mt-1"></i>
                            <div>
                              <span class="text-dark d-block mb-1"
                                style="max-width: 250px; overflow: hidden; text-overflow: ellipsis;"
                                title="<?= $message ?>">
                                <?= strlen($message) > 80 ? substr($message, 0, 80) . '...' : $message ?>
                              </span>
                              <?php if ($isReactivateRequest): ?>
                                <small
                                  class="badge bg-primary bg-opacity-10 text-primary border-primary border-opacity-25 px-2 py-1"
                                  style="background-color: rgba(59, 130, 246, 0.1) !important;
        color: #2563eb !important;
        border: 1px solid rgba(59, 130, 246, 0.2) !important; border-radius: 50px;">
                                  <i class="bi bi-arrow-clockwise me-1"></i>Reactivation Request
                                </small>
                              <?php endif; ?>
                            </div>
                          </div>
                        </td>

                        <!-- Date -->
                        <td class="p-2 px-3">
                          <div class="d-flex flex-column">
                            <small class="text-dark"><?= date('M d, Y', strtotime($support->created_at)) ?></small>
                            <small class="text-muted"><?= date('H:i', strtotime($support->created_at)) ?></small>
                          </div>
                        </td>

                        <!-- Status -->
                        <td class="p-2 px-3">
                          <span
                            class="badge d-inline-flex align-items-center gap-1 bg-warning text-dark px-3 py-2 rounded-5">
                            <i class="bi bi-clock"></i> Pending
                          </span>
                        </td>

                        <!-- Actions -->
                        <td class="text-center p-2 px-3">
                          <div class="d-flex gap-2 justify-content-center">
                            <?php if ($isReactivateRequest) { ?>
                              <button class="btn btn-sm bg-success status text-white rounded-5" value="inactive"
                                data-id="<?= $support->user_id ?>" data-user="<?= $support->user_type ?>"
                                data-url="<?= base_url() ?>updateStatus" data-queryid="<?= $support->id ?>"
                                data-module="<?= $support->user_type == 'user' ? 'users' : 'service_providers' ?>">
                                <i class="bi bi-check-circle me-1"></i>Approve
                              </button>

                              <button class="btn btn-sm bg-danger query_status text-white rounded-5"
                                data-queryid="<?= $support->id ?>" data-url="<?= base_url() ?>updateQueryStatus">
                                <i class="bi bi-x-circle me-1"></i>Reject
                              </button>
                            <?php } else { ?>
                              <button type="button" class="btn btn-sm bg-success text-white rounded-5"
                                onclick="confirmMarkAsSolved(<?= $support->id ?>)">
                                <i class="bi bi-check-circle me-1"></i>Mark as Solved
                              </button>
                              <a href="<?= base_url('sp-supportticket/' . $support->id) ?>"
                                class="btn btn-outline-primary btn-sm px-3 py-2 rounded-5"
                                style="background: #c6d2ff !important; border-color: #a3b4ff !important; color: #1a1a1a !important;">
                                <i class="bi bi-chat-left-text me-1"></i>Chat
                              </a>
                            <?php } ?>
                          </div>
                        </td>
                      </tr>
                    <?php }
                  endforeach; ?>
                </tbody>
              </table>
            </div>

            <?php if ($unsolvedCount == 0): ?>
              <div class="text-center py-5">
                <i class="bi bi-check-all display-1 text-success"></i>
                <h5 class="mt-3 text-dark">No unsolved tickets</h5>
                <p class="text-muted">All support tickets have been resolved.</p>
              </div>
            <?php endif; ?>
          </div>

          <!-- SOLVED TAB -->
          <div class="tab-pane fade" id="solved" role="tabpanel" aria-labelledby="solved-tab">
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead style="background: #c6d2ff !important;">
                  <tr>
                    <th style="background: #c6d2ff !important;" class="py-3 fs-6 text-black fw-semibold border-bottom">
                      Ticket
                      ID</th>
                    <th style="background: #c6d2ff !important;" class="py-3 fs-6 text-black fw-semibold border-bottom">
                      User
                      Details</th>
                    <th style="background: #c6d2ff !important;" class="py-3 fs-6 text-black fw-semibold border-bottom">
                      Message</th>
                    <th style="background: #c6d2ff !important;" class="py-3 fs-6 text-black fw-semibold border-bottom">
                      Date
                    </th>
                    <th style="background: #c6d2ff !important;" class="py-3 fs-6 text-black fw-semibold border-bottom">
                      Status
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($supports as $support):
                    if ($support->status == 'completed' || $support->status == 'resolved') {
                      $path = ($support->user_type == 'user' || $support->user_type == 'client') ? 'users' : 'providers';
                      $message = htmlspecialchars($support->message);
                      ?>
                      <tr>
                        <!-- Ticket ID -->
                        <td class="fw-bold text-dark p-2 px-3">#<?= $support->id ?></td>

                        <!-- User Details (merged Profile + Name + Phone + User Type badge) -->
                        <td class="p-2 px-3">
                          <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle overflow-hidden" style="width: 45px; height: 45px;">
                              <img class="w-100 h-100 object-fit-cover"
                                src="<?= $support->profile ? 'https://beta.petsfolio.in/public/uploads/' . $path . '/' . $support->profile : 'https://fitinex.com/public/img/avatar/avatar.png'; ?>"
                                alt="Profile">
                            </div>
                            <div>
                              <div class="fw-medium text-dark mb-1"><?= $support->name ?></div>
                              <div class="d-flex flex-wrap flex-column align-items-start gap-2">
                                <small class="text-muted">
                                  <i class="bi bi-telephone me-1"></i><?= $support->phone ?>
                                </small>
                                <small class="text-muted">
                                  <i class="bi bi-envelope me-1"></i><?= $support->email ?>
                                </small>
                              </div>
                            </div>
                          </div>
                        </td>

                        <!-- Message -->
                        <td class="p-2 px-3">
                          <div class="d-flex align-items-start">
                            <i class="bi bi-chat-left-text me-2 text-muted mt-1"></i>
                            <div>
                              <span class="text-dark d-block"
                                style="max-width: 250px; overflow: hidden; text-overflow: ellipsis;"
                                title="<?= $message ?>">
                                <?= strlen($message) > 80 ? substr($message, 0, 80) . '...' : $message ?>
                              </span>
                            </div>
                          </div>
                        </td>

                        <!-- Date -->
                        <td class="p-2 px-3">
                          <div class="d-flex flex-column">
                            <small class="text-dark"><?= date('M d, Y', strtotime($support->created_at)) ?></small>
                            <small class="text-muted"><?= date('H:i', strtotime($support->created_at)) ?></small>
                          </div>
                        </td>

                        <!-- Status -->
                        <td class="p-2 px-3">
                          <span class="badge d-inline-flex align-items-center gap-1 bg-success px-3 py-2 rounded-5">
                            <i class="bi bi-check-circle"></i> Solved
                          </span>
                        </td>
                      </tr>
                    <?php }
                  endforeach; ?>
                </tbody>
              </table>
            </div>

            <?php if (count($supports) - $unsolvedCount == 0): ?>
              <div class="text-center py-5">
                <i class="bi bi-emoji-frown display-1 text-muted"></i>
                <h5 class="mt-3 text-dark">No solved tickets</h5>
                <p class="text-muted">No support tickets have been marked as solved yet.</p>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- Pagination -->
      <div class="card-footer border-0 d-flex justify-content-between align-items-center p-2">
        <div class="text-muted small">
          Showing <?= count($supports) ?> tickets
        </div>
        <div class="btn-group pt-0 mt-0" style="border-top: none !important;">
          <button class="btn btn-outline-primary btn-sm">
            <i class="bi bi-chevron-left"></i> Previous
          </button>
          <button class="btn btn-primary btn-sm">1</button>
          <button class="btn btn-outline-primary btn-sm">
            Next <i class="bi bi-chevron-right"></i>
          </button>
        </div>
      </div>
    </div>
  </div>
</main>
<style>
  /* Custom styling for support tickets */
  .nav-tabs .nav-link {
    border: none;
    border-radius: 50px;
    padding: 0.75rem 1.5rem;
    color: #6c757d;
    background: transparent;
    transition: all 0.3s ease;
  }

  .nav-tabs .nav-link:hover {
    color: #6366f1;
    background-color: rgba(99, 102, 241, 0.05);
  }

  .nav-tabs .nav-link.active {
    background: #3f30e3;
    color: #ffffff;
    border-bottom: 1px solid #6366f1;
    box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
  }

  .table-responsive {
    border-radius: 8px;
    border: 1px solid #e5e7eb;
  }

  .sticky-top {
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
  }

  .badge.bg-warning {
    background-color: rgba(251, 191, 36, 0.1) !important;
    color: #d97706 !important;
    border: 1px solid rgba(251, 191, 36, 0.2) !important;
  }

  .badge.bg-success {
    background-color: rgba(34, 197, 94, 0.1) !important;
    color: #16a34a !important;
    border: 1px solid rgba(34, 197, 94, 0.2) !important;
  }

  .btn-sm {
    padding: 0.25rem 0.75rem;
    font-size: 0.875rem;
    border-radius: 6px;
  }

  /* Table row hover effect */
  .table-striped tbody tr:hover {
    background-color: rgba(99, 102, 241, 0.03) !important;
  }

  /* Fix for modal backdrop if any modals are used */
  .modal-backdrop {
    background-color: rgba(0, 0, 0, 0.7) !important;
  }

  .bg-gradient-primary {
    background: linear-gradient(135deg, #3f30e3 0%, #5555fa 100%) !important;
  }
</style>

<?php echo $this->include('admin/footer') ?>

<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  // ✅ Handle Active/Inactive Status Toggle
  $(document).on('click', '.status', function (e) {
    e.preventDefault();

    const id = $(this).data('id');
    const url = $(this).data('url');
    const module = $(this).data('module');
    const userType = $(this).data('user');
    const queryId = $(this).data('queryid');
    const currentStatus = $(this).attr('value');
    const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
    const button = $(this);

    Swal.fire({
      title: 'Confirm Action',
      text: `Are you sure you want to ${newStatus === 'active' ? 'activate' : 'deactivate'} this account?`,
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#10b981',
      cancelButtonColor: '#ef4444',
      confirmButtonText: 'Yes, proceed',
      cancelButtonText: 'Cancel'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          type: 'POST',
          url: url,
          data: {
            id: id,
            status: newStatus,
            module: module,
            user_type: userType,
            queryId: queryId
          },
          success: function (data) {
            if (data.status === true) {
              // ✅ Update button appearance dynamically
              button.attr('value', newStatus)
                .toggleClass('btn-success btn-danger')
                .html(`<i class="bi ${newStatus === 'active' ? 'bi-check-circle' : 'bi-x-circle'} me-1"></i>${newStatus === 'active' ? 'Active' : 'Inactive'}`);

              Swal.fire({
                icon: 'success',
                title: 'Success',
                text: data.message,
                timer: 2000,
                showConfirmButton: false
              }).then(() => {
                // Refresh the page after success
                location.reload();
              });
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message
              });
            }
          },
          error: function () {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'Something went wrong!'
            });
          }
        });
      }
    });
  });

  // ✅ Handle Query Status Update
  $(document).on('click', '.query_status', function (e) {
    e.preventDefault();

    const queryId = $(this).data('queryid');
    const url = $(this).data('url');
    const button = $(this);

    // If it's a reject button for reactivation request
    if ($(this).hasClass('btn-danger')) {
      Swal.fire({
        title: 'Reject Reactivation',
        text: 'Are you sure you want to reject this reactivation request?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, reject',
        cancelButtonText: 'Cancel'
      }).then((result) => {
        if (result.isConfirmed) {
          markAsSolved(queryId, url, button);
        }
      });
    } else {
      Swal.fire({
        title: 'Mark as Solved',
        text: 'Are you sure you want to mark this ticket as solved?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, mark as solved',
        cancelButtonText: 'Cancel'
      }).then((result) => {
        if (result.isConfirmed) {
          markAsSolved(queryId, url, button);
        }
      });
    }
  });

  function markAsSolved(queryId, url, button) {
    $.ajax({
      type: 'POST',
      url: url + '/' + queryId,
      success: function (data) {
        if (data.status === true) {
          Swal.fire({
            icon: 'success',
            title: 'Success',
            text: data.message,
            timer: 2000,
            showConfirmButton: false
          }).then(() => {
            location.reload();
          });
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: data.message
          });
        }
      },
      error: function () {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Something went wrong!'
        });
      }
    });
  }

  // Initialize tooltips
  $(function () {
    $('[data-toggle="tooltip"]').tooltip();
  });
  function confirmMarkAsSolved(chatId) {
    Swal.fire({
      title: 'Mark as Solved',
      text: 'Are you sure you want to mark this ticket as solved?',
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#10b981',
      cancelButtonColor: '#6b7280',
      confirmButtonText: 'Yes, mark as solved',
      cancelButtonText: 'Cancel'
    }).then((result) => {
      if (result.isConfirmed) {
        updateTicketStatus(chatId, 'resolved');
      }
    });
  }

  function updateTicketStatus(chatId, status) {
    fetch("https://petsfolio.in/api/supportticket/updateStatus", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: `chat_id=${chatId}&status=${status}`,
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === "ok") {
          Swal.fire({
            icon: 'success',
            title: 'Success',
            text: 'Status updated successfully.',
            timer: 2000,
            showConfirmButton: false
          }).then(() => {
            location.reload();
          });
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Failed to update status.'
          });
        }
      })
      .catch(err => {
        console.error("Error updating status:", err);
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Something went wrong!'
        });
      });
  }

</script>

<?php echo $this->endSection() ?>