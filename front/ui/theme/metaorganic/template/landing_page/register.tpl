<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><meta http-equiv="X-UA-Compatible" content="IE=edge"><meta name="viewport" content="width=device-width,initial-scale=1"><link rel="icon" type="image/svg" href="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/img/favicon.svg"><title>KwikBasket | Fresh Produce Supply Reimagined</title><link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"><link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"><link rel="stylesheet" type="text/css" href="https://cdn.tutorialjinni.com/izitoast/1.4.0/css/iziToast.min.css"><link href="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/css/style.min.css" rel="stylesheet"></head><body><main><div class="auth-layout-wrap"><div class="auth-content"><div class="card o-hidden"><div class="row"><div class="col-md-12"><div class="p-4"><div class="auth-logo text-center mb-4"><a class="base_url" href="<?= BASE_URL."/index.php" ?>"><img src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/img/logo.svg" class="logo" alt="KwikBasket Logo"></a></div><h1 class="mb-2 auth-card-header">Sign Up</h1><form id="register-form"><div class="form-row"><div class="form-group col-md-6"><label for="register-first-name">First Name</label> <input required type="text" class="form-control" id="register-first-name"></div><div class="form-group col-md-6"><label for="register-last-name">Last Name</label> <input required type="text" class="form-control" id="register-last-name"></div></div><div class="form-row"><div class="form-group col-md-6"><label for="register-email">Email Address</label> <input type="email" class="form-control" id="register-email"></div><div class="form-group col-md-6"><label for="register-phone">Phone</label><div class="input-group"><div class="input-group-prepend"><div class="input-group-text">+254</div></div><input required type="text" class="form-control" id="register-phone"></div></div></div><div class="form-row"><div class="form-group col-md-6"><label for="register-company-name">Company Name</label> <input required type="text" class="form-control" id="register-company-name"></div><div class="form-group col-md-6"><label for="register-company-address">Company Address</label> <input required type="text" class="form-control" id="register-company-address"></div></div><div class="form-row"><div class="form-group col-md-6"><label for="register-business-type">Business Type</label> <select class="form-control" id="register-business-type"><option value="6">Restaurant</option><option value="4">Super Market</option><option value="3">Caterer</option><option value="7">Hostel (Colleges &amp; Schools)</option><option value="10">Hospitals</option><option value="11">Industrial Canteens</option><option value="9">Others</option><option value="12">Champions</option></select></div><div class="form-group col-md-6"><label for="register-building-name">House Number / Building Name</label> <input required type="text" class="form-control" id="register-building-name"></div></div><div class="form-row"><div class="form-group col-md-6"><label for="register-address-line">Address Line 1 (Optional)</label> <input type="text" class="form-control" id="register-address-line"></div><div class="form-group col-md-6"><label for="register-location">Location</label> <input required type="text" class="form-control pac-target-input" id="register-location" autocomplete="off"></div></div><div class="form-row"><div class="form-group col-md-6"><label for="register-password">Password</label> <input required type="password" class="form-control" id="register-password"></div><div class="form-group col-md-6"><label for="register-password-confirm">Confirm Password</label> <input required type="password" class="form-control" id="register-password-confirm"></div></div><button id="register-button" class="btn btn-primary mt-1 btn-block">SIGN UP</button></form></div></div></div></div></div></div></main><script type="text/javascript" src="https://maps.google.com/maps/api/js?key=<?= $this->config->get('config_google_api_key') ?>&libraries=places"></script><script src="https://code.jquery.com/jquery-3.2.1.min.js"></script><script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script><script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script><script src="https://unpkg.com/scroll-out/dist/scroll-out.min.js"></script><script src="https://cdn.tutorialjinni.com/izitoast/1.4.0/js/iziToast.min.js"></script><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/scripts.min.js"></script></body></html>