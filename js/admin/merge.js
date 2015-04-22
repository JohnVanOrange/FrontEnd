/* global JVO */

$(function() {

	//image thumb previews
	$('.mergee').keyup(function(){
		$(this).val($.trim($(this).val()));
		if ($(this).val().length === 6) {
			var thumb_holder = $(this).parent().find('.preview').find('img');
			JVO.call('image/get', {image: $(this).val()})
				.done(function(result){
					thumb_holder.attr('src',result.media.thumb.url);
				});
		}
	});

	//handle actual merging
	$('#merge').click(function() {
		JVO.call('image/merge', {image1: $('#image1').val(),image2: $('#image2').val()})
			.done(function(){
				$('.mergee').val('');
				$('.image_container img').attr('src','');
			});
	});

});
