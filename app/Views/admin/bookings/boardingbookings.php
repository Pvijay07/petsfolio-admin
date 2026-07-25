<?= $this->extend('admin/includes/header'); ?>
<!--start content-->
<?= $this->section('content'); ?><main class="page-content">
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
                     <th>Add ons</th>
                     <th>From date</th>
                     <th>To date</th>
                     <th>Message</th>
                     <th>City</th>
                     <th>Quotations</th>
                     <th>Status</th>
                  </tr>
               </thead>
               <tbody>
                 <?php
                 $i = 1;
                 foreach($bookings as $booking){
                 ?>
                  <tr>
                     <td><?=$i?></td>
                     <td><?= date('d M, y', strtotime($booking->created_at)) ?></td>
                     <td><?= date('H:i', strtotime($booking->created_at)) ?></td>
                     <td><?=$booking->client_id?></td>
                     <td><?=$booking->pet_id?></td>
                     <td><?=$booking->add_ons?></td>
                     <td><?=$booking->from_date?></td>
                     <td><?=$booking->to_date?></td>
                     <td><?=$booking->message?></td>
                     <td><?=$booking->city?></td>
                     <td><?=$booking->quotations?></td>
                     <td><?=$booking->status?></td>
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
