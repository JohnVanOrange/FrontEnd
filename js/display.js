$('document').ready(function(){
	
	$.cookie.json = true;
	
	/*Next image preload*/
	var preload = function() {
		call('image/random', function(next_image_data){
			next_image = new Image();
			next_image.src = next_image_data.media.primary.url;
			$('#main').attr('href',next_image_data.page_url); 
		});
	}
	preload();
	
	/*Add tag autosuggest*/
	$('#addTag').typeahead({
		remote: '/api/tag/suggest?term=%QUERY',
		limit: 10
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
				$('#tags').empty();
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
	
	/*filter dialog*/
	$('#filter').one('click', function(event){
		event.preventDefault();
		var saveFilter = function () {
			var filter = {};
			$('#filterDialog form').each(function () {
				var checkbox = $(this).find('.checkbox input');
				var name = checkbox.attr('name');
				var value = $('#' + name).val();
				if (checkbox.is(':checked')) {
					filter[name] = value;
				}
			});
			//the filter for format doesn't seem to work
			$.cookie('filter', filter, {expires: 365});
			noty({text: 'Filters saved', dismissQueue: true});
			$('#filter').addClass('active')
			$('#filterDialog').modal('hide');
			preload();
		};
		var clearFilter = function () {
			$.removeCookie('filter');
			noty({text: 'Filters cleared', dismissQueue: true});
			$('#filter').removeClass('active');
			$('#filterDialog').modal('hide');
			preload();
		}
		$('#filterSubmit').click(function(){
			saveFilter();
		});
		$('#filterClear').click(function(){
			clearFilter();
		});
	});

});