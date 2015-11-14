<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
<div class="page-header">
    <div class="container-fluid">
        <div class="pull-right">
            <button type="submit" form="form-by-total" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
    <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-by-total" class="form-horizontal">
            <div class="row">
                <div class="col-sm-2">
                    <ul class="nav nav-pills nav-stacked">
                        <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
                        <?php foreach ($geo_zones as $geo_zone) { ?>
                        <li><a href="#tab-geo-zone<?php echo $geo_zone['geo_zone_id']; ?>" data-toggle="tab"><?php echo $geo_zone['name']; ?></a></li>
                        <?php } ?>
                    </ul>
                </div>
                <div class="col-sm-10">
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab-general">
                            <table class="table table-bordered">
                                <colgroup>
                                    <col span="1" style="width: 25%;">
                                    <col span="1" style="width: 75%;">
                                </colgroup>
                                <tr>
                                    <td><?php echo $entry_tax_class; ?></td>
                                    <td><select name="by_total_tax_class_id">
                                            <option value="0"><?php echo $text_none; ?></option>
                                            <?php foreach ($tax_classes as $tax_class) { ?>
                                            <?php if ($tax_class['tax_class_id'] == $by_total_tax_class_id) { ?>
                                            <option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
                                            <?php } else { ?>
                                            <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
                                            <?php } ?>
                                            <?php } ?>
                                        </select></td>
                                </tr>
                                <tr>
                                    <td><?php echo $entry_status; ?></td>
                                    <td><select name="by_total_status">
                                            <?php if ($by_total_status) { ?>
                                            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                            <option value="0"><?php echo $text_disabled; ?></option>
                                            <?php } else { ?>
                                            <option value="1"><?php echo $text_enabled; ?></option>
                                            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                            <?php } ?>
                                        </select></td>
                                </tr>
                                <tr>
                                    <td><?php echo $entry_sort_order; ?></td>
                                    <td><input type="text" name="by_total_sort_order" value="<?php echo $by_total_sort_order; ?>" size="1" /></td>
                                </tr>
                            </table>
                        </div>
                        <?php foreach ($geo_zones as $geo_zone) { ?>
                        <div class="tab-pane" id="tab-geo-zone<?php echo $geo_zone['geo_zone_id']; ?>">
                            <table class="table table-bordered">
                                <colgroup>
                                    <col span="1" style="width: 25%;">
                                    <col span="1" style="width: 75%;">
                                </colgroup>
                                <tr>
                                    <td><?php echo $entry_rate; ?></td>
                                    <td><textarea name="by_total_<?php echo $geo_zone['geo_zone_id']; ?>_rate" cols="60" rows="5" style="width: 100%;"><?php echo ${'by_total_' . $geo_zone['geo_zone_id'] . '_rate'}; ?></textarea></td>
                                </tr>
                                <tr>
                                    <td><?php echo $entry_status; ?></td>
                                    <td><select name="by_total_<?php echo $geo_zone['geo_zone_id']; ?>_status">
                                            <?php if (${'by_total_' . $geo_zone['geo_zone_id'] . '_status'}) { ?>
                                            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                            <option value="0"><?php echo $text_disabled; ?></option>
                                            <?php } else { ?>
                                            <option value="1"><?php echo $text_enabled; ?></option>
                                            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                            <?php } ?>
                                        </select></td>

                                </tr>
                            </table>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
</div>
</div>
<?php echo $footer; ?>