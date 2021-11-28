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
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="input-date-end"><?php echo $entry_date_end; ?></label>
                                <div class="input-group date">
                                    <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="input-group"><?= $entry_group_by ?></label>
                                <select name="filter_group" id="input-group" class="form-control">
                                  <?php foreach ($groups as $group) { ?>
                                  <?php if ($group['value'] == $filter_group) { ?>
                                  <option value="<?php echo $group['value']; ?>" selected="selected"><?php echo $group['text']; ?></option>
                                  <?php } else { ?>
                                  <option value="<?php echo $group['value']; ?>"><?php echo $group['text']; ?></option>
                                  <?php } ?>
                                  <?php } ?>
                                </select>
                              </div>
                            <div class="form-group">
                                <label class="control-label" for="input-status"><?= $entry_shopper ?></label>
                                <input name="filter_vendor" id="input-status" class="form-control" value="<?= $filter_vendor ?>" />
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="input-date-end"><?= $entry_city ?></label>
                                <input type="text" name="filter_city" value="<?php echo $filter_city; ?>" placeholder="City" class="form-control" />
                            </div>
                            <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <td class="text-left"><?= $column_shopper ?></td>
                                <td class="text-left"><?php echo $column_email; ?></td>
                                <td class="text-left"><?php echo $column_status; ?></td>
                                <th><?= $column_city ?></th>
                                <th><?= $column_date_from ?></th>
                                <th><?= $column_date_to ?></th>    
                                <td class="text-right"><?php echo $column_orders; ?></td>
                                <td class="text-right"><?= $column_commision ?></td>
                                <td class="text-right"><?php echo $column_action; ?></td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($shoppers) { ?>
                                <?php foreach ($shoppers as $shopper) { ?>
                                <tr>
                                    <td class="text-left"><?php echo $shopper['shopper']; ?></td>
                                    <td class="text-left"><?php echo $shopper['email']; ?></td>
                                    <td class="text-left"><?php echo $shopper['status']; ?></td>
                                    <td><?= $shopper['city'] ?></td>
                                    <td><?= $shopper['date_from'] ?></td>
                                    <td><?= $shopper['date_to'] ?></td>
                                    <td class="text-right"><?php echo $shopper['orders']; ?></td>
                                    <td class="text-right"><?php echo $shopper['commision']; ?></td>
                                    <td class="text-right"><a href="<?php echo $shopper['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
                                </tr>
                                <?php } ?>
                            <?php } else { ?>
                            <tr>
                                <td class="text-center" colspan="9"><?php echo $text_no_results; ?></td>
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
        
    $('input[name=\'filter_city\']').autocomplete({
        'source': function(request, response) {
                $.ajax({
                        url: 'index.php?path=report/shopper/city_autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
                        dataType: 'json',			
                        success: function(json) {
                            response($.map(json, function(item) {
                                    return {
                                            label: item['name'],
                                            value: item['city_id']
                                    }
                            }));
                        }
                });
        },
        'select': function(item) {
                $('input[name=\'filter_city\']').val(item['label']);
        }	
    });
    
    $('input[name=\'filter_vendor\']').autocomplete({
        'source': function(request, response) {
                $.ajax({
                        url: 'index.php?path=report/shopper/name_autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
                        dataType: 'json',			
                        success: function(json) {
                            response($.map(json, function(item) {
                                    return {
                                            label: item['name'],
                                            value: item['user_id']
                                    }
                            }));
                        }
                });
        },
        'select': function(item) {
                $('input[name=\'filter_vendor\']').val(item['label']);
        }	
    });

  $('#button-filter').on('click', function () {
            url = 'index.php?path=report/shopper&token=<?php echo $token; ?>';

            var filter_city = $('input[name=\'filter_city\']').val();

            if (filter_city) {
                url += '&filter_city=' + encodeURIComponent(filter_city);
            }
            
            var filter_date_start = $('input[name=\'filter_date_start\']').val();

            if (filter_date_start) {
                url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
            }

            var filter_date_end = $('input[name=\'filter_date_end\']').val();

            if (filter_date_end) {
                url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
            }

            var filter_vendor = $('input[name=\'filter_vendor\']').val();

            if (filter_vendor != 0) {
                url += '&filter_vendor=' + encodeURIComponent(filter_vendor);
            }

            location = url;
        });

        $('.date').datetimepicker({
                pickTime: false
        });
//--></script>
</div>

<?php echo $footer; ?>