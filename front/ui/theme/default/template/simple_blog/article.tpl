<?php echo $header; ?>

<div role="main" id="main" class="artical_container container" style="min-height: 350px;">
    <div class="row mobile-title">
        <div class="col-md-12">
            <div class="content-wrapper with-padding static-page-heading">
                <h2><?php echo $heading_title; ?></h2>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="content-wrapper with-padding">
            <div class="row">
            <div class="col-md-9" id="article_wrapper">
                
                <?php if($articles) { ?>
                <?php foreach($articles as $article) { ?>                            
                <div class="article form-group">
                    <div class="article-title heading-title">
                        <a href="<?php echo $article['href']; ?>"><?php echo ucwords($article['article_title']); ?></a>
                    </div>

                    <div class="article-sub-title">
                        <span class="article-author">
                            <a href="<?php echo $article['author_href']; ?>"><?php echo $article['author_name']; ?></a></span>
                        <!-- <span class="article-author"><?php echo $article['author_name']; ?></span> -->
                        <span class="bullet">&bull;</span>
                        <span class="article-date"><?php echo $article['date_added']; ?></span>

                        <?php if($article['allow_comment']) { ?>
                        <span class="bullet">&bull;</span>
                        <span class="article-comment"><a href="<?php echo $article['comment_href']; ?>#comment-section"><?php echo $article['total_comment']; ?></a></span>
                        <?php } ?>

                    </div>

                    <?php if($article['image']) { ?>
                    <?php if($article['featured_found']) { ?>
                    <div class="article-image">
                        <img src="<?php echo $article['image']; ?>" alt="<?php echo $article['article_title']; ?>" />
                    </div>
                    <?php } else { ?>
                    <div class="article-thumbnail-image">
                        <img src="<?php echo $article['image']; ?>" alt="<?php echo $article['article_title']; ?>" />
                        <span class="article-description">
                            <?php echo $article['description']; ?>
                        </span>
                    </div>
                    <?php } ?>
                    <?php } ?>

                    <?php if($article['featured_found']) { ?>						
                    <div class="article-description">
                        <?php echo $article['description']; ?>
                    </div>
                    <?php } else { ?>
                    <div class="article-description">
                        <?php echo $article['description']; ?>
                    </div>
                    <?php } ?>

                    <div align="right">
                        <a href="<?php echo $article['href']; ?>"><b><?= $text_read_more ?></b></a>
                    </div>

                    <?php if(!$article['featured_found']) { ?>
                    <div class="article-thumbnail-found"></div>
                    <?php } ?>                                
                </div>
                <?php } ?>

                <div class="hidden"><?php echo $pagination; ?></div>

                <?php } else { ?>
                <h3 class="text-center"><?php echo $text_no_found; ?></h3>
                <?php } ?>
            </div>
            <div class="col-md-3">
                <?= $this->load->controller('module/simple_blog_category') ?>
            </div>
            </div>    
        </div>
    </div>
</div>

<?php echo $footer; ?> 

<style>
    #infscr-loading{
        position: relative;
    }
</style>
    
<script type="text/javascript" src="front/ui/javascript/jquery/infinitescroll/jquery.infinitescroll.min.js" ></script>

<script type="text/javascript">
$(document).ready(function() {
    var $container = $('#article_wrapper');
    $container.infinitescroll({
        animate:true,
        navSelector  : '.pagination',    // selector for the paged navigation 
        nextSelector : '.pagination a',  // selector for the NEXT link (to page 2)
        itemSelector : '.article',     // selector for all items you'll retrieve
        loading: {
            finishedMsg: 'No more artical to load.',
            msgText: 'Loading...',
            img: 'image/theme/ajax-loader_63x63-0113e8bf228e924b22801d18632db02b.gif'
            
        }
    });			
});
</script>
