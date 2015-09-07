<?php

/**
 * ECSHOP mobile首页
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liuhui $
 * $Id: index.php 15013 2010-03-25 09:31:42Z liuhui $.
 */
define('IN_ECS', true);
//define('ECS_ADMIN', true);

require dirname(__FILE__).'/includes/init.php';

if (isset($_GET['access']) && $_GET['access'] == 'computer') {
    $Loaction = '../index.php?access=computer';
    if (!empty($Loaction)) {
        ecs_header("Location: $Loaction\n");
        exit;
    }
}

$smarty->assign('page_title',   $_CFG['shop_title']);    // 页面标题

$sql = 'SELECT * FROM '.$ecs->table('mobile_ad');
$result = $db->getAll($sql);
$smarty->assign('playerdb', $result);
mobile_common();

/* 热门商品 */
$hot_goods = get_recommend_goods('hot');
$hot_num = count($hot_goods);
$smarty->assign('hot_num', $hot_num);

if ($hot_num > 0) {
    $i = 0;
    foreach ($hot_goods as $key => $hot_data) {
        $hot_goods[$key]['shop_price'] = encode_output($hot_data['shop_price']);
        $hot_goods[$key]['name'] = encode_output($hot_data['name']);
        /*if ($i > 2)
        {
            break;
        }*/
        ++$i;
    }
    $smarty->assign('hot_goods', $hot_goods);
}

$smarty->assign('wap_logo', $_CFG['wap_logo']);
$smarty->assign('footer', get_footer());
$smarty->display('index.dwt');
