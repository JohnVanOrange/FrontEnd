var JVO = JVO || {};

JVO.api = {

	client : function ( method, opt ) {
		'use strict';

		var deferred = $.Deferred();
		var url = '/api/' + method;
		$.ajax({
			type: 'post',
			url: url,
			data: opt,
			dataType: 'json',

			success: function( response ) {
				if (response.hasOwnProperty('error')) {
					deferred.reject( {name: response.error, message: response.message} );
				}
				else {
					deferred.resolve( response );
				}
			},

			error : function( xhr, status, error ) {
				var response = xhr.responseJSON;
				if (response.hasOwnProperty('error')) {
					deferred.reject( {name: response.error, message: response.message} );
				}
				else {
					deferred.reject( error );
				}
			}

		});

		return deferred.promise();

	},

	debug : function( method, opt ) {
		var id = Math.floor( (Math.random()*1000)+1 );
		var start = +new Date();
		JVO.api.client(method, opt).always(
			function( response ){
				var end = +new Date();
				var time = end - start;
				console.dir({
					id: id,
					response: response,
					time : time+'ms'
				});
			}
		);
		return id;
	}
};
