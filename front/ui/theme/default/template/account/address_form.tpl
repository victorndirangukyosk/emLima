<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"> <?php echo $content_top; ?>
      <h2><?php echo $text_edit_address; ?></h2>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
        <fieldset>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-name"><?= $entry_name; ?></label>
            <div class="col-sm-10">
              <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
              <?php if ($error_name) { ?>
              <div class="text-danger"><?php echo $error_name; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-name"><?= $entry_contact_no; ?></label>
            <div class="col-sm-10">
              <input type="text" name="contact_no" value="<?php echo $contact_no; ?>" placeholder="Contact no" class="form-control" />
              <?php if ($error_contact_no) { ?>
              <div class="text-danger"><?php echo $error_contact_no; ?></div>
              <?php } ?>
            </div>
          </div>
          
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-address"><?=  $entry_flat_number; ?></label>
            <div class="col-sm-10">
              <input name="flat_number" type="text" placeholder="Flat number" value="<?php echo $flat_number; ?>" id="input-flat-number" class="form-control"/>
              <?php if ($error_flat_number) { ?>
              <div class="text-danger"><?php echo $error_flat_number; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-building-name"><?=  $entry_building_name; ?></label>
            <div class="col-sm-10">
              <input name="building_name" type="text" placeholder="Building Name" id="input-building-name" class="form-control" value="<?php echo $building_name; ?>"  />
              <?php if ($error_building_name) { ?>
              <div class="text-danger"><?php echo $error_building_name; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-address"><?=  $entry_address; ?></label>
            <div class="col-sm-10">
              <input name="address" type="text" placeholder="Address" id="input-address" class="form-control" value="<?php echo $address; ?>" />
              <?php if ($error_address) { ?>
              <div class="text-danger"><?php echo $error_address; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-landmark"><?=  $entry_landmark; ?></label>
            <div class="col-sm-10">
              <input name="landmark" type="text" placeholder="Landmark" id="input-landmark" class="form-control" value="<?php echo $landmark; ?>"/>
              <?php if ($error_landmark) { ?>
              <div class="text-danger"><?php echo $error_landmark; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-city"><?php echo $entry_city; ?></label>
            <div class="col-sm-10">
              <select name="city_id" id="selectCity" class="form-control" onchange="getZipcodes()">
                  <option selected value=""><?= $text_select ?></option>
                  <?php foreach($cities as $city){ ?>
                  <?php if($city['city_id'] == $city_id){ ?>
                    <option selected value="<?= $city['city_id'] ?>"><?= $city['name'] ?></option>
                  <?php }else{ ?>
                    <option value="<?= $city['city_id'] ?>"><?= $city['name'] ?></option>
                  <?php } ?>
                  <?php } ?>
              </select>
              <?php if ($error_city_id) { ?>
              <div class="text-danger"><?php echo $error_city_id; ?></div>
              <?php } ?>
            </div>
          </div>

          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-city"><?php echo $entry_zipcode; ?></label>
            <div class="col-sm-10" id="all-zipcodes">
              <?php echo $zipcode ?>
              <?php if ($error_zipcode) { ?>
              <div class="text-danger"><?php echo $error_zipcode; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_default; ?></label>
            <div class="col-sm-10">
              <?php if ($default) { ?>
              <label class="radio-inline">
                <input type="radio" name="default" value="1" checked="checked" />
                <?php echo $text_yes; ?></label>
              <label class="radio-inline">
                <input type="radio" name="default" value="0" />
                <?php echo $text_no; ?></label>
              <?php } else { ?>
              <label class="radio-inline">
                <input type="radio" name="default" value="1" />
                <?php echo $text_yes; ?></label>
              <label class="radio-inline">
                <input type="radio" name="default" value="0" checked="checked" />
                <?php echo $text_no; ?></label>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_type; ?></label>
            <div class="col-sm-10">
              <?php if ($address_type == 1) { ?>
              <label class="radio-inline">
                <input type="radio" name="address_type" value="1" checked="checked" />
                <?php echo $text_home; ?></label>
              <label class="radio-inline">
                <input type="radio" name="address_type" value="0"  />
                <?php echo $text_office; ?></label>
              <?php } else { ?>
              <label class="radio-inline">
                <input type="radio" name="address_type" value="1"  />
                <?php echo $text_home; ?></label>
              <label class="radio-inline">
                <input type="radio" name="address_type" value="0" checked="checked"  />
                <?php echo $text_office; ?></label>
              <?php } ?>
            </div>
          </div>
        </fieldset>
        <div class="buttons clearfix">
          <div class="pull-left"><a href="<?php echo $back; ?>" class="btn btn-default"><?php echo $button_back; ?></a></div>
          <div class="pull-right">
            <input type="submit" value="<?php echo $button_continue; ?>" class="btn btn-primary" />
          </div>
        </div>
      </form>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>


<?php echo $footer; ?>

<script type="text/javascript"><!--

function getZipcodes() {
  console.log("ssss");
  var city_id = $('#selectCity').find(":selected").val();
  console.log("ss"+$('#selectCity').find(":selected").val());

  $.ajax({
        url : 'index.php?path=account/address/getZipcodes&city_id='+  encodeURIComponent(city_id),
        method: 'get',
        data: {city_id : city_id},
        success:function(data){
            console.log("data"+data);
            if(data){
              $('#all-zipcodes').html(data);
               
            }else{
                console.log("ss");
            }
        }
    });
}
</script>
