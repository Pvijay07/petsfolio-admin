<?php echo $this->extend('admin/includes/header'); ?>
<!--start content-->

<?php echo $this->section('content'); ?>
<div class="conatiner-fluid content-inner pb-0 px-4" id="page_layout">
  <!-- Modern Header Section -->
  <div class="container-fluid p-0 mb-4 bg-gradient-primary rounded-3">
    <div class=" text-white p-5  shadow position-relative overflow-hidden">
      <div class="position-relative z-1">
        <h1 class="h2 fw-bold mb-2 text-white">Verification Rules</h1>
        <p class="text-indigo-100 mb-0">Manage provider verification requirements based on service type and location</p>
      </div>
      <i class="bi bi-shield-check position-absolute end-0 bottom-0 opacity-10"
        style="font-size: 12rem; transform: rotate(12deg); margin-right: -2rem; margin-bottom: -2rem;"></i>
    </div>
    <div class="p-3">
      <div class="card border-0 shadow-sm mb-0">
        <div class="card-body p-3">
          <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
            <div class="d-flex flex-column flex-md-row align-items-stretch align-items-md-center gap-3 w-100 w-md-auto">
              <div class="input-group flex-grow-1 flex-md-grow-0">
                <span class="input-group-text bg-light border-end-0">
                  <i class="bi bi-search text-muted"></i>
                </span>
                <input type="text" class="form-control border-start-0 ps-0" placeholder="Search rules..." id="ruleSearch">
              </div>
              <button type="button" class="btn bg-primary text-white d-flex align-items-center justify-content-center gap-1" onclick="openAddRuleModal()">
                <i class="bi bi-plus-lg"></i>
                <span>Add New Rule</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Rules Table -->
  <div class="card border-0 shadow-sm overflow-hidden mb-4">
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead style="background-color: #c6d2ff!important;">
          <tr>
            <th class="ps-4 py-3 text-black fs-6 fw-semibold border-bottom">Service Type</th>
            <th class="py-3 text-black fs-6 fw-semibold border-bottom">Location</th>
            <th class="py-3 text-black fs-6 fw-semibold border-bottom text-center">Reg. Fee</th>
            <th class="py-3 text-black fs-6 fw-semibold border-bottom text-center">Requirements</th>
            <th class="py-3 text-black fs-6 fw-semibold border-bottom text-center">Deferred</th>
            <th class="py-3 text-black fs-6 fw-semibold border-bottom text-center">Status</th>
            <th class="pe-4 py-3 text-black fs-6 fw-semibold border-bottom text-end">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rules as $rule): ?>
            <tr>
              <td class="ps-4 py-3">
                <div class="d-flex align-items-center gap-2">
                  <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px;">
                    <?= strtoupper(substr($rule['service_type'], 0, 1)) ?>
                  </div>
                  <span class="fw-medium text-dark text-capitalize"><?= $rule['service_type'] ?></span>
                </div>
              </td>
              <td class="py-3">
                <div class="d-flex flex-column">
                  <span class="text-dark fw-medium">
                    <?= $rule['city'] ?: ($rule['state'] ?: ($rule['country'] ?: 'Global')) ?>
                  </span>
                  <small class="text-muted">
                    <?php
                    if ($rule['city']) echo "City: " . $rule['city'];
                    elseif ($rule['state']) echo "State: " . $rule['state'];
                    else echo "Country: " . $rule['country'] ?: 'All';
                    ?>
                  </small>
                </div>
              </td>
              <td class="py-3 text-center">
                <span class="fw-bold text-dark">₹<?= number_format($rule['registration_fee'], 2) ?></span>
              </td>
              <td class="py-3 text-center">
                <div class="d-flex gap-1 justify-content-center">
                  <?php if ($rule['call_verification_required']): ?>
                    <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25" title="Call Verification">Call</span>
                  <?php endif; ?>
                  <?php if ($rule['aadhaar_verification_required']): ?>
                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25" title="Aadhaar Verification">Aadhaar</span>
                  <?php endif; ?>
                </div>
              </td>
              <td class="py-3 text-center">
                <?php if ($rule['deferred_verification']): ?>
                  <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25">Yes</span>
                <?php else: ?>
                  <span class="badge bg-light text-muted border">No</span>
                <?php endif; ?>
              </td>
              <td class="py-3 text-center">
                <span class="badge <?= $rule['status'] === 'active' ? 'bg-success' : 'bg-danger' ?> bg-opacity-10 <?= $rule['status'] === 'active' ? 'text-success' : 'text-danger' ?> border px-3 py-1 fw-medium" style="border-radius: 50px;">
                  <?= ucfirst($rule['status']) ?>
                </span>
              </td>
              <td class="pe-4 py-3 text-end">
                <div class="d-flex justify-content-end gap-2">
                  <button class="btn btn-sm btn-outline-primary border-0 bg-light" onclick="editRule(<?= $rule['id'] ?>)">
                    <i class="bi bi-pencil-square"></i>
                  </button>
                  <button class="btn btn-sm btn-outline-danger border-0 bg-light" onclick="deleteRule(<?= $rule['id'] ?>)">
                    <i class="bi bi-trash"></i>
                  </button>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Verification Flow Component (Modal) -->

