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
 
});