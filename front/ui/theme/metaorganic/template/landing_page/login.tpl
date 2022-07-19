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

.</style></head><body><link rel="stylesheet" type="text/css" href="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/css/iziToast.min.css"><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/iziToast.min.js" async defer="defer"></script><main><div class="auth-layout-wrap"><div class="auth-content"><div class="card o-hidden"><div id="login-view" class="row"><div class="col-md-12"><div class="p-4"><div class="auth-logo text-center mb-4"><a class="base_url" href="<?= BASE_URL."/index.php" ?>"><img src="<?= $logo ?>" class="logo" alt="KwikBasket Logo"><!--                                    <img src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/img/logo.svg" class="logo" alt="KwikBasket Logo">--></a></div><h1 class="mb-2 auth-card-header">Log In</h1><form><div class="form-group"><label for="email">Email address / Phone No</label> <input required type="email" class="form-control" id="login-email" aria-describedby="emailHelp" autocomplete="off"></div><div class="form-group"><label for="password">Password</label><div class="input-group mb-2 mr-sm-2"><input required type="password" class="form-control" id="login-password" autocomplete="off"><div class="input-group-prepend"><div class="input-group-text"><a href="#"><i class="fa fa-eye-slash" id="togglePassword"></i></a></div></div></div></div><button id="login-button" class="btn btn-primary mt-1 btn-block">LOGIN</button> <input type="hidden" value="0" id="lat"> <input type="hidden" value="0" id="lng"></form><div class="mt-3 text-center"><a href="<?= BASE_URL."/index.php?path=account/login/newCustomer" ?>" class="btn">Create An Account</a></div><div class="mt-3 text-center"><a href="#" id="forgot-password-btn" class="text-muted"><u>Forgot Password?</u></a></div></div></div></div><div id="forgot-password-view" class="row"><div class="col-md-12"><div class="p-4"><div class="auth-logo text-center mb-4"><a class="base_url" href="<?= BASE_URL."/index.php" ?>"><img src="<?= $logo ?>" class="logo" alt="KwikBasket Logo"><!--                                    <img src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/img/logo.svg" class="logo" alt="KwikBasket Logo">--></a></div><h1 class="mb-2 auth-card-header">Reset Password</h1><form><div class="form-group"><label for="reset-password-email">Account Email Address / Phone No</label> <input required type="email" class="form-control" id="reset-password-email"></div><button id="password-reset-button" class="btn btn-primary mt-1 btn-block">RESET PASSWORD</button></form></div></div></div><div id="otp-view" class="row"><div class="col-md-12"><div class="p-4"><div class="auth-logo text-center mb-4"><a class="base_url" href="<?= BASE_URL."/index.php" ?>"><img src="<?= $logo ?>" class="logo" alt="KwikBasket Logo"><!--                                    <img src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/img/logo.svg" class="logo" alt="KwikBasket Logo">--></a></div><h1 class="mb-2 auth-card-header">Verify OTP</h1><p class="text-muted">As you logged in from new IP address, We sent you an email with an OTP</p><form><div class="form-group"><label for="otp">Enter OTP</label> <input required type="text" class="form-control" id="otp-value"></div><button id="ip-otp-verify-button" class="btn btn-primary mt-1 btn-block">VERIFY</button></form></div></div></div></div><div class="container mt-5"><div class="row"><div class="col-md-12 text-center"><a href="<?= BASE_URL."/index.php" ?>" class="btn">Back To Homepage</a></div></div></div></div></div></main><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/jquery-3.2.1.min.js"></script><script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script><link href="https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet"><script src="https://code.jquery.com/jquery-migrate-3.0.0.min.js"></script><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/popper.min.js"></script><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/bootstrap.min.js"></script><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/scroll-out.min.js"></script><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/iziToast.min.js" async defer="defer"></script><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/gsap.min.js"></script><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/modernizr-custom.js"></script><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/scripts.min.js"></script><script src="front/ui/javascript/carousel.js" type="text/javascript"></script><link href="front/ui/stylesheet/carousel.css" rel="stylesheet"><script>const togglePassword = document.querySelector("#togglePassword");
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