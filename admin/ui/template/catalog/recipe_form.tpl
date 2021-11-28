<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
		<button type="submit" onclick="save('save')" form="form-attribute" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-success" data-original-title="Save"><i class="fa fa-check"></i></button>
		<button type="submit" form="form-attribute" data-toggle="tooltip" title="<?php echo $button_saveclose; ?>" class="btn btn-default" data-original-title="Save & Close"><i class="fa fa-save text-success"></i></button>
		<button type="submit" onclick="save('new')" form="form-attribute" data-toggle="tooltip" title="<?php echo $button_savenew; ?>" class="btn btn-default" data-original-title="Save & New"><i class="fa fa-plus text-success"></i></button>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-category" class="form-horizontal">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
            <li><a href="#tab-directions" data-toggle="tab"><?php echo $tab_directions; ?></a></li>
            <li><a href="#tab-products" data-toggle="tab"><?php echo $tab_products; ?></a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active in" id="tab-general">
              
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-column"><?= $entry_title ?></label>
                <div class="col-sm-10">
                  <input type="text" name="title" value="<?php echo $title; ?>" placeholder="Enter title" id="input-column" class="form-control" />
                  <?php if ($error_title) { ?>
                  <div class="text-danger"><?php echo $error_title; ?></div>
                  <?php } ?>
                </div>
              </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-image"><?= $entry_image ?></label>
                    <div class="col-sm-10">
                        <a href="" id="thumb-image" data-toggle="image" class="img-thumbnail">
                            <img src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" />
                        </a>
                        <input type="hidden" name="image" value="<?php echo $image; ?>" id="input-image" />
                    </div>
                </div>

              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-description"><?= $entry_description ?></label>
                <div class="col-sm-10">
                    <textarea name="description" rows="5" placeholder="Enter description" id="input-description" class="form-control"><?php echo $description; ?></textarea>
                    <?php if ($error_description) { ?>
                  <div class="text-danger"><?php echo $error_description; ?></div>
                  <?php } ?>
                </div>
              </div>
                
              <div class="form-group">
                <label class="col-sm-2 control-label"><?= $entry_categories ?></label>
                <div class="col-sm-10">
                  <div class="well well-sm" style="height: 150px; overflow: auto;">
                    <?php foreach ($categories as $row) { ?>
                    <div class="checkbox">
                      <label>
                        <?php if (in_array($row['category_id'], $category)) { ?>
                        <input type="checkbox" name="category[]" value="<?php echo $row['category_id']; ?>" checked="checked" />
                        <?php echo $row['name']; ?>
                        <?php } else { ?>
                        <input type="checkbox" name="category[]" value="<?php echo $row['category_id']; ?>" />
                        <?php echo $row['name']; ?>
                        <?php } ?>
                      </label>
                    </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
                
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-author"><?= $entry_author ?></label>
                <div class="col-sm-10">
                  <input type="text" name="author" value="<?php echo $author; ?>" placeholder="Enter author" id="input-author" class="form-control" />
                </div>
              </div>
                
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-video"><?= $entry_utube_link ?></label>
                <div class="col-sm-10">
                  <input type="text" name="video" value="<?php echo $video; ?>" placeholder="Enter video" id="input-video" class="form-control" />
                </div>
              </div>
                
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-sortorder"><?= $entry_sort_order ?></label>
                <div class="col-sm-10">
                  <input type="text" name="sort_order" value="<?php echo $sort_order; ?>" placeholder="Enter sort order" id="input-sort_order" class="form-control" />
                </div>
              </div>
                                
            </div><!-- END #tab-general -->
              
            <div class="tab-pane" id="tab-directions">
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-directions"><?= $entry_directions ?></label>
                <div class="col-sm-10">
                    <textarea name="directions" rows="5" placeholder="Enter directions" id="input-directions" class="form-control"><?php echo $directions; ?></textarea>
                  <?php if ($error_directions) { ?>
                  <div class="text-danger"><?php echo $error_directions; ?></div>
                  <?php } ?>
                </div>                
              </div>
            </div><!-- END tab-directions -->
            
            <div class="tab-pane" id="tab-products">
              <div class="table-responsive">
                <table id="products" class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <td class="text-left"><?= $column_image ?></td>
                      <td class="text-left"><?= $column_name ?></td>
                      <td class="text-left"><?= $column_model ?></td>
                      <td class="text-left"><?= $column_qty ?></td>
                      <td></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $product_row = 0; ?>
                    <?php foreach ($products as $product) { ?>
                    <tr id="image-row<?php echo $product_row; ?>">
                      <td class="text-left">
                          <a href="" id="thumb-image<?php echo $product_row; ?>" data-toggle="image" class="img-thumbnail">
                              <img src="<?php echo $product['thumb']; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" />
                          </a>
                          <input type="hidden" name="products[<?php echo $product_row; ?>][image]" value="<?php echo $product['image']; ?>" id="input-image<?php echo $product_row; ?>" />
                      </td>
                      <td class="text-left">
                          <input type="text" name="products[<?php echo $product_row; ?>][name]" value="<?php echo $product['name']; ?>" placeholder="Name" class="form-control" />
                      </td>
                      <td class="text-left">
                          <input type="text" name="products[<?php echo $product_row; ?>][model]" value="<?php echo $product['model']; ?>" placeholder="Model" class="form-control" />
                      </td>
                      <td class="text-left">
                          <input type="text" name="products[<?php echo $product_row; ?>][quantity]" value="<?php echo $product['quantity']; ?>" placeholder="Qty" class="form-control" />
                      </td>
                      <td class="text-left">
                          <button type="button" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button>
                      </td>
                    </tr>
                    <?php $product_row++; ?>
                    <?php } ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <td colspan="4"></td>
                      <td class="text-left">
                          <button type="button" onclick="addProduct();" data-toggle="tooltip" title="Add Image" class="btn btn-primary">
                              <i class="fa fa-plus-circle"></i>
                          </button>
                      </td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div><!-- END #tab-products -->
             
            </div><!-- END .tab-content -->
            </form>
          </div><!-- END .panel-body -->
    </div><!-- END .panel -->
  </div><!-- END .content-fluid -->
</div><!-- END #content -->

  <script type="text/javascript">
      $('#input-directions').summernote({
                height: 300
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
//--></script>

<script type="text/javascript"><!--
    
$(document).delegate('#products .btn-danger', 'click', function(){
    $(this).parents('tr').remove();
});

var product_row = <?php echo $product_row; ?>;

function addProduct() {
	html  = '<tr id="product-row' + product_row + '">';
	html += '  <td class="text-left"><a href="" id="thumb-image' + product_row + '"data-toggle="image" class="img-thumbnail"><img src="<?php echo $placeholder; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /><input type="hidden" name="products[' + product_row + '][image]" value="" id="input-image' + product_row + '" /></td>';
	html += '  <td class="text-left"><input type="text" name="products[' + product_row + '][name]" value="" placeholder="Name" class="form-control" /></td>';
        html += '  <td class="text-left"><input type="text" name="products[' + product_row + '][model]" value="" placeholder="Model" class="form-control" /></td>';
	html += '  <td class="text-left"><input type="text" name="products[' + product_row + '][quantity]" value="" placeholder="Qty" class="form-control" /></td>';
        html += '  <td class="text-left"><button type="button" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
	html += '</tr>';
	
	$('#products tbody').append(html);
	
	product_row++;
}
//--></script> 
            
<?php echo $footer; ?>