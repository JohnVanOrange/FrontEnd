$(document).ready(function () {

 $('#cat').click(function() {
  call('tag/add',function(){$('.image').click();},{image: $('.image').attr('id'), name: 'cat'});
 });
 $('#dog').click(function() {
  call('tag/add',function(){$('.image').click();},{image: $('.image').attr('id'), name: 'dog'});
 });
 $('#skip').click(function() {
  $('.image').click();
 });
 
});