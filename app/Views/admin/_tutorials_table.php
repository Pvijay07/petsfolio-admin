<thead>
  <tr>
    <th>ID</th>
    <th>S.No.</th>
    <th>Link</th>
    <th>Title</th>
    <th>Description</th>
    <th>Thumbnail</th>
    <th>Created Date</th>
    <th>Actions</th>
  </tr>
</thead>
<tbody>
  <?php $i = 1; ?>
  <?php foreach ($filteredTutorials as $tutorial): ?>
    <tr>
      <td><?= $tutorial->id ?></td>
      <td><?= $i ?></td>

      <!-- Link as clickable -->
      <td>
        <a href="<?= esc($tutorial->link) ?>" target="_blank">
          <?= esc($tutorial->link) ?>
        </a>
      </td>
      <td>
        <?= esc($tutorial->title) ?>
      </td>
      <!-- Description -->
      <td>
        <small class="text-muted d-block"><?= esc(substr($tutorial->description, 0, 60)) ?>...</small>
      </td>

      <!-- Iframe Preview -->
      <td>
        <!-- <iframe src="<?= esc($tutorial->link) ?>" width="200" height="150" frameborder="0" allowfullscreen></iframe> -->

        <a href="<?= base_url('public/uploads/' . $tutorial->thumbnail) ?>" target="_blank"> <img src="<?= base_url('public/uploads/' . $tutorial->thumbnail) ?>" alt="" style="width: 80%;height:80%"></a><!-- <iframe width="560" height="315" src="<?= esc($tutorial->embedUrl) ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe> -->
      </td>

      <!-- Created Date -->
      <td><?= date('M d, Y', strtotime($tutorial->created_at)) ?></td>

      <!-- Actions -->
      <td>
        <a href="<?= esc($tutorial->link) ?>" target="_blank" class="btn btn-sm btn-info" data-toggle="tooltip" title="View Tutorial">
          <i class="fas fa-eye"></i>
        </a>
        <!-- <a href="/tutorials/download/<?= $tutorial->id ?>" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Download Materials">
          <i class="fas fa-download"></i>
        </a> -->
        <a href="javascript:void(0)"
          class="btn btn-sm btn-primary edit-tutorial"
          data-id="<?= $tutorial->id ?>"
          data-user_type="<?= $tutorial->user_type ?>"
          data-service_type="<?= $tutorial->service_type ?>"
          data-title="<?= $tutorial->title ?>"
          data-link="<?= $tutorial->link ?>"
          data-description="<?= $tutorial->description ?>">
          <i class="fas fa-edit"></i>
        </a>
        <a href="<?= site_url('tutorials/delete/' . $tutorial->id) ?>"
          class="btn btn-sm btn-danger"
          onclick="return confirm('Are you sure you want to delete this tutorial?');">
          <i class="fas fa-trash"></i>
        </a>
      </td>
    </tr>
    <?php $i++; ?>
  <?php endforeach; ?>
</tbody>