jQuery(document).ready(function($) {
	$(document).on('keydown', ".InputNumber", function(e) {
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                return;
        }
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
	$('.search-store').autocomplete({
		minChars:3,
	    serviceUrl: AJAXURL('search/store'),
    	dataType: 'json',
	    onSelect: function (suggestion) {
	    	var pickup_when = $('.pickup_when:checked').val();
	    	window.location.href=AJAXURL('estore/pickup/'+pickup_when+'/'+suggestion.data);
	    }
	});
	$(document).on('click', '.filterItems', function(event) {
		event.preventDefault();
		var target = $(this).attr('data-href');
		$('.catFilter li').removeClass('active');
		$(this).closest('li').addClass('active');
		$('.allItems').css('display', 'none');
		$('.'+target).css('display', 'block');
	});
	$(document).on('change', '.selectAbleCheck', function(event) {
		event.preventDefault();
		var selectedAttr = $('.selectAbleCheck:checked').attr('data-class');
		$('.commonAttrAll').closest('label.container').css('display', 'none');
		$('.commonAttrAll').closest('.radio-danger').css('display', 'none');
		$('.commonAttrAll').closest('.attributeClassCommon').css('display', 'none');
        $('.'+selectedAttr).closest('label.container').css('display', 'block');
        $('.'+selectedAttr).closest('.radio-danger').css('display', 'block');
		$('.'+selectedAttr).closest('.attributeClassCommon').css('display', 'block');
		$('.commonAttrAll').prop('checked', false);
	});
	/*$(document).on('change', '.checkChecked', function(event) {
		event.preventDefault();
		var totalPrice = 0;
		var itemPrice = $('.menu-price-updated').attr('data-price');
		totalPrice = totalPrice + parseInt(itemPrice);
		$.each($('.checkChecked'), function(index, val) {
			if ($(this).is(':checked')) {
				var attrPrice = $(this).attr('data-attr_price');
				if ($(this).attr('data-attr_type') == 'remove') {
					totalPrice = totalPrice - parseInt(attrPrice);
				} else {
					totalPrice = totalPrice + parseInt(attrPrice);
				}				
			}
		});
		$('.menu-price-updated').html('$ '+totalPrice+'.00');
	});*/
	
	$('.addToCartAttr').click(function(event) {
		var $this = $(this);
		var attributes = $this.data();
		$.ajax({
			url: AJAXURL('item/getMenuItemAttributes'),
			type: 'GET',
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			data: attributes,
		})
		.done(function(response) {
			$('#attrModalContent').html(response);
			$('.selectAbleCheck').trigger('change');
		});	
	});
	$('.addToCartItem').click(function(event) {				
		var $this = $(this);
		addUPdateCart($this);
	});

	$(document).on('click', '.quantity-right-plus', function(e) {
		e.preventDefault();
		var $this = $(this);
		var menu_item_id = $(this).data('menu_item_id');
		var quantity = parseInt($this.closest('.dish-btn-qblk').find('.quantity_'+menu_item_id).val());
		$('.quantity_'+menu_item_id).val(quantity + 1);
		addUPdateCart($this);	        
	});
	$(document).on('click', '.quantity-left-minus', function(e) {
		e.preventDefault();
		var $this = $(this);
		var menu_item_id = $(this).data('menu_item_id');
		var quantity = parseInt($this.closest('.dish-btn-qblk').find('.quantity_'+menu_item_id).val());
		if(quantity>0){
			$('.quantity_'+menu_item_id).val(quantity - 1);
		}		
		addUPdateCart($this);
	});
	$(document).on('change', '.checkCheckedCheckbox', function(event) {
		event.preventDefault();
		var allowedCount = $(this).attr('data-maxChoice');
		var foundCheckboxCount = $(this).closest('.attrSelectionCountCheck').find('.checkCheckedCheckbox:checked').length;
		if (foundCheckboxCount > allowedCount) {
			$(this).prop('checked', false);
		}
	});
	function addUPdateCart($this)
	{
		var menu_item_id = $this.data('menu_item_id');
		var quantity = $this.closest('.dish-btn-qblk').find('.quantity_'+menu_item_id).val();
		$this.data('quantity', quantity);
		var attributes = $this.data();
		$.ajax({
			url: AJAXURL('item/addToCart'),
			type: 'POST',
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			data: attributes,
		})
		.done(function(response) {
			var result = $.parseJSON(response);
			windowALert(result.status, result.message);
			if (result.count == 0) {
				window.location.href = AJAXURL('');
				return false;
			}
			if (attributes.item_page == 'checkoutPage') {
				$('#parentContanier').html(result.cartHtml);
				checkoutSlider()
			} else {
				$('a.cartButton span').text(result.count);
				$('.cartGroup').html(result.cartHtml);
			}					
			if (result.status == 'success') {
				//$('.addTOCART_'+menu_item_id).fadeOut();
				//$('.dish_qty_'+menu_item_id).fadeIn();
			}	
			
		});	
	}
	$(document).on('click', '.addToCartAttributeButton', function(event) {
		event.preventDefault();
		/*if ($('.checkChecked:checked').length == 0) {
			window.alert('Please select atleast one item option');
			return false;
		}*/
		var proceedNext = true;
		var $this = $(this);
		var title = '';
		$.each($('.checkbox_1'), function(index, val) {
			var checkedLength = $(this).closest('.attriMandat').find('.checkbox_1:checked').length;
			if (checkedLength == 0) {
				title = $(this).closest('.attributeClassCommon').attr('data-class');
				proceedNext = false;
			}
		});
		$('.showMessage').removeClass('show').addClass('hide').text('');
		if (proceedNext == false) {
			$('.showMessage').removeClass('hide').addClass('show').text('Please select one attribute from '+title);
			return false;
		}
		var dataString = $('.itemAttributeForm').serialize();
		var $this = $(this);
		var attributes = $this.data();
		dataString += '&store_id='+attributes.store_id+'&menu_item_id='+attributes.menu_item_id+'&type=attribute';
		$.ajax({
			url: AJAXURL('item/addToCart'),
			type: 'POST',
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			data: dataString,
		})
		.done(function(response) {
			var result = $.parseJSON(response);
			windowALert(result.status, result.message);
			/*if (result.count == 0) {
				window.location.href = AJAXURL('');
				return false;
			}*/
			if (attributes.item_page == 'checkoutPage') {
				$('#parentContanier').html(result.cartHtml);
				checkoutSlider()
			} else {
				$('a.cartButton span').text(result.count);
				$('.cartGroup').html(result.cartHtml);
				$('#itemAttributeMOdal').modal('hide');
			}
		});	
	});
	$('.deleteItemFromCart').click(function(event) {
		var attributes = $(this).data();
		$.ajax({
			url: AJAXURL('item/deleteFromCart'),
			type: 'POST',
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			data: attributes,
		})
		.done(function(response) {
			var result = $.parseJSON(response);
			$('a.cartButton span').text(result.count);
			windowALert(result.status, result.message);
			$('.cartGroup').html(result.cartHtml);
			if (result.status == 'success') {
				window.location.reload();
			}
		});	
	});
	$(document).on('click', '.removeTip', function(event) {
		event.preventDefault();
		$.ajax({
			url: AJAXURL('cart/remove/tip'),
			type: 'POST',
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		})
		.done(function(response) {
			var result = $.parseJSON(response);
			windowALert(result.status, result.message);
			$('#parentContanier').html(result.cartHtml);
			checkoutSlider();
		});	
	});
	$(document).on('click', '.tipOption', function(event) {
		event.preventDefault();
		var attributes = $(this).data();
		$.ajax({
			url: AJAXURL('cart/add/tip'),
			type: 'POST',
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			data: attributes,
		})
		.done(function(response) {
			var result = $.parseJSON(response);
			windowALert(result.status, result.message);
			$('#parentContanier').html(result.cartHtml);
			checkoutSlider();
		});	
	});
	$(document).on('change', '#checkout-name', function(event) {
		event.preventDefault();
		if ($(this).val() != '') {
			$(this).closest('.inputGroupContainer').find('i').removeClass('glyphicon glyphicon-remove-circle').addClass('glyphicon glyphicon-ok-circle').css('color', 'green');
		} else {
			$(this).closest('.inputGroupContainer').find('i').removeClass('glyphicon glyphicon-ok-circle').addClass('glyphicon glyphicon-remove-circle').css('color', 'red');
		}
	});
	$(document).on('change', '#checkout-email', function(event) {
		event.preventDefault();
		if ($(this).val() != '' && isValidEmailAddress($(this).val())) {
			$(this).closest('.inputGroupContainer').find('i').removeClass('glyphicon glyphicon-remove-circle').addClass('glyphicon glyphicon-ok-circle').css('color', 'green');
		} else {
			$(this).closest('.inputGroupContainer').find('i').removeClass('glyphicon glyphicon-ok-circle').addClass('glyphicon glyphicon-remove-circle').css('color', 'red');
		}
	});
	$(document).on('change', '#otp', function(event) {
		event.preventDefault();
		if ($(this).val() != '') {
			$(this).closest('.inputGroupContainer').find('i').removeClass('glyphicon glyphicon-remove-circle').addClass('glyphicon glyphicon-ok-circle').css('color', 'green');
		} else {
			$(this).closest('.inputGroupContainer').find('i').removeClass('glyphicon glyphicon-ok-circle').addClass('glyphicon glyphicon-remove-circle').css('color', 'red');
		}
	})	
	$(document).on('change', '#checkout-phone', function(event) {
		event.preventDefault();
		var $this = $(this);
		var phone = $this.val();
		$('#otpShow').fadeOut();
		if ($('#checkout-phone').val().length != 10) {
			$('.showWarningMessage').removeClass('hide').addClass('show').text('Phone length equal 10');
			return false;
		}
		$('.showWarningMessage').removeClass('show').addClass('hide').text('');
		$.ajax({
			url: AJAXURL('verifyPhoneNO'),
			type: 'POST',
			data: {phone: phone},
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		})
		.done(function(response) {
			var result = $.parseJSON(response);
			if (result.showOtp == true) {
				$('#otpShow').fadeIn();
				$this.closest('.inputGroupContainer').find('i').removeClass('glyphicon glyphicon-ok-circle').addClass('glyphicon glyphicon-remove-circle').css('color', 'red');
			}
			if (result.status == 'success') {
				$this.closest('.inputGroupContainer').find('i').removeClass('glyphicon glyphicon-remove-circle').addClass('glyphicon glyphicon-ok-circle').css('color', 'green');
			}
			$('.showWarningMessage').removeClass('hide').addClass('show').text(result.message);
		});
	});
	$(document).on('click', '.proceedOrder', function(event) {
		event.preventDefault();
		$('.showWarningMessage').removeClass('show').addClass('hide').text('');
		if ($('#checkout-name').val() == '') {
			$('.showWarningMessage').removeClass('hide').addClass('show').text('Name is required');
			return false;
		} else if ($('#checkout-email').val() == '') {
			$('.showWarningMessage').removeClass('hide').addClass('show').text('Email is required');
			return false;
		} else if (!isValidEmailAddress($('#checkout-email').val())) {
			$('.showWarningMessage').removeClass('hide').addClass('show').text('Email is invalid');
			return false;
		} else if ($('#checkout-phone').val() == '') {
			$('.showWarningMessage').removeClass('hide').addClass('show').text('Phone is required');
			return false;
		} else if (isNaN($('#checkout-phone').val())) {
			$('.showWarningMessage').removeClass('hide').addClass('show').text('Phone is invalid');
			return false;
		} else if ($('#checkout-phone').val().length != 10) {
			$('.showWarningMessage').removeClass('hide').addClass('show').text('Phone length equal 10');
			return false;
		} else if (!$('#accpet_term_condition').is(':checked')) {
			$('.showWarningMessage').removeClass('hide').addClass('show').text('Please accept term & conditions');
			return false;
		}
		var dataString = $('.checkout-form-submit').serialize();
		$.ajax({
			url: AJAXURL('process/order'),
			type: 'POST',
			data: dataString,
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		})
		.done(function(response) {
			var result = $.parseJSON(response);
			if (result.showOtp == true) {
				$('#otpShow').fadeIn();
			}
			if (result.status == 'success') {
				//updateQueryStringParam('page', 'payment');
				//updateQueryStringParam('transaction_id', result.transaction_id);
				window.location.href=AJAXURL('payment/checkout/?page=payment&transaction_id='+result.transaction_id);
				$('#parentContanier').html(result.cartHtml);
			}
			windowALert(result.status, result.message);
		});							
	});
	$(document).on('click', '#verifyPhone', function(event) {
		event.preventDefault();
		var phone = $('#checkout-phone').val();
		var otp = $('#otp').val();
		$.ajax({
			url: AJAXURL('submit/verify/phone'),
			type: 'POST',
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			data: {
				phone: phone,
				otp: otp
			},
		})
		.done(function(response) {
			var result = $.parseJSON(response);
			windowALert(result.status, result.message);
			if (result.status == 'success') {
				$('#checkout-phone').closest('.inputGroupContainer').find('i').removeClass('glyphicon glyphicon-remove-circle').addClass('glyphicon glyphicon-ok-circle').css('color', 'green');
				$('#otp').val('');
				$('#otpShow').fadeOut();
			}
		});	
	});
	var options = {
	  types: ['geocode'],
	  componentRestrictions: {country: "aus"}
	 };

	 var input = document.getElementById('locationPicker');
	 //console.log('another input ',input);
	 var autocomplete = new google.maps.places.Autocomplete(input, options);
	 google.maps.event.addListener(autocomplete, 'place_changed', function() {
	    let places=autocomplete.getPlace();
	    if (places.address_components.length > 0) {
	    	for (var i = 0; i < places.address_components.length; i++) {
	    		var address_components = places.address_components[i];
	    		if (address_components.types[0] == "postal_code") {
					// console.log("postalCode");
	    			$('#pincode').val(address_components.long_name);
	    		}
	    		if (address_components.types[0] == "locality") {
	    			$('#city').val(address_components.long_name);
	    		}
	    		if (address_components.types[0] == "administrative_area_level_1") {
	    			$('#suburb').val(address_components.long_name);
	    		}
				//BY GKK
				
				if(places.geometry.location.lat()){
					$('#lat').val(places.geometry.location.lat());
				}
				if(places.geometry.location.lat()){
					$('#lng').val(places.geometry.location.lng());
				}
	    	}
	    }
	 });

	/*$('.locationPicker').placepicker({
        placeChanged: function(place) {
          console.log("place changed: ", place.formatted_address, this.getLocation());
        }
    }).data('placepicker');*/
	if ($(".regular").length > 0) {
		$(".regular").slick({
			dots: true,
			infinite: true,
			slidesToShow: 2,
			autoplay: false,
			slidesToScroll: 1,
			arrows: false,
			responsive: [{
					breakpoint: 1024,
					settings: {
						slidesToShow: 3,
						slidesToScroll: 3,
						infinite: true,
						dots: true
					}
				},
				{
					breakpoint: 600,
					settings: {
						slidesToShow: 2,
						slidesToScroll: 2
					}
				},
				{
					breakpoint: 480,
					settings: {
						slidesToShow: 1,
						slidesToScroll: 1
					}
				}
			]
		});
	}
});
function checkoutSlider()
{
	if ($(".checkout").length > 0 ) {

		$(".checkout").slick({
			dots: false,
			infinite: true,
			slidesToShow: 5,
			autoplay: false,
			slidesToScroll: 1,
			arrows: true,
			responsive: [{
					breakpoint: 1024,
					settings: {
						slidesToShow: 3,
						slidesToScroll: 3,
						infinite: true,
						dots: true
					}
				},
				{
					breakpoint: 600,
					settings: {
						slidesToShow: 2,
						slidesToScroll: 2
					}
				},
				{
					breakpoint: 480,
					settings: {
						slidesToShow: 1,
						slidesToScroll: 1
					}
				}
			]
		});
	}
	$('.search-block span').click(function(){
		$('.search').animate(
			{'width':'100%', 'opacity':'1'}, 300
		);
		$('.search').focus();
	});
	var opacity_hid = "0", // remember to set in css the same value
	    opacity_show = "1";

	$(function() {
	  $(".menu-mob-inner #mob_menu").click(function() {

	    if($(".mob-cat").css("opacity") == opacity_show) // is it left?
	      $(".mob-cat").animate({ opacity: opacity_hid }, 1000
	      ); // move right
	    else
	      $(".mob-cat").animate({ opacity: opacity_show },500
	      ); // move left

	  });
	});
}
function AJAXURL(url)
{
	var href = $('base').attr('href');
	return href+'/'+url;
}
function windowALert(type = 'success', message = '')
{
	return;
	var alertHtml = '<div class="alert alert-'+type+'" role="alert">'
	  +message
	+'</div>';
	$('#success-alert .alert-body').html(alertHtml);
	$("#success-alert").fadeTo(2000, 1000).fadeOut(1000, function(){
	    $("#success-alert").fadeOut(1000);
	});
}
checkoutSlider();

function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
    return pattern.test(emailAddress);
}
function updateQueryStringParam(key, value) {
	baseUrl = [location.protocol, '//', location.host, location.pathname].join('');
	urlQueryString = document.location.search;
	var newParam = key + '=' + value,
	params = '?' + newParam;
	if (urlQueryString) {
		keyRegex = new RegExp('([\?&])' + key + '[^&]*');
		if (urlQueryString.match(keyRegex) !== null) {
			params = urlQueryString.replace(keyRegex, "$1" + newParam);
		} else {
			params = urlQueryString + '&' + newParam;
		}
	}
	window.history.replaceState({}, "", baseUrl + params);
}
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
