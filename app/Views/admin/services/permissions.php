<?php echo $this->extend('admin/includes/header'); ?>
<?php echo $this->section('content'); ?>

<style>
  .bg-gradient-permissions {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%) !important;
  }
  .perm-card-modern {
    border: none;
    border-radius: 16px;
    background: #ffffff;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }
  .perm-card-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
  }
  .nav-tabs-modern {
    border-bottom: 2px solid #e2e8f0;
    gap: 8px;
  }
  .nav-tabs-modern .nav-link {
    border: none;
    border-radius: 12px 12px 0 0;
    padding: 12px 24px;
    font-weight: 700;
    color: #64748b;
    background: transparent;
    transition: all 0.2s ease;
  }
  .nav-tabs-modern .nav-link.active {
    color: #4f46e5;
    background: #ffffff;
    border-bottom: 3px solid #4f46e5;
    box-shadow: 0 -4px 12px rgba(79, 70, 229, 0.08);
  }
  .form-switch .form-check-input {
    width: 2.6em;
    height: 1.3em;
    cursor: pointer;
  }
  .form-switch .form-check-input:checked {
    background-color: #4f46e5;
    border-color: #4f46e5;
  }
  .badge-inherited {
    background: #e0e7ff;
    color: #4338ca;
    font-weight: 600;
  }
  .badge-direct {
    background: #dcfce7;
    color: #15803d;
    font-weight: 700;
  }
  .module-header {
    background: #f8fafc;
    border-left: 4px solid #4f46e5;
  }
</style>

