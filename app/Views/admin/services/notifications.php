<?= $this->extend('admin/includes/header'); ?>
<!--start content-->
<?= $this->section('content'); ?>

<main class="main-content">
  <div class="position-relative iq-banner">
    <!--Nav Start-->
    <?= $this->include('admin/nav_bar') ?>

  </div>
  <div class="conatiner-fluid content-inner mt-n5 py-0">
    <div class="row">
      <div class="col-sm-12">
        <div class="card">
          <div class="card-header d-flex justify-content-between">
            <div class="header-title">
              <h4 class="card-title">Notifications</h4>
            </div>
          </div>
          <div class="card-body">
            <div class="d-flex justify-content-between flex-column flex-md-row gap-3">
              <div class="btn-toolbar gap-2 align-items-stretch flex-column flex-md-row" role="toolbar" aria-label="Toolbar with buttons">
                <button type="button" class="btn btn-primary d-flex align-items-center gap-1 add" data-bs-toggle="offcanvas" data-bs-target="#form-offcanvas" data-crud-id="0" title="Create Duration">
                  <i class="icon-add-new"></i>
                  New
                </button>
              </div>
            </div>
            <div class="table-responsive">
              <table
                id="datatable"
                class="table table-striped"
                data-toggle="data-table">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>S.No.</th>
                    <th>Label</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $i = 1;
                  foreach ($notifications as $notification) {


                  ?>
                    <tr>
                      <td><?php echo $notification->id; ?></td>
                      <td><?php echo $i; ?></td>
                      <td><?php echo $notification->title; ?></td>
                      <td><?php echo $notification->type ?></td>
                      <td>
                        <div class="form-check form-switch">
                          <input type="checkbox"
                            data-token=""
                            data-url="<?= base_url() ?>updateStatus"
                            data-module="notification_templates"
                            class="switch-status-change form-check-input status"
                            id="datatable-row-<?= $notification->id; ?>" name="status"
                            data-id="<?= $notification->id; ?>"
                            value="<?= $notification->status; ?>" <?= $notification->status == '1' ? 'checked' : ''; ?>>
                        </div>
                      </td>
                      <td>
                        <div class="d-flex gap-2 align-items-center">
                        <!-- <button type="button" class="fs-4 text-primary border-0 bg-transparent edit"
                            data-bs-toggle="offcanvas" data-bs-target="#form-offcanvas1"
                            aria-label="Edit" data-bs-original-title="Edit"
                            data-module="notification_templates"
                            data-url="<?= base_url() ?>getUser"
                            data-id="<?= $notification->id; ?>">
                            <i class="fas fa-pencil-alt"></i>
                          </button> -->
                          <a href="<?= base_url() ?>deleteNotification" id="delete-employees-<?= $notification->id; ?>"
                            class="fs-4 text-danger" data-type="ajax" data-method="DELETE"
                            data-token="WY9UR433BdtOHpyqGYdQZivZoEc7vdNGeFkSyjI5" data-bs-toggle="tooltip"
                            data-confirm="Are you certain you want to delete it?" aria-label="Delete"
                            data-bs-original-title="Delete"
                            data-id="<?php echo $notification->id; ?>"
                            >
                            <i class="fa fa-trash" aria-hidden="true"></i>
                          </a>
                        </div>
                      </td>
                    </tr>
                  <?php
                    $i++;
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
          <form action="<?= base_url() ?>addNotification" id="" method="post">
            <div class="offcanvas offcanvas-end offcanvas-booking" tabindex="-1" id="form-offcanvas"
              aria-labelledby="form-offcanvasLabel" aria-modal="true" role="dialog"
              style=" width: 50%;">
              <div class="offcanvas-header border-bottom">
                <h5 class="offcanvas-title" id="form-offcanvasLabel">Add Notification</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
              </div>
              <div class="offcanvas-body">
                <div class="row g-3">
                  <div class="col-md-6">
                    <label for="FirstName" class="form-label">Label <span class="text-danger">*</span></label>
                    <input type="hidden" name="id" value="" id="id">
                    <input type="hidden" name="module" value="service_providers" id="module">
                    <input type="text" class="form-control" name="label" id="label" placeholder="Enter Label">
                  </div>
                  <div class="col-md-6">
                    <label for="Email" class="form-label">Type <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="type" id="type" placeholder="Enter Type">
                  </div>
                  <div class="col-md-12 d-flex align-items-center">
                    <label for="category-status" class="form-label me-3">Status</label>
                    <div class="form-check form-switch">
                      <input class="form-check-input" name="status" type="checkbox" id="category-status">
                    </div>
                  </div>
                </div>
              </div>
              <div class="offcanvas-footer border-top p-3">
                <button type="submit" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="offcanvas">Cancel</button>
              </div>
            </div>
          </form>
          <form action="<?= base_url() ?>updateNotification" id="updateUser" method="post">
            <div class="offcanvas offcanvas-end offcanvas-booking" tabindex="-1" id="form-offcanvas1"
              aria-labelledby="form-offcanvasLabel" aria-modal="true" role="dialog"
              style=" width: 50%;">
              <div class="offcanvas-header border-bottom">
                <h5 class="offcanvas-title" id="form-offcanvasLabel">Edit Notification</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
              </div>
              <div class="offcanvas-body">
                <div class="row g-3">
                  <div class="col-md-6">
                    <label for="FirstName" class="form-label">Label <span class="text-danger">*</span></label>
                    <input type="hidden" name="id" value="" id="id">
                    <input type="hidden" name="module" value="notification_templates" id="module">
                    <input type="text" class="form-control" name="label" id="label" placeholder="Enter Label">
                  </div>
                  <div class="col-md-6">
                    <label for="Email" class="form-label">Type <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="type" id="type" placeholder="Enter Type">
                  </div>
                  <div class="col-md-12 d-flex align-items-center">
                    <label for="category-status" class="form-label me-3">Status</label>
                    <div class="form-check form-switch">
                      <input class="form-check-input" name="status" type="checkbox" id="category-status">
                    </div>
                  </div>
                </div>
              </div>
              <div class="offcanvas-footer border-top p-3">
                <button type="submit" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="offcanvas">Cancel</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <?= $this->include('admin/footer') ?>

</main>
<script>
   $(document).on('click', '.edit', function () {
    const dataId = $(this).data('id')
    const moduleId = $(this).data('module')
    const url = $(this).data('url')

    $.ajax({
      url: url,
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      data: {
        id: dataId,
        module: moduleId
      },
      success: function (response) {
        if (response.status === true) {
          var data = response.data
          $('#id').val(data.id)
          $('#label').val(data.title)
          $('#type').val(data.type)
          $('#category-status').prop('checked', data.status)
          console.log(response)
          $('#form-offcanvas1').offcanvas('show');
        } else {
          Swal.fire('Error!', 'Failed to get data.', 'error')
        }
      },
      error: function (xhr, status, error) {
        console.error('Error calling the provider:', error)
        Swal.fire('Error!', 'An error occurred. Please try again later.', 'error')
      }
    })
  })
</script>

<?= $this->endSection() ?>