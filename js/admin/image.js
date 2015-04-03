$(function() {

	$('#approve').click(function() {
		JVO.call('image/approve',function(){$('.image').click();},{image: $('.image').attr('id')});
	});

	$('#nsfw').click(function() {
		JVO.call('image/approve',function(){$('.image').click();},{image: $('.image').attr('id'),nsfw: true});
	});

	$('#reject').click(function() {
		JVO.call('image/remove',function(){$('.image').click();},{image: $('.image').attr('id')});
	});

	$('#skip').click(function() {
		$('.image').click();
	});

});
