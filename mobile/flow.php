<?php

/**
 * ECSHOP 商品页
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liuhui $
 * $Id: order.php 15013 2008-10-23 09:31:42Z liuhui $
*/

define('IN_ECS', true);


require(dirname(__FILE__) . '/includes/init.php');
require(ROOT_PATH . 'includes/lib_order.php');
require(ROOT_PATH . 'includes/lib_payment.php');
require(ROOT_PATH . 'includes/lib_transaction.php');

include_once(ROOT_PATH . 'languages/' .$_CFG['lang']. '/shopping_flow.php');
include_once(ROOT_PATH . 'languages/' .$_CFG['lang']. '/common.php');
$smarty->assign('lang', $_LANG);


$_LANG['goods_attr'] = '';

$flow_type = 0;

if ($_SESSION['user_id'] > 0)
{
    $smarty->assign('user_name', $_SESSION['user_name']);
}
else
{
    $goods_id = isset($_REQUEST['goods_id']) ? $_REQUEST['goods_id']:'';
    if(!empty($goods_id ) &&  $_REQUEST['act'] == 'add_goods')
    {
         $_SESSION['re_url']='goods.php?id='.$goods_id;
    }
    ecs_header("Location: user.php\n");
}

if (!isset($_REQUEST['act']))
{
    $_REQUEST['act'] = "add_order";
}

