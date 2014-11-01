var api = {
	client : function (method, callback, opt) {
		var url = '/api/' + method;
		$.ajax({
		type: 'post',
		url: url,
		data: opt,
		dataType: 'json',
		success: function(response) {
			try {
				if (response.hasOwnProperty('error')) {
					throw {name: response.error, message: response.message};
				}
				callback(response);
				process(response);
			} catch(e) {
				exception_handler(e);
			}
		},
		error: function(xhr) {
			var response = JSON.parse(xhr.responseText);
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
 },
 call : function (method, callback, opt) {
  return this.client(method, callback, opt);
 }
};

function exception_handler(e) {
 noty({text: e.message, type: 'error', dismissQueue:true});
 switch (e.name) {
 case 1020: //Must be logged in to save image
 case 1021: //Must be logged in to unsave image
 case 1022://Must be logged in to like images
 case 1023://Must be logged in to dislike images
  $('.like').removeClass('highlight');
  $('#save_image').removeClass('highlight');
  $('#login').click();
  break;
 }
}

function call(method, callback, opt) {
 "use strict";
 try {
  api.call(method, callback, opt);
 } catch (e) {
  exception_handler(e);
 }
 return null;
}

function process(result) {
 "use strict";
 try {
  if (result.message) {
   var message = result.message;
   if (result.thumb) {
    message = message + '<br><img src="' + result.thumb + '">';
   }
   if (result.url) {
    message = '<a href="' + result.url + '">' + message + '</a>';
   }
   noty({text: message, dismissQueue: true});
  }
  return result;
 } catch (e) {
  exception_handler(e);
 }
 return null;
}

function debug(method, opt) {
 var id = Math.floor((Math.random()*1000)+1);
 var start = +new Date();
 call(method,
  function(response){
   end = +new Date();
   time = end - start;
   console.log({
    id: id,
    response: response,
	time : time+'ms'
   });
  },
  opt);
  return id;
}

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
		window.location.href = $('#main').attr('href');
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
 
 /*Make sure input boxes don't propagate keypresses to the body*/
 inputKeyboardHandler = function() {
  $('input').on('keydown', function (event) {
   event.stopPropagation();
  });
 };
 inputKeyboardHandler();

 /*Logout*/
 $('#logout').click(function () {
  event.preventDefault();
  call('user/logout', function(response){
   if (!response.error) window.location.reload();
  });
 });
 
 /*Login dialog*/
 $('#login').click(function (event) {
  event.preventDefault();
  var login = function () {
   call('user/login', function(response){
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
  //$('#loginDialog').modal('hide');
  var create = function () {
   if ($('#createPassword').val() !== $('#createPasswordConfirm').val()) {
    var e = {message: "Passwords don't match"};
    exception_handler(e);
   } else {
    call('user/add',function(response){
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
   call('image/addFromURL', function(){}, {
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
   call('user/requestPwReset', function(response){
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
      call('tag/get', function(taginfo){
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
	
	/*Firefox Open Web App integration*/
	$('#firefox_menu').click(function (event) {
		event.preventDefault();
		console.log('this is happening');
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