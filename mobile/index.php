<?php

/**
 * ECSHOP mobile��ҳ
 * ============================================================================
 * * ��Ȩ���� 2005-2012 �Ϻ���������Ƽ����޹�˾������������Ȩ����
 * ��վ��ַ: http://www.ecshop.com��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�
 * ʹ�ã�������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
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

$smarty->assign('page_title',   $_CFG['shop_title']);    // ҳ�����

$sql = 'SELECT * FROM '.$ecs->table('mobile_ad');
$result = $db->getAll($sql);
$smarty->assign('playerdb', $result);
mobile_common();

/* ������Ʒ */
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
