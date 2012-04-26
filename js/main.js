$(document).ready(function() {
 $('#set_theme').click(function() {
  $('body').toggleClass('light dark');
  $.cookie('theme',$('body').attr('class'),{expires: 365});
 });
});
