<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="button" class="btn btn-info" onclick="location = '<?php echo $clear; ?>'" data-toggle="tooltip" title="<?php echo $button_clear_cache; ?>"><i class="fa fa-eraser"></i>&nbsp;<?php echo $button_clear_cache; ?></button>
        <button type="button" id="insert" class="btn btn-success" data-toggle="tooltip" title="<?php echo $button_add; ?>"><i class="fa fa-plus-circle"></i>&nbsp;<?php echo $button_add; ?></button>
        <button type="button" id="delete" class="btn btn-danger" data-toggle="tooltip" title="<?php echo $button_delete; ?>"><i class="fa fa-trash"></i>&nbsp;<?php echo $button_delete; ?></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i>&nbsp;<?php echo $button_cancel; ?></a>
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
    <div class="panel-body">
      <form action="<?php echo $save; ?>" method="post" enctype="multipart/form-data" id="form-add">
        <div class="well">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-filter_query"><?php echo $column_filter_query; ?></label>
                <input type="text" name="filter_query" value="<?php echo $filter_query; ?>" placeholder="<?php echo $column_filter_query; ?>" id="input-filter_query" class="form-control" />
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-filter_keyword"><?php echo $column_filter_keyword; ?></label>
                <input type="text" name="filter_keyword" value="<?php echo $filter_keyword; ?>" placeholder="<?php echo $column_filter_keyword; ?>" id="input-filter_keyword" class="form-control" />
              </div>
              </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label"><?php echo $column_action; ?></label><br />
                <button type="button" id="button-filter" class="btn btn-primary"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
              </div>
            </div>
          </div>
        </div>
        <div id="add-url" class="well">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <input type="hidden" name="url_alias_id" value="">
                <label class="control-label" for="input-query"><?php echo $column_query; ?></label>
                <input type="text" name="query" value="" placeholder="<?php echo $column_query; ?>" id="input-query" class="form-control" />
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-keyword"><?php echo $column_keyword; ?></label>
                <input type="text" name="keyword" value="" placeholder="<?php echo $column_keyword; ?>" id="input-keyword" class="form-control" />
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label"><?php echo $column_action; ?></label><br />
                <button type="button" id="button-save" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>&nbsp;
                <button type="button" id="button-cancel" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-danger"><i class="fa fa-reply"></i></button>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
    <div class="panel panel-default">
      <div class="panel-body">
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-url-alias">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td class="text-left"><?php if ($sort == 'query') { ?>
                    <a href="<?php echo $sort_query; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_query; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_query; ?>"><?php echo $column_query; ?></a>
                  <?php } ?></td>
                  <td class="text-right"><?php if ($sort == 'keyword') { ?>
                    <a href="<?php echo $sort_keyword; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_keyword; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_keyword; ?>"><?php echo $column_keyword; ?></a>
                  <?php } ?></td>
                  <td class="text-right"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($url_aliases) { ?>
                <?php foreach ($url_aliases as $url_alias) { ?>
                <tr>
                  <td class="text-center"><?php if (in_array($url_alias['url_alias_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $url_alias['url_alias_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $url_alias['url_alias_id']; ?>" />
                  <?php } ?></td>
                  <td class="text-left query_<?php echo $url_alias['url_alias_id']; ?>"><?php echo $url_alias['query']; ?></td>
                  <td class="text-right keyword_<?php echo $url_alias['url_alias_id']; ?>"><?php echo $url_alias['keyword']; ?></td>
                  <td class="text-right">
                    <button type="button" onclick="edit(<?php echo $url_alias['url_alias_id']; ?>)" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></button>
                  </td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="4"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </form>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
    $('#add-url').hide();

    $('#button-save').on('click', function(){
      $('#form-add').submit();
    });

    function edit(url_alias_id) {
      $('#add-url').show();
      $('input[name="query"]').val($('.query_'+url_alias_id).text());
      $('input[name="keyword"]').val($('.keyword_'+url_alias_id).text());
      $('input[name="url_alias_id"]').val(url_alias_id);
      $('input[name="query"]').focus();
    }

    $('#button-cancel').on('click', function(){
      $('#add-url').hide();
      $('input[name="query"]').val('');
      $('input[name="keyword"]').val('');
    });

    $('#insert').on('click', function(){
      $('#add-url').show();
      $('input[name="query"]').val('');
      $('input[name="keyword"]').val('');
    });

    $('#delete').on('click', function() {
      if (!confirm('<?php echo $text_confirm; ?>')) {
        return false;
      } else {
        $('#form-url-alias').submit();
      }
    });

    $('#button-filter').on('click', function() {
      url = 'index.php?route=octeam_tools/seo_manager&token=<?php echo $token; ?>';
      
      var filter_query = $('input[name=\'filter_query\']').val();
      
      if (filter_query) {
        url += '&filter_query=' + encodeURIComponent(filter_query);
      }
      
      var filter_keyword = $('input[name=\'filter_keyword\']').val();
      
      if (filter_keyword) {
        url += '&filter_keyword=' + encodeURIComponent(filter_keyword);
      }
        
      location = url;
    });

    $('input[name=\'filter_query\'], input[name=\'filter_keyword\']').on('keydown', function(e) {
      if (e.keyCode == 13) {
        $('#button-filter').trigger('click');
      }
    });
  </script>
</div>
<?php echo $footer; ?>