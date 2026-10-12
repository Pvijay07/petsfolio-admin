<!DOCTYPE html>
<html>

<head>
<<<<<<< HEAD
  <!-- Theme Early Detection (Zero-Flicker) -->
  <script>
    (function() {
      try {
        var theme = localStorage.getItem('petsfolio_theme');
        if (!theme) {
          theme = (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) ? 'dark' : 'light';
        }
        if (theme === 'dark') {
          document.documentElement.classList.add('dark');
          document.documentElement.setAttribute('data-bs-theme', 'dark');
          document.documentElement.setAttribute('data-theme', 'dark');
        } else {
          document.documentElement.classList.remove('dark');
          document.documentElement.setAttribute('data-bs-theme', 'light');
          document.documentElement.setAttribute('data-theme', 'light');
        }
      } catch (e) {}
    })();
  </script>
=======
>>>>>>> origin/main
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Petsfolio | Admin</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="apple-touch-icon" sizes="76x76"
    href="https://cmschamps.com/psf-admin/public/assets/images/icons/Petsfolio.png">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <!-- Library / Plugin Css Build -->
  <link rel="stylesheet" href="<?= base_url() ?>public/assets/css/core/libs.min.css" />

  <!-- Aos Animation Css -->
  <link rel="stylesheet" href="<?= base_url() ?>public/assets/vendor/aos/dist/aos.css" />

  <!-- Hope Ui Design System Css -->
  <link rel="stylesheet" href="<?= base_url() ?>public/assets/css/hope-ui.min.css?v=2.0.0" />

  <!-- Custom Css -->
  <link rel="stylesheet" href="<?= base_url() ?>public/assets/css/custom.min.css?v=2.0.0" />

  <!-- Dark Css -->
  <link rel="stylesheet" href="<?= base_url() ?>public/assets/css/dark.min.css" />

<<<<<<< HEAD
  <!-- Universal Dark Mode Overrides (Global Engine) -->
  <link rel="stylesheet" href="<?= base_url('public/assets/css/theme-dark-overrides.css?v=' . time()) ?>" />

