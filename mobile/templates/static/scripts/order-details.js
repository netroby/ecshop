/*
 * 订单详情页
*/
require.config({
	baseUrl: '../../static/scripts',
	paths: {
		'jquery': './lib/jquery-1.10.2',
		'dialog': './lib/dialog',
		'popup': './lib/popup',
		'dialog-config': './lib/dialog-config'
	}
});

define(['jquery','dialog'], function ($, dialog) {
	var d1 = new dialog(),
		d2 = new dialog({
			'left': '100px',
			'top': '200px'
		});
		
	d1.content($('#dialog-order-success').html())
	d1.show();


	d2.content($('#dialog-received-good').html())
	d2.show();
})