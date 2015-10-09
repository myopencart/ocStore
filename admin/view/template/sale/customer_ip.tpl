<div class="table-responsive">
  <table class="table table-bordered table-hover">
    <thead>
      <tr>
        <td class="text-left"><?php echo $column_ip; ?></td>
        <td class="text-right"><?php echo $column_total; ?></td>
        <td class="text-left"><?php echo $column_date_added; ?></td>
        <td class="text-right"><?php echo $column_action; ?></td>
      </tr>
    </thead>
    <tbody>
      <?php if ($ips) { ?>
      <?php foreach ($ips as $ip) { ?>
      <tr>
        <td class="text-left"><a href="http://www.geoiptool.com/en/?IP=<?php echo $ip['ip']; ?>" target="_blank"><?php echo $ip['ip']; ?></a></td>
        <td class="text-right"><a href="<?php echo $ip['filter_ip']; ?>" target="_blank"><?php echo $ip['total']; ?></a></td>
        <td class="text-left"><?php echo $ip['date_added']; ?></td>
        <td class="text-right"><?php if ($ip['ban_ip']) { ?>
          <button type="button" value="<?php echo $ip['ip']; ?>" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-danger btn-xs button-ban-remove"><i class="fa fa-minus-circle"></i> <?php echo $text_remove_ban_ip; ?></button>
          <?php } else { ?>
          <button type="button" value="<?php echo $ip['ip']; ?>" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-success btn-xs button-ban-add"><i class="fa fa-plus-circle"></i> <?php echo $text_add_ban_ip; ?></button>
          <?php } ?></td>
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
<div class="row">
  <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
  <div class="col-sm-6 text-right"><?php echo $results; ?></div>
</div>