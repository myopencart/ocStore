<?php 
/**
 * Support:
 * https://opencartforum.com/user/3463-shoputils/
 * http://opencart.shoputils.ru/?route=information/contact
 * 
*/
?>
<?php echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en" xml:lang="en">
  <head></head>
  <body>
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
    <script type="text/javascript"><!--
      document.forms['checkout'].submit();
    //--></script>
  </body>
</html>