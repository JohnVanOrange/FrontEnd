Dropzone.autoDiscover = false;
$('document').ready(function(){
	$("#drop").dropzone({
		paramName: "image",
		url: '/api/image/add'
	});
});