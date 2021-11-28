<?php if ($text_footer or $text_version) { ?>
	<footer id="footer">
	<?php if ($text_footer) { ?>
		<div class="footer-text">
			<?php echo $text_footer; ?>
		</div>
	<?php } ?>
	<?php if ($text_version) { ?>
		<!-- <div class="footer-version">
			<?php echo $text_version; ?>. 
			<b><?php echo $text_powered_by; ?> </b> &copy; WoWScripts
		</div> -->
	<?php } ?>
	</footer>
<?php } ?>
</div>


</body></html>