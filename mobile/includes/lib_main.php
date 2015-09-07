<?php

/**
 * ECSHOP mobileǰ̨��������
 * ============================================================================
 * * ��Ȩ���� 2005-2012 �Ϻ���������Ƽ����޹�˾������������Ȩ����
 * ��վ��ַ: http://www.ecshop.com��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�
 * ʹ�ã�������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
 * ============================================================================
 * $Author: testyang $
 * $Id: lib_main.php 15013 2008-10-23 09:31:42Z testyang $
*/

if (!defined('IN_ECS'))
{
    die('Hacking attempt');
}

/**
 * ���������
 *
 * @access  public
 * @param   string   $str
 * @return  string
 */
function encode_output($str)
{
//    if (EC_CHARSET != 'utf-8')
//    {
//        $str = ecs_iconv(EC_CHARSET, 'utf-8', $str);
//    }
    return htmlspecialchars($str);
}

/**
 * wap��ҳ����
 *
 * @access      public
 * @param       int     $num        �ܼ�¼��
 * @param       int     $perpage    ÿҳ��¼��
 * @param       int     $curr_page  ��ǰҳ��
 * @param       string  $mpurl      ��������ӵ�ַ
 * @param       string  $pvar       ��ҳ����
 */
function get_wap_pager($num, $perpage, $curr_page, $mpurl,$pvar)
{
    $multipage = '';
    if($num > $perpage)
    {
        $page = 2;
        $offset = 1;
        $pages = ceil($num / $perpage);
        $all_pages = $pages;
        $tmp_page = $curr_page;
        $setp = strpos($mpurl, '?') === false ? "?" : '&amp;';
        if($curr_page > 1)
        {
            $multipage .= "<a href=\"$mpurl${setp}${pvar}=".($curr_page-1)."\">��һҳ</a>";
        }
        $multipage .= $curr_page."/".$pages;
        if(($curr_page++) < $pages)
        {
            $multipage .= "<a href=\"$mpurl${setp}${pvar}=".$curr_page++."\">��һҳ</a><br/>";
        }
        //$multipage .= $pages > $page ? " ... <a href=\"$mpurl&amp;$pvar=$pages\"> [$pages] &gt;&gt;</a>" : " ҳ/".$all_pages."ҳ";
        //$url_array = explode("?" , $mpurl);
       // $field_str = "";
       // if (isset($url_array[1]))
       // {
          //  $filed_array = explode("&amp;" , $url_array[1]);
           // if (count($filed_array) > 0)
            //{
             //   foreach ($filed_array AS $data)
              //  {
               //     $value_array = explode("=" , $data);
                //    $field_str .= "<postfield name='".$value_array[0]."' value='".encode_output($value_array[1])."'/>\n";
               // }
           // }
      //  }
        //$multipage .= "��ת����<input type='text' name='pageno' format='*N' size='4' value='' maxlength='2' emptyok='true' />ҳ<anchor>[GO]<go href='{$url_array[0]}' method='get'>{$field_str}<postfield name='".$pvar."' value='$(pageno)'/></go></anchor>";
        //<postfield name='snid' value='".session_id()."'/>
    }
    return $multipage;
}

/**
 * ����β�ļ�
 *
 * @return  string
 */
function get_footer()
{
    if ($_SESSION['user_id'] > 0)
    {
        $footer = "<br/><a href='user.php?act=user_center'>�û�����</a>|<a href='user.php?act=logout'>�˳�</a>|<a href='javascript:scroll(0,0)' hidefocus='true'>�ص�����</a><br/>Copyright 2009<br/>Powered by ECShop v2.7.2";
    }
    else
    {
        $footer = "<br/><a href='user.php?act=login'>��½</a>|<a href='user.php?act=register'>���ע��</a>|<a href='javascript:scroll(0,0)' hidefocus='true'>�ص�����</a><br/>Copyright 2009<br/>Powered by ECShop v2.7.2";
    }

    return $footer;
}





function mobile_common()
{
    $GLOBALS['smarty']->assign('mobile_navigator',        get_mobile_navigator());  //�Զ��嵼����
}


function get_mobile_navigator()
{
    $sql="SELECT * FROM " . $GLOBALS['ecs']->table('mobile_nav') ." WHERE `ifshow`=1 ORDER BY `vieworder`  LIMIT 0,3";
    $result=$GLOBALS['db']->getAll($sql);
    return $result;
}


function mobile_json($content,$flag)
{

    if(EC_CHARSET != 'utf-8')
    {
         $content=ecs_iconv(EC_CHARSET,'utf-8',  $content);
    }
    $josn=array();
    $josn['success']=$content;
    $josn['flag']=$flag;
    echo json_encode($josn);
    exit;

}


function mobile_error ($act,$url='',$name='')
{
    $mobile_error=array();
    if(empty($url))
    {
        if(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER']))
        {
               $url=$_SERVER['HTTP_REFERER'];
        }
        else
        {
               $url='index.php';
        }
    }
    $mobile_error['act']=$act;
    $mobile_error['url']=$url;
    $mobile_error['name']=$name;
    $GLOBALS['smarty']->assign('mobile_error',        $mobile_error);
    $GLOBALS['smarty']->display('mobile_error.dwt');
    exit;
}



?>