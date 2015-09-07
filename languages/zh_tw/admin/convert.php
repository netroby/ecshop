<?php

/**
 * ECSHOP 轉換程序語言文件
 * ============================================================================
 * 版權所有 2005-2011 上海商派網絡科技有限公司，並保留所有權利。
 * 網站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 這不是一個自由軟件！您只能在不用於商業目的的前提下對程序代碼進行修改和
 * 使用；不允許對程序代碼以任何形式任何目的的再發佈。
 * ============================================================================
 * $Author: liubo $
 * $Id: convert.php 17217 2011-01-19 06:29:08Z liubo $
 */

$_LANG['confirm_convert'] = '注意：執行轉換程序將會使現有數據丟失，請您三思而行！！！';
$_LANG['backup_data'] = '如果現有數據對您可能還有價值，請您先做好備份。';
$_LANG['backup'] = '現在就去備份';
$_LANG['select_system'] = '請選擇您要轉換的系統：';
$_LANG['note_select_system'] = '（如果您的系統不在左邊的列表中，您可以到<a href="http://www.ecshop.com" target="_blank"><strong>我們的網站</strong></a>尋求幫助）';
$_LANG['select_charset'] = '請選擇您要轉換的系統使用的字符集：';
$_LANG['note_select_charset'] = '（如果你的系統使用的不是 UTF-8 字符集，轉換可能需要較長時間）';
$_LANG['dir_notes'] = '請注意原商城的根目錄路徑請使用相對於admin目錄的路徑。<br />例如：原商城的目錄在根目錄下的shop，而ecshop放在根目錄下，則該路徑為 ../shop';
$_LANG['your_config'] = '請設置原系統的配置信息：';
$_LANG['your_host'] = '主機名稱或地址：';
$_LANG['your_user'] = '登錄帳號：';
$_LANG['your_pass'] = '登錄密碼：';
$_LANG['your_db'] = '數據庫名稱：';
$_LANG['your_prefix'] = '數據庫表前綴：';
$_LANG['your_path'] = '原商城根目錄：';
$_LANG['convert'] = '轉換數據';
$_LANG['remark'] = '備註：';
$_LANG['remark_info'] = '<ul>' .
        '<li>對於特價的商品，您需要編輯其原價（本店售價）和促銷期；</li>' .
        '<li>請重新設置水印；</li>' .
        '<li>請重新設置廣告；</li>' .
        '<li>請重新設置配送方式；</li>' .
        '<li>請重新設置支付方式；</li>' .
        '<li>請把原來不屬於末級分類的商品轉移到末級分類；</li>' .
        '</ul>';

$_LANG['connect_db_error'] = '無法連接數據庫，請檢查配置信息。';
$_LANG['table_error'] = '缺少必需的表 %s ，請檢查配置信息。';
$_LANG['dir_error'] = '缺少必需的目錄 %s ，請檢查配置信息。';
$_LANG['dir_not_readable'] = '目錄不可讀 %s';
$_LANG['dir_not_writable'] = '目錄不可寫 %s';
$_LANG['file_not_readable'] = '文件不可讀 %s';
$_LANG['js_languages']['check_your_db'] = '正在檢查您的系統的數據庫...';
$_LANG['js_languages']['act_ok'] = '恭喜您，操作成功！';

$_LANG['js_languages']['no_system'] = '沒有可用的轉換程序';
$_LANG['js_languages']['host_not_null'] = '主機名稱或地址不能為空';
$_LANG['js_languages']['db_not_null'] = '數據庫名稱不能為空';
$_LANG['js_languages']['user_not_null'] = '登錄帳號不能為空';
$_LANG['js_languages']['path_not_null'] = '原商城根目錄不能為空';
?>