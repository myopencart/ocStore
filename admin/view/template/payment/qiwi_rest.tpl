<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-qiwi-rest" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">

    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-qiwi-rest" class="form-horizontal">

      <table width=100%>
	<tr><td width=10 valign=top>
	<a href="http://ishop.qiwi.com" onclick="return !window.open(this.href)" ><img  src="view/image/payment/qiwi_rest2.jpg" alt="QIWI Кошелек" title="QIWI Кошелек" style="border: 1px solid #EEEEEE;" /></a>
	</td>
	<td>

          <div class="form-group required">
            <label class="col-sm-2 control-label" for="qiwi_rest_ccy_select"><span data-toggle="tooltip" title="<?php echo $help_qiwi_rest_ccy_select; ?>"><?php echo $entry_qiwi_rest_ccy_select; ?></span></label>
            <div class="col-sm-10">
              <select name="qiwi_rest_ccy_select" id="qiwi_rest_ccy_select" class="form-control">
              <?php foreach ($currencies as $currency) { ?>
              <?php if ($currency['code'] == $qiwi_rest_ccy_select) { ?>
              <option value="<?php echo $currency['code']; ?>" selected="selected"><?php echo $currency['code']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $currency['code']; ?>"><?php echo $currency['code']; ?></option>
              <?php } ?>
              <?php } ?>
              </select>
            </div>
          </div>

          <div class="form-group required">
            <label class="col-sm-2 control-label" for="qiwi_rest_shop_id"><span data-toggle="tooltip" title="<?php echo $help_qiwi_rest_shop_id; ?>"><?php echo $entry_qiwi_rest_shop_id; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="qiwi_rest_shop_id" value="<?php echo $qiwi_rest_shop_id; ?>" placeholder="<?php echo $help_qiwi_rest_shop_id; ?>" id="qiwi_rest_shop_id" class="form-control" />
			<?php if ($error_qiwi_rest_shop_id) { ?>
              		<div class="text-danger"><?php echo $error_qiwi_rest_shop_id; ?></div>
              	<?php } ?>
            </div>
          </div>

          <div class="form-group required">
            <label class="col-sm-2 control-label" for="qiwi_rest_id"><span data-toggle="tooltip" title="<?php echo $help_qiwi_rest_id; ?>"><?php echo $entry_qiwi_rest_id; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="qiwi_rest_id" value="<?php echo $qiwi_rest_id; ?>" placeholder="<?php echo $help_qiwi_rest_id; ?>" id="qiwi_rest_id" class="form-control" />
			<?php if ($error_qiwi_rest_id) { ?>
              		<div class="text-danger"><?php echo $error_qiwi_rest_id; ?></div>
              	<?php } ?>
            </div>
          </div>

          <div class="form-group required">
            <label class="col-sm-2 control-label" for="qiwi_rest_password"><span data-toggle="tooltip" title="<?php echo $help_qiwi_rest_password; ?>"><?php echo $entry_qiwi_rest_password; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="qiwi_rest_password" value="<?php echo $qiwi_rest_password; ?>" placeholder="<?php echo $help_qiwi_rest_password; ?>" id="qiwi_rest_password" class="form-control" />
			<?php if ($error_qiwi_rest_password) { ?>
              		<div class="text-danger"><?php echo $error_qiwi_rest_password; ?></div>
              	<?php } ?>
            </div>
          </div>

          <div class="form-group required">
            <label class="col-sm-2 control-label" for="qiwi_rest_lifetime"><span data-toggle="tooltip" title="<?php echo $help_qiwi_rest_lifetime; ?>"><?php echo $entry_qiwi_rest_lifetime; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="qiwi_rest_lifetime" value="<?php echo $qiwi_rest_lifetime; ?>" placeholder="<?php echo $help_qiwi_rest_lifetime; ?>" id="qiwi_rest_lifetime" class="form-control" />
			<?php if ($error_qiwi_rest_lifetime) { ?>
              		<div class="text-danger"><?php echo $error_qiwi_rest_lifetime; ?></div>
              	<?php } ?>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="qiwi_rest_result_url"><span data-toggle="tooltip" title="<?php echo $help_qiwi_rest_result_url; ?>"><?php echo $entry_qiwi_rest_result_url; ?></span></label>
            <div class="col-sm-10">
		<span class="form-control"><b><?php echo $qiwi_rest_result_url; ?></b></span>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="qiwi_rest_order_status_progress_id"><span data-toggle="tooltip" title="<?php echo $help_qiwi_rest_order_status_progress_id; ?>"><?php echo $entry_qiwi_rest_order_status_progress_id; ?></span></label>
            <div class="col-sm-10">
              <select name="qiwi_rest_order_status_progress_id" id="qiwi_rest_order_status_progress_id" class="form-control">
              <?php foreach ($order_statuses as $order_status) { ?>
              <?php if ($order_status['order_status_id'] == $qiwi_rest_order_status_progress_id) { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
              <?php } ?>
              <?php } ?>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="qiwi_rest_order_status_id"><span data-toggle="tooltip" title="<?php echo $help_qiwi_rest_order_status_id; ?>"><?php echo $entry_qiwi_rest_order_status_id; ?></span></label>
            <div class="col-sm-10">
              <select name="qiwi_rest_order_status_id" id="qiwi_rest_order_status_id" class="form-control">
              <?php foreach ($order_statuses as $order_status) { ?>
              <?php if ($order_status['order_status_id'] == $qiwi_rest_order_status_id) { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
              <?php } ?>
              <?php } ?>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="qiwi_rest_order_status_cancel_id"><span data-toggle="tooltip" title="<?php echo $help_qiwi_rest_order_status_cancel_id; ?>"><?php echo $entry_qiwi_rest_order_status_cancel_id; ?></span></label>
            <div class="col-sm-10">
              <select name="qiwi_rest_order_status_cancel_id" id="qiwi_rest_order_status_cancel_id" class="form-control">
              <?php foreach ($order_statuses as $order_status) { ?>
              <?php if ($order_status['order_status_id'] == $qiwi_rest_order_status_cancel_id) { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
              <?php } ?>
              <?php } ?>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="qiwi_rest_geo_zone_id"><span data-toggle="tooltip" title="<?php echo $help_qiwi_rest_geo_zone_id; ?>"><?php echo $entry_qiwi_rest_geo_zone_id; ?></span></label>
            <div class="col-sm-10">
              <select name="qiwi_rest_geo_zone_id" id="qiwi_rest_geo_zone_id" class="form-control">
              <option value="0"><?php echo $text_all_zones; ?></option>
              <?php foreach ($geo_zones as $geo_zone) { ?>
              <?php if ($geo_zone['geo_zone_id'] == $qiwi_rest_geo_zone_id) { ?>
              <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
              <?php } ?>
              <?php } ?>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="qiwi_rest_total"><span data-toggle="tooltip" title="<?php echo $help_qiwi_rest_total; ?>"><?php echo $entry_qiwi_rest_total; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="qiwi_rest_total" value="<?php echo $qiwi_rest_total; ?>" placeholder="<?php echo $help_qiwi_rest_total; ?>" id="qiwi_rest_total" class="form-control" />
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="qiwi_rest_show_pay_now"><span data-toggle="tooltip" title="<?php echo $help_qiwi_rest_show_pay_now; ?>"><?php echo $entry_qiwi_rest_show_pay_now; ?></span></label>
            <div class="col-sm-10">
			<div class="checkbox">
				<label>
		                  <?php if (isset($qiwi_rest_show_pay_now)) { ?>
		                  <input type="checkbox" name="qiwi_rest_show_pay_now" id="qiwi_rest_show_pay_now" value="1" checked="checked" />
		                  <?php } else { ?>
		                  <input type="checkbox" name="qiwi_rest_show_pay_now" id="qiwi_rest_show_pay_now" value="1" />
		                  <?php } ?>
      		      </label>
			</div>
		</div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="qiwi_rest_mode_select"><span data-toggle="tooltip" title="<?php echo $help_qiwi_rest_mode_select; ?>"><?php echo $entry_qiwi_rest_mode_select; ?></span></label>
            <div class="col-sm-10">
              <select name="qiwi_rest_mode_select" id="qiwi_rest_mode_select" class="form-control">
              <?php foreach ($entry_qiwi_rest_modes as $mode) { ?>
              <?php if ($mode['code'] == $qiwi_rest_mode_select) { ?>
              <option value="<?php echo $mode['code']; ?>" selected="selected"><?php echo $mode['code_text']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $mode['code']; ?>"><?php echo $mode['code_text']; ?></option>
              <?php } ?>
              <?php } ?>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="qiwi_rest_mode_show_picture"><span data-toggle="tooltip" title="<?php echo $help_qiwi_rest_mode_show_picture; ?>"><?php echo $entry_qiwi_rest_mode_show_picture; ?></span></label>
            <div class="col-sm-10">
              <select name="qiwi_rest_mode_show_picture" id="qiwi_rest_mode_show_picture" class="form-control">
              <?php foreach ($entry_qiwi_rest_modes_show_picture as $mode) { ?>
              <?php if ($mode['code'] == $qiwi_rest_mode_show_picture) { ?>
              <option value="<?php echo $mode['code']; ?>" selected="selected"><?php echo $mode['code_text']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $mode['code']; ?>"><?php echo $mode['code_text']; ?></option>
              <?php } ?>
              <?php } ?>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="qiwi_rest_markup"><span data-toggle="tooltip" title="<?php echo $help_qiwi_rest_markup; ?>"><?php echo $entry_qiwi_rest_markup; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="qiwi_rest_markup" value="<?php echo $qiwi_rest_markup; ?>" placeholder="<?php echo $help_qiwi_rest_markup; ?>" id="qiwi_rest_markup" class="form-control" />
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="qiwi_rest_name"><span data-toggle="tooltip" title="<?php echo $help_qiwi_rest_name; ?>"><?php echo $entry_qiwi_rest_name; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="qiwi_rest_name" value="<?php echo $qiwi_rest_name; ?>" placeholder="<?php echo $help_qiwi_rest_name; ?>" id="qiwi_rest_name" class="form-control" />
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="qiwi_rest_group_desc"><span data-toggle="tooltip" title="<?php echo $help_qiwi_rest_groups_settings; ?>"><?php echo $entry_qiwi_rest_groups_settings; ?></span></label>
            <div class="col-sm-10">

		<table class="table table-bordered table-hover" style="margin-bottom: 0px;">

            <tr>
              <td style="color: #1e91cf; font-weight: bold;"><?php echo $entry_qiwi_rest_group_name; ?></td>
		  <td style="color: #1e91cf; font-weight: bold;"><?php echo $entry_qiwi_rest_group_markup; ?></td>
		  <td style="color: #1e91cf; font-weight: bold;"><?php echo $entry_qiwi_rest_group_desc; ?></td>
            </tr>

            <?php if ($customer_groups) { ?>
            <?php foreach ($customer_groups as $customer_group) { ?>
            <tr>
              <td class="left"><?php echo $customer_group['name']; ?></td>
		  <td><input type="text" name="qiwi_rest_group_markup[<?php echo $customer_group['customer_group_id']; ?>]" value="<?php if (isset($qiwi_rest_group_markup[$customer_group['customer_group_id']])) echo $qiwi_rest_group_markup[$customer_group['customer_group_id']];?>"></td>    
		  <td><input type="text" name="qiwi_rest_group_desc[<?php echo $customer_group['customer_group_id']; ?>]" value="<?php if (isset($qiwi_rest_group_desc[$customer_group['customer_group_id']])) echo $qiwi_rest_group_desc[$customer_group['customer_group_id']];?>"></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="3"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>

		</table>

            </div>
          </div>


          <div class="form-group">
            <label class="col-sm-2 control-label" for="qiwi_rest_status"><span data-toggle="tooltip" title="<?php echo $help_qiwi_rest_status; ?>"><?php echo $entry_qiwi_rest_status; ?></span></label>
            <div class="col-sm-10">
              <select name="qiwi_rest_status" id="qiwi_rest_status" class="form-control">
              <?php if ($qiwi_rest_status) { ?>
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
            <label class="col-sm-2 control-label" for="qiwi_rest_sort_order"><span data-toggle="tooltip" title="<?php echo $help_qiwi_rest_sort_order; ?>"><?php echo $entry_qiwi_rest_sort_order; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="qiwi_rest_sort_order" value="<?php echo $qiwi_rest_sort_order; ?>" placeholder="<?php echo $help_qiwi_rest_sort_order; ?>" id="qiwi_rest_sort_order" class="form-control" />
            </div>
          </div>

		
        </td></tr>
      </table>



    </form>
      </div>
    </div>
<br>
		<div style="text-align:center; color:#555555;">QIWI v<?php echo $qiwi_rest_version; ?></div>

  </div>
</div>
<?php echo $footer; ?> 