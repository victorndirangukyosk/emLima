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
            
            <li><a href="#tab-images" data-toggle="tab">
                Images</a></li>
          </ul>

          <div class="tab-content">

            <div class="tab-pane active" id="tab-general">
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
                  <?php if ($error_name) { ?>
                  <div class="text-danger"><?php echo $error_name; ?></div>
                  <?php } ?>
                </div>
              </div>
              

              <div class="form-group">
                  <label class="col-sm-2 control-label"  ><?= $entry_store ?></label>
                  <div class="col-sm-10">
                    <input name="store_name" value="<?= $store ?>" class="form-control" />
                    <input type="hidden" name="store_id" value="<?= $store_id ?>" />
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


            <!-- newtab -->
              <div class="tab-pane" id="tab-images">

                <div class="table-responsive">
                <table id="images" class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <td class="text-left">
                        Image
                      </td>
                      <td>Link</td>
                      <td class="text-left"> Actions </td>
                    </tr>
                  </thead>
                  <tbody>
                    <!-- <tr>
                        <td class="text-left">
                        <a href="" id="thumb-image-default" data-toggle="image" class="img-thumbnail">
                          <img src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" />
                        </a>
                        <input type="hidden" name="image" value="<?php echo $image; ?>" id="input-image" />
                        </td>
                      <td class="text-right"> <input type="text" name="link" ></td>
                      <td class="text-left"></td>
                    </tr> -->
                    <?php $image_row=0; ?>

                    <input type="hidden" name="slider_image_rows" value="<?= count($slider_images)?>" id="slider_image_rows" />

                    <?php foreach ($slider_images as $slider_image) { ?>
                    <tr id="image-row<?php echo $image_row; ?>">
                      <td class="text-left">
                        
                        <a href="" id="image-image<?= $image_row?>" data-toggle="image" class="img-thumbnail">

                        <img src="<?php echo $slider_image['thumb']; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" />

                        <input type="hidden" name="slider_image[<?= $image_row?>][image]" value="<?php echo $slider_image['image']; ?>" id="input-image<?= $image_row?>" /></a>
                      </td>


                      <!-- <input type="hidden" name="slider_image[' + image_row + '][image]" value="" id="input-image' + image_row + '" /> -->

                      <td class="text-right">
                        <div class="">
                        <!-- <?= $slider_image["link"] ?> -->
                          <select id="product_collection_id"  name="slider_image[<?= $image_row?>][link]">
                            <?php foreach($product_collection_ids as $product_collection_id){ ?>

                              <?php if($slider_image["link"] == $product_collection_id["product_collection_id"]) {  ?>
                               <option selected value='<?= $product_collection_id["product_collection_id"] ?>' > <?= $product_collection_id["name"] ?> </option>

                               <?php }else{ ?>
                                <option value='<?= $product_collection_id["product_collection_id"] ?>' > <?= $product_collection_id["name"] ?></option>

                              <?php } ?>

                            <?php } ?>
                          </select>
                        </div>
                      </td>
                      <td class="text-left">
                        <button type="button" data-id="<?php echo $slider_image['id'] ?>" class="btn btn-danger deleteImage">
                          <i class="fa fa-minus-circle"></i>
                        </button>
                       </td>

                    </tr>
                    <?php $image_row++; ?>
                    <?php } ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <td colspan="2"></td>
                      <td class="text-left">
                        <button type="button" onclick="addImages();" data-toggle="tooltip" title="Add Image" class="btn btn-primary">
                           <i class="fa fa-plus-circle"></i>
                        </button>
                      </td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
            <!-- img tab end -->

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

<script type="text/javascript">
  <!--
  var image_row = $('#slider_image_rows').val();

  console.log(image_row);

  function addImages() {
    console.log("addImages");

    html = '<tr id="image-row' + image_row + '">';
    html += '  <td class="text-left"><a href="" id="image-image' + image_row + '"data-toggle="image" class="img-thumbnail"><img src="<?php echo $placeholder; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /><input type="hidden" name="slider_image[' + image_row + '][image]" value="" id="input-image' + image_row + '" /></td>';


    //html += '  <td class="text-right"><input type="text" name="slider_image[' + image_row + '][link]" value="" placeholder="Link" class="pull-right form-control" /></td>';

    html += '  <td class="text-right"><div class=""><select id="product_collection_id" size="1" class="form-control" name="slider_image[' + image_row + '][link]" ><?php foreach($product_collection_ids as $product_collection_id){ ?><option value=<?= $product_collection_id["product_collection_id"] ?> > <?= $product_collection_id["name"] ?></option><?php } ?></select></div></td>';


    

    html += '  <td class="text-left"><button type="button" onclick="$(\'#image-row' + image_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
    html += '</tr>';

    $('#images tbody').append(html);

    image_row++;
  }
  //-->
</script>


 
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});



$('#images').delegate('.deleteImage', 'click', function() {
  console.log('remove');
  $(this).closest('tr').remove();
  var data = {
    id :$(this).data('id'),
    token:'<?php echo $token; ?>'
  };

  $.ajax({
    url:'index.php?path=design/slider/deleteImage',
    data:data,
    success:function(data){
      
    }
  });
});


</script>
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