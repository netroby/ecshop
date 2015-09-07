<?php

/**
 * ECSHOP 管理中心公用文件
 * ============================================================================
 * 版权所有 (C) 2005-2007 康盛创想（北京）科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com
 * ----------------------------------------------------------------------------
 * 这是一个免费开源的软件；这意味着您可以在不用于商业目的的前提下对程序代码
 * 进行修改、使用和再发布。
 * ============================================================================
 * $Author: levie $
 * $Date: 2008-09-04 16:41:47 +0800 (Thu, 04 Sep 2008) $
 * $Id: init.php 5666 2008-09-04 08:41:47Z levie $
*/

if (!defined('IN_ECS'))
{
    die('Hacking attempt');
}

define('ECS_ADMIN', true);

//error_reporting(E_ALL);

if (__FILE__ == '')
{
    die('Fatal error code: 0');
}

/* 取得当前ecshop所在的根目录 */
define('ROOT_PATH', str_replace('includes/init.php', '', str_replace('\\', '/', __FILE__)));

/* 初始化设置 */
@ini_set('memory_limit',          '16M');
@ini_set('session.cache_expire',  180);
@ini_set('session.use_trans_sid', 0);
@ini_set('session.use_cookies',   1);
@ini_set('session.auto_start',    0);
@ini_set('display_errors',        1);


if (DIRECTORY_SEPARATOR == '\\')
{
    @ini_set('include_path',      '.;' . ROOT_PATH);
}
else
{
    @ini_set('include_path',      '.:' . ROOT_PATH);
}

include('includes/config.inc.php');

if (defined('DEBUG_MODE') == false)
{
    define('DEBUG_MODE', 0);
}

if (PHP_VERSION >= '5.1' && !empty($timezone))
{
    date_default_timezone_set($timezone);
}

require('includes/inc_constant.php');
require('includes/cls_ecshop.php');
require('includes/cls_error.php');
require('includes/lib_common.php');
require('includes/lib_main.php');
require('includes/lib_time.php');
require('includes/lib_base.php');
require('includes/cls_exchange.php');
require('includes/global.inc.php');

/* 对用户传入的变量进行转义操作。*/
if (!get_magic_quotes_gpc())
{
    if (!empty($_GET))
    {
        $_GET  = addslashes_deep($_GET);
    }
    if (!empty($_POST))
    {
        $_POST = addslashes_deep($_POST);
    }

    $_COOKIE   = addslashes_deep($_COOKIE);
    $_REQUEST  = addslashes_deep($_REQUEST);
    $_FILES  = addslashes_deep($_FILES);

}
/* 创建 ECSHOP 对象 */
$ecs = new ECS($db_name, $prefix);

/* 初始化数据库类 */
require(ROOT_PATH . 'includes/cls_mysql.php');
$db = new cls_mysql($db_host, $db_user, $db_pass, $db_name);
//$db_user = new cls_mysql($db_host, $db_user, $db_pass, '');
$db_bbs = new cls_mysql($db_host, $db_user, $db_pass, 'uc_maifou_net');
$db_host = $db_user = $db_pass = $db_name = NULL;

/* 创建错误处理对象 */
$err = new ecs_error('message.htm');

/* 初始化session */
require(ROOT_PATH . 'includes/cls_session.php');
$sess = new cls_session($db, $ecs->table('sessions'), $ecs->table('sessions_data'), 'ECSCP_ID');

/* 初始化 action */
if (!isset($_REQUEST['act']))
{
    $_REQUEST['act'] = '';
}
elseif (($_REQUEST['act'] == 'login' || $_REQUEST['act'] == 'logout' || $_REQUEST['act'] == 'signin') && strpos($_SERVER['PHP_SELF'], '/privilege.php') === false)
{
    $_REQUEST['act'] = '';
}
elseif (($_REQUEST['act'] == 'forget_pwd' || $_REQUEST['act'] == 'reset_pwd' || $_REQUEST['act'] == 'get_pwd') && strpos($_SERVER['PHP_SELF'], '/get_password.php') === false)
{
    $_REQUEST['act'] = '';
}

// TODO : 登录部分准备拿出去做，到时候把以下操作一起挪过去
if ($_REQUEST['act'] == 'captcha')
{
    include('includes/cls_captcha.php');

    $img = new captcha('data/captcha/');
    @ob_end_clean(); //清除之前出现的多余输入
    $img->generate_image();

    exit;
}

require('languages/' .$_CFG['lang']. '/admin/common.php');
require('languages/' .$_CFG['lang']. '/admin/log_action.php');

if (file_exists('languages/' . $_CFG['lang'] . '/admin/' . basename($_SERVER['PHP_SELF'])))
{
    include('languages/' . $_CFG['lang'] . '/admin/' . basename($_SERVER['PHP_SELF']));
}

if (!file_exists('templates/caches'))
{
    @mkdir('templates/caches', 0777);
    @chmod('templates/caches', 0777);
}

if (!file_exists('templates/compiled/admin'))
{
    @mkdir('templates/compiled/admin', 0777);
    @chmod('templates/compiled/admin', 0777);
}

clearstatcache();

/* 创建 Smarty 对象。*/
require('includes/cls_template.php');
$smarty = new cls_template;

