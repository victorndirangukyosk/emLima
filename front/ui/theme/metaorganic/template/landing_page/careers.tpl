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
</head>

<body>
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container"><a class="navbar-brand" href="<?= BASE_URL." /index.php " ?>"><img src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/img/logo.svg" class="logo" alt="KwikBasket Logo"> </a><button class="navbar-toggler" type="button" data-toggle="collapse"
                data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse justify-content-between align-items-center w-100"
                id="navbarNav">
                <ul class="navbar-nav mx-auto text-center">
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL." /index.php?path=common/home/homepage#what-we-do " ?>">What We Do</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL." /index.php?path=common/home/homepage#existing-clients " ?>">Customers</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL." /index.php?path=common/home/farmers " ?>">Farmers</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL." /index.php?path=common/home/partners " ?>">Partners</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL." /index.php?path=common/home/covid19 " ?>">COVID-19</a></li>
                </ul>
                <ul class="nav navbar-nav flex-row justify-content-center flex-nowrap">
                    <li class="nav-item mr-3"><a href="<?= BASE_URL." /index.php?path=account/login/customer " ?>" class="btn btn-outline-secondary">Login</a></li>
                    <li class="nav-item"><a href="<?= BASE_URL." /index.php?path=account/login/newCustomer " ?>" class="btn btn-primary">Register</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <script src="https://www.google.com/recaptcha/api.js" async defer="defer"></script>
    <main>
        <section id="careers">
            <div class="container">
                <div class="row">
                    <div class="section-header col-md-12">
                        <h4 class="section-title">Careers</h4>
                        <p class="section-subtitle">Our team is made up of all kinds of nerds, from Software Engineers to Finance Gurus, UI/UX Designers to Customer Success Experts. Looking to work with us? We'd love to have you on our team!</p>
                    </div>
                </div>

                   <?php if (count($job_categories)>1) { ?>
                <div class="row mt-6">
                <form action="filter" method="post" enctype="multipart/form-data" id="form">
                <div class="row" style="width:100%">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="input-job_category"></label>
                                <select name="job_category" id="input-job_category" class="form-control">
                                <option value="All Job Category" selected="selected">All Job Category </option>
                                <?php foreach ($job_categories as $job_category) { ?>
                                <?php if ($job_category['job_category']  == $job_category_name) { ?>
                                <option value="<?php echo $job_category['job_category'] ; ?>" selected="selected"><?php echo $job_category['job_category'] ; ?></option>
                                <?php } else { ?>
                                <option value="<?php echo $job_category['job_category'] ; ?>"><?php echo $job_category['job_category'] ; ?></option>
                                <?php } ?>
                                <?php } ?>
                                </select> 
                             </div> 
                         </div>
                        <div class="col-sm-4">
                            <div class="form-group" >
                                <label class="control-label" for="input-job_type"></label>
                                 <select  name="job_type" id="input-job_type" class="form-control">
                          <option value="All Job Type" selected="selected">All Job Type </option>
                            <?php foreach ($job_types as $job_type) { ?>
                            <?php if ($job_type['job_type']  == $job_type_name) { ?>
                            <option value="<?php echo $job_type['job_type'] ; ?>" selected="selected"><?php echo $job_type['job_type'] ; ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $job_type['job_type'] ; ?>"><?php echo $job_type['job_type'] ; ?></option>
                            <?php } ?>
                            <?php } ?>
                          </select>
                            </div> 

                        </div>



                        <div class="col-sm-4">

                           <div class="form-group">
                                <label class="control-label" for="input-job_location"></label>
                                 <select name="job_location" id="input-job_location" class="form-control">
                          <option value="All Job Location" selected="selected">All Job Location </option>
                            <?php foreach ($job_locations as $job_location) { ?>
                            <?php if ($job_location['job_location']  == $job_location_name) { ?>
                            <option value="<?php echo $job_loction['job_location'] ; ?>" selected="selected"><?php echo $job_location ['job_location']; ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $job_location ['job_location']; ?>"><?php echo $job_location['job_location'] ; ?></option>
                            <?php } ?>
                            <?php } ?>
                          </select> 
                            </div>      
                            <!--<button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> Filter</button>-->

                        </div>

                        
                    </div> 
                  </form>  
                </div>

                <div class="row" id="sum_widgets">              
                        <?php foreach ($jobpositions as $jobposition) { ?>
                        <div class="col-lg-4 col-md-4 col-sm-6"><div class="panel income db mbm">
                        <div class="panel-body"  >
                            <!--<p class="icon"><i class="icon fa fa-money"></i></p>-->
                            <h4 class="value"><span><?php echo $jobposition['job_category'] ; ?></span></h4>
                            <p class="description"> </p>
                            <p class="description"> </p>
                            <p class="description"><?php echo $jobposition['job_type'] ; ?></p>
                            <p class="description"><?php echo $jobposition['job_location'] ; ?></p>
                            <p class="description"> 
                            <a href="<?= BASE_URL." /index.php?path=common/home/jobopenings&id=" ?><?php echo $jobposition['job_id'] ; ?>">more details</a>
                            </p>
                        </div>
                        </div>
                        </div>
                                                    
                        <?php } ?>
                </div>   

     <?php } else { ?>


      <div class="row mt-4">
                    <div class="col-md-12">
                        <form id="careers-form">
                            <div class="form-row">
                                <div class="form-group col-md-4"><label for="careers-first-name">First Name</label> <input required type="text" class="form-control rounded-0" id="careers-first-name"></div>
                                <div class="form-group col-md-4"><label for="careers-last-name">Last Name</label> <input required type="text" class="form-control rounded-0" id="careers-last-name"></div>
                                <div class="form-group col-md-4"><label for="careers-designation">Desired Role</label> <input required type="text" class="form-control rounded-0" id="careers-designation"></div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12"><label for="careers-about-yourself">Tell us about yourself</label> <textarea id="careers-about-yourself" cols="30" rows="10" class="form-control">
                                </textarea></div>
                            </div>
                            <div class="form-row mb-3">
                                <div class="col-md-12 d-flex justify-content-center">
                                    <div class="g-recaptcha" data-sitekey="<?= $site_key ?>"></div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-12 text-center"><button id="careers-submit-button" class="btn btn-secondary rounded-0 mt-4 px-5 py-2">SUBMIT</button></div>
                            </div>
                        </form>
                    </div>
                </div>

 <?php } ?>

            </div>
        </section>
    </main>
    <footer>
        <div id="footer" class="footers pt-3 pb-3">
            <div class="container pt-5">
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-4 footers-one">
                        <div class="footers-logo"><img src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/img/logo.svg" class="logo" alt="KwikBasket Logo"></div>
                        <div class="footers-info mt-3">
                            <p>3rd Floor, Heritan House<br>Woodlands Road Nairobi, Kenya<br><br>+254738770186 / +254780703586<br><a href="mailto:hello@kwikbasket.com?subject = Feedback&body = Message">hello@kwikbasket.com</a></p>
                        </div>
                        <div class="social-icons mb-3"><a target="_blank" href="https://www.facebook.com/kwikbasket"><i id="social-fb" class="fa fa-facebook-square fa-2x social"></i></a>
                            <!-- <a target="_blank" href="#"><i id="social-tw" class="fa fa-twitter-square fa-2x social"></i></a> --><a target="_blank" href="https://www.linkedin.com/company/kwikbasket"><i id="social-li" class="fa fa-linkedin-square fa-2x social"></i></a> <a target="_blank" href="https://www.instagram.com/kwikbasket/"><i id="social-in" class="fa fa-instagram fa-2x social"></i></a></div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-2 footers-two">
                        <h5 class="primary-text">Company</h5>
                        <ul class="list-unstyled">
                            <li><a href="<?= BASE_URL." /index.php?path=common/home/about_us " ?>">About Us</a></li>
                            <li><a href="<?= BASE_URL." /index.php?path=common/home/careers " ?>">Careers</a></li>
                        </ul>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-2 footers-three">
                        <h5 class="primary-text">Explore</h5>
                        <ul class="list-unstyled">
                            <li><a href="<?= BASE_URL." /index.php?path=common/home/homepage#order-cycle " ?>">Customers</a></li>
                            <li><a href="<?= BASE_URL." /index.php?path=common/home/farmers " ?>">Farmers</a></li>
                            <li><a href="<?= BASE_URL." /index.php?path=common/home/partners " ?>">Partners</a></li>
                        </ul>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-2 footers-four">
                        <h5 class="primary-text">Resources</h5>
                        <ul class="list-unstyled">
                            <li><a href="<?= BASE_URL." /index.php?path=common/home/technology " ?>">Technology</a></li>
                            <li><a href="<?= BASE_URL." /index.php?path=common/home/faq " ?>">FAQs</a></li>
                            <li><a href="<?= BASE_URL." /index.php?path=common/home/blog " ?>">Blog & Media</a></li>
                        </ul>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-2 footers-five">
                        <h5 class="primary-text">Reach Out</h5>
                        <ul class="list-unstyled">
                            <li><a target="_blank" href="https://www.facebook.com/kwikbasket">Facebook</a></li>
                            <li><a target="_blank" href="https://www.linkedin.com/company/kwikbasket">LinkedIn</a></li>
                            <li><a target="_blank" href="https://www.instagram.com/kwikbasket/">Instagram</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="copyright border">
            <div class="container">
                <div class="row pt-3 pb-1">
                    <div class="content-container col-md-12">
                        <p class="text-muted">© 2020 KwikBasket | All Rights Reserved</p>
                        <ul>
                            <li><a href="<?= BASE_URL." /index.php?path=common/home/terms_and_conditions " ?>">Terms & Conditions</a></li>
                            <li><a href="<?= BASE_URL." /index.php?path=common/home/privacy_policy " ?>">Privacy Policy</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/jquery-3.2.1.min.js"></script>
    <script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/popper.min.js"></script>
    <script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/bootstrap.min.js"></script>
    <script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/scroll-out.min.js"></script>
    <script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/iziToast.min.js" async defer="defer"></script>
    <script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/gsap.min.js"></script>
    <script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/modernizr-custom.js"></script>
    <script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/scripts.min.js"></script>
</body>

</html>


<script>
        function filter() {

           alert(1);
        }
</script>