<?php echo $header; ?>
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
      <h1><img src="view/image/payment.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location='<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <div id="languages" class="htabs">
        <?php foreach ($languages as $language) { ?>
        <a href="#language<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
        <?php } ?>
      </div>
      <?php foreach ($languages as $language) { ?>
      <div id="language<?php echo $language['language_id']; ?>">
        <table class="form">
        <tr>
          <td><span class="required">*</span> <?php echo $entry_bank; ?></td>
          <td><textarea name="sberbank_transfer_bank_<?php echo $language['language_id']; ?>" cols="50" rows="3"><?php echo isset(${'sberbank_transfer_bank_' . $language['language_id']}) ? ${'sberbank_transfer_bank_' . $language['language_id']} : ''; ?></textarea><br />
            <?php if (isset(${'error_bank_' . $language['language_id']})) { ?>
            <span class="error"><?php echo ${'error_bank_' . $language['language_id']}; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_inn; ?></td>
          <td><input name="sberbank_transfer_inn_<?php echo $language['language_id']; ?>" type="text" size="20" value="<?php echo isset(${'sberbank_transfer_inn_' . $language['language_id']}) ? ${'sberbank_transfer_inn_' . $language['language_id']} : ''; ?>" /><br />
            <?php if (isset(${'error_inn_' . $language['language_id']})) { ?>
            <span class="error"><?php echo ${'error_inn_' . $language['language_id']}; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_rs; ?></td>
          <td><input name="sberbank_transfer_rs_<?php echo $language['language_id']; ?>" type="text" size="20" value="<?php echo isset(${'sberbank_transfer_rs_' . $language['language_id']}) ? ${'sberbank_transfer_rs_' . $language['language_id']} : ''; ?>" /><br />
            <?php if (isset(${'error_rs_' . $language['language_id']})) { ?>
            <span class="error"><?php echo ${'error_rs_' . $language['language_id']}; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_bankuser; ?></td>
          <td><input name="sberbank_transfer_bankuser_<?php echo $language['language_id']; ?>" type="text" size="20" value="<?php echo isset(${'sberbank_transfer_bankuser_' . $language['language_id']}) ? ${'sberbank_transfer_bankuser_' . $language['language_id']} : ''; ?>" /><br />
            <?php if (isset(${'error_bankuser_' . $language['language_id']})) { ?>
            <span class="error"><?php echo ${'error_bankuser_' . $language['language_id']}; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_bik; ?></td>
          <td><input name="sberbank_transfer_bik_<?php echo $language['language_id']; ?>" type="text" size="20" value="<?php echo isset(${'sberbank_transfer_bik_' . $language['language_id']}) ? ${'sberbank_transfer_bik_' . $language['language_id']} : ''; ?>" /><br />
            <?php if (isset(${'error_bank_' . $language['language_id']})) { ?>
            <span class="error"><?php echo ${'error_bik_' . $language['language_id']}; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_ks; ?></td>
          <td><input name="sberbank_transfer_ks_<?php echo $language['language_id']; ?>" type="text" size="20" value="<?php echo isset(${'sberbank_transfer_ks_' . $language['language_id']}) ? ${'sberbank_transfer_ks_' . $language['language_id']} : ''; ?>" /><br />
            <?php if (isset(${'error_bank_' . $language['language_id']})) { ?>
            <span class="error"><?php echo ${'error_ks_' . $language['language_id']}; ?></span>
            <?php } ?></td>
        </tr>
        </table>
      </div>
      <?php } ?>
      <table class="form">
        <tr>
          <td><?php echo $entry_order_status; ?></td>
          <td><select name="sberbank_transfer_order_status_id">
              <?php foreach ($order_statuses as $order_status) { ?>
              <?php if ($order_status['order_status_id'] == $sberbank_transfer_order_status_id) { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_geo_zone; ?></td>
          <td><select name="sberbank_transfer_geo_zone_id">
              <option value="0"><?php echo $text_all_zones; ?></option>
              <?php foreach ($geo_zones as $geo_zone) { ?>
              <?php if ($geo_zone['geo_zone_id'] == $sberbank_transfer_geo_zone_id) { ?>
              <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_status; ?></td>
          <td><select name="sberbank_transfer_status">
              <?php if ($sberbank_transfer_status) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_sort_order; ?></td>
          <td><input type="text" name="sberbank_transfer_sort_order" value="<?php echo $sberbank_transfer_sort_order; ?>" size="1" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
<script type="text/javascript"><!--
$('#languages a').tabs(); 
//--></script> 
<?php echo $footer; ?>