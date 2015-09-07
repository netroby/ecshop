<?php

/**
 * ECSHOP ����
 * ============================================================================
 * ��Ȩ���� 2005-2010 �Ϻ���������Ƽ����޹�˾������������Ȩ����
 * ��վ��ַ: http://www.ecshop.com��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�
 * ʹ�ã�������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
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
        mobile_error('������ҳ', $url = 'index.php', 'δ�ҵ���Ӧ������');
    }

    if (!empty($article_row['link']) && $article_row['link'] != 'http://' && $article_row['link'] != 'https://') {
        mobile_error('������ҳ', $url = 'index.php', 'δ�ҵ���Ӧ������');
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
    mobile_error('������ҳ', $url = 'index.php', 'δ�ҵ���Ӧ������');
}

function get_article_info($article_id)
{
    /* ������µ���Ϣ */
    $sql = 'SELECT a.*, IFNULL(AVG(r.comment_rank), 0) AS comment_rank '.
            'FROM '.$GLOBALS['ecs']->table('article').' AS a '.
            'LEFT JOIN '.$GLOBALS['ecs']->table('comment').' AS r ON r.id_value = a.article_id AND comment_type = 1 '.
            "WHERE a.is_open = 1 AND a.article_id = '$article_id' GROUP BY a.article_id";
    $row = $GLOBALS['db']->getRow($sql);

    if ($row !== false) {
        $row['comment_rank'] = ceil($row['comment_rank']);                              // �û����ۼ���ȡ��
        $row['add_time'] = local_date($GLOBALS['_CFG']['date_format'], $row['add_time']); // �������ʱ����ʾ

        /* ������Ϣ���Ϊ�գ�������վ�����滻 */
        if (empty($row['author']) || $row['author'] == '_SHOPHELP') {
            $row['author'] = $GLOBALS['_CFG']['shop_name'];
        }
    }

    return $row;
}