if($_REQUEST['act'] == 'add_goods')
{
    $goods_id = isset($_REQUEST['goods_id']) ? $_REQUEST['goods_id']:'';
    $number   =isset($_REQUEST['number']) ? intval($_REQUEST['number']):'';
    $spec=array();
    foreach ($_POST as $key=>$val)
    {
        if(preg_match('/^spec_[0-9]+/',$key))
        {
             $spec[]=intval($val);
        }
    }
    if($number<1)
    {
         mobile_error ('返回上一页','','输入的库存数量不对');
    }
    if($goods_id)
    {
        clear_cart();

        $state= is_stock($goods_id,$number,$spec);

        if($state==false)
        {
             mobile_error ('返回上一页','','库存不足');
        }
        elseif($state===2)
        {
             mobile_error ('返回上一页','','配件不允许单独销售');
        }
        elseif($state===3)
        {
            mobile_error ('返回上一页','','商品信息错误');
        }
        elseif($state===4)
        {
            mobile_error ('返回上一页','','商品已经下架，无法购买');
        }


        if(!addto_cart($goods_id,$number,$spec))
        {
            mobile_error ('返回上一页','','购买失败，请重新购买');
        }
        else
        {
            $goods_order = 1;
            ecs_header("Location: flow.php?act=add_order");
            exit;
        }
    }
    else
    {
          mobile_error ('返回上一页','','参数错误');
    }
}
elseif($_REQUEST['act'] == 'add_order')
{
     $cart_goods = cart_goods($flow_type);
     if(empty($cart_goods))
     {
        mobile_error ('返回首页','index.php','购物车里面无商品');

     }
     else
     {

       $smarty->assign('cart_goods', $cart_goods);
     }
     $consignee=get_consignee($_SESSION['user_id']);
     if($consignee['address_id'])
     {
        $_SESSION['mobile_flow_consignee']=$consignee;
        save_consignee($consignee, true);
     }
     else
     {
         $_SESSION['mobile_flow_consignee']= NULL;
     }

    $smarty->assign('consignee', $consignee);

    $region   = array($_SESSION['mobile_flow_consignee']['country'], $_SESSION['mobile_flow_consignee']['province'], $_SESSION['mobile_flow_consignee']['city'], $_SESSION['mobile_flow_consignee']['district']);

    if ($flow_type ==0)
    {
        $discount = compute_discount();
        $smarty->assign('discount', $discount['discount']);
        $favour_name = empty($discount['name']) ? '' : join(',', $discount['name']);
        $smarty->assign('your_discount', sprintf($_LANG['your_discount'], $favour_name, price_format($discount['discount'])));
    }





    $shipping_list     = available_shipping_list($region);
    $smarty->assign('shipping_list', $shipping_list);

    $payment_list=mobile_payment_list('0');
    $smarty->assign('payment_list', $payment_list); 



    if(!empty($shipping_list))
    {
        $default_shipping_id=$shipping_list['0']['shipping_id'];
        $smarty->assign('default_shipping_id',    $default_shipping_id);
    }

    if(!empty($payment_list))
    {
        $default_payment_id=$payment_list['0']['pay_id'];
        $smarty->assign('default_payment_id',    $default_payment_id);
    }


    $order = array(
        'shipping_id'     => isset($default_shipping_id) ? intval($default_shipping_id) : '0',
        'pay_id'          =>  '0',
        'pack_id'         => 0,
        'card_id'         =>  0,
        //'card_message'    => trim($_POST['card_message']),
        'surplus'         => '0.00',
        'integral'        =>  0,
        'bonus_id'        =>  0,
        'need_inv'        => 1,
        'inv_type'        => '',
        //'inv_payee'       => trim($_POST['inv_payee']),
        'inv_content'     =>'',
        //'postscript'      => trim($_POST['postscript']),
        'how_oos'         =>  '',
        'need_insure'     =>  0,
        'user_id'         => $_SESSION['user_id'],
        'add_time'        => gmtime(),
        'order_status'    => OS_UNCONFIRMED,
        'shipping_status' => SS_UNSHIPPED,
        'pay_status'      => PS_UNPAYED,
        'agency_id'       => get_agency_by_regions(array($consignee['country'], $consignee['province'], $consignee['city'], $consignee['district']))
        );

    $total = order_fee($order, $cart_goods, $consignee);
    $total['shipping_fee']  = price_format($total['shipping_fee'], false);
    $smarty->assign('total',    $total);


    $smarty->assign('common_header_title',    '订单详情');

    $smarty->display('order_info.dwt');
    //var_dump($_SESSION);
}
elseif($_REQUEST['act'] == 'select_shipping')
{
    $cart_goods = cart_goods($flow_type);
     if(empty($cart_goods))
     {
        mobile_error ('返回首页','index.php','购物车里面无商品');

     }
    $shipping     = isset($_REQUEST['shopping_id']) ? intval($_REQUEST['shopping_id']) : '0';

    $consignee = get_consignee($_SESSION['user_id']);

    $order = array(
        'shipping_id'     => isset($shipping) ? intval($shipping) : '0',
        'pay_id'          =>  '0',
        'pack_id'         => 0,
        'card_id'         =>  0,
        //'card_message'    => trim($_POST['card_message']),
        'surplus'         => '0.00',
        'integral'        =>  0,
        'bonus_id'        =>  0,
        'need_inv'        => 1,
        'inv_type'        => '',
        //'inv_payee'       => trim($_POST['inv_payee']),
        'inv_content'     =>'',
        //'postscript'      => trim($_POST['postscript']),
        'how_oos'         =>  '',
        'need_insure'     =>  0,
        'user_id'         => $_SESSION['user_id'],
        'add_time'        => gmtime(),
        'order_status'    => OS_UNCONFIRMED,
        'shipping_status' => SS_UNSHIPPED,
        'pay_status'      => PS_UNPAYED,
        'agency_id'       => get_agency_by_regions(array($consignee['country'], $consignee['province'], $consignee['city'], $consignee['district']))
        );

    $total = order_fee($order, $cart_goods, $consignee);

    $t=array();
    $total['shipping_fee']  = price_format($total['shipping_fee'], false);
    if(strtoupper(EC_CHARSET) == 'GBK')
    {
        $t['ordermon'] = iconv("gb2312","UTF-8",  $total['amount_formated']);
        $t['freightmon'] = iconv("gb2312","UTF-8",  $total['shipping_fee']);
    }
    else
    {
        $t['ordermon']=$total['amount_formated'];
        $t['freightmon']=$total['shipping_fee'];
    }
    echo json_encode($t);







}


