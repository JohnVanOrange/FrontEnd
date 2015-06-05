var JVO = JVO || {};

JVO.exception = function( e ) {
	"use strict";
	console.error( e.message );
	noty({ text: e.message, type: 'error', dismissQueue:true });
	switch ( e.name ) {
		case 1020: //Must be logged in to save image
		case 1021: //Must be logged in to unsave image
			$( '#save_image' ).removeClass( 'highlight' );
			/*falls through*/
		case 1032: //Must be logged in to add tag to image
			$( '#login' ).click();
		break;
	}
};

JVO.call = function( method, opt ) {
	"use strict";

	var deferred = $.Deferred();

	JVO.api.client( method, opt )
		.done( function( response ) {
			if ( response.message ) {
				var message = response.message;
				if ( response.thumb ) {
					message = message + '<br><img src="' + response.thumb + '">';
				}
				if ( response.url ) {
					message = '<a href="' + response.url + '">' + message + '</a>';
				}
				noty({text: message, dismissQueue: true});
				response.notification_message = message;
			}
			deferred.resolve( response );
		} )
		.fail( function( error ) {
			JVO.exception( error );
			deferred.reject( error );
		});

	return deferred.promise();
};

JVO.dialog = {

	load: function(){},
	submit: function(){},
	handler: '',

	render: function( name, e ) {
		JVO.dialog.init(name);
		var $modal = JVO.dialog.build(e);
		JVO.dialog.show($modal, e);
	},

	init: function(name) {
		"use strict";

		JVO.dialog.handler  = JVO.dialogHandlers[name];

		if (typeof JVO.dialog.handler.load === 'function') {
			JVO.dialog.load = JVO.dialog.handler.load;
		}

		if (typeof JVO.dialog.handler.submit === 'function') {
			JVO.dialog.submit = JVO.dialog.handler.submit;
		}

		$('.modal.in').modal('hide');
	},

	build: function(e) {
		var data = {
			title: JVO.dialog.handler.title
		};
		
		var $modal = $( _.template( $('#dialogBase').html() )( {data: data}) );
		var $modal_content = $( _.template( $(e).html() )() );
		$modal.find('.modal-body').append($modal_content);
		$modal.attr('data-dialog-name', e);
		if (JVO.dialog.handler.size) $modal.find('.modal-dialog').addClass('modal-' + JVO.dialog.handler.size);
		if (JVO.dialog.handler.submitButton) {
			var submitButton = _.template( $('#dialogSubmitButton').html() )({ text: JVO.dialog.handler.submitButton });
			$modal.find('.modal-footer').append( submitButton );
		}
		return $modal;
	},

	show: function(modal, e) {
		modal.modal('show');

		modal.on('shown.bs.modal', function(){
			JVO.dialog.load();
			$('.modal input.submit').bind('keydown', function (event) {
				if (event.keyCode === 13) {
					event.preventDefault();
					JVO.dialog.submit();
				}
			});
			$('.modal button.submit').click(function(){
				JVO.dialog.submit();
			});
			$('.modal .focus').focus();
		});

		modal.on('hidden.bs.modal', function(){
			$( '.modal[data-dialog-name=' + e + ']' ).remove();
		});
	}

};

JVO.notifications = {
	store: function( notification ) {
		localStorage.setItem('storedNotification', notification );
	},
	load: function() {
		var storedNotification = localStorage.getItem('storedNotification');
		if ( storedNotification ) {
			noty({text: storedNotification, dismissQueue: true});
			localStorage.removeItem('storedNotification');
		}
	}
};
