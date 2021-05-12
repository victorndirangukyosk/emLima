<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
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
        <h3 class="panel-title"><i class="fa fa-bar-chart"></i> <?php echo $text_list; ?></h3>
		  <div class="pull-right">
                    <button type="button" onclick="excel();" data-toggle="tooltip" title="" class="btn btn-success btn-sm" data-original-title="Download Excel"><i class="fa fa-download"></i></button>

			<button type="button" data-toggle="tooltip" title="<?php echo $button_show_filter; ?>" class="btn btn-primary btn-sm" id="showFilter"><i class="fa fa-eye"></i></button>
			<button type="button" data-toggle="tooltip" title="<?php echo $button_hide_filter; ?>" class="btn btn-primary btn-sm" id="hideFilter"><i class="fa fa-eye-slash"></i></button>
		  </div>		
      </div>
      <div class="panel-body">
        <div class="well" style="display:none;">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-date-start"><?php echo $entry_date_start; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-date-end"><?php echo $entry_date_end; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
            </div>


            
           <div class="col-sm-4">

           <div class="form-group">
                                <label class="control-label" for="input-organization">Organization</label>
                                <input type="text" name="filter_organization" value="<?php echo $filter_organization; ?>" placeholder="Organization" id="input-organization" class="form-control" />
                            </div>

              <div class="form-group">
               <label class="control-label" for="input-farmer"><?php echo $entry_farmer; ?></label>
                <input type="text" name="filter_farmer" value="<?php echo $filter_farmer; ?>" id="input-farmer" class="form-control" />
              
              </div>
            </div>

            <div class="col-sm-4">
              <div class="form-group">
                
                 <label class="control-label" for="input-key">Activity Type</label>
                 
                   <select name="filter_key" id="input-key" class="form-control">
                  <option value="0">All</option>
                  <?php foreach ($activity_key as $order_status) { ?>
                  <?php if ($order_status['key'] == $filter_key) { ?>
                  <option value="<?php echo $order_status['key']; ?>" selected="selected"><?php echo $order_status['key']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $order_status['key']; ?>"><?php echo $order_status['key']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
                  
                </select>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-ip"><?php echo $entry_ip; ?></label>
                <input type="text" name="filter_ip" value="<?php echo $filter_ip; ?>" id="input-ip" class="form-control" />
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <td class="text-left">Organization</td>
                <td class="text-left">Farmer Email</td>
                <td class="text-left"><?php echo $column_comment; ?></td>
                <td class="text-left"><?php echo $column_ip; ?></td>
                <td class="text-left"><?php echo $column_date_added; ?></td>
              </tr>
            </thead>
            <tbody>
              <?php if ($activities) { ?>
              <?php foreach ($activities as $activity) { ?>
              <tr>
                <td class="text-left"><?php echo $activity['organization']; ?></td>
                <td class="text-left"><?php echo $activity['email']; ?></td>
                <td class="text-left"><?php echo $activity['comment']; ?></td>
                <td class="text-left"><?php echo $activity['ip']; ?></td>
                <td class="text-left"><?php echo $activity['date_added']; ?></td>
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
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	url = 'index.php?path=report/farmer_activity&token=<?php echo $token; ?>';
	
	var filter_farmer = $('input[name=\'filter_farmer\']').val();
	
	if (filter_farmer) {
		url += '&filter_farmer=' + encodeURIComponent(filter_farmer);
	}
	var filter_ip = $('input[name=\'filter_ip\']').val();
	
	if (filter_ip) {
		url += '&filter_ip=' + encodeURIComponent(filter_ip);
	}
	
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}

  	var filter_organization = $('input[name=\'filter_organization\']').val();
	
	if (filter_organization) {
		url += '&filter_organization=' + encodeURIComponent(filter_organization);
	}
	var filter_key = $('select[name=\'filter_key\']').val();
	
	if (filter_key != 0) {
		url += '&filter_key=' + encodeURIComponent(filter_key);
	}	
 

	location = url;
});

$companyName="";
$('input[name=\'filter_farmer\']').autocomplete({
            'source': function (request, response) {
                $.ajax({
                    url: 'index.php?path=sale/farmer/autocompletefarmer&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request)+'&filter_company=' +$companyName,
                    dataType: 'json',
                    success: function (json) {
                        response($.map(json, function (item) {
                            return {
                                label: item['name'],
                                value: item['farmer_id']
                            }
                        }));
                    }
                });
            },
            'select': function (item) {
                $('input[name=\'filter_farmer\']').val(item['label']);
            }
        });

         $('input[name=\'filter_organization\']').autocomplete({
            'source': function (request, response) {
                $.ajax({
                    url: 'index.php?path=sale/farmer/autocompleteorganization&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request),
                    dataType: 'json',
                    success: function (json) {
                        response($.map(json, function (item) {
                            return {
                                label: item['name'],
                                value: item['name']
                            }
                        }));

                        
                    }
                });
                $companyName="";
            },
            'select': function (item) {
                $('input[name=\'filter_organization\']').val(item['label']);
                $('input[name=\'filter_farmer\']').val('');
                $companyName=item['label'];
            }
        });


        
function excel() {
       url = 'index.php?path=report/farmer_activity/farmeractivityexcel&token=<?php echo $token; ?>';
      
        
       var filter_farmer = $('input[name=\'filter_farmer\']').val();	
	if (filter_farmer) {
		url += '&filter_farmer=' + encodeURIComponent(filter_farmer);
	}
	var filter_ip = $('input[name=\'filter_ip\']').val();
	
	if (filter_ip) {
		url += '&filter_ip=' + encodeURIComponent(filter_ip);
	}
	
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}

  	var filter_organization = $('input[name=\'filter_organization\']').val();
	
	if (filter_organization) {
		url += '&filter_organization=' + encodeURIComponent(filter_organization);
	}
	var filter_key = $('select[name=\'filter_key\']').val();
	
	if (filter_key != 0) {
		url += '&filter_key=' + encodeURIComponent(filter_key);
	}	
 

	location = url;
}


//--></script> 
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false,  widgetParent: 'body'
});
//--></script></div>
<?php echo $footer; ?>


<style>
body {
    position: inline;
}
</style>