=======
>>>>>>> origin/main
  <!-- Customizer Css -->
  <link rel="stylesheet" href="<?= base_url() ?>public/assets/css/customizer.min.css" />

  <!-- RTL Css -->
  <link rel="stylesheet" href="<?= base_url() ?>public/assets/css/rtl.min.css" />

  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

  <!-- Lucide Icons CDN -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lucide-static@latest/font/lucide.css" />

  <!-- jQuery -->
  <script src="<?= base_url() ?>public/assets/js/jquery.min.js"></script>

  <style>
    input[type="date"] {
      display: block;
      position: relative;
      padding: 1rem 3.5rem 1rem 0.75rem;
      font-size: 1rem;
      font-family: monospace;
      border: 1px solid #8292a2;
      border-radius: 0.25rem;
      background: white url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='20' height='22' viewBox='0 0 20 22'%3E%3Cg fill='none' fill-rule='evenodd' stroke='%23688EBB' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' transform='translate(1 1)'%3E%3Crect width='18' height='18' y='2' rx='2'/%3E%3Cpath d='M13 0L13 4M5 0L5 4M0 8L18 8'/%3E%3C/g%3E%3C/svg%3E") right 1rem center no-repeat;
      cursor: pointer;
    }

    input[type="date"]:focus {
      outline: none;
      border-color: #3acfff;
      box-shadow: 0 0 0 0.25rem rgba(0, 120, 250, 0.1);
    }

    ::-webkit-datetime-edit-text {
      opacity: 0;
    }

    ::-webkit-clear-button,
    ::-webkit-inner-spin-button {
      display: none;
    }

    ::-webkit-calendar-picker-indicator {
      position: absolute;
      width: 2.5rem;
      height: 100%;
      top: 0;
      right: 0;
      bottom: 0;
      opacity: 0;
      cursor: pointer;
      color: rgba(0, 120, 250, 1);
      background: rgba(0, 120, 250, 1);
    }

    input[type="date"]:hover::-webkit-calendar-picker-indicator {
      opacity: 0.05;
    }

    input[type="date"]:hover::-webkit-calendar-picker-indicator:hover {
      opacity: 0.15;
    }

    .dropdown-menu {
      border: 1px solid #dee2e6;
      border-radius: 0.5rem;
      padding: 0.5rem;
      width: 400px;
      max-height: 300px;
      overflow-y: auto;
    }

    .dropdown-item {
      border-bottom: 1px solid #dee2e6;
      padding: 10px;
      font-size: 18px;
    }

    .dropdown-item:last-child {
      border-bottom: none;
    }

    .dropdown-header {
      font-weight: bold;
      font-size: 20px;
      padding: 5px 10px;
      background-color: #f8f9fa;
      border-bottom: 1px solid #dee2e6;
    }

    .dropdown-divider {
      margin: 0.5rem 0;
    }

    .tag-link {
      color: #007bff;
      text-decoration: underline;
      cursor: pointer;
    }

    .tag-link:hover {
      color: #0056b3;
      text-decoration: none;
    }

    .popup,
    .addpopup {
      display: none;
      position: fixed;
      top: 0;
      left: 113px;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      justify-content: center;
      align-items: center;
    }

    .popup-content {
      background: #fff;
      padding: 20px;
      border-radius: 8px;
      position: relative;
      width: 500px;
      text-align: center;
    }

    .close-icon {
      position: absolute;
      top: 10px;
      right: 10px;
      width: 30px;
      height: 30px;
      cursor: pointer;
    }

    .popup-content h2 {
      margin-top: 0;
    }

    .popup-content input,
    .popup-content select,
    .popup-content button {
      width: 100%;
      margin: 10px 0;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    .popup-content button {
      background-color: #007bff;
      color: white;
      cursor: pointer;
      border: none;
    }

    .popup-content button:hover {
      background-color: #0056b3;
    }

    .toggle-switch {
      position: relative;
      width: 60px;
      height: 30px;
    }

    .toggle-switch input {
      display: none;
    }

    .toggle-switch label {
      display: block;
      width: 100%;
      height: 100%;
      background-color: #ccc;
      border-radius: 30px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .toggle-switch label::after {
      content: '';
      position: absolute;
      width: 26px;
      height: 26px;
      background-color: white;
      border-radius: 50%;
      top: 2px;
      left: 2px;
      transition: transform 0.3s ease;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .toggle-switch input:checked+label {
      background-color: #4caf50;
    }

    .toggle-switch input:checked+label::after {
      transform: translateX(30px);
    }

    .close-button {
      color: #aaa;
      float: right;
      font-size: 28px;
      font-weight: bold;
      cursor: pointer;
    }

    .close-button:hover,
    .close-button:focus {
      color: black;
      text-decoration: none;
      cursor: pointer;
    }

    .close {
      color: #aaa;
      float: right;
      font-size: 28px;
      font-weight: bold;
    }

    .close:hover,
    .close:focus {
      color: black;
      text-decoration: none;
      cursor: pointer;
    }

    .verification {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    .verification-item {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .verification-img {
      width: 50px;
      height: 50px;
      object-fit: cover;
    }

    button {
      padding: 10px;
      font-size: 16px;
      cursor: pointer;
      background-color: #4CAF50;
      /* color: white; */
      border: none;
      border-radius: 5px;
    }

    .sidebar-content::-webkit-scrollbar {
      width: 8px;
    }

    .sidebar-content::-webkit-scrollbar-track {
      background: #f1f1f1;
      border-radius: 4px;
    }

    .sidebar-content::-webkit-scrollbar-thumb {
      background: #c5c5c5;
      border-radius: 4px;
    }

    button:hover {
      background-color: #45a049;
    }

    #quick-action-form .form-control,
    #quick-action-form .btn {
      height: 38px;
    }

    .verifyBtn {
      margin-bottom: 10px;
    }

    .verifiedStatus {
      font-weight: bold;
      color: green;
      padding: 5px;
      text-align: center;
      display: inline-block;
    }

    .scr .data-scrollbar {
      max-height: 87vh !important;
    }

    .permission-table {
      margin-top: 20px;
    }

    .permission-table th,
    .permission-table td {
      text-align: center;
      padding: 12px;
    }

    .btn-toolbar {
      margin-bottom: 20px;
    }

    .btn-toolbar button {
      margin-right: 10px;
    }

    .module-name {
      font-weight: bold;
    }

    .module-description {
      font-size: 14px;
      color: #888;
    }

    #passwordModal {
      z-index: 1055 !important;
    }

    .page-link.active,
    .active>.page-link {
      z-index: 1 !important;
    }

    .disabled-link {
      color: gray !important;
      pointer-events: none;
      opacity: 0.5;
      text-decoration: none;
      cursor: not-allowed;
    }

    .disabled-link i {
      margin-right: 5px;
    }

    .modal-backdrop {
      z-index: 1050 !important;
    }

<<<<<<< HEAD
=======
    .swal2-container {
      z-index: 99999 !important;
    }

>>>>>>> origin/main
    .font-weight-bold {
      font-weight: 700;
      background-color: #f8f9fa;
    }

    .booking-modal {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 2000;
    }

    .booking-modal-content {
      background: #fff;
      padding: 20px;
      border-radius: 5px;
      max-width: 600px;
      width: 90%;
      position: relative;
    }

    .modal-dialog-scrollable .modal-body {
      overflow-y: auto;
      max-height: calc(100vh - 200px);
    }

    .modal-dialog-scrollable .modal-content {
      max-height: 90vh;
    }

    .srv_dtls .modal-content {
      width: 800px !important;
    }

    .modal {
      display: none;
      background-color: rgb(0, 0, 0);
      background-color: rgba(0, 0, 0, 0.4);
      width: 100%;
      height: 100%;
      overflow: auto;
      position: fixed;
      z-index: 1060;
      left: 0;
      top: 0;
    }

    .modal-content {
      background-color: #fefefe;
      margin: 15% auto;
      padding: 20px;
      border: 1px solid #888;
      width: 100%;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .in_prgs .modal-content {
      width: 68vw;
      margin: 0px auto;
    }

    .address-tooltip {
      position: relative;
      display: inline-block;
      cursor: help;
    }

    .address-tooltip .address-tooltip-content {
      visibility: hidden;
      width: 300px;
      background-color: #fff;
      color: #333;
      text-align: left;
      border-radius: 4px;
      padding: 12px;
      position: absolute;
      z-index: 100;
      bottom: 125%;
      left: 50%;
      margin-left: -150px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
      border: 1px solid #ddd;
      opacity: 0;
      transition: opacity 0.3s;
    }

    .address-tooltip .address-tooltip-content::after {
      content: "";
      position: absolute;
      top: 100%;
      left: 50%;
      margin-left: -5px;
      border-width: 5px;
      border-style: solid;
      border-color: #fff transparent transparent transparent;
    }

    .address-tooltip:hover .address-tooltip-content {
      visibility: visible;
      opacity: 1;
    }

    .address-tooltip-content ol {
      margin: 8px 0 0 0;
      padding-left: 20px;
    }

    .address-tooltip-content li {
      margin-bottom: 6px;
    }

    .show-more-addresses:hover {
      text-decoration: underline;
    }

    .table-container {
      overflow-x: auto;
    }

    table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0;
    }

    th {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 18px 20px;
      text-align: left;
      font-weight: 600;
      font-size: 13px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    td {
      padding: 18px 20px;
      border-bottom: 1px solid #f0f0f0;
      color: #555;
      font-size: 14px;
    }

    tr:hover td {
      background: #f8f9ff;
    }

    .btn {
      padding: 8px 16px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-weight: 600;
      font-size: 13px;
      transition: all 0.3s ease;
      margin: 3px;
      display: inline-block;
      text-decoration: none;
    }

    .btn-primary {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }

    .btn-success {
      background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
      color: white;
      box-shadow: 0 4px 15px rgba(56, 239, 125, 0.3);
    }

    .btn-success:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(56, 239, 125, 0.4);
    }

    .btn-warning {
      background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
      color: white;
      box-shadow: 0 4px 15px rgba(245, 87, 108, 0.3);
    }

    .btn-warning:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(245, 87, 108, 0.4);
    }

    .btn-secondary {
      background: #e0e0e0;
      color: #555;
    }

    .btn-secondary:hover {
      background: #d0d0d0;
    }

    .status-badge {
      padding: 6px 14px;
      border-radius: 20px;
      font-size: 11px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      display: inline-block;
    }

    .status-sent {
      background: #e3f2fd;
      color: #1976d2;
    }

    .status-pending {
      background: #fff3e0;
      color: #f57c00;
    }

    .status-in-progress {
      background: #f3e5f5;
      color: #7b1fa2;
    }

    .status-closed {
      background: #e0e0e0;
      color: #616161;
    }

    .status-active {
      background: #e8f5e9;
      color: #2e7d32;
    }

    .status-inactive {
      background: #ffebee;
      color: #c62828;
    }

    .status-expired {
      background: #fafafa;
      color: #757575;
    }

    .status-settled {
      background: #e0f2f1;
      color: #00695c;
    }

    .status-rejected {
      background: #fce4ec;
      color: #ad1457;
    }

    .hidden {
      display: none;
    }

    .detail-section {
      margin-bottom: 25px;
      padding: 20px;
      background: #f8f9ff;
      border-radius: 12px;
      border-left: 4px solid #667eea;
    }

    .detail-section h3 {
      font-size: 18px;
      color: #667eea;
      margin-bottom: 15px;
      font-weight: 700;
    }

    .detail-row {
      display: flex;
      padding: 12px 0;
      border-bottom: 1px solid #e0e0e0;
    }

    .detail-row:last-child {
      border-bottom: none;
    }

    .detail-label {
      width: 180px;
      font-weight: 600;
      color: #555;
      font-size: 14px;
    }

    .detail-value {
      flex: 1;
      color: #333;
      font-size: 14px;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      margin-bottom: 8px;
      font-weight: 600;
      color: #555;
      font-size: 14px;
    }

    .form-group input,
    .form-group textarea,
    .form-group select {
      width: 100%;
      padding: 12px 15px;
      border: 2px solid #e0e0e0;
      border-radius: 10px;
      font-size: 14px;
      transition: all 0.3s ease;
      font-family: inherit;
    }

    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
      outline: none;
      border-color: #667eea;
      box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .form-group textarea {
      min-height: 100px;
      resize: vertical;
    }

    .form-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
    }

    .btn-group {
      display: flex;
      gap: 10px;
      justify-content: flex-end;
      margin-top: 25px;
      padding-top: 20px;
      border-top: 2px solid #f0f0f0;
    }

    .status-options {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
      gap: 10px;
      margin-bottom: 20px;
    }

    .status-option {
      padding: 12px 20px;
      background: #f0f0f0;
      border-radius: 10px;
      cursor: pointer;
      text-align: center;
      font-weight: 600;
      font-size: 13px;
      transition: all 0.3s ease;
      border: 2px solid transparent;
    }

    .status-option:hover {
      background: #e0e0e0;
    }

    .status-option.active {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      border-color: #667eea;
      box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 25px;
      margin-bottom: 30px;
    }

    .stat-card {
      background: white;
      padding: 30px;
      border-radius: 20px;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
      text-align: center;
    }

    .stat-card h3 {
      color: #555;
      font-size: 16px;
      margin-bottom: 15px;
      font-weight: 600;
    }

    .stat-value {
      font-size: 36px;
      font-weight: 700;
      color: #667eea;
      margin-bottom: 10px;
    }

    .stat-trend {
      font-size: 14px;
      color: #888;
    }

    @media (max-width: 768px) {
      .sidebar {
        width: 100%;
        height: auto;
      }

      .content {
        margin-left: 0;
        width: 100%;
        padding: 20px;
      }

      .form-row {
        grid-template-columns: 1fr;
      }

      .filters {
        flex-direction: column;
        align-items: stretch;
      }

      .nav-tabs {
        border: none;
        background: #f9fafb;
        border-radius: 9999px;
        padding: 4px;
        display: inline-flex;
        gap: 6px;
      }

      .nav-tabs .nav-link {
        border: none;
        border-radius: 9999px;
        color: #6b7280;
        font-weight: 600;
        background: transparent;
        transition: all 0.25s ease;
        padding: 8px 18px;
      }

      .nav-tabs .nav-link:hover {
        color: #111827;
        background: #e5e7eb;
      }

      .nav-tabs .nav-link.active {
        color: #fff !important;
        background: #2563eb !important;
        box-shadow: 0 2px 6px rgba(37, 99, 235, 0.25);
      }
    }

    @media (max-width: 576px) {
      .nav-tabs {
        flex-direction: column;
        border-radius: 12px;
      }
    }

    /* Updated Sidebar Styles */
    .sidebar-container {
      position: fixed;
      top: 0;
      left: 0;
      z-index: 30;
      height: 100vh;
      width: 16rem;
      background-color: white;
      border-right: 1px solid #f3f4f6;
      /* box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); */
      transition: all 0.3s ease-in-out;
      overflow-y: auto;
      overflow-x: hidden;
    }

    /* When sidebar is expanded or always show text */
    .sidebar-container:not(.collapsed) {
      width: 16rem;
    }

    .sidebar-container.collapsed {
      width: 4rem;
    }

    .sidebar-container.collapsed .nav-text,
    .sidebar-container.collapsed .nav-badge,
    .sidebar-container.collapsed .user-name,
    .sidebar-container.collapsed .user-role,
    .sidebar-container.collapsed .nav-section-title,
    .sidebar-container.collapsed .nav-chevron {
      display: none;
    }

    .sidebar-container.collapsed .sidebar-logo span {
      display: none;
    }

    .sidebar-container.collapsed .sidebar-content {
      padding: 1rem 0.5rem;
    }

    .sidebar-container.collapsed .nav-button {
      padding: 0.625rem;
      justify-content: center;
    }

    .sidebar-container.collapsed .nav-button-content {
      justify-content: center;
    }

    .sidebar-container.collapsed .sidebar-footer {
      padding: 0.5rem;
      justify-content: center;
    }

    .sidebar-container.collapsed .user-profile {
      justify-content: center;
    }

    .sidebar-container.collapsed .user-info {
      display: none;
    }

    .sidebar-container.collapsed .logout-btn {
      display: none;
    }

    /* Sidebar Toggle Button */
    .sidebar-toggle {
      position: absolute;
      top: 1rem;
      right: 10px;
      width: 24px;
      height: 24px;
      background-color: black;
      border: 1px solid #e5e7eb;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      z-index: 40;
      box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
    }

    .sidebar-toggle:hover {
      background-color: #777;
      border-color: #d1d5db;
    }

    .sidebar-toggle svg {
      width: 16px;
      height: 16px;
      transition: transform 0.3s ease;
    }

    .sidebar-container.collapsed .sidebar-toggle svg {
      transform: rotate(180deg);
    }

    /* Sidebar Header */
    .sidebar-header {
      height: 4rem;
      display: flex;
      align-items: center;
      padding-left: 1.5rem;
      padding-right: 1.5rem;
      border-bottom: 1px solid #f3f4f6;
      position: relative;
    }

    .sidebar-logo {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      color: #4f46e5;
      font-weight: bold;
      font-size: 1.25rem;
      transition: all 0.3s ease;
    }

    .sidebar-logo svg {
      width: 1.5rem;
      height: 1.5rem;
      flex-shrink: 0;
    }

    .sidebar-content {
      height: calc(100vh - 8rem);
      overflow-y: auto;
      padding: 1rem 0.75rem;
      overflow-x: hidden;
    }

    .sidebar-footer {
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      padding: 1rem;
      background-color: #f9fafb;
      border-top: 1px solid #f3f4f6;
      transition: all 0.3s ease;
    }

    .user-profile {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      transition: all 0.3s ease;
    }

    .user-avatar {
      width: 2rem;
      height: 2rem;
      border-radius: 50%;
      border: 2px solid white;
      box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
      flex-shrink: 0;
    }

    .user-info {
      flex: 1;
      overflow: hidden;
      transition: all 0.3s ease;
    }

    .user-name {
      font-size: 0.875rem;
      font-weight: bold;
      color: #111827;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .user-role {
      font-size: 0.75rem;
      color: #6b7280;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .logout-btn {
      padding: 0.375rem;
      color: #9ca3af;
      transition: color 0.2s;
      flex-shrink: 0;
    }

    .logout-btn:hover {
      color: #ef4444;
    }

    /* Navigation Items */
    .nav-item {
      margin-bottom: 0.25rem;
      position: relative;
    }

    .nav-button {
      width: 100%;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0.625rem 0.75rem;
      border-radius: 0.75rem;
      transition: all 0.2s;
      background-color: transparent;
      border: none;
      cursor: pointer;
      text-align: left;
      overflow: hidden;
      white-space: nowrap;
      margin-bottom: 0.25rem;
    }

    .nav-button:hover {
      background-color: #f5f3ff;
      color: #4f46e5;
    }

    .nav-button.active {
      background-color: #4f46e5;
      color: white;
      /* box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.1), 0 2px 4px -1px rgba(79, 70, 229, 0.06); */
    }

    .nav-button-content {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      overflow: hidden;
      flex: 1;
    }

    .nav-icon {
      width: 1rem;
      height: 1rem;
      flex-shrink: 0;
    }

    .nav-text {
      font-size: 0.875rem;
      font-weight: 500;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      flex: 1;
    }

    .nav-badge {
      font-size: 0.75rem;
      padding: 0.125rem 0.5rem;
      border-radius: 9999px;
      background-color: #fee2e2;
      color: #dc2626;
      flex-shrink: 0;
    }

    .nav-chevron {
      width: 0.75rem;
      height: 0.75rem;
      flex-shrink: 0;
      transition: transform 0.3s ease;
    }

    /* Sub Navigation */
    .sub-nav {
      margin-top: 0.25rem;
      margin-left: 1rem;
      padding-left: 0.75rem;
      border-left: 2px solid #e5e7eb;
      overflow: hidden;
      display: none;
    }

    .sub-nav.open {
      display: block;
    }

    .sub-nav .nav-button {
      padding: 0.375rem 0.75rem;
      font-size: 0.875rem;
    }

    .sub-nav .nav-button.active {
      background-color: #f5f3ff;
      color: #4f46e5;
    }

    .nav-divider {
      margin: 0.5rem 0;
      border: 0;
      border-top: 1px solid #e5e7eb;
    }

    .nav-section-title {
      padding: 0.75rem 0.75rem 0.375rem;
      font-size: 0.75rem;
      font-weight: 600;
      color: #6b7280;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    /* Disabled links */
    .disabled-link {
      opacity: 0.5;
      cursor: not-allowed;
    }

    .disabled-link:hover {
      background-color: transparent !important;
      color: inherit !important;
    }

    .disabled-link .nav-text {
      color: inherit;
    }

    .disabled-link i {
      margin-left: auto;
      font-size: 0.75rem;
      color: #9ca3af;
    }

    /* Main Content Adjustment */
    main {
      transition: margin-left 0.3s ease;
      min-height: 100vh;
    }

    body::-webkit-scrollbar {
      width: 8px;
    }

    body::-webkit-scrollbar-track {
      background: #f1f1f1;
      border-radius: 4px;
    }

    body::-webkit-scrollbar-thumb {
      background: #c5c5c5;
      border-radius: 4px;
    }

    .sidebar-container.collapsed+main {
      margin-left: 4rem;
    }

    /* Tooltip for collapsed sidebar */
    .sidebar-container.collapsed .nav-button {
      position: relative;
    }

    .sidebar-container.collapsed .nav-button::after {
      content: attr(data-tooltip);
      position: absolute;
      left: 100%;
      top: 50%;
      transform: translateY(-50%);
      background-color: #1f2937;
      color: white;
      padding: 0.5rem 0.75rem;
      border-radius: 0.375rem;
      font-size: 0.75rem;
      white-space: nowrap;
      opacity: 0;
      visibility: hidden;
      transition: all 0.2s ease;
      z-index: 50;
      margin-left: 0.5rem;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    .sidebar-container.collapsed .nav-button:hover::after {
      opacity: 1;
      visibility: visible;
    }

    .sidebar-container.collapsed .nav-button::before {
      content: '';
      position: absolute;
      left: 100%;
      top: 50%;
      transform: translateY(-50%);
      border-width: 5px;
      border-style: solid;
      border-color: transparent transparent transparent #1f2937;
      opacity: 0;
      visibility: hidden;
      transition: all 0.2s ease;
      margin-left: -0.25rem;
      z-index: 51;
    }

    .sidebar-container.collapsed .nav-button:hover::before {
      opacity: 1;
      visibility: visible;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
      .sidebar-container {
        transform: translateX(-100%);
        width: 16rem;
      }

      .sidebar-container.open {
        transform: translateX(0);
      }

      .sidebar-container.collapsed {
        transform: translateX(-100%);
        width: 16rem;
      }

      .sidebar-container.collapsed.open {
        transform: translateX(0);
        width: 16rem;
      }

      .sidebar-container.collapsed .nav-text,
      .sidebar-container.collapsed .nav-badge,
      .sidebar-container.collapsed .user-name,
      .sidebar-container.collapsed .user-role,
      .sidebar-container.collapsed .nav-section-title,
      .sidebar-container.collapsed .nav-chevron {
        display: block;
      }

      .sidebar-container.collapsed .sidebar-logo span {
        display: inline;
      }

      .sidebar-container.collapsed .sidebar-footer {
        padding: 1rem;
      }

      .sidebar-container.collapsed .user-profile {
        justify-content: flex-start;
      }

      .sidebar-container.collapsed .user-info {
        display: block;
      }

      .sidebar-container.collapsed .logout-btn {
        display: block;
      }

      .sidebar-toggle {
        display: none;
      }

      main {
        margin-left: 0 !important;
        width: 100%;
      }

      .sidebar-toggle {
        display: none;
      }
    }
<<<<<<< HEAD

    /* ==========================================================================
       GLOBAL DARK & LIGHT MODE STYLES
       ========================================================================== */
    :root {
      --pf-transition-speed: 0.25s;
    }

    body, .sidebar-container, nav.navbar.iq-navbar, .card, .modal-content, .dropdown-menu, .form-control, .form-select, input, select, textarea {
      transition: background-color var(--pf-transition-speed) ease, border-color var(--pf-transition-speed) ease, color var(--pf-transition-speed) ease, box-shadow var(--pf-transition-speed) ease !important;
    }

    /* Dark Theme Variables & Base */
    html.dark, body.dark, .dark {
      color-scheme: dark;
      background-color: #0b111e !important;
      color: #cbd5e1 !important;
    }

    html.dark body, body.dark {
      background-color: #0b111e !important;
      color: #cbd5e1 !important;
    }

    body.dark h1, body.dark h2, body.dark h3, body.dark h4, body.dark h5, body.dark h6,
    html.dark h1, html.dark h2, html.dark h3, html.dark h4, html.dark h5, html.dark h6,
    body.dark .h1, body.dark .h2, body.dark .h3, body.dark .h4, body.dark .h5, body.dark .h6 {
      color: #f8fafc !important;
    }

    body.dark .text-dark, html.dark .text-dark,
    body.dark .text-black, html.dark .text-black {
      color: #f8fafc !important;
    }

    body.dark .text-muted, html.dark .text-muted {
      color: #94a3b8 !important;
    }

    body.dark .text-secondary, html.dark .text-secondary {
      color: #cbd5e1 !important;
    }

    /* Sidebar Dark Mode */
    body.dark .sidebar-container {
      background-color: #141c2e !important;
      border-right-color: #26354d !important;
      box-shadow: 4px 0 20px rgba(0, 0, 0, 0.35) !important;
    }

    body.dark .sidebar-header {
      background-color: #141c2e !important;
      border-bottom-color: #26354d !important;
    }

    body.dark .sidebar-footer {
      background-color: #101726 !important;
      border-top-color: #26354d !important;
    }

    body.dark .user-name {
      color: #f8fafc !important;
    }

    body.dark .user-role {
      color: #94a3b8 !important;
    }

    body.dark .nav-section-title {
      color: #64748b !important;
    }

    body.dark .nav-button {
      color: #cbd5e1 !important;
      background-color: transparent !important;
    }

    body.dark .nav-button:hover {
      background-color: #1f2b42 !important;
      color: #60a5fa !important;
    }

    body.dark .nav-button.active {
      background-color: #2563eb !important;
      color: #ffffff !important;
      box-shadow: 0 4px 12px rgba(37, 99, 235, 0.35) !important;
    }

    body.dark .nav-button.active .nav-icon {
      color: #ffffff !important;
    }

    body.dark .nav-icon {
      color: #94a3b8 !important;
    }

    body.dark .sidebar-toggle {
      background-color: #26354d !important;
      border-color: #334155 !important;
    }

    body.dark .sidebar-toggle svg {
      stroke: #cbd5e1 !important;
    }

    body.dark .sidebar-toggle:hover {
      background-color: #334155 !important;
    }

    body.dark .sidebar-container.collapsed .nav-button::after {
      background-color: #1e293b !important;
      color: #f8fafc !important;
      border: 1px solid #334155 !important;
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.5) !important;
    }

    body.dark .sidebar-container.collapsed .nav-button::before {
      border-right-color: #1e293b !important;
    }

    /* Navbar Dark Mode */
    body.dark .iq-navbar,
    body.dark nav.navbar.iq-navbar {
      background: rgba(20, 28, 46, 0.94) !important;
      border-bottom: 1px solid #26354d !important;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4) !important;
      backdrop-filter: blur(12px) !important;
      -webkit-backdrop-filter: blur(12px) !important;
    }

    body.dark .navbar-inner h3,
    body.dark .navbar-inner .text-dark,
    body.dark .navbar-nav .text-dark,
    body.dark .navbar-list .text-dark {
      color: #f8fafc !important;
    }

    body.dark .navbar-toggler-bar {
      background-color: #cbd5e1 !important;
    }

    body.dark .theme-toggle-btn {
      background: #182234 !important;
      border-color: #26354d !important;
      color: #f8fafc !important;
    }

    body.dark .theme-toggle-btn:hover {
      background: #1f2b42 !important;
      border-color: #3b82f6 !important;
    }

    /* Cards & Panels */
    body.dark .card,
    html.dark .card {
      background-color: #141c2e !important;
      border-color: #26354d !important;
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3) !important;
      color: #cbd5e1;
    }

    body.dark .card-header,
    html.dark .card-header {
      background-color: #141c2e !important;
      border-bottom-color: #26354d !important;
      color: #f8fafc !important;
    }

    body.dark .card-footer,
    html.dark .card-footer {
      background-color: #101726 !important;
      border-top-color: #26354d !important;
    }

    body.dark .bg-white,
    html.dark .bg-white {
      background-color: #141c2e !important;
    }

    body.dark .bg-light,
    html.dark .bg-light {
      background-color: #182234 !important;
      color: #cbd5e1 !important;
    }

    body.dark .border,
    html.dark .border,
    body.dark .border-bottom,
    html.dark .border-bottom,
    body.dark .border-top,
    html.dark .border-top,
    body.dark .border-start,
    html.dark .border-start,
    body.dark .border-end,
    html.dark .border-end {
      border-color: #26354d !important;
    }

    /* Tables */
    body.dark .table,
    html.dark .table {
      color: #cbd5e1 !important;
      border-color: #26354d !important;
    }

    body.dark .table > :not(caption) > * > *,
    html.dark .table > :not(caption) > * > * {
      background-color: transparent !important;
      border-bottom-color: #26354d !important;
      color: #cbd5e1 !important;
    }

    body.dark .table-hover > tbody > tr:hover > *,
    html.dark .table-hover > tbody > tr:hover > * {
      background-color: #182234 !important;
      color: #ffffff !important;
    }

    body.dark .table thead,
    body.dark .table thead th,
    body.dark .table-light,
    html.dark .table-light {
      background-color: #101726 !important;
      color: #94a3b8 !important;
      border-bottom-color: #26354d !important;
    }

    body.dark .table-striped > tbody > tr:nth-of-type(odd) > * {
      background-color: rgba(24, 34, 52, 0.4) !important;
    }

    /* Forms & Inputs */
    body.dark .form-control,
    body.dark .form-select,
    body.dark input[type="text"],
    body.dark input[type="password"],
    body.dark input[type="email"],
    body.dark input[type="number"],
    body.dark input[type="tel"],
    body.dark input[type="search"],
    body.dark input[type="date"],
    body.dark select,
    body.dark textarea,
    html.dark .form-control,
    html.dark .form-select {
      background-color: #162032 !important;
      border-color: #26354d !important;
      color: #f8fafc !important;
    }

    body.dark .form-control:focus,
    body.dark .form-select:focus,
    body.dark input:focus,
    body.dark select:focus,
    body.dark textarea:focus {
      background-color: #162032 !important;
      border-color: #3b82f6 !important;
      color: #ffffff !important;
      box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25) !important;
    }

    body.dark .form-control::placeholder,
    body.dark input::placeholder,
    body.dark textarea::placeholder {
      color: #64748b !important;
    }

    body.dark .input-group-text {
      background-color: #182234 !important;
      border-color: #26354d !important;
      color: #94a3b8 !important;
    }

    body.dark input[type="date"] {
      background-color: #162032 !important;
      color: #f8fafc !important;
      border-color: #26354d !important;
    }

    body.dark .form-check-input {
      background-color: #162032 !important;
      border-color: #334155 !important;
    }

    body.dark .form-check-input:checked {
      background-color: #3b82f6 !important;
      border-color: #3b82f6 !important;
    }

    /* Dropdowns & Modals */
    body.dark .dropdown-menu,
    html.dark .dropdown-menu {
      background-color: #141c2e !important;
      border: 1px solid #26354d !important;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5) !important;
      color: #cbd5e1 !important;
    }

    body.dark .dropdown-item,
    html.dark .dropdown-item {
      color: #cbd5e1 !important;
      border-bottom-color: #1e2c40 !important;
    }

    body.dark .dropdown-item:hover,
    body.dark .dropdown-item:focus,
    html.dark .dropdown-item:hover {
      background-color: #1f2b42 !important;
      color: #ffffff !important;
    }

    body.dark .dropdown-divider,
    html.dark .dropdown-divider {
      border-top-color: #26354d !important;
    }

    body.dark .dropdown-header,
    html.dark .dropdown-header {
      background-color: #101726 !important;
      color: #94a3b8 !important;
      border-bottom-color: #26354d !important;
    }

    body.dark .notification-item:hover {
      background-color: #1f2b42 !important;
    }

    body.dark .modal-content,
    html.dark .modal-content {
      background-color: #141c2e !important;
      border: 1px solid #26354d !important;
      color: #cbd5e1 !important;
      box-shadow: 0 20px 50px rgba(0, 0, 0, 0.6) !important;
    }

    body.dark .modal-header,
    html.dark .modal-header {
      border-bottom-color: #26354d !important;
    }

    body.dark .modal-footer,
    html.dark .modal-footer {
      border-top-color: #26354d !important;
      background-color: #101726 !important;
    }

    body.dark .modal-body.bg-light {
      background-color: #101726 !important;
    }

    body.dark .btn-close,
    html.dark .btn-close {
      filter: invert(1) grayscale(100%) brightness(200%);
    }

    /* Badges & Soft Colors */
    body.dark .badge.bg-light,
    html.dark .badge.bg-light {
      background-color: #1f2b42 !important;
      color: #cbd5e1 !important;
      border-color: #26354d !important;
    }

    body.dark .bg-primary-subtle {
      background-color: rgba(59, 130, 246, 0.18) !important;
      color: #93c5fd !important;
    }

    body.dark .bg-danger-subtle {
      background-color: rgba(239, 68, 68, 0.18) !important;
      color: #fca5a5 !important;
    }

    body.dark .bg-warning-subtle {
      background-color: rgba(245, 158, 11, 0.18) !important;
      color: #fcd34d !important;
    }

    body.dark .bg-success-subtle {
      background-color: rgba(16, 185, 129, 0.18) !important;
      color: #6ee7b7 !important;
    }

    body.dark .bg-info-subtle {
      background-color: rgba(6, 182, 212, 0.18) !important;
      color: #67e8f9 !important;
    }

    body.dark .border-primary-subtle { border-color: rgba(59, 130, 246, 0.35) !important; }
    body.dark .border-danger-subtle { border-color: rgba(239, 68, 68, 0.35) !important; }
    body.dark .border-warning-subtle { border-color: rgba(245, 158, 11, 0.35) !important; }
    body.dark .border-success-subtle { border-color: rgba(16, 185, 129, 0.35) !important; }
    body.dark .border-info-subtle { border-color: rgba(6, 182, 212, 0.35) !important; }

    /* Buttons */
    body.dark .btn-light,
    html.dark .btn-light {
      background-color: #1f2b42 !important;
      border-color: #26354d !important;
      color: #f8fafc !important;
    }

    body.dark .btn-light:hover,
    html.dark .btn-light:hover {
      background-color: #2a3b59 !important;
      color: #ffffff !important;
    }

    body.dark .btn-outline-secondary,
    html.dark .btn-outline-secondary {
      border-color: #334155 !important;
      color: #cbd5e1 !important;
    }

    body.dark .btn-outline-secondary:hover,
    html.dark .btn-outline-secondary:hover {
      background-color: #1f2b42 !important;
      color: #ffffff !important;
    }

    /* Nav Tabs & Accordions */
    body.dark .nav-tabs {
      border-bottom-color: #26354d !important;
    }

    body.dark .nav-tabs .nav-link {
      color: #94a3b8 !important;
    }

    body.dark .nav-tabs .nav-link.active {
      background-color: #141c2e !important;
      color: #60a5fa !important;
      border-color: #26354d #26354d #141c2e !important;
    }

    body.dark .nav-pills .nav-link {
      color: #cbd5e1 !important;
    }

    body.dark .nav-pills .nav-link.active {
      background-color: #2563eb !important;
      color: #ffffff !important;
    }

    body.dark .accordion-item {
      background-color: #141c2e !important;
      border-color: #26354d !important;
    }

    body.dark .accordion-button {
      background-color: #182234 !important;
      color: #f8fafc !important;
    }

    body.dark .accordion-button:not(.collapsed) {
      background-color: #1f2b42 !important;
      color: #60a5fa !important;
    }

    body.dark .accordion-button::after {
      filter: invert(1);
    }

    /* Popups & SweetAlert2 */
    body.dark .popup-content,
    body.dark .addpopup .popup-content {
      background: #141c2e !important;
      color: #f8fafc !important;
      border: 1px solid #26354d !important;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7) !important;
    }

    body.dark .popup-content input,
    body.dark .popup-content select {
      background-color: #162032 !important;
      border-color: #26354d !important;
      color: #f8fafc !important;
    }

    body.dark .swal2-popup {
      background: #141c2e !important;
      color: #f8fafc !important;
      border: 1px solid #26354d !important;
    }

    body.dark .swal2-title,
    body.dark .swal2-html-container {
      color: #f8fafc !important;
    }

    /* Code & Pre Blocks */
    body.dark code {
      background-color: #182234 !important;
      color: #f43f5e !important;
    }

    body.dark pre {
      background-color: #0f172a !important;
      color: #38bdf8 !important;
    }

    /* Footer */
    body.dark .footer,
    html.dark .footer {
      background-color: #101726 !important;
      border-top-color: #26354d !important;
      color: #94a3b8 !important;
    }

    body.dark .footer a {
      color: #60a5fa !important;
    }

    /* DataTables pagination */
    body.dark .dataTables_wrapper .dataTables_paginate .paginate_button {
      color: #cbd5e1 !important;
    }

    body.dark .dataTables_wrapper .dataTables_paginate .paginate_button.current {
      background: #2563eb !important;
      color: #ffffff !important;
      border-color: #2563eb !important;
    }

    body.dark .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
      background: #1f2b42 !important;
      color: #ffffff !important;
      border-color: #26354d !important;
    }

    body.dark .dataTables_wrapper select,
    body.dark .dataTables_wrapper input {
      background-color: #162032 !important;
      border-color: #26354d !important;
      color: #f8fafc !important;
    }

    /* Dashboard, Analytics & Specialized Components */
    body.dark .dashboard-tab {
      color: #cbd5e1 !important;
    }
    body.dark .dashboard-tab:hover {
      color: #60a5fa !important;
      background: rgba(59, 130, 246, 0.15) !important;
    }
    body.dark .dashboard-tab.active {
      background-color: rgba(59, 130, 246, 0.25) !important;
      color: #60a5fa !important;
      border: 1px solid rgba(59, 130, 246, 0.5) !important;
    }
    body.dark .chart-container-premium,
    body.dark .chart-container {
      background-color: #141c2e !important;
      border-color: #26354d !important;
    }
    body.dark .stat-card-hover {
      border-color: #26354d !important;
    }
    body.dark .stat-card-hover:hover {
      border-color: #3b82f6 !important;
      box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5) !important;
    }
    body.dark .glass-card {
      background: rgba(20, 28, 46, 0.8) !important;
      border-color: #26354d !important;
    }
    body.dark .glass-pill {
      background: rgba(255, 255, 255, 0.1) !important;
      border: 1px solid rgba(255, 255, 255, 0.15) !important;
      color: #f8fafc !important;
    }
    body.dark .glass-btn {
      background: rgba(255, 255, 255, 0.9) !important;
      color: #0b111e !important;
    }
    body.dark .glass-btn:hover {
      background: #ffffff !important;
      color: #0b111e !important;
    }
    body.dark .list-group-item {
      background-color: #141c2e !important;
      border-color: #26354d !important;
      color: #cbd5e1 !important;
    }
    body.dark .list-group-item-action:hover {
      background-color: #1f2b42 !important;
      color: #ffffff !important;
    }
    body.dark .alert {
      border-color: #26354d !important;
    }
    body.dark .progress {
      background-color: #182234 !important;
    }
