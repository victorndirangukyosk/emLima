<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"></div>
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
        <h3 class="panel-title"><i class="fa fa-eye"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <div id="form-customer" class="form-horizontal">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
            <?php if ($customer_id) { ?>
            <li><a href="#tab-history" data-toggle="tab"><?php echo $tab_history; ?></a></li>
            <li><a href="#tab-credit" data-toggle="tab"><?php echo $tab_credit; ?></a></li>
            <li><a href="#tab-ip" data-toggle="tab"><?php echo $tab_ip; ?></a></li>
            <li><a href="#tab-referral" data-toggle="tab"><?php echo $tab_referral; ?></a></li>
	    <li><a href="#tab-sub-customer" data-toggle="tab"><?php echo $tab_sub_customer; ?></a></li>
            <li><a href="#tab-otp" data-toggle="tab">OTP</a></li>
            <li><a href="#tab-activity" data-toggle="tab">Activities</a></li>
            <?php } ?>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-general">
              <div class="row">
                <div class="col-sm-2">
                  <ul class="nav nav-pills nav-stacked" id="address">
                    <li class="active"><a href="#tab-customer" data-toggle="tab"><?php echo $tab_general; ?></a></li>
                    <?php $address_row = 1; ?>
                    <?php foreach ($addresses as $address) { ?>
                    <li><a href="#tab-address<?php echo $address_row; ?>" data-toggle="tab"> <?php echo $tab_address . ' ' . $address_row; ?></a></li>
                    <?php $address_row++; ?>
                    <?php } ?>
                  </ul>
                </div>
                <div class="col-sm-10">
                  <div class="tab-content">
                      <div class="tab-pane active" id="tab-customer">
                          <table class="table table-bordered">
                              <tbody>
                                  <tr>
                                      <th>Customer Group</th>
                                      <td><?php echo $customer_group_info['name']; ?></td>
                                  </tr>
                                  <tr>
                                      <th>First Name</th>
                                      <td><?php echo $firstname; ?></td>
                                  </tr>
                                  <tr>
                                      <th>Last Name</th>
                                      <td><?php echo $lastname; ?></td>
                                  </tr>
                                  <tr>
                                      <th>E-Mail</th>
                                      <td><?php echo $email; ?></td>
                                  </tr>
                                  <tr>
                                      <th>Company Name</th>
                                      <td><?php echo $company_name; ?></td>
                                  </tr>
                                  <tr>
                                      <th>Company Address</th>
                                      <td><?php echo $company_address; ?></td>
                                  </tr>
                                  <tr>
                                      <th>Gender</th>
                                      <td><?php echo $gender; ?></td>
                                  </tr>
                                  <tr>
                                      <th>Phone No.</th>
                                      <td><?php echo '+254 '.$telephone; ?></td>
                                  </tr>
                                  <tr>
                                      <th>Tax No.</th>
                                      <td><?php echo $fax; ?></td>
                                  </tr>
                                  <tr>
                                      <th>Newsletter</th>
                                      <td><?php if($newsletter == 0) { echo 'Disabled'; } else { echo 'Enabled'; } ?></td>
                                  </tr>
                                  <tr>
                                      <th>Status</th>
                                      <td><?php if ($status == 0) { echo 'Disabled'; } else { echo 'Enabled'; }?></td>
                                  </tr>
                                  <tr>
                                      <th>Verified</th>
                                      <td><?php if ($approved == 0) { echo 'No'; } else { echo 'Yes'; }?></td>
                                  </tr>
                                  <tr>
                                      <th>Safe</th>
                                      <td><?php if ($safe == 0) { echo 'No'; } else { echo 'Yes'; }?></td>
                                  </tr>
                                  <tr>
                                      <th>Price Category</th>
                                      <td><?php echo $customer_category; ?></td>
                                  </tr>
                                  <tr>
                                      <th>Parent User Name</th>
                                      <td><?php echo $parent_user_name; ?></td>
                                  </tr>
                                  <tr>
                                      <th>Parent User Email</th>
                                      <td><?php echo $parent_user_email; ?></td>
                                  </tr>
                                  <tr>
                                      <th>Parent User Phone</th>
                                      <td><?php echo '+254 '.$parent_user_phone; ?></td>
                                  </tr>
                                  <tr>
                                      <th>Source</th>
                                      <td><?php echo $source; ?></td>
                                  </tr>
                                  <tr>
                                      <th>Latitude</th>
                                      <td><?php echo $latitude; ?></td>
                                  </tr>
                                  <tr>
                                      <th>Longitude</th>
                                      <td><?php echo $longitude; ?></td>
                                  </tr>
                                  <tr>
                                      <th>SAP Customer Number</th>
                                      <td><?php echo $SAP_customer_no; ?></td>
                                  </tr>
                                  <tr>
                                      <th>Temporary Password</th>
                                      <td><?php echo $temporary_password;  ?></td>
                                  </tr>
                                  
                                  <tr>
                                      <th>Account Manager</th>
                                      <td><?php if($account_manager_name != NULL) { echo $account_manager_name; } ?></td>
                                  </tr>

                                  <?php if($longitude != '' &&  $latitude != '' && $longitude != NULL &&  $latitude != NULL && $longitude != 0 &&  $latitude != 0) { ?>
 					<tr>
					<th>Location:</th>
					<td>
					<input type="button" class="btn btn-primary" onclick="initOrderedLocationMapLoad()" value="View Map">
					</td>
					</tr>
					<tr>
					<td colspan=2>
					<div class="" id="orderdlocationmap" style="height: 100%; min-height: 600px;">
		    		</div>		    		  
					</td>
				  </tr>
				  <?php } ?>
                              </tbody>
                          </table>  
                      </div>
                    <?php $address_row = 1; ?>
                    <?php foreach ($addresses as $address) { ?>
                    <div class="tab-pane" id="tab-address<?php echo $address_row; ?>">
                    <input type="hidden" name="address[<?php echo $address_row; ?>][address_id]" value="<?php echo $address['address_id']; ?>" />
                       <table class="table table-bordered">
                              <tbody>
                                  <tr>
                                      <th>Address Type</th>
                                      <td><?php echo $address['address_type']; ?></td>
                                  </tr>
                                  <tr>
                                      <th>Name</th>
                                      <td><?php echo $address['name']; ?></td>
                                  </tr>
                                  <tr>
                                      <th>House No. and Building Name</th>
                                      <td><?php echo $address['flat_number']; ?></td>
                                  </tr>
                                  <tr>
                                      <th>Your Location</th>
                                      <td><?php echo $address['landmark']; ?></td>
                                  </tr>
                                  <tr>
                                      <th>Default Address</th>
                                      <td><?php if($address_row == 1) { echo 'Yes'; } else { echo 'No'; } ?></td>
                                  </tr>
                              </tbody>
                          </table>  
                    </div>
                    <?php $address_row++; ?>
                    <?php } ?>
                  </div>
                </div>
              </div>
            </div>
            <?php if ($customer_id) { ?>
            <div class="tab-pane" id="tab-history">
              <div id="history"></div>
              <br />
            </div>
            <div class="tab-pane" id="tab-credit">
              <div id="credit"></div>
              <br />
            </div>
            <div class="tab-pane" id="tab-reward">
              <div id="reward"></div>
              <br />
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-reward-description"><?php echo $entry_description; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="description" value="" placeholder="<?php echo $entry_description; ?>" id="input-reward-description" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-points"><span data-toggle="tooltip" title="<?php echo $help_points; ?>"><?php echo $entry_points; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="points" value="" placeholder="<?php echo $entry_points; ?>" id="input-points" class="form-control" />
                </div>
              </div>
              <div class="text-right">
                <button type="button" id="button-reward" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i> <?php echo $button_reward_add; ?></button>
              </div>
            </div>
            
            <div class="tab-pane" id="tab-ip">
              <div id="ip"></div>
              <br />
            </div>

            <div class="tab-pane" id="tab-referral">
              <div id="referral"></div>
              <br />
            </div>
            <div class="tab-pane" id="tab-referral">
              <div id="referral"></div>
              <br />
            </div>


               <div class="tab-pane" id="tab-activity">
                <div id="activity"></div>
                <br />
            </div>

            <?php } ?>
            <div class="tab-pane" id="tab-sub-customer">
              <table class="table table-bordered">
            <thead>
            <tr>
              <th>Customer Name </th>
              <th>E-Mail</th>
              <th>Phone No</th>
              <th>Customer Group</th>
              <th>Status</th>
            </tr>
            </thead>
            <tbody>
            <?php if(count($sub_users)){?>
            <?php foreach($sub_users as $user){?>
            <tr>
            <td><?php echo $user['firstname'].' '.$user['lastname'];?></td>
            <td><?php echo $user['email'];?></td>
            <td><?php echo $user['telephone'];?></td>
            <td><?php echo $user['customer_group'];?></td>
            <td><?php echo ($user['approved']==0) ? 'Unverified': 'Verified'?></td>
            <!--<td>Action</td>-->
            </tr>
            <?php } ?>
            <?php }else{ ?>
            <tr style="text-align:center">
              <td colspan="5">No User found</td>
            </tr>
            <?php } ?>
            </tbody>
          </table>
            </div>
            
            <div class="tab-pane" id="tab-otp">
              <table class="table table-bordered">
            <thead>
            <tr>
              <th>Customer Name </th>
              <th>OTP</th>
              <th>Type</th>
              <th>Created At</th>
              <th>Updated At</th>
            </tr>
            </thead>
            <tbody>
            <?php if(count($customer_otp_list)) { ?>
            <?php foreach($customer_otp_list as $otp) { ?>
            <tr>
            <td><?php echo $firstname.' '.$lastname; ?></td>
            <td><?php echo $otp['otp'];?></td>
            <td><?php echo $otp['type'];?></td>
            <td><?php echo $otp['created_at'];?></td>
            <td><?php echo $otp['updated_at']; ?></td>
            </tr>
            <?php } ?>
            <?php } ?>
            <?php if(count($customer_otp_list_phone)) { ?>
            <?php foreach($customer_otp_list_phone as $otp_phone) { ?>
            <tr>
            <td><?php echo $firstname.' '.$lastname; ?></td>
            <td><?php echo $otp_phone['otp'];?></td>
            <td><?php echo $otp_phone['type'];?></td>
            <td><?php echo $otp_phone['created_at'];?></td>
            <td><?php echo $otp_phone['updated_at']; ?></td>
            </tr>
            <?php } ?>
            <?php } ?>
            <?php if(count($customer_otp_list) == 0 && count($customer_otp_list_phone) == 0) { ?>
            <tr style="text-align:center">
              <td colspan="5">No OTP Found</td>
            </tr>
            <?php } ?>
            </tbody>
          </table>
            </div> 
            
          </div>
        </div>
      </div>
    </div>
  </div>


  <script type="text/javascript"><!--
