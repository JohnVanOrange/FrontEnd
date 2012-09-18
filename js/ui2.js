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

$(document).ready(function() {
 /*Force images to fit to page width*/
 $('#img_container').imagefit();
 
 /*Options for Notifications*/
 $.noty.defaultOptions.layout = 'topLeft';
 $.noty.defaultOptions.type = 'information';
 $.noty.defaultOptions.timeout = 10000;
 
});