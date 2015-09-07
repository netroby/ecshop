<?php

/**
 * ECSHOP 管理中心起始頁語言文件
 * ============================================================================
 * 版權所有 2005-2011 上海商派網絡科技有限公司，並保留所有權利。
 * 網站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 這不是一個自由軟件！您只能在不用於商業目的的前提下對程序代碼進行修改和
 * 使用；不允許對程序代碼以任何形式任何目的的再發佈。
 * ============================================================================
 * $Author: liubo $
 * $Id: goods.php 17217 2011-01-19 06:29:08Z liubo $
 */

$_LANG['edit_goods'] = '編輯商品信息';
$_LANG['copy_goods'] = '複製商品信息';
$_LANG['continue_add_goods'] = '繼續添加新商品';
$_LANG['back_goods_list'] = '返回商品列表';
$_LANG['add_goods_ok'] = '添加商品成功。';
$_LANG['edit_goods_ok'] = '編輯商品成功。';
$_LANG['trash_goods_ok'] = '把商品放入回收站成功。';
$_LANG['restore_goods_ok'] = '還原商品成功。';
$_LANG['drop_goods_ok'] = '刪除商品成功。';
$_LANG['batch_handle_ok'] = '批量操作成功。';
$_LANG['drop_goods_confirm'] = '您確實要刪除該商品嗎？';
$_LANG['batch_drop_confirm'] = '徹底刪除商品將刪除與該商品有關的所有信息。\n您確實要刪除選中的商品嗎？';
$_LANG['trash_goods_confirm'] = '您確實要把該商品放入回收站嗎？';
$_LANG['batch_trash_confirm'] = '您確實要把選中的商品放入回收站嗎？';
$_LANG['trash_product_confirm'] = '您確認要把該商品刪除嗎？';
$_LANG['restore_goods_confirm'] = '您確實要把該商品還原嗎？';
$_LANG['batch_restore_confirm'] = '您確實要把選中的商品還原嗎？';
$_LANG['batch_on_sale_confirm'] = '您確實要把選中的商品上架嗎？';
$_LANG['batch_not_on_sale_confirm'] = '您確實要把選中的商品下架嗎？';
$_LANG['batch_best_confirm'] = '您確實要把選中的商品設為精品嗎？';
$_LANG['batch_not_best_confirm'] = '您確實要把選中的商品取消精品嗎？';
$_LANG['batch_new_confirm'] = '您確實要把選中的商品設為新品嗎？';
$_LANG['batch_not_new_confirm'] = '您確實要把選中的商品取消新品嗎？';
$_LANG['batch_hot_confirm'] = '您確實要把選中的商品設為熱銷嗎？';
$_LANG['batch_not_hot_confirm'] = '您確實要把選中的商品取消熱銷嗎？';
$_LANG['cannot_found_goods'] = '找不到指定的商品。';
$_LANG['sel_goods_type'] = '請選擇商品類型';
$_LANG['sel_goods_suppliers'] = '請選擇供貨商';
/*------------------------------------------------------ */
//-- 圖片處理相關提示信息
/*------------------------------------------------------ */
$_LANG['no_gd'] = '您的服務器不支持 GD 或者沒有安裝處理該圖片類型的擴展庫。';
$_LANG['img_not_exists'] = '沒有找到原始圖片，創建縮略圖失敗。';
$_LANG['img_invalid'] = '創建縮略圖失敗，因為您上傳了一個無效的圖片文件。';
$_LANG['create_dir_failed'] = 'images 文件夾不可寫，創建縮略圖失敗。';
$_LANG['safe_mode_warning'] = '您的服務器運行在安全模式下，而且 %s 目錄不存在。您可能需要先行創建該目錄才能上傳圖片。';
$_LANG['not_writable_warning'] = '目錄 %s 不可寫，您需要把該目錄設為可寫才能上傳圖片。';

/*------------------------------------------------------ */
//-- 商品列表
/*------------------------------------------------------ */
$_LANG['goods_cat'] = '所有分類';
$_LANG['goods_brand'] = '所有品牌';
$_LANG['intro_type'] = '全部';
$_LANG['keyword'] = '關鍵字';
$_LANG['is_best'] = '精品';
$_LANG['is_new'] = '新品';
$_LANG['is_hot'] = '熱銷';
$_LANG['is_promote'] = '特價';
$_LANG['all_type'] = '全部推薦';
$_LANG['sort_order'] = '推薦排序';

