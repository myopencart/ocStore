<?php if (count($languages) > 1) { ?>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
  <div id="language"><?php echo $text_language; ?><br />
    <?php foreach ($languages as $language) { ?>
		  <?php if($this->config->get('config_seo_url')) { ?>
			  <a href="<?php echo $language['redirect']?>"><img src="image/flags/<?php echo $language['image']; ?>" alt="<?php echo $language['name']; ?>"
																title="<?php echo $language['name']; ?>"/></a>
			  <?php } else { ?>
			  <img src="image/flags/<?php echo $language['image']; ?>" alt="<?php echo $language['name']; ?>" title="<?php echo $language['name']; ?>"
				   onclick="$('input[name=\'language_code\']').attr('value', '<?php echo $language['code']; ?>'); $(this).parent().parent().submit();"/>
			  <?php } ?>
		  <?php } ?>
    <input type="hidden" name="language_code" value="" />
    <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
  </div>
</form>
<?php } ?>
