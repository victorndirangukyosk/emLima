<div class="row">
    <div class="col-md-12">

        <?php if ($shipping_methods) { ?>

                <ul class="nav nav-tabs nav-justified" role="tablist" id="shipping_tab" >

                <?php foreach ($shipping_methods as $shipping_method) { ?>

                    <?php if (!$shipping_method['error']) { ?>

                        
                            <?php foreach ($shipping_method['quote'] as $key => $quote) { ?>    

                                <?php if($key != 'express') { ?>
                                    <li role="presentation">
                                        <a href="#play_<?php echo key($shipping_method['quote']); ?>"  role="tab" data-toggle="tab"><?php echo $shipping_method['title']; ?> </a>
                                    </li>
                                <?php } ?>
                                
                            <?php } ?>
                        

                    <?php }else{ ?>
                    <div class="alert alert-danger"><?php echo $shipping_method['error']; ?></div>
                    <?php } ?>
                <?php } ?>

                </ul>
        <?php } ?>

       <!--  <ul class="nav nav-tabs nav-justified" role="tablist" >
            <li class="active" role="presentation" ><a href="#play_pickup" role="tab" data-toggle="tab">Tab 1</a>
            </li>
            <li role="presentation" ><a href="#play_store_delivery" role="tab" data-toggle="tab">Tab 2</a>
            </li>
            <li role="presentation" ><a href="#play_normal" role="tab" data-toggle="tab">Tab 3</a>
            </li>
            <li role="presentation" ><a href="#play_express" role="tab" data-toggle="tab">Tab 4</a>
            </li>
        </ul>
 -->

        <div class="tab-content">

            <?php if ($shipping_methods) { ?>

                    <?php foreach ($shipping_methods as $shipping_method) { ?>

                        <?php if (!$shipping_method['error']) { ?>

                                    <?php $j = 0; foreach ($shipping_method['quote'] as $key => $quote) { ?>    

                                        <?php if($key != 'express') { ?>
                                            <?php if($j ==  0 ) { ?>

                                                <div role="tabpanel" class="tab-pane active" id="play_<?php echo key($shipping_method['quote']); ?>">

                                            <?php } else { ?>
                                                
                                                <div role="tabpanel" class="tab-pane" id="play_<?php echo key($shipping_method['quote']); ?>">

                                            <?php } $j++; ?>

                                            <div class="time-slots">

                                                <ul class="nav nav-tabs nav-justified" role="tablist" data-store-id='<?= $store["store_id"] ?>' id="dates_<?= $store['store_id'] ?>" name="dates[<?= $store['store_id'] ?>]">

                                                    <?php $q = 0; foreach ($shipping_method['shipping_timeslots']['dates'] as $d): ?>
                                                            <?php if($q ==  0 ) { ?>
                                                            <li role="presentation" class="active" id="dates_selected" data-value="<?php echo $d ?>"  ><a href="#<?php echo $d ?>" aria-controls="<?php echo $d ?>" role="tab" data-toggle="tab"><?php echo $d ?></a></li>
                                                            <?php } else { ?>
                                                                <li role="presentation" id="dates_selected" data-value="<?php echo $d ?>" ><a href="#<?php echo $d ?>" aria-controls="<?php echo $d ?>" role="tab" data-toggle="tab"><?php echo $d ?></a></li>
                                                            <?php } $q++; ?>

                                                    <?php endforeach ?>
                                                </ul>
                                                <div class="tab-content">
                                                    
                                                    <?php $p = 0; foreach ($shipping_method['shipping_timeslots']['timeslots'] as $key => $values){ ?>

                                                        <?php if($p ==  0 ) { ?>

                                                            <div role="tabpanel" class="tab-pane active" id="<?= $key ?>">
                                                                <div class="time-slots">
                                                                    <ul class="list-group">
                                                                        <?php if(count($values) > 0 ) { ?>
                                                                            <?php foreach ($values as $value): ?>
                                                                                <li class="list-group-item" id="time_selected" data-value="<?= $value['timeslot']?>" data-date="<?= $key ?>">
                                                                                    <p class="control control--radio"><?= $value['timeslot']?>
                                                                                    </p>
                                                                                </li>
                                                                            <?php endforeach ?>
                                                                        <?php } else { ?>

                                                                        <li class="list-group-item"> <?= $text_no_timeslot?></li>
                                                                        <?php } ?>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        <?php } else { ?>
                                                            <div role="tabpanel" class="tab-pane" id="<?= $key ?>">
                                                                <div class="time-slots">
                                                                    <ul class="list-group">
                                                                        <?php if(count($values) > 0 ) { ?>
                                                                            <?php foreach ($values as $value): ?>
                                                                                <li class="list-group-item" id="time_selected" data-value="<?= $value['timeslot']?>" data-date="<?= $key?>">
                                                                                    <p class="control control--radio"><?= $value['timeslot']?>
                                                                                    </p>
                                                                                </li>
                                                                            <?php endforeach ?>
                                                                        <?php } else { ?>

                                                                        <li class="list-group-item"> <?= $text_no_timeslot?></li>
                                                                        <?php } ?>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        <?php } $p++; ?>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                         </div>
                                    <?php } ?>
                                    
                                <?php } ?><!-- END foreach -->
                        <?php } ?>
                    <?php } ?><!-- END foreach shipping-methods -->

            <?php } ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('#shipping_tab li:first').addClass('active');
    
    $('#shipping_tab li:last').children().click();
    $('#shipping_tab li:first').children().click();
</script>