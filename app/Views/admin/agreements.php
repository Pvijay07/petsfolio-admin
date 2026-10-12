<?= $this->extend('admin/includes/header'); ?>
<?= $this->section('content'); ?>

<style>
  .agreements-header {
    background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
    border-radius: 1rem;
    padding: 2rem;
    color: white;
    margin-bottom: 2rem;
    position: relative;
    overflow: hidden;
    box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.3);
  }
  .stat-card {
    border: none;
    border-radius: 1rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    background: #ffffff;
  }
  .stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
  }
  .stat-icon-wrapper {
    width: 52px;
    height: 52px;
    border-radius: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
  }
  .nav-pills-agreements .nav-link {
    border-radius: 0.75rem;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    color: #4b5563;
    border: 1px solid #e5e7eb;
    background: #ffffff;
    transition: all 0.2s ease;
  }
  .nav-pills-agreements .nav-link.active {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    color: #ffffff;
    border-color: #2563eb;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
  }
  .table-agreements thead th {
    background-color: #f8fafc;
    color: #475569;
    font-size: 0.8rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    border-bottom: 2px solid #e2e8f0;
    padding: 0.9rem 1rem;
    white-space: nowrap;
  }
  .table-agreements tbody td {
    padding: 1rem;
    vertical-align: middle;
    font-size: 0.875rem;
    border-bottom: 1px solid #f1f5f9;
  }
  .badge-accepted {
    background-color: #dcfce7;
    color: #15803d;
    border: 1px solid #86efac;
    font-weight: 600;
    font-size: 0.75rem;
    padding: 0.35rem 0.75rem;
    border-radius: 9999px;
  }
  .badge-version {
    background-color: #ede9fe;
    color: #6d28d9;
    border: 1px solid #ddd6fe;
    font-weight: 600;
    font-size: 0.75rem;
    padding: 0.3rem 0.65rem;
    border-radius: 9999px;
  }
  .btn-pdf {
    background-color: #fee2e2;
    color: #b91c1c;
    border: 1px solid #fca5a5;
    font-weight: 600;
    font-size: 0.8rem;
    padding: 0.35rem 0.75rem;
    border-radius: 0.5rem;
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    transition: all 0.2s ease;
    text-decoration: none;
  }
  .btn-pdf:hover {
    background-color: #ef4444;
    color: #ffffff;
    border-color: #ef4444;
  }
  .btn-audit {
    background-color: #f1f5f9;
    color: #475569;
    border: 1px solid #cbd5e1;
    font-weight: 600;
    font-size: 0.8rem;
    padding: 0.35rem 0.75rem;
    border-radius: 0.5rem;
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    transition: all 0.2s ease;
  }
  .btn-audit:hover {
    background-color: #334155;
    color: #ffffff;
    border-color: #334155;
  }
</style>

