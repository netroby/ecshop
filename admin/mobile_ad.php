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
 * $Author: liubo $
 * $Id: flashplay.php 17217 2011-01-19 06:29:08Z liubo $.
 */
define('IN_ECS', true);

require dirname(__FILE__).'/includes/init.php';
$uri = $ecs->url();
$allow_suffix = array('gif', 'jpg', 'png', 'jpeg', 'bmp');

/*------------------------------------------------------ */
//-- 系统
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'list') {
    admin_priv('mobile_manage');
    $sql = 'SELECT * FROM '.$ecs->table('mobile_ad');
    $playerdb = $db->getAll($sql);
    /* 标签初始化 */
    $group_list = array(
        'sys' => array('text' => $_LANG['system_set'], 'url' => ''),
        'cus' => array('text' => $_LANG['custom_set'], 'url' => 'mobile_ad.php?act=custom_list'),
                       );

    $smarty->assign('current', 'sys');
    $smarty->assign('group_list', $group_list);
    $smarty->assign('group_selected', $_CFG['index_ad']);
    $smarty->assign('uri', $uri);
    $smarty->assign('ur_here', $_LANG['flashplay']);
    $smarty->assign('action_link_special', array('text' => $_LANG['add_new'], 'href' => 'mobile_ad.php?act=add'));

    $smarty->assign('playerdb', $playerdb);
    $smarty->display('mobile_ad_list.htm');
} elseif ($_REQUEST['act'] == 'add') {
    admin_priv('mobile_manage');

    if (empty($_POST['step'])) {
        $url = isset($_GET['url']) ? $_GET['url'] : 'http://';
        $src = isset($_GET['src']) ? $_GET['src'] : '';
        $sort = 0;
        $rt = array('act' => 'add','img_url' => $url,'img_src' => $src, 'img_sort' => $sort);

//        $width_height = get_width_height();
//        assign_query_info();
//        if(isset($width_height['width'])|| isset($width_height['height']))
//        {
//            $smarty->assign('width_height', sprintf($_LANG['width_height'], $width_height['width'], $width_height['height']));
//        }

        $smarty->assign('action_link', array('text' => $_LANG['go_url'], 'href' => 'mobile_ad.php?act=list'));
        $smarty->assign('rt', $rt);
        $smarty->assign('ur_here', $_LANG['add_picad']);
        $smarty->display('mobile_ad_add.htm');
    } elseif ($_POST['step'] == 2) {
        if (!empty($_FILES['img_file_src']['name'])) {
            if (!get_file_suffix($_FILES['img_file_src']['name'], $allow_suffix)) {
                sys_msg($_LANG['invalid_type']);
            }
            $name = date('Ymd');
            for ($i = 0; $i < 6; ++$i) {
                $name .= chr(mt_rand(97, 122));
            }
            $name .= '.'.end(explode('.', $_FILES['img_file_src']['name']));
            $target = ROOT_PATH.DATA_DIR.'/afficheimg/'.$name;
            if (move_upload_file($_FILES['img_file_src']['tmp_name'], $target)) {
                $src = DATA_DIR.'/afficheimg/'.$name;
            }
        } elseif (!empty($_POST['img_src'])) {
            $src = $_POST['img_src'];

            if (strstr($src, 'http') && !strstr($src, $_SERVER['SERVER_NAME'])) {
                $src = get_url_image($src);
            }
        } else {
            $links[] = array('text' => $_LANG['add_new'], 'href' => 'mobile_ad.php?act=add');
            sys_msg($_LANG['src_empty'], 0, $links);
        }

        if (empty($_POST['img_url'])) {
            $links[] = array('text' => $_LANG['add_new'], 'href' => 'mobile_ad.php?act=add');
            sys_msg($_LANG['link_empty'], 0, $links);
        }
        $img_url = $_POST['img_url'];
        $img_sort = intval($_POST['img_sort']);
        $explain = $_POST['img_text'];
        $sql = 'INSERT INTO '.$ecs->table('mobile_ad')."(`http_url`,`order_id`,`image_url`,`explain`) VALUES  ('$img_url','$img_sort','$src','$explain')";
        $db->query($sql);

        $links[] = array('text' => $_LANG['go_url'], 'href' => 'mobile_ad.php?act=list');
        sys_msg($_LANG['edit_ok'], 0, $links);
    }
} elseif ($_REQUEST['act'] == 'edit') {
    admin_priv('mobile_manage');

    $id = (int) $_REQUEST['id']; //取得id

    $sql = 'SELECT * FROM '.$ecs->table('mobile_ad')." WHERE `id`='$id'";

    $rt = $db->getRow($sql); //取得数据

    if (isset($rt['id'])) {
    } else {
        $links[] = array('text' => $_LANG['go_url'], 'href' => 'mobile_ad.php?act=list');
        sys_msg($_LANG['id_error'], 0, $links);
    }

    if (empty($_POST['step'])) {
        $rt['act'] = 'edit';
        $rt['img_url'] = $rt['http_url'];
        $rt['img_src'] = $rt['image_url'];
        $rt['img_txt'] = $rt['explain'];
        $rt['img_sort'] = empty($rt['order_id']) ? 0 : $rt['order_id'];

        $rt['id'] = $id;
        $smarty->assign('action_link', array('text' => $_LANG['go_url'], 'href' => 'mobile_ad.php?act=list'));
        $smarty->assign('rt', $rt);
        $smarty->assign('ur_here', $_LANG['edit_picad']);
        $smarty->display('mobile_ad_add.htm');
    } elseif ($_POST['step'] == 2) {
        if (empty($_POST['img_url'])) {
            //若链接地址为空
            $links[] = array('text' => $_LANG['return_edit'], 'href' => 'flashplay.php?act=edit&id='.$id);
            sys_msg($_LANG['link_empty'], 0, $links);
        }

        if (!empty($_FILES['img_file_src']['name'])) {
            if (!get_file_suffix($_FILES['img_file_src']['name'], $allow_suffix)) {
                sys_msg($_LANG['invalid_type']);
            }
            //有上传
            $name = date('Ymd');
            for ($i = 0; $i < 6; ++$i) {
                $name .= chr(mt_rand(97, 122));
            }
            $name .= '.'.end(explode('.', $_FILES['img_file_src']['name']));
            $target = ROOT_PATH.DATA_DIR.'/afficheimg/'.$name;

            if (move_upload_file($_FILES['img_file_src']['tmp_name'], $target)) {
                $src = DATA_DIR.'/afficheimg/'.$name;
            }
        } elseif (!empty($_POST['img_src'])) {
            $src = $_POST['img_src'];

            if (strstr($src, 'http') && !strstr($src, $_SERVER['SERVER_NAME'])) {
                $src = get_url_image($src);
            }
        } else {
            $links[] = array('text' => $_LANG['return_edit'], 'href' => 'mobile_ad.php?act=edit&id='.$id);
            sys_msg($_LANG['src_empty'], 0, $links);
        }

        if (strpos($rt['src'], 'http') === false && $rt['src'] != $src) {
            @unlink(ROOT_PATH.$rt['src']);
        }

        $img_url = $_POST['img_url'];
        $img_sort = intval($_POST['img_sort']);
        $explain = $_POST['img_text'];

        $sql = 'UPDATE '.$ecs->table('mobile_ad')." SET `http_url`='$img_url',`order_id`='$img_sort',`image_url`='$src',`explain`='$explain' WHERE `id`='$id'";
        $db->query($sql);
        $links[] = array('text' => $_LANG['go_url'], 'href' => 'mobile_ad.php?act=list');
        sys_msg($_LANG['edit_ok'], 0, $links);
    }
} elseif ($_REQUEST['act'] == 'del') {
    admin_priv('mobile_manage');

    $id = (int) $_GET['id'];
    $sql = 'DELETE FROM '.$ecs->table('mobile_ad')." WHERE `id`='$id'";
    $db->query($sql);
    ecs_header("Location: mobile_ad.php?act=list\n");
    exit;
}
