$(document).ready(function () {
 $('#approve').click(function() {
  call('image/approve',{image:$('#uid').val()});
  $('#main_image').click();
 });
 $('#reject').click(function() {
  call('image/remove',{image:$('#uid').val()});
  //$('#main_image').click();
 });
 $('#skip').click(function() {
  $('#main_image').click();
 });
});