$('#history').delegate('.pagination a', 'click', function(e) {
  e.preventDefault();

  $('#history').load(this.href);
});

$('#history').load('index.php?path=sale/customer/history&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>');

$('#button-history').on('click', function(e) {
  e.preventDefault();

  $.ajax({
    url: 'index.php?path=sale/customer/history&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>',
    type: 'post',
    dataType: 'html',
    data: 'comment=' + encodeURIComponent($('#tab-history textarea[name=\'comment\']').val()),
    beforeSend: function() {
      $('#button-history').button('loading');
    },
    complete: function() {
      $('#button-history').button('reset');
    },
    success: function(html) {
      $('.alert').remove();

      $('#history').html(html);

      $('#tab-history textarea[name=\'comment\']').val('');
    }
  });
});
//--></script> 
  <script type="text/javascript"><!--
$('#credit').delegate('.pagination a', 'click', function(e) {
  e.preventDefault();

  $('#credit').load(this.href);
});

$('#credit').load('index.php?path=sale/customer/credit&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>');

$('#button-credit').on('click', function(e) {

  if(encodeURIComponent($('#tab-credit input[name=\'amount\']').val())==0)
  {
    alert("please enter valid amount");
    return;
  }
  e.preventDefault();

        $.ajax({
    url: 'index.php?path=sale/customer/credit&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>',
    type: 'post',
    dataType: 'html',
    data: 'description=' + encodeURIComponent($('#tab-credit input[name=\'description\']').val()) + '&amount=' + encodeURIComponent($('#tab-credit input[name=\'amount\']').val()),
    beforeSend: function() {
      $('#button-credit').button('loading');
         },
    complete: function() {
      $('#button-credit').button('reset');
    },
    success: function(html) {
        $('.alert').remove();

      $('#credit').html(html);

       $('#tab-credit input[name=\'amount\']').val('');
      $('#tab-credit input[name=\'description\']').val('');
    }
  });
});
//--></script> 

  <script type="text/javascript"><!--
