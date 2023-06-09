
 
 $(document).delegate('.product-box .dropdown-menu a', 'click',function(){

 	console.log("drop down");
    $product_id = $(this).attr('data-product-id');    
    $title = $(this).find('.text').html();    
    $variation_id = $(this).attr('data-variation-id');
    
    if(typeof $variation_id === "undefined"){
        $variation_id = 0;
    }

    $.get('index.php?path=product/store/getVariation&product_id='+$product_id+'&variation_id='+$variation_id, function(data){
        var data = JSON.parse(data);

        $('#action_'+$product_id).html(data['action_html']);
   
        $('#action_'+$product_id).attr('data-variation-id',$variation_id);

        $('#product_'+$product_id+' .jvimage').attr('src', data['image']);

        $('#product_'+$product_id+' .homeprice-rate').html(data['price_html']);

        $('#product_'+$product_id+' .filter-option').html($title);        

        //new
        $('#product_'+$product_id+' .product_name').html(data['product_name']);
        $('#product_'+$product_id+' .product_unit').html(data['product_unit']);
    });
});   

//shop info modal 
$(document).ready(function(){

	$(document).ready(function() {
		//'#currency input[name=\'code\']'
       //$('input[type="radio"]').click(function() {
       	$('input[name="customer_group_id"]').click(function() {
            if($(this).attr('id') == 'display-me') {
                $('#show-me').show();           
            } else {
                $('#show-me').hide();   
            }
       });
    });

	console.log("in common.js");
    $('#shop-info-link, .shopinfomodelBtn').on('click',function(){
       $('.shop-info-container').html(''); 
       $.get('index.php?path=common/store_info', function(data){
          $('.shop-info-container').html(data); 
       });
    });
});

$(window).scroll(function () {
    var $this = $(this),
        $head = $('.headtabs');
    if ($this.scrollTop() > 70) {
       $head.addClass('affix');
       $head.removeClass('affix-top');
    } else {
       $head.removeClass('affix');
       $head.addClass('affix-top');
    }
});

function getURLVar(key) {
	var value = [];

	var query = String(document.location).split('?');

	if (query[1]) {
		var part = query[1].split('&');

		for (i = 0; i < part.length; i++) {
			var data = part[i].split('=');

			if (data[0] && data[1]) {
				value[data[0]] = data[1];
			}
		}

		if (value[key]) {
			return value[key];
		} else {
			return '';
		}
	}
}

$(document).ready(function() {

	// Adding the clear Fix

    /*$('.date').datepicker({
        pickTime: false,
        format: 'dd/mm/yyyy',
        todayHighlight: true,
        autoclose: true,
    }); */   
    
	cols1 = $('#column-right, #column-left').length;
	
	if (cols1 == 2) {
		$('#content .product-layout:nth-child(2n+2)').after('<div class="clearfix visible-md visible-sm"></div>');
	} else if (cols1 == 1) {
		$('#content .product-layout:nth-child(3n+3)').after('<div class="clearfix visible-lg"></div>');
	} else {
		$('#content .product-layout:nth-child(4n+4)').after('<div class="clearfix"></div>');
	}
	
	// Highlight any found errors
	$('.text-danger').each(function() {
		var element = $(this).parent().parent();
		
		if (element.hasClass('form-group')) {
			element.addClass('has-error');
		}
	});
		
	// Currency
	$('#currency .currency-select').on('click', function(e) {
		e.preventDefault();

		$('#currency input[name=\'code\']').attr('value', $(this).attr('name'));

		$('#currency').submit();
	});

	// Language
	$('#language a').on('click', function(e) {

		console.log("languae change click");
		e.preventDefault();

		$('#language input[name=\'code\']').attr('value', $(this).attr('href'));

		$('#language').submit();
	});

	$('#search input[name=\'search\']').on('keydown', function(e) {
		if (e.keyCode == 13) {
			$('header input[name=\'search\']').parent().find('button').trigger('click');
		}
	});

	// Menu
	$('#menu .dropdown-menu').each(function() {
		var menu = $('#menu').offset();
		var dropdown = $(this).parent().offset();

		var i = (dropdown.left + $(this).outerWidth()) - (menu.left + $('#menu').outerWidth());

		if (i > 0) {
			$(this).css('margin-left', '-' + (i + 5) + 'px');
		}
	});

	// Product List
	$('#list-view').click(function() {
		$('#content .product-layout > .clearfix').remove();

		//$('#content .product-layout').attr('class', 'product-layout product-list col-xs-12');
		$('#content .row > .product-layout').attr('class', 'product-layout product-list col-xs-12');
		
		localStorage.setItem('display', 'list');
	});

	// Product Grid
	$('#grid-view').click(function() {
		$('#content .product-layout > .clearfix').remove();

		// What a shame bootstrap does not take into account dynamically loaded columns
		cols = $('#column-right, #column-left').length;

		if (cols == 2) {
			$('#content .product-layout').attr('class', 'product-layout product-grid col-lg-6 col-md-6 col-sm-12 col-xs-12');
		} else if (cols == 1) {
			$('#content .product-layout').attr('class', 'product-layout product-grid col-lg-4 col-md-4 col-sm-6 col-xs-12');
		} else {
			$('#content .product-layout').attr('class', 'product-layout product-grid col-lg-3 col-md-3 col-sm-6 col-xs-12');
		}

		 localStorage.setItem('display', 'grid');
	});

	if (localStorage.getItem('display') == 'list') {
		$('#list-view').trigger('click');
	} else {
		$('#grid-view').trigger('click');
	}
});
	
