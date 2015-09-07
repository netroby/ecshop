<?php

/**
 * ECSHOP
 * ============================================================================
 * 版權所有 2005-2011 上海商派網絡科技有限公司，並保留所有權利。
 * 網站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 這不是一個自由軟件！您只能在不用於商業目的的前提下對程序代碼進行修改和
 * 使用；不允許對程序代碼以任何形式任何目的的再發佈。
 * ============================================================================
 *
 * $Author: liubo $
 * $Id: goods_export.php 17217 2011-01-19 06:29:08Z liubo $
*/

$_LANG['user_guide'] =
        '<br/>使用说明：' .
        '<ol>' .
          '<li>使用搜索条件每次只允许搜索符合条件的50个商品。</li>' .
          '<li>如果用户需要导出某分类下的所有的商品，在条件中选择分类后，不需点击搜索，直接选择数据格式和编码导出即可。</li>' .
        '</ol>';
$_LANG['export_taobao'] = '導出淘寶助理支持數據格式';
$_LANG['export_taobao_v43'] = '導出淘寶助理V4.3支持數據格式';
$_LANG['good_cat'] = '商品分類';
$_LANG['select_please'] = '請選擇要導出的分類';
$_LANG['select_charset'] = '請選擇要導出的編碼';
$_LANG['export_taobao_v46'] = '導出淘寶助理V4.6支持數據格式';
$_LANG['goods_class'] = '寶貝欄目ID';
$_LANG['tabobao_shipping'] = '淘寶配送';
$_LANG['post_express'] = '平郵價格';
$_LANG['express'] = '快遞價格';
$_LANG['ems'] = 'EMS價格';
$_LANG['notice_goods_class'] = '寶貝欄目ID為淘寶分類的ID，如若不確定，請填寫0';

$_LANG['post_express_not_null'] = '平郵價格必須大於0';
$_LANG['express_not_null'] = '快遞價格必須大於0';
$_LANG['ems_not_null'] = 'EMS價格必須大於0';

/* 淘寶 */
$_LANG['taobao']['goods_name'] = '寶貝名稱';
$_LANG['taobao']['goods_class'] = '寶貝類目';
$_LANG['taobao']['shop_class'] = '店舖類目';
$_LANG['taobao']['new_level'] = '新舊程度';
$_LANG['taobao']['province'] = '省';
$_LANG['taobao']['city'] = '城市';
$_LANG['taobao']['sell_type'] = '出售方式';
$_LANG['taobao']['shop_price'] = '寶貝價格';
$_LANG['taobao']['add_price'] = '加價幅度';
$_LANG['taobao']['goods_number'] = '寶貝數量';
$_LANG['taobao']['die_day'] = '有效期';
$_LANG['taobao']['load_type'] = '運費承擔';
$_LANG['taobao']['post_express'] = '平郵';
$_LANG['taobao']['ems'] = 'EMS';
$_LANG['taobao']['express'] = '快遞';
$_LANG['taobao']['pay_type'] = '付款方式';
$_LANG['taobao']['allow_alipay'] = '支付寶';
$_LANG['taobao']['invoice'] = '發票';
$_LANG['taobao']['repair'] = '保修';
$_LANG['taobao']['resend'] = '自動重發';
$_LANG['taobao']['is_store'] = '放入倉庫';
$_LANG['taobao']['window'] = '櫥窗推薦';
$_LANG['taobao']['add_time'] = '發佈時間';
$_LANG['taobao']['story'] = '心情故事';
$_LANG['taobao']['goods_desc'] = '寶貝描述';
$_LANG['taobao']['goods_img'] = '寶貝圖片';
$_LANG['taobao']['goods_attr'] = '寶貝屬性';
$_LANG['taobao']['group_buy'] = '團購價';
$_LANG['taobao']['group_buy_num'] = '最小團購件數';
$_LANG['taobao']['template'] = '郵費模版ID';
$_LANG['taobao']['discount'] = '會員打折';
$_LANG['taobao']['modify_time'] = '修改時間';
$_LANG['taobao']['upload_status'] = '上傳狀態';
$_LANG['taobao']['img_status'] = '圖片狀態';

