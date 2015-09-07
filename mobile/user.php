<?php

/**
 * ECSHOP �û�����
 * ============================================================================
 * * ��Ȩ���� 2005-2012 �Ϻ���������Ƽ����޹�˾������������Ȩ����
 * ��վ��ַ: http://www.ecshop.com��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�
 * ʹ�ã�������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
 * ============================================================================
 * $Author: liubo $
 * $Id: user.php 16643 2009-09-08 07:02:13Z liubo $
*/

define('IN_ECS', true);


require(dirname(__FILE__) . '/includes/init.php');
/* ���������ļ� */
include_once(ROOT_PATH . 'languages/' .$_CFG['lang']. '/common.php');
include_once(ROOT_PATH . 'languages/' .$_CFG['lang']. '/shopping_flow.php');
require_once(ROOT_PATH . 'languages/' .$_CFG['lang']. '/user.php');
$smarty->assign('lang', $_LANG);


$_LANG['order_already_received'] = '�˶����Ѿ�ȷ�Ϲ��ˣ���л���ڱ�վ�����ӭ�ٴι��١�';

$act = isset($_GET['act']) ? $_GET['act'] : '';
$ui_arr=array('order_info','cancel_order','affirm_received','address_list','to_pay','edit_address','add_address','drop_consignee','act_edit_address');
if(in_array($act,$ui_arr) && empty($_SESSION['user_id']))
{
    $act = 'login';
}






