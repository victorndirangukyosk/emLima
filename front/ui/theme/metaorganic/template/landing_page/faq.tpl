<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><meta http-equiv="X-UA-Compatible" content="IE=edge"><meta name="viewport" content="width=device-width,initial-scale=1"><link rel="icon" type="image/svg" href="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/img/favicon.svg"><title>KwikBasket | Fresh Produce Supply Reimagined</title><link rel="preload" href="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/css/style.min.css" as="style"><link rel="stylesheet" href="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/css/reset.css"><link rel="stylesheet" href="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/css/bootstrap.min.css"><link rel="stylesheet" href="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/css/font-awesome.min.css"><link rel="stylesheet" type="text/css" href="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/css/iziToast.min.css"><link rel="stylesheet" href="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/css/style.min.css"></head><body><nav class="navbar navbar-expand-lg fixed-top"><div class="container"><a class="navbar-brand" href="<?= BASE_URL."/index.php" ?>"><img src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/img/logo.svg" class="logo" alt="KwikBasket Logo"> </a><button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button><div class="collapse navbar-collapse justify-content-between align-items-center w-100" id="navbarNav"><ul class="navbar-nav mx-auto text-center"><li class="nav-item"><a class="nav-link" href="<?= BASE_URL."/index.php?path=common/home/homepage#what-we-do" ?>">What We Do</a></li><li class="nav-item"><a class="nav-link" href="<?= BASE_URL."/index.php?path=common/home/homepage#existing-clients" ?>">Customers</a></li><li class="nav-item"><a class="nav-link" href="<?= BASE_URL."/index.php?path=common/home/farmers" ?>">Farmers</a></li><li class="nav-item"><a class="nav-link" href="<?= BASE_URL."/index.php?path=common/home/partners" ?>">Partners</a></li></ul><ul class="nav navbar-nav flex-row justify-content-center flex-nowrap"><li class="nav-item mr-3"><a href="<?= BASE_URL."/index.php?path=account/login/customer" ?>" class="btn btn-outline-secondary">Login</a></li><li class="nav-item"><a href="<?= BASE_URL."/index.php?path=account/login/newCustomer" ?>" class="btn btn-primary">Register</a></li></ul></div></div></nav><main><section id="faq"><div class="container"><div class="row"><div class="section-header col-md-12"><h4 class="section-title">Frequently Asked Questions</h4></div></div> <?php foreach($data as $category) { ?> <div class="row"><div class="col-md-12"><div id="accordion" class="py-3"><h6 class="mb-3"><strong><?= $category['category'] ?></strong></h6> <?php foreach($category['questions'] as $faq) { ?> <div class="card border-0 wow fadeInUp" style="visibility: visible; animation-name: fadeInUp;"><div class="card-header p-0 border-0" id="heading-<?= $faq['help_id'] ?>"><button class="btn btn-link accordion-title border-0 collapsed" data-toggle="collapse" data-target="#collapse-<?= $faq['help_id'] ?>" aria-expanded="false" aria-controls="#collapse-<?= $faq['help_id'] ?>"><i class="fas fa-minus text-center d-flex align-items-center justify-content-center h-100"></i> <?= $faq['question'] ?> </button></div><div id="collapse-<?= $faq['help_id'] ?>" class="collapse" aria-labelledby="heading-<?= $faq['help_id'] ?>" data-parent="#accordion"><div class="card-body accordion-body"><p> <?= $faq['answer'] ?> </p></div></div></div> <?php } ?> </div></div></div> <?php } ?> </div></section></main><div class="covid-prevention-bar"><p>We have placed measures to curb the spread of COVID-19</p><a href="<?= BASE_URL."/index.php?path=common/home/covid19" ?>">Learn More</a></div><footer><div id="footer" class="footers pt-3 pb-3"><div class="container pt-5"><div class="row"><div class="col-xs-12 col-sm-6 col-md-4 footers-one"><div class="footers-logo"><img src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/img/logo.svg" class="logo" alt="KwikBasket Logo"></div><div class="footers-info mt-3"><p>3rd Floor, Heritan House<br>Woodlands Road Nairobi, Kenya<br><br>+254738770186 / +254780703586<br><a href="mailto:hello@kwikbasket.com?subject = Feedback&body = Message">hello@kwikbasket.com</a></p></div><div class="social-icons mb-3"><a target="_blank" href="https://www.facebook.com/kwikbasket"><i id="social-fb" class="fa fa-facebook-square fa-2x social"></i></a><!-- <a target="_blank" href="#"><i id="social-tw" class="fa fa-twitter-square fa-2x social"></i></a> --> <a target="_blank" href="https://www.linkedin.com/company/kwikbasket"><i id="social-li" class="fa fa-linkedin-square fa-2x social"></i></a> <a target="_blank" href="https://www.instagram.com/kwikbasket/"><i id="social-in" class="fa fa-instagram fa-2x social"></i></a></div></div><div class="col-xs-12 col-sm-6 col-md-2 footers-two"><h5 class="primary-text">Company</h5><ul class="list-unstyled"><li><a href="<?= BASE_URL."/index.php?path=common/home/about_us" ?>">About Us</a></li><li><a href="<?= BASE_URL."/index.php?path=common/home/careers" ?>">Careers</a></li></ul></div><div class="col-xs-12 col-sm-6 col-md-2 footers-three"><h5 class="primary-text">Explore</h5><ul class="list-unstyled"><li><a href="<?= BASE_URL."/index.php?path=common/home/homepage#order-cycle" ?>">Customers</a></li><li><a href="<?= BASE_URL."/index.php?path=common/home/farmers" ?>">Farmers</a></li><li><a href="<?= BASE_URL."/index.php?path=common/home/partners" ?>">Partners</a></li></ul></div><div class="col-xs-12 col-sm-6 col-md-2 footers-four"><h5 class="primary-text">Resources</h5><ul class="list-unstyled"><li><a href="<?= BASE_URL."/index.php?path=common/home/technology" ?>">Technology</a></li><li><a href="<?= BASE_URL."/index.php?path=common/home/faq" ?>">FAQs</a></li><li><a href="<?= BASE_URL."/index.php?path=common/home/blog" ?>">Blog & Media</a></li></ul></div><div class="col-xs-12 col-sm-6 col-md-2 footers-five"><h5 class="primary-text">Reach Out</h5><ul class="list-unstyled"><li><a target="_blank" href="https://www.facebook.com/kwikbasket">Facebook</a></li><li><a target="_blank" href="https://www.linkedin.com/company/kwikbasket">LinkedIn</a></li><li><a target="_blank" href="https://www.instagram.com/kwikbasket/">Instagram</a></li></ul></div></div></div></div><div class="copyright border"><div class="container"><div class="row pt-3 pb-1"><div class="content-container col-md-12"><p class="text-muted">© 2020 KwikBasket | All Rights Reserved</p><ul><li><a href="<?= BASE_URL."/index.php?path=common/home/terms_and_conditions" ?>">Terms & Conditions</a></li><li><a href="<?= BASE_URL."/index.php?path=common/home/privacy_policy" ?>">Privacy Policy</a></li></ul></div></div></div></div></footer><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/jquery-3.2.1.min.js"></script><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/popper.min.js"></script><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/bootstrap.min.js"></script><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/scroll-out.min.js"></script><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/iziToast.min.js" async defer="defer"></script><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/gsap.min.js"></script><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/modernizr-custom.js"></script><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/scripts.min.js"></script></body></html>