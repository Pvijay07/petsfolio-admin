<?php echo $this->extend ( 'admin/includes/header' ); ?>
<!--start content-->
<?php echo $this->section ( 'content' ); ?>

<main class="main-content">
  <div class="position-relative iq-banner">
    <?php echo $this->include ( 'admin/nav_bar' ) ?>
  </div>
  <div class="conatiner-fluid content-inner mt-n5 py-0" id="book-rqst">
    <div class="row">
      <div class="col-xl-12 col-lg-8"></div>
      <div class="card mt-4">
        <div class="card-header d-flex justify-content-between">
          <div class="header-title">

            <h5 class="mb-0 card-title">⚠️ Payment Done – Booking Not Updated</h5>
          </div>
        </div>

        <div class="card-body">
          <?php if ( empty ( $unupdatedJobs ) ) : ?>
            <p class="text-muted">No unupdated paid bookings found.</p>
          <?php else : ?>
            <table class="table table-bordered table-sm">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Transaction ID</th>
                  <th>Service Name</th>
                  <th>Client</th>
                  <th>Pet</th>
                  <th>Service Provider</th>
                  <th>Amount</th>
                  <th>Payment Date</th>
                  <th>Payment Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                if ( !empty ( $unupdatedJobsn ) ) :
                  foreach ( $unupdatedJobs as $index => $job ) : ?>
                    <tr>
                      <td><?= $index + 1 ?></td>
                      <td><?= esc ( $job->transaction_id ) ?></td>
                      <td><?= esc ( $job->service_name ) ?></td>
                      <td><?= esc ( $job->user_name ) ?>
                        <br>
                        <?= esc ( $job->user_phone ) ?>
                      </td>
                      <?php
                      $petNames  = explode ( ',', $job->pet_names );
                      $petBreeds = explode ( ',', $job->pet_breeds );
                      ?>
                      <td>
                        <?php echo implode ( ', ', $petNames ); ?>
                        <br>
                        <?php echo implode ( ', ', $petBreeds ); ?>
                      </td>

                      <td></td>
                      <td><?= esc ( $job->amount ) ?></td>
                      <td><?= esc ( $job->updated_at ) ?></td>
                      <td><span class="badge bg-secondary"><?= esc ( $job->status ) ?></span></td>
                      <td>
                        <button class="btn btn-success btn-sm markActiveBtn" data-id="<?= $job->id ?>">
                          Mark Active
                        </button>
                      </td>
                    </tr>
                  <?php endforeach;
                else : ?>

                  <td>No Data Available</td>
                <?php endif; ?>
              </tbody>
            </table>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
  </div>
  <?php echo $this->include ( 'admin/footer' ) ?>
  <script src="https://unpkg.com/@popperjs/core@2"></script>
  <script src="https://unpkg.com/tippy.js@6"></script>
  <script>
    $(document).on('click', '.markActiveBtn', function () {
      const jobId = $(this).data('id');
      if (!confirm('Are you sure you want to mark this job as Active?')) return;

      $.ajax({
        url: '<?= base_url ( 'admin/jobs/markActive' ) ?>',
        method: 'POST',
        data: {
          job_id: jobId
        },
        dataType: 'json',
        success: function (response) {
          alert(response.message);
          if (response.status === 'success') {
            location.reload();
          }
        }
      });
    });
  </script>

</main>

<?php echo $this->endSection () ?>