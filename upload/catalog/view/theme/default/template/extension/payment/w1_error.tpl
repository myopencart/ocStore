<?php echo $header; ?>
<div class="container">
    <?php if ($error) { ?>
        <div class="alert alert-danger">
            <i class="fa fa-exclamation-circle"></i> <?php echo $error; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    <?php } ?>
</div>
<?php echo $footer; ?>
