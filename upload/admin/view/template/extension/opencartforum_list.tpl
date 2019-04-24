<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"></div>
      <h1><?php echo $heading_title; ?></h1>
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
        <h3 class="panel-title"><i class="fa fa-puzzle-piece"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="input-group" id="extension-filter">
            <input type="text" name="filter_search" value="<?php echo $filter_search; ?>" placeholder="<?php echo $text_search; ?>" class="form-control" />
            <div class="input-group-btn"><?php foreach ($categories as $category) { ?>
              <?php if ($category['value'] == $filter_category) { ?>
              <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><?php echo $text_category; ?> (<?php echo $category['text']; ?>) <span class="caret"></span></button>
              <?php } ?>
              <?php } ?>
              <ul class="dropdown-menu">
                <?php foreach ($categories as $category) { ?>
                <li><a href="<?php echo $category['href']; ?>"><?php echo $category['text']; ?></a></li>
                <?php } ?>
              </ul>
              <input type="hidden" name="filter_category_id" value="<?php echo $filter_category_id; ?>" class="form-control" />
              <input type="hidden" name="filter_download_id" value="<?php echo $filter_download_id; ?>" class="form-control" />
              <input type="hidden" name="filter_rating" value="<?php echo $filter_rating; ?>" class="form-control" />
              <input type="hidden" name="filter_license" value="<?php echo $filter_license; ?>" class="form-control" />
              <input type="hidden" name="filter_partner" value="<?php echo $filter_partner; ?>" class="form-control" />
              <input type="hidden" name="sort" value="<?php echo $sort; ?>" class="form-control" />
              <button type="button" id="button-filter" class="btn btn-primary"><i class="fa fa-filter"></i></button>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-9 col-xs-7">
            <div class="btn-group"><?php foreach ($licenses as $license) { ?>
              <?php if ($license['value'] == $filter_license) { ?><a href="<?php echo $license['href']; ?>" class="btn btn-default active"><?php echo $license['text']; ?></a><?php } else { ?><a href="<?php echo $license['href']; ?>" class="btn btn-default"><?php echo $license['text']; ?></a><?php } ?>
              <?php } ?></div>
          </div>
          <div class="col-sm-3 col-xs-5">
            <div class="input-group pull-right">
              <div class="input-group-addon"><i class="fa fa-sort-amount-asc"></i></div>
              <select onchange="location = this.value;" class="form-control">
                  <?php foreach ($sorts as $sort) { ?>
                  <?php if ($sorts['value'] == $sort) { ?>
                <option value="<?php echo $sort['href']; ?>" selected="selected"><?php echo $sort['text']; ?></option>
                 <?php } else { ?>
                <option value="<?php echo $sort['href']; ?>"><?php echo $sort['text']; ?></option>
                  <?php } ?>
                  <?php } ?>
              </select>
            </div>
          </div>
        </div>
        <br />
        <div id="extension-list"><?php if ($promotions) { ?>
          <h3>Featured</h3>
          <div class="row"><?php foreach ($promotions as $extension) { ?>
            <?php if ($extension) { ?>
            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
              <section>
                <div class="extension-preview"><a href="<?php echo $extension['href']; ?>">
                  <div class="extension-description"></div>
                  <img src="<?php echo $extension['image']; ?>" alt="<?php echo $extension['name']; ?>" title="<?php echo $extension['name']; ?>" class="img-responsive" /></a></div>
                <div class="extension-name">
                  <h4><a href="<?php echo $extension['href']; ?>"><?php echo $extension['name']; ?></a></h4>
                  <?php echo $extension['price']; ?></div>
                <div class="extension-rate">
                  <div class="row">
                    <div class="col-xs-6"><?php for ($i = 1; $i <= 5; $i++) { ?>
                      <?php if ($extension['rating'] >= $i) { ?><i class="fa fa-star"></i><?php } else { ?><i class="fa fa-star-o"></i><?php } ?>
                      <?php } ?></div>
                    <div class="col-xs-6">
                      <div class="text-right"><?php echo $extension['rating_total']; ?> <?php echo $text_reviews; ?></div>
                    </div>
                  </div>
                </div>
              </section>
            </div>
            <?php } ?>
            <?php } ?></div>
          <hr />
          <?php } ?>
          
          <?php if ($extensions) { ?>
          <div class="row"> <?php foreach ($extensions as $extension) { ?>
            
            <?php if ($extension) { ?>
            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
              <section>
                <div class="extension-preview"><a href="<?php echo $extension['href']; ?>">
                  <div class="extension-description"></div>
                  <img src="<?php echo $extension['image']; ?>" alt="<?php echo $extension['name']; ?>" title="<?php echo $extension['name']; ?>" class="img-responsive" /></a></div>
                <div class="extension-name">
                  <h4><a href="<?php echo $extension['href']; ?>"><?php echo $extension['name']; ?></a></h4>
                  <?php echo $extension['price']; ?></div>
                <div class="extension-rate">
                  <div class="row">
                    <div class="col-xs-6"><?php for ($i = 1; $i <= 5; $i++) { ?>
                      <?php if ($extension['rating'] >= $i) { ?><i class="fa fa-star"></i><?php } else { ?><i class="fa fa-star-o"></i><?php } ?>
                      <?php } ?></div>
                    <div class="col-xs-6">
                      <div class="text-right"><?php echo $extension['rating_total']; ?> <?php echo $text_reviews; ?></div>
                    </div>
                  </div>
                </div>
              </section>
            </div>
            <?php } ?>
            <?php } ?> </div>
          <?php } else { ?>
          <p class="text-center"><?php echo $text_no_results; ?></p>
          <?php } ?> </div>
        <div class="row">
          <div class="col-sm-12 text-center"><?php echo $pagination; ?></div>
        </div>
      </div>
    </div>
  </div>
<script type="text/javascript"><!--
$('#button-filter').on('click', function(e) {
	var url = 'index.php?route=extension/opencartforum&token=<?php echo $token; ?>';

	var input = $('#extension-filter :input');

	for (i = 0; i < input.length; i++) {
		if ($(input[i]).val() != '' && $(input[i]).val() != null) {
			url += '&' + $(input[i]).attr('name') + '=' + $(input[i]).val()
		}
	}

	location = url;
});

$('input[name="filter_search"]').keydown(function(e) {
	if (e.keyCode == 13) {
		e.preventDefault();

		$('#button-filter').trigger('click');
	}
});
//--></script></div>
<?php echo $footer; ?> 