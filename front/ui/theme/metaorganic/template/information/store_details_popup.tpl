<div class="store-company">
    <img src="<?= $store_info['image']?>" class="img-circle">
</div>

<div class="rows">
    <h1><?= $store_info['name'] ?></h1>
</div>

<div class="row" style="margin-top: 10px;">
    <div class="col-md-12">
        <h3> <?= $store_info['about_us'] ?> </h3>
    </div>
</div>



    <div class="row">

        <div class=""></div>
        <div class="" style="display: inline-block;">
            
            <span >
                <fieldset class="show_rating">
                    <input type="radio" id="star5" name="show_rating" disabled="disabled" value="5"/>

                    <label class = "full" for="star5" title="Awesome - 5 stars"></label>


                    <input type="radio" id="star4half" name="show_rating" disabled="disabled" value="4.5" />

                    <label class="half" for="star4half" title="Pretty good - 4.5 stars"></label>

                    <input type="radio" id="star4" name="show_rating" disabled="disabled" value="4"/>

                    <label class = "full" for="star4" title="Pretty good - 4 stars"></label>

                    <input type="radio" id="star3half" name="show_rating" disabled="disabled" value="3.5"/>

                    <label class="half" for="star3half" title="Meh - 3.5 stars"></label>

                    <input type="radio" id="star3" name="show_rating" disabled="disabled" value="3"/>

                    <label class = "full" for="star3" title="Meh - 3 stars"></label>

                    <input type="radio" id="star2half" name="show_rating" disabled="disabled" value="2.5"/>

                    <label class="half" for="star2half" title="Kinda bad - 2.5 stars"></label>

                    <input type="radio" id="star2" name="show_rating" disabled="disabled" value="2"/>

                    <label class = "full" for="star2" title="Kinda bad - 2 stars"></label>


                    <input type="radio" id="star1half" name="show_rating" disabled="disabled" value="1.5" />

                    <label class="half" for="star1half" title="Meh - 1.5 stars"></label>


                    <input type="radio" id="star1" name="show_rating" disabled="disabled" value="1"/>

                    <label class = "full" for="star1" title="Sucks big time - 1 star"></label>


                    <input type="radio" id="starhalf" name="show_rating" disabled="disabled" value=".5"/>

                    <label class="half" for="starhalf" title="Sucks big time - 0.5 stars"></label>
                </fieldset>
            </span>

            <span style="float: left;margin-left: 2px;margin-top: 10px;"> (<?= $review_count; ?>) </span>         
        </div>
    </div>
<!-- <div class="row" style="margin-top: 10px;">
    <div class="col-md-12">
        <h2> <?= $store_detail_two; ?> </h2>
    </div>
</div> -->



<div class="row">
    <div class="col-md-12">
        <p> <?= $store_info['address'] ?></p>
    </div>
</div>

<?php if(!empty($store_open_hours)) { ?>
    <div class="row">
        <div class="col-md-12" >
            <p > <span> Open hour :  </span> <span style="color: green"> <?= $store_open_hours['timeslot'] ?> </span></p>
        </div>
    </div>

<?php } ?>

<?php if($store_next_timeslot != "--") { ?>
    <div class="row">
        <div class="col-md-12" >
            <p > <span> Delivery :  </span> <span style="color: green"> <?= $store_next_timeslot ?> </span></p>
        </div>
    </div>

<?php } ?>

<!-- <div class="row">
    <div id="us1" style="width: 100%; height: 200px;"></div>
                                
    <input type="hidden" name="latitude" value="<?= $store_info['latitude'] ?>" />
    <input type="hidden" name="longitude" value="<?= $store_info['longitude'] ?>" />
</div> -->

<script type="text/javascript" src="https://maps.google.com/maps/api/js?key=<?= $this->config->get('config_google_api_key') ?>&libraries=places&callback=initialize"></script>

<script type="text/javascript" src="<?= $base?>admin/ui/javascript/map-picker/js/locationpicker.jquery.js?v=2.2"></script>

<script type="text/javascript">
    
    $(document).ready(function() {
            //show rating start

        rating = '';
        console.log(<?= $rating ?>+"rating received");

        if(<?= $rating ?> % 1 === 0) {
            rating = <?= $rating ?>;
        } else {
            rating = (<?= $rating ?> - .5)+"half";
        }
        console.log(rating);

        $('#star'+rating).removeAttr('disabled');
        $('#star'+rating).click();

        //end show rating

    });

    function initialize() {

        console.log("initialize");
        /*$('#us1').locationpicker({
            location: {
                latitude: <?= $store_info['latitude']?$store_info['latitude']:0 ?>,
                longitude: <?= $store_info['longitude']?$store_info['longitude']:0 ?>
            },  
            radius: 0,
            inputBinding: {
                latitudeInput: $('input[name="latitude"]'),
                longitudeInput: $('input[name="longitude"]'),
                locationNameInput: $('#us2-address')
            },
            enableAutocomplete: true
        });*/

    }


</script>


