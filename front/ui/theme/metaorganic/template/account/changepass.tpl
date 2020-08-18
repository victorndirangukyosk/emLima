<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width,initial-scale=1"> 
<link rel="preload" href="<?= BASE_URL;?>/front/ui/theme/metaorganic/assets_landing_page/css/style.min.css" as="style">
<link rel="stylesheet" href="<?= BASE_URL;?>/front/ui/theme/metaorganic/assets_landing_page/css/reset.css">
<link rel="stylesheet" href="<?= BASE_URL;?>/front/ui/theme/metaorganic/assets_landing_page/css/bootstrap.min.css">
<link rel="stylesheet" href="<?= BASE_URL;?>/front/ui/theme/metaorganic/assets_landing_page/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="<?= BASE_URL;?>/front/ui/theme/metaorganic/assets_landing_page/css/iziToast.min.css">
<link rel="stylesheet" href="<?= BASE_URL;?>/front/ui/theme/metaorganic/assets_landing_page/css/style.min.css">
</head>
<body>
<main>
<div class="auth-layout-wrap"><div class="auth-content">
<div class="card o-hidden"><div id="login-view" class="row">
<div class="col-md-12">
<div class="p-4">  

<div class="auth-logo text-center mb-4"><a class="base_url" href="<?= BASE_URL."/index.php" ?>">
<img src="<?= BASE_URL."/front/ui/theme/metaorganic/assets_landing_page/img/logo.svg"?>" class="logo" alt="KwikBasket Logo"></a>
</div><h1 class="mb-2 auth-card-header">Change Password</h1>

<form action="changepass" method="post"  enctype="multipart/form-data" class="form-horizontal">
<div class="form-group required" ><label for="input-name"><?= $label_new ?></label>
 <input required type="password" class="form-control" id="newpassword"  name='newpassword'>

  <?php if ($error_new) { ?>
                                        <div class="text-danger"><?php echo $error_new; ?></div>
                                        <?php } ?>

 </div><div class="form-group required"><label for="input-name"><?= $label_retype ?></label>
  <input required type="password" class="form-control" id="retypepassword" name='retypepassword'>
 <div id="status"></div>
   <?php if ($error_retype) { ?>
                                        <div class="text-danger"><?php echo $error_retype; ?></div>
                                        <?php } ?>

  </div>
  <div class="form-action">
   <input type="submit" class="btn btn-lg btn-primary ladda-button" id="ChangePassword" name="submit" value="submit">  
   </div>
  <!-- <button id="login-button" class="btn btn-primary mt-1 btn-block">LOGIN</button>-->


 
         </form>
  
  




   </div></div></div></div><div class="container mt-5"><div class="row"><div class="col-md-12 text-center"></div></div>
   </div></div></div></main><script src="<?= BASE_URL; ?>/front/ui/theme/metaorganic/assets_landing_page/js/jquery-3.2.1.min.js">
   </script><script src="<?= BASE_URL;?>/front/ui/theme/metaorganic/assets_landing_page/js/popper.min.js"></script>
   <script src="<?= BASE_URL;?>/front/ui/theme/metaorganic/assets_landing_page/js/bootstrap.min.js"></script>
   <script src="<?= BASE_URL;?>/front/ui/theme/metaorganic/assets_landing_page/js/scroll-out.min.js"></script>
   <script src="<?= BASE_URL;?>/front/ui/theme/metaorganic/assets_landing_page/js/iziToast.min.js" async defer="defer">
   </script><script src="<?= BASE_URL;?>/front/ui/theme/metaorganic/assets_landing_page/js/gsap.min.js"></script>
   <script src="<?= BASE_URL;?>/front/ui/theme/metaorganic/assets_landing_page/js/modernizr-custom.js">
</script><script src="<?= BASE_URL;?>/front/ui/theme/metaorganic/assets_landing_page/js/scripts.min.js"></script>
</body></html>  