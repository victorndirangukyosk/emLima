<form id="edit-address-form">
    <input type="hidden" value="<?php echo $city_id; ?>" name="shipping_city_id" id="shipping_city_id">
    <input type="hidden" value="<?php echo $zipcode; ?>" name="shipping_zipcode" id="shipping_zipcode">
    <input type="hidden" value="<?php echo $address_id; ?>" name="address_id" id="address_id">
    <div class="form-group">
        <label class="col-md-12 control-label" for="address"></label>
        <div class="col-md-12">
            <div class="select-locations">
                <label class="control control--radio"><?= $text_home_address?>
                    
                    <?php if($address_type == 'home') {?> 
                        <input type="radio" name="edit_modal_address_type" value="home" checked="checked" />
                    <?php } else {?>
                    <input type="radio" name="edit_modal_address_type" value="home"/>
                    <?php } ?>
                    <div class="control__indicator"></div>
                </label>
                <label class="control control--radio"><?= $text_office?>
                    <?php if($address_type == 'office'){ ?> 
                        <input type="radio" name="edit_modal_address_type" value="office" checked="checked" />
                    <?php } else {?>
                    <input type="radio" name="edit_modal_address_type" value="office"/>
                    <?php } ?>
                    <div class="control__indicator"></div>
                </label>
                <label class="control control--radio"><?= $text_other?>
                    <?php if($address_type == 'other'){ ?> 
                        <input type="radio" name="edit_modal_address_type" value="other" checked="checked" />
                    <?php } else { ?>
                    <input type="radio" name="edit_modal_address_type" value="other"/>
                    <?php } ?>
                    <div class="control__indicator"></div>
                </label>
            </div>
        </div>
    </div>
    <!-- Text input-->
    <div class="form-group">
        <div class="col-md-12">
            <label class="control-label" for="name"><?= $text_name?></label>
            <input id="name" name="edit_modal_address_name" type="text" value="<?= $name ?>"  class="form-control input-md" required="">
        </div>
    </div>
    
    <!-- Text input-->
    
    <input id="edit-street" name="edit_modal_address_street" type="hidden" value="<?= $building_name ?>"  class="form-control input-md" required="">

    <input id="picker_city_name" name="picker_city_name" type="hidden" value="">

    <!-- Text input-->
    <div class="form-group">
        <label class="col-md-12 control-label" for="flat"><?= $text_flat_house_office?></label>
        <div class="col-md-12">
            <input id="flat" name="edit_modal_address_flat" type="text" value="<?= $flat_number ?>" class="form-control input-md" required="" placeholder="45, Sunshine Apartments">
        </div>
    </div>
    
    <!-- Text input-->
    <div class="form-group">
        <label class="col-md-12 control-label" for="Locality"><?= $text_locality?></label>
        <!-- <div class="col-md-12">
            

            <?php if($check_address) { ?>
                <input id="Locality" name="edit_modal_address_locality" type="text" value="<?= $landmark ?>"  class="form-control input-md" required="">

                <button type="button" data-toggle="modal" data-target="#GMapPopup"  >
                    <i class="fa-crosshairs fa"></i> Detect Location
                </button>

            <?php } else { ?>
                <input id="Locality" name="edit_modal_address_locality" type="text" value="<?= $landmark ?>"  class="form-control input-md" required="">
            <?php } ?>
                                            
        </div> -->
        <?php if($check_address) { ?>
            <div class="col-md-12">
                <div class="input-group">

                    <input  name="edit_modal_address_locality" type="text"  value="<?= $landmark ?>" class="form-control input-md edit_LocalityId" required="" autocomplete="off">                                                    
                    <span class="input-group-btn">

                        <button class="btn btn-default" style="color: #333;background-color: #fff;border-color: #ccc;line-height: 2.438571; " type="button" data-toggle="modal" onclick="openGMap()" data-target="#GMapPopup"  ><i class="fa-crosshairs fa"></i> <?= $locate_me ?> </button>

                    </span>
                </div>
            </div>

        <?php } else { ?>
            <div class="col-md-12">
                <input  name="edit_modal_address_locality" type="text"  value="<?= $landmark ?>" class="form-control input-md edit_LocalityId" required="">
            </div>
        <?php } ?>

    </div>

    <?php if($this->config->get('config_store_location') == 'zipcode') { ?>
        <div class="form-group">
            <label class="col-md-12 control-label" for="zipcode"><?= $label_zipcode ?></label>
            <div class="col-md-12">
                <input id="shipping_zipcode" type="text" value="<?php echo $zipcode; ?>" name="edit_shipping_zipcode" class="form-control input-md" disabled="true">
            </div>
        </div>
    <?php } else { ?>
        <input id="shipping_zipcode" type="hidden" value="00100" name="shipping_zipcode">
    <?php } ?>

    <!-- Button -->
    <div class="form-group">
        <div class="col-md-12">
            <button id="singlebutton" name="singlebutton" type="button" class="btn btn-primary" onclick="editAddressBook()"><?= $text_save?></button>
            <button type="button" class="btn btn-grey" data-dismiss="modal"><?= $text_close?></button>
        </div>
    </div>

    <!-- <input type="hidden" name="edit_latitude" value="<?= $latitude ?>" />
    <input type="hidden" name="edit_longitude" value="<?= $longitude ?>" /> -->
    
    <input type="hidden" name="latitude" value="<?= $latitude ?>" />
    <input type="hidden" name="longitude" value="<?= $longitude ?>" />

    <script type="text/javascript">

        /*jQuery(function($){
            console.log("mask");
           $("#shipping_zipcode").mask("<?= $zipcode_mask_number ?>",{autoclear:false,placeholder:"<?= $zipcode_mask ?>"});
        });*/
    
        $('#us2').locationpicker({
            location: {
                latitude: <?= $latitude?$latitude:0 ?>,
                longitude: <?= $longitude?$longitude:0 ?>
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
    </script>
</form>