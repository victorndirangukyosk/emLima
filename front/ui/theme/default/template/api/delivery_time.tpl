<div class="">
       <div class="col-sm-3">
        <div class="form-group formgroup2">
            <div class="deliverytime-ddown css3-metro-dropdown">
                <select data-store-id='<?= $store["store_id"] ?>' id="dates_<?= $store["store_id"] ?>" name="dates[<?= $store['store_id'] ?>]" class="form-control" >
                    <option value="">Select Date</option>
                    <?php foreach ($dates as $d): ?>
                        <option value="<?php echo $d; ?>"> <?php echo $d ?></option>
                    <?php endforeach ?>
                </select>                
            </div>
            <small class="help-block" style="display: none;">Select a Date</small>
        </div>
    </div>    
    <div class="col-sm-3"></div>
    <div class="col-sm-3">
        <div class="form-group formgroup2">
            <div class="deliverytime-ddown css3-metro-dropdown">
                <select name="timeslot[<?= $store['store_id'] ?>]" class="form-control" id="timeslot_<?= $store["store_id"] ?>">
                    <option>Select Time Slot</option>
                </select>                
            </div>
            <small class="help-block" style="display: none;">Select a delivery time</small>
        </div>
    </div>
</div>
<!-- <script type="text/javascript">
$(document).on('change', '#dates_<?= $store["store_id"] ?>', function() {
    getTimeSlot(<?= $store['store_id'] ?>,$(this).val());
    
});
$(document).on('change', '#timeslot_<?= $store["store_id"] ?>', function() {
    saveTimeSlot(<?= $store['store_id'] ?>,$(this).val());
    
});
</script> -->