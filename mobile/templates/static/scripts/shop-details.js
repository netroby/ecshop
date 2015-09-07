/*
 * 商品详情页
*/
require.config({
	baseUrl: '../../static/scripts',
	paths: {
		'jquery': './lib/jquery-1.10.2',
		'dialog': './lib/dialog',
		'popup': './lib/popup',
		'dialog-config': './lib/dialog-config',
		'unslider': './lib/unslider.min'
	}
});

define(['jquery','dialog', 'unslider'], function ($, dialog, unslider) {
	/*var d1 = new dialog();
		
	d1.content($('#dialog-goods-short').html())
	d1.show(); */


	(window.chrome) && ($('.img li').css('background-size', '100% 100%'));
	$('.img').unslider({
		fluid: true,
		indicator: true,
		speed: 500
	});


})