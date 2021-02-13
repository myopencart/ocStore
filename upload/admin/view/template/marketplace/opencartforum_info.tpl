<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div id="marketplace-extension-info" class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-puzzle-piece"></i> <?php echo $name; ?></h3>
      </div>
      <div class="panel-body">
        <div class="row extension-info">
          <div class="col-sm-8"><?php if ($banner) { ?>
            <div id="banner" class="text-center thumbnail"><img src="<?php echo $banner; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" class="img-responsive" /></div>
            <?php } ?>
            <div class="row thumbnails"><?php foreach ($images as $image) { ?>
              <div class="col-xs-4 col-sm-2"><a href="<?php echo $image['popup']; ?>" class="thumbnail"><img src="<?php echo $image['thumb']; ?>" class="img-responsive" /></a></div>
              <?php } ?> </div>
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab-description" data-toggle="tab"><?php echo $tab_general; ?></a></li>
              <li><a href="#tab-changelog" data-toggle="tab"><?php echo $tab_changelog; ?></a></li>
            </ul>
            <div class="tab-content">
              <div id="tab-description" class="tab-pane active"><?php echo $description; ?></div>
              <div id="tab-changelog" class="tab-pane"><?php echo $changelog; ?></div>
            </div>
          </div>
          <div class="col-sm-4">
            <div id="buy" class="well"><?php if ($license == '1' and !$purchased) { ?>
              <a target="_blank" href="<?php echo $extension_url; ?>?utm_source=ocstore23_marketplace" id="button-buy" class="btn btn-success btn-lg btn-block"><?php echo $button_purchase; ?></a>
              <?php } ?>
              <div id="price" class="row">
                <div class="col-xs-5"><strong><?php echo $text_price; ?></strong></div>
                <div class="col-xs-7 text-right"><?php if ($license) { ?>
                  <?php echo $price; ?>
                  <?php } else { ?>
                  <?php echo $text_free; ?>
                  <?php } ?></div>
              </div>
              <hr>
              <ul class="list-check">
                  <?php foreach ($cfields as $field) { ?>
                    <li><?php echo $field['name']; ?>: <?php echo $field['value']; ?></li>
                  <?php } ?>
              </ul>
              <hr>
              <div class="row">
                <div class="col-xs-5"><strong><?php echo $text_rating; ?></strong></div>
                <div class="col-xs-7 text-right"><?php for ($i = 1; $i <= 5; $i++) { ?>
                  <?php if ($rating >= $i) { ?><i class="fa fa-star"></i><?php } else { ?><i class="fa fa-star-o"></i><?php } ?>
                  <?php } ?> (<?php echo $rating_total; ?>)</div>
              </div>
              <hr>
              <div class="row">
                <div class="col-xs-5"><strong><?php echo $text_date_modified; ?></strong></div>
                <div class="col-xs-7 text-right"><?php echo $date_modified; ?></div>
              </div>
              <hr>
              <div class="row">
                <div class="col-xs-5"><strong><?php echo $text_date_added; ?></strong></div>
                <div class="col-xs-7 text-right"><?php echo $date_added; ?></div>
              </div>
            </div>
            <div id="sales" class="well"><i class="opencart-icon-cart-mini"></i> <strong><?php echo $sales; ?></strong> <?php echo $text_sales; ?></div>
            <div id="sales" class="well"><i class="opencart-icon-cart-mini"></i> <strong><?php echo $downloaded; ?></strong> <?php echo $text_downloaded; ?></div>
            <div class="well">
              <div class="media">
                <div class="media-left media-middle"><img src="<?php echo $member_image; ?>" alt="<?php echo $member_username; ?>" title="<?php echo $member_username; ?>" class="media-object img-circle"></div>
                <div class="media-body"> <span><a target="_blank" href="<?php echo $member_url; ?>"><?php echo $member_username; ?></a></span><br>
                  <small><?php echo $text_member_since; ?> <?php echo $member_date_added; ?></small></div>
              </div>
              <br />
              <a href="<?php echo $filter_member; ?>" class="btn btn-primary btn-lg btn-block"><?php echo $button_view_all; ?></a> <a href="https://opencartforum.com/topic/<?php echo $topicid; ?>-<?php echo $topicseoname; ?>" target="_blank" class="btn btn-ghost-dark btn-lg btn-block"><?php echo $button_get_support; ?></a></div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
function next(url, element, i) {
	i = i + 1;

	$.ajax({
		url: url,
		dataType: 'json',
		success: function(json) {
			$('#progress-bar').css('width', (i * 20) + '%');

			if (json['error']) {
				$('#progress-bar').addClass('progress-bar-danger');
				$('#progress-text').html('<div class="text-danger">' + json['error'] + '</div>');
			}
			
			if (json['success']) {
				$('#progress-bar').addClass('progress-bar-success');
				$('#progress-text').html('<span class="text-success">' + json['success'] + '</span>');
			}
			
			if (json['text']) {
				$('#progress-text').html(json['text']);
			}

			if (json['next']) {
				next(json['next'], element, i);
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
}
//--></script>
  <script type="text/javascript"><!--
    $(document).ready(function() {
      $('.thumbnails').magnificPopup({
        type:'image',
        delegate: 'a',
        gallery: {
          enabled:true
        }
      });
    });
    //--></script>
</div>
<?php echo $footer; ?>
