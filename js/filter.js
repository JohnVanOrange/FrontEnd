$('document').ready(function(){
	'use strict';
  $.cookie.json = true;

	/*filter dialog*/
	$('#filter').one('click', function(event){
		event.preventDefault();
		//load current settings
		var current = $.cookie('filter');
		for (var i in current) {
			if (current.hasOwnProperty(i)) {
				var setting = $('#' + i);
				setting.val(current[i]);
				setting.parent().parent().find('input[type="checkbox"]').click();
			}
		}
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
			$.cookie('filter', filter, {expires: 365});
			noty({text: 'Filters saved', dismissQueue: true});
			$('#filter').addClass('active');
			$('#filterDialog').modal('hide');
			preload();
		};
		var clearFilter = function () {
			$.removeCookie('filter');
			noty({text: 'Filters cleared', dismissQueue: true});
			$('#filter').removeClass('active');
			$('#filterDialog').modal('hide');
			preload();
		};
		$('#filterSubmit').click(function(){
			saveFilter();
		});
		$('#filterClear').click(function(){
			clearFilter();
		});
	});

	$('#filterDialog input[type="checkbox"]').change(function(){
		if ($(this).is(':checked')) {
			$(this).parent().parent().parent().find('.form-control').attr('disabled', false);
		}
		else {
			$(this).parent().parent().parent().find('.form-control').attr('disabled', true);
		}
	});
});