<div id="verification-container" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm hidden">
  <div class="bg-white/90 backdrop-blur-md rounded-3xl shadow-2xl max-w-lg w-full overflow-hidden border border-white/20 transform transition-all duration-300 scale-95 opacity-0" id="verification-modal">
    <!-- Modal Header -->
    <div class="relative h-32 bg-gradient-to-br from-indigo-600 to-violet-600 p-6 flex items-end">
      <div class="absolute top-4 right-4 text-white/80 cursor-pointer hover:text-white transition-colors" onclick="closeVerificationModal()">
        <i class="fas fa-times text-xl"></i>
      </div>
      <div>
        <h2 class="text-2xl font-bold text-white mb-1">Provider Verification</h2>
        <p class="text-indigo-100 text-sm">Complete these steps to start taking orders</p>
      </div>
      <div class="absolute -right-8 -top-8 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
    </div>

    <!-- Modal Body -->
    <div class="p-8">
      <div class="space-y-6">
        <!-- Step 1: Registration Fee -->
        <div class="flex items-start gap-4 p-4 rounded-2xl bg-indigo-50 border border-indigo-100/50 transition-all hover:shadow-md">
          <div class="w-12 h-12 rounded-xl bg-indigo-600 flex items-center justify-center text-white shadow-lg shadow-indigo-200 flex-shrink-0">
            <i class="fas fa-wallet text-xl"></i>
          </div>
          <div class="flex-grow">
            <div class="flex justify-between items-center mb-1">
              <h3 class="font-semibold text-gray-800">Registration Fee</h3>
              <span class="text-indigo-600 font-bold">₹49</span>
            </div>
            <p class="text-sm text-gray-600">One-time non-refundable fee for platform maintenance and background checks.</p>
          </div>
        </div>

        <!-- Step 2: Call Verification -->
        <div class="flex items-start gap-4 p-4 rounded-2xl bg-white border border-gray-100 transition-all hover:shadow-md opacity-75 grayscale-[0.5]">
          <div class="w-12 h-12 rounded-xl bg-gray-200 flex items-center justify-center text-gray-500 flex-shrink-0">
            <i class="fas fa-phone-alt text-xl"></i>
          </div>
          <div class="flex-grow">
            <h3 class="font-semibold text-gray-800">Call Verification</h3>
            <p class="text-sm text-gray-600">Our team will call you to verify your experience and service details.</p>
          </div>
        </div>

        <!-- Step 3: Aadhaar Verification -->
        <div class="flex items-start gap-4 p-4 rounded-2xl bg-white border border-gray-100 transition-all hover:shadow-md opacity-75 grayscale-[0.5]">
          <div class="w-12 h-12 rounded-xl bg-gray-200 flex items-center justify-center text-gray-500 flex-shrink-0">
            <i class="fas fa-id-card text-xl"></i>
          </div>
          <div class="flex-grow">
            <h3 class="font-semibold text-gray-800">Aadhaar Verification</h3>
            <p class="text-sm text-gray-600">Secure identity verification using your Aadhaar number or document upload.</p>
          </div>
        </div>
      </div>

      <!-- Action Button -->
      <button class="mt-10 w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-bold text-lg shadow-xl shadow-indigo-200 transform transition-all active:scale-[0.98] focus:ring-4 focus:ring-indigo-200" id="btn-pay-start">
        Pay ₹49 & Start Verification
      </button>

      <p class="mt-4 text-center text-xs text-gray-400">
        <i class="fas fa-shield-alt me-1"></i> Your data is encrypted and secure
      </p>
    </div>
  </div>
