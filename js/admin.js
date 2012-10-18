$(document).ready(function () {
 $('#approve').click(function() {
  call('image/approve',{image: $('.image').attr('id')});
  $('.image').click();
 });
 $('#nsfw').click(function() {
  call('image/approve',{image: $('.image').attr('id'),nsfw: true});
  $('.image').click();
 });
 $('#reject').click(function() {
  call('image/remove',{image: $('.image').attr('id')});
  $('.image').click();
 });
 $('#skip').click(function() {
  $('.image').click();
 });
});