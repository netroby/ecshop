<?php

/**
 * ECSHOP 授權證書管理語言包
 * ============================================================================
 * 版權所有 2005-2011 上海商派網絡科技有限公司，並保留所有權利。
 * 網站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 這不是一個自由軟件！您只能在不用於商業目的的前提下對程序代碼進行修改和
 * 使用；不允許對程序代碼以任何形式任何目的的再發佈。
 * ============================================================================
 * $Author: testyang $
 * $Id: index.php 15086 2008-10-27 06:21:49Z testyang $
*/

/* 菜單 */
$_LANG['upload_license'] = '上傳證書';
$_LANG['download_license'] = '下載證書';
$_LANG['back'] = '返回';

/* 列表頁 */
$_LANG['license_here'] = 'license證書';

/* 詳情頁 */
$_LANG['label_certificate_download'] = 'ECshop證書下載備份';
$_LANG['label_license_key'] = '授權碼：';
$_LANG['label_certificate_reset'] = 'ECshop證書上傳恢復';
$_LANG['label_delete_license'] = '錯誤證書刪除';
$_LANG['label_select_license'] = '選擇上傳的證書：';

/* 系統提示 */
$_LANG['delete_license_notice'] = '當您上傳了錯誤的ECShop證書導致證書功能失效時，請先在此處清空錯誤的證書，再使用證書上傳恢復功能恢復正確的證書。';
$_LANG['license_notice'] = 'ECShop證書是您享受ECShop服務的唯一標識，它記錄了您的網店的授權信息、購買官方服務記錄、短信賬戶等重要信息。您需要通過“證書下載備份”功能備份證書，并妥善保管。在您遇到商店系統需要重新安裝時，可以在新安裝的系統中使用“證書上傳恢復”功能將之前的證書恢復，這樣新系統就可以繼續使用證書內的重要信息。';
$_LANG['delete_license'] = "錯誤的證書已刪除。";
$_LANG['fail_license'] = "證書內容不全。請先確定證書是否正確然后重新上傳！";
$_LANG['recover_license'] = "證書恢復功能。";
$_LANG['no_license_down'] = "失敗。暫無證書可下載！";
$_LANG['fail_license_login'] = "證書登錄失敗。上傳證書不正確！";

/* JS提示 */
$_LANG['js_languages']['del_confirm'] = '確認刪除錯誤的證書嗎？';

?>