$(document).ready(function () {

 //Image admin page
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
 
 
 //Merge Page
 $('.mergee').keyup(function(){
  $(this).val($.trim($(this).val()));
  if ($(this).val().length === 6) {
   var thumb_holder = $(this).parent().find('.preview').find('img');
   call('image/get',function(result){
    thumb_holder.attr('src',result.media.thumb.url);
   },{image: $(this).val()});
  }
 });
 
 $('#merge').click(function() {
  call('image/merge', function(){
   $('.mergee').val('');
   $('.image_container img').attr('src','');
  }, {image1: $('#image1').val(),image2: $('#image2').val()});
 });
 
});