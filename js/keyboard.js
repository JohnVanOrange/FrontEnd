$(function() {

	$('body').on('keydown', 'input, textarea', function (event) {
		event.stopPropagation();
	});

	var ctrl_down = false;

	$(document).keydown(function(event) {
		if (event.keyCode === 17) { ctrl_down = true; }
	}).keyup(function(event) {
		if (event.keyCode === 17) { ctrl_down = false; }
	});

	$('body').keydown(function (event) {
		if (!ctrl_down) {
			switch (event.keyCode) {
		case 32://Space
			event.preventDefault();
			if ($('.next-image').length) {
				window.location.href = $('.next-image').attr('href');
			}
			break;
		case 37://left arrow
			window.history.back();
			break;
		case 39://right arrow
			window.history.forward();
			break;
		case 82://r
			$('#report_image').click();
			break;
		case 83://s
			$('#save_image').click();
			break;
		case 84://t
			event.preventDefault();
			$('#add_tag').click();
			break;
			case 107://+
			$('#like_image').click();
			break;
		case 109://-
			$('#dislike_image').click();
			break;
		case 124:
			window.location.href = 'http://jvo.io/joJpMJ';
			break;
			}
		}
	});
});
