<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">

        <div class="page-header">
            <div class="container-fluid">
                <div class="pull-right">
                    <button type="submit" form="form" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
                    <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
                <h1><img src="view/image/payment/oplata.png" style="height:25px; margin-top:-5px;" />   <?php echo $heading_title; ?></h1>
                <ul class="breadcrumb">
                    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    <div class="container-fluid">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
            </div>
        <div class="panel-body">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">

          <div class="form-group required">
              <label class="col-sm-2 control-label"><?php echo $entry_merchant; ?></label>
              <div class="col-sm-10">
          <input type="text" name="oplata_merchant" value="<?php echo $oplata_merchant; ?>" class="form-control" />
              <?php if ($error_merchant) { ?>
              <span class="error"><?php echo $error_merchant; ?></span>
              <?php } ?>
          </div>
          </div>

          <div class="form-group required">
            <label class="col-sm-2 control-label"><?php echo $entry_secretkey; ?></label>
              <div class="col-sm-10">
              <input type="text" name="oplata_secretkey" value="<?php echo $oplata_secretkey; ?>" class="form-control" />
              <?php if ($error_secretkey) { ?>
              <span class="error"><?php echo $error_secretkey; ?></span>
              <?php } ?>
              </div>
          </div>

          <div class="form-group">
              <label class="col-sm-2 control-label" ><?php echo $entry_currency; ?></label>
              <div class="col-sm-10">
                  <select name="oplata_currency"  class="form-control">
                      <?php foreach ($oplata_currencyc as $oplata_currenc) { ?>

                      <?php if ($oplata_currenc == $oplata_currency) { ?>
                      <option value="<?php echo $oplata_currenc; ?>" selected="selected"><?php echo $oplata_currenc; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $oplata_currenc; ?>"><?php echo $oplata_currenc; ?></option>
                      <?php } ?>

                      <?php } ?>
                  </select>
              </div>
          </div>

          <div class="form-group ">
              <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $entry_help_lang; ?>"> <?php echo $entry_language; ?></span></label>
              <div class="col-sm-10">
                <input type="text" name="oplata_language" value="<?php echo ($oplata_language == "") ? "RU" : $oplata_language; ?>"  class="form-control"/>
              </div>
          </div>

          <div class="form-group">
              <label class="col-sm-2 control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
              <div class="col-sm-10">
                  <select name="oplata_order_status_id" class="form-control">
                <?php 
                foreach ($order_statuses as $order_status) { 

                $st = ($order_status['order_status_id'] == $oplata_order_status_id) ? ' selected="selected" ' : ""; 
                ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" <?= $st ?> ><?php echo $order_status['name']; ?></option>
                <?php } ?>
              </select> </div>
          </div>




          <div class="form-group">
          <label class="col-sm-2 control-label" for="input-order-status"><?php echo $entry_order_process_status; ?></label>
          <div class="col-sm-10">
                <select name="oplata_order_process_status_id" class="form-control">
                        <?php
                foreach ($order_statuses as $order_status) {

                $st = ($order_status['order_status_id'] == $oplata_order_process_status_id) ? ' selected="selected" ' : "";
                ?>
                        <option value="<?php echo $order_status['order_status_id']; ?>" <?= $st ?> ><?php echo $order_status['name']; ?></option>
                        <?php } ?>
                    </select></div>
        </div>


          <div class="form-group">
              <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
              <div class="col-sm-10">
              <select name="oplata_status" class="form-control">
                <? $st0 = $st1 = ""; 
                 if ( $oplata_status == 0 ) $st0 = 'selected="selected"';
                  else $st1 = 'selected="selected"';
                ?>

                <option value="1" <?= $st1 ?> ><?php echo $text_enabled; ?></option>
                <option value="0" <?= $st0 ?> ><?php echo $text_disabled; ?></option>

              </select>
          </div>
        </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
                <div class="col-sm-10">
            <input type="text" name="oplata_sort_order" value="<?php echo $oplata_sort_order; ?>" size="100" class="form-control" />
                </div>
            </div>
		  <div class="form-group">
			  <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_styles; ?></label>
			  <div class="col-sm-10">
			    <textarea name="oplata_styles" rows="15" placeholder="Styles" placeholder="Chekout Styles" class="form-control"><?php echo isset($oplata_styles) ? $oplata_styles : ''; ?></textarea>
			  </div>
		  </div>
      </form>
    </div>
    </div>
</div>
<?php echo $footer; ?>