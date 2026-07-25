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
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <div class="mb-3 text-end">
                                <label for="statusFilter" class="form-label me-2">Filter by Status:</label>
                                <select id="statusFilter" class="form-select d-inline-block" style="width: 200px;">
                                    <option value="">All</option>
                                    <option value="New">New</option>
                                    <option value="Confirmed">Confirmed</option>
                                    <option value="Cancelled">Cancelled</option>
                                    <option value="Completed">Completed</option>
                                </select>
                            </div>
                            <table id="example" class="table table-striped" data-toggle="data-table">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>S.No</th>
                                        <th>Client Name</th>
                                        <th>Pet Name</th>
                                        <th>Pet Detail</th>
                                        <th>Service</th>
                                        <th>Package</th>
                                        <th>Price</th>
                                        <th>Booking Date</th>
                                        <th>Booking Time</th>
                                        <th>Status</th>
                                        <th>Created at</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    foreach ($bookings as $booking) {
                                        $start_date = $booking->service_start_date;
                                        $end_date   = $booking->service_end_date;
                                    ?>
                                        <tr>
                                            <td><?php echo $booking->id; ?></td>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo $booking->user_name; ?></td>
                                            <td><?php echo $booking->pet_name; ?></td>
                                            <td><?php echo isset($booking) && isset($booking->type) && isset($booking->breed) ? $booking->type . ' (' . $booking->breed . ')' : '--'; ?></td>
                                            <td><?php echo $booking->service_name ?></td>
                                            <td><?php echo $booking->package_name ?></td>
                                            <td><?php echo $booking->total_price ?></td>
                                            <td><?php echo date('d/M/Y', timestamp: strtotime($start_date)) . ' - ' . date('d/M/Y', timestamp: strtotime($end_date)); ?></td>
                                            <td><?php echo $booking->preferable_time ?></td>
                                            <td><?php echo $booking->status ?></td>
                                            <td><?php echo $booking->created_at ?></td>
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
        var table = $('#example').DataTable({
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

        $('#statusFilter').on('change', function() {
            table.column(10).search(this.value).draw();
        });
    });
</script>
<?= $this->endSection() ?>