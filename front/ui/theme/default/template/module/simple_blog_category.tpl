<?php if($categories) { ?>

<div class="list-group">
    <?php foreach ($categories as $category) { ?>
        <?php if ($category['simple_blog_category_id'] == $category_id) { ?>
            <a href="<?php echo $category['href']; ?>" class="list-group-item active"><?php echo $category['name']; ?></a>
            <?php if ($category['children']) { ?>
                <?php foreach ($category['children'] as $child) { ?>
                    <?php if ($child['category_id'] == $child_id) { ?>
                        <a href="<?php echo $child['href']; ?>" class="list-group-item active">&nbsp;&nbsp;&nbsp;- <?php echo $child['name']; ?></a>
                    <?php } else { ?>
                        <a href="<?php echo $child['href']; ?>" class="list-group-item">&nbsp;&nbsp;&nbsp;- <?php echo $child['name']; ?></a>
                    <?php } ?>
                <?php } ?>
            <?php } ?>
        <?php } else { ?>
         <a href="<?php echo $category['href']; ?>" class="list-group-item"><?php echo $category['name']; ?></a>
        <?php } ?>
    <?php } ?>
</div>

<div id="blog-search" style="margin-top: 5px; margin-bottom: 5px;">
    <div>
        <input type="text" name="article_search" value="<?php echo $blog_search; ?>" placeholder="<?php echo $text_search_article; ?>" class="form-control" style="margin-bottom: 5px;" />

        <a id="button-search" class="btn"><i class="fa fa-search"></i></a>
    </div>
</div>

<?php } ?>

<script type="text/javascript">
	$('#blog-search input[name=\'article_search\']').keydown(function(e) {
		if (e.keyCode == 13) {
			$('#button-search').trigger('click');
		}
	});

	$('#button-search').bind('click', function() {
		url = 'index.php?path=blog/search';
		
		var article_search = $('#blog-search input[name=\'article_search\']').val();
		
		if (article_search) {
			url += '&blog_search=' + encodeURIComponent(article_search);
		}
		
		location = url;
	});
</script> 
