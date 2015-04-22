/* global JVO */

$(function() {

	$('#cat').click(function() {
		JVO.call('tag/add', {image: $('.image').attr('id'), name: 'cat'})
			.done(function(){
				$('.image').click();
			});
	});

	$('#dog').click(function() {
		JVO.call('tag/add', {image: $('.image').attr('id'), name: 'dog'})
			.done(function(){
				$('.image').click();
			});
	});

	$('#skip').click(function() {
		$('.image').click();
	});

});