$smarty->template_dir  = ROOT_PATH . 'templates';
$smarty->compile_dir   = ROOT_PATH . 'templates/compiled/admin';
//$smarty->plugins_dir   = ROOT_PATH . 'includes/smarty/plugins';
//$smarty->caching       = false;
//$smarty->compile_force = false;
//$smarty->register_resource('db', array('db_get_template', 'db_get_timestamp', 'db_get_secure', 'db_get_trusted'));

//$smarty->register_function('insert_scripts', 'smarty_insert_scripts');
//$smarty->register_function('create_pages',   'smarty_create_pages');

$smarty->assign('lang', $_LANG);

/* 如果有新版本，升级 */
if (!isset($_CFG['ecs_version']))
{
    $_CFG['ecs_version'] = 'v2.5';
}

if (preg_replace('/\.[a-z]*$/i', '', $_CFG['ecs_version']) != preg_replace('/\.[a-z]*$/i', '', VERSION)
        && file_exists('upgrade/index.php'))
{
    // 转到升级文件
    header("Location: upgrade/index.php\n");

    exit;
}

/* 验证管理员身份 */
if ((!isset($_SESSION['admin_id']) || intval($_SESSION['admin_id']) <= 0) &&
    $_REQUEST['act'] != 'login' && $_REQUEST['act'] != 'signin' &&
    $_REQUEST['act'] != 'forget_pwd' && $_REQUEST['act'] != 'reset_pwd' && $_REQUEST['act'] != 'check_order')
{
    /* session 不存在，检查cookie */
    if (!empty($_COOKIE['ECSCP']['admin_id']) && !empty($_COOKIE['ECSCP']['admin_pass']))
    {
        // 找到了cookie, 验证cookie信息
        $sql = 'SELECT user_id, user_name, password, action_list, last_login ' .
                ' FROM ' .$ecs->table('admin_user') .
                " WHERE user_id = '" . intval($_COOKIE['ECSCP']['admin_id']) . "'";
        $row = $db->GetRow($sql);

        if (!$row)
        {
            // 没有找到这个记录
            setcookie($_COOKIE['ECSCP']['admin_id'],   '', 1);
            setcookie($_COOKIE['ECSCP']['admin_pass'], '', 1);

            if (!empty($_REQUEST['is_ajax']))
            {
                make_json_error($_LANG['priv_error']);
            }
            else
            {
                header("Location: privilege.php?act=login\n");
            }

            exit;
        }
        else
        {
            // 检查密码是否正确
            if (md5($row['password'] . $_CFG['hash_code']) == $_COOKIE['ECSCP']['admin_pass'])
            {
                !isset($row['last_time']) && $row['last_time'] = '';
                set_admin_session($row['user_id'], $row['user_name'], $row['action_list'], $row['last_time']);

                // 更新最后登录时间和IP
                $db->query('UPDATE ' . $ecs->table('admin_user') .
                            " SET last_login = '" . gmtime() . "', last_ip = '" . real_ip() . "'" .
                            " WHERE user_id = '" . $_SESSION['admin_id'] . "'");
            }
            else
            {
                setcookie($_COOKIE['ECSCP']['admin_id'],   '', 1);
                setcookie($_COOKIE['ECSCP']['admin_pass'], '', 1);

                if (!empty($_REQUEST['is_ajax']))
                {
                    make_json_error($_LANG['priv_error']);
                }
                else
                {
                    header("Location: privilege.php?act=login\n");
                }

                exit;
            }
        }
    }
    else
    {
        if (!empty($_REQUEST['is_ajax']))
        {
            make_json_error($_LANG['priv_error']);
        }
        else
        {
            header("Location: privilege.php?act=login\n");
        }

        exit;
    }
}
if ($_REQUEST['act'] != 'login' && $_REQUEST['act'] != 'signin' &&
    $_REQUEST['act'] != 'forget_pwd' && $_REQUEST['act'] != 'reset_pwd' && $_REQUEST['act'] != 'check_order')
{
    $admin_path = preg_replace('/:\d+/', '', $ecs->url());

    if (!empty($_SERVER['HTTP_REFERER']) &&
        strpos(preg_replace('/:\d+/', '', $_SERVER['HTTP_REFERER']), $admin_path) === false)
    {
        if (!empty($_REQUEST['is_ajax']))
        {
            make_json_error($_LANG['priv_error']);
        }
        else
        {
            header("Location: privilege.php?act=login\n");
        }

        exit;
    }
}

/* 管理员登录后可在任何页面使用 act=phpinfo 显示 phpinfo() 信息 */
if ($_REQUEST['act'] == 'phpinfo' && function_exists('phpinfo'))
{
    phpinfo();

    exit;
}

//header('Cache-control: private');
header('content-type: text/html; charset=utf-8');
header('Expires: Fri, 14 Mar 1980 20:53:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: no-cache, must-revalidate');
header('Pragma: no-cache');

if ((DEBUG_MODE & 1) == 1)
{
    error_reporting(E_ALL);
}
else
{
    error_reporting(E_ALL ^ E_NOTICE);
}
if ((DEBUG_MODE & 4) == 4)
{
    include('includes/lib.debug.php');
}

/* 服务过期邮件提示 */
//mail_expiry_notice();

/* 判断是否支持gzip模式 */
if (gzip_enabled())
{
    ob_start('ob_gzhandler');
}
else
{
    ob_start();
}

?>