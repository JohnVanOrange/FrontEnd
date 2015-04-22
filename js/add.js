/* global JVO */
/* global Dropzone */
/* global noty */

Dropzone.autoDiscover = false;
$(function() {
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
});