if($act == 'login')
{
    if(isset($_SESSION['login_fail']) && $_SESSION['login_fail'] >=3)
    {
        $login_captcha=1;
    }
    else
    {
        $login_captcha=0;
    }


    $smarty->assign('common_header_title',    '�û���¼');
    $smarty->assign('login_captcha',    $login_captcha);

    $smarty->display('login.dwt');
}
elseif ($act == 'do_login')
{
    $mobile_phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $captcha_word= isset( $_REQUEST['code']) ?$_REQUEST['code']:'';
    if(isset($_SESSION['login_fail'])&& $_SESSION['login_fail'] >=3 )
    {
        if(!check_captcha_word($captcha_word))
        {
            mobile_error ($act='�����µ�¼',$url='user.php',$name='��֤�����');
        }
    }

    if(is_numeric($mobile_phone) && strlen($mobile_phone)==11)
    {
        $sql="SELECT `user_name` FROM ".$ecs->table('users'). " WHERE `mobile_phone`='$mobile_phone'";
        $user_name=$db->getOne($sql);
        if(empty($user_name))
        {
             $user_name=$mobile_phone;
        }
    }
    else
    {
         $user_name=$mobile_phone;
    }

    $pwd = !empty($_POST['password']) ? trim($_POST['password']) : '';

    if (empty($user_name) || empty($pwd))
    {
        $login_faild = 1;
        if( !isset($_SESSION['login_fail']) )
        {
            $_SESSION['login_fail']=1;
        }
        else
        {
            $_SESSION['login_fail'] ++;
        }
        mobile_error ($act='�����µ�¼',$url='user.php',$name='δ�ҵ����û�');
    }
    else
    {
        if ($user->check_user($user_name, $pwd) > 0)
        {

            $user->set_session($user_name);
            $user->set_cookie($user_name);
            update_user_info();
            if(isset($_SESSION['re_url']) && !empty($_SESSION['re_url']))
            {
                $url=$_SESSION['re_url'];
                unset($_SESSION['re_url']);
                mobile_error ($act='������һҳ',$url,$name='��¼�ɹ�');
            }
            show_user_center();
        }
        else
        {
            //��¼ʧ��
            if( !isset($_SESSION['login_fail']) )
            {
                $_SESSION['login_fail']=1;
            }
            else
            {
                $_SESSION['login_fail'] ++;
            }

           mobile_error ('�����û���¼',$url='user.php','�û��������������');
        }
    }
}
elseif($act=='find_pwd')
{
    if ($_SESSION['user_id'] > 0)
    {
        show_user_center();
    }
    else
    {
        $smarty->assign('common_header_title',    '�һ�����');
        $smarty->assign('footer', get_footer());
        $smarty->display('find_pwd.dwt');
    }
}
elseif($act=='send_pwd_mobile')
{
    //�һ����뷢����

    $captcha_word= isset( $_REQUEST['code']) ?$_REQUEST['code']:'';
    $mobile_num=isset($_REQUEST['phone']) && is_numeric($_REQUEST['phone']) ?$_REQUEST['phone']:'';

    if(!check_captcha_word($captcha_word))
    {
         echo 'fail1';exit;
    }
    if(!is_mobile($mobile_num))
    {
         echo 'fail3';exit;
    }


    $user_id='';
    if(is_mobile($mobile_num))
    {
         $sql="SELECT `user_id`  FROM " .$ecs->table('users'). " WHERE `mobile_phone`='$mobile_num'";
         $user_id=$db->getOne($sql);
    }
    else
    {

         echo 'fail2';exit;
    }

    if(!empty($user_id))
    {
       $now_time=time();
       $sql="SELECT `id` FROM ".$ecs->table('user_send')." WHERE `user_id`='$user_id' AND `send_time`> ($now_time-1800) AND `check_start`=0";
       $count=$db->getOne($sql);
        if($count)
        {
             echo 'successful';exit;
        }
        else
        {
             /*����У����*/
             $send_num=makecode(6);
             /*�������ݿ�,��װ�����ɱ�*/
             $send_num=strtolower($send_num);
             include_once(ROOT_PATH . 'includes/cls_sms.php');
             $sms = new sms();
             $send_content='�����ֻ���֤��Ϊ:'.$send_num.' �뾡����֤';
             $state=$sms->send($mobile_num, $send_content,'', 13,1);

             if($state=='error' || $state==false)
             {
                 echo 'send_error';
                 exit;
             }

             $sql="INSERT INTO ".$ecs->table('user_send')." (`user_id`,`send_num`,`send_time`,`check_start`,`check_time`,`mobile_phone`) VALUES('$user_id','$send_num','$now_time','0','0','$mobile_num')";

             $db->query($sql);
             /*���Ͷ���*/
             echo 'success';exit;
        }
    }
    else
    {
         echo 'fail3';exit;
    }

}
elseif($act=='do_find_pwd')
{

    $send_num=isset($_REQUEST['phone-code']) ? trim($_REQUEST['phone-code']):'1';
    $send_num=strtolower(htmlspecialchars($send_num));

    $mobile_num=isset($_REQUEST['phone']) && is_numeric($_REQUEST['phone']) ?$_REQUEST['phone']:'';

    $now_time=time();
    if (strlen($send_num) >8)
    {
        mobile_error ('�����û���¼',$url='user.php','��֤���ʽ����');
    }

    $sql="SELECT `id`,`user_id` FROM ".$ecs->table('user_send')." WHERE `check_start`=0 AND `send_time`> ($now_time-1800) AND `send_num`='$send_num' AND `mobile_phone`='$mobile_num' ";
    $result=$db->getRow($sql);

    if(empty($result['id']))
    {
        mobile_error ('�����û���¼',$url='user.php','δ�ҵ��û�');
    }

    $user_id=$result['user_id'];
    if(empty($user_id))
    {
        mobile_error ('�����û���¼',$url='user.php','δ�ҵ��û�');
    }
    else
    {
        /*��������*/
        $sql="SELECT `user_name` FROM ".$ecs->table('users'). " WHERE user_id ='$user_id'";
        $_SESSION['user_name']=$db->getOne($sql);
        $old_password =  null;
        $new_password = isset($_POST['password']) ? trim($_POST['password']) : '';
        //$code         = isset($_POST['code']) ? trim($_POST['code'])  : '';
        if (strlen($new_password) < 6)
        {
             mobile_error ('�����û���¼',$url='user.php','���벻����λ');
        }

        if ($user->edit_user(array('username'=> $_SESSION['user_name'], 'old_password'=>$old_password, 'password'=>$new_password),  0 ))
        {
			$sql="UPDATE ".$ecs->table('users'). "SET `ec_salt`='0' WHERE user_id= '".$user_id."'";
            $db->query($sql);
            $sql="UPDATE ".$ecs->table('user_send'). " SET `check_start`=1, `check_time`='now_time' WHERE `send_num`='$send_num'";
			$db->query($sql);
            $user->logout();

             mobile_error ('�����û���¼',$url='user.php','���ĳɹ�');
        }
        else
        {
             mobile_error ('�����û���¼',$url='user.php','����ʧ��');
        }
    }
}



