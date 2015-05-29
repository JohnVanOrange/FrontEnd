/*Next image preload*/
var preload = function() {
	JVO.call('image/random')
		.done(function(next_image_data){
			var next_image = new Image();
			next_image.src = next_image_data.media.primary.url;
			$('#main').attr('href',next_image_data.page_url);
		});
};

$(function () {
	preload();

	$('#tag_container').append(_.template($('#tagList').html())( {tags: tags} ));

	$('#save_image').click(function () {
		$('#save_image').toggleClass('highlight');
		if ($('#save_image').hasClass('highlight')) {
			JVO.call('image/save', {image:$('.main').attr('id')});
		} else {
			JVO.call('image/unsave', {image:$('.main').attr('id')});
		}
	});

	$('#like_image').click(function () {
		$('#like_image').addClass('highlight');
		$('#dislike_image').removeClass('highlight');
		JVO.call('image/like', {image:$('.main').attr('id')});
	});

	$('#dislike_image').click(function () {
		$('#dislike_image').addClass('highlight');
		$('#like_image').removeClass('highlight');
		JVO.call('image/dislike', {image:$('.main').attr('id')});
	});

});
