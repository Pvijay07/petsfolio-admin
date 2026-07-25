<?= $this->extend('admin/includes/header'); ?>
<!--start content-->
<?= $this->section('content'); ?>
<main class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3"><?= $title ?></div>
        <div class="ms-auto">
            <a href="<?= base_url("packages/add") ?>" class="btn btn-success"> <i class="bi bi-plus"></i> Add
                Package</a>
        </div>
    </div>
    <!--end breadcrumb-->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Service</th>
                            <th>Package</th>
                            <th>General Addons</th>
                            <th>Extra Addons</th>
                            <th>Sessions</th>
                            <th>Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        foreach ($packages as $package) {
                        ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>
                                    <div class="d-flex align-items-center gap-3 fs-6">
                                        <a href="<?= base_url("index.php/admin/packages/edit/") ?>" class="text-warning"
                                            data-bs-toggle="tooltip" data-bs-placement="bottom" title=""
                                            data-bs-original-title="Edit info" aria-label="Edit"><i
                                                class="bi bi-pencil-fill"></i></a>
                                        <a href="<?= base_url("index.php/admin/packages/delete/") ?>"
                                            onclick="return confirm('are you sure to delete?')" class="text-danger"
                                            data-bs-toggle="tooltip" data-bs-placement="bottom" title=""
                                            data-bs-original-title="Delete" aria-label="Delete"><i
                                                class="bi bi-trash-fill"></i></a>
                                    </div>
                                </td>
                            </tr>
                        <?php $i++;
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
<!--end page main-->
<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/fixedcolumns/3.2.2/js/dataTables.fixedColumns.min.js"></script>
<script>
    $(document).ready(function() {
        var table = $('#example').DataTable({

            columnDefs: [{
                "width": "400px",
                "height": "150px",
                "targets": [5]
            }]
        });
    });
</script>
<?= $this->endSection() ?>