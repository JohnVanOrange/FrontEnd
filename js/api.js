var JVO = JVO || {};

JVO.api = {
	//use promises
	//http://www.bitstorm.org/weblog/2012-1/Deferred_and_promise_in_jQuery.html

	client : function ( method, callback, opt ) {
		var url = '/api/' + method;
		$.ajax({
		type: 'post',
		url: url,
		data: opt,
		dataType: 'json',
		success: function( response ) {
			try {
				if (response.hasOwnProperty('error')) {
					throw {name: response.error, message: response.message};
				}
				callback( response );
				JVO.api.result_process( response );
			} catch(e) {
				JVO.exception( e );
			}
		},

		error : function( xhr ) {
			var response = JSON.parse(xhr.responseText);
			try {
				if (response.hasOwnProperty('error')) {
					throw {name: response.error, message: response.message};
				}
				JVO.api.result_process( response );
			} catch( e ) {
				JVO.exception( e );
			}
		}
	});
	},

	call : function ( method, callback, opt ) {
		return this.client(method, callback, opt);
	},

	result_process : function( result ) {
		"use strict";
		try {
			if ( result.message ) {
				var message = result.message;
				if ( result.thumb ) {
					message = message + '<br><img src="' + result.thumb + '">';
				}
				if ( result.url ) {
					message = '<a href="' + result.url + '">' + message + '</a>';
				}
				noty({text: message, dismissQueue: true});
			}
			return result;
		} catch ( e ) {
			JVO.exception( e );
		}
		return null;
	},

	debug : function( method, opt ) {
		var id = Math.floor( (Math.random()*1000)+1 );
		var start = +new Date();
		JVO.api.call(
			method,
			function( response ){
				var end = +new Date();
				var time = end - start;
				console.dir({
					id: id,
					response: response,
					time : time+'ms'
				});
			},
			opt
		);
		return id;
	}
};
