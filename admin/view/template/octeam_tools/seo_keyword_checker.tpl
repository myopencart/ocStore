<?php echo $header; ?>

<style type="text/css">
input.save {
	cursor: pointer;
	max-height: 24px;
	height: 24px;
	padding: 12px;
	background: url('view/image/octeam/tool/save_inactive_32x32_grey.png');
	background-size:100%;
	border: 0px;
}
input.save:hover {
	background: url('view/image/octeam/tool/save_active_32x32_blue.png');
	background-size:100%;
}

input.delete {
	cursor: pointer;
	max-height: 24px;
	height: 24px;
	padding: 12px;
	background: url('view/image/octeam/tool/delete_inactive_32x32_grey.png');
	background-size:100%;
	border: 0px;
}
input.delete:hover {
	background: url('view/image/octeam/tool/delete_active_32x32_red.png');
	background-size:100%;
}
</style>

<div id="content">
	<div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
		<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>
	<?php if ($error_warning) { ?>
	<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>
	<div class="box">
		<div class="heading">
			<h1><img src="view/image/octeam/tool/<?php echo $tool; ?>.png" alt="<?php echo $tool; ?>" width="22" height="22" /> <?php echo $heading_title; ?></h1>
			<div class="buttons">
				<a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a>
				<a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a>
<!-- [w] update checker data __DO_NOT_EDIT__ :: begin //-->
				<span style="padding: 0px 15px 0px 15px;">||</span>
				<a id="version_update_checker" class="button" style="margin-left:0px;"><img src="view/image/webme/check_updates.png" style="cursor:pointer; height:12px; padding-right:5px;"><?php echo $text_check_updates; ?></a>
<!-- [w] update checker data __DO_NOT_EDIT__ :: end //-->
			</div>
		</div>
		<div class="content">
			
			<!-- [w] update checker data __DO_NOT_EDIT__ :: begin //-->
			<div id="ext_check_version_result"></div>
			<!-- [w] update checker data __DO_NOT_EDIT__ :: end //-->
			
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
			</form>
				<table class="form">
					<tr>
						<td>
							<a id="find_duplicates_ctrl" class="button"><span><?php echo $button_find_duplicates; ?></span></a>
							<br />
							<br />
							<div id="find_duplicates_result"></div>
						</td>
					</tr>
				</table>
		</div>
	</div>
</div>

<script type="text/javascript"><!--
$('#find_duplicates_ctrl').live('click', function(){
	$('#find_duplicates_result').html('');
	var close_image = '<img src="view/image/webme/close.png" alt="" class="close">';
	$.ajax({
			url: '<?php echo str_replace('&amp;', '&', $searchForDuplicatesUrl); ?>',
			dataType: 'json',
			success: function(data) {
				html = '<div class="error"><?php echo $error_unexpected_error; ?>'+close_image+'</div>';
				if (data.error) {
					html = '';
					html += '<div class="warning">'+data.error+''+close_image+'</div>';
					html += '<div id="actionIndication" style="clear:both;"></div>';
					html += '<div style="clear:both;">';
					html += '<table class="list">';
					//html += '<table class="form">';
						html += '<thead>';
						html += '<tr>';
						html += '    <td class="left">';
						html += '        <?php echo $column_type; ?>';
						html += '    </td>';
						html += '    <td class="left">';
						html += '        <?php echo $column_query; ?>';
						html += '    </td>';
						html += '    <td class="left">';
						html += '        <?php echo $column_keyword; ?>';
						html += '    </td>';
						html += '    <td class="right">';
						html += '        <?php echo $column_action; ?>';
						html += '    </td>';
						html += '</tr>';
						html += '</thead>';
					
					$.each(data.duplicates, function(i, item) {
						html += '<tr id="urlAlias_'+item.url_alias_id+'">';
						
						html += '    <td>';
						html += '        '+item.type+''+item.itemName+'';
						html += '        <input type="hidden" name="url_alias_id" value="'+item.url_alias_id+'">';
						html += '    </td>';
						html += '    <td>';
						//html += '        <input type="text" name="query" value="'+item.query+'">';
						html += '        '+item.query+'';
						html += '        <input type="hidden" name="query" value="'+item.query+'">';
						html += '    </td>';
						html += '    <td>';
						html += '        <input type="text" name="keyword" value="'+item.keyword+'" size="'+(item.keyword.length+10)+'">';
						html += '        <span class="help">'+item.keyword+'</span>';
						html += '    </td>';
						html += '    <td class="right" style="width:80px;">';
						html += '        <input type="button" id="urlAliasCtrlUpdate_'+item.url_alias_id+'" class="save" title="<?php echo $button_update; ?>" />';
						html += '        <input type="button" id="urlAliasCtrlDelete_'+item.url_alias_id+'" class="delete" title="<?php echo $button_delete; ?>" />';
						html += '    </td>';
						
						html += '</tr>';
					});
					
					html += '</table>';
					html += '</div>';
				}
				if (data.success) {
					html = '';
					html += '<div class="success">'+data.success+''+close_image+'</div>';
				}
				
				$('#find_duplicates_result').html(html);
			}
		});
	return false;
});

/* === url_alias editing === */
$('.save').live('click', function(){
	
	var ctrl_id = $(this).attr('id');
	var ctrl_id_parts = ctrl_id.split('_');
	
	var uaId = ctrl_id_parts[1];
	var uaKeyword = $('#urlAlias_'+uaId+'').find('input[name="keyword"]').val();
	
	// highlight colors
	var errorColor = '#FFD1D1';
	var successColor = '#BBDF8D';
	
	var close_image = '<img src="view/image/webme/close.png" alt="" class="close">';
	$.ajax({
		url: '<?php echo str_replace('&amp;', '&', $duplicateUpdateUrl); ?>',
		type: 'POST',
		data: 'url_alias_id='+uaId+'&keyword='+uaKeyword,
		dataType: 'json',
		beforeSend: function() {
			$('#actionIndication > .attention, #actionIndication > .error, #actionIndication > .warning, #actionIndication > .success').remove();
			
			var html_1 = '';
			html_1 = '<div class="attention"><img src="view/image/loading.gif" /><?php echo $text_wait; ?></div>';
			$('#actionIndication').html(html_1);
		},
		success: function(data) {
			if (data.error) {
				$('#urlAlias_'+uaId+'').css('border','#FF0000 1px solid');
				$('#urlAlias_'+uaId+'').find('input[name="keyword"]').css('border','#FF0000 1px solid');
				
					$('#actionIndication > .attention, #actionIndication > .error, #actionIndication > .warning, #actionIndication > .success').remove();
					alert(data.error);
				
				var options = {color: ''+errorColor+''};
				function saveCallbackError() {
				};
				$('#urlAlias_'+uaId+'').effect('highlight', options, 3000, saveCallbackError);
			}
			if (data.success) {
				$('#urlAlias_'+uaId+'').css('border','#008800 1px solid');
				$('#urlAlias_'+uaId+'').find('input[name="keyword"]').css('border','#008800 1px solid');
				var options = {color: ''+successColor+''};
				function saveCallbackSuccess() {
					alert(data.success);
					$('#actionIndication > .attention, #actionIndication > .error, #actionIndication > .warning, #actionIndication > .success').remove();
					$('#urlAlias_'+uaId+'').remove();
					
					// trigger duplicates search
					$('#find_duplicates_ctrl').trigger('click');
				};
				$('#urlAlias_'+uaId+'').effect('highlight', options, 3000, saveCallbackSuccess);
			}
		}
	});
	
	return false;
});

/* === url_alias deletion === */
$('.delete').live('click', function(){
	if (!confirm('<?php echo $text_confirm; ?>')) {
		return false;
	}
	
	var ctrl_id = $(this).attr('id');
	var ctrl_id_parts = ctrl_id.split('_');
	
	var uaId = ctrl_id_parts[1];
	
	// highlight colors
	var errorColor = '#FFD1D1';
	var successColor = '#BBDF8D';
	
	var close_image = '<img src="view/image/webme/close.png" alt="" class="close">';
	$.ajax({
		url: '<?php echo str_replace('&amp;', '&', $duplicateDeleteUrl); ?>',
		type: 'POST',
		data: 'url_alias_id='+uaId,
		dataType: 'json',
		beforeSend: function() {
			$('#actionIndication > .attention, #actionIndication > .error, #actionIndication > .warning, #actionIndication > .success').remove();
			
			var html_1 = '';
			html_1 = '<div class="attention"><img src="view/image/loading.gif" /><?php echo $text_wait; ?></div>';
			$('#actionIndication').html(html_1);
		},
		success: function(data) {
			if (data.error) {
				$('#urlAlias_'+uaId+'').css('border','#FF0000 1px solid');
				var options = {color: ''+errorColor+''};
				
					alert(data.error);
					$('#actionIndication > .attention, #actionIndication > .error, #actionIndication > .warning, #actionIndication > .success').remove();
				
				function deleteCallbackError() {
				};
				$('#urlAlias_'+uaId+'').effect('highlight', options, 3000, deleteCallbackError);
			}
			if (data.success) {
				$('#urlAlias_'+uaId+'').css('border','#008800 1px solid');
				var options = {color: ''+successColor+''};
				function deleteCallbackSuccess() {
					alert(data.success);
					$('#actionIndication > .attention, #actionIndication > .error, #actionIndication > .warning, #actionIndication > .success').remove();
					$('#urlAlias_'+uaId+'').remove();
					
					$('#find_duplicates_ctrl').trigger('click');
				};
				$('#urlAlias_'+uaId+'').effect('highlight', options, 3000, deleteCallbackSuccess);
			}
		}
	});
	
	return false;
});
//--></script>

<!-- [w] update checker data __DO_NOT_EDIT__ :: begin //-->
<style type="text/css">
.success .close, .warning .close, .attention .close, .information .close {
	float: right;
	padding-top: 4px;
	padding-right: 4px;
	cursor: pointer;
}
</style>

<script type="text/javascript"><!--
$('#version_update_checker').live('click', function(){
	$('#ext_check_version_result').html('');
	var close_image = '<img src="view/image/webme/close.png" alt="" class="close">';
	$.ajax({
			url: '<?php echo $updateCheckerUrl; ?>',
			dataType: 'json',
			success: function(data) {
				html = '<div class="error"><?php echo $error_check_update; ?>'+close_image+'</div>';
				if (data.error) {
					html = '';
					html += '<div class="warning">'+data.error+''+close_image+'</div>';
				}
				if (data.up_to_date) {
					html = '';
					html += '<div class="success">'+data.up_to_date+''+close_image+'</div>';
				}
				if (data.get_update) {
					html = '';
					html += '<div class="attention">'+data.get_update+''+close_image+'</div>';
				}
				
				$('#ext_check_version_result').html(html);
			}
		});
	return false;
});

$('.success img, .warning img, .attention img, .information img').live('click', function() {
	$(this).parent().fadeOut('slow', function() {
		$(this).remove();
	});
});
//--></script>
<!-- [w] update checker data __DO_NOT_EDIT__ :: end //-->

<?php echo $footer; ?>