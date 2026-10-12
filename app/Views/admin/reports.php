<!doctype html>
<<<<<<< HEAD
<html lang="en">

<head>
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
<html lang="en" class="light-theme">

<head>
>>>>>>> origin/main
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="assets/images/favicon-32x32.png" type="image/png" />
  <!--plugins-->
  <link href="assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
  <link href="assets/plugins/datetimepicker/css/classic.css" rel="stylesheet" />
	<link href="assets/plugins/datetimepicker/css/classic.time.css" rel="stylesheet" />
	<link href="assets/plugins/datetimepicker/css/classic.date.css" rel="stylesheet" />
	<link rel="stylesheet" href="assets/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.min.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link href="assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
  <link href="assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
  <!-- Bootstrap CSS -->
  <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
  <link href="assets/css/bootstrap-extended.css" rel="stylesheet" />
  <link href="assets/css/style.css" rel="stylesheet" />
  <link href="assets/css/icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

  <!-- loader-->
	<link href="assets/css/pace.min.css" rel="stylesheet" />


  <!--Theme Styles-->
  <link href="assets/css/dark-theme.css" rel="stylesheet" />
  <link href="assets/css/light-theme.css" rel="stylesheet" />
  <link href="assets/css/semi-dark.css" rel="stylesheet" />
  <link href="assets/css/header-colors.css" rel="stylesheet" />
<<<<<<< HEAD
  <link rel="stylesheet" href="<?= base_url('public/assets/css/theme-dark-overrides.css?v=' . time()) ?>" />
=======
>>>>>>> origin/main

  <title>Reports</title>
</head>

<body>


  <!--start wrapper-->
  <div class="wrapper">
    <?php include("includes/header.php"); ?>

       <?php include("includes/sidebar.php"); ?>

       <!--start content-->
       <main class="page-content">
				<!--breadcrumb-->
				<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
					<div class="breadcrumb-title pe-3">Reports</div>
				</div>
				<!--end breadcrumb-->
        
				<div class="row">
					
					

            <div class="card">
              <div class="card-body">
                <div class="border p-3 rounded">
                
                <form class="row g-3">
                  <div class="col-6">
                    <label class="form-label">Select Once</label>
                    <select name="" id="" class="form-select">
                      <option value="">Stores</option>
                      <option value="">Customers</option>
                      <option value="">Orders</option>
                      <option value="">Payments</option>
                    </select>
                  </div>
                  <div class="col-6">
                    <label class="form-label">Date from</label>
                    <input type="text" class="form-control datepicker" />
                  </div>
                  <div class="col-6">
                    <label class="form-label">Date to</label>
                    <input type="text" class="form-control datepicker" />
                  </div>
                  <div class="col-6">
                    <label class="form-label">Select Status</label>
                    <select name="" id="" class="form-select">
                      <option value="">Active</option>
                      <option value="">Inactive</option>
                    </select>
                  </div>
                  
                  <div class="col-sm-9">
                    <button type="submit" class="btn btn-primary px-5">Submit</button>
                  </div>
                </form>
              </div>
              </div>
            </div>
		
				</div>
				<!--end row-->

			</main>
       <!--end page main-->


       <!--start overlay-->
        <div class="overlay nav-toggle-icon"></div>
       <!--end overlay-->

        <!--Start Back To Top Button-->
        <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
        <!--End Back To Top Button-->
        
       

  </div>
  <!--end wrapper-->

  <!-- Bootstrap bundle JS -->
  <script src="assets/js/bootstrap.bundle.min.js"></script>
  <!--plugins-->
  <script src="assets/js/jquery.min.js"></script>
  <script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
  <script src="assets/plugins/metismenu/js/metisMenu.min.js"></script>
  <script src="assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
  <script src="assets/js/pace.min.js"></script>
  <script src="assets/plugins/datetimepicker/js/legacy.js"></script>
	<script src="assets/plugins/datetimepicker/js/picker.js"></script>
	<script src="assets/plugins/datetimepicker/js/picker.time.js"></script>
	<script src="assets/plugins/datetimepicker/js/picker.date.js"></script>
	<script src="assets/plugins/bootstrap-material-datetimepicker/js/moment.min.js"></script>
	<script src="assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.min.js"></script>
  <script src="assets/js/form-date-time-pickes.js"></script>

  <!--app-->
  <script src="assets/js/app.js"></script>
  
  

</body>

</html>