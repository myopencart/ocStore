<div class="well well-sm">
  <p>
    <?php echo $text_instruction; ?><br /><br />
    <?php echo $text_printpay; ?><br /><br />
    <?php echo $text_payment; ?><br /><br />
    <?php if ($text_order_history) { ?>
    <?php echo $text_order_history; ?><br /><br />
    <?php } ?>
    <?php echo $text_payment_comment; ?>
  </p>
</div>
<div class="buttons">
  <div class="pull-right">
    <input type="button" value="<?php echo $button_confirm; ?>" id="button-confirm" class="btn btn-primary" />
  </div>
</div>
<script type="text/javascript"><!--
$('#button-confirm').on('click', function() {
  $.ajax({
    type: 'get',
    url: 'index.php?route=extension/payment/sberbank_transfer/confirm',
		cache: false,
		beforeSend: function() {
			$('#button-confirm').button('loading');
		},
		complete: function() {
			$('#button-confirm').button('reset');
		},
    success: function() {
      location = '<?php echo $continue; ?>';
    }
  });
});
//--></script>
