<?php

/**
 * ECSHOP 程序说明
 * ===========================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ==========================================================
 * $Author: wangleisvn $
 * $Id: flashplay.php 16131 2009-05-31 08:21:41Z wangleisvn $
 */

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

/*------------------------------------------------------ */
//-- 证书编辑页
/*------------------------------------------------------ */
if ($_REQUEST['act']== 'list_edit')
{
    /* 检查权限 */
    admin_priv('shop_authorized');

    include_once(ROOT_PATH . 'includes/lib_license.php');

    mobile_shop_license();
    $license=get_m_shop_license();

    if(!empty($license['certificate_id']))
    {
       $cert_license = m_license_login();

    }

    $smarty->assign('ur_here', $_LANG['license_here']);
    $smarty->assign('certificate_id', $license['certificate_id']);
    $smarty->assign('token', $license['token']);

    $smarty->display('mobile_license.htm');
}





/*------------------------------------------------------ */
//-- 证书删除
/*------------------------------------------------------ */

elseif ($_REQUEST['act']== 'del')
{
    /* 检查权限 */
    admin_priv('shop_authorized');

    $sql = "UPDATE " . $ecs->table('shop_config') . "
            SET value = '0'
            WHERE code IN('m_certificate_id', 'm_token')";
    $db->query($sql);

    $links[] = array('text' => $_LANG['back'], 'href' => 'mobile_license.php?act=list_edit');
    sys_msg($_LANG['delete_license'], 0, $links);
}


function mobile_shop_license ()
{
    $sql="SELECT `id`,`value` FROM  ". $GLOBALS['ecs']->table('shop_config') ." WHERE `code`='m_certificate_id' or `code`='m_token'";
    $result=$GLOBALS['db']->getAll($sql);
    if(!empty($result))
    {
        if($result['0']['m_certificate_id']!=0)
        {
        }
        else
        {
            m_license_reg();
        }

    }
    else
    {

        shop_config_update ('m_certificate_id','0');
        shop_config_update ('m_token','0');
        m_license_reg();
        //注册
    }
}


function shop_config_update ($config_code,$config_value)
{
	$sql="SELECT `id` FROM ".$GLOBALS['ecs']->table('shop_config')." WHERE `code`='$config_code'";
	$c_node_id=$GLOBALS['db']->getOne($sql);
	if(empty($c_node_id))
    {
    	for ($i=247;$i<=270;$i++)
        {
        	$sql="SELECT `id` FROM ".$GLOBALS['ecs']->table('shop_config')." WHERE `id`='$i'";
        	$c_id=$GLOBALS['db']->getOne($sql);
        	if(empty($c_id))
            {
            	$sql="INSERT INTO ".$GLOBALS['ecs']->table('shop_config')."(`id`,`parent_id`,`code`,`type`,`value`,`sort_order`) VALUES ('$i','2','$config_code','hidden','$config_value','1')";
            	$GLOBALS['db']->query($sql);
            	break;
            }
        }
    }
    else
    {
    	$sql="UPDATE ".$GLOBALS['ecs']->table('shop_config')." SET `value`='$config_value'  WHERE `code`='$config_code'";
    	$GLOBALS['db']->query($sql);
    }
}




function m_license_reg($certi_added = '')
{
    // 登录信息配置
    $certi['certi_app'] = ''; // 证书方法
    $certi['app_id'] = 'ecshop_b2c'; // 说明客户端来源
    $certi['app_instance_id'] = ''; // 应用服务ID
    $certi['version'] = LICENSE_VERSION; // license接口版本号
    $certi['shop_version'] = VERSION . '#' .  RELEASE; // 网店软件版本号
    $certi['certi_url'] = sprintf($GLOBALS['ecs']->url().'mobile'); // 网店URL
    $certi['certi_session'] = $GLOBALS['sess']->get_session_id(); // 网店SESSION标识
    $certi['certi_validate_url'] = sprintf($GLOBALS['ecs']->url() . 'mobile/certi.php'); // 网店提供于官方反查接口
    $certi['format'] = 'json'; // 官方返回数据格式
    $certi['certificate_id'] = ''; // 网店证书ID
    // 标识
    $certi_back['succ']   = 'succ';
    $certi_back['fail']   = 'fail';
    // return 返回数组
    $return_array = array();

    if (is_array($certi_added))
    {
        foreach ($certi_added as $key => $value)
        {
            $certi[$key] = $value;
        }
    }

    // 取出网店 license
    $license = get_m_shop_license();
    // 注册
    $certi['certi_app'] = 'certi.reg'; // 证书方法
    $certi['certi_ac'] = make_shopex_ac($certi, ''); // 网店验证字符串
    unset($certi['certificate_id']);

    $request_arr = exchange_shop_license($certi, $license);
    if (is_array($request_arr) && $request_arr['res'] == $certi_back['succ'])
    {
        // 注册信息入库
        $sql = "UPDATE " . $GLOBALS['ecs']->table('shop_config') . "
                SET value = '" . $request_arr['info']['certificate_id'] . "' WHERE code = 'm_certificate_id'";
        $GLOBALS['db']->query($sql);
        $sql = "UPDATE " . $GLOBALS['ecs']->table('shop_config') . "
                SET value = '" . $request_arr['info']['token'] . "' WHERE code = 'm_token'";
        $GLOBALS['db']->query($sql);

        $return_array['flag'] = 'reg_succ';
        $return_array['request'] = $request_arr;
        clear_cache_files();
    }
    elseif (is_array($request_arr) && $request_arr['res'] == $certi_back['fail'])
    {
        $return_array['flag'] = 'reg_fail';
        $return_array['request'] = $request_arr;
    }
    else
    {
        $return_array['flag'] = 'reg_ping_fail';
        $return_array['request'] = array('res' => 'fail');
    }

    return $return_array;
}


