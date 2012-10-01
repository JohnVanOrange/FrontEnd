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

function exception_handler(e) {
 noty({text: e.message, type: 'error', dismissQueue:true});
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
   noty({text: message, dismissQueue: true});
  }
  return result;
 } catch (e) {
  exception_handler(e);
 }
 return null;
}

$(document).ready(function() {
 /*Force images to fit to page width*/
 $('#img_container').imagefit();
 
 /*Icon for search button*/
 $('#search button[type=submit]').button({text: false, icons: {primary: 'ui-icon-search'} });
 
 /*Options for Notifications*/
 $.noty.defaults.layout = 'topRight';
 $.noty.defaults.type = 'information';
 $.noty.defaults.timeout = 10000;
 
 /*Keyboard controls*/
 $('body').keydown(function (event) {
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
  //case 66://b
  // $('#brazzify').click();
  // break;
  //case 82: //r
  // $('#refresh').click();
  // break;
  case 83://s
   $('#save_image').click();
   break;
  case 84://t
   $('#add_tag').click();
   break;
  //case 84://t
  // $('#set_theme').click();
  // break;
  case 124:
   window.location.href = 'http://johnvanorange.com/v/joJpMJ';
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
    'username': $('#login_username').val(),
    'password': $('#login_password').val()
   });
   if (response.sid) {
    $('#login_dialog').dialog('close');
    window.location.reload();
   }
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
 $('#logout').click(function (event) {
  var response = call('user/logout');
  if (!response.error) window.location.reload();
 });
 
$('#save_image').click(function () {
 $('#save_image').toggleClass('saved not_saved');
 if ($('#save_image').hasClass('saved')) {
  call('image/save',{image:$('.image').attr('id')});
 } else {
  call('image/unsave',{image:$('.image').attr('id')});
 }
});

 /*Tag Search Autocomplete*/
 $('#tag_search').autocomplete({
  source: '/api/tag/suggest',
  minLength: 2
 });
 
 /*Upload Image dialog*/
 $('#addInternet').click(function (event) {
  event.preventDefault();
  $('#add_internet_dialog').dialog({
   title: 'Add Image from URL',
   width: 500,
   buttons: {
    'Add': function () {
     call('image/addFromURL', {
      'url': $('#url').val()
     });
     $(this).dialog('close');
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
     call('image/report', {
      'id': $('#image_id').val(),
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
  var tag_name_ac = $('#tag_name').autocomplete({
   source: '/api/tag/suggest',
   minLength: 2
  });
  var addtag = function () {
   var result = call('tag/add', {
    'name': $('#tag_name').val(),
    'image' : $('.image').attr('id')
   });
   var tagtext = '', i;
   for (i in result.tags) {
    tagtext = tagtext + '<a href="' + result.tags[i].url + '">' + result.tags[i].name + '</a>, ';
   }
   tagtext = tagtext.substring(0, tagtext.length - 2);
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
 
 /*Search by tag box*/
 $('form#search').submit(function (event) {
  event.preventDefault();
  var taginfo = call('tag/get', {'value': $('#tag_search').val(), 'search_by': 'name'});
  window.location.href = taginfo[0].url;
 });
 
 
 
 
 
});