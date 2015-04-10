var JVO = JVO || {};

JVO.dialogHandlers = {

	login: {
		submit: function() {
			JVO.call('user/login',
				{
					'username': $('#loginUsername').val(),
					'password': $('#loginPassword').val()
				})
			.done( function( response ){
				if ( response.sid ) {
					$('#loginDialog').modal('hide');
					JVO.notifications.store( response.notification_message );
					window.location.reload();
				}
			});
		}
	},

	create_acct: {
		submit: function() {
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
					JVO.notifications.store( response.notification_message );
					window.location.reload();
				});
			}
		}
	},

	add_image: {
		submit: function() {
			JVO.call('image/addFromURL',
				{
					'url': $('#addImageURL').val()
				});
			$('#addImageDialog').modal('hide');
		}
	},

	pwreset_request: {
		submit: function() {
			JVO.call('user/requestPwReset',
				{
					'username': $('#pwresetRequestUsername').val()
				})
			.done(function( response ){
				$('#pwresetRequestDialog').modal('hide');
			});
		}
	},

	admin_message: {
		submit: function() {
			JVO.call('message/admin',
				{
					'name': $('#messageName').val(),
					'email': $('#messageEmail').val(),
					'message': $('#messageText').val()
				})
			.done(function(response){
				$('#adminMessageDialog').modal('hide');
			});
		}
	},

	search: {
		load: function() {
			$('#searchTag').typeahead('setQuery','');
		},
		submit: function() {
			JVO.call('tag/get',
				{
					'value': $('#searchTag').val(),
					'search_by': 'name'
				})
			.done(function( taginfo ){
				window.location.href = taginfo[0].url;
			});
			$('#searchDialog').modal('hide');
		}
	},

	add_tag: {
		load: function() {
			$('#addTag').typeahead({
				remote: '/api/tag/suggest?term=%QUERY',
				limit: 16
			});
			$('#addTag').typeahead('setQuery','');
			$('#addTagDialog .tt-hint').addClass('form-control');
		},
		submit: function() {
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
		}
	},

	remove_image: {
		submit: function() {
			JVO.call('image/remove', {
				'image' : $('.main').attr('id')
			});
			$('#removeImageDialog').modal('hide');
		}
	},

	report: {
		load: function() {
			JVO.call('report/all')
			.done(function( data ) {
				for (var i in data) {
					var report = $('<div class="form-group"><button class="btn report_button" value="' + data[i].id + '">' + data[i].value + '</button></div>');
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
		}
	}

}


$(function () {

	$( "button[data-dialog], a[data-dialog]" ).click(function( event ) {
		event.preventDefault();
		var e = $(this).attr('href');
		var name = $(this).attr('data-dialog');
		JVO.dialog( name, e );
	});

});
