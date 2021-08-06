<?php echo $header; ?>
<?php $categoryPath = $_SERVER['REQUEST_SCHEME'] .'://'. $_SERVER['HTTP_HOST'] 
     . explode('?', $_SERVER['REQUEST_URI'], 2)[0];?>
<div class="container--full-width featured-categories">
      <div class="container" style="width:100%;">
          <div class="_47ahp" data-test-selector="search-results">
              <div class="row">
                  <div class="col-md-4">
                  </div>
                  <div class="col-md-5">
                  </div>
                  <div class="col-md-3">
                      <select class="form-control" id="sorting" name="sorting" style="height:34px !important;" data-url="<?= $category_url; ?>">
                          <option value="">Sort Products</option>
                          <option value="">Default</option>
                          <option value="nasc" <?php if(isset($this->request->get['filter_sort']) && $this->request->get['filter_sort'] == 'nasc') { echo "selected"; } ?> >Name (A-Z)</option>
                          <option value="ndesc" <?php if(isset($this->request->get['filter_sort']) && $this->request->get['filter_sort'] == 'ndesc') { echo "selected"; } ?> >Name (Z-A)</option>
                          <option value="pasc" <?php if(isset($this->request->get['filter_sort']) && $this->request->get['filter_sort'] == 'pasc') { echo "selected"; } ?> >Price (Low > High)</option>
                          <option value="pdesc" <?php if(isset($this->request->get['filter_sort']) && $this->request->get['filter_sort'] == 'pdesc') { echo "selected"; } ?> >Price (High > Low)</option>
                      </select>
                  </div>
              </div>
          </div>  
      </div>