$_LANG['goods_name'] = '商品名稱';
$_LANG['goods_sn'] = '貨號';
$_LANG['shop_price'] = '價格';
$_LANG['is_on_sale'] = '上架';
$_LANG['goods_number'] = '庫存';

$_LANG['copy'] = '複製';
$_LANG['item_list'] = '貨品列表';

$_LANG['integral'] = '積分額度';
$_LANG['on_sale'] = '上架';
$_LANG['not_on_sale'] = '下架';
$_LANG['best'] = '精品';
$_LANG['not_best'] = '取消精品';
$_LANG['new'] = '新品';
$_LANG['not_new'] = '取消新品';
$_LANG['hot'] = '熱銷';
$_LANG['not_hot'] = '取消熱銷';
$_LANG['move_to'] = '轉移到分類';

// ajax
$_LANG['goods_name_null'] = '請輸入商品名稱';
$_LANG['goods_sn_null'] = '請輸入貨號';
$_LANG['shop_price_not_number'] = '價格不是數字';
$_LANG['shop_price_invalid'] = '您輸入了一個非法的市場價格。';
$_LANG['goods_sn_exists'] = '您輸入的貨號已存在，請換一個';

/*------------------------------------------------------ */
//-- 添加/編輯商品信息
/*------------------------------------------------------ */
$_LANG['tab_general'] = '通用信息';
$_LANG['tab_detail'] = '詳細描述';
$_LANG['tab_mix'] = '其他信息';
$_LANG['tab_properties'] = '商品屬性';
$_LANG['tab_gallery'] = '商品相冊';
$_LANG['tab_linkgoods'] = '關聯商品';
$_LANG['tab_groupgoods'] = '配件';
$_LANG['tab_article'] = '關聯文章';

$_LANG['lab_goods_name'] = '商品名稱：';
$_LANG['lab_goods_sn'] = '商品貨號：';
$_LANG['lab_goods_cat'] = '商品分類：';
$_LANG['lab_other_cat'] = '擴展分類：';
$_LANG['lab_goods_brand'] = '商品品牌：';
$_LANG['lab_shop_price'] = '本店售價：';
$_LANG['lab_market_price'] = '市場售價：';
$_LANG['lab_user_price'] = '會員價格：';
$_LANG['lab_promote_price'] = '促銷價：';
$_LANG['lab_promote_date'] = '促銷日期：';
$_LANG['lab_picture'] = '上傳商品圖片：';
$_LANG['lab_thumb'] = '上傳商品縮略圖：';
$_LANG['auto_thumb'] = '自動生成商品縮略圖';
$_LANG['lab_keywords'] = '商品關鍵詞：';
$_LANG['lab_goods_brief'] = '商品簡單描述：';
$_LANG['lab_seller_note'] = '商家備註：';
$_LANG['lab_goods_type'] = '商品類型：';
$_LANG['lab_picture_url'] = '商品圖片外部URL';
$_LANG['lab_thumb_url'] = '商品縮略圖外部URL';

$_LANG['lab_goods_weight'] = '商品重量：';
$_LANG['unit_g'] = '克';
$_LANG['unit_kg'] = '千克';
$_LANG['lab_goods_number'] = '商品庫存數量：';
$_LANG['lab_warn_number'] = '庫存警告數量：';
$_LANG['lab_integral'] = '積分購買金額：';
$_LANG['lab_give_integral'] = '贈送消費積分數：';
$_LANG['lab_rank_integral'] = '贈送等級積分數：';
$_LANG['lab_intro'] = '加入推薦：';
$_LANG['lab_is_on_sale'] = '上架：';
$_LANG['lab_is_alone_sale'] = '能作為普通商品銷售：';
$_LANG['lab_is_free_shipping'] = '是否為免運費商品：';

$_LANG['compute_by_mp'] = '按市場價計算';

