<div class="row">
    <div class="col-md-12">

        <!-- <?php 
            echo 'date_default_timezone_set: ' . date_default_timezone_get() . '<br />';
            echo "The time is " . date("h:i:sa");
        ?> -->
        <div>
            <ul class="nav nav-tabs nav-justified" role="tablist" data-store-id='<?= $store["store_id"] ?>' id="dates_<?= $store['store_id'] ?>" name="dates[<?= $store['store_id'] ?>]">

                <?php $i = 0; foreach ($dates as $d): ?>
                        <?php if($i ==  0 ) { ?>
                        <li role="presentation" class="active" id="dates_selected" data-value="<?php echo $d ?>"  ><a href="#<?= $store['store_id'] ?>_<?php echo $d ?>" aria-controls="<?php echo $d ?>" role="tab" data-toggle="tab"><?php echo $d ?></a></li>
                        <?php } else { ?>
                            <li role="presentation" id="dates_selected" data-value="<?php echo $d ?>" ><a href="#<?= $store['store_id'] ?>_<?php echo $d ?>" aria-controls="<?php echo $d ?>" role="tab" data-toggle="tab"><?php echo $d ?></a></li>
                        <?php } $i++; ?>

                <?php endforeach ?>
            </ul>
            <div class="tab-content">
                
                <?php $i = 0; foreach ($timeslots as $key => $values){ ?>

                    <?php if($i ==  0 ) { ?>

                        <div role="tabpanel" class="tab-pane active" id="<?= $store['store_id'] ?>_<?= $key ?>">
                            <div class="time-slots">
                                <ul class="list-group">
                                    <?php if(count($values) > 0 ) { ?>
                                        <?php foreach ($values as $value): ?>
                                            <li class="list-group-item timeslot-selected" id="time_selected" data-value="<?= $value['timeslot']?>" data-date="<?= $key ?>" data-store="<?= $store['store_id'] ?>" >
                                                <label class="control control--radio"><?= $value['timeslot']?>
                                                    <input type="radio" name="radAnswer_<?= $store['store_id'] ?>" />
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </li>
                                        <?php endforeach ?>
                                    <?php } else { ?>

                                    <li class="list-group-item"> <?= $text_no_timeslot?></li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div role="tabpanel" class="tab-pane" id="<?= $store['store_id'] ?>_<?= $key ?>">
                            <div class="time-slots">
                                <ul class="list-group">
                                    <?php if(count($values) > 0 ) { ?>
                                        <?php foreach ($values as $value): ?>
                                            <li class="list-group-item timeslot-selected" id="time_selected" data-value="<?= $value['timeslot']?>" data-date="<?= $key?>" data-store="<?= $store['store_id'] ?>" >
                                                <label class="control control--radio"><?= $value['timeslot']?>
                                                    <input type="radio" name="radAnswer_<?= $store['store_id'] ?>" />
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </li>
                                        <?php endforeach ?>
                                    <?php } else { ?>

                                    <li class="list-group-item"> <?= $text_no_timeslot?></li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    <?php } $i++; ?>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

$('.timeslot-selected').unbind().click(function(e) {
    
    console.log($(this));
    saveNewTimeSlot($(this).attr('data-store'),$(this).attr('data-value'),$(this).attr('data-date'));
    $(this).children().children().prop("checked", true);
    
     $('#select-timeslot').html("Selected : "+ $(this).attr('data-date')+ ', ' + $(this).attr('data-value'));

    e.preventDefault();
});
</script>
