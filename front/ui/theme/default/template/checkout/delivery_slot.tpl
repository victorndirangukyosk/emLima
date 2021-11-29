<option value="">Select Time Slot</option>
<?php foreach ($timeslot as $ts): ?>
	<option value="<?php echo $ts['timeslot'] ?>"><?php echo $ts['timeslot'] ?></option>
<?php endforeach ?>