=======
>>>>>>> origin/main
  </style>
</head>

<body>
  <!-- Sidebar -->
  <aside class="sidebar-container">
    <!-- Sidebar Toggle Button -->
    <div class="sidebar-toggle" onclick="toggleSidebar()">
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="m15 18-6-6 6-6"></path>
      </svg>
    </div>

    <!-- Sidebar Header -->
    <div class="sidebar-header">
      <img class="w-50 self-center" src="https://www.petsfolio.com/in/packages/assets/main-logo-BaG-3MLw.svg" alt="">
    </div>

    <!-- Sidebar Content -->
    <div class="sidebar-content">
      <!-- Home Section -->
      <div class="nav-section-title">Home</div>

      <!-- Dashboard -->
      <div class="nav-item">
<<<<<<< HEAD
        <button class="nav-button <?= service('uri')->getSegment(1) === 'dashboard' ? 'active' : '' ?>"
          onclick="window.location.href='<?= base_url() ?>dashboard'" data-tooltip="Dashboard">
          <div class="nav-button-content">
            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
              fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect width="7" height="9" x="3" y="3" rx="1"></rect>
              <rect width="7" height="5" x="14" y="3" rx="1"></rect>
              <rect width="7" height="9" x="14" y="12" rx="1"></rect>
              <rect width="7" height="5" x="3" y="16" rx="1"></rect>
            </svg>
            <span class="nav-text">Dashboard</span>
          </div>
        </button>
      </div>

      <!-- AI Business Analyst -->
      <div class="nav-item">
        <button class="nav-button <?= service('uri')->getSegment(1) === 'ai-analyst' || service('uri')->getSegment(1) === 'ai-analysis' ? 'active' : '' ?>"
          onclick="window.location.href='<?= base_url('ai-analyst') ?>'" data-tooltip="AI Analyst">
          <div class="nav-button-content">
            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
              fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
              <line x1="8" y1="21" x2="16" y2="21"></line>
              <line x1="12" y1="17" x2="12" y2="21"></line>
              <polyline points="7 10 10 7 13 10 17 6"></polyline>
            </svg>
            <span class="nav-text">AI Analyst <span class="badge bg-success ms-1" style="font-size:10px;">BI</span></span>
          </div>
        </button>
      </div>
=======
        <?php if (hasPermission('dashboard.view')) : ?>
          <button class="nav-button <?= service('uri')->getSegment(1) === 'dashboard' ? 'active' : '' ?>"
            onclick="window.location.href='<?= base_url() ?>dashboard'" data-tooltip="Dashboard">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect width="7" height="9" x="3" y="3" rx="1"></rect>
                <rect width="7" height="5" x="14" y="3" rx="1"></rect>
                <rect width="7" height="9" x="14" y="12" rx="1"></rect>
                <rect width="7" height="5" x="3" y="16" rx="1"></rect>
              </svg>
              <span class="nav-text">Dashboard</span>
            </div>
          </button>
        <?php else : ?>
          <button class="nav-button disabled-link" data-tooltip="Dashboard">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect width="7" height="9" x="3" y="3" rx="1"></rect>
                <rect width="7" height="5" x="14" y="3" rx="1"></rect>
                <rect width="7" height="9" x="14" y="12" rx="1"></rect>
                <rect width="7" height="5" x="3" y="16" rx="1"></rect>
              </svg>
              <span class="nav-text">Dashboard</span>
            </div>
            <i class="fas fa-lock"></i>
          </button>
        <?php endif; ?>
      </div>

      <!-- AI Business Analyst -->
      <?php if (hasPermission('ai_analyst.view')) : ?>
        <div class="nav-item">
          <button class="nav-button <?= service('uri')->getSegment(1) === 'ai-analyst' || service('uri')->getSegment(1) === 'ai-analysis' ? 'active' : '' ?>"
            onclick="window.location.href='<?= base_url('ai-analyst') ?>'" data-tooltip="AI Analyst">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                <line x1="8" y1="21" x2="16" y2="21"></line>
                <line x1="12" y1="17" x2="12" y2="21"></line>
                <polyline points="7 10 10 7 13 10 17 6"></polyline>
              </svg>
              <span class="nav-text">AI Analyst <span class="badge bg-success ms-1" style="font-size:10px;">BI</span></span>
            </div>
          </button>
        </div>
      <?php endif; ?>
>>>>>>> origin/main


      <!-- Bookings Dropdown -->
      <div class="nav-item">
<<<<<<< HEAD
        <button class="nav-button <?=
                                  service('uri')->getSegment(1) === 'bookingRequests' ||
                                    service('uri')->getSegment(1) === 'bookings' ||
                                    service('uri')->getSegment(1) === 'missed-walks' ? 'active' : '' ?>"
          onclick="toggleDropdown('bookings-dropdown')" data-tooltip="Bookings">
          <div class="nav-button-content">
            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
              fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M8 2v4"></path>
              <path d="M16 2v4"></path>
              <rect width="18" height="18" x="3" y="4" rx="2"></rect>
              <path d="M3 10h18"></path>
              <path d="M8 14h.01"></path>
              <path d="M12 14h.01"></path>
              <path d="M16 14h.01"></path>
              <path d="M8 18h.01"></path>
              <path d="M12 18h.01"></path>
              <path d="M16 18h.01"></path>
            </svg>
            <span class="nav-text">Bookings</span>
            <span id="booking-parent-badge" class="nav-badge" style="display: none;"></span>
          </div>
          <svg class="nav-chevron" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="m6 9 6 6 6-6"></path>
          </svg>
        </button>
        <div id="bookings-dropdown" class="sub-nav <?=
                                                    service('uri')->getSegment(1) === 'bookingRequests' ||
                                                      service('uri')->getSegment(1) === 'bookings' ||
                                                      service('uri')->getSegment(1) === 'missed-walks' ? 'open' : '' ?>">
          <!-- New Requests -->
          <?php if (hasPermission('57')) : ?>
            <button class="nav-button <?= service('uri')->getSegment(1) === 'bookingRequests' ? 'active' : '' ?>"
              onclick="window.location.href='<?= base_url('bookingRequests') ?>'" data-tooltip="New Requests">
              <span class="nav-text">New Requests</span>
              <span id="new-requests-badge" class="nav-badge" style="display: none;"></span>
            </button>
          <?php else : ?>
            <button class="nav-button disabled-link" data-tooltip="New Requests">
              <span class="nav-text">New Requests</span>
              <i class="fas fa-lock"></i>
            </button>
          <?php endif; ?>

          <!-- Active Bookings -->
          <?php if (hasPermission('58')) : ?>
            <button
              class="nav-button <?= service('uri')->getSegment(1) === 'bookings' && service('uri')->getSegment(2) === 'in_progress' ? 'active' : '' ?>"
              onclick="window.location.href='<?= base_url('bookings/in_progress') ?>'" data-tooltip="Active Bookings">
              <span class="nav-text">Active Bookings</span>
              <span id="active-bookings-badge" class="nav-badge" style="display: none;"></span>
            </button>
          <?php else : ?>
            <button class="nav-button disabled-link" data-tooltip="Active Bookings">
              <span class="nav-text">Active Bookings</span>
              <i class="fas fa-lock"></i>
            </button>
          <?php endif; ?>

          <!-- On Hold -->
          <?php if (hasPermission('60')) : ?>
            <button
              class="nav-button <?= service('uri')->getSegment(1) === 'bookings' && service('uri')->getSegment(2) === 'halted' ? 'active' : '' ?>"
              onclick="window.location.href='<?= base_url() ?>bookings/halted'" data-tooltip="On Hold">
              <span class="nav-text">On Hold</span>
              <span id="on-hold-badge" class="nav-badge" style="display: none;"></span>
            </button>
          <?php else : ?>
            <button class="nav-button disabled-link" data-tooltip="On Hold">
              <span class="nav-text">On Hold</span>
              <i class="fas fa-lock"></i>
            </button>
          <?php endif; ?>

          <!-- No Show -->
          <?php if (hasPermission('77')) : ?>
            <button class="nav-button <?= service('uri')->getSegment(1) === 'missed-walks' ? 'active' : '' ?>"
              onclick="window.location.href='<?= base_url() ?>missed-walks'" data-tooltip="No Show">
              <span class="nav-text">No Show</span>
              <span id="no-show-badge" class="nav-badge" style="display: none;"></span>
            </button>
          <?php else : ?>
            <button class="nav-button disabled-link" data-tooltip="No Show">
              <span class="nav-text">No Show</span>
              <i class="fas fa-lock"></i>
            </button>
          <?php endif; ?>

          <!-- Completed -->
          <?php if (hasPermission('59')) : ?>
            <button
              class="nav-button <?= service('uri')->getSegment(1) === 'bookings' && service('uri')->getSegment(2) === 'completed' ? 'active' : '' ?>"
              onclick="window.location.href='<?= base_url() ?>bookings/completed'" data-tooltip="Completed">
              <span class="nav-text">Completed</span>
              <span id="completed-bookings-badge" class="nav-badge" style="display: none;"></span>
            </button>
          <?php else : ?>
            <button class="nav-button disabled-link" data-tooltip="Completed">
              <span class="nav-text">Completed</span>
              <i class="fas fa-lock"></i>
            </button>
          <?php endif; ?>

          <!-- Cancelled -->
          <?php if (hasPermission('78')) : ?>
            <button
              class="nav-button <?= service('uri')->getSegment(1) === 'bookings' && service('uri')->getSegment(2) === 'cancelled' ? 'active' : '' ?>"
              onclick="window.location.href='<?= base_url() ?>bookings/cancelled'" data-tooltip="Cancelled">
              <span class="nav-text">Cancelled</span>
              <span id="cancelled-bookings-badge" class="nav-badge" style="display: none;"></span>
            </button>
          <?php else : ?>
            <button class="nav-button disabled-link" data-tooltip="Cancelled">
              <span class="nav-text">Cancelled</span>
              <i class="fas fa-lock"></i>
            </button>
          <?php endif; ?>

          <!-- Deleted -->
          <?php if (hasPermission('61')) : ?>
            <button
              class="nav-button <?= service('uri')->getSegment(1) === 'bookings' && service('uri')->getSegment(2) === 'deleted' ? 'active' : '' ?>"
              onclick="window.location.href='<?= base_url() ?>bookings/deleted'" data-tooltip="Deleted">
              <span class="nav-text">Deleted</span>
              <span id="deleted-bookings-badge" class="nav-badge" style="display: none;"></span>
            </button>
          <?php else : ?>
            <button class="nav-button disabled-link" data-tooltip="Deleted">
              <span class="nav-text">Deleted</span>
              <i class="fas fa-lock"></i>
            </button>
          <?php endif; ?>
        </div>
      </div>

      <hr class="nav-divider">

=======
        <?php if (hasPermission('bookings.walking') || hasPermission('bookings.view_requests') || hasPermission('bookings.all')) : ?>
          <button class="nav-button <?=
                                    service('uri')->getSegment(1) === 'bookingRequests' ||
                                      service('uri')->getSegment(1) === 'bookings' ||
                                      service('uri')->getSegment(1) === 'missed-walks' ? 'active' : '' ?>"
            onclick="toggleDropdown('bookings-dropdown')" data-tooltip="Bookings">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M8 2v4"></path>
                <path d="M16 2v4"></path>
                <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                <path d="M3 10h18"></path>
                <path d="M8 14h.01"></path>
                <path d="M12 14h.01"></path>
                <path d="M16 14h.01"></path>
                <path d="M8 18h.01"></path>
                <path d="M12 18h.01"></path>
                <path d="M16 18h.01"></path>
              </svg>
              <span class="nav-text">Bookings</span>
              <span id="booking-parent-badge" class="nav-badge" style="display: none;"></span>
            </div>
            <svg class="nav-chevron" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
              fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="m6 9 6 6 6-6"></path>
            </svg>
          </button>
          <div id="bookings-dropdown" class="sub-nav <?=
                                                      service('uri')->getSegment(1) === 'bookingRequests' ||
                                                        service('uri')->getSegment(1) === 'bookings' ||
                                                        service('uri')->getSegment(1) === 'missed-walks' ? 'open' : '' ?>">
            <!-- New Requests -->
            <?php if (hasPermission('bookings.view_requests')) : ?>
              <button class="nav-button <?= service('uri')->getSegment(1) === 'bookingRequests' ? 'active' : '' ?>"
                onclick="window.location.href='<?= base_url('bookingRequests') ?>'" data-tooltip="New Requests">
                <span class="nav-text">New Requests</span>
                <span id="new-requests-badge" class="nav-badge" style="display: none;"></span>
              </button>
            <?php else : ?>
              <button class="nav-button disabled-link" data-tooltip="New Requests">
                <span class="nav-text">New Requests</span>
                <i class="fas fa-lock"></i>
              </button>
            <?php endif; ?>

            <!-- Active Bookings -->
            <?php if (hasPermission('bookings.walking')) : ?>
              <button
                class="nav-button <?= service('uri')->getSegment(1) === 'bookings' && service('uri')->getSegment(2) === 'in_progress' ? 'active' : '' ?>"
                onclick="window.location.href='<?= base_url('bookings/in_progress') ?>'" data-tooltip="Active Bookings">
                <span class="nav-text">Active Bookings</span>
                <span id="active-bookings-badge" class="nav-badge" style="display: none;"></span>
              </button>
            <?php else : ?>
              <button class="nav-button disabled-link" data-tooltip="Active Bookings">
                <span class="nav-text">Active Bookings</span>
                <i class="fas fa-lock"></i>
              </button>
            <?php endif; ?>

            <!-- Halted -->
            <?php if (hasPermission('bookings.walking')) : ?>
              <button
                class="nav-button <?= service('uri')->getSegment(1) === 'bookings' && service('uri')->getSegment(2) === 'halted' ? 'active' : '' ?>"
                onclick="window.location.href='<?= base_url() ?>bookings/halted'" data-tooltip="Halted">
                <span class="nav-text">Halted</span>
                <span id="on-hold-badge" class="nav-badge" style="display: none;"></span>
              </button>
            <?php else : ?>
              <button class="nav-button disabled-link" data-tooltip="Halted">
                <span class="nav-text">Halted</span>
                <i class="fas fa-lock"></i>
              </button>
            <?php endif; ?>

            <!-- No Show -->
            <?php if (hasPermission('bookings.missed_walks')) : ?>
              <button class="nav-button <?= service('uri')->getSegment(1) === 'missed-walks' ? 'active' : '' ?>"
                onclick="window.location.href='<?= base_url() ?>missed-walks'" data-tooltip="No Show">
                <span class="nav-text">No Show</span>
                <span id="no-show-badge" class="nav-badge" style="display: none;"></span>
              </button>
            <?php else : ?>
              <button class="nav-button disabled-link" data-tooltip="No Show">
                <span class="nav-text">No Show</span>
                <i class="fas fa-lock"></i>
              </button>
            <?php endif; ?>

            <!-- Completed -->
            <?php if (hasPermission('bookings.walking')) : ?>
              <button
                class="nav-button <?= service('uri')->getSegment(1) === 'bookings' && service('uri')->getSegment(2) === 'completed' ? 'active' : '' ?>"
                onclick="window.location.href='<?= base_url() ?>bookings/completed'" data-tooltip="Completed">
                <span class="nav-text">Completed</span>
                <span id="completed-bookings-badge" class="nav-badge" style="display: none;"></span>
              </button>
            <?php else : ?>
              <button class="nav-button disabled-link" data-tooltip="Completed">
                <span class="nav-text">Completed</span>
                <i class="fas fa-lock"></i>
              </button>
            <?php endif; ?>

            <!-- Cancelled -->
            <?php if (hasPermission('bookings.walking')) : ?>
              <button
                class="nav-button <?= service('uri')->getSegment(1) === 'bookings' && service('uri')->getSegment(2) === 'cancelled' ? 'active' : '' ?>"
                onclick="window.location.href='<?= base_url() ?>bookings/cancelled'" data-tooltip="Cancelled">
                <span class="nav-text">Cancelled</span>
                <span id="cancelled-bookings-badge" class="nav-badge" style="display: none;"></span>
              </button>
            <?php else : ?>
              <button class="nav-button disabled-link" data-tooltip="Cancelled">
                <span class="nav-text">Cancelled</span>
                <i class="fas fa-lock"></i>
              </button>
            <?php endif; ?>

            <!-- Deleted -->
            <?php if (hasPermission('bookings.delete')) : ?>
              <button
                class="nav-button <?= service('uri')->getSegment(1) === 'bookings' && service('uri')->getSegment(2) === 'deleted' ? 'active' : '' ?>"
                onclick="window.location.href='<?= base_url() ?>bookings/deleted'" data-tooltip="Deleted">
                <span class="nav-text">Deleted</span>
                <span id="deleted-bookings-badge" class="nav-badge" style="display: none;"></span>
              </button>
            <?php else : ?>
              <button class="nav-button disabled-link" data-tooltip="Deleted">
                <span class="nav-text">Deleted</span>
                <i class="fas fa-lock"></i>
              </button>
            <?php endif; ?>
          </div>
        <?php else : ?>
          <button class="nav-button disabled-link" data-tooltip="Bookings">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M8 2v4"></path>
                <path d="M16 2v4"></path>
                <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                <path d="M3 10h18"></path>
              </svg>
              <span class="nav-text">Bookings</span>
            </div>
            <i class="fas fa-lock"></i>
          </button>
        <?php endif; ?>
      </div>
>>>>>>> origin/main
      <!-- AI Call Intelligence & Conversation Analysis -->
      <div class="nav-section-title">AI Call Intelligence</div>

      <!-- SP Verification Call Analysis (Executive <-> SP) -->
      <div class="nav-item">
        <button class="nav-button <?= service('uri')->getSegment(1) === 'call-analysis' ? 'active' : '' ?>"
          onclick="window.location.href='<?= base_url('call-analysis') ?>'" data-tooltip="SP Verification Calls">
          <div class="nav-button-content">
            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
              fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z"></path>
              <path d="M19 10v2a7 7 0 0 1-14 0v-2"></path>
              <line x1="12" x2="12" y1="19" y2="22"></line>
            </svg>
            <span class="nav-text">SP Verification Calls <span class="badge bg-soft-primary text-primary ms-1" style="font-size:10px;">Executive</span></span>
          </div>
        </button>
      </div>
<<<<<<< HEAD

=======
>>>>>>> origin/main
      <!-- Client & SP Service Call Analysis (Client <-> SP) -->
      <div class="nav-item">
        <button class="nav-button <?= service('uri')->getSegment(1) === 'client-call-analysis' ? 'active' : '' ?>"
          onclick="window.location.href='<?= base_url('client-call-analysis') ?>'" data-tooltip="Client & SP Calls">
          <div class="nav-button-content">
            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
              fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
            </svg>
            <span class="nav-text">Client & SP Calls <span class="badge bg-soft-success text-success ms-1" style="font-size:10px;">Service AI</span></span>
          </div>
        </button>
      </div>
<<<<<<< HEAD
=======
      <hr class="nav-divider">
>>>>>>> origin/main

      <!-- Users Section -->
      <div class="nav-section-title">Users</div>

<<<<<<< HEAD
      <div class="nav-item">
        <button class="nav-button <?= service('uri')->getSegment(1) === 'chat-history' ? 'active' : '' ?>"
          onclick="window.location.href='<?= base_url() ?>chat-history'" data-tooltip="Chat History">
          <div class="nav-button-content">
            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
              fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
            </svg>
            <span class="nav-text">Chat History</span>
          </div>
        </button>
      </div>
=======
      <!-- AI Call Analysis -->
      <!-- <div class="nav-item">
        <button class="nav-button <?= service('uri')->getSegment(1) === 'ai-analysis' ? 'active' : '' ?>"
          onclick="window.location.href='<?= base_url() ?>ai-analysis'" data-tooltip="AI Call Analysis">
          <div class="nav-button-content">
            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
              fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z"></path>
              <path d="M19 10v2a7 7 0 0 1-14 0v-2"></path>
              <line x1="12" x2="12" y1="19" y2="22"></line>
            </svg>
            <span class="nav-text">AI Call Analysis</span>
          </div>
        </button>
      </div> -->

      <?php if (hasPermission('chat_history.view')) : ?>
        <div class="nav-item">
          <button class="nav-button <?= service('uri')->getSegment(1) === 'chat-history' ? 'active' : '' ?>"
            onclick="window.location.href='<?= base_url() ?>chat-history'" data-tooltip="Chat History">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
              </svg>
              <span class="nav-text">Chat History</span>
            </div>
          </button>
        </div>
      <?php endif; ?>
