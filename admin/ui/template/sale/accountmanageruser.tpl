



<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="button" onclick="excel();" data-toggle="tooltip" title="" class="btn btn-success " data-original-title="Download Excel"><i class="fa fa-download"></i></button>
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
        <?php if ($error_warning) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
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

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label" for="input-company">Account Manager Name</label>
                                <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
                            </div>

                            <div class="form-group">
                                <label class="control-label" for="input-date-added"><?php echo $entry_date_added; ?></label>
                                <div class="input-group date" style="max-width: 321px;">
                                    <input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" placeholder="<?php echo $entry_date_added; ?>" data-date-format="YYYY-MM-DD" id="input-date-added" class="form-control" />
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                    </span></div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label" for="input-telephone"><?php echo $column_telephone; ?></label>
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <button class="btn btn-default" type="button"><?php echo '+' . $this->config->get('config_telephone_code'); ?></button>                                      
                                    </span>
                                    <input type="text" name="filter_telephone" value="<?php echo $filter_telephone; ?>" placeholder="<?php echo $entry_telephone; ?>" id="input-telephone" class="form-control" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57"  minlength="9" maxlength="9"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="input-ip"><?php echo $entry_ip; ?></label>
                                <input type="text" name="filter_ip" value="<?php echo $filter_ip; ?>" placeholder="<?php echo $entry_ip; ?>" id="input-ip" class="form-control" />
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label" for="input-email"><?php echo $entry_email; ?></label>
                                <input type="text" name="filter_email" value="<?php echo $filter_email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
                            </div>
                            <!--<div class="form-group">
                                <label class="control-label" for="input-status"><?php echo $entry_status; ?></label>
                                <select name="filter_status" id="input-status" class="form-control">
                                    <option value="*"></option>
                                    <?php if ($filter_status) { ?>
                                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                    <?php } else { ?>
                                    <option value="1"><?php echo $text_enabled; ?></option>
                                    <?php } ?>
                                    <?php if (!$filter_status && !is_null($filter_status)) { ?>
                                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                    <?php } else { ?>
                                    <option value="0"><?php echo $text_disabled; ?></option>
                                    <?php } ?>
                                </select>
                            </div>-->
                        </div>
                        <div class="col-sm-3">
                            <!-- <div class="form-group">
                              <label class="control-label" for="input-approved"><?php echo $entry_approved; ?></label>
                              <select name="filter_approved" id="input-approved" class="form-control">
                                <option value="*"></option>
                                <?php if ($filter_approved) { ?>
                                <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                                <?php } else { ?>
                                <option value="1"><?php echo $text_yes; ?></option>
                                <?php } ?>
                                <?php if (!$filter_approved && !is_null($filter_approved)) { ?>
                                <option value="0" selected="selected"><?php echo $text_no; ?></option>
                                <?php } else { ?>
                                <option value="0"><?php echo $text_no; ?></option>
                                <?php } ?>
                              </select>
                            </div> -->
                            <div class="form-group">
                                <label class="control-label" for="input-status"><?php echo $entry_status; ?></label>
                                <select name="filter_status" id="input-status" class="form-control">
                                    <option value="*"></option>
                                    <?php if ($filter_status) { ?>
                                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                    <?php } else { ?>
                                    <option value="1"><?php echo $text_enabled; ?></option>
                                    <?php } ?>
                                    <?php if (!$filter_status && !is_null($filter_status)) { ?>
                                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                    <?php } else { ?>
                                    <option value="0"><?php echo $text_disabled; ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <!--<div class="form-group">
                                <label class="control-label" for="input-ip"><?php echo $entry_ip; ?></label>
                                <input type="text" name="filter_ip" value="<?php echo $filter_ip; ?>" placeholder="<?php echo $entry_ip; ?>" id="input-ip" class="form-control" />
                            </div>-->
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label"></label>
                                <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>    
                            </div>
                        </div>
                    </div>
                </div>
                <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-customer">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                                    <td class="text-left"><?php if ($sort == 'name') { ?>
                                        <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                                        <?php } else { ?>
                                        <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                                        <?php } ?></td>
                                    <td class="text-left"><?php if ($sort == 'c.email') { ?>
                                        <a href="<?php echo $sort_email; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_email; ?></a>
                                        <?php } else { ?>
                                        <a href="<?php echo $sort_email; ?>"><?php echo $column_email; ?></a>
                                        <?php } ?></td>
                                    <td class="text-left"><?php echo $column_telephone; ?></td>
                                    <td class="text-left"><?php if ($sort == 'c.status') { ?>
                                        <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                                        <?php } else { ?>
                                        <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                                        <?php } ?></td>
                                    <td class="text-left"><?php if ($sort == 'c.ip') { ?>
                                        <a href="<?php echo $sort_ip; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_ip; ?></a>
                                        <?php } else { ?>
                                        <a href="<?php echo $sort_ip; ?>"><?php echo $column_ip; ?></a>
                                        <?php } ?></td>
                                    <td class="text-left"><?php if ($sort == 'c.date_added') { ?>
                                        <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
                                        <?php } else { ?>
                                        <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
                                        <?php } ?></td>
                                    <td class="text-right"><?php echo $column_action; ?></td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($customers) { ?>
                                <?php foreach ($customers as $customer) { ?>
                                <tr>
                                    <td class="text-center"><?php if (in_array($customer['user_id'], $selected)) { ?>
                                        <input type="checkbox" name="selected[]" value="<?php echo $customer['user_id']; ?>" checked="checked" />
                                        <?php } else { ?>
                                        <input type="checkbox" name="selected[]" value="<?php echo $customer['user_id']; ?>" />
                                        <?php } ?></td>
                                    <td class="text-left"><?php echo $customer['name']; ?></td>
                                    <td class="text-left"><?php echo $customer['email']; ?></td>
                                    <td class="text-left"><?php echo $customer['telephone']; ?></td>
                                    <td class="text-left"><?php echo $customer['status']; ?></td>
                                    <td class="text-left"><?php echo $customer['ip']; ?></td>
                                    <td class="text-left"><?php echo $customer['date_added']; ?></td>
                                    <td class="text-right">
                                        <a target="_blank" class="btn btn-info" data-toggle="tooltip" title="<?php echo 'Login as customer'; ?>" href="index.php?path=sale/accountmanager/login&token=<?php echo $token; ?>&customer_id=<?php echo $customer['user_id']; ?>&store_id=0">
                                            <i class="fa fa-lock"></i>
                                        </a>
                                </tr>
                                <?php } ?>
                                <?php } else { ?>
                                <tr>
                                    <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
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
  $('#button-filter').on('click', function () {
            url = 'index.php?path=sale/accountmanageruser&token=<?php echo $token; ?>';

            var filter_name = $('input[name=\'filter_name\']').val();

            if (filter_name) {
                url += '&filter_name=' + encodeURIComponent(filter_name);
            }

            var filter_email = $('input[name=\'filter_email\']').val();

            if (filter_email) {
                url += '&filter_email=' + encodeURIComponent(filter_email);
            }

            var filter_status = $('select[name=\'filter_status\']').val();

            if (filter_status != '*') {
                url += '&filter_status=' + encodeURIComponent(filter_status);
            }

            var filter_telephone = $('input[name=\'filter_telephone\']').val();

            if (filter_telephone != '*') {
                url += '&filter_telephone=' + encodeURIComponent(filter_telephone);
            }

            var filter_ip = $('input[name=\'filter_ip\']').val();

            if (filter_ip) {
                url += '&filter_ip=' + encodeURIComponent(filter_ip);
            }

            var filter_date_added = $('input[name=\'filter_date_added\']').val();

            if (filter_date_added) {
                url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
            }

            location = url;
        });
        //--></script> 
    <script type="text/javascript"><!--

        $companyName = "";
        $('input[name=\'filter_name\']').autocomplete({
            'source': function (request, response) {
                $.ajax({
                    url: 'index.php?path=sale/accountmanageruser/autocompletebyCompany&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request) + '&filter_company=' + $companyName,
                    dataType: 'json',
                    success: function (json) {
                        response($.map(json, function (item) {
                            return {
                                label: item['name'],
                                value: item['user_id']
                            }
                        }));
                    }
                });
            },
            'select': function (item) {
                $('input[name=\'filter_name\']').val(item['label']);
            }
        });

        $('input[name=\'filter_company\']').autocomplete({
            'source': function (request, response) {
                $.ajax({
                    url: 'index.php?path=sale/accountmanageruser/autocompletecompany&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request),
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
                $companyName = "";
            },
            'select': function (item) {
                $('input[name=\'filter_company\']').val(item['label']);
                $('input[name=\'filter_customer\']').val('');
                $companyName = item['label'];
            }
        });


        $('input[name=\'filter_email\']').autocomplete({
            'source': function (request, response) {
                $.ajax({
                    url: 'index.php?path=sale/accountmanageruser/autocomplete&token=<?php echo $token; ?>&filter_email=' + encodeURIComponent(request),
                    dataType: 'json',
                    success: function (json) {
                        response($.map(json, function (item) {
                            return {
                                label: item['email'],
                                value: item['user_id']
                            }
                        }));
                    }
                });
            },
            'select': function (item) {
                $('input[name=\'filter_email\']').val(item['label']);
            }
        });
        //--></script> 
    <script type="text/javascript"><!--
  $('.date').datetimepicker({
            pickTime: false,
            widgetParent: 'body'
        });

        function excel() {

            url = 'index.php?path=sale/accountmanageruser/export_excel&token=<?php echo $token; ?>';

            location = url;
        }

        //--></script></div>
<?php echo $footer; ?> 

<style>
    body {
        position: relative;
    }
</style>

