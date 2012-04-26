$(document).ready(function() {
 var History = window.History;
 History.Adapter.bind(window,'statechange',function(){
  var State = History.getState();
 });
 $('#set_theme').click(function() {
  $('body').toggleClass('light dark');
  $.cookie('theme',$('body').attr('class'),{expires: 365, path: '/'});
 });
 $('#brazzify').click(function() {
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
