/* global Dropzone */

'use strict()';

var JVO = JVO || {};

JVO.dialogHandlers = {

	login: {
		title: 'Login',
		submitButton: 'Login',
		submit: function() {
			JVO.call('user/login',
				{
					'username': $('#loginUsername').val(),
					'password': $('#loginPassword').val()
				})
			.done( function( response ){
				if ( response.sid ) {
					$( '.modal' ).modal('hide');
					JVO.notifications.store( response.notification_message );
					window.location.reload();
				}
			});
		}
	},

	create_acct: {
		title: 'Create Account',
		submitButton: 'Create',
		submit: function() {
			if ($('#createPassword').val() !== $('#createPasswordConfirm').val()) {
				var e = {message: 'Passwords don\'t match'};
				JVO.exception( e );
			} else {
				JVO.call('user/add',
					{
						'username': $('#createUsername').val(),
						'password': $('#createPassword').val(),
						'email': $('#createEmail').val()
					})
				.done(function( response ){
					$( '.modal' ).modal('hide');
					JVO.notifications.store( response.notification_message );
					window.location.reload();
				});
			}
		}
	},

	add_image: {
		title: 'Add Image',
		load: function() {
			Dropzone.autoDiscover = false;
			$("#drop").dropzone({
				paramName: "image",
				url: '/api/image/add',
				success: function(file, response) {
					try {
						if (response.hasOwnProperty('error')) {
							throw {name: response.error, message: response.message};
						}
						var message = response.message + '<br><img src="' + response.thumb + '">';
						noty({text: message, dismissQueue: true});
					} catch( e ) {
						JVO.exception( e );
					}
				}
			});
		},
		submit: function() {
			JVO.call('image/addFromURL',
				{
					'url': $('#addImageURL').val()
				});
			$( '.modal' ).modal('hide');
		}
	},

	pwreset_request: {
		title: 'Forgot Password',
		submitButton: 'Submit',
		submit: function() {
			JVO.call('user/requestPwReset',
				{
					'username': $('#pwresetRequestUsername').val()
				})
			.done(function(){
				$( '.modal' ).modal('hide');
			});
		}
	},

	admin_message: {
		title: 'Send Message to Admin',
		submitButton: 'Send Message',
		submit: function() {
			JVO.call('message/admin',
				{
					'name': $('#messageName').val(),
					'email': $('#messageEmail').val(),
					'message': $('#messageText').val()
				})
			.done(function(){
				$( '.modal' ).modal('hide');
			});
		}
	},

	search: {
		title: 'Search Tags',
		submitButton: 'Search',
		load: function() {
			$('#searchTag').typeahead({
				remote: '/api/tag/suggest?term=%QUERY',
				limit: 16
			});
			$('.modal .tt-hint').addClass('form-control');
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
			$( '.modal' ).modal('hide');
		}
	},

	add_tag: {
		title: 'Add Tag',
		submitButton: 'Add',
		load: function() {
			$('#addTag').typeahead({
				remote: '/api/tag/suggest?term=%QUERY',
				limit: 16
			});
			$('.modal .tt-hint').addClass('form-control');
			$('#addTag').typeahead('setQuery','');
		},
		submit: function() {
			JVO.call('tag/add',
				{
					'name': $('#addTag').val(),
					'image' : $('.main').attr('id')
				})
			.done(function( result ){
				$('#tags a, #tags em').remove();
				for (var i in result.tags) {
					if (result.tags.hasOwnProperty(i)) {
						var tag = $('<a>');
						$(tag).attr('href', result.tags[i].url).addClass('tag').html(result.tags[i].name);
						$('#tags').append(tag);
					}
				}
			});
			$( '.modal' ).modal('hide');
		}
	},

	remove_image: {
		title: 'Remove Image',
		submitButton: 'OK',
		size: 'sm',
		submit: function() {
			JVO.call('image/remove', {
				'image' : $('.main').attr('id')
			});
			$( '.modal' ).modal('hide');
		}
	},

	report: {
		title: 'Report Image',
		load: function() {
			JVO.call('report/all')
			.done(function( data ) {
				var $report = $('<div></div>');
				for (var i in data) {
					if (data.hasOwnProperty(i)) {
						$report.append('<div class="form-group"><button class="report_button" value="' + data[i].id + '">' + data[i].value + '</button></div>');
					}
				}
				$( '.modal form' ).append($report);
				$('.report_button').on('click', function(event){
					event.preventDefault();
					JVO.call('image/report', {
						image: $('.main').attr('id'),
						type: $(this).val()
					});
					$( '.modal' ).modal('hide');
				});
			});
		}
	},
	
	keyboard: {
		title: 'Keyboard Shortcuts'
	},
	
	filter: {
		title: 'Filter Images'
	}

};


$(function () {

	$( 'body' ).on( 'click', 'button[data-dialog], a[data-dialog]', function( event ) {
		event.preventDefault();
		var e = $(this).attr('href');
		var name = $(this).attr('data-dialog');
		JVO.dialog( name, e );
	});

});