<div class="container-fluid px-4 py-3">
  <!-- Banner Header -->
  <div class="agreements-header">
    <div class="row align-items-center">
      <div class="col-lg-8">
        <div class="d-flex align-items-center gap-3 mb-2">
          <span class="badge bg-white text-primary px-3 py-2 rounded-pill fw-bold fs-7">
            <i class="bi bi-shield-check me-1"></i> Compliance & Legal
          </span>
          <span class="badge bg-white bg-opacity-25 text-white px-3 py-2 rounded-pill fs-7">
            <i class="bi bi-clock-history me-1"></i> Real-time Audit Trail
          </span>
        </div>
        <h1 class="h2 fw-bold text-white mb-2">Agreements Received</h1>
        <p class="text-white text-opacity-75 mb-0" style="max-width: 700px;">
          View, audit, and download legal digital agreements accepted by Service Providers (SP) and Pet Parent Clients upon registration or onboarding.
        </p>
      </div>
      <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
        <button class="btn btn-light rounded-pill px-3 py-2 fw-bold shadow-sm" onclick="location.reload();">
          <i class="bi bi-arrow-clockwise me-1"></i> Refresh Data
        </button>
      </div>
    </div>
    <i class="bi bi-file-earmark-lock2-fill position-absolute end-0 bottom-0 opacity-10 text-white"
       style="font-size: 14rem; transform: rotate(15deg); margin-right: -2rem; margin-bottom: -3.5rem; pointer-events: none;"></i>
  </div>

  <!-- KPI Summary Cards -->
  <div class="row g-3 mb-4">
    <!-- Total Agreements -->
    <div class="col-xl-3 col-md-6">
      <div class="card stat-card h-100">
        <div class="card-body p-3 p-xl-4 d-flex align-items-center gap-3">
          <div class="stat-icon-wrapper bg-primary bg-opacity-10 text-primary">
            <i class="bi bi-file-earmark-check-fill"></i>
          </div>
          <div>
            <div class="text-muted small fw-semibold text-uppercase">Total Received</div>
            <div class="h3 fw-bold mb-0 text-dark"><?= number_format($totalAgreements) ?></div>
            <small class="text-muted">Client & SP signed</small>
          </div>
        </div>
      </div>
    </div>

    <!-- SP Agreements -->
    <div class="col-xl-3 col-md-6">
      <div class="card stat-card h-100">
        <div class="card-body p-3 p-xl-4 d-flex align-items-center gap-3">
          <div class="stat-icon-wrapper bg-success bg-opacity-10 text-success">
            <i class="bi bi-person-badge-fill"></i>
          </div>
          <div>
            <div class="text-muted small fw-semibold text-uppercase">SP Agreements</div>
            <div class="h3 fw-bold mb-0 text-success"><?= number_format($totalSpAgreements) ?></div>
            <small class="text-muted">Partners onboarded</small>
          </div>
        </div>
      </div>
    </div>

    <!-- Client Agreements -->
    <div class="col-xl-3 col-md-6">
      <div class="card stat-card h-100">
        <div class="card-body p-3 p-xl-4 d-flex align-items-center gap-3">
          <div class="stat-icon-wrapper bg-info bg-opacity-10 text-info">
            <i class="bi bi-people-fill"></i>
          </div>
          <div>
            <div class="text-muted small fw-semibold text-uppercase">Client Agreements</div>
            <div class="h3 fw-bold mb-0 text-info"><?= number_format($totalClientAgreements) ?></div>
            <small class="text-muted">Pet parents signed</small>
          </div>
        </div>
      </div>
    </div>

    <!-- Recent Activity -->
    <div class="col-xl-3 col-md-6">
      <div class="card stat-card h-100">
        <div class="card-body p-3 p-xl-4 d-flex align-items-center gap-3">
          <div class="stat-icon-wrapper bg-warning bg-opacity-10 text-warning">
            <i class="bi bi-lightning-charge-fill"></i>
          </div>
          <div>
            <div class="text-muted small fw-semibold text-uppercase">Last 24 Hours</div>
            <div class="h3 fw-bold mb-0 text-dark"><?= number_format($recentCount) ?></div>
            <small class="text-muted">
              <?= $latestItem ? 'Latest: ' . esc(mb_strimwidth($latestItem['name'], 0, 15, '...')) : 'No recent signs' ?>
            </small>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Search and Tab Controls -->
  <div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-3 p-md-4">
      <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
        <!-- Navigation Tabs -->
        <ul class="nav nav-pills nav-pills-agreements gap-2" id="agreementsTabs" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active d-flex align-items-center gap-2" id="tab-sp" data-bs-toggle="pill" data-bs-target="#content-sp" type="button" role="tab">
              <i class="bi bi-person-badge"></i>
              <span>Service Provider (SP) Agreements</span>
              <span class="badge bg-white text-primary rounded-pill ms-1"><?= count($spAgreements) ?></span>
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link d-flex align-items-center gap-2" id="tab-client" data-bs-toggle="pill" data-bs-target="#content-client" type="button" role="tab">
              <i class="bi bi-person-heart"></i>
              <span>Client Agreements</span>
              <span class="badge bg-white text-primary rounded-pill ms-1"><?= count($clientAgreements) ?></span>
            </button>
          </li>
        </ul>

        <!-- Search Bar -->
        <div class="position-relative" style="min-width: 280px; max-width: 400px;">
          <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
          <input type="text" id="agreementSearchInput" class="form-control ps-5 rounded-pill border-light bg-light"
                 placeholder="Search by name, phone, email, ID..." onkeyup="filterAgreementsTable();">
        </div>
      </div>

      <!-- Tabs Content -->
      <div class="tab-content" id="agreementsTabsContent">

        <!-- TAB 1: SERVICE PROVIDER AGREEMENTS -->
        <div class="tab-pane fade show active" id="content-sp" role="tabpanel">
          <div class="table-responsive rounded-3 border">
            <table class="table table-hover table-agreements mb-0 align-middle" id="spAgreementsTable">
              <thead>
                <tr>
                  <th scope="col" style="width: 70px;">SP ID</th>
                  <th scope="col">Provider Name</th>
                  <th scope="col">Contact Info</th>
                  <th scope="col">Service Type</th>
                  <th scope="col">Version</th>
                  <th scope="col">Status</th>
                  <th scope="col">Accepted At</th>
                  <th scope="col" class="text-end">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($spAgreements)): ?>
                  <tr>
                    <td colspan="8" class="text-center py-5 text-muted">
                      <i class="bi bi-file-earmark-x fs-1 d-block mb-2 text-secondary"></i>
                      No Service Provider agreements received yet.
                    </td>
                  </tr>
                <?php else: ?>
                  <?php foreach ($spAgreements as $sp): ?>
                    <tr class="sp-row"
                        data-search="<?= strtolower(esc(($sp['provider_id'] ?? '') . ' ' . ($sp['provider_name'] ?? '') . ' ' . ($sp['provider_phone'] ?? '') . ' ' . ($sp['provider_email'] ?? '') . ' ' . ($sp['service_type'] ?? ''))) ?>">
                      <td>
                        <span class="fw-bold text-dark">#<?= esc($sp['provider_id'] ?? $sp['id']) ?></span>
                      </td>
                      <td>
                        <div class="d-flex align-items-center gap-2">
                          <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center fw-bold"
                               style="width: 38px; height: 38px; flex-shrink: 0; font-size: 0.9rem;">
                            <?= strtoupper(substr($sp['provider_name'] ?? 'P', 0, 1)) ?>
                          </div>
                          <div>
                            <div class="fw-bold text-dark"><?= esc($sp['provider_name'] ?? 'N/A') ?></div>
                            <?php if (!empty($sp['aadhaar_number'])): ?>
                              <small class="text-muted"><i class="bi bi-card-heading me-1"></i>Aadhaar: <?= esc($sp['aadhaar_number']) ?></small>
                            <?php endif; ?>
                          </div>
                        </div>
                      </td>
                      <td>
                        <div><i class="bi bi-telephone text-muted me-1"></i><strong><?= esc($sp['provider_phone'] ?? '—') ?></strong></div>
                        <small class="text-muted"><i class="bi bi-envelope text-muted me-1"></i><?= esc($sp['provider_email'] ?? '—') ?></small>
                      </td>
                      <td>
                        <span class="badge bg-light text-dark border px-2 py-1">
                          <i class="bi bi-gear-fill me-1 text-primary"></i><?= esc($sp['service_type'] ?? 'Walking') ?>
                        </span>
                      </td>
                      <td>
                        <span class="badge-version"><?= esc($sp['agreement_version'] ?? 'v1.0') ?></span>
                      </td>
                      <td>
                        <span class="badge-accepted">
                          <i class="bi bi-check2-circle me-1"></i><?= ucfirst(esc($sp['status'] ?? 'accepted')) ?>
                        </span>
                      </td>
                      <td>
                        <div class="text-dark fw-semibold">
                          <?= !empty($sp['accepted_at']) ? date('d M Y', strtotime($sp['accepted_at'])) : '—' ?>
                        </div>
                        <small class="text-muted">
                          <?= !empty($sp['accepted_at']) ? date('h:i:s A', strtotime($sp['accepted_at'])) : '' ?>
                        </small>
                      </td>
                      <td class="text-end">
                        <div class="d-inline-flex gap-2">
                          <button type="button" class="btn-audit" onclick="showAgreementDetails('sp', <?= (int)$sp['id'] ?>)">
                            <i class="bi bi-info-circle"></i> Audit Info
                          </button>
                          <?php if (!empty($sp['pdf_url'])): ?>
                            <a href="<?= esc($sp['pdf_url']) ?>" target="_blank" class="btn-pdf" title="Open Signed Agreement PDF">
                              <i class="bi bi-file-earmark-pdf-fill"></i> View PDF
                            </a>
                          <?php else: ?>
                            <span class="badge bg-secondary">No PDF</span>
                          <?php endif; ?>
                        </div>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>

        <!-- TAB 2: CLIENT AGREEMENTS -->
        <div class="tab-pane fade" id="content-client" role="tabpanel">
          <div class="table-responsive rounded-3 border">
            <table class="table table-hover table-agreements mb-0 align-middle" id="clientAgreementsTable">
              <thead>
                <tr>
                  <th scope="col" style="width: 70px;">User ID</th>
                  <th scope="col">Client Name</th>
                  <th scope="col">Contact Info</th>
                  <th scope="col">Version</th>
                  <th scope="col">Status</th>
                  <th scope="col">Accepted At</th>
                  <th scope="col" class="text-end">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($clientAgreements)): ?>
                  <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                      <i class="bi bi-file-earmark-x fs-1 d-block mb-2 text-secondary"></i>
                      No Client agreements received yet.
                    </td>
                  </tr>
                <?php else: ?>
                  <?php foreach ($clientAgreements as $cl): ?>
                    <tr class="client-row"
                        data-search="<?= strtolower(esc(($cl['user_id'] ?? '') . ' ' . ($cl['client_name'] ?? '') . ' ' . ($cl['client_phone'] ?? '') . ' ' . ($cl['client_email'] ?? ''))) ?>">
                      <td>
                        <span class="fw-bold text-dark">#<?= esc($cl['user_id'] ?? $cl['id']) ?></span>
                      </td>
                      <td>
                        <div class="d-flex align-items-center gap-2">
                          <div class="rounded-circle bg-info bg-opacity-10 text-info d-flex align-items-center justify-content-center fw-bold"
                               style="width: 38px; height: 38px; flex-shrink: 0; font-size: 0.9rem;">
                            <?= strtoupper(substr($cl['client_name'] ?? 'C', 0, 1)) ?>
                          </div>
                          <div>
                            <div class="fw-bold text-dark"><?= esc($cl['client_name'] ?? 'N/A') ?></div>
                            <small class="text-muted"><i class="bi bi-person-check me-1"></i>Pet Parent</small>
                          </div>
                        </div>
                      </td>
                      <td>
                        <div><i class="bi bi-telephone text-muted me-1"></i><strong><?= esc($cl['client_phone'] ?? '—') ?></strong></div>
                        <small class="text-muted"><i class="bi bi-envelope text-muted me-1"></i><?= esc($cl['client_email'] ?? '—') ?></small>
                      </td>
                      <td>
                        <span class="badge-version"><?= esc($cl['agreement_version'] ?? 'v1.0') ?></span>
                      </td>
                      <td>
                        <span class="badge-accepted">
                          <i class="bi bi-check2-circle me-1"></i><?= ucfirst(esc($cl['status'] ?? 'accepted')) ?>
                        </span>
                      </td>
                      <td>
                        <div class="text-dark fw-semibold">
                          <?= !empty($cl['accepted_at']) ? date('d M Y', strtotime($cl['accepted_at'])) : '—' ?>
                        </div>
                        <small class="text-muted">
                          <?= !empty($cl['accepted_at']) ? date('h:i:s A', strtotime($cl['accepted_at'])) : '' ?>
                        </small>
                      </td>
                      <td class="text-end">
                        <div class="d-inline-flex gap-2">
                          <button type="button" class="btn-audit" onclick="showAgreementDetails('client', <?= (int)$cl['id'] ?>)">
                            <i class="bi bi-info-circle"></i> Audit Info
                          </button>
                          <?php if (!empty($cl['pdf_url'])): ?>
                            <a href="<?= esc($cl['pdf_url']) ?>" target="_blank" class="btn-pdf" title="Open Signed Agreement PDF">
                              <i class="bi bi-file-earmark-pdf-fill"></i> View PDF
                            </a>
                          <?php else: ?>
                            <span class="badge bg-secondary">No PDF</span>
                          <?php endif; ?>
                        </div>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<!-- Modal: Agreement Audit Trail Details -->
