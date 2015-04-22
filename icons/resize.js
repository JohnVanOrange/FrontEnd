#!/usr/bin/env node

var master = 'master/';
var sizes = 'sizes';

var fs = require('fs');
var sys = require('util');
var exec = require('child_process').exec;

var puts = function(err, stdout, stderr) {
	if (stderr) {
		throw stderr;
	}
	else {
		sys.puts('Image created');
	}
};

var images = fs.readdirSync(master);
var sizes = fs.readFileSync(sizes, 'utf8').split("\n").filter(Number);

for(var i in images) {
	var set = images[i].split('.')[0];
	fs.mkdir(set);
	for(var s in sizes) {
		exec('convert ' + master + images[i] + ' -resize ' + sizes[s] + 'x' + sizes[s] + ' ' + set + '/' + sizes[s] + '.png', puts);
	}
}