// Live Search 
$.fn.liveSearch = function(option) {
	return this.each(function() {
		this.timer = null;
		this.items = new Array();

		$.extend(this, option);

		$(this).attr('autocomplete', 'off');
		
		// Blur
		$(this).on('blur', function() {
			setTimeout(function(object) {
		        object.hide();
			}, 200, this);
		});
		
		// Keydown
		$(this).on('input', function(event) {
			this.request();
		});
		
		// Show
		this.show = function() {
			var pos = $(this).position();

			$(this).siblings('ul.dropdown-menu').css({
				top: pos.top + $(this).outerHeight(),
				left: pos.left
			});

			$(this).siblings('ul.dropdown-menu').show();
		}
		
		// Hide
		this.hide = function() {
			$(this).siblings('ul.dropdown-menu').hide();
		}       
		
		// Request
		this.request = function() {
			clearTimeout(this.timer);
	
			this.timer = setTimeout(function(object) {
				object.source($(object).val(), $.proxy(object.response, object));
			}, 200, this);
		}
		
		// Response
		this.response = function(json) {
			html = '';

			if (json.length) {
				for (i = 0; i < json.length; i++) {
					this.items[json[i]['value']] = json[i];
				}
				var count = json.length;
				
				if(count >= 5) {
					count = 5;
				} 
				
				
				for (i = 0; i < count; i++) {
					html += '<li data-value="' + json[i]['value'] + '"><a href="' + json[i]['href'] + '">';
					html += '<div class="ajaxadvance">';
					html += '<div class="image">';
					html += '<img title="' + json[i]['value'] + '" src="' + json[i]['image'] + '"/>';
					html += '</div>';
					html += '<div class="content">';
					html += 	'<div class="name">' + json[i]['label'] + '</div>';
					html += 	'<div class="price">' + json[i]['price'] + '</div>';
					html +='</div>';
					html += '</div></a></li>'
				}
				
				if(count == 5) {
					html += '<li data-value="' + json[i]['value'] + '"><a href="' + json[i]['searchall'] + '">';
					html += '<div class="ajaxadvance">';
					html += ' -- View All -- ';
					html += '</div></a></li>'
				}
			
			}

			if (html) {
				this.show();
			} else {
				this.hide();
			}

			$(this).siblings('ul.dropdown-menu').html(html);
		}
		
		$(this).after('<ul class="dropdown-menu" style="padding:2px 2px 2px 2px;"></ul>');
		$(this).siblings('ul.dropdown-menu').delegate('a', 'click', $.proxy(this.click, this)); 
		
	});
};
  
