<link href="<?=base_url()?>assets/css/theme-default.css" rel="stylesheet" />

<!--start content-->
<main class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3"><?=$title?></div>
        <div class="ms-auto">
            <a href="<?=base_url("index.php/admin/providers")?>" class="btn btn-primary"><i class="bi bi-list"></i> View
                Service Providers</a>
        </div>
    </div>
    <!--end breadcrumb-->
    <div class="row">
        <div class="card">
            <div class="card-body">
                <div class="border p-3 rounded">
                    <?php $this->load->view("admin/includes/_messages"); ?>
                    <form class="row g-3" action="<?=base_url('index.php/admin/providers/edit/'.$provider->id)?>" method="post"
                        enctype="multipart/form-data">

                        <input type="hidden" id="latitude" name="latitude" value="">
                        <input type="hidden" id="longitude" name="longitude" value="">

                        <div class="col-6">
                            <label class="form-label">Service</label>
                            <select class="form-select" name="service" data-validation="required">
                                <option value="">Select Service</option>
                                <?php foreach($services as $service){ ?>
                                <option value="<?=$service->id?>"
                                    <?php if($provider->service_id == $service->id) echo 'selected'; ?>>
                                    <?=$service->name?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="col-6">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" data-validation="required"
                                value="<?=$provider->name?>">
                        </div>

                        <div class="col-6">
                            <label class="form-label">Mobile No.</label>
                            <input type="text" class="form-control" name="phone" data-validation="required"
                                value="<?=$provider->phone?>">
                        </div>

                        <div class="col-6">
                            <label class="form-label">Email</label>
                            <input type="text" class="form-control" name="email" data-validation="email"
                                value="<?=$provider->email?>">
                        </div>

                        <div class="col-6">
                            <label class="form-label">Gender</label>
                            <select class="form-select" name="gender" data-validation="required">
                                <option value="">Select Gender</option>
                                <option value="Male" <?php if($provider->gender == "Male") echo "selected"; ?>>Male
                                </option>
                                <option value="Female" <?php if($provider->gender == "Female") echo "selected"; ?>>
                                    Female
                                </option>
                            </select>
                        </div>

                        <div class="col-6">
                            <label class="form-label">Age</label>
                            <input type="text" class="form-control" name="age" data-validation="required"
                                value="<?=$provider->age?>">
                        </div>

                        <div class="col-6">
                            <label class="form-label">Address</label>
                            <input type="text" class="form-control" name="location" data-validation="required"
                                value="<?=$provider->location?>">
                        </div>

                        <div class="col-6">
                            <label class="form-label">Upload Photo</label>
                            <?php if(!empty($provider->photo)){ ?>
                            <a href="<?=$provider->photo?>" target="_blank" style="float:right">View Photo</a>
                            <?php } ?>
                            <input name="photo" class="form-control" type="file" data-validation="mime size" break0=""
                                data-validation-allowing="jpg, png, gif" break="" data-validation-max-size="5M" />
                            <input type="hidden" name="old_photo" value="<?=$provider->photo?>">
                        </div>

                        <div class="col-6">
                            <label class="form-label">Upload ID Card</label>
                            <?php if(!empty($provider->id_proof)){ ?>
                            <a href="<?=$provider->id_proof?>" target="_blank" style="float:right">View ID Card</a>
                            <?php } ?>
                            <input name="id_proof" class="form-control" type="file" data-validation="mime size"
                                break0="" data-validation-allowing="jpg, png, gif" break=""
                                data-validation-max-size="5M" />
                            <input type="hidden" name="old_id_proof" value="<?=$provider->id_proof?>">
                        </div>

                        <div class="col-6">
                            <label class="form-label">Upload Address Proof</label>
                            <?php if(!empty($provider->address_proof)){ ?>
                            <a href="<?=$provider->address_proof?>" target="_blank" style="float:right">View Address
                                Proof</a>
                            <?php } ?>
                            <input name="address_proof" class="form-control" type="file" data-validation="mime size"
                                break0="" data-validation-allowing="jpg, png, gif" break=""
                                data-validation-max-size="5M" />
                            <input type="hidden" name="old_address_proof" value="<?=$provider->address_proof?>">
                        </div>

                        <div class="col-6">
                            <label class="form-label">Status </label>
                            <select name="status" class="form-select" data-validation="required">
                                <option value="">Select Status</option>
                                <option value="1" <?php if($provider->status == 1) echo 'selected'; ?>>Active</option>
                                <option value="0" <?php if($provider->status == 0) echo 'selected'; ?>>Inactive</option>
                            </select>
                        </div>

                        <div class="col-6">
                            <label class="form-label">Verify Status </label>
                            <select name="verify" class="form-select" data-validation="required">
                                <option value="">Select Status</option>
                                <option value="1" <?php if($provider->is_verify == 1) echo 'selected'; ?>>Verified
                                </option>
                                <option value="0" <?php if($provider->is_verify == 0) echo 'selected'; ?>>Not Verify
                                </option>
                            </select>
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