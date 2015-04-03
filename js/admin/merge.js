$(function() {

	//image thumb previews
	$('.mergee').keyup(function(){
		$(this).val($.trim($(this).val()));
		if ($(this).val().length === 6) {
			var thumb_holder = $(this).parent().find('.preview').find('img');
			JVO.call('image/get',function(result){
				thumb_holder.attr('src',result.media.thumb.url);
			},{image: $(this).val()});
		}
	});

	//handle actual merging
	$('#merge').click(function() {
		JVO.call('image/merge', function(){
			$('.mergee').val('');
			$('.image_container img').attr('src','');
		}, {image1: $('#image1').val(),image2: $('#image2').val()});
	});

});
