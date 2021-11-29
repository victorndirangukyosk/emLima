<?php echo $header; ?>


<div class="col-lg-10 col-md-9 col-sm-8 col-xs-12 product_scroll">
    <div class="row">
        <div class="col-md-12">
            <div class="product-category-block">
                <h2><?php echo $text_search; ?></h2>
            </div>
        </div>
    </div>
    <div class="row">
        <?php if($products){ ?>
            <div class="store-list-wrapper" style="display:block;">
                <?php require(DIR_BASE.'front/ui/theme/mvgv2/template/product/product_collection.php'); ?>
                <div style='display: none;'>
                    <?= $pagination ?>
                </div>

            </div>
                                
            

        <?php } else { ?>
        
            <p><?php echo $text_empty; ?></p>
            
        <?php } ?>
            
    </div>
    <center class="loader-gif" style="margin-top: 40px">
    </center>
</div>
<!-- <div class="row" style="padding-bottom:30px;"></div> -->
        </div>
    </div>
</div>
<div class="modal-wrapper"></div> 
<div style="padding-bottom:30px;"></div>
<?php echo $footer; ?> 
<?= $login_modal ?>
<?= $signup_modal ?>
<?= $forget_modal ?>
<div class="listModal-popup">
    <div class="modal fade" id="listModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="uncheckAll()"><span aria-hidden="true">&times;</span></button>
                    <div class="store-find-block">
                        <div class="mydivsssx">
                            <div class="store-find">
                                <div class="store-head">
                                    <h1><?= $text_add_to_list ?></h1>
                                </div>

                                <div id="list-message-success" style="color: green;">
                                </div>

                                <div id="list-message-error" style="color: red;">
                                </div>

                                <form id="add-in-list" action="" method="post" enctype="multipart/form-data">

                                    <table class="table table-striped">
                                        <thead>
                                          <tr>
                                            <th style="text-align: center;"><?= $text_list_name ?></th>
                                            <th style="text-align: center;"><?= $text_add_to ?> </th>
                                          </tr>
                                        </thead>
                                        <tbody id="users-list">
                                            <?php foreach ($lists as $list) { ?>
                                              <tr>
                                                <td><?= $list['name'] ?></td>
                                                <td class=""> <input type="checkbox" class="" name="add_to_list[]" value="<?= $list['wishlist_id'] ?>"></td>
                                              </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>

                                    <input type="hidden" name="listproductId" class="listproductId" value=""/>

                                    <button id="add-in-list-button" type="button" name="next" class="btn btn-default btn-lg">
                                        <span class="add-in-list-modal-text"><?= $text_confirm ?> </span>
                                        <div class="add-in-list-loader" style="display: none;"></div>
                                    </button>
                                </form>

                               
                                <p class="seperator"><?= $text_or ?> </p>
                                <div class="social-login-section">
                                    <form id="list-create-form" action="" method="post" enctype="multipart/form-data" class="form">
                                        
                                        <input type="hidden" name="listproductId" class="listproductId" value=""/>
                                        <div class="row">
                                            <div class="col-sm-9 form-group required">
                                                <input id="list-name" name="name" type="text" placeholder="<?= $text_enter_list_name ?>" class="form-control input-bg" required>
                                            </div>
                                            
                                            
                                            <div class="col-sm-3 form-group">
                                                <button id="list-create-button" type="button" name="next" class="btn btn-default btn-lg">
                                                        <span class="list-create-modal-text"><?= $text_create_list ?> </span>
                                                        <div class="list-create-loader" style="display: none;"></div>
                                                </button>
                                            </div> 
                                            
                                        </div>
                                        
                                       
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="changelocationModal">
    <div class="modal fade" id="useraddress-popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                 <div class="exclamation-icon"><i class="fa fa-exclamation-circle fa-4x"></i></div>
                 <div class="changelocationModal-content">
                    <h2><?= $text_change_locality ?></h2>
                    <?php if($this->config->get('config_multi_store')) { ?>
                        <p><?= $text_only_on_change_locality_warning ?></p>
                    <?php } else { ?>
                        <p><?= $text_change_locality_warning ?></p>
                    <?php } ?> 

                    <?php if($this->config->get('config_store_location') == 'zipcode') { ?>

                        <b> <?= $text_change_location_name ?> : <?= $zipcode ?></b>
                    <?php } else { ?>
                        <b><?= $text_change_location_name ?> : <?= $location_name ?></b>
                        
                    <?php } ?>
                            
                </div>
                <a href="<?php echo $toHome ?>" class="btn btn-primary"><?= $button_change_locality ?></a>
                <a href="<?php echo $toStore ?>" class="btn btn-default"><?= 
                $button_change_store ?></a>
                
            </div>
        </div>
    </div>
</div>
    
<script type="text/javascript" src="<?= $base; ?>front/ui/javascript/jquery/infinitescroll/jquery.infinitescroll.min.js" ></script>
    <!-- <script type="text/javascript" src="<?= $base; ?>front/ui/theme/mvgv2/js/jquery.lazy.min.js"></script>
    <script type="text/javascript" src="<?= $base; ?>front/ui/theme/mvgv2/js/jquery.lazy.plugins.min.js"></script> -->

    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.4/jquery.lazy.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.4/jquery.lazy.plugins.min.js"></script>


    <script src="<?= $base; ?>front/ui/theme/mvgv2/js/jquery.maskedinput.js" type="text/javascript"></script>

    <!-- <link rel="stylesheet" href="<?= $base; ?>front/ui/theme/mvgv2/css/bootstrap-iso.css" /> -->
    <link rel="stylesheet" href="https://formden.com/static/cdn/bootstrap-iso.css" />
    
<!--     <script type="text/javascript" src="<?= $base; ?>front/ui/theme/mvgv2/js/bootstrap-datepicker.min.js"></script>
    <link rel="stylesheet" href="<?= $base; ?>front/ui/theme/mvgv2/css/bootstrap-datepicker3.css"/>
 -->
     <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
    <script type="text/javascript" src="<?= $base; ?>front/ui/theme/mvgv2/js/html5lightbox.js"></script>
    

<script type="text/javascript">

$('.date-dob').datepicker({
    pickTime: false,
    format: 'dd/mm/yyyy',
    todayHighlight: true,
    autoclose: true,
});
/*jQuery(function($){
    console.log("signup mask");
   $("#phone_number").mask("(99) 99999-9999",{autoclear:false,placeholder:"(##) #####-####"});
});*/
jQuery(function($){
    console.log("mask");
   $("#phone_number").mask("<?= $telephone_mask_number ?>",{autoclear:false,placeholder:"<?= $telephone_mask ?>"});
});

jQuery(function($) {
        console.log(" fax-number mask");
       $("#fax-number").mask("<?= $taxnumber_mask_number ?>",{autoclear:false,placeholder:"<?= $taxnumber_mask ?>"});
    });

$(function() {
    console.log("lazy f");
    $('img.lazy').Lazy({
            beforeLoad: function(element) {
                console.log("lazy");
            },
            effect: 'show',
            effectTime : 700,
            visibleOnly: true,
        }
    );
});

$(document).ready(function() {
    console.log("scroller");
    var $container = $('.store-list-wrapper');
    $container.infinitescroll({
        animate: false,
        navSelector  : '.pagination',    // selector for the paged navigation 
        nextSelector : '.pagination a',  // selector for the NEXT link (to page 2)
        itemSelector : '.product-details',     // selector for all items you'll retrieve
        loading: {
            finishedMsg: '<h2><?php echo $text_no_more_products ?></h2>',
            msgText: ' ',
            img: '<?= $base ?>image/theme/ring.gif',
            selector: '.loader-gif',
            
        }
    },

    // Function called once the elements are retrieved
    function(new_elts) {

        $('img.lazy').Lazy({
                beforeLoad: function(element) {
                    // called before an elements gets handled
                    console.log("lazy");
                },
                effect: 'show',
                effectTime : 700,
                visibleOnly : true
                //visibleOnly: true,
            }
        );
    });     
});

</script>

<script type="text/javascript">

    $(document).ready(function() {
        $('[data-toggle="offcanvas"]').click(function() {
            $('.row-offcanvas').toggleClass('active')
        });

        $('[data-toggle="tooltip"]').tooltip(); 
    });
    $('.header-search-form').on('click', function() {
        $('body').toggleClass('overflow-y-hidden');
    });
    $('.header-search-form').on('click', function() {
        $('.overlay-body').toggleClass('backdrop');
    });

    $(document).ready(function() {
        console.log("search page popup");
        $(document).delegate('.open-popup', 'click', function(){
            
            console.log("search product blocks"+$(this).attr('data-id'));
            $.get('index.php?path=product/product/view&product_store_id='+$(this).attr('data-id'), function(data){
                $('.modal-wrapper').html(data);
                $('#popupmodal').modal('show');
            });
        });
    });

    $(document).delegate('#clearcart', 'click', function(){
        var choice = confirm($(this).attr('data-confirm'));

        if(choice) {

            $.ajax({
                url: 'index.php?path=checkout/cart/clear_cart',
                type: 'post',
                data:'',
                dataType: 'json',
                success: function(json) {
                if (json['location']) {

                    location = json.redirect;
                    location = location;
                }}
            });
        }
    });
</script>
<script type="text/javascript" src="<?= $base; ?>front/ui/theme/mvgv2/js/side-menu-script.js"></script>
    <script type="text/javascript" src="<?= $base; ?>front/ui/theme/mvgv2/js/owl.carousel.min.js"></script>
    <script type="text/javascript" src="<?= $base; ?>front/ui/theme/mvgv2/js/slider-carousel.js"></script>
</body>

</html>
