<?php echo $header; ?>
    <div >
        <div >
            <div class="main-container col1-layout wow bounceInUp animated">   
                
                       
	       <div class="main">  
                           <div class="cart wow bounceInUp animated">
                         <div class="table-responsive shopping-cart-tbl  container">
                            <?php $this->load->model('account/address'); ?>
                            <fieldset>
 <table id="shopping-cart-table" class="data-table cart-table table-striped">
                <colgroup><col width="1">
                <col>
                <col width="1">
                                        <col width="1">
                                        <col width="1">
                            <col width="1">
                                        <col width="1">

                            </colgroup><thead>
                    <tr class="first last">
                        <th rowspan="1">SNo.</th>
                        <th rowspan="1">&nbsp;</th>
                        <th rowspan="1"><span class="nobr">Product Name</span></th>
                        <!--<th rowspan="1"><span class="nobr">Product Note</span></th>-->
                                                <th class="text-center" ><span class="nobr">Unit Price</span></th>
                                                <th class="text-center"><span class="nobr">Unit </span></th>
                        <th rowspan="1" class="text-center">Qty</th>
                        <th class="text-center">Sub Total</th>
                        <th rowspan="1"></th>
                        <th class="text-center">&nbsp;</th>
                        
                    </tr>
                                    </thead>
               
                <tbody>  
                         </tbody>
           
            
                            
                            <?php foreach ($arrs as $key=> $products) { ?>
                             
                                <div >
                                    <div  class="checkout-cart-merchant-box text-center"> <span  ><h1>Order Summary</h1></span> <span class="checkout-cart-merchant-item"></span> </div>
                                    <div >
                                        <div class="collapse in" id="collapseExample<?= $key ?>">
                                            <div class="checkout-item-list">

                                                  <?php echo $product ;?>
                                                    <?php 
                                                      $orderNotes ='';
                                                      $i = 1; 
													  foreach ($products as $key=>$product) {
                                                       //echo '<pre>';print_r($product);exit;
                                                        if((isset($product['product_note']) && ($product['product_note'] != null) && ($product['product_note'] != 'null') ) || (isset($product['produce_type']) && ($product['produce_type'] != null) && ($product['produce_type'] != 'null'))){ 
                                                         $orderNotes .= $product['name'];
														 
                                                         if(isset($product['produce_type']) && ($product['produce_type'] != null) && ($product['produce_type'] != 'null'))
                                                         $orderNotes .='( '.$product['produce_type'].') ';
													 
                                                         if(isset($product['product_note']) && ($product['product_note'] != null) && ($product['product_note'] != 'null'))
                                                         $orderNotes .='( Note: '.$product['product_note'].') ';
													 
                                                         if($key != (count($products)-1))
                                                         $orderNotes .=" / ";
                                                          
                                                     }    
                                                     ?>

                                                
                                    
<tr class="odd">

  <td class="a-left hidden-table">
                            <span class="cart-price">
                                                <span class="price"><?= $i?></span>                
            </span>


                    </td>
    <td class="image hidden-table"><img src="<?= $product['thumb'] ?>" width="75" alt=""></td>
    <td class="a-left hidden-table"  style="width:400px">
     <span ><?= $product['name']?></span> 
<div style="font-size:13px;">
      <?php  $fpt ='';
       foreach ($product['produce_type'] as $pt) {
           if($pt['type']!= null &&  $pt['type']!= 'null')
       $fpt.=' '.  $pt['type'].'-'.$pt['value'] ;
        }    
         ?><?= $fpt?></div>  

     <?php if(isset($product['product_note']) && ($product['product_note'] != null) && ($product['product_note'] != 'null')){?>
     <div style="font-size:13px;">( <?= $product['product_note']?> )</div> 
     <?php } ?>
    </td>
    <!--<td class="a-left hidden-table"  style="width:300px">
     <span style="font-size:13px;"><?= $product['product_note']?></span> 
    </td>-->
  
    
    
                <td class="a-right hidden-table">
                            <span class="cart-price">
                                                <span class="price"><?= $product['price']?></span>                
            </span>


                    </td>

                     <td class="a-right hidden-table"  style="width:150px">
                            <span class="cart-price">
                                                <span style="font-size:13px;" class="font-bold"><?= $product['unit'] ?></span>                
            </span>


                    </td> 
                        <td class="a-right movewishlist">
        <input disabled type="number" name="cart[<?= $i ?>][qty]" id="cart[<?= $i ?>][qty]" value="<?= $product['quantity']?>" size="4" title="Qty" class="input-text qty" maxlength="12" style="width:80px !important;disabled">
    </td>
        <td class="a-right hidden-table" >
                    <span class="cart-price">
        
                                                <span class="price font-bold"><?php echo $product['total']; ?></span>                            
        </span>
            </td>
              <td   class="a-center hidden-table"  >
               <stop> <a       class="edit-bnt" title="Edit item parameters"><span><?= $i?></span></a><stop>
            

  </td>
  <td class="a-center hidden-table">
  <p> <a title="Remove item" class="button remove-item" style=" background-color: #ec9f4e ;"><span><span><?= $product['key']?></span></span></a></p>
  </td>





</tr>  
                                                <?php $i++; } ?>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            <?php $i++; } ?>

<tfoot>
<tr class="first last">
<td colspan="9" class="a-right last">
<a  href="<?php echo $continue; ?>"> <button type="button" title="Continue Shopping" class="button btn-continue" style="width:210px;background-color: #ec9f4e ; padding: 15px 20px 27px 20px;" ><span><span>Continue Shopping</span></span></button></a>
                                                       
                                                       
<button type="submit" style="width:170px;background-color: #ec9f4e ; padding: 15px 20px 27px 20px;" name="save_basket_action" id="save_basket_action" value="save_basket" title="Save Basket" class="button btn-update"><span id="savebasket" data-confirm="This will move products into basket!!">Save Basket</span></button>
                           <button type="submit" style="width:170px;background-color: #ec9f4e ; padding: 15px 20px 27px 20px;" name="update_cart_action" value="update_qty" title="Update Cart" class="button btn-update"><span id="updatecart">Update Cart</span></button>
                          
                            <button type="submit"  style="width:180px;background-color: #ec9f4e ; padding: 15px 20px 27px 20px;" name="update_cart_action" value="empty_cart" title="Clear Cart" class="button btn-empty" id="empty_cart_button"><span id="clearcart" class="cart-header_items-count clear-cart" style="border-bottom:none;" data-confirm="This will empty your cart!!" >Clear Cart</span></button>
</td>
</tr>
</tfoot>
 </table>
  </fieldset>

   
                  
                           

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


<div class="cart-collaterals container"> 
<!-- BEGIN COL2 SEL COL 1 -->
<div class="row">

<!-- BEGIN TOTALS COL 2 -->
<div class="col-sm-4">

        
<div class="shipping">

        <h3>Order Notes</h3>
        <div class="sEstimate Shipping and Taxhipping-form">
       <form  id="comment-order-form" >
            
            <ul class="form-list">
            <li>
            </li>
                 
                                        <li>
                   <label for="order_note">Please add order note, if you have any.</label>

                     <?php foreach ($arrs as $key=> $products) { ?>
                      <div class="checkout-promocode-form">
                                        
                                        <textarea name="dropoff_notes" class="form-control" maxlength="200" placeholder="<?= $text_dropoff_notes?>" id="dropoff_notes" style="height: 100px;"><?php // echo $orderNotes;?></textarea>
                                        
                                    </div>
                                     <?php } ?>
                </li>
            </ul>
           
        </form>
      

    </div>
</div>
 
</div>   

<div class="col-sm-4">    
    
<div class="discount">
      <h3>Discount Codes</h3>
  <form id="discount-coupon-form" action="" method="post" class="promo-form">  

    <div class="promo-code-success-message" style="color: green;">
                            </div>
                            <div class="promo-code-message" style="color: red;">
                            </div>
                                                 
            <label for="coupon_code">Enter your coupon code if you have one.</label>
            <input type="hidden" name="remove" id="remove-coupone" value="0">                          
                <input class="form-control" type="text" id="coupon" name="coupon" value="">                                                      
                  <button type="button" title="Apply Coupon" class="button coupon "style="width:180px;background-color: #ec9f4e;align:center;margin-top:20px; padding:20px 0px 27px 0px"  id="promo-form-button" value="Apply Coupon"><span>Apply Coupon</span></button>                
                               
