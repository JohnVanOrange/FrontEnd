#!/usr/bin/env node

'use strict';

var fs = require('fs');
var path = require('path');
var sys = require('util');
var exec = require('child_process').exec;

var args = process.argv;

var dir = process.cwd();
if (args.length > 2) {
 dir = dir + '/' + args[2];
}
dir = path.resolve(dir + '/');

var master = dir + '/master/';
var sizes = dir + '/sizes';

var puts = function(err, stdout, stderr) {
	if (stderr) {
		throw stderr;
	}
	else {
		sys.puts('Image created');
	}
};

var images = fs.readdirSync(master);
var sizes = fs.readFileSync(sizes, 'utf8').split('\n').filter(Number);

for(var i in images) {
	if (images.hasOwnProperty(i)) {
		var set = dir + '/' + images[i].split('.')[0];
		fs.mkdir(set);
		for(var s in sizes) {
			if (sizes.hasOwnProperty(s)) {
				exec('convert ' + master + images[i] + ' -resize ' + sizes[s] + 'x' + sizes[s] + ' ' + set + '/' + sizes[s] + '.png', puts);
			}
		}
	}
}
