<?php echo $this->extend('admin/includes/header'); ?>
<!--start content-->
<?php echo $this->section('content'); ?>

<main class="main-content">
  <div class="position-relative iq-banner">
    <!--Nav Start-->
    <?php echo $this->include('admin/nav_bar') ?>

  </div>
  <div class="conatiner-fluid content-inner mt-n5 py-0">
    <div class="row">
      <div class="col-sm-12">
        <div class="card">
          <div class="card-header d-flex justify-content-between">
            <div class="header-title">
              <h4 class="card-title">Tax</h4>
            </div>
          </div>
          <div class="card-body">
            <div class="col-auto">
              <button type="button" class="btn btn-primary d-flex align-items-center gap-1 add" title="Create Duration"
                data-bs-toggle="offcanvas" data-bs-target="#send_push_notification">
                <i class="fa fa-plus" aria-hidden="true"></i>
                New
              </button>
            </div>
            <div class="table-responsive">
              <table id="datatable" class="table table-striped" data-toggle="data-table">
                <thead>
                  <tr>
                    <th>Pet Type</th>
                    <th>No Of Breeds</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  foreach ($pets as $pet) { ?>
                    <tr>
                      <td><?php echo $pet->name ?></td>
                      <td><?php echo $pet->status ?></td>
                      <td><?php echo $pet->status ?></td>
                      <td>
                        <div class="form-check form-switch ">
                          <input type="checkbox" data-url="<?php echo base_url() ?>updateStatus" data-module="taxes"
                            data-token="<?php echo csrf_hash(); ?>" class="switch-status-change form-check-input status"
                            id="datatable-row-<?php echo $pet->id; ?>" name="status" data-id="<?php echo $pet->id; ?>"
                            value="<?php echo $pet->status; ?>" <?php echo $pet->status == 1 ? 'checked' : ''; ?>>
                        </div>
                      </td>
                      <td>
                        <button type="button" class="fs-4 text-primary border-0 bg-transparent" data-bs-toggle="offcanvas"
                          data-bs-target="#form-offcanvas" aria-label="Edit" data-bs-original-title="Edit"
                          data-edit="<?php echo $pet->id; ?>" data-module="taxes" data-url="<?php echo base_url() ?>getUser"
                          data-id="<?php echo $pet->id; ?>">
                          <i class="fas fa-pencil-alt"></i>
                        </button>

                        <a href="<?php echo base_url() ?>deleteUser" id="delete-employees-<?php echo $pet->id; ?>"
                          class="fs-4 text-danger" data-type="ajax" data-method="DELETE" data-token="<?php echo csrf_hash(); ?>"
                          data-bs-toggle="tooltip" data-id="<?php echo $pet->id; ?>"
                          data-confirm="Are you certain you want to delete it?" aria-label="Delete"
                          data-bs-original-title="Delete">
                          <i class="fa fa-trash" aria-hidden="true"></i>
                        </a>

                      </td>
                    </tr>
                  <?php $i++;
                  } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <form action="<?php echo base_url() ?>tax/add" method="post" id="updateUser" class="">
          <div class="offcanvas offcanvas-end" id="send_push_notification" tabindex="-1"
            aria-labelledby="form-offcanvasLabel" role="dialog">
            <div class="offcanvas-header border-bottom">
              <h5 class="offcanvas-title" id="form-offcanvasLabel">Add Tax</h5>
              <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
              <div class="row">
                <div class="col-12">
                  <div class="form-group">
                    <label class="form-label" for="title">Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="title" id="title" placeholder="Enter tax title"
                      required>
                    <span class="text-danger"></span>

                    <label class="form-label mt-3" for="value">Value <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="value" id="value" placeholder="Enter tax value"
                      required>

                    <label class="form-label mt-3" for="type">Type <span class="text-danger">*</span></label>
                    <select name="type" id="type" class="form-control" required>
                      <option value="">Select Type</option>
                      <option value="percentage">Percentage</option>
                      <option value="fixed">Fixed</option>
                    </select>

                    <label class="form-label mt-3" for="module">Module <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="module_type" id="module"
                      placeholder="Enter module name" required>

                    <label class="form-label mt-3" for="status">Status <span class="text-danger">*</span></label>
                    <select name="status" id="status" class="form-control" required>
                      <option value="">Select Status</option>
                      <option value="1">Active</option>
                      <option value="0">Inactive</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="offcanvas-footer border-top">
              <div class="d-grid d-md-flex gap-3 p-3">
                <button class="btn btn-primary d-flex align-items-center gap-1" id="save-button" type="submit">
                  Save <i class="fa-regular fa-paper-plane"></i>
                </button>
                <button class="btn btn-soft-primary d-flex align-items-center gap-1" type="button"
                  data-bs-dismiss="offcanvas">
                  Cancel <i class="icon-Arrow---Right-2"></i>
                </button>
              </div>
            </div>
          </div>
        </form>
        <form action="<?php echo base_url() ?>update" id="updateUser">
          <div class="offcanvas offcanvas-end offcanvas-booking" tabindex="-1" id="form-offcanvas"
            aria-labelledby="form-offcanvasLabel" role="dialog" style=" width: 50%;">
            <div class="offcanvas-header border-bottom">
              <h5 class="offcanvas-title" id="form-offcanvasLabel">Edit Client</h5>
              <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
              <div class="row g-3">
                <!-- First Name -->
                <div class="col-md-6">
                  <label for="FirstName" class="form-label">Title<span class="text-danger">*</span></label>
                  <input type="hidden" name="id" value="" id="id">
                  <input type="hidden" name="module" value="users" id="module">
                  <input type="text" class="form-control" name="title" id="title" placeholder="Enter tax title"
                    required>

                  <label class="form-label mt-3" for="value">Value <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="value" id="value" placeholder="Enter tax value"
                    required>
                </div>

                <div class="col-md-6">

                  <label class="form-label mt-3" for="module">Module <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="module_type" id="module" placeholder="Enter module name"
                    required>

                  <label class="form-label mt-3" for="type">Type <span class="text-danger">*</span></label>
                  <select name="type" id="type" class="form-control" required>
                    <option value="">Select Type</option>
                    <option value="percentage">Percentage</option>
                    <option value="fixed">Fixed</option>
                  </select>
                </div>

                <div class="col-md-12 d-flex align-items-center">
                  <label for="category-status" class="form-label me-3">Status</label>
                  <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="status" id="category-status">
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


  <?php echo $this->endSection() ?>