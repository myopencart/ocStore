<?php if ($instruction) { ?>
  <div class="well well-sm"><p><?php echo $instruction; ?></p></div>
<?php } ?>
    <form action="<?php echo $action ?>" method="post" id="checkout">
        <?php foreach ($parameters as $key => $value) { ?>
          <?php if (is_array($value)) { ?>
                <?php foreach ($value as $name => $title) { ?>
                    <input type="hidden" name="<?php echo $key; ?>" value="<?php echo $name; ?>"/>
                <?php } ?>
          <?php } else { ?>
              <input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value; ?>"/>
          <?php } ?>
        <?php } ?>
    </form>
<div class="buttons">
  <div class="pull-right">
    <?php if ($laterpay_mode == 2) { //laterpay_mode == 'mixed' ?>
    <input type="button" value="<?php echo $button_laterpay; ?>" id="button-laterpay" class="btn btn-primary" />
    <?php } ?>
    <input type="button" value="<?php echo $button_confirm; ?>" id="button-confirm" class="btn btn-primary" />
  </div>
<script type="text/javascript"><!--
    $('#button-laterpay, #button-confirm').on('click', function() {
        var node = this;
        $.ajax({
            type: 'get',
            url: '<?php echo $confirm; ?>',
            beforeSend: function() {
                $('#button-confirm').attr('disabled', true);
                $('#button-confirm').button('loading');
            },
            complete: function() {
                $('#button-confirm').button('reset');
            },
            success: function() {
              var laterpay_mode = <?php echo $laterpay_mode; ?>;
              switch(laterpay_mode) {
                  case 0: {
                    document.forms['checkout'].submit();
                    break;
                  }
                  case 1: {
                    location = '<?php echo $continue; ?>';
                    break;
                  }
                  default: {
                    if ($(node).attr('id') == 'button-confirm') {
                        document.forms['checkout'].submit();
                    } else {
                        location = '<?php echo $continue; ?>';
                    }
                  }
              }
            }
        });
    });
//--></script>
</div><!-- </div class="buttons">  -->
