<div class="content">

<form action="<?php echo $action; ?>" method="get" id="payments" >
	<input type="hidden" name="shop" value="<?php echo $shop; ?>" />
	<input type="hidden" name="transaction" value="<?php echo $transaction; ?>" />
	<input type="hidden" name="successUrl" value="<?php echo $successUrl; ?>" />
	<input type="hidden" name="failUrl" value="<?php echo $failUrl; ?>" />

	<input type="hidden" name="qiwi_phone2" value="" />

	<div style="text-align: right;"><?php echo $markup; ?></div>


<?php if ($summ < 15000) { ?>
	<div style="text-align: right;"><?php echo $sub_text_info_phone; ?><input type="text" name="qiwi_phone" value="<?php echo $phone; ?>" size="15" maxlength="15"></div>
	<div style="text-align: right; color: red;" id="qiwi_error"></div>
	

	<div style="text-align: right;" ><?php echo $qiwi_continue; ?> <input type="checkbox" <?php if (isset($qiwi_rest_show_pay_now)) echo 'checked'; ?> name="qiwi_continue" id="qiwi_continue"></div>
	

<?php } else { ?>
	<center><?php echo $qiwi_rest_limit; ?></center>
<?php } ?>

</form>

<div class="buttons">
  <div class="pull-right">

<?php if ($summ < 15000) { ?>
    <input type="button" value="<?php echo $button_confirm; ?>" id="button-confirm" class="btn btn-primary" data-loading-text="<?php echo $text_loading; ?>" />
<?php } else { ?>
    <input type="button" value="<?php echo $button_back; ?>" id="payment_back" class="btn btn-primary" data-loading-text="<?php echo $text_loading; ?>" />
<?php } ?>

  </div>
</div>

</div>

<script type="text/javascript">

	function get_error(error_code) {
		switch (error_code) {
			case 5:
				return '<?php echo $qiwi_error_5;?>';
			break;

			case 13:
				return '<?php echo $qiwi_error_13;?>';
			break;

			case 78:
				return '<?php echo $qiwi_error_78;?>';
			break;

			case 150:
				return '<?php echo $qiwi_error_150;?>';
			break;

			case 152:
				return '<?php echo $qiwi_error_152;?>';
			break;

			case 210:
				return '<?php echo $qiwi_error_210;?>';
			break;

			case 215:
				return '<?php echo $qiwi_error_215;?>';
			break;

			case 220:
				return '<?php echo $qiwi_error_220;?>';
			break;

			case 241:
				return '<?php echo $qiwi_error_241;?>';
			break;

			case 242:
				return '<?php echo $qiwi_error_242;?>';
			break;

			case 298:
				return '<?php echo $qiwi_error_298;?>';
			break;

			case 300:
				return '<?php echo $qiwi_error_300;?>';
			break;

			case 303:
				return '<?php echo $qiwi_error_303;?>';
			break;

			case 316:
				return '<?php echo $qiwi_error_316;?>';
			break;

			case 319:
				return '<?php echo $qiwi_error_319;?>';
			break;

			case 341:
				return '<?php echo $qiwi_error_341;?>';
			break;

			case 700:
				return '<?php echo $qiwi_error_700;?>';
			break;

			case 702:
				return '<?php echo $qiwi_error_702;?>';
			break;

			case 1001:
				return '<?php echo $qiwi_error_1001;?>';
			break;

			case 1003:
				return '<?php echo $qiwi_error_1003;?>';
			break;

			case 1019:
				return '<?php echo $qiwi_error_1019;?>';
			break;


		}

		return '<?php echo $qiwi_error;?>' + error_code;
	}



	 $("#button-confirm").bind('click', function() {
			$.ajax({
		 		type: 'POST',
				url: 'index.php?route=extension/payment/qiwi_rest/confirm',
				dataType: 'json',
				data: 'qiwi_phone=' + encodeURIComponent($('input[name=\'qiwi_phone\']').val()), 
				success: function (json) {
					$('#qiwi_error').html('');

					if (json['error']!='no_error')
					{
						if (json['error']) {
 							$('#qiwi_error').html(get_error(json['error']));
						}
					}
					else
					{
						
						var checkedValue = document.getElementById("qiwi_continue").checked;
			     			if (checkedValue)
							$('#payments').submit();
						else
							location = '<?php echo $continue; ?>';

					}
	
				}	
			
			});
			return false;
	});



	$("#payment_back").click(function(event){
	 		location.href = 'index.php?route=checkout/cart'	
			return false;
	});



</script>