</form>

</div> <!--discount--> 
</div> <!--col-sm-4-->

<div class="col-sm-4">
 <div class="totals">
<h3>Shopping Cart Total</h3>
<div class="inner">

    <table id="shopping-cart-totals-table" class="table shopping-cart-table-total">
        <colgroup><col>
        <col width="1">
        </colgroup><tfoot>
            <tr>
  <!--  <td style="" class="a-left" >
        <strong>Grand Total</strong>
    </td>
    <td style="" class="a-right">
        <strong><span class="price"><?= $product_total_amount?></span></strong>
    </td>-->
</tr>
        </tfoot>
        <tbody>
          <tr >
    <!--<td style="" class="a-left" >
        Subtotal    </td>
    <td style="" class="a-right">
        <span class="price"><?= $product_total_amount?></span>    </td>-->


         <div class="checkout-sidebar" id="checkout-total-wrapper">

                                

                            </div>
</tr>
        </tbody>
    </table>
  
<ul class="checkoutnew">           
<li>
     <!-- Continue shopping --> 
                                <div class="checkout-promocode-form"  >
                                 <div class="form-group">
                                        <span class="input-group-btn"  onclick="setOrderNotes()">
                                            <a id="button-reward" href="<?php echo $continue.'/index.php?path=checkout/checkout'; ?>" class="btn btn-primary btnsetall" style="width: 100%;height: 100%;" type="button">Proceed to Check out
                                            </a>
                                        </span>
                                    </div>
                                
                                </div>
                            <!-- END Continue shopping --> 
</li>
</ul>                
</div><!--inner-->
 </div><!--totals-->
</div> <!--col-sm-4-->


</div> <!--cart-collaterals-->


</div>
    
    
    <?= $login_modal ?>
    <?= $signup_modal ?>
    <?= $forget_modal ?>
    <?= $contactus_modal ?>
    <div class="addressModal">
        <div class="modal fade" id="addressModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <div class="row">
                            <div class="col-md-12">
                                <h2><?= $text_new_delivery_adddress ?></h2>
                            </div>
                            <div id="address-message" class="col-md-12" style="color: red">
                            </div>
                            <div id="address-success-message" style="color: green">
                            </div>
                            <div class="addnews-address-form">
                                    <!-- Multiple Radios (inline) -->
                                <form id="new-address-form">
                                        
                                
                                    <input type="hidden" value="<?php echo $city_id; ?>" name="shipping_city_id" id="shipping_city_id">
                                    <input type="hidden" value="<?php echo $zipcode; ?>" name="shipping_zipcode" id="shipping_zipcode">

                                    <div class="form-group">
                                        <label class="col-md-12 control-label" for="address"></label>
                                        <div class="col-md-12">
                                            <div class="select-locations">
                                                <label class="control control--radio"><?= $text_home_address?>
                                                    <input type="radio" name="modal_address_type" value="home" checked="checked" />
                                                    <div class="control__indicator"></div>
                                                </label>
                                                <label class="control control--radio"><?= $text_office?>
                                                    <input type="radio" value="office" name="modal_address_type" />
                                                    <div class="control__indicator"></div>
                                                </label>
                                                <label class="control control--radio"><?= $text_other?>
                                                    <input type="radio" value="other" name="modal_address_type" />
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Text input-->
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label class="control-label" for="name"><?= $text_name ?></label>
                                            <input id="name" name="modal_address_name" type="text" placeholder="Name" value="<?= $name ?>" class="form-control input-md" required="">
                                        </div>
                                    </div>
                                    <!-- Text input-->
                                    <div class="form-group">
                                        <label class="col-md-12 control-label" for="flat"><?= $text_flat_house_office?></label>
                                        <div class="col-md-12">
                                            <input id="flat" name="modal_address_flat" type="text" placeholder="45, Sunshine Apartments" class="form-control input-md" required="">
                                        </div>
                                    </div>
                                  

                                    <input id="street" name="modal_address_street" type="hidden"  class="form-control input-md">
                                        
                                    <input id="picker_city_name" name="picker_city_name" type="hidden" value="">
                                    
                                    <!-- Text input-->

                                    <?php if($this->config->get('config_store_location') == 'zipcode') { ?>
                                        <div class="form-group">
                                            <label class="col-md-12 control-label" for="Locality"><?= $text_locality?></label>
                                            
                                            <?php if($check_address) { ?>

                                                <div class="col-md-12">
                                                    <div class="input-group">

                                                        <input id="Locality"  name="modal_address_locality" type="text"  placeholder="<?= $text_flat_house_office?>" class="form-control input-md LocalityId" required="">                                                    
                                                        <span class="input-group-btn">

                                                            <button id="locateme" class="btn btn-default disabled" style="color: #333;background-color: #fff;border-color: #ccc;line-height: 2.438571; " type="button" data-toggle="modal" onclick="openGMap()" data-target="#GMapPopup"  ><i class="fa-crosshairs fa"></i> <?= $locate_me ?> </button>

                                                        </span>
                                                    </div>
                                                </div>
                                            <?php } else { ?>
                                                <div class="col-md-12">
                                                    <input  name="modal_address_locality" id="Locality" type="text"  class="form-control input-md LocalityId" required="">
                                                </div>
                                            <?php } ?>

                                        </div>

                                    <?php } else { ?>
                                        
                                        <div class="form-group">
                                            <label class="col-md-12 control-label" for="Locality"><?= $text_locality?></label>
                                            
                                            <div class="col-md-12">

                                                <?php if( defined('const_latitude') && defined('const_longitude') && !empty(const_latitude) && !empty(const_longitude) ) { ?>
                                                    

                                                    <input  name="modal_address_locality" id="Locality" type="text" class="form-control input-md LocalityId" required="">
                                                    
                                                <?php } else { ?>
                                                    <?php if(!empty($address_locality)) { ?>
                                                    <input  name="modal_address_locality" id="Locality" type="text" style="background-color:#DEDEDE;" readonly value="<?= $address_locality ?>" class="form-control input-md LocalityId pac-target-input" required="">
                                                   <?php }else{ ?>
                                                    <input  name="modal_address_locality" id="Locality" type="text"  value="<?= $address_locality ?>" class="form-control input-md LocalityId pac-target-input" required="">
                                                   <?php } ?>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    <?php } ?>

                                    

                                    <?php if($this->config->get('config_store_location') == 'zipcode') { ?>
                                        <div class="form-group">
                                            <label class="col-md-12 control-label" for="zipcode"><?= $label_zipcode ?></label>
                                            <div class="col-md-12">
                                                <input id="shipping_zipcode" type="text" value="<?php echo $zipcode; ?>" name="shipping_zipcode" class="form-control input-md" disabled="true">
                                            </div>
                                        </div>
                                    <?php } else { ?>
                                        <input id="shipping_zipcode" type="hidden" value="<?php echo $zipcode; ?>" name="shipping_zipcode">
                                    <?php } ?>
                                    

                                    
                                    <!-- Button -->
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <button id="singlebutton" name="singlebutton" type="button" class="btn btn-primary btnsetall" onclick="saveInAddressBook()"><?= $text_save?></button>
                                            <button type="button" class="btn btn-grey" data-dismiss="modal"><?= $text_close?></button>
                                        </div>
                                    </div>

                                    <input type="hidden" name="latitude" value="<?= $latitude ?>" />
                                    <input type="hidden" name="longitude" value="<?= $longitude ?>" />

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="GMapPopup">
        <div class="modal fade" id="GMapPopup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <div class="row">

                            <div class="col-md-12">
                                <center> 
                                    <h2><?= $text_your_location ?> </h2>
                                </center>
                            </div>
                        </div>

                        <div id="wrapper">
                           
                            <div id="us1" style="width: 100%; height: 400px;"></div> 
                           
                           <div id="over_map">

                                <div class="input-group">

                                    <input  name="modal_address_locality" type="text" id="gmap-input" class="form-control input-md LocalityId LocalityId2" required="" >                                                    
                                    <span class="input-group-btn">

                                        <button class="btn btn-default" id="detect_location" style="color: #333;background-color: #fff;border-color: #ccc;width: 150px;line-height: 2.438571; " type="button"  onclick="getLocation()"><i class="fa fa-location-arrow"></i> <?= $detect_location ?></button>

                                    </span>
                                </div>
                                
                           </div>
                        </div>
                        
                        <style>
                           #wrapper { position: relative; }
                           #over_map { position: absolute; top: 10px; padding-right: 12px;
                                        padding-left: 12px;  z-index: 99; width: 100%}
                        </style>

                        <script type="text/javascript">
                            

                            
                        </script>
                        <div class="row" style="margin-top: 10px;">
                            
                            <center>
                                <button id="saveLatLng" type="button" class="btn btn btn-primary btnsetall" onclick="saveLatLng()"><?= $text_ok?></button>
                            </center>    
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <style type="text/css">
        .pac-container {
          z-index: 99999999;
        }
        #map * {
            overflow:visible;
        }


        .promo-form:before {             
           top: 46px;
           content:none;
  
}
    </style>

    <?= $footer ?>


    <!-- CSS Style -->
