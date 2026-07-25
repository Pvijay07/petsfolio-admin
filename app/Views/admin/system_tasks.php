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
          <h1 class="h2 fw-bold mb-2 text-white">Task Management</h1>
          <p class="text-white-75 mb-0">Organize, track, and manage all your tasks efficiently</p>
        </div>
        <i class="bi bi-check2-circle position-absolute end-0 bottom-0 opacity-10"
          style="font-size: 14rem; transform: rotate(12deg); margin-right: -3rem; margin-bottom: -3rem;"></i>
      </div>
    </div>

    <!-- Search and Filter Card -->
    <div class="card border-0 shadow-sm mb-4 rounded-3 search-filter-card">
      <div class="card-body p-3">
        <div class="row g-3 align-items-center">
          <div class="col-md-4">
            <div class="input-group">
              <span class="input-group-text bg-black border-end-0 text-white">
                <i class="bi bi-search"></i>
              </span>
              <input type="text" class="form-control border-start-0" placeholder="Search tasks..." id="searchInput">
            </div>
          </div>
          <div class="col-md-3">
            <select class="form-select p-2" id="taskFilter">
              <option value="all">All Tasks</option>
              <option value="pending">Pending</option>
              <option value="in_progress">In Progress</option>
              <option value="completed">Completed</option>
              <option value="cancelled">Cancelled</option>
              <option value="overdue">Overdue</option>
              <option value="alerts">With Alerts</option>
            </select>
          </div>
          <div class="col-md-3">
            <select class="form-select p-2" id="typeFilter">
              <option value="all">All Types</option>
              <option value="one_time">One Time</option>
              <option value="repeated">Repeated</option>
            </select>
          </div>
          <div class="col-md-2">
            <button class="btn btn-black bg-black p-2 text-white w-100" id="addTaskBtn">
              <i class="bi bi-plus-circle me-1"></i>Add Task
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
      <div class="col-md-2 col-sm-6">
        <div class="card stat-card bg-primary text-white mb-0">
          <div class="card-body">
            <h6 class="card-title text-white opacity-75">Total Tasks</h6>
            <h2 class="text-white fw-bold"><?= $statistics['total'] ?? 0 ?></h2>
            <!-- <div class="small opacity-75">All tasks in system</div> -->
          </div>
        </div>
      </div>
      <div class="col-md-2 col-sm-6">
        <div class="card stat-card bg-success text-white mb-0">
          <div class="card-body">
            <h6 class="card-title text-white opacity-75">Completed</h6>
            <h2 class="text-white fw-bold"><?= $statistics['completed'] ?? 0 ?></h2>
            <!-- <div class="small opacity-75">Successfully completed</div> -->
          </div>
        </div>
      </div>
      <div class="col-md-2 col-sm-6">
        <div class="card stat-card bg-warning text-white mb-0">
          <div class="card-body">
            <h6 class="card-title text-white opacity-75">Pending</h6>
            <h2 class="text-white fw-bold"><?= $statistics['pending'] ?? 0 ?></h2>
            <!-- <div class="small opacity-75">Awaiting action</div> -->
          </div>
        </div>
      </div>
      <div class="col-md-2 col-sm-6">
        <div class="card stat-card bg-danger text-white mb-0">
          <div class="card-body">
            <h6 class="card-title text-white opacity-75">Overdue</h6>
            <h2 class="text-white fw-bold"><?= $statistics['overdue_count'] ?? 0 ?></h2>
            <!-- <div class="small opacity-75">Past due date</div> -->
          </div>
        </div>
      </div>
      <div class="col-md-2 col-sm-6">
        <div class="card stat-card bg-info text-white mb-0">
          <div class="card-body">
            <h6 class="card-title text-white opacity-75">Alerts</h6>
            <h2 class="text-white fw-bold"><?= $statistics['alert_count'] ?? 0 ?></h2>
            <!-- <div class="small opacity-75">Upcoming tasks</div> -->
          </div>
        </div>
      </div>
      <div class="col-md-2 col-sm-6">
        <div class="card stat-card bg-secondary text-white mb-0">
          <div class="card-body">
            <h6 class="card-title text-white opacity-75">Completion</h6>
            <h2 class="text-white fw-bold"><?= $statistics['completion_rate'] ?? 0 ?>%</h2>
            <!-- <div class="small opacity-75">Completion rate</div> -->
          </div>
        </div>
      </div>
    </div>

    <!-- Tasks Table -->
    <div class="card border-0 shadow-sm overflow-hidden rounded-3">
      <div class="table-responsive">
        <table class="table table-hover mb-0 task-table" id="tasksTable">
          <thead class="card-header-bg">
            <tr>
              <th class="py-3 text-black fw-semibold border-bottom" style="background: #c6d2ff !important;">#</th>
              <th class="py-3 text-black fw-semibold border-bottom" style="background: #c6d2ff !important;">Task Details</th>
              <th class="py-3 text-black fw-semibold border-bottom" style="background: #c6d2ff !important;">Schedule</th>
              <th class="py-3 text-black fw-semibold border-bottom" style="background: #c6d2ff !important;">Type</th>
              <th class="py-3 text-black fw-semibold border-bottom" style="background: #c6d2ff !important;">Alerts</th>
              <th class="py-3 text-black fw-semibold border-bottom" style="background: #c6d2ff !important;">Status</th>
              <th class="py-3 text-black fw-semibold border-bottom text-center" style="width: 180px; background: #c6d2ff !important;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php $i = 1;
            foreach ($tasks as $task):
              $startDate = new DateTime($task['start_date']);
              $now = new DateTime();
              $isOverdue = $startDate < $now && $task['status'] != 'completed' && $task['status'] != 'cancelled';
              $alertDate = clone $startDate;
              $alertDate->modify('-' . $task['alert_before_days'] . ' days');
              $isAlert = $alertDate <= $now && $startDate >= $now && $task['status'] != 'completed' && $task['status'] != 'cancelled';

              // Determine row class
              $rowClass = 'task-row';
              if ($isAlert)
                $rowClass .= ' task-row-alert';
              if ($isOverdue)
                $rowClass .= ' task-row-overdue';
              ?>
              <tr class="<?= $rowClass ?>" data-status="<?= $isOverdue ? 'overdue' : $task['status'] ?>"
                data-type="<?= $task['task_type'] ?>"
                data-search="<?= strtolower(esc($task['title'] . ' ' . ($task['description'] ?? ''))) ?>">
                <!-- Task Number -->
                <td class="ps-4 py-3 fw-semibold text-dark"><?= $i++ ?></td>

                <!-- Task Details -->
                <td class="py-3">
                  <div class="d-flex flex-column">
                    <div class="fw-bold text-dark mb-1"><?= esc($task['title']) ?></div>
                    <?php if (!empty($task['description'])): ?>
                      <div class="text-muted small" style="font-size: 0.875rem;">
                        <?= esc($task['description']) ?>
                      </div>
                    <?php endif; ?>
                  </div>
                </td>

                <!-- Schedule -->
                <td class="py-3">
                  <div class="d-flex align-items-center text-dark small">
                    <i class="bi bi-calendar me-2 text-primary"></i>
                    <?= date('M j, Y', strtotime($task['start_date'])) ?>
                  </div>
                  <?php if ($task['task_type'] == 'repeated'): ?>
                    <div class="text-muted small mt-1">
                      <i class="bi bi-arrow-repeat me-1"></i>
                      <?= ucfirst($task['repeat_interval']) ?>
                    </div>
                  <?php endif; ?>
                </td>

                <!-- Type -->
                <td class="py-3">
                  <span class="badge type-badge-<?= str_replace('_', '-', $task['task_type']) ?>">
                    <?= $task['task_type'] == 'repeated' ? '<i class="bi bi-arrow-repeat me-1"></i>' : '' ?>
                    <?= ucwords(str_replace('_', ' ', $task['task_type'])) ?>
                  </span>
                </td>

                <!-- Alerts -->
                <td class="py-3">
                  <div class="d-flex flex-column gap-1">
                    <span class="badge <?= $isAlert ? 'alert-badge' : 'bg-light text-dark border' ?>">
                      <i class="bi bi-bell me-1"></i>
                      <?= $task['alert_before_days'] ?> days
                    </span>
                    <?php if ($isAlert): ?>
                      <small class="text-warning">Alert active</small>
                    <?php endif; ?>
                  </div>
                </td>

                <!-- Status -->
                <td class="py-3">
                  <?php
                  $statusClass = '';
                  if ($isOverdue) {
                    $statusClass = 'status-badge-overdue';
                    $statusText = 'Overdue';
                  } else {
                    $statusClass = 'status-badge-' . str_replace('_', '-', $task['status']);
                    $statusText = ucwords(str_replace('_', ' ', $task['status']));
                  }
                  ?>
                  <span class="badge <?= $statusClass ?>">
                    <?php if ($isOverdue): ?>
                      <i class="bi bi-exclamation-triangle-fill me-1"></i>
                    <?php elseif ($task['status'] == 'completed'): ?>
                      <i class="bi bi-check-circle-fill me-1"></i>
                    <?php elseif ($task['status'] == 'in_progress'): ?>
                      <i class="bi bi-play-circle-fill me-1"></i>
                    <?php elseif ($task['status'] == 'cancelled'): ?>
                      <i class="bi bi-x-circle-fill me-1"></i>
                    <?php else: ?>
                      <i class="bi bi-clock-fill me-1"></i>
                    <?php endif; ?>
                    <?= $statusText ?>
                  </span>
                </td>

                <!-- Actions -->
                <td class="py-3 text-center">
                  <div class="d-flex justify-content-center gap-2 action-btns">
                    <?php if ($task['status'] != 'completed' && $task['status'] != 'cancelled'): ?>
                      <button class="btn text-white bg-success btn-sm resolveBtn" data-id="<?= $task['id'] ?>"
                        data-name="<?= esc($task['title']) ?>" title="Mark as completed">
                        <i class="bi bi-check-circle"></i>
                      </button>
                    <?php endif; ?>
                    <button class="btn text-white bg-primary btn-sm editTaskBtn" data-id="<?= $task['id'] ?>"
                      data-title="<?= esc($task['title']) ?>" data-description="<?= esc($task['description'] ?? '') ?>"
                      data-start-date="<?= $task['start_date'] ?>" data-type="<?= $task['task_type'] ?>"
                      data-interval="<?= $task['repeat_interval'] ?>" data-alert-days="<?= $task['alert_before_days'] ?>"
                      data-status="<?= $task['status'] ?>" title="Edit task">
                      <i class="bi bi-pencil"></i>
                    </button>
                    <a href="<?= base_url('admin/tasks/delete/' . $task['id']) ?>" class="btn text-white bg-danger btn-sm"
                      onclick="return confirm('Are you sure you want to delete this task?')" title="Delete task">
                      <i class="bi bi-trash"></i>
                    </a>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <?php if (isset($pager)): ?>
        <div class="d-flex justify-content-between align-items-center p-3 border-top">
          <div class="text-muted small">
            Showing <?= (($currentPage - 1) * $perPage) + 1 ?> to <?= min($currentPage * $perPage, $total) ?> of
            <?= $total ?> tasks
          </div>
          <div class="pagination-container">
            <?= $pager->links() ?>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
