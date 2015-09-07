<?php

/**
 * ECSHOP 公用函数�&#65533;
 * ============================================================================
 * 版权所�&#65533; 2005-2008 上海商派网络科技有限公司，并保留所有权利�&#65533;
 * 网站地址: http://www.ecshop.com�&#65533;
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改�&#65533;
 * 使用；不允许对程序代码以任何形式任何目的的再发布�&#65533;
 * ============================================================================
 * $Author: liubo $
 * $Id: lib_common.php 6108 2009-09-17 07:40:49Z liubo $.
 */
if (!defined('IN_ECS')) {
    die('Hacking attempt');
}

/**
 * 创建像这样的查询: "IN('a','b')";.
 *
 * @param mix    $item_list  列表数组或字符串
 * @param string $field_name 字段名称
 */
function db_create_in($item_list, $field_name = '')
{
    if (empty($item_list)) {
        return $field_name." IN ('') ";
    } else {
        if (!is_array($item_list)) {
            $item_list = explode(',', $item_list);
        }
        $item_list = array_unique($item_list);
        $item_list_tmp = '';
        foreach ($item_list as $item) {
            if ($item !== '') {
                $item_list_tmp .= $item_list_tmp ? ",'$item'" : "'$item'";
            }
        }
        if (empty($item_list_tmp)) {
            return $field_name." IN ('') ";
        } else {
            return $field_name.' IN ('.$item_list_tmp.') ';
        }
    }
}

/**
 * 验证输入的邮件地址是否合法.
 *
 * @param string $email 需要验证的邮件地址
 *
 * @return bool
 */