elseif($act=='order_info')
{
    $order_id=intval($_GET['id']);
    $sql=    $sql = "SELECT  pay_id, pay_name,shipping_name,order_id, order_sn, order_status, shipping_status, pay_status, add_time, " .
           "(goods_amount + shipping_fee + insure_fee + pay_fee + pack_fee + card_fee + tax - discount) AS total_fee ".
           " FROM " .$GLOBALS['ecs']->table('order_info') .
           " WHERE user_id = '".$_SESSION['user_id']."'  AND order_id='$order_id' ORDER BY add_time DESC";
    $result=$db->getRow($sql);


    if(!empty($result))
    {

      if($result['order_status']!=2 && $result['order_status']!=3 && $result['order_status']!=4 && $result['pay_status']==0 )
      {
           $con_pay=1;
      }
      else
      {
           $con_pay=0;
      }
      $sql="SELECT `pay_id` FROM ".$GLOBALS['ecs']->table('mobile_payment') ." WHERE `pay_id`= ".$result['pay_id'];
      $pay=$db->getOne($sql);
      if($con_pay && !$pay)
      {
          $con_pay=2;
      }




      if($result['pay_status']==2 && $result['shipping_status']==1 )
      {
           $con_shipping=1;
      }
      else
      {
           $con_shipping=0;
      }


      $result['order_status'] = $GLOBALS['_LANG']['os'][$result['order_status']] . ',' . $GLOBALS['_LANG']['ps'][$result['pay_status']] . ',' . $GLOBALS['_LANG']['ss'][$result['shipping_status']];
      $sql="SELECT g.goods_id ,g.goods_name,g.goods_thumb as goods_thumb, o.goods_price,o.goods_number FROM ".$GLOBALS['ecs']->table('order_goods')." as o  LEFT JOIN ".$GLOBALS['ecs']->table('goods')." as g ON o.goods_id=g.goods_id    WHERE  o.order_id=".$result['order_id'];
      $goods_thumb=$db->getAll($sql);
      foreach ($goods_thumb as $key=>$val)
      {
        $goods_thumb[$key]['goods_price']=price_format($val['goods_price'], false);
      }
      $smarty->assign('goods_thumb', $goods_thumb);
    }
    else
    {
        mobile_error ('�����û�����','user.php','û�������Ķ���');
    }
    $smarty->assign('con_pay', $con_pay);
    $smarty->assign('con_shipping', $con_shipping);
    $smarty->assign('order_info', $result);
    $smarty->assign('common_header_title',    '��������');
    $smarty->display('user_order.dwt');
}
elseif($act=='to_pay')
{
    $order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
    $sql="SELECT `order_sn` FROM ".$ecs->table('order_info')." WHERE `order_id`=$order_id AND `user_id` ='$_SESSION[user_id]' AND  pay_status=0 ";
    $order_sn=$db->getOne($sql);
    if(!empty($order_sn))
    {
        $Loaction='flow.php?act=to_pay&order_sn='.$order_sn;
        ecs_header("Location: $Loaction\n");
    }
    else
    {
         mobile_error ('�����û�����','user.php','û�������Ķ���');
    }

}


/* ȡ������ */
elseif ($act == 'cancel_order')
{
    include_once(ROOT_PATH . 'includes/lib_transaction.php');
    include_once(ROOT_PATH . 'includes/lib_order.php');

    $order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
    if (cancel_order($order_id, $_SESSION['user_id']))
    {
        ecs_header("Location: user.php?act=order_list\n");
        exit;
    }
}

/* ȷ���ջ� */
elseif ($act == 'affirm_received')
{
    include_once(ROOT_PATH . 'includes/lib_transaction.php');

    $order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
    $_LANG['buyer'] = '���';
    if (affirm_received($order_id, $_SESSION['user_id']))
    {
        mobile_error ('�����û�����',$url='user.php','ȷ���ջ��ɹ�');
    }
    else
    {
        mobile_error ('�����û�����',$url='user.php','ȷ���ջ�ʧ��');

    }

}

