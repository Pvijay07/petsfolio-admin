<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <style>
    body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; background: #f4f6f9; }
    .container { max-width: 640px; margin: 20px auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
    .header { background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #fff; padding: 30px; text-align: center; }
    .header h1 { margin: 0 0 5px; font-size: 22px; }
    .header p { margin: 0; opacity: 0.9; font-size: 14px; }
    .body { padding: 30px; }
    .section { margin-bottom: 24px; }
    .section-title { font-size: 14px; font-weight: 700; color: #374151; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 12px; padding-bottom: 6px; border-bottom: 2px solid #e5e7eb; }
    .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    .info-item { background: #f9fafb; padding: 12px; border-radius: 8px; }
    .info-item label { display: block; font-size: 11px; color: #6b7280; text-transform: uppercase; margin-bottom: 4px; }
    .info-item span { font-size: 14px; font-weight: 600; color: #111827; }
    .badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; }
    .badge-success { background: #d1fae5; color: #065f46; }
    .badge-warning { background: #fef3c7; color: #92400e; }
    .badge-danger { background: #fee2e2; color: #991b1b; }
    .badge-info { background: #dbeafe; color: #1e40af; }
    .change-list { list-style: none; padding: 0; margin: 0; }
    .change-list li { padding: 8px 0; border-bottom: 1px solid #f3f4f6; font-size: 14px; }
    .change-list li:last-child { border-bottom: none; }
    .change-list li i { margin-right: 6px; }
    .approval-table { width: 100%; border-collapse: collapse; }
    .approval-table th { background: #f9fafb; padding: 8px 12px; text-align: left; font-size: 12px; color: #6b7280; border-bottom: 1px solid #e5e7eb; }
    .approval-table td { padding: 8px 12px; font-size: 13px; border-bottom: 1px solid #f3f4f6; }
    .footer { background: #f9fafb; padding: 20px 30px; text-align: center; border-top: 1px solid #e5e7eb; }
    .footer p { margin: 0; font-size: 12px; color: #9ca3af; }
  </style>
</head>
<body>
  <div class="container">
    <!-- Header -->
    <div class="header">
      <h1>Petsfolio Release Approval</h1>
      <p>Version <?= esc($version['version_number']) ?></p>
    </div>

    <div class="body">
      <!-- Release Info -->
      <div class="section">
        <div class="section-title">Release Information</div>
        <div class="info-grid">
          <div class="info-item">
            <label>Release Version</label>
            <span><?= esc($version['version_number']) ?></span>
          </div>
          <div class="info-item">
            <label>Platform</label>
            <span><?= $platformLabel ?></span>
          </div>
          <div class="info-item">
            <label>Release Type</label>
            <span><?= $typeLabel ?></span>
          </div>
          <div class="info-item">
            <label>Planned Go-Live</label>
            <span><?= $plannedDate ?></span>
          </div>
          <div class="info-item">
            <label>Status</label>
            <span class="badge badge-info"><?= $statusLabel ?></span>
          </div>
          <div class="info-item">
            <label>Build</label>
            <span><?= esc($version['build_number'] ?? 'N/A') ?></span>
          </div>
        </div>
      </div>

      <!-- Changes -->
      <div class="section">
        <div class="section-title">Changes</div>
        <?php foreach ($updates as $catKey => $catData): ?>
          <?php if (!empty($catData['items'])): ?>
            <p style="font-size:13px;font-weight:600;color:#374151;margin:12px 0 6px"><?= $catData['label'] ?></p>
            <ul class="change-list">
              <?php foreach ($catData['items'] as $item): ?>
                <li>
                  <?php if ($catKey === 'new_features'): ?>✨<?php elseif ($catKey === 'bug_fixes'): ?>🐛<?php elseif ($catKey === 'enhancements'): ?>⬆️<?php else: ?>•<?php endif; ?>
                  <?= esc($item['title']) ?>
                  <?php if ($item['ticket_id']): ?> <span style="color:#6b7280">(<?= esc($item['ticket_id']) ?>)</span><?php endif; ?>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
        <?php endforeach; ?>
      </div>

      <!-- QA Status -->
      <div class="section">
        <div class="section-title">QA Status</div>
        <div class="info-grid">
          <div class="info-item">
            <label>QA Progress</label>
            <span><?= $qaPercent ?>%</span>
          </div>
          <div class="info-item">
            <label>Regression Status</label>
            <span class="badge <?= $qaPercent >= 100 ? 'badge-success' : 'badge-warning' ?>"><?= $qaPercent >= 100 ? 'Complete' : 'In Progress' ?></span>
          </div>
        </div>
      </div>

      <!-- Backup Status -->
      <div class="section">
        <div class="section-title">Backup Status</div>
        <?php if ($backup): ?>
          <div class="info-grid">
            <div class="info-item">
              <label>Previous Production</label>
              <span><?= esc($backup['current_prod_version'] ?? 'N/A') ?></span>
            </div>
            <div class="info-item">
              <label>Rollback Version</label>
              <span><?= esc($backup['rollback_version'] ?? 'N/A') ?></span>
            </div>
            <div class="info-item">
              <label>Database Backup</label>
              <span><?= esc($backup['database_backup'] ?? 'N/A') ?></span>
            </div>
            <div class="info-item">
              <label>Verified</label>
              <span class="badge <?= $backup['verified_by'] ? 'badge-success' : 'badge-warning' ?>"><?= $backup['verified_by'] ? 'Yes' : 'Pending' ?></span>
            </div>
          </div>
        <?php else: ?>
          <p style="font-size:13px;color:#ef4444">⚠️ No backup record found.</p>
        <?php endif; ?>
      </div>

      <!-- Approvals -->
      <div class="section">
        <div class="section-title">Approval Status</div>
        <table class="approval-table">
          <thead>
            <tr><th>Role</th><th>Status</th><th>Approved By</th><th>Date</th></tr>
          </thead>
          <tbody>
            <?php foreach ($approvals as $a): ?>
              <tr>
                <td><?= ucfirst($a['approval_type']) ?></td>
                <td>
                  <span class="badge badge-<?= $a['status'] === 'approved' ? 'success' : ($a['status'] === 'rejected' ? 'danger' : 'warning') ?>">
                    <?= ucfirst($a['status']) ?>
                  </span>
                </td>
                <td><?= esc($a['approver_name'] ?? '—') ?></td>
                <td><?= $a['approved_at'] ? date('d-M H:i', strtotime($a['approved_at'])) : '—' ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Footer -->
    <div class="footer">
      <p>This email was auto-generated by the Petsfolio Release Management system.<br>
      <?= date('d-M-Y H:i:s') ?></p>
    </div>
  </div>
</body>
</html>
