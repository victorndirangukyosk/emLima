<?php echo $header; ?>
    <?php echo $column_left; ?>
    
    <div id="content">
        <div class="page-header">
            <div class="container-fluid">
                <div class="pull-right">
                    <button type="submit" form="form-blog-category" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
                    <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
                </div>
                <h1><?php echo $heading_title; ?></h1>
                <ul class="breadcrumb">
                    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                    <?php } ?>
                </ul>
            </div>
        </div> <!-- end of page-header class -->
        
        <div class="container-fluid">
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
                    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-blog-category" class="form-horizontal">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
                            <li><a href="#tab-data" data-toggle="tab"><?php echo $tab_data; ?></a></li>
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
                                                <label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label"><?php echo $entry_name; ?></label>
                                                <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                                    <input type="text" name="category_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['name'] : ''; ?>" class="form-control" />
        	                  						<?php if (isset($error_name[$language['language_id']])) { ?>
        	                  							<span class="text-danger"><?php echo $error_name[$language['language_id']]; ?></span>
        	                  						<?php } ?>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label"><?php echo $entry_meta_description; ?></label>
                                                <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                                   <textarea name="category_description[<?php echo $language['language_id']; ?>][meta_description]" class="form-control"><?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['meta_description'] : ''; ?></textarea>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label"><?php echo $entry_meta_keyword; ?></label>
                                                <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                                   <textarea name="category_description[<?php echo $language['language_id']; ?>][meta_keyword]" class="form-control"><?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['meta_keyword'] : ''; ?></textarea>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label"><?php echo $entry_description; ?></label>
                                                <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                                   <textarea name="category_description[<?php echo $language['language_id']; ?>][description]" id="description<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['description'] : ''; ?></textarea>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    <?php } ?>
                                 </div> <!-- end of tab-content id -->
                                
                                
                            </div> <!-- end of tab-general id -->
                            
                            <div class="tab-pane" id="tab-data">
                                
                                <div class="form-group">
                                    <label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label"><?php echo $entry_parent; ?></label>
                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                        
                                        <select name="parent_id" class="form-control">
                      						<option value="0"><?php echo $text_none; ?></option>
                      						<?php foreach ($categories as $category) { ?>
                      							<?php if ($category['simple_blog_category_id'] == $parent_id) { ?>
                      								<option value="<?php echo $category['simple_blog_category_id']; ?>" selected="selected"><?php echo $category['name']; ?></option>
                      							<?php } else { ?>
                      								<option value="<?php echo $category['simple_blog_category_id']; ?>"><?php echo $category['name']; ?></option>
                      							<?php } ?>
                      						<?php } ?>
                    					</select>
                                        
                                    </div>
                                </div>
                                
                                <div class="form-group required">
                                    <label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label"><span data-toggle="tooltip" title="<?php echo $help_keyword; ?>"><?php echo $entry_keyword; ?></label>
                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                        <input type="text" name="keyword" value="<?php echo $keyword; ?>" class="form-control" />
                                        <?php if($error_seo_keyword) { ?>
    					            		<span class="text-danger"><?php echo $error_seo_keyword; ?></span>
    					            	<?php } ?>   
                                    </div>
                                </div>
                                
                                <input type="hidden" name="category_store[]" value="0" />
                                
                                <div class="form-group">
                                    <label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label"><?php echo $entry_image; ?></label>
                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                        <a href="" id="thumb-image" data-toggle="image" class="img-thumbnail">
                                            <img src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $no_image; ?>" /></a>
                                        <input type="hidden" name="image" value="<?php echo $image; ?>" id="input-image" />   
                                    </div>
                                </div>
                                
                                <input type="hidden" name="top" value="1" />
                                <input type="hidden" name="blog_category_column" value="<?php echo $blog_category_column; ?>" />    
                                
                                <div class="form-group">
                                    <label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label"><?php echo $entry_article_limit; ?></label>
                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                        <input type="text" name="column" value="<?php echo $column; ?>" class="form-control" />
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label"><?php echo $entry_sort_order; ?></label>
                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                        <input type="text" name="sort_order" value="<?php echo $sort_order; ?>" class="form-control" />
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label"><?php echo $entry_status; ?></label>
                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                        <select name="status" class="form-control">
                      						<?php if ($status) { ?>
    						                  	<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
    						                  	<option value="0"><?php echo $text_disabled; ?></option>
                      						<?php } else { ?>
    							                <option value="1"><?php echo $text_enabled; ?></option>
    							                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                      						<?php } ?>
                    					</select>
                                    </div>
                                </div>                                
                            </div> <!-- end of tab-data id -->
                        </div>                        
                    </form>
                </div>
            </div>
        </div>        
    </div>
    
    <script type="text/javascript">
        
        <?php foreach ($languages as $language) { ?>
            $('#description<?php echo $language['language_id']; ?>').summernote({height: 300});
        <?php } ?>
    
        $('#language a:first').tab('show');
        $('#option a:first').tab('show');
    </script>
    
<?php echo $footer; ?>