// Cart add remove functions
var cart = {
	'add': function(product_id, quantity, variation_id, store_id=null) {

		
		console.log("add");
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

				$qty_wrapper = $(document).find('#'+$product_store_id+'-'+$variation_id+' .middle-quantity').html($qty);
    			$qty_wrapper = $(document).find('.unique'+$product_store_id+'-'+$variation_id+' .middle-quantity').html($qty);
    			$qty_wrapper = $(document).find('.unique_middle_button'+$product_store_id+'-'+$variation_id).html($qty);
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
			                console.log(text);
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
				//$('.checkout-modal-text').html(text);
			},			
			success: function(json) {	

				console.log(json);
				console.log("remove cart");
                //$('#action_'+json['product_store_id']+' .add-cart-btn').css('display','block');
				$('#action_'+json['product_store_id']+' p.error-msg').html('');
				$('#action_'+json['product_store_id']+' .add-cart-btn').parent().parent().find('.info').css('display','none');

				if (json['location'] == 'cart-checkout') {
				location = 'index.php?path=checkout/cart';
				} else {
				    //update total count for mobile 
				    /*start*/
				    $('.cart-panel-content').load('index.php?path=common/cart/newInfo');

					$('.cart-count').html(json['count_products']);
                    $('.cart-total-amount').html(json['total_amount']);

                    /*end*/

				    $('.shoppingitem-fig').html(json['count_products']);
					$('#cart').load('index.php?path=common/cart/info');

					$('#action_'+json['product_store_id']+' .add-cart-btn').parent().parent().find('.info').css('display','none');
					$('#action_'+json['product_store_id']+' .add-cart-btn').parent().parent().find('.middle-quantity').html('1')	
					
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
			                console.log("yes");
			                $("#proceed_to_checkout").removeAttr("disabled");
			                $("#proceed_to_checkout").attr("href", json['href']);
			                //$("#proceed_to_checkout_button").html(json['text_proceed_to_checkout']);
			                //$('.checkout-modal-text').html(json['text_proceed_to_checkout']);

			                $("#proceed_to_checkout_button").css({ 'background-color' : '', 'border-color' : '' });
                        	$('.checkout-loader').hide();
			                $('.checkout-modal-text').html(json['text_proceed_to_checkout']);
			            } else {    
			                console.log("no frm jsx");
			                $("#proceed_to_checkout").attr("disabled", "disabled");
			                $("#proceed_to_checkout").removeAttr("href");
			                //$("#proceed_to_checkout_button").html(json['amount']);
			                //$('.checkout-modal-text').html(json['amount']);
			                $("#proceed_to_checkout_button").css('background-color', '#ccc');
			                $("#proceed_to_checkout_button").css('border-color', '#ccc');
    
			                

                        	$('.checkout-loader').hide();
                        	$('.checkout-modal-text').html(json['text_proceed_to_checkout']);
			            }

			            
			        }
			    });
			    
			}
		});

		
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

var voucher = {
	'add': function() {

	},
	'remove': function(key) {
		$.ajax({
			url: 'index.php?path=checkout/cart/remove',
			type: 'post',
			data: 'key=' + key,
			dataType: 'json',
			beforeSend: function() {
				$('#cart > button').button('loading');
			},
			complete: function() {
				$('#cart > button').button('reset');
			},
			success: function(json) {
				// Need to set timeout otherwise it wont update the total
				setTimeout(function () {
					$('#cart > button').html('<span id="cart-total"><i class="fa fa-shopping-cart"></i> ' + json['total'] + '</span>');
				}, 100);

				if (json['location'] == 'cart-checkout') {
					location = 'index.php?path=checkout/cart';
				} else {
					$('#cart > ul').load('index.php?path=common/cart/info ul li');
				}
			}
		});
	}
}