/* �˳���Ա���� */
elseif ($act == 'logout')
{
    if (!isset($back_act) && isset($GLOBALS['_SERVER']['HTTP_REFERER']))
    {
        $back_act = strpos($GLOBALS['_SERVER']['HTTP_REFERER'], 'user.php') ? './index.php' : $GLOBALS['_SERVER']['HTTP_REFERER'];
    }

    $user->logout();
    $Loaction = 'index.php';
    ecs_header("Location: $Loaction\n");

}
/* ��ʾ��Աע����� */
elseif ($act == 'register')
{
    if (!isset($back_act) && isset($GLOBALS['_SERVER']['HTTP_REFERER']))
    {
        $back_act = strpos($GLOBALS['_SERVER']['HTTP_REFERER'], 'user.php') ? './index.php' : $GLOBALS['_SERVER']['HTTP_REFERER'];
    }
    $smarty->assign('footer', get_footer());
    $smarty->assign('common_header_title',    '��Աע��');
    $smarty->display('user_passport.dwt');

}
elseif($act == 're_send_mobile')
{
    //ע�ᷢ�Ͷ���
    $captcha_word=isset($_REQUEST['code'])?$_REQUEST['code']:'';

    $mobile_num=isset($_REQUEST['phone'])&& is_numeric($_REQUEST['phone']) ? $_REQUEST['phone'] :'';
    if(!check_captcha_word($captcha_word))
    {
        echo 'fail_captcha';
        exit;
    }
    $user_id='';
    if(!is_mobile($mobile_num))
    {
        echo 'fail_phone';
        exit;
    }


       $now_time=time();
       $sql="SELECT `id` FROM ".$ecs->table('user_send')." WHERE `send_num`='$mobile_num' AND `send_time`> ($now_time-1800) AND `check_start`=0";
       $count=$db->getOne($sql);
        if($count)
        {
            echo "successful";
            exit;
        }
        else
        {
             /*����У����*/
             $send_num=makecode(6);
             /*�������ݿ�,��װ�����ɱ�*/
             $send_num=strtolower($send_num);
             include_once(ROOT_PATH . 'includes/cls_sms.php');

             /*���Ͷ���*/
             $sms = new sms();
             $send_content='�����ֻ���֤��Ϊ:'.$send_num.' �뾡����֤';
             $state=$sms->send($mobile_num, $send_content,'', 13,1);

             if($state=='error' || $state==false)
             {
                 echo 'send_error';
                 exit;
             }
             
             $sql="INSERT INTO ".$ecs->table('user_send')." (`user_id`,`send_num`,`mobile_phone`,`send_time`,`check_start`,`check_time`) VALUES('0','$send_num','$mobile_num','$now_time','0','0')";
             $db->query($sql);
             echo "success";
             exit;
        }
}

/* ע���Ա�Ĵ��� */
elseif ($act == 'act_register')
{
        $now_time=time();
        include_once(ROOT_PATH . 'includes/lib_passport.php');
        $username = 'mbl'.local_date('ymd').makecode(6);
        $password = isset($_POST['password']) ? trim($_POST['password']) : '';
        $email    = 'mbl'.makecode(6).'@mbl.com';
        $phone_code=isset($_POST['phone-code']) ? trim($_POST['phone-code']) :'';
        $mobile_phone=isset($_POST['phone']) ? $_POST['phone'] : '';
        if(!is_mobile($mobile_phone))
        {
             mobile_error ('�����û���¼',$url='user.php','����д�ķ��ֻ�����');
        }
        if(strlen($phone_code)>6)
        {
             mobile_error ('�����û���¼',$url='user.php','�ֻ���֤�����');
        }
        else
        {
            
            $sql="SELECT count(`id`)  FROM ".$ecs->table('user_send')." WHERE `send_num`='$phone_code' AND `send_time` > ($now_time-1800) AND  `mobile_phone`='$mobile_phone' " ;
            $result=$db->getOne($sql);
            if(!$result)
            {
               mobile_error ('�����û���¼',$url='user.php','�ֻ���֤����');
            }
        }
        if(strlen($password)>22)
        {
               mobile_error ('�����û���¼',$url='user.php','�������22λ');
        }
        if(strlen($password)<6)
        {
               mobile_error ('�����û���¼',$url='user.php','����С��6λ');
        }
        //$other['msn'] = isset($_POST['extend_field1']) ? $_POST['extend_field1'] : '';
        //$other['qq'] = isset($_POST['extend_field2']) ? $_POST['extend_field2'] : '';
        //$other['office_phone'] = isset($_POST['extend_field3']) ? $_POST['extend_field3'] : '';
        //$other['home_phone'] = isset($_POST['extend_field4']) ? $_POST['extend_field4'] : '';
        

        $other['mobile_phone'] = $mobile_phone;
        $back_act = isset($_POST['back_act']) ? trim($_POST['back_act']) : '';


        if (m_register($username, $password, $email, $other) !== false)
        {
            $sql="UPDATE ".$ecs->table('user_send'). " SET check_start=1,`check_time`='$now_time' WHERE `send_num`='$phone_code' AND `send_time` > ($now_time-1800)";
            $db->query($sql);
            $ucdata = empty($user->ucdata)? "" : $user->ucdata;
            if(isset($_SESSION['re_url']) && !empty($_SESSION['re_url']))
            {
                $url=$_SESSION['re_url'];
                unset($_SESSION['re_url']);
                mobile_error ($act='������һҳ',$url,$name='ע��ɹ�');
            }
        }
        else
        {
               mobile_error ('�����û���¼',$url='user.php','ע��ʧ��');
        }
}

