<?php echo $header; ?>

                            <div class="col-md-9 nopl">
                                <div class="dashboard-address-content">
                                    <div class="row">
                                        <div class="col-md-12"><h2><?= $text_delivery_address?></h2> <br></div>
                                    </div>
                                    <div class="row" id="address-panel">
                                        <?php if($addresses) { ?>
                                         
                                            <?php foreach($addresses as $address){ ?>
                                                <div class="col-md-6">
                                                    <div class="address-block">
                                                        <!-- <h3 class="address-locations"><?= $address['address_type'] ?></h3> -->
                                                         <h3 class="address-locations">
                                                            <?php if($address['address_type'] == 'Home') { ?>
                                                                <?= $text_home_address ?>

                                                            <?php } elseif($address['address_type'] == 'Office') { ?>
                                                                    <?= $text_office ?>
                                                            <?php } else {?>
                                                                    <?= $text_other ?>
                                                            <?php }?>
                                                        </h3>

                                                        <h4 class="address-name"><?= $address['name'] ?></h4>
                                                        <p><?php echo $address['address'] ?><br>
                                                            <?php echo $address['flat_number'].', ' ?><br>
                                                            <?php echo $address['building_name'] ?><br>
                                                            <?php echo $address['city']; ?>
                                                            </p>
                                                            <a  href="#" onclick="editAddressModal(<?= $address['address_id'] ?>)" type="button" data-toggle="modal" data-target="#editAddressModal" class="btn btn-default"> <?php echo $button_edit; ?></a>
                                                            <a  href="<?php echo $address['delete']; ?>" id="delete-address" class="btn btn-primary" onclick="return confirm('Are you sure?')"><?php echo $button_delete; ?></a>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        <?php } ?>
                                    </div>
                                    <div class="col-md-12"><a href="#" type="button" class="btn-link text_green" data-toggle="modal" data-target="#addressModal"><i class="fa fa-plus-circle"></i> <?= $text_add_new_address?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
   <?php echo $footer; ?>

    <div class="addressModal">
        <div class="modal fade" id="addressModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <div class="row">
                            <div class="col-md-12">
                                <h2><?= $text_add_New_Address?></h2>
                            </div>
                            <div id="address-message" class="col-md-12" style="color: red">
                            </div>
                            <div id="address-success-message" style="color: green">
                            </div>
                            <div class="addnews-address-form">
                                    <!-- Multiple Radios (inline) -->
                                <form id="new-address-form">
                                     
                                    <div class="form-group">
                                        <label class="col-md-12 control-label" for="address"></label>
                                        <div class="col-md-12">
                                            <div class="select-locations">
                                                <label class="control control--radio"><?= $text_home_address?>
                                                    <input type="radio" name="modal_address_type" value="home" checked="checked" />
                                                    <div class="control__indicator"></div>
                                                </label>
                                                <label class="control control--radio"><?= $text_office?>
                                                    <input type="radio" value="office" name="modal_address_type" />
                                                    <div class="control__indicator"></div>
                                                </label>
                                                <label class="control control--radio"><?= $text_other?>
                                                    <input type="radio" value="other" name="modal_address_type" />
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Text input-->
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label class="control-label" for="name"><?= $text_name?></label>
                                            <input id="name" name="modal_address_name" type="text"  value="<?= $full_name?>" class="form-control input-md" required="">
                                        </div>
                                    </div>
                                   
                                    <input id="street" name="modal_address_street" type="hidden"  class="form-control input-md" required="">

                                    <input id="picker_city_name" name="picker_city_name" type="hidden" value="">
                                    


                                    <!-- Text input-->
                                    <div class="form-group">
                                        <label class="col-md-12 control-label" for="flat"><?= $text_flat_house_office?></label>
                                        <div class="col-md-12">
                                            <input id="flat" name="modal_address_flat" type="text"  class="form-control input-md" required="" placeholder="45, Sunshine Apartments">
                                        </div>
                                    </div>
                                    
                                    <!-- Text input-->

                                    <?php if($this->config->get('config_store_location') == 'zipcode') { ?>
                                        <div class="form-group">
                                            <label class="col-md-12 control-label" for="Locality"><?= $text_locality?></label>

                                            <?php if($check_address) { ?>

                                                <div class="col-md-12">
                                                    <div class="input-group">

                                                        <input  name="modal_address_locality" type="text"  class="form-control input-md LocalityId" required="">                                                    
                                                        <span class="input-group-btn">

                                                            <button id="locateme" class="btn btn-default disabled" style=" color: #333;background-color: #fff;border-color: #ccc;line-height: 2.438571; " type="button" data-toggle="modal" onclick="openGMap()" data-target="#GMapPopup"  ><i class="fa-crosshairs fa"></i> <?= $locate_me ?> </button>

                                                        </span>
                                                    </div>
                                                </div>
                                            <?php } else { ?>
                                                <div class="col-md-12">
                                                    <input  name="modal_address_locality" type="text"  class="form-control input-md LocalityId" required="">
                                                </div>
                                            <?php } ?>
                                        </div>

                                    <?php } else { ?>
                                        
                                        <div class="form-group">
                                            <label class="col-md-12 control-label" for="Locality"><?= $text_locality?></label>

                                            <?php if($check_address) { ?>

                                                <div class="col-md-12">
                                                    <div class="input-group">

                                                        <input  name="modal_address_locality" type="text"  class="form-control input-md LocalityId" required="">                                                    
                                                        <span class="input-group-btn">

                                                            <button id="locateme" class="btn btn-default disabled" style="height:38px;color: #333;background-color: #fff;border-color: #ccc;line-height: 2.438571; " type="button" data-toggle="modal" onclick="openGMap()" data-target="#GMapPopup"  ><i class="fa-crosshairs fa"></i> <?= $locate_me ?> </button>

                                                        </span>
                                                    </div>
                                                </div>
                                            <?php } else { ?>
                                                <div class="col-md-12">
                                                    <input  name="modal_address_locality" type="text"  class="form-control input-md LocalityId" required="">
                                                </div>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>

                                    <?php if($this->config->get('config_store_location') == 'zipcode') { ?>
                                        <div class="form-group">
                                            <label class="col-md-12 control-label" for="zipcode"><?= $label_zipcode ?></label>
                                            <div class="col-md-12">
                                                <input  id="shipping_zipcode_input" type="text" value="<?php echo $zipcode; ?>" name="shipping_zipcode" class="form-control input-md"  required="">
                                            </div>
                                        </div>
                                    <?php } else { ?>
                                        <input id="shipping_zipcode" type="hidden" value="<?php echo $zipcode; ?>" name="shipping_zipcode">
                                    <?php } ?>
                                    


  <div class="form-group">
            <label class="col-md-4 control-label" style="top:8px" for="isdefault_address">Default Address
            </label>
            <div class="col-sm-4" >
            
                <input id="isdefault_address" name="isdefault_address"  type="checkbox" value="<?php echo $isdefault_address; ?>"   class="form-control input-md" >
            


           </div>
        </div>


                                    
                                    <!-- Button -->
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <button id="singlebutton" name="singlebutton" type="button" class="btn btn-primary" onclick="saveInAddressBook()"><?= $text_save?></button>
                                            <button type="button" class="btn btn-grey  cancelbut" data-dismiss="modal"><?= $text_close?></button>
                                        </div>
                                    </div>

                                  
                                    
                                    <!-- <div id="us1" style="width: 100%; height: 400px;"></div>  -->

                                    <input type="hidden" name="latitude" value="<?= $latitude ?>" />
                                    <input type="hidden" name="longitude" value="<?= $longitude ?>" />
                                    

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

   

    <style type="text/css">
        .pac-container {
          z-index: 99999999;
        }
        #map * {
            overflow:visible;
        }
    </style>
    
    <div class="editAddressModal">
        <div class="modal fade" id="editAddressModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <div class="row">
                            <div class="col-md-12">
                                <h2><?= $text_edit_address_new?></h2></div>
                            <div id="edit-address-message" class="col-md-12" style="color: red">
                            </div>
                            <div id="edit-address-success-message" style="color: green">
                            </div>

                            <div class="edit-address-form-panel">
                                    <!-- Edit form here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="GMapPopup">
        <div class="modal fade" id="GMapPopup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <div class="row">

                            <div class="col-md-12">
                                <center> 
                                    <h2><?= $text_your_location ?> </h2>
                                </center>
                            </div>
                        </div>

                        <div id="wrapper">
                           
                            <div id="us1" style="width: 100%; height: 400px;"></div> 
                            <div id="us2" style="width: 100%; height: 400px;display: none"></div>
                           
                           <div id="over_map">

                                <div class="input-group">

                                    <input  name="modal_address_locality" type="text" id="gmap-input" class="form-control input-md LocalityId LocalityId2" required="" >                                                    
                                    <span class="input-group-btn">

                                        <button class="btn btn-default" id="detect_location" style="color: #333;background-color: #fff;border-color: #ccc;width: 150px;line-height: 2.438571; " type="button"  onclick="getLocation()"><i class="fa fa-location-arrow"></i> <?= $detect_location ?></button>

                                    </span>
                                </div>
                                
                           </div>
                        </div>
                        
                        <style>
                           #wrapper { position: relative; }
                           #over_map { position: absolute; top: 10px; padding-right: 12px;
                                        padding-left: 12px;  z-index: 99; width: 100%}
                        </style>

                        <script type="text/javascript">
                            

                            
                        </script>
                        <div class="row" style="margin-top: 10px;">
                            
                            <center>
                                <button id="saveLatLng" type="button" class="btn btn btn-primary" onclick="saveLatLng()"><?= $text_ok?></button>
                            </center>    
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="<?= $base?>front/ui/theme/mvgv2/js/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="<?= $base?>front/ui/theme/mvgv2/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?= $base?>front/ui/theme/mvgv2/js/side-menu-script.js"></script>
    <script src="<?= $base?>front/ui/theme/mvgv2/js/jquery.maskedinput.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="<?= $base?>front/ui/theme/mvgv2/js/html5lightbox.js"></script>
    <script type="text/javascript" src="<?= $base?>front/ui/theme/mvgv2/js/jquery.sticky.min.js"></script>
    <script type="text/javascript" src="<?= $base?>front/ui/theme/mvgv2/js/header-sticky.js"></script>

    <script type="text/javascript" src="https://maps.google.com/maps/api/js?key=<?= $this->config->get('config_google_api_key') ?>&libraries=places"></script>

    <script type="text/javascript" src="<?= $base?>admin/ui/javascript/map-picker/js/locationpicker.jquery.js?v=2.2"></script>
    

    <script type="text/javascript">

            console.log("error_email");
            

            
            $('#us1').locationpicker({
                location: {
                    latitude: <?= $latitude?$latitude:0 ?>,
                    longitude: <?= $longitude?$longitude:0 ?>
                },  
                radius: 0,
                inputBinding: {
                    latitudeInput: $('input[name="latitude"]'),
                    longitudeInput: $('input[name="longitude"]'),
                    locationNameInput: $('.LocalityId')
                },
                enableAutocomplete: true,
                zoom:13

            }); 


            function saveLatLng() {
                $('#GMapPopup').modal('hide');

                $('.LocalityId').val($('.LocalityId').val());
            }

            console.log("ehjdhj");
            jQuery(function($){
                console.log("mask");
               $("#shipping_zipcode_input").mask("<?= $zipcode_mask_number ?>",{autoclear:false,placeholder:"<?= $zipcode_mask ?>"});
            });

            function saveInAddressBook() {

                console.log($('#new-address-form').serialize());
                console.log("saveInAddressBook");
                $('.alert').remove();
                $('#save-address').button('saving');

                $('.help-block').hide();
                $('.has-error').removeClass('has-error');

                $error = false;
                var shipping_address = $('input[name="modal_address_street"]').val();
                var shipping_zipcode = $('input[name="shipping_zipcode"]').val();
                var shipping_city_id = $('input[name="shipping_city_id"]').val();
                var landmark = $('input[name="modal_address_locality"]').val();
                var building_name = $('input[name="modal_address_name"]').val();
                var flat_number = $('input[name="modal_address_flat"]').val();
                var address_type = $('input[name="modal_address_type"]:checked').val();
                //validate all fields

                if (landmark.length <= 0) {
                    $error = true;
                    $('input[name="modal_address_locality"]').parents('.form-group').addClass('has-error').find('.help-block').show();
                }

                if (building_name.length <= 0) {
                    $error = true;
                    $('input[name="modal_address_name"]').parents('.form-group').addClass('has-error').find('.help-block').show();
                }

                if (flat_number.length <= 0 ) {
                    $error = true;
                    $('input[name="modal_address_flat"]').parents('.form-group').addClass('has-error').find('.help-block').show();
                }

                <?php if($this->config->get('config_store_location') == 'zipcode') { ?>

                    if (shipping_zipcode.length <= 0) {
                        $error = true;
                        $('input[name="shipping_zipcode"]').parents('.form-group').addClass('has-error').find('.help-block').show();
                    }
                    
                <?php } ?>

                
                
                console.log(shipping_address+"**"+landmark+"**"+building_name+"**"+flat_number+"**"+address_type+"**");
                
                if (!$error) {
                //if (false) {

                    $valid_address = 0;
                    $.ajax({
                        url: 'index.php?path=account/address/addInAddressBookFromAccount',
                        type: 'post',
                        async: false,
                        data: $('#new-address-form').serialize(),
                        dataType: 'json',
                        cache: false,
                        success: function(json) {

                            console.log(json);
                            console.log("address add success");
                            if (json.status == 0) {
                                $('#address-message').html(json['message']);
                                $('#address-success-message').html('');
                               
                            } else {
                                console.log("address add success else");
                                $('#address-panel').html(json.html);
                                $('#addressModal').modal('hide');
                                 location=location;
                                return false;
                            }
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            //alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                            $('#button-confirm').button('reset');
                            return false;
                        }
                    });
                    return true;
                } else {
                    /*$('html, body').animate({
                        scrollTop: 0
                    }, 'slow');
                    $('#button-confirm').button('reset');*/
                    return false;
                }
            }

            function editAddressBook() {
                console.log("editAddressBook");
                $('.alert').remove();
                $('#save-address').button('saving');

                $('.help-block').hide();
                $('.has-error').removeClass('has-error');

                $error = false;
                var shipping_address = $('input[name="edit_modal_address_street"]').val();
                var shipping_zipcode = $('input[name="shipping_zipcode"]').val();
                var shipping_city_id = $('input[name="shipping_city_id"]').val();
                var landmark = $('input[name="edit_modal_address_locality"]').val();
                var building_name = $('input[name="edit_modal_address_name"]').val();
                var flat_number = $('input[name="edit_modal_address_flat"]').val();
                var address_type = $('input[name="edit_modal_address_type"]:checked').val();
                //validate all fields

                

                if (landmark.length <= 0) {
                    $error = true;
                    $('input[name="edit_modal_address_locality"]').parents('.form-group').addClass('has-error').find('.help-block').show();
                }
                
                if (building_name.length <= 0) {
                    $error = true;
                    $('input[name="edit_modal_address_name"]').parents('.form-group').addClass('has-error').find('.help-block').show();
                }

                /*if (flat_number.length <= 0 ) {
                    $error = true;
                    $('input[name="edit_modal_address_flat"]').parents('.form-group').addClass('has-error').find('.help-block').show();
                }*/

                /*if (shipping_zipcode.length <= 0) {
                    $error = true;
                    $('input[name="shipping_zipcode"]').parents('.form-group').addClass('has-error').find('.help-block').show();
                }*/

                console.log(shipping_address+"**"+landmark+"**"+building_name+"**"+flat_number+"**"+address_type);
                
                if (!$error) {

                    $valid_address = 0;
                    $.ajax({
                        url: 'index.php?path=account/address/editAddress',
                        type: 'post',
                        async: false,
                        data: $('#edit-address-form').serialize(),
                        dataType: 'json',
                        cache: false,
                        success: function(json) {

                            console.log(json);
                            console.log("address add success");
                            if (json.status == 0) {
                                $('#edit-address-message').html(json['message']);
                                $('#edit-address-success-message').html('');
                                
                            } else {
                                console.log("address add success else");
                                $('#address-panel').html(json.html);
                                $('#editAddressModal').modal('hide'); 
                                location = location;                               
                                return false;
                            }

                            location = location;
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            $('#button-confirm').button('reset');
                            return false;
                        }
                    });
                    return true;
                } else {
                    /*$('html, body').animate({
                        scrollTop: 0
                    }, 'slow');
                    $('#button-confirm').button('reset');*/
                    return false;
                }
            }


            function editAddressModal($address_id) {

                $('#edit-address-message').html('');
                $('#edit-address-success-message').html('');
                console.log($address_id);
                console.log("address_id");
                $.ajax({
                    url: 'index.php?path=account/address/getAddress',
                    type: 'post',
                    async: false,
                    data: {address_id: $address_id},
                    dataType: 'json',
                    cache: false,
                    success: function(json) {

                        console.log(json);
                        $('.edit-address-form-panel').html(json['html']);

                        $('#us2').locationpicker({
                            location: {
                                latitude: json['latitude'],
                                longitude: json['longitude']
                            },  
                            radius: 0,
                            inputBinding: {
                                latitudeInput: $('input[name="latitude"]'),
                                longitudeInput: $('input[name="longitude"]'),
                                locationNameInput: $('.edit_LocalityId')
                            },
                            enableAutocomplete: true,
                            zoom:13,

                        });
                        
                        console.log($('#us1').locationpicker('location'));

                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                        $('#button-confirm').button('reset');
                        return false;
                    }
                });
            }
    </script>

<?php if ($kondutoStatus) { ?>

<script src="https://i.k-analytix.com/konduto.min.js" type="text/javascript"></script>

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

    function openGMap() {

        $("#GMapPopup").on('shown.bs.modal', function () {
            $('div#GMapPopup').show();
            $('#us1').locationpicker('autosize');

            console.log("efre");
            
        });
    }

    function GMapPopupInput() {

        var acInputs = document.getElementsByClassName("LocalityId2");

        

        var autocomplete = new google.maps.places.Autocomplete(acInputs);
        
        google.maps.event.addListener(autocomplete, 'place_changed', function () {
                
            console.log("latitude");
            console.log(autocomplete);
            $('#us1').locationpicker({
                location: {
                    latitude: position.coords.latitude,
                    longitude: position.coords.longitude
                },  
                radius: 0,
                inputBinding: {
                    latitudeInput: $('input[name="latitude"]'),
                    longitudeInput: $('input[name="longitude"]'),
                    locationNameInput: $('.LocalityId2')
                },
                enableAutocomplete: true,
                zoom:13
                
            });
        });       
    }

    

    function initialize() {

        var acInputs = document.getElementsByClassName("LocalityId");

        for (var i = 0; i < acInputs.length; i++) {

            var autocomplete = new google.maps.places.Autocomplete(acInputs[i]);
            
            google.maps.event.addListener(autocomplete, 'place_changed', function () {
                    


                
            });
        }
    }

    function getLocation() {

        $('#detect_location').html('<i class="fa fa-location-arrow"></i> <?= $text_locating ?>');
        console.log("getLocation");

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }

    function showPosition(position) {
        //var latlon = position.coords.latitude + "," + position.coords.longitude;
        console.log("showPosition");
        console.log(position);

        

        $('#us1').locationpicker({
            location: {
                latitude: position.coords.latitude,
                longitude: position.coords.longitude
            },  
            radius: 0,
            inputBinding: {
                latitudeInput: $('input[name="latitude"]'),
                longitudeInput: $('input[name="longitude"]'),
                locationNameInput: $('.LocalityId')
            },
            enableAutocomplete: true,
            zoom:13
        });

        console.log($('#us1').locationpicker('location'));

        $('#detect_location').html('<i class="fa fa-location-arrow"></i> <?= $detect_location ?>');
    }

    //initialize();

    $(document.body).on('mousedown', '.pac-container .pac-item', function(e) {
        console.log('click fired');
        $('#locateme').removeClass('disabled');
    });

    $(document.body).on('change', '.LocalityId', function(e) {
        console.log('change LocalityId');

        var address= $('#us1').locationpicker('location');
        console.log(address);

        /*if(address.addressComponents.streetName && address.addressComponents.streetNumber ) {
            $('#street').val(address.addressComponents.streetNumber+' '+address.addressComponents.streetName);
            $('#edit-street').val(address.addressComponents.streetNumber+' '+address.addressComponents.streetName);
        } else {
            $('#street').val(address.addressComponents.streetName);
            $('#edit-street').val(address.addressComponents.streetName);
        }*/

        

        if(!$('.LocalityId').val().length) {
            $('#locateme').addClass('disabled');
        }
    });

    $(document.body).on('change', '.edit_LocalityId', function(e) {
        console.log('change edit_LocalityId');

        var address= $('#us2').locationpicker('location');
        console.log(address);

        //$('.LocalityId').val();
        if(address.addressComponents.streetName && address.addressComponents.streetNumber ) {
            $('#street').val(address.addressComponents.streetNumber+' '+address.addressComponents.streetName);
            $('#edit-street').val(address.addressComponents.streetNumber+' '+address.addressComponents.streetName);
        } else {
            $('#street').val(address.addressComponents.streetName);
            $('#edit-street').val(address.addressComponents.streetName);
        }

        

        if(!$('.LocalityId').val().length) {
            $('#locateme').addClass('disabled');
        }
    });

var autocomplete;
autocomplete = new google.maps.places.Autocomplete((document.getElementById('gmap-input'));
google.maps.event.addListener(autocomplete, 'place_changed', function () {
                
            console.log("latitude");
            console.log(autocomplete);
            $('#us1').locationpicker({
                location: {
                    latitude: position.coords.latitude,
                    longitude: position.coords.longitude
                },  
                radius: 0,
                inputBinding: {
                    latitudeInput: $('input[name="latitude"]'),
                    longitudeInput: $('input[name="longitude"]'),
                    locationNameInput: $('.LocalityId2')
                },
                enableAutocomplete: true,
                zoom:13
                
            });
});        
</script>
</body>

</html>
