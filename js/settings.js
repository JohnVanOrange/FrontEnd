
$('.form-control').change(function(){
    call('setting/update', function(){}, {
        name: $(this).attr('name'),
        value: $(this).val()
    });
});