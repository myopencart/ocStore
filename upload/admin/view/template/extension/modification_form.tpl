<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a type="button" onclick="update()" data-toggle="tooltip" title="<?php echo $button_update; ?>" class="btn btn-success"><i class="fa fa-check"></i></a>
        <button type="submit" form="form-modification" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-modification" class="form-horizontal">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
            <li><a href="#tab-backup" data-toggle="tab"><?php echo $tab_backup; ?></a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-general">
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
                <div class="col-sm-10">
                  <input type="text"  name="name" value="<?php echo isset($name) ? $name : ''; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-xml"><?php echo $entry_xml; ?></label>
                <div class="col-sm-10">
                  <textarea  name="xml" placeholder="<?php echo $entry_xml; ?>" id="input-xml" class="form-control CodeMirror"><?php echo isset($xml) ? $xml : ''; ?></textarea>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-backup">
              <div class="table-responsive">
                <table class="table table-bordered table-hover">
                  <thead>
                  <tr>
                    <td class="text-left"><?php echo $column_id; ?></td>
                    <td class="text-left"><?php echo $column_code; ?></td>
                    <td class="text-left"><?php echo $column_date_added; ?></td>
                    <td class="text-right"><?php echo $column_restore; ?> <a type="button" href="<?php echo $history; ?>" data-toggle="tooltip" title="<?php echo $button_history; ?>" class="btn btn-default"><i class="fa fa-trash"></i></a></td>
                  </tr>
                  </thead>
                  <tbody>
                  <?php if ($backups) { ?>
                  <?php foreach ($backups as $backup) { ?>
                  <tr>
                    <td class="text-left"><?php echo $backup['backup_id']; ?></td>
                    <td class="text-left"><?php echo $backup['code']; ?></td>
                    <td class="text-left"><?php echo $backup['date_added']; ?></td>
                    <td class="text-right"><a href="<?php echo $backup['restore']; ?>" data-toggle="tooltip" title="<?php echo $button_restore; ?>" class="btn btn-primary"><i class="fa fa-share-square"></i></a></td>
                  </tr>
                  <?php } ?>
                  <?php } else { ?>
                  <tr>
                    <td class="text-center" colspan="4"><?php echo $text_no_results; ?></td>
                  </tr>
                  <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
  // Initialize codemirrror
  var editor = CodeMirror.fromTextArea(document.querySelector('.CodeMirror'), {
    mode: 'xml',
    height: '300px',
    lineNumbers: true,
    autofocus: true,
    theme: 'xq-dark'
  });
  editor.setSize(null, 800);

  function update () {
      var action = $('form#form-modification').attr('action');
      $('form#form-modification').attr('action', action+"&update=1");
      $('form#form-modification').submit();
  }
--></script>
</div>
<?php echo $footer; ?>