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
	/*$('.search-store').autocomplete({
	    serviceUrl: AJAXURL('search/store'),
    	dataType: 'json',
	    onSelect: function (suggestion) {
	    	var pickup_when = $('.pickup_when:checked').val();
	    	window.location.href=AJAXURL('estore/pickup/'+pickup_when+'/'+suggestion.data);
	    }
	});*/
	$( ".search-store" ).autocomplete({
      source: function( request, response ) {
        $.ajax( {
          url: AJAXURL('search/store'),
          dataType: "json",
          data: {
            term: request.term
          },
          success: function( data ) {
            response( data );
          }
        } );
      },
      minLength: 2,
      select: function( event, ui ) {
      	var pickup_when = $('.pickup_when:checked').val();
	    window.location.href=AJAXURL('estore/pickup/'+pickup_when+'/'+ui.item.id);
      }
    } );
	$(document).on('click', '.filterItems', function(event) {
		event.preventDefault();
		var target = $(this).attr('data-href');
		$('.catFilter li').removeClass('active');
		$(this).closest('li').addClass('active');
		scrollUpTO(target);
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
	
	$(document).on('click','.addToCartAttr',function(event) {
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
		})
		.fail(function() {
			window.alert('Timeout, Please refresh screen and try again later');
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
		var openToggleDisplay = $('.cart-details').css('display');
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
				$('.addTOCART_'+menu_item_id).fadeOut();
				$('.dish_qty_'+menu_item_id).fadeIn();
			}			
			$('.cart-details').css('display', openToggleDisplay);
			
		})
		.fail(function() {
			window.alert('Timeout, Please refresh screen and try again later');
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
		var minChoice = 0;
		$.each($('.checkbox_1'), function(index, val) {
			if ($(this).closest('.attributeClassCommon').css('display') == 'block') {
				var checkedLength = $(this).closest('.attriMandat').find('.checkbox_1:checked').length;
				minChoice = $(this).closest('.attriMandat').find('.checkbox_1').attr('data-minChoice');
				if (checkedLength == 0 || checkedLength < minChoice) {
					title = $(this).closest('.attributeClassCommon').attr('data-class');
					proceedNext = false;
				}
			}			
		});
		$('.showMessage').removeClass('show').addClass('hide').text('');
		if (proceedNext == false) {
			$('.showMessage').removeClass('hide').addClass('show').text('Please select required options from '+title);
			return false;
		}
		var dataString = $('.itemAttributeForm').serialize();
		var $this = $(this);
		var attributes = $this.data();
		dataString += '&store_id='+attributes.store_id+'&menu_item_id='+attributes.menu_item_id+'&type=attribute&item_page='+attributes.item_page;
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
				$('#itemAttributeMOdal').modal('hide');
				checkoutSlider()
			} else {
				$('a.cartButton span').text(result.count);
				$('.cartGroup').html(result.cartHtml);
				$('#itemAttributeMOdal').modal('hide');
			}
		})
		.fail(function() {
			window.alert('Timeout, Please refresh screen and try again later');
		});	
	});
	$(document).on('click', '.clearCart', function(event) {
		event.preventDefault();
		$.ajax({
			url: AJAXURL('item/clearCart'),
			type: 'POST',
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		})
		.done(function(response) {
			var result = $.parseJSON(response);
			$('a.cartButton span').text(result.count);
			windowALert(result.status, result.message);
			$('.cartGroup').html(result.cartHtml);
			if (result.status == 'success') {
				window.location.reload();
			}
		})
		.fail(function() {
			window.alert('Timeout, Please refresh screen and try again later');
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
		})
		.fail(function() {
			window.alert('Timeout, Please refresh screen and try again later');
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
		})
		.fail(function() {
			window.alert('Timeout, Please refresh screen and try again later');
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
		})
		.fail(function() {
			window.alert('Timeout, Please refresh screen and try again later');
		});	
	});
	$(document).on('change', '#checkout-name', function(event) {
		event.preventDefault();
		if ($(this).val() != '') {
			$.ajax({
				url: AJAXURL('cart/add/fields'),
				type: 'POST',
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				data: {field: 'name', 'value': $(this).val()},
			})
			.done(function(response) {
			})
			.fail(function() {
				window.alert('Timeout, Please refresh screen and try again later');
			});	
			$(this).closest('.inputGroupContainer').find('i').removeClass('glyphicon glyphicon-remove-circle').addClass('glyphicon glyphicon-ok-circle').css('color', 'green');
			$(this).closest('.inputGroupContainer').find('.error-span').css('display', 'none');
		} else {
			$(this).closest('.inputGroupContainer').find('i').removeClass('glyphicon glyphicon-ok-circle').addClass('glyphicon glyphicon-remove-circle').css('color', 'red');
			$(this).closest('.inputGroupContainer').find('.error-span').css('display', 'block');
		}
	});
	$(document).on('change', '#checkout-email', function(event) {
		event.preventDefault();
		if ($(this).val() != '' && isValidEmailAddress($(this).val())) {
			$.ajax({
				url: AJAXURL('cart/add/fields'),
				type: 'POST',
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				data: {field: 'email', 'value': $(this).val()},
			})
			.done(function(response) {
			});	
			$(this).closest('.inputGroupContainer').find('i').removeClass('glyphicon glyphicon-remove-circle').addClass('glyphicon glyphicon-ok-circle').css('color', 'green');
			$(this).closest('.inputGroupContainer').find('.error-span').css('display', 'none');
		} else {
			$(this).closest('.inputGroupContainer').find('i').removeClass('glyphicon glyphicon-ok-circle').addClass('glyphicon glyphicon-remove-circle').css('color', 'red');
			$(this).closest('.inputGroupContainer').find('.error-span').css('display', 'block');
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
	$(document).on('keyup', '#checkout-phone', function(event) {
		event.preventDefault();
		if ($(this).val().length < 10) {
			return false;
		}
		var $this = $(this);
		var phone = $this.val();
		$('#otpShow').fadeOut();
		if ($('#checkout-phone').val().length != 10) {
			$('.showWarningMessage').removeClass('hide').addClass('show').text('Phone length equal 10');
			return false;
		}
		$.ajax({
			url: AJAXURL('cart/add/fields'),
			type: 'POST',
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			data: {field: 'phone', 'value': $this.val()},
		})
		.done(function(response) {
		});	
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
				$this.closest('.inputGroupContainer').find('.error-span').css('display', 'block');
			}
			if (result.status == 'success') {
				$this.closest('.inputGroupContainer').find('i').removeClass('glyphicon glyphicon-remove-circle').addClass('glyphicon glyphicon-ok-circle').css('color', 'green');
				$this.closest('.inputGroupContainer').find('.error-span').css('display', 'none');
			}
			$('.showWarningMessage').removeClass('hide').addClass('show').text(result.message);
		});
	});
	$(document).on('click', '.proceedOrder', function(event) {
		event.preventDefault();
		$('.showWarningMessage').removeClass('show').addClass('hide').text('');
		$('.error-span').css('display', 'none');
		if ($('#checkout-name').val() == '') {
			//$('.showWarningMessage').removeClass('hide').addClass('show').text('Name is required');
			$('#checkout-name').closest('.inputGroupContainer').find('.error-span').css('display', 'block');
			scrollUpTO('showWarningMessageScroll');
			return false;
		} else if ($('#checkout-email').val() == '') {
			//$('.showWarningMessage').removeClass('hide').addClass('show').text('Email is required');
			$('#checkout-email').closest('.inputGroupContainer').find('.error-span').css('display', 'block').text('Email is required');
			scrollUpTO('showWarningMessageScroll');
			return false;
		} else if (!isValidEmailAddress($('#checkout-email').val())) {
			//$('.showWarningMessage').removeClass('hide').addClass('show').text('Email is invalid');
			$('#checkout-email').closest('.inputGroupContainer').find('.error-span').css('display', 'block').text('Phone is required');
			scrollUpTO('showWarningMessageScroll');
			return false;
		} else if ($('#checkout-phone').val() == '') {
			//$('.showWarningMessage').removeClass('hide').addClass('show').text('Phone is required');
			$('#checkout-phone').closest('.inputGroupContainer').find('.error-span').css('display', 'block').text('Phone is required');
			scrollUpTO('showWarningMessageScroll');
			return false;
		} else if (isNaN($('#checkout-phone').val())) {
			//$('.showWarningMessage').removeClass('hide').addClass('show').text('Phone is invalid');
			$('#checkout-phone').closest('.inputGroupContainer').find('.error-span').css('display', 'block').text('Phone is invalid');
			scrollUpTO('showWarningMessageScroll');
			return false;
		} else if ($('#checkout-phone').val().length != 10) {
			//$('.showWarningMessage').removeClass('hide').addClass('show').text('Phone length equal 10');
			$('#checkout-phone').closest('.inputGroupContainer').find('.error-span').css('display', 'block').text('Phone length equal 10');
			scrollUpTO('showWarningMessageScroll');
			return false;
		}
		var name = $('#checkout-name').val();
		var email = $('#checkout-email').val();
		var phone = $('#checkout-phone').val();
		var accpet_term_condition = $('#accpet_term_condition').val();
		var special_instructions = $('#special_instructions').val();
		var dataString = 'name='+name+'&email='+email+'&phone='+phone+'&accpet_term_condition='+accpet_term_condition+'&special_instructions='+special_instructions;
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
				$('.checkoutDetails').fadeOut();
				$('.payment-page').fadeIn();
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
		})
		.fail(function() {
			window.alert('Timeout, Please refresh screen and try again later');
		});	
	});
	$(document).on('click', '#applyPromoCodeAction', function(event) {
		event.preventDefault();
		var couponCode = $('#promo_code').val();
		$('.couponAlert').css('display', 'none');
		$.ajax({
			url: AJAXURL('applyCouponCode'),
			type: 'POST',
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			data: {
				couponCode: couponCode
			}
		})
		.done(function(response) {
			var result = $.parseJSON(response);
			if (result.status == 'false') {
				$('.couponAlert').css('color', 'red');
			}
			if (result.status == 'success') {
				$('#parentContanier').html(result.cartHtml);
				$('.couponAlert').css('color', 'green');
				$('#applyPromoCode').modal('hide');
			}
			$('.couponAlert').css('display', 'block').text(result.message);
			$('.couponAlert').fadeOut(5000);
		})
		.fail(function() {
			window.alert('Timeout, Please refresh screen and try again later');
		});		
	});
	$(document).on('click', '.applyDealBtn', function(event) {
		event.preventDefault();
		var dealID = $(this).attr('data-dealID');
		$('.couponAlert').css('display', 'none');
		$.ajax({
			url: AJAXURL('applyDeal'),
			type: 'POST',
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			data: {dealID: dealID}
		})
		.done(function(response) {
			var result = $.parseJSON(response);
			if (result.status == 'false') {
				$('.couponAlert').css('color', 'red');
			}
			if (result.status == 'success') {
				$('#parentContanier').html(result.cartHtml);
				$('.couponAlert').css('color', 'green');
				$('#applyPromoCode').modal('hide');
				checkoutSlider();
			}
			$('.couponAlert').css('display', 'block').text(result.message);
			$('.couponAlert').fadeOut(5000);
		})
		.fail(function() {
			window.alert('Timeout, Please refresh screen and try again later');
		});		
	});
	$(document).on('click', '#removePromoCodeAction', function(event) {
		event.preventDefault();
		$('.couponAlert').css('display', 'none');
		$.ajax({
			url: AJAXURL('removeCouponCode'),
			type: 'POST',
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		})
		.done(function(response) {
			var result = $.parseJSON(response);
			if (result.status == 'false') {
				$('.couponAlert').css('color', 'red');
			}
			if (result.status == 'success') {
				$('#parentContanier').html(result.cartHtml);
				$('.couponAlert').css('color', 'green');
				checkoutSlider()
			}
			$('.couponAlert').css('display', 'block').text(result.message);
			$('.couponAlert').fadeOut(2000);
		})
		.fail(function() {
			window.alert('Timeout, Please refresh screen and try again later');
		});		
	});
	
	var options = {
	  types: ['geocode'],
	  componentRestrictions: {country: "aus"}
	 };

	 var input = document.getElementById('locationPicker');
	//  console.log('test by gaurav',input);
	 var autocomplete = new google.maps.places.Autocomplete(input, options);
	 google.maps.event.addListener(autocomplete, 'place_changed', function() {
	    let places=autocomplete.getPlace();
	    if (places.address_components.length > 0) {
	    	for (var i = 0; i < places.address_components.length; i++) {
	    		var address_components = places.address_components[i];
	    		if (address_components.types[0] == "postal_code") {
					//console.log("yyyyyyh",places.geometry.location.lat());
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
jQuery(window).scroll(function(event) {
	var slectedID = '';
	$.each($(".closestDIV"), function(index, val) {
		var id = $(this).attr('id');
		var firstEle = $('.catFilter').offset().top;  	//first element distance from top
        var secondEle = $(this).offset().top;                 //second element distance from top
        var distance = (secondEle - firstEle);
		if (distance < 65) {
			slectedID = id
		}
	});
	$('.filterItems').closest('li').removeClass('active');
	$('.filterItems[data-href="'+slectedID+'"]').closest('li').addClass('active');
});
function scrollUpTO(divID = null)
{
	$('html, body').animate({
        scrollTop: $("#"+divID).offset().top - 20
    }, 500);
}
function checkoutSlider()
{
	if ($("#checkoutAlsoLikeSlider").length > 0 ) {

		$("#checkoutAlsoLikeSlider").slick({
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
	$.notify(
		message, 
		type
	);
	return;
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
