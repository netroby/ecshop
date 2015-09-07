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
 * $Id: index.php 15013 2010-03-25 09:31:42Z liuhui $
*/

define('IN_ECS', true);
//define('ECS_ADMIN', true);

require(dirname(__FILE__) . '/includes/init.php');

$id=isset($_GET['id'])?intval($_GET['id']):'';

if(empty($id))
{
    mobile_error ('������ҳ','index.php','�ҳ������');
}
else
{
    $new_time=time();
    $sql='SELECT * FROM '.$ecs->table('favourable_activity')." WHERE `start_time`<' $new_time'  AND `end_time`>'$new_time' AND (`act_type`=1 or `act_type`=2) AND `act_id` ='$id'";


    $result=$db->getRow($sql);
    if(empty($result))
    {
        mobile_error ('������ҳ','index.php','�ҳ�����ڻ����Ѿ�����');
    }
    else
    {
        $result['content']='';
        if(!empty($result['user_rank']))
        {

            $rank=explode(',',$result['user_rank']);

            if(!in_array($_SESSION['user_rank'],$rank))
            {
                 mobile_error ('������ҳ','index.php','����ǰ�ȼ��޷�����');

            }
        }
        $result['content']='';
        if(!empty($result['user_rank']))
        {
            $rank=explode(',',$result['user_rank']);

            $sql="SELECT `rank_name` FROM ".$ecs->table('user_rank')." WHERE  " .db_create_in($rank,'rank_id');
            $user_rank=$db->getAll($sql);
            $result['content']='�ɲμӻ�Ļ�Ա�ȼ�Ϊ:';
            foreach ($user_rank as $val)
            {
                $result['content'].=$val['rank_name'].'&nbsp    ';
            }
            $result['content'].='<br/>';
        }

       $result['content'].='���ʼʱ��Ϊ:'.local_date('Y-m-d H:i', $result['start_time']).',�����ʱ��Ϊ:'.local_date('Y-m-d H:i', $result['end_time']);

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
