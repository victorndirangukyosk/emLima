<?php if (count($languages) > 1) { ?>
<div class="pull-left">
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="language">
  <div class="btn-group" style="z-index: 10000;">
    <button class="btn btn-link dropdown-toggle" data-toggle="dropdown">
    <?php foreach ($languages as $language) { ?>
    <?php if ($language['code'] == $code) { ?>
    <img src="<?= $base ?>image/flags/<?php echo $language['image']; ?>" alt="<?php echo $language['code']; ?>" title="<?php echo $language['code']; ?>">
    
    <!-- <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_language; ?></span> <i class="fa fa-caret-down"></i> -->
    <span class="hidden-xs hidden-sm hidden-md"><?php echo strtoupper($language['code']); ?></span> <i class="fa fa-caret-down"></i>
    <?php } ?>
    <?php } ?>

    </button>
    <!-- <ul class="dropdown-menu" style="bottom: 100%;top :auto;"> -->
    <ul class="dropdown-menu">
      <?php foreach ($languages as $language) { ?>
      <li><a href="<?php echo $language['code']; ?>"><img src="<?= $base ?>image/flags/<?php echo $language['image']; ?>" alt="<?php echo strtoupper($language['code']); ?>" title="<?php echo strtoupper($language['code']); ?>" /> <?php echo strtoupper($language['code']); ?></a></li>
      <?php } ?>
    </ul>
  </div>
  <input type="hidden" name="code" value="" />
  <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
</form>
</div>
<?php } ?>
