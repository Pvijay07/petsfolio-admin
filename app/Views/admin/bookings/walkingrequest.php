<?= $this->extend('admin/includes/header'); ?>
<!--start content-->
<?= $this->section('content'); ?>

<main class="main-content">
  <div class="position-relative iq-banner">
    <?= $this->include('admin/nav_bar') ?>
  </div>
  <div class="conatiner-fluid content-inner mt-n5 py-0">
    <div class="row">
      <div class="col-sm-12">
        <div class="card">
          <div class="card-header d-flex justify-content-between">
            <div class="header-title">
              <!-- <h4 class="card-title">Bookings</h4> -->
            </div>
          </div>
          <div class="card-body">

            <div class="table-responsive">
              <table
                id="datatable"
                class="table table-striped"
                data-toggle="data-table">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>S.No.</th>
                    <th>Customer Name</th>
                    <th>Date</th>
                    <th>Pet Name</th>
                    <th>Pet Detail</th>
                    <th>Package Name</th>
                    <th>From date</th>
                    <th>To date</th>
                    <th>Timings</th>
                    <th>City</th>
                    <th>Quotations</th>
                    <th>Status</th>
                    <th>Payment Status</th>

                  </tr>
                </thead>
                <tbody>
                  <?php
                  $i = 1;
                  foreach ($bookings as $booking) {
                  ?>
                    <tr>
                      <td><?= $booking->id; ?></td>
                      <td><?= $i ?></td>
                      <td><?= $booking->user_name ?? '--' ?></td>
                      <td><?= date('d M, y - H:i', strtotime($booking->created_at)) ?></td>
                      <td><?= $booking->pet_name ?? '--' ?></td>
                      <td><?= $booking->type . ' (' . ($booking->breed) . ')' ?></td>
                      <td><?= $booking->package_name ?></td>
                      <td><?= date('m/d/y', strtotime($booking->service_start_date)) ?></td>
                      <td><?= date('m/d/y', strtotime($booking->service_end_date)) ?></td>
                      <td><?= $booking->preferable_time ?></td>
                      <td><?= $booking->city ?></td>
                      <td><a href="<?= base_url('bookings/walkingQuotes/' . $booking->id) ?>"><?= $booking->totalQuotes ?? 0 ?></a></td>
                      <td><?= $booking->status ?></td>
                      <td></td>

                    </tr>
                  <?php
                    $i++;
                  }
                  ?>
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