>>>>>>> origin/main

      <!-- Unauthorized Provider Visits -->
      <div class="nav-item">
        <button class="nav-button <?= service('uri')->getSegment(1) === 'suspicious-visits' ? 'active' : '' ?>"
          onclick="window.location.href='<?= base_url('suspicious-visits') ?>'" data-tooltip="Unauthorized Visits">
          <div class="nav-button-content">
            <svg class="nav-icon text-danger" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
              fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
              <line x1="12" y1="8" x2="12" y2="12"></line>
              <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            <span class="nav-text text-danger font-weight-bold">Unauthorized Visits</span>
          </div>
        </button>
      </div>

      <!-- Service Providers Dropdown -->
      <div class="nav-item">
<<<<<<< HEAD
        <button class="nav-button <?= service('uri')->getSegment(1) === 'providers' ? 'active' : '' ?>"
          onclick="toggleDropdown('providers-dropdown')" data-tooltip="Service Providers">
          <div class="nav-button-content">
            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
              fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
              <path d="M16 3.128a4 4 0 0 1 0 7.744"></path>
              <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
              <circle cx="9" cy="7" r="4"></circle>
            </svg>
            <span class="nav-text">Service Providers</span>
          </div>
          <svg class="nav-chevron" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="m6 9 6 6 6-6"></path>
          </svg>
        </button>
        <div id="providers-dropdown"
          class="sub-nav <?= service('uri')->getSegment(1) === 'providers' ? 'open' : '' ?>">
          <?php if (hasPermission('53')) : ?>
            <!-- New SP Flow -->
            <div class="px-3 py-2 fw-bold text-primary small bg-light mb-2 rounded" style="font-size: 0.7rem;">NEW SP FLOW</div>
            
            <button
              class="nav-button <?= (uri_string() === 'providers' && isset($_GET['status']) && $_GET['status'] === 'pending_registration') ? 'active' : '' ?>"
              onclick="window.location.href='<?= base_url('providers?status=pending_registration') ?>'"
              data-tooltip="Fee Pending">
              <span class="nav-text">Fee pending</span>
            </button>
            <button
              class="nav-button <?= (uri_string() === 'providers' && isset($_GET['status']) && $_GET['status'] === 'pending_verification' && (!isset($_GET['overdue']) || $_GET['overdue'] !== '1')) ? 'active' : '' ?>"
              onclick="window.location.href='<?= base_url('providers?status=pending_verification') ?>'"
              data-tooltip="Waiting for Call">
              <span class="nav-text">waiting for call</span>
            </button>
            <button
              class="nav-button <?= (uri_string() === 'providers' && isset($_GET['status']) && $_GET['status'] === 'pending_verification' && isset($_GET['overdue']) && $_GET['overdue'] === '1') ? 'active' : '' ?>"
              onclick="window.location.href='<?= base_url('providers?status=pending_verification&overdue=1') ?>'"
              data-tooltip="Waiting for Call (>24h)">
              <span class="nav-text" style="color: #ff4d4d !important; font-weight: bold;">waiting for call (>24h)</span>
            </button>
            <button
              class="nav-button <?= (uri_string() === 'providers' && isset($_GET['status']) && $_GET['status'] === 'approval') ? 'active' : '' ?>"
              onclick="window.location.href='<?= base_url('providers?status=approval') ?>'"
              data-tooltip="Manager Approval">
              <span class="nav-text">Manager Approval</span>
            </button>
            <button
              class="nav-button <?= (uri_string() === 'providers' && isset($_GET['status']) && $_GET['status'] === 'pending_kyc') ? 'active' : '' ?>"
              onclick="window.location.href='<?= base_url('providers?status=pending_kyc') ?>'"
              data-tooltip="KYC Pending">
              <span class="nav-text">KYC pending</span>
            </button>
            <button
              class="nav-button <?= (uri_string() === 'providers' && isset($_GET['status']) && $_GET['status'] === 'active' && !isset($_GET['flow'])) ? 'active' : '' ?>"
              onclick="window.location.href='<?= base_url('providers?status=active') ?>'"
              data-tooltip="Verified">
              <span class="nav-text">Verified</span>
            </button>
            <button
              class="nav-button <?= (uri_string() === 'providers' && isset($_GET['status']) && $_GET['status'] === 'inactive') ? 'active' : '' ?>"
              onclick="window.location.href='<?= base_url('providers?status=inactive') ?>'"
              data-tooltip="Suspended">
              <span class="nav-text">Suspended</span>
            </button>

            <div class="px-3 py-2 fw-bold text-secondary small bg-light my-2 rounded" style="font-size: 0.7rem;">OLD SP FLOW</div>
            
            <button
              class="nav-button <?= (uri_string() === 'providers' && isset($_GET['status']) && $_GET['status'] === 'non_verified') ? 'active' : '' ?>"
              onclick="window.location.href='<?= base_url('providers?status=non_verified') ?>'"
              data-tooltip="Old SP-Non Verified">
              <span class="nav-text">Old SP-Non Verified</span>
            </button>
            <button
              class="nav-button <?= (uri_string() === 'providers' && isset($_GET['status']) && $_GET['status'] === 'active' && isset($_GET['flow']) && $_GET['flow'] === 'old') ? 'active' : '' ?>"
              onclick="window.location.href='<?= base_url('providers?status=active&flow=old') ?>'"
              data-tooltip="Verified">
              <span class="nav-text">Verified</span>
            </button>
          <?php endif; ?>
        </div>
=======
        <?php if (hasPermission('providers.view') || (function_exists('hasAnyProviderPermission') && hasAnyProviderPermission())) : ?>
          <button class="nav-button <?= (service('uri')->getSegment(1) === 'providers' || service('uri')->getSegment(1) === 'aadhaar-verification-logs') ? 'active' : '' ?>"
            onclick="toggleDropdown('providers-dropdown')" data-tooltip="Service Providers">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                <path d="M16 3.128a4 4 0 0 1 0 7.744"></path>
                <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                <circle cx="9" cy="7" r="4"></circle>
              </svg>
              <span class="nav-text">Service Providers</span>
            </div>
            <svg class="nav-chevron" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
              fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="m6 9 6 6 6-6"></path>
            </svg>
          </button>
          <div id="providers-dropdown"
            class="sub-nav <?= (service('uri')->getSegment(1) === 'providers' || service('uri')->getSegment(1) === 'aadhaar-verification-logs') ? 'open' : '' ?>">
            <?php if (hasPermission('providers.view') || hasPermission('fee_pending.view')) : ?>
              <button
                class="nav-button <?= (uri_string() === 'providers' && isset($_GET['status']) && $_GET['status'] === 'pending_registration') ? 'active' : '' ?>"
                onclick="window.location.href='<?= base_url('providers?status=pending_registration') ?>'"
                data-tooltip="Fee Pending">
                <span class="nav-text">Fee pending</span>
              </button>
            <?php endif; ?>

            <?php if (hasPermission('providers.view') || hasPermission('wait_for_call.view')) : ?>
              <button
                class="nav-button <?= (uri_string() === 'providers' && isset($_GET['status']) && $_GET['status'] === 'pending_verification' && (!isset($_GET['overdue']) || $_GET['overdue'] !== '1')) ? 'active' : '' ?>"
                onclick="window.location.href='<?= base_url('providers?status=pending_verification') ?>'"
                data-tooltip="Waiting for Call">
                <span class="nav-text">waiting for call</span>
              </button>
            <?php endif; ?>

            <?php if (hasPermission('providers.view') || hasPermission('wait_for_call_24.view') || hasPermission('wait_for_call.view')) : ?>
              <button
                class="nav-button <?= (uri_string() === 'providers' && isset($_GET['status']) && $_GET['status'] === 'pending_verification' && isset($_GET['overdue']) && $_GET['overdue'] === '1') ? 'active' : '' ?>"
                onclick="window.location.href='<?= base_url('providers?status=pending_verification&overdue=1') ?>'"
                data-tooltip="Waiting for Call (>24h)">
                <span class="nav-text" style="color: #ff4d4d !important; font-weight: bold;">waiting for call (>24h)</span>
              </button>
            <?php endif; ?>

            <?php if (hasPermission('providers.view') || hasPermission('manager_approval.view')) : ?>
              <button
                class="nav-button <?= (uri_string() === 'providers' && isset($_GET['status']) && $_GET['status'] === 'approval') ? 'active' : '' ?>"
                onclick="window.location.href='<?= base_url('providers?status=approval') ?>'"
                data-tooltip="Manager Approval">
                <span class="nav-text">Manager Approval</span>
              </button>
            <?php endif; ?>

            <?php if (hasPermission('providers.view') || hasPermission('kyc_pending.view')) : ?>
              <button
                class="nav-button <?= (uri_string() === 'providers' && isset($_GET['status']) && $_GET['status'] === 'pending_kyc') ? 'active' : '' ?>"
                onclick="window.location.href='<?= base_url('providers?status=pending_kyc') ?>'"
                data-tooltip="KYC Pending">
                <span class="nav-text">KYC pending</span>
              </button>
            <?php endif; ?>

            <?php if (hasPermission('providers.view') || hasPermission('verified.view') || hasPermission('old_verified.view')) : ?>
              <button
                class="nav-button <?= (uri_string() === 'providers' && isset($_GET['status']) && $_GET['status'] === 'active') ? 'active' : '' ?>"
                onclick="window.location.href='<?= base_url('providers?status=active') ?>'"
                data-tooltip="Verified">
                <span class="nav-text">Verified</span>
              </button>
            <?php endif; ?>

            <?php if (hasPermission('providers.view') || hasPermission('old_non_verified.view')) : ?>
              <button
                class="nav-button <?= (uri_string() === 'providers' && isset($_GET['status']) && $_GET['status'] === 'non_verified') ? 'active' : '' ?>"
                onclick="window.location.href='<?= base_url('providers?status=non_verified') ?>'"
                data-tooltip="Old SP-Non Verified">
                <span class="nav-text">Old SP-Non Verified</span>
              </button>
            <?php endif; ?>

            <?php if (hasPermission('providers.view') || hasPermission('suspended.view')) : ?>
              <button
                class="nav-button <?= (uri_string() === 'providers' && isset($_GET['status']) && $_GET['status'] === 'inactive') ? 'active' : '' ?>"
                onclick="window.location.href='<?= base_url('providers?status=inactive') ?>'"
                data-tooltip="Suspended">
                <span class="nav-text">Suspended</span>
              </button>
            <?php endif; ?>

            <?php if (hasPermission('providers.view')) : ?>
              <div class="px-3 py-2 fw-bold text-primary small bg-light my-2 rounded" style="font-size: 0.7rem;">VERIFICATIONS</div>
              <button
                class="nav-button <?= (uri_string() === 'aadhaar-verification-logs') ? 'active' : '' ?>"
                onclick="window.location.href='<?= base_url('aadhaar-verification-logs') ?>'"
                data-tooltip="Aadhaar Verification Logs">
                <span class="nav-text"><i class="bi bi-shield-check me-1"></i> Aadhaar Logs</span>
              </button>
            <?php endif; ?>

            <?php if (hasPermission('media.approval')) : ?>
              <div class="px-3 py-2 fw-bold text-success small bg-light my-2 rounded" style="font-size: 0.7rem;">MEDIA MANAGEMENT</div>
              <button
                class="nav-button <?= (uri_string() === 'service-media-approval') ? 'active' : '' ?>"
                onclick="window.location.href='<?= base_url('service-media-approval') ?>'"
                data-tooltip="Media Approval">
                <span class="nav-text"><i class="fas fa-photo-video mr-1"></i> Media Approval</span>
              </button>
            <?php endif; ?>
          </div>
        <?php else : ?>
          <button class="nav-button disabled-link" data-tooltip="Service Providers">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                <path d="M16 3.128a4 4 0 0 1 0 7.744"></path>
                <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                <circle cx="9" cy="7" r="4"></circle>
              </svg>
              <span class="nav-text">Service Providers</span>
            </div>
            <i class="fas fa-lock"></i>
          </button>
        <?php endif; ?>
>>>>>>> origin/main
      </div>

      <!-- Clients Dropdown -->
      <div class="nav-item">
<<<<<<< HEAD
        <button class="nav-button <?= service('uri')->getSegment(1) === 'clients' ? 'active' : '' ?>"
          onclick="toggleDropdown('clients-dropdown')" data-tooltip="Clients">
          <div class="nav-button-content">
            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
              fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
              <circle cx="9" cy="7" r="4"></circle>
              <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
              <path d="M16 3.128a4 4 0 0 1 0 7.744"></path>
            </svg>
            <span class="nav-text">Clients</span>
          </div>
          <svg class="nav-chevron" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="m6 9 6 6 6-6"></path>
          </svg>
        </button>
        <div id="clients-dropdown"
          class="sub-nav <?= service('uri')->getSegment(1) === 'clients' ? 'open' : '' ?>">
          <?php if (hasPermission('50')) : ?>
=======
        <?php if (hasPermission('clients.view')) : ?>
          <button class="nav-button <?= service('uri')->getSegment(1) === 'clients' ? 'active' : '' ?>"
            onclick="toggleDropdown('clients-dropdown')" data-tooltip="Clients">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.128a4 4 0 0 1 0 7.744"></path>
              </svg>
              <span class="nav-text">Clients</span>
            </div>
            <svg class="nav-chevron" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
              fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="m6 9 6 6 6-6"></path>
            </svg>
          </button>
          <div id="clients-dropdown"
            class="sub-nav <?= service('uri')->getSegment(1) === 'clients' ? 'open' : '' ?>">
>>>>>>> origin/main
            <button
              class="nav-button <?= (uri_string() === 'clients' && isset($_GET['status']) && $_GET['status'] === 'active') ? 'active' : '' ?>"
              onclick="window.location.href='<?= base_url('clients?status=active') ?>'" data-tooltip="Active Clients">
              <span class="nav-text">Active Clients</span>
            </button>
            <button
              class="nav-button <?= uri_string() === 'clients' && isset($_GET['status']) && $_GET['status'] === 'inactive' ? 'active' : '' ?>"
              onclick="window.location.href='<?= base_url('clients?status=inactive') ?>'"
              data-tooltip="Blocked Clients">
              <span class="nav-text">Blocked Clients</span>
            </button>
<<<<<<< HEAD
          <?php else : ?>
            <button class="nav-button disabled-link" data-tooltip="Clients">
              <span class="nav-text">Clients</span>
              <i class="fas fa-lock"></i>
            </button>
          <?php endif; ?>
        </div>
=======
          </div>
        <?php else : ?>
          <button class="nav-button disabled-link" data-tooltip="Clients">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.128a4 4 0 0 1 0 7.744"></path>
              </svg>
              <span class="nav-text">Clients</span>
            </div>
            <i class="fas fa-lock"></i>
          </button>
        <?php endif; ?>
      </div>

      <!-- Client & SP Agreements -->
      <div class="nav-item">
        <?php if (hasPermission('clients.view') || hasPermission('service_providers.view') || hasPermission('agreements.view')) : ?>
          <button class="nav-button <?= service('uri')->getSegment(1) === 'agreements' ? 'active' : '' ?>"
            onclick="window.location.href='<?= base_url('agreements') ?>'" data-tooltip="Agreements Received">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="16" y1="13" x2="8" y2="13"></line>
                <line x1="16" y1="17" x2="8" y2="17"></line>
                <polyline points="10 9 9 9 8 9"></polyline>
              </svg>
              <span class="nav-text">Agreements</span>
            </div>
            <span id="agreements-badge" class="nav-badge" style="display: none;"></span>
          </button>
        <?php else : ?>
          <button class="nav-button disabled-link" data-tooltip="Agreements Received">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="16" y1="13" x2="8" y2="13"></line>
                <line x1="16" y1="17" x2="8" y2="17"></line>
                <polyline points="10 9 9 9 8 9"></polyline>
              </svg>
              <span class="nav-text">Agreements</span>
            </div>
            <i class="fas fa-lock"></i>
          </button>
        <?php endif; ?>
>>>>>>> origin/main
      </div>

      <!-- Employees -->
      <div class="nav-item">
<<<<<<< HEAD
        <?php if (hasPermission('34')) : ?>
=======
        <?php if (hasPermission('employees.view')) : ?>
>>>>>>> origin/main
          <button class="nav-button <?= service('uri')->getSegment(1) === 'employees' ? 'active' : '' ?>"
            onclick="window.location.href='<?= base_url() ?>employees'" data-tooltip="Employees">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
              </svg>
              <span class="nav-text">Employees</span>
            </div>
          </button>
        <?php else : ?>
          <button class="nav-button disabled-link" data-tooltip="Employees">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
              </svg>
              <span class="nav-text">Employees</span>
            </div>
            <i class="fas fa-lock"></i>
          </button>
        <?php endif; ?>
      </div>

<<<<<<< HEAD
      <div class="nav-item">
        <button class="nav-button <?= service('uri')->getSegment(1) === 'walker-leaderboard' ? 'active' : '' ?>"
          onclick="window.location.href='<?= base_url() ?>walker-leaderboard'" data-tooltip="Walker Leaderboard">
          <div class="nav-button-content">
            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
              fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
              <circle cx="9" cy="7" r="4"></circle>
            </svg>
            <span class="nav-text">Walker Dashboard</span>
          </div>
        </button>
      </div>
      <div class="nav-item">
        <button class="nav-button <?= service('uri')->getSegment(1) === 'sp-management' ? 'active' : '' ?>"
          onclick="window.location.href='<?= base_url() ?>sp-management'" data-tooltip="sp-management">
          <div class="nav-button-content">
            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
              fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
              <circle cx="9" cy="7" r="4"></circle>
            </svg>
            <span class="nav-text">SP Management</span>
          </div>
        </button>
=======


      <!-- Walker Dashboard -->
      <div class="nav-item">
        <?php if (hasPermission('walker.leaderboard')) : ?>
          <button class="nav-button <?= service('uri')->getSegment(1) === 'walker-leaderboard' ? 'active' : '' ?>"
            onclick="window.location.href='<?= base_url() ?>walker-leaderboard'" data-tooltip="Walker Leaderboard">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
              </svg>
              <span class="nav-text">Walker Dashboard</span>
            </div>
          </button>
        <?php else : ?>
          <button class="nav-button disabled-link" data-tooltip="Walker Dashboard">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
              </svg>
              <span class="nav-text">Walker Dashboard</span>
            </div>
            <i class="fas fa-lock"></i>
          </button>
        <?php endif; ?>
      </div>

      <!-- SP Management -->
      <div class="nav-item">
        <?php if (hasPermission('walker.sp_management')) : ?>
          <button class="nav-button <?= service('uri')->getSegment(1) === 'sp-management' ? 'active' : '' ?>"
            onclick="window.location.href='<?= base_url() ?>sp-management'" data-tooltip="sp-management">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
              </svg>
              <span class="nav-text">SP Management</span>
            </div>
          </button>
        <?php else : ?>
          <button class="nav-button disabled-link" data-tooltip="SP Management">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
              </svg>
              <span class="nav-text">SP Management</span>
            </div>
            <i class="fas fa-lock"></i>
          </button>
        <?php endif; ?>
>>>>>>> origin/main
      </div>
      <hr class="nav-divider">

      <!-- Support Section -->
      <div class="nav-section-title">Support</div>

      <!-- Client Support -->
      <div class="nav-item">
<<<<<<< HEAD
        <?php if (hasPermission('72')) : ?>
=======
        <?php if (hasPermission('support.client')) : ?>
>>>>>>> origin/main
          <button class="nav-button <?= service('uri')->getSegment(1) === 'support' ? 'active' : '' ?>"
            onclick="window.location.href='<?= base_url() ?>support'" data-tooltip="Client Support">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <path d="m4.93 4.93 4.24 4.24"></path>
                <path d="m14.83 9.17 4.24-4.24"></path>
                <path d="m14.83 14.83 4.24 4.24"></path>
                <path d="m9.17 14.83-4.24 4.24"></path>
                <circle cx="12" cy="12" r="4"></circle>
              </svg>
              <span class="nav-text">Client Support</span>
            </div>
            <span id="client-support-badge" class="nav-badge" style="display: none;"></span>
          </button>
        <?php else : ?>
          <button class="nav-button disabled-link" data-tooltip="Client Support">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <path d="m4.93 4.93 4.24 4.24"></path>
                <path d="m14.83 9.17 4.24-4.24"></path>
                <path d="m14.83 14.83 4.24 4.24"></path>
                <path d="m9.17 14.83-4.24 4.24"></path>
                <circle cx="12" cy="12" r="4"></circle>
              </svg>
              <span class="nav-text">Client Support</span>
            </div>
            <i class="fas fa-lock"></i>
          </button>
        <?php endif; ?>
      </div>

      <!-- SP Support -->
      <div class="nav-item">
