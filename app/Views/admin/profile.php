<?php echo $this->extend('admin/includes/header'); ?>
<!--start content-->


<?php echo $this->section('content'); ?>

<main class="main-content">
  <div class="position-relative iq-banner">
    <?php echo $this->include('admin/nav_bar') ?>
  </div>

  <div class="container-fluid content-inner mt-n5 py-0 px-4 profile-container">
    <!-- Modern Header Section -->
    <div class="container-fluid p-0 mb-4">
      <div class="bg-gradient-primary text-white p-5 rounded-3 shadow position-relative overflow-hidden">
        <div class="position-relative z-1">
          <h1 class="h2 fw-bold mb-2 text-white">Profile Settings</h1>
          <p class="text-white-75 mb-0">Manage your account information and security settings</p>
        </div>
        <i class="fas fa-user-cog position-absolute end-0 bottom-0 opacity-10"
          style="font-size: 14rem; transform: rotate(12deg); margin-right: -3rem; margin-bottom: -3rem;"></i>
      </div>
    </div>

    <div class="row">
      <!-- Sidebar Navigation -->
      <div class="col-md-4 col-lg-3">
        <div class="profile-sidebar">
          <div class="nav flex-column nav-pills" style="background-color: transparent !important;" id="profile-tabs"
            role="tablist">
            <button class="nav-link active" id="profile-tab" data-bs-toggle="pill" data-bs-target="#profile-content"
              type="button" role="tab">
              <span>Personal Information</span>
            </button>
            <button class="nav-link" id="password-tab" data-bs-toggle="pill" data-bs-target="#password-content"
              type="button" role="tab">
              <i class="fas fa-key"></i>
              <span>Change Password</span>
            </button>
          </div>
        </div>
      </div>

      <!-- Main Content Area -->
      <div class="col-md-8 col-lg-9">
        <div class="tab-content" id="profile-tabs-content">
          <!-- Personal Information Tab -->
          <div class="tab-pane fade show active profile-content" id="profile-content" role="tabpanel">
            <div class="profile-header">
              <h2>
                <i class="fas fa-user-circle text-primary"></i>
                Personal Information
              </h2>
              <p class="text-muted mb-0">Update your personal details and profile picture</p>
            </div>

            <form method="post" action="<?php echo base_url() ?>updateProfile" id="updateUser"
              enctype="multipart/form-data">
              <div class="row">
                <div class="col-md-4 mb-4">
                  <div class="avatar-upload">
                    <?php if (!empty($user->profile)): ?>
                      <img src="<?php echo base_url() ?>public/uploads/<?php echo $user->profile ?>"
                        class="profile-avatar" alt="Profile Picture"
                        onerror="this.src='<?php echo base_url() ?>public/assets/images/default-avatar.png'">
                    <?php else: ?>
                      <div class="profile-avatar">
                        <i class="fas fa-user"></i>
                      </div>
                    <?php endif; ?>
                    <div class="avatar-overlay">
                      <label for="profileUpload">
                        <i class="fas fa-camera text-dark"></i>
                      </label>
                    </div>
                    <input type="file" class="d-none" name="profile" id="profileUpload" accept=".jpeg,.jpg,.png,.gif">
                  </div>
                  <div class="text-center">
                    <button type="button" class="btn btn-sm btn-outline-danger mt-2" onclick="removeProfileImage()">
                      <i class="fas fa-trash me-1"></i> Remove Photo
                    </button>
                    <input type="hidden" name="remove_profile" id="removeProfile" value="0">
                  </div>
                </div>

                <div class="col-md-8">
                  <div class="row">
                    <div class="col-md-6 form-group">
                      <label class="form-label" for="name">
                        <i class="fas fa-user"></i> Full Name
                        <span class="text-danger">*</span>
                      </label>
                      <input type="text" class="form-control" id="name" name="name" placeholder="Enter your full name"
                        value="<?php echo htmlspecialchars($user->name) ?>" required>
                    </div>

                    <div class="col-md-6 form-group">
                      <label class="form-label" for="email">
                        <i class="fas fa-envelope"></i> Email Address
                        <span class="text-danger">*</span>
                      </label>
                      <input type="email" class="form-control" id="email" name="email"
                        placeholder="Enter your email address" value="<?php echo htmlspecialchars($user->email) ?>"
                        required>
                    </div>

                    <div class="col-md-6 form-group">
                      <label class="form-label" for="phone">
                        <i class="fas fa-phone"></i> Phone Number
                        <span class="text-danger">*</span>
                      </label>
                      <input type="tel" class="form-control" id="phone" name="phone"
                        placeholder="Enter 10-digit phone number" value="<?php echo htmlspecialchars($user->phone) ?>"
                        pattern="[0-9]{10}" maxlength="10" minlength="10" required>
                    </div>

                    <div class="col-md-6 form-group">
                      <label class="form-label">
                        <i class="fas fa-venus-mars"></i> Gender
                      </label>
                      <div class="radio-group">
                        <div class="form-check">
                          <input class="form-check-input" type="radio" style="spadding: 12px 12px !important;"
                            name="gender" id="male" value="male" <?php echo ($user->gender == 'male') ? 'checked' : ''; ?>>
                          <label class="form-check-label" for="male"> Male
                          </label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" style="padding: 12px 12px !important;"
                            name="gender" id="female" value="female" <?php echo ($user->gender == 'female') ? 'checked' : ''; ?>>
                          <label class="form-check-label" for="female">Female
                          </label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" style="padding: 12px 12px !important;"
                            name="gender" id="other" value="other" <?php echo ($user->gender == 'other') ? 'checked' : ''; ?>>
                          <label class="form-check-label" for="other">Other
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="submit-btn">
                      <i class="fas fa-save"></i> Save Changes
                    </button>
                  </div>
                </div>
              </div>
            </form>
          </div>

          <!-- Change Password Tab -->
          <div class="tab-pane fade profile-content" id="password-content" role="tabpanel">
            <div class="profile-header">
              <h2>
                <i class="fas fa-key text-primary"></i>
                Change Password
              </h2>
              <p class="text-muted mb-0">Update your password to keep your account secure</p>
            </div>

            <form method="post" action="<?php echo base_url() ?>updatePassword" id="updatePassword">
              <div class="row">
                <div class="col-md-8">
                  <div class="form-group">
                    <label class="form-label" for="old_password">
                      <i class="fas fa-lock"></i> Current Password
                      <span class="text-danger">*</span>
                    </label>
                    <input type="password" class="form-control" id="old_password" name="old_password"
                      placeholder="Enter your current password" required>
                  </div>

                  <div class="form-group">
                    <label class="form-label" for="new_password">
                      <i class="fas fa-key"></i> New Password
                      <span class="text-danger">*</span>
                    </label>
                    <input type="password" class="form-control" id="new_password" name="new_password"
                      placeholder="Enter new password" required>
                    <div class="password-strength">
                      <div class="password-strength-bar" id="passwordStrengthBar"></div>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="form-label" for="confirm_password">
                      <i class="fas fa-key"></i> Confirm New Password
                      <span class="text-danger">*</span>
                    </label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                      placeholder="Confirm new password" required>
                    <div class="text-danger small mt-1" id="passwordMatchError"></div>
                  </div>

                  <div class="password-requirements">
                    <h6 class="fw-bold mb-2 text-dark">Password Requirements:</h6>
                    <ul>
                      <li id="req-length">
                        <i class="fas fa-circle text-muted"></i>
                        At least 8 characters
                      </li>
                      <li id="req-uppercase">
                        <i class="fas fa-circle text-muted"></i>
                        One uppercase letter
                      </li>
                      <li id="req-lowercase">
                        <i class="fas fa-circle text-muted"></i>
                        One lowercase letter
                      </li>
                      <li id="req-number">
                        <i class="fas fa-circle text-muted"></i>
                        One number
                      </li>
                      <li id="req-special">
                        <i class="fas fa-circle text-muted"></i>
                        One special character
                      </li>
                    </ul>
                  </div>

                  <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="submit-btn" id="passwordSubmitBtn" disabled>
                      <i class="fas fa-save"></i> Update Password
                    </button>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php echo $this->include('admin/footer') ?>
