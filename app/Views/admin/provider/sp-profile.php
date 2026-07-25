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
                            <!-- <h4 class="card-title">Service Providers</h4> -->
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="table-responsive">
                            <table
                                id="datatable"
                                class="table table-striped"
                                data-toggle="data-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>S.No.</th>
                                        <th>Name</th>
                                        <th>Aadhaar Verification</th>
                                        <th>Police Verification</th>
                                        <th>Profile %</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    foreach ($providers as $provider) {

                                    ?>
                                        <tr>
                                            <td><?php echo $provider->id; ?></td>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo $provider->name; ?></td>
                                            <td><?php echo $provider->phone ?></td>
                                            <td><?php echo $provider->email ?></td>
                                            <td>
                                                <div class="progress">
                                                    <div class="progress-bar" id="progress-bar" style="width: <?= $provider->completionPercentage ?>%;">
                                                        <span><?= $provider->completionPercentage ?>%</span>
                                                    </div>
                                                </div>
                                            </td>

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
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const progressBar = document.getElementById('progress-bar');
        const percentage = progressBar.textContent.replace('%', '');
        progressBar.style.width = percentage + '%';
    });
</script>
<?= $this->endSection() ?>