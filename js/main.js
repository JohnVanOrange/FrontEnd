var api = {
 client : function (method, opt) {
  var url = '/api/' + method;
  var response = $.parseJSON($.ajax({
   type: 'post',
   async: false,
   url: url,
   data: opt,
   dataType: 'json'
  }).responseText);
  if (response.hasOwnProperty('error')) {
   throw {name: response.error, message: response.message};
  }
  return response;
 },
 call : function (method, opt) {
  return this.client(method, opt);
 }
};

var page_refresh = {
 timer : null,
 set : function(secs) {
  this.timer = setTimeout(function() {
   $('#main_image').click();
  }, secs * 1000);
 },
 remove : function() {
  clearTimeout(this.timer);
 }
}
 
function exception_handler(e) {
 noty({text: e.message, type: 'error'});
 switch (e.name) {
 case 1020: //Must be logged in to save image
 case 1021: //Must be logged in to unsave image
  $('#star').toggleClass('saved not_saved');
  break; 
 }
}

function call(method, opt) {
 "use strict";
 try {
  var result = api.call(method, opt);
  if (result.message) {
   var message = result.message;
   if (result.thumb) {
    message = message + '<br><img src="' + result.thumb + '">';
   }
   if (result.url) {
    message = '<a href="' + result.url + '">' + message + '</a>';
   }
   noty({text: message});
  }
  return result;
 } catch (e) {
  exception_handler(e);
 }
 return null;
}

