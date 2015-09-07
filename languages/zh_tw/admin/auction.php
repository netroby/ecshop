<?php

/**
 * ECSHOP 管理中心拍賣活動語言文件
 * ============================================================================
 * 版權所有 2005-2011 上海商派網絡科技有限公司，並保留所有權利。
 * 網站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 這不是一個自由軟件！您只能在不用於商業目的的前提下對程序代碼進行修改和
 * 使用；不允許對程序代碼以任何形式任何目的的再發佈。
 * ============================================================================
 * $Author: liubo $
 * $Id: auction.php 17217 2011-01-19 06:29:08Z liubo $
 */

/* menu */
$_LANG['auction_list'] = '拍賣活動列表';
$_LANG['add_auction'] = '添加拍賣活動';
$_LANG['edit_auction'] = '編輯拍賣活動';
$_LANG['auction_log'] = '拍賣活動出價記錄';
$_LANG['continue_add_auction'] = '繼續添加拍賣活動';
$_LANG['back_auction_list'] = '返回拍賣活動列表';
$_LANG['add_auction_ok'] = '添加拍賣活動成功';
$_LANG['edit_auction_ok'] = '編輯拍賣活動成功';
$_LANG['settle_deposit_ok'] = '處理凍結的保證金成功';

/* list */
$_LANG['act_is_going'] = '僅顯示進行中的活動';
$_LANG['act_name'] = '拍賣活動名稱';
$_LANG['goods_name'] = '商品名稱';
$_LANG['start_time'] = '開始時間';
$_LANG['end_time'] = '結束時間';
$_LANG['deposit'] = '保證金';
$_LANG['start_price'] = '起拍價';
$_LANG['end_price'] = '一口價';
$_LANG['amplitude'] = '加價幅度';
$_LANG['auction_not_exist'] = '您要操作的拍賣活動不存在';
$_LANG['auction_cannot_remove'] = '該拍賣活動已經有人出價，不能刪除';
$_LANG['js_languages']['batch_drop_confirm'] = '您確實要刪除選中的拍賣活動嗎？';
$_LANG['batch_drop_ok'] = '操作完成（已經有人出價的拍賣活動不能刪除）';
$_LANG['no_record_selected'] = '沒有選擇記錄';

/* info */
$_LANG['label_act_name'] = '拍賣活動名稱：';
$_LANG['notice_act_name'] = '如果留空，取拍賣商品的名稱（該名稱僅用於後台，前台不會顯示）';
$_LANG['label_act_desc'] = '拍賣活動描述：';
$_LANG['label_search_goods'] = '根據商品編號、名稱或貨號搜索商品';
$_LANG['label_goods_name'] = '拍賣商品名稱：';
$_LANG['label_start_time'] = '拍賣開始時間：';
$_LANG['label_end_time'] = '拍賣結束時間：';
$_LANG['label_status'] = '當前狀態：';
$_LANG['label_start_price'] = '起拍價：';
$_LANG['label_end_price'] = '一口價：';
$_LANG['label_no_top'] = '無封頂';
$_LANG['label_amplitude'] = '加價幅度：';
$_LANG['label_deposit'] = '保證金：';
$_LANG['bid_user_count'] = '已有 %s 個買家出價';
$_LANG['settle_frozen_money'] = '怎樣處理買家的凍結資金？';
$_LANG['unfreeze'] = '解凍保證金';
$_LANG['deduct'] = '扣除保證金';
$_LANG['invalid_status'] = '當前狀態不正確';
$_LANG['no_deposit'] = '沒有保證金需要處理';
$_LANG['unfreeze_auction_deposit'] = '解凍拍賣活動的保證金：%s';
$_LANG['deduct_auction_deposit'] = '扣除拍賣活動的保證金：%s';

$_LANG['auction_status'][PRE_START] = '未開始';
$_LANG['auction_status'][UNDER_WAY] = '進行中';
$_LANG['auction_status'][FINISHED] = '已結束';
$_LANG['auction_status'][SETTLED] = '已結束';

$_LANG['pls_search_goods'] = '請先搜索商品';
$_LANG['search_result_empty'] = '沒有找到商品，請重新搜索';

$_LANG['pls_select_goods'] = '請選擇拍賣商品';
$_LANG['goods_not_exist'] = '您要拍賣的商品不存在';

$_LANG['js_languages']['start_price_not_number'] = '起拍價格式不正確（數字）';
$_LANG['js_languages']['end_price_not_number'] = '一口價格式不正確（數字）';
$_LANG['js_languages']['end_gt_start'] = '一口價應該大於起拍價';
$_LANG['js_languages']['amplitude_not_number'] = '加價幅度格式不正確（數字）';
$_LANG['js_languages']['deposit_not_number'] = '保證金格式不正確（數字）';
$_LANG['js_languages']['start_lt_end'] = '拍賣開始時間不能大於結束時間';
$_LANG['js_languages']['search_is_null'] = '沒有搜索到任何商品，請重新搜索';

/* log */
$_LANG['bid_user'] = '買家';
$_LANG['bid_price'] = '出價';
$_LANG['bid_time'] = '時間';
$_LANG['status'] = '狀態';

?>