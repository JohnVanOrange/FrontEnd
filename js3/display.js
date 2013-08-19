$('document').ready(function(){
	
	/*Add tag autosuggest*/
	$('#addTag').typeahead({
		remote: '/api/tag/suggest?term=%QUERY'
	});
	$('#addTagDialog .tt-hint').addClass('form-control');
	
	/*Add Tag dialog*/
	$('#add_tag').click(function (event) {
		event.preventDefault();
		$('#addTag').typeahead('setQuery','');
	});
	
	$('#addTagDialog').on('shown.bs.modal', function(){
		$('#addTag').focus();
		console.log('test');
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

});