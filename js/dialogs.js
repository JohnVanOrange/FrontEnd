var JVO = JVO || {};

JVO.dialogHandlers = {

	login: function() {
		JVO.call('user/login',
			{
				'username': $('#loginUsername').val(),
				'password': $('#loginPassword').val()
			})
		.done( function( response ){
			if ( response.sid ) {
				$('#loginDialog').modal('hide');
				window.location.reload();
			}
		});
	},

	create_acct: function() {
		if ($('#createPassword').val() !== $('#createPasswordConfirm').val()) {
			var e = {message: "Passwords don't match"};
			JVO.exception( e );
		} else {
			JVO.call('user/add',
				{
					'username': $('#createUsername').val(),
					'password': $('#createPassword').val(),
					'email': $('#createEmail').val()
				})
			.done(function( response ){
				$('#accountDialog').modal('hide');
				window.location.reload();
			});
		}
	},

	add_image: function() {
		JVO.call('image/addFromURL',
			{
				'url': $('#addImageURL').val()
			});
		$('#addImageDialog').modal('hide');
	},

	pwreset_request: function() {
		JVO.call('user/requestPwReset',
			{
				'username': $('#pwresetRequestUsername').val()
			})
		.done(function( response ){
			$('#pwresetRequestDialog').modal('hide');
		});
	},

	admin_message: function() {
		JVO.call('message/admin',
			{
				'name': $('#messageName').val(),
				'email': $('#messageEmail').val(),
				'message': $('#messageText').val()
			})
		.done(function(response){
			$('#adminMessageDialog').modal('hide');
		});
	},

	search: function() {
		JVO.call('tag/get',
			{
				'value': $('#searchTag').val(),
				'search_by': 'name'
			})
		.done(function( taginfo ){
			window.location.href = taginfo[0].url;
		});
		$('#searchDialog').modal('hide');
	},

	add_tag: function() {
		JVO.call('tag/add',
			{
				'name': $('#addTag').val(),
				'image' : $('.main').attr('id')
			})
		.done(function( result ){
			$('#tags a, #tags em').remove();
			for (i in result.tags) {
				tag = $('<a>');
				$(tag).attr('href', result.tags[i].url).addClass('tag').html(result.tags[i].name);
				$('#tags').append(tag);
			}
		});
		$('#addTagDialog').modal('hide');
	},

	remove_image: function() {
		JVO.call('image/remove', {
			'image' : $('.main').attr('id')
		});
		$('#removeImageDialog').modal('hide');
	}

}


$(function () {

	$( "button[data-dialog], a[data-dialog]" ).click(function( event ) {
		event.preventDefault();
		var e = $(this).attr('href');
		var name = $(this).attr('data-dialog');
		JVO.dialog( name, e );
	});


	//search specific stuff. Should refactor how this works at some point
	$('#search').click(function (event) {
		event.preventDefault();
		$('#searchTag').typeahead('setQuery','');
	});
	$('#searchDialog').on('shown.bs.modal', function(){
		$('#searchTag').focus();
	});


});
