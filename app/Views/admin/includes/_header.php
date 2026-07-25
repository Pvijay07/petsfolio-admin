<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admin Panel</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <!--plugins-->
  <link href="<?= base_url()?>public/assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
  <link href="<?= base_url()?>public/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
  <link href="<?= base_url()?>public/assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
  <link href="<?= base_url()?>public/assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
  <!-- Bootstrap CSS -->
  <link href="<?= base_url()?>public/assets/css/bootstrap.min.css" rel="stylesheet" />
  <link href="<?= base_url()?>public/assets/css/bootstrap-extended.css" rel="stylesheet" />
  <link href="<?= base_url()?>public/assets/css/style.css" rel="stylesheet" />
  <link href="<?= base_url()?>public/assets/css/icons.css" rel="stylesheet">
  
  
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

  <!-- loader-->
	<link href="<?= base_url()?>public/assets/css/pace.min.css" rel="stylesheet" />


  <!--Theme Styles-->
  
  <link href="<?= base_url()?>public/assets/css/light-theme.css" rel="stylesheet" />  
  <!-- jQuery -->
  <script src="<?= base_url() ?>public/assets/js/jquery.min.js"></script>

</head>

<body class="hold-transition sidebar-mini">

<!-- Main Wrapper Start -->
<div class="wrapper">

  <!-- Navbar -->



  <!--start top header-->
  <header class="top-header">        
      <nav class="navbar navbar-expand">
        <div class="mobile-toggle-icon d-xl-none">
            <i class="bi bi-list"></i>
          </div>

          <div class="top-navbar-right ms-3 ms-auto">
            <ul class="navbar-nav align-items-center">
            <li class="nav-item dropdown dropdown-large">
              <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown">
                <div class="user-setting d-flex align-items-center gap-1">
                  <img src="<?=base_url() ?>assets/images/avatars/avatar-1.png" class="user-img" alt="">
                  <div class="user-name d-none d-sm-block"></div>
                </div>
              </a>
              <ul class="dropdown-menu dropdown-menu-end">
                
                 <li>
                    <a class="dropdown-item" href="<?= base_url('index.php/admin/profile'); ?>">
                       <div class="d-flex align-items-center">
                         <div class="setting-icon"><i class="bi bi-person-fill"></i></div>
                         <div class="setting-text ms-3"><span>Profile</span></div>
                       </div>
                     </a>
                  </li>
                  <li>
                    <a class="dropdown-item" href="<?= base_url('index.php/admin/profile/change_pwd'); ?>">
                       <div class="d-flex align-items-center">
                         <div class="setting-icon"><i class="bi bi-gear-fill"></i></div>
                         <div class="setting-text ms-3"><span>Change Password</span></div>
                       </div>
                     </a>
                  </li>
                  
                  <li>
                    <a class="dropdown-item" href="<?= base_url('index.php/admin/auth/logout') ?>">
                       <div class="d-flex align-items-center">
                         <div class="setting-icon"><i class="bi bi-lock-fill"></i></div>
                         <div class="setting-text ms-3"><span>Logout</span></div>
                       </div>
                     </a>
                  </li>
              </ul>
            </li>
            
            </ul>
            </div>
      </nav>
    </header>


  <!-- /.navbar -->


  <!-- Sideabr -->


  <?php view('admin/includes/_sidebar'); ?>


  <!-- / .Sideabr -->
