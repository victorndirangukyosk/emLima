<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><meta http-equiv="X-UA-Compatible" content="IE=edge"><meta name="viewport" content="width=device-width,initial-scale=1"><meta property="og:image" content="https://photos.app.goo.gl/avWcasi3g9C3yL4P7"><link rel="icon" type="image/svg" href="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/img/favicon.svg"><title><?= $store_name; ?> | Fresh Produce Supply Reimagined</title><link rel="preload" href="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/css/style.min.css" as="style"><link rel="stylesheet" href="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/css/reset.css"><link rel="stylesheet" href="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/css/bootstrap.min.css"><link rel="stylesheet" href="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/css/font-awesome.min.css"><link rel="stylesheet" type="text/css" href="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/css/iziToast.min.css"><link rel="stylesheet" href="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/css/style.min.css"><link rel="stylesheet" href="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/css/custom.css"></head><body><nav class="navbar navbar-expand-lg fixed-top"><div class="container"><a class="navbar-brand" href="<?= BASE_URL."/index.php" ?>"><!--        <img src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/img/logo.svg" class="logo" alt="KwikBasket Logo">--> <img src="<?= $logo ?>" class="logo" alt="KwikBasket Logo"> </a><button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button><div class="collapse navbar-collapse justify-content-between align-items-center w-100" id="navbarNav"><ul class="navbar-nav mx-auto text-center"><li class="nav-item"><a class="nav-link" href="<?= BASE_URL."/index.php?path=common/home/homepage#what-we-do" ?>">What We Do</a></li><li class="nav-item"><a class="nav-link" href="<?= BASE_URL."/index.php?path=common/home/homepage#existing-clients" ?>">Customers</a></li><li class="nav-item"><a class="nav-link" href="<?= BASE_URL."/index.php?path=common/home/farmers" ?>">Farmers</a></li><li class="nav-item"><a class="nav-link" href="<?= BASE_URL."/index.php?path=common/home/partners" ?>">Partners</a></li><li class="nav-item"><a class="nav-link" href="<?= BASE_URL."/index.php?path=common/home/covid19" ?>">COVID-19</a></li></ul><ul class="nav navbar-nav flex-row justify-content-center flex-nowrap"><li class="nav-item mr-3"><a href="<?= BASE_URL."/index.php?path=account/login/customer" ?>" class="btn btn-outline-secondary">Login</a></li><li class="nav-item"><a href="<?= BASE_URL."/index.php?path=account/login/newCustomer" ?>" class="btn btn-primary">Register</a></li></ul></div></div></nav><main><section id="careers"><div class="container"><div class="row"><div class="section-header col-md-12"><h4 class="section-title"> <?= $job_category ?> </h4><p class="section-subtitle">Job Type : <?= $job_type ?> </p></div></div><div class="row mt-4"><div class="section-header col-md-12"><!--<h4 class="section-title">Roles & Responsibilities</h4>--></div><div class="col-md-12 text-center"> <?= html_entity_decode($roles_responsibilities) ?> </div></div><div class="row mt-5"><div class="section-header col-md-12"><h4 class="section-title">Apply for this position</h4></div><div class="col-md-12 mt-3"><label id="success" style="color:green;"><?= $message ?></label> <label id="error" style="color:red;"><?= $errormessage ?></label><form id="careers-form" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal"><div class="form-row"><div class="form-group col-md-4"><label for="careers-first-name">Full Name</label> <input required type="text" class="form-control rounded-0" id="careers-first-name" name="careers-first-name"></div><div class="form-group col-md-4"><label for="careers-email">Email</label> <input required type="email" class="form-control rounded-0" id="careers-email" name="careers-email"></div><div class="form-group col-md-4"><label for="careers-phone-number">Phone Number</label> <input required type="number" class="form-control rounded-0" id="careers-phone-number" name="careers-phone-number"></div></div><div class="form-row"><div class="form-group col-md-12"><label for="careers-cover-letter">Cover Letter</label> <textarea required id="careers-cover-letter" name="careers-cover-letter" cols="30" rows="10" class="form-control"></textarea></div></div><div class="form-row"><div class="form-group col-md-12"><label for="careers-resume">Upload CV/Resume</label> <input required type="file" accept=".doc,.docx" class="form-control rounded-0" id="careers-resume" name="careers-resume"> <small>Allowed Type(s): .pdf, .doc, .docx</small></div></div><div class="form-row"><div class="form-group col-md-4"><input hidden required type="text" value=" <?= ($job_id) ?> " class="form-control rounded-0" id="careers-job-id" name="careers-job-id"></div><div class="form-group col-md-4"><input hidden required type="text" value=" <?= ($job_category) ?> " class="form-control rounded-0" id="careers-job-position" name="careers-job-position"></div></div><!--<div class="form-row mb-3">
                                                            <div class="col-md-12 d-flex justify-content-center">
                                                                    <div class="g-recaptcha" data-sitekey="<?= $site_key ?>"></div>
                                                            </div>
                                                    </div>--><div class="form-row"><div class="col-md-12 text-center"><button id="careers-submit-button1" type="submit" class="btn btn-secondary rounded-0 mt-4 px-5 py-2">SUBMIT</button></div></div></form></div></div></div></section></main><footer><div id="footer" class="footers pt-3 pb-3"><div class="container pt-5"><div class="row"><div class="col-xs-12 col-sm-6 col-md-4 footers-one"><div class="footers-logo"><!--              <img src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/img/logo.svg" class="logo" alt="KwikBasket Logo">--> <img src="<?= $logo ?>" class="logo" alt="KwikBasket Logo"></div><div class="footers-info mt-3"><p>3rd Floor, Heritan House<br>Woodlands Road Nairobi, Kenya<br><br>+254780703586<br><a href="mailto:hello@kwikbasket.com?subject = Feedback&body = Message">hello@kwikbasket.com</a></p></div><div class="social-icons mb-3"><a target="_blank" href="https://www.facebook.com/kwikbasket"><i id="social-fb" class="fa fa-facebook-square fa-2x social"></i></a><!-- <a target="_blank" href="#"><i id="social-tw" class="fa fa-twitter-square fa-2x social"></i></a> --> <a target="_blank" href="https://www.linkedin.com/company/kwikbasket"><i id="social-li" class="fa fa-linkedin-square fa-2x social"></i></a> <a target="_blank" href="https://www.instagram.com/kwikbasket/"><i id="social-in" class="fa fa-instagram fa-2x social"></i></a></div></div><div class="col-xs-12 col-sm-6 col-md-2 footers-two"><h5 class="primary-text">Company</h5><ul class="list-unstyled"><li><a href="<?= BASE_URL."/index.php?path=common/home/about_us" ?>">About Us</a></li><li><a href="<?= BASE_URL."/index.php?path=common/home/careers" ?>">Careers</a></li></ul></div><div class="col-xs-12 col-sm-6 col-md-2 footers-three"><h5 class="primary-text">Explore</h5><ul class="list-unstyled"><li><a href="<?= BASE_URL."/index.php?path=common/home/homepage#order-cycle" ?>">Customers</a></li><li><a href="<?= BASE_URL."/index.php?path=common/home/farmers" ?>">Farmers</a></li><li><a href="<?= BASE_URL."/index.php?path=common/home/partners" ?>">Partners</a></li></ul></div><div class="col-xs-12 col-sm-6 col-md-2 footers-four"><h5 class="primary-text">Resources</h5><ul class="list-unstyled"><li><a href="<?= BASE_URL."/index.php?path=common/home/technology" ?>">Technology</a></li><li><a href="<?= BASE_URL."/index.php?path=common/home/faq" ?>">FAQs</a></li><li><a href="<?= BASE_URL."/index.php?path=common/home/blog" ?>">Blog & Media</a></li></ul></div><div class="col-xs-12 col-sm-6 col-md-2 footers-five"><h5 class="primary-text">Reach Out</h5><ul class="list-unstyled"><li><a target="_blank" href="https://www.facebook.com/kwikbasket">Facebook</a></li><li><a target="_blank" href="https://www.linkedin.com/company/kwikbasket">LinkedIn</a></li><li><a target="_blank" href="https://www.instagram.com/kwikbasket/">Instagram</a></li></ul></div></div></div></div><div class="copyright border"><div class="container"><div class="row pt-3 pb-1"><div class="content-container col-md-12"><p class="text-muted">© 2021 KwikBasket | All Rights Reserved</p><ul><li><a href="<?= BASE_URL."/index.php?path=common/home/terms_and_conditions" ?>">Terms & Conditions</a></li><li><a href="<?= BASE_URL."/index.php?path=common/home/privacy_policy" ?>">Privacy Policy</a></li></ul></div></div></div></div></footer><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/jquery-3.2.1.min.js"></script><script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script><link href="https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet"><script src="https://code.jquery.com/jquery-migrate-3.0.0.min.js"></script><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/popper.min.js"></script><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/bootstrap.min.js"></script><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/scroll-out.min.js"></script><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/iziToast.min.js" async defer="defer"></script><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/gsap.min.js"></script><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/modernizr-custom.js"></script><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/scripts.min.js"></script><!-- The core Firebase JS SDK is always required and must be listed first --><script src="https://www.gstatic.com/firebasejs/7.24.0/firebase-app.js"></script><!-- TODO: Add SDKs for Firebase products that you want to use
     https://firebase.google.com/docs/web/setup#available-libraries --><script src="https://www.gstatic.com/firebasejs/7.24.0/firebase-analytics.js"></script><script src="front/ui/javascript/carousel.js" type="text/javascript"></script><link href="front/ui/stylesheet/carousel.css" rel="stylesheet"><script>// Your web app's Firebase configuration
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