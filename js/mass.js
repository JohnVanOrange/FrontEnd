var add_image = function() {
	var rand = Math.floor((Math.random()*10000)+1);
	call('image/unapproved', function(data){
		content = '<div class="thumb_wrap" id="' + data.uid + '">' + 
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
	}, {rand: rand})
}

var approve = function(uid, nsfw){
	call('image/approve', function(){
		$('#' + uid).fadeOut();
		add_image();
	}, {image: uid, nsfw: nsfw});
}

var skip = function(uid) {
	$('#' + uid).fadeOut();
	add_image();
}

var reject = function(uid) {
	if (confirm('Remove Image?')) {
		call('image/remove', function(){
			$('#' + uid).fadeOut();
			add_image();
		}, {image: uid});
	}
}

$(document).ready(function () {
 for (var i = 1; i <= 12; i++) {
	add_image();
 }
 
 
});