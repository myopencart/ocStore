<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/product.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="location = '<?php echo $insert; ?>'" class="button"><?php echo $button_insert; ?></a><a onclick="$('#form').attr('action', '<?php echo $copy; ?>'); $('#form').submit();" class="button"><?php echo $button_copy; ?></a><a onclick="$('form').submit();" class="button"><?php echo $button_delete; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
	<input hidden id="page" value="<?php echo $page ?>">
	<input hidden id="sort" value="<?php echo $sort ?>">
	<input hidden id="order" value="<?php echo $order ?>">
        <table class="list">
          <thead>
            <tr id="head">
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="center"><?php echo $column_image; ?></td>
              <td class="left">                
                <?php echo $column_category; ?>
              </td>
              <td class="left">                
                <?php echo $column_manufacturer; ?>
              </td>
              <td class="left"><?php if ($sort == 'pd.name') { ?>
                <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'p.model') { ?>
                <a href="<?php echo $sort_model; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_model; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_model; ?>"><?php echo $column_model; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'p.price') { ?>
                <a href="<?php echo $sort_price; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_price; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_price; ?>"><?php echo $column_price; ?></a>
                <?php } ?></td>
              <td class="right"><?php if ($sort == 'p.quantity') { ?>
                <a href="<?php echo $sort_quantity; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_quantity; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_quantity; ?>"><?php echo $column_quantity; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'p.status') { ?>
                <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                <?php } ?></td>
              <td class="right"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          <tbody>
            <tr class="filter">
              <td></td>
              <td></td>
              <td><select name="filter_category_id" style="width: 100px;">
                  <option value="*"></option>
		  <option value="null">-</option>
		  <?php foreach($categories as $category) { ?>
			  <option value="<?php echo $category['category_id'] ?>" <?php if($filter_category_id == $category['category_id']) echo 'selected="selected"'; ?>><?php echo $category['name'] ?></option>
		  <?php }?>
                </select></td>
              <td><select name="filter_manufacturer_id" style="width: 100px;">
                  <option value="*"></option>
                  <option value="null">-</option>
		  <?php foreach($manufacturers as $manufacturer) { ?>
			  <option value="<?php echo $manufacturer['manufacturer_id'] ?>"<?php if($filter_manufacturer_id == $manufacturer['manufacturer_id']) echo 'selected="selected"'; ?>><?php echo $manufacturer['name'] ?></option>
		  <?php }?>

                </select></td>

              
              <td><input type="text" name="filter_name" value="<?php echo $filter_name; ?>" /></td>
              <td><input type="text" name="filter_model" value="<?php echo $filter_model; ?>" /></td>
              <td align="left"><input type="text" name="filter_price" value="<?php echo $filter_price; ?>" size="8"/></td>
              <td align="right"><input type="text" name="filter_quantity" value="<?php echo $filter_quantity; ?>" size="8" style="text-align: right;" /></td>
              <td><select name="filter_status">
                  <option value="*"></option>
                  <?php if ($filter_status) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <?php } ?>
                  <?php if (!is_null($filter_status) && !$filter_status) { ?>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
              <td align="right"><a onclick="clear_filter();" class="button"><?php echo $button_clear; ?></a></td>
            </tr>
            <?php if ($products) { ?>
            <?php foreach ($products as $product) { ?>
            <tr>
              <td style="text-align: center;"><?php if ($product['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" />
                <?php } ?></td>
              <td class="center"><img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" style="padding: 1px; border: 1px solid #DDDDDD;" /></td>

              <td class="left"><?php foreach ($product['category'] as $cat) echo $cat['name'] . '<br />'; ?></td>
              <td class="left"><?php echo $product['manufacturer']; ?></td>
              <td class="left"><?php echo $product['name']; ?></td>
              <td class="left"><?php echo $product['model']; ?></td>
              <td class="left"><?php if ($product['special']) { ?>
                <span style="text-decoration: line-through;"><?php echo $product['price']; ?></span><br/>
                <span style="color: #b00;"><?php echo $product['special']; ?></span>
                <?php } else { ?>
                <?php echo $product['price']; ?>
                <?php } ?></td>
              <td class="right"><?php if ($product['quantity'] <= 0) { ?>
                <span style="color: #FF0000;"><?php echo $product['quantity']; ?></span>
                <?php } elseif ($product['quantity'] <= 5) { ?>
                <span style="color: #FFA500;"><?php echo $product['quantity']; ?></span>
                <?php } else { ?>
                <span style="color: #008000;"><?php echo $product['quantity']; ?></span>
                <?php } ?></td>
              <td class="left"><?php echo $product['status']; ?></td>
              <td class="right"><?php foreach ($product['action'] as $action) { ?>
                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                <?php } ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="10"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </form>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>
<script id="productTemplate" type="text/x-jquery-tmpl">
<tr>
              <td style="text-align: center;">
                <input type="checkbox" name="selected[]" value="${product_id}" />
              </td>
              <td class="center"><img src="${image}" alt="${name}" style="padding: 1px; border: 1px solid #DDDDDD;" /></td>
	      <td class="left">{{each(i, cat) category}}${cat['name']}<br/>{{/each}}</td>
	      <td class="left">${manufacturer}</td>
              <td class="left">${name}</td>
              <td class="left">${model}</td>
              <td class="left">{{if special}}
                <span style="text-decoration: line-through;">${price}</span><br/>
                <span style="color: #b00;">${special}</span>
                {{else}}
                ${price}
                {{/if}}
	      </td>
              <td class="right">
		      {{if quantity <= 5}}
			      {{if quantity <= 0}}
				      <span style="color: #FF0000;">${quantity}</span>
			      {{else}}
				      <span style="color: #FFA500;">${quantity}</span>
			      {{/if}}
		      {{else}}
			      <span style="color: #008000;">${quantity}</span>
		      {{/if}}
              </td>
              <td class="left">${status}</td>
              <td class="right">
		{{each action}}
			[ <a href="${href}">${text}</a> ]
                {{/each}}
	      </td>
            </tr>
</script>
<script type="text/javascript" src="view/javascript/jquery/jquery.tmpl.min.js"></script>
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=catalog/product/filter&token=<?php echo $token; ?>';

	url += '&page=' + $('#page').val();

	if ($('#sort').val()) {
		url += '&sort=' + $('#sort').val();
	}
	if ($('#order').val()) {
		url += '&order=' + $('#order').val();
	}
	
	var filter_name = $('input[name=\'filter_name\']').attr('value');
	
	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}
	
	var filter_model = $('input[name=\'filter_model\']').attr('value');
	
	if (filter_model) {
		url += '&filter_model=' + encodeURIComponent(filter_model);
	}
	
	var filter_price = $('input[name=\'filter_price\']').attr('value');
	
	if (filter_price) {
		url += '&filter_price=' + encodeURIComponent(filter_price);
	}
	
	var filter_quantity = $('input[name=\'filter_quantity\']').attr('value');
	
	if (filter_quantity) {
		url += '&filter_quantity=' + encodeURIComponent(filter_quantity);
	}
	
	var filter_status = $('select[name=\'filter_status\']').attr('value');
	
	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}	

	var category_id = $('select[name=\'filter_category_id\']').attr('value');
	
	if (category_id != '*') {
		url += '&filter_category_id=' + encodeURIComponent(category_id);
	}	

	var manufacturer_id = $('select[name=\'filter_manufacturer_id\']').attr('value');
	
	if (manufacturer_id != '*') {
		url += '&filter_manufacturer_id=' + encodeURIComponent(manufacturer_id);
	}	

	$.ajax({
		url: url,
		dataType: 'json',
		success : function(json) {
				  $('table.list tr:gt(1)').empty();
				  $("#productTemplate").tmpl(json.products).appendTo("table.list");
				  $('.pagination').html(json.pagination);
			  }
	});
}
//--></script> 
<script type="text/javascript"><!--

function gsUV(e, t, v) {
    var n = String(e).split("?");
    var r = "";
    if (n[1]) {
        var i = n[1].split("&");
        for (var s = 0; s <= i.length; s++) {
            if (i[s]) {
                var o = i[s].split("=");
                if (o[0] && o[0] == t) {
                    r = o[1];
                    if (v != undefined) {
                        i[s] = o[0] +'=' + v;
                    }
                    break;
                }
            }
        }
    }
    if (v != undefined) {
        return n[0] +'?'+ i.join('&');
    }
    return r
}
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		$('#page').val(1);
		filter();
	}
});
$('#form input').bind("input", function() {
	if ($(this).val()=='') {
		$('#page').val(1);
		filter();
	}
});

$('#form select').bind("change", function() {
	$('#page').val(1);
	filter();
});

$('.pagination .links a').live("click", function() {
	var page = gsUV($(this).attr('href'), 'page');
	$('#page').val(page);
	filter();
	return false;
});

$('#head a').live("click", function() {

	var sort = gsUV($(this).attr('href'), 'sort');
	$('#sort').val(sort);
	var order = gsUV($(this).attr('href'), 'order');
	$('#order').val(order);
	$(this).attr('href', gsUV($(this).attr('href'), 'order', order=='DESC'?'ASC':'DESC'));
	$('#head a').removeAttr('class');
	this.className = order.toLowerCase();
	filter();
	return false;
});
function clear_filter() {
	$('tr.filter select option:selected').prop('selected', false);
	$('tr.filter input').val('');
	filter();
	return false;
}
//--></script> 
<script type="text/javascript"><!--
$('.filter input').autocomplete({
	delay: 500,
	source: function(request, response) {
	    filter();
	}
});

//--></script> 
<?php echo $footer; ?>