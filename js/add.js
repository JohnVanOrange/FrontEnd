Dropzone.autoDiscover = false;
$('document').ready(function(){
	$("#drop").dropzone({
		paramName: "image",
		url: '/api/image/add',
		success: function(file, response) {
			try {
			 if (response.hasOwnProperty('error')) {
				console.log('haserror');
				throw {name: response.error, message: response.message};
			 }
			 console.log('going to process');
			 process(response);
			} catch(e) {
				console.log('execption was caught');
				exception_handler(e);
			}
		}
	});
});