/*淘宝4.6*/
$_LANG['taobao46']['goods_name'] = '寶貝名稱';
$_LANG['taobao46']['goods_class'] = '寶貝類目';
$_LANG['taobao46']['shop_class'] = '店鋪類目';
$_LANG['taobao46']['new_level'] = '新舊程度';
$_LANG['taobao46']['province'] = '省';
$_LANG['taobao46']['city'] = '城市';
$_LANG['taobao46']['sell_type'] = '出售方式';
$_LANG['taobao46']['shop_price'] = '寶貝價格';
$_LANG['taobao46']['add_price'] = '加價幅度';
$_LANG['taobao46']['goods_number'] = '寶貝數量';
$_LANG['taobao46']['die_day'] = '有效期';
$_LANG['taobao46']['load_type'] = '運費承擔';
$_LANG['taobao46']['post_express'] = '平郵';
$_LANG['taobao46']['ems'] = 'EMS';
$_LANG['taobao46']['express'] = '快遞';
$_LANG['taobao46']['pay_type'] = '付款方式';
$_LANG['taobao46']['allow_alipay'] = '支付寶';
$_LANG['taobao46']['invoice'] = '發票';
$_LANG['taobao46']['repair'] = '保修';
$_LANG['taobao46']['resend'] = '自動重發';
$_LANG['taobao46']['is_store'] = '放入倉庫';
$_LANG['taobao46']['window'] = '櫥窗推薦';
$_LANG['taobao46']['add_time'] = '發布時間';
$_LANG['taobao46']['story'] = '心情故事';
$_LANG['taobao46']['goods_desc'] = '寶貝描述';
$_LANG['taobao46']['goods_img'] = '寶貝圖片';
$_LANG['taobao46']['goods_attr'] = '寶貝屬性';
$_LANG['taobao46']['group_buy'] = '團購價';
$_LANG['taobao46']['group_buy_num'] = '最小團購件數';
$_LANG['taobao46']['template'] = '郵費模版ID';
$_LANG['taobao46']['discount'] = '會員打折';
$_LANG['taobao46']['modify_time'] = '修改時間';
$_LANG['taobao46']['upload_status'] = '上傳狀態';
$_LANG['taobao46']['img_status'] = '圖片狀態';

$_LANG['taobao46']['rebate_proportion'] = '返點比例';
$_LANG['taobao46']['new_picture'] = '新圖片';
$_LANG['taobao46']['video'] = '視頻';
$_LANG['taobao46']['marketing_property_mix'] = '銷售屬性組合';
$_LANG['taobao46']['user_input_ID_numbers'] = '用戶輸入ID串';
$_LANG['taobao46']['input_user_name_value'] = '用戶輸入名-值對';
$_LANG['taobao46']['sellers_code'] = '商家編碼';
$_LANG['taobao46']['another_of_marketing_property'] = '銷售屬性別名';
$_LANG['taobao46']['charge_type'] = '代充類型';
$_LANG['taobao46']['treasure_number'] = '寶貝編號';
$_LANG['taobao46']['ID_number'] = '數字ID';