<<<<<<< HEAD
        <?php if (hasPermission('73')) : ?>
=======
        <?php if (hasPermission('support.provider')) : ?>
>>>>>>> origin/main
          <button class="nav-button <?= service('uri')->getSegment(1) === 'sp-support' ? 'active' : '' ?>"
            onclick="window.location.href='<?= base_url() ?>sp-support'" data-tooltip="SP Support">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <path d="m4.93 4.93 4.24 4.24"></path>
                <path d="m14.83 9.17 4.24-4.24"></path>
                <path d="m14.83 14.83 4.24 4.24"></path>
                <path d="m9.17 14.83-4.24 4.24"></path>
                <circle cx="12" cy="12" r="4"></circle>
              </svg>
              <span class="nav-text">SP Support</span>
            </div>
            <span id="sp-support-badge" class="nav-badge" style="display: none;"></span>
          </button>
        <?php else : ?>
          <button class="nav-button disabled-link" data-tooltip="SP Support">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <path d="m4.93 4.93 4.24 4.24"></path>
                <path d="m14.83 9.17 4.24-4.24"></path>
                <path d="m14.83 14.83 4.24 4.24"></path>
                <path d="m9.17 14.83-4.24 4.24"></path>
                <circle cx="12" cy="12" r="4"></circle>
              </svg>
              <span class="nav-text">SP Support</span>
            </div>
            <i class="fas fa-lock"></i>
          </button>
        <?php endif; ?>
      </div>

      <hr class="nav-divider">

      <!-- Insurance Section -->
      <div class="nav-section-title">Insurance</div>

      <!-- New Enquiries -->
      <div class="nav-item">
<<<<<<< HEAD
        <?php if (hasPermission('67')) : ?>
          <button class="nav-button <?= service('uri')->getSegment(1) === 'enquiries' ? 'active' : '' ?>"
            onclick="window.location.href='<?= base_url() ?>enquiries'" data-tooltip="New Enquiries">
            <div class="nav-button-content">
              <span class="nav-text">📋 New Enquiries</span>
=======
        <?php if (hasPermission('insurance.view')) : ?>
          <button class="nav-button <?= service('uri')->getSegment(1) === 'enquiries' ? 'active' : '' ?>"
            onclick="window.location.href='<?= base_url() ?>enquiries'" data-tooltip="New Enquiries">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
              </svg>
              <span class="nav-text">New Enquiries</span>
>>>>>>> origin/main
            </div>
          </button>
        <?php else : ?>
          <button class="nav-button disabled-link" data-tooltip="New Enquiries">
            <div class="nav-button-content">
<<<<<<< HEAD
              <span class="nav-text">📋 New Enquiries</span>
=======
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
              </svg>
              <span class="nav-text">New Enquiries</span>
>>>>>>> origin/main
            </div>
            <i class="fas fa-lock"></i>
          </button>
        <?php endif; ?>
      </div>

<<<<<<< HEAD


      <!-- Active Insurance -->
      <div class="nav-item">
        <?php if (hasPermission('64')) : ?>
          <button class="nav-button <?= service('uri')->getSegment(1) === 'active' ? 'active' : '' ?>"
            onclick="window.location.href='<?= base_url() ?>active'" data-tooltip="Active Insurance">
            <div class="nav-button-content">
              <span class="nav-text">✅ Active Insurance</span>
=======
      <!-- Active Insurance -->
      <div class="nav-item">
        <?php if (hasPermission('insurance.view')) : ?>
          <button class="nav-button <?= service('uri')->getSegment(1) === 'active' ? 'active' : '' ?>"
            onclick="window.location.href='<?= base_url() ?>active'" data-tooltip="Active Insurance">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                <polyline points="9 12 11 14 15 10"></polyline>
              </svg>
              <span class="nav-text">Active Insurance</span>
>>>>>>> origin/main
            </div>
          </button>
        <?php else : ?>
          <button class="nav-button disabled-link" data-tooltip="Active Insurance">
            <div class="nav-button-content">
<<<<<<< HEAD
              <span class="nav-text">✅ Active Insurance</span>
=======
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                <polyline points="9 12 11 14 15 10"></polyline>
              </svg>
              <span class="nav-text">Active Insurance</span>
>>>>>>> origin/main
            </div>
            <i class="fas fa-lock"></i>
          </button>
        <?php endif; ?>
      </div>

      <!-- Expiring Soon -->
      <div class="nav-item">
<<<<<<< HEAD
        <?php if (hasPermission('65')) : ?>
          <button class="nav-button <?= service('uri')->getSegment(1) === 'expiring' ? 'active' : '' ?>"
            onclick="window.location.href='<?= base_url() ?>expiring'" data-tooltip="Expiring Soon">
            <div class="nav-button-content">
              <span class="nav-text">⏰ Expiring Soon</span>
=======
        <?php if (hasPermission('insurance.view')) : ?>
          <button class="nav-button <?= service('uri')->getSegment(1) === 'expiring' ? 'active' : '' ?>"
            onclick="window.location.href='<?= base_url() ?>expiring'" data-tooltip="Expiring Soon">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12 16 14"></polyline>
              </svg>
              <span class="nav-text">Expiring Soon</span>
>>>>>>> origin/main
            </div>
          </button>
        <?php else : ?>
          <button class="nav-button disabled-link" data-tooltip="Expiring Soon">
            <div class="nav-button-content">
<<<<<<< HEAD
              <span class="nav-text">⏰ Expiring Soon</span>
=======
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12 16 14"></polyline>
              </svg>
              <span class="nav-text">Expiring Soon</span>
>>>>>>> origin/main
            </div>
            <i class="fas fa-lock"></i>
          </button>
        <?php endif; ?>
      </div>

      <!-- Insurance Claims -->
      <div class="nav-item">
<<<<<<< HEAD
        <?php if (hasPermission('65')) : ?>
          <button class="nav-button <?= service('uri')->getSegment(1) === 'claims' ? 'active' : '' ?>"
            onclick="window.location.href='<?= base_url() ?>claims'" data-tooltip="Insurance Claims">
            <div class="nav-button-content">
              <span class="nav-text">📑 Insurance Claims</span>
=======
        <?php if (hasPermission('insurance.view')) : ?>
          <button class="nav-button <?= service('uri')->getSegment(1) === 'claims' ? 'active' : '' ?>"
            onclick="window.location.href='<?= base_url() ?>claims'" data-tooltip="Insurance Claims">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="16" y1="13" x2="8" y2="13"></line>
                <line x1="16" y1="17" x2="8" y2="17"></line>
                <polyline points="10 9 9 9 8 9"></polyline>
              </svg>
              <span class="nav-text">Insurance Claims</span>
>>>>>>> origin/main
            </div>
          </button>
        <?php else : ?>
          <button class="nav-button disabled-link" data-tooltip="Insurance Claims">
            <div class="nav-button-content">
<<<<<<< HEAD
              <span class="nav-text">📑 Insurance Claims</span>
=======
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="16" y1="13" x2="8" y2="13"></line>
                <line x1="16" y1="17" x2="8" y2="17"></line>
                <polyline points="10 9 9 9 8 9"></polyline>
              </svg>
              <span class="nav-text">Insurance Claims</span>
>>>>>>> origin/main
            </div>
            <i class="fas fa-lock"></i>
          </button>
        <?php endif; ?>
      </div>

      <hr class="nav-divider">

      <!-- Community Section -->
      <div class="nav-section-title">Community</div>

      <!-- Channels -->
      <div class="nav-item">
        <button
          class="nav-button <?= service('uri')->getSegment(1) === 'community' && service('uri')->getSegment(2) === 'channels' ? 'active' : '' ?>"
          onclick="window.location.href='<?= base_url('community/channels') ?>'" data-tooltip="Channels">
          <div class="nav-button-content">
            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
              fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M17 6.1H3"></path>
              <path d="M21 12.1H3"></path>
              <path d="M15.1 18H3"></path>
            </svg>
            <span class="nav-text">Channels</span>
          </div>
        </button>
      </div>

      <!-- Post Management -->
      <div class="nav-item">
        <button
          class="nav-button <?= service('uri')->getSegment(1) === 'community' && service('uri')->getSegment(2) === 'posts' ? 'active' : '' ?>"
          onclick="window.location.href='<?= base_url('community/posts') ?>'" data-tooltip="Post Management">
          <div class="nav-button-content">
            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
              fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect width="18" height="18" x="3" y="3" rx="2" ry="2"></rect>
              <line x1="3" x2="21" y1="9" y2="9"></line>
              <line x1="9" x2="9" y1="21" y2="9"></line>
            </svg>
            <span class="nav-text">Post Management</span>
          </div>
        </button>
      </div>

      <!-- Reported Posts -->
      <div class="nav-item">
        <button
          class="nav-button <?= service('uri')->getSegment(1) === 'community' && service('uri')->getSegment(2) === 'reports' ? 'active' : '' ?>"
          onclick="window.location.href='<?= base_url('community/reports') ?>'" data-tooltip="Reported Posts">
          <div class="nav-button-content">
            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
              fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"></path>
              <line x1="4" x2="4" y1="22" y2="15"></line>
            </svg>
            <span class="nav-text">Reported Posts</span>
          </div>
        </button>
      </div>

      <hr class="nav-divider">
      <!-- Loyalty Section -->
<<<<<<< HEAD
      <div class="nav-section-title">Loyalty Management</div>

      <!-- Programs -->
      <div class="nav-item">
        <button class="nav-button <?= service('uri')->getSegment(1) === 'loyalty-programs' ? 'active' : '' ?>"
          onclick="window.location.href='<?= base_url('loyalty-programs') ?>'" data-tooltip="Programs">
          <div class="nav-button-content">
            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
              fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"></path>
              <path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"></path>
              <path d="M4 22h16"></path>
              <path d="M10 14.66V17c0 .55.45 1 1 1h2c.55 0 1-.45 1-1v-2.34"></path>
              <path d="M18 22V5a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v17"></path>
            </svg>
            <span class="nav-text">Loyalty Programs</span>
          </div>
        </button>
      </div>

      <!-- Users with Points -->
      <div class="nav-item">
        <button class="nav-button <?= service('uri')->getSegment(1) === 'loyalty-admin' ? 'active' : '' ?>"
          onclick="window.location.href='<?= base_url('loyalty-admin') ?>'" data-tooltip="Point Holders">
          <div class="nav-button-content">
            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
              fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="8" r="5"></circle>
              <path d="M20 21a8 8 0 0 0-16 0"></path>
              <path d="M12 13V8"></path>
            </svg>
            <span class="nav-text">Loyalty Points</span>
          </div>
        </button>
      </div>

      <!-- Transactions -->
      <div class="nav-item">
        <button class="nav-button <?= service('uri')->getSegment(1) === 'loyalty-transactions' ? 'active' : '' ?>"
          onclick="window.location.href='<?= base_url('loyalty-transactions') ?>'" data-tooltip="Transactions">
          <div class="nav-button-content">
            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
              fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"></path>
              <path d="M12 8v4"></path>
              <path d="M12 16h.01"></path>
            </svg>
            <span class="nav-text">Loyalty Transactions</span>
          </div>
        </button>
      </div>

      <!-- Settings -->
      <div class="nav-item">
        <button class="nav-button <?= service('uri')->getSegment(1) === 'loyalty-settings' ? 'active' : '' ?>"
          onclick="window.location.href='<?= base_url('loyalty-settings') ?>'" data-tooltip="Loyalty Settings">
          <div class="nav-button-content">
            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
              fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="3"></circle>
              <path
                d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z">
              </path>
            </svg>
            <span class="nav-text">Loyalty Settings</span>
          </div>
        </button>
      </div>
      <button class="nav-button <?= service('uri')->getSegment(1) === 'reward-orders' ? 'active' : '' ?>"
        onclick="window.location.href='<?= base_url('reward-orders') ?>'" data-tooltip="Reward Orders">
        <div class="nav-button-content">
          <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 12h-4l-3 9L9 3l-3 9H2"></path>
          </svg>
          <span class="nav-text">Reward Orders</span>
        </div>
        <span id="reward-orders-badge" class="nav-badge" style="display: none;"></span>
      </button>
      <button class="nav-button <?= service('uri')->getSegment(1) === 'delivered-orders' ? 'active' : '' ?>"
        onclick="window.location.href='<?= base_url('delivered-orders') ?>'" data-tooltip="Delivered Orders">
        <div class="nav-button-content">
          <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 12h-4l-3 9L9 3l-3 9H2"></path>
          </svg>
          <span class="nav-text">Delivered Orders</span>
          <span id="delivered-orders-badge" class="nav-badge" style="display: none;"></span>
      </button>
=======
      <?php if (hasPermission('loyalty.view')) : ?>
        <div class="nav-section-title">Loyalty Management</div>

        <!-- Programs -->
        <div class="nav-item">
          <button class="nav-button <?= service('uri')->getSegment(1) === 'loyalty-programs' ? 'active' : '' ?>"
            onclick="window.location.href='<?= base_url('loyalty-programs') ?>'" data-tooltip="Programs">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"></path>
                <path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"></path>
                <path d="M4 22h16"></path>
                <path d="M10 14.66V17c0 .55.45 1 1 1h2c.55 0 1-.45 1-1v-2.34"></path>
                <path d="M18 22V5a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v17"></path>
              </svg>
              <span class="nav-text">Loyalty Programs</span>
            </div>
          </button>
        </div>

        <!-- Users with Points -->
        <div class="nav-item">
          <button class="nav-button <?= service('uri')->getSegment(1) === 'loyalty-admin' ? 'active' : '' ?>"
            onclick="window.location.href='<?= base_url('loyalty-admin') ?>'" data-tooltip="Point Holders">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="8" r="5"></circle>
                <path d="M20 21a8 8 0 0 0-16 0"></path>
                <path d="M12 13V8"></path>
              </svg>
              <span class="nav-text">Loyalty Points</span>
            </div>
          </button>
        </div>

        <!-- Transactions -->
        <div class="nav-item">
          <button class="nav-button <?= service('uri')->getSegment(1) === 'loyalty-transactions' ? 'active' : '' ?>"
            onclick="window.location.href='<?= base_url('loyalty-transactions') ?>'" data-tooltip="Transactions">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"></path>
                <path d="M12 8v4"></path>
                <path d="M12 16h.01"></path>
              </svg>
              <span class="nav-text">Loyalty Transactions</span>
            </div>
          </button>
        </div>

        <!-- Settings -->
        <div class="nav-item">
          <button class="nav-button <?= service('uri')->getSegment(1) === 'loyalty-settings' ? 'active' : '' ?>"
            onclick="window.location.href='<?= base_url('loyalty-settings') ?>'" data-tooltip="Loyalty Settings">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="3"></circle>
                <path
                  d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z">
                </path>
              </svg>
              <span class="nav-text">Loyalty Settings</span>
            </div>
          </button>
        </div>
        <button class="nav-button <?= service('uri')->getSegment(1) === 'reward-orders' ? 'active' : '' ?>"
          onclick="window.location.href='<?= base_url('reward-orders') ?>'" data-tooltip="Reward Orders">
          <div class="nav-button-content">
            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
              fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M22 12h-4l-3 9L9 3l-3 9H2"></path>
            </svg>
            <span class="nav-text">Reward Orders</span>
          </div>
          <span id="reward-orders-badge" class="nav-badge" style="display: none;"></span>
        </button>
        <button class="nav-button <?= service('uri')->getSegment(1) === 'delivered-orders' ? 'active' : '' ?>"
          onclick="window.location.href='<?= base_url('delivered-orders') ?>'" data-tooltip="Delivered Orders">
          <div class="nav-button-content">
            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
              fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M22 12h-4l-3 9L9 3l-3 9H2"></path>
            </svg>
            <span class="nav-text">Delivered Orders</span>
            <span id="delivered-orders-badge" class="nav-badge" style="display: none;"></span>
        </button>
      <?php endif; ?>
>>>>>>> origin/main


      <hr class="nav-divider">
      <!-- Notification Hub Section -->
      <div class="nav-section-title">Notification Hub</div>

      <div class="nav-item">
<<<<<<< HEAD
        <button class="nav-button <?= service('uri')->getSegment(1) === 'notification-manager' && !service('uri')->getSegment(2) ? 'active' : '' ?>"
          onclick="toggleDropdown('notifications-hub-dropdown')" data-tooltip="Notification Hub">
          <div class="nav-button-content">
            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
              fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="m3 11 18-5v12L3 13v-2z"></path>
              <path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"></path>
            </svg>
            <span class="nav-text">Notifications</span>
          </div>
          <svg class="nav-chevron" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="m6 9 6 6 6-6"></path>
          </svg>
        </button>
        <div id="notifications-hub-dropdown" class="sub-nav <?= service('uri')->getSegment(1) === 'notification-manager' ? 'open' : '' ?>">
          <button class="nav-button <?= service('uri')->getSegment(1) === 'notification-manager' && !service('uri')->getSegment(2) ? 'active' : '' ?>"
            onclick="window.location.href='<?= base_url('notification-manager') ?>'" data-tooltip="Dashboard">
            <span class="nav-text">Dashboard</span>
          </button>
          <button class="nav-button <?= service('uri')->getSegment(2) === 'templates' ? 'active' : '' ?>"
            onclick="window.location.href='<?= base_url('notification-manager/templates') ?>'" data-tooltip="Templates">
            <span class="nav-text">Templates</span>
          </button>
          <button class="nav-button <?= service('uri')->getSegment(2) === 'campaigns' ? 'active' : '' ?>"
            onclick="window.location.href='<?= base_url('notification-manager/campaigns') ?>'" data-tooltip="Campaigns">
            <span class="nav-text">Campaigns</span>
          </button>
          <button class="nav-button <?= service('uri')->getSegment(2) === 'logs' ? 'active' : '' ?>"
            onclick="window.location.href='<?= base_url('notification-manager/logs') ?>'" data-tooltip="Logs">
            <span class="nav-text">Logs</span>
          </button>
        </div>
      </div>

      <hr class="nav-divider">
      <!-- Statistics Section -->
      <div class="nav-section-title">Statistics</div>

      <!-- Customer Journey Funnel Analytics -->
      <div class="nav-item">
        <button class="nav-button <?= in_array(service('uri')->getSegment(1), ['customer-journey', 'analytics']) ? 'active' : '' ?>"
          onclick="window.location.href='<?= base_url('customer-journey') ?>'" data-tooltip="Customer Journey">
          <div class="nav-button-content">
            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
              fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
            </svg>
            <span class="nav-text">Customer Journey</span>
          </div>
        </button>
      </div>

      <!-- Client Statistics -->
      <div class="nav-item">
        <?php if (hasPermission('70')) : ?>
=======
        <?php if (hasPermission('notifications.campaigns')) : ?>
          <button class="nav-button <?= service('uri')->getSegment(1) === 'notification-manager' && !service('uri')->getSegment(2) ? 'active' : '' ?>"
            onclick="toggleDropdown('notifications-hub-dropdown')" data-tooltip="Notification Hub">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m3 11 18-5v12L3 13v-2z"></path>
                <path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"></path>
              </svg>
              <span class="nav-text">Notifications</span>
            </div>
            <svg class="nav-chevron" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
              fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="m6 9 6 6 6-6"></path>
            </svg>
          </button>
          <div id="notifications-hub-dropdown" class="sub-nav <?= service('uri')->getSegment(1) === 'notification-manager' ? 'open' : '' ?>">
            <button class="nav-button <?= service('uri')->getSegment(1) === 'notification-manager' && !service('uri')->getSegment(2) ? 'active' : '' ?>"
              onclick="window.location.href='<?= base_url('notification-manager') ?>'" data-tooltip="Dashboard">
              <span class="nav-text">Dashboard</span>
            </button>
            <button class="nav-button <?= service('uri')->getSegment(2) === 'templates' ? 'active' : '' ?>"
              onclick="window.location.href='<?= base_url('notification-manager/templates') ?>'" data-tooltip="Templates">
              <span class="nav-text">Templates</span>
            </button>
            <button class="nav-button <?= service('uri')->getSegment(2) === 'campaigns' ? 'active' : '' ?>"
              onclick="window.location.href='<?= base_url('notification-manager/campaigns') ?>'" data-tooltip="Campaigns">
              <span class="nav-text">Campaigns</span>
            </button>
            <button class="nav-button <?= service('uri')->getSegment(2) === 'logs' ? 'active' : '' ?>"
              onclick="window.location.href='<?= base_url('notification-manager/logs') ?>'" data-tooltip="Logs">
              <span class="nav-text">Logs</span>
            </button>
            <button class="nav-button <?= service('uri')->getSegment(2) === 'analytics' ? 'active' : '' ?>"
              onclick="window.location.href='<?= base_url('notification-manager/analytics') ?>'" data-tooltip="Analytics & SP Health">
              <span class="nav-text">Analytics ⭐</span>
            </button>
          </div>
        <?php else : ?>
          <button class="nav-button disabled-link" data-tooltip="Notification Hub">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m3 11 18-5v12L3 13v-2z"></path>
                <path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"></path>
              </svg>
              <span class="nav-text">Notifications</span>
            </div>
            <i class="fas fa-lock"></i>
          </button>
        <?php endif; ?>
      </div>

      <!-- Statistics Section -->
      <div class="nav-section-title">Statistics</div>

      <!-- Client Statistics -->
      <div class="nav-item">
        <?php if (hasPermission('statistics.client')) : ?>
