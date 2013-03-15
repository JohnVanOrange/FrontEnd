$(document).ready(function () {
 $('#approve').click(function() {
  call('image/approve',function(){$('.image').click();},{image: $('.image').attr('id')});
 });
 $('#nsfw').click(function() {
  call('image/approve',function(){$('.image').click();},{image: $('.image').attr('id'),nsfw: true});
 });
 $('#reject').click(function() {
  call('image/remove',function(){$('.image').click();},{image: $('.image').attr('id')});
 });
 $('#skip').click(function() {
  $('.image').click();
 });
});