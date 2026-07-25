<link href="<?=base_url()?>assets/css/theme-default.css" rel="stylesheet" />

<!--start content-->
<main class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3"><?=$title?></div>
        <div class="ms-auto">
            <a href="<?=base_url("index.php/admin/reviews")?>" class="btn btn-primary"><i class="bi bi-list"></i> View Reviews</a>
        </div>
    </div>
    <!--end breadcrumb-->
    <div class="row">
        <div class="card">
            <div class="card-body">
                <div class="border p-3 rounded">
                    <?php $this->load->view("admin/includes/_messages"); ?>
                    <form class="row g-3" action="<?=base_url('index.php/admin/reviews/edit/'.$review_info->id)?>" method="post"
                        enctype="multipart/form-data">

                        <div class="col-6">
                            <label class="form-label">Select Guru</label>
                            <select class="form-select" name="guru" data-validation="required">
                                <option value="0">Select Guru</option>
                                <?php foreach($gurus as $guru){ ?>
                                <option value="<?=$guru->id?>"
                                    <?php if($review_info->guru_id == $guru->id) echo 'selected'; ?>><?=$guru->name?>
                                </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Select Student</label>
                            <select class="form-select" name="student" data-validation="required">
                                <option value="0">Select Student</option>
                                <?php foreach($students as $student){ ?>
                                <option value="<?=$student->id?>"
                                    <?php if($review_info->student_id == $student->id) echo 'selected'; ?>>
                                    <?=$student->name?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Display Status</label>
                            <select name="status" class="form-select" data-validation="required">
                                <option value="0" <?php if($review_info->status == 0) echo 'selected'; ?>>Hide</option>
                                <option value="1" <?php if($review_info->status == 1) echo 'selected'; ?>>Show</option>
                            </select>
                        </div>

                        <div class="col-6">
                            <label class="form-label">Select Rating </label>
                            <select name="rating" class="form-select" data-validation="required">
                                <option value="">Select Rating</option>
                                <option value="1" <?php if($review_info->rating == 1) echo 'selected'; ?>>1</option>
                                <option value="2" <?php if($review_info->rating == 2) echo 'selected'; ?>>2</option>
                                <option value="3" <?php if($review_info->rating == 3) echo 'selected'; ?>>3</option>
                                <option value="4" <?php if($review_info->rating == 4) echo 'selected'; ?>>4</option>
                                <option value="5" <?php if($review_info->rating == 5) echo 'selected'; ?>>5</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Review</label>
                            <textarea class="form-control" name="review"
                                data-validation="required"><?=$review_info->review?></textarea>
                        </div>

                        <div class="col-sm-9">
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

<script>
$.validate({
    modules: 'file'
});
</script>