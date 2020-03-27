$(document).ready(function() {
	$('html').addClass('prompt--login--shown');

	$('.prompt--login__close').remove();
	$('.prompt-bg').remove();
	$('.prompt--login').css('z-index', '1');
});

var closePrompt = function(){};