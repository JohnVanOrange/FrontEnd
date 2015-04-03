$(function () {

	/*Login dialog*/
	$('#login').click(function (event) {
		event.preventDefault();
		var login = function () {
			JVO.call('user/login', function(response){
			if (response.sid) {
				$('#loginDialog').modal('hide');
				window.location.reload();
			}
			},
			{
			'username': $('#loginUsername').val(),
			'password': $('#loginPassword').val()
			});

		};
		$('#loginPassword').bind('keydown', function (event) {
			if (event.keyCode === 13) {
				event.preventDefault();
				login();
			}
		});
		$('#loginUsername').bind('keydown', function (event) {
			if (event.keyCode === 13) {
				event.preventDefault();
				login();
			}
		});
		$('#loginSubmit').click(function(){
			login();
		});
	});

	/*Create Account dialog*/
	$('.create_acct').click(function (event) {
		event.preventDefault();
		var create = function () {
			if ($('#createPassword').val() !== $('#createPasswordConfirm').val()) {
				var e = {message: "Passwords don't match"};
				JVO.exception( e );
			} else {
			JVO.call('user/add',function(response){
				if (!response.error) {
					$('#accountDialog').modal('hide');
					window.location.reload();
				}
			},
			{
				'username': $('#createUsername').val(),
				'password': $('#createPassword').val(),
				'email': $('#createEmail').val()
			});
			}
		};
		$('#createEmail').bind('keydown', function (event) {
			if (event.keyCode === 13) {
			event.preventDefault();
			create();
			}
		});
		$('#createSubmit').click(function(){
			create();
		});
	});

	/*Add from URL dialog*/
	$('#addImage').click(function (event) {
		event.preventDefault();
		upload = function() {
			JVO.call('image/addFromURL', function(){}, {
				'url': $('#addImageURL').val()
			});
			$('#addImageDialog').modal('hide');
		};
		$('#addImageURL').bind('keydown', function (event) {
			if (event.keyCode === 13) {
			event.preventDefault();
			upload();
			}
		});
		$('#addImageSubmit').click(function(){
			upload();
		});
	});

	/*Password Reset Request dialog*/
	$('#pwresetRequest').click(function (event) {
		event.preventDefault();
		var pwresetreq = function () {
			JVO.call('user/requestPwReset', function(response){
				$('#pwresetRequestDialog').modal('hide');
			},
			{
				'username': $('#pwresetRequestUsername').val()
			});
		};
		$('#pwresetRequestUsername').bind('keydown', function (event) {
			if (event.keyCode === 13) {
				event.preventDefault();
				pwresetreq();
			}
		});
		$('#pwresetRequestSubmit').click(function(){
			pwresetreq();
		});
	});

	/*Search Tag dialog*/
	$('#search').click(function (event) {
		event.preventDefault();
		$('#searchTag').typeahead('setQuery','');
	});
	$('#searchDialog').on('shown.bs.modal', function(){
		$('#searchTag').focus();
	});
	$('#search').one('click', function(event){
		event.preventDefault();
		var search = function () {
			JVO.call('tag/get', function(taginfo){
				window.location.href = taginfo[0].url;
			}, {'value': $('#searchTag').val(), 'search_by': 'name'});
			$('#searchDialog').modal('hide');
		};
		$('#searchTag').bind('keydown', function (event) {
			if (event.keyCode === 13) {
				event.preventDefault();
				search();
			}
		});
		$('#searchSubmit').click(function(){
			search();
		});
	});

	/*Send Admin Message dialog*/
	$('#adminMessage').click(function (event) {
		event.preventDefault();
		var sendAdminMessage = function () {
			JVO.call('message/admin', function(response){
				$('#adminMessageDialog').modal('hide');
			},
			{
				'name': $('#messageName').val(),
				'email': $('#messageEmail').val(),
				'message': $('#messageText').val()
			});
		};
		$('#adminMessageSubmit').click(function(){
			sendAdminMessage();
		});
	});

});
