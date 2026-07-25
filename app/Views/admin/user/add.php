<link href="<?=base_url()?>assets/css/theme-default.css" rel="stylesheet" />
<link href="<?=base_url()?>assets/plugins/input-tags/css/tagsinput.css" rel="stylesheet" />
<link rel='stylesheet' href='https://www.tiny.cloud/css/codepen.min.css'>

<!--start content-->
<main class="page-content">
   <!--breadcrumb-->
   <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
      <div class="breadcrumb-title pe-3"><?=$title?></div>
      <div class="ms-auto">
         <a href="<?=base_url("index.php/admin/users/index/".$user_type)?>" class="btn btn-primary">View <?=ucfirst($user_type).'s'?></a>
      </div>
   </div>
   <!--end breadcrumb-->
   <div class="row">
      <div class="card">
         <div class="card-body">
            <div class="border p-3 rounded">
            <?php $this->load->view("admin/includes/_messages"); ?>
               <form class="row g-3" action="<?=base_url('index.php/admin/users/add/'.$user_type)?>" method="post" enctype="multipart/form-data">
                  <div class="col-6">
                     <label class="form-label">Name</label>
                     <input type="text" class="form-control" name="name" data-validation="required">
                  </div>
                  
                  <div class="col-6">
                     <label class="form-label">Email</label>
                     <input type="email" class="form-control" name="email" data-validation="email">
                  </div>
                  
                  <div class="col-6">
                     <label class="form-label">Mobile No.</label>
                     <input type="number" class="form-control" name="phone" data-validation="required">
                  </div>

                  <div class="col-6">
                     <label class="form-label">Skype ID</label>
                     <input type="text" class="form-control" name="skype" data-validation="required">
                  </div>

                  <div class="col-6">
                     <label class="form-label">Password</label>
                     <input type="password" class="form-control" name="pass_confirmation" data-validation="length" data-validation-length="min6">
                  </div>
                  <div class="col-6">
                     <label class="form-label">Confirm Password</label>
                     <input type="password" class="form-control" name="pass" data-validation="confirmation">
                  </div>
                  <div class="col-6">
                     <label class="form-label">Status</label>
                     <select name="status" class="form-select">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                     </select>
                  </div>
                  <div class="col-6">
                     <label class="form-label">Upload Image</label>
                     <input name="image" class="form-control" type="file" data-validation="mime size" break0="" data-validation-allowing="jpg, png, gif" break=""
       data-validation-max-size="5M" />
                  </div>

                  <?php if($user_type == "teacher"){ ?>

                  <div class="col-6">
                     <label class="form-label">Qualification</label>
                     <input type="text" class="form-control" name="qualification" data-validation="required">
                  </div>
                  
                  <div class="col-6">
                     <label class="form-label">Skills</label>
                     <input type="text" name="skills" class="form-control" data-role="tagsinput" value="Training">
                  </div>

                  <div class="col-6">
                     <label class="form-label">Speaking Languages</label>
                     <input type="text" name="languages" class="form-control" data-role="tagsinput" value="English">
                  </div>

                  <div class="col-12">
                     <label class="form-label">About Me</label>
                     <textarea name="about" class="form-control"></textarea>
                  </div>

                     <h5>Courses</h5><hr>
                     <?php 
                     foreach($categories as $category) { 
                     $courses = $this->crud_model->get_by_arry('courses', array("category" => $category->id));  
                     ?>
                        <p><strong><?=$category->name?> :</strong></p>
                        <?php 
                         if(count($courses) != 0){ 
                           foreach($courses as $course) { ?>
                           <div class="col-3">
                                 <div class="form-check">
                                    <input class="form-check-input course_check" type="checkbox" name="courses[]" id="flexCheckDefault" value="<?=$course->id?>">
                                    <label class="form-check-label" for="flexCheckDefault"><?=$course->name?></label>
                                    <input type="number" class="form-control price_per_lesson<?=$course->id?>" name="prices[]" placeholder="Price per Lesson" style="display:none;" data-validation="required" data-validation="number" data-validation-allowing="float">
                                 </div>
                           </div>
                           <?php } } else echo '<p class="text-danger">No Courses found in this Category.</p>'; ?>
                     <?php  } ?>
                  

                 
                  <h5>Timing Availability</h5><hr>

                  <table class="table mb-0">
									<thead>
										<tr>
											<th scope="col"># Date</th>
											<th scope="col" colspan="2">Morning Sessions</th>
                                 <th scope="col" colspan="2">Evening Sessions</th>
										</tr>
									</thead>
									<tbody>
                              <?php 
                              $days_arry = array("monday", "tuesday", "wednesday", "thursday", "friday", "saturday", "sunday");
                              for($i=0; $i<7; $i++){ ?>
										<tr>
											<th scope="row"><div class="form-check">
                                    <input class="form-check-input days_check" type="checkbox" name="day[]" value="<?=date( 'Y-m-d', strtotime( $days_arry[$i].' this week' ) )?>" id="flexCheckDefault" checked>
                                    <input type="text" class="form-control week_date<?=date( 'Y-m-d', strtotime( $days_arry[$i].' this week' ) )?>" data-validation="required" name="dates[]" value="<?=date( 'Y-m-d', strtotime( $days_arry[$i].' this week' ) )?>" data-validation="required">
                                 </div></th>
											<td> 
                                    <select name="from_time1[]" class="form-select week_from1<?=date( 'Y-m-d', strtotime( $days_arry[$i].' this week' ) )?>" data-validation="required">
                                       <option value="">From Hours</option>
                                       <?php for($j=1; $j<24; $j++){ ?>
                                       <option value="<?=$j.':00';?>"><?=$j.':00';?></option>
                                       <?php } ?>
                                    </select>
                                 </td>
											<td>
                                    <select name="to_time1[]" class="form-select week_to1<?=date( 'Y-m-d', strtotime( $days_arry[$i].' this week' ) )?>" data-validation="required">
                                       <option value="">To Hours</option>
                                       <?php for($j=1; $j<=24; $j++){ ?>
                                       <option value="<?=$j.':00';?>"><?=$j.':00';?></option>
                                       <?php } ?>
                                    </select>
                                 </td>
											<td> 
                                 <select name="from_time2[]" class="form-select week_from2<?=date( 'Y-m-d', strtotime( $days_arry[$i].' this week' ) )?>" data-validation="required">
                                       <option value="">From Hours</option>
                                       <?php for($j=1; $j<=24; $j++){ ?>
                                       <option value="<?=$j.':00';?>"><?=$j.':00';?></option>
                                       <?php } ?>
                                    </select>
                                 </td>
											<td>
                                 <select name="to_time2[]" class="form-select week_to2<?=date( 'Y-m-d', strtotime( $days_arry[$i].' this week' ) )?>" data-validation="required">
                                       <option value="">To Hours</option>
                                       <?php for($j=1; $j<=24; $j++){ ?>
                                       <option value="<?=$j.':00';?>"><?=$j.':00';?></option>
                                       <?php } ?>
                                    </select>
                                 </td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
                        <?php } ?>

                  <div class="col-sm-12">
                     <input name="submit" type="submit" class="btn btn-primary px-5" value="Submit">
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
   <!--end row-->
</main>
<!--end page main-->

<script src="<?=base_url()?>assets/js/jquery.form-validator.js"></script>
<script src="<?=base_url()?>assets/plugins/input-tags/js/tagsinput.js"></script>

<script>
    $.validate({
        modules : 'security,file'
    });

</script>

<script src="https://cpwebassets.codepen.io/assets/common/stopExecutionOnTimeout-1b93190375e9ccc259df3a57c1abc0e64599724ae30d7ea4c6877eb615f89387.js"></script>

  <script src='https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=qagffr3pkuv17a8on1afax661irst1hbr4e6tbv888sz91jc'></script>
      <script id="rendered-js" >
tinymce.init({
  selector: 'textarea',
  height: 300,
  theme: 'modern',
  plugins: 'autolink print preview fullpage powerpaste searchreplace autolink directionality advcode visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak anchor toc insertdatetime advlist lists textcolor wordcount tinymcespellchecker a11ychecker imagetools mediaembed  linkchecker contextmenu colorpicker textpattern help',
  toolbar1: 'undo redo | bold italic strikethrough forecolor backcolor | formatselect blockquote | link image | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent | removeformat fullscreen',
  image_advtab: true,
  image_caption: true,
  image_list: [
  { title: 'Dog', value: 'mydog.jpg' },
  { title: 'Cat', value: 'mycat.gif' }],

  font_formats: 'Aileron=aileron, sans-serif;Helvetica=helvetica, arial;Lato=lato, sans-serif;Lobster=lobster, chicago, serif;Noto Serif=noto serif, serif;Permanent Marker=permanent marker, sans-serif;Raleway=raleway, sans-serif;Roboto=roboto, sans-serif;Source Code Pro=source code pro, monospace',
  templates: [
  { title: 'Test template 1', content: 'Test 1' },
  { title: 'Test template 2', content: 'Test 2' }],

  content_css: [
  'https://fonts.googleapis.com/css?family=Lato|Lobster|Noto+Serif|Permanent+Marker|Raleway|Roboto|Source+Code+Pro',
  '//www.tiny.cloud/css/codepen.min.css'],

  content_style: "blockquote{margin-left:100px; padding:10px;background:#f0f0f0}" });


    $('.course_check').click(function(){
      var val = $(this).attr("value");
         if (this.checked) {
            $('.price_per_lesson'+ val).show();
        }
        else {
            $('.price_per_lesson'+ val).hide();
        }   
    });

    $('.days_check').click(function(){
      var val = $(this).attr("value");
         if (this.checked) {
            $('.week_from1'+val).prop('disabled', false);
         $('.week_from2'+val).prop('disabled', false);
         $('.week_to1'+val).prop('disabled', false);
         $('.week_to2'+val).prop('disabled', false);
        }
        else {
         $('.week_from1'+val).prop('disabled', 'disabled');
         $('.week_from2'+val).prop('disabled', 'disabled');
         $('.week_to1'+val).prop('disabled', 'disabled');
         $('.week_to2'+val).prop('disabled', 'disabled');
        }   
    });

    </script>