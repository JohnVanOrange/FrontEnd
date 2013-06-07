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

function display_mods() {

 /*Uploaded by message*/
 if ($('#img_container').length !== 0) {
  $('#img_container p').position({
   my: 'right bottom',
   at: 'right top',
   of: $('.image'),
   offset: '0, -15'
  });
 }
}

$(document).ready(function() {

 /*Ubuntu integration*/
 try {
  window.Unity = external.getUnityObject(1.0);
  Unity.init({
   name: site_name,
   iconUrl: web_root+"icons/"+icon_set+"/128.png",
   onInit: null
  });
 } catch(err) {}
 
 /*Display hacks*/
 display_mods()
 /*Hide search button*/
 $('#search button[type=submit]').button().hide();
 
 /*Options for Notifications*/
 $.noty.defaults.layout = 'topLeft';
 $.noty.defaults.type = 'information';
 $.noty.defaults.timeout = 10000;
 
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
    $('.image').click();
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
 
 /*Make it so keyboard events don't propagate to the dialog box buttons*/
 if ($.ui !== undefined) {
  $.extend($.ui.dialog.prototype.options, { 
   open: function() {
    $(this).parent().find('.ui-dialog-buttonpane button').keydown(function (event) {
     event.stopPropagation();//allow spaces to submit form
    });
   } 
  });
 }

 /*Create Account dialog*/
 $('.create_acct').click(function (event) {
  event.preventDefault();
  $('#login_dialog').dialog('close');
  var create = function () {
   if ($('#create_password').val() !== $('#create_password2').val()) {
    var e = {message: "Passwords don't match"};
    exception_handler(e);
   } else {
    call('user/add',function(response){
     if (!response.error) {  
      $('#account_dialog').dialog('close');
      window.location.reload();
     }
    },
    {
     'username': $('#create_username').val(),
     'password': $('#create_password').val(),
     'email': $('#create_email').val()
    });
   };
  }
  $('#create_email').bind('keydown', function (event) {
   if (event.keyCode === 13) {
    event.preventDefault();
    create();
   }
  });
  $('#account_dialog').dialog({
   title: 'Create Account',
   width: 430,
   buttons: {
    'Create': function () {
     create();
    }
   }
  });
 });

 /*Login dialog*/
 $('#login').click(function (event) {
  event.preventDefault();
  var login = function () {
   call('user/login', function(response){
    if (response.sid) {
     $('#login_dialog').dialog('close');
     window.location.reload();
    }
   },
   {
    'username': $('#login_username').val(),
    'password': $('#login_password').val()
   });

  };
  $('#login_password').bind('keydown', function (event) {
   if (event.keyCode === 13) {
    event.preventDefault();
    login();
   }
  });
  $('#login_username').bind('keydown', function (event) {
   if (event.keyCode === 13) {
    event.preventDefault();
    login();
   }
  });
  $('#login_dialog').dialog({
   title: 'Login',
   buttons: {
    'Login': function () {
     login();
    }
   }
  });
 });
 
 /*Logout*/
 $('#logout').click(function () {
  event.preventDefault();
  call('user/logout', function(response){
   if (!response.error) window.location.reload();
  })
 });
 
 $('#save_image').click(function () {
  $('#save_image').toggleClass('highlight');
  if ($('#save_image').hasClass('highlight')) {
   call('image/save',function(){},{image:$('.image').attr('id')});
  } else {
   call('image/unsave',function(){},{image:$('.image').attr('id')});
  }
 });
 
 $('#like_image').click(function () {
  $('#like_image').addClass('highlight');
  $('#dislike_image').removeClass('highlight');
  call('image/like',function(){},{image:$('.image').attr('id')});
 });
 
  $('#dislike_image').click(function () {
  $('#dislike_image').addClass('highlight');
  $('#like_image').removeClass('highlight');
  call('image/dislike',function(){},{image:$('.image').attr('id')});
 });

 /*Tag Search Autocomplete*/
 if ($.ui !== undefined) {
  $('#tag_search').autocomplete({
   source: '/api/tag/suggest',
   minLength: 2
  });
 }
 
 /*Upload Image dialog*/
 $('#addInternet').click(function (event) {
  event.preventDefault();
  upload = function() {
   call('image/addFromURL', function(){}, {
    'url': $('#url').val()
   });
   $('#add_internet_dialog').dialog('close');
  };
  $('#url').bind('keydown', function (event) {
   if (event.keyCode === 13) {
    event.preventDefault();
    upload();
   }
  });
  $('#add_internet_dialog').dialog({
   title: 'Add Image from URL',
   width: 500,
   buttons: {
    'Add': function () {
     upload();
    }
   }
  });
 });
 
 /*Report Image dialog*/
 $('#report_image').click(function (event) {
  event.preventDefault();
  $('#report_dialog').dialog({
   title: 'Report Image',
   buttons: {
    'Report': function () {
     call('image/report', function(){}, {
      'image': $('.image').attr('id'),
      'type': $('#report_dialog input[type=radio]:checked').val()
     });
     $(this).dialog('close');
    }
   }
  });
 });
 
 /*Add Tag dialog*/
 $('#add_tag').click(function (event) {
  event.preventDefault();
  inputKeyboardHandler();
  $('#tag_name').val('');
  var tag_name_ac = $('#tag_name').autocomplete({
   source: '/api/tag/suggest',
   minLength: 2
  });
  var addtag = function () {
   call('tag/add', function(result){
    $('#tags').empty();
    for (i in result.tags) {
     tag = document.createElement('a');
     $(tag).attr('href', result.tags[i].url).addClass('tag').html(result.tags[i].name);
     $('#tags').append(tag);
    }
   }, 
   {
    'name': $('#tag_name').val(),
    'image' : $('.image').attr('id')
   });

  };
  $('#tag_name').bind('keydown', function (event) {
   if (event.keyCode === 13) {
    event.preventDefault();
    addtag();
    $('#tag_dialog').dialog('close');
   }
  });
  $('#tag_dialog').dialog({
   title: 'Add Tags',
   width: 350,
   buttons: {
    'Add': function () {
     addtag();
     $(this).dialog('close');
    }
   },
   close: function () {
    $('#tag_name').unbind('keydown');
    tag_name_ac.autocomplete('destroy');
   }
  });
 });
 
 /*Keyboard Shortcut dialog*/
 $('#keyboard').click(function (event) {
  event.preventDefault();
  $('#keyboard_dialog').dialog({
   title: 'Keyboard Shortcuts',
   width: 335
  });
 });
 
 /*Search by tag box*/
 $('form#search').submit(function (event) {
  event.preventDefault();  
  call('tag/get', function(taginfo){
   window.location.href = taginfo[0].url;
  }, {'value': $('#tag_search').val(), 'search_by': 'name'});
 });
 
 $('.fileinput-button').click(function (event) {
  $('#fileupload').fileupload({'url':'/upload-handler/index.php'});
 });
 
 $('.icon').click(function() {
  $(this).addClass('pressed');
  setTimeout(function(){$('.icon').removeClass('pressed')},80);
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
   };
  };
 });
 
 /*Next image preload*/
 if ($('#rand_image').length !== 0) {
  call('image/random', function(next_image_data){
   next_image = new Image();
   next_image.src = next_image_data.image_url;
   $('#rand_image').attr('href',next_image_data.page_url); 
  });
 }
 
});
