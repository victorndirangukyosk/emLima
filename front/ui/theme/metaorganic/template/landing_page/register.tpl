<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><meta http-equiv="X-UA-Compatible" content="IE=edge"><meta name="viewport" content="width=device-width,initial-scale=1"><meta property="og:image" content="https://photos.app.goo.gl/avWcasi3g9C3yL4P7"><link rel="icon" type="image/svg" href="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/img/favicon.svg"><title><?= $store_name; ?> | Empowering African Farmers with Technology</title><link rel="preload" href="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/css/style.min.css" as="style"><link rel="stylesheet" href="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/css/reset.css"><link rel="stylesheet" href="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/css/bootstrap.min.css"><link rel="stylesheet" href="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/css/font-awesome.min.css"><link rel="stylesheet" type="text/css" href="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/css/iziToast.min.css"><link rel="stylesheet" href="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/css/style.min.css"><link rel="stylesheet" href="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/css/custom.css"><!-- Global site tag (gtag.js) - Google Analytics --><script async src="https://www.googletagmanager.com/gtag/js?id=G-RXVNTRRM14"></script><script>window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-RXVNTRRM14');</script><style>.newset {
      color: #fff;
      float: right;
      position: relative;
      text-decoration: none;
        margin-top: 0px;
  }
  .dropdownset{
    background: #de5d0f;
    width: 200px;
    float: right;
  }  

  .newset a {
    color: #fff;
    background: #de5d0f;
  } 
  .menuset .newset .dropdownset {
      background: 45264c;
    opacity: 0;
    min-width: 5rem;
      position: absolute;
    transition: all 0.5s ease;
      right: 0;

  }

  .menuset .newset:hover > .dropdownset,
  .menuset .newset:focus-within > .dropdownset,
  .menuset .newset .dropdownset:hover {
    visibility: visible !important;
    opacity: 1;
      }

  .menuset .newset .dropdownset .dropdownsetnew {
    width: 130%;
    padding: 0px 10px;
  }

