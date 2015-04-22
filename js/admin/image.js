/* global JVO */

$(function() {

	$('#approve').click(function() {
		JVO.call('image/approve', {image: $('.image').attr('id')})
			.done(function(){
				$('.image').click();
			});
	});

	$('#nsfw').click(function() {
		JVO.call('image/approve', {image: $('.image').attr('id'),nsfw: true})
			.done(function(){
				$('.image').click();
			});
	});

	$('#reject').click(function() {
		JVO.call('image/remove', {image: $('.image').attr('id')})
			.done(function(){
				$('.image').click();
			});
	});

	$('#skip').click(function() {
		$('.image').click();
	});

});
