/*
 * 对话框
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
		d2 = new dialog({'align': 'right bottom'});

	d1.content($('#dialog-share-to').html())
	d1.show();

	d2.content($('#dialog-share-success').html())
	d2.show();
})