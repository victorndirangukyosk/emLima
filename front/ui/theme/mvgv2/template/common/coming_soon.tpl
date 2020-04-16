<!DOCTYPE html>
<html lang="en" class="">
<!-- <head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Gatoo - Coming Soon</title>
    <meta name="description" content="Gatoo - Brusells Grocery Delivery Coming Soon">
    <meta name="author" content="www.krafty.in">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" type="image/png" href="images/favboxes.png" />
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,800%7CRanga:700" rel="stylesheet">  
	<link rel="stylesheet" type="text/css" href="css/normalize.css" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="css/animate.css">	
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/theme.css?v=1.1.0">
    <link rel="stylesheet" href="css/responsive.css?v=1.0.0">
	<script src="js/modernizr.custom.js"></script>

</head> -->

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
    
    
    

    <!-- ori css -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,800%7CRanga:700" rel="stylesheet">  
    <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/mvgv2/css/coming_soon_normalize.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/mvgv2/css/coming_soon_animate.css">
    <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/mvgv2/css/coming_soon_style.css?v=1.0.1">
    <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/mvgv2/css/coming_soon_theme.css">
    <link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/theme/mvgv2/css/coming_soon_responsive.css">
    <script type="text/javascript" src="<?= $base;?>front/ui/theme/mvgv2/js/coming_soon_modernizr.custom.js"></script>

</head>