<!--<link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/stylesheet/bootstrap.min.css">-->
<link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/stylesheet/font-awesome.css" media="all">
<link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/stylesheet/revslider.css" >
<link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/stylesheet/owl.carousel.css">
<link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/stylesheet/owl.theme.css">
<link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/stylesheet/jquery.bxslider.css">
<link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/stylesheet/jquery.mobile-menu.css">
<link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/stylesheet/style.css" media="all">
<link rel="stylesheet" type="text/css" href="<?= $base;?>front/ui/stylesheet/responsive.css" media="all">

<link href="https://fonts.googleapis.com/css?family=Merriweather:300,300i,400,400i,700" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Oswald:300,400,500,600,700" rel="stylesheet">
<link href='http://fonts.googleapis.com/css?family=Open+Sans:700,600,800,400' rel='stylesheet' type='text/css'>
<link href="https://fonts.googleapis.com/css?family=Rubik:400,400i,500,500i,700,700i,900" rel="stylesheet">

   
    <script type="text/javascript" src="<?= $base;?>front/ui/theme/mvgv2/js/side-menu-script.js"></script>

    

    <script type="text/javascript" src="<?= $base;?>front/ui/theme/mvgv2/js/jquery.sticky.js"></script>
    <script type="text/javascript" src="<?= $base;?>front/ui/theme/mvgv2/js/header-sticky.js"></script>
    
    <!-- <script src="<?= $base;?>front/ui/theme/mvgv2/js/jquery.maskedinput.js" type="text/javascript"></script> -->
    
    <!-- <link rel="stylesheet" href="<?= $base;?>front/ui/theme/mvgv2/css/bootstrap-iso.css" /> -->
    <link rel="stylesheet" href="https://formden.com/static/cdn/bootstrap-iso.css" />

    <!-- <script type="text/javascript" src="<?= $base;?>front/ui/theme/mvgv2/js/bootstrap-datepicker.min.js"></script>
    <link rel="stylesheet" href="<?= $base;?>front/ui/theme/mvgv2/css/bootstrap-datepicker3.css"/> -->
    
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>

    <script type="text/javascript" src="https://maps.google.com/maps/api/js?key=<?= $this->config->get('config_google_api_key') ?>&libraries=places"></script>
    <script type="text/javascript" src="<?= $base?>admin/ui/javascript/map-picker/js/locationpicker.jquery.js?v=2.3"></script>
    
  <script type="text/javascript">
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


 $("#shopping-cart-table tbody").find("stop").click(function (){

      
   
var key=$(this).text().trim();
//alert($(this).text().trim());
console.log("cart["+key+"][qty]");
$("cart["+key+"][qty]").removeAttr('disabled');
 document.getElementById("cart["+key+"][qty]").disabled = false;

 });


  $("#shopping-cart-table tbody").find("p").click(function (){

      
    
var key=$(this).text().trim();
console.log(key);

 	$.ajax({
			url: 'index.php?path=checkout/cart/remove',
			type: 'post',
			data: 'key=' + key,
			dataType: 'json',
			beforeSend: function() {
				//$('#cart > button').button('loading');
			},
			complete: function() {
				//$('#cart > button').button('reset');
			},			
			success: function(json) {	
                            //refresh page 
                            location = location;
			}
		});
});


  function setOrderNotes()
{
    
     var dropoff_notes = $('textarea[name="dropoff_notes"]').val();
     
  
 document.cookie = "dropoff_notes="+dropoff_notes;
}

  $(document).delegate('#updatecart', 'click', function(){    

      console.log("updating the cart");  
   
 var complex = <?php echo json_encode($products); ?>;

   console.log(complex); 
 var i,j, len, text;
 j=1;
                for (i = 0, len = complex.length, text = ""; i < len; i++) {
                text = complex[i]['key']  ;
                console.log(text);
                console.log(j);
                // alert("cart["+j+"][qty]");
            var updatedQuantity =  
                document.getElementById("cart["+j+"][qty]").value;  
               j++;
               // alert(updatedQuantity) ;  
         
	 $.ajax({
			url: 'index.php?path=checkout/cart/update',
			type: 'post',
			data: 'key=' + complex[i]['key'] + '&quantity=' + (typeof(updatedQuantity) != 'undefined' ? updatedQuantity : 1),
			dataType: 'json',
			async: false, 
			beforeSend: function() {
				//$('#cart > button').button('loading');
			},
			complete: function() {
				//$('#cart > button').button('reset');
				
			},			
			success: function(json) {
                // Hide for qnty Box
				/*$qty_wrapper = $(document).find('#'+$product_store_id+'-'+$variation_id+' .middle-quantity').html($qty);
    			 $qty_wrapper = $(document).find('.unique'+$product_store_id+'-'+$variation_id+' .middle-quantity').html($qty);
    			 $qty_wrapper = $(document).find('.unique_middle_button'+$product_store_id+'-'+$variation_id).html($qty);
				*/
				 //reflact changes in list 
                $('#action_'+json['product_id']+'[data-variation-id="'+json['variation_id']+'"] .middle-quantity').html(json['quantity']);
                	location = 'index.php?path=checkout/checkoutitems';
                
				/*	if (json['location'] == 'cart-checkout') {
					location = 'index.php?path=checkout/cart';
				} else {
                
                    //update total count for mobile 
                    $('.shoppingitem-fig').html(json['count_products']);
                        
					$('#cart').load('index.php?path=common/cart/info');

					$('.cart-panel-content').load('index.php?path=common/cart/newInfo');

					$('.cart-count').html(json['count_products']);
                    $('.cart-total-amount').html(json['total_amount']);
				}*/

				$.ajax({
			        url: 'index.php?path=common/home/cartDetails',
			        type: 'post',
			        dataType: 'json',

			        success: function(json) {
			            console.log(json);

			            for (var key in json['store_note']) {
	                        //alert("User " + data[key] + " is #" + key); // "User john is #234"
	                        $('.store_note'+key).html(json['store_note'][key]);

	                        console.log(json['store_note'][key]);
	                    }

			            if (json['status']) {
			                console.log("yesz");
			                
			                $("#proceed_to_checkout").removeAttr("disabled");
			                $("#proceed_to_checkout").attr("href", json['href']);
			                //$("#proceed_to_checkout_button").html(json['text_proceed_to_checkout']);
			                //$('.checkout-modal-text').html(json['text_proceed_to_checkout']);

			                $("#proceed_to_checkout_button").css({ 'background-color' : '', 'border-color' : '' });
			                $('.checkout-modal-text').html(json['text_proceed_to_checkout']);
                        	$('.checkout-loader').hide();
			                
			            } else {    
			                console.log("no frm jsz");
			                $("#proceed_to_checkout").attr("disabled", "disabled");
			                $("#proceed_to_checkout").removeAttr("href");
			                //$("#proceed_to_checkout_button").html(json['amount']);
			                //$('.checkout-modal-text').html(json['amount']);
                        	$('.checkout-loader').hide();
                        	$('.checkout-modal-text').html(json['text_proceed_to_checkout']);
                        	$("#proceed_to_checkout_button").css('background-color', '#ccc');
			                $("#proceed_to_checkout_button").css('border-color', '#ccc');

                        	

			            }
			            
			            
			        }
			    });

			    


			}
		});
 }
         window.location.reload(true);
    });

      