.</style></head><body><script src="https://www.google.com/recaptcha/api.js" async defer="defer"></script><script type="text/javascript" src="https://maps.google.com/maps/api/js?key=<?= $this->config->get('config_google_api_key') ?>&libraries=places"></script><main><div class="auth-layout-wrap"><div class="auth-content"><div class="card o-hidden"><div id="registration-view" class="row"><div class="col-md-12"><div class="p-4"><div class="auth-logo text-center mb-4"><a class="base_url" href="<?= BASE_URL."/index.php" ?>"><img src="<?= $logo ?>" class="logo" alt="KwikBasket Logo"><!--                                    <img src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/img/logo.svg" class="logo" alt="KwikBasket Logo">--></a></div><h1 class="mb-2 auth-card-header">Sign Up</h1><form id="register-form"><div class="form-row"><div class="form-group col-md-4"><label for="register-first-name">First Name</label> <input required type="text" class="form-control" id="register-first-name"></div><div class="form-group col-md-4"><label for="register-last-name">Last Name</label> <input required type="text" class="form-control" id="register-last-name"></div><div class="form-group col-md-4"><label for="register-email">Email Address</label> <input type="email" class="form-control" id="register-email" required></div></div><div class="form-row"><div class="form-group col-md-4"><label for="register-phone">Phone</label><div class="input-group"><div class="input-group-prepend"><div class="input-group-text">+254</div></div><input required type="text" class="form-control" id="register-phone"></div></div><div class="form-group col-md-4"><label for="register-company-name">Company Name</label> <input required type="text" class="form-control" id="register-company-name"></div><div class="form-group col-md-4"><label for="register-company-address">Company Address</label> <input required type="text" class="form-control" id="register-company-address"></div></div><div class="form-row"><div class="form-group col-md-4"><label for="register-business-type">Business Type</label> <select class="form-control" id="register-business-type" required><option value="">Business Type</option> <?php foreach($customer_groups as $customer_group){?> <option value="<?=$customer_group['customer_group_id']?>"> <?=$customer_group['name']?> </option> <?php } ?> </select></div><div class="form-group col-md-4"><label for="register-building-name">Address Line 1</label> <input required type="text" class="form-control" id="register-building-name"> <input type="hidden" value="0" id="address_lat"> <input type="hidden" value="0" id="address_lng"></div><div class="form-group col-md-4"><label for="register-address-line">Address Line 2 (Optional)</label> <input type="text" class="form-control" id="register-address-line"></div></div><div class="form-row"><div class="form-group col-md-4"><label for="register-location">Geolocation</label> <input type="text" class="form-control" id="register-location"></div><div class="form-group col-md-4"><label for="register-password">Password</label> <input required type="password" class="form-control" id="register-password"></div><div class="form-group col-md-4"><label for="register-password-confirm">Confirm Password</label> <input required type="password" class="form-control" id="register-password-confirm"></div></div><div class="form-row"><div class="form-group col-md-12"><label for="city-id">City</label> <select class="form-control" id="city-id" name="city-id"><option value="">City</option> <?php foreach($cities as $city){?> <option value="<?=$city['city_id'];?>"> <?=$city['name'];?> </option> <?php } ?> </select></div></div><div class="form-row"><div class="form-group col-md-12"><label for="register-accountmanager-id">Account Manager Name</label> <select class="form-control" id="register-accountmanager-id" name="register-accountmanager-id"><option value="">Account Manager</option> <?php foreach($account_managers as $account_managers){?> <option value="<?=$account_managers['user_id']?>"> <?=$account_managers['firstname'].' '.$account_managers['lastname']?> </option> <?php } ?> </select></div></div><!--<div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="register-accountmanager-id">Account Manager Name</label>
                                        <input type="text" class="form-control" id="register-accountmanager-id" name="register-accountmanager-id" register_accountmanager_id="" placeholder="Search Account Manager">
                                    </div>
                                </div>--><div class="form-row mb-3"><div class="col-md-12 d-flex justify-content-center"><div class="g-recaptcha" data-sitekey="<?= $site_key ?>"></div></div></div><button id="register-button" class="btn btn-primary mt-1 btn-block">SIGN UP</button> <input type="hidden" value="0" id="lat"> <input type="hidden" value="0" id="lng"></form></div></div></div><div id="otp-view" class="row"><div class="col-md-12"><div class="p-4"><div class="auth-logo text-center mb-4"><a class="base_url" href="<?= BASE_URL."/index.php" ?>"><img src="<?= $logo ?>" class="logo" alt="KwikBasket Logo"><!--                                    <img src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/img/logo.svg" class="logo" alt="KwikBasket Logo">--></a></div><h1 class="mb-2 auth-card-header">Verify OTP</h1><p class="text-muted">We sent you an email and sms with OTP</p><form><div class="form-group"><label for="otp">Enter OTP</label> <input required type="text" class="form-control" id="otp-value"></div><button id="otp-verify-button" class="btn btn-primary mt-1 btn-block">VERIFY</button></form></div></div></div></div><div class="container mt-5"><div class="row"><div class="col-md-12 text-center"><a href="<?= BASE_URL."/index.php" ?>" class="btn">Back To Homepage</a></div></div></div></div></div></main><script type="text/javascript">var autocomplete;
    autocomplete = new google.maps.places.Autocomplete((document.getElementById('register-location')), {
        types: ['geocode'],
        componentRestrictions: {
            country: 'KE'
        }
    });

    google.maps.event.addListener(autocomplete, 'place_changed', function () {
        var near_place = autocomplete.getPlace();
        $("#address_lat").val(autocomplete.getPlace().geometry.location.lat());
        $("#address_lng").val(autocomplete.getPlace().geometry.location.lng());
        console.log(autocomplete.getPlace().geometry.location.lat());
        console.log(autocomplete.getPlace().geometry.location.lng());
    });</script><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/jquery-3.2.1.min.js"></script><script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script><link href="https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet"><script src="https://code.jquery.com/jquery-migrate-3.0.0.min.js"></script><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/popper.min.js"></script><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/bootstrap.min.js"></script><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/scroll-out.min.js"></script><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/iziToast.min.js" async defer="defer"></script><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/gsap.min.js"></script><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/modernizr-custom.js"></script><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/scripts.min.js"></script><script src="front/ui/javascript/carousel.js" type="text/javascript"></script><link href="front/ui/stylesheet/carousel.css" rel="stylesheet"><script>const togglePassword = document.querySelector("#togglePassword");
        const password = document.querySelector("#login-password");

        togglePassword.addEventListener("click", function () {
            // toggle the type attribute
            const type = password.getAttribute("type") === "password" ? "text" : "password";
            password.setAttribute("type", type);
            
           if($(this).hasClass('fa-eye-slash')){
           
          $(this).removeClass('fa-eye-slash');
          
          $(this).addClass('fa-eye');
          
            
        }else{
         
          $(this).removeClass('fa-eye');
          
          $(this).addClass('fa-eye-slash');  
          
        }
        });</script></body></html>