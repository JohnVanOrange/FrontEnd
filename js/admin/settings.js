$(function() {
	$('.form-control').change(function(){
		JVO.call('setting/update', function(){}, {
			name: $(this).attr('name'),
			value: $(this).val()
		});
	});
});
