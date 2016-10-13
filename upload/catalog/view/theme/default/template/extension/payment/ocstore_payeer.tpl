<?php 
/**
 * Support:
 * https://opencartforum.com/user/3463-shoputils/
 * http://opencart.shoputils.ru/?route=information/contact
 * 
*/
?>
<?php if ($instruction) { ?>
  <div class="well well-sm"><p><?php echo $instruction; ?></p></div>
<?php } ?>
<form action="<?php echo $action ?>" method="get" id="checkout">
    <?php foreach ($parameters as $key => $value) { ?>
      <?php if (is_array($value)) { ?>
        <?php foreach ($value as $val) { ?>
          <input type="hidden" name="<?php echo $key; ?>" value="<?php echo $val; ?>"/>
        <?php } ?>
      <?php } else { ?>
        <input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value; ?>"/>
      <?php } ?>
    <?php } ?>
</form>
<div class="buttons">
  <div class="pull-right">
    <input type="button" value="<?php echo $button_confirm; ?>" id="button-confirm" class="btn btn-primary" data-loading-text="<?php echo $text_loading; ?>" />
  </div>
<script type="text/javascript"><!--
$('#button-confirm').on('click', function() {
        $.ajax({
            type: 'get',
            url: 'index.php?route=extension/payment/ocstore_payeer/confirm',
            beforeSend: function() {
                $('#button-confirm').attr('disabled', true);
                $('#button-confirm').button('loading');
            },
            complete: function() {
                $('#button-confirm').button('reset');
            },
            success: function() {
              <?php if ($pay_status) { ?>
                document.forms['checkout'].submit();
              <?php } else { ?>
                location = '<?php echo $continue; ?>';
              <?php } ?>
           }
        });
    });
//--></script>
</div>