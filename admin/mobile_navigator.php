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
 * $Id: navigator.php 17217 2011-01-19 06:29:08Z liubo $.
 */
define('IN_ECS', true);
require dirname(__FILE__).'/includes/init.php';

admin_priv('navigator');

$exc = new exchange($ecs->table('mobile_nav'), $db, 'id', 'name');

/*------------------------------------------------------ */
//-- 自定义导航栏列表
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'list') {
    $smarty->assign('ur_here', $_LANG['navigator']);

    $smarty->assign('action_link', array('text' => $_LANG['add_new'], 'href' => 'mobile_navigator.php?act=add'));

    //$smarty->assign('full_page',  1);

    $navdb = get_nav();

    $smarty->assign('navdb',   $navdb['navdb']);
    //$smarty->assign('filter',       $navdb['filter']);
    //$smarty->assign('record_count', $navdb['record_count']);
    //$smarty->assign('page_count',   $navdb['page_count']);

    assign_query_info();
    $smarty->display('mobile_navigator.htm');
}
/*------------------------------------------------------ */
//-- 自定义导航栏列表Ajax
/*------------------------------------------------------ */
//elseif ($_REQUEST['act'] == 'query')
//{
//    $navdb = get_nav();
//    $smarty->assign('navdb',    $navdb['navdb']);
//    $smarty->assign('filter',       $navdb['filter']);
//    $smarty->assign('record_count', $navdb['record_count']);
//    $smarty->assign('page_count',   $navdb['page_count']);
//
//    $sort_flag  = sort_flag($navdb['filter']);
//    $smarty->assign($sort_flag['tag'], $sort_flag['img']);
//
//    make_json_result($smarty->fetch('navigator.htm'), '', array('filter' => $navdb['filter'], 'page_count' => $navdb['page_count']));
//}
/*------------------------------------------------------ */
//-- 自定义导航栏增加
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'add') {
    if (empty($_REQUEST['step'])) {
        $rt = array('act' => 'add');

        //$sysmain = get_sysnav();

        $smarty->assign('action_link', array('text' => $_LANG['go_list'], 'href' => 'mobile_navigator.php?act=list'));
        $smarty->assign('ur_here', $_LANG['navigator']);
        assign_query_info();
        //$smarty->assign('sysmain',$sysmain);
        $smarty->assign('rt', $rt);
        $smarty->display('mobile_navigator_add.htm');
    } elseif ($_REQUEST['step'] == 2) {
        $item_name = $_REQUEST['item_name'];
        $item_url = $_REQUEST['item_url'];
        $item_ifshow = (int) $_REQUEST['item_ifshow'];
        $item_opennew = (int) $_REQUEST['item_opennew'];
        $item_vieworder = (int) $_REQUEST['item_vieworder'];
        //$item_type = $_REQUEST['item_type'];

        $sql = 'INSERT INTO '.$ecs->table('mobile_nav')."(`name`,`url`,`ifshow`,`opennew`,`vieworder`) VALUES('$item_name','$item_url','$item_ifshow','$item_opennew','$item_vieworder')";

        $db->query($sql);
        clear_cache_files();
        $links[] = array('text' => $_LANG['navigator'], 'href' => 'mobile_navigator.php?act=list');
        $links[] = array('text' => $_LANG['add_new'], 'href' => 'mobile_navigator.php?act=add');
        sys_msg($_LANG['edit_ok'], 0, $links);
    }
}
/*------------------------------------------------------ */
//-- 自定义导航栏编辑
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'edit') {
    $id = $_REQUEST['id'];
    if (empty($_REQUEST['step'])) {
        $rt = array('act' => 'edit','id' => $id);
        $row = $db->getRow('SELECT * FROM '.$GLOBALS['ecs']->table('mobile_nav')." WHERE id='$id'");
        $rt['item_name'] = $row['name'];
        $rt['item_url'] = $row['url'];
        $rt['item_vieworder'] = $row['vieworder'];
        $rt['item_ifshow_'.$row['ifshow']] = 'selected';
        $rt['item_opennew_'.$row['opennew']] = 'selected';
        $rt['item_type_'.$row['type']] = 'selected';

        //$sysmain = get_sysnav();

        $smarty->assign('action_link', array('text' => $_LANG['go_list'], 'href' => 'mobile_navigator.php?act=list'));
        $smarty->assign('ur_here', $_LANG['navigator']);
        //assign_query_info();
        $smarty->assign('sysmain', $sysmain);
        $smarty->assign('rt', $rt);
        $smarty->display('mobile_navigator_add.htm');
    } elseif ($_REQUEST['step'] == 2) {
        $item_name = $_REQUEST['item_name'];
        $item_url = $_REQUEST['item_url'];
        $item_ifshow = $_REQUEST['item_ifshow'];
        $item_opennew = $_REQUEST['item_opennew'];
        $item_type = $_REQUEST['item_type'];
        $item_vieworder = (int) $_REQUEST['item_vieworder'];

        $sql = 'UPDATE '.$GLOBALS['ecs']->table('mobile_nav').
                " SET name='$item_name',ctype='".$arr['type']."',cid='".$arr['id']."',ifshow='$item_ifshow',vieworder='$item_vieworder',opennew='$item_opennew',url='$item_url',type='$item_type' WHERE id='$id'";

        $db->query($sql);
        clear_cache_files();
        $links[] = array('text' => $_LANG['navigator'], 'href' => 'mobile_navigator.php?act=list');
        sys_msg($_LANG['edit_ok'], 0, $links);
    }
}
/*------------------------------------------------------ */
//-- 自定义导航栏删除
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'del') {
    $id = (int) $_GET['id'];
    $sql = ' DELETE FROM '.$GLOBALS['ecs']->table('mobile_nav')." WHERE id='$id' LIMIT 1";
    $db->query($sql);
    clear_cache_files();
    ecs_header("Location: mobile_navigator.php?act=list\n");
    exit;
}