elseif($_REQUEST['act']=='done')
{
   include_once('includes/lib_clips.php');

    /* 检查购物车中是否有商品 */
    $sql = "SELECT COUNT(*) FROM " . $ecs->table('cart') .
        " WHERE session_id = '" . SESS_ID . "' " .
        "AND parent_id = 0 AND is_gift = 0 AND rec_type = '$flow_type'";
    if ($db->getOne($sql) == 0)
    {
        mobile_error ('返回首页','index.php','您的购物车中没有商品');

    }

    /* 检查商品库存 */
    /* 如果使用库存，且下订单时减库存，则减少库存 */
    if ($_CFG['use_storage'] == '1' && $_CFG['stock_dec_time'] == SDT_PLACE)
    {
        $cart_goods_stock = get_cart_goods();
        $_cart_goods_stock = array();
        foreach ($cart_goods_stock['goods_list'] as $value)
        {
            $_cart_goods_stock[$value['rec_id']] = $value['goods_number'];
        }
        flow_cart_stock($_cart_goods_stock);
        unset($cart_goods_stock, $_cart_goods_stock);
    }



    $consignee = get_consignee($_SESSION['user_id']);



    $_POST['how_oos'] = isset($_POST['how_oos']) ? intval($_POST['how_oos']) : 0;
    $_POST['card_message'] = isset($_POST['card_message']) ? htmlspecialchars($_POST['card_message']) : '';
    $_POST['inv_type'] = !empty($_POST['inv_type']) ? htmlspecialchars($_POST['inv_type']) : '';
    $_POST['inv_payee'] = isset($_POST['inv_payee']) ? htmlspecialchars($_POST['inv_payee']) : '';
    $_POST['inv_content'] = isset($_POST['inv_content']) ? htmlspecialchars($_POST['inv_content']) : '';
    $_POST['postscript'] = isset($_POST['postscript']) ? htmlspecialchars($_POST['postscript']) : '';

    $order = array(
        'shipping_id'     => isset($_POST['shipping']) ? intval($_POST['shipping']) : '0',
        'pay_id'          => isset($_POST['payment']) ? intval($_POST['payment']) : '0',
        'pack_id'         => isset($_POST['pack']) ? intval($_POST['pack']) : 0,
        'card_id'         => isset($_POST['card']) ? intval($_POST['card']) : 0,
        //'card_message'    => trim($_POST['card_message']),
        'surplus'         => isset($_POST['surplus']) ? floatval($_POST['surplus']) : 0.00,
        'integral'        => isset($_POST['integral']) ? intval($_POST['integral']) : 0,
        'bonus_id'        => isset($_POST['bonus']) ? intval($_POST['bonus']) : 0,
        'need_inv'        => empty($_POST['need_inv']) ? 0 : 1,
        'inv_type'        => $_POST['inv_type'],
        //'inv_payee'       => trim($_POST['inv_payee']),
        'inv_content'     => $_POST['inv_content'],
        //'postscript'      => trim($_POST['postscript']),
        'how_oos'         => isset($_LANG['oos'][$_POST['how_oos']]) ? addslashes($_LANG['oos'][$_POST['how_oos']]) : '',
        'need_insure'     => isset($_POST['need_insure']) ? intval($_POST['need_insure']) : 0,
        'user_id'         => $_SESSION['user_id'],
        'add_time'        => gmtime(),
        'order_status'    => OS_UNCONFIRMED,
        'shipping_status' => SS_UNSHIPPED,
        'pay_status'      => PS_UNPAYED,
        'agency_id'       => get_agency_by_regions(array($consignee['country'], $consignee['province'], $consignee['city'], $consignee['district']))
        );


        if(empty($order['shipping_id']))
        {
           $sql="SELECT ua.`address_id` FROM ".$ecs->table('user_address') ." as ua LEFT JOIN ".$ecs->table('users') . " as u ON u.address_id =ua.address_id WHERE u.user_id='".$_SESSION['user_id']."'";
           $address_id=$db->getOne($sql);
           if(empty($address_id))
           {
               mobile_error ('返回购物','flow.php','请先选择收货地址，然后选择配送方式');
           }
           else
           {
               mobile_error ('返回购物','flow.php','未选择配送');
           }
        }
        if(empty($order['pay_id']))
        {
           mobile_error ('返回购物','flow.php','未选择支付方式');
        }
    /* 扩展信息 */
    if (isset($_SESSION['flow_type']) && intval($_SESSION['flow_type']) != CART_GENERAL_GOODS)
    {
        $order['extension_code'] = $_SESSION['extension_code'];
        $order['extension_id'] = $_SESSION['extension_id'];
    }
    else
    {
        $order['extension_code'] = '';
        $order['extension_id'] = 0;
    }

    /* 检查积分余额是否合法 */
    $user_id = $_SESSION['user_id'];
    if ($user_id > 0)
    {
        $user_info = user_info($user_id);

        $order['surplus'] = min($order['surplus'], $user_info['user_money'] + $user_info['credit_line']);
        if ($order['surplus'] < 0)
        {
            $order['surplus'] = 0;
        }

        // 查询用户有多少积分
        $flow_points = flow_available_points();  // 该订单允许使用的积分
        $user_points = $user_info['pay_points']; // 用户的积分总数

        $order['integral'] = min($order['integral'], $user_points, $flow_points);
        if ($order['integral'] < 0)
        {
            $order['integral'] = 0;
        }
    }
    else
    {
        $order['surplus']  = 0;
        $order['integral'] = 0;
    }


    /* 订单中的商品 */
    $cart_goods = cart_goods($flow_type);

    if (empty($cart_goods))
    {
         mobile_error ('返回首页','index.php','您的购物车中没有商品');

    }


    /* 收货人信息 */
    foreach ($consignee as $key => $value)
    {
        $order[$key] = addslashes($value);
    }

    /* 订单中的总额 */
    $total = order_fee($order, $cart_goods, $consignee);

//var_dump($total,$order,$cart_goods,$consignee);exit;


    $order['bonus']        = $total['bonus'];
    $order['goods_amount'] = $total['goods_price'];
    $order['discount']     = $total['discount'];
    $order['surplus']      = $total['surplus'];
    $order['tax']          = $total['tax'];


    /* 配送方式 */
    if ($order['shipping_id'] > 0)
    {
        $shipping = shipping_info($order['shipping_id']);
        $order['shipping_name'] = addslashes($shipping['shipping_name']);
    }

    $order['shipping_fee'] = $total['shipping_fee'];
    $order['insure_fee']   = $total['shipping_insure'];

    /* 支付方式 */
    if ($order['pay_id'] > 0)
    {
        $payment = mobile_payment_info($order['pay_id']);
        $order['pay_name'] = addslashes($payment['pay_name']);
    }
    if(empty($order['pay_name']))
    {
        mobile_error ('返回用户中心','user.php','非手机订单，无法在线支付');
    }


    $order['pay_fee'] = $total['pay_fee'];
    $order['cod_fee'] = $total['cod_fee'];

    $order['integral_money']   = $total['integral_money'];
    $order['integral']         = $total['integral'];

//    if ($order['extension_code'] == 'exchange_goods')
//    {
//        $order['integral_money']   = 0;
//        $order['integral']         = $total['exchange_integral'];
//    }
//
//    $order['from_ad']          = !empty($_SESSION['from_ad']) ? $_SESSION['from_ad'] : '0';
//    $order['referer']          = !empty($_SESSION['referer']) ? addslashes($_SESSION['referer']) : '';
//    
    $order['order_amount']  = number_format($total['amount'], 2, '.', '');
//    /* 如果全部使用余额支付，检查余额是否足够 */
//    if ($payment['pay_code'] == 'balance' && $order['order_amount'] > 0)
//    {
//        if($order['surplus'] >0) //余额支付里如果输入了一个金额
//        {
//            $order['order_amount'] = $order['order_amount'] + $order['surplus'];
//            $order['surplus'] = 0;
//        }
//        if ($order['order_amount'] > ($user_info['user_money'] + $user_info['credit_line']))
//        {
//            $tips = '您的余额不足以支付整个订单，以未付款方式下单';
//
//        }
//        else
//        {
//            $order['surplus'] = $order['order_amount'];
//            $order['order_amount'] = 0;
//        }
//    }
//    
//     /* 如果订单金额为0（使用余额或积分或红包支付），修改订单状态为已确认、已付款 */
//    if ($order['order_amount'] <= 0)
//    {
//        $order['order_status'] = OS_CONFIRMED;
//        $order['confirm_time'] = gmtime();
//        $order['pay_status']   = PS_PAYED;
//        $order['pay_time']     = gmtime();
//        $order['order_amount'] = 0;
//    }
//
//    $order['integral_money']   = $total['integral_money'];
//    $order['integral']         = $total['integral'];
//
//    if ($order['extension_code'] == 'exchange_goods')
//    {
//        $order['integral_money']   = 0;
//        $order['integral']         = $total['exchange_integral'];
//    }
//
//    $order['from_ad']          = !empty($_SESSION['from_ad']) ? $_SESSION['from_ad'] : '0';
//    $order['referer']          = !empty($_SESSION['referer']) ? addslashes($_SESSION['referer']) : '';
//
//    /* 记录扩展信息 */
//    if ($flow_type != CART_GENERAL_GOODS)
//    {
//        $order['extension_code'] = $_SESSION['extension_code'];
//        $order['extension_id'] = $_SESSION['extension_id'];
//    }
//
    $affiliate = unserialize($_CFG['affiliate']);


    /* 插入订单表 */
    $error_no = 0;
    do
    {
        $order['order_sn'] = get_order_sn(); //获取新订单号
        $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('order_info'), $order, 'INSERT');

        $error_no = $GLOBALS['db']->errno();

        if ($error_no > 0 && $error_no != 1062)
        {
            die($GLOBALS['db']->errorMsg());
        }
    }
    while ($error_no == 1062); //如果是订单号重复则重新提交数据

    $new_order_id = $db->insert_id();
    $order['order_id'] = $new_order_id;

    /* 插入订单商品 */
    $sql = "INSERT INTO " . $ecs->table('order_goods') . "( " .
                "order_id, goods_id, goods_name, goods_sn, goods_number, market_price, ".
                "goods_price, goods_attr, is_real, extension_code, parent_id, is_gift, goods_attr_id) ".
            " SELECT '$new_order_id', goods_id, goods_name, goods_sn, goods_number, market_price, ".
                "goods_price, goods_attr, is_real, extension_code, parent_id, is_gift, goods_attr_id".
            " FROM " .$ecs->table('cart') .
            " WHERE session_id = '".SESS_ID."' AND rec_type = '$flow_type'";
    $db->query($sql);



    if ($_CFG['use_storage'] == '1' && $_CFG['stock_dec_time'] == SDT_PLACE)
    {
        change_order_goods_storage($order['order_id'], true, SDT_PLACE);
    }


    /* 清空购物车 */
    clear_cart($flow_type);
