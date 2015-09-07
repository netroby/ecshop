<?php

/**
 * ECSHOP 商品批量上傳、修改語言文件
 * ============================================================================
 * 版權所有 2005-2011 上海商派網絡科技有限公司，並保留所有權利。
 * 網站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 這不是一個自由軟件！您只能在不用於商業目的的前提下對程序代碼進行修改和
 * 使用；不允許對程序代碼以任何形式任何目的的再發佈。
 * ============================================================================
 * $Author: liubo $
 * $Id: goods_batch.php 17217 2011-01-19 06:29:08Z liubo $
 */

$_LANG['select_method'] = '選擇商品的方式：';
$_LANG['by_cat'] = '根據商品分類、品牌';
$_LANG['by_sn'] = '根據商品貨號';
$_LANG['select_cat'] = '選擇商品分類：';
$_LANG['select_brand'] = '選擇商品品牌：';
$_LANG['goods_list'] = '商品列表：';
$_LANG['src_list'] = '待選列表：';
$_LANG['dest_list'] = '選定列表：';
$_LANG['input_sn'] = '輸入商品貨號：<br />（每行一個）';
$_LANG['edit_method'] = '編輯方式：';
$_LANG['edit_each'] = '逐個編輯';
$_LANG['edit_all'] = '統一編輯';
$_LANG['go_edit'] = '進入編輯';

$_LANG['notice_edit'] = '會員價格為-1表示會員價格將根據會員等級折扣比例計算';

$_LANG['goods_class'] = '商品類別';
$_LANG['g_class'][G_REAL] = '實體商品';
$_LANG['g_class'][G_CARD] = '虛擬卡';

$_LANG['goods_sn'] = '貨號';
$_LANG['goods_name'] = '商品名稱';
$_LANG['market_price'] = '市場價格';
$_LANG['shop_price'] = '本店價格';
$_LANG['integral'] = '積分購買';
$_LANG['give_integral'] = '贈送積分';
$_LANG['goods_number'] = '庫存';
$_LANG['brand'] = '品牌';

$_LANG['batch_edit_ok'] = '批量修改成功';

$_LANG['export_format'] = '數據格式';
$_LANG['export_ecshop'] = 'ecshop支持數據格式';
$_LANG['export_taobao'] = '淘寶助理支持數據格式';
$_LANG['export_taobao46'] = '淘寶助理4.6支持數據格式';
$_LANG['export_paipai'] = '拍拍助理支持數據格式';
$_LANG['export_paipai3'] = '拍拍助理3.0支持數據格式';
$_LANG['goods_cat'] = '所屬分類：';
$_LANG['csv_file'] = '上傳批量csv文件：';
$_LANG['notice_file'] = '（CSV文件中一次上傳商品數量最好不要超過1000，CSV文件大小最好不要超過500K.）';
$_LANG['file_charset'] = '文件編碼：';
$_LANG['download_file'] = '下載批量CSV文件（%s）';
$_LANG['use_help'] = '使用說明：' .
        '<ol>' .
          '<li>根據使用習慣，下載相應語言的csv文件，例如中國內地用戶下載簡體中文語言的文件，港台用戶下載繁體語言的文件；</li>' .
          '<li>填寫csv文件，可以使用excel或文本編輯器打開csv文件；<br />' .
              '碰到「是否精品」之類，填寫數字0或者1，0代表「否」，1代表「是」；<br />' .
              '商品圖片和商品縮略圖請填寫帶路徑的圖片文件名，其中路徑是相對於 [根目錄]/images/ 的路徑，例如圖片路徑為[根目錄]/images/200610/abc.jpg，只要填寫 200610/abc.jpg 即可；<br />' .
              '<font style="color:#FE596A;">如果是淘寶助理格式請確保cvs編碼為在網站的編碼，如編碼不正確，可以用編輯軟件轉換編碼。</font></li>' .
          '<li>將填寫的商品圖片和商品縮略圖上傳到相應目錄，例如：[根目錄]/images/200610/；</li>' .
              '<font style="color:#FE596A;">請首先上傳商品圖片和商品縮略圖再上傳csv文件，否則圖片無法處理。</font></li>' .
          '<li>選擇所上傳商品的分類以及文件編碼，上傳csv文件</li>' .
        '</ol>';

$_LANG['js_languages']['please_select_goods'] = '請您選擇商品';
$_LANG['js_languages']['please_input_sn'] = '請您輸入商品貨號';
$_LANG['js_languages']['goods_cat_not_leaf'] = '請選擇底級分類';
$_LANG['js_languages']['please_select_cat'] = '請您選擇所屬分類';
$_LANG['js_languages']['please_upload_file'] = '請您上傳批量csv文件';

// 批量上傳商品的字段
$_LANG['upload_goods']['goods_name'] = '商品名稱';
$_LANG['upload_goods']['goods_sn'] = '商品貨號';
$_LANG['upload_goods']['brand_name'] = '商品品牌';   // 需要轉換成brand_id
$_LANG['upload_goods']['market_price'] = '市場售價';
$_LANG['upload_goods']['shop_price'] = '本店售價';
$_LANG['upload_goods']['integral'] = '積分購買額度';
$_LANG['upload_goods']['original_img'] = '商品原始圖';
$_LANG['upload_goods']['goods_img'] = '商品圖片';
$_LANG['upload_goods']['goods_thumb'] = '商品縮略圖';
$_LANG['upload_goods']['keywords'] = '商品關鍵詞';
$_LANG['upload_goods']['goods_brief'] = '簡單描述';
$_LANG['upload_goods']['goods_desc'] = '詳細描述';
$_LANG['upload_goods']['goods_weight'] = '商品重量（kg）';
$_LANG['upload_goods']['goods_number'] = '庫存數量';
$_LANG['upload_goods']['warn_number'] = '庫存警告數量';
$_LANG['upload_goods']['is_best'] = '是否精品';
$_LANG['upload_goods']['is_new'] = '是否新品';
$_LANG['upload_goods']['is_hot'] = '是否熱銷';
$_LANG['upload_goods']['is_on_sale'] = '是否上架';
$_LANG['upload_goods']['is_alone_sale'] = '能否作為普通商品銷售';
$_LANG['upload_goods']['is_real'] = '是否實體商品';

$_LANG['batch_upload_ok'] = '批量上傳成功';
$_LANG['goods_upload_confirm'] = '批量上傳確認';
?>