$('#reward').delegate('.pagination a', 'click', function(e) {
  e.preventDefault();
  
  $('#reward').load(this.href);
});

$('#reward').load('index.php?path=sale/customer/reward&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>');

$('#referral').delegate('.pagination a', 'click', function(e) {
  e.preventDefault();

 $('#referral').load(this.href);
});

$('#referral').load('index.php?path=sale/customer/referral&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>');

$('#button-reward').on('click', function(e) {
  if(encodeURIComponent($('#tab-reward input[name=\'description\']').val())=='')
  {
    alert("please enter valid description");
    return;
  }
  if(encodeURIComponent($('#tab-reward input[name=\'points\']').val())=='')
  {
    alert("please enter valid points");
    return;
  }  
  e.preventDefault();

  $.ajax({
    url: 'index.php?path=sale/customer/reward&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>',
    type: 'post',
    dataType: 'html',
    data: 'description=' + encodeURIComponent($('#tab-reward input[name=\'description\']').val()) + '&points=' + encodeURIComponent($('#tab-reward input[name=\'points\']').val()),
    beforeSend: function() {
      $('#button-reward').button('loading');
    },
    complete: function() {
      $('#button-reward').button('reset');
    },
    success: function(html) {
      $('.alert').remove();

      $('#reward').html(html);

       $('#tab-reward input[name=\'points\']').val('');
      $('#tab-reward input[name=\'description\']').val('');
       }
  });
});

