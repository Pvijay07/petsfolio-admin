<?= $this->extend('admin/includes/header'); ?>
<!--start content-->
<?= $this->section('content'); ?>

<main class="main-content">
    <div class="position-relative iq-banner">
        <!--Nav Start-->
        <?= $this->include('admin/nav_bar') ?>

    </div>
    <div class="conatiner-fluid content-inner mt-n5 py-0">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <!-- <h4 class="card-title">Bookings</h4> -->
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="table-responsive">
                            <table id="example" class="table table-striped" data-toggle="data-table">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>S.No</th>
                                        <th>Name</th>
                                        <th>Contact Number</th>
                                        <th>Area</th>
                                        <th>Verification Status</th>
                                        <th>Status</th>
                                        <th>Created at</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    foreach ($providers as $walker) {

                                    ?>
                                        <tr>
                                            <td><?php echo $walker->id; ?></td>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo $walker->name; ?></td>
                                            <td><?php echo $walker->phone; ?></td>
                                            <td><?php echo $walker->area; ?></td>
                                            <td><?php echo $walker->verification ?? '--'; ?></td>
                                            <td><?php echo $walker->status; ?></td>
                                            <td><?php echo $walker->created_at ?></td>

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
            </div>
        </div>
    </div>
    <?= $this->include('admin/footer') ?>

</main>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script>
    $(document).ready(function() {
        $(document).ready(function() {
            $('#example').DataTable({
                order: [
                    [0, 'desc']
                ],
                columnDefs: [{
                    targets: 0,
                    visible: false
                }],
                paging: true,
                searching: true,
                ordering: true,
                lengthMenu: [5, 10, 15],
                pageLength: 20,
            });
        });
    });
</script>
<?= $this->endSection() ?>