elseif ($act == 'captcha')
{
    include(ROOT_PATH . 'includes/cls_captcha.php');

    $img = new captcha('../data/captcha/');
    @ob_end_clean(); //���֮ǰ���ֵĶ�������
    $img->generate_image();
    exit;
}
elseif($act == 'check_code')
{
    $captcha_word=isset($_REQUEST['code'])?$_REQUEST['code']:'';

    if(!check_captcha_word($captcha_word))
    {
        echo 'error';
    }
    else
    {
        echo 'success';
    }
    exit;

}
elseif($act == 'check_phone-code')
{        
    $now_time=time();
    $phone_code=isset($_REQUEST['pcode']) ? trim($_REQUEST['pcode']) :'';
    $mobile_phone=isset($_REQUEST['phone']) ? $_REQUEST['phone'] : '';
    if(!is_mobile($mobile_phone))
    {
       echo 'fail1';
    }
    if(strlen($phone_code)>6)
    {
        echo 'fail2';
    }
    else
    {
        
        $sql="SELECT count(`id`)  FROM ".$ecs->table('user_send')." WHERE `send_num`='$phone_code' AND `send_time` > ($now_time-1800) AND  `mobile_phone`='$mobile_phone' " ;
        $result=$db->getOne($sql);
        if(!$result)
        {
           echo 'fail3';
        }
        else
        {
            echo 'success';
        }
    }
}
elseif($act=='phone')
{
    $phone=isset($_REQUEST['phone']) ? trim($_REQUEST['phone']) :'';
    if(is_mobile($phone))
    {
        $sql="SELECT `user_id` FROM " .$ecs->table('users'). " WHERE `mobile_phone` ='$phone'";
        $result=$db->getOne($sql);
        if(!empty($result))
        {
             echo 'fail2';
        }
        else
        {
            echo 'success';
        }

    }
    else
    {
        echo 'fail';

    }

}
elseif($act=='find_phone')
{
    $phone=isset($_REQUEST['phone']) ? trim($_REQUEST['phone']) :'';
    if(is_mobile($phone))
    {
        $sql="SELECT `user_id` FROM " .$ecs->table('users'). " WHERE `mobile_phone` ='$phone'";
        $result=$db->getOne($sql);
        if(empty($result))
        {
             echo 'fail2';
        }
        else
        {
            echo 'success';
        }

    }
    else
    {
        echo 'fail';

    }

}