>>>>>>> origin/main
          <button class="nav-button <?= service('uri')->getSegment(1) === 'client-statistics' ? 'active' : '' ?>"
            onclick="window.location.href='<?= base_url('client-statistics') ?>'" data-tooltip="Client Statistics">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path
                  d="M21 12c.552 0 1.005-.449.95-.998a10 10 0 0 0-8.953-8.951c-.55-.055-.998.398-.998.95v8a1 1 0 0 0 1 1z">
                </path>
                <path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path>
              </svg>
              <span class="nav-text">Client Statistics</span>
            </div>
          </button>
        <?php else : ?>
          <button class="nav-button disabled-link" data-tooltip="Client Statistics">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path
                  d="M21 12c.552 0 1.005-.449.95-.998a10 10 0 0 0-8.953-8.951c-.55-.055-.998.398-.998.95v8a1 1 0 0 0 1 1z">
                </path>
                <path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path>
              </svg>
              <span class="nav-text">Client Statistics</span>
            </div>
            <i class="fas fa-lock"></i>
          </button>
        <?php endif; ?>
      </div>

      <!-- SP Statistics -->
      <div class="nav-item">
<<<<<<< HEAD
        <?php if (hasPermission('71')) : ?>
=======
        <?php if (hasPermission('statistics.provider')) : ?>
>>>>>>> origin/main
          <button class="nav-button <?= service('uri')->getSegment(1) === 'provider-statistics' ? 'active' : '' ?>"
            onclick="window.location.href='<?= base_url('provider-statistics') ?>'" data-tooltip="SP Statistics">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path
                  d="M21 12c.552 0 1.005-.449.95-.998a10 10 0 0 0-8.953-8.951c-.55-.055-.998.398-.998.95v8a1 1 0 0 0 1 1z">
                </path>
                <path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path>
              </svg>
              <span class="nav-text">SP Statistics</span>
            </div>
          </button>
        <?php else : ?>
          <button class="nav-button disabled-link" data-tooltip="SP Statistics">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path
                  d="M21 12c.552 0 1.005-.449.95-.998a10 10 0 0 0-8.953-8.951c-.55-.055-.998.398-.998.95v8a1 1 0 0 0 1 1z">
                </path>
                <path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path>
              </svg>
              <span class="nav-text">SP Statistics</span>
            </div>
            <i class="fas fa-lock"></i>
          </button>
        <?php endif; ?>
      </div>

      <!-- Clients Segregation -->
      <div class="nav-item">
<<<<<<< HEAD
        <?php if (hasPermission('70')) : ?>
=======
        <?php if (hasPermission('statistics.client')) : ?>
>>>>>>> origin/main
          <button class="nav-button <?= service('uri')->getSegment(1) === 'client-list' ? 'active' : '' ?>"
            onclick="window.location.href='<?= base_url('client-list') ?>'" data-tooltip="Clients Segregation">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path
                  d="M21 12c.552 0 1.005-.449.95-.998a10 10 0 0 0-8.953-8.951c-.55-.055-.998.398-.998.95v8a1 1 0 0 0 1 1z">
                </path>
                <path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path>
              </svg>
              <span class="nav-text">Clients Segregation</span>
            </div>
          </button>
        <?php else : ?>
          <button class="nav-button disabled-link" data-tooltip="Clients Segregation">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path
                  d="M21 12c.552 0 1.005-.449.95-.998a10 10 0 0 0-8.953-8.951c-.55-.055-.998.398-.998.95v8a1 1 0 0 0 1 1z">
                </path>
                <path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path>
              </svg>
              <span class="nav-text">Clients Segregation</span>
            </div>
            <i class="fas fa-lock"></i>
          </button>
        <?php endif; ?>
      </div>

      <hr class="nav-divider">

      <!-- Finance Section -->
      <div class="nav-section-title">Finance</div>

      <!-- Provider Earnings -->
      <div class="nav-item">
<<<<<<< HEAD
        <?php if (hasPermission('67')) : ?>
=======
        <?php if (hasPermission('finance.sp_earnings')) : ?>
>>>>>>> origin/main
          <button class="nav-button <?= service('uri')->getSegment(1) === 'sp-earnings' ? 'active' : '' ?>"
            onclick="window.location.href='<?= base_url() ?>sp-earnings'" data-tooltip="Provider Earnings">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path
                  d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1">
                </path>
                <path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4"></path>
              </svg>
              <span class="nav-text">Provider Earnings</span>
            </div>
          </button>
        <?php else : ?>
          <button class="nav-button disabled-link" data-tooltip="Provider Earnings">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path
                  d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1">
                </path>
                <path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4"></path>
              </svg>
              <span class="nav-text">Provider Earnings</span>
            </div>
            <i class="fas fa-lock"></i>
          </button>
        <?php endif; ?>
      </div>

      <!-- Client Spendings -->
      <div class="nav-item">
<<<<<<< HEAD
        <?php if (hasPermission('64')) : ?>
=======
        <?php if (hasPermission('finance.client_spendings')) : ?>
>>>>>>> origin/main
          <button class="nav-button <?= service('uri')->getSegment(1) === 'client-spendings' ? 'active' : '' ?>"
            onclick="window.location.href='<?= base_url() ?>client-spendings'" data-tooltip="Client Spendings">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path
                  d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1">
                </path>
                <path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4"></path>
              </svg>
              <span class="nav-text">Client Spendings</span>
            </div>
          </button>
        <?php else : ?>
          <button class="nav-button disabled-link" data-tooltip="Client Spendings">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path
                  d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1">
                </path>
                <path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4"></path>
              </svg>
              <span class="nav-text">Client Spendings</span>
            </div>
            <i class="fas fa-lock"></i>
          </button>
        <?php endif; ?>
      </div>

      <!-- Providers Payouts -->
      <div class="nav-item">
<<<<<<< HEAD
        <?php if (hasPermission('63')) : ?>
=======
        <?php if (hasPermission('finance.sp_payments')) : ?>
>>>>>>> origin/main
          <button class="nav-button <?= service('uri')->getSegment(1) === 'providers-payments' ? 'active' : '' ?>"
            onclick="window.location.href='<?= base_url() ?>providers-payments'" data-tooltip="Providers Payouts">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path
                  d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1">
                </path>
                <path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4"></path>
              </svg>
              <span class="nav-text">Providers Payouts</span>
            </div>
            <span id="payout-badge" class="nav-badge" style="display: none;"></span>
          </button>
        <?php else : ?>
          <button class="nav-button disabled-link" data-tooltip="Providers Payouts">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path
                  d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1">
                </path>
                <path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4"></path>
              </svg>
              <span class="nav-text">Providers Payouts</span>
            </div>
            <i class="fas fa-lock"></i>
          </button>
        <?php endif; ?>
      </div>

<<<<<<< HEAD
      <!-- Company Earnings -->
      <div class="nav-item">
        <?php if (hasPermission('65')) : ?>
=======
      <!-- Call Analysis -->
      <!-- <div class="nav-item">
        <?php if (hasPermission('call_analysis.view')) : ?>
          <button class="nav-button <?= service('uri')->getSegment(1) === 'call-analysis' ? 'active' : '' ?>"
            onclick="window.location.href='<?= base_url() ?>call-analysis'" data-tooltip="Call Analysis">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 12h-4l-3 9L9 3l-3 9H2"></path>
              </svg>
              <span class="nav-text">Call Analysis</span>
            </div>
          </button>
        <?php else : ?>
          <button class="nav-button disabled-link" data-tooltip="Call Analysis">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 12h-4l-3 9L9 3l-3 9H2"></path>
              </svg>
              <span class="nav-text">Call Analysis</span>
            </div>
            <i class="fas fa-lock"></i>
          </button>
        <?php endif; ?>
      </div> -->

      <!-- Call Verification Executive Payouts -->
      <div class="nav-item">
        <?php if (hasPermission('verification.payouts')) : ?>
          <button class="nav-button <?= service('uri')->getSegment(1) === 'call-verification-payouts' ? 'active' : '' ?>"
            onclick="window.location.href='<?= base_url() ?>call-verification-payouts'" data-tooltip="Call Verification Payouts">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
              </svg>
              <span class="nav-text">Call Verification Payouts</span>
            </div>
            <span id="call-verification-payouts-badge" class="nav-badge" style="display: none;"></span>
          </button>
        <?php else : ?>
          <button class="nav-button disabled-link" data-tooltip="Call Verification Payouts">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
              </svg>
              <span class="nav-text">Call Verification Payouts</span>
            </div>
            <i class="fas fa-lock"></i>
          </button>
        <?php endif; ?>
      </div>

      <!-- Company Earnings -->
      <div class="nav-item">
        <?php if (hasPermission('finance.company_earnings')) : ?>
>>>>>>> origin/main
          <button class="nav-button <?= service('uri')->getSegment(1) === 'company-earnings' ? 'active' : '' ?>"
            onclick="window.location.href='<?= base_url() ?>company-earnings'" data-tooltip="Company Earnings">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path
                  d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1">
                </path>
                <path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4"></path>
              </svg>
              <span class="nav-text">Company Earnings</span>
            </div>
          </button>
        <?php else : ?>
          <button class="nav-button disabled-link" data-tooltip="Company Earnings">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path
                  d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1">
                </path>
                <path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4"></path>
              </svg>
              <span class="nav-text">Company Earnings</span>
            </div>
            <i class="fas fa-lock"></i>
          </button>
        <?php endif; ?>
      </div>

      <!-- Client Wallets -->
      <div class="nav-item">
<<<<<<< HEAD
        <?php if (hasPermission('74')) : ?>
=======
        <?php if (hasPermission('finance.client_wallets')) : ?>
>>>>>>> origin/main
          <button class="nav-button <?= service('uri')->getSegment(1) === 'client-wallets' ? 'active' : '' ?>"
            onclick="window.location.href='<?= base_url() ?>client-wallets'" data-tooltip="Client Wallets">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path
                  d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1">
                </path>
                <path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4"></path>
              </svg>
              <span class="nav-text">Client Wallets</span>
            </div>
            <span id="refund-badge" class="nav-badge" style="display: none;"></span>
          </button>
        <?php else : ?>
          <button class="nav-button disabled-link" data-tooltip="Client Wallets">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path
                  d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1">
                </path>
                <path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4"></path>
              </svg>
              <span class="nav-text">Client Wallets</span>
            </div>
            <i class="fas fa-lock"></i>
          </button>
        <?php endif; ?>
      </div>

      <!-- Payments -->
      <div class="nav-item">
<<<<<<< HEAD
        <?php if (hasPermission('75')) : ?>
=======
        <?php if (hasPermission('finance.payments')) : ?>
>>>>>>> origin/main
          <button class="nav-button <?= service('uri')->getSegment(1) === 'payments' ? 'active' : '' ?>"
            onclick="window.location.href='<?= base_url() ?>payments'" data-tooltip="Payments">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path
                  d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1">
                </path>
                <path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4"></path>
              </svg>
              <span class="nav-text">Payments</span>
            </div>
          </button>
        <?php else : ?>
          <button class="nav-button disabled-link" data-tooltip="Payments">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path
                  d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1">
                </path>
                <path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4"></path>
              </svg>
              <span class="nav-text">Payments</span>
            </div>
            <i class="fas fa-lock"></i>
          </button>
        <?php endif; ?>
      </div>

<<<<<<< HEAD
      <!-- Payments -->
      <div class="nav-item">
        <?php if (hasPermission('75')) : ?>
=======
      <!-- SP Payments -->
      <div class="nav-item">
        <?php if (hasPermission('finance.sp_payments')) : ?>
>>>>>>> origin/main
          <button class="nav-button <?= service('uri')->getSegment(1) === 'sp-payments' ? 'active' : '' ?>"
            onclick="window.location.href='<?= base_url() ?>sp-payments'" data-tooltip="SP Payments">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path
                  d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1">
                </path>
                <path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4"></path>
              </svg>
              <span class="nav-text">SP Payments</span>
            </div>
          </button>
        <?php else : ?>
          <button class="nav-button disabled-link" data-tooltip="SP Payments">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path
                  d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1">
                </path>
                <path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4"></path>
              </svg>
              <span class="nav-text">SP Payments</span>
            </div>
            <i class="fas fa-lock"></i>
          </button>
        <?php endif; ?>
      </div>

      <!-- Reconciliation -->
      <div class="nav-item">
<<<<<<< HEAD
        <button class="nav-button <?= service('uri')->getSegment(1) === 'recoinciliation' ? 'active' : '' ?>"
          onclick="window.location.href='<?= base_url() ?>recoinciliation'" data-tooltip="Reconciliation">
          <div class="nav-button-content">
            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
              fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path
                d="M21 12c.552 0 1.005-.449.95-.998a10 10 0 0 0-8.953-8.951c-.55-.055-.998.398-.998.95v8a1 1 0 0 0 1 1z">
              </path>
              <path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path>
            </svg>
            <span class="nav-text">Reconciliation</span>
          </div>
        </button>
=======
        <?php if (hasPermission('finance.reconciliation')) : ?>
          <button class="nav-button <?= service('uri')->getSegment(1) === 'recoinciliation' ? 'active' : '' ?>"
            onclick="window.location.href='<?= base_url() ?>recoinciliation'" data-tooltip="Reconciliation">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path
                  d="M21 12c.552 0 1.005-.449.95-.998a10 10 0 0 0-8.953-8.951c-.55-.055-.998.398-.998.95v8a1 1 0 0 0 1 1z">
                </path>
                <path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path>
              </svg>
              <span class="nav-text">Reconciliation</span>
            </div>
          </button>
        <?php else : ?>
          <button class="nav-button disabled-link" data-tooltip="Reconciliation">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path
                  d="M21 12c.552 0 1.005-.449.95-.998a10 10 0 0 0-8.953-8.951c-.55-.055-.998.398-.998.95v8a1 1 0 0 0 1 1z">
                </path>
                <path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path>
              </svg>
              <span class="nav-text">Reconciliation</span>
            </div>
            <i class="fas fa-lock"></i>
          </button>
        <?php endif; ?>
>>>>>>> origin/main
      </div>

      <hr class="nav-divider">

      <!-- Reports Section -->
      <div class="nav-section-title">Reports</div>

      <!-- Daily Bookings -->
      <div class="nav-item">
<<<<<<< HEAD
        <?php if (hasPermission('66')) : ?>
=======
        <?php if (hasPermission('finance.daily_bookings')) : ?>
>>>>>>> origin/main
          <button class="nav-button <?= service('uri')->getSegment(1) === 'daily-bookings' ? 'active' : '' ?>"
            onclick="window.location.href='<?= base_url() ?>daily-bookings'" data-tooltip="Daily Bookings">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M8 2v4"></path>
                <path d="M16 2v4"></path>
                <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                <path d="M3 10h18"></path>
              </svg>
              <span class="nav-text">Daily Bookings</span>
            </div>
          </button>
        <?php else : ?>
          <button class="nav-button disabled-link" data-tooltip="Daily Bookings">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M8 2v4"></path>
                <path d="M16 2v4"></path>
                <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                <path d="M3 10h18"></path>
              </svg>
              <span class="nav-text">Daily Bookings</span>
            </div>
            <i class="fas fa-lock"></i>
          </button>
        <?php endif; ?>
      </div>

      <hr class="nav-divider">

      <!-- Services Settings Section -->
      <div class="nav-section-title">Services Settings</div>

      <!-- Walking Services -->
      <div class="nav-item">
<<<<<<< HEAD
        <?php if (hasPermission('22')) : ?>
=======
        <?php if (hasPermission('bookings.walking')) : ?>
>>>>>>> origin/main
          <button class="nav-button <?=
                                    service('uri')->getSegment(1) === 'bookings' &&
                                      (service('uri')->getSegment(2) === 'addons' || service('uri')->getSegment(2) === 'packages') &&
                                      isset($_GET['type']) && $_GET['type'] === 'walking' ? 'active' : '' ?>"
            onclick="toggleDropdown('walking-dropdown')" data-tooltip="Walking">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="5" r="1"></circle>
                <path d="m9 20 3-6 3 6"></path>
                <path d="m6 8 6 2 6-2"></path>
                <path d="M12 10v4"></path>
              </svg>
              <span class="nav-text">Walking</span>
            </div>
            <svg class="nav-chevron" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
              fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="m6 9 6 6 6-6"></path>
            </svg>
          </button>
          <div id="walking-dropdown" class="sub-nav <?=
                                                    service('uri')->getSegment(1) === 'bookings' &&
                                                      (service('uri')->getSegment(2) === 'addons' || service('uri')->getSegment(2) === 'packages') &&
                                                      isset($_GET['type']) && $_GET['type'] === 'walking' ? 'open' : '' ?>">
            <button
              class="nav-button <?= (uri_string() === 'bookings/addons' && isset($_GET['type']) && $_GET['type'] === 'walking') ? 'active' : '' ?>"
              onclick="window.location.href='<?= base_url('bookings/addons?type=walking') ?>'"
              data-tooltip="Service Addons">
              <span class="nav-text">Service Addons</span>
            </button>
            <button
              class="nav-button <?= (uri_string() === 'bookings/packages' && isset($_GET['type']) && $_GET['type'] === 'walking') ? 'active' : '' ?>"
              onclick="window.location.href='<?= base_url() ?>bookings/packages?type=walking'" data-tooltip="Packages">
              <span class="nav-text">Packages</span>
            </button>
            <button
              class="nav-button <?= (uri_string() === 'providers' && isset($_GET['type']) && $_GET['type'] === 'walkers') ? 'active' : '' ?>"
              onclick="window.location.href='<?= base_url('providers?type=walkers') ?>'" data-tooltip="Walkers List">
              <span class="nav-text">Walkers List</span>
            </button>
          </div>
        <?php else : ?>
          <button class="nav-button disabled-link" data-tooltip="Walking">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="5" r="1"></circle>
                <path d="m9 20 3-6 3 6"></path>
                <path d="m6 8 6 2 6-2"></path>
                <path d="M12 10v4"></path>
              </svg>
              <span class="nav-text">Walking</span>
            </div>
            <i class="fas fa-lock"></i>
          </button>
        <?php endif; ?>
      </div>

      <!-- Grooming Services -->
      <div class="nav-item">
<<<<<<< HEAD
        <?php if (hasPermission('14')) : ?>
=======
        <?php if (hasPermission('bookings.grooming')) : ?>
