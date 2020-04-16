<div class="list-group">
    
  <?php if (!$logged) { ?>
  <a href="<?php echo $login; ?>" class="list-group-item"><?php echo $text_login; ?></a> 
  <a href="<?php echo $register; ?>" class="list-group-item"><?php echo $text_register; ?></a> 
  <a href="<?php echo $forgotten; ?>" class="list-group-item"><?php echo $text_forgotten; ?></a>
  <?php } ?>
  
  <a href="<?php echo $account; ?>" class="list-group-item"><?php echo $text_account; ?></a>
  <a href="<?php echo $address; ?>" class="list-group-item"><?php echo $text_address; ?></a>
  
  <?php if ($logged) { ?>  
  <?php if($member_account){ ?>
  <a href="<?php echo $member_account; ?>" class="list-group-item"><?php echo $text_become_member; ?></a>
  <?php } ?>  
  <a href="<?php echo $edit; ?>" class="list-group-item"><?php echo $text_edit; ?></a>
  <a href="<?php echo $password; ?>" class="list-group-item"><?php echo $text_password; ?></a>  
  <?php } ?>
  
  <a href="<?php echo $order; ?>" class="list-group-item"><?php echo $text_order; ?></a> 
  <a href="<?php echo $reward; ?>" class="list-group-item"><?php echo $text_reward; ?></a> <a href="<?php echo $return; ?>" class="list-group-item"><?php echo $text_return; ?></a> <a href="<?php echo $credit; ?>" class="list-group-item"><?php echo $text_credit; ?></a> <a href="<?php echo $newsletter; ?>" class="list-group-item"><?php echo $text_newsletter; ?></a>
  <?php if ($logged) { ?>
  <a href="<?php echo $logout; ?>" class="list-group-item"><?php echo $text_logout; ?></a>
  <?php } ?>
</div>
