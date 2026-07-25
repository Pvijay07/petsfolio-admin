<link href="<?=base_url()?>assets/css/theme-default.css" rel="stylesheet" />

<!--start content-->
<main class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3"><?=$title?></div>
        <div class="ms-auto">
            <a href="<?=base_url("index.php/admin/services/")?>" class="btn btn-success"><i class="bi bi-list"></i> View
                Services</a>
        </div>
    </div>
    <!--end breadcrumb-->
    <div class="row">
        <div class="card">
            <div class="card-body">
                <div class="border p-3 rounded">
                    <?php $this->load->view('admin/includes/_messages.php') ?>
                    <form class="row g-3" method="post" action="<?=base_url("index.php/admin/services/edit/".$service->id)?>"
                        enctype="multipart/form-data">
                        <div class="col-6">
                            <label class="form-label">Service Name</label>
                            <input type="text" class="form-control" name="name" data-validation="required"
                                value="<?=$service->name?>">
                        </div>

                        <div class="col-6">
                            <label class="form-label">Select status</label>
                            <select name="status" class="form-select" data-validation="required">
                                <option value="">Select Status</option>
                                <option value="Active" <?php if($service->status == "Active") echo 'selected'; ?>>Active
                                </option>
                                <option value="Inactive" <?php if($service->status == "Inactive") echo 'selected'; ?>>
                                    Inactive
                                </option>
                            </select>
                        </div>

                        <div class="col-6">
                            <img src="<?=$service->icon?>" width="120" height="60">
                            <input name="image" class="form-control" type="file" data-validation="mime size" break0=""
                                data-validation-allowing="jpg, png, gif" break="" data-validation-max-size="5M" />
                            <input type="hidden" name="old_image" value="<?=$service->icon?>">
                        </div>

                        <div class="col-sm-9">
                            <input type="submit" name="submit" class="btn btn-primary px-5" value="Submit">
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
    modules: 'security'
});
</script>