$_LANG['export_paipai'] = '導出到拍拍助理支持數據格式';
$_LANG['paipai']['id'] = 'id';
$_LANG['paipai']['tree_node_id'] = 'tree_node_id';
$_LANG['paipai']['old_tree_node_id'] = 'old_tree_node_id';
$_LANG['paipai']['title'] = 'title';
$_LANG['paipai']['id_in_web'] = 'id_in_web';
$_LANG['paipai']['auctionType'] = 'auctionType';
$_LANG['paipai']['category'] = 'category';
$_LANG['paipai']['shopCategoryId'] = 'shopCategoryId';
$_LANG['paipai']['pictURL'] = 'pictURL';
$_LANG['paipai']['quantity'] = 'quantity';
$_LANG['paipai']['duration'] = 'duration';
$_LANG['paipai']['startDate'] = 'startDate';
$_LANG['paipai']['stuffStatus'] = 'stuffStatus';
$_LANG['paipai']['price'] = 'price';
$_LANG['paipai']['increment'] = 'increment';
$_LANG['paipai']['prov'] = 'prov';
$_LANG['paipai']['city'] = 'city';
$_LANG['paipai']['shippingOption'] = 'shippingOption';
$_LANG['paipai']['ordinaryPostFee'] = 'ordinaryPostFee';
$_LANG['paipai']['fastPostFee'] = 'fastPostFee';
$_LANG['paipai']['paymentOption'] = 'paymentOption';
$_LANG['paipai']['haveInvoice'] = 'haveInvoice';
$_LANG['paipai']['haveGuarantee'] = 'haveGuarantee';
$_LANG['paipai']['secureTradeAgree'] = 'secureTradeAgree';
$_LANG['paipai']['autoRepost'] = 'autoRepost';
$_LANG['paipai']['shopWindow'] = 'shopWindow';
$_LANG['paipai']['failed_reason'] = 'failed_reason';
$_LANG['paipai']['pic_size'] = 'pic_size';
$_LANG['paipai']['pic_filename'] = 'pic_filename';
$_LANG['paipai']['pic'] = 'pic';
$_LANG['paipai']['description'] = 'description';
$_LANG['paipai']['story'] = 'story';
$_LANG['paipai']['putStore'] = 'putStore';
$_LANG['paipai']['pic_width'] = 'pic_width';
$_LANG['paipai']['pic_height'] = 'pic_height';
$_LANG['paipai']['skin'] = 'skin';
$_LANG['paipai']['prop'] = 'prop';

$_LANG['export_paipai4'] = '導出到拍拍助理3.0支持數據格式';
$_LANG['paipai4']['id'] = 'id';
$_LANG['paipai4']['goods_name'] = '商品名称';
$_LANG['paipai4']['auctionType'] = '出售方式';
$_LANG['paipai4']['category'] = '商品类目';
$_LANG['paipai4']['shopCategoryId'] = '店铺类目';
$_LANG['paipai4']['quantity'] = '商品数量';
$_LANG['paipai4']['duration'] = '有效期';
$_LANG['paipai4']['startDate'] = '定时上架';
$_LANG['paipai4']['stuffStatus'] = '新旧程度';
$_LANG['paipai4']['price'] = '价格';
$_LANG['paipai4']['increment'] = '加价幅度';
$_LANG['paipai4']['prov'] = '省';
$_LANG['paipai4']['city'] = '市';
$_LANG['paipai4']['shippingOption'] = '运费承担';
$_LANG['paipai4']['ordinaryPostFee'] = '平邮';
$_LANG['paipai4']['fastPostFee'] = '快递';
$_LANG['paipai4']['buyLimit'] = '购买限制';
$_LANG['paipai4']['paymentOption'] = '付款方式';
$_LANG['paipai4']['haveInvoice'] = '有发票';
$_LANG['paipai4']['haveGuarantee'] = '有保修';
$_LANG['paipai4']['secureTradeAgree'] = '支持财付通';
$_LANG['paipai4']['autoRepost'] = '自动重发';
$_LANG['paipai4']['failed_reason'] = '错误原因';
$_LANG['paipai4']['pic_filename'] = '图片';
$_LANG['paipai4']['description'] = '商品详情';
$_LANG['paipai4']['shelfOption'] = '上架选项';
$_LANG['paipai4']['skin'] = '皮肤风格';
$_LANG['paipai4']['attr'] = '属性';
$_LANG['paipai4']['chengBao'] = '诚保';
$_LANG['paipai4']['shopWindow'] = '橱窗';