function get_m_shop_license()
{
     $sql = "SELECT code, value
            FROM " . $GLOBALS['ecs']->table('shop_config') . "
            WHERE code IN ('m_certificate_id', 'm_token', 'certi')
            LIMIT 0,3";
    $license_info = $GLOBALS['db']->getAll($sql);
    $license_info = is_array($license_info) ? $license_info : array();
    $license = array();
    foreach ($license_info as $value)
    {
        if($value['code']=='m_certificate_id')
        {
            $license['certificate_id'] = $value['value'];
        }
        elseif($value['code']=='m_token')
        {
            $license['token'] = $value['value'];
        }
        else
        {
            $license[$value['code']] = $value['value'];
        }

    }

    return $license;

}


function m_license_login($certi_added = '')
{
    // 登录信息配置
    $certi['certi_app'] = ''; // 证书方法
    $certi['app_id'] = 'ecshop_b2c'; // 说明客户端来源
    $certi['app_instance_id'] = ''; // 应用服务ID
    $certi['version'] = LICENSE_VERSION; // license接口版本号
    $certi['shop_version'] = VERSION . '#' .  RELEASE; // 网店软件版本号
    $certi['certi_url'] = sprintf($GLOBALS['ecs']->url().'mobile'); // 网店URL
    $certi['certi_session'] = $GLOBALS['sess']->get_session_id(); // 网店SESSION标识
    $certi['certi_validate_url'] = sprintf($GLOBALS['ecs']->url() . '/mobile/certi.php'); // 网店提供于官方反查接口
    $certi['format'] = 'json'; // 官方返回数据格式
    $certi['certificate_id'] = ''; // 网店证书ID
    // 标识
    $certi_back['succ']   = 'succ';
    $certi_back['fail']   = 'fail';
    // return 返回数组
    $return_array = array();

    if (is_array($certi_added))
    {
        foreach ($certi_added as $key => $value)
        {
            $certi[$key] = $value;
        }
    }

    // 取出网店 license
    $license = get_m_shop_license();

    // 检测网店 license
    if (!empty($license['certificate_id']) && !empty($license['token']) && !empty($license['certi']))
    {
        // 登录
        $certi['certi_app'] = 'certi.login'; // 证书方法
        $certi['app_instance_id'] = 'cert_auth'; // 应用服务ID
        $certi['certificate_id'] = $license['certificate_id']; // 网店证书ID
        $certi['certi_ac'] = make_shopex_ac($certi, $license['token']); // 网店验证字符串

        $request_arr = exchange_shop_license($certi, $license);
        if (is_array($request_arr) && $request_arr['res'] == $certi_back['succ'])
        {
            $return_array['flag'] = 'login_succ';
            $return_array['request'] = $request_arr;
        }
        elseif (is_array($request_arr) && $request_arr['res'] == $certi_back['fail'])
        {
            $return_array['flag'] = 'login_fail';
            $return_array['request'] = $request_arr;
        }
        else
        {
            $return_array['flag'] = 'login_ping_fail';
            $return_array['request'] = array('res' => 'fail');
        }
    }
    else
    {
        $return_array['flag'] = 'login_param_fail';
        $return_array['request'] = array('res' => 'fail');
    }

    return $return_array;
}







?>