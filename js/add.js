Dropzone.autoDiscover = false;
$('document').ready(function(){
	$("#drop").dropzone({
		paramName: "image",
		url: '/api/image/add',
		success: function(file, response) {
			try {
			 if (response.hasOwnProperty('error')) {
				throw {name: response.error, message: response.message};
			 }
			 process(response);
			} catch(e) {
				exception_handler(e);
			}
		}
	});
});