// Cart add remove functions
var cart = {
	'add': function(product_id, quantity, variation_id, store_id=null) {

		
		console.log("add variation id", variation_id);
		$.ajax({
			url: 'index.php?path=checkout/cart/add',
			type: 'post',
			data: 'variation_id='+variation_id+'&product_id=' + product_id + '&quantity=' + (typeof(quantity) != 'undefined' ? quantity : 1)+'&store_id=' + store_id,
			dataType: 'json',
			beforeSend: function() {
				//$('#cart > button').button('loading');
			},
			complete: function() {
				//$('#cart > button').button('reset');
			},			
			success: function(json) {
				console.log(json);
				console.log("jsonxxxxx");
				//console.log($('.normalalert').html());
				console.log("json");
				//$('.alert, .text-danger').remove();

				if (json['redirect']) {
					location = json['redirect'];
				}

				if (json['success']) {

					$('.plus-quantity[data-id="'+product_id+'"]').attr('data-key', json['key']);
					$('.minus-quantity[data-id="'+product_id+'"]').attr('data-key', json['key']);

					//$('#add-btn[data-id="'+product_id+'"]').css({ 'display': "none" });
					//$('#add-btn[data-id="'+product_id+'"]').removeAttr('display');
					$('#add-btn[data-id="'+product_id+'"]').css({ 'display': "none" });


					$('.inc-dec-quantity[data-id="'+product_id+'"]').css({ 'display': "block" });
					

					//$('html, body').animate({ scrollTop: 0 }, 'slow');
					//update total count for mobile 
					$('.shoppingitem-fig').html(json['count_products']);
					$('.cart-panel-content').load('index.php?path=common/cart/newInfo');
                    $('#cart').load('index.php?path=common/cart/info');

                    $('.cart-count').html(json['count_products']);
                    $('.cart-total-amount').html(json['total_amount']);

                    $('.cart-total-amount').html(json['total_amount']);
				}

				

				//window.location.reload(true);
			}
		});
	},
	'update': function(key, quantity) {

		var text = $('.checkout-modal-text').html();
	    $('.checkout-modal-text').html('');
	    $('.checkout-loader').show();

		console.log("cart update api js file");
		$.ajax({
			url: 'index.php?path=checkout/cart/update',
			type: 'post',
			data: 'key=' + key + '&quantity=' + (typeof(quantity) != 'undefined' ? quantity : 1),
			dataType: 'json',
			async: false, 
			beforeSend: function() {
				//$('#cart > button').button('loading');
			},
			complete: function() {
				//$('#cart > button').button('reset');
				
			},			
			success: function(json) {
                // Hide for qnty Box
				/*$qty_wrapper = $(document).find('#'+$product_store_id+'-'+$variation_id+' .middle-quantity').html($qty);
    			 $qty_wrapper = $(document).find('.unique'+$product_store_id+'-'+$variation_id+' .middle-quantity').html($qty);
    			 $qty_wrapper = $(document).find('.unique_middle_button'+$product_store_id+'-'+$variation_id).html($qty);
				*/
				 //reflact changes in list 
                $('#action_'+json['product_id']+'[data-variation-id="'+json['variation_id']+'"] .middle-quantity').html(json['quantity']);
                
				if (json['location'] == 'cart-checkout') {
					location = 'index.php?path=checkout/cart';
				} else {
                
                    //update total count for mobile 
                    $('.shoppingitem-fig').html(json['count_products']);
                        
					$('#cart').load('index.php?path=common/cart/info');

					$('.cart-panel-content').load('index.php?path=common/cart/newInfo');

					$('.cart-count').html(json['count_products']);
                    $('.cart-total-amount').html(json['total_amount']);
				}

				$.ajax({
			        url: 'index.php?path=common/home/cartDetails',
			        type: 'post',
			        dataType: 'json',

			        success: function(json) {
			            console.log(json);

			            for (var key in json['store_note']) {
	                        //alert("User " + data[key] + " is #" + key); // "User john is #234"
	                        $('.store_note'+key).html(json['store_note'][key]);

	                        console.log(json['store_note'][key]);
	                    }

			            if (json['status']) {
			                console.log("yesz");
			                
			                $("#proceed_to_checkout").removeAttr("disabled");
			                $("#proceed_to_checkout").attr("href", json['href']);
			                //$("#proceed_to_checkout_button").html(json['text_proceed_to_checkout']);
			                //$('.checkout-modal-text').html(json['text_proceed_to_checkout']);

			                $("#proceed_to_checkout_button").css({ 'background-color' : '', 'border-color' : '' });
			                $('.checkout-modal-text').html(json['text_proceed_to_checkout']);
                        	$('.checkout-loader').hide();
			                
			            } else {    
			                console.log("no frm jsz");
			                $("#proceed_to_checkout").attr("disabled", "disabled");
			                $("#proceed_to_checkout").removeAttr("href");
			                //$("#proceed_to_checkout_button").html(json['amount']);
			                //$('.checkout-modal-text').html(json['amount']);
                        	$('.checkout-loader').hide();
                        	$('.checkout-modal-text').html(json['text_proceed_to_checkout']);
                        	$("#proceed_to_checkout_button").css('background-color', '#ccc');
			                $("#proceed_to_checkout_button").css('border-color', '#ccc');

                        	

			            }
			            
			            
			        }
			    });

			    


			}
		});

		
	},
	'remove': function(key) {

		var text = $('.checkout-modal-text').html();
	    $('.checkout-modal-text').html('');
	    $('.checkout-loader').show();

	
		
	},
	'update_product_type': function(key,value) {
		console.log("update product_type");
		$.ajax({
			url: 'index.php?path=checkout/cart/updateProductType',
			type: 'post',
			data: 'key=' + key+ '&product_type='+value,
			dataType: 'json',
			beforeSend: function() {
			},
			complete: function() {
			},			
			success: function(json) {	
				console.log("update product_type end");
				console.log(json);
				console.log("update product_type");
			}
		});
	}
}

	
    </script>;
    <style>
    #shopping-cart-table a.remove-item {
	background-color: #ec9f4e !important;
	background-image: none;
	color: #333;
	cursor: pointer;
	padding: 8px 13px;
	cursor: pointer;
	text-decoration: none;
	float: left;
	transition: all 0.3s linear;
	-moz-transition: all 0.3s linear;
	-webkit-transition: all 0.3s linear;
	border: 1px #ddd solid;
	border-radius: 999px;

}
#shopping-cart-table a.remove-item:hover {
	background-color: #ed6663;
	color: #fff;
	border: 1px #ed6663 solid
}
#shopping-cart-table a.remove-item:before {
	content: "\f014";
	font-family: FontAwesome;
	font-size: 25px;
    background-color: #ec9f4e !important;
}
#shopping-cart-table a.remove-item span {
	display: none;
}
</style>

    <script type="text/javascript">
        console.log("map address");
            
        $('#us1').locationpicker({
            location: {
                latitude: <?= $latitude?$latitude:0 ?>,
                longitude: <?= $longitude?$longitude:0 ?>
            },  
            radius: 0,
            inputBinding: {
                latitudeInput: $('input[name="latitude"]'),
                longitudeInput: $('input[name="longitude"]'),
                locationNameInput: $('.LocalityId')
            },
            enableAutocomplete: true,
            zoom:13,

        }); 


        function saveLatLng() {
            $('#GMapPopup').modal('hide');
            $('.LocalityId').val($('.LocalityId').val());
        }


        function openGMap() {

            $("#GMapPopup").on('shown.bs.modal', function () {
                $('#us1').locationpicker('autosize');
            });
        }

        function GMapPopupInput() {

            var acInputs = document.getElementsByClassName("LocalityId2");

            

            var autocomplete = new google.maps.places.Autocomplete(acInputs);
            
            google.maps.event.addListener(autocomplete, 'place_changed', function () {
                    
                console.log("latitude");
                console.log(autocomplete);
                $('#us1').locationpicker({
                    location: {
                        latitude: position.coords.latitude,
                        longitude: position.coords.longitude
                    },  
                    radius: 0,
                    inputBinding: {
                        latitudeInput: $('input[name="latitude"]'),
                        longitudeInput: $('input[name="longitude"]'),
                        locationNameInput: $('.LocalityId2')
                    },
                    enableAutocomplete: true,
                    zoom:13,
                });
            });
        }

        

        function initialize() {

            var acInputs = document.getElementsByClassName("LocalityId");

            for (var i = 0; i < acInputs.length; i++) {

                var autocomplete = new google.maps.places.Autocomplete(acInputs[i]);
                
                google.maps.event.addListener(autocomplete, 'place_changed', function () {
                });
            }
        }

        function getLocation() {

            $('#detect_location').html('<i class="fa fa-location-arrow"></i> <?= $text_locating ?>');
            console.log("getLocation");

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }

        function showPosition(position) {
            //var latlon = position.coords.latitude + "," + position.coords.longitude;
            console.log("showPosition");
            console.log(position);

            

            $('#us1').locationpicker({
                location: {
                    latitude: position.coords.latitude,
                    longitude: position.coords.longitude
                },  
                radius: 0,
                inputBinding: {
                    latitudeInput: $('input[name="latitude"]'),
                    longitudeInput: $('input[name="longitude"]'),
                    locationNameInput: $('.LocalityId')
                },
                enableAutocomplete: true,
                zoom:13,
            });

            console.log($('#us1').locationpicker('location'));

            $('#detect_location').html('<i class="fa fa-location-arrow"></i> <?= $detect_location ?>');
        }
    </script>


