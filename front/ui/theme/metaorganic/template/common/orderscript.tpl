<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="kdt:page" content="home-page"> 

<meta http-equiv="content-language" content="<?= $config_language?>">
    
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content= "<?php echo $keywords; ?>" />
<?php } ?>
<title><?= $heading_title ?></title>
<?php if ($icon) { ?>
        <link href="<?php echo $icon; ?>" rel="icon" />
<?php } ?>
	
<link rel="shortcut icon" href="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/images/favicon.png" type="image/x-icon">
<link rel="icon" href="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/images/favicon.png" type="image/x-icon">

<!-- CSS Style -->
<link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/stylesheet/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/stylesheet/font-awesome.css" media="all">
<link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/stylesheet/revslider.css" >
<link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/stylesheet/owl.carousel.css">
<link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/stylesheet/owl.theme.css">
<link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/stylesheet/jquery.bxslider.css">
<link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/stylesheet/jquery.mobile-menu.css">
<link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/stylesheet/style.css" media="all">
<link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/stylesheet/responsive.css" media="all">
<link href="https://fonts.googleapis.com/css?family=Merriweather:300,300i,400,400i,700" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Oswald:300,400,500,600,700" rel="stylesheet">
<link href='http://fonts.googleapis.com/css?family=Open+Sans:700,600,800,400' rel='stylesheet' type='text/css'>
<link href="https://fonts.googleapis.com/css?family=Rubik:400,400i,500,500i,700,700i,900" rel="stylesheet">
</head>

<body>
<div id="preloader"></div>
<div id="page">
<?php if(count($products)>0){?>
<div class="container"><table id="productsToCart" style="width:48%;" class="table table-bordered table-striped table-responsive">
      <h1>Products found in portal</h1> 
	  <span><button type="button" style="background-color:blue;" onclick="AddToCart();" class="btn btn-primary">Proceed To Cart</button></span>
	   <thead>
       <tr>
        <th>Product Name</th>
        <th>Product Id</th>
        <th>Qty</th>
      </tr>
      </thead>
	  <tbody>
	  <?php foreach($products as $product){?>
			  <tr class="info" data-product-id="<?php echo $product['product_id'];?>" data-quantity="<?php echo $product['quantity'];?>">
				    <th><?php echo $product['product_name'];?></th>
					<th><?php echo $product['product_id'];?></th>
					<th><?php echo $product['quantity'];?></th>
			   </tr>
		  <?php } ?>
	  </tbody>
	  </table></div>

<?php } ?>
<?php if(count($notFoundProducts)>0){?>
<div class="container">
<table style="width:48%;" class="table table-bordered table-striped table-responsive">
 <h1>Products found in portal</h1>
      <thead>
       <tr>
        <th>Product Name</th>
      </tr>
      </thead>
	  <tbody>
	  <?php foreach($notFoundProducts as $product){?>
			  <tr>
				    <th><?php echo $product;?></th>
			   </tr>
		  <?php } ?>
	  </tbody>
</table>
</div>
<?php } ?>
<?php if((!isset($notFoundProducts)) && (!isset($products)) ){?>
<form action="<?php echo BASE_URL.'/index.php?path=common/orderscript/uploadOrderExcelsubmit'?>" method="post" enctype="multipart/form-data">
<input type="file"  name="filepath" id="filepath"/></td><td><input type="submit" name="SubmitButton"/>
<?php } ?>
</div>

<!-- JavaScript --> 
<script src="<?= $base;?>front/ui/theme/mvgv2/js/jquery.min.js"></script>
<script src="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/js/bootstrap.min.js"></script> 
<script src="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/js/parallax.js"></script> 
<script src="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/js/revslider.js"></script> 
<script src="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/js/common.js"></script> 
<script src="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/js/jquery.bxslider.min.js"></script> 
<script src="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/js/owl.carousel.min.js"></script> 
<script src="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/js/jquery.mobile-menu.min.js"></script> 
<script src="<?= $base;?>front/ui/theme/metaorganic/assets_newhome/js/countdown.js"></script> 
	 <script src="<?= $base;?>front/ui/theme/metaorganic/javascript/common.js?v=2.0.7" type="text/javascript"></script>
	 <script src="https://www.google.com/recaptcha/api.js" type="text/javascript"></script>
	  <script src="<?= $base;?>front/ui/theme/mvgv2/js/jquery.maskedinput.js" type="text/javascript"></script>
	  <script src="<?= $base;?>front/ui/javascript/home.js?v=1.0.4"></script> 
	 <script type="text/javascript" src="https://maps.google.com/maps/api/js?key=<?= $this->config->get('config_google_api_key') ?>&libraries=places"></script>
     <script type="text/javascript" src="<?= $base;?>admin/ui/javascript/map-picker/js/locationpicker.jquery.js?v=2.2"></script>
<script>
jQuery('.zipcode-enter').focus();

      /* jQuery('#us1').locationpicker({
                location: {
                    latitude: 0,
                    longitude: 0                },  
                radius: 0,
                inputBinding: {
                    latitudeInput: jQuery('input[name="latitude"]'),
                    longitudeInput: jQuery('input[name="longitude"]'),
                    locationNameInput: jQuery('.LocalityId')
                },
                enableAutocomplete: true,
                zoom:13

            }); 


            function saveLatLng() {
                $('#GMapPopup').modal('hide');

                $('.LocalityId').val($('.LocalityId').val());
            }*/
			
    jQuery(function($) {
        console.log(" fax-number mask");
       jQuery("#fax-number").mask("<?= $taxnumber_mask_number ?>",{autoclear:false,placeholder:"<?= $taxnumber_mask ?>"});
    });
	
	  jQuery(function($){
            console.log("mask");
           jQuery("#searchTextField").mask("<?= $zipcode_mask_number ?>",{autoclear:false,placeholder:"<?= $zipcode_mask ?>"});
        });
			
