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
              <h3 class="card-title">Feedback</h3>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table id="example" class="table table-striped" data-toggle="data-table">
                <thead>
                  <tr>
                    <th>Id</th>
                    <th>S.No</th>
                    <th>User Type</th>
                    <th>User</th>
                    <th>Rating</th>
                    <th>Comment</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $i = 1;
                  foreach ($feedbacks as $feedback) {
                  ?>
                    <tr>
                      <td><?= $feedback->id ?></td>
                      <td><?= $i ?></td>
                      <td><?= $feedback->user_type ?></td>
                      <td><?= $feedback->user_name ?></td>
                      <td><?= $feedback->rating ?></td>
                      <td><?= $feedback->comment ?></td>
                    </tr>
                  <?php $i++;
                  } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?= $this->include('admin/footer') ?>

</main>

<?= $this->endSection() ?>