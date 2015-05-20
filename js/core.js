var JVO = JVO || {};

JVO.exception = function( e ) {
	"use strict";
	console.error( e.message );
	noty({ text: e.message, type: 'error', dismissQueue:true });
	switch ( e.name ) {
		case 1020: //Must be logged in to save image
		case 1021: //Must be logged in to unsave image
			$( '#save_image' ).removeClass( 'highlight' );
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

JVO.dialog = function( name, e ) {
	"use strict";

	var load = function(){};
	var submit = function(){};
	var handler = JVO.dialogHandlers[name];

	if (typeof handler.load === 'function') {
		load = handler.load;
	}

	if (typeof handler.submit === 'function') {
		submit = handler.submit;
	}

	$('.modal.in').modal('hide');

	var data = {
		title: handler.title
	}
	
	//build dialog box
	var $modal = $( _.template( $('#dialogBase').html() )( {data: data}) );
	var $modal_content = $( _.template( $(e).html() )() );
	$modal.find('.modal-body').append($modal_content);
	$modal.attr('data-dialog-name', e);
	if (handler.size) $modal.find('.modal-dialog').addClass('modal-' + handler.size);
	if (handler.submitButton) {
		var submitButton = _.template( $('#dialogSubmitButton').html() )({ text: handler.submitButton });
		$modal.find('.modal-footer').append( submitButton );
	}

	$modal.modal('show');

	$modal.on('shown.bs.modal', function(){
		load();
		$('.modal input.submit').bind('keydown', function (event) {
			if (event.keyCode === 13) {
				event.preventDefault();
				submit();
			}
		});
		$('.modal button.submit').click(function(){
			submit();
		});
		$('.modal .focus').focus();
	});

	$modal.on('hidden.bs.modal', function(){
		$( '.modal[data-dialog-name=' + e + ']' ).remove();
	});

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
