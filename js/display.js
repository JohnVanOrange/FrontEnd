/*Next image preload*/
var preload = function() {
	call('image/random', function(next_image_data){
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
			call('tag/add', function(result){
				$('#tags a, #tags em').remove();
				for (i in result.tags) {
					tag = $('<a>');
					$(tag).attr('href', result.tags[i].url).addClass('tag').html(result.tags[i].name);
					$('#tags').append(tag);
				}
			}, 
			{
				'name': $('#addTag').val(),
				'image' : $('.main').attr('id')
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
			call('image/save',function(){},{image:$('.main').attr('id')});
		} else {
			call('image/unsave',function(){},{image:$('.main').attr('id')});
		}
	});

	$('#like_image').click(function () {
		$('#like_image').addClass('highlight');
		$('#dislike_image').removeClass('highlight');
		call('image/like',function(){},{image:$('.main').attr('id')});
	});
	 
	$('#dislike_image').click(function () {
		$('#dislike_image').addClass('highlight');
		$('#like_image').removeClass('highlight');
		call('image/dislike',function(){},{image:$('.main').attr('id')});
	});
	
	/*Load report types*/
  $('#report_image').one('click', function() {
    api.call('report/all', function(data) {
      for (var i in data) {
        report = $('<div class="form-group"><button class="btn report_button" value="' + data[i].id + '">' + data[i].value + '</button></div>');
        $('#reportDialog form').append(report);
      }
      $('.report_button').on('click', function(){
        event.preventDefault();
        api.call('image/report', function(){}, {
          image: $('.main').attr('id'),
          type: $(this).val()
        });
        $('#reportDialog').modal('hide');
      });
		});
  });

});