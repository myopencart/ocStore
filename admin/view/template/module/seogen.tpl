<?php echo $header; ?>
<style type="text/css">
	.list tbody td {
		padding: 5px;
		vertical-align: top;
	}
	.list input[type="text"] {
		width: 430px;
	}
	.list a.button {
		float: right;
		margin-top: 5px;
		margin-right: 10px
	}
</style>

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
    <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
		<table class="list" style="width: 590px;">
			<tr id="categories">
				<td style="width: 120px;"><?php echo $text_categories; ?></td>
				<td>
					<?php echo $text_template; ?>&nbsp;<input type="text" name="seogen[categories_template]" value="<?php echo $seogen['categories_template']; ?>"><br/>
					<?php echo $text_available_tags . ' ' . $text_categories_tags; ?><br/>
					<a onclick="generate('#categories');" class="button"><?php echo $text_generate; ?></a>
				</td>
			</tr>
			<tr id="products">
				<td><?php echo $text_products ?></td>
				<td>
					<?php echo $text_template; ?>&nbsp;<input type="text" name="seogen[products_template]" value="<?php echo $seogen['products_template']; ?>"><br/>
					<?php echo $text_available_tags . ' ' . $text_products_tags; ?><br/>
					<a onclick="generate('#products');" class="button"><?php echo $text_generate; ?></a>
				</td>
			</tr>
			<tr id="manufacturers">
				<td><?php echo $text_manufacturers ?></td>
				<td>
					<?php echo $text_template; ?>&nbsp;<input type="text" name="seogen[manufacturers_template]" value="<?php echo $seogen['manufacturers_template']; ?>"><br/>
					<?php echo $text_available_tags . ' ' . $text_manufacturers_tags; ?><br/>
					<a onclick="generate('#manufacturers');" class="button"><?php echo $text_generate; ?></a>
				</td>
			</tr>
		</table>
    </form>
	  <a id="generate_url" style="display: none" href="<?php echo $generate?>"></a>
	  <script type="text/javascript">
		  function generate(selector) {
			  $(".success").remove();
			  $.post($('#generate_url').attr('href'), $(selector +' :input').serialize(), function(html) {
					  $(".breadcrumb").after('<div class="success">'+html+'</div>');
			 });
		  }
	  </script>
  </div>
</div>

<?php echo $footer; ?>