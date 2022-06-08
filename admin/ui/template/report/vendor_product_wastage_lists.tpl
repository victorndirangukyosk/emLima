<?php echo $header; ?><?php echo $column_left;?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">               
                    <button type="button" onclick="excel();" data-toggle="tooltip" title="" class="btn btn-success btn-sm" data-original-title="Download Excel"><i class="fa fa-download"></i></button>
            
                <!--<?php if($this->user->getGroupName() == 'Administrator') { ?>-->
                          
            <!--<?php } ?>-->
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
        <!--<?php if ($error_warning) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>-->
        <?php if ($success) { ?>
        <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
                <div class="pull-right">
                    <button type="button" data-toggle="tooltip" title="<?php echo $button_show_filter; ?>" class="btn btn-primary btn-sm" id="showFilter"><i class="fa fa-eye"></i></button>
                    <button type="button" data-toggle="tooltip" title="<?php echo $button_hide_filter; ?>" class="btn btn-primary btn-sm" id="hideFilter"><i class="fa fa-eye-slash"></i></button>
                </div>
            </div>
            <div class="panel-body">

                <div class="well" style="display:none;">
                    <div class="row">
                         <div class="<?php echo $is_vendor ? 'col-sm-4' : 'col-sm-4' ?>">
                            <div class="form-group">
                                <label class="control-label" for="input-name"><?php echo $entry_name; ?></label>
                                <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
                            </div>    

                           

                        </div>
                        
                        <div class="col-sm-4">
                            
                           

                             <div class="form-group">

                             <label class="control-label" for="input-date-added">Date Added / From</label>
                                <div class="input-group date">
                                    <input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" placeholder="Date Added" data-date-format="YYYY-MM-DD" id="input-date-added" class="form-control" />
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                                

                               
                            </div>

                              

                            
                        </div>
                      

                      


                        <div class="<?php echo $is_vendor ? 'col-sm-4' : 'col-sm-4' ?>">
                             
                           
 <div class="form-group">

                             <label class="control-label" for="input-date-added-to">Date Added To</label>
                                <div class="input-group date">
                                    <input type="text" name="filter_date_added_to" value="<?php echo $filter_date_added_to; ?>" placeholder="Date Added To" data-date-format="YYYY-MM-DD" id="input-date-added-to" class="form-control" />
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                                

                               
                            </div>

         <div class="form-group" >
                  <label style="margin-top:30px;display:none"><input type="checkbox" name="filter_group_by_date[]" value="<?php echo $filter_group_by_date; ?>" <?php if($filter_group_by_date == 1) { ?> checked="" <?php } ?>> Group By Day </label>
                            <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
             
              </div>
                            


                        </div>
                    </div>
                </div>
                <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-product">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    

                                    <!--<td class="text-left"><?php if ($sort == 'p.product_id') { ?>
                                        <a href="<?php echo $sort_product_id; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_product_id; ?></a>
                                        <?php } else { ?>
                                        <a href="<?php echo $sort_product_id; ?>"><?php echo $column_product_id; ?></a>
                                        <?php } ?></td> 


                                    <td class="text-left"><?php if ($sort == 'ps.product_store_id') { ?>
                                        <a href="<?php echo $sort_vproduct_id; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_vproduct_id; ?></a>
                                        <?php } else { ?>
                                        <a href="<?php echo $sort_vproduct_id; ?>"><?php echo $column_vproduct_id; ?></a>
                                        <?php } ?></td>



                                   <td class="text-left"><?php if ($sort == 'pd.name') { ?>
                                        <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                                        <?php } else { ?>
                                        <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                                        <?php } ?>
                                    </td>-->

                                   


                                    <td><?php echo $column_vproduct_id; ?></td>
                                    <td><?php echo $column_name; ?></td>
                                    <td>Unit</td>
                                    <td>Date</td>

                                     
                                     <td class="text-right">Wastage Quantity</td>
                                     <td class="text-right">Avg. Buying Price</td>
                                     

                                    
                                     
                                    
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($products) { ?>
                                <?php foreach ($products as $product) { ?>
                                <tr>
                                    
                                   <!-- <td class="text-right"><?php echo $product['product_id']; ?></td>-->
                                    <td class="text-right"><?php echo $product['product_store_id']; ?></td>
                                    <td class="text-left"><?php echo $product['name']; ?></td>


                                    <td class="text-left"><?php echo $product['unit']; ?></td>
                                    <td class="text-left"><?php echo $product['date_added']; ?></td>
                                     
                                    <td class="text-right"><?php echo $product['wastage_qty']; ?>
                                    </td>                        
				     <td class="text-right"><?php echo $product['avg_buying_price']; ?>
                                    </td> 
				 
                                    
                                </tr>
									 
                                <?php } ?>
                                <?php } else { ?>
                                <tr>
                                    <td class="text-center" colspan="13"><?php echo $text_no_results; ?></td>
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
        
  

 

  $('#button-filter').on('click', function() {

            var url = 'index.php?path=report/inventory_wastage&token=<?php echo $token; ?>';

            var filter_name = $('input[name=\'filter_name\']').val();

            if (filter_name) {
                url += '&filter_name=' + encodeURIComponent(filter_name);
            }

           //  var filter_group_by_date = 0;
            
          // if ($('input[name=\'filter_group_by_date[]\']').is(':checked')) {
           //     filter_group_by_date = 1;
           //    url += '&filter_group_by_date=' + encodeURIComponent(filter_group_by_date);
           // } else {
           // url += '&filter_group_by_date=' + encodeURIComponent(filter_group_by_date);    
           //}

             
            
            var filter_date_added = $('input[name=\'filter_date_added\']').val();

            if (filter_date_added) {
                url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
            }


              var filter_date_added_to = $('input[name=\'filter_date_added_to\']').val();

            if (filter_date_added_to) {
                url += '&filter_date_added_to=' + encodeURIComponent(filter_date_added_to);
            }
            

            
             

            location = url;
        });
  //--></script> 
    <script type="text/javascript"><!--
  $('input[name=\'filter_name\']').autocomplete({
            'source': function(request, response) {
                $.ajax({
                    url: 'index.php?path=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request),
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
                $('input[name=\'filter_name\']').val(item['label']);
            }
        });

        
  //--></script></div>

<script type="text/javascript"><!--

 

        $('.date').datetimepicker({
	pickTime: false,  widgetParent: 'body'
});

    setInterval(function() {
     location = location;
    }, 300 * 1000);
 
//--></script>
<script type="text/javascript"><!--
 
  
            
function excel() {
      
            var url = 'index.php?path=report/inventory_wastage/excel&token=<?php echo $token; ?>';
       var filter_name = $('input[name=\'filter_name\']').val();

            if (filter_name) {
                url += '&filter_name=' + encodeURIComponent(filter_name);
            }

            // var filter_group_by_date = 0;
            
           //if ($('input[name=\'filter_group_by_date[]\']').is(':checked')) {
             //   filter_group_by_date = 1;
             //  url += '&filter_group_by_date=' + encodeURIComponent(filter_group_by_date);
            //} else {
            //url += '&filter_group_by_date=' + encodeURIComponent(filter_group_by_date);    
           //}

            
            var filter_date_added = $('input[name=\'filter_date_added\']').val();

            if (filter_date_added) {
                url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
            }


              var filter_date_added_to = $('input[name=\'filter_date_added_to\']').val();

            if (filter_date_added_to) {
                url += '&filter_date_added_to=' + encodeURIComponent(filter_date_added_to);
            }
            

            
             

	location = url;
}

 
 
 
     

 
</script>
<style>
.bootstrap-select {
width : 100% !important;    
}
</style>

<?php echo $footer; ?>