<div class="modal fade" id="agreementDetailsModal" tabindex="-1" aria-labelledby="agreementDetailsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content rounded-4 border-0 shadow">
      <div class="modal-header border-bottom-0 pb-0">
        <h5 class="modal-title fw-bold" id="agreementDetailsModalLabel">
          <i class="bi bi-shield-check text-primary me-2"></i> Agreement Digital Audit Trail
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <div id="modalLoadingSpinner" class="text-center py-4">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
          <div class="text-muted mt-2 small">Loading audit evidence...</div>
        </div>
        <div id="modalContentArea" style="display: none;">
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <div class="p-3 rounded-3 bg-light">
                <small class="text-muted d-block mb-1">Party / Signatory Name</small>
                <h6 class="fw-bold mb-0 text-dark" id="modalPartyName">—</h6>
              </div>
            </div>
            <div class="col-md-6">
              <div class="p-3 rounded-3 bg-light">
                <small class="text-muted d-block mb-1">Accepted Timestamp</small>
                <h6 class="fw-bold mb-0 text-dark" id="modalAcceptedAt">—</h6>
              </div>
            </div>
          </div>

          <h6 class="fw-bold text-dark mt-4 mb-2">Technical & Device Evidence</h6>
          <div class="table-responsive rounded-3 border">
            <table class="table table-sm table-striped mb-0">
              <tbody>
                <tr>
                  <th style="width: 180px;">IP Address</th>
                  <td id="modalIpAddress">—</td>
                </tr>
                <tr>
                  <th>Device UUID</th>
                  <td id="modalDeviceUuid">—</td>
                </tr>
                <tr>
                  <th>Device Info</th>
                  <td id="modalDeviceInfo">—</td>
                </tr>
                <tr>
                  <th>User Agent</th>
                  <td id="modalUserAgent" class="small text-muted text-break">—</td>
                </tr>
                <tr>
                  <th>Dispatch Evidence</th>
                  <td id="modalDispatchEvidence" class="small text-break">—</td>
                </tr>
              </tbody>
            </table>
          </div>

          <div id="acceptanceRecordContainer" class="mt-4" style="display: none;">
            <h6 class="fw-bold text-dark mb-2">Signatory Acceptance Record</h6>
            <pre class="bg-dark text-light p-3 rounded-3 small mb-0" id="modalAcceptanceRecord" style="max-height: 200px; overflow-y: auto;"></pre>
          </div>
        </div>
      </div>
      <div class="modal-footer border-top-0">
        <a href="#" id="modalPdfLink" target="_blank" class="btn btn-primary rounded-pill px-4" style="display: none;">
          <i class="bi bi-file-earmark-pdf me-1"></i> Open PDF Document
        </a>
        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
  function filterAgreementsTable() {
    const query = (document.getElementById('agreementSearchInput').value || '').trim().toLowerCase();
    
    // Filter SP Rows
    const spRows = document.querySelectorAll('#spAgreementsTable tbody tr.sp-row');
    spRows.forEach(row => {
      const searchData = row.getAttribute('data-search') || '';
      row.style.display = searchData.includes(query) ? '' : 'none';
    });

    // Filter Client Rows
    const clientRows = document.querySelectorAll('#clientAgreementsTable tbody tr.client-row');
    clientRows.forEach(row => {
      const searchData = row.getAttribute('data-search') || '';
      row.style.display = searchData.includes(query) ? '' : 'none';
    });
  }

  function showAgreementDetails(type, id) {
    const modal = new bootstrap.Modal(document.getElementById('agreementDetailsModal'));
    document.getElementById('modalLoadingSpinner').style.display = 'block';
    document.getElementById('modalContentArea').style.display = 'none';
    document.getElementById('modalPdfLink').style.display = 'none';
    modal.show();

    fetch('<?= base_url('agreements/details') ?>/' + encodeURIComponent(type) + '/' + encodeURIComponent(id))
      .then(res => res.json())
      .then(data => {
        document.getElementById('modalLoadingSpinner').style.display = 'none';
        if (!data.status || !data.data) {
          alert(data.message || 'Error fetching details');
          return;
        }

        const d = data.data;
        const name = (type === 'sp') ? (d.provider_name || 'N/A') : (d.client_name || 'N/A');
        document.getElementById('modalPartyName').textContent = name + ' (ID: #' + (d.provider_id || d.user_id || d.id) + ')';
        document.getElementById('modalAcceptedAt').textContent = d.accepted_at ? new Date(d.accepted_at).toLocaleString() : 'N/A';
        document.getElementById('modalIpAddress').textContent = d.ip_address || '—';
        document.getElementById('modalDeviceUuid').textContent = d.device_uuid || '—';
        document.getElementById('modalDeviceInfo').textContent = d.device_info || '—';
        document.getElementById('modalUserAgent').textContent = d.user_agent || '—';
        document.getElementById('modalDispatchEvidence').textContent = d.dispatch_evidence || '—';

        if (d.acceptance_record) {
          document.getElementById('acceptanceRecordContainer').style.display = 'block';
          try {
            const parsed = JSON.parse(d.acceptance_record);
            document.getElementById('modalAcceptanceRecord').textContent = JSON.stringify(parsed, null, 2);
          } catch (e) {
            document.getElementById('modalAcceptanceRecord').textContent = d.acceptance_record;
          }
        } else {
          document.getElementById('acceptanceRecordContainer').style.display = 'none';
        }

        if (d.pdf_url) {
          const pdfLink = document.getElementById('modalPdfLink');
          pdfLink.href = d.pdf_url;
          pdfLink.style.display = 'inline-flex';
        }

        document.getElementById('modalContentArea').style.display = 'block';
      })
      .catch(err => {
        document.getElementById('modalLoadingSpinner').style.display = 'none';
        console.error(err);
        alert('Network or server error while loading agreement details.');
      });
  }
</script>

<?= $this->endSection(); ?>
