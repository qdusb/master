function lShow(){
	var tCompany = $('.company'),
		bRelated = $('.related');
	tCompany.addClass('lShow');
	bRelated.addClass('lShow');
	var lShow = $('.lShow');
	lShow.hover(function(){
		$(this).find('.hd').addClass('on');
		$(this).find('.bd').stop().fadeIn();
	},function(){
		lShow.find('.hd').removeClass('on');
		lShow.find('.bd').stop().fadeOut();
	});
};
function rShow(){
	var rShow = $('.case .item');
	rShow.hover(function(){
		$(this).find('.txt').stop().animate({left:0});
	},function(){
		$(this).find('.txt').stop().animate({left:'-150'});
	});
};

$(function(){
	lShow();
	rShow();
});