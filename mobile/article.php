<?php

/**
 * ECSHOP 文章
 * ============================================================================
 * 版权所有 2005-2010 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: article.php 16455 2009-07-13 09:57:19Z liubo $.
 */
define('IN_ECS', true);

require dirname(__FILE__).'/includes/init.php';

$a_id = !empty($_GET['id']) ? intval($_GET['id']) : '';
if ($a_id > 0) {
    $article_row = get_article_info($a_id);

    if (empty($article_row)) {
        mobile_error('返回首页', $url = 'index.php', '未找到对应的文章');
    }

    if (!empty($article_row['link']) && $article_row['link'] != 'http://' && $article_row['link'] != 'https://') {
        mobile_error('返回首页', $url = 'index.php', '未找到对应的文章');
    }

    $smarty->assign('common_header_title',    encode_output($article_row['title']));
    $article_row['title'] = encode_output($article_row['title']);
    $replace_tag = array('<br />' , '<br/>' , '<br>' , '</p>');
    $article_row['content'] = htmlspecialchars_decode(encode_output($article_row['content']));
    $article_row['content'] = str_replace($replace_tag, '{br}', $article_row['content']);
    $article_row['content'] = strip_tags($article_row['content']);
    $article_row['content'] = str_replace('{br}', '<br />', $article_row['content']);
    $smarty->assign('article_data', $article_row);

    $smarty->display('article_desc.dwt');
} else {
    mobile_error('返回首页', $url = 'index.php', '未找到对应的文章');
}

function get_article_info($article_id)
{
    /* 获得文章的信息 */
    $sql = 'SELECT a.*, IFNULL(AVG(r.comment_rank), 0) AS comment_rank '.
            'FROM '.$GLOBALS['ecs']->table('article').' AS a '.
            'LEFT JOIN '.$GLOBALS['ecs']->table('comment').' AS r ON r.id_value = a.article_id AND comment_type = 1 '.
            "WHERE a.is_open = 1 AND a.article_id = '$article_id' GROUP BY a.article_id";
    $row = $GLOBALS['db']->getRow($sql);

    if ($row !== false) {
        $row['comment_rank'] = ceil($row['comment_rank']);                              // 用户评论级别取整
        $row['add_time'] = local_date($GLOBALS['_CFG']['date_format'], $row['add_time']); // 修正添加时间显示

        /* 作者信息如果为空，则用网站名称替换 */
        if (empty($row['author']) || $row['author'] == '_SHOPHELP') {
            $row['author'] = $GLOBALS['_CFG']['shop_name'];
        }
    }

    return $row;
}
