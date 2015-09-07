<?php

/**
 * ECSHOP 管理中心團購商品語言文件
 * ============================================================================
 * 版權所有 2005-2011 上海商派網絡科技有限公司，並保留所有權利。
 * 網站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 這不是一個自由軟件！您只能在不用於商業目的的前提下對程序代碼進行修改和
 * 使用；不允許對程序代碼以任何形式任何目的的再發佈。
 * ============================================================================
 * $Author: liubo $
 * $Id: group_buy.php 17217 2011-01-19 06:29:08Z liubo $
*/

/* 當前頁面標題及可用鏈接名稱 */
$_LANG['group_buy_list'] = '團購活動列表';
$_LANG['add_group_buy'] = '添加團購活動';
$_LANG['edit_group_buy'] = '編輯團購活動';

/* 活動列表頁 */
$_LANG['goods_name'] = '商品名稱';
$_LANG['start_date'] = '開始時間';
$_LANG['end_date'] = '結束時間';
$_LANG['deposit'] = '保證金';
$_LANG['restrict_amount'] = '限購';
$_LANG['gift_integral'] = '贈送積分';
$_LANG['valid_order'] = '訂單';
$_LANG['valid_goods'] = '訂購商品';
$_LANG['current_price'] = '當前價格';
$_LANG['current_status'] = '狀態';
$_LANG['view_order'] = '查看訂單';

/* 添加/編輯活動頁 */
$_LANG['goods_cat'] = '商品分類';
$_LANG['all_cat'] = '所有分類';
$_LANG['goods_brand'] = '商品品牌';
$_LANG['all_brand'] = '所有品牌';

$_LANG['label_goods_name'] = '團購商品：';
$_LANG['notice_goods_name'] = '請先搜索商品,在此生成選項列表...';
$_LANG['label_start_date'] = '活動開始時間：';
$_LANG['label_end_date'] = '活動結束時間：';
$_LANG['notice_datetime'] = '（年月日－時）';
$_LANG['label_deposit'] = '保證金：';
$_LANG['label_restrict_amount'] = '限購數量：';
$_LANG['notice_restrict_amount']= '達到此數量，團購活動自動結束。0表示沒有數量限制。';
$_LANG['label_gift_integral'] = '贈送積分數：';
$_LANG['label_price_ladder'] = '價格階梯：';
$_LANG['notice_ladder_amount'] = '數量達到';
$_LANG['notice_ladder_price'] = '享受價格';
$_LANG['label_desc'] = '活動說明：';
$_LANG['label_status'] = '活動當前狀態：';
$_LANG['gbs'][GBS_PRE_START] = '未開始';
$_LANG['gbs'][GBS_UNDER_WAY] = '進行中';
$_LANG['gbs'][GBS_FINISHED] = '結束未處理';
$_LANG['gbs'][GBS_SUCCEED] = '成功結束';
$_LANG['gbs'][GBS_FAIL] = '失敗結束';
$_LANG['label_order_qty'] = '訂單數 / 有效訂單數：';
$_LANG['label_goods_qty'] = '商品數 / 有效商品數：';
$_LANG['label_cur_price'] = '當前價：';
$_LANG['label_end_price'] = '最終價：';
$_LANG['label_handler'] = '操作：';
$_LANG['error_group_buy'] = '您要操作的團購活動不存在';
$_LANG['error_status'] = '當前狀態不能執行該操作！';
$_LANG['button_finish'] = '結束活動';
$_LANG['notice_finish'] = '（修改活動結束時間為當前時間）';
$_LANG['button_succeed'] = '活動成功';
$_LANG['notice_succeed'] = '（更新訂單價格）';
$_LANG['button_fail'] = '活動失敗';
$_LANG['notice_fail'] = '（取消訂單，保證金退回帳戶餘額，失敗原因可以寫到活動說明中）';
$_LANG['cancel_order_reason'] = '團購失敗';
$_LANG['js_languages']['succeed_confirm'] = '此操作不可逆，您確定要設置該團購活動成功嗎？';
$_LANG['js_languages']['fail_confirm'] = '此操作不可逆，您確定要設置該團購活動失敗嗎？';
$_LANG['button_mail'] = '發送郵件';
$_LANG['notice_mail'] = '（通知客戶付清餘款，以便發貨）';
$_LANG['mail_result'] = '該團購活動共有 %s 個有效訂單，成功發送了 %s 封郵件。';
$_LANG['invalid_time'] = '您輸入了一個無效的團購時間。';

$_LANG['add_success'] = '添加團購活動成功。';
$_LANG['edit_success'] = '編輯團購活動成功。';
$_LANG['back_list'] = '返回團購活動列表。';
$_LANG['continue_add'] = '繼續添加團購活動。';

/* 添加/編輯活動提交 */
$_LANG['error_goods_null'] = '您沒有選擇團購商品！';
$_LANG['error_goods_exist'] = '您選擇的商品目前有一個團購活動正在進行！';
$_LANG['error_price_ladder'] = '您沒有輸入有效的價格階梯！';
$_LANG['error_restrict_amount'] = '限購數量不能小於價格階梯中的最大數量';

$_LANG['js_languages']['error_goods_null'] = '您沒有選擇團購商品！';
$_LANG['js_languages']['error_deposit'] = '您輸入的保證金不是數字！';
$_LANG['js_languages']['error_restrict_amount'] = '您輸入的限購數量不是整數！';
$_LANG['js_languages']['error_gift_integral'] = '您輸入的贈送積分數不是整數！';
$_LANG['js_languages']['search_is_null'] = '沒有搜索到任何商品，請重新搜索';

/* 刪除團購活動 */
$_LANG['js_languages']['batch_drop_confirm'] = '您確定要刪除選定的團購活動嗎？';
$_LANG['error_exist_order'] = '該團購活動已經有訂單，不能刪除！';
$_LANG['batch_drop_success'] = '成功刪除了 %s 條團購活動記錄（已經有訂單的團購活動不能刪除）。';
$_LANG['no_select_group_buy'] = '您現在沒有團購活動記錄！';

/* 操作日誌 */
$_LANG['log_action']['group_buy'] = '團購商品';

?>