>>>>>>> origin/main
          <button class="nav-button <?=
                                    (service('uri')->getSegment(1) === 'bookings' &&
                                      (service('uri')->getSegment(2) === 'addons' || service('uri')->getSegment(2) === 'packages') &&
                                      isset($_GET['type']) && $_GET['type'] === 'grooming') ||
                                      service('uri')->getSegment(1) === 'grooming' ? 'active' : '' ?>"
            onclick="toggleDropdown('grooming-dropdown')" data-tooltip="Grooming">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 2v8"></path>
                <path d="m4.93 10.93 1.41 1.41"></path>
                <path d="M2 18h2"></path>
                <path d="M20 18h2"></path>
                <path d="m19.07 10.93-1.41 1.41"></path>
                <path d="M22 22H2"></path>
                <path d="m16 6-4 4-4-4"></path>
                <path d="M16 18a4 4 0 0 0-8 0"></path>
              </svg>
              <span class="nav-text">Grooming</span>
            </div>
            <svg class="nav-chevron" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
              fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="m6 9 6 6 6-6"></path>
            </svg>
          </button>
          <div id="grooming-dropdown" class="sub-nav <?=
                                                      (service('uri')->getSegment(1) === 'bookings' &&
                                                        (service('uri')->getSegment(2) === 'addons' || service('uri')->getSegment(2) === 'packages') &&
                                                        isset($_GET['type']) && $_GET['type'] === 'grooming') ||
                                                        service('uri')->getSegment(1) === 'grooming' ? 'open' : '' ?>">
            <button
              class="nav-button <?= (uri_string() === 'booking/addons' && isset($_GET['type']) && $_GET['type'] === 'grooming') ? 'active' : '' ?>"
              onclick="window.location.href='<?= base_url('bookings/addons?type=grooming') ?>'"
              data-tooltip="Service Addons">
              <span class="nav-text">Service Addons</span>
            </button>
            <button
              class="nav-button <?= (uri_string() === 'booking/packages' && isset($_GET['type']) && $_GET['type'] === 'grooming') ? 'active' : '' ?>"
              onclick="window.location.href='<?= base_url() ?>bookings/packages?type=grooming'" data-tooltip="Packages">
              <span class="nav-text">Packages</span>
            </button>
            <button
              class="nav-button <?= (uri_string() === 'providers' && isset($_GET['type']) && $_GET['type'] === 'groomers') ? 'active' : '' ?>"
              onclick="window.location.href='<?= base_url('providers?type=groomers') ?>'" data-tooltip="Groomers List">
              <span class="nav-text">Groomers List</span>
            </button>
            <button class="nav-button <?= (uri_string() === 'grooming/disputes') ? 'active' : '' ?>"
              onclick="window.location.href='<?= base_url('grooming/disputes') ?>'" data-tooltip="Booking Disputes">
              <span class="nav-text">Booking Disputes</span>
            </button>
          </div>
        <?php else : ?>
          <button class="nav-button disabled-link" data-tooltip="Grooming">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 2v8"></path>
                <path d="m4.93 10.93 1.41 1.41"></path>
                <path d="M2 18h2"></path>
                <path d="M20 18h2"></path>
                <path d="m19.07 10.93-1.41 1.41"></path>
                <path d="M22 22H2"></path>
                <path d="m16 6-4 4-4-4"></path>
                <path d="M16 18a4 4 0 0 0-8 0"></path>
              </svg>
              <span class="nav-text">Grooming</span>
            </div>
            <i class="fas fa-lock"></i>
          </button>
        <?php endif; ?>
      </div>

      <!-- Training Services -->
      <div class="nav-item">
<<<<<<< HEAD
        <?php if (hasPermission('20')) : ?>
=======
        <?php if (hasPermission('bookings.training')) : ?>
>>>>>>> origin/main
          <button class="nav-button <?=
                                    service('uri')->getSegment(1) === 'bookings' &&
                                      (service('uri')->getSegment(2) === 'addons' || service('uri')->getSegment(2) === 'packages') &&
                                      isset($_GET['type']) && $_GET['type'] === 'training' ? 'active' : '' ?>"
            onclick="toggleDropdown('training-dropdown')" data-tooltip="Training">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
              </svg>
              <span class="nav-text">Training</span>
            </div>
            <svg class="nav-chevron" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
              fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="m6 9 6 6 6-6"></path>
            </svg>
          </button>
          <div id="training-dropdown" class="sub-nav <?=
                                                      service('uri')->getSegment(1) === 'bookings' &&
                                                        (service('uri')->getSegment(2) === 'addons' || service('uri')->getSegment(2) === 'packages') &&
                                                        isset($_GET['type']) && $_GET['type'] === 'training' ? 'open' : '' ?>">
            <button
              class="nav-button <?= (uri_string() === 'bookings/addons' && isset($_GET['type']) && $_GET['type'] === 'training') ? 'active' : '' ?>"
              onclick="window.location.href='<?= base_url('bookings/addons?type=training') ?>'"
              data-tooltip="Service Addons">
              <span class="nav-text">Service Addons</span>
            </button>
            <button
              class="nav-button <?= (uri_string() === 'bookings/packages' && isset($_GET['type']) && $_GET['type'] === 'training') ? 'active' : '' ?>"
              onclick="window.location.href='<?= base_url() ?>bookings/packages?type=training'" data-tooltip="Packages">
              <span class="nav-text">Packages</span>
            </button>
            <button
              class="nav-button <?= (uri_string() === 'providers' && isset($_GET['type']) && $_GET['type'] === 'trainers') ? 'active' : '' ?>"
              onclick="window.location.href='<?= base_url('providers?type=trainers') ?>'" data-tooltip="Trainers List">
              <span class="nav-text">Trainers List</span>
            </button>
          </div>
        <?php else : ?>
          <button class="nav-button disabled-link" data-tooltip="Training">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
              </svg>
              <span class="nav-text">Training</span>
            </div>
            <i class="fas fa-lock"></i>
          </button>
        <?php endif; ?>
      </div>

      <!-- Boarding Services -->
      <div class="nav-item">
<<<<<<< HEAD
        <?php if (hasPermission('6')) : ?>
=======
        <?php if (hasPermission('bookings.boarding')) : ?>
>>>>>>> origin/main
          <button class="nav-button <?=
                                    service('uri')->getSegment(1) === 'bookings' &&
                                      (service('uri')->getSegment(2) === 'addons' || service('uri')->getSegment(2) === 'packages') &&
                                      isset($_GET['type']) && $_GET['type'] === 'boarding' ? 'active' : '' ?>"
            onclick="toggleDropdown('boarding-dropdown')" data-tooltip="Boarding">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 9h18v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9Z"></path>
                <path d="m3 9 2.45-4.9A2 2 0 0 1 7.24 3h9.52a2 2 0 0 1 1.8 1.1L21 9"></path>
                <path d="M12 3v6"></path>
              </svg>
              <span class="nav-text">Boarding</span>
            </div>
            <svg class="nav-chevron" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
              fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="m6 9 6 6 6-6"></path>
            </svg>
          </button>
          <div id="boarding-dropdown" class="sub-nav <?=
                                                      service('uri')->getSegment(1) === 'bookings' &&
                                                        (service('uri')->getSegment(2) === 'addons' || service('uri')->getSegment(2) === 'packages') &&
                                                        isset($_GET['type']) && $_GET['type'] === 'boarding' ? 'open' : '' ?>">
            <button
              class="nav-button <?= (uri_string() === 'bookings/addons' && isset($_GET['type']) && $_GET['type'] === 'boarding') ? 'active' : '' ?>"
              onclick="window.location.href='<?= base_url('bookings/addons?type=boarding') ?>'"
              data-tooltip="Service Addons">
              <span class="nav-text">Service Addons</span>
            </button>
            <button
              class="nav-button <?= (uri_string() === 'bookings/packages' && isset($_GET['type']) && $_GET['type'] === 'boarding') ? 'active' : '' ?>"
              onclick="window.location.href='<?= base_url() ?>bookings/packages?type=boarding'" data-tooltip="Packages">
              <span class="nav-text">Packages</span>
            </button>
            <button
              class="nav-button <?= (uri_string() === 'providers' && isset($_GET['type']) && $_GET['type'] === 'boarders') ? 'active' : '' ?>"
              onclick="window.location.href='<?= base_url('providers?type=boarders') ?>'" data-tooltip="Boarders List">
              <span class="nav-text">Boarders List</span>
            </button>
          </div>
        <?php else : ?>
          <button class="nav-button disabled-link" data-tooltip="Boarding">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 9h18v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9Z"></path>
                <path d="m3 9 2.45-4.9A2 2 0 0 1 7.24 3h9.52a2 2 0 0 1 1.8 1.1L21 9"></path>
                <path d="M12 3v6"></path>
              </svg>
              <span class="nav-text">Boarding</span>
            </div>
            <i class="fas fa-lock"></i>
          </button>
        <?php endif; ?>
      </div>

      <!-- Veterinary Services -->
      <div class="nav-item">
<<<<<<< HEAD
        <?php if (hasPermission('17')) : ?>
=======
        <?php if (hasPermission('bookings.sitting')) : ?>
>>>>>>> origin/main
          <button class="nav-button" onclick="toggleDropdown('veterinary-dropdown')" data-tooltip="Veterinary">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M11 2v2"></path>
                <path d="M5 2v2"></path>
                <path d="M5 3H4a2 2 0 0 0-2 2v4a6 6 0 0 0 12 0V5a2 2 0 0 0-2-2h-1"></path>
                <path d="M8 15a6 6 0 0 0 12 0v-3"></path>
                <circle cx="20" cy="10" r="2"></circle>
              </svg>
              <span class="nav-text">Veterinary</span>
            </div>
            <svg class="nav-chevron" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
              fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="m6 9 6 6 6-6"></path>
            </svg>
          </button>
          <div id="veterinary-dropdown" class="sub-nav">
            <button class="nav-button" data-tooltip="Booking requests">
              <span class="nav-text">Booking requests</span>
            </button>
            <button class="nav-button" data-tooltip="Veterinarians List">
              <span class="nav-text">Veterinarians List</span>
            </button>
            <button class="nav-button" data-tooltip="Packages">
              <span class="nav-text">Packages</span>
            </button>
            <button class="nav-button" data-tooltip="Treatment Item list">
              <span class="nav-text">Treatment Item list</span>
            </button>
          </div>
        <?php else : ?>
          <button class="nav-button disabled-link" data-tooltip="Veterinary">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M11 2v2"></path>
                <path d="M5 2v2"></path>
                <path d="M5 3H4a2 2 0 0 0-2 2v4a6 6 0 0 0 12 0V5a2 2 0 0 0-2-2h-1"></path>
                <path d="M8 15a6 6 0 0 0 12 0v-3"></path>
                <circle cx="20" cy="10" r="2"></circle>
              </svg>
              <span class="nav-text">Veterinary</span>
            </div>
            <i class="fas fa-lock"></i>
          </button>
        <?php endif; ?>
      </div>

      <!-- Pet Types & Breeds -->
      <div class="nav-item">
<<<<<<< HEAD
        <?php if (hasPermission('76')) : ?>
=======
        <?php if (hasPermission('pets.view')) : ?>
>>>>>>> origin/main
          <button class="nav-button <?= service('uri')->getSegment(1) === 'pets' ? 'active' : '' ?>"
            onclick="window.location.href='<?= base_url() ?>pets'" data-tooltip="Pet Types & Breeds">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M11.25 16.25h1.5L12 17z"></path>
                <path d="M16 14v.5"></path>
                <path
                  d="M4.42 11.247A13.152 13.152 0 0 0 4 14.556C4 18.728 7.582 21 12 21s8-2.272 8-6.444a11.702 11.702 0 0 0-.493-3.309">
                </path>
                <path d="M8 14v.5"></path>
                <path
                  d="M8.5 8.5c-.384 1.05-1.083 2.028-2.344 2.5-1.931.722-3.576-.297-3.656-1-.113-.994 1.177-6.53 4-7 1.923-.321 3.651.845 3.651 2.235A7.497 7.497 0 0 1 14 5.277c0-1.39 1.844-2.598 3.767-2.277 2.823.47 4.113 6.006 4 7-.08.703-1.725 1.722-3.656 1-1.261-.472-1.855-1.45-2.239-2.5">
                </path>
              </svg>
              <span class="nav-text">Pet Types & Breeds</span>
            </div>
          </button>
        <?php else : ?>
          <button class="nav-button disabled-link" data-tooltip="Pet Types & Breeds">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M11.25 16.25h1.5L12 17z"></path>
                <path d="M16 14v.5"></path>
                <path
                  d="M4.42 11.247A13.152 13.152 0 0 0 4 14.556C4 18.728 7.582 21 12 21s8-2.272 8-6.444a11.702 11.702 0 0 0-.493-3.309">
                </path>
                <path d="M8 14v.5"></path>
                <path
                  d="M8.5 8.5c-.384 1.05-1.083 2.028-2.344 2.5-1.931.722-3.576-.297-3.656-1-.113-.994 1.177-6.53 4-7 1.923-.321 3.651.845 3.651 2.235A7.497 7.497 0 0 1 14 5.277c0-1.39 1.844-2.598 3.767-2.277 2.823.47 4.113 6.006 4 7-.08.703-1.725 1.722-3.656 1-1.261-.472-1.855-1.45-2.239-2.5">
                </path>
              </svg>
              <span class="nav-text">Pet Types & Breeds</span>
            </div>
            <i class="fas fa-lock"></i>
          </button>
        <?php endif; ?>
      </div>

      <hr class="nav-divider">

<<<<<<< HEAD
      <!-- Release Management Section -->
      <?php if (hasPermission('release_management.view', session()->get('user_role'))): ?>
      <div class="nav-section-title">Release Management</div>
      <div class="nav-item">
        <button class="nav-button <?= service('uri')->getSegment(1) === 'releases' && service('uri')->getSegment(2) === '' ? 'active' : '' ?>"
          onclick="window.location.href='<?= base_url() ?>releases'" data-tooltip="Releases">
          <div class="nav-button-content">
            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
              fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"></path>
              <path d="m12 15-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 0 1-4 2z"></path>
              <path d="M9 12H4s.55-3.03 2-4c1.62-1.08 5 0 5 0"></path>
              <path d="M12 15v5s3.03-.55 4-2c1.08-1.62 0-5 0-5"></path>
            </svg>
            <span class="nav-text">Dashboard</span>
          </div>
        </button>

        <button class="nav-button <?= service('uri')->getSegment(1) === 'releases' && service('uri')->getSegment(2) === 'create' ? 'active' : '' ?>"
          onclick="window.location.href='<?= base_url() ?>releases/create'" data-tooltip="New Version">
          <div class="nav-button-content">
            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
              fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10"></circle>
              <line x1="12" y1="8" x2="12" y2="16"></line>
              <line x1="8" y1="12" x2="16" y2="12"></line>
            </svg>
            <span class="nav-text">New Version</span>
          </div>
        </button>
      </div>
      <?php endif; ?>

      <hr class="nav-divider">

      <!-- System Settings Section -->
      <div class="nav-section-title">System Settings</div>
      <div class="nav-item">
        <?php if (hasPermission('46')) : ?>
=======
      <!-- System Settings Section -->
      <div class="nav-section-title">System Settings</div>
      <div class="nav-item">
        <?php if (hasPermission('verification.questions')) : ?>
>>>>>>> origin/main
          <button class="nav-button <?= service('uri')->getSegment(1) === 'verification-questions' ? 'active' : '' ?>"
            onclick="window.location.href='<?= base_url() ?>verification-questions'" data-tooltip="Notifications">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"></path>
                <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"></path>
              </svg>
              <span class="nav-text">Interview Questions</span>
            </div>
          </button>
        <?php else : ?>
          <button class="nav-button disabled-link" data-tooltip="Notifications">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"></path>
                <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"></path>
              </svg>
              <span class="nav-text">Notifications</span>
            </div>
            <i class="fas fa-lock"></i>
          </button>
        <?php endif; ?>
      </div>
<<<<<<< HEAD
      <!-- System Tasks -->
      <div class="nav-item">
        <button class="nav-button <?= service('uri')->getSegment(1) === 'tasks' ? 'active' : '' ?>"
          onclick="window.location.href='<?= base_url() ?>tasks'" data-tooltip="System Tasks">
          <div class="nav-button-content">
            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
              fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"></path>
              <polyline points="14 2 14 8 20 8"></polyline>
              <path d="M16 13H8"></path>
              <path d="M16 17H8"></path>
              <path d="M10 9H8"></path>
            </svg>
            <span class="nav-text">System Tasks</span>
=======

      <!-- Activity & Audit Logs -->
      <div class="nav-item">
        <?php if (hasPermission('audit_logs.view') || hasPermission('employees.view')) : ?>
          <button class="nav-button <?= service('uri')->getSegment(1) === 'audit-logs' ? 'active' : '' ?>"
            onclick="window.location.href='<?= base_url() ?>audit-logs'" data-tooltip="Activity Logs">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="16" y1="13" x2="8" y2="13"></line>
                <line x1="16" y1="17" x2="8" y2="17"></line>
                <polyline points="10 9 9 9 8 9"></polyline>
              </svg>
              <span class="nav-text">Activity Logs</span>
            </div>
          </button>
        <?php endif; ?>
      </div>
      <!-- System Tasks -->
      <?php if (hasPermission('tasks.view')) : ?>
        <div class="nav-item">
          <button class="nav-button <?= service('uri')->getSegment(1) === 'tasks' ? 'active' : '' ?>"
            onclick="window.location.href='<?= base_url() ?>tasks'" data-tooltip="System Tasks">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <path d="M16 13H8"></path>
                <path d="M16 17H8"></path>
                <path d="M10 9H8"></path>
              </svg>
              <span class="nav-text">System Tasks</span>
            </div>
          </button>
        </div>
      <?php endif; ?>

      <!-- Version Releases -->
      <div class="nav-item">
        <button class="nav-button <?= service('uri')->getSegment(1) === 'version-releases' ? 'active' : '' ?>"
          onclick="window.location.href='<?= base_url() ?>version-releases'" data-tooltip="Version Releases">
          <div class="nav-button-content">
            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
              fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"></path>
              <path d="m12 15-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 0 1-4 2z"></path>
              <path d="M9 12H4s.55-3.03 2-4c1.62-1.08 5 0 5 0"></path>
              <path d="M12 15v5s3.03-.55 4-2c1.08-1.62 0-5 0-5"></path>
            </svg>
            <span class="nav-text">Version Releases</span>
>>>>>>> origin/main
          </div>
        </button>
      </div>

      <!-- Service States -->
<<<<<<< HEAD
      <div class="nav-item">
        <button class="nav-button <?= service('uri')->getSegment(1) === 'servicestates' ? 'active' : '' ?>"
          onclick="window.location.href='<?= base_url() ?>servicestates'" data-tooltip="Service States">
          <div class="nav-button-content">
            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
              fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M12 2v8"></path>
              <path d="m4.93 10.93 1.41 1.41"></path>
              <path d="M2 18h2"></path>
              <path d="M20 18h2"></path>
              <path d="m19.07 10.93-1.41 1.41"></path>
              <path d="M22 22H2"></path>
              <path d="m16 6-4 4-4-4"></path>
              <path d="M16 18a4 4 0 0 0-8 0"></path>
            </svg>
            <span class="nav-text">Service States</span>
          </div>
        </button>
      </div>

      <!-- Notifications -->
      <div class="nav-item">
        <?php if (hasPermission('46')) : ?>
=======
      <?php if (hasPermission('service_states.view')) : ?>
        <div class="nav-item">
          <button class="nav-button <?= service('uri')->getSegment(1) === 'servicestates' ? 'active' : '' ?>"
            onclick="window.location.href='<?= base_url() ?>servicestates'" data-tooltip="Service States">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 2v8"></path>
                <path d="m4.93 10.93 1.41 1.41"></path>
                <path d="M2 18h2"></path>
                <path d="M20 18h2"></path>
                <path d="m19.07 10.93-1.41 1.41"></path>
                <path d="M22 22H2"></path>
                <path d="m16 6-4 4-4-4"></path>
                <path d="M16 18a4 4 0 0 0-8 0"></path>
              </svg>
              <span class="nav-text">Service States</span>
            </div>
          </button>
        </div>
      <?php endif; ?>

      <!-- Notifications -->
      <!-- <div class="nav-item">
        <?php if (hasPermission('notifications.view')) : ?>
>>>>>>> origin/main
          <button class="nav-button <?= service('uri')->getSegment(1) === 'notifications' ? 'active' : '' ?>"
            onclick="window.location.href='<?= base_url() ?>notifications'" data-tooltip="Notifications">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"></path>
                <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"></path>
              </svg>
              <span class="nav-text">Notifications</span>
            </div>
          </button>
        <?php else : ?>
          <button class="nav-button disabled-link" data-tooltip="Notifications">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"></path>
                <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"></path>
              </svg>
              <span class="nav-text">Notifications</span>
            </div>
            <i class="fas fa-lock"></i>
          </button>
        <?php endif; ?>
<<<<<<< HEAD
      </div>

      <!-- Permissions -->
      <div class="nav-item">
        <?php if (hasPermission('46')) : ?>
=======
      </div> -->

      <!-- Permissions -->
      <div class="nav-item">
        <?php if (hasPermission('permissions.view')) : ?>
>>>>>>> origin/main
          <button class="nav-button <?= service('uri')->getSegment(1) === 'permissions' ? 'active' : '' ?>"
            onclick="window.location.href='<?= base_url() ?>permissions'" data-tooltip="Permissions">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect width="18" height="11" x="3" y="11" rx="2" ry="2"></rect>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
              </svg>
              <span class="nav-text">Permissions</span>
            </div>
          </button>
        <?php else : ?>
          <button class="nav-button disabled-link" data-tooltip="Permissions">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect width="18" height="11" x="3" y="11" rx="2" ry="2"></rect>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
              </svg>
              <span class="nav-text">Permissions</span>
            </div>
            <i class="fas fa-lock"></i>
          </button>
        <?php endif; ?>
      </div>

      <!-- Banners -->
      <div class="nav-item">
