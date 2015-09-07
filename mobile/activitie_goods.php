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
 * $Id: index.php 15013 2010-03-25 09:31:42Z liuhui $
*/

define('IN_ECS', true);
//define('ECS_ADMIN', true);

require(dirname(__FILE__) . '/includes/init.php');

$id=isset($_GET['id'])?intval($_GET['id']):'';

if(empty($id))
{
    mobile_error ('返回首页','index.php','活动页不存在');
}
else
{
    $new_time=time();
    $sql='SELECT * FROM '.$ecs->table('favourable_activity')." WHERE `start_time`<' $new_time'  AND `end_time`>'$new_time' AND (`act_type`=1 or `act_type`=2) AND `act_id` ='$id'";


    $result=$db->getRow($sql);
    if(empty($result))
    {
        mobile_error ('返回首页','index.php','活动页不存在或者已经过期');
    }
    else
    {
        $result['content']='';
        if(!empty($result['user_rank']))
        {

            $rank=explode(',',$result['user_rank']);

            if(!in_array($_SESSION['user_rank'],$rank))
            {
                 mobile_error ('返回首页','index.php','您当前等级无法查阅');

            }
        }
        $result['content']='';
        if(!empty($result['user_rank']))
        {
            $rank=explode(',',$result['user_rank']);

            $sql="SELECT `rank_name` FROM ".$ecs->table('user_rank')." WHERE  " .db_create_in($rank,'rank_id');
            $user_rank=$db->getAll($sql);
            $result['content']='可参加活动的会员等级为:';
            foreach ($user_rank as $val)
            {
                $result['content'].=$val['rank_name'].'&nbsp    ';
            }
            $result['content'].='<br/>';
        }

       $result['content'].='活动开始时间为:'.local_date('Y-m-d H:i', $result['start_time']).',活动结束时间为:'.local_date('Y-m-d H:i', $result['end_time']);

       $smarty->assign('activity', $result);

      $smarty->assign('common_header_title',   $result['act_name']);


       $sql1="SELECT count(`goods_id`) FROM ".$ecs->table('goods');
       $sql2 = 'SELECT g.goods_id, g.goods_name,g.goods_sn, g.goods_name_style, g.market_price, g.is_new, g.is_best, g.is_hot, g.shop_price AS org_price, ' .
                "IFNULL(mp.user_price, g.shop_price * '$_SESSION[discount]') AS shop_price, g.promote_price, g.goods_type, " .
                'g.promote_start_date, g.promote_end_date, g.goods_brief, g.goods_thumb , g.goods_img ' .
            'FROM ' . $GLOBALS['ecs']->table('goods') . ' AS g ' .
            'LEFT JOIN ' . $GLOBALS['ecs']->table('member_price') . ' AS mp ' .
                "ON mp.goods_id = g.goods_id AND mp.user_rank = '$_SESSION[user_rank]' ";
        include_once(ROOT_PATH . 'includes/lib_transaction.php');

        if($result['act_range']=='0')
        {
            $sql1.=" WHERE  `is_on_sale`=1 AND `is_alone_sale`=1";
            $sql2.="WHERE `is_on_sale`=1 AND `is_alone_sale`=1 ";
        }
        elseif($result['act_range']=='1')
        {
           $a_cat_id=explode(',',$result['act_range_ext']);
           $sql1.=" WHERE `is_on_sale`=1 AND `is_alone_sale`=1 AND   ".db_create_in($a_cat_id,'cat_id');
           $sql2.=" WHERE  `is_on_sale`=1 AND `is_alone_sale`=1 AND   ".db_create_in($a_cat_id,'g.cat_id');
        }
        elseif($result['act_range']=='2')
        {
           $a_bind_id=explode(',',$result['act_range_ext']);
           $sql1.=" WHERE `is_on_sale`=1 AND `is_alone_sale`=1 AND    ".db_create_in($a_bind_id,'brand_id');
           $sql2.=" WHERE  `is_on_sale`=1 AND `is_alone_sale`=1 AND   ".db_create_in($a_bind_id,'g.brand_id');
        }
        else
        {
           $a_goods_id=explode(',',$result['act_range_ext']);
           $sql1.=" WHERE  `is_on_sale`=1 AND `is_alone_sale`=1 AND   ".db_create_in($a_goods_id,'goods_id');
           $sql2.=" WHERE  `is_on_sale`=1 AND `is_alone_sale`=1 AND   ".db_create_in($a_goods_id,'g.goods_id');
        }

        $record_count=$db->getOne($sql1);
        if($record_count > 0)
        {
            $page_num = '10';
            $page = !empty($_GET['page']) ? intval($_GET['page']) : 1;
            $pages = ceil($record_count / $page_num);

            if ($page <= 0)
            {
                $page = 1;
            }
            if ($pages == 0)
            {
                $pages = 1;
            }
            if ($page > $pages)
            {
                $page = $pages;
            }

           $pagebar = get_wap_pager($record_count, $page_num, $page, "activities_goods.php?id=$id", 'page');
           $smarty->assign('pagebar', $pagebar);
           $start=$page_num * ($page - 1);
           $sql2.=" LIMIT $start,$page_num";
           $goods_list=$db->getAll($sql2);
           if(!empty($goods_list))
           {
               foreach ($goods_list as $key=>$val)
               {
                   $goods_list[$key]['shop_price']=price_format($val['shop_price']);
               }
           }
           $smarty->assign('goods_list', $goods_list);
           $smarty->display('activity_goods.dwt');

        }





    }





}


?>