$_LANG['notice_goods_sn'] = '如果您不輸入商品貨號，系統將自動生成一個唯一的貨號。';
$_LANG['notice_integral'] = '（此處需填寫金額）購買該商品時最多可以使用積分的金額';
$_LANG['notice_give_integral'] = '購買該商品時贈送消費積分數,-1表示按商品價格贈送';
$_LANG['notice_rank_integral'] = '購買該商品時贈送等級積分數,-1表示按商品價格贈送';
$_LANG['notice_seller_note'] = '僅供商家自己看的信息';
$_LANG['notice_storage'] = '庫存在商品為虛貨或商品存在貨品時為不可編輯狀態，庫存數只取決于其虛貨數量或貨品數量';
$_LANG['notice_keywords'] = '用空格分隔';
$_LANG['notice_user_price'] = '會員價格為-1時表示會員價格按會員等級折扣率計算。你也可以為每個等級指定一個固定價格';
$_LANG['notice_goods_type'] = '請選擇商品的所屬類型，進而完善此商品的屬性';

$_LANG['on_sale_desc'] = '打勾表示允許銷售，否則不允許銷售。';
$_LANG['alone_sale'] = '打勾表示能作為普通商品銷售，否則只能作為配件或贈品銷售。';
$_LANG['free_shipping'] = '打鉤表示此商品不會產生運費花銷，否則按照正常運費計算。';

$_LANG['invalid_goods_img'] = '商品圖片格式不正確！';
$_LANG['invalid_goods_thumb'] = '商品縮略圖格式不正確！';
$_LANG['invalid_img_url'] = '商品相冊中第%s個圖片格式不正確!';

$_LANG['goods_img_too_big'] = '商品圖片文件太大了（最大值：%s），無法上傳。';
$_LANG['goods_thumb_too_big'] = '商品縮略圖文件太大了（最大值：%s），無法上傳。';
$_LANG['img_url_too_big'] = '商品相冊中第%s個圖片文件太大了（最大值：%s），無法上傳。';

$_LANG['integral_market_price'] = '取整數';
$_LANG['upload_images'] = '上傳圖片';
$_LANG['spec_price'] = '屬性價格';
$_LANG['drop_img_confirm'] = '您確實要刪除該圖片嗎？';

$_LANG['select_font'] = '字體樣式';
$_LANG['font_styles'] = array('strong' => '加粗', 'em' => '斜體', 'u' => '下劃線', 'strike' => '刪除線');

$_LANG['rapid_add_cat'] = '添加分類';
$_LANG['rapid_add_brand'] = '添加品牌';
$_LANG['category_manage'] = '分類管理';
$_LANG['brand_manage'] = '品牌管理';
$_LANG['hide'] = '隱藏';

$_LANG['lab_volume_price'] = '商品優惠價格：';
$_LANG['volume_number'] = '優惠數量';
$_LANG['volume_price'] = '優惠價格';
$_LANG['notice_volume_price'] = '購買數量達到優惠數量時享受的優惠價格';
$_LANG['volume_number_continuous'] = '優惠數量重複！';

$_LANG['label_suppliers']          = '選擇供貨商：';
$_LANG['suppliers_no']             = '不指定供貨商屬于本店商品';
$_LANG['suppliers_move_to']        = '轉移到供貨商';
$_LANG['lab_to_shopex']         = '轉移到網店';

/*------------------------------------------------------ */
//-- 關聯商品
/*------------------------------------------------------ */

$_LANG['all_goods'] = '可選商品';
$_LANG['link_goods'] = '跟該商品關聯的商品';
$_LANG['single'] = '單向關聯';
$_LANG['double'] = '雙向關聯';
$_LANG['all_article'] = '可選文章';
$_LANG['goods_article'] = '跟該商品關聯的文章';
$_LANG['top_cat'] = '頂級分類';

/*------------------------------------------------------ */
//-- 組合商品
/*------------------------------------------------------ */

$_LANG['group_goods'] = '該商品的配件';
$_LANG['price'] = '價格';

/*------------------------------------------------------ */
//-- 商品相冊
/*------------------------------------------------------ */

$_LANG['img_desc'] = '圖片描述';
$_LANG['img_url'] = '上傳文件';
$_LANG['img_file'] = '或者輸入外部圖片鏈接地址';

/*------------------------------------------------------ */
//-- 關聯文章
/*------------------------------------------------------ */
$_LANG['article_title'] = '文章標題';

$_LANG['goods_not_exist'] = '該商品不存在';
$_LANG['goods_not_in_recycle_bin'] = '該商品尚未放入回收站，不能刪除';