<main class="main-content">
  <div class="position-relative iq-banner">
    <?php echo $this->include('admin/nav_bar') ?>
  </div>

  <div class="container-fluid content-inner mt-n5 py-0 px-4" id="page_layout">

    <!-- Hero Banner -->
    <div class="container-fluid p-0 mb-4 bg-gradient-permissions rounded-3 shadow">
      <div class="text-white p-4 p-md-5 rounded-3 d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
          <h1 class="h2 fw-bold mb-2 text-white"><i class="fas fa-user-shield me-2"></i> Access Control & Permissions Management</h1>
          <p class="text-white-75 mb-0">Role-Based Access Control (RBAC), Direct User Permission Overrides, and Granular Functionality Matrix.</p>
        </div>
        <div class="d-flex gap-2">
          <button class="btn btn-warning text-dark fw-bold shadow-sm" onclick="syncPermissionsFromRegistry()">
            <i class="fas fa-sync me-1"></i> Sync Permissions Registry
          </button>
          <button class="btn btn-light text-primary fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#addRoleModal">
            <i class="fas fa-plus-circle me-1"></i> Add New Role
          </button>
          <button class="btn btn-outline-light fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#addPermissionModal">
            <i class="fas fa-key me-1"></i> Add Custom Permission
          </button>
        </div>
      </div>
    </div>

    <!-- Summary KPI Row -->
    <div class="row g-3 mb-4">
      <div class="col-12 col-sm-6 col-xl-3">
        <div class="card perm-card-modern p-3">
          <div class="d-flex align-items-center justify-content-between">
            <div>
              <span class="text-muted small fw-semibold uppercase">Total Roles</span>
              <h3 class="fw-extrabold mb-0 mt-1 text-primary"><?= count($roles ?? []) ?></h3>
            </div>
            <div class="avatar bg-soft-primary text-primary rounded-circle p-3">
              <i class="fas fa-users-cog fa-2x"></i>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-xl-3">
        <div class="card perm-card-modern p-3">
          <div class="d-flex align-items-center justify-content-between">
            <div>
              <span class="text-muted small fw-semibold uppercase">Total Permissions</span>
              <h3 class="fw-extrabold mb-0 mt-1 text-success"><?= count($permissions ?? []) ?></h3>
            </div>
            <div class="avatar bg-soft-success text-success rounded-circle p-3">
              <i class="fas fa-shield-alt fa-2x"></i>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-xl-3">
        <div class="card perm-card-modern p-3">
          <div class="d-flex align-items-center justify-content-between">
            <div>
              <span class="text-muted small fw-semibold uppercase">Active Modules</span>
              <h3 class="fw-extrabold mb-0 mt-1 text-info"><?= count($groupedPermissions ?? []) ?></h3>
            </div>
            <div class="avatar bg-soft-info text-info rounded-circle p-3">
              <i class="fas fa-cubes fa-2x"></i>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-xl-3">
        <div class="card perm-card-modern p-3">
          <div class="d-flex align-items-center justify-content-between">
            <div>
              <span class="text-muted small fw-semibold uppercase">Configured Staff</span>
              <h3 class="fw-extrabold mb-0 mt-1 text-warning"><?= count($employees ?? []) ?></h3>
            </div>
            <div class="avatar bg-soft-warning text-warning rounded-circle p-3">
              <i class="fas fa-user-check fa-2x"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Navigation Tabs -->
    <ul class="nav nav-tabs nav-tabs-modern mb-4" id="permissionsTab" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="role-matrix-tab" data-bs-toggle="tab" data-bs-target="#role-matrix" type="button" role="tab">
          <i class="fas fa-table-cells me-2"></i> Role Permissions Matrix
        </button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="user-override-tab" data-bs-toggle="tab" data-bs-target="#user-override" type="button" role="tab">
          <i class="fas fa-user-lock me-2"></i> User-Wise Direct Permissions
        </button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="manage-roles-tab" data-bs-toggle="tab" data-bs-target="#manage-roles" type="button" role="tab">
          <i class="fas fa-id-card me-2"></i> Roles & User Assignment
        </button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="perm-master-tab" data-bs-toggle="tab" data-bs-target="#perm-master" type="button" role="tab">
          <i class="fas fa-list me-2"></i> Functionalities Directory
        </button>
      </li>
    </ul>

    <!-- Tab Contents -->
    <div class="tab-content" id="permissionsTabContent">

      <!-- TAB 1: ROLE PERMISSIONS MATRIX -->
      <div class="tab-pane fade show active" id="role-matrix" role="tabpanel">
        <div class="card border-0 shadow-sm rounded-3 mb-4">
          <div class="card-header bg-transparent border-bottom p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
              <div>
                <label class="form-label fw-bold small text-muted text-uppercase mb-1">Select System Role</label>
                <select id="matrixRoleSelect" class="form-select fw-bold border-primary shadow-sm" style="min-width: 260px;">
                  <?php foreach (($roles ?? []) as $r) : ?>
                    <option value="<?= $r['id'] ?>"><?= esc($r['name']) ?> <?= $r['id'] == 1 ? '(Super Admin - Full Access)' : '' ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <span id="rolePermCountBadge" class="badge bg-soft-primary text-primary px-3 py-2 rounded-pill fw-bold mt-4">
                0 Permissions Active
              </span>
            </div>

            <!-- Quick Action Toolbar -->
            <div class="d-flex flex-wrap gap-2 align-items-center mt-3 mt-md-0">
              <button type="button" class="btn btn-sm btn-outline-primary fw-bold" onclick="quickSelectAllMatrix(true)">
                <i class="fas fa-check-double me-1"></i> Select All
              </button>
              <button type="button" class="btn btn-sm btn-outline-secondary fw-bold" onclick="quickSelectAllMatrix(false)">
                <i class="fas fa-times-circle me-1"></i> Deselect All
              </button>
              <button type="button" class="btn btn-sm btn-outline-info fw-bold" onclick="quickSelectTypeMatrix('view')">
                <i class="fas fa-eye me-1"></i> Toggle All View
              </button>
              <button type="button" class="btn btn-sm btn-outline-success fw-bold" onclick="quickSelectTypeMatrix('add')">
                <i class="fas fa-plus me-1"></i> Toggle All Add
              </button>
              <button type="button" class="btn btn-sm btn-outline-warning fw-bold" onclick="quickSelectTypeMatrix('edit')">
                <i class="fas fa-edit me-1"></i> Toggle All Edit
              </button>
              <button type="button" class="btn btn-sm btn-outline-danger fw-bold" onclick="quickSelectTypeMatrix('delete')">
                <i class="fas fa-trash me-1"></i> Toggle All Delete
              </button>
            </div>
          </div>

          <div class="card-body p-4">
            <!-- Filter Search Bar -->
            <div class="mb-4">
              <input type="text" id="matrixSearchInput" class="form-control form-control-lg rounded-3 border-light shadow-sm" placeholder="🔍 Search module or functionality name (e.g. Booking, Call Analysis, Refund, Clients)...">
            </div>

            <!-- Role Matrix Form -->
            <form id="roleMatrixForm">
              <input type="hidden" id="matrixFormRoleId" name="role_id">
              
              <div class="row g-4" id="matrixModulesContainer">
                <?php if (!empty($groupedPermissions)) : ?>
                  <?php foreach ($groupedPermissions as $moduleName => $modulePerms) : ?>
                    <div class="col-12 col-md-6 col-xl-4 module-card-wrapper" data-module="<?= strtolower(esc($moduleName)) ?>">
                      <div class="card border rounded-3 h-100 shadow-sm overflow-hidden">
                        <div class="module-header p-3 d-flex justify-content-between align-items-center">
                          <h6 class="fw-bold mb-0 text-dark">
                            <i class="fas fa-folder me-2 text-primary"></i> <?= esc($moduleName) ?>
                          </h6>
                          <button type="button" class="btn btn-xs btn-soft-primary" onclick="toggleModuleGroup('<?= esc($moduleName) ?>', true)">Select Module</button>
                        </div>
                        <div class="card-body p-3">
                          <div class="d-flex flex-column gap-2">
                            <?php foreach ($modulePerms as $p) : ?>
                              <div class="d-flex align-items-center justify-content-between p-2 rounded hover-bg-light border-bottom border-light perm-item" data-perm-name="<?= strtolower(esc($p['name'])) ?>">
                                <div>
                                  <span class="fw-semibold text-dark text-capitalize small"><?= esc($p['name']) ?></span>
                                  <small class="text-muted d-block" style="font-size:0.75rem;">ID #<?= $p['id'] ?></small>
                                </div>
                                <div class="form-check form-switch m-0">
                                  <input class="form-check-input matrix-checkbox" 
                                         type="checkbox" 
                                         name="permissions[]" 
                                         value="<?= $p['id'] ?>" 
                                         id="matrix_p_<?= $p['id'] ?>"
                                         data-module="<?= esc($moduleName) ?>"
                                         data-type="<?= strtolower(esc($p['name'])) ?>"
                                         onchange="onMatrixToggle(<?= $p['id'] ?>, this.checked)">
                                </div>
                              </div>
                            <?php endforeach; ?>
                          </div>
                        </div>
                      </div>
                    </div>
                  <?php endforeach; ?>
                <?php endif; ?>
              </div>

              <!-- Floating Save Bar -->
              <div class="mt-4 pt-3 border-top text-end">
                <button type="submit" class="btn btn-primary btn-lg px-5 shadow rounded-pill fw-bold" id="btnSaveRoleMatrix">
                  <i class="fas fa-save me-2"></i> Save Role Permissions
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- TAB 2: USER-WISE DIRECT PERMISSIONS -->
      <div class="tab-pane fade" id="user-override" role="tabpanel">
        <div class="card border-0 shadow-sm rounded-3 mb-4">
          <div class="card-header bg-transparent border-bottom p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
              <h5 class="fw-bold mb-1"><i class="fas fa-user-lock me-2 text-primary"></i> User Direct Permissions & Overrides</h5>
              <p class="text-muted small mb-0">Assign or revoke specific permissions for an individual staff member regardless of their global role.</p>
            </div>
            <div class="d-flex align-items-center gap-3">
              <select id="userSelect" class="form-select fw-bold border-success shadow-sm" style="min-width: 280px;">
                <option value="">-- Select Employee / User --</option>
                <?php foreach (($employees ?? []) as $emp) : ?>
                  <option value="<?= $emp['id'] ?>"><?= esc($emp['name']) ?> (Role: <?= esc($emp['role_name'] ?: 'Unassigned') ?>)</option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <div class="card-body p-4">
            <div id="userOverridePrompt" class="text-center py-5 text-muted">
              <i class="fas fa-user-shield fa-3x mb-3 text-secondary opacity-50"></i>
              <h5>Select an Employee to view & manage direct permission overrides.</h5>
            </div>

            <div id="userOverrideContent" style="display:none;">
              <!-- User Info Header Box -->
              <div class="alert alert-info border-0 rounded-3 mb-4 d-flex justify-content-between align-items-center">
                <div>
                  <h6 class="fw-bold mb-1 text-dark" id="userHeaderName">Employee Name</h6>
                  <p class="mb-0 text-muted small" id="userHeaderRole">Role: -</p>
                </div>
                <div class="d-flex gap-2">
                  <span class="badge badge-inherited p-2"><i class="fas fa-shield-alt me-1"></i> Inherited from Role</span>
                  <span class="badge badge-direct p-2"><i class="fas fa-user-check me-1"></i> Direct User Override</span>
                </div>
              </div>

              <!-- User Override Form -->
              <form id="userOverrideForm">
                <input type="hidden" id="overrideUserId" name="user_id">
                
                <div class="row g-4" id="userModulesContainer">
                  <?php if (!empty($groupedPermissions)) : ?>
                    <?php foreach ($groupedPermissions as $moduleName => $modulePerms) : ?>
                      <div class="col-12 col-md-6 col-xl-4">
                        <div class="card border rounded-3 h-100 shadow-sm">
                          <div class="module-header p-3 d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold mb-0 text-dark"><i class="fas fa-folder me-2 text-primary"></i> <?= esc($moduleName) ?></h6>
                          </div>
                          <div class="card-body p-3">
                            <div class="d-flex flex-column gap-2">
                              <?php foreach ($modulePerms as $p) : ?>
                                <div class="d-flex align-items-center justify-content-between p-2 rounded hover-bg-light border-bottom border-light">
                                  <div>
                                    <span class="fw-semibold text-dark text-capitalize small"><?= esc($p['name']) ?></span>
                                    <span id="user_badge_<?= $p['id'] ?>" class="badge bg-secondary ms-2 small" style="font-size:0.7rem;">None</span>
                                  </div>
                                  <div class="form-check form-switch m-0">
                                    <input class="form-check-input user-override-checkbox" 
                                           type="checkbox" 
                                           name="permissions[]" 
                                           value="<?= $p['id'] ?>" 
                                           id="user_p_<?= $p['id'] ?>"
                                           onchange="onUserOverrideToggle(<?= $p['id'] ?>, this.checked)">
                                  </div>
                                </div>
                              <?php endforeach; ?>
                            </div>
                          </div>
                        </div>
                      </div>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </div>

                <div class="mt-4 pt-3 border-top text-end">
                  <button type="submit" class="btn btn-success btn-lg px-5 shadow rounded-pill fw-bold" id="btnSaveUserOverride">
                    <i class="fas fa-save me-2"></i> Save User Direct Overrides
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

      <!-- TAB 3: ROLES & USER ASSIGNMENT -->
      <div class="tab-pane fade" id="manage-roles" role="tabpanel">
        <div class="row g-4">
          <!-- Roles Cards Grid -->
          <div class="col-12 col-lg-5">
            <div class="card border-0 shadow-sm rounded-3">
              <div class="card-header bg-transparent border-bottom p-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0"><i class="fas fa-users-cog me-2 text-primary"></i> System Roles</h5>
                <button class="btn btn-sm btn-primary rounded-pill fw-bold" data-bs-toggle="modal" data-bs-target="#addRoleModal">
                  <i class="fas fa-plus me-1"></i> New Role
                </button>
              </div>
              <div class="card-body p-3">
                <div class="list-group list-group-flush">
                  <?php foreach (($roles ?? []) as $r) : ?>
                    <div class="list-group-item p-3 border rounded-3 mb-2 d-flex justify-content-between align-items-center bg-light">
                      <div>
                        <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                          <h6 class="fw-bold mb-0 text-dark"><?= esc($r['name']) ?></h6>
                          <?php if (!empty($r['slug'])) : ?>
                            <span class="badge bg-white text-primary border px-2 py-1 small font-monospace shadow-xs">
                              <i class="fas fa-hashtag me-1 opacity-50"></i><?= esc($r['slug']) ?>
                            </span>
                          <?php endif; ?>
                        </div>
                        <small class="text-muted"><?= esc($r['description'] ?: 'Standard system access role') ?></small>
                      </div>
                      <div class="d-flex gap-1">
                        <button class="btn btn-sm btn-icon btn-outline-primary" onclick="editRole(<?= $r['id'] ?>, '<?= esc($r['name']) ?>', '<?= esc($r['slug'] ?? '') ?>', '<?= esc($r['description'] ?? '') ?>')">
                          <i class="fas fa-edit"></i>
                        </button>
                        <?php if ($r['id'] != 1 && ($r['slug'] ?? '') !== 'super_admin') : ?>
                          <button class="btn btn-sm btn-icon btn-outline-danger" onclick="deleteRole(<?= $r['id'] ?>, '<?= esc($r['name']) ?>')">
                            <i class="fas fa-trash"></i>
                          </button>
                        <?php endif; ?>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>
          </div>

          <!-- Employees Role Assignment Table -->
          <div class="col-12 col-lg-7">
            <div class="card border-0 shadow-sm rounded-3">
              <div class="card-header bg-transparent border-bottom p-4">
                <h5 class="fw-bold mb-0"><i class="fas fa-user-tag me-2 text-primary"></i> Staff Role Assignment</h5>
              </div>
              <div class="card-body p-0">
                <div class="table-responsive">
                  <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                      <tr>
                        <th class="py-3 px-4">Staff Member</th>
                        <th class="py-3">Email</th>
                        <th class="py-3">Assigned Role</th>
                        <th class="py-3 text-center">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach (($employees ?? []) as $emp) : ?>
                        <tr>
                          <td class="px-4 fw-bold text-dark">
                            <i class="fas fa-user-circle me-1 text-primary"></i> <?= esc($emp['name']) ?>
                          </td>
                          <td class="text-muted small"><?= esc($emp['email']) ?></td>
                          <td>
                            <select class="form-select form-select-sm fw-bold border-secondary" onchange="changeUserRole(<?= $emp['id'] ?>, this.value)">
                              <?php foreach (($roles ?? []) as $r) : ?>
                                <option value="<?= $r['id'] ?>" <?= $emp['role_id'] == $r['id'] ? 'selected' : '' ?>><?= esc($r['name']) ?></option>
                              <?php endforeach; ?>
                            </select>
                          </td>
                          <td class="text-center">
                            <button class="btn btn-sm btn-outline-primary" onclick="switchToUserTab(<?= $emp['id'] ?>)">
                              <i class="fas fa-user-lock me-1"></i> Overrides
                            </button>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- TAB 4: FUNCTIONALITIES DIRECTORY -->
      <div class="tab-pane fade" id="perm-master" role="tabpanel">
        <div class="card border-0 shadow-sm rounded-3 mb-4">
          <div class="card-header bg-transparent border-bottom p-4 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0"><i class="fas fa-key me-2 text-primary"></i> Master Functionalities & Permissions</h5>
            <button class="btn btn-sm btn-primary rounded-pill fw-bold" data-bs-toggle="modal" data-bs-target="#addPermissionModal">
              <i class="fas fa-plus me-1"></i> Add Permission
            </button>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                  <tr>
                    <th class="py-3 px-4">ID</th>
                    <th class="py-3">Module Name</th>
                    <th class="py-3">Permission Key / Action</th>
                    <th class="py-3 text-center">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach (($permissions ?? []) as $p) : ?>
                    <tr>
                      <td class="px-4 fw-bold">#<?= $p['id'] ?></td>
                      <td><span class="badge bg-soft-primary text-primary fw-bold px-3 py-2"><?= esc($p['module']) ?></span></td>
                      <td class="fw-bold text-dark text-capitalize"><?= esc($p['name']) ?></td>
                      <td class="text-center">
                        <button class="btn btn-sm btn-icon btn-outline-danger" onclick="deletePermission(<?= $p['id'] ?>)">
                          <i class="fas fa-trash"></i>
                        </button>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</main>