</div>
  <!-- Rule Administration Modal -->
  <div id="rule-admin-container" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm hidden">
    <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full overflow-hidden border border-gray-100 transform transition-all duration-300 scale-95 opacity-0" id="rule-admin-modal">
      <div class="p-6 border-bottom bg-light">
        <div class="d-flex justify-content-between align-items-center">
          <h3 class="h5 mb-0 fw-bold" id="modal-title">Add New Rule</h3>
          <button type="button" class="btn-close" onclick="closeRuleAdminModal()"></button>
        </div>
      </div>
      <form id="rule-form" class="p-6">
        <input type="hidden" name="id" id="rule-id">
        <div class="row g-3">
          <div class="col-md-12">
            <label class="form-label small fw-bold text-muted text-uppercase">Service Type</label>
            <select name="service_type" id="rule-service-type" class="form-select border-light bg-light" required>
              <option value="walking">Walking</option>
              <option value="grooming">Grooming</option>
              <option value="training">Training</option>
              <option value="boarding">Boarding / Sitting</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label small fw-bold text-muted text-uppercase">City</label>
            <input type="text" name="city" id="rule-city" class="form-control border-light bg-light" placeholder="e.g. Hyderabad">
          </div>
          <div class="col-md-6">
            <label class="form-label small fw-bold text-muted text-uppercase">State</label>
            <input type="text" name="state" id="rule-state" class="form-control border-light bg-light" placeholder="e.g. Telangana">
          </div>
          <div class="col-md-12">
            <label class="form-label small fw-bold text-muted text-uppercase">Registration Fee (₹)</label>
            <input type="number" name="registration_fee" id="rule-fee" class="form-control border-light bg-light" value="49" required>
          </div>
          <div class="col-md-12">
            <div class="form-check form-switch mb-2">
              <input class="form-check-input" type="checkbox" name="call_verification_required" id="rule-call-req" checked>
              <label class="form-check-label fw-medium">Call Verification Required</label>
            </div>
            <div class="form-check form-switch mb-2">
              <input class="form-check-input" type="checkbox" name="aadhaar_verification_required" id="rule-aadhaar-req" checked>
              <label class="form-check-label fw-medium">Aadhaar Verification Required</label>
            </div>
            <div class="form-check form-switch mb-2">
              <input class="form-check-input" type="checkbox" name="deferred_verification" id="rule-deferred">
              <label class="form-check-label fw-medium">Deferred Verification (Verify later)</label>
            </div>
          </div>
          <div class="col-md-12">
            <label class="form-label small fw-bold text-muted text-uppercase">Status</label>
            <select name="status" id="rule-status" class="form-select border-light bg-light">
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
            </select>
          </div>
        </div>
        <div class="mt-4">
          <button type="submit" class="btn btn-primary w-100 py-3 rounded-2xl fw-bold shadow-sm">Save Rule</button>
        </div>
      </form>
    </div>
  </div>

  <?php echo $this->include('admin/footer') ?>
</main>
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
  }

  .table tbody tr {
    transition: all 0.2s ease;
  }

  .table tbody tr:hover {
    background-color: rgba(99, 102, 241, 0.03) !important;
  }

  .badge {
    padding: 0.5rem 0.75rem !important;
    font-weight: 600 !important;
  }

  .form-control:focus {
    border-color: #6366f1 !important;
    box-shadow: 0 0 0 0.25rem rgba(99, 102, 241, 0.25) !important;
  }

  .bg-black\/60 {
    background-color: rgba(0, 0, 0, 0.6);
  }

  .backdrop-blur-sm {
    backdrop-filter: blur(4px);
  }

  .backdrop-blur-md {
    backdrop-filter: blur(12px);
  }

  .rounded-3xl {
    border-radius: 1.5rem;
  }

  .rounded-2xl {
    border-radius: 1rem;
  }

  .shadow-2xl {
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
  }

  .shadow-indigo-200 {
    box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.2);
  }

  .bg-white\/90 {
    background-color: rgba(255, 255, 255, 0.9);
  }

  .bg-indigo-50 {
    background-color: #f5f3ff;
  }

  .bg-indigo-600 {
    background-color: #4f46e5;
  }

  .text-indigo-600 {
    color: #4f46e5;
  }

  .text-indigo-100 {
    color: #e0e7ff;
  }

  .border-indigo-100\/50 {
    border-color: rgba(224, 231, 255, 0.5);
  }