$_LANG['js_languages']['goods_name_not_null'] = '商品名稱不能為空。';
$_LANG['js_languages']['goods_cat_not_null'] = '商品分類必須選擇。';
$_LANG['js_languages']['category_cat_not_null'] = '分類名稱不能為空';
$_LANG['js_languages']['brand_cat_not_null'] = '品牌名稱不能為空';
$_LANG['js_languages']['goods_cat_not_leaf'] = '您選擇的商品分類不是底級分類，請選擇底級分類。';
$_LANG['js_languages']['shop_price_not_null'] = '本店售價不能為空。';
$_LANG['js_languages']['shop_price_not_number'] = '本店售價不是數值。';

$_LANG['js_languages']['select_please'] = '請選擇...';
$_LANG['js_languages']['button_add'] = '添加';
$_LANG['js_languages']['button_del'] = '刪除';
$_LANG['js_languages']['spec_value_not_null'] = '規格不能為空';
$_LANG['js_languages']['spec_price_not_number'] = '加價不是數字';
$_LANG['js_languages']['market_price_not_number'] = '市場價格不是數字';
$_LANG['js_languages']['goods_number_not_int'] = '商品庫存不是整數';
$_LANG['js_languages']['warn_number_not_int'] = '庫存警告不是整數';
$_LANG['js_languages']['promote_not_lt'] = '促銷開始日期不能大於結束日期';
$_LANG['js_languages']['promote_start_not_null'] = '促銷開始時間不能為空';
$_LANG['js_languages']['promote_end_not_null'] = '促銷結束時間不能為空';

$_LANG['js_languages']['drop_img_confirm'] = '您確實要刪除該圖片嗎？';
$_LANG['js_languages']['batch_no_on_sale'] = '您確實要將選定的商品下架嗎？';
$_LANG['js_languages']['batch_trash_confirm'] = '您確實要把選中的商品放入回收站嗎？';
$_LANG['js_languages']['go_category_page'] = '本頁數據將丟失，確認要去商品分類頁添加分類嗎？';
$_LANG['js_languages']['go_brand_page'] = '本頁數據將丟失，確認要去商品品牌頁添加品牌嗎？';

$_LANG['js_languages']['volume_num_not_null'] = '請輸入優惠數量';
$_LANG['js_languages']['volume_num_not_number'] = '優惠數量不是數字';
$_LANG['js_languages']['volume_price_not_null'] = '請輸入優惠價格';
$_LANG['js_languages']['volume_price_not_number'] = '優惠價格不是數字';

$_LANG['js_languages']['cancel_color'] = '無樣式';

/* 虛擬卡 */
$_LANG['card'] = '查看虛擬卡信息';
$_LANG['replenish'] = '補貨';
$_LANG['batch_card_add'] = '批量補貨';
$_LANG['add_replenish'] = '添加虛擬卡卡密';

$_LANG['goods_number_error'] = '商品庫存數量錯誤';

/*------------------------------------------------------ */
//-- 貨品
/*------------------------------------------------------ */
$_LANG['product'] = '貨品';
$_LANG['product_info'] = '貨品信息';
$_LANG['specifications'] = '規格';
$_LANG['total'] = '合計：';
$_LANG['add_products'] = '添加貨品';
$_LANG['save_products'] = '保存貨品成功';
$_LANG['product_id_null'] = '貨品id為空';
$_LANG['cannot_found_products'] = '未找到指定貨品';
$_LANG['product_batch_del_success'] = '貨品批量刪除成功';
$_LANG['product_batch_del_failure'] = '貨品批量刪除失敗';
$_LANG['batch_product_add'] = '批量添加';
$_LANG['batch_product_edit'] = '批量編輯';
$_LANG['products_title'] = '商品名稱：%s';
$_LANG['products_title_2'] = '貨品：%s';
$_LANG['good_shop_price'] = '（商品價格：%d）';
$_LANG['good_goods_sn'] = '（商品貨號：%s）';
$_LANG['exist_same_goods_sn'] = '貨品貨號不允許與產品貨號重復';
$_LANG['exist_same_product_sn'] = '貨品貨號重復';
$_LANG['cannot_add_products'] = '貨品添加失敗';
$_LANG['exist_same_goods_attr'] = '貨品規格屬性重復';
$_LANG['cannot_goods_number'] = '此商品存在貨品，不能修改商品庫存';
$_LANG['not_exist_goods_attr'] = '此商品不存在規格，請為其添加規格';
$_LANG['goods_sn_exists'] = '您输入的货号已存在，请换一个';
?>