elseif($act == 'address_list')
{
    $sql="SELECT * FROM ".$ecs->table('user_address')." WHERE `user_id`=$_SESSION[user_id]";
    $address_list=$db->getAll($sql);

    $re_url=isset($_REQUEST['re_url'])?$_REQUEST['re_url']:0;

    if(!empty($re_url))
    {
        $smarty->assign('re_url',    $re_url);
    }
    else
    {
        $smarty->assign('re_url',    '0');
    }




    foreach ( $address_list as $key=>$val)
    {
        $address_list[$key]['country']=$db->getOne("SELECT `region_name` FROM ".$ecs->table('region')." WHERE `region_id`='$val[country]'");
        $address_list[$key]['province']=$db->getOne("SELECT `region_name` FROM ".$ecs->table('region')." WHERE `region_id`='$val[province]'");
        $address_list[$key]['city']=$db->getOne("SELECT `region_name` FROM ".$ecs->table('region')." WHERE `region_id`='$val[city]'");
        $address_list[$key]['district']=$db->getOne("SELECT `region_name` FROM ".$ecs->table('region')." WHERE `region_id`='$val[district]'");
    }

    $GLOBALS['smarty']->assign('address_list', $address_list);
    $smarty->assign('common_header_title',    '�ջ���ַ�б�');
    $smarty->display('address_list.dwt');
}
elseif($act == 'add_address')
{
    $re_url=isset($_REQUEST['re_url'])?$_REQUEST['re_url']:'';
    $smarty->assign('re_url',    $re_url);

    $smarty->assign('country_list',       get_regions());
    $consignee['country']  = isset($consignee['country'])  ? intval($consignee['country'])  : 0;
    $consignee['province'] = isset($consignee['province']) ? intval($consignee['province']) : 0;
    $consignee['city']     = isset($consignee['city'])     ? intval($consignee['city'])     : 0;

    $province_list[0] = get_regions(1, $consignee['country']);
    $city_list[0]     = get_regions(2, $consignee['province']);
    $district_list[0] = get_regions(3, $consignee['city']);
    $smarty->assign('act',    'add_address');
    $smarty->assign('sn',    '0');
    $smarty->assign('province_list',    $province_list);
    $smarty->assign('city_list',        $city_list);
    $smarty->assign('district_list',    $district_list);

    $smarty->assign('common_header_title',    '�ջ���ַ');

    $smarty->display('address_add.dwt');
}
elseif($act == 'edit_address')
{

    $address_id=isset($_REQUEST['address_id'])?intval($_REQUEST['address_id']):'';
    $re_url=isset($_REQUEST['re_url'])?$_REQUEST['re_url']:'';
    $smarty->assign('re_url',    $re_url);

    $sql="SELECT * FROM ".$ecs->table('user_address')." WHERE `address_id`= '$address_id' AND  `user_id`=".$_SESSION['user_id'];
    $consignee=$db->getRow($sql);
    if(empty($consignee))
    {
        mobile_error ('�����û�����',$url='user.php','���͵�ַ������');
    }
    $smarty->assign('country_list',       get_regions());
    $consignee['country']  = isset($consignee['country'])  ? intval($consignee['country'])  : 0;
    $consignee['province'] = isset($consignee['province']) ? intval($consignee['province']) : 0;
    $consignee['city']     = isset($consignee['city'])     ? intval($consignee['city'])     : 0;

    $smarty->assign('consignee',     $consignee);
    $province_list[0] = get_regions(1, $consignee['country']);
    $city_list[0]     = get_regions(2, $consignee['province']);
    $district_list[0] = get_regions(3, $consignee['city']);

    $smarty->assign('address_id',    $address_id);
    $smarty->assign('sn',    '0');
    $smarty->assign('province_list',    $province_list);
    $smarty->assign('city_list',        $city_list);
    $smarty->assign('district_list',    $district_list);
    $smarty->assign('common_header_title',    '�ջ���ַ');
    $smarty->display('address_add.dwt');
}
elseif($act == 'act_edit_address')
{
    include_once(ROOT_PATH . 'includes/lib_transaction.php');
    $address_id=isset($_REQUEST['address_id'])?intval($_REQUEST['address_id']):'';
    $re_url=isset($_REQUEST['re_url'])?$_REQUEST['re_url']:'';


    if(!empty($address_id))
    {
        $sql="SELECT * FROM ".$ecs->table('user_address')." WHERE `address_id`= $address_id AND  `user_id`=".$_SESSION['user_id'];
        $consignee=$db->getRow($sql);
        if(empty($consignee))
        {
            mobile_error ('�����û�����',$url='user.php?act=address_list&re_url='.$re_url,'���͵�ַ������');
        }
    }

    $address = array(
        'user_id'    => $_SESSION['user_id'],
        'address_id' => isset($_POST['address_id'])?intval($_POST['address_id']):0,
        'country'    => isset($_POST['country'])   ? intval($_POST['country'])  : 0,
        'province'   => isset($_POST['province'])  ? intval($_POST['province']) : 0,
        'city'       => isset($_POST['city'])      ? intval($_POST['city'])     : 0,
        'district'   => isset($_POST['district'])  ? intval($_POST['district']) : 0,
        'address'    => isset($_POST['address'])   ? compile_str(trim($_POST['address']))    : '',
        'consignee'  => isset($_POST['consignee']) ? compile_str(trim($_POST['consignee']))  : '',
        'email'      => isset($_POST['email'])     ? compile_str(trim($_POST['email']))      : '',
        'tel'        => isset($_POST['tel'])       ? compile_str(make_semiangle(trim($_POST['tel']))) : '',
        'mobile'     => isset($_POST['mobile'])    ? compile_str(make_semiangle(trim($_POST['mobile']))) : '',
        'best_time'  => isset($_POST['best_time']) ? compile_str(trim($_POST['best_time']))  : '',
        'sign_building' => isset($_POST['sign_building']) ? compile_str(trim($_POST['sign_building'])) : '',
        'zipcode'       => isset($_POST['zipcode'])       ? compile_str(make_semiangle(trim($_POST['zipcode']))) : '',
        'default'   => !empty($re_url)  ? 1 : 0,
        );


    if (update_address($address))
    {
        if(empty($re_url))
        {
            mobile_error ('�����û�����',$url='user.php?act=address_list','�޸����͵�ַ�ɹ�');
        }
        else
        {
            if($re_url=='flow')
            {
               mobile_error ('���ض���',$url='flow.php?act=add_order','�Ѿ�ʹ�øõ�ַ');
            }
            else
            {
               mobile_error ('�����û�����',$url='user.php?act=address_list','�޸����͵�ַ�ɹ�');
            }
        }

    }
    else
    {
        mobile_error ('�����û�����',$url='user.php?act=address_list&re_url='.$re_url,'�޸����͵�ַʧ��');
    }
}
elseif($act=='drop_consignee')
{
    $address_id=isset($_REQUEST['address_id'])?intval($_REQUEST['address_id']):'';

    $sql="SELECT * FROM ".$ecs->table('user_address')." WHERE `address_id`= '$address_id' AND  `user_id`=".$_SESSION['user_id'];
    $consignee=$db->getRow($sql);
    if(empty($consignee))
    {
        mobile_error ('�����û�����',$url='user.php?act=address_list','����Ȩɾ�����ߵ�ַ������');
    }
    else
    {
        $sql="DELETE FROM ".$ecs->table('user_address'). " WHERE `address_id`= '$address_id'";
        $db->query($sql);
        mobile_error ('�����û�����',$url='user.php?act=address_list','��ַɾ���ɹ�');
    }


}


