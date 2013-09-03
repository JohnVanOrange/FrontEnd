Dropzone.autoDiscover = false;
$('document').ready(function(){
	$("#drop").dropzone({
		paramName: "image",
		url: '/api/image/add',
		success: function(file, response) {
			process(response);
		}
	});
});