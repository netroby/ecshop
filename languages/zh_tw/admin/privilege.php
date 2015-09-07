<?php

/**
 * ECSHOP 管理中心權限管理模塊語言文件
 * ============================================================================
 * 版權所有 2005-2011 上海商派網絡科技有限公司，並保留所有權利。
 * 網站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 這不是一個自由軟件！您只能在不用於商業目的的前提下對程序代碼進行修改和
 * 使用；不允許對程序代碼以任何形式任何目的的再發佈。
 * ============================================================================
 * $Author: liubo $
 * $Id: privilege.php 17217 2011-01-19 06:29:08Z liubo $
*/

/* 字段信息 */
$_LANG['user_id'] = '編號';
$_LANG['user_name'] = '用戶名';
$_LANG['email'] = 'Email地址';
$_LANG['password'] = '密  碼';
$_LANG['join_time'] = '加入時間';
$_LANG['last_time'] = '最後登錄時間';
$_LANG['last_ip'] = '最後訪問的IP';
$_LANG['action_list'] = '操作權限';
$_LANG['nav_list'] = '導航條';
$_LANG['language'] = '使用的語言';

$_LANG['allot_priv'] = '分派權限';
$_LANG['allot_list'] = '權限列表';
$_LANG['go_allot_priv'] = '設置管理員權限';

$_LANG['view_log'] = '查看日誌';

$_LANG['back_home'] = '返回首頁';
$_LANG['forget_pwd'] = '您忘記了密碼嗎?';
$_LANG['get_new_pwd'] = '找回管理員密碼';
$_LANG['pwd_confirm'] = '確認密碼';
$_LANG['new_password'] = '新密碼';
$_LANG['old_password'] = '舊密碼';
$_LANG['agency'] = '負責的辦事處';
$_LANG['self_nav'] = '個人導航';
$_LANG['all_menus'] = '所有菜單';
$_LANG['add_nav'] = '增加';
$_LANG['remove_nav'] = '移除';
$_LANG['move_up'] = '上移';
$_LANG['move_down'] = '下移';
$_LANG['continue_add'] = '繼續添加管理員';
$_LANG['back_list'] = '返回管理員列表';
$_LANG['select_role'] = '角色選擇';
$_LANG['select_please'] = '請選擇...';

$_LANG['admin_edit'] = '編輯管理員';
$_LANG['edit_pwd'] = '修改密碼';

$_LANG['back_admin_list'] = '返回管理員列表';

/* 提示信息 */
$_LANG['js_languages']['user_name_empty'] = '管理員用戶名不能為空!';
$_LANG['js_languages']['password_invaild'] = '密碼必須同時包含字母及數字且長度不能小于6!';
$_LANG['js_languages']['email_empty'] = 'Email地址不能為空!';
$_LANG['js_languages']['email_error'] = 'Email地址格式不正確!';
$_LANG['js_languages']['password_error'] = '兩次輸入的密碼不一致!';
$_LANG['js_languages']['captcha_empty'] = '您沒有輸入驗證碼!';
$_LANG['action_succeed'] = '操作成功!';
$_LANG['edit_profile_succeed'] = '您已經成功的修改了個人帳號信息!';
$_LANG['edit_password_succeed'] = '您已經成功的修改了密碼，因此您必須重新登錄!';
$_LANG['user_name_exist'] = '該管理員已經存在!';
$_LANG['email_exist'] = 'Email地址已經存在!';
$_LANG['captcha_error'] = '您輸入的驗證碼不正確。';
$_LANG['login_faild'] = '您輸入的帳號信息不正確。';
$_LANG['login_disable'] = '您輸入的帳號暫時不可用。';
$_LANG['user_name_drop'] = '已被成功刪除!';
$_LANG['pwd_error'] = '輸入的舊密碼錯誤!';
$_LANG['old_password_empty'] = '如果要修改密碼,必須先輸入你的舊密碼!';
$_LANG['edit_admininfo_error'] = '只能編輯自己的個人資料!';
$_LANG['edit_admininfo_cannot'] = '您不能對此管理員的權限進行任何操作!';
$_LANG['edit_remove_cannot'] = '您不能刪除demo這個管理員!';
$_LANG['remove_self_cannot'] = '您不能刪除自己!';
$_LANG['remove_cannot'] = '此管理員您不能進行刪除操作!';

$_LANG['modif_info'] = '編輯個人資料';
$_LANG['edit_navi'] = '設置個人導航';

/* 幫助信息 */
$_LANG['password_notic'] = '如果要修改密碼,請先填寫舊密碼,如留空,密碼保持不變';
$_LANG['email_notic'] = '輸入管理員的Email郵箱,必須為Email格式';
$_LANG['confirm_notic'] = '輸入管理員的確認密碼,兩次輸入必須一致';

/* 登錄表單 */
$_LANG['label_username'] = '管理員姓名：';
$_LANG['label_password'] = '管理員密碼：';
$_LANG['label_captcha'] = '驗證碼：';
$_LANG['click_for_another'] = '看不清？點擊更換另一個驗證碼。';
$_LANG['signin_now'] = '進入管理中心';
$_LANG['remember'] = '請保存我這次的登錄信息。';
?>