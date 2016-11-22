<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form" class="btn btn-primary"><i class="fa fa-save"></i> <?php echo $button_save; ?></button>
        <a href="<?php echo $cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i> <?php echo $button_cancel; ?></a>
      </div>
      <h1><?php echo $heading_title; ?></h1>
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
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
          <fieldset>
            <legend><i class="fa fa-info-circle"></i> <?php echo $text_info; ?></legend>

              <div class="form-group">
                <span class="col-sm-12"><?php echo $text_register; ?></span>
              </div>

              <div class="form-group">
                <span class="col-sm-12"><?php echo $text_instruction; ?></span>
              </div>

              <div class="form-group">
                <label class="col-sm-4 control-label" for="input-result_url"><?php echo $entry_result_url; ?></label>
                <div class="col-sm-8">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-link"></i></span>
                    <input type="text" readonly="readonly" value="<?php echo $ocstore_w1_result_url; ?>" id="input-result_url" class="form-control">
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-4 control-label" for="input-ecp"><?php echo $entry_ecp; ?></label>
                <div class="col-sm-8">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-link"></i></span>
                    <input type="text" readonly="readonly" value="MD5" id="input-ecp" class="form-control">
                  </div>
                </div>
              </div>

          </fieldset>
          <fieldset>
            <legend><i class="fa fa-wrench"></i> <?php echo $text_setting; ?></legend>

              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-shop_id"><?php echo $entry_shop_id; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="ocstore_w1_shop_id" value="<?php echo $ocstore_w1_shop_id; ?>"
                         placeholder="<?php echo $entry_shop_id; ?>" id="input-shop_id" class="form-control" />
                  <?php if ($error_shop_id) { ?>
                  <div class="text-danger"><?php echo $error_shop_id; ?></div>
                  <?php } ?>
                </div>
              </div>

              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-sign"><?php echo $entry_sign; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="ocstore_w1_sign" value="<?php echo $ocstore_w1_sign; ?>"
                         placeholder="<?php echo $entry_sign; ?>" id="input-sign" class="form-control" />
                  <?php if ($error_sign) { ?>
                  <div class="text-danger"><?php echo $error_sign; ?></div>
                  <?php } ?>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-currency"><?php echo $entry_currency; ?></label>
                <div class="col-sm-10">
                  <select name="ocstore_w1_currency" id="input-currency" class="form-control">
                    <?php foreach ($currencies as $key => $value) { ?>
                    <?php if ($key == $ocstore_w1_currency) { ?>
                    <option value="<?php echo $key; ?>"
                            selected="selected"><?php echo $value['title']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $key; ?>"><?php echo $value['title']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-order_confirm_status"><?php echo $entry_order_confirm_status; ?></label>
                <div class="col-sm-10">
                  <select name="ocstore_w1_order_confirm_status_id" id="input-order_confirm_status" class="form-control">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if (!isset($order_status['order_status_id'])) continue; ?>
                    <?php if ($order_status['order_status_id'] == $ocstore_w1_order_confirm_status_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-order_status"><?php echo $entry_order_status; ?></label>
                <div class="col-sm-10">
                  <select name="ocstore_w1_order_status_id" id="input-order_status" class="form-control">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if (!isset($order_status['order_status_id'])) continue; ?>
                    <?php if ($order_status['order_status_id'] == $ocstore_w1_order_status_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-order_fail_status"><?php echo $entry_order_fail_status; ?></label>
                <div class="col-sm-10">
                  <select name="ocstore_w1_order_fail_status_id" id="input-order_fail_status" class="form-control">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if (!isset($order_status['order_status_id'])) continue; ?>
                    <?php if ($order_status['order_status_id'] == $ocstore_w1_order_fail_status_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-geo_zone"><?php echo $entry_geo_zone; ?></label>
                <div class="col-sm-10">
                  <select name="ocstore_w1_geo_zone_id" id="input-geo_zone" class="form-control">
                    <option value="0"><?php echo $text_all_zones; ?></option>
                    <?php foreach ($geo_zones as $geo_zone) { ?>
                    <?php if ($geo_zone['geo_zone_id'] == $ocstore_w1_geo_zone_id) { ?>
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
                  <select name="ocstore_w1_status" id="input-status" class="form-control">
                    <?php if ($ocstore_w1_status) { ?>
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
                <label class="col-sm-2 control-label" for="input-sort_order"><?php echo $entry_sort_order; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="ocstore_w1_sort_order" value="<?php echo $ocstore_w1_sort_order; ?>"
                         placeholder="<?php echo $entry_sort_order; ?>" id="input-sort_order" class="form-control" />
                </div>
              </div>

          </fieldset>
        </form>
      </div><!-- </div class="panel-body"> -->
    </div><!-- </div class="panel panel-default"> -->
  </div><!-- </div class="container-fluid"> -->
</div><!-- </div id="content"> -->