</main>

<style>
  /* Profile Settings Styling */
  .profile-container {
    min-height: calc(100vh - 180px);
  }

  .profile-sidebar {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 16px;
    border: 1px solid #e9ecef;
    padding: 2rem 1rem;
    height: fit-content;
    position: sticky;
    top: 20px;
  }

  .nav-pills .nav-link {
    color: #495057;
    border-radius: 12px;
    padding: 1rem 1.5rem;
    margin-bottom: 0.75rem;
    border: 1px solid transparent;
    transition: all 0.3s ease;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.75rem;
  }

  .nav-pills .nav-link i {
    width: 20px;
    text-align: center;
    font-size: 1.1rem;
  }

  .nav-pills .nav-link:hover {
    background-color: rgba(99, 102, 241, 0.05);
    border-color: #dee2e6;
    color: #6366f1;
  }

  .nav-pills .nav-link.active {
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    color: white;
    border-color: #6366f1;
    box-shadow: 0 4px 15px rgba(99, 102, 241, 0.2);
  }

  .profile-content {
    background: white;
    border-radius: 16px;
    border: 1px solid #e9ecef;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    padding: 2rem;
  }

  .profile-header {
    border-bottom: 2px solid #f1f5f9;
    padding-bottom: 1.5rem;
    margin-bottom: 2rem;
  }

  .profile-header h2 {
    color: #1e293b;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin: 0;
  }

  .profile-avatar {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    border: 4px solid white;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    object-fit: cover;
    background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    color: #6b7280;
  }

  .avatar-upload {
    position: relative;
    width: fit-content;
    margin: 0 auto 2rem;
  }

  .avatar-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
  }

  .avatar-upload:hover .avatar-overlay {
    opacity: 1;
  }

  .avatar-upload label {
    background: rgba(255, 255, 255, 0.9);
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
  }

  .avatar-upload label:hover {
    background: white;
    transform: scale(1.1);
  }

  .form-group {
    margin-bottom: 1.5rem;
  }

  .form-label {
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }

  .form-label i {
    color: #6366f1;
    font-size: 0.875rem;
  }

  .form-control {
    border: 2px solid #e5e7eb;
    border-radius: 10px;
    padding: 0.875rem 1rem;
    font-size: 0.95rem;
    transition: all 0.3s ease;
  }

  .form-control:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
  }

  .radio-group {
    display: flex;
    gap: 2rem;
    flex-wrap: wrap;
  }

  .form-check {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin: 0;
  }

  .form-check-input {
    width: 1.2em;
    height: 1.2em;
    margin-top: 0;
    border: 2px solid #d1d5db;
  }

  .form-check-input:checked {
    background-color: #6366f1;
    border-color: #6366f1;
  }

  .form-check-label {
    color: #4b5563;
    font-weight: 500;
  }

  .submit-btn {
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    border: none;
    border-radius: 10px;
    padding: 1rem 2rem;
    color: white;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(99, 102, 241, 0.2);
  }

  .submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(99, 102, 241, 0.3);
  }

  .submit-btn:active {
    transform: translateY(0);
  }

  .password-strength {
    height: 4px;
    border-radius: 2px;
    margin-top: 0.5rem;
    background: #e5e7eb;
    overflow: hidden;
  }

  .password-strength-bar {
    height: 100%;
    width: 0;
    transition: all 0.3s ease;
  }

  .password-requirements {
    margin-top: 1rem;
    padding: 1rem;
    background: #f8fafc;
    border-radius: 8px;
    border-left: 4px solid #6366f1;
  }

  .password-requirements ul {
    list-style: none;
    padding: 0;
    margin: 0;
  }

  .password-requirements li {
    margin-bottom: 0.5rem;
    color: #64748b;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }

  .password-requirements li i {
    font-size: 0.75rem;
  }

  .password-requirements li.valid {
    color: #10b981;
  }

  @media (max-width: 768px) {
    .profile-sidebar {
      position: static;
      margin-bottom: 2rem;
    }

    .nav-pills .nav-link {
      padding: 0.75rem 1rem;
    }

    .profile-content {
      padding: 1.5rem;
    }

    .radio-group {
      gap: 1rem;
    }

    .profile-avatar {
      width: 100px;
      height: 100px;
      font-size: 2.5rem;
    }
  }

  @media (max-width: 576px) {
    .profile-content {
      padding: 1rem;
    }

    .profile-header {
      padding-bottom: 1rem;
      margin-bottom: 1.5rem;
    }

    .profile-header h2 {
      font-size: 1.5rem;
    }

    .submit-btn {
      width: 100%;
      justify-content: center;
    }
  }

  /* Fixed gradient header */
  .bg-gradient-primary {
    background: linear-gradient(135deg, #3f30e3 0%, #5555fa 100%) !important;
  }
</style>

<script>
  $(document).ready(function () {
    // Tab functionality
    $('#profile-tabs button').click(function (e) {
      e.preventDefault();
      $(this).tab('show');
    });

    // Profile image upload
    $('#profileUpload').change(function () {
      if (this.files && this.files[0]) {
        const file = this.files[0];
        const reader = new FileReader();

        // Validate file type
        const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!validTypes.includes(file.type)) {
          showAlert('error', 'Please upload a valid image file (JPEG, PNG, GIF)');
          return;
        }

        // Validate file size (max 5MB)
        if (file.size > 5 * 1024 * 1024) {
          showAlert('error', 'Image size should be less than 5MB');
          return;
        }

        reader.onload = function (e) {
          $('.profile-avatar').attr('src', e.target.result);
        }
        reader.readAsDataURL(file);

        // Reset remove flag
        $('#removeProfile').val('0');
      }
    });

    // Remove profile image
    window.removeProfileImage = function () {
      if (confirm('Are you sure you want to remove your profile picture?')) {
        const defaultAvatar = '<?php echo base_url() ?>public/assets/images/default-avatar.png';
        $('.profile-avatar').attr('src', defaultAvatar);
        $('#removeProfile').val('1');
        $('#profileUpload').val('');
      }
    };

    // Password strength checker
    $('#new_password').on('input', function () {
      const password = $(this).val();
      const strengthBar = $('#passwordStrengthBar');
      const requirements = {
        length: password.length >= 8,
        uppercase: /[A-Z]/.test(password),
        lowercase: /[a-z]/.test(password),
        number: /\d/.test(password),
        special: /[!@#$%^&*(),.?":{}|<>]/.test(password)
      };

      // Update requirement indicators
      $('#req-length').toggleClass('valid', requirements.length)
        .find('i').toggleClass('fa-check text-success', requirements.length)
        .toggleClass('fa-circle text-muted', !requirements.length);

      $('#req-uppercase').toggleClass('valid', requirements.uppercase)
        .find('i').toggleClass('fa-check text-success', requirements.uppercase)
        .toggleClass('fa-circle text-muted', !requirements.uppercase);

      $('#req-lowercase').toggleClass('valid', requirements.lowercase)
        .find('i').toggleClass('fa-check text-success', requirements.lowercase)
        .toggleClass('fa-circle text-muted', !requirements.lowercase);

      $('#req-number').toggleClass('valid', requirements.number)
        .find('i').toggleClass('fa-check text-success', requirements.number)
        .toggleClass('fa-circle text-muted', !requirements.number);

      $('#req-special').toggleClass('valid', requirements.special)
        .find('i').toggleClass('fa-check text-success', requirements.special)
        .toggleClass('fa-circle text-muted', !requirements.special);

      // Calculate strength
      let strength = 0;
      if (requirements.length) strength += 20;
      if (requirements.uppercase) strength += 20;
      if (requirements.lowercase) strength += 20;
      if (requirements.number) strength += 20;
      if (requirements.special) strength += 20;

      // Update strength bar
      strengthBar.css('width', strength + '%');

      // Set color based on strength
      if (strength < 40) {
        strengthBar.css('background-color', '#ef4444');
      } else if (strength < 80) {
        strengthBar.css('background-color', '#f59e0b');
      } else {
        strengthBar.css('background-color', '#10b981');
      }

      validatePasswordForm();
    });

    // Password match validation
    $('#confirm_password').on('input', function () {
      validatePasswordForm();
    });

    function validatePasswordForm() {
      const newPassword = $('#new_password').val();
      const confirmPassword = $('#confirm_password').val();
      const passwordMatchError = $('#passwordMatchError');
      const submitBtn = $('#passwordSubmitBtn');

      // Check if passwords match
      if (newPassword && confirmPassword) {
        if (newPassword === confirmPassword) {
          passwordMatchError.text('');
        } else {
          passwordMatchError.text('Passwords do not match');
        }
      }

      // Check if all requirements are met
      const allRequirementsMet = $('.password-requirements li.valid').length === 5;
      const passwordsMatch = newPassword === confirmPassword && newPassword.length > 0;
      const oldPassword = $('#old_password').val().length > 0;

      // Enable/disable submit button
      submitBtn.prop('disabled', !(allRequirementsMet && passwordsMatch && oldPassword));
    }

    // Form submission
    $('#updateUser').on('submit', function (e) {
      e.preventDefault();
      submitForm($(this), 'Profile updated successfully!');
    });

    $('#updatePassword').on('submit', function (e) {
      e.preventDefault();
      submitForm($(this), 'Password updated successfully!');
    });

    function submitForm(form, successMessage) {
      const url = form.attr('action');
      const formData = new FormData(form[0]);

      $.ajax({
        url: url,
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: function () {
          $('.submit-btn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');
        },
        success: function (response) {
          if (response.status === true) {
            Swal.fire({
              icon: 'success',
              title: 'Success!',
              text: successMessage,
              timer: 2000,
              showConfirmButton: false
            }).then(() => {
              if (response.redirect) {
                window.location.href = response.redirect;
              } else {
                location.reload();
              }
            });
          } else {
            showValidationErrors(form, response.errors);
            Swal.fire('Error!', response.message || 'Please check the form for errors', 'error');
          }
        },
        error: function (xhr) {
          Swal.fire('Error!', 'An error occurred. Please try again later.', 'error');
        },
        complete: function () {
          $('.submit-btn').prop('disabled', false).html('<i class="fas fa-save"></i> Save Changes');
        }
      });
    }

    function showValidationErrors(form, errors) {
      // Clear previous errors
      form.find('.text-danger').text('');
      form.find('.is-invalid').removeClass('is-invalid');

      if (errors) {
        $.each(errors, function (field, messages) {
          const input = form.find('[name="' + field + '"]');
          if (input.length) {
            input.addClass('is-invalid');
            input.next('.text-danger').text(messages[0]);
          }
        });
      }
    }

    function showAlert(type, message) {
      const alertDiv = document.createElement('div');
      alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed shadow-lg`;
      alertDiv.style.cssText = `
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            max-width: 400px;
            border-radius: 10px;
            border: none;
            animation: slideIn 0.3s ease-out;
        `;

      const icon = type === 'error' ? 'exclamation-triangle' : 'check-circle';
      const color = type === 'error' ? '#dc3545' : '#198754';

      alertDiv.innerHTML = `
            <div class="d-flex align-items-start gap-3">
                <div class="flex-shrink-0">
                    <i class="fas fa-${icon} fa-lg" style="color: ${color};"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="fw-bold mb-1">${type === 'error' ? 'Error' : 'Success'}</div>
                    <div class="small">${message}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;

      document.body.appendChild(alertDiv);

      setTimeout(() => {
        if (alertDiv.parentNode) {
          alertDiv.classList.add('fade');
          setTimeout(() => {
            if (alertDiv.parentNode) {
              alertDiv.remove();
            }
          }, 300);
        }
      }, 5000);
    }

    // Initialize password validation on load
    validatePasswordForm();
  });
</script>

<?php echo $this->endSection() ?>