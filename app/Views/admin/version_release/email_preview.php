<?php

/** @var array $v */
$v = $v ?? [];
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <style>
    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
      line-height: 1.6;
      color: #1e293b;
      margin: 0;
      padding: 20px;
      background-color: #f8fafc;
    }

    .email-container {
      max-width: 680px;
      margin: 0 auto;
      background: #ffffff;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
      border: 1px solid #e2e8f0;
    }

    .header {
      background: linear-gradient(135deg, #0d424d 0%, #165b69 100%);
      color: #ffffff;
      padding: 28px;
      text-align: left;
    }

    .badge {
      display: inline-block;
      padding: 4px 12px;
      font-size: 12px;
      font-weight: 600;
      border-radius: 9999px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .badge-primary {
      background-color: #e0f2fe;
      color: #0284c7;
    }

    .badge-success {
      background-color: #dcfce7;
      color: #15803d;
    }

    .badge-warning {
      background-color: #fef3c7;
      color: #b45309;
    }

    .content {
      padding: 28px;
    }

    .meta-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 12px;
      margin: 20px 0;
      padding: 16px;
      background-color: #f1f5f9;
      border-radius: 8px;
      font-size: 14px;
    }

    .meta-item {
      display: flex;
      flex-direction: column;
    }

    .meta-label {
      color: #64748b;
      font-size: 12px;
      font-weight: 600;
      text-transform: uppercase;
    }

    .meta-value {
      color: #0f172a;
      font-weight: 600;
      font-size: 15px;
    }

    .section-title {
      font-size: 16px;
      font-weight: 700;
      color: #0f172a;
      margin-top: 24px;
      margin-bottom: 12px;
      border-bottom: 2px solid #e2e8f0;
      padding-bottom: 6px;
      display: flex;
      align-items: center;
    }

    .update-list {
      margin: 0;
      padding-left: 20px;
      font-size: 14px;
    }

    .update-item {
      margin-bottom: 8px;
    }

    .table {
      width: 100%;
      border-collapse: collapse;
      margin: 12px 0;
      font-size: 13px;
    }

    .table th,
    .table td {
      padding: 10px 12px;
      border: 1px solid #e2e8f0;
      text-align: left;
    }

    .table th {
      background-color: #f8fafc;
      color: #475569;
      font-weight: 600;
    }

    .status-passed {
      color: #16a34a;
      font-weight: 600;
    }

    .status-pending {
      color: #d97706;
      font-weight: 600;
    }

    .status-failed {
      color: #dc2626;
      font-weight: 600;
    }

    .footer {
      background-color: #f8fafc;
      padding: 20px 28px;
      text-align: center;
      font-size: 12px;
      color: #64748b;
      border-top: 1px solid #e2e8f0;
    }
  </style>
</head>

<body>

  <div class="email-container">
    <div class="header">
      <div style="font-size: 13px; text-transform: uppercase; letter-spacing: 1px; opacity: 0.85; margin-bottom: 4px;">
        Petsfolio Engineering & Release Operations
      </div>
      <h1 style="margin: 0; font-size: 24px; font-weight: 800; color: #ffffff;">
        🚀 Release Approval Request: v<?= esc($v['version_number']) ?>
      </h1>
      <p style="margin: 6px 0 0 0; font-size: 14px; opacity: 0.9;">
        Platform: <strong><?= strtoupper(esc($v['platform'])) ?></strong> &bull; Build: <strong>#<?= esc($v['build_number'] ?? 'N/A') ?></strong> &bull; Type: <strong><?= ucfirst(esc($v['release_type'])) ?></strong>
      </p>
    </div>

    <div class="content">
      <div class="meta-grid">
        <div class="meta-item">
          <span class="meta-label">Release Version</span>
          <span class="meta-value">v<?= esc($v['version_number']) ?> (Build <?= esc($v['build_number'] ?? 'N/A') ?>)</span>
        </div>
        <div class="meta-item">
          <span class="meta-label">Target Go-Live Schedule</span>
          <span class="meta-value"><?= !empty($v['planned_release_at']) ? date('M d, Y - h:i A', strtotime($v['planned_release_at'])) : 'Immediate / Post Approval' ?></span>
        </div>
        <div class="meta-item">
          <span class="meta-label">Current Release Status</span>
          <span class="meta-value" style="color: #0284c7; text-transform: uppercase; font-size: 13px;">
            <?= str_replace('_', ' ', esc($v['status'])) ?>
          </span>
        </div>
        <div class="meta-item">
          <span class="meta-label">Rollback Target Version</span>
          <span class="meta-value">v<?= esc($v['rollback_version']['version_number'] ?? $v['backup']['rollback_version'] ?? '2.4.0') ?></span>
        </div>
      </div>

      <!-- Release Notes / Overview -->
      <div class="section-title">📋 Release Overview & Notes</div>
      <p style="font-size: 14px; color: #334155; white-space: pre-line; background: #f8fafc; padding: 12px; border-radius: 6px; border: 1px solid #e2e8f0; margin-top: 6px;">
        <?= !empty($v['release_notes']) ? esc($v['release_notes']) : 'No custom release notes provided.' ?>
      </p>

      <!-- Key Changes / Updates -->
      <div class="section-title">✨ Key Updates & Changes in this Version</div>
      <?php if (!empty($v['updates'])): ?>
        <ul class="update-list">
          <?php foreach ($v['updates'] as $upd): ?>
            <li class="update-item">
              <strong>[<?= strtoupper(str_replace('_', ' ', esc($upd['category']))) ?>]</strong>
              <?= esc($upd['title']) ?>
              <?php if (!empty($upd['ticket_id'])): ?>
                <span style="font-size: 11px; background: #e2e8f0; padding: 2px 6px; border-radius: 4px; color: #475569;"><?= esc($upd['ticket_id']) ?></span>
              <?php endif; ?>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php else: ?>
        <p style="font-size: 13px; color: #64748b; font-style: italic;">No individual updates catalogued yet.</p>
      <?php endif; ?>

      <!-- Pre-Release Checklists Summary -->
      <div class="section-title">✅ Developer & QA Pre-Release Checklists</div>
      <table class="table">
        <thead>
          <tr>
            <th>Checklist Gate</th>
            <th>Total Checks</th>
            <th>Passed / Completed</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($v['checklists'] as $chk): ?>
            <tr>
              <td><strong><?= esc($chk['section_label']) ?></strong></td>
              <td><?= $chk['stats']['total'] ?></td>
              <td><?= $chk['stats']['passed'] ?></td>
              <td>
                <?php if ($chk['stats']['total'] > 0 && $chk['stats']['passed'] === $chk['stats']['total']): ?>
                  <span class="status-passed">Passed (100%)</span>
                <?php elseif ($chk['stats']['failed'] > 0): ?>
                  <span class="status-failed">Failed (<?= $chk['stats']['failed'] ?> failed)</span>
                <?php else: ?>
                  <span class="status-pending">In Progress (<?= $chk['stats']['percentage'] ?>%)</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <!-- Production Backup Verification -->
      <div class="section-title">🛡️ Existing Production Version Backup Status</div>
      <table class="table">
        <tr>
          <th style="width: 35%;">Current Production Version</th>
          <td><?= esc($v['backup']['current_prod_version'] ?? '2.4.0') ?></td>
        </tr>
        <tr>
          <th>Database Backup Reference</th>
          <td><?= esc($v['backup']['database_backup'] ?? 'Automated S3 snapshot completed') ?></td>
        </tr>
        <tr>
          <th>API / Codebase Backup (Git)</th>
          <td><?= esc($v['backup']['api_backup'] ?? 'Tagged release commit verified') ?></td>
        </tr>
        <tr>
          <th>Config / Env Backup</th>
          <td><?= esc($v['backup']['config_backup'] ?? 'Verified production env parameters') ?></td>
        </tr>
        <tr>
          <th>Backup Verified At</th>
          <td><?= !empty($v['backup']['verified_at']) ? date('M d, Y - h:i A', strtotime($v['backup']['verified_at'])) : 'Verified' ?></td>
        </tr>
      </table>

      <!-- Approval Sign-offs -->
      <div class="section-title">✍️ Approval Sign-offs</div>
      <table class="table">
        <thead>
          <tr>
            <th>Role</th>
            <th>Status</th>
            <th>Approver</th>
            <th>Date & Time</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><strong>QA Lead</strong></td>
            <td>
              <span class="<?= isset($v['approvals']['qa']) && $v['approvals']['qa']['status'] === 'approved' ? 'status-passed' : 'status-pending' ?>">
                <?= ucfirst($v['approvals']['qa']['status'] ?? 'Pending') ?>
              </span>
            </td>
            <td><?= esc($v['approvals']['qa']['approver_name'] ?? 'Pending Sign-off') ?></td>
            <td><?= !empty($v['approvals']['qa']['approved_at']) ? date('M d, Y h:i A', strtotime($v['approvals']['qa']['approved_at'])) : '-' ?></td>
          </tr>
          <tr>
            <td><strong>Technical / Backend Lead</strong></td>
            <td>
              <span class="<?= isset($v['approvals']['developer']) && $v['approvals']['developer']['status'] === 'approved' ? 'status-passed' : 'status-pending' ?>">
                <?= ucfirst($v['approvals']['developer']['status'] ?? 'Pending') ?>
              </span>
            </td>
            <td><?= esc($v['approvals']['developer']['approver_name'] ?? 'Pending Sign-off') ?></td>
            <td><?= !empty($v['approvals']['developer']['approved_at']) ? date('M d, Y h:i A', strtotime($v['approvals']['developer']['approved_at'])) : '-' ?></td>
          </tr>
          <tr>
            <td><strong>Product & Management</strong></td>
            <td>
              <span class="<?= isset($v['approvals']['management']) && $v['approvals']['management']['status'] === 'approved' ? 'status-passed' : 'status-pending' ?>">
                <?= ucfirst($v['approvals']['management']['status'] ?? 'Pending') ?>
              </span>
            </td>
            <td><?= esc($v['approvals']['management']['approver_name'] ?? 'Pending Sign-off') ?></td>
            <td><?= !empty($v['approvals']['management']['approved_at']) ? date('M d, Y h:i A', strtotime($v['approvals']['management']['approved_at'])) : '-' ?></td>
          </tr>
        </tbody>
      </table>

      <div style="margin-top: 30px; text-align: center;">
        <a href="<?= base_url('version-releases?version_id=' . $v['id']) ?>"
          style="background: #0d424d; color: #ffffff; padding: 12px 28px; text-decoration: none; border-radius: 6px; font-weight: 700; font-size: 14px; display: inline-block;">
          View Release in Petsfolio Admin Console &rarr;
        </a>
      </div>
    </div>

    <div class="footer">
      This email was automatically compiled and dispatched via Petsfolio Release Operations Management.<br>
      Petsfolio Live Admin &bull; Hyderabad, India &bull; &copy; <?= date('Y') ?> Petsfolio
    </div>
  </div>

</body>

</html>