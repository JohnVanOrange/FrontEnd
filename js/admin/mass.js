var add_image = function() {
	var rand = Math.floor((Math.random()*10000)+1);
	JVO.call('image/unapproved', {rand: rand})
		.done(function( data ){
			var content = '<div class="thumb_wrap col-md-1" id="' + data.uid + '">' +
										'<a href="/' + data.uid + '"><img src="' + data.media.thumb.url + '"></a>' +
										'<div>' +
										'<button class="btn btn-success btn-xs approve icon-thumbs-up" value="' + data.uid + '"></button>' +
										'<button class="btn btn-warning btn-xs nsfw" value="' + data.uid + '">!</button>' +
										'<button class="btn btn-default btn-xs skip" value="' + data.uid + '">X</button>' +
										'<button class="btn btn-danger btn-xs reject icon-thumbs-down" value="' + data.uid + '"></button>' +
										'</div>' +
										'</div>';
			$('#content').append(content);
			$('#' + data.uid + ' .approve').click(function() {
				approve(data.uid, 0);
			});
			$('#' + data.uid + ' .nsfw').click(function() {
				approve(data.uid, 1);
			});
			$('#' + data.uid + ' .skip').click(function() {
				skip(data.uid);
			});
			$('#' + data.uid + ' .reject').click(function() {
				reject(data.uid);
			});
		});
}

var approve = function(uid, nsfw){
	JVO.call('image/approve', {image: uid, nsfw: nsfw})
		.done(function(){
			$('#' + uid).fadeOut();
			add_image();
		});
}

var skip = function(uid) {
	$('#' + uid).fadeOut();
	add_image();
}

var reject = function(uid) {
	if (confirm('Remove Image?')) {
		JVO.call('image/remove', {image: uid})
			.done(function(){
				$('#' + uid).fadeOut();
				add_image();
			});
	}
}

$(document).ready(function () {
 for (var i = 1; i <= 24; i++) {
	add_image();
 }


});
