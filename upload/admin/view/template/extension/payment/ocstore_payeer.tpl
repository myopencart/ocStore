<?php
/**
 * Support:
 * https://opencartforum.com/user/3463-shoputils/
 * http://opencart.shoputils.ru/?route=information/contact
 * 
*/
?>
<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-ocstore-payeer" class="btn btn-primary"><i class="fa fa-save"></i> <?php echo $button_save; ?></button>
        <a href="<?php echo $cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i> <?php echo $button_cancel; ?></a></div>
      <h1><img src="view/image/payment/ocstore_payeer.png"> <?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-ocstore-payeer" class="form-horizontal">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><i class="fa fa-power-off"></i> <?php echo $tab_general; ?></a></li>
            <li><a href="#tab-emails" data-toggle="tab"><i class="fa fa-envelope-o"></i> <?php echo $tab_emails; ?></a></li>
            <li><a href="#tab-settings" data-toggle="tab"><i class="fa fa-wrench"></i> <?php echo $tab_settings; ?></a></li>
            <li><a href="#tab-log" data-toggle="tab"><i class="fa fa-eye"></i> <?php echo $tab_log; ?></a></li>
            <li><a href="#tab-information" data-toggle="tab"><i class="fa fa-info-circle"></i> <?php echo $tab_information; ?></a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-general">
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-geo-zone"><?php echo $entry_geo_zone; ?></label>
                <div class="col-sm-10">
                  <select name="ocstore_payeer_geo_zone_id" id="input-geo-zone" class="form-control">
                    <option value="0"><?php echo $text_all_zones; ?></option>
                    <?php foreach ($geo_zones as $geo_zone) { ?>
                    <?php if ($geo_zone['geo_zone_id'] == $ocstore_payeer_geo_zone_id) { ?>
                    <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                <div class="col-sm-10">
                  <select name="ocstore_payeer_status" id="input-status" class="form-control">
                    <?php if ($ocstore_payeer_status) { ?>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                    <option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="ocstore_payeer_sort_order" value="<?php echo $ocstore_payeer_sort_order; ?>"
                         placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-hide_mode"><?php echo $entry_hide_mode; ?></label>
                <div class="col-sm-10">
                  <select name="ocstore_payeer_hide_mode" id="input-hide_mode" class="form-control">
                    <?php if ($ocstore_payeer_hide_mode) { ?>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                    <option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
                  </select>
                  <span class="help-block"><?php echo $help_hide_mode; ?></span>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-minimal-order"><?php echo $entry_minimal_order; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="ocstore_payeer_minimal_order" value="<?php echo $ocstore_payeer_minimal_order; ?>"
                         placeholder="<?php echo $entry_minimal_order; ?>" id="input-minimal-order" class="form-control" />
                  <span class="help-block"><?php echo $help_minimal_order; ?></span>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-maximal-order"><?php echo $entry_maximal_order; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="ocstore_payeer_maximal_order" value="<?php echo $ocstore_payeer_maximal_order; ?>"
                         placeholder="<?php echo $entry_maximal_order; ?>" id="input-maximal-order" class="form-control" />
                  <span class="help-block"><?php echo $help_maximal_order; ?></span>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-order-confirm-status"><?php echo $entry_order_confirm_status; ?></label>
                <div class="col-sm-10">
                  <select name="ocstore_payeer_order_confirm_status_id" id="input-order-confirm-status" class="form-control">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $ocstore_payeer_order_confirm_status_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                  <span class="help-block"><?php echo $help_order_confirm_status; ?></span>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
                <div class="col-sm-10">
                  <select name="ocstore_payeer_order_status_id" id="input-order-status" class="form-control">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $ocstore_payeer_order_status_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                  <span class="help-block"><?php echo $help_order_status; ?></span>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-order-fail-status"><?php echo $entry_order_fail_status; ?></label>
                <div class="col-sm-10">
                  <select name="ocstore_payeer_order_fail_status_id" id="input-order-fail-status" class="form-control">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $ocstore_payeer_order_fail_status_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                  <span class="help-block"><?php echo $help_order_fail_status; ?></span>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_laterpay_mode; ?></label>
                <div class="col-sm-10">
                  <label class="radio-inline">
                    <?php if ($ocstore_payeer_laterpay_mode) { ?>
                    <input type="radio" name="ocstore_payeer_laterpay_mode" value="1" checked="checked" />
                    <?php echo $text_yes; ?>
                    <?php } else { ?>
                    <input type="radio" name="ocstore_payeer_laterpay_mode" value="1" />
                    <?php echo $text_yes; ?>
                    <?php } ?>
                  </label>
                  <label class="radio-inline">
                    <?php if (!$ocstore_payeer_laterpay_mode) { ?>
                    <input type="radio" name="ocstore_payeer_laterpay_mode" value="0" checked="checked" />
                    <?php echo $text_no; ?>
                    <?php } else { ?>
                    <input type="radio" name="ocstore_payeer_laterpay_mode" value="0" />
                    <?php echo $text_no; ?>
                    <?php } ?>
                  </label>
                  <span class="help-block"><?php echo $help_laterpay_mode; ?></span>
                </div>
              </div>

              <div class="form-group" id="laterpay-mode-tr" style="display: none;">
                <label class="col-sm-2 control-label" for="input-order-later-status"><?php echo $entry_order_later_status; ?></label>
                <div class="col-sm-10">
                  <select name="ocstore_payeer_order_later_status_id" id="input-order-later-status" class="form-control">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $ocstore_payeer_order_later_status_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                  <span class="help-block"><?php echo $help_order_later_status; ?></span>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_title; ?></label>
                <div class="col-sm-10">
                  <?php foreach ($oc_languages as $language) { ?>
                  <div class="input-group">
                    <?php $language_image = version_compare(VERSION, '2.2.0.0', '<') ? 'view/image/flags/' . $language['image'] : sprintf('language/%1$s/%1$s.png', $language['code']); ?>
                    <span class="input-group-addon"><img src="<?php echo $language_image; ?>" title="<?php echo $language['name']; ?>" /></span>
                    <input type="text" name="ocstore_payeer_langdata[<?php echo $language['language_id']; ?>][title]"
                           value="<?php echo !empty($ocstore_payeer_langdata[$language['language_id']]['title'])
                                  ? $ocstore_payeer_langdata[$language['language_id']]['title'] : $title_default[0]; ?>"
                                  placeholder="<?php echo $entry_title; ?>" class="form-control" />
                  </div>
                  <?php } ?>
                  <span class="help-block"><?php echo $help_title; ?></span>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_instruction; ?></label>
                <div class="col-sm-10">
                  <?php foreach ($oc_languages as $language) { ?>
                  <div class="input-group">
                    <?php $language_image = version_compare(VERSION, '2.2.0.0', '<') ? 'view/image/flags/' . $language['image'] : sprintf('language/%1$s/%1$s.png', $language['code']); ?>
                    <span class="input-group-addon"><img src="<?php echo $language_image; ?>" title="<?php echo $language['name']; ?>" /></span>
                    <textarea name="ocstore_payeer_langdata[<?php echo $language['language_id']; ?>][instruction]" rows="5"
                              placeholder="<?php echo $placeholder_instruction; ?>"
                              name="ocstore_payeer_langdata[<?php echo $language['language_id']; ?>][instruction]"
                              class="form-control"><?php echo !empty($ocstore_payeer_langdata[$language['language_id']]['instruction'])
                                                   ? $ocstore_payeer_langdata[$language['language_id']]['instruction'] : ''; ?></textarea>
                  </div>
                  <?php } ?>
                  <span class="help-block"><?php echo $help_instruction; ?></span>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_laterpay_button_lk; ?></label>
                <div class="col-sm-10">
                  <label class="radio-inline">
                    <?php if ($ocstore_payeer_laterpay_button_lk) { ?>
                    <input type="radio" name="ocstore_payeer_laterpay_button_lk" value="1" checked="checked" />
                    <?php echo $text_yes; ?>
                    <?php } else { ?>
                    <input type="radio" name="ocstore_payeer_laterpay_button_lk" value="1" />
                    <?php echo $text_yes; ?>
                    <?php } ?>
                  </label>
                  <label class="radio-inline">
                    <?php if (!$ocstore_payeer_laterpay_button_lk) { ?>
                    <input type="radio" name="ocstore_payeer_laterpay_button_lk" value="0" checked="checked" />
                    <?php echo $text_no; ?>
                    <?php } else { ?>
                    <input type="radio" name="ocstore_payeer_laterpay_button_lk" value="0" />
                    <?php echo $text_no; ?>
                    <?php } ?>
                  </label>
                  <span class="help-block"><?php echo $help_laterpay_button_lk; ?></span>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_button_confirm; ?></label>
                <div class="col-sm-10">
                  <?php foreach ($oc_languages as $language) { ?>
                  <div class="input-group">
                    <?php $language_image = version_compare(VERSION, '2.2.0.0', '<') ? 'view/image/flags/' . $language['image'] : sprintf('language/%1$s/%1$s.png', $language['code']); ?>
                    <span class="input-group-addon"><img src="<?php echo $language_image; ?>" title="<?php echo $language['name']; ?>" /></span>
                    <input type="text" name="ocstore_payeer_langdata[<?php echo $language['language_id']; ?>][button_confirm]"
                           value="<?php echo !empty($ocstore_payeer_langdata[$language['language_id']]['button_confirm'])
                                  ? $ocstore_payeer_langdata[$language['language_id']]['button_confirm'] : $sample_button_confirm; ?>"
                                  placeholder="<?php echo $entry_button_confirm; ?>" class="form-control" />
                  </div>
                  <?php } ?>
                  <span class="help-block"><?php echo $help_button_confirm; ?></span>
                </div>
              </div>

            </div><!-- </div id="tab-general"> -->
            <div class="tab-pane" id="tab-emails">

              <div class="form-group">
                <label class="col-sm-4 control-label"><?php echo $entry_notify_customer_success; ?></label>
                <div class="col-sm-8">
                  <label class="radio-inline">
                    <?php if ($ocstore_payeer_notify_customer_success) { ?>
                    <input type="radio" name="ocstore_payeer_notify_customer_success" value="1" checked="checked" />
                    <?php echo $text_yes; ?>
                    <?php } else { ?>
                    <input type="radio" name="ocstore_payeer_notify_customer_success" value="1" />
                    <?php echo $text_yes; ?>
                    <?php } ?>
                  </label>
                  <label class="radio-inline">
                    <?php if (!$ocstore_payeer_notify_customer_success) { ?>
                    <input type="radio" name="ocstore_payeer_notify_customer_success" value="0" checked="checked" />
                    <?php echo $text_no; ?>
                    <?php } else { ?>
                    <input type="radio" name="ocstore_payeer_notify_customer_success" value="0" />
                    <?php echo $text_no; ?>
                    <?php } ?>
                  </label>
                  <span class="help-block"><?php echo $help_notify_customer_success; ?></span>
                </div>
              </div>

              <div class="form-group required" id="mail-customer-success-tr" style="display: none;">
                <div class="col-sm-4">
                  <span class="help-block"><?php echo $help_mail_customer_success_content; ?></span>
                  <span class="help-block"><?php echo $list_helper; ?></span>
                </div>
                <div class="col-sm-8">
                  <label class="control-label"><?php echo $entry_mail_customer_success_subject; ?></label>
                  <?php foreach ($oc_languages as $language) { ?>
                  <div class="input-group">
                    <?php $language_image = version_compare(VERSION, '2.2.0.0', '<') ? 'view/image/flags/' . $language['image'] : sprintf('language/%1$s/%1$s.png', $language['code']); ?>
                    <span class="input-group-addon"><img src="<?php echo $language_image; ?>" title="<?php echo $language['name']; ?>" /></span>
                    <input type="text" name="ocstore_payeer_langdata[<?php echo $language['language_id']; ?>][mail_customer_success_subject]"
                           value="<?php echo !empty($ocstore_payeer_langdata[$language['language_id']]['mail_customer_success_subject'])
                                  ? $ocstore_payeer_langdata[$language['language_id']]['mail_customer_success_subject'] : $sample_mail_customer_success_subject; ?>"
                                  placeholder="<?php echo $entry_mail_customer_success_subject; ?>" class="form-control" />
                  </div>
                  <?php } ?>
                  <?php if ($error_mail_customer_success_subject) { ?>
                  <div class="text-danger"><?php echo $error_mail_customer_success_subject; ?></div>
                  <?php } ?>
                  <br />
                  <label class="control-label"><?php echo $entry_mail_customer_success_content; ?></label>
                  <?php foreach ($oc_languages as $language) { ?>
                  <div class="input-group">
                    <?php $language_image = version_compare(VERSION, '2.2.0.0', '<') ? 'view/image/flags/' . $language['image'] : sprintf('language/%1$s/%1$s.png', $language['code']); ?>
                    <span class="input-group-addon"><img src="<?php echo $language_image; ?>" title="<?php echo $language['name']; ?>" /></span>
                    <textarea name="ocstore_payeer_langdata[<?php echo $language['language_id']; ?>][mail_customer_success_content]" rows="5"
                              id="mail-customer-success-content<?php echo $language['language_id']; ?>"
                              placeholder="<?php echo $entry_mail_customer_success_content; ?>"
                              data-lang="<?php echo $lang; ?>"
                              class="form-control summernote"><?php echo !empty($ocstore_payeer_langdata[$language['language_id']]['mail_customer_success_content'])
                                                   ? $ocstore_payeer_langdata[$language['language_id']]['mail_customer_success_content'] : $sample_mail_customer_success_content; ?></textarea>
                  </div>
                  <?php } ?>
                  <?php if ($error_mail_customer_success_content) { ?>
                  <div class="text-danger"><?php echo $error_mail_customer_success_content; ?></div>
                  <?php } ?>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-4 control-label"><?php echo $entry_notify_customer_fail; ?></label>
                <div class="col-sm-8">
                  <label class="radio-inline">
                    <?php if ($ocstore_payeer_notify_customer_fail) { ?>
                    <input type="radio" name="ocstore_payeer_notify_customer_fail" value="1" checked="checked" />
                    <?php echo $text_yes; ?>
                    <?php } else { ?>
                    <input type="radio" name="ocstore_payeer_notify_customer_fail" value="1" />
                    <?php echo $text_yes; ?>
                    <?php } ?>
                  </label>
                  <label class="radio-inline">
                    <?php if (!$ocstore_payeer_notify_customer_fail) { ?>
                    <input type="radio" name="ocstore_payeer_notify_customer_fail" value="0" checked="checked" />
                    <?php echo $text_no; ?>
                    <?php } else { ?>
                    <input type="radio" name="ocstore_payeer_notify_customer_fail" value="0" />
                    <?php echo $text_no; ?>
                    <?php } ?>
                  </label>
                  <span class="help-block"><?php echo $help_notify_customer_fail; ?></span>
                </div>
              </div>

              <div class="form-group required" id="mail-customer-fail-tr" style="display: none;">
                <div class="col-sm-4">
                  <span class="help-block"><?php echo $help_mail_customer_fail_content; ?></span>
                  <span class="help-block"><?php echo $list_helper; ?></span>
                </div>
                <div class="col-sm-8">
                  <label class="control-label"><?php echo $entry_mail_customer_fail_subject; ?></label>
                  <?php foreach ($oc_languages as $language) { ?>
                  <div class="input-group">
                    <?php $language_image = version_compare(VERSION, '2.2.0.0', '<') ? 'view/image/flags/' . $language['image'] : sprintf('language/%1$s/%1$s.png', $language['code']); ?>
                    <span class="input-group-addon"><img src="<?php echo $language_image; ?>" title="<?php echo $language['name']; ?>" /></span>
                    <input type="text" name="ocstore_payeer_langdata[<?php echo $language['language_id']; ?>][mail_customer_fail_subject]"
                           value="<?php echo !empty($ocstore_payeer_langdata[$language['language_id']]['mail_customer_fail_subject'])
                                  ? $ocstore_payeer_langdata[$language['language_id']]['mail_customer_fail_subject'] : $sample_mail_customer_fail_subject; ?>"
                                  placeholder="<?php echo $entry_mail_customer_fail_subject; ?>" class="form-control" />
                  </div>
                  <?php } ?>
                  <?php if ($error_mail_customer_fail_subject) { ?>
                  <div class="text-danger"><?php echo $error_mail_customer_fail_subject; ?></div>
                  <?php } ?>
                  <br />
                  <label class="control-label"><?php echo $entry_mail_customer_fail_content; ?></label>
                  <?php foreach ($oc_languages as $language) { ?>
                  <div class="input-group">
                    <?php $language_image = version_compare(VERSION, '2.2.0.0', '<') ? 'view/image/flags/' . $language['image'] : sprintf('language/%1$s/%1$s.png', $language['code']); ?>
                    <span class="input-group-addon"><img src="<?php echo $language_image; ?>" title="<?php echo $language['name']; ?>" /></span>
                    <textarea name="ocstore_payeer_langdata[<?php echo $language['language_id']; ?>][mail_customer_fail_content]" rows="5"
                              id="mail-customer-fail-content<?php echo $language['language_id']; ?>"
                              placeholder="<?php echo $entry_mail_customer_fail_content; ?>"
                              data-lang="<?php echo $lang; ?>"
                              class="form-control summernote"><?php echo !empty($ocstore_payeer_langdata[$language['language_id']]['mail_customer_fail_content'])
                                                   ? $ocstore_payeer_langdata[$language['language_id']]['mail_customer_fail_content'] : $sample_mail_customer_fail_content; ?></textarea>
                  </div>
                  <?php } ?>
                  <?php if ($error_mail_customer_fail_content) { ?>
                  <div class="text-danger"><?php echo $error_mail_customer_fail_content; ?></div>
                  <?php } ?>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-4 control-label"><?php echo $entry_notify_admin_success; ?></label>
                <div class="col-sm-8">
                  <label class="radio-inline">
                    <?php if ($ocstore_payeer_notify_admin_success) { ?>
                    <input type="radio" name="ocstore_payeer_notify_admin_success" value="1" checked="checked" />
                    <?php echo $text_yes; ?>
                    <?php } else { ?>
                    <input type="radio" name="ocstore_payeer_notify_admin_success" value="1" />
                    <?php echo $text_yes; ?>
                    <?php } ?>
                  </label>
                  <label class="radio-inline">
                    <?php if (!$ocstore_payeer_notify_admin_success) { ?>
                    <input type="radio" name="ocstore_payeer_notify_admin_success" value="0" checked="checked" />
                    <?php echo $text_no; ?>
                    <?php } else { ?>
                    <input type="radio" name="ocstore_payeer_notify_admin_success" value="0" />
                    <?php echo $text_no; ?>
                    <?php } ?>
                  </label>
                  <span class="help-block"><?php echo $help_notify_admin_success; ?></span>
                </div>
              </div>

              <div class="form-group required" id="mail-admin-success-tr" style="display: none;">
                <div class="col-sm-4">
                  <span class="help-block"><?php echo $help_mail_customer_success_content; ?></span>
                  <span class="help-block"><?php echo $list_helper; ?></span>
                </div>
                <div class="col-sm-8">
                  <label class="control-label" for="input-mail-admin-success-subject"><?php echo $entry_mail_admin_success_subject; ?></label>
                  <input type="text" name="ocstore_payeer_mail_admin_success_subject" id="input-mail-admin-success-subject"
                         value="<?php echo !empty($ocstore_payeer_mail_admin_success_subject)
                                ? $ocstore_payeer_mail_admin_success_subject : $sample_mail_admin_success_subject; ?>"
                                placeholder="<?php echo $entry_mail_admin_success_subject; ?>" class="form-control" />
                  <?php if ($error_mail_admin_success_subject) { ?>
                  <div class="text-danger"><?php echo $error_mail_admin_success_subject; ?></div>
                  <?php } ?>
                  <br />
                  <label class="control-label"><?php echo $entry_mail_admin_success_content; ?></label>
                  <textarea name="ocstore_payeer_mail_admin_success_content" rows="5"
                            id="input-mail-admin-success-content"
                            placeholder="<?php echo $entry_mail_admin_success_content; ?>"
                            data-lang="<?php echo $lang; ?>"
                            class="form-control summernote"><?php echo !empty($ocstore_payeer_mail_admin_success_content)
                                                 ? $ocstore_payeer_mail_admin_success_content : $sample_mail_admin_success_content; ?></textarea>
                  <?php if ($error_mail_admin_success_content) { ?>
                  <div class="text-danger"><?php echo $error_mail_admin_success_content; ?></div>
                  <?php } ?>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-4 control-label"><?php echo $entry_notify_admin_fail; ?></label>
                <div class="col-sm-8">
                  <label class="radio-inline">
                    <?php if ($ocstore_payeer_notify_admin_fail) { ?>
                    <input type="radio" name="ocstore_payeer_notify_admin_fail" value="1" checked="checked" />
                    <?php echo $text_yes; ?>
                    <?php } else { ?>
                    <input type="radio" name="ocstore_payeer_notify_admin_fail" value="1" />
                    <?php echo $text_yes; ?>
                    <?php } ?>
                  </label>
                  <label class="radio-inline">
                    <?php if (!$ocstore_payeer_notify_admin_fail) { ?>
                    <input type="radio" name="ocstore_payeer_notify_admin_fail" value="0" checked="checked" />
                    <?php echo $text_no; ?>
                    <?php } else { ?>
                    <input type="radio" name="ocstore_payeer_notify_admin_fail" value="0" />
                    <?php echo $text_no; ?>
                    <?php } ?>
                  </label>
                  <span class="help-block"><?php echo $help_notify_admin_fail; ?></span>
                </div>
              </div>

              <div class="form-group required" id="mail-admin-fail-tr" style="display: none;">
                <div class="col-sm-4">
                  <span class="help-block"><?php echo $help_mail_customer_fail_content; ?></span>
                  <span class="help-block"><?php echo $list_helper; ?></span>
                </div>
                <div class="col-sm-8">
                  <label class="control-label" for="input-mail-admin-fail-subject"><?php echo $entry_mail_admin_fail_subject; ?></label>
                  <input type="text" name="ocstore_payeer_mail_admin_fail_subject" id="input-mail-admin-fail-subject"
                         value="<?php echo !empty($ocstore_payeer_mail_admin_fail_subject)
                                ? $ocstore_payeer_mail_admin_fail_subject : $sample_mail_admin_fail_subject; ?>"
                                placeholder="<?php echo $entry_mail_admin_fail_subject; ?>" class="form-control" />
                  <?php if ($error_mail_admin_fail_subject) { ?>
                  <div class="text-danger"><?php echo $error_mail_admin_fail_subject; ?></div>
                  <?php } ?>
                  <br />
                  <label class="control-label"><?php echo $entry_mail_admin_fail_content; ?></label>
                  <textarea name="ocstore_payeer_mail_admin_fail_content" rows="5"
                            id="input-mail-admin-fail-content"
                            placeholder="<?php echo $entry_mail_admin_fail_content; ?>"
                            data-lang="<?php echo $lang; ?>"
                            class="form-control summernote"><?php echo !empty($ocstore_payeer_mail_admin_fail_content)
                                                 ? $ocstore_payeer_mail_admin_fail_content : $sample_mail_admin_fail_content; ?></textarea>
                  <?php if ($error_mail_admin_fail_content) { ?>
                  <div class="text-danger"><?php echo $error_mail_admin_fail_content; ?></div>
                  <?php } ?>
                </div>
              </div>

            </div><!-- </div id="tab-emails"> -->
            <div class="tab-pane" id="tab-settings">

              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-shop_id"><?php echo $entry_shop_id; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="ocstore_payeer_shop_id" value="<?php echo $ocstore_payeer_shop_id; ?>"
                         placeholder="<?php echo $entry_shop_id; ?>" id="input-shop_id" class="form-control" />
                  <span class="help-block"><?php echo $help_shop_id; ?></span>
                  <?php if ($error_shop_id) { ?>
                  <div class="text-danger"><?php echo $error_shop_id; ?></div>
                  <?php } ?>
                </div>
              </div>

              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-sign_hash"><?php echo $entry_sign_hash; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="ocstore_payeer_sign_hash" value="<?php echo $ocstore_payeer_sign_hash; ?>"
                         placeholder="<?php echo $entry_sign_hash; ?>" id="input-sign_hash" class="form-control" />
                  <span class="help-block"><?php echo $help_sign_hash; ?></span>
                  <?php if ($error_sign_hash) { ?>
                  <div class="text-danger"><?php echo $error_sign_hash; ?></div>
                  <?php } ?>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-currency"><?php echo $entry_currency; ?></label>
                <div class="col-sm-10">
                  <?php if (!count($currencies)) { ?>
                    <div class="text-danger"><?php echo $error_currency; ?></div>
                  <?php } else { ?>
                    <select name="ocstore_payeer_currency" id="input-currency" class="form-control">
                      <?php foreach ($currencies as $key => $value) { ?>
                      <?php if ($key == $ocstore_payeer_currency) { ?>
                      <option value="<?php echo $key; ?>"
                              selected="selected"><?php echo $value; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  <?php } ?>
                  <span class="help-block"><?php echo $help_currency; ?></span>
                </div>
              </div>

            </div><!-- </div id="tab-settings"> -->
            <div class="tab-pane" id="tab-log">

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-log"><?php echo $entry_log; ?></label>
                <div class="col-sm-8">
                  <input type="hidden" name="ocstore_payeer_log_filename" value="<?php echo $log_filename ?>" />
                  <input type="hidden" name="ocstore_payeer_version" value="<?php echo $version ?>" />
                  <select name="ocstore_payeer_log" id="input-log" class="form-control">
                    <?php foreach ($logs as $key => $value) { ?>
                    <?php if ($key == $ocstore_payeer_log) { ?>
                    <option value="<?php echo $key; ?>"
                            selected="selected"><?php echo $value; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                  <span class="help-block"><?php echo $help_log; ?></span>
                </div>
                <div class="col-sm-2">
                  <a class="btn btn-danger" id="button-clear"><i class="fa fa-eraser"></i> <?php echo $button_clear; ?></a>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_log_file; ?></label>
                <div class="col-sm-10">
                  <div class="well well-sm" style="height: 300px; overflow: auto;">
                    <pre id="pre-log" style="font-size:11px; min-height: 280px;"><?php foreach ($log_lines as $log_line) {echo $log_line;} ?></pre>
                  </div>
                  <span class="help-block"><?php echo $help_log_file; ?></span>
                </div>
              </div>

            </div><!-- </div id="tab-log"> -->
            <div class="tab-pane" id="tab-information">

              <div class="alert alert-success" style="padding: 30px 10px;"><i class="fa fa-check-circle"></i>
                <?php echo $text_info ?>
              </div>
              <div class="form-group">
                <label class="col-sm-4 control-label"><?php echo $entry_success_url; ?></label>
                <div class="col-sm-8">
                  <span class="form-control"><?php echo $text_success_url; ?></span>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-4 control-label"><?php echo $entry_fail_url; ?></label>
                <div class="col-sm-8">
                  <span class="form-control"><?php echo $text_fail_url; ?></span>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-4 control-label"><?php echo $entry_status_url; ?></label>
                <div class="col-sm-8">
                  <span class="form-control"><?php echo $text_status_url; ?></span>
                </div>
              </div>

            </div><!-- </div id="tab-information"> -->
          </div><!-- </div class="tab-content"> -->
        </form>
      </div><!-- </div class="panel-body"> -->
    </div><!-- </div class="panel panel-default"> -->
  </div><!-- </div class="container-fluid"> -->
</div><!-- </div id="content"> -->
<script type="text/javascript"><!--
  <?php if ($ckeditor) { ?>
  ckeditorInit('input-mail-admin-success-content', getURLVar('token'));
  ckeditorInit('input-mail-admin-fail-content', getURLVar('token'));
  <?php foreach ($oc_languages as $language) { ?>
    ckeditorInit('mail-customer-success-content<?php echo $language['language_id']; ?>', getURLVar('token'));
    ckeditorInit('mail-customer-fail-content<?php echo $language['language_id']; ?>', getURLVar('token'));
  <?php } ?>
  <?php } ?>

  $(document).ready(function(){
    $('input:radio[name^="ocstore_payeer_laterpay_mode"]:checked').each(function(indx){
      changeLaterpayMode($(this).val());
    });
    $('input:radio[name^="ocstore_payeer_notify_customer_success"]:checked').each(function(indx){
      changeNotifyCustomerSuccess($(this).val());
    });
    $('input:radio[name^="ocstore_payeer_notify_customer_fail"]:checked').each(function(indx){
      changeNotifyCustomerFail($(this).val());
    });
    $('input:radio[name^="ocstore_payeer_notify_admin_success"]:checked').each(function(indx){
      changeNotifyAdminSuccess($(this).val());
    });
    $('input:radio[name^="ocstore_payeer_notify_admin_fail"]:checked').each(function(indx){
      changeNotifyAdminFail($(this).val());
    });
  });
    $('input:radio[name^="ocstore_payeer_laterpay_mode"]').change(function(){
      changeLaterpayMode($(this).val());
    });
    $('input:radio[name^="ocstore_payeer_notify_customer_success"]').change(function(){
      changeNotifyCustomerSuccess($(this).val());
    });
    $('input:radio[name^="ocstore_payeer_notify_customer_fail"]').change(function(){
      changeNotifyCustomerFail($(this).val());
    });
    $('input:radio[name^="ocstore_payeer_notify_admin_success"]').change(function(){
      changeNotifyAdminSuccess($(this).val());
    });
    $('input:radio[name^="ocstore_payeer_notify_admin_fail"]').change(function(){
      changeNotifyAdminFail($(this).val());
    });
  function changeLaterpayMode(value) {
      value == '1' ? $("#laterpay-mode-tr").fadeIn('slow') : $("#laterpay-mode-tr").fadeOut('slow').delay(200);
  }
  function changeNotifyCustomerSuccess(value) {
      value == '1' ? $("#mail-customer-success-tr").fadeIn('slow') : $("#mail-customer-success-tr").fadeOut('slow').delay(200);
  }
  function changeNotifyCustomerFail(value) {
      value == '1' ? $("#mail-customer-fail-tr").fadeIn('slow') : $("#mail-customer-fail-tr").fadeOut('slow').delay(200);
  }
  function changeNotifyAdminSuccess(value) {
      value == '1' ? $("#mail-admin-success-tr").fadeIn('slow') : $("#mail-admin-success-tr").fadeOut('slow').delay(200);
  }
  function changeNotifyAdminFail(value) {
      value == '1' ? $("#mail-admin-fail-tr").fadeIn('slow') : $("#mail-admin-fail-tr").fadeOut('slow').delay(200);
  }

  $(document).delegate('#button-clear', 'click', function() {
    if (confirm('<?php echo $text_confirm; ?>')){
      $.ajax({
        url: '<?php echo $clear_log; ?>',
        type: 'post',
        dataType: 'json',
        beforeSend: function() {
          $('#button-clear').attr('disabled', true);
          $('#button-clear').button('loading');
        },
        complete: function() {
          $('#button-clear').button('reset');
        },
        success: function(json) {
          $('.alert-success, .alert-danger').remove();
                
          if (json['error']) {
            $('#content > .container-fluid').before('<div class="alert alert-danger" style="display: none;"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
            $('.alert-danger').fadeIn('slow');
          }
          
          if (json['success']) {
            $('#content > .container-fluid').before('<div class="alert alert-success" style="display: none;"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');
            
            $('#pre-log').empty();
            $('.alert-success').fadeIn('slow');
          }

          $('html, body').animate({ scrollTop: 0 }, 'slow'); 
        },
        error: function(xhr, ajaxOptions, thrownError) {
          alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
      });
    }
  });
//--></script>
<?php echo $footer; ?>