</style>

<script>
  // Verification Rule Admin Logic
  function openAddRuleModal() {
    document.getElementById('rule-form').reset();
    document.getElementById('rule-id').value = '';
    document.getElementById('modal-title').innerText = 'Add New Rule';
    showAdminModal();
  }

  function editRule(id) {
    $.get(`/admin/get-rule-data/${id}`, function(res) {
      if (res.status) {
        const rule = res.rule;
        document.getElementById('rule-id').value = rule.id;
        document.getElementById('rule-service-type').value = rule.service_type;
        document.getElementById('rule-city').value = rule.city || '';
        document.getElementById('rule-state').value = rule.state || '';
        document.getElementById('rule-fee').value = rule.registration_fee;
        document.getElementById('rule-call-req').checked = rule.call_verification_required == 1;
        document.getElementById('rule-aadhaar-req').checked = rule.aadhaar_verification_required == 1;
        document.getElementById('rule-deferred').checked = rule.deferred_verification == 1;
        document.getElementById('rule-status').value = rule.status;

        document.getElementById('modal-title').innerText = 'Edit Rule';
        showAdminModal();
      } else {
        alert('Failed to fetch rule data');
      }
    });
  }

  function deleteRule(id) {
    if (confirm('Are you sure you want to delete this rule?')) {
      $.get(`/admin/delete-rule/${id}`, function(res) {
        if (res.status) {
          location.reload();
        } else {
          alert(res.message || 'Failed to delete rule');
        }
      });
    }
  }

  function showAdminModal() {
    const container = document.getElementById('rule-admin-container');
    const modal = document.getElementById('rule-admin-modal');
    container.classList.remove('hidden');
    setTimeout(() => {
      modal.classList.remove('scale-95', 'opacity-0');
      modal.classList.add('scale-100', 'opacity-100');
    }, 10);
  }

  function closeRuleAdminModal() {
    const container = document.getElementById('rule-admin-container');
    const modal = document.getElementById('rule-admin-modal');
    modal.classList.remove('scale-100', 'opacity-100');
    modal.classList.add('scale-95', 'opacity-0');
    setTimeout(() => {
      container.classList.add('hidden');
    }, 300);
  }

  // Handle form submission
  $('#rule-form').on('submit', function(e) {
    e.preventDefault();
    const formData = $(this).serialize();

    $.post('/admin/save-rule', formData, function(res) {
      if (res.status) {
        location.reload();
      } else {
        alert(res.message || 'Failed to save rule');
      }
    });
  });

  // Search logic
  $('#ruleSearch').on('keyup', function() {
    const value = $(this).val().toLowerCase();
    $('table tbody tr').filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });

  // Original Verification Flow functions
  function openVerificationModal(data) {
    const container = document.getElementById('verification-container');
    const modal = document.getElementById('verification-modal');

    container.classList.remove('hidden');
    setTimeout(() => {
      modal.classList.remove('scale-95', 'opacity-0');
      modal.classList.add('scale-100', 'opacity-100');
    }, 10);

    if (data.registration_fee) {
      document.getElementById('btn-pay-start').innerText = `Pay ₹${data.registration_fee} & Start Verification`;
    }
  }

  function closeVerificationModal() {
    const container = document.getElementById('verification-container');
    const modal = document.getElementById('verification-modal');

    modal.classList.remove('scale-100', 'opacity-100');
    modal.classList.add('scale-95', 'opacity-0');

    setTimeout(() => {
      container.classList.add('hidden');
    }, 300);
  }

  function onServiceCardClick(serviceId, city, state) {
    $.post('/admin/checkVerificationRequirement', {
      service_id: serviceId,
      city: city,
      state: state
    }, function(res) {
      if (res.status && res.verification_required) {
        openVerificationModal(res);
      } else {
        window.location.href = `/admin/services/${serviceId}/manage`;
      }
    });
  }

</script>

<?php echo $this->endSection() ?>