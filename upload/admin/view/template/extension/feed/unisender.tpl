<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <h3 class="panel-title"><img src="http://www.unisender.com/favicon.ico" alt="" /> </i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
	  
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="unisender_key"><span data-toggle="tooltip" title="<?php echo $entry_unisender_key_help; ?>"><?php echo $entry_unisender_key; ?></span></label>
            <div class="col-sm-10">
				<input id="unisender_key" type="text" class="form-control" name="unisender_key" value="<?php echo $unisender_key; ?>" required/>
				<a href="http://www.unisender.com/?a=opencart" target="_blank"><?php echo $text_get_key; ?></a>
				<div id="key_td" style="width:100%;">
              <?php if (isset($_error['unisender_key'])) { ?>
              <span class="error alert alert-danger col-sm-12" id="key_error"><?php echo $_error['unisender_key']; ?></span>
              <?php } ?>			
			    </div>
            </div>
          </div>
          
          <div class="form-group">
            <label class="col-sm-2 control-label" for="subscribtion_selector"><span data-toggle="tooltip" title="<?php echo $entry_unisender_subscribtion_help; ?>"><?php echo $entry_unisender_subscribtion; ?></span></label>
            <div class="col-sm-10">
              <select name="unisender_subscribtion[]" id="subscribtion_selector" size="10" class="form-control" multiple>
              </select>
			  <br/><a id="unselect_subscribtions" href="javascript:void(0);"><?php echo $text_unselect; ?></a>
            </div>
          </div>
		  
          <div class="form-group">
			<div class="col-sm-2"></div>
            <div class="col-sm-10">
			<input type="checkbox" id="input-ignore" name="unisender_ignore" value="1" <?php echo $unisender_ignore ? 'checked' : ''; ?>/>
			<label class="control-label" for="input-ignore"><?php echo $entry_unisender_ignore; ?></label>
            </div>
          </div>
		  
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_unisender_status; ?></label>
            <div class="col-sm-10">
              <select name="unisender_status" id="input-status" class="form-control">
                <?php if ($unisender_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
			  <a href="<?php echo $export; ?>"><?php echo $text_export; ?></a>
            </div>
          </div>
      </form>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#unselect_subscribtions').click(function() {
		$('#subscribtion_selector option').removeAttr('selected');
		return false;
	})
	var subscribtions = {};
<?php 
if (is_array($unisender_subscribtion)) {
	foreach($unisender_subscribtion as $s) { ?>
	subscribtions['<?=$s;?>'] = true;
<?php 
	}
} ?>
	$('#unisender_key').change(function() {
		$('#subscribtion_selector').html('');
		$('#key_error').remove();
		$('#subscribtion_selector').removeAttr('readonly');
		$('#unisender_key').removeAttr('readonly');
		var err_codes = {
			invalid_api_key: 'Указан неправильный ключ доступа к API. Проверьте, совпадает ли значение ключа со значением, указанным в личном кабинете.',
			access_denied: 'Доступ запрещён. Проверьте, включён ли доступ к API в личном кабинете Unisender.'
		};
		if ($(this).val()) {
			$('#subscribtion_selector').attr('readonly', 'readonly');
			$('#unisender_key').attr('readonly', 'readonly');
			$.getJSON('index.php?route=extension/feed/unisender/subscribtions&key='+$(this).val()+'&token=<?=$token;?>', function(data) {
				$('#subscribtion_selector').removeAttr('readonly');
				$('#unisender_key').removeAttr('readonly');
				if (data.error) {
					var err_text = (err_codes[data.code] ? err_codes[data.code] : 'Ошибка взаимодействия с Unisender API');
					$('#key_td').html('<span class="error alert alert-danger col-sm-12" id="key_error">'+err_text+'</span>');
				}
				else {
					$.each(data.result, function() {
						var itm = $(this)[0];
						$('#subscribtion_selector').append('<option value="' + itm.id + '"' + (subscribtions[itm.id.toString()] ? ' selected' : '') + '>' + itm.title + '</option>');
					})
				}
			})
		}
	})
	$('#unisender_key').trigger('change');
})
//--></script> 
<?php echo $footer; ?>