//    /* 清除缓存，否则买了商品，但是前台页面读取缓存，商品数量不减少 */
    clear_all_files();
//
//    if(!empty($order['shipping_name']))
//    {
//        $order['shipping_name']=trim(stripcslashes($order['shipping_name']));
//    }
//    /* 取得支付信息，生成支付代码 */
//    if ($order['order_amount'] > 0)
//    {
//        $payment = payment_info($order['pay_id']);
//
//        include_once('includes/modules/payment/' . $payment['pay_code'] . '.php');
//
//        $pay_obj    = new $payment['pay_code'];
        $order['log_id'] = insert_pay_log($new_order_id, $order['order_amount'], PAY_ORDER);
        //$pay_online = $pay_obj->get_code($order, unserialize_config($payment['pay_config']));
//
//        $order['pay_desc'] = $payment['pay_desc'];
//
//        $smarty->assign('pay_online', $pay_online);
//    }

    /* 订单信息 */
    $smarty->assign('order',      $order);
    $smarty->assign('total',      $total);
    //$smarty->assign('goods_list', $cart_goods);
    //$smarty->assign('order_submit_back', sprintf('您可以 %s 或去 %s', '<a href="index.php">返回首页</a>', '<a href="user.php">用户中心</a>')); // 返回提示

    unset($_SESSION['flow_consignee']); // 清除session中保存的收货人信息
    unset($_SESSION['flow_order']);
    unset($_SESSION['direct_shopping']);

    if ($_SESSION['user_id'] > 0)
    {
        $smarty->assign('user_name', $_SESSION['user_name']);
    }
    $smarty->assign('footer', get_footer());


    $smarty->assign('common_header_title',    '订单支付');
    $smarty->assign('order', $order);
    $smarty->display('order_pay.dwt');
    exit;
}
elseif($_REQUEST['act'] == 'to_pay')
{
    $order_sn=isset($_REQUEST['order_sn']) && is_numeric($_REQUEST['order_sn']) ?$_REQUEST['order_sn']: '' ;
    if(empty($order_sn))
    {
        mobile_error ('返回用户中心','user.php','无效订单号');
        //echo '无效订单号';exit;
    }
    $sql="SELECT * FROM ". $ecs->table('order_info') . " WHERE `order_sn`='$order_sn' ";
    $order=$db->getRow($sql);
    $smarty->assign('order',      $order);
    if(empty($order) || $order['user_id']!=$_SESSION['user_id'])
    {
        mobile_error ('返回用户中心','user.php','错误订单号或者该订单不是您的订单');
    }
    

        $payment = mobile_payment_info($order['pay_id']);
        if(empty($payment))
        {
            mobile_error ('返回用户中心','user.php','非手机下单，该订单无法支付');
        }
        include_once('includes/modules/payment/' . $payment['pay_code'] . '.php');
        $pay_obj    = new $payment['pay_code'];

        $pay_online = $pay_obj->get_code($order, unserialize_config($payment['pay_config']));

        $smarty->assign('pay_online',      $pay_online);
        $smarty->display('to_pay.dwt');

}








