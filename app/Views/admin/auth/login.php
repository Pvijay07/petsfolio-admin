<!doctype html>
<html lang="en" dir="ltr">

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
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Petsfolio | Login</title>

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

  <!-- loader-->
  <link href="<?= base_url() ?>public/assets/css/pace.min.css" rel="stylesheet" />

  <!--Theme Styles-->
  <link href="<?= base_url() ?>public/assets/css/light-theme.css" rel="stylesheet" />
  <!-- jQuery -->
  <script src="<?= base_url() ?>public/assets/js/jquery.min.js"></script>

  <style>
<<<<<<< HEAD
    /* Dark Mode Overrides for Login */
    html.dark body, body.dark {
      background-color: #0b111e !important;
      color: #cbd5e1 !important;
    }
    body.dark .bg-white {
      background-color: #0b111e !important;
    }
    body.dark .card {
      background-color: #141c2e !important;
      color: #cbd5e1 !important;
    }
    body.dark .form-control {
      background-color: #162032 !important;
      border-color: #26354d !important;
      color: #f8fafc !important;
    }
    body.dark .form-control:focus {
      border-color: #3b82f6 !important;
      color: #ffffff !important;
    }
    body.dark .form-label, body.dark h2, body.dark h5, body.dark .text-dark {
      color: #f8fafc !important;
    }
    body.dark .text-muted {
      color: #94a3b8 !important;
    }
    body.dark .loader-overlay {
      background: rgba(11, 17, 30, 0.85);
    }
=======
>>>>>>> origin/main
    .error-message {
      color: red;
      position: absolute;
      top: 0;
      left: 50%;
      transform: translate(-50%, -50%);
      text-align: center;
      font-size: 20px;
      font-weight: bold;
    }

    /* Loader Overlay */
    .loader-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(255, 255, 255, 0.8);
      z-index: 9999;
      display: none;
      align-items: center;
      justify-content: center;
    }

    .loader-spinner {
      border: 5px solid #f3f3f3;
      border-top: 5px solid #3b8aff;
      border-radius: 50%;
      width: 50px;
      height: 50px;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      0% {
        transform: rotate(0deg);
      }
<<<<<<< HEAD
=======

>>>>>>> origin/main
      100% {
        transform: rotate(360deg);
      }
    }
  </style>
</head>

<body class=" " data-bs-spy="scroll" data-bs-target="#elements-section" data-bs-offset="0" tabindex="0">
  <!-- loader overlay -->
  <div class="loader-overlay" id="loaderOverlay">
    <div class="loader-spinner"></div>
  </div>

  <div class="wrapper">
    <section class="login-content">
      <div class="row m-0 align-items-center bg-white vh-100">
        <div class="col-md-6">
          <div class="row justify-content-center">
            <div class="col-md-10">
              <div class="card card-transparent shadow-none d-flex justify-content-center mb-0 auth-card">
                <div class="card-body login">
                  <a href="#" class="navbar-brand d-flex align-items-center mb-3">
                    <div class="logo-main">
                      <div class="logo-normal">
                        <img src="<?= base_url() ?>public/assets/images/icons/Petsfolio.png" alt="">
                      </div>
                      <div class="logo-mini">
                        <svg class="text-primary icon-30" viewBox="0 0 30 30" fill="none"
                          xmlns="http://www.w3.org/2000/svg">
                          <rect x="-0.757324" y="19.2427" width="28" height="4" rx="2"
                            transform="rotate(-45 -0.757324 19.2427)" fill="currentColor" />
                          <rect x="7.72803" y="27.728" width="28" height="4" rx="2"
                            transform="rotate(-45 7.72803 27.728)" fill="currentColor" />
                          <rect x="10.5366" y="16.3945" width="16" height="4" rx="2"
                            transform="rotate(45 10.5366 16.3945)" fill="currentColor" />
                          <rect x="10.5562" y="-0.556152" width="28" height="4" rx="2"
                            transform="rotate(45 10.5562 -0.556152)" fill="currentColor" />
                        </svg>
                      </div>
                    </div>
                  </a>
                  <span class="error-message"></span>
                  <h2 class="mb-2 text-center">Sign In</h2>
                  <p class="text-center">Login to stay connected.</p>
                  <form id="loginForm" method="POST">
                    <?= csrf_field(); ?>

                    <div class="row">
                      <div class="col-lg-12">
                        <div class="form-group">
                          <label for="email" class="form-label">Email</label>
                          <input type="email" class="form-control" id="email" name="email"
                            placeholder="Enter your email">
                        </div>
