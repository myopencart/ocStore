<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 8 ]><html dir="ltr" lang="ru" class="ie8"><![endif]-->
<!--[if IE 9 ]><html dir="ltr" lang="ru" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html dir="ltr" lang="ru">
<!--<![endif]-->
  <head>
    <title><?php echo $text_proceed_payment; ?></title>
  </head>
  <body>
    <div style="text-align:center;"><?php echo $text_proceed_payment; ?><br /><img src="<?php echo $loading; ?>" alt="" /></div>
    <form action="<?php echo $action ?>" method="post" id="checkout">
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