<link href="<?=base_url()?>assets/css/theme-default.css" rel="stylesheet" />

<!--start content-->
<main class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3"><?=$title?></div>
        <div class="ms-auto">
            <a href="<?=base_url("index.php/admin/providers")?>" class="btn btn-primary"><i class="bi bi-list"></i> View
                Clients</a>
        </div>
    </div>
    <!--end breadcrumb-->
    <div class="row">
        <div class="col-12 col-lg-3">
            <div class="card shadow-sm border-0 overflow-hidden">
                <div class="card-body">
                    <div class="profile-avatar text-center">
                        <img src="<?=$client->profile_pic?>" class="rounded-circle shadow" width="120" height="120"
                            alt="">
                    </div>

                    <div class="text-center mt-4">
                        <h4 class="mb-1"><?=$client->name?></h4>
                        <p class="mb-0 text-secondary"><?=$client->phone?></p>
                        <p class="mb-0 text-secondary"><?=$client->email?></p>
                    </div>
                </div>
                <?php $this->load->view("admin/includes/clients-menu"); ?>
            </div>
        </div>
        <div class="col-12 col-lg-9">
            <div class="card shadow-sm border-0">
                <form class="row g-3" action="<?=base_url('index.php/admin/clients/edit/'.$client->id)?>" method="post"
                    enctype="multipart/form-data">
                    <div class="card-body">
                        <h5 class="mb-0"><?=$heading?></h5>
                        <hr>
                        <?php $this->load->view("admin/includes/_messages"); ?>
                        <div class="card shadow-none border">

                            <div class="card-body">
                                <row class="row g-3">
                                    <div class="col-6">
                                        <label class="form-label">Name</label>
                                        <input type="text" name="name" class="form-control" value="<?=$client->name?>"
                                            data-validation="required">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Email</label>
                                        <input type="text" name="email" class="form-control" value="<?=$client->email?>"
                                            data-validation="email">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Mobile No.</label>
                                        <input type="text" name="phone" class="form-control" value="<?=$client->phone?>"
                                            readonly>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Password</label>
                                        <input type="password" name="password" class="form-control">
                                    </div>

                                    <div class="col-6">
                                        <label class="form-label">Upload Photo</label>
                                        <?php if(!empty($client->profile_pic)){ ?>
                                        <a href="<?=$client->profile_pic?>" target="_blank" style="float:right">View
                                            Photo</a>
                                        <?php } ?>
                                        <input name="photo" class="form-control" type="file" data-validation="mime size"
                                            break0="" data-validation-allowing="jpg, png, gif" break=""
                                            data-validation-max-size="5M" accept="image/*" />
                                        <input type="hidden" name="old_photo" value="<?=$client->profile_pic?>">
                                    </div>

                                    <div class="col-6">
                                        <label class="form-label">Status</label>
                                        <select name="status" class="form-select">
                                            <option value="">Select Status</option>
                                            <option value="1" <?php if($client->is_active == 1) echo "selected"; ?>>
                                                Active
                                            </option>
                                            <option value="0" <?php if($client->is_active == 0) echo "selected"; ?>>
                                                Inactive
                                            </option>
                                        </select>
                                    </div>

                                </row>
                            </div>

                        </div>

                        <div class="text-start">
                            <input name="submit" type="submit" class="btn btn-primary px-5" value="Submit">
                        </div>
                    </div>
                </form>
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

<script>
if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(showPosition);
} else {
    alert("Geolocation is not supported by this browser.");
}

function showPosition(position) {
    document.getElementById("latitude").value = position.coords.latitude;
    document.getElementById("longitude").value = position.coords.longitude;
}
</script>