<?php if ($kondutoStatus) { ?>

    
<script src="https://i.k-analytix.com/konduto.min.js" type="text/javascript"></script>

<script type="text/javascript">

  var __kdt = __kdt || [];

  var public_key = '<?php echo $konduto_public_key ?>';

  console.log("public_key");
  console.log(public_key);
__kdt.push({"public_key": public_key}); // The public key identifies your store
__kdt.push({"post_on_load": false});   
  (function() {
           var kdt = document.createElement('script');
           kdt.id = 'kdtjs'; kdt.type = 'text/javascript';
           kdt.async = true;    kdt.src = 'https://i.k-analytix.com/k.js';
           var s = document.getElementsByTagName('body')[0];

           console.log(s);
           s.parentNode.insertBefore(kdt, s);
            })();

            var visitorID;
    (function() {
      var period = 300;
      var limit = 20 * 1e3;
      var nTry = 0;
      var intervalID = setInterval(function() {
      var clear = limit/period <= ++nTry;

      console.log("visitorID trssy");
      if (typeof(Konduto.getVisitorID) !== "undefined") {
               visitorID = window.Konduto.getVisitorID();
               clear = true;
      }
      console.log("visitorID clear");
      if (clear) {
     clearInterval(intervalID);
    }
    }, period);
    })(visitorID);

    var page_category = 'checkout-page';
    (function() {
      var period = 300;
      var limit = 20 * 1e3;
      var nTry = 0;
      var intervalID = setInterval(function() {
               var clear = limit/period <= ++nTry;
               if (typeof(Konduto.sendEvent) !== "undefined") {

                Konduto.sendEvent (' page ', page_category); //Programmatic trigger event
                    clear = true;
               }
             if (clear) {
            clearInterval(intervalID);
         }
        },
        period);
        })(page_category);
</script>

<?php } ?>


    <script type="text/javascript">

    function show() {
        $(".overlayed").show();
    }
    function hide() {
        $(".overlayed").hide();
    }

   
    $(document).ready(function() {

        $('.replacable').on('click', function(){
            console.log("replacable");
            if($(this).attr('data-value') == 'replacable') {
                //toggle
                console.log("repl yes");
                $(this).attr('data-value', 'not-replacable');
                $(this).removeClass('badge-success');
                $(this).addClass('badge-danger');
                $(this).html('<?= $text_not_replacable ?>');
                
                $(this).attr('title', '<?= $text_not_replacable_title ?>');
            } else {
                console.log("nss");
                $(this).attr('data-value', 'replacable');
                $(this).removeClass('badge-danger');
                $(this).addClass('badge-success');
                $(this).html('<?= $text_replacable ?>');
                $(this).attr('title', '<?= $text_replacable_title ?>');
            }   


            $product_type = $(this).attr('data-value');  

            console.log($product_type);
            $this = $(this);
            
            
            if($this.attr('data-key').length > 0) {
                console.log("replacable first"+$product_type);
                console.log($this.attr('data-key'));
                var response = cart.update_product_type($this.attr('data-key'),$product_type);
            }

             loadTotals($(this).attr('data-city_id'));
        });

        $('.dropdown-toggle').dropdown();
        
        var divs = $('.mydivs>div');
        var now = 0; // currently shown div
        divs.hide().first().show();
        $("button[name=next]").click(function(e) {
            divs.eq(now).hide();
            now = (now + 1 < divs.length) ? now + 1 : 0;
            divs.eq(now).show(); // show next
        });
        $("button[name=prev]").click(function(e) {
            divs.eq(now).hide();
            now = (now > 0) ? now - 1 : divs.length - 1;
            divs.eq(now).show(); // or .css('display','block');
            //console.log(divs.length, now);
        });
    });
    </script>


<script type="text/javascript">
    $('#button-reward').on('click', function() {
        $.ajax({
            url: 'index.php?path=checkout/reward/reward',
            type: 'post',
            data: 'reward=' + encodeURIComponent($('input[name=\'reward\']').val()),
            dataType: 'json',
            beforeSend: function() {
                $('#button-reward').button('loading');
                $('#reward-error').hide();
                $('#reward-success').hide();
            },
            complete: function() {
                $('#button-reward').button('reset');
            },
            success: function(json) {
                if (json['error']) {
                    $('#reward-error').html('<p id="error">'+json['error']+'</p>').show();
                }else{
                    $('#reward-success').html('<p id="success">'+json['success']+'</p>').show();
                    loadTotals($('input#shipping_city_id').val());
                }
            }
        });
    });
    $(document).delegate('.addressmenu li', 'click', function() {
        $('input[name="shipping_contact_no"]').val($(this).attr('data-contact_no'));
        $('input[name="shipping_name"]').val($(this).attr('data-name'));
        $('textarea[name="shipping_address"]').val($(this).attr('data-address'));
        $('input[name="shipping_city_id"]').val($(this).attr('data-city_id'));

        $('input[name="flat_number"]').val($(this).attr('data-flat_number'));
        $('input[name="building_name"]').val($(this).attr('data-building_name'));
        $('input[name="landmark"]').val($(this).attr('data-landmark'));

        loadTotals($(this).attr('data-city_id'));
    });
</script>

<script type="text/javascript">