// 批量上傳商品的字段
$_LANG['export_ecshop'] = '導出到ECShop數據格式';
$_LANG['ecshop']['goods_name'] = '商品名稱';
$_LANG['ecshop']['goods_sn'] = '商品貨號';
$_LANG['ecshop']['brand_name'] = '商品品牌';   // 需要轉換成brand_id
$_LANG['ecshop']['market_price'] = '市場售價';
$_LANG['ecshop']['shop_price'] = '本店售價';
$_LANG['ecshop']['integral'] = '積分購買額度';
$_LANG['ecshop']['original_img'] = '商品原始圖';
$_LANG['ecshop']['goods_img'] = '商品圖片';
$_LANG['ecshop']['goods_thumb'] = '商品縮略圖';
$_LANG['ecshop']['keywords'] = '商品關鍵詞';
$_LANG['ecshop']['goods_brief'] = '簡單描述';
$_LANG['ecshop']['goods_desc'] = '詳細描述';
$_LANG['ecshop']['goods_weight'] = '商品重量（kg）';
$_LANG['ecshop']['goods_number'] = '庫存數量';
$_LANG['ecshop']['warn_number'] = '庫存警告數量';
$_LANG['ecshop']['is_best'] = '是否精品';
$_LANG['ecshop']['is_new'] = '是否新品';
$_LANG['ecshop']['is_hot'] = '是否熱銷';
$_LANG['ecshop']['is_on_sale'] = '是否上架';
$_LANG['ecshop']['is_alone_sale'] = '能否作為普通商品銷售';
$_LANG['ecshop']['is_real'] = '是否實體商品';

//自定義導出數據格式
$_LANG['export_custom'] = '導出到自定義數據格式';
$_LANG['custom']['goods_name'] = '商品名稱';
$_LANG['custom']['goods_sn'] = '商品貨號';
$_LANG['custom']['brand_name'] = '商品品牌';
$_LANG['custom']['market_price'] = '市場售價';
$_LANG['custom']['shop_price'] = '本店售價';
$_LANG['custom']['integral'] = '積分購買額度';
$_LANG['custom']['original_img'] = '商品原始圖';
$_LANG['custom']['goods_img'] = '商品圖片';
$_LANG['custom']['goods_thumb'] = '商品縮略圖';
$_LANG['custom']['keywords'] = '商品關鍵詞';
$_LANG['custom']['goods_brief'] = '簡單描述';
$_LANG['custom']['goods_desc'] = '詳細描述';
$_LANG['custom']['goods_weight'] = '商品重量（kg）';
$_LANG['custom']['goods_number'] = '庫存數量';
$_LANG['custom']['warn_number'] = '庫存警告數量';
$_LANG['custom']['is_best'] = '是否精品';
$_LANG['custom']['is_new'] = '是否新品';
$_LANG['custom']['is_hot'] = '是否熱銷';
$_LANG['custom']['is_on_sale'] = '是否上架';
$_LANG['custom']['is_alone_sale'] = '能否作為普通商品銷售';
$_LANG['custom']['is_real'] = '是否實體商品';

$_LANG['custom_keyword'] = '關鍵字';
$_LANG['custom_goods_cat'] = '所有分類';
$_LANG['custom_goods_brand'] = '所有品牌';
$_LANG['custom_goods_list'] = '選擇商品數據列';
$_LANG['custom_goods_type'] = '所有商品類型';
$_LANG['custom_export_list'] = '輸出商品數據列';
$_LANG['custom_up'] = '上';
$_LANG['custom_down'] = '下';
$_LANG['custom_goods_search'] = '導出條件';
$_LANG['custom_goods_field_not_null'] = '輸出的商品數據列不能為空';

// 導出條件
$_LANG['export_condition'] = '商品數據批量導出';
$_LANG['export_condition_search'] = '搜 索';
$_LANG['export_format'] = '數據格式';

?>
