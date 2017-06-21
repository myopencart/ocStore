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
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><i class="fa fa-power-off"></i> <?php echo $tab_general; ?></a></li>
            <li><a href="#tab-log" data-toggle="tab"><i class="fa fa-eye"></i> <?php echo $tab_log; ?></a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-general">

              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-merch_r"><?php echo $entry_merch_r; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="webmoney_wmv_merch_r" value="<?php echo $webmoney_wmv_merch_r; ?>"
                         placeholder="<?php echo $entry_merch_r; ?>" id="input-merch_r" class="form-control" />
                  <span class="help-block"><?php echo $help_merch_r; ?></span>
                  <?php if ($error_merch_r) { ?>
                  <div class="text-danger"><?php echo $error_merch_r; ?></div>
                  <?php } ?>
                </div>
              </div>

              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-secret_key"><?php echo $entry_secret_key; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="webmoney_wmv_secret_key" value="<?php echo $webmoney_wmv_secret_key; ?>"
                         placeholder="<?php echo $entry_secret_key; ?>" id="input-secret_key" class="form-control" />
                  <?php if ($error_secret_key) { ?>
                  <div class="text-danger"><?php echo $error_secret_key; ?></div>
                  <?php } ?>
                </div>
              </div>

              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-secret_key_x20"><?php echo $entry_secret_key_x20; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="webmoney_wmv_secret_key_x20" value="<?php echo $webmoney_wmv_secret_key_x20; ?>"
                         placeholder="<?php echo $entry_secret_key_x20; ?>" id="input-secret_key_x20" class="form-control" />
                  <?php if ($error_secret_key_x20) { ?>
                  <div class="text-danger"><?php echo $error_secret_key_x20; ?></div>
                  <?php } ?>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_result_url; ?></label>
                <div class="col-sm-10">
                  <span class="form-control" style="height:auto;"><?php echo $webmoney_wmv_result_url; ?></span>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_success_url; ?></label>
                <div class="col-sm-10">
                  <span class="form-control" style="height:auto;"><?php echo $webmoney_wmv_success_url; ?></span>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_fail_url; ?></label>
                <div class="col-sm-10">
                  <span class="form-control" style="height:auto;"><?php echo $webmoney_wmv_fail_url; ?></span>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-order_confirm_status"><?php echo $entry_order_confirm_status; ?></label>
                <div class="col-sm-10">
                  <select name="webmoney_wmv_order_confirm_status_id" id="input-order_confirm_status" class="form-control">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if (!isset($order_status['order_status_id'])) continue; ?>
                    <?php if ($order_status['order_status_id'] == $webmoney_wmv_order_confirm_status_id) { ?>
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
                <label class="col-sm-2 control-label" for="input-order_status"><?php echo $entry_order_status; ?></label>
                <div class="col-sm-10">
                  <select name="webmoney_wmv_order_status_id" id="input-order_status" class="form-control">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if (!isset($order_status['order_status_id'])) continue; ?>
                    <?php if ($order_status['order_status_id'] == $webmoney_wmv_order_status_id) { ?>
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
                <label class="col-sm-2 control-label" for="input-order_fail_status"><?php echo $entry_order_fail_status; ?></label>
                <div class="col-sm-10">
                  <select name="webmoney_wmv_order_fail_status_id" id="input-order_fail_status" class="form-control">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if (!isset($order_status['order_status_id'])) continue; ?>
                    <?php if ($order_status['order_status_id'] == $webmoney_wmv_order_fail_status_id) { ?>
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
                <label class="col-sm-2 control-label" for="input-hide_mode"><?php echo $entry_hide_mode; ?></label>
                <div class="col-sm-10">
                  <select name="webmoney_wmv_hide_mode" id="input-hide_mode" class="form-control">
                    <?php if ($webmoney_wmv_hide_mode) { ?>
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
                <label class="col-sm-2 control-label" for="input-minimal_order"><?php echo $entry_minimal_order; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="webmoney_wmv_minimal_order" value="<?php echo $webmoney_wmv_minimal_order; ?>"
                         placeholder="<?php echo $entry_minimal_order; ?>" id="input-minimal_order" class="form-control" />
                  <span class="help-block"><?php echo $help_minimal_order; ?></span>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-maximal_order"><?php echo $entry_maximal_order; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="webmoney_wmv_maximal_order" value="<?php echo $webmoney_wmv_maximal_order; ?>"
                         placeholder="<?php echo $entry_maximal_order; ?>" id="input-maximal_order" class="form-control" />
                  <span class="help-block"><?php echo $help_maximal_order; ?></span>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-geo_zone"><?php echo $entry_geo_zone; ?></label>
                <div class="col-sm-10">
                  <select name="webmoney_wmv_geo_zone_id" id="input-geo_zone" class="form-control">
                    <option value="0"><?php echo $text_all_zones; ?></option>
                    <?php foreach ($geo_zones as $geo_zone) { ?>
                    <?php if ($geo_zone['geo_zone_id'] == $webmoney_wmv_geo_zone_id) { ?>
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
                  <select name="webmoney_wmv_status" id="input-status" class="form-control">
                    <?php if ($webmoney_wmv_status) { ?>
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
                  <input type="text" name="webmoney_wmv_sort_order" value="<?php echo $webmoney_wmv_sort_order; ?>"
                         placeholder="<?php echo $entry_sort_order; ?>" id="input-sort_order" class="form-control" />
                </div>
              </div>

            </div><!-- </div id="tab-general"> -->
            <div class="tab-pane" id="tab-log">
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-log"><?php echo $entry_log; ?></label>
                <div class="col-sm-8">
                  <input type="hidden" name="webmoney_wmv_log_filename" value="<?php echo $log_filename ?>" />
                  <select name="webmoney_wmv_log" id="input-log" class="form-control">
                    <?php foreach ($logs as $key => $value) { ?>
                    <?php if ($key == $webmoney_wmv_log) { ?>
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
                  <a data-toggle="tooltip" title="<?php echo $button_clear; ?>" class="btn btn-danger" id="button-clear"><i class="fa fa-eraser"></i> <?php echo $button_clear; ?></a>
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
          </div><!-- </div class="tab-content"> -->
        </form>
      </div><!-- </div class="panel-body"> -->
    </div><!-- </div class="panel panel-default"> -->
  </div><!-- </div class="container-fluid"> -->
</div><!-- </div id="content"> -->
<script type="text/javascript"><!--
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
//--></script>
