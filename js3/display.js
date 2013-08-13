$('document').ready(function(){
	
	/*Add tag autosuggest*/
	$('#addTag').typeahead({
		remote: '/api/tag/suggest?term=%QUERY'
	});
	$('#addTagDialog .tt-hint').addClass('form-control');
});