jQuery('input[name="telephone"]').keyup(function(e)
                                {
  if (/\D/g.test(this.value))
  {
    // Filter non-digits from input value.
    this.value = this.value.replace(/\D/g, '');
  }
});
 jQuery("ul#nav >li").click(function() {
       var minus = 190;
       var id = jQuery(this).attr('data-link');
 
      if(jQuery( "#header" ).hasClass( "sticky-header-bar") == true){
        minus = 100;
      }
	  if(id != 'home'){
		 // jQuery('html, body').animate({scrollTop: jQuery("#"+id).offset().top-minus},2000);
	     jQuery("html, body").animate({ scrollTop: 0 }, 2000);

	  }else{
	     jQuery("html, body").animate({ scrollTop: 0 }, 2000);
	  }
   
});
        jQuery(document).ready(function(){
            jQuery('#thm-rev-slider').show().revolution({
                dottedOverlay: 'none',
                delay: 5000,
                startwidth: 0,
                startheight:750,

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
<!-- Hot Deals Timer 1--> 
<script>
      var dthen1 = new Date("12/25/17 11:59:00 PM");
      start = "08/04/15 03:02:11 AM";
      start_date = Date.parse(start);
      var dnow1 = new Date(start_date);
      if (CountStepper > 0)
      ddiff = new Date((dnow1) - (dthen1));
      else
      ddiff = new Date((dthen1) - (dnow1));
      gsecs1 = Math.floor(ddiff.valueOf() / 1000);
      
      var iid1 = "countbox_1";
      CountBack_slider(gsecs1, "countbox_1", 1);
    </script>
    
<script>    
    jQuery(document).ready(function($) {  

// site preloader -- also uncomment the div in the header and the css style for #preloader
$(window).load(function(){
	$('#preloader').fadeOut('fast',function(){$(this).remove();});
});

});

jQuery(document).delegate('#contactus', 'click', function() {
    console.log("contactus click");

    var text = jQuery('.contact-modal-text').html();
    jQuery('.contact-modal-text').html('');
    jQuery('.contact-loader').show();


    jQuery.ajax({
        url: 'index.php?path=information/contact',
        type: 'post',
        data: jQuery('#contactForm').serialize(),
        dataType: 'json',
        async: true,
        success: function(json) {
            console.log(json);
            if (json['status']) {
                jQuery('#contactus-message').html('');
                jQuery('#contactus-success-message').html("<p style='color:green'>"+json['text_message']+"</p>");

                jQuery('.contact-modal-text').html(text);
                jQuery('.contact-loader').hide();

                
                jQuery('#contactusModal').find('form').trigger('reset');
				jQuery('#contactForm').trigger("reset");
                
            } else {
                $error = '';

                if(json['error_email']){
                    $error += json['error_email']+'<br/>';
                }
				if(json['error_name']){
                    $error += json['error_name']+'<br/>';
                }
				
				if(json['error_company']){
                    $error += json['error_company']+'<br/>';
                }
				
                if(json['error_enquiry']){
                    //$error += json['error_enquiry']+'<br/>';
				    $error +='Comments must be between 10 and 3000 characters!<br/>';
                }
              
                jQuery('.contact-modal-text').html(text);
                jQuery('.contact-loader').hide();

                jQuery('#contactus-message').html("<p style='color:red'>"+$error+"</p>");
            }
        }
    });
});

function showHide(formId){
    //jQuery('#preloader').show();
	jQuery('#'+formId).show();
    jQuery("html, body").animate({ scrollTop: 200 }, 2000);
    if(formId == 'register-form-div'){
        jQuery('#login-form-div').hide(1000);
    }else{
	  jQuery('#register-form-div').hide(1000);
	}
	//jQuery('#preloader').fadeOut('fast',function(){$(this).remove();});
	

}

function AddToCart(){
  var numItems = jQuery('tr.info').length - parseInt(1);
  jQuery("table#productsToCart tr.info").each(function(i)
	{
	    var product_id = jQuery(this).attr('data-product-id');
		var quantity = jQuery(this).attr('data-quantity');
		var store_id =75;
		var variation_id =0;
		console.log(jQuery(this).attr('data-product-id'));
		console.log(jQuery(this).attr('data-quantity'));
		
			jQuery.ajax({
				url: 'index.php?path=checkout/cart/add',
				type: 'post',
				data: 'variation_id='+variation_id+'&product_id=' + product_id + '&quantity=' + (typeof(quantity) != 'undefined' ? quantity : 1)+'&store_id=' + store_id,
				dataType: 'json',
				beforeSend: function() {
					//$('#cart > button').button('loading');
				},
				complete: function() {
					//$('#cart > button').button('reset');
				},			
				success: function(json) {
					console.log(json);
					console.log("jsonxxxxx",i);
					if( i == numItems){
					  alert('Product added in cart');
					  window.location = window.location.origin+window.location.pathname+'?path=checkout/checkoutitems';
					}
				}
			});
		
   });
}

   </script>
</body>
</html>
