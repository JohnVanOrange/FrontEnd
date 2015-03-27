$('document').ready(function(){

 $("body").removeClass("preload");

 /*Ubuntu integration*/
 try {
  window.Unity = external.getUnityObject(1.0);
  Unity.init({
   name: site_name,
   iconUrl: web_root+"icons/"+icon_set+"/128.png",
   onInit: null
  });
 } catch(err) {}

 /*Options for Notifications*/
 $.noty.defaults.layout = 'topLeft';
 $.noty.defaults.type = 'information';
 $.noty.defaults.timeout = 10000;

 /*Tag search autosuggest*/
 $('#searchTag').typeahead({
	remote: '/api/tag/suggest?term=%QUERY',
	limit: 16
 });
 $('#searchDialog .tt-hint').addClass('form-control');

 /*Keyboard controls*/
 var ctrl_down = false;

 $(document).keydown(function(event) {
  if (event.keyCode === 17) ctrl_down = true;
 }).keyup(function(event) {
  if (event.keyCode === 17) ctrl_down = false;
 });

 $('body').keydown(function (event) {
  if (!ctrl_down) {
   switch (event.keyCode) {
	case 32://Space
		event.preventDefault();
    if ($('.next-image').length) {
      window.location.href = $('.next-image').attr('href');
    }
		break;
	case 37://left arrow
		window.history.back();
		break;
	case 39://right arrow
		window.history.forward();
		break;
	case 82://r
		$('#report_image').click();
		break;
	case 83://s
		$('#save_image').click();
		break;
	case 84://t
		event.preventDefault();
		$('#add_tag').click();
		break;
    case 107://+
		$('#like_image').click();
		break;
	case 109://-
		$('#dislike_image').click();
		break;
	case 124:
		window.location.href = 'http://jvo.io/joJpMJ';
		break;
   }
  }
 });

 /*Make sure input/textarea input's don't propagate keypresses to the body*/
 inputKeyboardHandler = function() {
  $('input, textarea').on('keydown', function (event) {
   event.stopPropagation();
  });
 };
 inputKeyboardHandler();

 /*Logout*/
 $('#logout').click(function () {
  event.preventDefault();
  JVO.call('user/logout', function(response){
   if (!response.error) window.location.reload();
  });
 });

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

	$('.icon').click(function() {
		$(this).addClass('pressed');
		setTimeout(function(){$('.icon').removeClass('pressed')},80);
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

	/*Firefox Open Web App integration*/
	$('#firefox_menu').click(function (event) {
		event.preventDefault();
		var apps = window.navigator.mozApps.getInstalled();
		apps.onsuccess = function() {
			if (!apps.result.length) {
				var request = window.navigator.mozApps.install(web_root+'manifest.webapp');
				request.onsuccess = function () {
					// Save the App object that is returned
					var appRecord = this.result;
					noty({text: 'Web App Installed', dismissQueue: true});
				};
				request.onerror = function () {
					// Display the error information from the DOMError object
					noty({text: 'Install failed, error: ' + this.error.name, type: 'error', dismissQueue:true});
				};
			}
		};
	});

});

var $body = $(document.body),
bodyHeight = $body.height(),
$logo = $('.title_o');
$(window).on('scroll', function (event) {
    //header scaling
    var scroll = $(this).scrollTop();
    if (scroll > 100) {
      $('header h1').addClass('mini');
    } else {
      $('header h1').removeClass('mini');
    }
    //logo circling
    $logo.css({
        'transform': 'rotate(' + ($body.scrollTop() / bodyHeight * 360) + 'deg)'
    });

});
