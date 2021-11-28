<?php echo $header; ?>
<div class="container">
    <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
    </ul>
    
    <div class="account-section">
        <div class="row secion-row">
            
            <?php if ($success) { ?>
            <div class="alert alert-success">
                <button class="close" data-dismiss="alert">&times;</button>
                <i class="fa fa-check-circle"></i>
                <?php echo $success; ?></div>
            <?php } ?>

            <?php echo $column_left; ?>
            <?php if ($column_left && $column_right) { ?>
            <?php $class = 'col-sm-6'; ?>
            <?php } elseif ($column_left || $column_right) { ?>
            <?php $class = 'col-sm-9'; ?>
            <?php } else { ?>
            <?php $class = 'col-sm-12'; ?>
            <?php } ?>
            <div id="content" class="<?php echo $class; ?>">
                <?php echo $content_top; ?>

                 <hr />
                 
                <div class="row">
                    <div class="col-sm-5">
                        <h3><?php echo $text_my_account; ?></h3>
                        <ul class="list-unstyled">
                            <li><a href="<?php echo $edit; ?>"><i class="fa fa-user">&nbsp;</i> <?php echo $text_edit; ?></a></li>
                            <li><a href="<?php echo $password; ?>"><i class="fa fa-key"> </i> <?php echo $text_password; ?></a></li>
                        </ul>
                    </div>
                    <div class="col-sm-5">
                        <h3><?php echo $text_my_orders; ?></h3>
                        <ul class="list-unstyled">
                            <li><a href="<?php echo $order; ?>"><i class="fa fa-shopping-cart"> </i> <?php echo $text_order; ?></a></li>
                            <?php if ($reward) { ?>
                            <li><a href="<?php echo $reward; ?>"><i class="fa fa-gift"> </i> <?php echo $text_reward; ?></a></li>
                            <?php } ?>
                            <li><a href="<?php echo $return; ?>"><i class="fa fa-reply"> </i> <?php echo $text_return; ?></a></li>
                            <li><a href="<?php echo $credit; ?>"><i class="fa fa-credit-card"> </i> <?php echo $text_credit; ?></a></li>
                        </ul>
                    </div>
                </div>

                <hr />
                
                <div class="row">
                    <div class="col-sm-5">	  
                        <h3><?php echo $text_my_newsletter; ?></h3>
                        <ul class="list-unstyled">
                            <li><a href="<?php echo $newsletter; ?>"><i class="fa fa-envelope"> </i> <?php echo $text_newsletter; ?></a></li>
                        </ul>
                    </div>
                    <div class="col-sm-5">	  
                        <h3><?php echo $text_my_logout; ?></h3>
                        <ul class="list-unstyled">
                            <li><a href="<?php echo $logout; ?>"><i class="fa fa-sign-out"> </i> <?php echo $text_logout; ?></a></li>
                        </ul>
                    </div>
                </div>
                
                <hr />
                
                <?php if(isset($button_pay)){ ?>
                <div class="row">
                    <div class="col-sm-5">
                        <h3><?= $text_membership ?></h3>
                        
                        <?= $status ?>
                        
                        <br /><br />
                        
                        <form action="<?= $action ?>" method="post">
                            <button class="btn btn-orange-addbtn"><?= $button_pay ?></button> 
                        </form>
                        
                    </div>
                </div>
                <?php }else{ ?>
                <div class="row">
                    <div class="col-sm-5">
                        <h3><?=  $text_membership ?></h3>
                
                        <form action="<?= $action ?>" method="post">
                            <button class="btn btn-orange-addbtn"><?= $button_become_member?></button> 
                        </form>                        
                    </div>
                </div>
                
                <?php } ?>
                
                <?php echo $content_bottom; ?></div>
            <?php echo $column_right; ?></div>
    </div>    
</div>
<?php echo $footer; ?>