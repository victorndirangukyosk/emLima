
    <!--Added CSS Style -->

	<link rel="stylesheet" type="text/css" href="<?= $base; ?>front/ui/theme/organic/stylesheet/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?= $base; ?>front/ui/theme/organic/stylesheet/font-awesome.css" media="all">
	<link rel="stylesheet" type="text/css" href="<?= $base; ?>front/ui/theme/organic/stylesheet/revslider.css" >
	<link rel="stylesheet" type="text/css" href="<?= $base; ?>front/ui/theme/organic/stylesheet/owl.carousel.css">
	<link rel="stylesheet" type="text/css" href="<?= $base; ?>front/ui/theme/organic/stylesheet/owl.theme.css">
	<link rel="stylesheet" type="text/css" href="<?= $base; ?>front/ui/theme/organic/stylesheet/jquery.bxslider.css">
	<link rel="stylesheet" type="text/css" href="<?= $base; ?>front/ui/theme/organic/stylesheet/jquery.mobile-menu.css">
	<link rel="stylesheet" type="text/css" href="<?= $base; ?>front/ui/theme/organic/stylesheet/style.css" media="all">
	<link rel="stylesheet" type="text/css" href="<?= $base; ?>front/ui/theme/organic/stylesheet/responsive.css" media="all">
	<link href="https://fonts.googleapis.com/css?family=Merriweather:300,300i,400,400i,700" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Oswald:300,400,500,600,700" rel="stylesheet">
	<link href='https://fonts.googleapis.com/css?family=Open+Sans:700,600,800,400' rel='stylesheet' type='text/css'>
	<link href="https://fonts.googleapis.com/css?family=Rubik:400,400i,500,500i,700,700i,900" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
 
			<!-- JavaScript start --> 
		<!--<script src="<?= $base; ?>front/ui/theme/organic/js/jquery.min.js"></script>-->
		 <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="<?= $base; ?>front/ui/theme/organic/js/bootstrap.min.js"></script>
		<script src="<?= $base; ?>front/ui/theme/organic/js/parallax.js"></script> 
		<script src="<?= $base; ?>front/ui/theme/organic/js/revslider.js"></script> 
		<script src="<?= $base; ?>front/ui/theme/organic/js/common.js"></script>
		<script src="<?= $base; ?>front/ui/theme/organic/js/jquery.bxslider.min.js"></script>
		<script src="<?= $base; ?>front/ui/theme/organic/js/owl.carousel.min.js"></script> 
		<script src="<?= $base; ?>front/ui/theme/organic/js/jquery.mobile-menu.min.js"></script> 
		<script src="<?= $base; ?>front/ui/theme/organic/js/countdown.js"></script> 
		
		<!-- JavaScript Emd --> 
		
		<script type="text/javascript">
       /*$(document).ready(function() {
        var divs = $('.mydivs>div');
        var now = 0; // currently shown div
        divs.hide().first().show();
        $("button[name=next]").click(function(e) {
            divs.eq(now).hide();
            now = (now + 1 < divs.length) ? now + 1 : 0;
            divs.eq(now).show(); // show next
        });
        $("button[name=prev]").click(function(e) {
            divs.eq(now).hide();
            now = (now > 0) ? now - 1 : divs.length - 1;
            divs.eq(now).show(); // or .css('display','block');
            //console.log(divs.length, now);
        });
       }); */
    </script>
	<script>
        jQuery(document).ready(function(){
            jQuery('#thm-rev-slider').show().revolution({
                dottedOverlay: 'none',
                delay: 5000,
                startwidth: 0,
                startheight:710,

                hideThumbs: 200,
                thumbWidth: 200,
                thumbHeight: 50,
                thumbAmount: 2,

                navigationType: 'thumb',
                navigationArrows: 'solo',
                navigationStyle: 'round',

                touchenabled: 'on',
                onHoverStop: 'on',
                
                swipe_velocity: 0.7,
                swipe_min_touches: 1,
                swipe_max_touches: 1,
                drag_block_vertical: false,
            
                spinner: 'spinner0',
                keyboardNavigation: 'off',

                navigationHAlign: 'center',
                navigationVAlign: 'bottom',
                navigationHOffset: 0,
                navigationVOffset: 20,

                soloArrowLeftHalign: 'left',
                soloArrowLeftValign: 'center',
                soloArrowLeftHOffset: 20,
                soloArrowLeftVOffset: 0,

                soloArrowRightHalign: 'right',
                soloArrowRightValign: 'center',
                soloArrowRightHOffset: 20,
                soloArrowRightVOffset: 0,

                shadow: 0,
                fullWidth: 'on',
                fullScreen: 'on',

                stopLoop: 'off',
                stopAfterLoops: -1,
                stopAtSlide: -1,

                shuffle: 'off',

                autoHeight: 'on',
                forceFullWidth: 'off',
                fullScreenAlignForce: 'off',
                minFullScreenHeight: 0,
                hideNavDelayOnMobile: 1500,
            
                hideThumbsOnMobile: 'off',
                hideBulletsOnMobile: 'off',
                hideArrowsOnMobile: 'off',
                hideThumbsUnderResolution: 0,

                hideSliderAtLimit: 0,
                hideCaptionAtLimit: 0,
                hideAllCaptionAtLilmit: 0,
                startWithSlide: 0,
                fullScreenOffsetContainer: ''
            });
        });
        </script> 
<script>
    function HideMe()
    {
        jQuery('.popup1').hide();
        jQuery('#fade').hide();
    }
</script> 

    
<script>    
    jQuery(document).ready(function($) {  

// site preloader -- also uncomment the div in the header and the css style for #preloader
$(window).load(function(){
	$('#preloader').fadeOut('slow',function(){$(this).remove();});
});

});
   </script>