</main>

<!-- Task Modal -->
<div class="modal fade" id="taskModal" tabindex="-1" aria-labelledby="taskModalLabel" aria-hidden="true"
  data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content border-0 shadow p-0">
      <div class="modal-header p-3 card-header-bg" style="border-bottom: 2px solid #a3b4ff;">
        <h5 class="modal-title text-black fw-bold" id="taskModalLabel">
          <i class="bi bi-plus-circle me-2"></i>Add/Edit Task
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-0" style="max-height: 70vh; overflow-y: auto;">
        <form action="<?= base_url('tasks/save') ?>" method="post" class="p-3">
          <input type="hidden" name="id" id="task_id">
          <div class="mb-3">
            <label class="form-label fw-semibold text-dark">Task Title <span class="text-danger">*</span></label>
            <input type="text" name="title" id="task_title" class="form-control p-2" required
              placeholder="Enter task title">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold text-dark">Description</label>
            <textarea name="description" id="task_description" class="form-control p-2" rows="3"
              placeholder="Enter task description (optional)"></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold text-dark">Start Date <span class="text-danger">*</span></label>
            <input type="date" name="start_date" id="start_date" class="form-control p-2" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold text-dark">Task Type <span class="text-danger">*</span></label>
            <select name="task_type" id="task_type" class="form-select p-2" required>
              <option value="one_time">One Time Task</option>
              <option value="repeated">Repeated Task</option>
            </select>
          </div>
          <div class="mb-3" id="repeatIntervalContainer" style="display: none;">
            <label class="form-label fw-semibold text-dark">Repeat Interval <span class="text-danger">*</span></label>
            <select name="repeat_interval" id="repeat_interval" class="form-select p-2">
              <option value="daily">Daily</option>
              <option value="weekly">Weekly</option>
              <option value="monthly">Monthly</option>
              <option value="yearly">Yearly</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold text-dark">Alert Before (Days)</label>
            <input type="number" name="alert_before_days" id="alert_before_days" class="form-control p-2" value="2"
              min="0" max="30">
            <div class="form-text text-muted">Number of days before start date to show alerts</div>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold text-dark">Status</label>
            <select name="status" id="task_status" class="form-select p-2">
              <option value="pending">Pending</option>
              <option value="in_progress">In Progress</option>
              <option value="completed">Completed</option>
              <option value="cancelled">Cancelled</option>
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer border-top p-3" style="border-top: 2px solid #e9ecef;">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="bi bi-x-circle me-1"></i>Cancel
        </button>
        <button type="submit" form="taskModal" class="btn bg-primary text-white">
          <i class="bi bi-save me-1"></i>Save Task
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Resolve Task Modal -->
<div class="modal fade" id="resolveModal" tabindex="-1" aria-labelledby="resolveModalLabel" aria-hidden="true"
  data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content border-0 shadow">
      <div class="modal-header p-3 card-header-bg" style="border-bottom: 2px solid #a3b4ff;">
        <h5 class="modal-title text-black fw-bold" id="resolveModalLabel">
          <i class="bi bi-check-circle me-2"></i>Resolve Task
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-0" style="max-height: 70vh; overflow-y: auto;">
        <form action="<?= base_url('tasks/resolve') ?>" method="post" class="p-3">
          <input type="hidden" name="id" id="resolve_task_id">
          <p class="mb-3 text-dark">
            You are resolving: <strong id="resolve_task_name" class="text-primary"></strong>
          </p>
          <div class="mb-3">
            <label class="form-label fw-semibold text-dark">Completion Comments <span
                class="text-danger">*</span></label>
            <textarea name="resolved_comment" class="form-control p-2" rows="4"
              placeholder="Enter completion details, notes, or remarks..." required></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer border-top p-3" style="border-top: 2px solid #e9ecef;">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="bi bi-x-circle me-1"></i>Cancel
        </button>
        <button type="submit" form="resolveModal" class="btn btn-success">
          <i class="bi bi-check-circle me-1"></i>Mark as Completed
        </button>
      </div>
    </div>
  </div>
