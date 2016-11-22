<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <?php if ($permission) { ?>
        <button type="submit" form="form-sberbank_transfer" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <?php } ?>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><img src="view/image/payment/sberbank_transfer_icon.png" width="26" height="26"> <?php echo $heading_title; ?></h1>
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
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-sberbank_transfer" class="form-horizontal">
          <div class="form-group required">
            <label class="col-sm-2 control-label"><?php echo $entry_bank; ?></label>
            <div class="col-sm-10">
              <?php foreach ($languages as $language) { ?>
              <div class="input-group"><span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span>
                <input type="text" name="sberbank_transfer_bank_<?php echo $language['language_id']; ?>"
                     value="<?php echo !empty(${'sberbank_transfer_bank_' . $language['language_id']}) ? ${'sberbank_transfer_bank_' . $language['language_id']} : ''; ?>"
                     placeholder="<?php echo $entry_bank; ?>" id="input-sberbank_transfer_bank_<?php echo $language['language_id']; ?>" class="form-control" />
              </div>
              <?php } ?>
              <?php if ($error_bank) { ?>
              <div class="text-danger"><?php echo $error_bank; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-sberbank_transfer_inn"><?php echo $entry_inn; ?></span></label>
            <div class="col-sm-10">
                <input type="text" name="sberbank_transfer_inn" id="input-sberbank_transfer_inn"
                     value="<?php echo $sberbank_transfer_inn; ?>"
                     placeholder="<?php echo $entry_inn; ?>" id="input-sberbank_transfer_inn; ?>" class="form-control" />
              <?php if ($error_inn) { ?>
              <div class="text-danger"><?php echo $error_inn; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-sberbank_transfer_rs"><?php echo $entry_rs; ?></span></label>
            <div class="col-sm-10">
                <input type="text" name="sberbank_transfer_rs" id="input-sberbank_transfer_rs"
                     value="<?php echo $sberbank_transfer_rs; ?>"
                     placeholder="<?php echo $entry_rs; ?>" id="input-sberbank_transfer_rs; ?>" class="form-control" />
              <?php if ($error_rs) { ?>
              <div class="text-danger"><?php echo $error_rs; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label"><?php echo $entry_bankuser; ?></label>
            <div class="col-sm-10">
              <?php foreach ($languages as $language) { ?>
              <div class="input-group"><span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span>
                <input type="text" name="sberbank_transfer_bankuser_<?php echo $language['language_id']; ?>"
                     value="<?php echo !empty(${'sberbank_transfer_bankuser_' . $language['language_id']}) ? ${'sberbank_transfer_bankuser_' . $language['language_id']} : ''; ?>"
                     placeholder="<?php echo $entry_bankuser; ?>" id="input-sberbank_transfer_bankuser_<?php echo $language['language_id']; ?>" class="form-control" />
              </div>
              <?php } ?>
              <?php if ($error_bankuser) { ?>
              <div class="text-danger"><?php echo $error_bankuser; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-sberbank_transfer_bik"><?php echo $entry_bik; ?></span></label>
            <div class="col-sm-10">
                <input type="text" name="sberbank_transfer_bik" id="input-sberbank_transfer_bik"
                     value="<?php echo $sberbank_transfer_bik; ?>"
                     placeholder="<?php echo $entry_bik; ?>" id="input-sberbank_transfer_bik; ?>" class="form-control" />
              <?php if ($error_bik) { ?>
              <div class="text-danger"><?php echo $error_bik; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-sberbank_transfer_ks"><?php echo $entry_ks; ?></span></label>
            <div class="col-sm-10">
                <input type="text" name="sberbank_transfer_ks" id="input-sberbank_transfer_ks"
                     value="<?php echo $sberbank_transfer_ks; ?>"
                     placeholder="<?php echo $entry_ks; ?>" id="input-sberbank_transfer_ks; ?>" class="form-control" />
              <?php if ($error_ks) { ?>
              <div class="text-danger"><?php echo $error_ks; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_title; ?>"><?php echo $entry_title; ?></span></label>
            <div class="col-sm-10">
              <?php foreach ($languages as $language) { ?>
              <div class="input-group"><span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span>
                <input type="text" name="sberbank_transfer_title_<?php echo $language['language_id']; ?>"
                     value="<?php echo !empty(${'sberbank_transfer_title_' . $language['language_id']})
                                    ? ${'sberbank_transfer_title_' . $language['language_id']} : $text_title_default; ?>"
                     placeholder="<?php echo $entry_title; ?>" id="input-sberbank_transfer_title_<?php echo $language['language_id']; ?>" class="form-control" />
              </div>
              <?php } ?>
              <?php if ($error_title) { ?>
              <div class="text-danger"><?php echo $error_title; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_button_confirm; ?>"><?php echo $entry_button_confirm; ?></span></label>
            <div class="col-sm-10">
              <?php foreach ($languages as $language) { ?>
              <div class="input-group"><span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span>
                <input type="text" name="sberbank_transfer_button_confirm_<?php echo $language['language_id']; ?>"
                     value="<?php echo !empty(${'sberbank_transfer_button_confirm_' . $language['language_id']})
                                    ? ${'sberbank_transfer_button_confirm_' . $language['language_id']} : $text_button_confirm_default; ?>"
                     placeholder="<?php echo $entry_button_confirm; ?>" id="input-sberbank_transfer_button_confirm_<?php echo $language['language_id']; ?>" class="form-control" />
              </div>
              <?php } ?>
              <?php if ($error_button_confirm) { ?>
              <div class="text-danger"><?php echo $error_button_confirm; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sberbank_transfer_minimal_order"><span data-toggle="tooltip" title="<?php echo $help_minimal_order; ?>"><?php echo $entry_minimal_order; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="sberbank_transfer_minimal_order" value="<?php echo $sberbank_transfer_minimal_order; ?>"
                     placeholder="<?php echo $entry_minimal_order; ?>" id="input-sberbank_transfer_minimal_order" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sberbank_transfer_maximal_order"><span data-toggle="tooltip" title="<?php echo $help_maximal_order; ?>"><?php echo $entry_maximal_order; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="sberbank_transfer_maximal_order" value="<?php echo $sberbank_transfer_maximal_order; ?>"
                     placeholder="<?php echo $entry_maximal_order; ?>" id="input-sberbank_transfer_maximal_order" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sberbank_transfer_order_status_id"><?php echo $entry_order_status; ?></label>
            <div class="col-sm-10">
              <select name="sberbank_transfer_order_status_id" id="input-sberbank_transfer_order_status_id" class="form-control">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $sberbank_transfer_order_status_id) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sberbank_transfer_geo_zone_id"><?php echo $entry_geo_zone; ?></label>
            <div class="col-sm-10">
              <select name="sberbank_transfer_geo_zone_id" id="input-sberbank_transfer_geo_zone_id" class="form-control">
                <option value="0"><?php echo $text_all_zones; ?></option>
                <?php foreach ($geo_zones as $geo_zone) { ?>
                <?php if ($geo_zone['geo_zone_id'] == $sberbank_transfer_geo_zone_id) { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sberbank_transfer_status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="sberbank_transfer_status" id="input-sberbank_transfer_status" class="form-control">
                <?php if ($sberbank_transfer_status) { ?>
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
            <label class="col-sm-2 control-label" for="input-sberbank_transfer_sort_order"><?php echo $entry_sort_order; ?></label>
            <div class="col-sm-10">
              <input type="text" name="sberbank_transfer_sort_order" value="<?php echo $sberbank_transfer_sort_order; ?>"
                     placeholder="<?php echo $entry_sort_order; ?>" id="input-sberbank_transfer_sort_order" class="form-control" />
            </div>
          </div>
        </form>
      </div><!-- </div class="panel-body"> -->
    </div><!-- </div class="panel panel-default"> -->
  </div><!-- </div class="container-fluid"> -->
</div><!-- </div id="content"> -->
<?php echo $footer; ?>