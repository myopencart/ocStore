<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><i class="fa fa-remove"></i> <?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php /* if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } */ ?>
    <div class="panel panel-default">
      <div class="panel-body">
        <h3><?php echo $text_delete; ?></h3>
        <a data-toggle="tooltip" title="<?php echo $text_title_image; ?>" class="btn btn-danger cache" data-type="image"><i class="fa fa-file-image-o"></i> <?php echo $button_image; ?></a>
        <a data-toggle="tooltip" title="<?php echo $text_title_system; ?>" class="btn btn-danger cache" data-type="system"><i class="fa fa-code"></i> <?php echo $button_system; ?></a>
        <a href="<?php echo $back; ?>" data-toggle="tooltip" title="<?php echo $button_back; ?>" class="btn btn-default"><i class="fa fa-reply"></i> <?php echo $button_back; ?></a></div>
        <br /><br />
        <textarea wrap="off" rows="15" readonly="readonly" class="form-control" id="cache_result"></textarea>
      </div>
    </div>
</div>

<script type="text/javascript"><!--
  $('.cache').on('click', function() {
      $.ajax({
        url: '<?php echo $action; ?>&token=<?php echo $token; ?>&type=' + $(this).data('type'),
        type: 'post',
        dataType: 'json',
        beforeSend: function() {
          $('#cache_result').text('');
        },

        success: function(json) {
          $('.alert-success, .alert-danger').remove();
                
          if (json['error']) {
            $('#content > .container-fluid').before('<div class="alert alert-danger" style="display: none;"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
            $('.alert-danger').slideDown().delay(2000).slideUp();
          }
          
          if (json['success']) {
              $('#cache_result').text(json['success']);
          }
        },

        error: function(xhr, ajaxOptions, thrownError) {
          alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
      });
  });
//--></script>

<?php echo $footer; ?>