</div>

<style>
  /* Custom styles matching your theme */
  .status-filter-btn.active .badge {
    background-color: rgba(220, 53, 69, 0.15) !important;
    color: #dc3545 !important;
    border: 1px solid rgba(220, 53, 69, 0.2) !important;
  }

  /* Status badge colors matching your theme */
  .status-badge-pending {
    background-color: rgba(255, 193, 7, 0.1) !important;
    color: #ffc107 !important;
    border: 1px solid rgba(255, 193, 7, 0.2) !important;
  }

  .status-badge-in-progress {
    background-color: rgba(59, 130, 246, 0.1) !important;
    color: #2563eb !important;
    border: 1px solid rgba(59, 130, 246, 0.2) !important;
  }

  .status-badge-completed {
    background-color: rgba(40, 167, 69, 0.1) !important;
    color: #28a745 !important;
    border: 1px solid rgba(40, 167, 69, 0.2) !important;
  }

  .status-badge-cancelled {
    background-color: rgba(108, 117, 125, 0.1) !important;
    color: #6c757d !important;
    border: 1px solid rgba(108, 117, 125, 0.2) !important;
  }

  .status-badge-overdue {
    background-color: rgba(220, 53, 69, 0.1) !important;
    color: #dc3545 !important;
    border: 1px solid rgba(220, 53, 69, 0.2) !important;
    animation: pulse 2s infinite;
  }

  /* Alert badges */
  .alert-badge {
    background-color: rgba(255, 193, 7, 0.15) !important;
    color: #ffc107 !important;
    border: 1px solid rgba(255, 193, 7, 0.3) !important;
    animation: alert-pulse 1.5s infinite;
  }

  /* Type badges */
  .type-badge-one-time {
    background-color: rgba(13, 110, 253, 0.1) !important;
    color: #0d6efd !important;
    border: 1px solid rgba(13, 110, 253, 0.2) !important;
  }

  .type-badge-repeated {
    background-color: rgba(111, 66, 193, 0.1) !important;
    color: #6f42c1 !important;
    border: 1px solid rgba(111, 66, 193, 0.2) !important;
  }

  /* Animations */
  @keyframes pulse {
    0% {
      box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.4);
    }

    70% {
      box-shadow: 0 0 0 6px rgba(220, 53, 69, 0);
    }

    100% {
      box-shadow: 0 0 0 0 rgba(220, 53, 69, 0);
    }
  }

  @keyframes alert-pulse {

    0%,
    100% {
      opacity: 1;
    }

    50% {
      opacity: 0.7;
    }
  }

  /* Table improvements */
  .task-table tbody tr {
    transition: all 0.2s ease;
  }

  .task-table tbody tr:hover {
    background-color: rgba(198, 210, 255, 0.1) !important;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
  }

  /* Card styling matching your theme */
  .card-header-bg {
    background: #c6d2ff !important;
    border-bottom: 2px solid #a3b4ff !important;
  }

  /* Modal improvements */
  body.modal-open {
    overflow: hidden !important;
    padding-right: 0 !important;
  }

  .modal-backdrop {
    background-color: rgba(0, 0, 0, 0.7) !important;
  }

  .modal-backdrop.show {
    opacity: 0.7 !important;
    background-color: #000 !important;
  }

  .modal-content {
    border: none !important;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-direction: column;
    max-height: 90vh;
  }

  .modal-header {
    flex-shrink: 0;
    border-bottom: 2px solid #a3b4ff !important;
  }

  .modal-body {
    flex: 1 1 auto;
    overflow-y: auto;
    padding: 0 !important;
  }

  .modal-footer {
    flex-shrink: 0;
    border-top: 2px solid #e9ecef !important;
  }

  /* Custom scrollbar for modal */
  .modal-body::-webkit-scrollbar {
    width: 8px;
  }

  .modal-body::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
  }

  .modal-body::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 4px;
  }

  .modal-body::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
  }

  /* Statistics card improvements */
  .stat-card {
    border-radius: 12px;
    overflow: hidden;
    transition: transform 0.3s ease;
    border: none;
    padding: 0 !important;
  }

  .stat-card:hover {
    transform: translateY(-5px);
  }

  .stat-card .card-body {
    padding: 1.25rem;
  }

  .stat-card h4 {
    font-weight: 700;
    margin-bottom: 0;
  }

  .stat-card .card-title {
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.5rem;
  }

  /* Badge styling */
  .badge {
    font-weight: 500 !important;
    letter-spacing: 0.3px;
    padding: 0.5em 0.9em;
    border-radius: 50px;
  }

  /* Task row alert styling */
  .task-row-alert {
    position: relative;
    border-left: 4px solid #ffc107 !important;
  }

  .task-row-overdue {
    position: relative;
    border-left: 4px solid #dc3545 !important;
  }

  /* Action buttons */
  .action-btns .btn {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
  }

  /* Search and filter styling */
  .search-filter-card {
    background: #f8f9fa;
    border-radius: 12px;
    border: 1px solid #e9ecef;
  }

  /* Pagination styling */
  .pagination .page-link {
    border-radius: 8px;
    margin: 0 3px;
    border: 1px solid #dee2e6;
    color: #495057;
  }

  .pagination .page-link:hover {
    background-color: #e9ecef;
    border-color: #dee2e6;
  }

  .pagination .page-item.active .page-link {
    background-color: #6366f1;
    border-color: #6366f1;
    color: white;
  }

  /* Responsive adjustments */
  @media (max-width: 768px) {
    .modal-dialog {
      margin: 20px;
      max-width: calc(100% - 40px);
    }

    .modal-content {
      max-height: 85vh;
    }

    .modal-body {
      max-height: 60vh;
    }

    .stat-card .card-body {
      padding: 1rem;
    }

    .stat-card h4 {
      font-size: 1.25rem;
    }

    .action-btns {
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
    }

    .action-btns .btn {
      width: 100%;
    }
  }

  @media (max-width: 576px) {
    .modal-dialog {
      margin: 10px;
      max-width: calc(100% - 20px);
    }

    .modal-content {
      max-height: 90vh;
    }

    .modal-body {
      max-height: 65vh;
    }

    .modal-header,
    .modal-footer {
      padding: 15px !important;
    }

    .modal-body form {
      padding: 15px !important;
    }
  }

  /* Loading animation for table rows */
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

  .task-row {
    animation: fadeIn 0.3s ease-out;
  }

  .bg-gradient-primary {
    background: linear-gradient(135deg, #3f30e3 0%, #5555fa 100%) !important;
  }

  /* Modal animation */
  .modal.fade .modal-dialog {
    transform: translate(0, -50px);
    transition: transform 0.3s ease-out;
  }

  .modal.show .modal-dialog {
    transform: translate(0, 0);
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Task type toggle
    const taskTypeSelect = document.getElementById('task_type');
    const repeatContainer = document.getElementById('repeatIntervalContainer');

    taskTypeSelect.addEventListener('change', function () {
      if (this.value === 'repeated') {
        repeatContainer.style.display = 'block';
      } else {
        repeatContainer.style.display = 'none';
      }
    });

    // Task edit functionality
    document.querySelectorAll('.editTaskBtn').forEach(btn => {
      btn.addEventListener('click', function () {
        document.getElementById('task_id').value = this.dataset.id;
        document.getElementById('task_title').value = this.dataset.title;
        document.getElementById('task_description').value = this.dataset.description || '';
        document.getElementById('start_date').value = this.dataset.startDate;
        document.getElementById('task_type').value = this.dataset.type;
        document.getElementById('task_status').value = this.dataset.status;
        document.getElementById('alert_before_days').value = this.dataset.alertDays;

        if (this.dataset.type === 'repeated') {
          repeatContainer.style.display = 'block';
          document.getElementById('repeat_interval').value = this.dataset.interval;
        } else {
          repeatContainer.style.display = 'none';
        }

        const modal = new bootstrap.Modal(document.getElementById('taskModal'));
        modal.show();
      });
    });

    // Task resolve functionality
    document.querySelectorAll('.resolveBtn').forEach(btn => {
      btn.addEventListener('click', function () {
        document.getElementById('resolve_task_id').value = this.dataset.id;
        document.getElementById('resolve_task_name').textContent = this.dataset.name;

        const modal = new bootstrap.Modal(document.getElementById('resolveModal'));
        modal.show();
      });
    });

    // Add Task button
    document.getElementById('addTaskBtn').addEventListener('click', function () {
      // Clear form
      document.getElementById('task_id').value = '';
      document.getElementById('task_title').value = '';
      document.getElementById('task_description').value = '';
      document.getElementById('start_date').value = '';
      document.getElementById('task_type').value = 'one_time';
      document.getElementById('task_status').value = 'pending';
      document.getElementById('alert_before_days').value = '2';
      repeatContainer.style.display = 'none';

      const modal = new bootstrap.Modal(document.getElementById('taskModal'));
      modal.show();
    });

    // Task filtering
    const taskFilter = document.getElementById('taskFilter');
    const typeFilter = document.getElementById('typeFilter');
    const searchInput = document.getElementById('searchInput');
    const tasksTable = document.getElementById('tasksTable');

    function filterTasks() {
      const statusFilter = taskFilter.value;
      const taskTypeFilter = typeFilter.value;
      const searchTerm = searchInput.value.toLowerCase();
      const rows = tasksTable.querySelectorAll('tbody tr');

      rows.forEach(row => {
        const status = row.dataset.status;
        const type = row.dataset.type;
        const searchText = row.dataset.search;

        let statusMatch = statusFilter === 'all' ||
          (statusFilter === 'pending' && status === 'pending') ||
          (statusFilter === 'in_progress' && status === 'in_progress') ||
          (statusFilter === 'completed' && status === 'completed') ||
          (statusFilter === 'cancelled' && status === 'cancelled') ||
          (statusFilter === 'overdue' && status === 'overdue') ||
          (statusFilter === 'alerts' && row.classList.contains('task-row-alert'));

        let typeMatch = taskTypeFilter === 'all' ||
          (taskTypeFilter === 'one_time' && type === 'one_time') ||
          (taskTypeFilter === 'repeated' && type === 'repeated');

        let searchMatch = searchTerm === '' ||
          searchText.includes(searchTerm);

        row.style.display = statusMatch && typeMatch && searchMatch ? '' : 'none';
      });
    }

    taskFilter.addEventListener('change', filterTasks);
    typeFilter.addEventListener('change', filterTasks);
    searchInput.addEventListener('input', filterTasks);

    // Alert for upcoming tasks
    const alertTasks = document.querySelectorAll('.task-row-alert, .task-row-overdue');
    if (alertTasks.length > 0) {
      const alertCount = document.querySelectorAll('.task-row-alert').length;
      const overdueCount = document.querySelectorAll('.task-row-overdue').length;

      let alertMessage = `You have ${alertCount} task(s) with upcoming alerts`;
      if (overdueCount > 0) {
        alertMessage += ` and ${overdueCount} overdue task(s)`;
      }

      // Create alert banner
      const alertBanner = document.createElement('div');
      alertBanner.className = 'alert alert-warning alert-dismissible fade show mb-4 border-0 shadow-sm';
      alertBanner.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill me-3 fs-4"></i>
                    <div>
                        <strong class="d-block mb-1">Task Alert!</strong>
                        <span class="d-block">${alertMessage}. Please take action.</span>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            `;

      // Insert after header
      const contentArea = document.querySelector('#page_layout');
      contentArea.insertBefore(alertBanner, contentArea.firstChild);
    }

    // Auto-show repeat interval for existing repeated tasks in modal
    <?php if (isset($task) && $task['task_type'] === 'repeated'): ?>
      repeatContainer.style.display = 'block';
    <?php endif; ?>

    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Fix forms - add IDs to forms
    document.querySelectorAll('.modal form').forEach((form, index) => {
      if (form.id === '') {
        form.id = index === 0 ? 'taskModalForm' : 'resolveModalForm';
      }
    });

    // Update submit buttons to use form IDs
    const taskModal = document.getElementById('taskModal');
    const resolveModal = document.getElementById('resolveModal');

    if (taskModal) {
      const saveBtn = taskModal.querySelector('.btn-primary[type="submit"]');
      if (saveBtn) {
        saveBtn.setAttribute('form', 'taskModalForm');
      }
    }

    if (resolveModal) {
      const saveBtn = resolveModal.querySelector('.btn-success[type="submit"]');
      if (saveBtn) {
        saveBtn.setAttribute('form', 'resolveModalForm');
      }
    }
  });

  // Prevent background scrolling when modal is open
  document.addEventListener('DOMContentLoaded', function () {
    const modals = document.querySelectorAll('.modal');
    
    modals.forEach(modal => {
      modal.addEventListener('show.bs.modal', function () {
        document.body.classList.add('modal-open');
      });
      
      modal.addEventListener('hidden.bs.modal', function () {
        document.body.classList.remove('modal-open');
      });
    });
  });
</script>

<?php echo $this->include('admin/footer') ?>
<?php echo $this->endSection() ?>