<<<<<<< HEAD
=======

>>>>>>> origin/main
                        <div class="d-flex justify-content-center">
                          <input type="button" id="sendOtp" class="btn btn-primary radius-30" value="Send OTP">
                        </div>
                      </div>

                      <div class="col-lg-12 otp" style="display: none;">
                        <div class="form-group">
                          <label for="otp" class="form-label">OTP</label>
                          <input type="tel" class="form-control" id="otp" name="otp" placeholder="Enter OTP"
                            maxlength="6">
                        </div>
                      </div>

                      <div class="d-flex justify-content-center" id="signInContainer">
                        <input style="display: none;" type="submit" id="signIn" class="btn btn-primary radius-30"
                          value="Sign In">
                      </div>
                    </div>
                  </form>

                  <div class="card forget"></div>

                </div>

              </div>
            </div>
          </div>
          <div class="sign-bg">
            <svg width="280" height="230" viewBox="0 0 431 398" fill="none" xmlns="http://www.w3.org/2000/svg">
              <g opacity="0.05">
                <rect x="-157.085" y="193.773" width="543" height="77.5714" rx="38.7857"
                  transform="rotate(-45 -157.085 193.773)" fill="#3B8AFF" />
                <rect x="7.46875" y="358.327" width="543" height="77.5714" rx="38.7857"
                  transform="rotate(-45 7.46875 358.327)" fill="#3B8AFF" />
                <rect x="61.9355" y="138.545" width="310.286" height="77.5714" rx="38.7857"
                  transform="rotate(45 61.9355 138.545)" fill="#3B8AFF" />
                <rect x="62.3154" y="-190.173" width="543" height="77.5714" rx="38.7857"
                  transform="rotate(45 62.3154 -190.173)" fill="#3B8AFF" />
              </g>
            </svg>
          </div>
        </div>
        <div class="col-md-6 d-md-block d-none bg-primary p-0 mt-n1 vh-100 overflow-hidden">
          <img src="<?= base_url() ?>public/assets/images/auth/01.png" class="img-fluid gradient-main animated-scaleX"
            alt="images">
        </div>
      </div>
    </section>
  </div>
<<<<<<< HEAD
=======
  <?php 
    $siteKey = $recaptchaSiteKey ?? getenv('RECAPTCHA_SITE_KEY') ?: '';
    if (!empty($siteKey)): 
  ?>
    <script src="https://www.google.com/recaptcha/api.js?render=<?= esc($siteKey); ?>"></script>
  <?php endif; ?>
