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

	var load = function(){};
	var submit = function(){};

	if (typeof JVO.dialogHandlers[name].load == 'function') {
		load = JVO.dialogHandlers[name].load;
	}

	if (typeof JVO.dialogHandlers[name].submit == 'function') {
		submit = JVO.dialogHandlers[name].submit;
	}

	$('.modal.in').modal('hide');

	$(e).modal('show');

	load();

	$(e).on('shown.bs.modal', function(){
		
		$(e + ' input.submit').bind('keydown', function (event) {
			if (event.keyCode === 13) {
				event.preventDefault();
				submit();
			}
		});
		$(e + ' button.submit').click(function( event ){
			submit();
		});

		$(e + ' .focus').focus();

	});


}
