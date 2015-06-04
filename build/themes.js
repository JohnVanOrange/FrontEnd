#!/usr/bin/env node

var less = require('less');
var fs = require('fs');
var path = require('path');

var args = process.argv;

var less_dir = process.cwd();
if (args.length > 2) {
 less_dir = less_dir + '/' + args[2];
}
less_dir = path.resolve(less_dir + '/');
var css_dir = path.resolve(less_dir + "/../css/");

var find_less_files = function( less_dir ) {
	var list = fs.readdirSync( less_dir );
	var lessfiles = [];
	for(var i in list) {
		if (list.hasOwnProperty(i)) {
			var a = list[i].split('.');
			var ext = a[a.length - 1];
			var base = a[0];
			if (ext === 'less') {
				lessfiles.push(base);
			}
		}
	}
	return lessfiles;
}

var themes = require( less_dir + '/themes.json' ).themes;
var less_files = find_less_files( less_dir );

for ( var t in themes ) {
	if (themes.hasOwnProperty(t)) {
		var css_theme_dir = path.resolve(css_dir + "/" + themes[t]);
		if (!fs.existsSync(css_theme_dir)){
			fs.mkdirSync(css_theme_dir);
		}
		for ( var f in less_files ) {
			//read in each less file
			var less_filename = less_dir + "/" + less_files[f] + ".less";
			var css_filename = css_theme_dir + "/" + less_files[f] + ".css";
			var sourcemap_filename = css_filename + ".map";
			var render = function(less_filename, css_filename, sourcemap_filename) {
				//process the less
				var sourceMapBasepath = path.dirname( less_filename );
				var sourceMapURL = path.basename( sourcemap_filename );
				less.render( fs.readFileSync( less_filename, "utf8" ), {
					filename: path.resolve( less_filename ),
					sourceMap: {
						sourceMapBasepath: sourceMapBasepath,
						sourceMapURL: sourceMapURL
					},
					compress: true,
					globalVars: {
						theme: themes[t]
					},
				}, function( err, output ) {
					if (err) {
						console.log( "ERR: " + err.message );
					} else {
						//write out the files
						fs.writeFileSync( css_filename, output.css);
						fs.writeFileSync( sourcemap_filename, output.map);
						console.log( css_filename + " generated.");
					}
				});
			};
			render(less_filename, css_filename, sourcemap_filename);
		}
	}
}