<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" onclick="save('save')" form="form-order-status" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-success"><i class="fa fa-check"></i></button>
    <button type="submit" form="form-order-status" data-toggle="tooltip" title="<?php echo $button_saveclose; ?>" class="btn btn-default" data-original-title="Save & Close"><i class="fa fa-save text-success"></i></button>
    <button type="submit" onclick="save('new')" form="form-order-status" data-toggle="tooltip" title="<?php echo $button_savenew; ?>" class="btn btn-default" data-original-title="Save & New"><i class="fa fa-plus text-success"></i></button>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-order-status" class="form-horizontal">
          <div class="tab-pane active in" id="tab-general">
            <ul class="nav nav-tabs" id="language">
                <?php foreach ($languages as $language) { ?>
                <li><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="ui/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
                <?php } ?>
            </ul>
            <div class="tab-content">
                <?php foreach ($languages as $language) { ?>
                <div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
                    <div class="form-group required">
                        <label class="col-sm-2 control-label"><?php echo $entry_name; ?></label>
                        <div class="col-sm-10">
                          <div class="input-group"><span class="input-group-addon"><img src="ui/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['title']; ?>" /></span>
                            <input type="text" name="block[<?php echo $language['language_id']; ?>][title]" value="<?php echo isset($block[$language['language_id']]) ? $block[$language['language_id']]['title'] : ''; ?>" placeholder="<?php echo $entry_name; ?>" class="form-control" />
                          </div>
                          <?php if (isset($error_name[$language['language_id']])) { ?>
                          <div class="text-danger"><?php echo $error_name[$language['language_id']]; ?></div>
                          <?php } ?>
                        </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label" for="input-description"><?= $entry_description ?> </label>
                      <div class="col-sm-10">
                        <textarea placeholder="Description" id="input-description" class="form-control" name="block[<?php echo $language['language_id']; ?>][description]" value="<?php echo isset($block[$language['language_id']]) ? $block[$language['language_id']]['description'] : ''; ?>" ><?php echo isset($block[$language['language_id']]) ? $block[$language['language_id']]['description'] : ''; ?>
                          
                        </textarea>
                        <?php if (isset($error_description[$language['language_id']])) { ?>
                          <div class="text-danger"><?php echo $error_description[$language['language_id']]; ?></div>
                          <?php } ?>
                      </div>
                    </div> 

                    <div class="form-group">
                      <label class="col-sm-2 control-label" for="input-link"><?= $entry_image ?></label>
                      <div class="col-sm-10">
                          <a href="" id="thumb-image<?php echo $language['language_id']; ?>" data-toggle="image" class="img-thumbnail">
                              <img src="<?php echo isset($block[$language['language_id']]) ? $block[$language['language_id']]['thumb'] : $thumb; ?>" alt="" />
                          </a>
                          <input type="hidden" name="block[<?php echo $language['language_id']; ?>][image]" value="<?php echo isset($block[$language['language_id']]) ? $block[$language['language_id']]['image'] : $thumb; ?>"  id="input-image<?php echo $language['language_id']; ?>" />

                          <?php if (isset($error_image[$language['language_id']])) { ?>
                          <div class="text-danger"><?php echo $error_image[$language['language_id']]; ?></div>
                          <?php } ?>

                      </div> 
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-name"><?= $column_sort_order ?></label>
                        <div class="col-sm-10">
                            <!-- <input name="sort_order" value="<?= $sort_order ?>" placeholder="Sort order" class="form-control" /> -->
                            <input type="text" name="block[<?php echo $language['language_id']; ?>][sort_order]" value="<?php echo isset($block[$language['language_id']]) ? $block[$language['language_id']]['sort_order'] : ''; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-title<?php echo $language['language_id']; ?>" class="form-control" />
                            
                        </div>
                    </div>
                  
                    </div>
                <?php } ?>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
<?php foreach ($languages as $language) {
    if ( $text_editor == 'summernote' ) { ?>
        $('#input-message-<?php echo $language['language_id']; ?>').summernote({
            height: 300
        });
    <?php } else if ( $text_editor == 'tinymce' ) { ?>
        $('#input-message-<?php echo $language['language_id']; ?>').tinymce({
            script_url : 'ui/javascript/tinymce/tinymce.min.js',
            plugins: "visualblocks,textpattern,table,media,pagebreak,link,image",
            target_list: [
                {title: 'None', value: ''},
                {title: 'Same page', value: '_self'},
                {title: 'New page', value: '_blank'},
                {title: 'LIghtbox', value: '_lightbox'}
            ],
            height : 500
        });
    <?php } ?>
<?php } ?>
//--></script>
<script type="text/javascript"><!--
$('#language a:first').tab('show');
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