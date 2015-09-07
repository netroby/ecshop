/*
 * 首页
*/
require.config({
	baseUrl: '../static/scripts',
	paths: {
		'jquery': './lib/jquery-1.10.2',
		'unslider': './lib/unslider.min'
	}
});

define(['jquery','unslider'], function ($, unslider) {
	(window.chrome) && ($('.sliderwrap li').css('background-size', '100% 100%'));
	$('.sliderwrap').unslider({
		fluid: true,
		indicator: true,
		speed: 500
	});
})