function flow_available_points()
{
    $sql = "SELECT SUM(g.integral * c.goods_number) ".
            "FROM " . $GLOBALS['ecs']->table('cart') . " AS c, " . $GLOBALS['ecs']->table('goods') . " AS g " .
            "WHERE c.session_id = '" . SESS_ID . "' AND c.goods_id = g.goods_id AND c.is_gift = 0 AND g.integral > 0 " .
            "AND c.rec_type = '" . CART_GENERAL_GOODS . "'";

    $val = intval($GLOBALS['db']->getOne($sql));

    return integral_of_value($val);
}

/**
 * 检查订单中商品库存
 *
 * @access  public
 * @param   array   $arr
 *
 * @return  void
 */
function flow_cart_stock($arr)
{
    foreach ($arr AS $key => $val)
    {
        $val = intval(make_semiangle($val));
        if ($val <= 0)
        {
            continue;
        }

        $sql = "SELECT `goods_id`, `goods_attr_id`, `extension_code` FROM" .$GLOBALS['ecs']->table('cart').
               " WHERE rec_id='$key' AND session_id='" . SESS_ID . "'";
        $goods = $GLOBALS['db']->getRow($sql);

        $sql = "SELECT g.goods_name, g.goods_number, c.product_id ".
                "FROM " .$GLOBALS['ecs']->table('goods'). " AS g, ".
                    $GLOBALS['ecs']->table('cart'). " AS c ".
                "WHERE g.goods_id = c.goods_id AND c.rec_id = '$key'";
        $row = $GLOBALS['db']->getRow($sql);

        //系统启用了库存，检查输入的商品数量是否有效
        if (intval($GLOBALS['_CFG']['use_storage']) > 0 && $goods['extension_code'] != 'package_buy')
        {
            if ($row['goods_number'] < $val)
            {
                show_message(sprintf($GLOBALS['_LANG']['stock_insufficiency'], $row['goods_name'],
                $row['goods_number'], $row['goods_number']));
                exit;
            }

            /* 是货品 */
            $row['product_id'] = trim($row['product_id']);
            if (!empty($row['product_id']))
            {
                $sql = "SELECT product_number FROM " .$GLOBALS['ecs']->table('products'). " WHERE goods_id = '" . $goods['goods_id'] . "' AND product_id = '" . $row['product_id'] . "'";
                $product_number = $GLOBALS['db']->getOne($sql);
                if ($product_number < $val)
                {
                    show_message(sprintf($GLOBALS['_LANG']['stock_insufficiency'], $row['goods_name'],
                    $row['goods_number'], $row['goods_number']));
                    exit;
                }
            }
        }
        elseif (intval($GLOBALS['_CFG']['use_storage']) > 0 && $goods['extension_code'] == 'package_buy')
        {
            if (judge_package_stock($goods['goods_id'], $val))
            {
                show_message($GLOBALS['_LANG']['package_stock_insufficiency']);
                exit;
            }
        }
    }

}

