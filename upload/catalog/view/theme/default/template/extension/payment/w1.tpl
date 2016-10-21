<form action="<?php echo $action; ?>" method="post">
    <input type="hidden" name="CMS" value="<?php echo $CMS; ?>">
    <input type="hidden" name="WMI_CURRENCY_ID" value="<?php echo $WMI_CURRENCY_ID; ?>">
    <?php if(!empty($WMI_CUSTOMER_EMAIL)){?>
        <input type="hidden" name="WMI_CUSTOMER_EMAIL" value="<?php echo $WMI_CUSTOMER_EMAIL; ?>">
    <?php }?>
    <?php if(!empty($WMI_CUSTOMER_FIRSTNAME)){?>
        <input type="hidden" name="WMI_CUSTOMER_FIRSTNAME" value="<?php echo $WMI_CUSTOMER_FIRSTNAME; ?>">
    <?php }?>
    <?php if(!empty($WMI_CUSTOMER_LASTNAME)){?>
        <input type="hidden" name="WMI_CUSTOMER_LASTNAME" value="<?php echo $WMI_CUSTOMER_LASTNAME; ?>">
    <?php }?>
    <input type="hidden" name="WMI_DESCRIPTION" value="<?php echo $WMI_DESCRIPTION; ?>">
    <input type="hidden" name="WMI_EXPIRED_DATE" value="<?php echo $WMI_EXPIRED_DATE; ?>">
    <input type="hidden" name="WMI_FAIL_URL" value="<?php echo $WMI_FAIL_URL; ?>">
    <input type="hidden" name="WMI_MERCHANT_ID" value="<?php echo $WMI_MERCHANT_ID; ?>">
    <input type="hidden" name="WMI_PAYMENT_AMOUNT" value="<?php echo $WMI_PAYMENT_AMOUNT; ?>">
    <input type="hidden" name="WMI_PAYMENT_NO" value="<?php echo $WMI_PAYMENT_NO; ?>">
    <?php if(!empty($WMI_RECIPIENT_LOGIN)){?>
        <input type="hidden" name="WMI_RECIPIENT_LOGIN" value="<?php echo $WMI_RECIPIENT_LOGIN; ?>">
    <?php }?>
    <input type="hidden" name="WMI_SUCCESS_URL" value="<?php echo $WMI_SUCCESS_URL; ?>">
    <?php if(!empty($WMI_PTENABLED)){?>
        <?php foreach($WMI_PTENABLED as $v){?>
            <input type="hidden" name="WMI_PTENABLED" value="<?php echo $v; ?>">
        <?php }?>
    <?php }?>
    <?php if(!empty($WMI_PTDISABLED)){?>
        <?php foreach($WMI_PTDISABLED as $v){?>
            <input type="hidden" name="WMI_PTDISABLED" value="<?php echo $v; ?>">
        <?php }?>
    <?php }?>
    <input type="hidden" name="WMI_SIGNATURE" value="<?php echo $WMI_SIGNATURE; ?>">
  <div class="buttons">
    <div class="pull-right">
      <input type="submit" value="<?php echo $button_confirm; ?>" class="btn btn-primary" />
    </div>
  </div>
</form>
