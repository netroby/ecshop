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

$id = isset($_GET['id']) ? intval($_GET['id']) : '';

if (empty($id)) {
    mobile_error('返回首页', 'index.php', '活动页不存在');
} else {
    $new_time = time();
    $sql = 'SELECT * FROM '.$ecs->table('favourable_activity')." WHERE `start_time`<' $new_time'  AND `end_time`>'$new_time' AND (`act_type`=1 or `act_type`=2)  AND `act_id` ='$id'";

    $result = $db->getRow($sql);
    if (empty($result)) {
        mobile_error('返回首页', 'index.php', '活动页不存在或者已经过期');
    } else {
        $result['content'] = '';
        if (!empty($result['user_rank'])) {
            $rank = explode(',', $result['user_rank']);

            $sql = 'SELECT `rank_name` FROM '.$ecs->table('user_rank').' WHERE  '.db_create_in($rank, 'rank_id');
            $user_rank = $db->getAll($sql);
            $result['content'] = '可参加活动的会员等级为:';
            foreach ($user_rank as $val) {
                $result['content'] .= $val['rank_name'].'    ';
            }
            $result['content'] .= '。<br/>';
        }

        $result['content'] .= '活动开始时间为:'.local_date('Y-m-d H:i', $result['start_time']).',活动结束时间为:'.local_date('Y-m-d H:i', $result['end_time']);

        $result['content'] .= "<a href =\"activitie_goods.php?id=$result[act_id]\">点击查看活动商品</a>";
        $smarty->assign('activity', $result);

        $activitie_url = $ecs->url().'activities.php?id='.$id;
        $share = array();

        $share['sina'] = 'http://v.t.sina.com.cn/share/share.php?url='.$activitie_url."&title='".$result['act_name']."'";
        $share['qzone'] = 'http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url='.$activitie_url;
        $share['qconnect'] = 'http://connect.qq.com/widget/shareqq/index.html?url='.$activitie_url."&title='".$result['act_name']."'";
        $share['renren'] = 'http://share.renren.com/share/buttonshare.do?link='.$activitie_url;
        $share['t_qq'] = 'http://v.t.qq.com/share/share.php?url='.$activitie_url."&title='".$result['act_name']."'";

        $smarty->assign('share', $share);
    }
    $addres = 'http://api.map.baidu.com/geocoder?address='.urlencode($_CFG['shop_address']).'&output=html';
    $smarty->assign('addres', $addres);
    $smarty->display('activity.dwt');
}
