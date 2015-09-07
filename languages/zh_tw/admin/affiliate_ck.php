<?php

/**
 * ECSHOP 程序說明
 * ===========================================================
 * 版權所有 2005-2011 上海商派網絡科技有限公司，並保留所有權利。
 * 網站地址: http://www.ecshop.com；
 * ----------------------------------------------------------
 * 這不是一個自由軟件！您只能在不用於商業目的的前提下對程序代碼進行修改和
 * 使用；不允許對程序代碼以任何形式任何目的的再發佈。
 * ==========================================================
 * $Author: liubo $
 * $Id: affiliate_ck.php 17217 2011-01-19 06:29:08Z liubo $
 */


$_LANG['order_id'] = '訂單號';
$_LANG['affiliate_separate'] = '分成';
$_LANG['affiliate_cancel'] = '取消';
$_LANG['affiliate_rollback'] = '撤銷';
$_LANG['log_info'] = '操作信息';
$_LANG['edit_ok'] = '操作成功';
$_LANG['edit_fail'] = '操作失敗';
$_LANG['separate_info'] = '訂單號 %s, 分成:金錢 %s 積分 %s';
$_LANG['separate_info2'] = '用戶ID %s ( %s ), 分成:金錢 %s 積分 %s';
$_LANG['sch_order'] = '搜索訂單號';

$_LANG['sch_stats']['name'] = '操作狀態';
$_LANG['sch_stats']['info'] = '按操作狀態查找:';
$_LANG['sch_stats']['all'] = '全部';
$_LANG['sch_stats'][0] = '等待處理';
$_LANG['sch_stats'][1] = '已分成';
$_LANG['sch_stats'][2] = '取消分成';
$_LANG['sch_stats'][3] = '已撤銷';
$_LANG['order_stats']['name'] = '訂單狀態';
$_LANG['order_stats'][0] = '未確認';
$_LANG['order_stats'][1] = '已確認';
$_LANG['order_stats'][2] = '已取消';
$_LANG['order_stats'][3] = '無效';
$_LANG['order_stats'][4] = '退貨';
$_LANG['js_languages']['cancel_confirm'] = '您確定要取消分成嗎？此操作不能撤銷。';
$_LANG['js_languages']['rollback_confirm'] = '您確定要撤銷此次分成嗎？';
$_LANG['js_languages']['separate_confirm'] = '您確定要分成嗎？';
$_LANG['loginfo'][0] = '用戶id:';
$_LANG['loginfo'][1] = '現金:';
$_LANG['loginfo'][2] = '積分:';
$_LANG['loginfo']['cancel'] = '分成被管理員取消！';

$_LANG['separate_type'] = '分成類型';
$_LANG['separate_by'][0] = '推薦註冊分成';
$_LANG['separate_by'][1] = '推薦訂單分成';
$_LANG['separate_by'][-1] = '推薦註冊分成';
$_LANG['separate_by'][-2] = '推薦訂單分成';

$_LANG['show_affiliate_orders'] = '此列表顯示此用戶推薦的訂單信息。';
$_LANG['back_note'] = '返回會員編輯頁面';
?>