$(document).ready(function () {
 $('#approve').click(function() {
  call('image/approve',{id:$('#image_id').val()});
  $('#main_image').click();
 });
 $('#reject').click(function() {
  call('image/remove',{id:$('#image_id').val()});
  //$('#main_image').click();
 });
 $('#skip').click(function() {
  $('#main_image').click();
 });
});