>>>>>>> origin/main
  <script src="<?= base_url() ?>public/assets/js/core/libs.min.js"></script>

  <!-- External Library Bundle Script -->
  <script src="<?= base_url() ?>public/assets/js/core/external.min.js"></script>

  <!-- Widgetchart Script -->
  <script src="<?= base_url() ?>public/assets/js/charts/widgetcharts.js"></script>

  <!-- mapchart Script -->
  <script src="<?= base_url() ?>public/assets/js/charts/vectore-chart.js"></script>
  <script src="<?= base_url() ?>public/assets/js/charts/dashboard.js"></script>

  <!-- fslightbox Script -->
  <script src="<?= base_url() ?>public/assets/js/plugins/fslightbox.js"></script>

  <!-- Settings Script -->
  <script src="<?= base_url() ?>public/assets/js/plugins/setting.js"></script>

  <!-- Slider-tab Script -->
  <script src="<?= base_url() ?>public/assets/js/plugins/slider-tabs.js"></script>

  <!-- Form Wizard Script -->
  <script src="<?= base_url() ?>public/assets/js/plugins/form-wizard.js"></script>

  <!-- AOS Animation Plugin-->
  <script src="<?= base_url() ?>public/assets/vendor/aos/dist/aos.js"></script>

  <!-- App Script -->
  <script src="<?= base_url() ?>public/assets/js/hope-ui.js" defer></script>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    function showLoader() {
      document.getElementById("loaderOverlay").style.display = "flex";
    }

    function hideLoader() {
      document.getElementById("loaderOverlay").style.display = "none";
    }

    document.addEventListener("DOMContentLoaded", function() {
      const emailInput = document.getElementById("email");
      const sendOtpBtn = document.getElementById("sendOtp");
      const otpField = document.querySelector(".otp");
      const otpInput = document.getElementById("otp");
      const signInContainer = document.getElementById("signInContainer");
      const signInBtn = document.getElementById("signIn");
<<<<<<< HEAD

      sendOtpBtn.addEventListener("click", function() {
        const email = emailInput.value.trim();
        if (email === "") {
          Swal.fire('Error!', "Please enter your email.", 'error')
          return;
        }

=======
      let cooldownTimer = null;

      function sendOtpRequest(email, recaptchaToken) {
>>>>>>> origin/main
        showLoader();

        fetch("<?= base_url('auth/send-otp'); ?>", {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
              "X-CSRF-TOKEN": "<?= csrf_hash(); ?>"
            },
<<<<<<< HEAD
            body: JSON.stringify({ email: email })
=======
            body: JSON.stringify({
              email: email,
              "g-recaptcha-response": recaptchaToken
            })
>>>>>>> origin/main
          })
          .then(response => response.json())
          .then(data => {
            hideLoader();
            if (data.success) {
              Swal.fire('Success!', "OTP sent to your email.", 'success');
              emailInput.disabled = true;
<<<<<<< HEAD
              sendOtpBtn.disabled = true;
              signInBtn.style.display = "block";
              otpField.style.display = "block";
            } else {
              Swal.fire('Error!', data.message, 'error')
=======
              signInBtn.style.display = "block";
              otpField.style.display = "block";

              // 60-second countdown cooldown for Send OTP button
              let cooldown = 60;
              sendOtpBtn.disabled = true;
              sendOtpBtn.value = `Resend OTP (${cooldown}s)`;

              if (cooldownTimer) clearInterval(cooldownTimer);
              cooldownTimer = setInterval(() => {
                cooldown--;
                if (cooldown > 0) {
                  sendOtpBtn.value = `Resend OTP (${cooldown}s)`;
                } else {
                  clearInterval(cooldownTimer);
                  sendOtpBtn.disabled = false;
                  sendOtpBtn.value = "Resend OTP";
                }
              }, 1000);
            } else {
              Swal.fire('Error!', data.message, 'error');
>>>>>>> origin/main
            }
          })
          .catch(error => {
            hideLoader();
            console.error("Error:", error);
          });
<<<<<<< HEAD
=======
      }

      sendOtpBtn.addEventListener("click", function() {
        const email = emailInput.value.trim();
        if (email === "") {
          Swal.fire('Error!', "Please enter your email.", 'error');
          return;
        }

        const recaptchaSiteKey = "<?= esc($siteKey); ?>";
        if (recaptchaSiteKey && typeof grecaptcha !== 'undefined') {
          showLoader();
          grecaptcha.ready(function() {
            grecaptcha.execute(recaptchaSiteKey, {action: 'send_otp'}).then(function(token) {
              sendOtpRequest(email, token);
            }).catch(function(err) {
              hideLoader();
              console.error('reCAPTCHA error:', err);
              sendOtpRequest(email, '');
            });
          });
        } else {
          sendOtpRequest(email, '');
        }
>>>>>>> origin/main
      });

      otpInput.addEventListener("input", function() {
        if (otpInput.value.length >= 4) {
          signInContainer.style.display = "flex";
        } else {
          signInContainer.style.display = "none";
        }
      });

      document.getElementById("loginForm").addEventListener("submit", function(event) {
        event.preventDefault();

        const otp = otpInput.value.trim();
        if (otp === "") {
          Swal.fire('Error!', "Please enter the OTP.", 'error')
          return;
        }

        showLoader();

        fetch("<?= base_url('auth/verify-otp'); ?>", {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
              "X-CSRF-TOKEN": "<?= csrf_hash(); ?>"
            },
<<<<<<< HEAD
            body: JSON.stringify({ email: emailInput.value, otp: otp })
=======
            body: JSON.stringify({
              email: emailInput.value,
              otp: otp
            })
>>>>>>> origin/main
          })
          .then(response => response.json())
          .then(data => {
            hideLoader();
            if (data.success) {
<<<<<<< HEAD
              window.location.href = "<?= base_url('dashboard'); ?>";
=======
              window.location.href = data.redirect;
>>>>>>> origin/main
            } else {
              Swal.fire('Error!', data.message, 'error')
            }
          })
          .catch(error => {
            hideLoader();
            console.error("Error:", error);
          });
      });
    });
  </script>
</body>

<<<<<<< HEAD
</html>
=======
</html>
>>>>>>> origin/main
