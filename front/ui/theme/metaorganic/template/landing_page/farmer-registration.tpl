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

.</style></head><body><script src="https://www.google.com/recaptcha/api.js" async defer="defer"></script><script type="text/javascript" src="https://maps.google.com/maps/api/js?key=<?= $this->config->get('config_google_api_key') ?>&libraries=places"></script><main><div class="auth-layout-wrap"><div class="auth-content"><div class="card o-hidden"><div class="row"><div class="col-md-12"><div class="p-4"><div class="auth-logo text-center mb-4"><a class="base_url" href="<?= BASE_URL."/index.php" ?>"><img src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/img/logo.svg" class="logo" alt="KwikBasket Logo"></a></div><h1 class="mb-2 auth-card-header">Farmer Registration</h1><form id="farmer-registration-form"><div class="form-row"><div class="form-group col-md-4"><label for="farmer-first-name">First Name</label> <input required type="text" class="form-control" name="farmer-first-name" id="farmer-first-name"></div><div class="form-group col-md-4"><label for="farmer-last-name">Last Name</label> <input required type="text" class="form-control" name="farmer-last-name" id="farmer-last-name"></div><div class="form-group col-md-4"><label for="farmer-email">Email Address</label> <input type="email" class="form-control" id="farmer-email"></div></div><div class="form-row"><div class="form-group col-md-4"><label for="farmer-phone">Phone</label><div class="input-group"><div class="input-group-prepend"><div class="input-group-text">+254</div></div><input required type="text" class="form-control" name="farmer-phone" id="farmer-phone"></div></div><div class="form-group col-md-4"><label for="farmer-type">Farmer Type</label> <select id="farmer-type" class="form-control"><option value="Commercial">Commercial</option><option value="Smallholder">Smallholder</option><!--                                            <option value="Subsistence">Subsistence</option> --></select></div><div class="form-group col-md-4"><label for="farmer-type">Irrigation Type</label> <select id="irrigation-type" class="form-control"><option value="Piped">Piped</option><option value="Natural">Natural</option><!--                                            <option value="Subsistence">Subsistence</option> --></select></div></div><div class="form-row"><div class="form-group col-md-4"><label for="farmer-location">Farm Location</label> <input required type="text" class="form-control" id="farmer-location"></div><div class="form-group col-md-4"><label for="farm-size">Farm Size</label> <input required type="text" class="form-control" id="farm-size"></div><div class="form-group col-md-4"><label for="farm-size-type">Farm Size Type</label> <select id="farm-size-type" class="form-control"><option value="Acres">Acres</option><option value="Hectares">Hectares</option><!--                                            <option value="Subsistence">Subsistence</option> --></select></div></div><div class="form-row"><div class="form-group col-md-12"><label for="farmer-organization">Farmer Organization</label> <input required type="text" class="form-control" id="farmer-organization"></div></div><div class="form-row"><div class="form-group col-md-12"><label for="farmer-produce-grown">Tell us about the produce you grow</label> <textarea required class="form-control" id="farmer-produce-grown" cols="30" rows="10"></textarea></div></div><div class="form-row mb-3"><div class="col-md-12 d-flex justify-content-center"><div class="g-recaptcha" data-sitekey="<?= $site_key ?>"></div></div></div><input type="hidden" value="0" id="lat"> <input type="hidden" value="0" id="lng"> <button id="farmer-register-button" class="btn btn-primary mt-1 btn-block">REGISTER</button></form></div></div></div></div><div class="container mt-5"><div class="row"><div class="col-md-12 text-center"><a href="<?= BASE_URL."/index.php" ?>" class="btn">Back To Homepage</a></div></div></div></div></div></main><script type="text/javascript">var autocomplete;
    autocomplete = new google.maps.places.Autocomplete((document.getElementById('farmer-location')), {
        types: ['geocode'],
        componentRestrictions: {
          country: 'KE'
      }
    });
	
    google.maps.event.addListener(autocomplete, 'place_changed', function () {
        var near_place = autocomplete.getPlace();
    });</script><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/jquery-3.2.1.min.js"></script><script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script><link href="https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet"><script src="https://code.jquery.com/jquery-migrate-3.0.0.min.js"></script><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/popper.min.js"></script><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/bootstrap.min.js"></script><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/scroll-out.min.js"></script><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/iziToast.min.js" async defer="defer"></script><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/gsap.min.js"></script><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/modernizr-custom.js"></script><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/scripts.min.js"></script><script src="front/ui/javascript/carousel.js" type="text/javascript"></script><link href="front/ui/stylesheet/carousel.css" rel="stylesheet"></body></html>