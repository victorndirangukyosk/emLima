<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><meta http-equiv="X-UA-Compatible" content="IE=edge"><meta name="viewport" content="width=device-width,initial-scale=1"><link rel="icon" type="image/svg" href="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/img/favicon.svg"><title><?= $store_name; ?> | Fresh Produce Supply Reimagined</title><link rel="preload" href="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/css/style.min.css" as="style"><link rel="stylesheet" href="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/css/reset.css"><link rel="stylesheet" href="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/css/bootstrap.min.css"><link rel="stylesheet" href="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/css/font-awesome.min.css"><link rel="stylesheet" type="text/css" href="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/css/iziToast.min.css"><link rel="stylesheet" href="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/css/style.min.css"></head><body><link rel="stylesheet" type="text/css" href="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/css/iziToast.min.css"><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/iziToast.min.js" async defer="defer"></script><main><div class="auth-layout-wrap"><div class="auth-content"><div class="card o-hidden"><div id="login-view" class="row"><div class="col-md-12"><div class="p-4"><div class="auth-logo text-center mb-4"><a class="base_url" href="<?= BASE_URL."/index.php" ?>"><img src="<?= $logo ?>" class="logo" alt="KwikBasket Logo"><!--                                    <img src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/img/logo.svg" class="logo" alt="KwikBasket Logo">--></a></div><h1 class="mb-2 auth-card-header">Log In</h1><form><div class="form-group"><label for="email">Email address</label> <input required type="email" class="form-control" id="login-email" aria-describedby="emailHelp" autocomplete="off"></div><div class="form-group"><label for="password">Password</label> <input required type="password" class="form-control" id="login-password" autocomplete="off"></div><button id="login-button" class="btn btn-primary mt-1 btn-block">LOGIN</button> <input type="hidden" value="0" id="lat"> <input type="hidden" value="0" id="lng"></form><div class="mt-3 text-center"><a href="<?= BASE_URL."/index.php?path=account/login/newCustomer" ?>" class="btn">Create An Account</a></div><div class="mt-3 text-center"><a href="#" id="forgot-password-btn" class="text-muted"><u>Forgot Password?</u></a></div></div></div></div><div id="forgot-password-view" class="row"><div class="col-md-12"><div class="p-4"><div class="auth-logo text-center mb-4"><a class="base_url" href="<?= BASE_URL."/index.php" ?>"><img src="<?= $logo ?>" class="logo" alt="KwikBasket Logo"><!--                                    <img src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/img/logo.svg" class="logo" alt="KwikBasket Logo">--></a></div><h1 class="mb-2 auth-card-header">Reset Password</h1><form><div class="form-group"><label for="reset-password-email">Account Email Address</label> <input required type="email" class="form-control" id="reset-password-email"></div><button id="password-reset-button" class="btn btn-primary mt-1 btn-block">RESET PASSWORD</button></form></div></div></div><div id="otp-view" class="row"><div class="col-md-12"><div class="p-4"><div class="auth-logo text-center mb-4"><a class="base_url" href="<?= BASE_URL."/index.php" ?>"><img src="<?= $logo ?>" class="logo" alt="KwikBasket Logo"><!--                                    <img src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/img/logo.svg" class="logo" alt="KwikBasket Logo">--></a></div><h1 class="mb-2 auth-card-header">Verify OTP</h1><p class="text-muted">As you logged in from new IP address, We sent you an email with an OTP</p><form><div class="form-group"><label for="otp">Enter OTP</label> <input required type="text" class="form-control" id="otp-value"></div><button id="ip-otp-verify-button" class="btn btn-primary mt-1 btn-block">VERIFY</button></form></div></div></div></div><div class="container mt-5"><div class="row"><div class="col-md-12 text-center"><a href="<?= BASE_URL."/index.php" ?>" class="btn">Back To Homepage</a></div></div></div></div></div></main><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/jquery-3.2.1.min.js"></script><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/popper.min.js"></script><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/bootstrap.min.js"></script><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/scroll-out.min.js"></script><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/iziToast.min.js" async defer="defer"></script><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/gsap.min.js"></script><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/modernizr-custom.js"></script><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/scripts.min.js"></script><!-- The core Firebase JS SDK is always required and must be listed first --><script src="https://www.gstatic.com/firebasejs/7.24.0/firebase-app.js"></script><!-- TODO: Add SDKs for Firebase products that you want to use
     https://firebase.google.com/docs/web/setup#available-libraries --><script src="https://www.gstatic.com/firebasejs/7.24.0/firebase-analytics.js"></script><script>// Your web app's Firebase configuration
    // For Firebase JS SDK v7.20.0 and later, measurementId is optional
    var firebaseConfig = {
      apiKey: "AIzaSyCKZWO_2THJJp61UWpkRCwVlRuMOHcVktE",
      authDomain: "emlima.firebaseapp.com",
      databaseURL: "https://emlima.firebaseio.com",
      projectId: "emlima",
      storageBucket: "emlima.appspot.com",
      messagingSenderId: "1441605741",
      appId: "1:1441605741:web:ead851e4b016daa0b1b764",
      measurementId: "G-L2HV2QE7XE"
    };
    // Initialize Firebase
    firebase.initializeApp(firebaseConfig);
    firebase.analytics();</script></body></html>