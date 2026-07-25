<!--start content-->
<main class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3"><?=$title?></div>
        <div class="ms-auto">
            <a href="<?=base_url("index.php/admin/reviews/add")?>" class="btn btn-success"> <i class="bi bi-plus"></i> Add
                Review</a>
        </div>
    </div>
    <!--end breadcrumb-->
    <div class="card">
        <div class="card-body">
            <?php $this->load->view("admin/includes/_messages"); ?>
            <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>Guru Name</th>
                            <th>Student Name</th>
                            <th>Rating</th>
                            <th>Created at</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                  $i = 1;
                  foreach($reviews as $review){ 
                    $guru_info = $this->crud_model->get_by_id('ci_users',$review->guru_id);
                    $student_info = $this->crud_model->get_by_id('ci_users',$review->student_id);
                  ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?=$guru_info->name?></td>
                            <td><?=$student_info->name?></td>
                            <td><?=$review->rating?>/5</td>
                            <td><?=$review->created_at; ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-3 fs-6">
                                    <a href="<?=base_url("index.php/admin/reviews/edit/".$review->id)?>" class="text-warning"
                                        data-bs-toggle="tooltip" data-bs-placement="bottom" title=""
                                        data-bs-original-title="Edit info" aria-label="Edit"><i
                                            class="bi bi-pencil-fill"></i></a>
                                    <a href="<?=base_url("admin/reviews/delete/".$review->id)?>"
                                        onclick="return confirm('are you sure to delete?')" class="text-danger"
                                        data-bs-toggle="tooltip" data-bs-placement="bottom" title=""
                                        data-bs-original-title="Delete" aria-label="Delete"><i
                                            class="bi bi-trash-fill"></i></a>
                                </div>
                            </td>
                        </tr>
                        <?php $i++; } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
<!--end page main-->