function is_email($user_email)
{
    $chars = '/^([a-z0-9+_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,6}$/i';
    if (strpos($user_email, '@') !== false && strpos($user_email, '.') !== false) {
        if (preg_match($chars, $user_email)) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

/**
 * 检查是否为一个合法的时间格式.
 *
 * @param string $time
 */
function is_time($time)
{
    $pattern = '/[\d]{4}-[\d]{1,2}-[\d]{1,2}\s[\d]{1,2}:[\d]{1,2}:[\d]{1,2}/';

    return preg_match($pattern, $time);
}

/**
 * 获得查询时间和次数，并赋值给smarty.
 */
function assign_query_info()
{
    /*    if ($GLOBALS['db']->queryTime == '')
    {
        $query_time = 0;
    }
    else
    {
        if (PHP_VERSION >= '5.0.0')
        {
            $query_time = number_format(microtime(true) - $GLOBALS['db']->queryTime, 6);
        }
        else
        {
            list($now_usec, $now_sec)     = explode(' ', microtime());
            list($start_usec, $start_sec) = explode(' ', $GLOBALS['db']->queryTime);
            $query_time = number_format(($now_sec - $start_sec) + ($now_usec - $start_usec), 6);
        }
    }
    $GLOBALS['smarty']->assign('query_info', sprintf($GLOBALS['_LANG']['query_info'], $GLOBALS['db']->queryCount, $query_time));

    // 内存占用情况
    if ($GLOBALS['_LANG']['memory_info'] && function_exists('memory_get_usage'))
    {
        $GLOBALS['smarty']->assign('memory_info', sprintf($GLOBALS['_LANG']['memory_info'], memory_get_usage() / 1048576));
    }

    // 是否启用�&#65533; gzip
    $gzip_enabled = gzip_enabled() ? $GLOBALS['_LANG']['gzip_enabled'] : $GLOBALS['_LANG']['gzip_disabled'];
    $GLOBALS['smarty']->assign('gzip_enabled', $gzip_enabled);
    */
}

/**
 * 创建地区的返回信�&#65533;.
 *
 * @param array $arr 地区数组 *
 */
function region_result($parent, $sel_name, $type)
{
    global $cp;

    $arr = get_regions($type, $parent);
    foreach ($arr as $v) {
        $region = &$cp->add_node('region');
        $region_id = &$region->add_node('id');
        $region_name = &$region->add_node('name');

        $region_id->set_data($v['region_id']);
        $region_name->set_data($v['region_name']);
    }
    $select_obj = &$cp->add_node('select');
    $select_obj->set_data($sel_name);
}

/**
 * 获得指定国家的所有省�&#65533;.
 *
 * @param       int     country    国家的编�&#65533;
 *
 * @return array
 */
function get_regions($type = 0, $parent = 0)
{
    $sql = 'SELECT region_id, region_name FROM '.$GLOBALS['ecs']->table('region').
            " WHERE region_type = '$type' AND parent_id = '$parent'";

    return $GLOBALS['db']->GetAll($sql);
}

/**
 * 获得配送区域中指定的配送方式的配送费用的计算参数.
 *
 * @param int $area_id 配送区域ID
 *
 * @return array;
 */
function get_shipping_config($area_id)
{
    /* 获得配置信息 */
    $sql = 'SELECT configure FROM '.$GLOBALS['ecs']->table('shipping_area')." WHERE shipping_area_id = '$area_id'";
    $cfg = $GLOBALS['db']->GetOne($sql);

    if ($cfg) {
        /* 拆分成配置信息的数组 */
        $arr = unserialize($cfg);
    } else {
        $arr = array();
    }

    return $arr;
}

/**
 * 初始化会员数据整合类.
 *
 * @return object
 */
function &init_users()
{
    $set_modules = false;
    static $cls = null;
    if ($cls != null) {
        return $cls;
    }
    include_once ROOT_PATH.'includes/modules/integrates/'.$GLOBALS['_CFG']['integrate_code'].'.php';
    $cfg = unserialize($GLOBALS['_CFG']['integrate_config']);
    $cls = new $GLOBALS['_CFG']['integrate_code']($cfg);

    return $cls;
}

/**
 * 获得指定分类下的子分类的数组.
 *
 * @param int  $cat_id      分类的ID
 * @param int  $selected    当前选中分类的ID
 * @param bool $re_type     返回的类�&#65533;: 值为真时返回下拉列表,否则返回数组
 * @param int  $level       限定返回的级数。为0时返回所有级�&#65533;
 * @param int  $is_show_all 如果为true显示所有分类，如果为false隐藏不可见分类�&#65533;
 *
 * @return mix
 */
function cat_list($cat_id = 0, $selected = 0, $re_type = true, $level = 0, $is_show_all = true)
{
    static $res = null;

    if ($res === null) {
        $data = read_static_cache('cat_pid_releate');
        if ($data === false) {
            $sql = 'SELECT c.cat_id, c.cat_name, c.measure_unit, c.parent_id, c.is_show, c.show_in_nav, c.grade, c.sort_order, COUNT(s.cat_id) AS has_children '.
                'FROM '.$GLOBALS['ecs']->table('category').' AS c '.
                'LEFT JOIN '.$GLOBALS['ecs']->table('category').' AS s ON s.parent_id=c.cat_id '.
                'GROUP BY c.cat_id '.
                'ORDER BY c.parent_id, c.sort_order ASC';
            $res = $GLOBALS['db']->getAll($sql);

            $sql = 'SELECT cat_id, COUNT(*) AS goods_num '.
                    ' FROM '.$GLOBALS['ecs']->table('goods').' AS g '.
                    ' GROUP BY cat_id';
            $res2 = $GLOBALS['db']->getAll($sql);

            $newres = array();
            foreach ($res2 as $k => $v) {
                $newres[$v['cat_id']] = $v['goods_num'];
            }

            foreach ($res as $k => $v) {
                $res[$k]['goods_num'] = !empty($newres[$v['cat_id']]) ? $newres[$v['cat_id']] : 0;
            }
            //如果数组过大，不采用静态缓存方�&#65533;
            if (count($res) <= 1000) {
                write_static_cache('cat_pid_releate', $res);
            }
        } else {
            $res = $data;
        }
    }

    if (empty($res) == true) {
        return $re_type ? '' : array();
    }

    $options = cat_options($cat_id, $res); // 获得指定分类下的子分类的数组

    $children_level = 99999; //大于这个分类的将被删�&#65533;
    if ($is_show_all == false) {
        foreach ($options as $key => $val) {
            if ($val['level'] > $children_level) {
                unset($options[$key]);
            } else {
                if ($val['is_show'] == 0) {
                    unset($options[$key]);
                    if ($children_level > $val['level']) {
                        $children_level = $val['level']; //标记一下，这样子分类也能删�&#65533;
                    }
                } else {
                    $children_level = 99999; //恢复初始�&#65533;
                }
            }
        }
    }

    /* 截取到指定的缩减级别 */
    if ($level > 0) {
        if ($cat_id == 0) {
            $end_level = $level;
        } else {
            $first_item = reset($options); // 获取第一个元�&#65533;
            $end_level = $first_item['level'] + $level;
        }

        /* 保留level小于end_level的部�&#65533; */
        foreach ($options as $key => $val) {
            if ($val['level'] >= $end_level) {
                unset($options[$key]);
            }
        }
    }

    if ($re_type == true) {
        $select = '';
        foreach ($options as $var) {
            $select .= '<option value="'.$var['cat_id'].'" ';
            $select .= ($selected == $var['cat_id']) ? "selected='ture'" : '';
            $select .= '>';
            if ($var['level'] > 0) {
                $select .= str_repeat('&nbsp;', $var['level'] * 4);
            }
            $select .= htmlspecialchars($var['cat_name'], ENT_QUOTES).'</option>';
        }

        return $select;
    } else {
        foreach ($options as $key => $value) {
            $options[$key]['url'] = build_uri('category', array('cid' => $value['cat_id']), $value['cat_name']);
        }

        return $options;
    }
}

/**
 * 过滤和排序所有分类，返回一个带有缩进级别的数组.
 *
 * @param int   $cat_id 上级分类ID
 * @param array $arr    含有所有分类的数组
 * @param int   $level  级别
 */
function cat_options($spec_cat_id, $arr)
{
    static $cat_options = array();

    if (isset($cat_options[$spec_cat_id])) {
        return $cat_options[$spec_cat_id];
    }

    if (!isset($cat_options[0])) {
        $level = $last_cat_id = 0;
        $options = $cat_id_array = $level_array = array();
        $data = read_static_cache('cat_option_static');
        if ($data === false) {
            while (!empty($arr)) {
                foreach ($arr as $key => $value) {
                    $cat_id = $value['cat_id'];
                    if ($level == 0 && $last_cat_id == 0) {
                        if ($value['parent_id'] > 0) {
                            break;
                        }

                        $options[$cat_id] = $value;
                        $options[$cat_id]['level'] = $level;
                        $options[$cat_id]['id'] = $cat_id;
                        $options[$cat_id]['name'] = $value['cat_name'];
                        unset($arr[$key]);

                        if ($value['has_children'] == 0) {
                            continue;
                        }
                        $last_cat_id = $cat_id;
                        $cat_id_array = array($cat_id);
                        $level_array[$last_cat_id] = ++$level;
                        continue;
                    }

                    if ($value['parent_id'] == $last_cat_id) {
                        $options[$cat_id] = $value;
                        $options[$cat_id]['level'] = $level;
                        $options[$cat_id]['id'] = $cat_id;
                        $options[$cat_id]['name'] = $value['cat_name'];
                        unset($arr[$key]);

                        if ($value['has_children'] > 0) {
                            if (end($cat_id_array) != $last_cat_id) {
                                $cat_id_array[] = $last_cat_id;
                            }
                            $last_cat_id = $cat_id;
                            $cat_id_array[] = $cat_id;
                            $level_array[$last_cat_id] = ++$level;
                        }
                    } elseif ($value['parent_id'] > $last_cat_id) {
                        break;
                    }
                }

                $count = count($cat_id_array);
                if ($count > 1) {
                    $last_cat_id = array_pop($cat_id_array);
                } elseif ($count == 1) {
                    if ($last_cat_id != end($cat_id_array)) {
                        $last_cat_id = end($cat_id_array);
                    } else {
                        $level = 0;
                        $last_cat_id = 0;
                        $cat_id_array = array();
                        continue;
                    }
                }

                if ($last_cat_id && isset($level_array[$last_cat_id])) {
                    $level = $level_array[$last_cat_id];
                } else {
                    $level = 0;
                }
            }
            //如果数组过大，不采用静态缓存方�&#65533;
            if (count($options) <= 2000) {
                write_static_cache('cat_option_static', $options);
            }
        } else {
            $options = $data;
        }
        $cat_options[0] = $options;
    } else {
        $options = $cat_options[0];
    }

    if (!$spec_cat_id) {
        return $options;
    } else {
        if (empty($options[$spec_cat_id])) {
            return array();
        }

        $spec_cat_id_level = $options[$spec_cat_id]['level'];

        foreach ($options as $key => $value) {
            if ($key != $spec_cat_id) {
                unset($options[$key]);
            } else {
                break;
            }
        }

        $spec_cat_id_array = array();
        foreach ($options as $key => $value) {
            if (($spec_cat_id_level == $value['level'] && $value['cat_id'] != $spec_cat_id) ||
                ($spec_cat_id_level > $value['level'])) {
                break;
            } else {
                $spec_cat_id_array[$key] = $value;
            }
        }
        $cat_options[$spec_cat_id] = $spec_cat_id_array;

        return $spec_cat_id_array;
    }
}

/**
 * 载入配置信息.
 *
 * @return array
 */
function load_config()
{
    $arr = array();

    $data = read_static_cache('shop_config');
    if ($data === false) {
        $sql = 'SELECT code, value FROM '.$GLOBALS['ecs']->table('shop_config').' WHERE parent_id > 0';
        $res = $GLOBALS['db']->getAll($sql);

        foreach ($res as $row) {
            $arr[$row['code']] = $row['value'];
        }

        /* 对数值型设置处理 */
        $arr['watermark_alpha'] = intval($arr['watermark_alpha']);
        $arr['market_price_rate'] = floatval($arr['market_price_rate']);
        $arr['integral_scale'] = floatval($arr['integral_scale']);
        //$arr['integral_percent']     = floatval($arr['integral_percent']);
        $arr['cache_time'] = intval($arr['cache_time']);
        $arr['thumb_width'] = intval($arr['thumb_width']);
        $arr['thumb_height'] = intval($arr['thumb_height']);
        $arr['image_width'] = intval($arr['image_width']);
        $arr['image_height'] = intval($arr['image_height']);
        $arr['best_number'] = !empty($arr['best_number']) && intval($arr['best_number']) > 0 ? intval($arr['best_number'])     : 3;
        $arr['new_number'] = !empty($arr['new_number']) && intval($arr['new_number']) > 0 ? intval($arr['new_number'])      : 3;
        $arr['hot_number'] = !empty($arr['hot_number']) && intval($arr['hot_number']) > 0 ? intval($arr['hot_number'])      : 3;
        $arr['promote_number'] = !empty($arr['promote_number']) && intval($arr['promote_number']) > 0 ? intval($arr['promote_number'])  : 3;
        $arr['top_number'] = intval($arr['top_number'])      > 0 ? intval($arr['top_number'])      : 10;
        $arr['history_number'] = intval($arr['history_number'])  > 0 ? intval($arr['history_number'])  : 5;
        $arr['comments_number'] = intval($arr['comments_number']) > 0 ? intval($arr['comments_number']) : 5;
        $arr['article_number'] = intval($arr['article_number'])  > 0 ? intval($arr['article_number'])  : 5;
        $arr['page_size'] = intval($arr['page_size'])       > 0 ? intval($arr['page_size'])       : 10;
        $arr['bought_goods'] = intval($arr['bought_goods']);
        $arr['goods_name_length'] = intval($arr['goods_name_length']);
        $arr['top10_time'] = intval($arr['top10_time']);
        $arr['goods_gallery_number'] = intval($arr['goods_gallery_number']) ? intval($arr['goods_gallery_number']) : 5;
        $arr['no_picture'] = !empty($arr['no_picture']) ? str_replace('../', './', $arr['no_picture']) : 'images/no_picture.gif'; // 修改默认商品图片的路�&#65533;
        $arr['qq'] = !empty($arr['qq']) ? $arr['qq'] : '';
        $arr['ww'] = !empty($arr['ww']) ? $arr['ww'] : '';
        $arr['default_storage'] = isset($arr['default_storage']) ? intval($arr['default_storage']) : 1;
        $arr['min_goods_amount'] = isset($arr['min_goods_amount']) ? floatval($arr['min_goods_amount']) : 0;
        $arr['one_step_buy'] = empty($arr['one_step_buy']) ? 0 : 1;
        $arr['invoice_type'] = empty($arr['invoice_type']) ? array('type' => array(), 'rate' => array()) : unserialize($arr['invoice_type']);
        $arr['show_order_type'] = isset($arr['show_order_type']) ? $arr['show_order_type'] : 0;    // 显示方式默认为列表方�&#65533;
        $arr['help_open'] = isset($arr['help_open']) ? $arr['help_open'] : 1;    // 显示方式默认为列表方�&#65533;
        $arr['upload_size_limit'] = $GLOBALS['personal']['level_info']['action']['upload_size_limit'];//限制上传文件大小
        $arr['visit_stats'] = 0;
        $arr['licensed'] = 0;

        if (!isset($GLOBALS['_CFG']['ecs_version'])) {
            /* 如果没有版本号则默认�&#65533;2.0.5 */
            $GLOBALS['_CFG']['ecs_version'] = 'v2.0.5';
        }

        //限定语言�&#65533;
        $lang_array = array('zh_cn', 'zh_tw', 'en_us', 'zh_us');
        if (empty($arr['lang']) || !in_array($arr['lang'], $lang_array)) {
            $arr['lang'] = 'zh_cn'; // 默认语言为简体中�&#65533;
        }

        if (empty($arr['integrate_code'])) {
            $arr['integrate_code'] = 'ecshop'; // 默认的会员整合插件为 ecshop
        }
        write_static_cache('shop_config', $arr);
    } else {
        $arr = $data;
    }

    return $arr;
}

/**
 * 取得品牌列表.
 *
 * @return array 品牌列表 id => name
 */
function get_brand_list()
{
    $sql = 'SELECT brand_id, brand_name FROM '.$GLOBALS['ecs']->table('brand').' ORDER BY sort_order';
    $res = $GLOBALS['db']->getAll($sql);

    $brand_list = array();
    foreach ($res as $row) {
        $brand_list[$row['brand_id']] = $row['brand_name'];
    }

    return $brand_list;
}

/**
 * 获得某个分类�&#65533;.
 *
 * @param int $cat
 *
 * @return array
 */
function get_brands($cat = 0, $app = 'brand')
{
    global $page_libs;
    $template = basename(PHP_SELF);
    $template = substr($template, 0, strrpos($template, '.'));
    include_once ROOT_PATH.'admin/includes/lib_template.php';
    static $static_page_libs = null;
    if ($static_page_libs == null) {
        $static_page_libs = $page_libs;
    }

    $children = ($cat > 0) ? ' AND '.get_children($cat) : '';

    $sql = "SELECT b.brand_id, b.brand_name, b.brand_logo, b.brand_desc, COUNT(*) AS goods_num, IF(b.brand_logo > '', '1', '0') AS tag ".
            'FROM '.$GLOBALS['ecs']->table('brand').'AS b, '.
                $GLOBALS['ecs']->table('goods').' AS g '.
            "WHERE g.brand_id = b.brand_id $children AND is_show = 1 ".
            ' AND g.is_on_sale = 1 AND g.is_alone_sale = 1 AND g.is_delete = 0 '.
            'GROUP BY b.brand_id HAVING goods_num > 0 ORDER BY tag DESC, b.sort_order ASC';
    if (isset($static_page_libs[$template]['/library/brands.lbi'])) {
        $num = get_library_number('brands');
        $sql .= " LIMIT $num ";
    }
    $row = $GLOBALS['db']->getAll($sql);

    foreach ($row as $key => $val) {
        $row[$key]['url'] = build_uri($app, array('cid' => $cat, 'bid' => $val['brand_id']), $val['brand_name']);
        $row[$key]['brand_desc'] = htmlspecialchars($val['brand_desc'], ENT_QUOTES);
    }

    return $row;
}

/**
 *  所有的促销活动信息.
 *
 * @return array
 */
function get_promotion_info($goods_id = '')
{
    $snatch = array();
    $group = array();
    $auction = array();
    $favourable = array();

    $gmtime = gmtime();
    $sql = 'SELECT act_id, act_name, act_type, start_time, end_time FROM '.$GLOBALS['ecs']->table('goods_activity')." WHERE is_finished=0 AND start_time <= '$gmtime' AND end_time >= '$gmtime'";
    if (!empty($goods_id)) {
        $sql .= " AND goods_id = '$goods_id'";
    }
    $res = $GLOBALS['db']->getAll($sql);
    foreach ($res as $data) {
        switch ($data['act_type']) {
            case GAT_SNATCH: //夺宝奇兵
                $snatch[$data['act_id']]['act_name'] = $data['act_name'];
                $snatch[$data['act_id']]['url'] = build_uri('snatch', array('sid' => $data['act_id']));
                $snatch[$data['act_id']]['time'] = sprintf($GLOBALS['_LANG']['promotion_time'], local_date('Y-m-d', $data['start_time']), local_date('Y-m-d', $data['end_time']));
                $snatch[$data['act_id']]['sort'] = $data['start_time'];
                $snatch[$data['act_id']]['type'] = 'snatch';
                break;

            case GAT_GROUP_BUY: //团购
                $group[$data['act_id']]['act_name'] = $data['act_name'];
                $group[$data['act_id']]['url'] = build_uri('group_buy', array('gbid' => $data['act_id']));
                $group[$data['act_id']]['time'] = sprintf($GLOBALS['_LANG']['promotion_time'], local_date('Y-m-d', $data['start_time']), local_date('Y-m-d', $data['end_time']));
                $group[$data['act_id']]['sort'] = $data['start_time'];
                $group[$data['act_id']]['type'] = 'group_buy';
                break;

            case GAT_AUCTION: //拍卖
                $auction[$data['act_id']]['act_name'] = $data['act_name'];
                $auction[$data['act_id']]['url'] = build_uri('auction', array('auid' => $data['act_id']));
                $auction[$data['act_id']]['time'] = sprintf($GLOBALS['_LANG']['promotion_time'], local_date('Y-m-d', $data['start_time']), local_date('Y-m-d', $data['end_time']));
                $auction[$data['act_id']]['sort'] = $data['start_time'];
                $auction[$data['act_id']]['type'] = 'auction';
                break;
        }
    }

    $user_rank = ','.$_SESSION['user_rank'].',';
    $favourable = array();
    $sql = 'SELECT act_id, act_range, act_range_ext, act_name, start_time, end_time FROM '.$GLOBALS['ecs']->table('favourable_activity')." WHERE start_time <= '$gmtime' AND end_time >= '$gmtime'";
    if (!empty($goods_id)) {
        $sql .= " AND CONCAT(',', user_rank, ',') LIKE '%".$user_rank."%'";
    }
    $res = $GLOBALS['db']->getAll($sql);

    if (empty($goods_id)) {
        foreach ($res as $rows) {
            $favourable[$rows['act_id']]['act_name'] = $rows['act_name'];
            $favourable[$rows['act_id']]['url'] = 'activity.php';
            $favourable[$rows['act_id']]['time'] = sprintf($GLOBALS['_LANG']['promotion_time'], local_date('Y-m-d', $rows['start_time']), local_date('Y-m-d', $rows['end_time']));
            $favourable[$rows['act_id']]['sort'] = $rows['start_time'];
            $favourable[$rows['act_id']]['type'] = 'favourable';
        }
    } else {
        $sql = 'SELECT cat_id, brand_id FROM '.$GLOBALS['ecs']->table('goods').
           "WHERE goods_id = '$goods_id'";
        $row = $GLOBALS['db']->getRow($sql);
        $category_id = $row['cat_id'];
        $brand_id = $row['brand_id'];

        foreach ($res as $rows) {
            if ($rows['act_range'] == FAR_ALL) {
                $favourable[$rows['act_id']]['act_name'] = $rows['act_name'];
                $favourable[$rows['act_id']]['url'] = 'activity.php';
                $favourable[$rows['act_id']]['time'] = sprintf($GLOBALS['_LANG']['promotion_time'], local_date('Y-m-d', $rows['start_time']), local_date('Y-m-d', $rows['end_time']));
                $favourable[$rows['act_id']]['sort'] = $rows['start_time'];
                $favourable[$rows['act_id']]['type'] = 'favourable';
            } elseif ($rows['act_range'] == FAR_CATEGORY) {
                /* 找出分类id的子分类id */
                $id_list = array();
                $raw_id_list = explode(',', $rows['act_range_ext']);
                foreach ($raw_id_list as $id) {
                    $id_list = array_merge($id_list, array_keys(cat_list($id, 0, false)));
                }
                $ids = implode(',', array_unique($id_list));

                if (strpos(','.$ids.',', ','.$category_id.',') !== false) {
                    $favourable[$rows['act_id']]['act_name'] = $rows['act_name'];
                    $favourable[$rows['act_id']]['url'] = 'activity.php';
                    $favourable[$rows['act_id']]['time'] = sprintf($GLOBALS['_LANG']['promotion_time'], local_date('Y-m-d', $rows['start_time']), local_date('Y-m-d', $rows['end_time']));
                    $favourable[$rows['act_id']]['sort'] = $rows['start_time'];
                    $favourable[$rows['act_id']]['type'] = 'favourable';
                }
            } elseif ($rows['act_range'] == FAR_BRAND) {
                if (strpos(','.$rows['act_range_ext'].',', ','.$brand_id.',') !== false) {
                    $favourable[$rows['act_id']]['act_name'] = $rows['act_name'];
                    $favourable[$rows['act_id']]['url'] = 'activity.php';
                    $favourable[$rows['act_id']]['time'] = sprintf($GLOBALS['_LANG']['promotion_time'], local_date('Y-m-d', $rows['start_time']), local_date('Y-m-d', $rows['end_time']));
                    $favourable[$rows['act_id']]['sort'] = $rows['start_time'];
                    $favourable[$rows['act_id']]['type'] = 'favourable';
                }
            } elseif ($rows['act_range'] == FAR_GOODS) {
                if (strpos(','.$rows['act_range_ext'].',', ','.$goods_id.',') !== false) {
                    $favourable[$rows['act_id']]['act_name'] = $rows['act_name'];
                    $favourable[$rows['act_id']]['url'] = 'activity.php';
                    $favourable[$rows['act_id']]['time'] = sprintf($GLOBALS['_LANG']['promotion_time'], local_date('Y-m-d', $rows['start_time']), local_date('Y-m-d', $rows['end_time']));
                    $favourable[$rows['act_id']]['sort'] = $rows['start_time'];
                    $favourable[$rows['act_id']]['type'] = 'favourable';
                }
            }
        }
    }

//    if(!empty($goods_id))
//    {
//        return array('snatch'=>$snatch, 'group_buy'=>$group, 'auction'=>$auction, 'favourable'=>$favourable);
//    }

    $sort_time = array();
    $arr = array_merge($snatch, $group, $auction, $favourable);
    foreach ($arr as $key => $value) {
        $sort_time[] = $value['sort'];
    }
    array_multisort($sort_time, SORT_NUMERIC, SORT_DESC, $arr);

    return $arr;
}

/**
 * 获得指定分类下所有底层分类的ID.
 *
 * @param int $cat 指定的分类ID
 *
 * @return string
 */
function get_children($cat = 0)
{
    return 'g.cat_id '.db_create_in(array_unique(array_merge(array($cat), array_keys(cat_list($cat, 0, false)))));
}

/**
 * 获得指定文章分类下所有底层分类的ID.
 *
 * @param int $cat 指定的分类ID
 */
function get_article_children($cat = 0)
{
    return db_create_in(array_unique(array_merge(array($cat), array_keys(article_cat_list($cat, 0, false)))), 'cat_id');
}

/**
 * 获取邮件模板
 *
 * @param:  $tpl_name[string]       模板代码
 *
 * @return array
 */
function get_mail_template($tpl_name)
{
    $sql = 'SELECT template_subject, is_html, template_content FROM '.$GLOBALS['ecs']->table('mail_templates')." WHERE template_code = '$tpl_name'";

    return $GLOBALS['db']->GetRow($sql);
}

/**
 * 记录订单操作记录.
 *
 * @param string $order_sn        订单编号
 * @param int    $order_status    订单状�&#65533;
 * @param int    $shipping_status 配送状�&#65533;
 * @param int    $pay_status      付款状�&#65533;
 * @param string $note            备注
 * @param string $username        用户名，用户自己的操作则�&#65533; buyer
 */
function order_action($order_sn, $order_status, $shipping_status, $pay_status, $note = '', $username = null)
{
    if (is_null($username)) {
        $username = $_SESSION['admin_name'];
    }

    $sql = 'INSERT INTO '.$GLOBALS['ecs']->table('order_action').
                ' (order_id, action_user, order_status, shipping_status, pay_status, action_note, log_time) '.
            'SELECT '.
                "order_id, '$username', '$order_status', '$shipping_status', '$pay_status', '$note', '".gmtime()."' ".
            'FROM '.$GLOBALS['ecs']->table('order_info')." WHERE order_sn = '$order_sn'";
    $GLOBALS['db']->query($sql);
}

/**
 * 格式化商品价�&#65533;.
 *
 * @param float $price 商品价格
 *
 * @return string
 */
function price_format($price, $change_price = true)
{
    empty($price) ? $price = 0 : $price = $price;
    if ($change_price && defined('ECS_ADMIN') === false) {
        switch ($GLOBALS['_CFG']['price_format']) {
            case 0:
                $price = number_format($price, 2, '.', '');
                break;
            case 1: // 保留不为 0 的尾�&#65533;
                $price = preg_replace('/(.*)(\\.)([0-9]*?)0+$/', '\1\2\3', number_format($price, 2, '.', ''));

                if (substr($price, -1) == '.') {
                    $price = substr($price, 0, -1);
                }
                break;
            case 2: // 不四舍五入，保留1�&#65533;
                $price = substr(number_format($price, 2, '.', ''), 0, -1);
                break;
            case 3: // 直接取整
                $price = intval($price);
                break;
            case 4: // 四舍五入，保�&#65533; 1 �&#65533;
                $price = number_format($price, 1, '.', '');
                break;
            case 5: // 先四舍五入，不保留小�&#65533;
                $price = round($price);
                break;
        }
    } else {
        $price = @number_format($price, 2, '.', '');
    }

    return sprintf($GLOBALS['_CFG']['currency_format'], $price);
}

/**
 * 返回订单中的虚拟商品.
 *
 * @param int  $order_id 订单id�&#65533;
 * @param bool $shipping 是否已经发货
 *
 * @return array()
 */
function get_virtual_goods($order_id, $shipping = false)
{
    if ($shipping) {
        $sql = 'SELECT goods_id, goods_name, send_number AS num, extension_code FROM '.
           $GLOBALS['ecs']->table('order_goods').
           " WHERE order_id = '$order_id' AND extension_code > ''";
    } else {
        $sql = 'SELECT goods_id, goods_name, (goods_number - send_number) AS num, extension_code FROM '.
           $GLOBALS['ecs']->table('order_goods').
           " WHERE order_id = '$order_id' AND is_real = 0 AND (goods_number - send_number) > 0 AND extension_code > '' ";
    }
    $res = $GLOBALS['db']->getAll($sql);

    $virtual_goods = array();
    foreach ($res as $row) {
        $virtual_goods[$row['extension_code']][] = array('goods_id' => $row['goods_id'], 'goods_name' => $row['goods_name'], 'num' => $row['num']);
    }

    return $virtual_goods;
}

/**
 *  虚拟商品发货.
 *
 * @param array  $virtual_goods 虚拟商品数组
 * @param string $msg           错误信息
 * @param string $order_sn      订单号�&#65533;
 * @param string $process       设定当前流程：split，发货分单流程；other，其他，默认�&#65533;
 *
 * @return bool
 */
function virtual_goods_ship(&$virtual_goods, &$msg, $order_sn, $return_result = false, $process = 'other')
{
    $virtual_card = array();
    foreach ($virtual_goods as $code => $goods_list) {
        /* 只处理虚拟卡 */
        if ($code == 'virtual_card') {
            foreach ($goods_list as $goods) {
                if (virtual_card_shipping($goods, $order_sn, $msg, $process)) {
                    if ($return_result) {
                        $virtual_card[] = array('goods_id' => $goods['goods_id'], 'goods_name' => $goods['goods_name'], 'info' => virtual_card_result($order_sn, $goods));
                    }
                } else {
                    return false;
                }
            }
            $GLOBALS['smarty']->assign('virtual_card',      $virtual_card);
        }
    }

    return true;
}

/**
 *  虚拟卡发�&#65533;.
 *
 * @param string $goods    商品详情数组
 * @param string $order_sn 本次操作的订�&#65533;
 * @param string $msg      返回信息
 * @param string $process  设定当前流程：split，发货分单流程；other，其他，默认�&#65533;
 *
 * @return boolen
 */
function virtual_card_shipping($goods, $order_sn, &$msg, $process = 'other')
{
    /* 包含加密解密函数所在文�&#65533; */
    include_once ROOT_PATH.'includes/lib_code.php';

    /* 检查有没有缺货 */
    $sql = 'SELECT COUNT(*) FROM '.$GLOBALS['ecs']->table('virtual_card')." WHERE goods_id = '$goods[goods_id]' AND is_saled = 0 ";
    $num = $GLOBALS['db']->GetOne($sql);

    if ($num < $goods['num']) {
        $msg .= sprintf($GLOBALS['_LANG']['virtual_card_oos'], $goods['goods_name']);

        return false;
    }

     /* 取出卡片信息 */
     $sql = 'SELECT card_id, card_sn, card_password, end_date, crc32 FROM '.$GLOBALS['ecs']->table('virtual_card')." WHERE goods_id = '$goods[goods_id]' AND is_saled = 0  LIMIT ".$goods['num'];
    $arr = $GLOBALS['db']->getAll($sql);

    $card_ids = array();
    $cards = array();

    foreach ($arr as $virtual_card) {
        $card_info = array();

        /* 卡号和密码解�&#65533; */

        $card_info['card_sn'] = decrypt($virtual_card['card_sn']);
        $card_info['card_password'] = decrypt($virtual_card['card_password']);

        $card_info['end_date'] = date($GLOBALS['_CFG']['date_format'], $virtual_card['end_date']);
        $card_ids[] = $virtual_card['card_id'];
        $cards[] = $card_info;
    }

     /* 标记已经取出的卡�&#65533; */
    $sql = 'UPDATE '.$GLOBALS['ecs']->table('virtual_card').' SET '.
           'is_saled = 1 ,'.
           "order_sn = '$order_sn' ".
           'WHERE '.db_create_in($card_ids, 'card_id');
    if (!$GLOBALS['db']->query($sql, 'SILENT')) {
        $msg .= $GLOBALS['db']->error();

        return false;
    }

    /* 更新库存 */
    $sql = 'UPDATE '.$GLOBALS['ecs']->table('goods')." SET goods_number = goods_number - '$goods[num]' WHERE goods_id = '$goods[goods_id]'";
    $GLOBALS['db']->query($sql);

    if (true) {
        /* 获取订单信息 */
        $sql = 'SELECT order_id, order_sn, consignee, email FROM '.$GLOBALS['ecs']->table('order_info')." WHERE order_sn = '$order_sn'";
        $order = $GLOBALS['db']->GetRow($sql);

        /* 更新订单信息 */
        if ($process == 'split') {
            $sql = 'UPDATE '.$GLOBALS['ecs']->table('order_goods')."
                    SET send_number = send_number + '".$goods['num']."'
                    WHERE order_id = '".$order['order_id']."'
                    AND goods_id = '".$goods['goods_id']."' ";
        } else {
            $sql = 'UPDATE '.$GLOBALS['ecs']->table('order_goods')."
                    SET send_number = '".$goods['num']."'
                    WHERE order_id = '".$order['order_id']."'
                    AND goods_id = '".$goods['goods_id']."' ";
        }

        if (!$GLOBALS['db']->query($sql, 'SILENT')) {
            $msg .= $GLOBALS['db']->error();

            return false;
        }
    }

    /* 发送邮�&#65533; */
    $GLOBALS['smarty']->assign('virtual_card',                   $cards);
    $GLOBALS['smarty']->assign('order',                          $order);
    $GLOBALS['smarty']->assign('goods',                          $goods);

    $GLOBALS['smarty']->assign('send_time', date('Y-m-d H:i:s'));
    $GLOBALS['smarty']->assign('shop_name', $GLOBALS['_CFG']['shop_name']);
    $GLOBALS['smarty']->assign('send_date', date('Y-m-d'));
    $GLOBALS['smarty']->assign('sent_date', date('Y-m-d'));

    $tpl = get_mail_template('virtual_card');
    $content = $GLOBALS['smarty']->fetch('str:'.$tpl['template_content']);
    send_mail($order['consignee'], $order['email'], $tpl['template_subject'], $content, $tpl['is_html']);

    return true;
}

/**
 *  返回虚拟卡信�&#65533;.
 *
 * @param
 */
function virtual_card_result($order_sn, $goods)
{
    /* 包含加密解密函数所在文�&#65533; */
    include_once ROOT_PATH.'includes/lib_code.php';

    /* 获取已经发送的卡片数据 */
    $sql = 'SELECT card_sn, card_password, end_date, crc32 FROM '.$GLOBALS['ecs']->table('virtual_card')." WHERE goods_id= '$goods[goods_id]' AND order_sn = '$order_sn' ";
    $res = $GLOBALS['db']->query($sql);

    $cards = array();

    while ($row = $GLOBALS['db']->FetchRow($res)) {
        /* 卡号和密码解�&#65533; */

            $row['card_sn'] = decrypt($row['card_sn']);
        $row['card_password'] = decrypt($row['card_password']);

        $cards[] = array('card_sn' => $row['card_sn'], 'card_password' => $row['card_password'], 'end_date' => date($GLOBALS['_CFG']['date_format'], $row['end_date']));
    }

    return $cards;
}

/**
 * 获取指定 id snatch 活动的结�&#65533;.
 *
 * @param int $id snatch_id
 *
 * @return array array(user_name, bie_price, bid_time, num)
 *               num通常�&#65533;1，如果为2表示�&#65533;2个用户取到最小值，但结果只返回最早出价用户�&#65533;
 */
function get_snatch_result($id)
{
    $sql = 'SELECT u.user_id, u.user_name, u.email, lg.bid_price, lg.bid_time, count(*) as num'.
            ' FROM '.$GLOBALS['ecs']->table('snatch_log').' AS lg '.
            ' LEFT JOIN '.$GLOBALS['ecs']->table('users').' AS u ON lg.user_id = u.user_id'.
            " WHERE lg.snatch_id = '$id'".
            ' GROUP BY lg.bid_price'.
            ' ORDER BY num ASC, lg.bid_price ASC, lg.bid_time ASC LIMIT 1';
    $rec = $GLOBALS['db']->GetRow($sql);

    if ($rec) {
        $rec['bid_time'] = local_date($GLOBALS['_CFG']['time_format'], $rec['bid_time']);
        $rec['formated_bid_price'] = price_format($rec['bid_price'], false);

        /* 活动信息 */
        $sql = 'SELECT ext_info " .
               " FROM '.$GLOBALS['ecs']->table('goods_activity').
               " WHERE act_id= '$id' AND act_type=".GAT_SNATCH.
               ' LIMIT 1';
        $row = $GLOBALS['db']->getOne($sql);
        $info = unserialize($row);

        if (!empty($info['max_price'])) {
            $rec['buy_price'] = ($rec['bid_price'] > $info['max_price']) ? $info['max_price'] : $rec['bid_price'];
        } else {
            $rec['buy_price'] = $rec['bid_price'];
        }

        /* 检查订�&#65533; */
        $sql = 'SELECT COUNT(*)'.
                ' FROM '.$GLOBALS['ecs']->table('order_info').
                " WHERE extension_code = 'snatch'".
                " AND extension_id = '$id'".
                ' AND order_status '.db_create_in(array(OS_CONFIRMED, OS_UNCONFIRMED));

        $rec['order_count'] = $GLOBALS['db']->getOne($sql);
    }

    return $rec;
}

/**
 *  清除指定后缀的模板缓存或编译文件.
 *
 * @param bool   $is_cache 是否清除缓存还是清出编译文件
 * @param string $ext      需要删除的文件名，不包含后缀
 *
 * @return int 返回清除的文件个�&#65533;
 */
function clear_tpl_files($is_cache = true, $ext = '')
{
    $dirs = array();

    if (isset($GLOBALS['shop_id']) && $GLOBALS['shop_id'] > 0) {
        $tmp_dir = USER_PATH.'temp';
    } else {
        $tmp_dir = 'temp';
    }
    if ($is_cache) {
        $cache_dir = ROOT_PATH.$tmp_dir.'/caches/';
        $dirs[] = ROOT_PATH.$tmp_dir.'/query_caches/';
        $dirs[] = ROOT_PATH.$tmp_dir.'/static_caches/';
        for ($i = 0; $i < 16; ++$i) {
            $hash_dir = $cache_dir.dechex($i);
            $dirs[] = $hash_dir.'/';
        }
    } else {
        $dirs[] = ROOT_PATH.$tmp_dir.'/compiled/';
        $dirs[] = ROOT_PATH.$tmp_dir.'/compiled/admin/';
    }

    $str_len = strlen($ext);
    $count = 0;

    foreach ($dirs as $dir) {
        $folder = @opendir($dir);

        if ($folder === false) {
            continue;
        }

        while ($file = readdir($folder)) {
            if ($file == '.' || $file == '..' || $file == 'index.htm' || $file == 'index.html') {
                continue;
            }
            if (is_file($dir.$file)) {
                /* 如果有文件名则判断是否匹�&#65533; */
                $pos = ($is_cache) ? strrpos($file, '_') : strrpos($file, '.');

                if ($str_len > 0 && $pos !== false) {
                    $ext_str = substr($file, 0, $pos);

                    if ($ext_str == $ext) {
                        if (@unlink($dir.$file)) {
                            ++$count;
                        }
                    }
                } else {
                    if (@unlink($dir.$file)) {
                        ++$count;
                    }
                }
            }
        }
        closedir($folder);
    }

    return $count;
}

/**
 *  清除指定后缀的模板缓存或编译文件(API专用).
 *
 * @param bool   $is_cache 是否清除缓存还是清出编译文件
 * @param string $ext      需要删除的文件名，不包含后缀
 *
 * @return int 返回清除的文件个�&#65533;
 */
function api_clear_tpl_files($is_cache = true, $ext = '', $user_dir)
{
    $dirs = array();
    $tmp_dir = $user_dir.'/temp';
    if ($is_cache) {
        $cache_dir = $tmp_dir.'/caches/';
        $dirs[] = $tmp_dir.'/query_caches/';
        $dirs[] = $tmp_dir.'/static_caches/';
        for ($i = 0; $i < 16; ++$i) {
            $hash_dir = $cache_dir.dechex($i);
            $dirs[] = $hash_dir.'/';
        }
    } else {
        $dirs[] = $tmp_dir.'/compiled/';
        $dirs[] = $tmp_dir.'/compiled/admin/';
    }

    $str_len = strlen($ext);
    $count = 0;

    foreach ($dirs as $dir) {
        $folder = @opendir($dir);

        if ($folder === false) {
            continue;
        }

        while ($file = readdir($folder)) {
            if ($file == '.' || $file == '..' || $file == 'index.htm' || $file == 'index.html') {
                continue;
            }
            if (is_file($dir.$file)) {
                /* 如果有文件名则判断是否匹�&#65533; */
                $pos = ($is_cache) ? strrpos($file, '_') : strrpos($file, '.');

                if ($str_len > 0 && $pos !== false) {
                    $ext_str = substr($file, 0, $pos);

                    if ($ext_str == $ext) {
                        if (@unlink($dir.$file)) {
                            ++$count;
                        }
                    }
                } else {
                    if (@unlink($dir.$file)) {
                        ++$count;
                    }
                }
            }
        }
        closedir($folder);
    }

    return $count;
}

/**
 * 清除模版编译文件.
 *
 * @param mix $ext 模版文件名， 不包含后缀
 */
function clear_compiled_files($ext = '')
{
    return clear_tpl_files(false, $ext);
}

/**
 * 清除缓存文件.
 *
 * @param mix $ext 模版文件名， 不包含后缀
 */
function clear_cache_files($ext = '')
{
    return clear_tpl_files(true, $ext);
}

/**
 * 清除模版编译和缓存文�&#65533;.
 *
 * @param mix $ext 模版文件名后缀
 */
function clear_all_files($ext = '')
{
    return clear_tpl_files(false, $ext) + clear_tpl_files(true,  $ext);
}

/**
 * 清除模版编译和缓存文�&#65533;(API调用专用).
 *
 * @param mix $ext 模版文件名后缀
 */
function api_clear_all_files($user_dir)
{
    return api_clear_tpl_files(false, '', $user_dir) + api_clear_tpl_files(true, '', $user_dir);
}

/**
 * 页面上调用的js文件.
 *
 * @param string $files
 */
function smarty_insert_scripts($args)
{
    static $scripts = array();

    $arr = explode(',', str_replace(' ', '', $args['files']));

    $str = '';
    foreach ($arr as $val) {
        if (in_array($val, $scripts) == false) {
            $scripts[] = $val;
            if ($val{0} == '.') {
                $str .= '<script type="text/javascript" src="'.$val.'"></script>';
            } else {
                $str .= '<script type="text/javascript" src="js/'.$val.'"></script>';
            }
        }
    }

    return $str;
}

/**
 * 创建分页的列�&#65533;.
 *
 * @param int $count
 *
 * @return string
 */
function smarty_create_pages($params)
{
    extract($params);

    $str = '';
    $len = 10;

    if (empty($page)) {
        $page = 1;
    }

    if (!empty($count)) {
        $step = 1;
        $str .= "<option value='1'>1</option>";

        for ($i = 2; $i < $count; $i += $step) {
            $step = ($i >= $page + $len - 1 || $i <= $page - $len + 1) ? $len : 1;
            $str .= "<option value='$i'";
            $str .= $page == $i ? " selected='true'" : '';
            $str .= ">$i</option>";
        }

        if ($count > 1) {
            $str .= "<option value='$count'";
            $str .= $page == $count ? " selected='true'" : '';
            $str .= ">$count</option>";
        }
    }

    return $str;
}

/**
 * 重写 URL 地址.
 *
 * @param string $app    执行程序
 * @param array  $params 参数数组
 * @param string $append 附加字串
 * @param int    $page   页数
 */
function build_uri($app, $params, $append = '', $page = 0, $size = 0)
{
    static $rewrite = null;

    if ($rewrite === null) {
        $rewrite = intval($GLOBALS['_CFG']['rewrite']);
    }

    $args = array('cid' => 0,
                  'gid' => 0,
                  'bid' => 0,
                  'acid' => 0,
                  'aid' => 0,
                  'sid' => 0,
                  'gbid' => 0,
                  'auid' => 0,
                  'sort' => '',
                  'order' => '',
                );

    extract(array_merge($args, $params));

    $uri = '';
    switch ($app) {
        case 'category':
            if (empty($cid)) {
                return false;
            } else {
                if ($rewrite) {
                    $uri = 'category-'.$cid;
                    if (isset($bid)) {
                        $uri .= '-b'.$bid;
                    }
                    if (isset($price_min)) {
                        $uri .= '-min'.$price_min;
                    }
                    if (isset($price_max)) {
                        $uri .= '-max'.$price_max;
                    }
                    if (isset($filter_attr)) {
                        $uri .= '-attr'.$filter_attr;
                    }
                    if (!empty($page)) {
                        $uri .= '-'.$page;
                    }
                    if (!empty($sort)) {
                        $uri .= '-'.$sort;
                    }
                    if (!empty($order)) {
                        $uri .= '-'.$order;
                    }
                } else {
                    $uri = 'category.php?id='.$cid;
                    if (!empty($bid)) {
                        $uri .= '&amp;brand='.$bid;
                    }
                    if (isset($price_min)) {
                        $uri .= '&amp;price_min='.$price_min;
                    }
                    if (isset($price_max)) {
                        $uri .= '&amp;price_max='.$price_max;
                    }
                    if (!empty($filter_attr)) {
                        $uri .= '&amp;filter_attr='.$filter_attr;
                    }

                    if (!empty($page)) {
                        $uri .= '&amp;page='.$page;
                    }
                    if (!empty($sort)) {
                        $uri .= '&amp;sort='.$sort;
                    }
                    if (!empty($order)) {
                        $uri .= '&amp;order='.$order;
                    }
                }
            }

            break;
        case 'goods':
            if (empty($gid)) {
                return false;
            } else {
                $uri = $rewrite ? 'goods-'.$gid : 'goods.php?id='.$gid;
            }

            break;
        case 'brand':
            if (empty($bid)) {
                return false;
            } else {
                if ($rewrite) {
                    $uri = 'brand-'.$bid;
                    if (isset($cid)) {
                        $uri .= '-c'.$cid;
                    }
                    if (!empty($page)) {
                        $uri .= '-'.$page;
                    }
                    if (!empty($sort)) {
                        $uri .= '-'.$sort;
                    }
                    if (!empty($order)) {
                        $uri .= '-'.$order;
                    }
                } else {
                    $uri = 'brand.php?id='.$bid;
                    if (!empty($cid)) {
                        $uri .= '&amp;cat='.$cid;
                    }
                    if (!empty($page)) {
                        $uri .= '&amp;page='.$page;
                    }
                    if (!empty($sort)) {
                        $uri .= '&amp;sort='.$sort;
                    }
                    if (!empty($order)) {
                        $uri .= '&amp;order='.$order;
                    }
                }
            }

            break;
        case 'article_cat':
            if (empty($acid)) {
                return false;
            } else {
                if ($rewrite) {
                    $uri = 'article_cat-'.$acid;
                    if (!empty($page)) {
                        $uri .= '-'.$page;
                    }
                    if (!empty($sort)) {
                        $uri .= '-'.$sort;
                    }
                    if (!empty($order)) {
                        $uri .= '-'.$order;
                    }
                } else {
                    $uri = 'article_cat.php?id='.$acid;
                    if (!empty($page)) {
                        $uri .= '&amp;page='.$page;
                    }
                    if (!empty($sort)) {
                        $uri .= '&amp;sort='.$sort;
                    }
                    if (!empty($order)) {
                        $uri .= '&amp;order='.$order;
                    }
                }
            }

            break;
        case 'article':
            if (empty($aid)) {
                return false;
            } else {
                $uri = $rewrite ? 'article-'.$aid : 'article.php?id='.$aid;
            }

            break;
        case 'group_buy':
            if (empty($gbid)) {
                return false;
            } else {
                $uri = $rewrite ? 'group_buy-'.$gbid : 'group_buy.php?act=view&amp;id='.$gbid;
            }

            break;
        case 'auction':
            if (empty($auid)) {
                return false;
            } else {
                $uri = $rewrite ? 'auction-'.$auid : 'auction.php?act=view&amp;id='.$auid;
            }

            break;
        case 'snatch':
            if (empty($sid)) {
                return false;
            } else {
                $uri = $rewrite ? 'snatch-'.$sid : 'snatch.php?id='.$sid;
            }

            break;
        case 'search':
            break;
        case 'exchange':
            if ($rewrite) {
                $uri = 'exchange-'.$cid;
                if (isset($price_min)) {
                    $uri .= '-min'.$price_min;
                }
                if (isset($price_max)) {
                    $uri .= '-max'.$price_max;
                }
                if (!empty($page)) {
                    $uri .= '-'.$page;
                }
                if (!empty($sort)) {
                    $uri .= '-'.$sort;
                }
                if (!empty($order)) {
                    $uri .= '-'.$order;
                }
            } else {
                $uri = 'exchange.php?cat_id='.$cid;
                if (isset($price_min)) {
                    $uri .= '&amp;integral_min='.$price_min;
                }
                if (isset($price_max)) {
                    $uri .= '&amp;integral_max='.$price_max;
                }

                if (!empty($page)) {
                    $uri .= '&amp;page='.$page;
                }
                if (!empty($sort)) {
                    $uri .= '&amp;sort='.$sort;
                }
                if (!empty($order)) {
                    $uri .= '&amp;order='.$order;
                }
            }

            break;
        case 'exchange_goods':
            if (empty($gid)) {
                return false;
            } else {
                $uri = $rewrite ? 'exchange-id'.$gid : 'exchange.php?id='.$gid.'&amp;act=view';
            }

            break;
        default:
            return false;
            break;
    }

    if ($rewrite) {
        if ($rewrite == 2 && !empty($append)) {
            $uri .= '-'.urlencode(preg_replace('/[\.|\/|\?|&|\+|\\\|\'|"|,]+/', '', $append));
        }

        $uri .= '.html';
    }
    if (($rewrite == 2) && (strpos(strtolower(EC_CHARSET), 'utf') !== 0)) {
        $uri = urlencode($uri);
    }

    return $uri;
}

/**
 * 格式化重量：小于1千克用克表示，否则用千克表示.
 *
 * @param float $weight 重量
 *
 * @return string 格式化后的重�&#65533;
 */
function formated_weight($weight)
{
    $weight = round(floatval($weight), 3);
    if ($weight > 0) {
        if ($weight < 1) {
            /* 小于1千克，用克表�&#65533; */
            return intval($weight * 1000).$GLOBALS['_LANG']['gram'];
        } else {
            /* 大于1千克，用千克表示 */
            return $weight.$GLOBALS['_LANG']['kilogram'];
        }
    } else {
        return 0;
    }
}

/**
 * 记录帐户变动.
 *
 * @param int    $user_id      用户id
 * @param float  $user_money   可用余额变动
 * @param float  $frozen_money 冻结余额变动
 * @param int    $rank_points  等级积分变动
 * @param int    $pay_points   消费积分变动
 * @param string $change_desc  变动说明
 * @param int    $change_type  变动类型：参见常量文�&#65533;
 */
function log_account_change($user_id, $user_money = 0, $frozen_money = 0, $rank_points = 0, $pay_points = 0, $change_desc = '', $change_type = ACT_OTHER)
{
    /* 插入帐户变动记录 */
    $account_log = array(
        'user_id' => $user_id,
        'user_money' => $user_money,
        'frozen_money' => $frozen_money,
        'rank_points' => $rank_points,
        'pay_points' => $pay_points,
        'change_time' => gmtime(),
        'change_desc' => $change_desc,
        'change_type' => $change_type,
    );
    $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('account_log'), $account_log, 'INSERT');

    /* 更新用户信息 */
    $sql = 'UPDATE '.$GLOBALS['ecs']->table('users').
            " SET user_money = user_money + ('$user_money'),".
            " frozen_money = frozen_money + ('$frozen_money'),".
            " rank_points = rank_points + ('$rank_points'),".
            " pay_points = pay_points + ('$pay_points')".
            " WHERE user_id = '$user_id' LIMIT 1";
    $GLOBALS['db']->query($sql);
}

/**
 * 获得指定分类下的子分类的数组.
 *
 * @param int  $cat_id   分类的ID
 * @param int  $selected 当前选中分类的ID
 * @param bool $re_type  返回的类�&#65533;: 值为真时返回下拉列表,否则返回数组
 * @param int  $level    限定返回的级数。为0时返回所有级�&#65533;
 *
 * @return mix
 */
function article_cat_list($cat_id = 0, $selected = 0, $re_type = true, $level = 0)
{
    static $res = null;

    if ($res === null) {
        $data = read_static_cache('art_cat_pid_releate');
        if ($data === false) {
            $sql = 'SELECT c.*, COUNT(s.cat_id) AS has_children, COUNT(a.article_id) AS aricle_num '.
               ' FROM '.$GLOBALS['ecs']->table('article_cat').' AS c'.
               ' LEFT JOIN '.$GLOBALS['ecs']->table('article_cat').' AS s ON s.parent_id=c.cat_id'.
               ' LEFT JOIN '.$GLOBALS['ecs']->table('article').' AS a ON a.cat_id=c.cat_id'.
               ' GROUP BY c.cat_id '.
               ' ORDER BY parent_id, sort_order ASC';
            $res = $GLOBALS['db']->getAll($sql);
            write_static_cache('art_cat_pid_releate', $res);
        } else {
            $res = $data;
        }
    }

    if (empty($res) == true) {
        return $re_type ? '' : array();
    }

    $options = article_cat_options($cat_id, $res); // 获得指定分类下的子分类的数组

    /* 截取到指定的缩减级别 */
    if ($level > 0) {
        if ($cat_id == 0) {
            $end_level = $level;
        } else {
            $first_item = reset($options); // 获取第一个元�&#65533;
            $end_level = $first_item['level'] + $level;
        }

        /* 保留level小于end_level的部�&#65533; */
        foreach ($options as $key => $val) {
            if ($val['level'] >= $end_level) {
                unset($options[$key]);
            }
        }
    }

    $pre_key = 0;
    foreach ($options as $key => $value) {
        $options[$key]['has_children'] = 1;
        if ($pre_key > 0) {
            if ($options[$pre_key]['cat_id'] == $options[$key]['parent_id']) {
                $options[$pre_key]['has_children'] = 1;
            }
        }
        $pre_key = $key;
    }

    if ($re_type == true) {
        $select = '';
        foreach ($options as $var) {
            $select .= '<option value="'.$var['cat_id'].'" ';
            $select .= ' cat_type="'.$var['cat_type'].'" ';
            $select .= ($selected == $var['cat_id']) ? "selected='ture'" : '';
            $select .= '>';
            if ($var['level'] > 0) {
                $select .= str_repeat('&nbsp;', $var['level'] * 4);
            }
            $select .= htmlspecialchars($var['cat_name']).'</option>';
        }

        return $select;
    } else {
        foreach ($options as $key => $value) {
            $options[$key]['url'] = build_uri('article_cat', array('acid' => $value['cat_id']), $value['cat_name']);
        }

        return $options;
    }
}

/**
 * 过滤和排序所有文章分类，返回一个带有缩进级别的数组.
 *
 * @param int   $cat_id 上级分类ID
 * @param array $arr    含有所有分类的数组
 * @param int   $level  级别
 */
function article_cat_options($spec_cat_id, $arr)
{
    static $cat_options = array();

    if (isset($cat_options[$spec_cat_id])) {
        return $cat_options[$spec_cat_id];
    }

    if (!isset($cat_options[0])) {
        $level = $last_cat_id = 0;
        $options = $cat_id_array = $level_array = array();
        while (!empty($arr)) {
            foreach ($arr as $key => $value) {
                $cat_id = $value['cat_id'];
                if ($level == 0 && $last_cat_id == 0) {
                    if ($value['parent_id'] > 0) {
                        break;
                    }

                    $options[$cat_id] = $value;
                    $options[$cat_id]['level'] = $level;
                    $options[$cat_id]['id'] = $cat_id;
                    $options[$cat_id]['name'] = $value['cat_name'];
                    unset($arr[$key]);

                    if ($value['has_children'] == 0) {
                        continue;
                    }
                    $last_cat_id = $cat_id;
                    $cat_id_array = array($cat_id);
                    $level_array[$last_cat_id] = ++$level;
                    continue;
                }

                if ($value['parent_id'] == $last_cat_id) {
                    $options[$cat_id] = $value;
                    $options[$cat_id]['level'] = $level;
                    $options[$cat_id]['id'] = $cat_id;
                    $options[$cat_id]['name'] = $value['cat_name'];
                    unset($arr[$key]);

                    if ($value['has_children'] > 0) {
                        if (end($cat_id_array) != $last_cat_id) {
                            $cat_id_array[] = $last_cat_id;
                        }
                        $last_cat_id = $cat_id;
                        $cat_id_array[] = $cat_id;
                        $level_array[$last_cat_id] = ++$level;
                    }
                } elseif ($value['parent_id'] > $last_cat_id) {
                    break;
                }
            }

            $count = count($cat_id_array);
            if ($count > 1) {
                $last_cat_id = array_pop($cat_id_array);
            } elseif ($count == 1) {
                if ($last_cat_id != end($cat_id_array)) {
                    $last_cat_id = end($cat_id_array);
                } else {
                    $level = 0;
                    $last_cat_id = 0;
                    $cat_id_array = array();
                    continue;
                }
            }

            if ($last_cat_id && isset($level_array[$last_cat_id])) {
                $level = $level_array[$last_cat_id];
            } else {
                $level = 0;
            }
        }
        $cat_options[0] = $options;
    } else {
        $options = $cat_options[0];
    }

    if (!$spec_cat_id) {
        return $options;
    } else {
        if (empty($options[$spec_cat_id])) {
            return array();
        }

        $spec_cat_id_level = $options[$spec_cat_id]['level'];

        foreach ($options as $key => $value) {
            if ($key != $spec_cat_id) {
                unset($options[$key]);
            } else {
                break;
            }
        }

        $spec_cat_id_array = array();
        foreach ($options as $key => $value) {
            if (($spec_cat_id_level == $value['level'] && $value['cat_id'] != $spec_cat_id) ||
                ($spec_cat_id_level > $value['level'])) {
                break;
            } else {
                $spec_cat_id_array[$key] = $value;
            }
        }
        $cat_options[$spec_cat_id] = $spec_cat_id_array;

        return $spec_cat_id_array;
    }
}

/**
 * 调用UCenter的函�&#65533;.
 *
 * @param string $func
 * @param array  $params
 *
 * @return mixed
 */
function uc_call($func, $params = null)
{
    restore_error_handler();
    if (!function_exists($func)) {
        include_once ROOT_PATH.'uc_client/client.php';
    }

    $res = call_user_func_array($func, $params);

    set_error_handler('exception_handler');

    return $res;
}

/**
 * error_handle回调函数.
 *
 * @return
 */
function exception_handler($errno, $errstr, $errfile, $errline)
{
    return;
}

/**
 * 重新获得商品图片与商品相册的地址.
 *
 * @param int    $goods_id 商品ID
 * @param string $image    原商品相册图片地址
 * @param bool   $thumb    是否为缩略图
 * @param string $call     调用方法(商品图片还是商品相册)
 * @param bool   $del      是否删除图片
 *
 * @return string $url
 */
/*function get_image_path($goods_id, $image='', $thumb=false, $call='goods', $del=false)
{
    $url = empty($image) ? $GLOBALS['_CFG']['no_picture'] : $image;
    return $url;
}
*/
/**
 * 调用使用UCenter插件时的函数.
 *
 * @param string $func
 * @param array  $params
 *
 * @return mixed
 */
function user_uc_call($func, $params = null)
{
    if (isset($GLOBALS['_CFG']['integrate_code']) && $GLOBALS['_CFG']['integrate_code'] == 'ucenter') {
        restore_error_handler();
        if (!function_exists($func)) {
            include_once ROOT_PATH.'includes/lib_uc.php';
        }

        $res = call_user_func_array($func, $params);

        set_error_handler('exception_handler');

        return $res;
    } else {
        return;
    }
}

/**
 * 取得商品优惠价格列表.
 *
 * @param string $goods_id   商品编号
 * @param string $price_type 价格类别(0为全店优惠比率，1为商品优惠价格，2为分类优惠比�&#65533;)
 *
 * @return 优惠价格列表
 */
function get_volume_price_list($goods_id, $price_type = '1')
{
    $volume_price = array();
    $temp_index = '0';

    $sql = 'SELECT `volume_number` , `volume_price`'.
           ' FROM '.$GLOBALS['ecs']->table('volume_price').''.
           " WHERE `goods_id` = '".$goods_id."' AND `price_type` = '".$price_type."'".
           ' ORDER BY `volume_number`';

    $res = $GLOBALS['db']->getAll($sql);

    foreach ($res as $k => $v) {
        $volume_price[$temp_index] = array();
        $volume_price[$temp_index]['number'] = $v['volume_number'];
        $volume_price[$temp_index]['price'] = $v['volume_price'];
        $volume_price[$temp_index]['format_price'] = price_format($v['volume_price']);
        ++$temp_index;
    }

    return $volume_price;
}

/**
 * 取得商品最终使用价�&#65533;.
 *
 * @param string $goods_id      商品编号
 * @param string $goods_num     购买数量
 * @param bool   $is_spec_price 是否加入规格价格
 * @param mix    $spec          规格ID的数组或者逗号分隔的字符串
 *
 * @return 商品最终购买价�&#65533;
 */
function get_final_price($goods_id, $goods_num = '1', $is_spec_price = false, $spec = array())
{
    $final_price = '0'; //商品最终购买价�&#65533;
    $volume_price = '0'; //商品优惠价格
    $promote_price = '0'; //商品促销价格
    $user_price = '0'; //商品会员价格

    //取得商品优惠价格列表
    $price_list = get_volume_price_list($goods_id, '1');

    if (!empty($price_list)) {
        foreach ($price_list as $value) {
            if ($goods_num >= $value['number']) {
                $volume_price = $value['price'];
            }
        }
    }

    //取得商品促销价格列表
    /* 取得商品信息 */
    $sql = 'SELECT g.promote_price, g.promote_start_date, g.promote_end_date, '.
                "IFNULL(mp.user_price, g.shop_price * '".$_SESSION['discount']."') AS shop_price ".
           ' FROM '.$GLOBALS['ecs']->table('goods').' AS g '.
           ' LEFT JOIN '.$GLOBALS['ecs']->table('member_price').' AS mp '.
                   "ON mp.goods_id = g.goods_id AND mp.user_rank = '".$_SESSION['user_rank']."' ".
           " WHERE g.goods_id = '".$goods_id."'".
           ' AND g.is_delete = 0';
    $goods = $GLOBALS['db']->getRow($sql);

    /* 计算商品的促销价格 */
    if ($goods['promote_price'] > 0) {
        $promote_price = bargain_price($goods['promote_price'], $goods['promote_start_date'], $goods['promote_end_date']);
    } else {
        $promote_price = 0;
    }

    //取得商品会员价格列表
    $user_price = $goods['shop_price'];

    //比较商品的促销价格，会员价格，优惠价格
    if (empty($volume_price) && empty($promote_price)) {
        //如果优惠价格，促销价格都为空则取会员价�&#65533;
        $final_price = $user_price;
    } elseif (!empty($volume_price) && empty($promote_price)) {
        //如果优惠价格为空时不参加这个比较�&#65533;
        $final_price = min($volume_price, $user_price);
    } elseif (empty($volume_price) && !empty($promote_price)) {
        //如果促销价格为空时不参加这个比较�&#65533;
        $final_price = min($promote_price, $user_price);
    } elseif (!empty($volume_price) && !empty($promote_price)) {
        //取促销价格，会员价格，优惠价格最小�&#65533;
        $final_price = min($volume_price, $promote_price, $user_price);
    } else {
        $final_price = $user_price;
    }

    //如果需要加入规格价�&#65533;
    if ($is_spec_price) {
        if (!empty($spec)) {
            $spec_price = spec_price($spec);
            $final_price += $spec_price;
        }
    }

    //返回商品最终购买价�&#65533;
    return $final_price;
}

/**
 * 获取指定id package 的信�&#65533;.
 *
 * @param int $id package_id
 *
 * @return array array(package_id, package_name, goods_id,start_time, end_time, min_price, integral)
 */
function get_package_info($id)
{
    global $ecs, $db,$_CFG;
    $id = is_numeric($id) ? intval($id) : 0;
    $now = gmtime();

    $sql = 'SELECT act_id AS id,  act_name AS package_name, goods_id , goods_name, start_time, end_time, act_desc, ext_info'.
           ' FROM '.$GLOBALS['ecs']->table('goods_activity').
           " WHERE act_id='$id' AND act_type = ".GAT_PACKAGE;

    $package = $db->GetRow($sql);

    /* 将时间转成可阅读格式 */
    if ($package['start_time'] <= $now && $package['end_time'] >= $now) {
        $package['is_on_sale'] = '1';
    } else {
        $package['is_on_sale'] = '0';
    }
    $package['start_time'] = local_date('Y-m-d H:i', $package['start_time']);
    $package['end_time'] = local_date('Y-m-d H:i', $package['end_time']);
    $row = unserialize($package['ext_info']);
    unset($package['ext_info']);
    if ($row) {
        foreach ($row as $key => $val) {
            $package[$key] = $val;
        }
    }

    $sql = 'SELECT pg.package_id, pg.goods_id, pg.goods_number, pg.admin_id, '.
           ' g.goods_sn, g.goods_name, g.market_price, g.goods_thumb, g.is_real, '.
           " IFNULL(mp.user_price, g.shop_price * '$_SESSION[discount]') AS rank_price ".
           ' FROM '.$GLOBALS['ecs']->table('package_goods').' AS pg '.
           '   LEFT JOIN '.$GLOBALS['ecs']->table('goods').' AS g '.
           '   ON g.goods_id = pg.goods_id '.
           ' LEFT JOIN '.$GLOBALS['ecs']->table('member_price').' AS mp '.
                "ON mp.goods_id = g.goods_id AND mp.user_rank = '$_SESSION[user_rank]' ".
           ' WHERE pg.package_id = '.$id.' '.
           ' ORDER BY pg.package_id, pg.goods_id';

    $goods_res = $GLOBALS['db']->getAll($sql);

    $market_price = 0;
    $real_goods_count = 0;
    $virtual_goods_count = 0;

    foreach ($goods_res as $key => $val) {
        $goods_res[$key]['goods_thumb'] = get_image_path($val['goods_id'], $val['goods_thumb'], true);
        $goods_res[$key]['market_price_format'] = price_format($val['market_price']);
        $goods_res[$key]['rank_price_format'] = price_format($val['rank_price']);
        $market_price += $val['market_price'] * $val['goods_number'];
        /* 统计实体商品和虚拟商品的个数 */
        if ($val['is_real']) {
            ++$real_goods_count;
        } else {
            ++$virtual_goods_count;
        }
    }

    if ($real_goods_count > 0) {
        $package['is_real'] = 1;
    } else {
        $package['is_real'] = 0;
    }

    $package['goods_list'] = $goods_res;
    $package['market_package'] = $market_price;
    $package['market_package_format'] = price_format($market_price);
    $package['package_price_format'] = price_format($package['package_price']);

    return $package;
}

/**
 * 获得指定礼包的商�&#65533;.
 *
 * @param int $package_id
 *
 * @return array
 */
function get_package_goods($package_id)
{
    $sql = "SELECT pg.goods_id, CONCAT(g.goods_name, ' -- [', pg.goods_number, ']') AS goods_name ".
            'FROM '.$GLOBALS['ecs']->table('package_goods').' AS pg, '.
                $GLOBALS['ecs']->table('goods').' AS g '.
            "WHERE pg.package_id = '$package_id' ".
            'AND pg.goods_id = g.goods_id ';
    if ($package_id == 0) {
        $sql .= " AND pg.admin_id = '$_SESSION[admin_id]'";
    }
    $row = $GLOBALS['db']->getAll($sql);

    return $row;
}

/**
 * 重新获得商品图片与商品相册的地址.
 *
 * @param int    $goods_id 商品ID
 * @param string $image    原商品相册图片地址
 * @param bool   $thumb    是否为缩略图
 * @param string $call     调用方法(商品图片还是商品相册)
 * @param bool   $del      是否删除图片
 */
function get_image_path($goods_id, $image = '', $thumb = false, $call = 'goods', $del = false)
{
    if (empty($GLOBALS['shop_id'])) {
        //   if(empty($image)) 

        $url = empty($image) ? $GLOBALS['_CFG']['no_picture'] : $image;
        //return $GLOBALS['_CFG']['no_picture'];
    } else {
        static $gim = 0;
        $url = '';
        if (!is_object($gim)) {
            if ($GLOBALS['shop_id'] > 34627) {
                $shop_id1 = $GLOBALS['shop_id'] - 34627;//减掉基数34627
                $dir_arr[] = ceil($shop_id1 / 22000);
                $no_picture = substr($GLOBALS['_CFG']['no_picture'], 1);
                $no_picture = '/'.$dir_arr[0].'_'.$no_picture;
            } else {
                $no_picture = $GLOBALS['_CFG']['no_picture'];
            }

            include_once ROOT_PATH.'includes/cls_goods_image.php';
            /* 新建处理商品图片相册的实�&#65533; */
            $gim = new cls_goods_image($GLOBALS['gim_cache_path'], $GLOBALS['gim_cache_url']);
            $gim->set_var($GLOBALS['domain'], $GLOBALS['_CFG']['image_width'], $GLOBALS['_CFG']['image_height'], $GLOBALS['_CFG']['thumb_width'], $GLOBALS['_CFG']['thumb_height'], $GLOBALS['db'], $GLOBALS['ecs'], IMAGE_DIR, $GLOBALS['_CFG']['watermark'], $GLOBALS['_CFG']['watermark_place'], $GLOBALS['_CFG']['watermark_alpha'], $no_picture, ROOT_PATH, $GLOBALS['_CFG']['bgcolor']);
        }
        if ($del === true) {
            return $gim->unlink_image($GLOBALS['shop_id'], $goods_id, $thumb, $call, $image);
        }
        if ($call == 'goods') {
            $url = $gim->get_goods_image($GLOBALS['shop_id'], $goods_id, $thumb, $image);
        } else {
            $url = $gim->get_gallery_image($GLOBALS['shop_id'], $goods_id, $image, $thumb);
        }
    }

    return $url;
}
//include_once("./user_files/bbs.wdwd.com/templates/greenwall/config_global_ldj.php");
;
