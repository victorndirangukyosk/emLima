<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
    <button type="submit" onclick="save('save')" form="form-offer" data-toggle="tooltip" notice="<?php echo $button_save; ?>" class="btn btn-success" data-original-notice="Save"><i class="fa fa-check"></i></button>
    <button type="submit" form="form-offer" data-toggle="tooltip" notice="<?php echo $button_saveclose; ?>" class="btn btn-default" data-original-notice="Save & Close"><i class="fa fa-save text-success"></i></button>
    <button type="submit" onclick="save('new')" form="form-offer" data-toggle="tooltip" notice="<?php echo $button_savenew; ?>" class="btn btn-default" data-original-notice="Save & New"><i class="fa fa-plus text-success"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" notice="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-times-circle text-danger"></i></a></div>
      <h1><?php echo $heading_notice; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
      
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-notice"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-offer" class="form-horizontal">
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-notice"><?php echo $entry_name; ?></label>
            <div class="col-sm-10">
              <input type="text" name="notice" value="<?php echo $notice; ?>" placeholder="<?php echo $entry_notice; ?>" id="input-notice" class="form-control" />
              <?php if ($error_notice) { ?>
              <div class="text-danger"><?php echo $error_notice; ?></div>
              <?php } ?>
            </div>
          </div>
         <!--  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-notice"><?= $entry_zipcode ?></label>
            <div class="col-sm-10">
                <input type="text" name="zipcode" value="<?php echo $zipcode; ?>" placeholder="Zipcode" id="input-notice" class="form-control" />
            </div>
          </div>  -->

          <input type="hidden" name="zipcode" value="<?php echo $zipcode; ?>" placeholder="Zipcode" id="input-notice" class="form-control" />


          <div class="form-group">
              <label class="col-sm-2 control-label" for="input-image"><?php echo $entry_image; ?></label>
              <div class="col-sm-10"><a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $image_thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                  <input type="hidden" name="image" value="<?php echo $image; ?>" id="input-image" />
              </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-zipcode"><?= $entry_force ?></label>
            <div class="col-sm-10">
                <select name="force" class="form-control">
                <?php if($force){ ?>
                    <option selected="" value="1"><?php echo $text_yes; ?></option>
                    <option value='0'><?php echo $text_no; ?></option>
                <?php } else{ ?>
                    <option value="1"><?php echo $text_yes; ?></option>
                    <option selected="" value='0'><?php echo $text_no; ?></option>
                <?php } ?>
                </select>
            </div>
          </div>

          

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-zipcode">Radius</label>
            <div class="col-sm-10">
                <input type="text" name="radius" value="<?php echo $radius; ?>" placeholder="Radius" id="input-notice" class="form-control" />
            </div>
          </div>


          <div class="form-group">
          <label class="col-sm-2 control-label" for="input-zipcode">Location</label>
            <div class="col-sm-10">

                <div style="margin-bottom: 15px;">
                  <label>Search</label>
                  <input class="form-control" type="text" id="us2-address" style="max-width: 100%" />                                
                </div>

                <div id="us1" style="width: 100%; height: 400px;"></div>
              
                <input type="hidden" name="latitude" value="<?= $latitude ?>" />
                <input type="hidden" name="longitude" value="<?= $longitude ?>" />
                
                <script>
                  $('#us1').locationpicker({
                    location: {
                      latitude: <?= $latitude?$latitude:0 ?>,
                      longitude: <?= $longitude?$longitude:0 ?>
                    },  
                    radius: 0,
                    inputBinding: {
                      latitudeInput: $('input[name="latitude"]'),
                      longitudeInput: $('input[name="longitude"]'),
                      locationNameInput: $('#us2-address')
                    },
                    enableAutocomplete: true
                  });
                </script>            
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-zipcode"><?= $entry_status ?></label>
            <div class="col-sm-10">
                <select name="status" class="form-control">
                <?php if($status){ ?>
                    <option selected="" value="1"><?php echo $text_enabled; ?></option>
                    <option value='0'><?php echo $text_disabled; ?></option>
                <?php } else{ ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
                    <option selected="" value='0'><?php echo $text_disabled; ?></option>
                <?php } ?>
                </select>
            </div>
          </div>
          


        </form>
      </div>
    </div>
  </div>
</div>

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
<?php echo $footer; ?>