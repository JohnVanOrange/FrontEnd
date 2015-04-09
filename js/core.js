var JVO = JVO || {};

JVO.exception = function( e ) {
	console.error( e.message );
	noty({ text: e.message, type: 'error', dismissQueue:true });
	switch ( e.name ) {
		case 1020: //Must be logged in to save image
		case 1021: //Must be logged in to unsave image
			$( '#save_image' ).removeClass( 'highlight' );
			$( '#login' ).click();
		break;
	}
}

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
			}
			deferred.resolve( response );
		} )
		.fail( function( error ) {
			JVO.exception( error );
			deferred.reject( error );
		});

	return deferred.promise();
}

JVO.dialog = function( name, e ) {
	"use strict";

	$('.modal.in').modal('hide');

	$(e).modal('show');

	$(e + ' input.submit').bind('keydown', function (event) {
		if (event.keyCode === 13) {
			event.preventDefault();
			JVO.dialogHandlers[name]();
		}
	});
	$(e + ' button.submit').click(function( event ){
		JVO.dialogHandlers[name]();
	});
}
