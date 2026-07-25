<?= $this->extend('admin/includes/header'); ?>
<!--start content-->
<?= $this->section('content'); ?>
<!--start content-->
<main class="page-content">
   <!--breadcrumb-->
   <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
      <div class="breadcrumb-title pe-3">Bookings</div>
      <div class="ms-auto">

      </div>
   </div>
   <!--end breadcrumb-->
   <div class="card">
      <div class="card-body">
         <div class="table-responsive">
            <table id="example" class="table table-striped table-bordered" style="width:100%">
               <thead>
                  <tr>
                     <th>S.No.</th>
                     <th>Date</th>
                     <th>Time</th>
                     <th>Client Name</th>
                     <th>Pet Name</th>
                     <th>Package Name</th>
                     <th>Addons</th>
                     <th>Booking date</th>
                     <th>Booking time</th>
                     <th>Message</th>
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
                        <td><?= $booking->client_id ?></td>
                        <td><?= $booking->pet_id ?></td>
                        <td><?= $booking->package_id ?></td>
                        <td><?= $booking->extra_addons ?></td>
                        <td><?= $booking->booking_date ?></td>
                        <td><?= $booking->booking_time ?></td>
                        <td><?= $booking->message ?></td>
                        <td><?= $booking->city ?></td>
                        <td><?= $booking->quotations ?></td>
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
</main>
<!--end page main-->
<?= $this->endSection() ?>