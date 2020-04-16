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

          <ul class="nav nav-tabs">
            <li class="active">
              <a href="#tab-general" data-toggle="tab">
                <?php echo $tab_general; ?>
              </a>
            </li>
            
            <li><a href="#tab-seo" data-toggle="tab">
                SEO </a></li>
          </ul>

          <div class="tab-content">

            <div class="tab-pane active" id="tab-general">

              <?php foreach ($languages as $language) { ?>
          

                <div class="form-group required">
                  <label class="col-sm-2 control-label" for="input-name">Name</label>
                  <div class="col-sm-10">
                    <div class="input-group">
                      <span class="input-group-addon"><img src="ui/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>"/ >
                      </span>

                      <input type="text" name="product_collection_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($product_collection_description[$language['language_id']]) ? $product_collection_description[$language['language_id']]['name'] : '';?>"  placeholder="Name" id="input-name" class="form-control" /> 

                    </div>
                    <?php if (isset($error_name[$language['language_id']])) { ?>
                    <div class="text-danger"><?php echo $error_name[$language['language_id']]; ?></div>
                    <?php } ?>
                  </div>
                </div>
              <?php } ?>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-product"><span data-toggle="tooltip" title="<?php echo $help_product; ?>">Products</span></label>
                <div class="col-sm-10">
                  
                  <input type="text" name="product" value="" placeholder="Products" id="input-product" class="form-control" />
                 
                  <div id="coupon-product" class="well well-sm" style="height: 150px; overflow: auto;">
                    <?php foreach ($product_collection_products as $product_collection_product) { ?>
                    <div id="coupon-product<?php echo $product_collection_product['product_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $product_collection_product['name']; ?>
                      <input type="hidden" name="product_collection_product[]" value="<?php echo $product_collection_product['product_id']; ?>" />
                    </div>
                    <?php } ?>
                  </div>
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

              <!-- seo tab start -->
          <div class="tab-pane fade" id="tab-seo">
              <ul class="nav nav-tabs" id="seo-language">
                <?php foreach ($languages as $language) { ?>
                <li><a href="#seo-language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="ui/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
                <?php } ?>
              </ul>
              <div class="tab-content">
                <?php foreach ($languages as $language) { ?>
                <div class="tab-pane" id="seo-language<?php echo $language['language_id']; ?>">
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-seo-url"><span data-toggle="tooltip" title="<?php echo $help_seo_url; ?>"><?php echo $entry_seo_url; ?></span></label>
                    <div class="col-sm-10">
                      <input type="text" name="seo_url[<?php echo $language['language_id']; ?>]" value="<?php echo isset($seo_url[$language['language_id']]) ? $seo_url[$language['language_id']] : ''; ?>" placeholder="<?php echo $entry_seo_url; ?>" id="input-seo-url" class="form-control" />
                      <?php if (isset($error_seo_url[$language['language_id']])) { ?>
                      <div class="text-danger">
                        <?php echo $error_seo_url[$language['language_id']]; ?>
                      </div>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-meta-title<?php echo $language['language_id']; ?>"><?php echo $entry_meta_title; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="product_collection_description[<?php echo $language['language_id']; ?>][meta_title]" value="<?php echo isset($product_collection_description[$language['language_id']]['meta_title']) ? $product_collection_description[$language['language_id']]['meta_title'] : ''; ?>" placeholder="<?php echo $entry_meta_title; ?>" id="input-meta-title<?php echo $language['language_id']; ?>" class="form-control" />
                      <?php if (isset($error_meta_title[$language['language_id']])) { ?>
                      <div class="text-danger">
                        <?php echo $error_meta_title[$language['language_id']]; ?>
                      </div>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-meta-description<?php echo $language['language_id']; ?>"><?php echo $entry_meta_description; ?></label>
                    <div class="col-sm-10">
                      <textarea name="product_collection_description[<?php echo $language['language_id']; ?>][meta_description]" rows="5" placeholder="<?php echo $entry_meta_description; ?>" id="input-meta-description<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($product_collection_description[$language['language_id']]) ? $product_collection_description[$language['language_id']]['meta_description'] : ''; ?></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-meta-keyword<?php echo $language['language_id']; ?>"><?php echo $entry_meta_keyword; ?></label>
                    <div class="col-sm-10">
                      <textarea name="product_collection_description[<?php echo $language['language_id']; ?>][meta_keyword]" rows="5" placeholder="<?php echo $entry_meta_keyword; ?>" id="input-meta-keyword<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($product_collection_description[$language['language_id']]) ? $product_collection_description[$language['language_id']]['meta_keyword'] : ''; ?></textarea>
                    </div>
                  </div>
                </div>
                <?php } ?>
              </div>
            </div>

          </div>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript">
$('#language a:first').tab('show');
  $('#seo-language a:first').tab('show');
  <!--
/*
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
*/
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
        $.ajax({
          url: 'index.php?path=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
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
    },
    'select': function(item) {
      $('input[name=\'product\']').val('');
      
      $('#coupon-product' + item['value']).remove();
      
      $('#coupon-product').append('<div id="coupon-product' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="product_collection_product[]" value="' + item['value'] + '" /></div>');  
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