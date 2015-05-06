$(function () {
	'use strict';

	var $likes_containter = $('#recent-likes');
	var likes_count = imageFit($likes_containter, 3);
	JVO.call('image/recentLikes', {count: likes_count}).done(function( response ) {
		addImages($likes_containter, response);
	});

	var $uploads_containter = $('#recent-uploads');
	var uploads_count = imageFit($likes_containter, 3);
	JVO.call('image/recent', {count: uploads_count}).done(function( response ) {
		addImages($uploads_containter, response);
	});

});

var addImages = function( containter, images) {
	'use strict';
	_.each(images, function( image ) {
		containter.append( _.template($('#thumbnail').html())( {image: image} ) );
	});
};

var imageFit = function( containter, rows ) {
	'use strict';
	var boxsize = containter.width();
	var per_row = Math.floor(boxsize / 130);
	return per_row * rows;
};