<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title><?= $config_name ?></title>

    <!-- Bootstrap core CSS -->
    <link href="ui/javascript/bootstrap/css/bootstrap.css" rel="stylesheet" />

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="ui/javascript/bootstrap/css/ie10-viewport-bug-workaround.css" rel="stylesheet" />

    <link href="ui/javascript/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
    
    <!-- Custom styles for this template -->
    <link href="ui/stylesheet/shopper.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    
    <script src="ui/javascript/bootstrap/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="container">

      <!-- Static navbar -->
      <nav class="navbar navbar-default">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?= $request ?>">
                <?= $config_name ?>
            </a>
          </div>
          <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
              <li><a id="home" href="<?= $request ?>"><?= $text_home ?></a></li>                
              <li><a href="<?= $order ?>"><?= $text_my_order ?></a></li>
              <li class="dropdown">
                <a aria-expanded="false" aria-haspopup="true" role="button" data-toggle="dropdown" class="dropdown-toggle" href="#">
                    <?= $text_my_account ?><span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                  <li><a href="<?= $wallet ?>"><?= $text_wallet ?></a></li>
                  <li><a href="<?= $setting ?>"><?= $text_setting ?></a></li>
                  <li><a href="<?= $password ?>"><?= $text_password ?></a></li>
                  <li><a href="<?= $logout ?>"><?= $text_logout ?></a></li>
                </ul>
              </li>              
            </ul>
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </nav>

