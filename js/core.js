var JVO = JVO || {};

JVO.exception = function( e ) {
	console.warn( e.message );
	noty({ text: e.message, type: 'error', dismissQueue:true });
	switch ( e.name ) {
		case 1020: //Must be logged in to save image
		case 1021: //Must be logged in to unsave image
			$( '#save_image' ).removeClass( 'highlight' );
			$( '#login' ).click();
		break;
	}
}

JVO.call = function( method, callback, opt ) {
	"use strict";
	try {
		JVO.api.call( method, callback, opt );
	} catch (e) {
		JVO.exception_handler( e );
	}
	return null;
}