/* �û����� */
else
{

        if ($_SESSION['user_id'] > 0)
        {
            show_user_center();
        }
        else
        {
            $smarty->assign('common_header_title',    '�û���¼');
            if(isset($_SESSION['login_fail']) && $_SESSION['login_fail'] >=3)
            {
                $login_captcha=1;
            }
            else
            {
                $login_captcha=0;
            }
            $smarty->assign('login_captcha',    $login_captcha);
            $smarty->display('login.dwt');
       }

}

/**
 * �û�������ʾ
 */
function show_user_center()
{
    order_list();
    $GLOBALS['smarty']->assign('user_name', $_SESSION['user_name']);
//    //$best_goods = get_recommend_goods('best');
//    if (count($best_goods) > 0)
//    {
//        foreach  ($best_goods as $key => $best_data)
//        {
//            $best_goods[$key]['shop_price'] = encode_output($best_data['shop_price']);
//            $best_goods[$key]['name'] = encode_output($best_data['name']);
//        }
//    }
//
//
//
//    $GLOBALS['smarty']->assign('best_goods' , $best_goods);

    $GLOBALS['smarty']->assign('common_header_title', '�û�����');
    $GLOBALS['smarty']->assign('footer', get_footer());
    $GLOBALS['smarty']->display('user.dwt');
}

/**
 * �ֻ�ע��
 */
function m_register($username, $password, $email, $other)
{
    /* ���username */
    if (empty($username))
    {

         mobile_error ('�����û���¼',$url='user.php','�û�������Ϊ��');


    }
    if (preg_match('/\'\/^\\s*$|^c:\\\\con\\\\con$|[%,\\*\\"\\s\\t\\<\\>\\&\'\\\\]/', $username))
    {
         mobile_error ('�����û���¼',$url='user.php','�û�������');
    }

    /* ���email */
    if (empty($email))
    {
         mobile_error ('�����û���¼',$url='user.php','email����Ϊ��');

    }
    if(!is_email($email))
    {
         mobile_error ('�����û���¼',$url='user.php','email����');

    }

    /* ����Ƿ�͹���Ա���� */
    if (admin_registered($username))
    {
         mobile_error ('�����û���¼',$url='user.php','���û��Ѵ���');
    }
    
    if(!is_mobile($other['mobile_phone']))
    {
         mobile_error ('�����û���¼',$url='user.php','�ֻ��Ŵ���');
    }


    if (!$GLOBALS['user']->add_user($username, $password, $email))
    {
         mobile_error ('�����û���¼',$url='user.php','ע��ʧ��');
    }
    else
    {
        //ע��ɹ�

        /* ���óɵ�¼״̬ */
        $GLOBALS['user']->set_session($username);
        $GLOBALS['user']->set_cookie($username);

     }

        //����other�Ϸ��ı�������
        $other_key_array = array('msn', 'qq', 'office_phone', 'home_phone', 'mobile_phone');
        $update_data['reg_time'] = local_strtotime(local_date('Y-m-d H:i:s'));
        if ($other)
        {
            foreach ($other as $key=>$val)
            {
                //ɾ���Ƿ�keyֵ
                if (!in_array($key, $other_key_array))
                {
                    unset($other[$key]);
                }
                else
                {
                    $other[$key] =  htmlspecialchars(trim($val)); //��ֹ�û�����javascript����
                }
            }
            $update_data = array_merge($update_data, $other);
        }
        $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('users'), $update_data, 'UPDATE', 'user_id = ' . $_SESSION['user_id']);

        update_user_info();      // �����û���Ϣ

        return true;

}

