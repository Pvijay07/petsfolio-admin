<!--start content-->
<main class="page-content">
   <!--breadcrumb-->
   <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
      <div class="breadcrumb-title pe-3"><?=$title?></div>
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
                     <th>Name</th>
                     <th>Phone</th>
                     <th>Email</th>
                     <th>Submitted Date & Time</th>
                  </tr>
               </thead>
               <tbody>
                  <?php 
                  $i=1;
                  foreach($enquiries as $enquiry){ 
                  ?>
                  <tr>
                     <td><?php echo $i; ?></td>
                     <td><?=$enquiry->name?></td>
                     <td><?=$enquiry->mobile?></td>
                     <td><?=$enquiry->email?></td>
                     <td><?=$enquiry->created_at?></td>                     
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