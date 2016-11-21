<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-ocstore-yk" class="btn btn-primary"><i class="fa fa-save"></i> <?php echo $button_save; ?></button>
        <a href="<?php echo $cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i> <?php echo $button_cancel; ?></a></div>
      <h1><img src="view/image/payment/ocstore_yk-icon.png" width="26" height="26"> <?php echo $heading_title; ?></h1>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-ocstore-yk" class="form-horizontal">
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
                <label class="col-sm-2 control-label" for="input-hide_mode"><?php echo $entry_hide_mode; ?></label>
                <div class="col-sm-10">
                  <select name="ocstore_yk_hide_mode" id="input-hide_mode" class="form-control">
                    <?php if ($ocstore_yk_hide_mode) { ?>
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
                <label class="col-sm-2 control-label" for="input-order-confirm-status"><?php echo $entry_order_confirm_status; ?></label>
                <div class="col-sm-10">
                  <select name="ocstore_yk_order_confirm_status_id" id="input-order-confirm-status" class="form-control">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $ocstore_yk_order_confirm_status_id) { ?>
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
                  <select name="ocstore_yk_order_status_id" id="input-order-status" class="form-control">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $ocstore_yk_order_status_id) { ?>
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
                  <select name="ocstore_yk_order_fail_status_id" id="input-order-fail-status" class="form-control">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $ocstore_yk_order_fail_status_id) { ?>
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
                  <select name="ocstore_yk_laterpay_mode" id="input-laterpay_mode" class="form-control">
                    <?php foreach ($laterpay_modes as $key => $value) { ?>
                    <?php if ($key == $ocstore_yk_laterpay_mode) { ?>
                    <option value="<?php echo $key; ?>"
                            selected="selected"><?php echo $value; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                  <span class="help-block"><?php echo $help_laterpay_mode; ?></span>
                </div>
              </div>

              <div class="form-group" id="laterpay-mode-tr" style="display: none;">
                <label class="col-sm-2 control-label" for="input-order-later-status"><?php echo $entry_order_later_status; ?></label>
                <div class="col-sm-10">
                  <select name="ocstore_yk_order_later_status_id" id="input-order-later-status" class="form-control">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $ocstore_yk_order_later_status_id) { ?>
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
                <label class="col-sm-2 control-label"><?php echo $entry_instruction; ?></label>
                <div class="col-sm-10">
                  <?php foreach ($oc_languages as $language) { ?>
                  <div class="input-group">
                    <?php $language_image = version_compare(VERSION, '2.2.0.0', '<') ? 'view/image/flags/' . $language['image'] : sprintf('language/%1$s/%1$s.png', $language['code']); ?>
                    <span class="input-group-addon"><img src="<?php echo $language_image; ?>" title="<?php echo $language['name']; ?>" /></span>
                    <textarea name="ocstore_yk_langdata[<?php echo $language['language_id']; ?>][instruction]" rows="5"
                              placeholder="<?php echo $placeholder_instruction; ?>"
                              name="ocstore_yk_langdata[<?php echo $language['language_id']; ?>][instruction]"
                              class="form-control"><?php echo !empty($ocstore_yk_langdata[$language['language_id']]['instruction'])
                                                   ? $ocstore_yk_langdata[$language['language_id']]['instruction'] : ''; ?></textarea>
                  </div>
                  <?php } ?>
                  <span class="help-block"><?php echo $help_instruction; ?></span>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_laterpay_button_lk; ?></label>
                <div class="col-sm-10">
                  <label class="radio-inline">
                    <?php if ($ocstore_yk_laterpay_button_lk) { ?>
                    <input type="radio" name="ocstore_yk_laterpay_button_lk" value="1" checked="checked" />
                    <?php echo $text_yes; ?>
                    <?php } else { ?>
                    <input type="radio" name="ocstore_yk_laterpay_button_lk" value="1" />
                    <?php echo $text_yes; ?>
                    <?php } ?>
                  </label>
                  <label class="radio-inline">
                    <?php if (!$ocstore_yk_laterpay_button_lk) { ?>
                    <input type="radio" name="ocstore_yk_laterpay_button_lk" value="0" checked="checked" />
                    <?php echo $text_no; ?>
                    <?php } else { ?>
                    <input type="radio" name="ocstore_yk_laterpay_button_lk" value="0" />
                    <?php echo $text_no; ?>
                    <?php } ?>
                  </label>
                  <span class="help-block"><?php echo $help_laterpay_button_lk; ?></span>
                </div>
              </div>

            </div><!-- </div id="tab-general"> -->
            <div class="tab-pane" id="tab-emails">

              <div class="form-group">
                <label class="col-sm-4 control-label"><?php echo $entry_notify_customer_success; ?></label>
                <div class="col-sm-8">
                  <label class="radio-inline">
                    <?php if ($ocstore_yk_notify_customer_success) { ?>
                    <input type="radio" name="ocstore_yk_notify_customer_success" value="1" checked="checked" />
                    <?php echo $text_yes; ?>
                    <?php } else { ?>
                    <input type="radio" name="ocstore_yk_notify_customer_success" value="1" />
                    <?php echo $text_yes; ?>
                    <?php } ?>
                  </label>
                  <label class="radio-inline">
                    <?php if (!$ocstore_yk_notify_customer_success) { ?>
                    <input type="radio" name="ocstore_yk_notify_customer_success" value="0" checked="checked" />
                    <?php echo $text_no; ?>
                    <?php } else { ?>
                    <input type="radio" name="ocstore_yk_notify_customer_success" value="0" />
                    <?php echo $text_no; ?>
                    <?php } ?>
                  </label>
                  <span class="help-block"><?php echo $help_notify_customer_success; ?></span>
                </div>
              </div>

              <div class="form-group required" id="mail-customer-success-tr" style="display: none;">
                <div class="col-sm-4">
                  <span class="help-block"><?php echo $help_fast_template; ?></span>
                  <span class="help-block"><?php echo $list_helper; ?></span>
                </div>
                <div class="col-sm-8">
                  <label class="control-label"><?php echo $entry_mail_customer_success_subject; ?></label>
                  <?php foreach ($oc_languages as $language) { ?>
                  <div class="input-group">
                    <?php $language_image = version_compare(VERSION, '2.2.0.0', '<') ? 'view/image/flags/' . $language['image'] : sprintf('language/%1$s/%1$s.png', $language['code']); ?>
                    <span class="input-group-addon"><img src="<?php echo $language_image; ?>" title="<?php echo $language['name']; ?>" /></span>
                    <input type="text" name="ocstore_yk_langdata[<?php echo $language['language_id']; ?>][mail_customer_success_subject]"
                           value="<?php echo !empty($ocstore_yk_langdata[$language['language_id']]['mail_customer_success_subject'])
                                  ? $ocstore_yk_langdata[$language['language_id']]['mail_customer_success_subject'] : $sample_mail_customer_success_subject; ?>"
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
                    <textarea name="ocstore_yk_langdata[<?php echo $language['language_id']; ?>][mail_customer_success_content]" rows="5"
                              id="mail-customer-success-content<?php echo $language['language_id']; ?>"
                              placeholder="<?php echo $entry_mail_customer_success_content; ?>"
                              data-lang="<?php echo $lang; ?>"
                              class="form-control summernote"><?php echo !empty($ocstore_yk_langdata[$language['language_id']]['mail_customer_success_content'])
                                                   ? $ocstore_yk_langdata[$language['language_id']]['mail_customer_success_content'] : $sample_mail_customer_success_content; ?></textarea>
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
                    <?php if ($ocstore_yk_notify_customer_fail) { ?>
                    <input type="radio" name="ocstore_yk_notify_customer_fail" value="1" checked="checked" />
                    <?php echo $text_yes; ?>
                    <?php } else { ?>
                    <input type="radio" name="ocstore_yk_notify_customer_fail" value="1" />
                    <?php echo $text_yes; ?>
                    <?php } ?>
                  </label>
                  <label class="radio-inline">
                    <?php if (!$ocstore_yk_notify_customer_fail) { ?>
                    <input type="radio" name="ocstore_yk_notify_customer_fail" value="0" checked="checked" />
                    <?php echo $text_no; ?>
                    <?php } else { ?>
                    <input type="radio" name="ocstore_yk_notify_customer_fail" value="0" />
                    <?php echo $text_no; ?>
                    <?php } ?>
                  </label>
                  <span class="help-block"><?php echo $help_notify_customer_fail; ?></span>
                </div>
              </div>

              <div class="form-group required" id="mail-customer-fail-tr" style="display: none;">
                <div class="col-sm-4">
                  <span class="help-block"><?php echo $help_fast_template; ?></span>
                  <span class="help-block"><?php echo $list_helper; ?></span>
                </div>
                <div class="col-sm-8">
                  <label class="control-label"><?php echo $entry_mail_customer_fail_subject; ?></label>
                  <?php foreach ($oc_languages as $language) { ?>
                  <div class="input-group">
                    <?php $language_image = version_compare(VERSION, '2.2.0.0', '<') ? 'view/image/flags/' . $language['image'] : sprintf('language/%1$s/%1$s.png', $language['code']); ?>
                    <span class="input-group-addon"><img src="<?php echo $language_image; ?>" title="<?php echo $language['name']; ?>" /></span>
                    <input type="text" name="ocstore_yk_langdata[<?php echo $language['language_id']; ?>][mail_customer_fail_subject]"
                           value="<?php echo !empty($ocstore_yk_langdata[$language['language_id']]['mail_customer_fail_subject'])
                                  ? $ocstore_yk_langdata[$language['language_id']]['mail_customer_fail_subject'] : $sample_mail_customer_fail_subject; ?>"
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
                    <textarea name="ocstore_yk_langdata[<?php echo $language['language_id']; ?>][mail_customer_fail_content]" rows="5"
                              id="mail-customer-fail-content<?php echo $language['language_id']; ?>"
                              placeholder="<?php echo $entry_mail_customer_fail_content; ?>"
                              data-lang="<?php echo $lang; ?>"
                              class="form-control summernote"><?php echo !empty($ocstore_yk_langdata[$language['language_id']]['mail_customer_fail_content'])
                                                   ? $ocstore_yk_langdata[$language['language_id']]['mail_customer_fail_content'] : $sample_mail_customer_fail_content; ?></textarea>
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
                    <?php if ($ocstore_yk_notify_admin_success) { ?>
                    <input type="radio" name="ocstore_yk_notify_admin_success" value="1" checked="checked" />
                    <?php echo $text_yes; ?>
                    <?php } else { ?>
                    <input type="radio" name="ocstore_yk_notify_admin_success" value="1" />
                    <?php echo $text_yes; ?>
                    <?php } ?>
                  </label>
                  <label class="radio-inline">
                    <?php if (!$ocstore_yk_notify_admin_success) { ?>
                    <input type="radio" name="ocstore_yk_notify_admin_success" value="0" checked="checked" />
                    <?php echo $text_no; ?>
                    <?php } else { ?>
                    <input type="radio" name="ocstore_yk_notify_admin_success" value="0" />
                    <?php echo $text_no; ?>
                    <?php } ?>
                  </label>
                  <span class="help-block"><?php echo $help_notify_admin_success; ?></span>
                </div>
              </div>

              <div class="form-group required" id="mail-admin-success-tr" style="display: none;">
                <div class="col-sm-4">
                  <span class="help-block"><?php echo $help_fast_template; ?></span>
                  <span class="help-block"><?php echo $list_helper; ?></span>
                </div>
                <div class="col-sm-8">
                  <label class="control-label" for="input-mail-admin-success-subject"><?php echo $entry_mail_admin_success_subject; ?></label>
                  <input type="text" name="ocstore_yk_mail_admin_success_subject" id="input-mail-admin-success-subject"
                         value="<?php echo !empty($ocstore_yk_mail_admin_success_subject)
                                ? $ocstore_yk_mail_admin_success_subject : $sample_mail_admin_success_subject; ?>"
                                placeholder="<?php echo $entry_mail_admin_success_subject; ?>" class="form-control" />
                  <?php if ($error_mail_admin_success_subject) { ?>
                  <div class="text-danger"><?php echo $error_mail_admin_success_subject; ?></div>
                  <?php } ?>
                  <br />
                  <label class="control-label"><?php echo $entry_mail_admin_success_content; ?></label>
                  <textarea name="ocstore_yk_mail_admin_success_content" rows="5"
                            id="input-mail-admin-success-content"
                            placeholder="<?php echo $entry_mail_admin_success_content; ?>"
                            data-lang="<?php echo $lang; ?>"
                            class="form-control summernote"><?php echo !empty($ocstore_yk_mail_admin_success_content)
                                                 ? $ocstore_yk_mail_admin_success_content : $sample_mail_admin_success_content; ?></textarea>
                  <?php if ($error_mail_admin_success_content) { ?>
                  <div class="text-danger"><?php echo $error_mail_admin_success_content; ?></div>
                  <?php } ?>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-4 control-label"><?php echo $entry_notify_admin_fail; ?></label>
                <div class="col-sm-8">
                  <label class="radio-inline">
                    <?php if ($ocstore_yk_notify_admin_fail) { ?>
                    <input type="radio" name="ocstore_yk_notify_admin_fail" value="1" checked="checked" />
                    <?php echo $text_yes; ?>
                    <?php } else { ?>
                    <input type="radio" name="ocstore_yk_notify_admin_fail" value="1" />
                    <?php echo $text_yes; ?>
                    <?php } ?>
                  </label>
                  <label class="radio-inline">
                    <?php if (!$ocstore_yk_notify_admin_fail) { ?>
                    <input type="radio" name="ocstore_yk_notify_admin_fail" value="0" checked="checked" />
                    <?php echo $text_no; ?>
                    <?php } else { ?>
                    <input type="radio" name="ocstore_yk_notify_admin_fail" value="0" />
                    <?php echo $text_no; ?>
                    <?php } ?>
                  </label>
                  <span class="help-block"><?php echo $help_notify_admin_fail; ?></span>
                </div>
              </div>

              <div class="form-group required" id="mail-admin-fail-tr" style="display: none;">
                <div class="col-sm-4">
                  <span class="help-block"><?php echo $help_fast_template; ?></span>
                  <span class="help-block"><?php echo $list_helper; ?></span>
                </div>
                <div class="col-sm-8">
                  <label class="control-label" for="input-mail-admin-fail-subject"><?php echo $entry_mail_admin_fail_subject; ?></label>
                  <input type="text" name="ocstore_yk_mail_admin_fail_subject" id="input-mail-admin-fail-subject"
                         value="<?php echo !empty($ocstore_yk_mail_admin_fail_subject)
                                ? $ocstore_yk_mail_admin_fail_subject : $sample_mail_admin_fail_subject; ?>"
                                placeholder="<?php echo $entry_mail_admin_fail_subject; ?>" class="form-control" />
                  <?php if ($error_mail_admin_fail_subject) { ?>
                  <div class="text-danger"><?php echo $error_mail_admin_fail_subject; ?></div>
                  <?php } ?>
                  <br />
                  <label class="control-label"><?php echo $entry_mail_admin_fail_content; ?></label>
                  <textarea name="ocstore_yk_mail_admin_fail_content" rows="5"
                            id="input-mail-admin-fail-content"
                            placeholder="<?php echo $entry_mail_admin_fail_content; ?>"
                            data-lang="<?php echo $lang; ?>"
                            class="form-control summernote"><?php echo !empty($ocstore_yk_mail_admin_fail_content)
                                                 ? $ocstore_yk_mail_admin_fail_content : $sample_mail_admin_fail_content; ?></textarea>
                  <?php if ($error_mail_admin_fail_content) { ?>
                  <div class="text-danger"><?php echo $error_mail_admin_fail_content; ?></div>
                  <?php } ?>
                </div>
              </div>

            </div><!-- </div id="tab-emails"> -->
            <div class="tab-pane" id="tab-settings">

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-type"><?php echo $entry_type; ?></label>
                <div class="col-sm-10">
                  <select name="ocstore_yk_type" id="input-type" class="form-control">
                    <?php foreach ($types as $key => $title) { ?>
                    <?php if ($key == $ocstore_yk_type) { ?>
                    <option value="<?php echo $key; ?>"
                            selected="selected"><?php echo $title ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $key; ?>"><?php echo $title ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                  <span class="help-block"><?php echo $help_type; ?></span>
                </div>
              </div>

              <div class="form-group required" id="wallet-tr" style="display: none;">
                <label class="col-sm-2 control-label" for="input-wallet"><?php echo $entry_wallet; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="ocstore_yk_wallet" value="<?php echo $ocstore_yk_wallet; ?>"
                         placeholder="<?php echo $entry_wallet; ?>" id="input-wallet" class="form-control" />
                  <span class="help-block"><?php echo $help_wallet; ?></span>
                  <?php if ($error_wallet) { ?>
                  <div class="text-danger"><?php echo $error_wallet; ?></div>
                  <?php } ?>
                </div>
              </div>

              <div class="form-group required" id="shop_id-tr" style="display: none;">
                <label class="col-sm-2 control-label" for="input-shop_id"><?php echo $entry_shop_id; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="ocstore_yk_shop_id" value="<?php echo $ocstore_yk_shop_id; ?>"
                         placeholder="<?php echo $entry_shop_id; ?>" id="input-shop_id" class="form-control" />
                  <span class="help-block"><?php echo $help_shop_id; ?></span>
                  <?php if ($error_shop_id) { ?>
                  <div class="text-danger"><?php echo $error_shop_id; ?></div>
                  <?php } ?>
                </div>
              </div>

              <div class="form-group required" id="scid-tr" style="display: none;">
                <label class="col-sm-2 control-label" for="input-scid"><?php echo $entry_scid; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="ocstore_yk_scid" value="<?php echo $ocstore_yk_scid; ?>"
                         placeholder="<?php echo $entry_scid; ?>" id="input-scid" class="form-control" />
                  <span class="help-block"><?php echo $help_scid; ?></span>
                  <?php if ($error_scid) { ?>
                  <div class="text-danger"><?php echo $error_scid; ?></div>
                  <?php } ?>
                </div>
              </div>

              <?php if ($permission) { ?>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-shop_password"><?php echo $entry_shop_password; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="ocstore_yk_shop_password" value="<?php echo $ocstore_yk_shop_password; ?>"
                         placeholder="<?php echo $entry_shop_password; ?>" id="input-shop_password" class="form-control" />
                  <span class="help-block"><?php echo $help_shop_password; ?></span>
                  <?php if ($error_shop_password) { ?>
                  <div class="text-danger"><?php echo $error_shop_password; ?></div>
                  <?php } ?>
                </div>
              </div>
              <?php } ?>

              <div class="form-group" id="physical_enabled_methods-tr" style="display: none;">
                <div class="col-sm-12">
                    <table class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <td class="text-left" rowspan="2"><?php echo $entry_enabled_methods; ?><div class="help-block" style="font-weight:normal;"><?php echo $text_physical_limits; ?></div></td>
                                <td style="vertical-align:top;" class="text-center"><?php echo $entry_comission; ?><div class="help-block" style="font-weight:normal;"><?php echo $help_comission; ?></div></td>
                                <td style="vertical-align:top;" class="text-center"><?php echo $entry_minimal_order; ?><div class="help-block" style="font-weight:normal;"><?php echo $help_minimal_order; ?></div></td>
                                <td style="vertical-align:top;" class="text-center"><?php echo $entry_maximal_order; ?><div class="help-block" style="font-weight:normal;"><?php echo $help_maximal_order; ?></div></td>
                                <td style="vertical-align:top;" class="text-center"><?php echo $entry_sort_order; ?><div class="help-block" style="font-weight:normal;"><?php echo $help_sort_order; ?></div></td>
                            </tr>
                            <tr>
                                <td style="vertical-align:top;" class="text-center"><?php echo $entry_description; ?><div class="help-block" style="font-weight:normal;"><?php echo $help_description; ?></div></td>
                                <td style="vertical-align:top;" class="text-center"><?php echo $entry_title; ?><div class="help-block" style="font-weight:normal;"><?php echo $help_title; ?></div></td>
                                <td style="vertical-align:top;" class="text-center"><?php echo $entry_button_confirm; ?><div class="help-block" style="font-weight:normal;"><?php echo $help_button_confirm; ?></div></td>
                                <td style="vertical-align:top;" class="text-center"><?php echo $entry_geo_zone; ?></td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($physical_enabled_methods as $key => $value) { ?>
                            <tr>
                                <td class="text-left" rowspan="2">
                                    <label class="radio-inline">
                                    <input type="checkbox" class="status" name="ocstore_yk_physical_enabled_methods[<?php echo $key; ?>]"
                                           value="<?php echo $value; ?>" <?php echo in_array($value, $ocstore_yk_physical_enabled_methods) ? 'checked="checked"' : ''?>/> - <?php echo $value; ?>
                                    <input type="hidden" name="ocstore_yk_physical_<?php echo $key; ?>_status"
                                           value="<?php echo (int)in_array($value, $ocstore_yk_physical_enabled_methods); ?>" />
                                    </label>
                                </td>
                                <td class="text-center">
                                    <input type="text" name="ocstore_yk_physical_<?php echo $key; ?>_comission"
                                           value="<?php echo ${'ocstore_yk_physical_' . $key . '_comission'}; ?>" size="3"/>&nbsp;%
                                </td>
                                <td class="text-center">
                                    <input type="text" name="ocstore_yk_physical_<?php echo $key; ?>_minimal_order"
                                           value="<?php echo ${'ocstore_yk_physical_' . $key . '_minimal_order'}; ?>" size="8"/>&nbsp;<?php echo $backend_currency; ?>
                                </td>
                                <td class="text-center">
                                    <input type="text" name="ocstore_yk_physical_<?php echo $key; ?>_maximal_order"
                                           value="<?php echo ${'ocstore_yk_physical_' . $key . '_maximal_order'}; ?>" size="8"/>&nbsp;<?php echo $backend_currency; ?>
                                </td>
                                <td class="text-center">
                                    <input type="text" name="ocstore_yk_physical_<?php echo $key; ?>_sort_order"
                                           value="<?php echo ${'ocstore_yk_physical_' . $key . '_sort_order'}; ?>" size="3"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">
                                    <label style="cursor:pointer; font-weight:normal"><input type="radio" name="ocstore_yk_physical_<?php echo $key; ?>_description"
                                                  value="1"<?php echo ${'ocstore_yk_physical_' . $key . '_description'} ? ' checked="checked"' : ''; ?> />
                                                  <?php echo $text_yes; ?></label>
                                    <label style="cursor:pointer; font-weight:normal"><input type="radio" name="ocstore_yk_physical_<?php echo $key; ?>_description"
                                                  value="0"<?php echo !${'ocstore_yk_physical_' . $key . '_description'} ? ' checked="checked"' : ''; ?> />
                                                  <?php echo $text_no; ?></label>
                                </td>
                                <td class="text-center">
                                    <?php foreach ($oc_languages as $language) { ?>
                                    <div class="input-group">
                                      <?php $language_image = version_compare(VERSION, '2.2.0.0', '<') ? 'view/image/flags/' . $language['image'] : sprintf('language/%1$s/%1$s.png', $language['code']); ?>
                                      <span class="input-group-addon"><img src="<?php echo $language_image; ?>" title="<?php echo $language['name']; ?>" /></span>
                                      <input type="text" name="ocstore_yk_physical_<?php echo $key; ?>_langdata[<?php echo $language['language_id']; ?>][title]"
                                             class="form-control" value="<?php echo !empty(${'ocstore_yk_physical_' . $key . '_langdata'}[$language['language_id']]['title'])
                                                    ? ${'ocstore_yk_physical_' . $key . '_langdata'}[$language['language_id']]['title'] : $value; ?>" />
                                    </div>
                                    <?php } ?>
                                </td>
                                <td class="text-center">
                                    <?php foreach ($oc_languages as $language) { ?>
                                    <div class="input-group">
                                      <?php $language_image = version_compare(VERSION, '2.2.0.0', '<') ? 'view/image/flags/' . $language['image'] : sprintf('language/%1$s/%1$s.png', $language['code']); ?>
                                      <span class="input-group-addon"><img src="<?php echo $language_image; ?>" title="<?php echo $language['name']; ?>" /></span>
                                      <input type="text" name="ocstore_yk_physical_<?php echo $key; ?>_langdata[<?php echo $language['language_id']; ?>][button_confirm]"
                                              class="form-control" value="<?php echo !empty(${'ocstore_yk_physical_' . $key . '_langdata'}[$language['language_id']]['button_confirm'])
                                                    ? ${'ocstore_yk_physical_' . $key . '_langdata'}[$language['language_id']]['button_confirm'] : $sample_button_confirm; ?>" />
                                    </div>
                                    <?php } ?>
                                </td>
                                <td class="text-center">
                                    <select name="ocstore_yk_physical_<?php echo $key; ?>_geo_zone_id" class="form-control">
                                      <option value="0"><?php echo $text_all_zones; ?></option>
                                      <?php foreach ($geo_zones as $geo_zone) { ?>
                                      <option value="<?php echo $geo_zone['geo_zone_id']; ?>"<?php echo $geo_zone['geo_zone_id'] == ${'ocstore_yk_physical_' . $key . '_geo_zone_id'} ?
                                          ' selected="selected"' : ''; ?>><?php echo $geo_zone['name']; ?></option>
                                      <?php } ?>
                                    </select>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
              </div>

              <div class="form-group" id="company_enabled_methods-tr" style="display: none;">
                <div class="col-sm-12">
                    <table class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <td class="text-left" rowspan="2"><?php echo $entry_enabled_methods; ?><div class="help-block" style="font-weight:normal;"><?php echo $text_company_limits; ?></div></td>
                                <td style="vertical-align:top;" class="text-center"><?php echo $entry_comission; ?><div class="help-block" style="font-weight:normal;"><?php echo $help_comission; ?></div></td>
                                <td style="vertical-align:top;" class="text-center"><?php echo $entry_minimal_order; ?><div class="help-block" style="font-weight:normal;"><?php echo $help_minimal_order; ?></div></td>
                                <td style="vertical-align:top;" class="text-center"><?php echo $entry_maximal_order; ?><div class="help-block" style="font-weight:normal;"><?php echo $help_maximal_order; ?></div></td>
                                <td style="vertical-align:top;" class="text-center"><?php echo $entry_sort_order; ?><div class="help-block" style="font-weight:normal;"><?php echo $help_sort_order; ?></div></td>
                            </tr>
                            <tr>
                                <td style="vertical-align:top;" class="text-center"><?php echo $entry_description; ?><div class="help-block" style="font-weight:normal;"><?php echo $help_description; ?></div></td>
                                <td style="vertical-align:top;" class="text-center"><?php echo $entry_title; ?><div class="help-block" style="font-weight:normal;"><?php echo $help_title; ?></div></td>
                                <td style="vertical-align:top;" class="text-center"><?php echo $entry_button_confirm; ?><div class="help-block" style="font-weight:normal;"><?php echo $help_button_confirm; ?></div></td>
                                <td style="vertical-align:top;" class="text-center"><?php echo $entry_geo_zone; ?></td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($company_enabled_methods as $key => $value) { ?>
                            <tr>
                                <td class="text-left" rowspan="2">
                                    <label class="radio-inline">
                                    <input type="checkbox" class="status" name="ocstore_yk_company_enabled_methods[<?php echo $key; ?>]"
                                           value="<?php echo $value; ?>" <?php echo in_array($value, $ocstore_yk_company_enabled_methods) ? 'checked="checked"' : ''?>/> - <?php echo $value; ?>
                                    <input type="hidden" name="ocstore_yk_company_<?php echo $key; ?>_status"
                                           value="<?php echo (int)in_array($value, $ocstore_yk_company_enabled_methods); ?>" />
                                    </label>
                                </td>
                                <td class="text-center">
                                    <input type="text" name="ocstore_yk_company_<?php echo $key; ?>_comission"
                                           value="<?php echo ${'ocstore_yk_company_' . $key . '_comission'}; ?>" size="3"/>&nbsp;%
                                </td>
                                <td class="text-center">
                                    <input type="text" name="ocstore_yk_company_<?php echo $key; ?>_minimal_order"
                                           value="<?php echo ${'ocstore_yk_company_' . $key . '_minimal_order'}; ?>" size="8"/>&nbsp;<?php echo $backend_currency; ?>
                                </td>
                                <td class="text-center">
                                    <input type="text" name="ocstore_yk_company_<?php echo $key; ?>_maximal_order"
                                           value="<?php echo ${'ocstore_yk_company_' . $key . '_maximal_order'}; ?>" size="8"/>&nbsp;<?php echo $backend_currency; ?>
                                </td>
                                <td class="text-center">
                                    <input type="text" name="ocstore_yk_company_<?php echo $key; ?>_sort_order"
                                           value="<?php echo ${'ocstore_yk_company_' . $key . '_sort_order'}; ?>" size="3"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">
                                    <label style="cursor:pointer; font-weight:normal"><input type="radio" name="ocstore_yk_company_<?php echo $key; ?>_description"
                                                  value="1"<?php echo ${'ocstore_yk_company_' . $key . '_description'} ? ' checked="checked"' : ''; ?> />
                                                  <?php echo $text_yes; ?></label>
                                    <label style="cursor:pointer; font-weight:normal"><input type="radio" name="ocstore_yk_company_<?php echo $key; ?>_description"
                                                  value="0"<?php echo !${'ocstore_yk_company_' . $key . '_description'} ? ' checked="checked"' : ''; ?> />
                                                  <?php echo $text_no; ?></label>
                                </td>
                                <td class="text-center">
                                    <?php foreach ($oc_languages as $language) { ?>
                                    <div class="input-group">
                                      <?php $language_image = version_compare(VERSION, '2.2.0.0', '<') ? 'view/image/flags/' . $language['image'] : sprintf('language/%1$s/%1$s.png', $language['code']); ?>
                                      <span class="input-group-addon"><img src="<?php echo $language_image; ?>" title="<?php echo $language['name']; ?>" /></span>
                                      <input type="text" name="ocstore_yk_company_<?php echo $key; ?>_langdata[<?php echo $language['language_id']; ?>][title]"
                                             class="form-control" value="<?php echo !empty(${'ocstore_yk_company_' . $key . '_langdata'}[$language['language_id']]['title'])
                                                    ? ${'ocstore_yk_company_' . $key . '_langdata'}[$language['language_id']]['title'] : $value; ?>" />
                                    </div>
                                    <?php } ?>
                                </td>
                                <td class="text-center">
                                    <?php foreach ($oc_languages as $language) { ?>
                                    <div class="input-group">
                                      <?php $language_image = version_compare(VERSION, '2.2.0.0', '<') ? 'view/image/flags/' . $language['image'] : sprintf('language/%1$s/%1$s.png', $language['code']); ?>
                                      <span class="input-group-addon"><img src="<?php echo $language_image; ?>" title="<?php echo $language['name']; ?>" /></span>
                                      <input type="text" name="ocstore_yk_company_<?php echo $key; ?>_langdata[<?php echo $language['language_id']; ?>][button_confirm]"
                                              class="form-control" value="<?php echo !empty(${'ocstore_yk_company_' . $key . '_langdata'}[$language['language_id']]['button_confirm'])
                                                    ? ${'ocstore_yk_company_' . $key . '_langdata'}[$language['language_id']]['button_confirm'] : $sample_button_confirm; ?>" />
                                    </div>
                                    <?php } ?>
                                </td>
                                <td class="text-center">
                                    <select name="ocstore_yk_company_<?php echo $key; ?>_geo_zone_id" class="form-control">
                                      <option value="0"><?php echo $text_all_zones; ?></option>
                                      <?php foreach ($geo_zones as $geo_zone) { ?>
                                      <option value="<?php echo $geo_zone['geo_zone_id']; ?>"<?php echo $geo_zone['geo_zone_id'] == ${'ocstore_yk_company_' . $key . '_geo_zone_id'} ?
                                          ' selected="selected"' : ''; ?>><?php echo $geo_zone['name']; ?></option>
                                      <?php } ?>
                                    </select>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-test-mode"><?php echo $entry_test_mode; ?></label>
                <div class="col-sm-10">
                  <select name="ocstore_yk_test_mode" id="input-test-mode" class="form-control">
                    <?php foreach ($modes as $key => $title) { ?>
                    <?php if ($key == $ocstore_yk_test_mode) { ?>
                    <option value="<?php echo $key; ?>"
                            selected="selected"><?php echo $title ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $key; ?>"><?php echo $title ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                  <span class="help-block" id="help-test-mode-company" style="display: none;"><?php echo $help_test_mode_company; ?></span>
                  <span class="help-block" id="help-test-mode-physical" style="display: none;"><?php echo $help_test_mode_physical; ?></span>
                </div>
              </div>

            </div><!-- </div id="tab-settings"> -->
            <div class="tab-pane" id="tab-log">

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-log"><?php echo $entry_log; ?></label>
                <div class="col-sm-8">
                  <input type="hidden" name="ocstore_yk_log_filename" value="<?php echo $log_filename ?>" />
                  <input type="hidden" name="ocstore_yk_version" value="<?php echo $version ?>" />
                  <select name="ocstore_yk_log" id="input-log" class="form-control">
                    <?php foreach ($logs as $key => $value) { ?>
                    <?php if ($key == $ocstore_yk_log) { ?>
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
                    <pre id="pre-log" style="font-size:11px; min-height: 280px;"><?php foreach ($log_lines as $log_line) {echo htmlentities($log_line, ENT_NOQUOTES, 'UTF-8');} ?></pre>
                  </div>
                  <span class="help-block"><?php echo $help_log_file; ?></span>
                </div>
              </div>

            </div><!-- </div id="tab-log"> -->
            <div class="tab-pane" id="tab-information">

              <div class="alert alert-success" style="padding: 30px 10px;"><i class="fa fa-check-circle"></i></div>
                <textarea style="display: none;" id="info-physical"><?php echo $text_info_physical; ?></textarea>
                <textarea style="display: none;" id="info-company"><?php echo $text_info_company; ?></textarea>
                <div class="help" style="display:none; padding:10px; color:#f00000; text-align:center;" id="ssl-info"><?php echo $text_ssl; ?></div>

                <?php foreach ($informations as $key => $information){ ?>
                    <div id="information-<?php echo $key; ?>" style="display: none">
                        <?php foreach ($information as $text => $value) { ?>
                        <div class="form-group">
                          <label class="col-sm-4 control-label"><?php echo $text; ?></label>
                          <div class="col-sm-8">
                            <span class="form-control" style="height:auto;"><?php echo $value; ?></span>
                          </div>
                        </div>
                        <?php } ?>
                    </div><!-- </div id="information-<?php echo $key; ?>"> -->
                <?php } ?>
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
    $('[name="ocstore_yk_laterpay_mode"]').each(function(indx){
      changeLaterpayMode($(this).val());
    });
    $('input:radio[name="ocstore_yk_notify_customer_success"]:checked').each(function(indx){
      changeNotifyCustomerSuccess($(this).val());
    });
    $('input:radio[name="ocstore_yk_notify_customer_fail"]:checked').each(function(indx){
      changeNotifyCustomerFail($(this).val());
    });
    $('input:radio[name="ocstore_yk_notify_admin_success"]:checked').each(function(indx){
      changeNotifyAdminSuccess($(this).val());
    });
    $('input:radio[name="ocstore_yk_notify_admin_fail"]:checked').each(function(indx){
      changeNotifyAdminFail($(this).val());
    });
    $('[name="ocstore_yk_type"] option:selected').each(function(indx){
      changeType($(this).val());
    });
  });
  
    $('[name="ocstore_yk_laterpay_mode"]').on('change', function(){
      changeLaterpayMode($(this).val());
    });
    $('input:radio[name="ocstore_yk_notify_customer_success"]').change(function(){
      changeNotifyCustomerSuccess($(this).val());
    });
    $('input:radio[name="ocstore_yk_notify_customer_fail"]').change(function(){
      changeNotifyCustomerFail($(this).val());
    });
    $('input:radio[name="ocstore_yk_notify_admin_success"]').change(function(){
      changeNotifyAdminSuccess($(this).val());
    });
    $('input:radio[name="ocstore_yk_notify_admin_fail"]').change(function(){
      changeNotifyAdminFail($(this).val());
    });
    $('[name="ocstore_yk_type"]').on('change', function(){
      changeType($(this).val());
    });

  function changeLaterpayMode(value) {
      ((value == '1') || (value == '2')) ? $("#laterpay-mode-tr").fadeIn('slow') : $("#laterpay-mode-tr").fadeOut('slow').delay(200);
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
  function changeType(value) {
      value == '1' ? $("#wallet-tr").fadeOut('slow').delay(200) : $("#wallet-tr").fadeIn('slow');
      value == '1' ? $("#scid-tr").fadeIn('slow') : $("#scid-tr").fadeOut('slow').delay(200);
      value == '1' ? $("#shop_id-tr").fadeIn('slow') : $("#shop_id-tr").fadeOut('slow').delay(200);
      value == '0' ? $("#physical_enabled_methods-tr").fadeIn('slow') : $("#physical_enabled_methods-tr").fadeOut('slow').delay(200);
      value == '1' ? $("#company_enabled_methods-tr").fadeIn('slow') : $("#company_enabled_methods-tr").fadeOut('slow').delay(200);
      value == '0' ? $("#information-physical").css('display', 'block') : $("#information-physical").css('display', 'none');
      value == '1' ? $("#information-company").css('display', 'block') : $("#information-company").css('display', 'none');
      value == '1' ? $("#ssl-info").css('display', 'block') : $("#ssl-info").css('display', 'none');
      value == '0' ? $("div.alert-success").html($('#info-physical').val()) : $("div.alert-success").html($('#info-company').val());
      value == '1' ? $("div.alert-success").html($('#info-company').val()) : $("div.alert-success").html($('#info-physical').val());
      value == '1' ? $("#help-test-mode-company").fadeIn('slow') : $("#help-test-mode-company").fadeOut('slow').delay(200);
      value == '0' ? $("#help-test-mode-physical").fadeIn('slow') : $("#help-test-mode-physical").fadeOut('slow').delay(200);
  }

  $('#button-clear').on('click', function() {
    if (confirm('<?php echo $text_confirm; ?>')){
      $.ajax({
        url: '<?php echo $clear_log; ?>',
        type: 'post',
        dataType: 'json',
        beforeSend: function() {
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

  $('input.status').on('click', function() {
    $(this).next('input').attr('value', Number($(this).prop("checked")));
  });
//--></script>
<?php echo $footer; ?>
