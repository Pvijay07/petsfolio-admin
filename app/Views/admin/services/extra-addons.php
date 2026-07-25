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
              <h4 class="card-title">Addons</h4>
            </div>
          </div>
          <div class="card-body">
            <div class="d-flex justify-content-between flex-column flex-md-row gap-3">
              <div class="btn-toolbar gap-2 align-items-stretch flex-column flex-md-row" role="toolbar"
                aria-label="Toolbar with buttons">
                <button type="button" class="btn btn-primary d-flex align-items-center gap-1 add" data-crud-id="0"
                  title="Create Duration">
                  <i class="fa fa-plus" aria-hidden="true"></i>
                  New
                </button>
              </div>
            </div>
            <div class="table-responsive">
              <table id="example" class="table table-striped" data-toggle="data-table">
                <thead>
                  <tr>
                    <th>Id</th>
                    <th>S.No</th>
                    <th>Addon Name</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $i = 1;
                  foreach ($addons as $addon) {
                  ?>
                    <tr>
                      <td><?php echo $addon->id; ?></td>
                      <td><?php echo $i; ?></td>
                      <td><?php echo $addon->name; ?></td>
                      <td>
                        <div class="form-check form-switch">
                          <input type="checkbox" data-url="<?= base_url() ?>updateStatus" data-token=""
                            class="switch-status-change form-check-input status" id="datatable-row-<?= $addon->id; ?>"
                            name="status" value="<?= $addon->status; ?>" data-id="<?= $addon->id; ?>" data-module="addons"
                            <?= $addon->status == 1 ? 'checked' : ''; ?>>
                        </div>
                      </td>
                      <td>
                        <div class="d-flex gap-2 align-items-center">
                          <button type="button" class="fs-4 text-primary border-0 bg-transparent open-popup"
                            data-url="<?= base_url() ?>deleteAddon" data-bs-toggle="offcanvas"
                            data-bs-target="#form-offcanvas" aria-label="Edit" data-bs-original-title="Edit"
                            data-id="<?= $addon->id; ?>" data-name="<?= $addon->name; ?>"
                            data-price="<?= $addon->name; ?>" data-status="<?= $addon->status; ?>">
                            <i class="fas fa-pencil-alt"></i>
                          </button>
                          <a href="<?= base_url() ?>deleteAddon" id="delete-employees-<?= $addon->id; ?>"
                            data-id="<?= $addon->id; ?>" class="fs-4 text-danger" data-type="ajax" data-method="DELETE"
                            data-token="<?= csrf_hash(); ?>" data-bs-toggle="tooltip"
                            data-confirm="Are you certain you want to delete it?" aria-label="Delete"
                            data-bs-original-title="Delete">
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
              <div class="popup" id="popup">
                <div class="popup-content">
                  <img src="<?= base_url() ?>public/assets/images/close.png" alt="Close" class="close-icon" />
                  <h2>Edit Addon</h2>
                  <form id="addonForm" action="<?= base_url('updateAddon') ?>">
                    <input type="hidden" id="addonId" name="addonId">

                    <input type="text" id="addonName" name="addonName" placeholder="Addon Name" required>
                    <!-- <input type="text" id="addonPrice" name="addonPrice" placeholder="Addon Price" required> -->

                    <select id="status" name="status" required>
                      <option value="">Select Status</option>
                      <option value="1">Active</option>
                      <option value="0">Inactive</option>
                    </select>
                    <button type="submit">Save</button>
                  </form>
                </div>
              </div>
              <div class="addpopup" id="addpopup">
                <div class="popup-content">
                  <img src="<?= base_url() ?>public/assets/images/close.png" alt="Close" class="close-icon" />
                  <h2>Add Addon</h2>
                  <form id="addAddonForm" action="<?= base_url('addAddon') ?>">
                    <input type="hidden" id="addonId" name="addonId">
                    <input type="text" id="addonName" name="addonName" placeholder="Addon Name" required>
                    <!-- <input type="text" id="addonPrice" name="addonPrice" placeholder="Addon Price" required> -->
                    <select id="status" name="status" required>
                      <option value="">Select Status</option>
                      <option value="1">Active</option>
                      <option value="0">Inactive</option>
                    </select>
                    <button type="submit">Save</button>
                  </form>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
  </div>
  <?= $this->include('admin/footer') ?>

</main>

<script>
  $(document).ready(function() {

    $('.add').on('click', function() {
      var id = $(this).attr('id');
      var name = $(this).attr('name');
      var price = $(this).attr('price');
      var status = $(this).attr('status');
      var package = $(this).attr('package_id');

      $('#addpopup').css('display', 'flex');
    })
    // Function to open the popup and bind data to the form
    function openPopup(id, name, price, status) {
      $('#popup').css('display', 'flex');
      $('#addonId').val(id);
      $('#addonName').val(name);
      $('#status').val(status);
    }

    // Function to close the popup
    function closePopup() {
      $('#popup').hide();
      // Clear form data (optional)
      $('#addonForm')[0].reset();
    }

    // Open popup event
    $(document).on('click', '.open-popup', function() {
      const id = $(this).data('id');
      const name = $(this).data('name');
      const status = $(this).data('status');
      const price = $(this).data('price');

      openPopup(id, name, price, status);
    });

    // Close popup on clicking the close icon
    $(document).on('click', '.close-icon', function() {
      closePopup();
      $('#addpopup').hide();

    });

    // Close popup on clicking outside of popup content
    $(document).on('click', '#popup,#addpopup', function(e) {
      if (!$(e.target).closest('.popup-content').length) {
        closePopup();
        $('#addpopup').hide();
      }
    });

  });
</script>
<?= $this->endSection() ?>