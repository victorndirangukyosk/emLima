<div class="row">

    <div id="show_message" style="display: none">
        
    </div>
    <div class="col-md-12">
        
        

    <!-- <?php

        echo "Today is " . date("Y/m/d") . "<br>";
        
        echo "The time is " . date("h:i:sa");
    ?> -->
    
        <div>
            <ul class="nav nav-tabs nav-justified" role="tablist" data-store-id='<?= $store["store_id"] ?>' id="dates_<?= $store['store_id'] ?>" name="dates[<?= $store['store_id'] ?>]">

                <?php $i = 0; foreach ($dates as $d): ?>
                        <?php if($i ==  0 ) { ?>
                        <li role="presentation" class="active" id="dates_selected" data-value="<?php echo $d ?>"  ><a href="#<?= $store['store_id'] ?>_<?php echo $d ?>" aria-controls="<?php echo $d ?>" role="tab" data-toggle="tab"><?php echo date("m-d-Y", strtotime($d));  ?></a></li>
                        <?php } else { ?>
                            <li role="presentation" id="dates_selected" data-value="<?php echo $d ?>" ><a href="#<?= $store['store_id'] ?>_<?php echo $d ?>" aria-controls="<?php echo $d ?>" role="tab" data-toggle="tab"><?php echo date("m-d-Y", strtotime($d)); ?></a></li>
                        <?php } $i++; ?>

                <?php endforeach ?>
            </ul>
            <div class="tab-content" style="    padding-top: 0px !important;">
                
                <?php $i = 0; foreach ($timeslots as $key => $values){ ?>

                    <?php if($i ==  0 ) { ?>

                        <div role="tabpanel" class="tab-pane active" id="<?= $store['store_id'] ?>_<?= $key ?>">
                            <div class="time-slots">
                                <ul class="list-group">
                                    <?php if(count($values) > 0 ) { ?>
                                        <?php foreach ($values as $value): ?>
                                            <li class="list-group-item timeslot-selected" id="time_selected" data-value="<?= $value['timeslot']?>" data-date="<?= $key ?>" data-store="<?= $store['store_id'] ?>" >
                                                    <input style="    margin-top: -15px;cursor:pointer" type="radio" name="radAnswer_<?= $store['store_id'] ?>" />
                                                <label  style="    margin-top: -12px;cursor:default"  class="radio-inline"><?= $value['timeslot']?>
                                                    
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
                                               <input style="    margin-top: -15px;cursor:pointer" type="radio" name="radAnswer_<?= $store['store_id'] ?>" />
                                                 <label  style="    margin-top: -12px;cursor:default;"  class="radio-inline"><?= $value['timeslot']?>
                                                   
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

    <div>
        <input type="hidden" id="delivery_date" value="">
        <input type="hidden" id="delivery_timeslot" value="">

        <center> <button type="button" class="btn btn-primary" onclick="updateTimeslot()"> Update </button></center>
    </div>
</div>
<script type="text/javascript">

$('.timeslot-selected').unbind().click(function(e) {
    
    console.log($(this));

    $('#delivery_date').val($(this).attr('data-date'));
    $('#delivery_timeslot').val($(this).attr('data-value'));

    //saveEditTimeSlot(<?= $order_id ?>,$(this).attr('data-value'),$(this).attr('data-date'));
    $(this).children().children().prop("checked", true);
    e.preventDefault();
});

function updateTimeslot() {
    saveEditTimeSlot(<?= $order_id ?>,$('#delivery_date').val(),$('#delivery_timeslot').val());
}

</script>
