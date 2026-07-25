<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!--print error messages-->
<?php if($this->session->flashdata('errors')): ?>
<div class="alert alert-danger">
    <?= $this->session->flashdata('errors')?>
</div>
<?php endif; ?>

<!--print custom error message-->
<?php if ($this->session->flashdata('error')): ?>
<div class="m-b-15">
    <div class="alert alert-danger alert-dismissable">
        <?php echo $this->session->flashdata('error'); ?>
    </div>
</div>
<?php endif; ?>

<!--print custom success message-->
<?php if ($this->session->flashdata('success')): ?>
<div class="m-b-15">
    <div class="alert alert-success alert-dismissable">
        <?php echo $this->session->flashdata('success'); ?>
    </div>
</div>
<?php endif; ?>