</div>     
<div class="header-lower-deck">
</div>
<div class="store-cart-panel">
        <div class="modal right fade" id="store-cart-side" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="cart-panel-content">
                    </div>
                    <div class="modal-footer">
                        <!-- <p><?= isset($text_verify_number) ? $text_verify_number : ''  ?></p> -->
                        <a href="<?php echo isset($checkout) ? $checkout : ''; ?>" id="proceed_to_checkout">
                        
                            <button type="button" class="btn btn-primary btn-block btn-lg" id="proceed_to_checkout_button">
                                <span class="checkout-modal-text"><?= isset($text_proceed_to_checkout) ? $text_proceed_to_checkout : '' ?> </span>
                                <div class="checkout-loader" style="display: none;"></div>
                                
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
<?php // echo $current_store;exit;echo '<pre>';print_r($categories);exit;?>
<!-- Organic Theme Code Start --->

 <div class="page" data-reactroot="">
       
            <div class="page__canvas">
                <div class="canvas">
    
                    <div class="_2Jqw2" role="main" data-test-selector="searchPage">
                        <div class="_3aKCG">
    
                            <div class="wZ5nK" id="content">
                                <div class="TTG-m">
                                    <div>
                                        <div data-test-selector="search-filters">
                                            <div class="JMiTG _3Gfh4" aria-label="Collapse filter">
                                                <div class="_1-BRi">
                                                    <div class="_3KLon">
                                                        <h3 class="_3SCbi">Filter &amp; Refine</h3><span class="_1tD-6"></span></div><a class="_3Xng6" href="#">Clear all</a>
                                                    <button class="_2xFAY">Done</button>
                                                </div>
                                                <div class="_1loc3" data-test-selector="filter-category">
                                                    <!--<button class="_61rqe XLw0c" type="button" aria-label="Click to collapse">
                                                        <div class="_1HhXF">
                                                            
                                                        </div>
                                                    </button>-->
                                                    <div class="_1y-cL">
                                                        <nav class="_31Giv">
                                                            <ul data-test-selector="category-filter">
                                                                <li class="_1SXRP">
                                                                    <a class="_1OYwR fXC7e _2tIrr" href="<?=$categoryPath?>">
                                                                        <svg fill="currentColor" preserveAspectRatio="xMidYMid meet" height="1em" width="1em" viewBox="0 0 8 12" class="lb8Bs" style="vertical-align:middle">
                                                                            <title>Chevron Left</title>
                                                                            <g fill="none" stroke="none" stroke-width="1" fill-rule="evenodd">
                                                                                <g transform="translate(1.000000, 1.000000)" stroke="currentColor" stroke-width="2">
                                                                                    <polyline transform="translate(3.500000, 5.507797) rotate(90.000000) translate(-3.500000, -5.507797) " points="-1.5 3 3.5155939 8.0155939 8.5 3.0311879"></polyline>
                                                                                </g>
                                                                            </g>
                                                                        </svg>All categories</a><!--<span class="_1kd89"><?= count($categories)?></span>--></li>
																		<?php
																		$products = array();
																		 foreach($categories as $category){
																		   $link_array = explode('/',$category['href']);
																		   $page_link = end($link_array);
																		   
																		 
																		 ?>
																		 <li class="_1SXRP"><a class="_1OYwR _1Y9yS" href="<?='?cat='.$page_link?>"> <?=$category['name']?></a><!--<span class="_1kd89">232</span>--></li>
																		
																	   <?php } ?>		
                                                                
                                                                
                                                            </ul>
                                                        </nav>
                                                    </div>
                                                </div>
												
                                            </div>
                                        </div>
                                    </div>
                                    <div class="_1o7LM" style="margin-top: -50px;">
                                        <div class="_1bTly" style="visibility:hidden">
                                            
											            <div class="btn-group">
                                                            
                                                            <a  style="visibility:hidden" onclick="changelayout('grid')" id="switch-grid" class="btn-search-switch" rel="nofollow" aria-label="grid view" href="#">
                                                                <svg fill="currentColor" preserveAspectRatio="xMidYMid meet" height="1em" width="1em" viewBox="0 0 16 16" title="Grid" style="vertical-align:middle">
                                                                    <title>Grid</title>
                                                                    <g>
                                                                        <path d="M1,3.80447821 L1,1 L3.80447821,1 L3.80447821,3.80447821 L1,3.80447821 Z M6.5977609,3.80447821 L6.5977609,1 L9.4022391,1 L9.4022391,3.80447821 L6.5977609,3.80447821 Z M12.1955218,3.80447821 L12.1955218,1 L15,1 L15,3.80447821 L12.1955218,3.80447821 Z M1,9.4022391 L1,6.59706118 L3.80447821,6.59706118 L3.80447821,9.4022391 L1,9.4022391 Z M6.5977609,9.4022391 L6.5977609,6.5977609 L9.4022391,6.5977609 L9.4022391,9.4022391 L6.5977609,9.4022391 Z M12.1955218,9.4022391 L12.1955218,6.59706118 L15,6.59706118 L15,9.4022391 L12.1955218,9.4022391 Z M1,14.9993003 L1,12.1948221 L3.80447821,12.1948221 L3.80447821,14.9993003 L1,14.9993003 Z M6.5977609,14.9993003 L6.5977609,12.1948221 L9.4022391,12.1948221 L9.4022391,14.9993003 L6.5977609,14.9993003 Z M12.1955218,14.9993003 L12.1955218,12.1948221 L15,12.1948221 L15,14.9993003 L12.1955218,14.9993003 Z"></path>
                                                                    </g>
                                                                </svg>
                                                            </a>
                                                        </div>
                                        </div>
                                        <div class="m0T0C">
										  <?php
											$products = array();
											 foreach($categories as $category){
											  $link_array = explode('/',$category['href']);
											   $page_link = end($link_array);
											   if(isset($_REQUEST['cat'])){
												 if(isset($category['products']) && ($_REQUEST['cat'] == $page_link)){
													$products  = array_merge( $products,$category['products']);
												 }
											   }else{
												  $products  = array_merge( $products,$category['products']);
											   }
											 
											 ?>
										   <?php } ?>
                                            <div class="_47ahp" data-test-selector="search-results">
											<?php if(count($products)>0){?>
                                                <ul id="items-ul" class="_2tY3C yOn4a" data-test-selector="item-cards-layout-grid">
												
												 <?php 
												   if(isset($_REQUEST['product'])){
													  $products = array_filter($products, function ($var) {
															return ($var['name'] == $_REQUEST['product']);
												      });
												   }
													foreach($products as $product) {
													//echo '<pre>';print_r($product);
												  ?>
                                                   <li class="_1cn3x" data-price="<?=str_replace('KSh ','',$product['variations'][0]['special'])?>">
                                                   
					
                                                    <div class="_2sT86 EurVi">
                                                    <article class="_3Oe1A">

 <a class="product-detail-bnt open-popup" role="button" data-store="<?= $product['store_id'] ?>" data-id="<?= $product['product_store_id'] ?>" target="_blank"  aria-label="<?=$product['name']?>">
                                                  
                                                    <div class="col-md-12 col-sm-12 pl0 pr0">
                                                    <div class="col-md-12 col-sm-12 pl0 pr0 listwidth">
                                                    <span class="view-all-buttons"><?php echo $product['vendor_display_name']; ?></span>
                                                    <section class="_25Upe">
                                                    <section class="inner_sec _2imXI">
                                                   <!-- <span class="discountuihome" style="color:red; font-weight:bold">Get <?= $product['percent_off'];?>% OFF <i class="fas fa-shopping-basket"></i></span>-->
                                                    <div class="_3XNMI">
                                                    <div class="_2_3rp">
                                                    <div style="">
                                                    <img class="_1xvs1" src="<?=$product['thumb']?>" title="<?=$product['name']?>" alt="<?=$product['name']?>" style="left: 0%;">
                                                    
                                                    </div>
                                                    </div>
                                                   
                                                   
                                                    </div>
                                                 
                                               
                                      
                                       
                                       
                                        
                                        </section>
                                        </section>
                                        </div>
                                        <div class="col-md-12 col-sm-12 listset" style="margin-bottom:15px;">
                                        <div class="vfsyA col-md-12 col-sm-12 pl0">
                                                    <div class="_25ygu">
                                                     <div class="JHf2a">
                                                        <!--<a class="R8zaM" href="#"><?= $heading_title;?></a>-->
                                                        
                                                        </div>

                                                          <!-- <a class="_2Pk9X" tabindex="0">-->
                                                    <?=$product['name']?>
                                                    <br/>
                                                    <div style="color:#6dbd46">
                                                     <?= $product['variations'][0]['special'];?>    <?php  echo '/ ' . $product['variations'][0]['weight'] . ' ' . $product['variations'][0]['unit']; ?>
                                                     </div>
  <span id="flag-qty-id-<?= $product['product_store_id'] ?>-<?= $product['store_product_variation_id'] ?>" style="padding:5px;display: <?= $product['qty_in_cart'] ? 'block' : 'none'; ?>"><?php echo $product['qty_in_cart']?> items in cart <i class="fas fa-flag"></i></span>


                                                   <!-- <a href="#" class="_2Pk9X" tabindex="0"><?=$product['name']?></a>
													
                                                    <a class="R8zaM">( per <?=$product['unit']?> )</a>-->
                                                       
                                                    </div>
                                                </div>
                                                 <div class="">
                                                <div class="_2D2lC">
                                                            <div class="-DeRq">
                                                               <!-- <?= $product['variations'][0]['special']; ?></div>-->
                                                        </div>
                                                        <div>
                                                        <div class="_2xqFO">
                                                               <!-- <div class="_3QV9M"><strike><?= $product['variations'][0]['price'];?></strike> </div>-->
                                                                    
                                                            </div>
                                                        </div>
                                                                          <div class="_2bSMY">
                                                        <div class="_31alT">
                                                           <!-- <a class="_3tfm8 _3ePxY  product-detail-bnt product-img product-description open-popup" role="button" data-store=<?= $current_store;?> data-id="<?= $product['product_store_id'] ?>" target="_blank" rel="noopener noreferrer">Preview</a>-->
                                                          <div class="pro-qty-addbtn" data-store-id="<?= $current_store ?>"data-variation-id="<?= $product['product_variation_store_id'] ?>" id="action_<?= $product['product_variation_store_id'] ?>">

													      
									
													      </div>
															<!--<a class="_3tfm8 wrc8W lpgPF" role="button" href="#" target="_blank" rel="noopener noreferrer">
                                                                <svg fill="currentColor" preserveAspectRatio="xMidYMid meet" height="1em" width="1em" viewBox="0 0 16 16" style="vertical-align:middle">
                                                                    <title>Cart</title>
                                                                    <g>
                                                                        <path d="M 0.009 1.349 C 0.009 1.753 0.347 2.086 0.765 2.086 C 0.765 2.086 0.766 2.086 0.767 2.086 L 0.767 2.09 L 2.289 2.09 L 5.029 7.698 L 4.001 9.507 C 3.88 9.714 3.812 9.958 3.812 10.217 C 3.812 11.028 4.496 11.694 5.335 11.694 L 14.469 11.694 L 14.469 11.694 C 14.886 11.693 15.227 11.36 15.227 10.957 C 15.227 10.552 14.886 10.221 14.469 10.219 L 14.469 10.217 L 5.653 10.217 C 5.547 10.217 5.463 10.135 5.463 10.031 L 5.487 9.943 L 6.171 8.738 L 11.842 8.738 C 12.415 8.738 12.917 8.436 13.175 7.978 L 15.901 3.183 C 15.96 3.08 15.991 2.954 15.991 2.828 C 15.991 2.422 15.65 2.09 15.23 2.09 L 3.972 2.09 L 3.481 1.077 L 3.466 1.043 C 3.343 0.79 3.084 0.612 2.778 0.612 C 2.777 0.612 0.765 0.612 0.765 0.612 C 0.347 0.612 0.009 0.943 0.009 1.349 Z M 3.819 13.911 C 3.819 14.724 4.496 15.389 5.335 15.389 C 6.171 15.389 6.857 14.724 6.857 13.911 C 6.857 13.097 6.171 12.434 5.335 12.434 C 4.496 12.434 3.819 13.097 3.819 13.911 Z M 11.431 13.911 C 11.431 14.724 12.11 15.389 12.946 15.389 C 13.784 15.389 14.469 14.724 14.469 13.911 C 14.469 13.097 13.784 12.434 12.946 12.434 C 12.11 12.434 11.431 13.097 11.431 13.911 Z"></path>
                                                                    </g>
                                                                </svg>
                                                            </a>-->
                                                        </div>
                                                    </div>
                                                </div>
                                        </div>
                                        </div>
                                        
                                        <section class="_38ivw">
                                            <section class="_9q1LS">
                                                <section class="_3dJU8">
                                                    <div class="oKU4K">
                                                        
                                                    </div>
                                                </section>
                                                <section class="_7H2LP">
                                                    <div class="-DeRq">
                                                        <?= $product['variations'][0]['special']; ?></div>
                                                    <div class="_1I1Wt">
                                                        
                                                    <div class="GeySM"><span class="_2g_QW">Unit:</span> <span class="_3TIJT"><?= $product['unit']?></span></div>
                                                </section>
                                                <section data-id="<?= $product['product_store_id'] ?>" class="VRlLl">
                                                    <a class="_3tfm8 _3ePxY  product-detail-bnt product-img product-description open-popup" role="button" data-store=<?= $current_store;?> data-id="<?= $product['product_store_id'] ?>" target="_blank" rel="noopener noreferrer">Preview</a>

                                                    <div class="pro-qty-addbtn" data-store-id="<?= $current_store ?>"
                                                         data-variation-id="<?= $product['product_variation_store_id'] ?>"
                                                         id="action_<?= $product['product_variation_store_id'] ?>">

													  
									
													 </div>
													
                                                </section>
                                            </section>
                                        </section>
                                         </a>
                                        </article>
                                    </div>
                                    </span>
                                                    </li>
													<!--- Product Details Modal Start --->
				     <div id="product_<?=$product['product_id']?>" class="modal fade" role="dialog">
						   <div class="modal-dialog">

                      <!-- Modal content-->
							<div class="modal-content">
							<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							</div>
							<div class="modal-body class="col-lg-2 col-md-4 col-sm-6 col-xs-6 nopadding product-details" style="border-right: 1px solid rgb(215, 220, 214);">
							<div>

							<?php /*echo "<pre>";print_r($product);die;*/ if(isset($product['percent_off']) && $product['percent_off'] != '0.00') { ?>

							<span class="spacial-offer"> <?php echo $product['percent_off'].'% OFF';?></span>
							<?php } ?>


							<?php if($this->customer->isLogged()) { ?>


							<a href="#" class="add-to-list list_button<?= $product['product_store_id'] ?>-<?= $product['store_product_variation_id'] ?>" id="list-btn" data-id="<?= $product['product_id'] ?>"
							type="button" data-toggle="modal" data-target="#listModal"  ><img class="add-list-png"   src="<?= $base;?>front/ui/theme/mvgv2/images/list-icon.png">
							</a>

							<?php } else { ?>
							<a href="#"  class="add-to-list" type="button" data-toggle="modal" data-target="#phoneModal"><img class="add-list-png" src="<?= $base;?>front/ui/theme/mvgv2/images/list-icon.png"></a>

							<?php } ?>
							</div>
							<div class="product-block"  data-id="<?= $product['product_store_id'] ?>">

							<div class="product-img product-description open-popup" data-id="<?= $product['product_store_id'] ?>" data-id="<?= $product['product_store_id'] ?>">
							<img class="lazy" data-src="<?= $product['thumb'] ?>" alt="">
							</div>
							<div class="product-description" data-id="<?= $product['product_store_id'] ?>">


							<h3 class="open-popup" data-id="<?= $product['product_store_id'] ?>">

							<a class="product-title"><?= $product['name']?></a>
							</h3>

							<?php if(trim($product['unit'])){ ?>
							<p class="product-info open-popup" data-id="<?= $product['product_store_id'] ?>"><span class="small-info"><?= $product['unit'] ?></span></p>
							<?php } else { ?>
							<p class="product-info open-popup" data-id="<?= $product['product_store_id'] ?>"><span class="small-info">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></p>
							<?php } ?>

							<div class="product-price">
							<?php if ( $product['variations'][0]['special'] == '0.00' || empty(trim($product['variations'][0]['special']))) { ?>
							<span class="price-cancelled open-popup" data-id="<?= $product['product_store_id'] ?>" style="display: none";>
							</span>
							<span class="price open-popup" data-id="<?= $product['product_store_id'] ?>">
							<?php echo $product['variations'][0]['price']; ?>
							</span>
							<?php } else { ?>
							<span class="price-cancelled open-popup" data-id="<?= $product['product_store_id'] ?>">
							<?php echo $product['variations'][0]['price']; ?>
							</span>
							<span class="price open-popup" data-id="<?= $product['product_store_id'] ?>">
							<?php echo $product['variations'][0]['special']; ?>
							</span>
							<?php } ?>
							<div class="pro-qty-addbtn" data-store-id="<?= $current_store ?>" data-variation-id="<?= $product['store_product_variation_id'] ?>" id="action_<?= $product['product_store_id'] ?>">

						 
							</div>
							</div>

							</div>
							</div>
							</div>
							<div class="modal-footer">
							Footer
							</div>
							</div>
							<!----- Product Detail Modal End --->

                  </div>
                </div>
                                                 <?php }?>
       
                                               </ul>
											   <?php }else{ ?>
             <center> <h2> There are no products to list in this category. </h2></center>
            <?php }?>

        </div>
        </div>
		       
                                        
                                        
        </div>							

    
        </div>
        </div>
        </div>
    
        </div>
        </div>
    
        </div>
        </div>
        </div>



