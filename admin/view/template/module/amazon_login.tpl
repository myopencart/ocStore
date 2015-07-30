<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
	<div class="container-fluid">
	  <div class="pull-right">
		<button type="submit" form="form-amazon-login" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-check-circle"></i></button>
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $heading_title; ?></h3>
      </div>
      <div class="panel-body">
		<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-amazon-login" class="form-horizontal">
		  <div class="form-group">
			<label class="col-sm-2 control-label" for="input-button-type"><?php echo $entry_button_type; ?></label>
			<div class="col-sm-10">
			  <select name="amazon_login_button_type" id="input-button-type" class="form-control">
				<?php if ($amazon_login_button_type == 'Login') { ?>
					<option value="LwA" ><?php echo $text_lwa_button; ?></option>
					<option value="Login" selected="selected"><?php echo $text_login_button; ?></option>
					<option value="A"><?php echo $text_a_button; ?></option>
				<?php } elseif ($amazon_login_button_type == 'A') { ?>
					<option value="LwA" ><?php echo $text_lwa_button; ?></option>
					<option value="Login" ><?php echo $text_login_button; ?></option>
					<option value="A" selected="selected"><?php echo $text_a_button; ?></option>
				<?php } else { ?>
					<option value="LwA" selected="selected"><?php echo $text_lwa_button; ?></option>
					<option value="Login" ><?php echo $text_login_button; ?></option>
					<option value="A"><?php echo $text_a_button; ?></option>
				<?php } ?>
			  </select>
			</div>
		  </div>
		  <div class="form-group">
			<label class="col-sm-2 control-label" for="input-button-colour"><?php echo $entry_button_colour; ?></label>
			<div class="col-sm-10">
			  <select name="amazon_login_button_colour" id="input-button-colour" class="form-control">
				<?php if ($amazon_login_button_colour == 'DarkGray') { ?>
					<option value="Gold" ><?php echo $text_gold_button; ?></option>
					<option value="DarkGray" selected="selected"><?php echo $text_darkgray_button; ?></option>
					<option value="LightGray"><?php echo $text_lightgray_button; ?></option>
				<?php } elseif ($amazon_login_button_colour == 'LightGray') { ?>
					<option value="Gold" ><?php echo $text_gold_button; ?></option>
					<option value="DarkGray"><?php echo $text_darkgray_button; ?></option>
					<option value="LightGray" selected="selected"><?php echo $text_lightgray_button; ?></option>
				<?php } else { ?>
					<option value="Gold" selected="selected"><?php echo $text_gold_button; ?></option>
					<option value="DarkGray"><?php echo $text_darkgray_button; ?></option>
					<option value="LightGray"><?php echo $text_lightgray_button; ?></option>
				<?php } ?>
			  </select>
			</div>
		  </div>
		  <div class="form-group">
			<label class="col-sm-2 control-label" for="input-button-size"><?php echo $entry_button_size; ?></label>
			<div class="col-sm-10">
			  <select name="amazon_login_button_size" id="input-button-size" class="form-control">
				<?php if ($amazon_login_button_size == 'small') { ?>
					<option value="small" selected="selected"><?php echo $text_small_button; ?></option>
					<option value="medium"><?php echo $text_medium_button; ?></option>
					<option value="large" selected="selected"><?php echo $text_large_button; ?></option>
					<option value="x-large"><?php echo $text_x_large_button; ?></option>
				<?php } elseif ($amazon_login_button_size == 'large') { ?>
					<option value="small" ><?php echo $text_small_button; ?></option>
					<option value="medium"><?php echo $text_medium_button; ?></option>
					<option value="large" selected="selected"><?php echo $text_large_button; ?></option>
					<option value="x-large"><?php echo $text_x_large_button; ?></option>
				<?php } elseif ($amazon_login_button_size == 'x-large') { ?>
					<option value="small"><?php echo $text_small_button; ?></option>
					<option value="medium"><?php echo $text_medium_button; ?></option>
					<option value="large"><?php echo $text_large_button; ?></option>
					<option value="x-large" selected="selected"><?php echo $text_x_large_button; ?></option>
				<?php } else { ?>
					<option value="small"><?php echo $text_small_button; ?></option>
					<option value="medium" selected="selected"><?php echo $text_medium_button; ?></option>
					<option value="large"><?php echo $text_large_button; ?></option>
					<option value="x-large"><?php echo $text_x_large_button; ?></option>
				<?php } ?>
			  </select>
			</div>
		  </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="amazon_login_status" id="input-status" class="form-control">
				<?php if ($amazon_login_status) { ?>
					<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
					<option value="0"><?php echo $text_disabled; ?></option>
				<?php } else { ?>
					<option value="1"><?php echo $text_enabled; ?></option>
					<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
				<?php } ?>
              </select>
            </div>
          </div>
		</form>
	  </div>
	</div>
  </div>
</div>
<?php echo $footer; ?>