<!-- Add/Edit Role Modal -->
<div class="modal fade" id="addRoleModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-gradient-permissions text-white p-4">
        <h5 class="modal-title text-white fw-bold" id="roleModalTitle"><i class="fas fa-users-cog me-2"></i> System Role</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form id="roleForm">
        <input type="hidden" id="roleEditId" name="role_id">
        <div class="modal-body p-4">
          <div class="mb-3">
            <label class="form-label fw-bold">Role Name <span class="text-danger">*</span></label>
            <input type="text" id="roleName" name="name" class="form-control" placeholder="e.g. Telecaller Manager" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold d-flex justify-content-between align-items-center mb-1">
              <span>Role Slug</span>
              <small class="text-muted fw-normal">Unique code identifier</small>
            </label>
            <div class="input-group">
              <span class="input-group-text bg-light text-muted"><i class="fas fa-hashtag"></i></span>
              <input type="text" id="roleSlug" name="slug" class="form-control font-monospace" placeholder="e.g. telecaller_manager">
            </div>
            <small class="text-muted">Lowercase letters, numbers, and underscores (auto-generated if left blank).</small>
          </div>
          <div class="mb-3">
            <label class="form-label small fw-semibold">Role Description</label>
            <textarea id="roleDescription" name="description" class="form-control" rows="3" placeholder="Describe the responsibilities for this role..."></textarea>
          </div>
        </div>
        <div class="modal-footer bg-light p-3">
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary btn-sm px-4" id="btnSaveRole">
            <i class="fas fa-save me-1"></i> Save Role
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Add Permission Modal -->
<div class="modal fade" idaddPermissionModal" tabindex="-1" id="addPermissionModal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-gradient-permissions text-white p-4">
        <h5 class="modal-title text-white fw-bold"><i class="fas fa-key me-2"></i> Add Custom Permission</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form method="post" action="<?= base_url('admin/add-permission') ?>">
        <div class="modal-body p-4">
          <div class="mb-3">
            <label class="form-label fw-bold">Module Name <span class="text-danger">*</span></label>
            <input type="text" name="module" class="form-control" placeholder="e.g. Booking, Call Analysis, Refund" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold">Permission Action / Key <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" placeholder="e.g. view, add, edit, delete, audit, export" required>
          </div>
        </div>
        <div class="modal-footer bg-light p-3">
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary btn-sm px-4">
            <i class="fas fa-plus me-1"></i> Add Permission
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  let currentRoleAssignedIds = [];
  let currentUserEffectiveData = null;

  $(document.ready ? document : window).ready(function() {
    loadRoleMatrixPermissions();

    $('#matrixRoleSelect').on('change', function() {
      loadRoleMatrixPermissions();
    });

    $('#userSelect').on('change', function() {
      loadUserPermissions();
    });

    // Reset modal on New Role button click
    $('[data-bs-target="#addRoleModal"]').on('click', function() {
      $('#roleEditId').val('');
      $('#roleName').val('');
      $('#roleSlug').val('');
      $('#roleDescription').val('');
      $('#roleModalTitle').html('<i class="fas fa-plus-circle me-2"></i> Create New Role');
    });

    // Real-time slug auto-generation when typing role name for new role
    $('#roleName').on('input', function() {
      if (!$('#roleEditId').val()) {
        let val = $(this).val();
        let slug = val.toLowerCase().replace(/[^a-z0-9_]+/g, '_').replace(/^_+|_+$/g, '');
        $('#roleSlug').val(slug);
      }
    });

    // Live search filter for Role Matrix
    $('#matrixSearchInput').on('keyup', function() {
      let query = $(this).val().toLowerCase().trim();
      if (!query) {
        $('.module-card-wrapper').show();
        $('.perm-item').show();
        return;
      }
      $('.module-card-wrapper').each(function() {
        let modName = $(this).data('module') || '';
        let hasMatchingPerm = false;
        $(this).find('.perm-item').each(function() {
          let permName = $(this).data('perm-name') || '';
          let text = $(this).text().toLowerCase();
          if (text.indexOf(query) > -1 || modName.indexOf(query) > -1) {
            $(this).show();
            hasMatchingPerm = true;
          } else {
            $(this).hide();
          }
        });
        if (hasMatchingPerm || modName.indexOf(query) > -1) {
          $(this).show();
        } else {
          $(this).hide();
        }
      });
    });

    // Role Matrix Form Submit
    $('#roleMatrixForm').on('submit', function(e) {
      e.preventDefault();
      let btn = $('#btnSaveRoleMatrix');
      btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i> Saving Role Matrix...');

      $.ajax({
        url: '<?= base_url('admin/permissions/save-role-permissions') ?>',
        type: 'POST',
        data: $(this).serialize(),
        success: function(resp) {
          btn.prop('disabled', false).html('<i class="fas fa-save me-2"></i> Save Role Permissions');
          if (resp.status) {
            showToast('Role permissions updated successfully!', 'success');
            loadRoleMatrixPermissions();
          } else {
            showToast(resp.message || 'Failed to update role permissions.', 'danger');
          }
        },
        error: function() {
          btn.prop('disabled', false).html('<i class="fas fa-save me-2"></i> Save Role Permissions');
          showToast('Server error saving role permissions.', 'danger');
        }
      });
    });

    // User Direct Override Form Submit
    $('#userOverrideForm').on('submit', function(e) {
      e.preventDefault();
      let btn = $('#btnSaveUserOverride');
      btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i> Saving Overrides...');

      $.ajax({
        url: '<?= base_url('admin/permissions/save-user-permissions') ?>',
        type: 'POST',
        data: $(this).serialize(),
        success: function(resp) {
          btn.prop('disabled', false).html('<i class="fas fa-save me-2"></i> Save User Direct Overrides');
          if (resp.status) {
            showToast('Direct user permission overrides updated successfully!', 'success');
            loadUserPermissions();
          } else {
            showToast(resp.message || 'Failed to update user overrides.', 'danger');
          }
        },
        error: function() {
          btn.prop('disabled', false).html('<i class="fas fa-save me-2"></i> Save User Direct Overrides');
          showToast('Server error saving user overrides.', 'danger');
        }
      });
    });

    // Save Role Form Submit
    $('#roleForm').on('submit', function(e) {
      e.preventDefault();
      let btn = $('#btnSaveRole');
      btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Saving...');

      $.ajax({
        url: '<?= base_url('admin/permissions/save-role') ?>',
        type: 'POST',
        data: $(this).serialize(),
        success: function(resp) {
          btn.prop('disabled', false).html('<i class="fas fa-save me-1"></i> Save Role');
          if (resp.status) {
            showToast(resp.message, 'success');
            setTimeout(function() { location.reload(); }, 1200);
          } else {
            showToast(resp.message, 'danger');
          }
        }
      });
    });
  });

  function loadRoleMatrixPermissions() {
    let roleId = $('#matrixRoleSelect').val();
    $('#matrixFormRoleId').val(roleId);

    $('.matrix-checkbox').prop('checked', false);

    $.ajax({
      url: '<?= base_url('admin/permissions/get-role-permissions') ?>',
      type: 'POST',
      data: { role_id: roleId },
      success: function(resp) {
        if (!resp.status) return;
        currentRoleAssignedIds = resp.permission_ids || [];
        
        $('#rolePermCountBadge').text(currentRoleAssignedIds.length + ' Permissions Active');

        currentRoleAssignedIds.forEach(id => {
          $('#matrix_p_' + id).prop('checked', true);
        });
      }
    });
  }

  function loadUserPermissions() {
    let userId = $('#userSelect').val();
    if (!userId) {
      $('#userOverridePrompt').show();
      $('#userOverrideContent').hide();
      return;
    }

    $('#overrideUserId').val(userId);
    $('.user-override-checkbox').prop('checked', false);

    $.ajax({
      url: '<?= base_url('admin/permissions/get-user-permissions') ?>',
      type: 'POST',
      data: { user_id: userId },
      success: function(resp) {
        if (!resp.status) return;
        let d = resp.data;
        currentUserEffectiveData = d;

        $('#userHeaderName').text(d.user.name + ' (' + d.user.email + ')');
        $('#userHeaderRole').text('Assigned Role: ' + (d.user.role_name || 'Role #' + d.user.role_id));

        let roleIds = d.role_permission_ids || [];
        let directIds = d.direct_permission_ids || [];

        $('.user-override-checkbox').each(function() {
          let pId = parseInt($(this).val());
          let badge = $('#user_badge_' + pId);

          if (directIds.includes(pId)) {
            $(this).prop('checked', true);
            badge.removeClass().addClass('badge badge-direct ms-2').text('Direct User Override');
          } else if (roleIds.includes(pId)) {
            $(this).prop('checked', true);
            badge.removeClass().addClass('badge badge-inherited ms-2').text('Role Inherited');
          } else {
            $(this).prop('checked', false);
            badge.removeClass().addClass('badge bg-secondary ms-2').text('Not Granted');
          }
        });

        $('#userOverridePrompt').hide();
        $('#userOverrideContent').show();
      }
    });
  }

  function onMatrixToggle(permId, isChecked) {
    let roleId = $('#matrixRoleSelect').val();
    $.ajax({
      url: '<?= base_url('admin/permissions/toggle-role-permission') ?>',
      type: 'POST',
      data: { role_id: roleId, permission_id: permId, enable: isChecked ? 1 : 0 },
      success: function(resp) {
        if (resp.status) {
          showToast('Permission toggle updated.', 'info');
        }
      }
    });
  }

  function onUserOverrideToggle(permId, isChecked) {
    let userId = $('#userSelect').val();
    $.ajax({
      url: '<?= base_url('admin/permissions/toggle-user-permission') ?>',
      type: 'POST',
      data: { user_id: userId, permission_id: permId, enable: isChecked ? 1 : 0 },
      success: function(resp) {
        if (resp.status) {
          showToast('User direct permission updated.', 'info');
        }
      }
    });
  }

  function quickSelectAllMatrix(enable) {
    $('.matrix-checkbox').prop('checked', enable);
    $('#roleMatrixForm').submit();
  }

  function quickSelectTypeMatrix(type) {
    $('.matrix-checkbox[data-type="' + type + '"]').prop('checked', true);
    $('#roleMatrixForm').submit();
  }

  function toggleModuleGroup(moduleName, enable) {
    $('.matrix-checkbox[data-module="' + moduleName + '"]').prop('checked', enable);
    $('#roleMatrixForm').submit();
  }

  function editRole(id, name, slug, desc) {
    $('#roleEditId').val(id);
    $('#roleName').val(name);
    $('#roleSlug').val(slug || '');
    $('#roleDescription').val(desc);
    $('#roleModalTitle').html('<i class="fas fa-edit me-2"></i> Edit Role #' + id);
    new bootstrap.Modal(document.getElementById('addRoleModal')).show();
  }

  function deleteRole(id, name) {
    if (!confirm('Are you sure you want to delete role "' + name + '"?')) return;
    $.ajax({
      url: '<?= base_url('admin/permissions/delete-role') ?>',
      type: 'POST',
      data: { role_id: id },
      success: function(resp) {
        if (resp.status) {
          showToast(resp.message, 'success');
          setTimeout(function() { location.reload(); }, 1200);
        } else {
          showToast(resp.message, 'danger');
        }
      }
    });
  }

  function deletePermission(id) {
    if (!confirm('Are you sure you want to delete permission #' + id + '?')) return;
    $.ajax({
      url: '<?= base_url('admin/permissions/delete-permission') ?>',
      type: 'POST',
      data: { permission_id: id },
      success: function(resp) {
        if (resp.status) {
          showToast(resp.message, 'success');
          setTimeout(function() { location.reload(); }, 1200);
        } else {
          showToast(resp.message, 'danger');
        }
      }
    });
  }

  function changeUserRole(userId, roleId) {
    $.ajax({
      url: '<?= base_url('admin/permissions/assign-user-role') ?>',
      type: 'POST',
      data: { user_id: userId, role_id: roleId },
      success: function(resp) {
        if (resp.status) {
          showToast('Staff member role updated successfully!', 'success');
        } else {
          showToast(resp.message || 'Failed to update user role.', 'danger');
        }
      }
    });
  }

  function switchToUserTab(userId) {
    $('#userSelect').val(userId).trigger('change');
    let tab = new bootstrap.Tab(document.getElementById('user-override-tab'));
    tab.show();
  }

  function syncPermissionsFromRegistry() {
    if (!confirm('Sync permissions from central registry? This will populate any new module permissions.')) return;
    $.ajax({
      url: '<?= base_url('admin/permissions/sync-permissions') ?>',
      type: 'POST',
      success: function(resp) {
        if (resp.status) {
          showToast(resp.message, 'success');
          setTimeout(function() { location.reload(); }, 1200);
        } else {
          showToast(resp.message || 'Sync failed.', 'danger');
        }
      },
      error: function() {
        showToast('Server error syncing permissions.', 'danger');
      }
    });
  }

  function showToast(message, type) {
    let bg = type === 'success' ? 'bg-success' : (type === 'danger' ? 'bg-danger' : 'bg-info');
    let toastId = 'toast-' + Date.now();
    let toastHtml = `
      <div id="${toastId}" class="toast align-items-center text-white ${bg} border-0 position-fixed bottom-0 end-0 m-3 shadow-lg" style="z-index: 9999;" role="alert">
        <div class="d-flex">
          <div class="toast-body d-flex align-items-center gap-2 fw-semibold">
            <i class="fas fa-info-circle me-1"></i> ${message}
          </div>
          <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
      </div>
    `;
    document.body.insertAdjacentHTML('beforeend', toastHtml);
    let toastEl = document.getElementById(toastId);
    let toast = new bootstrap.Toast(toastEl, { delay: 2500 });
    toast.show();
    toastEl.addEventListener('hidden.bs.toast', function() { this.remove(); });
  }
</script>

<?php echo $this->include('admin/footer') ?>
<?php echo $this->endSection(); ?>