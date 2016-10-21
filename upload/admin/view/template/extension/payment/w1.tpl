<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form-w1" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary">
                    <i class="fa fa-save"></i> <?php echo $button_save; ?>
                </button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default">
                    <i class="fa fa-reply"></i> <?php echo $button_cancel; ?>
                </a>
            </div>
            <h1><?php echo $heading_title; ?></h1>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                    <li>
                        <a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="container-fluid">
        <?php if ($error_warning) { ?>
            <div class="alert alert-danger">
                <i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        <?php } ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="fa fa-pencil"></i> <?php echo $text_edit; ?>
                </h3>
            </div>
            <div class="panel-body">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-pp-express" class="form-horizontal">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_info" data-toggle="tab"><?php echo $tab_info; ?></a></li>
                        <li><a href="#tab_api" data-toggle="tab"><?php echo $tab_api; ?></a></li>
                        <li><a href="#tab_dop_api" data-toggle="tab"><?php echo $tab_dop_api; ?></a></li>
                        <li><a href="#tab_order_status" data-toggle="tab"><?php echo $tab_order_status; ?></a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_info">
                            <div class="media">
                                <div class="media-left">
                                  <a href="https://www.walletone.com/merchant/?promo=ocstore" target="_blank">
                                    <img class="media-object" src="view/image/payment/w1/w1_logo.png" alt="Wallet One Единая касса">
                                  </a>
                                  <p><?php echo $text_about; ?></p>
                                  <ul class="list-characteristic">
                                      <li><i>&#10004;</i><?php echo $text_connect; ?></li>
                                        <li><i>&#10004;</i><?php echo $text_integration; ?></li>
                                        <li><i>&#10004;</i><?php echo $text_analityc; ?></li>
                                        <li><i>&#10004;</i><?php echo $text_support; ?></li>
                                  </ul>
                                </div>
                                <div class="media-body">
                                    <blockquote>
                                      <p><span><?php echo $text_fast_setting; ?></span></p>
                                      <ul class="list-settings-ul">
                                          <li><div class="circle-fgnghn">1</div><span><?php echo $text_register; ?></span></li>
                                          <li><div class="circle-fgnghn">2</div><span><?php echo $text_fill_fields; ?></span></li>
                                          <li><div class="circle-fgnghn">3</div><span><?php echo $text_payment_methods; ?></span></li>
                                      </ul>
                                      <ul class="app_links">
                                          <li><a href="https://itunes.apple.com/ru/app/merchant-w1/id925378607?mt=8" target="_blank" class="app_link"></a></li>
                                          <li><a href="https://play.google.com/store/apps/details?id=com.w1.merchant.android" target="_blank" class="app_link __android"></a></li>
                                      </ul>
                                      <div class="clearfix"></div>
                                    </blockquote>
                                </div>
                              </div>
                        </div>
                        <div class="tab-pane" id="tab_api">
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                                <div class="col-sm-10">
                                  <select name="w1_status" id="input-status" class="form-control">
                                    <?php if ($w1_status) { ?>
                                        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                        <option value="0"><?php echo $text_disabled; ?></option>
                                    <?php } 
                                    else { ?>
                                        <option value="1"><?php echo $text_enabled; ?></option>
                                        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                    <?php } ?>
                                  </select>
                                </div>
                            </div>
                            <div class="form-group">
                              <label class="col-sm-2 control-label" for="input-geo-zone"><?php echo $entry_geo_zone; ?></label>
                              <div class="col-sm-10">
                                <select name="w1_geo_zone_id" id="input-geo-zone" class="form-control">
                                  <option value="0"><?php echo $text_all_zones; ?></option>
                                  <?php foreach ($geo_zones as $geo_zone) { ?>
                                  <?php if ($geo_zone['geo_zone_id'] == $w1_geo_zone_id) { ?>
                                  <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                                  <?php } else { ?>
                                  <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                                  <?php } ?>
                                  <?php } ?>
                                </select>
                              </div>
                            </div>
                            <div class="form-group">
                              <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
                              <div class="col-sm-10">
                                <input type="text" name="w1_sort_order" value="<?php echo $w1_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
                              </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-merchant_id">
                                    <span data-toggle="tooltip" title="<?php echo $help_merchant_id; ?>"><?php echo $entry_merchant_id; ?></span>
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" name="w1_merchant_id" value="<?php echo $w1_merchant_id; ?>" placeholder="<?php echo $entry_merchant_id; ?>" 
                                           id="entry-merchant_id" class="form-control" />
                                    <?php if ($error_merchant_id) { ?>
                                        <div class="text-danger"><?php echo $error_merchant_id; ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="entry-currency_id"><?php echo $entry_currency_id; ?></label>
                                <div class="col-sm-10">
                                    <select name="w1_currency_id" id="input-currency" class="form-control">
                                        <?php foreach ($currencies as $key => $currency) { ?>
                                            <?php if ($key == $w1_currency_id) { ?>
                                                <option value="<?php echo $currency; ?>" selected="selected"><?php echo $currency; ?></option>
                                            <?php } else { ?>
                                                <option value="<?php echo $key; ?>"><?php echo $currency; ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-signature">
                                    <span data-toggle="tooltip" title="<?php echo $help_signature; ?>"><?php echo $entry_signature; ?></span>
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" name="w1_signature" value="<?php echo $w1_signature; ?>" placeholder="<?php echo $entry_signature; ?>" id="entry-signature" class="form-control" />
                                    <?php if ($error_signature) { ?>
                                    <div class="text-danger"><?php echo $error_signature; ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="w1_return_url"><?php echo $entry_return_url; ?></label>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-link"></i></span>
                                        <input type="text" readonly="readonly" value="<?php echo $w1_return_url; ?>" id="w1_return_url" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab_dop_api">
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><?php echo $entry_ptenabled; ?></label>
                                <div class="col-sm-10">
                                    <div class="well well-sm list_payments">
                                        <?php foreach ($list_payments as $list) {?>
                                            <p><strong><?php echo $list->name;?></strong>
                                                <?php if (!empty($list->icon)){?>
                                                    <img src="<?php echo $icon_path . $list->icon?>">
                                                <?php }?>
                                            </p>
                                            <?php if (!empty($list->data)) {?>
                                                <?php foreach ($list->data as $sublist) {?>
                                                    <?php if (!empty($sublist->data)) {?>
                                                        <span>
                                                            <strong><?php echo $sublist->name?></strong>
                                                            <?php if (!empty($sublist->icon)){?>
                                                                <img src="<?php echo $icon_path . $sublist->icon?>">
                                                            <?php }?>
                                                        </span>
                                                        <?php foreach ($sublist->data as $key => $subsublist) {?>
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox" name="w1_ptenabled[]" value="<?php echo $subsublist->id?>" 
                                                                    <?php if (!empty($w1_ptenabled) && in_array($subsublist->id, $w1_ptenabled)) { ?> checked <?php }?>>
                                                                    <?php echo $subsublist->name?>
                                                                    <?php if (!empty($subsublist->icon)){?>
                                                                        <img src="<?php echo $icon_path . $subsublist->icon?>">
                                                                    <?php }?>
                                                                </label>
                                                            </div>
                                                        <?php }?>
                                                    <?php }
                                                    else {?>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="checkbox" name="w1_ptenabled[]"
                                                                <?php if (!empty($w1_ptenabled) && in_array($sublist->id, $w1_ptenabled)) { ?> checked <?php }?>>
                                                                <?php if (!empty($sublist->icon)){?>
                                                                    <img src="<?php echo $icon_path . $sublist->icon?>">
                                                                <?php }?>
                                                                <?php echo $sublist->name?>
                                                            </label>
                                                        </div>
                                                    <?php }?>
                                                <?php }?>
                                            <?php }?>
                                        <?php }?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><?php echo $entry_ptdisabled; ?></label>
                                <div class="col-sm-10">
                                    <div class="well well-sm list_payments">
                                        <?php foreach ($list_payments as $list) {?>
                                            <p><strong><?php echo $list->name;?></strong>
                                                <?php if (!empty($list->icon)){?>
                                                    <img src="<?php echo $icon_path . $list->icon?>">
                                                <?php }?>
                                            </p>
                                            <?php if (!empty($list->data)) {?>
                                                <?php foreach ($list->data as $sublist) {?>
                                                    <?php if (!empty($sublist->data)) {?>
                                                        <span>
                                                            <strong><?php echo $sublist->name?></strong>
                                                            <?php if (!empty($sublist->icon)){?>
                                                                <img src="<?php echo $icon_path . $sublist->icon?>">
                                                            <?php }?>
                                                        </span>
                                                        <?php foreach ($sublist->data as $key => $subsublist) {?>
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox" name="w1_ptdisabled[]" value="<?php echo $subsublist->id?>" 
                                                                    <?php if (!empty($w1_ptdisabled) && in_array($subsublist->id, $w1_ptdisabled)) { ?> checked <?php }?>>
                                                                    <?php echo $subsublist->name?>
                                                                    <?php if (!empty($subsublist->icon)){?>
                                                                        <img src="<?php echo $icon_path . $subsublist->icon?>">
                                                                    <?php }?>
                                                                </label>
                                                            </div>
                                                        <?php }?>
                                                    <?php }
                                                    else {?>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="checkbox" name="w1_ptdisabled[]"
                                                                <?php if (!empty($w1_ptdisabled) && in_array($sublist->id, $w1_ptdisabled)) { ?> checked <?php }?>>
                                                                <?php if (!empty($sublist->icon)){?>
                                                                    <img src="<?php echo $icon_path . $sublist->icon?>">
                                                                <?php }?>
                                                                <?php echo $sublist->name?>
                                                            </label>
                                                        </div>
                                                    <?php }?>
                                                <?php }?>
                                            <?php }?>
                                        <?php }?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab_order_status">
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-processed-status"><?php echo $entry_processed_status; ?></label>
                                <div class="col-sm-10">
                                    <select name="w1_order_status_processed_id" id="input-processed-status" class="form-control">
                                        <?php foreach ($order_statuses as $order_status) { ?>
                                            <?php if ($order_status['order_status_id'] == $w1_order_status_processed_id) { ?>
                                                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                                            <?php } else { ?>
                                                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-pending-status"><?php echo $entry_pending_status; ?></label>
                                <div class="col-sm-10">
                                    <select name="w1_order_status_pending_id" id="input-pending-status" class="form-control">
                                        <?php foreach ($order_statuses as $order_status) { ?>
                                            <?php if ($order_status['order_status_id'] == $w1_order_status_pending_id) { ?>
                                                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                                            <?php } else { ?>
                                                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-completed-status"><?php echo $entry_completed_status; ?></label>
                                <div class="col-sm-10">
                                    <select name="w1_order_status_completed_id" id="input-completed-status" class="form-control">
                                        <?php foreach ($order_statuses as $order_status) { ?>
                                            <?php if ($order_status['order_status_id'] == $w1_order_status_completed_id) { ?>
                                                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                                            <?php } else { ?>
                                                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                            <?php } ?>
                                            <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-failed-status"><?php echo $entry_failed_status; ?></label>
                                <div class="col-sm-10">
                                    <select name="w1_order_status_failed_id" id="input-failed-status" class="form-control">
                                        <?php foreach ($order_statuses as $order_status) { ?>
                                            <?php if ($order_status['order_status_id'] == $w1_order_status_failed_id) { ?>
                                                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                                            <?php } else { ?>
                                                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php echo $footer; ?> 