<!-- BEGIN Main Container col2-left -->
<section class="main-container col2-left-layout bounceInUp animated"> 
  <!-- For version 1, 2, 3, 8 --> 
  <!-- For version 1, 2, 3 -->
  
   
</section>
<!--main-container col2-left-layout--> 

<!--- Page Heading + breadcrumb End --->





<!-- Organic Theme Code End --->



        
        </div>
    </div>
</div>

<div class="modal-wrapper"></div> 

    
    
    
    <?php echo $footer; ?> 
    <?= $login_modal ?>
    <?= $signup_modal ?>
    <?= $forget_modal ?>
    <?= $contactus_modal ?>

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
                        <b><?= $text_change_location_name ?> : <?= isset($location_name_full) ? $location_name_full : ''  ?></b>
                        
                    <?php } ?>
                    
                </div>
                <a href="<?php echo $toHome ?>" class="btn btn-primary"><?= $button_change_locality ?></a>
                <a href="<?php echo $toStore ?>" class="btn btn-default"><?= 
                $button_change_store ?></a>
                
            </div>
        </div>
    </div>
</div>
</div>

<!-- Start store banner -->

<script type="text/javascript">
    
    $(document).delegate('.close-model', 'click', function(){
        console.log("close product block");
            $('#bannermodal').modal('hide');
            $('.modal-backdrop').remove();
    });
