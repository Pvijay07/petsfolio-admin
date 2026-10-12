<!doctype html>
<html lang="en" dir="ltr">

<head>
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
  <?php 
    $siteKey = $recaptchaSiteKey ?? getenv('RECAPTCHA_SITE_KEY') ?: '';
    if (!empty($siteKey)): 
  ?>
    <script src="https://www.google.com/recaptcha/api.js?render=<?= esc($siteKey); ?>"></script>
  <?php endif; ?>
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
      let cooldownTimer = null;

      function sendOtpRequest(email, recaptchaToken) {
        showLoader();

        fetch("<?= base_url('auth/send-otp'); ?>", {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
              "X-CSRF-TOKEN": "<?= csrf_hash(); ?>"
            },
            body: JSON.stringify({
              email: email,
              "g-recaptcha-response": recaptchaToken
            })
          })
          .then(response => response.json())
          .then(data => {
            hideLoader();
            if (data.success) {
              Swal.fire('Success!', "OTP sent to your email.", 'success');
              emailInput.disabled = true;
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
            }
          })
          .catch(error => {
            hideLoader();
            console.error("Error:", error);
          });
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
            body: JSON.stringify({
              email: emailInput.value,
              otp: otp
            })
          })
          .then(response => response.json())
          .then(data => {
            hideLoader();
            if (data.success) {
              window.location.href = data.redirect;
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

</html>