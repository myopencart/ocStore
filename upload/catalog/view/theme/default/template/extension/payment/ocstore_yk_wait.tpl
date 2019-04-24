<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en" xml:lang="en">
  <head>
    <meta charset="UTF-8" />
    <title><?php echo $heading_title; ?></title>
    <script src="catalog/view/javascript/jquery/jquery-2.1.1.min.js" type="text/javascript"></script>
  </head>
  <body>
    <div id="wait" style="text-align:center;"><?php echo $text_wait; ?><br /><img src="<?php echo $loading; ?>" alt="" /></div>
    <script type="text/javascript"><!--
        var wait = <?php echo $wait; ?>;
        setInterval(function() {            
            $('#wait span').text(--wait);

            if (wait == 0) {
                location = '<?php echo $redirect; ?>';
            }
        }, 1000);
    //--></script>
  </body>
</html>