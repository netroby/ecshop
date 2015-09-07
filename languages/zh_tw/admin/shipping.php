<?php

/**
 * ECSHOP 管理中心配送方式管理語言文件
 * ============================================================================
 * 版權所有 2005-2011 上海商派網絡科技有限公司，並保留所有權利。
 * 網站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 這不是一個自由軟件！您只能在不用於商業目的的前提下對程序代碼進行修改和
 * 使用；不允許對程序代碼以任何形式任何目的的再發佈。
 * ============================================================================
 * $Author: liubo $
 * $Id: shipping.php 17217 2011-01-19 06:29:08Z liubo $
*/

$_LANG['shipping_name'] = '配送方式名稱';
$_LANG['shipping_version'] = '插件版本';
$_LANG['shipping_desc'] = '配送方式描述';
$_LANG['shipping_author'] = '插件作者';
$_LANG['insure'] = '保價費用';
$_LANG['support_cod'] = '貨到付款？';
$_LANG['shipping_area'] = '設置區域';
$_LANG['shipping_print_edit'] = '編輯打印模板';
$_LANG['shipping_print_template'] = '快遞單模板';
$_LANG['shipping_template_info'] = '訂單模板變量說明:<br/>{$shop_name}表示網店名稱<br/>{$province}表示網店所屬省份<br/>{$city}表示網店所屬城市<br/>{$shop_address}表示網店地址<br/>{$service_phone}表示網店聯繫電話<br/>{$order.order_amount}表示訂單金額<br/>{$order.region}表示收件人地區<br/>{$order.tel}表示收件人電話<br/>{$order.mobile}表示收件人手機<br/>{$order.zipcode}表示收件人郵編<br/>{$order.address}表示收件人詳細地址<br/>{$order.consignee}表示收件人名稱<br/>{$order.order_sn}表示訂單號';

/* 表單部分 */
$_LANG['shipping_install'] = '安裝配送方式';
$_LANG['install_succeess'] = '配送方式 %s 安裝成功！';
$_LANG['del_lable'] = '刪除標簽';
$_LANG['upload_shipping_bg'] = '上傳打印單圖片';
$_LANG['del_shipping_bg'] = '刪除打印單圖片';
$_LANG['save_setting'] = '保存設置';
$_LANG['recovery_default'] = '恢復默認';

/* 快递单部分 */
$_LANG['lable_select_notice'] = '--選擇插入標簽--';
$_LANG['lable_box']['shop_country'] = '網店-國家';
$_LANG['lable_box']['shop_province'] = '網店-省份';
$_LANG['lable_box']['shop_city'] = '網店-城市';
$_LANG['lable_box']['shop_name'] = '網店-名稱';
$_LANG['lable_box']['shop_district'] = '網店-區/縣';
$_LANG['lable_box']['shop_tel'] = '網店-聯系電話';
$_LANG['lable_box']['shop_address'] = '網店-地址';
$_LANG['lable_box']['customer_country'] = '收件人-國家';
$_LANG['lable_box']['customer_province'] = '收件人-省份';
$_LANG['lable_box']['customer_city'] = '收件人-城市';
$_LANG['lable_box']['customer_district'] = '收件人-區/縣';
$_LANG['lable_box']['customer_tel'] = '收件人-電話';
$_LANG['lable_box']['customer_mobel'] = '收件人-手機';
$_LANG['lable_box']['customer_post'] = '收件人-郵編';
$_LANG['lable_box']['customer_address'] = '收件人-詳細地址';
$_LANG['lable_box']['customer_name'] = '收件人-姓名';
$_LANG['lable_box']['year'] = '年-當日日期';
$_LANG['lable_box']['months'] = '月-當日日期';
$_LANG['lable_box']['day'] = '日-當日日期';
$_LANG['lable_box']['order_no'] = '訂單號-訂單';
$_LANG['lable_box']['order_postscript'] = '備注-訂單';
$_LANG['lable_box']['order_best_time'] = '發貨時間-訂單';
$_LANG['lable_box']['pigeon'] = '√-對號';
//$_LANG['lable_box']['custom_content'] = '自定義內容';

/* 提示信息 */
$_LANG['no_shipping_name'] = '對不起，配送方式名稱不能為空。';
$_LANG['no_shipping_desc'] = '對不起，配送方式描述內容不能為空。';
$_LANG['repeat_shipping_name'] = '對不起，已經存在一個同名的配送方式。';
$_LANG['uninstall_success'] = '配送方式 %s 已經成功卸載。';
$_LANG['add_shipping_area'] = '為該配送方式新建配送區域';
$_LANG['no_shipping_insure'] = '對不起，保價費用不能為空，不想使用請將其設置為0';
$_LANG['not_support_insure'] = '該配送方式不支持保價,保價費用設置失敗';
$_LANG['invalid_insure'] = '配送保價費用不是一個合法價格';
$_LANG['no_shipping_install'] = '您的配送方式尚未安裝，暫不能編輯模板';
$_LANG['edit_template_success'] = '快遞模板已經成功編輯。';

/* JS 語言 */
$_LANG['js_languages']['lang_removeconfirm'] = '您確定要卸載該配送方式嗎？';
$_LANG['js_languages']['shipping_area'] = '設置區域';
$_LANG['js_languages']['upload_falid'] = '錯誤：文件類型不正確。請上傳“%s”類型的文件！';
$_LANG['js_languages']['upload_del_falid'] = '錯誤：刪除失敗！';
$_LANG['js_languages']['upload_del_confirm'] = "提示：您確認刪除打印單圖片嗎？";
$_LANG['js_languages']['no_select_upload'] = "錯誤：您還沒有選擇打印單圖片。請使用“瀏覽...”按鈕選擇！";
$_LANG['js_languages']['no_select_lable'] = "操作終止！您未選擇任何標簽。";
$_LANG['js_languages']['no_add_repeat_lable'] = "操作失敗！不允許添加重復標簽。";
$_LANG['js_languages']['no_select_lable_del'] = "刪除失敗！您沒有選中任何標簽。";
$_LANG['js_languages']['recovery_default_suer'] = "您確認恢復默認嗎？恢復默認后將顯示安裝時的內容。";
?>