function is_stock($goods_id,$num,$spec=array()) 
{

    $sql="SELECT goods_number,is_alone_sale,is_on_sale FROM " .$GLOBALS['ecs']->table('goods'). " WHERE goods_id = '$goods_id'";

    $goods=$GLOBALS['db']->getRow($sql);
    if(empty($goods))
    {
         return 3;
    }
    if ($goods['is_on_sale'] == 0)
    {
        return 4;
    }


    if( $goods['is_alone_sale']==0)
    {    
        
        return 2;// 不是配件时检查是否允许单独销售
    }

    if ($GLOBALS['_CFG']['use_storage'] == 1)
    {
        $sql = "SELECT * FROM " .$GLOBALS['ecs']->table('products'). " WHERE goods_id = '$goods_id' LIMIT 0, 1";
        $prod = $GLOBALS['db']->getRow($sql);
    }
    else
    {
        return true;
    }

    if (is_spec($spec) && !empty($prod))
    {
        $product_info = get_products_info($goods_id, $spec);
    }
        //检查：商品购买数量是否大于总库存
        if ($num > $goods['goods_number'])
        {
           // $GLOBALS['err']->add(sprintf($GLOBALS['_LANG']['shortage'], $goods['goods_number']), ERR_OUT_OF_STOCK);

            return false;
        }

        //商品存在规格 是货品 检查该货品库存
        if (is_spec($spec) && !empty($prod))
        {
            if (!empty($spec))
            {
                /* 取规格的货品库存 */
                if ($num > $product_info['product_number'])
                {
                    //$GLOBALS['err']->add(sprintf($GLOBALS['_LANG']['shortage'], $product_info['product_number']), ERR_OUT_OF_STOCK);
                    return false;
                }
            }
        }       


    return  true;
}