/*------------------------------------------------------ */
//-- 编辑排序
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'edit_sort_order') {
    check_authz_json('nav');

    $id = intval($_POST['id']);
    $order = json_str_iconv(trim($_POST['val']));

    /* 检查输入的值是否合法 */
    if (!preg_match('/^[0-9]+$/', $order)) {
        make_json_error(sprintf($_LANG['enter_int'], $order));
    } else {
        if ($exc->edit("vieworder = '$order'", $id)) {
            clear_cache_files();
            make_json_result(stripslashes($order));
        } else {
            make_json_error($db->error());
        }
    }
}

/*------------------------------------------------------ */
//-- 切换是否显示
/*------------------------------------------------------ */

if ($_REQUEST['act'] == 'toggle_ifshow') {
    $id = intval($_POST['id']);
    $val = intval($_POST['val']);

    $row = $db->getRow('SELECT type,ctype,cid FROM '.$GLOBALS['ecs']->table('mobile_nav')." WHERE id = '$id' LIMIT 1");

    if ($row['type'] == 'middle' && $row['ctype'] && $row['cid']) {
        set_show_in_nav($row['ctype'], $row['cid'], $val);
    }

    if (nav_update($id, array('ifshow' => $val)) != false) {
        clear_cache_files();
        make_json_result($val);
    } else {
        make_json_error($db->error());
    }
}

/*------------------------------------------------------ */
//-- 切换是否新窗口
/*------------------------------------------------------ */

if ($_REQUEST['act'] == 'toggle_opennew') {
    $id = intval($_POST['id']);
    $val = intval($_POST['val']);

    if (nav_update($id, array('opennew' => $val)) != false) {
        clear_cache_files();
        make_json_result($val);
    } else {
        make_json_error($db->error());
    }
}

function get_nav()
{
    $sql = $sql = 'SELECT id, name, ifshow, vieworder, opennew, url, type'.
               ' FROM '.$GLOBALS['ecs']->table('mobile_nav');
    $navdb = $GLOBALS['db']->getAll($sql);

    $type = '';
    $navdb2 = array();
    foreach ($navdb as $k => $v) {
        if (!empty($type) && $type != $v['type']) {
            $navdb2[] = array();
        }
        $navdb2[] = $v;
        $type = $v['type'];
    }

    $arr = array('navdb' => $navdb2, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);

    return $arr;
}

/*------------------------------------------------------ */
//-- 排序相关
/*------------------------------------------------------ */

/*------------------------------------------------------ */
//-- 列表项修改
/*------------------------------------------------------ */
function nav_update($id, $args)
{
    if (empty($args) || empty($id)) {
        return false;
    }

    return $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('mobile_nav'), $args, 'update', "id='$id'");
}
