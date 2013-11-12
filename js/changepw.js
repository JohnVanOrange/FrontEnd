$(document).ready(function () {
	$('form').submit(function(event){
		if ($('#password').val() !== $('#passwordConfirm').val()) {
			var e = {message: "Passwords don't match"};
			exception_handler(e);
			event.preventDefault();
		}
	});
});