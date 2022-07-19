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

.</style></head><body><nav class="navbar navbar-expand-lg fixed-top"><div class="container"><a class="navbar-brand" href="<?= BASE_URL."/index.php" ?>"><!--        <img src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/img/logo.svg" class="logo" alt="KwikBasket Logo">--> <img src="<?= $logo ?>" class="logo" alt="KwikBasket Logo"> </a><button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button><div class="collapse navbar-collapse justify-content-between align-items-center w-100" id="navbarNav"><ul class="navbar-nav mx-auto text-center"><li class="nav-item"><a class="nav-link" href="<?= BASE_URL."/index.php?path=common/home/homepage#what-we-do" ?>">What We Do</a></li><li class="nav-item"><a class="nav-link" href="<?= BASE_URL."/index.php?path=common/home/homepage#existing-clients" ?>">Customers</a></li><li class="nav-item"><a class="nav-link" href="<?= BASE_URL."/index.php?path=common/home/farmers" ?>">Farmers</a></li><li class="nav-item"><a class="nav-link" href="<?= BASE_URL."/index.php?path=common/home/partners" ?>">Partners</a></li><li class="nav-item"><a class="nav-link" href="<?= BASE_URL."/index.php?path=common/home/covid19" ?>">COVID-19</a></li></ul><ul class="nav navbar-nav flex-row justify-content-center flex-nowrap"><li class="nav-item mr-3"><a href="<?= BASE_URL."/index.php?path=account/login/customer" ?>" class="btn btn-outline-secondary">Customer Login</a></li><div><div class="menuset"><div class="newset" style="margin-top: 2px;"><a class="btn" href="#" style="color: #fff;!important!;"><span>Register</span></a><div class="dropdownset"><div class="dropdownsetnew" style="margin-top: 10px;"><a class="header__upper-deck-item-link" href="https://register.kwikbasket.com/#/"><i class="fa fa-user"></i> Register as Customer</a></div><br><div class="dropdownsetnew"><a class="header__upper-deck-item-link" href="https://farmers.kwikbasket.com/farmer-registration"><i class="fa fa-user"></i> Register as Farmer</a></div></div><br></div></div></div></ul></div></div></nav><main><section id="faq"><div class="container"><div class="row"><div class="section-header col-md-12"><h4 class="section-title">Frequently Asked Questions</h4></div></div> <?php foreach($data as $category) { ?> <div class="row"><div class="col-md-12"><div id="accordion-<?= $category['category'] ?>" class="py-3"><h6 class="mb-3"><strong><?= $category['category'] ?></strong></h6> <?php foreach($category['questions'] as $faq) { ?> <div class="card border-0 wow fadeInUp" style="visibility: visible; animation-name: fadeInUp;"><div class="card-header p-0 border-0" id="heading-<?= $faq['help_id'] ?>"><button class="btn btn-link accordion-title border-0 collapsed" data-toggle="collapse" data-target="#collapse-<?= $faq['help_id'] ?>" aria-expanded="false" aria-controls="#collapse-<?= $faq['help_id'] ?>"><i class="fas fa-minus text-center d-flex align-items-center justify-content-center h-100"></i> <?= $faq['question'] ?> </button></div><div id="collapse-<?= $faq['help_id'] ?>" class="collapse" aria-labelledby="heading-<?= $faq['help_id'] ?>" data-parent="#accordion-<?= $category['category'] ?>"><div class="card-body accordion-body"><p> <?= $faq['answer'] ?> </p></div></div></div> <?php } ?> </div></div></div> <?php } ?> </div></section></main><footer><div id="footer" class="footers pt-3 pb-3"><div class="container pt-5"><div class="row"><div class="col-xs-12 col-sm-6 col-md-4 footers-one"><div class="footers-logo"><!--              <img src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/img/logo.svg" class="logo" alt="KwikBasket Logo">--> <img src="<?= $logo ?>" class="logo" alt="KwikBasket Logo"></div><div class="footers-info mt-3"><p>3rd Floor, Heritan House<br>Woodlands Road Nairobi, Kenya<br><br>+254780703586<br><a href="mailto:hello@kwikbasket.com?subject = Feedback&body = Message">hello@kwikbasket.com</a></p></div><div class="social-icons mb-3"><a target="_blank" href="https://www.facebook.com/kwikbasket"><i id="social-fb" class="fa fa-facebook-square fa-2x social"></i></a><!-- <a target="_blank" href="#"><i id="social-tw" class="fa fa-twitter-square fa-2x social"></i></a> --> <a target="_blank" href="https://www.linkedin.com/company/kwikbasket"><i id="social-li" class="fa fa-linkedin-square fa-2x social"></i></a> <a target="_blank" href="https://www.instagram.com/kwikbasket/"><i id="social-in" class="fa fa-instagram fa-2x social"></i></a></div></div><div class="col-xs-12 col-sm-6 col-md-2 footers-two"><h5 class="primary-text">Company</h5><ul class="list-unstyled"><li><a href="<?= BASE_URL."/index.php?path=common/home/about_us" ?>">About Us</a></li><li><a href="<?= BASE_URL."/index.php?path=common/home/careers" ?>">Careers</a></li></ul></div><div class="col-xs-12 col-sm-6 col-md-2 footers-three"><h5 class="primary-text">Explore</h5><ul class="list-unstyled"><li><a href="<?= BASE_URL."/index.php?path=common/home/homepage#order-cycle" ?>">Customers</a></li><li><a href="<?= BASE_URL."/index.php?path=common/home/farmers" ?>">Farmers</a></li><li><a href="<?= BASE_URL."/index.php?path=common/home/partners" ?>">Partners</a></li></ul></div><div class="col-xs-12 col-sm-6 col-md-2 footers-four"><h5 class="primary-text">Resources</h5><ul class="list-unstyled"><li><a href="<?= BASE_URL."/index.php?path=common/home/technology" ?>">Technology</a></li><li><a href="<?= BASE_URL."/index.php?path=common/home/faq" ?>">FAQs</a></li><li><a href="<?= BASE_URL."/index.php?path=common/home/blog" ?>">Blog & Media</a></li></ul></div><div class="col-xs-12 col-sm-6 col-md-2 footers-five"><h5 class="primary-text">Reach Out</h5><ul class="list-unstyled"><li><a target="_blank" href="https://www.facebook.com/kwikbasket">Facebook</a></li><li><a target="_blank" href="https://www.linkedin.com/company/kwikbasket">LinkedIn</a></li><li><a target="_blank" href="https://www.instagram.com/kwikbasket/">Instagram</a></li></ul></div></div></div></div><div class="copyright border"><div class="container"><div class="row pt-3 pb-1"><div class="content-container col-md-12"><p class="text-muted">© 2021 KwikBasket | All Rights Reserved</p><ul><li><a href="<?= BASE_URL."/index.php?path=common/home/terms_and_conditions" ?>">Terms & Conditions</a></li><li><a href="<?= BASE_URL."/index.php?path=common/home/privacy_policy" ?>">Privacy Policy</a></li></ul></div></div></div></div></footer><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/jquery-3.2.1.min.js"></script><script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script><link href="https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet"><script src="https://code.jquery.com/jquery-migrate-3.0.0.min.js"></script><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/popper.min.js"></script><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/bootstrap.min.js"></script><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/scroll-out.min.js"></script><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/iziToast.min.js" async defer="defer"></script><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/gsap.min.js"></script><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/modernizr-custom.js"></script><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/scripts.min.js"></script><script src="front/ui/javascript/carousel.js" type="text/javascript"></script><link href="front/ui/stylesheet/carousel.css" rel="stylesheet"><script>const togglePassword = document.querySelector("#togglePassword");
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