$(document).ready(function () {
 /*Force images to fit to page width*/
 $('#img_container').imagefit();

 /*Star and brazzify positioning/visual hacks*/
 $('#main_image').hover(function() {
  //onhover
  if (!$('#main_image').hasClass('brazzified')) $('#brazzers_text').fadeIn('fast');
  $('#brazzers_text').position({
   my: 'right bottom',
   at: 'right bottom',
   of: $('#main_image'),
   offset: '-3, -3'
  });
  $('#star').fadeIn('fast');
  $('#star').position({
   my: 'left top',
   at: 'left top',
   of: $('#main_image'),
   offset: '3, 3'
  });
 },function() {
  //offhover
  if (!$('#star').is(':hover')) $('#star').fadeOut('fast');
  if (!$('#brazzers_text').is(':hover')) $('#brazzers_text').fadeOut('fast');
 });
 

 /*Options for Notifications*/
 $.noty.defaultOptions.layout = 'topRight';
 $.noty.defaultOptions.type = 'information';
 $.noty.defaultOptions.timeout = 10000;

 /*Initialize history.js*/
 var History = window.History;

 /*Keyboard controls*/
 $('body').keydown(function (event) {
  /*console.log(event.keyCode);*/
  switch (event.keyCode) {
  case 32://Space
   event.preventDefault();
   $('#main_image').click();
   break;
  case 37://left arrow
   window.history.back();
   break;
  case 39://right arrow
   window.history.forward();
   break;
  case 66://b
   $('#brazzify').click();
   break;
  case 82: //r
   $('#refresh').click();
   break;
  case 83://s
   $('#star').click();
   break;
  case 84://t
   $('#set_theme').click();
   break;
  case 124:
   window.location.href = 'http://johnvanorange.com/b/joJpMJ';
   break;
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
 $.extend($.ui.dialog.prototype.options, { 
  open: function() {
   $(this).parent().find('.ui-dialog-buttonpane button').keydown(function (event) {
    event.stopPropagation();//allow spaces to submit form
   });
  } 
 });

 /*Create Account dialog*/
 $('#create_acct').click(function (event) {
  event.preventDefault();
  var create = function () {
   if ($('#create_password').val() !== $('#create_password2').val()) {
    var e = {message: "Passwords don't match"};
    exception_handler(e);
   } else {
    var response = call('user/add', {
     'username': $('#create_username').val(),
     'password': $('#create_password').val(),
     'email': $('#create_email').val()
    });
    if (!response.error) {
     call('user/login', {
      'username': $('#create_username').val(),
      'password': $('#create_password').val()
     });
     $('#account_dialog').dialog('close');
     window.location.reload();
    }
   }
  };
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
   var response = call('user/login', {
    'username': $('#username').val(),
    'password': $('#password').val()
   });
   if (response.sid) {
    $('#login_dialog').dialog('close');
    window.location.reload();
   }
  };
  $('#password').bind('keydown', function (event) {
   if (event.keyCode === 13) {
    event.preventDefault();
    login();
   }
  });
  $('#username').bind('keydown', function (event) {
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
 $('#logout').click(function (event) {
  var response = call('user/logout');
  if (!response.error) window.location.reload();
 });

 /*Search by tag box*/
 $('#search form').submit(function (event) {
  event.preventDefault();
  var taginfo = call('tag/get', {'value': $('#tagsearch').val(), 'search_by': 'name'});
  window.location.href = taginfo[0].url;
 });

 /*Report Image dialog*/
 $('#report').click(function (event) {
  event.preventDefault();
  $('#report_dialog').dialog({
   title: 'Report Image',
   buttons: {
    'Report': function () {
     call('image/report', {
      'id': $('#image_id').val(),
      'type': $('#report_dialog input[type=radio]:checked').val()
     });
     $(this).dialog('close');
    }
   }
  });
 });

 /*Upload Image dialog*/
 $('#upload').click(function (event) {
  event.preventDefault();
  $('#addimage_dialog').dialog({
   title: 'Add Images',
   width: 500,
   buttons: {
    'Select from Computer' : function () {
     window.location.href = '/upload';
    },
    'Add from URL': function () {
     call('image/addFromURL', {
      'url': $('#url').val()
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
  var tag_name_ac = $('#tag_name').autocomplete({
   source: '/api/tag/suggest',
   minLength: 2
  });
  var addtag = function () {
   var result = call('tag/add', {
    'name': $('#tag_name').val(),
    'image' : $('#uid').val()
   });
   var tagtext = '', i;
   for (i in result.tags) {
    tagtext = tagtext + '<a href="' + result.tags[i].url + '">' + result.tags[i].name + '</a>';
   }
   $('#tagtext').html(tagtext);
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

 /*Theme changer*/
 $('#set_theme').click(function () {
  $('body').toggleClass('light dark');
  call('theme/set',{theme:$('body').attr('class')});
 });
 
 /*Save image*/
 $('#star').click(function () {
  $('#star').toggleClass('saved not_saved');
  if ($('#star').hasClass('saved')) {
   call('image/save',{image:$('#uid').val()});
  } else {
   call('image/unsave',{image:$('#uid').val()});
  }
 });
 
 /*Auto refresh*/
 if ($('#refresh_time').val() > 0) page_refresh.set($('#refresh_time').val());
 $('#refresh').change(function () {
  if ($('#refresh').attr('checked')) {
   refresh = call('refresh/set');
   page_refresh.set(refresh.refresh);
  } else {
   call('refresh/remove');
   page_refresh.remove();
  }
 });

 /*Brazzify page changing using history.js*/
 $('#brazzify').click(function (event) {
  event.preventDefault();
  History.pushState({state: 1}, 'Brazzified', '/b/' + $('#uid').val());
 });
 $(window).bind("statechange", function () {
  var state = History.getState();
  if (state.data.state === 1) {brazzify(); } else {normal(); }
  if (state.data.state === 1) {brazzify(); } else {normal(); }
 });

 /*Tag Search Autocomplete*/
 $('#tagsearch').autocomplete({
  source: '/api/tag/suggest',
  minLength: 2
 });

 /*Image Carousel*/
 $("#carousel").CloudCarousel(
  {
   xPos: 300,
   yPos: 80,
   mouseWheel: true
  }
 );
 

});

function brazzify() {
 $('#main_image').attr('src', 'http://brazzify.me/?s=http://' + document.domain + '/media/' + $('#image_name').val());
 $('#brazzers_text').hide();
 $('#main_image').addClass('brazzified');
}

function normal() {
 $('#main_image').attr('src', 'http://' + document.domain + '/media/' + $('#image_name').val());
 $('#main_image').removeClass('brazzified');
}