// Load totals
function loadTotals($city_id) {

    console.log('sri divya');

    $('#checkout-total-wrapper').html('<center><div class="login-loader" style=""></div></center>');

    $.ajax({
        url: 'index.php?path=checkout/totals&city_id=' + $city_id,
        type: 'post',
        dataType: 'html',
        cache: false,
        async: true,
        beforeSend: function() {
            
        },
        success: function(html) {
            $('#checkout-total-wrapper').html(html);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
}
//Load Delivery Time
function loadDeliveryTime(store_id) {

    $('#delivery-time-wrapper-'+store_id+'').html('<center><div class="login-loader" style=""></div></center>');

    console.log("loadDeliveryTime");
    var shipping_method = $('input[name=\'shipping_method-'+store_id+'\']:checked').attr('value')
    console.log(shipping_method);
    console.log("shipping_method");
    //$('input[id="shipping_method"]').val(shipping_method);

    if($('input[id="shipping_method"]').val() == 'express.express') {
        $('#timeslot-next-hidden').attr("href","#collapseFour");
        $('#delivery_time_panel_link').attr("href","");

        $('input[name="shipping_time_selected"]').val('');
        $('input[name="dates_selected"]').val('');
        saveOrder();
    } else {
        $('#timeslot-next-hidden').attr("href","#collapseThree");
        $('#delivery_time_panel_link').attr("href","#collapseThree");
    }

    $.ajax({
        url: 'index.php?path=checkout/delivery_time&shipping_method='+shipping_method+'&store_id='+store_id+'',
        type: 'get',
        dataType: 'html',
        cache: false,
        async: true,
        beforeSend: function() {
            // $('#delivery-time-wrapper-'+store_id+'').html('<div class="text-center"><i class="fa fa-spinner fa-spin checkout-spin"></i></div>');
        },
        success: function(html) {
            console.log(html);
            $('#delivery-time-wrapper-'+store_id+'').html(html);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
}


function getTimeSlot(store_id,date) {

    var shipping_method = $('input[name=\'shipping_method-'+store_id+'\']:checked').attr('value')
    
    //$('input[id="shipping_method"]').val(shipping_method);

    $.ajax({
        url: 'index.php?path=checkout/delivery_time/get_time_slot&shipping_method='+shipping_method+'&store_id='+store_id+'&date='+date+'',
        type: 'get',
        dataType: 'html',
        cache: false,
        async: false,
        beforeSend: function() {
        },
        success: function(html) {
            $('#timeslot_'+store_id+'').html(html);
            loadPaymentMethods();
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
}

// Methods related with shipping
$(document).ready(function() {
    console.log("logged in as ");

     loadTotals($(this).attr('data-city_id'));
    <?php

        if($loggedin && $profile_complete) { ?>
            
            console.log("remove href");
            $('#delivery_option__panel_link').attr("href","");
            $('#delivery_time_panel_link').attr("href","");
            $('#payment_panel_link').attr("href","");
            document.getElementById('address-next').click();
            
        <?php } else { ?>
            $('#address_panel_link').attr("href","");
            $('#delivery_time_panel_link').attr("href","");//#collapseThree
            $('#delivery_option__panel_link').attr("href","");//#collapseDeliveryOptions
            $('#payment_panel_link').attr("href","");//#collapseFour
        <?php }
        if ($shipping_required) { 

        foreach ($store_data as $os): 
        ?>
            console.log("call to loadShippingMethods");
            loadShippingMethods('<?php echo $os["store_id"] ?>'); 
            
        <?php
        endforeach;
        } ?>
    
    loadPaymentMethods();
});

<?php
if ($shipping_required) { 

?>
    // Load shipping methods
    function loadShippingMethods(store_id) {
        data = {
            store_id : store_id
        }
        
        $.ajax({
            url: 'index.php?path=checkout/shipping_method',
            type: 'post',
            dataType: 'html',
            data:data,
            cache: false,
            async: false,
            beforeSend: function() {
                $('#shipping-method-wrapper-'+store_id+'').html('<div class="text-center"><i class="fa fa-spinner fa-spin checkout-spin"></i></div>');

            },
            success: function(html) {
                console.log("shipping-method-wrapper");
                console.log(html);
                $('#shipping-method-wrapper-'+store_id+'').html(html);
                saveShippingMethod(store_id);   
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }

    // Save the selected shipping method
    function saveShippingMethod(store_id) {
        console.log("save-shipping-method");
        console.log(store_id);

        $('#timeslot-next').removeAttr('disabled');

        var shipping_method = $('input[name=\'shipping_method-'+store_id+'\']:checked').attr('value');
        console.log(shipping_method); 
        if (shipping_method == undefined) {
            shipping_method = 0;
        }

        $.ajax({
            url: 'index.php?path=checkout/shipping_method/save',
            type: 'post',
            data: {
                store_id:store_id,
                shipping_method: shipping_method
            },
            dataType: 'html',
            cache: false,
            async: true,
            success: function(json) {

                console.log("shipng resp"); 
                console.log(json); 

                var obj = JSON.parse(json);
                console.log(obj.shipping_name); 

                if (json['redirect']) {
                    //location = json['redirect'];
                } else if (json['error']) {
                    if (json['error']['warning']) {
                        $('#shipping-method-wrapper').prepend('<div class="alert alert-warning">' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                    }
                } else {

                    if(obj.shipping_name) {

                        if(shipping_method == 'express.express') {
                            $('#select-timeslot').html(obj.express_minutes);    
                            $('#select-delivery-method').html("Selected delivery method : "+obj.shipping_name);    
                        } else {
                            $('#select-timeslot').html('Please select a timeslot');    
                            $('#select-delivery-method').html("Selected delivery method : "+obj.shipping_name);        
                        }
                        
                    }
                    

                    loadDeliveryTime(store_id);
                    loadTotals($('input[name="shipping_city_id"]').val());
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                //alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }

     <?php

} ?>




// Load payment methods
function loadPaymentMethods() {
    console.log("load loadPaymentMethods");

    $.ajax({
        url: 'index.php?path=checkout/payment_method',
        type: 'post',
        dataType: 'html',
        cache: false,
        async: false,
        beforeSend: function() {
            $('#payment-method-wrapper').html('<div class="text-center"><i class="fa fa-spinner fa-spin checkout-spin"></i></div>');
        },
        success: function(html) {
            console.log("loaded loadPaymentMethods");
            $('#payment-method-wrapper').html(html);
            savePaymentMethod();
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
}
// Save the selected payment method
function savePaymentMethod() {
    console.log("savepayment");

    var payment_method = $('input[name=\'payment_method\']:checked').attr('value');
    if (payment_method == undefined) {
        payment_method = 0;
    }
    console.log(payment_method);

    $('#payment-method-wrapper-loader').show();
    $('#payment-method-wrapper').hide();
    $('#pay-confirm-order').hide();
    

    $.ajax({
        url: 'index.php?path=checkout/payment_method/save',
        type: 'post',
        data: {
            payment_method: payment_method
        },
        dataType: 'html',
        cache: false,
        async: true,
        beforeSend: function() {

            $('#new-confirm-order').attr('id', 'confirm-order');
            $('.confirm-order-loader').css({ 'display': "none" });
            $('#confirm-order').css({ 'display': "block" });
            $('#pay-confirm-order').css({ 'display': "none" });
            $('.confirm-order-text').html('Confirm button');
            // confirm-order,confirm-order-loader,pay-confirm-order
        },
        success: function(json) {
            console.log(json);
            if (json['redirect']) {
                //  location = json['redirect'];
            } else if (json['error']) {
                if (json['error']['warning']) {
                    $('#payment-method-wrapper').prepend('<div class="alert alert-warning">' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                }
            } else {
                loadConfirm();
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        },
        complete: function() {

            $('#payment-method-wrapper-loader').hide();
            $('#payment-method-wrapper').show();
            $('#pay-confirm-order').show();
        },
    });
}
// Load confirm page
function loadConfirm() {
    console.log("loadConfirm");
    saveOrder();
}
function saveAddress() {
    return true;
}
function saveNewTimeSlot(store_id,timeslot,date) {
    console.log("saveTimeSlot new");
    
    $('#payment-next').removeAttr('disabled');
    $('#payment-next').removeClass('btn-grey');
    $('#payment-next').addClass('btn-default');

    data = {
        store_id :store_id,
        date : date,
        timeslot :timeslot
    }

    console.log(data);

    $.ajax({
        url: 'index.php?path=checkout/delivery_time/save',
        type: 'post',
        data:data,
        dataType: 'html',
        beforeSend: function() {
            //$('#confirm-wrapper').html('<div class="text-center"><i class="fa fa-spinner fa-spin checkout-spin"></i></div>');
        },
        success: function(html) {
            //$('#confirm-wrapper').html(html);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });

    return false;
}

function saveOrder() {

    console.log("saveOrder");

    /*$.ajax({
        url: 'index.php?path=checkout/confirm/confirmPayment',
        type: 'post',
        data: $('#place-order-form').serialize(),
        dataType: 'html',
        cache: false,
        beforeSend: function() {
            $('#confirm-order').html('<div class="text-center"><i class="fa fa-spinner fa-spin checkout-spin"></i></div>');
        },
        success: function(html) {
            console.log(html);
            $('#confirm-order').html(html);

            <button type="button" id="button-confirm" class="btn-account-checkout btn-large btn-orange">CONFIRM ORDER</button>

            <button type="button" id="confirm-order" class="collapsed btn btn-default">
                <span class="confirm-order-text"><?= $button_confirm ?></span>
                <div class="confirm-order-loader" style="display: none;"></div>
            </button>
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });

    return false;*/
    $error = false;

    var shipping_name = $('input[name="shipping_name"]').val();
    var shipping_contact_no = $('input[name="shipping_contact_no"]').val();
    var shipping_address = $('textarea[name="shipping_address"]').val();
    var shipping_city_id = $('input[name="shipping_city_id"]').val();

    var landmark = $('input[name="landmark"]').val();
    var building_name = $('input[name="building_name"]').val();
    var flat_number = $('input[name="flat_number"]').val();
    var address_type = $('input[name="address_type"]').val();


    var dropoff_notes = $('textarea[name="dropoff_notes"]').val();
    

    //$('input[name="shipping_address_id"]').val($(this).attr('data-address-id'));
    if ($('input[name="shipping_address_id"]').val().length <= 0) {
        $error = true;
        console.log($('input[name="shipping_address_id"]').val()+"err");
        $('input[name="shipping_address_id"]').parents('.form-group').addClass('has-error').find('.help-block').show();
    }

    appendDataToSend = '';
    <?php foreach ($store_data as $os):  ?>
        var shipping_method = $('input[name=\'shipping_method-'+<?php echo $os['store_id'] ?>+'\']:checked').attr('value')
        if (shipping_method.length <= 0) {
            console.log("shipping_method selected");
            $error = true;
        }
        console.log("shipping-method-wrapper"+shipping_method);

        /*if($('#delivery-time-wrapper-<?php echo $os["store_id"] ?> ul.list-group input[type=radio]:checked').val() == undefined ) {
            $error = true;
            console.log("shipping-method-wrappercer not selected");
        }*/
        var note =  encodeURIComponent($('textarea[name=dropoff_notes-<?php echo $os["store_id"] ?>]').val());
        appendDataToSend += '&dropoff_notes['+<?php echo $os['store_id'] ?>+']='+note;

    <?php endforeach; ?>


   // var sendData = $('#place-order-form').serialize() + '&dropoff_notes=' + dropoff_notes+appendDataToSend;
    var sendData =   '&dropoff_notes=' + dropoff_notes+appendDataToSend;
    console.log('sendData');
    console.log(sendData);
    if (!$error) {

        $valid_address = 0;
        $.ajax({
            url: 'index.php?path=checkout/confirm/multiStoreIndex',
            type: 'post',
            data: sendData,
            dataType: 'html',
            cache: false,
            async: false,
            success: function(json) {
                console.log("json");
                console.log(json);
                $('#confirm-order').css({ 'display': "none" });
                $('#confirm-order').attr('id', 'new-confirm-order');
                /*$('#confirm-order').remove(); */
                $('#pay-confirm-order').html(json);
                $('#pay-confirm-order').removeAttr('style');
                
                return true;
                    //window.location = json.redirect;
                },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                $('#button-confirm').button('reset');
                return false;
            }
        });

        return true;

        /*if ($valid_address) {
            return true;
        }*/

    } else {
        $('html, body').animate({
            scrollTop: 0
        }, 'slow');
        $('#button-confirm').button('reset');
        return false;
    }
}

function saveInAddressBook() {

    console.log("saveInAddressBook");
    $('.alert').remove();
    $('#save-address').button('saving');

    $('.help-block').hide();
    $('.has-error').removeClass('has-error');

    $error = false;
    //var shipping_address = $('input[name="modal_address_street"]').val();
    var shipping_zipcode = $('input[name="shipping_zipcode"]').val();
    var shipping_city_id = $('input[name="shipping_city_id"]').val();
    var landmark = $('input[name="modal_address_locality"]').val();
    var building_name = $('input[name="modal_address_name"]').val();
    var flat_number = $('input[name="modal_address_flat"]').val();
    var address_type = $('input[name="modal_address_type"]:checked').val();
    //validate all fields

    if (landmark.length <= 0) {
        $error = true;
        $('input[name="modal_address_locality"]').parents('.form-group').addClass('has-error').find('.help-block').show();
    }
    if (building_name.length <= 0) {
        $error = true;
        $('input[name="modal_address_name"]').parents('.form-group').addClass('has-error').find('.help-block').show();
    }
    if (flat_number.length <= 0) {
        $error = true;
        $('input[name="modal_address_flat"]').parents('.form-group').addClass('has-error').find('.help-block').show();
    }
    if (address_type.length <= 0) {
        $error = true;
        $('input[name="modal_address_type"]').parents('.form-group').addClass('has-error').find('.help-block').show();
    }

    console.log(landmark+"**"+building_name+"**"+flat_number+"**"+address_type);
    
    if (!$error) {

        $valid_address = 0;
        $.ajax({
            url: 'index.php?path=checkout/address/addInAddressBook',
            type: 'post',
            async: false,
            data: $('#new-address-form').serialize(),
            dataType: 'json',
            cache: false,
            success: function(json) {

                console.log(json);
                console.log("checkout address add success");
                if (json.status == 0) {
                    $('#address-message').html(json['message']);
                    $('#address-success-message').html('');

                    return false;
                } else {
                    $('input[name="shipping_address_id"]').val(json.address_id);
                    $('#address-panel').html(json.html);
                    $('#addressModal').modal('hide');
                    $('.close').click();
                    return false;
                    //console.log("address add success else after return");

                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                $('#button-confirm').button('reset');
                return false;
            }
        });
        console.log("return false");
        return true;
    } else {
        /*$('html, body').animate({
            scrollTop: 0
        }, 'slow');
        $('#button-confirm').button('reset');*/
        return false;
    }
}
    $('.check-change').keyup(function(){
      //your stuff
      console.log("in change");
        $('#save-address').prop('disabled', false);
    });

    $(document).delegate('#open-address', 'click', function() {
        $('input[name="shipping_address_id"]').val($(this).attr('data-address-id'));
        console.log("address id selected"+$(this).attr('data-address-id'));        
        
        $.ajax({
            url: 'index.php?path=checkout/confirm/setAddressIdSession',
            type: 'post',
            async: true,
            data: {'shipping_address_id' : $(this).attr('data-address-id') },
            dataType: 'json',
            success: function(json) {
                console.log("address selected");
                $('#select-address').html(json['address']);

                <?php 

                foreach ($store_data as $os): 
                ?>
                    console.log("call to loadShippingMethods");
                    loadShippingMethods('<?php echo $os["store_id"] ?>'); 
                    
                <?php
                endforeach;?>

                $('#step-2').addClass('checkout-step-color');


                $('#delivery-option').click();

            }
        });

        
        //$(this).css({'background-color' : "green",'border-color' : "green"});
    });
    $(document).delegate('#dates_selected', 'click', function() {
        $('input[name="dates_selected"]').val($(this).attr('data-value'));
        console.log("address id selected"+$(this).attr('data-value'));
    });

    // $(document).delegate('#time_selected', 'click', function() {
    //     console.log($(this));
    //     console.log("time id selected"+$(this).attr('data-value'));
    //     console.log("time id selected"+$(this).attr('data-date'));
    // });

    $(document).delegate('#confirm-order', 'click', function() {
        console.log("order confirm click");

        var text = $('.confirm-order-text').html();
        console.log(text);
        $('.confirm-order-text').html('');
        $('.confirm-order-loader').show();
        setTimeout(function(){saveOrder();},200);
        
    });

</script>
<script type="text/javascript">

    $('.date-dob').datepicker({
        pickTime: false,
        format: 'dd/mm/yyyy',
        todayHighlight: true,
        autoclose: true,
    });

    /*jQuery(function($){
        console.log("mask");
       $("#phone_number").mask("<?= $telephone_mask_number ?>",{autoclear:false,placeholder:"<?= $telephone_mask ?>"});
    });*/

    /*jQuery(function($) {
        console.log(" fax-number mask");
       $("#fax-number").mask("<?= $taxnumber_mask_number ?>",{autoclear:false,placeholder:"<?= $taxnumber_mask ?>"});
    });*/
    
    $(document).delegate('#checkoutLogout', 'click', function() {
        console.log("checkout lohout click");

        $.ajax({
            url: 'index.php?path=account/logout/checkoutLogout',
            type: 'post',
            dataType: 'json',
            success: function(json) {
                console.log(json);
                if (json['status']) {
                    //location = json['redirect'];
                    window.location.reload(false);
                } else {
                }
            }
        });
    });
    

    $(document).delegate('#timeslot-next', 'click', function() {
        $('#step-3').addClass('checkout-step-color');
        console.log("timeslot-next click");

        $('#timeslot-next').html('<center><div class="login-loader" style=""></div></center>');

        var bothExpress = true;

        <?php foreach ($store_data as $os):  ?>
            var shipping_method = $('input[name=\'shipping_method-'+<?php echo $os['store_id'] ?>+'\']:checked').attr('value');

            console.log("shipping_method"+shipping_method);
            
            if (shipping_method != 'express.express') {
                bothExpress = false;
            }

        <?php endforeach; ?>

        console.log(bothExpress);
    
        if(bothExpress) {

            console.log('both express');
            

            <?php if($checkout_question_enabled) { ?>
                $('#timeslot-next-hidden').attr("href","#collapseQuestion");    
            <?php } else { ?>
                $('#timeslot-next-hidden').attr("href","#collapseFour");
            <?php } ?>

            $('#delivery_time_panel_link').attr("href","");            

        } else {
            $('#timeslot-next-hidden').attr("href","#collapseThree");
            $('#delivery_time_panel_link').attr("href","#collapseThree");
        }

        var p = saveOrder();

        console.log("timeslot  next saveOrder");
        
        $('#timeslot-next').html('<?= $text_next?>');
        
        $('#timeslot-next-hidden').click();

        $('#delivery_option__panel_link').attr("href","#collapseDeliveryOptions");
    });

    $(document).delegate('#payment-next', 'click', function() {
        $('#step-4').addClass('checkout-step-color');
        console.log("payment-next click");

        $('#payment-next').html('<center><div class="login-loader" style=""></div></center>');

        $error = false;

        <?php foreach ($store_data as $os):  ?>
            var shipping_method = $('input[name=\'shipping_method-'+<?php echo $os['store_id'] ?>+'\']:checked').attr('value')
            if (shipping_method.length <= 0) {
                console.log("shipping_method not selected");
                $error = true;
            }
            console.log("shipping-method-wrapper"+shipping_method);

            //console.log($('#delivery-time-wrapper-<?php echo $os["store_id"] ?> ul.list-group input[type=radio]:checked').val());

            if($('#delivery-time-wrapper-<?php echo $os["store_id"] ?> ul.list-group').length) {
                if($('#delivery-time-wrapper-<?php echo $os["store_id"] ?> ul.list-group input[type=radio]:checked').val() == undefined ) {
                    $error = true;
                    console.log("shipping-method-wrappercer not selected");
                }    
            } else {
                // shipping is express
            }
            


        <?php endforeach; ?>

        if (!$error) {
            $('#delivery-time-wrapper').click();
            var p = saveOrder();

            console.log("asae order pay next");
            console.log(p);
            if(p) {
                $('#payment-next').html('<?= $text_next?>');
            }
            
        } else {

            $('#payment-next').attr('disabled','disabled');

            $('#payment-next').html('<?= $text_next?>');
        }
        
    });

    $(document).delegate('.question-inputs', 'click', function() {
        console.log("validate question payment-next click");

        $error = false;

        <?php foreach ($questions as $question):  ?>
            var questionRes = $('input[name=\'question-'+<?php echo $question['checkout_question_id'] ?>+'\']').is(':checked');
            if (!questionRes) {
                console.log("questionRes not selected");
                $error = true;
            }
            console.log("shipping-method-wrapper"+questionRes);

        <?php endforeach; ?>

        if (!$error) {
            $('#question-payment-next').removeAttr('disabled');
            $('#question-payment-next').removeClass('btn-grey');
            $('#question-payment-next').addClass('btn-default');

            
        } else {

            $('#question-payment-next').attr('disabled','disabled');
            $('#question-payment-next').addClass('btn-grey');

            $('#question-payment-next').html('<?= $text_next?>');
        }
        
    });

    $(document).delegate('#question-payment-next', 'click', function() {
        console.log("question payment-next click");

        //$('#question-payment-next').html('<center><div class="login-loader" style=""></div></center>');

        $error = false;
        var sendData = {};

        <?php foreach ($questions as $question):  ?>
            var questionRes = $('input[name=\'question-'+<?php echo $question['checkout_question_id'] ?>+'\']').is(':checked');
            if (!questionRes) {
                console.log("questionRes not selected");
                $error = true;
            } else{
                
                sendData[<?php echo $question['checkout_question_id'] ?>] = $('input[name=\'question-'+<?php echo $question['checkout_question_id'] ?>+'\']:checked').attr('value');
                //sendData.push(obj);
            }
        <?php endforeach; ?>


        if (!$error) {

            console.log(sendData);

            dataSend = {
                data : sendData
            };
        
            $.ajax({
                url: 'index.php?path=checkout/checkout/saveQuestionResponse',
                type: 'post',
                data: dataSend,
                dataType: 'json',
                cache: false,
                async: false,
                beforeSend: function() {

                },
                success: function(json) {
                    console.log(json);
                    if (json['status']) {
                        var p = saveOrder();
                        $('#question-next-button').click();

                        console.log("asae order pay next");
                        console.log(p);
                        if(p) {
                            $('#question-payment-next').html('<?= $text_next?>');
                        }             
                    } else {
                        $('#question-payment-next').html('<?= $text_next?>');
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        }
    });


    $(document).delegate('#headingDeliveryOptions', 'click', function() {
        console.log("headingDeliveryOptions click");
        $('#timeslot-next-hidden').attr("href","#collapseThree");
        $('#delivery_time_panel_link').attr("href","");
    });

    $(document).delegate('#promo-form-button', 'click', function() {
        console.log("promo-form-button click");
        $.ajax({
            url: 'index.php?path=checkout/coupon/coupon',
            type: 'post',
            data: $('.promo-form').serialize(),
            dataType: 'json',
            success: function(json) {
                console.log(json);
                if (json['status']) {
                    $('.promo-code-message').html('');
                    $('.promo-code-success-message').html(json['message']);
                    loadTotals($('input#shipping_city_id').val());
                    //setTimeout(function(){ window.location.reload(false); }, 1000);
                    
                } else {
                    $error = '';
                    if(json['error']){
                        $error += json['error'];
                    }
                    $('.promo-code-message').html($error);
                }
            }
        });
    });

    $(document.body).on('mousedown', '.pac-container .pac-item', function(e) {
        console.log('click fired');
        $('#locateme').removeClass('disabled');
    });

    $(document.body).on('change', '.LocalityId', function(e) {
        console.log('change LolityId checkout page');

        var address= $('#us1').locationpicker('location');
        console.log(address);

        /*if(address.addressComponents.streetName && address.addressComponents.streetNumber) {
            $('#street').val(address.addressComponents.streetNumber+' '+address.addressComponents.streetName);
            $('#edit-street').val(address.addressComponents.streetNumber+' '+address.addressComponents.streetName);
        } else {
            $('#street').val(address.addressComponents.streetNumber+' '+address.addressComponents.streetName);
            $('#edit-street').val(address.addressComponents.streetNumber+' '+address.addressComponents.streetName);
        }*/
        

        if(!$('.LocalityId').val().length) {
            $('#locateme').addClass('disabled');
        }
        
    });

    $('#accordion').on('shown.bs.collapse', function (e) {
        //after menu opens
        console.log("target"+e.target.id);
        if(e.target.id == "collapseTwo") {
            $('.checkoutChangeButton').hide();
            $('.checkoutChangeTimeButton').hide();
            $('.checkoutDeliveryOptionsChangeButton').hide();

            $('#step-3').removeClass('checkout-step-color');
            $('#step-4').removeClass('checkout-step-color');
            $('#step-5').removeClass('checkout-step-color');
        }


        if(e.target.id == "collapseThree") {
            $('.checkoutChangeTimeButton').hide(); 
            $('.checkoutDeliveryOptionsChangeButton').show();
            $('#step-4').removeClass('checkout-step-color');
            $('#step-5').removeClass('checkout-step-color');
        }

        if(e.target.id == "collapseFour") {
            $('.checkoutDeliveryOptionsChangeButton').show();
        }

        
    });

    $('#accordion').on('hidden.bs.collapse', function (e) {
        //after menu closes
        console.log("menu close target"+e.target.id);
        if(e.target.id == "collapseTwo") {
            $('.checkoutChangeButton').show();
        }

        if(e.target.id == "collapseThree") {
            $('.checkoutChangeTimeButton').show();
            $('.checkoutDeliveryOptionsChangeButton').show();
        }

        if(e.target.id == "collapseFour") {
            $('.checkoutChangeTimeButton').hide();
            $('.checkoutDeliveryOptionsChangeButton').hide();
        }
    });

$(document).delegate('#savebasket', 'click', function(){
        var choice = confirm($(this).attr('data-confirm'));

        if(choice) {

            $.ajax({
                url: 'index.php?path=checkout/cart/save_basket',
                type: 'post',
                data:'',
                dataType: 'json',
                success: function(json) {
                console.log(json); 
                window.location.href = json;
                if (json['location']) {
                    location = json.redirect;
                    location = location;
                }}
            });
        }
    });
</script>
<script src="https://api-test.equitybankgroup.com/js/eazzycheckout.js"></script>
</body>

</html>