function is_mobile($mobile_phone)
{
    if(!is_numeric($mobile_phone))
    {
        return false;
    }
    if(preg_match("/1[3458]{1}\d{9}$/",$mobile_phone))
    {
        return true;
    }
    else
    {
        return false;
    }
}


function check_captcha_word($word)
{
    $recorded = isset($_SESSION['captcha_word']) ? base64_decode($_SESSION['captcha_word']) : '';

    $given    = substr(md5(strtoupper($word)), 1, 10);

    return (preg_match("/$given/", $recorded));
}

function makecode($num=1) 
{
    $re = '';
    $s = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';
    while(strlen($re)<$num) {
        $re .= $s[rand(0, strlen($s)-1)]; 
    }
    return $re;
}


function  order_list()
{
    include_once(ROOT_PATH . 'includes/lib_transaction.php');
    $record_count = $GLOBALS['db']->getOne("SELECT COUNT(*) FROM " .$GLOBALS['ecs']->table('order_info'). " WHERE user_id = {$_SESSION['user_id']}");
    if ($record_count > 0)
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
        $pagebar = get_wap_pager($record_count, $page_num, $page, 'user.php', 'page');
        $GLOBALS['smarty']->assign('pagebar' , $pagebar);
        /* ����״̬ */
        $_LANG['os'][OS_UNCONFIRMED] = 'δȷ��';
        $_LANG['os'][OS_CONFIRMED] = '��ȷ��';
        $_LANG['os'][OS_SPLITED] = '��ȷ��';
        $_LANG['os'][OS_SPLITING_PART] = '��ȷ��';
        $_LANG['os'][OS_CANCELED] = '��ȡ��';
        $_LANG['os'][OS_INVALID] = '��Ч';
        $_LANG['os'][OS_RETURNED] = '�˻�';

        $_LANG['ss'][SS_UNSHIPPED] = 'δ����';
        $_LANG['ss'][SS_PREPARING] = '�����';
        $_LANG['ss'][SS_SHIPPED] = '�ѷ���';
        $_LANG['ss'][SS_RECEIVED] = '�ջ�ȷ��';
        $_LANG['ss'][SS_SHIPPED_PART] = '�ѷ���(������Ʒ)';
        $_LANG['ss'][SS_SHIPPED_ING] = '�����'; // �ѷֵ�

        $_LANG['ps'][PS_UNPAYED] = 'δ����';
        $_LANG['ps'][PS_PAYING] = '������';
        $_LANG['ps'][PS_PAYED] = '�Ѹ���';
        $_LANG['cancel'] = 'ȡ������';
        $_LANG['pay_money'] = '����';
        $_LANG['view_order'] = '�鿴����';
        $_LANG['received'] = 'ȷ���ջ�';
        $_LANG['ss_received'] = '�����';
        $_LANG['confirm_received'] = '��ȷ���Ѿ��յ���������';
        $_LANG['confirm_cancel'] = '��ȷ��Ҫȡ���ö�����ȡ����˶�������Ϊ��Ч����';




        $orders = get_user_orders($_SESSION['user_id'], $page_num, $page_num * ($page - 1));

        //var_dump($orders);
        if (!empty($orders))
        {
            foreach ($orders as $key => $val)
            {
                $orders[$key]['total_fee'] = encode_output($val['total_fee']);
            }
        }
        //$merge  = get_user_merge($_SESSION['user_id']);

        $GLOBALS['smarty']->assign('orders', $orders);
    }

}

function address_i_info($country,$province,$city,$district)
{
    $address=array($country,$province,$city,$district);
    $address=array_filter($address);

    $sql="SELECT `region_name` FROM ".$GLOBALS['ecs']->table('region'). "WHERE `region_id` ".db_create_in($address) ." order by `parent_id`";
    $address= $GLOBALS['db']->getAll($sql);
    $test='';
    foreach ($address as $val)
    {
         $test.=$val['region_name'].',';
    }
    $test=substr($test,0,-1);
    return $test;

}


?>