<<<<<<< HEAD
        <?php if (hasPermission('46')) : ?>
=======
        <?php if (hasPermission('banners.view')) : ?>
>>>>>>> origin/main
          <button class="nav-button <?= service('uri')->getSegment(1) === 'banners' ? 'active' : '' ?>"
            onclick="window.location.href='<?= base_url() ?>banners'" data-tooltip="Banners">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect width="20" height="16" x="2" y="4" rx="2"></rect>
                <path d="M6 8h.01"></path>
                <path d="M10 8h.01"></path>
                <path d="M14 8h.01"></path>
              </svg>
              <span class="nav-text">Banners</span>
            </div>
          </button>
        <?php else : ?>
          <button class="nav-button disabled-link" data-tooltip="Banners">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect width="20" height="16" x="2" y="4" rx="2"></rect>
                <path d="M6 8h.01"></path>
                <path d="M10 8h.01"></path>
                <path d="M14 8h.01"></path>
              </svg>
              <span class="nav-text">Banners</span>
            </div>
            <i class="fas fa-lock"></i>
          </button>
        <?php endif; ?>
      </div>

      <!-- Events -->
      <div class="nav-item">
<<<<<<< HEAD
        <?php if (hasPermission('46')) : ?>
=======
        <?php if (hasPermission('events.view')) : ?>
>>>>>>> origin/main
          <button class="nav-button <?= service('uri')->getSegment(1) === 'events' ? 'active' : '' ?>"
            onclick="window.location.href='<?= base_url() ?>events'" data-tooltip="Events">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect width="18" height="18" x="3" y="4" rx="2" ry="2"></rect>
                <line x1="16" x2="16" y1="2" y2="6"></line>
                <line x1="8" x2="8" y1="2" y2="6"></line>
                <line x1="3" x2="21" y1="10" y2="10"></line>
              </svg>
              <span class="nav-text">Events</span>
            </div>
          </button>
        <?php else : ?>
          <button class="nav-button disabled-link" data-tooltip="Events">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect width="18" height="18" x="3" y="4" rx="2" ry="2"></rect>
                <line x1="16" x2="16" y1="2" y2="6"></line>
                <line x1="8" x2="8" y1="2" y2="6"></line>
                <line x1="3" x2="21" y1="10" y2="10"></line>
              </svg>
              <span class="nav-text">Events</span>
            </div>
            <i class="fas fa-lock"></i>
          </button>
        <?php endif; ?>
      </div>

      <!-- Tutorials -->
      <div class="nav-item">
<<<<<<< HEAD
        <?php if (hasPermission('79')) : ?>
=======
        <?php if (hasPermission('tutorials.view')) : ?>
>>>>>>> origin/main
          <button class="nav-button <?= service('uri')->getSegment(1) === 'tutorials' ? 'active' : '' ?>"
            onclick="window.location.href='<?= base_url() ?>tutorials'" data-tooltip="Tutorials">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
              </svg>
              <span class="nav-text">Tutorials</span>
            </div>
          </button>
        <?php else : ?>
          <button class="nav-button disabled-link" data-tooltip="Tutorials">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
              </svg>
              <span class="nav-text">Tutorials</span>
            </div>
            <i class="fas fa-lock"></i>
          </button>
        <?php endif; ?>

        <!-- AI Business Analyst Sidebar Link -->
<<<<<<< HEAD
        <a href="<?= base_url('ai-analyst') ?>" class="nav-button <?= (current_url() == base_url('ai-analyst')) ? 'active' : '' ?>" style="text-decoration: none;">
          <div class="nav-button-content">
            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
              fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
              <line x1="8" y1="21" x2="16" y2="21"></line>
              <line x1="12" y1="17" x2="12" y2="21"></line>
              <polyline points="7 10 10 7 13 10 17 6"></polyline>
            </svg>
            <span class="nav-text">AI Analyst</span>
          </div>
        </a>
=======
        <?php if (hasPermission('ai_analyst.view')) : ?>
          <a href="<?= base_url('ai-analyst') ?>" class="nav-button <?= (current_url() == base_url('ai-analyst')) ? 'active' : '' ?>" style="text-decoration: none;">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                <line x1="8" y1="21" x2="16" y2="21"></line>
                <line x1="12" y1="17" x2="12" y2="21"></line>
                <polyline points="7 10 10 7 13 10 17 6"></polyline>
              </svg>
              <span class="nav-text">AI Analyst</span>
            </div>
          </a>
        <?php else : ?>
          <button class="nav-button disabled-link" data-tooltip="AI Analyst">
            <div class="nav-button-content">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                <line x1="8" y1="21" x2="16" y2="21"></line>
                <line x1="12" y1="17" x2="12" y2="21"></line>
                <polyline points="7 10 10 7 13 10 17 6"></polyline>
              </svg>
              <span class="nav-text">AI Analyst</span>
            </div>
            <i class="fas fa-lock"></i>
          </button>
        <?php endif; ?>
>>>>>>> origin/main
      </div>
    </div>


  </aside>

  <!-- Main Content -->
  <main style="margin-left: 16rem;">
    <?= $this->renderSection('content') ?>
  </main>

  <!-- <script>
    window.omnidimensionConfig = {
      metadata: {
        user_id: "271"
      }
    };
  </script>
  <script
    id="omnidimension-web-widget"
    async
    src="https://omnidim.io/web_widget.js?secret_key=cc7096796292801e2941c5e38da370ca">
  </script> -->

<<<<<<< HEAD
  <!-- JavaScript for Sidebar & Theme Functionality -->
  <script>
    // Global Dark / Light Theme Engine
    function applyTheme(theme) {
      const isDark = theme === 'dark';
      if (isDark) {
        document.documentElement.classList.add('dark');
        document.body && document.body.classList.add('dark');
        document.documentElement.setAttribute('data-bs-theme', 'dark');
        document.documentElement.setAttribute('data-theme', 'dark');
      } else {
        document.documentElement.classList.remove('dark');
        document.body && document.body.classList.remove('dark');
        document.documentElement.setAttribute('data-bs-theme', 'light');
        document.documentElement.setAttribute('data-theme', 'light');
      }

      // Update navbar toggle icons
      document.querySelectorAll('.theme-icon-moon').forEach(el => {
        el.style.display = isDark ? 'none' : 'inline-block';
      });
      document.querySelectorAll('.theme-icon-sun').forEach(el => {
        el.style.display = isDark ? 'inline-block' : 'none';
      });

      // Update dropdown switch checkbox
      const switchCb = document.getElementById('themeSwitchCheckbox');
      if (switchCb) {
        switchCb.checked = isDark;
      }

      try {
        localStorage.setItem('petsfolio_theme', theme);
      } catch (e) {}
    }

    function toggleTheme() {
      const currentTheme = localStorage.getItem('petsfolio_theme') || (document.documentElement.classList.contains('dark') ? 'dark' : 'light');
      const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
      applyTheme(newTheme);
    }

    // Apply on DOM load
    document.addEventListener('DOMContentLoaded', function() {
      const savedTheme = localStorage.getItem('petsfolio_theme') || (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
      applyTheme(savedTheme);
    });

    // Sync across tabs
    window.addEventListener('storage', function(e) {
      if (e.key === 'petsfolio_theme' && e.newValue) {
        applyTheme(e.newValue);
      }
    });

=======
  <!-- JavaScript for Sidebar Functionality -->
  <script>
>>>>>>> origin/main
    function toggleSidebar() {
      const sidebar = document.querySelector('.sidebar-container');
      sidebar.classList.toggle('collapsed');

      // Update main content margin
      const mainContent = document.querySelector('main');
      if (sidebar.classList.contains('collapsed')) {
        mainContent.style.marginLeft = '4rem';
      } else {
        mainContent.style.marginLeft = '16rem';
      }
    }

    function toggleDropdown(dropdownId) {
      const dropdown = document.getElementById(dropdownId);
<<<<<<< HEAD
      const button = dropdown.previousElementSibling;
      const chevron = button.querySelector('.nav-chevron');

      dropdown.classList.toggle('open');

      if (dropdown.classList.contains('open')) {
        chevron.style.transform = 'rotate(180deg)';
        button.classList.add('active');
      } else {
        chevron.style.transform = 'rotate(0deg)';
        button.classList.remove('active');
      }
    }

    // Auto-open dropdowns and highlight active items on page load
    document.addEventListener('DOMContentLoaded', function() {
      // Auto-open dropdowns that contain active items
=======
      if (!dropdown) return;
      const button = dropdown.previousElementSibling;
      const chevron = button ? button.querySelector('.nav-chevron') : null;

      dropdown.classList.toggle('open');

      let openDropdowns = [];
      try {
        openDropdowns = JSON.parse(sessionStorage.getItem('sidebar_open_dropdowns') || '[]');
      } catch (e) {}

      if (dropdown.classList.contains('open')) {
        if (chevron) chevron.style.transform = 'rotate(180deg)';
        if (button) button.classList.add('active');
        if (!openDropdowns.includes(dropdownId)) {
          openDropdowns.push(dropdownId);
        }
      } else {
        if (chevron) chevron.style.transform = 'rotate(0deg)';
        if (button) button.classList.remove('active');
        openDropdowns = openDropdowns.filter(id => id !== dropdownId);
      }

      sessionStorage.setItem('sidebar_open_dropdowns', JSON.stringify(openDropdowns));
    }

    // Auto-open dropdowns, highlight active items, and preserve sticky sidebar scroll position
    document.addEventListener('DOMContentLoaded', function() {
      const sidebarContent = document.querySelector('.sidebar-content');

      // 1. Reopen dropdowns saved in sessionStorage
      try {
        const openDropdowns = JSON.parse(sessionStorage.getItem('sidebar_open_dropdowns') || '[]');
        openDropdowns.forEach(id => {
          const dd = document.getElementById(id);
          if (dd && dd.classList.contains('sub-nav')) {
            dd.classList.add('open');
            const parentButton = dd.previousElementSibling;
            if (parentButton && parentButton.classList.contains('nav-button')) {
              parentButton.classList.add('active');
              const chevron = parentButton.querySelector('.nav-chevron');
              if (chevron) chevron.style.transform = 'rotate(180deg)';
            }
          }
        });
      } catch (e) {}

      // 2. Auto-open dropdowns that contain active items
>>>>>>> origin/main
      const activeButtons = document.querySelectorAll('.nav-button.active');
      activeButtons.forEach(button => {
        const dropdown = button.nextElementSibling;
        if (dropdown && dropdown.classList.contains('sub-nav')) {
          dropdown.classList.add('open');
          const parentButton = button.parentElement.previousElementSibling;
          if (parentButton && parentButton.classList.contains('nav-button')) {
            parentButton.classList.add('active');
            const chevron = parentButton.querySelector('.nav-chevron');
            if (chevron) chevron.style.transform = 'rotate(180deg)';
          }
        }
      });

      // Also check for active parent buttons
      document.querySelectorAll('.sub-nav').forEach(dropdown => {
        const activeChild = dropdown.querySelector('.nav-button.active');
        if (activeChild) {
          dropdown.classList.add('open');
          const parentButton = dropdown.previousElementSibling;
          if (parentButton && parentButton.classList.contains('nav-button')) {
            parentButton.classList.add('active');
            const chevron = parentButton.querySelector('.nav-chevron');
            if (chevron) chevron.style.transform = 'rotate(180deg)';
          }
        }
      });

<<<<<<< HEAD
=======
      // 3. Make sidebar sticky and preserve scroll position on click & navigation
      if (sidebarContent) {
        const savedScroll = sessionStorage.getItem('sidebar_scroll_top');
        if (savedScroll !== null) {
          sidebarContent.scrollTop = parseInt(savedScroll, 10);
        } else {
          // Scroll active item into view if no saved scroll position
          const activeItem = sidebarContent.querySelector('.nav-button.active');
          if (activeItem) {
            activeItem.scrollIntoView({
              block: 'center',
              behavior: 'instant'
            });
          }
        }

        // Keep scroll position updated on scroll
        sidebarContent.addEventListener('scroll', function() {
          sessionStorage.setItem('sidebar_scroll_top', sidebarContent.scrollTop);
        }, {
          passive: true
        });

        // Save scroll position immediately on clicking any navigation button
        document.querySelectorAll('.sidebar-container .nav-button').forEach(button => {
          button.addEventListener('click', function() {
            sessionStorage.setItem('sidebar_scroll_top', sidebarContent.scrollTop);
          });
        });
      }

>>>>>>> origin/main
      // Set up direct navigation for non-dropdown buttons
      document.querySelectorAll('.nav-button:not([onclick*="toggleDropdown"])').forEach(button => {
        if (!button.onclick) {
          const link = button.getAttribute('onclick');
          if (link && link.includes('window.location.href')) {
            // Already has onclick handler
          } else {
            button.addEventListener('click', function(e) {
              if (!this.classList.contains('disabled-link')) {
                const url = this.getAttribute('data-url') || this.querySelector('a')?.href;
<<<<<<< HEAD
                if (url) window.location.href = url;
=======
                if (url) {
                  if (e.ctrlKey || e.metaKey || e.button === 1) {
                    window.open(url, '_blank');
                  } else {
                    window.location.href = url;
                  }
                }
>>>>>>> origin/main
              }
            });
          }
        }
      });

      // Make sure chevrons are properly rotated for open dropdowns
      document.querySelectorAll('.sub-nav.open').forEach(dropdown => {
        const parentButton = dropdown.previousElementSibling;
        if (parentButton && parentButton.classList.contains('nav-button')) {
          const chevron = parentButton.querySelector('.nav-chevron');
          if (chevron) chevron.style.transform = 'rotate(180deg)';
        }
      });
    });

<<<<<<< HEAD
=======
    // Handle Ctrl + Click, Cmd + Click, or Middle Click on any sidebar nav button to open in a NEW TAB
    function handleNavNewTab(e) {
      const navBtn = e.target.closest('.nav-button:not([onclick*="toggleDropdown"])');
      if (!navBtn || navBtn.classList.contains('disabled-link')) return;

      if (e.ctrlKey || e.metaKey || e.button === 1) {
        let targetUrl = navBtn.getAttribute('data-url') || navBtn.getAttribute('data-href') || navBtn.getAttribute('href');
        if (!targetUrl) {
          const onclickStr = navBtn.getAttribute('onclick') || '';
          const match = onclickStr.match(/(?:window\.location\.href|location\.href)\s*=\s*['"]([^'"]+)['"]/);
          if (match && match[1]) {
            targetUrl = match[1];
          }
        }
        if (targetUrl) {
          e.preventDefault();
          e.stopPropagation();
          e.stopImmediatePropagation();
          window.open(targetUrl, '_blank');
          return false;
        }
      }
    }

    // Capture phase event listeners to intercept navigation before inline onclick executes
    document.addEventListener('click', handleNavNewTab, true);
    document.addEventListener('auxclick', handleNavNewTab, true);

>>>>>>> origin/main
    // Close dropdowns when clicking outside on mobile
    document.addEventListener('click', function(e) {
      if (window.innerWidth <= 768) {
        const sidebar = document.querySelector('.sidebar-container');
<<<<<<< HEAD
        if (!sidebar.contains(e.target) && sidebar.classList.contains('open')) {
=======
        if (sidebar && !sidebar.contains(e.target) && sidebar.classList.contains('open')) {
>>>>>>> origin/main
          sidebar.classList.remove('open');
        }
      }
    });

    // Fetch sidebar counts dynamically to avoid slow initial page load
<<<<<<< HEAD
    // async function fetchSidebarCounts() {
    //   try {
    //     const response = await fetch('<?= base_url() ?>getSidebarCounts');
    //     if (!response.ok) return;
    //     const counts = await response.json();

    //     // updateBadge('booking-parent-badge', counts.bookingRequests);
    //     updateBadge('new-requests-badge', counts.bookingRequests);
    //     updateBadge('active-bookings-badge', counts.Confirmedbookings);
    //     updateBadge('on-hold-badge', counts.Haltedbookings);
    //     updateBadge('no-show-badge', counts.missedwalks);
    //     updateBadge('completed-bookings-badge', counts.Completedbookings);
    //     updateBadge('cancelled-bookings-badge', counts.Cancelledbookings);
    //     updateBadge('deleted-bookings-badge', counts.Deletedbookings);
    //     updateBadge('client-support-badge', counts.clsupports);
    //     updateBadge('sp-support-badge', counts.spsupports);
    //     updateBadge('payout-badge', counts.providerPayouts);
    //     updateBadge('refund-badge', counts.refundRequests);
    //     updateBadge('reward-orders-badge', counts.pending_rewards);
    //     updateBadge('delivered-orders-badge', counts.delivered_rewards);

    //   } catch (error) {
    //     console.error('Error fetching sidebar counts:', error);
    //   }

    //   // Fetch Loyalty Counts
    //   // try {
    //   //   const response = await fetch('<?= base_url() ?>loyalty-admin/getCounts');
    //   //   if (response.ok) {
    //   //     const lCounts = await response.json();
    //   //     updateBadge('reward-orders-badge', lCounts.pending);
    //   //     updateBadge('delivered-orders-badge', lCounts.delivered);
    //   //     updateBadge('loyalty-parent-badge', lCounts.pending);
    //   //   }
    //   // } catch (error) {
    //   //   console.error('Error fetching loyalty counts:', error);
    //   // }
    // }
=======
    async function fetchSidebarCounts() {
      try {
        const response = await fetch('<?= base_url('getSidebarCounts') ?>');
        if (!response.ok) return;
        const counts = await response.json();

        updateBadge('booking-parent-badge', (counts.bookingRequests || 0) + (counts.Confirmedbookings || 0));
        updateBadge('new-requests-badge', counts.bookingRequests);
        updateBadge('active-bookings-badge', counts.Confirmedbookings);
        updateBadge('on-hold-badge', counts.Haltedbookings);
        updateBadge('no-show-badge', counts.missedwalks);
        updateBadge('completed-bookings-badge', counts.Completedbookings);
        updateBadge('cancelled-bookings-badge', counts.Cancelledbookings);
        updateBadge('deleted-bookings-badge', counts.Deletedbookings);
        updateBadge('client-support-badge', counts.clsupports);
        updateBadge('sp-support-badge', counts.spsupports);
        updateBadge('payout-badge', counts.providerPayouts);
        updateBadge('call-verification-payouts-badge', counts.callVerificationPayouts);
        updateBadge('agreements-badge', counts.agreementsCount);
        updateBadge('refund-badge', counts.refundRequests);
        updateBadge('reward-orders-badge', counts.pending_rewards);
        updateBadge('delivered-orders-badge', counts.delivered_rewards);

      } catch (error) {
        console.error('Error fetching sidebar counts:', error);
      }
    }
>>>>>>> origin/main

    function updateBadge(id, count) {
      const badge = document.getElementById(id);
      if (badge) {
        if (count > 0) {
          badge.textContent = count;
          badge.style.display = 'inline-block';
        } else {
          badge.style.display = 'none';
        }
      }
    }

    // Call fetchSidebarCounts on page load
<<<<<<< HEAD
    // document.addEventListener('DOMContentLoaded', fetchSidebarCounts);

    // Refresh counts every 30 seconds for real-time updates
    // setInterval(fetchSidebarCounts, 30000);
  </script>
  <!-- Library Bundle Script -->
  <script src="<?= base_url() ?>public/assets/js/core/libs.min.js"></script>
  
  <!-- External Library Bundle Script -->
  <script src="<?= base_url() ?>public/assets/js/core/external.min.js"></script>
  
=======
    document.addEventListener('DOMContentLoaded', fetchSidebarCounts);

    // Refresh counts every 30 seconds only when tab is active/visible
    setInterval(() => {
      if (document.visibilityState === 'visible') {
        fetchSidebarCounts();
      }
    }, 30000);

    // Immediately refresh counts when user switches back to this tab
    document.addEventListener('visibilitychange', () => {
      if (document.visibilityState === 'visible') {
        fetchSidebarCounts();
      }
    });
  </script>
  <!-- Library Bundle Script -->
  <script src="<?= base_url() ?>public/assets/js/core/libs.min.js"></script>

  <!-- External Library Bundle Script -->
  <script src="<?= base_url() ?>public/assets/js/core/external.min.js"></script>

>>>>>>> origin/main
  <!-- Hope Ui Framework JavaScript -->
  <script src="<?= base_url() ?>public/assets/js/hope-ui.js" defer></script>

  <!-- Bootstrap Bundle JS (Fallback) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>