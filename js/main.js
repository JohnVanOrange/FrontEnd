$(document).ready(function() {
 var History = window.History;
 History.Adapter.bind(window,'statechange',function(){
  var State = History.getState();
 });
 $('#report').click(function(event) {
  event.preventDefault();
  $('#report_dialog').dialog({
   title: 'Report Image',
   buttons: {
    'Report': function() {
     api.call('reportImage',{
      'id': $('#main_image').attr('image_id'),
      'type': $('#report_dialog input[type=radio]:checked').val()
     });
     $(this).dialog('close');
    }
   },
  });
 });
 
 $('#set_theme').click(function() {
  $('body').toggleClass('light dark');
  $.cookie('theme',$('body').attr('class'),{expires: 365, path: '/'});
 });
 $('#brazzify').click(function(event) {
  event.preventDefault();
  state = {brazzify: true};
  History.pushState({state:1},'Brazzified','/brazzify/'+$('#main_image').attr('name'));
 });
 $(window).bind("statechange", function() {
  state = History.getState();
  if (state.data.state == 1) {brazzify();} else {normal();}
 });
});

function brazzify() {
 $('#main_image').attr('src','http://brazzify.me/?s=http://'+document.domain+'/media/'+$('#main_image').attr('name'));
 $('#brazzers_text').hide();
}

function normal() {
 $('#main_image').attr('src','http://'+document.domain+'/media/'+$('#main_image').attr('name'));
 $('#brazzers_text').show();
}

api = {
 client : function (method, opt) {
  url = '/api/' + method;
  response = $.parseJSON($.ajax({
   type: 'post',
   async: false,
   url: url,
   data: opt,
   dataType: 'json'
  }).responseText);
 return response;
 },
 call : function(method, opt) {
  return this.client(method, opt);
 }
};
