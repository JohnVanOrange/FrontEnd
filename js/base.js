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
 $('#tag_search').typeahead({
	remote: '/api/tag/suggest?term=%QUERY'
 });
 
 /*Keyboard controls*/
 var ctrl_down = false;

 $(document).keydown(function(event) {
  if (event.keyCode == 17) ctrl_down = true;
 }).keyup(function(event) {
  if (event.keyCode == 17) ctrl_down = false;
 });
 
 $('body').keydown(function (event) {
  if (!ctrl_down) {
   switch (event.keyCode) {
   case 32://Space
    event.preventDefault();
    $('.main').click();
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
   case 124:
    window.location.href = 'http://johnvanorange.com/joJpMJ';
    break;
   }
  }
 });
 
 /*Make sure input boxes don't propagate keypresses to the body*/
 inputKeyboardHandler = function() {
  $('input').on('keydown', function (event) {
   event.stopPropagation();
  });
 }
 inputKeyboardHandler();
 
 /*Search by tag box*/
 $('form#search').submit(function (event) {
  event.preventDefault();  
  call('tag/get', function(taginfo){
   window.location.href = taginfo[0].url;
  }, {'value': $('#tag_search').val(), 'search_by': 'name'});
 });
 
	/*Load report types*/
  $('#report_image').one('click', function() {
		console.log('test');
    api.call('report/all', function(data) {
      for (i in data) {
        report = $('<div class="form-group"><button class="btn report_button" value="' + data[i].id + '">' + data[i].value + '</button></div>');
        $('#reportDialog form').append(report);
      }
      $('.report_button').on('click', function(){
        event.preventDefault();
        api.call('image/report', function(){}, {
          image: $('.main').attr('id'),
          type: $(this).val()
        });
        $('#reportDialog').modal('hide');
      });
		});
  });

 /*Logout*/
 $('#logout').click(function () {
  event.preventDefault();
  call('user/logout', function(response){
   if (!response.error) window.location.reload();
  })
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
  $('#loginDialog').modal('hide');
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
   };
  }
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
	
});