<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" onclick="save('save')" form="form-attribute" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-success" data-original-title="Save"><i class="fa fa-check"></i></button>
        <button type="submit" form="form-attribute" data-toggle="tooltip" title="<?php echo $button_saveclose; ?>" class="btn btn-default" data-original-title="Save & Close"><i class="fa fa-save text-success"></i></button>
        <button type="submit" onclick="save('new')" form="form-attribute" data-toggle="tooltip" title="<?php echo $button_savenew; ?>" class="btn btn-default" data-original-title="Save & New"><i class="fa fa-plus text-success"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-times-circle text-danger"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-category" class="form-horizontal">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
          <li><a href="#tab-seo" data-toggle="tab"><?php echo $tab_seo; ?></a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tab-general">
              <ul class="nav nav-tabs" id="language">
                <?php foreach ($languages as $language) { ?>
                <li><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="ui/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
                <?php } ?>
              </ul>
              <div class="tab-content">
                <?php foreach ($languages as $language) { ?>
                <div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
                  <div class="form-group required">
                      <label class="col-sm-2 control-label" for="input-name"><?= $column_name ?></label>
                      <div class="col-sm-10">
                          
                          <input type="text" name="help_category[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($help_category[$language['language_id']]) ? $help_category[$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $entry_name; ?>" id="input-title<?php echo $language['language_id']; ?>" class="form-control" />
                          <?php if (isset($error_name[$language['language_id']])) { ?>
                          <div class="text-danger"><?php echo $error_name[$language['language_id']]; ?></div>
                          <?php } ?>

                      </div>
                  </div>
                      
                  <div class="form-group required">
                      <label class="col-sm-2 control-label" for="input-name"><?= $column_icon ?></label>
                      <div class="col-sm-10">
                         <!--  <input name="icon" value="<?= $icon ?>" placeholder="Icon" class="form-control" />
                          <?php if($error_icon){ ?>
                          <div class="text-danger"><?= $error_icon ?></div>
                          <?php } ?> -->
                          <input type="text" name="help_category[<?php echo $language['language_id']; ?>][icon]" value="<?php echo isset($help_category[$language['language_id']]) ? $help_category[$language['language_id']]['icon'] : ''; ?>" placeholder="<?php echo $entry_icon; ?>" id="input-title<?php echo $language['language_id']; ?>" class="form-control" />
                          <?php if (isset($error_icon[$language['language_id']])) { ?>
                          <div class="text-danger"><?php echo $error_icon[$language['language_id']]; ?></div>
                          <?php } ?>

                      </div>
                  </div>
                      
                  <div class="form-group">
                      <label class="col-sm-2 control-label" for="input-name"><?= $column_sort_order ?></label>
                      <div class="col-sm-10">
                          <!-- <input name="sort_order" value="<?= $sort_order ?>" placeholder="Sort order" class="form-control" /> -->
                          <input type="text" name="help_category[<?php echo $language['language_id']; ?>][sort_order]" value="<?php echo isset($help_category[$language['language_id']]) ? $help_category[$language['language_id']]['sort_order'] : ''; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-title<?php echo $language['language_id']; ?>" class="form-control" />
                          
                      </div>
                  </div>

              </div>
              <?php } ?>
            </div>
          </div>
          <div class="tab-pane fade" id="tab-seo">
                  <ul class="nav nav-tabs" id="seo-language">
                      <?php foreach ($languages as $language) { ?>
                      <li><a href="#seo-language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="ui/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
                      <?php } ?>
                  </ul>
                  <div class="tab-content">
                      <?php foreach ($languages as $language) { ?>
                      <div class="tab-pane" id="seo-language<?php echo $language['language_id']; ?>">
                          <div class="form-group">
                              <label class="col-sm-2 control-label" for="input-seo-url"><span data-toggle="tooltip" title="<?php echo $help_seo_url; ?>"><?php echo $entry_seo_url; ?></span></label>
                              <div class="col-sm-10">
                                  <input type="text" name="seo_url[<?php echo $language['language_id']; ?>]" value="<?php echo isset($seo_url[$language['language_id']]) ? $seo_url[$language['language_id']] : ''; ?>" placeholder="<?php echo $entry_seo_url; ?>" id="input-seo-url" class="form-control" />
                                  <?php if (isset($error_seo_url[$language['language_id']])) { ?>
                                  <div class="text-danger"><?php echo $error_seo_url[$language['language_id']]; ?></div>
                                  <?php } ?>
                              </div>
                          </div>
                          <div class="form-group">
                              <label class="col-sm-2 control-label" for="input-meta-title<?php echo $language['language_id']; ?>"><?php echo $entry_meta_title; ?></label>
                              <div class="col-sm-10">
                                  <input name="help_category[<?php echo $language['language_id']; ?>][meta_title]" value="<?php echo isset($help_category[$language['language_id']]) ? $help_category[$language['language_id']]['meta_title'] : ''; ?>" placeholder="<?php echo $entry_meta_title; ?>" id="input-meta-title<?php echo $language['language_id']; ?>" class="form-control" />
                                  <?php if (isset($error_meta_title[$language['language_id']])) { ?>
                                  <div class="text-danger"><?php echo $error_meta_title[$language['language_id']]; ?></div>
                                  <?php } ?>
                              </div>
                          </div>
                          <div class="form-group">
                              <label class="col-sm-2 control-label" for="input-meta-description<?php echo $language['language_id']; ?>"><?php echo $entry_meta_description; ?></label>
                              <div class="col-sm-10">
                                  <textarea name="help_category[<?php echo $language['language_id']; ?>][meta_description]" rows="5" placeholder="<?php echo $entry_meta_description; ?>" id="input-meta-description<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($help_category[$language['language_id']]) ? $help_category[$language['language_id']]['meta_description'] : ''; ?></textarea>
                              </div>
                          </div>
                          <div class="form-group">
                              <label class="col-sm-2 control-label" for="input-meta-keyword<?php echo $language['language_id']; ?>"><?php echo $entry_meta_keyword; ?></label>
                              <div class="col-sm-10">
                                  <textarea name="help_category[<?php echo $language['language_id']; ?>][meta_keyword]" rows="5" placeholder="<?php echo $entry_meta_keyword; ?>" id="input-meta-keyword<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($help_category[$language['language_id']]) ? $help_category[$language['language_id']]['meta_keyword'] : ''; ?></textarea>
                              </div>
                          </div>
                      </div>
                      <?php } ?>
                  </div>
              </div>
            </div>

        </form>
      </div>
    </div>
  </div>
</div>
  <script type="text/javascript"><!--
<?php foreach ($languages as $language) { ?>
  <?php if( $text_editor == 'summernote' ) { ?>
    $('#input-description<?php echo $language['language_id']; ?>').summernote({
      height: 300
    });
  <?php } else if ( $text_editor == 'tinymce' ) { ?>
    $('#input-description<?php echo $language['language_id']; ?>').tinymce({
      script_url : 'ui/javascript/tinymce/tinymce.min.js',
      plugins: "visualblocks,textpattern,table,media,pagebreak,link,image",
          target_list: [
           {title: 'None', value: ''},
           {title: 'Same page', value: '_self'},
           {title: 'New page', value: '_blank'},
           {title: 'LIghtbox', value: '_lightbox'}
          ]
    });
  <?php } ?>
<?php } ?>
//--></script> 
  <script type="text/javascript"><!--
$('#language a:first').tab('show');
$('#seo-language a:first').tab('show');
//--></script>
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