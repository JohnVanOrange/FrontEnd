$(function () {
	'use strict';

	JVO.call('image/recentLikes', {count: 24}).done(function( response ) {
		_.each(response, function( image ) {
			$('#recent-likes').append( _.template($('#thumbnail').html())( {image: image} ) );
		});
	});

	JVO.call('image/recent', {count: 24}).done(function( response ) {
		_.each(response, function( image ) {
			$('#recent-uploads').append( _.template($('#thumbnail').html())( {image: image} ) );
		});
	});

});