var wishlist = {
	'add': function(product_id) {
		$.ajax({
			url: 'index.php?path=account/wishlist/add',
			type: 'post',
			data: 'product_id=' + product_id,
			dataType: 'json',
			success: function(json) {
				$('.alert').remove();

				if (json['success']) {
					$('#content').parent().before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}

				if (json['info']) {
					$('#content').parent().before('<div class="alert alert-info"><i class="fa fa-info-circle"></i> ' + json['info'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}

				$('#wishlist-total').html(json['total']);

				$('html, body').animate({ scrollTop: 0 }, 'slow');
			}
		});
	},
	'remove': function() {

	}
}

var compare = {
	'add': function(product_id) {
		$.ajax({
			url: 'index.php?path=product/compare/add',
			type: 'post',
			data: 'product_id=' + product_id,
			dataType: 'json',
			success: function(json) {
				$('.alert').remove();

				if (json['success']) {
					$('#content').parent().before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

					$('#compare-total').html(json['total']);

					$('html, body').animate({ scrollTop: 0 }, 'slow');
				}
			}
		});
	},
	'remove': function() {

	}
}

/* quantity plus-minus function just send input( quantity input ) id */
var quantity = {
	'plus': function(plus_id) {
		if ($( '#' + plus_id ).val () > 0 ) {
			$( '#' + plus_id ).val( parseInt( $( '#' + plus_id ).val(), 10 ) + 1 );
		 } else {
 			$( '#' + plus_id ).val( 1 );
		 }
	},
	'minus': function(minus_id) { 
		if ($( '#' + minus_id ).val () > 1 ) {
			$( '#' + minus_id ).val( parseInt( $( '#' + minus_id ).val(), 10 ) - 1 );
		 } else {
			$( '#' + minus_id ).val( 1 );
		 }
	}
}

/* Agree to Terms */
$(document).delegate('.mini-cart-button', 'click', function(e) {
	//e.preventDefault();
	console.log("mini click");

	var text = $('.checkout-modal-text').html();
    $('.checkout-modal-text').html('');
    $('.checkout-loader').show();


	$('.cart-panel-content').load('index.php?path=common/cart/newInfo');
	//$('#cart').load('index.php?path=common/cart/info');

	console.log("mini cart click");

    $.ajax({
        url: 'index.php?path=common/home/cartDetails',
        type: 'post',
        dataType: 'json',
        success: function(json) {

        	console.log("cartdet ui js comm");
            console.log(json);

            for (var key in json['store_note']) {
                //alert("User " + data[key] + " is #" + key); // "User john is #234"
                $('.store_note'+key).html(json['store_note'][key]);

                console.log(json['store_note'][key]);
            }

            if (json['status']) {
            	console.log("yesx");
                $("#proceed_to_checkout").removeAttr("disabled");
                $("#proceed_to_checkout").attr("href", json['href']);
                //$("#proceed_to_checkout_button").html(json['text_proceed_to_checkout']);
                //$('.checkout-modal-text').html(json['text_proceed_to_checkout']);
                $("#proceed_to_checkout_button").css({ 'background-color' : '', 'border-color' : '' });
                $('.checkout-modal-text').html(json['text_proceed_to_checkout']);
				$('.checkout-loader').hide();
                
            } else {    
                $("#proceed_to_checkout").attr("disabled", "disabled");
                $("#proceed_to_checkout").removeAttr("href");
                //$("#proceed_to_checkout_button").html(json['amount']);
                //$('.checkout-modal-text').html(json['amount']);

                $("#proceed_to_checkout_button").css('background-color', '#ccc');
			    $("#proceed_to_checkout_button").css('border-color', '#ccc');

                
			    $('.checkout-modal-text').html(json['text_proceed_to_checkout']);
                $('.checkout-loader').hide();
            }
            
        }
    });

    

});

/* Agree to Terms */
$(document).delegate('.agree', 'click', function(e) {
	e.preventDefault();

	$('#modal-agree').remove();

	var element = this;

	$.ajax({
		url: $(element).attr('href'),
		type: 'get',
		dataType: 'html',
		success: function(data) {
			html  = '<div id="modal-agree" class="modal">';
			html += '  <div class="modal-dialog">';
			html += '    <div class="modal-content">';
			html += '      <div class="modal-header">';
			html += '        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
			html += '        <h4 class="modal-title">' + $(element).text() + '</h4>';
			html += '      </div>';
			html += '      <div class="modal-body">' + data + '</div>';
			html += '    </div';
			html += '  </div>';
			html += '</div>';

			$('body').append(html);

			$('#modal-agree').modal('show');
		}
	});
});

// Autocomplete */
(function($) {
	$.fn.autocomplete = function(option) {
		return this.each(function() {
			this.timer = null;
			this.items = new Array();
	
			$.extend(this, option);
	
			$(this).attr('autocomplete', 'off');
			
			// Focus
			$(this).on('focus', function() {
				this.request();
			});
			
			// Blur
			$(this).on('blur', function() {
				setTimeout(function(object) {
					object.hide();
				}, 200, this);				
			});
			
			// Keydown
			$(this).on('keydown', function(event) {
				switch(event.keyCode) {
					case 27: // escape
						this.hide();
						break;
					default:
						this.request();
						break;
				}				
			});
			
			// Click
			this.click = function(event) {
				event.preventDefault();
	
				value = $(event.target).parent().attr('data-value');
	
				if (value && this.items[value]) {
					this.select(this.items[value]);
				}
			}
			
			// Show
			this.show = function() {
				var pos = $(this).position();
	
				$(this).siblings('ul.dropdown-menu').css({
					top: pos.top + $(this).outerHeight(),
					left: pos.left,
					width : "500px",
				});
	
				$(this).siblings('ul.dropdown-menu').show();
			}
			
			// Hide
			this.hide = function() {
				$(this).siblings('ul.dropdown-menu').hide();
			}		
			
			// Request
			this.request = function() {
				clearTimeout(this.timer);
		
				this.timer = setTimeout(function(object) {
					object.source($(object).val(), $.proxy(object.response, object));
				}, 200, this);
			}
			
			// Response
			this.response = function(json) {
				html = '';
	
				if (json.length) {
					for (i = 0; i < json.length; i++) {
						this.items[json[i]['value']] = json[i];
					}
	
					for (i = 0; i < json.length; i++) {
						if (!json[i]['category']) {
							html += '<li data-value="' + json[i]['value'] + '"><a href="#">' + json[i]['label'] + '</a></li>';
						}
					}
	
					// Get all the ones with a categories
					var category = new Array();
	
					for (i = 0; i < json.length; i++) {
						if (json[i]['category']) {
							if (!category[json[i]['category']]) {
								category[json[i]['category']] = new Array();
								category[json[i]['category']]['name'] = json[i]['category'];
								category[json[i]['category']]['item'] = new Array();
							}
	
							category[json[i]['category']]['item'].push(json[i]);
						}
					}
	
					for (i in category) {
						html += '<li class="dropdown-header">' + category[i]['name'] + '</li>';
	
						for (j = 0; j < category[i]['item'].length; j++) {
							html += '<li data-value="' + category[i]['item'][j]['value'] + '"><a href="#">&nbsp;&nbsp;&nbsp;' + category[i]['item'][j]['label'] + '</a></li>';
						}
					}
				}
	
				if (html) {
					this.show();
				} else {
					this.hide();
				}
	
				$(this).siblings('ul.dropdown-menu').html(html);
			}
			
			$(this).after('<ul class="dropdown-menu"></ul>');
			$(this).siblings('ul.dropdown-menu').delegate('a', 'click', $.proxy(this.click, this));	
			
		});
	}

	$(function(){
		console.log("sd");
		$('input[name=\'product_name\']').autocomplete({
			'source': function(request, response) {                
					$.ajax({
							url: 'index.php?path=product/search/product_autocomplete&filter_name=' +  encodeURIComponent(request),
							dataType: 'json',			
							success: function(json) {
									response($.map(json, function(item) {

											if(item['product_id'] == 'getall') {
												return {
														label: item['name'],
														name_label: item['name'],
														value: item['product_id']
												}
											} else {
												return {
														label: item['name']+" - "+item['unit'],
														name_label: item['name'],
														value: item['product_id']
												}
											}
											
									}));
							}
					});

					$('input[name=\'product_name\']').val(request);
			},
			'select': function(item) {
					

					if(item['value'] != 'getall') {
						$('input[name=\'product_name\']').val(item['name_label']).focus();
						$('input[name=\'product_id\']').val(item['value']);
					}


					$('#product-search-form').submit();
					
			}	
		});
	});

})(window.jQuery);

/*  
$('input[name=\'search\']').liveSearch({
	'source': function(request, response) {
		if(request != '' && request.length > 2) {
			$.ajax({
				url: 'index.php?path=common/search/liveSearch&filter_name=' +  encodeURIComponent(request),
				dataType: 'json',
				success: function(json) {
					response($.map(json, function(item) {
						return {
							label: item.name,
							value: item.product_id,
							image: item.image,
							price: item.price,
							href: item.href,
							searchall: item.searchall,
						}
					}));
				}
			});
	   } else {
			$('#search > .dropdown-menu').hide();
	   }	
	}
});	
});
*/

/*** Cookie Functions (Need to paste in Commom js) ***/
function setCookie(cname, cvalue, exdays) {
	var d = new Date();
	d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
	var expires = "expires="+d.toUTCString();
	document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
  }

   function getCookie(cname) {
	  var name = cname + "=";
	  var ca = document.cookie.split(';');
	  for(var i = 0; i < ca.length; i++) {
		var c = ca[i];
		while (c.charAt(0) == ' ') {
		  c = c.substring(1);
		}
		if (c.indexOf(name) == 0) {
		  return c.substring(name.length, c.length);
		}
	  }
	  return "";
   }
  /***  ****/