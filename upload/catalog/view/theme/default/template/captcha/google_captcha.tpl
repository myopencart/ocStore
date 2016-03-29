<div class="form-group required">
  <?php if (substr($route, 0, 9) == 'checkout/') { ?>
    <label class="control-label" for="input-captcha"><?php echo $entry_captcha; ?></label>
    <div id="input-captcha" class="g-recaptcha" data-sitekey="<?php echo $site_key; ?>"></div>
  <?php } else { ?>
    <div class="col-sm-offset-2 col-sm-10">
      <div id="input-payment-captcha" class="g-recaptcha" data-sitekey="<?php echo $site_key; ?>"></div>
      <?php if ($error_captcha) { ?>
      <div class="text-danger"><?php echo $error_captcha; ?></div>
      <?php } ?>
    </div>
  <?php } ?>
</div>

<script src="https://www.google.com/recaptcha/api.js"></script>
<script type="text/javascript">
if (typeof grecaptcha != "undefined") {
  $(document).ready(function() {
    $('.g-recaptcha').each(function(){
      $(this).html('');     
      var widgetId = grecaptcha.render($(this)[0], {sitekey : $(this).data('sitekey')});
    });
  });
}
</script>
