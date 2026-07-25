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
                              <th>S.No.</th>
                              <th>Date</th>
                              <th>Time</th>
                              <th>Client Name</th>
                              <th>Pet Name</th>
                              <th>Package Name</th>
                              <th>Type</th>
                              <th>Walks</th>
                              <th>From date</th>
                              <th>To date</th>
                              <th>Timings</th>
                              <th>Type</th>
                              <th>Area</th>
                              <th>City</th>
                              <th>Quotations</th>
                              <th>Status</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php
                           $i = 1;
                           foreach ($bookings as $booking) {
                           ?>
                              <tr>
                                 <td><?= $i ?></td>
                                 <td><?= date('d M, y', strtotime($booking->created_at)) ?></td>
                                 <td><?= date('H:i', strtotime($booking->created_at)) ?></td>
                                 <td><?= $booking->user_name ?? '--' ?></td>
                                 <td><?= $booking->pet_name ?? 0 ?></td>
                                 <td><?= $booking->package_name ?></td>
                                 <td><?= $booking->service_days ?></td>
                                 <td><?= $booking->service_frequency ?></td>
                                 <td><?= $booking->service_start_date ?></td>
                                 <td><?= $booking->service_end_date ?></td>
                                 <td><?= $booking->preferable_time ?></td>
                                 <td><?= $booking->type ?></td>
                                 <td><?= $booking->address ?></td>
                                 <td><?= $booking->city ?></td>
                                 <td><?= $booking->totalQuotes ?? 0 ?></td>
                                 <td><?= $booking->status ?></td>
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
</main>

<?= $this->endSection() ?>