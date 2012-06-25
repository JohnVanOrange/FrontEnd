$(document).ready(function() {
 $('body').imagefit();
 $.noty.defaultOptions.layout = 'topRight';
 $.noty.defaultOptions.type = 'information';
 $.noty.defaultOptions.timeout = 10000;
 var History = window.History;
 History.Adapter.bind(window,'statechange',function(){
  var State = History.getState();
 });
 $('#search form').submit(function(event){
  event.preventDefault();
  taginfo = call('tag/get',{'value':$('#tagsearch').val(),'search_by':'basename'});
  window.location.href = taginfo[0].url; 
 });
 $('#report').click(function(event) {
  event.preventDefault();
  $('#report_dialog').dialog({
   title: 'Report Image',
   buttons: {
    'Report': function() {
     call('image/report',{
      'id': $('#image_id').val(),
      'type': $('#report_dialog input[type=radio]:checked').val()
     });
     $(this).dialog('close');
    }
   },
  });
 });
 $('#upload').click(function(event) {
  event.preventDefault();
  $('#addimage_dialog').dialog({
   title: 'Add Images',
   width: 500,
   buttons: {
    'Select from Computer' : function() {
     window.location.href='/upload';
    },
    'Add from URL': function() {
     call('image/addFromURL',{
      'url': $('#url').val()
     });
     $(this).dialog('close');
    }
   },
  });
 });
 $('#add_tag').click(function(event) {
  event.preventDefault();
  addtag = function() {
   result = call('tag/add',{
    'name': $('#tag_name').val(),
    'image' : $('#uid').val()
   });
   tagtext = '';
   for(i in result.tags) {
    tagtext = tagtext+'<a href="'+result.tags[i].url+'">'+result.tags[i].name+'</a> ';
   }
   $('#tagtext').html(tagtext);   
  };
  $('#tag_name').bind('keydown', function(event) {
   if(event.keyCode===13){
    event.preventDefault();
    addtag();
    $('#tag_dialog').dialog('close');
   }
  });
  $('#tag_dialog').dialog({
   title: 'Add Tags',
   width: 350,
   buttons: {
    'Add': function() {
     addtag();
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
  History.pushState({state:1},'Brazzified','/b/'+$('#uid').val());
 });
 $(window).bind("statechange", function() {
  state = History.getState();
  if (state.data.state == 1) {brazzify();} else {normal();}
  if (state.data.state == 1) {brazzify();} else {normal();}
 });
 $('#tag_name').autocomplete({
  source: '/api/tag/suggest',
  minLength: 2
 });
 $('#tagsearch').autocomplete({
  source: '/api/tag/suggest',
  minLength: 2
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

function call(method, opt) {
 try {
  result = api.call(method, opt);
  if (result.message) {
   message = result.message;
   if (result.url) message = '<a href="'+result.url+'">'+message+'</a>';
   noty({text:message});
  }
  return result;
 }
 catch(e) {
  exception_handler(e);
 }
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
 if (response.error) {
  throw {name:response.error, message:response.message};
 }
 return response;
 },
 call : function(method, opt) {
  return this.client(method, opt);
 }
};

function exception_handler(e) {
 noty({text:e.message,type:'error'});
 switch(e.name) {
  case 1000: //Missing URL
  break;
 }
}
