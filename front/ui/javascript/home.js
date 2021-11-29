

function detect_location() {
	$('#btn_location i').attr('class', 'fa fa-spinner fa-spin');
	if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(showPosition);
	} else {
		//handle error 
	}
}

function showPosition(position) {
	$.get('http://maps.googleapis.com/maps/api/geocode/json?latlng=' + position.coords.latitude + ',' + position.coords.longitude + '&sensor=true', function(data) {
		$('input[name="address"]').val(data.results[0].formatted_address);
		zip_code = get_zipcode(data.results[0]);
		$('#btn_location i').attr('class', 'fa-crosshairs fa');
		//checkStoreisAvailable(zip_code);
	});
}

function checkStoreisAvailable(zipcode,url,text) {

	console.log('checkStoreisAvailable');
	console.log(zipcode);

	$.get('index.php?path=common/home/find_store&zipcode=' + zipcode, function(data) {
		var categorySelected =  $('#selectedCategory option:selected').attr("value");
		var data = JSON.parse(data);
		$('#combobox-shop .selectize-dropdown').removeClass('hide');
		console.log(data);
		console.log("data");
		if (data.store) {

			if (url.indexOf('?') > -1) {
			  	url += '&zipcode=' + encodeURIComponent(zipcode);
			} else {
				url += '?zipcode=' + encodeURIComponent(zipcode);
			}

			
			location = url;
		} else {

			$('.start-shopping-text').html(text);
			$('.loader').hide();
			swal({
			  title: data.text_not_found,
			  html: data.text_error,
			  type: "error",
			  confirmButtonColor: "#f86e01",
			  closeOnConfirm: false
			});

			console.log('fasle');
			return false;
		}
		return false;
	});
}

function checkCollectionStoreisAvailable(zipcode,url,text,collection_id) {

	console.log('checkStoreisAvailable');
	console.log(zipcode);

	$.get('index.php?path=store/collection/find_store&zipcode=' + zipcode+'&collection_id='+collection_id, function(data) {

			console.log(data);
			console.log("data origi");
			var data = JSON.parse(data);
			$('#combobox-shop .selectize-dropdown').removeClass('hide');
			/*console.log(data);
			console.log(data.redirect_url);
			console.log("data");*/
			if (data.store) {
				location = data.redirect_url;
			} else {

				$('.start-shopping-text').html(text);
				$('.loader').hide();
				swal({
				  title: data.text_not_found,
				  html: data.text_error,
				  type: "error",
				  confirmButtonColor: "#f86e01",
				  closeOnConfirm: false
				});

				console.log('fasle');
				return false;
			}
			return false;
		});
}

$('.start-shopping').click(function() {

	//alert("erg");
	var text = $('.start-shopping-text').html();
	$('.start-shopping-text').html('');
	$('.loader').show();

	var zipcode = $('input[name=\'zipcode\']').val();

	var url = $('input[name=\'store_list_url\']').val();


	if($('input[name="zipcode"]').val().length > 0 ) {
		
		console.log('urlss');
		console.log(url);
		var zipcode = $('input[name=\'zipcode\']').val();
		/*console.log("zipcode"+zipcode);*/	
		
		checkStoreisAvailable(zipcode,url,text);
		
	}else{
		$('.start-shopping-text').html(text);
		$('.loader').hide();
		$('#no-zip').show();
		$('#no-zip').html('No Zipcode Found');
	}
});

$('.collection-start-shopping').click(function() {

	
	var text = $('.start-shopping-text').html();
	$('.start-shopping-text').html('');
	$('.loader').show();

	var zipcode = $('input[name=\'zipcode\']').val();

	var url = $('input[name=\'store_list_url\']').val();

	if($('input[name="zipcode"]').val().length > 0 ) {
		
		console.log('urlss');
		console.log(url);
		var zipcode = $('input[name=\'zipcode\']').val();

		var collection_id = $('input[name=\'collection_id\']').val();

		/*console.log("zipcode"+zipcode);*/	

		
		checkCollectionStoreisAvailable(zipcode,url,text,collection_id);
		
	}else{
		$('.start-shopping-text').html(text);
		$('.loader').hide();
		$('#no-zip').show();
		$('#no-zip').html('No Zipcode Found');
	}
});

$(document).click(function() {
	$('#combobox-shop .selectize-dropdown').addClass('hide');
});



$(document).ready(function() {

	// Language
	$('#language a').on('click', function(e) {

		console.log("languae change click");
		e.preventDefault();

		$('#language input[name=\'code\']').attr('value', $(this).attr('href'));

		$('#language').submit();
	});

	if($('#store_location').val() == 'autosuggestion') {

		var zip_code = '';
		var input = document.getElementById('searchTextField'); 
		var options = {
		    //types: ['(cities)']
		    //types: ['address'],
			componentRestrictions: {country: 'ke'}
		};

	


		var autocomplete = new google.maps.places.Autocomplete(input, options);

		//google.maps.event.addListener(autocomplete, 'place_changed', function() {
		autocomplete.addListener('place_changed', function(event) {

			//console.log(event);

			/*if (event.keyCode === 13) { 
		        event.preventDefault(); 
		    }*/



			console.log("on suggestion selected");

			console.log("val"+$('#searchTextField').val());

			//$('#searchTextField').change();

			

			console.log(autocomplete);

			var data = autocomplete.getPlace();

			if (!data.geometry) {
	            // User entered the name of a Place that was not suggested and
	            // pressed the Enter key, or the Place Details request failed.
	            //window.alert("No details available for input: '" + data.name + "'");
	            return false;
          	}

          	// $('#searchTextField').attr('disabled','disabled');

			var lat = data.geometry.location.lat();
    		var lng = data.geometry.location.lng();
    		var textAddress = $('#searchTextField').val();

    		var textAddress = data.name+', '+data.formatted_address;
    		
    		/*console.log(lat);
    		console.log(lng);*/

			//zip_code = get_zipcode(data);

			zipcode = lat+","+lng;

			//console.log("zip_code from suggestion selected");

			var url = $('input[name=\'store_list_url\']').val();
			sendData = {
				lat:lat,
				lng:lng,
				name:textAddress,
			}
			var categorySelected =  $('#selectedCategory option:selected').attr("value");
			//alert(categorySelectedg);
			$.ajax({
                url: 'index.php?path=common/home/saveLocation',
                type: 'post',
                data : sendData,
                dataType: 'json',
                success: function(json) {
                    console.log(json);
                    if (url.indexOf('?') > -1) {
					  	url += '&location=' + zipcode;
					} else {
						url += '?location=' + zipcode;
					}
                    if(categorySelected != undefined){
						url +='&page=stores&category='+categorySelected
					}else{
						url +='&page=stores'
					}
					//alert(url);
					location = url;
                }
            });
		});
	}	
});

function get_zipcode(place) {
	for (var i = 0; i < place.address_components.length; i++)
	{
		for (var j = 0; j < place.address_components[i].types.length; j++)
		{
			if (place.address_components[i].types[j] == "postal_code")
			{
				return place.address_components[i].long_name;
			}
		}
	}
}
