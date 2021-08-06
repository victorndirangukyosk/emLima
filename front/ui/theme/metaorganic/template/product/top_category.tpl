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
    <!--<div class="header-lower-deck__wrapper">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="col-md-3 col-sm-3 imgset">
                <img src="<?= $thumb?>" />
            </div>
            <div class="col-md-9 col-sm-9 col-xs-12">
                <div class="col-md-12 col-sm-12 mainheadingname color-white margin25">
                <?= isset($store_info['name']) ? $store_info['name'] : '' ?>
                </div>
				<?php 
				$catArray = array();
				foreach($categories as $cat){
				     array_push($catArray,$cat['name']);
				} 
				$categoyText = implode(',',$catArray);
				?>
                <div class="col-md-12 col-sm-12 detailsheader"><i><?= $categoyText?></i></div>
                <div class="col-md-12 col-sm-12 addressdetail"><?= isset($store_info['address']) ? $store_info['address'] : '' ?>
                </div>
                 <div class="col-md-12 col-sm-12 mt20">
                    <div class="col-md-2 col-sm-2 col-xs-5 border-right pl0">
                    <div class="fontset"><i class="fa fa-star" aria-hidden="true"></i> <?= (rand(3,5));?></div>
                    <div class="smalltext"><?= (rand(100,500));?>+ Ratings</div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-7 pl30">
                    <div class="fontset">Same Day Time</div>
					<?php foreach($store_info['store_open_hours'] as $storeValue){?>
                    <div class="smalltext">Open Hours : <?= $storeValue['timeslot'];?></div>
					<?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>-->
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
                                <!--<div class="BNYKr">
                                    <div class="_2YVIw">
                                        <button class="ZLSF6 _3riWF" data-test-selector="toggle-filters-panel">
                                            <svg fill="currentColor" preserveAspectRatio="xMidYMid meet" height="18" width="18" viewBox="0 0 18 18" class="_2CX5O" style="vertical-align:middle">
                                                <g class="QDdL6" fill="#333333">
                                                    <rect x="0" y="3" width="18" height="2"></rect>
                                                </g>
                                                <g class="_3Os_n" fill="#333333">
                                                    <rect x="0" y="8" width="18" height="2"></rect>
                                                </g>
                                                <g class="_1xGcT" fill="#333333">
                                                    <rect x="0" y="13" width="18" height="2"></rect>
                                                </g>
                                                <g class="_36iK0">
                                                    <g>
                                                        <circle fill="#333333" cx="4.5" cy="4" r="2.2"></circle>
                                                        <circle fill="#FFFFFF" cx="4.5" cy="4" r="0.8"></circle>
                                                    </g>
                                                </g>
                                                <g class="_1A3u9">
                                                    <g>
                                                        <circle fill="#333333" cx="13.5" cy="9" r="2.2"></circle>
                                                        <circle fill="#FFFFFF" cx="13.5" cy="9" r="0.8"></circle>
                                                    </g>
                                                </g>
                                                <g class="_3Fxu-">
                                                    <g>
                                                        <circle fill="#333333" cx="9" cy="14" r="2.2"></circle>
                                                        <circle fill="#FFFFFF" cx="9" cy="14" r="0.8"></circle>
                                                    </g>
                                                </g>
                                            </svg><span class="_3wJqg">Filter</span><span class="_3wJqg _3VkCv">(2)</span><span class="_3wJqg _2pUcL">&amp; Refine</span></button>
                                    </div>
                                    <div class="_32VAW">
                                        <div class="_20J4V">
                                            <div class="cUuZI">
                                                <div class="_1UmTF _2d9NF">
                                                    <div class="search-facet-layout-switcher">
                                                        <div class="btn-group">
                                                            <a class="btn-search-switch is-active" rel="nofollow" aria-label="list view" href="#">
                                                                <svg fill="currentColor" preserveAspectRatio="xMidYMid meet" height="1em" width="1em" viewBox="0 0 16 16" title="List" style="vertical-align:middle">
                                                                    <title>List</title>
                                                                    <g>
                                                                        <path d="M0,3 L0,1 L2,1 L2,3 L0,3 Z M0,7 L0,5 L2,5 L2,7 L0,7 Z M0,11 L0,9 L2,9 L2,11 L0,11 Z M0,15 L0,13 L2,13 L2,15 L0,15 Z M4,3 L4,1 L16,1 L16,3 L4,3 Z M4,7 L4,5 L16,5 L16,7 L4,7 Z M4,11 L4,9 L16,9 L16,11 L4,11 Z M4,15 L4,13 L16,13 L16,15 L4,15 Z"></path>
                                                                    </g>
                                                                </svg>
                                                            </a>
                                                            <a class="btn-search-switch" rel="nofollow" aria-label="grid view" href="#">
                                                                <svg fill="currentColor" preserveAspectRatio="xMidYMid meet" height="1em" width="1em" viewBox="0 0 16 16" title="Grid" style="vertical-align:middle">
                                                                    <title>Grid</title>
                                                                    <g>
                                                                        <path d="M1,3.80447821 L1,1 L3.80447821,1 L3.80447821,3.80447821 L1,3.80447821 Z M6.5977609,3.80447821 L6.5977609,1 L9.4022391,1 L9.4022391,3.80447821 L6.5977609,3.80447821 Z M12.1955218,3.80447821 L12.1955218,1 L15,1 L15,3.80447821 L12.1955218,3.80447821 Z M1,9.4022391 L1,6.59706118 L3.80447821,6.59706118 L3.80447821,9.4022391 L1,9.4022391 Z M6.5977609,9.4022391 L6.5977609,6.5977609 L9.4022391,6.5977609 L9.4022391,9.4022391 L6.5977609,9.4022391 Z M12.1955218,9.4022391 L12.1955218,6.59706118 L15,6.59706118 L15,9.4022391 L12.1955218,9.4022391 Z M1,14.9993003 L1,12.1948221 L3.80447821,12.1948221 L3.80447821,14.9993003 L1,14.9993003 Z M6.5977609,14.9993003 L6.5977609,12.1948221 L9.4022391,12.1948221 L9.4022391,14.9993003 L6.5977609,14.9993003 Z M12.1955218,14.9993003 L12.1955218,12.1948221 L15,12.1948221 L15,14.9993003 L12.1955218,14.9993003 Z"></path>
                                                                    </g>
                                                                </svg>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="_1mVg7">
                                                    <div class="_3zS3G">
                                                        <label for="sortby" class="_35LYV">Sort by:
                                                            <!-- -->
                                                        <!--</label>
                                                        <select id="sortby" name="sortby" class="_2s17p">
                                                            <option selected="" value="relevance">Best match</option>
                                                            <option value="sales">Best sellers</option>
                                                            <option value="date">Newest</option>
                                                            <option value="rating">Best rated</option>
                                                            <option value="trending">Trending</option>
                                                            <option value="price-asc">Price: low to high</option>
                                                            <option value="price-desc">Price: high to low</option>
                                                        </select>
                                                        <svg fill="currentColor" preserveAspectRatio="xMidYMid meet" height="14" width="22" viewBox="-49 141 512 512" class="_2D6hF" style="vertical-align:middle">
                                                            <title>Chevron-Down</title>
                                                            <g>
                                                                <path d="M442.9 233.7l-189.5 189.5c-3.2 3.2-8.7 3.2-11.9 0l-189.5-189.5c-3.2-3.2-3.2-8.7 0-11.9l57.8-57.8c3.2-3.2 8.7-3.2 11.9 0l125.8 125.8 125.8-125.8c3.2-3.2 8.7-3.2 11.9 0l57.8 57.8c1.2 2 2.4 3.6 2.4 5.9-.1 2.5-.9 4-2.5 6z"></path>
                                                            </g>
                                                        </svg>
                                                    </div>
                                                    <div class="_2VZec">
                                                        <button class="_1WPp9 _3RfX3">Best match</button>
                                                        <button class="_1WPp9">Best sellers</button>
                                                        <button class="_1WPp9">Newest</button>
                                                        <button class="_1WPp9">Best rated</button>
                                                        <button class="_1WPp9">Trending</button>
                                                        <button class="_1WPp9">Price
                                                            <svg fill="currentColor" preserveAspectRatio="xMidYMid meet" height="1em" width="1em" viewBox="0 0 11 17" class="ogwO6 KJMn5" style="vertical-align:middle">
                                                                <title>Sort order</title>
                                                                <g>
                                                                    <polygon fill="#FFFFFF" points="4.1,17.3 -0.7,12.5 0.7,11.1 2.1,12.5 2.1,0 4.1,0"></polygon>
                                                                    <polygon fill="#B3B3B3" points="8.3,16.8 6.3,16.8 6.3,-0.6 11.2,4.3 9.7,5.7 8.3,4.3"></polygon>
                                                                </g>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div><span class="_2LyRT">All prices are in KSh</span></div>-->
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
                                                                <!--<li class="_1SXRP"><a class="_1OYwR _1HFue" href="#">Site Templates</a><span class="_1kd89">232</span></li>
                                                                <li class="_1SXRP"><a class="_1OYwR _1Y9yS" href="#">Retail</a><span class="_1kd89">108</span></li>-->
                                                                
                                                            </ul>
                                                        </nav>
                                                    </div>
                                                </div>
												<!--<div class="_1loc3" data-test-selector="filter-price">
                                                  Price: KSh <span id="show-min">0</span>-<span id="show-max">4000</span> <input id="ex2" type="text"  class="span2" value="" data-slider-min="0" data-slider-max="4000" data-slider-step="10" data-slider-value="[0,4000]"/>
                                                </div>-->	
												<!--<div class="_1loc3">
												   <h4>Filter By Price</h4>
													<div class="checkbox">
													  <label><input name="price_slabs" data-start="1" data-end="500" type="checkbox" class="price_filter" value="1-500">1 - 500 KSh</label>
													</div>
													<div class="checkbox price_filter">
													  <label><input type="checkbox" name="price_slabs" data-start="500" data-end="1000" class="price_filter" value="500-1000">500 - 1000 KSh</label>
													</div>
													<div class="checkbox price_filter">
													  <label><input type="checkbox" name="price_slabs" data-start="1000" data-end="2000" class="price_filter" value="1000-2000">1000 - 2000 KSh</label>
													</div>
													<div class="checkbox price_filter">
													  <label><input type="checkbox" name="price_slabs" class="price_filter" data-start="2000" data-end="5000" value="2000-5000">2000 - 5000 KSh</label>
													</div>
                                                </div>-->

                                                <!--<div class="_1loc3" data-test-selector="filter-tags">
                                                    
                                                 </div>
                                                <div class="_1loc3" data-test-selector="filter-price">
                                                   
                                                </div>
                                                <div class="_1loc3" data-test-selector="filter-sales">
                                                 
                                                </div>
                                                <div class="_1loc3" data-test-selector="filter-rating">
                                                    
                                                </div>
                                                <div class="_1loc3" data-test-selector="filter-date-added">
                                                    
                                                </div>
                                                <div class="_1loc3" data-test-selector="filter-compatible-with">
                                                   
                                                </div>-->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="_1o7LM" style="margin-top: -50px;">
                                        <div class="_1bTly" style="visibility:hidden">
                                            <!--<div id="selected-filters" class="_3rUJ8">
                                                <p class="_1X0WU"><span class="_3HYTF">232 </span>items
                                                    
                                                <ol vocab="http://schema.org/" typeof="BreadcrumbList" class="_1cD1-">
                                                    <li property="itemListElement" typeof="ListItem" class="_2OqIW"><a property="item" typeof="WebPage" class="_3LDhC" href="#"><span property="name">All Categories</span></a>
                                                        <meta property="position" content="1">
                                                    </li><span class="_2OYn5">/</span>
                                                    <li property="itemListElement" typeof="ListItem" class="_2OqIW"><a property="item" typeof="WebPage" class="_2g9Ug" href="#"><span property="name" class="_3o55k">Site Templates</span></a>
                                                        <meta property="position" content="2">
                                                    </li>
                                                </ol>
                                                <div class="_2rHkX" data-test-selector="selectedFiltersWrapper">
                                                    <ul class="_2FpDo">
                                                        <li class="s8ScO">
                                                            <a class="_3g6eY" aria-label="Remove Term 'list view'" href="#">
                                                                <div class="_1x3ky"><span class="wcg2R">Term 'list view'</span></div><span class="deTU3"><svg fill="currentColor" preserveAspectRatio="xMidYMid meet" height="8" width="8" viewBox="0 0 10 10" style="vertical-align: middle;"><title>Close</title><g><path d="M9.888641,1.2053571 C9.962881,1.2797623 10,1.3690471 10,1.4732143 C10,1.5773815 9.962881,1.6666663 9.888641,1.7410714 L6.904232,4.7321429 C6.829992,4.806548 6.792873,4.8958328 6.792873,5 C6.792873,5.1041672 6.829992,5.193452 6.904232,5.2678571 L9.86637,8.2589286 C9.955457,8.3333337 10,8.4226185 10,8.5267857 C10,8.6309529 9.955457,8.7202377 9.86637,8.7946429 L8.797327,9.8883929 C8.723088,9.962798 8.63029,10 8.518931,10 C8.407572,10 8.314774,9.962798 8.240535,9.8883929 L5.278396,6.8973214 C5.204157,6.8229163 5.111359,6.7857143 5,6.7857143 C4.888641,6.7857143 4.795843,6.8229163 4.721604,6.8973214 L1.737194,9.8883929 C1.662954,9.962798 1.573868,10 1.469933,10 C1.365998,10 1.276912,9.962798 1.202673,9.8883929 L0.111359,8.7946429 C0.037119,8.7202377 0,8.6309529 0,8.5267857 C0,8.4226185 0.037119,8.3333337 0.111359,8.2589286 L3.095768,5.2678571 C3.170008,5.193452 3.207127,5.1041672 3.207127,5 C3.207127,4.8958328 3.170008,4.806548 3.095768,4.7321429 L0.111359,1.7410714 C0.037119,1.6666663 0,1.5736613 0,1.4620536 C0,1.3504459 0.037119,1.2574408 0.111359,1.1830357 L1.202673,0.1116071 C1.276912,0.037202 1.36971,0 1.481069,0 C1.592428,0 1.685226,0.037202 1.759465,0.1116071 L4.721604,3.1026786 C4.795843,3.1770837 4.888641,3.2142857 5,3.2142857 C5.111359,3.2142857 5.204157,3.1770837 5.278396,3.1026786 L8.262806,0.1116071 C8.337046,0.037202 8.426132,0 8.530067,0 C8.634002,0 8.723088,0.037202 8.797327,0.1116071 L9.888641,1.2053571 Z"></path></g></svg></span></a>
                                                        </li>
                                                    </ul><a class="GlMar" href="#">Clear all</a></div>
                                            </div>-->
											            <div class="btn-group">
                                                            <!--<a onclick="changelayout('list')" id="switch-list" class="btn-search-switch is-active" rel="nofollow" aria-label="list view" href="#">
                                                                <svg fill="currentColor" preserveAspectRatio="xMidYMid meet" height="1em" width="1em" viewBox="0 0 16 16" title="List" style="vertical-align:middle">
                                                                    <title>List</title>
                                                                    <g>
                                                                        <path d="M0,3 L0,1 L2,1 L2,3 L0,3 Z M0,7 L0,5 L2,5 L2,7 L0,7 Z M0,11 L0,9 L2,9 L2,11 L0,11 Z M0,15 L0,13 L2,13 L2,15 L0,15 Z M4,3 L4,1 L16,1 L16,3 L4,3 Z M4,7 L4,5 L16,5 L16,7 L4,7 Z M4,11 L4,9 L16,9 L16,11 L4,11 Z M4,15 L4,13 L16,13 L16,15 L4,15 Z"></path>
                                                                    </g>
                                                                </svg>
                                                            </a>-->
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

 <a class="product-detail-bnt open-popup" role="button" data-store=<?= $current_store;?> data-id="<?= $product['product_store_id'] ?>" target="_blank"  aria-label="<?=$product['name']?>">
                                                  
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
                                                 
                                               
                                      <!--  <section class="_1SQpT">
                                             <a role="button" data-store="<?= ACTIVE_STORE_ID;?>" data-id="<?= $product['product_store_id'] ?>" target="_blank" rel="noopener noreferrer"
										 class="KFSGT product-detail-bnt product-img product-description open-popup" role="toolbar" title="<?=$product['name']?>"></a>
                                            
                                        </section>-->
                                       
                                       
                                        
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
                                                 <div class=""><!--col-md-12 col-sm-12 pl0 pr0 setproductimg-->
                                                   <!-- <div class="variation-selector-container">
                                                        <p class="variations-title">Variation</p>
                                                        <select class="product-variation">
                                                            <?php foreach($product['variations'] as $variation) { ?>
                                                            <option value="<?php echo $variation[variation_id]; ?>"
                                                                    data-price="<?php echo $variation[price]; ?>"
                                                                    data-special="<?php echo $variation[special]; ?>">
                                                                <?php  echo 'per ' . $variation[weight] . ' ' . $variation['unit']; ?>
                                                            </option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>-->
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
                                                        <!--<a role="button" href="#" class="" rel="nofollow">
                                                            <svg fill="currentColor" preserveAspectRatio="xMidYMid meet" height="1em" width="1em" viewBox="0 0 11 9" class="_3lCzm -Vcje" style="vertical-align:middle">
                                                                <title>Add to Collection</title>
                                                                <g stroke="none" stroke-width="1" fill-rule="evenodd" transform="translate(-3.000000, -4.000000)">
                                                                    <g transform="translate(3.000000, 4.500000)">
                                                                        <polygon points="0 1.18461536 9.05384614 1.18461536 9.05384614 0 0 0"></polygon>
                                                                        <polygon points="0 6.26153844 4.52692307 6.26153844 4.52692307 5.07692308 0 5.07692308"></polygon>
                                                                        <polygon points="5.92307692 6.26153844 11 6.26153844 11 5.07692308 5.92307692 5.07692308"></polygon>
                                                                        <polygon transform="translate(8.461538, 5.669231) rotate(-270.000000) translate(-8.461538, -5.669231) " points="5.92307692 6.26153844 11 6.26153844 11 5.07692308 5.92307692 5.07692308"></polygon>
                                                                        <polygon points="0 3.7230769 6.76923077 3.7230769 6.76923077 2.53846154 0 2.53846154"></polygon>
                                                                    </g>
                                                                </g>
                                                            </svg>
                                                        </a>-->
                                                        <!--<a role="button" href="#" class="" rel="nofollow">
                                                            <svg fill="currentColor" preserveAspectRatio="xMidYMid meet" height="1em" width="1em" viewBox="0 0 512 512" class="_1UJnU _255P_" style="vertical-align:middle">
                                                                <title>Add to Favorites</title>
                                                                <g>
                                                                    <path d="M256 475.8c-4.9 0-9.1-1.7-12.5-5.1l-176.7-170.5c-1.9-1.5-4.5-4-7.8-7.4-3.3-3.4-8.5-9.6-15.7-18.6-7.2-9-13.6-18.2-19.3-27.6-5.7-9.4-10.7-20.9-15.2-34.3-4.3-13.3-6.5-26.3-6.5-38.9 0-41.5 12-74 36-97.4 24-23.4 57.1-35.1 99.4-35.1 11.7 0 23.6 2 35.8 6.1 12.2 4.1 23.5 9.5 34 16.4 10.5 6.9 19.5 13.4 27 19.4 7.5 6 14.7 12.5 21.5 19.3 6.8-6.8 14-13.2 21.5-19.3 7.5-6 16.6-12.5 27-19.4 10.5-6.9 21.8-12.4 34-16.4 12.2-4.1 24.1-6.1 35.8-6.1 42.3 0 75.4 11.7 99.4 35.1 24 23.4 36 55.9 36 97.4 0 41.7-21.6 84.2-64.9 127.4l-176.3 169.9c-3.4 3.4-7.6 5.1-12.5 5.1z"></path>
                                                                </g>
                                                            </svg>
                                                        </a>-->
                                                    </div>
                                                </section>
                                                <section class="_7H2LP">
                                                    <div class="-DeRq">
                                                        <?= $product['variations'][0]['special']; ?></div>
                                                    <div class="_1I1Wt">
                                                        <!--<div class="_3yoIm" aria-label="Rated 3.83 out of 5">
                                                            <svg fill="currentColor" preserveAspectRatio="xMidYMid meet" height="1em" width="1em" viewBox="0 0 16 16" style="vertical-align:middle;color:#fec42d">
                                                                <title>Star-Full</title>
                                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                    <g fill="#F9BF00">
                                                                        <path d="M15,6.42527246 C15,6.55455774 14.9269858,6.6960766 14.7809847,6.84853675 L11.7271493,9.97160064 L12.4504175,14.3824632 C12.4559497,14.4236438 12.459017,14.4828014 12.459017,14.5593617 C12.459017,14.6828748 12.4295758,14.7870899 12.370666,14.8719783 C12.3117835,14.9575271 12.2258973,15 12.1142398,15 C12.0074845,15 11.8952245,14.9646203 11.7774322,14.8944926 L7.99967135,12.8122586 L4.22254044,14.8944926 C4.09861343,14.9646203 3.98695588,15 3.88573284,15 C3.76794056,15 3.67961689,14.9575558 3.62070706,14.8719783 C3.56182461,14.7870612 3.532356,14.6828461 3.532356,14.5593617 C3.532356,14.523982 3.53788821,14.4647957 3.54892525,14.3824632 L4.27282331,9.97160064 L1.21041574,6.84853675 C1.06991948,6.68964393 1,6.54878557 1,6.42527246 C1,6.20785377 1.15703812,6.07210709 1.47114173,6.01938213 L5.69487547,5.37548281 L7.58743945,1.3615221 C7.6941947,1.12029677 7.8315962,1 7.99969874,1 C8.1684038,1 8.30520278,1.12029677 8.41195803,1.3615221 L10.3051245,5.37548281 L14.5288583,6.01938213 C14.8423594,6.07210709 15,6.20785377 15,6.42527246 L15,6.42527246 Z"></path>
                                                                    </g>
                                                                </g>
                                                            </svg>
                                                            <svg fill="currentColor" preserveAspectRatio="xMidYMid meet" height="1em" width="1em" viewBox="0 0 16 16" style="vertical-align:middle;color:#fec42d">
                                                                <title>Star-Full</title>
                                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                    <g fill="#F9BF00">
                                                                        <path d="M15,6.42527246 C15,6.55455774 14.9269858,6.6960766 14.7809847,6.84853675 L11.7271493,9.97160064 L12.4504175,14.3824632 C12.4559497,14.4236438 12.459017,14.4828014 12.459017,14.5593617 C12.459017,14.6828748 12.4295758,14.7870899 12.370666,14.8719783 C12.3117835,14.9575271 12.2258973,15 12.1142398,15 C12.0074845,15 11.8952245,14.9646203 11.7774322,14.8944926 L7.99967135,12.8122586 L4.22254044,14.8944926 C4.09861343,14.9646203 3.98695588,15 3.88573284,15 C3.76794056,15 3.67961689,14.9575558 3.62070706,14.8719783 C3.56182461,14.7870612 3.532356,14.6828461 3.532356,14.5593617 C3.532356,14.523982 3.53788821,14.4647957 3.54892525,14.3824632 L4.27282331,9.97160064 L1.21041574,6.84853675 C1.06991948,6.68964393 1,6.54878557 1,6.42527246 C1,6.20785377 1.15703812,6.07210709 1.47114173,6.01938213 L5.69487547,5.37548281 L7.58743945,1.3615221 C7.6941947,1.12029677 7.8315962,1 7.99969874,1 C8.1684038,1 8.30520278,1.12029677 8.41195803,1.3615221 L10.3051245,5.37548281 L14.5288583,6.01938213 C14.8423594,6.07210709 15,6.20785377 15,6.42527246 L15,6.42527246 Z"></path>
                                                                    </g>
                                                                </g>
                                                            </svg>
                                                            <svg fill="currentColor" preserveAspectRatio="xMidYMid meet" height="1em" width="1em" viewBox="0 0 16 16" style="vertical-align:middle;color:#fec42d">
                                                                <title>Star-Full</title>
                                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                    <g fill="#F9BF00">
                                                                        <path d="M15,6.42527246 C15,6.55455774 14.9269858,6.6960766 14.7809847,6.84853675 L11.7271493,9.97160064 L12.4504175,14.3824632 C12.4559497,14.4236438 12.459017,14.4828014 12.459017,14.5593617 C12.459017,14.6828748 12.4295758,14.7870899 12.370666,14.8719783 C12.3117835,14.9575271 12.2258973,15 12.1142398,15 C12.0074845,15 11.8952245,14.9646203 11.7774322,14.8944926 L7.99967135,12.8122586 L4.22254044,14.8944926 C4.09861343,14.9646203 3.98695588,15 3.88573284,15 C3.76794056,15 3.67961689,14.9575558 3.62070706,14.8719783 C3.56182461,14.7870612 3.532356,14.6828461 3.532356,14.5593617 C3.532356,14.523982 3.53788821,14.4647957 3.54892525,14.3824632 L4.27282331,9.97160064 L1.21041574,6.84853675 C1.06991948,6.68964393 1,6.54878557 1,6.42527246 C1,6.20785377 1.15703812,6.07210709 1.47114173,6.01938213 L5.69487547,5.37548281 L7.58743945,1.3615221 C7.6941947,1.12029677 7.8315962,1 7.99969874,1 C8.1684038,1 8.30520278,1.12029677 8.41195803,1.3615221 L10.3051245,5.37548281 L14.5288583,6.01938213 C14.8423594,6.07210709 15,6.20785377 15,6.42527246 L15,6.42527246 Z"></path>
                                                                    </g>
                                                                </g>
                                                            </svg>
                                                            <svg fill="currentColor" preserveAspectRatio="xMidYMid meet" height="1em" width="1em" viewBox="0 0 16 16" style="vertical-align:middle;color:#fec42d">
                                                                <title>Star-Full</title>
                                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                    <g fill="#F9BF00">
                                                                        <path d="M15,6.42527246 C15,6.55455774 14.9269858,6.6960766 14.7809847,6.84853675 L11.7271493,9.97160064 L12.4504175,14.3824632 C12.4559497,14.4236438 12.459017,14.4828014 12.459017,14.5593617 C12.459017,14.6828748 12.4295758,14.7870899 12.370666,14.8719783 C12.3117835,14.9575271 12.2258973,15 12.1142398,15 C12.0074845,15 11.8952245,14.9646203 11.7774322,14.8944926 L7.99967135,12.8122586 L4.22254044,14.8944926 C4.09861343,14.9646203 3.98695588,15 3.88573284,15 C3.76794056,15 3.67961689,14.9575558 3.62070706,14.8719783 C3.56182461,14.7870612 3.532356,14.6828461 3.532356,14.5593617 C3.532356,14.523982 3.53788821,14.4647957 3.54892525,14.3824632 L4.27282331,9.97160064 L1.21041574,6.84853675 C1.06991948,6.68964393 1,6.54878557 1,6.42527246 C1,6.20785377 1.15703812,6.07210709 1.47114173,6.01938213 L5.69487547,5.37548281 L7.58743945,1.3615221 C7.6941947,1.12029677 7.8315962,1 7.99969874,1 C8.1684038,1 8.30520278,1.12029677 8.41195803,1.3615221 L10.3051245,5.37548281 L14.5288583,6.01938213 C14.8423594,6.07210709 15,6.20785377 15,6.42527246 L15,6.42527246 Z"></path>
                                                                    </g>
                                                                </g>
                                                            </svg>
                                                            <svg fill="currentColor" preserveAspectRatio="xMidYMid meet" height="1em" width="1em" viewBox="0 0 16 16" style="vertical-align:middle;color:#fec42d">
                                                                <title>Star-Empty</title>
                                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                    <g stroke="#F9BF00">
                                                                        <path d="M14.4149055,6.50778699 L9.96650226,5.82963728 L9.85289956,5.58877268 L7.99978321,1.65939651 L6.0335367,5.82963134 L5.77022882,5.86977207 L1.5776195,6.50886977 L4.8072668,9.80248587 L4.76622274,10.0525759 L4.04940067,14.4189972 L7.99965123,12.2413268 L8.24102803,12.3743694 L11.949704,14.4188336 L11.1928076,9.80288899 L11.3696559,9.62203171 L14.4149055,6.50778699 Z M4.03255885,14.588468 C4.03252835,14.5884236 4.03249783,14.5883793 4.03246729,14.5883351 Z M3.88714866,14.5000131 C3.88667315,14.5000044 3.88620118,14.5 3.88573284,14.5 C3.88671005,14.5 3.88860849,14.4996521 3.89145519,14.4988551 Z"></path>
                                                                    </g>
                                                                </g>
                                                            </svg>
                                                        </div>--><!--<span class="_2jw0T">(12)</span>--></div>
                                                    <!--<div class="_3QV9M">356
                                                        Sales</div>-->
                                                    <div class="GeySM"><span class="_2g_QW">Unit:</span> <span class="_3TIJT"><?= $product['unit']?></span></div>
                                                </section>
                                                <section data-id="<?= $product['product_store_id'] ?>" class="VRlLl">
                                                    <a class="_3tfm8 _3ePxY  product-detail-bnt product-img product-description open-popup" role="button" data-store=<?= $current_store;?> data-id="<?= $product['product_store_id'] ?>" target="_blank" rel="noopener noreferrer">Preview</a>

                                                    <div class="pro-qty-addbtn" data-store-id="<?= $current_store ?>"
                                                         data-variation-id="<?= $product['product_variation_store_id'] ?>"
                                                         id="action_<?= $product['product_variation_store_id'] ?>">

													  
									
													 </div>
													<!--<a class="_3tfm8 wrc8W lpgPF" role="button" href="#" target="_blank" rel="noopener noreferrer">
                                                        <svg fill="currentColor" preserveAspectRatio="xMidYMid meet" height="1em" width="1em" viewBox="0 0 16 16" style="vertical-align:middle">
                                                            <title>Cart</title>
                                                            <g>
                                                                <path d="M 0.009 1.349 C 0.009 1.753 0.347 2.086 0.765 2.086 C 0.765 2.086 0.766 2.086 0.767 2.086 L 0.767 2.09 L 2.289 2.09 L 5.029 7.698 L 4.001 9.507 C 3.88 9.714 3.812 9.958 3.812 10.217 C 3.812 11.028 4.496 11.694 5.335 11.694 L 14.469 11.694 L 14.469 11.694 C 14.886 11.693 15.227 11.36 15.227 10.957 C 15.227 10.552 14.886 10.221 14.469 10.219 L 14.469 10.217 L 5.653 10.217 C 5.547 10.217 5.463 10.135 5.463 10.031 L 5.487 9.943 L 6.171 8.738 L 11.842 8.738 C 12.415 8.738 12.917 8.436 13.175 7.978 L 15.901 3.183 C 15.96 3.08 15.991 2.954 15.991 2.828 C 15.991 2.422 15.65 2.09 15.23 2.09 L 3.972 2.09 L 3.481 1.077 L 3.466 1.043 C 3.343 0.79 3.084 0.612 2.778 0.612 C 2.777 0.612 0.765 0.612 0.765 0.612 C 0.347 0.612 0.009 0.943 0.009 1.349 Z M 3.819 13.911 C 3.819 14.724 4.496 15.389 5.335 15.389 C 6.171 15.389 6.857 14.724 6.857 13.911 C 6.857 13.097 6.171 12.434 5.335 12.434 C 4.496 12.434 3.819 13.097 3.819 13.911 Z M 11.431 13.911 C 11.431 14.724 12.11 15.389 12.946 15.389 C 13.784 15.389 14.469 14.724 14.469 13.911 C 14.469 13.097 13.784 12.434 12.946 12.434 C 12.11 12.434 11.431 13.097 11.431 13.911 Z"></path>
                                                            </g>
                                                        </svg>
                                                    </a>-->
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
        <!--<nav class="rKk-w" role="navigation">
            <ul class="_360un">
                <li class="pIPk0"><span class="_24O42 _200rh">1</span></li>
                <li class="pIPk0"><a class="_200rh" href="#">2</a></li>
                <li class="pIPk0"><a class="_200rh" href="#">3</a></li>
                <li class="pIPk0"><a class="_200rh" href="#">4</a></li>
                <li class="pIPk0"><a class="_200rh" href="#">5</a></li>
                <li class="pIPk0"><a class="_200rh" href="#">6</a></li>
                <li class="pIPk0"><a class="_200rh" href="#">7</a></li>
                <li class="pIPk0"><a class="_200rh" href="#">8</a></li>
                <li class="pIPk0">
                    <a class="riG7A k89zG" data-test-selector="paginationNext" href="#">
                        <svg fill="currentColor" preserveAspectRatio="xMidYMid meet" height="12" width="8" viewBox="0 0 8 13" style="vertical-align:middle">
                            <title>Chevron Right</title>
                            <g fill="none" stroke="none" stroke-width="1" fill-rule="evenodd">
                                <g transform="translate(-2.000000, 4.000000)" stroke="currentColor" stroke-width="2">
                                    <polyline transform="translate(5.500000, 2.500000) rotate(90.000000) translate(-5.500000, -2.500000)" points="10.5 5 5.5 6.66133815e-16 0.5 5"></polyline>
                                </g>
                            </g>
                        </svg>
                    </a>
                </li>
            </ul>
        </nav>-->
        </div>
        </div>
		       
                                        
                                        
        </div>							
        <!--<div class="_2oKeI">
            <p class="_2uwx2">Searches related to<span class="LSdVQ"> list view</span></p>
            <ul class="_2D2KF">
                <li class="qF50j"><a class="_37J6z _7JhbN" href="#">blog</a></li>
                <li class="qF50j"><a class="_37J6z _7JhbN" href="#">blog clean</a></li>
                <li class="qF50j"><a class="_37J6z _7JhbN" href="#">blog adsense</a></li>
                <li class="qF50j"><a class="_37J6z _7JhbN" href="#">blog minimal</a></li>
                <li class="qF50j"><a class="_37J6z _7JhbN" href="#">blog lifestyle</a></li>
                <li class="qF50j"><a class="_37J6z _7JhbN" href="#">blog news</a></li>
                <li class="qF50j"><a class="_37J6z _7JhbN" href="#">blog seo</a></li>
                <li class="qF50j"><a class="_37J6z _7JhbN" href="#">blog marketing</a></li>
            </ul>
        </div>-->
    
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

<!--<div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">

    <?php if(isset($offer_show) && $offer_show && count($offer_products['products']) > 0 ) { ?>
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="product-category-block">
                    <h2><?= $text_offer ?><a href=""> <a style="color: #d60343;margin-left: 7px;font-size: 14px;letter-spacing: 0;text-decoration: underline;" href="<?= $offer_href ?>" id="view-all"><?= $text_view ?></a></h2>
                </div>
            </div>
        </div>
         <div class="row">
             <div class="store-list-wrapper" style="display:block;">
                <?php $products = $offer_products['products'] ?>
                <?php /* require(DIR_BASE.'../../front/ui/theme/mvgv2/template/product/product_collection.php'); */ ?>
            </div>
        </div>
    <?php } ?>

    <?php foreach($categories as $category){ ?>

            <?php if($category['products']){ ?>
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="product-category-block">
                            <h2><?= $category['name'] ?><a href=""> <a style="color: #d60343;margin-left: 7px;font-size: 14px;letter-spacing: 0;text-decoration: underline;" href="<?= $category['href'] ?>" id="view-all"><?= $text_view ?></a></h2>
                        </div>
                    </div>
                </div>
                 <div class="row">
                     <div class="store-list-wrapper" style="display:block;">
                        <?php $products = $category['products'] ?>
                        <?php // require(DIR_BASE.'../../front/ui/theme/mvgv2/template/product/product_collection.php'); ?>
                    </div>
                </div>
        <?php } ?>
    <?php } ?>
</div>-->

        
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

<!--<div class="bannerModal_popup">
    <div class="modal fade" id="bannermodal"  tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close close-model" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <center><img src="<?php echo $banner_logo; ?>" alt="" class="img-responsive"></center>
                    
                </div>
            </div>
        </div>
    </div>
</div>-->

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
$(document).delegate('#selectedCategory', 'change', function () {
console.log($( this ).val());
console.log($( this ).attr('data-url'));
var url = $( this ).attr('data-url');
var sorting = $("#sorting").val();
window.location.replace(url+"index.php?path=common/home&filter_category="+$( this ).val()+"&filter_sort="+sorting);
});

$(document).delegate('#sorting', 'change', function () {
console.log($( this ).val());
console.log($( this ).attr('data-url'));
var url = $( this ).attr('data-url');
var selectedCategory = $("#selectedCategory").val();
window.location.replace(url+"index.php?path=common/home&filter_sort="+$( this ).val()+"&filter_category="+selectedCategory);
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
