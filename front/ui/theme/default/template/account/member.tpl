<?php echo $header; ?>
<div class="container">
    <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
    </ul>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?></div>
    <?php } ?>
    <div class="account-section">
        <div class="row secion-row">
            <?php echo $column_left; ?>
            <?php if ($column_left && $column_right) { ?>
            <?php $class = 'col-sm-6'; ?>
            <?php } elseif ($column_left || $column_right) { ?>
            <?php $class = 'col-sm-9'; ?>
            <?php } else { ?>
            <?php $class = 'col-sm-12'; ?>
            <?php } ?>
            <div id="content" class="<?php echo $class; ?>">
                <div class="secion-row">
                    <div class="title"><?= $text_become_member; ?></div>
                    
                    <?php if($error){ ?>
                    <div class="alert alert-error">
                        <?= $error ?>
                    </div>
                    <?php } ?>
                    
                    <p><?= $text_benefits ?></p>
                        
                    <hr />
                    
                    <form action='<?= $action ?>' method="post">
                        <button class="btn btn-orange-addbtn btn-lg"><?= $button_pay ?></button>
                    </form>
                    
                </div>
                
            </div>
            <?php echo $column_right; ?></div>
    </div>    
</div>
<?php echo $footer; ?>