$('#ip').delegate('.pagination a', 'click', function(e) {
  e.preventDefault();

 $('#ip').load(this.href);
});


$('#ip').load('index.php?path=sale/customer/customerviewip&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>');

$('body').delegate('.button-ban-add', 'click', function() {
  var element = this;

  $.ajax({
    url: 'index.php?path=sale/customer/addbanip&token=<?php echo $token; ?>',
    type: 'post',
    dataType: 'json',
    data: 'ip=' + encodeURIComponent(this.value),
    beforeSend: function() {
      $(element).button('loading');
    },
    complete: function() {
      $(element).button('reset');
    },
    success: function(json) {
      $('.alert').remove();

      if (json['error']) {
         $('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');

        $('.alert').fadeIn('slow');
      }

      if (json['success']) {
        $('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');

        $(element).replaceWith('<button type="button" value="' + element.value + '" class="btn btn-danger btn-xs button-ban-remove"><i class="fa fa-minus-circle"></i> <?php echo $text_remove_ban_ip; ?></button>');
      }
    }
  });
});



$('#activity').delegate('.pagination a', 'click', function(e) {
  e.preventDefault();

 $('#activity').load(this.href);
});


$('#activity').load('index.php?path=sale/customer/customerviewactivity&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>');



$('.date_dob').datetimepicker({
  pickTime: false,
  pickDate: true,
  format: 'DD/MM/YYYY',
  todayHighlight: true,
  autoclose: true,
  Default: 'MMMM YYYY'
});

$('.date').datetimepicker({
  pickTime: false
});

