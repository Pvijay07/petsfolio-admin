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
                            <h4 class="card-title">Clients</h4>
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="table-responsive">
                            <table
                                id="example"
                                class="table table-striped"
                                data-toggle="data-table">
                                <thead>
                                    <tr>
                                        <th>Service</th>
                                        <th>Package</th>
                                        <th>Booking Date</th>
                                        <th>Booking Time</th>
                                        <!-- <th>Message</th> -->
                                        <th>Created at</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    foreach ($bookings as $booking) {
                                        $start_date = is_numeric($booking->service_start_date) ? $booking->service_start_date : strtotime($booking->service_start_date);
                                        $end_date = is_numeric($booking->service_end_date) ? $booking->service_end_date : strtotime($booking->service_end_date);


                                    ?>
                                        <tr>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo $booking->service_name ?></td>
                                            <td><?php echo $booking->package_name ?></td>
                                            <td><?php echo date('Y-m-d', $start_date) . ' - ' . date('Y-m-d', $end_date); ?>

                                            </td>
                                            <td><?php echo $booking->preferable_time ?></td>
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