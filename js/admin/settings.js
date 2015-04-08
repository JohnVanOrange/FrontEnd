$(function() {
	$('.form-control').change(function(){
		JVO.call('setting/update', {
			name: $(this).attr('name'),
			value: $(this).val()
		});
	});
});
