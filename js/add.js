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
				JVO.api.result_process( response );
			} catch( e ) {
				JVO.exception( e );
			}
		}
	});
});
