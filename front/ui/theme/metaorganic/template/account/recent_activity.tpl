<?php if ($recent_activity) { ?>
<?php foreach ($recent_activity as $resat) { ?>
<li class="tl-item">
    <div class="tl-wrap {{class}}">
        <span class="tl-date"><?= $resat['date_added'] ?></span>
        <div class="tl-content panel padder b-a">
            <span class="arrow left pull-up"></span>
            <div><?php echo $resat['firstname']." ".$resat['lastname']. " ".$resat['comment1']?> <a
                    href="<?php echo $resat['href'];?>" target="_blank" data-toggle="tooltip" title="order info"
                    class="btn-link text_green">
                    <?php echo  "#". $resat['order_id']; ?></a><?php echo " for ".$resat['total'] ."   ".$resat['comment2'] ?>
            </div>

        </div>
    </div>
</li>
<?php } ?>
<?php } else { ?>
<div class="tl-item"><?php echo 'No Recent Activity Found'; ?></div>
<?php } ?>