function mobile_payment_list($support_cod, $cod_fee = 0, $is_online = false)
{
    $sql = 'SELECT pay_id, pay_code, pay_name, pay_fee, pay_desc, pay_config, is_cod' .
            ' FROM ' . $GLOBALS['ecs']->table('mobile_payment') .
            ' WHERE enabled = 1 ';
    if (!$support_cod)
    {
        $sql .= 'AND is_cod = 0 '; // 如果不支持货到付款
    }
    if ($is_online)
    {
        $sql .= "AND is_online = '1' ";
    }
    $sql .= 'ORDER BY pay_order'; // 排序
    $res = $GLOBALS['db']->query($sql);

    $pay_list = array();
    while ($row = $GLOBALS['db']->fetchRow($res))
    {
        if ($row['is_cod'] == '1')
        {
            $row['pay_fee'] = $cod_fee;
        }

        $row['format_pay_fee'] = strpos($row['pay_fee'], '%') !== false ? $row['pay_fee'] :
        price_format($row['pay_fee'], false);
        $modules[] = $row;
    }

    include_once(ROOT_PATH.'includes/lib_compositor.php');

    if(isset($modules))
    {
        return $modules;
    }
}


function mobile_payment_info($pay_id)
{
    $sql = 'SELECT * FROM ' . $GLOBALS['ecs']->table('mobile_payment') .
            " WHERE pay_id = '$pay_id' AND enabled = 1";

    return $GLOBALS['db']->getRow($sql);
}








?>