</script>

<!-- End store banner -->


    <script type="text/javascript" src="<?= $base ?>front/ui/theme/organic/js/owl.carousel.min.js"></script>
    <script type="text/javascript" src="<?= $base ?>front/ui/theme/mvgv2/js/slider-carousel.js"></script>
    <script type="text/javascript" src="<?= $base ?>front/ui/theme/mvgv2/js/side-menu-script.js"></script>
    
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.4/jquery.lazy.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.4/jquery.lazy.plugins.min.js"></script>

    <script type="text/javascript" src="<?= $base ?>front/ui/theme/mvgv2/js/html5lightbox.js"></script>
    <script src="<?= $base ?>front/ui/theme/mvgv2/js/jquery.maskedinput.min.js" type="text/javascript"></script>

    <link rel="stylesheet" href="https://formden.com/static/cdn/bootstrap-iso.css" />
    
    
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.6.2/bootstrap-slider.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.6.2/css/bootstrap-slider.min.css"/>
	
	


    <script type="text/javascript">

   

    $('.date-dob').datepicker({
        pickTime: false,
        format: 'dd/mm/yyyy',
        todayHighlight: true,
        autoclose: true,
    });
    // jQuery(function($){
    //     console.log("signup mask");
    //    $("#phone_number").mask("(99) 99999-9999",{autoclear:false,placeholder:"(##) #####-####"});
    // });
    /*jQuery(function($){
        console.log("mask");
       $("#phone_number").mask("<?= $telephone_mask_number ?>",{autoclear:false,placeholder:"<?= $telephone_mask ?>"});
    });*/
    
    jQuery(function($) {
        console.log(" fax-number mask");
       $("#fax-number").mask("<?= $taxnumber_mask_number ?>",{autoclear:false,placeholder:"<?= $taxnumber_mask ?>"});
    });

    $(function() {
        console.log("lazy f");
        $('img.lazy').Lazy({
                beforeLoad: function(element) {
                    // called before an elements gets handled
                    console.log("lazy");
                },
                effect: 'fadeIn',
                effectTime : 500,
                visibleOnly: true,
            }
        );
    });

    $(document).ready(function() {

        
        
        if(<?php  echo isset( $show_banner) ? 'true' : 'false' ?>) {

            $('#bannermodal').modal('show');    
        }

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
    
    $(".ddd").on("click", function() {
        var $button = $(this);
        var $input = $button.closest('.sp-quantity').find("input.quntity-input");

        $input.val(function(i, value) {
            return +value + (1 * +$button.data('multi'));
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

    <?php if ($not_delivery): ?>

    <div id="notallowed-others" class="modal fade " aria-hidden="true">
        <div class="modal-dialog  modal-sm">
            <div class="modal-content" style="height:150px">
                <div class="modal-header">
                    <h4 class="modal-title"><?= $error_no_delivery ?></h4>
                </div>
                <div class="modal-body text-center">
                    <a href="#" id="clearcart" class="btn btn-danger btn-lg"><?= $button_clear_cart ?></a>
                    <a href="index.php?path=checkout/checkout" class="btn btn-success btn-lg"><?= $button_checkout ?></a>
                </div><!-- END .modal-body -->
            </div><!-- END .modal-content -->
        </div><!-- END .modal-dialog -->
    </div>
    <script type="text/javascript">
     $('#notallowed-others').modal({backdrop: 'static', keyboard: false}); 
     
    </script>
    <?php endif ?>


    

    <script type="text/javascript">
	function getUrlParameter(sParam) {
		var sPageURL = window.location.search.substring(1),
			sURLVariables = sPageURL.split('&'),
			sParameterName,
			i;

		for (i = 0; i < sURLVariables.length; i++) {
			sParameterName = sURLVariables[i].split('=');

			if (sParameterName[0] === sParam) {
				return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
			}
		}
   };
    function changelayout(view){
		$("#switch-list").removeClass("is-active");
		$("#switch-grid").removeClass("is-active");
		$("#items-ul").removeClass("yOn4a");
		$("#switch-"+view).addClass("is-active");
		$('.inner_sec').removeClass('_2imXI');
		//$('._2_3rp').css('padding-top','');
		var layout = 'item-cards-layout-'+view;

		$("#items-ul").attr("data-test-selector",layout);
		if(view == 'list'){
			$("#items-ul").addClass("yOn4a");
			$('.inner_sec').addClass('_2imXI');
			$('._2sT86').removeClass('_1fLGj').addClass('EurVi');
			$('.inner_sec_div').removeClass('_2DGi-').addClass('U157g');
		}else{
			//$('._2_3rp').css('padding-top','50.847457627118644%');
			$('._2sT86').removeClass('EurVi').addClass('_1fLGj');
			$('.inner_sec_div').removeClass('U157g').addClass('_2DGi-');
			
		}
		
	}
	
	    var originalVal;

		$('#ex2').slider().on('slideStart', function(ev){
			originalVal = $('#ex2').data('slider').getValue();
		});
		
		$('#ex2').slider().on('slideStop', function(ev){
			var newVal = $('#ex2').data('slider').getValue();
			if(originalVal != newVal) {
				
				var min = newVal[0];
				var max = newVal[1];
				$('#show-min').text(min);
				$('#show-max').text(max);
				$('#items-ul li').each(function(i)
				{
					var price = $(this).attr('data-price');
					var data_price = price.replace(/\,/g,'')
					//console.log('min',min);
					//console.log('max',max);
					//console.log('data-price',parseInt(data_price));
				   if((parseInt(data_price) >= parseInt(min)) && ( parseInt(data_price) <= parseInt(max)))
				   {
					     $(this).show();
				   }else{
					   $(this).hide();
				   }
				});
				
			}
		});

        $(document).ready(function() {
			$("#ex2").slider({tooltip: 'always'});
		    var product = undefined;//getUrlParameter('product');
			if(product == undefined){
			    $("#switch-grid").click();
			}else{
				$("#switch-grid").hide();
			}
            console.log("ready in top_category");
  $(document).ready(function () {
    $(document).delegate('.open-popup', 'click', function () {
       $('.search-dropdown').css('display', 'none');
       $('.search-dropdown').find('li').remove();

      $('.open-popup').prop('disabled', true);
      // console.log("product blocks" + $(this).attr('data-id'));
      $.get('index.php?path=product/product/view&product_store_id=' + $(this).attr('data-id') + '&store_id=' + $(this).attr('data-store'), function (data) {
        $('.open-popup').prop('disabled', false);
        $('.modal-wrapper').html(data);
        $('#popupmodal').modal('show');
      });
      $('#product_name').val('');
    });
  });
        });
		
		$('input[name="price_slabs"]').click(function () {
		     var minMax = getMinMax();
			 console.log('minMax',minMax);
			 var min = minMax[0];
			 var max = minMax[1];
			 
			 $('#items-ul li').each(function(i)
				{
					$(this).show();
					
					if((min > 0) && ( max > 0)){
						var price = $(this).attr('data-price');
						var data_price = price.replace(/\,/g,'')
						console.log('min',parseInt(min));
						console.log('max',parseInt(max));
						console.log('data-price',parseInt(data_price));
					   if((parseInt(data_price) >= parseInt(min)) && ( parseInt(data_price) <= parseInt(max)))
					   {
							$(this).show();
					   }else{
						   $(this).hide();
					   }
					}
				});
			 

        });
		
		function getMinMax(){
			var minArray =[];
			var maxArray =[];
			
			$('input[name="price_slabs"]:checked').each(function() {
				minArray.push($(this).attr("data-start"));
				maxArray.push($(this).attr("data-end"));
				//console.log('this',this);
	        });
			if(maxArray.length > 0 && minArray.length > 0){
				var max = Math.max.apply(Math, maxArray);
				var min = Math.min.apply(Math, minArray);
				return [min,max];
		    }else{
			   return [0,0];
		   }
		}

    </script>
    <script type="text/javascript">
   
    $("#sidebarss").stick_in_parent();


    if(window.screen.availWidth < 450 || window.screen.availHeight < 732) {
        $("#sidebarss").trigger("sticky_kit:detach");
    } else {
        $("#sidebarss").stick_in_parent();
    }

    $('.add-to-list').on('click', function (e) {
        
        console.log("erg");
        data = {
            product_id : $(this).data("id")
        }

        $.ajax({
            url: 'index.php?path=account/wishlist/getProductWislists',
            type: 'post',
            data:data,
            dataType: 'json',
            success: function(json) {
                if (json['status']) {

                    console.log(json);
                    $('#users-list').html(json['html']);
                }
            }
        });
    });
	
	

        
</script>
<script type="text/javascript">
$(document).delegate('#sorting', 'change', function () {
var url = '<?= $category_url; ?>';
window.location.replace(url+"&filter_sort="+sorting);
});
</script>
    <style>
		.slider-handle.min-slider-handle.round {
			margin-left: 1px;
		}
		.slider-handle.max-slider-handle.round {
			margin-left: -20px;
		}
        .cat-list > li:hover  .drop-menu-2  {
          display: block;
        }
        .drop-menu-2 li:hover .drop-menu-3{
            display: block;
        }
		input.price_filter {
         margin-top: 0px;
       }
   .view-all-buttons {
    margin-top: 5px;
    /*margin-bottom: 24px;*/
    display: flex;
    align-items: center;
    justify-content: center;
    }
    </style>
</body>

</html>
