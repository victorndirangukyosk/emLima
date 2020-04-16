<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" onclick="save('save')" form="form-coupon" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-success"><i class="fa fa-check"></i></button>
		<button type="submit" form="form-coupon" data-toggle="tooltip" title="<?php echo $button_saveclose; ?>" class="btn btn-default" data-original-title="Save & Close"><i class="fa fa-save text-success"></i></button>
		<button type="submit" onclick="save('new')" form="form-coupon" data-toggle="tooltip" title="<?php echo $button_savenew; ?>" class="btn btn-default" data-original-title="Save & New"><i class="fa fa-plus text-success"></i></button>		
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-times-circle text-danger"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
      
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-coupon" class="form-horizontal">
          <div class="tab-content">
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
                  <?php if ($error_name) { ?>
                  <div class="text-danger"><?php echo $error_name; ?></div>
                  <?php } ?>
                </div>
              </div>
              <!-- <div class="form-group">
                <label class="col-sm-2 control-label" for="input-discount"><?php echo $entry_discount; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="discount" value="<?php echo $discount; ?>" placeholder="<?php echo $entry_discount; ?>" id="input-discount" class="form-control" />
                </div>
              </div> -->

              <div class="form-group">
                  <label class="col-sm-2 control-label"  ><?= $entry_store ?></label>
                  <div class="col-sm-10">
                    <input name="store_name" value="<?= $store ?>" class="form-control" />
                    <input type="hidden" name="store_id" value="<?= $store_id ?>" />
                  </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-product"><span data-toggle="tooltip" title="<?php echo $help_product; ?>"><?php echo $entry_product; ?></span></label>
                <div class="col-sm-10">
                  
                  <?php if($store_id) { ?>
                      <input type="text" name="product" value="" placeholder="<?php echo $entry_product; ?>" id="input-product" class="form-control" />
                  <?php  } else { ?>
                      <input type="text" name="product" value="" placeholder="<?php echo $entry_product; ?>" id="input-product" class="form-control" disabled/>
                  <?php } ?>
                  <div id="coupon-product" class="well well-sm" style="height: 150px; overflow: auto;">
                    <?php foreach ($offer_products as $offer_product) { ?>
                    <div id="coupon-product<?php echo $offer_product['product_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $offer_product['name']; ?>
                      <input type="hidden" name="offer_product[]" value="<?php echo $offer_product['product_id']; ?>" />
                    </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-date-start"><?php echo $entry_date_start; ?></label>
                <div class="col-sm-3">
                  <div class="input-group date">
                    <input type="text" name="date_start" value="<?php echo $date_start; ?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                    <span class="input-group-btn">
                    <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                    </span></div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-date-end"><?php echo $entry_date_end; ?></label>
                <div class="col-sm-3">
                  <div class="input-group date">
                    <input type="text" name="date_end" value="<?php echo $date_end; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
                    <span class="input-group-btn">
                    <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                    </span></div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                <div class="col-sm-10">
                  <select name="status" id="input-status" class="form-control">
                    <?php if ($status) { ?>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                    <option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--

  $("input[name=\'store_name\']").bind("change paste keyup", function() {


      var store_id = $('input[name=\'store_name\']').val();

      console.log('check store');
      console.log(store_id);
      if(store_id) {
          $('#input-product').removeAttr('disabled');
          
      } else {
        $('#input-product').attr('disabled','disabled');
      }
  });

  $(function(){
        $('input[name=\'store_name\']').autocomplete({
            'source': function(request, response) {                
                    $.ajax({
                            url: 'index.php?path=report/store_sales/store_autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
                            dataType: 'json',           
                            success: function(json) {
                                    response($.map(json, function(item) {
                                            return {
                                                    label: item['name'],
                                                    value: item['store_id']
                                            }
                                    }));
                            }
                    });
            },
            'select': function(item) {
                    $('input[name=\'store_name\']').val(item['label']);
                    $('input[name=\'store_id\']').val(item['value']);
                    var store_id = $('input[name=\'store_name\']').val();
                    if(store_id) {
                        $('#input-product').removeAttr('disabled');
                        
                    } else {
                      $('#input-product').attr('disabled','disabled');
                    }
            }   
        });
    });

    var filter_store = $('input[name=\'store_name\']').val();
    
    

$('input[name=\'product\']').autocomplete({
	'source': function(request, response) {

    console.log('request');
    console.log(request);
    var store_id = $('input[name=\'store_id\']').val()
    console.log(store_id);
    if(store_id) {
      console.log("if store_id");
        $.ajax({
          url: 'index.php?path=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request)+'&filter_store=' +  encodeURIComponent(store_id),
          dataType: 'json',     
          success: function(json) {
            response($.map(json, function(item) {
              return {
                label: item['name'],
                value: item['product_id']
              }
            }));
          }
        });
    }
    },
    'select': function(item) {
      $('input[name=\'product\']').val('');
      
      $('#coupon-product' + item['value']).remove();
      
      $('#coupon-product').append('<div id="coupon-product' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="offer_product[]" value="' + item['value'] + '" /></div>');  
    }
		
});

$('#coupon-product').delegate('.fa-minus-circle', 'click', function() {
	$(this).parent().remove();
});

// Category
$('input[name=\'category\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?path=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['category_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'category\']').val('');
		
		$('#coupon-category' + item['value']).remove();
		
		$('#coupon-category').append('<div id="coupon-category' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="coupon_category[]" value="' + item['value'] + '" /></div>');
	}	
});

$('#coupon-category').delegate('.fa-minus-circle', 'click', function() {
	$(this).parent().remove();
});
//--></script>
 
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script>
<script type="text/javascript"><!--
function save(type){
	var input = document.createElement('input');
	input.type = 'hidden';
	input.name = 'button';
	input.value = type;
	form = $("form[id^='form-']").append(input);
	form.submit();
}
//--></script></div>
<?php echo $footer; ?>