$('.datetime').datetimepicker({
  pickDate: true,
  pickTime: true
});

$('.time').datetimepicker({
  pickDate: false
}); 
//--></script></div>
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

<style>
  #tab-general select, #tab-general textarea {
    max-width: 220px !important;
  }
  #tab-general input[type="radio"]{
      margin-left: 0 !important;
  }
</style>

<?php echo $footer; ?>

<style>
  #tab-general select, #tab-general textarea {
    max-width: 220px !important;
  }
  #tab-general input[type="radio"]{
      margin-left: 0 !important;
  }
</style>
 

    <style type="text/css">
        .pac-container {
          z-index: 99999999;
        }
        #map * {
            overflow:visible;
        }
    </style>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="../front/ui/theme/mvgv2/js/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script type="text/javascript" src="../front/ui/theme/mvgv2/js/side-menu-script.js"></script>
    <script src="../front/ui/theme/mvgv2/js/jquery.maskedinput.js" type="text/javascript"></script>
    <script type="text/javascript" src="../front/ui/theme/mvgv2/js/html5lightbox.js"></script>
    <script type="text/javascript" src="../front/ui/theme/mvgv2/js/jquery.sticky.js"></script>
    <script type="text/javascript" src="../front/ui/theme/mvgv2/js/header-sticky.js"></script>

    <script type="text/javascript" src="https://maps.google.com/maps/api/js?key=<?= $this->config->get('config_google_api_key') ?>&libraries=places"></script>

    <script type="text/javascript" src="../admin/ui/javascript/map-picker/js/locationpicker.jquery.js?v=2.2"></script>
<?php if ($kondutoStatus) { ?>

<script src="https://i.k-analytix.com/konduto.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gmaps.js/0.4.25/gmaps.min.js"></script>
<script type="text/javascript" src="ui/javascript/app-maps-google-delivery.js"></script>

<script type="text/javascript">

      var __kdt = __kdt || [];

      var public_key = '<?php echo $konduto_public_key ?>';

      console.log("public_key");
      console.log(public_key);
    __kdt.push({"public_key": public_key}); // The public key identifies your store
    __kdt.push({"post_on_load": false});   
      (function() {
               var kdt = document.createElement('script');
               kdt.id = 'kdtjs'; kdt.type = 'text/javascript';
               kdt.async = true;    kdt.src = 'https://i.k-analytix.com/k.js';
               var s = document.getElementsByTagName('body')[0];

               console.log(s);
               s.parentNode.insertBefore(kdt, s);
                })();

                var visitorID;
        (function() {
          var period = 300;
          var limit = 20 * 1e3;
          var nTry = 0;
          var intervalID = setInterval(function() {
          var clear = limit/period <= ++nTry;

          console.log("visitorID trssy");
          if (typeof(Konduto.getVisitorID) !== "undefined") {
                   visitorID = window.Konduto.getVisitorID();
                   clear = true;
          }
          console.log("visitorID clear");
          if (clear) {
         clearInterval(intervalID);
        }
        }, period);
        })(visitorID);


        var page_category = 'my-address-page';
        (function() {
          var period = 300;
          var limit = 20 * 1e3;
          var nTry = 0;
          var intervalID = setInterval(function() {
                   var clear = limit/period <= ++nTry;
                   if (typeof(Konduto.sendEvent) !== "undefined") {

                    Konduto.sendEvent (' page ', page_category); //Programmatic trigger event
                        clear = true;
                   }
                 if (clear) {
                clearInterval(intervalID);
             }
            },
            period);
            })(page_category);
</script>

<?php } ?>
<script type="text/javascript">
        function initOrderedLocationMapLoad () {
                const myLatLng = { lat: <?php echo $latitude ?>, lng:<?php echo $longitude ?> };
                const map = new google.maps.Map(document.getElementById("orderdlocationmap"), {
                        zoom: 4,
                        center: myLatLng,
                });
                new google.maps.Marker({
                        position: myLatLng,
                        map,
                        title: "Ordered Location!",
                });
                }
</script>
</body>

</html>