<body class="mrs-v16 mrs-bg">
	<!-- Page Loader Start -->
	<!-- <div class="marshall-loading-screen">
	    <div class="marshall-loading-icon">
	        <div class="marshall-loading-inner">
	        	<div class="marshall-load" data-name="G"></div>
	        </div>
	    </div>
	</div> -->

	<div class="marshall-container">
		<div class="marshall-col-6 marshall-col-content">
			<div class="marshall-logo">
				<img src="<?= $logo;?>" alt="<?php echo $this->config->get('config_name') ?> Logo">
			</div>
			<!-- <div class="marshall-content jquery-center"> -->
			<div class="marshall-content" style="top : calc(50% - 252px)">
				<!-- <h2 class="special_title fadeIn fast"><?php echo $text_heading; ?></h2> -->
				<h3><?php echo $text_heading; ?></h2>
				<h3><?php echo $text_subheading; ?></h3>
				<div class="default_mrs_newsletter mrs_inline_newsletter">
					<form id="marshall-forms" class="marshall-newsletter-content">
						
						<!-- <label style=""> <?php echo $text_subscribe_to_know ?> </label> -->
						<input id="marshall-email" type="email" name="email" autocomplete="off" placeholder="<?php echo $text_your_email; ?>" >

						<button class="marshall_submit" type="button" onclick="send_mail()"><?php echo $text_sen; ?></button>
					</form>

					<p id="message" style="width:80%;text-align:center;margin-bottom:0;"> </p>

				</div>	

				<h3><p id="interest" style="margin-top:180px;width:80%;text-align:center;"><?php echo $text_interested_to_earn ?></p></h3>
				<a class="shopper-button" target="_blank" href="<?php echo $shopper_link;?>/<?php echo $config_language;?>"><?php echo $text_become_shopper; ?></a>			
					
				
			</div>
			<div class="marshall-social-column">
				<p><?php echo $text_follow_us; ?> :</p>
				<ul class="marshall-social-links">

					 <li> <a href="<?= $facebook ?>" target="_blank" title="Facebook" ><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                    <li><a href="<?= $twitter ?>"  target="_blank" title="Twitter"><i class="fa fa-twitter" aria-hidden="true"></i> </a></li>
                    <li><a href="<?= $instagram ?>" target="_blank" title="Instagram"> <i class="fa fa-instagram" aria-hidden="true"></i> </a></li>
                    <li><a href="<?= $google ?>" target="_blank" title="Google Plus"> <i class="fa fa-google-plus" aria-hidden="true"></i> </a></li>
                    <li><a href="mailto:<?= $mail_to ?>" title="Email"><i class="fa fa-envelope" aria-hidden="true"></i></a></li>

				</ul>

				<ul class="marshall-social-links">
			
					<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="language">

						<?php foreach ($languages as $language) { ?>
							<span>| </span>
					    	<li>

					    		
						    	<?php if($language['code'] == $config_language) { ?>

						    		<a href="<?php echo $language['code']; ?>" style="color: #cc2e0b;"; title="<?php echo $language['name']; ?>" ><?php echo strtoupper($language['code']); ?>
						    		</a>


						    	<?php } else { ?>
							    	<a href="<?php echo $language['code']; ?>" title="<?php echo $language['name']; ?>" ><?php echo strtoupper($language['code']);?>
							    	</a>
						    	<?php } ?>

					    	</li>
					    <?php } ?>

				    	<input type="hidden" name="code" value="" />
						<input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />

					</form>	
				</ul>
							
			</div>
		</div>
		<div class="marshall-col-6 marshall-col-screen display_in_mobile">
			<div id="marshall-animate-area" data-hide="mrs-scaleDown" class="marshall-single-fit-thumb marshall-animate-content marshall-animate-content mrs-active marshall-fit-column" style="background-image: url(front/ui/theme/mvgv2/images/coming_soon_bg.jpg);">
			<div class="info_box">
				<h3><?php echo $text_door_step_delivery; ?></h3>
			</div>
			</div>
		</div>
	</div>


	<!-- All marshall js files -->

	

	<!-- <script type="text/javascript" src="js/jquery-3.1.1.min.js"></script>
	<script src="js/classie.js"></script>
	<script src="js/uiMorphingButton_fixed.js"></script>
	<script src="js/form-init.js"></script>
	<script src="js/jquery.ajaxchimp.min.js"></script>
	<script type="text/javascript" src="js/main.js"></script> -->

	
	<script type="text/javascript" src="<?= $base;?>front/ui/theme/mvgv2/js/coming_soon_jquery-3.1.1.min.js"></script>

	<script type="text/javascript" src="<?= $base;?>front/ui/theme/mvgv2/js/coming_soon_classie.js"></script>
	<script type="text/javascript" src="<?= $base;?>front/ui/theme/mvgv2/js/coming_soon_uiMorphingButton_fixed.js"></script>
	<script type="text/javascript" src="<?= $base;?>front/ui/theme/mvgv2/js/coming_soon_form-init.js"></script>

	<script type="text/javascript" src="<?= $base;?>front/ui/theme/mvgv2/js/coming_soon_jquery.ajaxchimp.min.js"></script>

	<script type="text/javascript" src="<?= $base;?>front/ui/theme/mvgv2/js/coming_soon_main.js"></script>

	

	<script type="text/javascript">
		$(document).ready(function(){

			// Language
			$('#language a').on('click', function(e) {

				console.log("languae change click");
				e.preventDefault();

				$('#language input[name=\'code\']').attr('value', $(this).attr('href'));

				$('#language').submit();
			});


		});	

		function validateEmail(email) {
		  var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		  return re.test(email);
		}

		


		function send_mail() {
			
			console.log("send_mail fn");

			$('#message').text('');

			if (!validateEmail($('#marshall-email').val())) {

				//alert("fwe");
				$('#message').text('<?php echo $text_invalid_email; ?>');
				$('#interest').css('margin-top','71px');
				//$('#message').css('color','red');

			  	return false;  
			} else {
				
				$('#interest').css('margin-top','71px');
			}

			console.log("send_mail fn passed");

			data = {
	            email : $('#marshall-email').val()
	        }

	        $('.marshall_submit').text('<?php echo $text_sending; ?>');

			
			$.ajax({
                url: '<?php echo $send_mail_action ?>',
                type: 'post',
                data:data,
                dataType: 'json',
		        beforeSend: function() {
		            
		        },
		        complete: function() {
		        	
		            //location = location
		        },
                success: function(json) {
                	console.log(json);
                	$('#message').text(json['message']);
                	$('.marshall_submit').text('<?php echo $text_sen; ?>');
                	
                	if(json['status']) {
                		$('#message').css('color','green');
                		$('#interest').css('margin-top','71px');
                		$('#marshall-email').val('');                		
                	} else {
                		$('#message').css('color','red');
                		$('#interest').css('margin-top','71px');
                	}
                	console.log(json);
                }
            });

            return false;
		}

	</script>
	
</body>
</html>