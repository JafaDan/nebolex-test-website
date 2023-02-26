$(window).scroll(function(){
	$('.test').each(function(){
		var imagePos = $(this).offset().top;

		var topOfWindow = $(window).scrollTop();
		if (imagePos < topOfWindow+600) {
			$(this).addClass("change-color");
		}
	})
})

$(document).ready(function() {
	$('#fullpage').fullpage({
		scrollingSpeed: 1000,
		navigation: true
	});
});



function getFullPhoto(product_id) {
	return "img/arthro" + product_id + ".png";
}

function getProductName(product_id) {
	return "Nebolex Arthro Initial ''30/" + (product_id + 1);
}

function selectProduct(product_id) {
	$('.product__example').each(function(){
		$(this).addClass("product_unselected");
	});
	$('.product__example')[product_id].classList.remove("product_unselected");
	$('.product__img').attr('src', getFullPhoto(product_id));
	$('.product__discription h1')[0].innerHTML = getProductName(product_id);
		if (product_id != 0) {
			$(".buylinks").css('display','none');
			$(".buylinks1").css('display','block');
			$(".button").css({"background":"#777","border-color":"#777"});
		}
		else {
			$(".buylinks").css('display','block');
			$(".buylinks1").css('display','none');
			$(".button").css({"background":"#0091D0","border-color":"#0091D0","color":"#fff"});
		}
}


$(document).ready(function(){

	$('.ios').iosCheckbox();
	$('.qustion__main').slick({
		vertical: true,
		infinite: false,
		adaptiveHeight: false,
		speed: 200,
		centerPadding: '0px',
		draggable: false
	});
	$('.txt__slider').slick({
		draggable: false
	});

});

$(function(){
  $(".order__input_phone").mask("+7(999) 999-99-99",{completed : function(){
       $(this).css({'border' : '2px solid #569b44'});
       $('.icon_answer').addClass("green_answer");
   }
   });
});


$(document).ready(function() {
	$('#buyoneclick_email').blur(function() {
		if($(this).val() != '') {
			var pattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;
			if(pattern.test($(this).val())){
				$(this).css({'border' : '2px solid #569b44'});
				$('#valid').text('Верно');
			} else {
				$(this).css({'border' : '2px solid #ff0000'});
				$('#valid').text('Не верно');
			}
		} else {
			$(this).css({'border' : '1px solid #ff0000'});
			$('#valid').text('Поле email не должно быть пустым');
		}
	});
});

$(document).ready(function() {

	$('.showPassword').click(function(){
		var inputPsw = $('#password');
		if (inputPsw.attr('type') == 'password') {
			document.getElementById('password').setAttribute('type', 'text');
		} else {
			document.getElementById('password').setAttribute('type', 'password');
		}
	});
	$('.showPassword2').click(function(){
	var inputPsw = $('#confirm_password');
	if (inputPsw.attr('type') == 'password') {
		document.getElementById('confirm_password').setAttribute('type', 'text');
	} else {
		document.getElementById('confirm_password').setAttribute('type', 'password');
	}
	});
});


$(document).ready(function(){
	$("#password").pwdMeter();
	$('.order_password').strongPassword();

});

new WOW().init();