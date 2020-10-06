<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <link rel="icon" type="image/svg" href="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/img/favicon.svg">
        <title>KwikBasket | Fresh Produce Supply Reimagined</title>
        <link rel="preload" href="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/css/style.min.css" as="style">
        <link rel="stylesheet" href="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/css/reset.css">
        <link rel="stylesheet" href="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/css/iziToast.min.css">
        <link rel="stylesheet" href="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/css/style.min.css">
        <style>
            .qrcode {
                display: none;
            }
        </style>
    </head>
    <body>
        <main>
            <div class="auth-layout-wrap">
                <div class="auth-content">
                    <div class="card o-hidden">
                        <div id="login-view" class="row">
                            <div class="col-md-12">
                                <div class="p-4">
                                    <div class="auth-logo text-center mb-4"><a class="base_url" href="<?= BASE_URL."/index.php" ?>"><img src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/img/logo.svg" class="logo" alt="KwikBasket Logo"></a></div>
                                    <h1 class="mb-2 auth-card-header">Log In</h1>
                                    <form>
                                        <div id="creds">
                                            <div class="form-group"><label for="email">Email address</label> <input required type="email" class="form-control" id="login-email" aria-describedby="emailHelp"></div>
                                            <div class="form-group"><label for="password">Password</label> <input required type="password" class="form-control" id="login-password"></div>
                                            <button id="login-button" class="btn btn-primary mt-1 btn-block">LOGIN</button>
                                        </div>
                                        <div id="qrcode" class="qrcode">
                                            <div class="form-group"><label for="qrocde">QR code</label></div>
                                            <div class="qrcode_img" id="qrcode_img" class="logo"></div>
                                            <div class="form-group"><label for="secretcode">Secret code</label> <input required type="secret_code" class="form-control" id="secret_code"></div>
                                            <div class="form-group"><label for=onetimecode">One time code</label> <input required type="one_time_code" class="form-control" id="one_time_code"></div>
                                            <button id="qr-login-button" class="btn btn-primary mt-1 btn-block">LOGIN</button>
                                        </div>
                                    </form>
                                    <div class="mt-3 text-center"><a href="<?= BASE_URL."/index.php?path=account/login/newCustomer" ?>" class="btn">Create An Account</a></div>
                                    <div class="mt-3 text-center"><a href="#" id="forgot-password-btn" class="text-muted"><u>Forgot Password?</u></a></div>
                                </div>
                            </div>
                        </div>
                        <div id="forgot-password-view" class="row">
                            <div class="col-md-12">
                                <div class="p-4">
                                    <div class="auth-logo text-center mb-4"><a class="base_url" href="<?= BASE_URL."/index.php" ?>"><img src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/img/logo.svg" class="logo" alt="KwikBasket Logo"></a></div>
                                    <h1 class="mb-2 auth-card-header">Reset Password</h1>
                                    <form>
                                        <div class="form-group"><label for="reset-password-email">Account Email Address</label> <input required type="email" class="form-control" id="reset-password-email"></div>
                                        <button id="password-reset-button" class="btn btn-primary mt-1 btn-block">RESET PASSWORD</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="container mt-5">
                        <div class="row">
                            <div class="col-md-12 text-center"><a href="<?= BASE_URL."/index.php" ?>" class="btn">Back To Homepage</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/jquery-3.2.1.min.js"></script><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/popper.min.js"></script><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/bootstrap.min.js"></script><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/scroll-out.min.js"></script><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/iziToast.min.js" async defer="defer"></script><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/gsap.min.js"></script><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/modernizr-custom.js"></script><script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/scripts.min.js"></script>
    </body>
</html>