/*Next image preload*/
var preload = function() {
	JVO.call('image/random')
		.done(function(next_image_data){
			next_image = new Image();
			next_image.src = next_image_data.media.primary.url;
			$('#main').attr('href',next_image_data.page_url);
		});
}

$('document').ready(function(){
	preload();

	/*Add tag autosuggest*/
	$('#addTag').typeahead({
		remote: '/api/tag/suggest?term=%QUERY',
		limit: 16
	});
	$('#addTagDialog .tt-hint').addClass('form-control');

	/*Add Tag dialog*/
	$('#add_tag').click(function (event) {
		event.preventDefault();
		$('#addTag').typeahead('setQuery','');
	});

	$('#addTagDialog').on('shown.bs.modal', function(){
		$('#addTag').focus();
	});

	$('#add_tag').one('click', function(event){
		event.preventDefault();
		var addtag = function () {
			JVO.call('tag/add', {'name': $('#addTag').val(), 'image' : $('.main').attr('id')})
				.done(function( result ){
					$('#tags a, #tags em').remove();
					for (i in result.tags) {
						tag = $('<a>');
						$(tag).attr('href', result.tags[i].url).addClass('tag').html(result.tags[i].name);
						$('#tags').append(tag);
					}
				});
			$('#addTagDialog').modal('hide');
		};
		$('#addTag').bind('keydown', function (event) {
			if (event.keyCode === 13) {
				event.preventDefault();
				addtag();
			}
		});
		$('#addTagSubmit').click(function(){
			addtag();
		});
	});

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

	/*Load report types*/
  $('#report_image').one('click', function() {
    JVO.call('report/all').done(function( data ) {
      for (var i in data) {
        report = $('<div class="form-group"><button class="btn report_button" value="' + data[i].id + '">' + data[i].value + '</button></div>');
        $('#reportDialog form').append(report);
      }
      $('.report_button').on('click', function(event){
        event.preventDefault();
        JVO.call('image/report', {
          image: $('.main').attr('id'),
          type: $(this).val()
        });
        $('#reportDialog').modal('hide');
      });
		});
  });

	/*Remove image*/
	$('#remove_image').one('click', function(event){
		event.preventDefault();
		var removeImage = function () {
			JVO.call('image/remove', {
				'image' : $('.main').attr('id')
			});
			$('#removeImageDialog').modal('hide');
		};
		$('#removeImageConfirm').click(function(){
			removeImage();
		});
	});

});
