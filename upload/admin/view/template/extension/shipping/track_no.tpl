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
	<?php if (!$track_no_status) { ?>
    <div class="alert alert-info"><i class="fa fa-info-circle"></i> После установки модуля зайдите в Менеджер дополнений и нажмите кнопку "Обновить"
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
	
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-3 control-label" for="input-status">Статус:</label>
            <div class="col-sm-7">
              <select name="track_no_status" id="input-status" class="form-control">			
                <?php if ($track_no_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
		  
		  <div class="form-group">
			<label class="col-sm-3 control-label" for="track_no_ignore_security"><span data-toggle="tooltip" title="Отключить проверку доступа к API при добавлении трек-номера">Не могу настроить доступ к API:</span></label>
			<div class="col-sm-7">
				<div class="checkbox">
					<label><input class="_form_flag" type="checkbox" name="track_no_ignore_security" value="1" <?php echo ($track_no_ignore_security ? ' checked="checked"' : ''); ?>/>
					<span style="color: red;">Используйте с осторожностью, в качестве временной меры!</span></label>
				</div>
			</div>
		  </div>
		  
		  <div class="form-group">
			<div class="col-sm-12"><h3>Что делать при добавлении трек-номера к заказу:</h3></div>
		  </div>
		  
		  <div class="form-group">
			<label class="col-sm-3 control-label" for="track_no_change_status">Менять статус заказа:</label>
			<div class="col-sm-7">
				<div class="checkbox">
					<label><input class="_form_flag" rel="_change_status" type="checkbox" name="track_no_change_status" value="1" <?php echo ($track_no_change_status ? ' checked="checked"' : ''); ?>/></label>
					</div>
			</div>
		  </div>
		  <div class="form-group _change_status">
			<label class="col-sm-3 control-label" for="track_no_order_status">Устанавливать статус:</label>
			<div class="col-sm-7">
			  <select name="track_no_order_status" class="form-control">			
				<?php foreach ($order_statuses as $order_status) { ?>
				<option value="<?php echo $order_status['order_status_id']; ?>"<?php echo ($order_status['order_status_id'] == $track_no_order_status ? ' selected="selected"' : ''); ?>><?php echo $order_status['name']; ?></option>
				<?php } ?>
              </select>
			</div>
		  </div>
		  
		  <div class="form-group">
			<label class="col-sm-3 control-label" for="track_no_email_notify"><span data-toggle="tooltip" title="При помощи добавления комментария в историю заказа">Уведомлять покупателя по E-mail:</span></label>
			<div class="col-sm-7">
				<div class="checkbox"><label><input class="_form_flag" rel="_email_notify" type="checkbox" name="track_no_email_notify" value="1" <?php echo ($track_no_email_notify ? ' checked="checked"' : ''); ?>/></div>
			  </div>
		  </div>
		  
		  <div class="form-group _email_notify">
			<label class="col-sm-3 control-label" for="track_no_email_text"><span data-toggle="tooltip" title="Подстановка в письмо: {order_id} - номер заказа, {track_no} - трек-номер, {shipping_firstname} и {shipping_lastname} - имя и фамилия покупателя.">Текст письма:</span></label>
			<div class="col-sm-7">
				<textarea name="track_no_email_text" rows="10" class="form-control"><?php echo $track_no_email_text; ?></textarea>
			</div>
		  </div>

		  <div class="form-group">
			<label class="col-sm-3 control-label" for="track_no_sms_notify"><span data-toggle="tooltip" title="Перейдите в раздел Система - Настройки - Мой магазин, и там настройте SMS-шлюз">Уведомлять покупателя по SMS:</span></label>
			<div class="col-sm-7">
				<div class="checkbox"><label><input class="_form_flag" rel="_sms_notify" type="checkbox" name="track_no_sms_notify" value="1" <?php echo ($track_no_sms_notify ? ' checked="checked"' : ''); ?>/>
				<?php if (!$sms_on) { ?><span style="color: red;">SMS-информирование у вас не настроено, либо не поддерживается. SMS отправляться не будут.</span><?php } ?>
				</div>
			</div>
		  </div>
		  
		  <div class="form-group _sms_notify">
			<label class="col-sm-3 control-label" for="track_no_sms_text"><span data-toggle="tooltip" title="Подстановка в SMS: {order_id} - номер заказа, {track_no} - трек-номер, {shipping_firstname} и {shipping_lastname} - имя и фамилия покупателя.">Текст SMS:</span></label>
			<div class="col-sm-7">
				<textarea name="track_no_sms_text" rows="5" class="form-control"><?php echo $track_no_sms_text; ?></textarea>
			</div>
		  </div>
		  
		  <div class="form-group">
			<label class="col-sm-3 control-label" for="track_no_export_liveinform"><span data-toggle="tooltip" title="LiveInform.ru - сервис отслеживания статусов доставки Почты России и уведомления покупателей.">Экспортировать в <a href="http://www.liveinform.ru/?partner=2324" target="_blank">LiveInform.ru</a>:</span></label>
			<div class="col-sm-7">
				<div class="checkbox"><label><input class="_form_flag" rel="_export_liveinform" type="checkbox" name="track_no_export_liveinform" value="1" <?php echo ($track_no_export_liveinform ? ' checked="checked"' : ''); ?>/>
				<span style="color: red;" data-toggle="tooltip" title="14 цифр для Почты России; либо 2 буквы, 9 цифр, 2 буквы для EMS; либо 10 цифр для СДЭК">Только если трек-номер Почты России, EMS или СДЭК</span>
				</div>
			</div>
		  </div>
		  
		  <div class="form-group _export_liveinform">
			<label class="col-sm-3 control-label" for="track_no_liveinform_api_id">API ID:</label>
			<div class="col-sm-7">
				<input type="text" name="track_no_liveinform_api_id" value="<?php echo $track_no_liveinform_api_id; ?>" class="form-control input-small" />
				<a href="http://www.liveinform.ru/?partner=2324">получить API ID</a>
			</div>
		  </div>
		  
		  <div class="form-group _export_liveinform">
			<label class="col-sm-3 control-label" for="track_no_liveinform_type">Тип отслеживания:</label>
			<div class="col-sm-7">
              <select name="track_no_liveinform_type" class="form-control">			
                <?php if ($track_no_liveinform_type == '1') { ?>
                <option value="1" selected="selected">1</option>
                <option value="2">2</option>
                <?php } else { ?>
                <option value="1">1</option>
                <option value="2" selected="selected">2</option>
                <?php } ?>
              </select>
			</div>
		  </div>
              
		  <div class="form-group _export_liveinform">
              <label class="col-sm-3 control-label" for="track_no_liveinform_sync">Синхронизировать статусы <a href="http://www.liveinform.ru/?partner=2324" target="_blank">LiveInform</a>:</label>
			<div class="col-sm-7">
				<div class="checkbox"><label><input class="_form_flag" rel="_liveinform_sync" type="checkbox" name="track_no_liveinform_sync" value="1" <?php echo ($track_no_liveinform_sync ? ' checked="checked"' : ''); ?>/>
				</div>
			</div>
		  </div>
              
		  <div class="form-group _export_liveinform _liveinform_sync">
              <label class="col-sm-3 control-label"><span data-toggle="tooltip" title="Запускать по планировщику через wget">Скрипт синхронизации:</span></label>
			<div class="col-sm-7">
				<a href="<?php echo $liveinform_sync_url; ?>" target="_blank"><?php echo $liveinform_sync_url; ?></a>
			</div>
		  </div>
              
		  <div class="form-group _export_liveinform _liveinform_sync">
            <label class="col-sm-3 control-label" for="order-statuses">Не обрабатывать заказы с этими статусами:</label>
            <div class="col-sm-7">
              <select name="track_no_order_statuses[]" id="order-statuses" size="15" multiple class="form-control">			
				<?php foreach ($order_statuses as $order_status) { ?>
				<option value="<?php echo $order_status['order_status_id']; ?>"<?php echo (in_array($order_status['order_status_id'], $track_no_order_statuses) ? ' selected="selected"' : ''); ?>><?php echo $order_status['name']; ?></option>
				<?php } ?>
              </select>
            </div>
		  </div>
		  
		  <div class="form-group _export_liveinform _liveinform_sync">
            <label class="col-sm-3 control-label" for="track_no_shipping_status">Статус заказа, когда он "В пути":</label>
            <div class="col-sm-7">
              <select name="track_no_shipping_status" class="form-control">			
				<?php foreach ($order_statuses as $order_status) { ?>
				<?php if ($order_status['order_status_id'] == $track_no_shipping_status) { ?>
				<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
				<?php } else { ?>
				<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
				<?php } ?>
				<?php } ?>
              </select>
            </div>
		  </div>
		  
		  <div class="form-group _export_liveinform _liveinform_sync">
            <label class="col-sm-3 control-label" for="track_no_postoffice_status">Статус заказа, когда он "На почте":</label>
            <div class="col-sm-7">
              <select name="track_no_postoffice_status" class="form-control">			
				<?php foreach ($order_statuses as $order_status) { ?>
				<?php if ($order_status['order_status_id'] == $track_no_postoffice_status) { ?>
				<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
				<?php } else { ?>
				<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
				<?php } ?>
				<?php } ?>
              </select>
            </div>
		  </div>
		  
		  <div class="form-group _export_liveinform _liveinform_sync">
            <label class="col-sm-3 control-label" for="track_no_issued_status">Статус заказа, когда он "Вручен":</label>
            <div class="col-sm-7">
              <select name="track_no_issued_status" class="form-control">			
				<?php foreach ($order_statuses as $order_status) { ?>
				<?php if ($order_status['order_status_id'] == $track_no_issued_status) { ?>
				<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
				<?php } else { ?>
				<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
				<?php } ?>
				<?php } ?>
              </select>
            </div>
		  </div>
		  
		  <div class="form-group _export_liveinform _liveinform_sync">
            <label class="col-sm-3 control-label" for="track_no_return_status">Статус заказа, когда "Возврат":</label>
            <div class="col-sm-7">
              <select name="track_no_return_status" class="form-control">			
				<?php foreach ($order_statuses as $order_status) { ?>
				<?php if ($order_status['order_status_id'] == $track_no_return_status) { ?>
				<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
				<?php } else { ?>
				<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
				<?php } ?>
				<?php } ?>
              </select>
            </div>
		  </div>
		  
		  <div class="form-group _export_liveinform _liveinform_sync">
			<label class="col-sm-3 control-label" for="track_no_sync_comment"><span data-toggle="tooltip" title="Подстановка в комментарий: {order_id} - номер заказа, {track_no} - трек-номер, {shipping_firstname} и {shipping_lastname} - имя и фамилия покупателя.">Текст комментария:</span></label>
			<div class="col-sm-7">
				<textarea name="track_no_sync_comment" rows="5" class="form-control"><?php echo $track_no_sync_comment; ?></textarea>
			</div>
		  </div>
		  
		  </div>
			
		<input type="hidden" name="track_no_set" value="1" />
      </form>
    </div>
  </div>
</div>
<script>
$(document).ready(function() {

$('._form_flag').change(function() {
	if ($(this).is(':checked')) {
		$('.'+$(this).attr('rel')).show();
	}
	else {
		$('.'+$(this).attr('rel')).hide();
	}
});
$('._form_flag').trigger('change');

var token = '';
// Login to the API
$.ajax({
	url: '<?php echo $store; ?>index.php?route=api/login',
	type: 'post',
	dataType: 'json',
	data: 'key=<?php echo $api_key; ?>',
	crossDomain: true,
	success: function(json) {
		$('.alert-api').remove();

        if (json['error']) {
    		if (json['error']['key']) {
    			$('#content > .container-fluid').prepend('<div class="alert alert-danger alert-api"><i class="fa fa-exclamation-circle"></i> ' + json['error']['key'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
    		}

            if (json['error']['ip']) {
    			$('#content > .container-fluid').prepend('<div class="alert alert-danger alert-api"><i class="fa fa-exclamation-circle"></i> ' + json['error']['ip'] + ' <button type="button" id="button-ip-add" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-danger btn-xs pull-right"><i class="fa fa-plus"></i> <?php echo $button_ip_add; ?></button></div>');
    		}
        }

        if (json['token']) {
			token = json['token'];
		}
	},
	error: function(xhr, ajaxOptions, thrownError) {
		alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
	}
});

$(document).delegate('#button-ip-add', 'click', function() {
	$.ajax({
		url: 'index.php?route=user/api/addip&token=<?php echo $token; ?>&api_id=<?php echo $api_id; ?>',
		type: 'post',
		data: 'ip=<?php echo $api_ip; ?>',
		dataType: 'json',
		beforeSend: function() {
			$('#button-ip-add').button('loading');
		},
		complete: function() {
			$('#button-ip-add').button('reset');
		},
		success: function(json) {
			$('.alert').remove();

			if (json['error']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			}

			if (json['success']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

})
</script>
<?php echo $footer; ?>
