<?php

/**
 * ECSHOP 前台公用文件
 * ============================================================================
 * 版权所有 (C) 2005-2008 康盛创想（北京）科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；http://www.comsenz.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: lib_maifou.php 5885 2009-02-16 05:09:07Z liubo $.
 */
if (!defined('IN_ECS')) {
    die('Hacking attempt');
}
#require_once ROOT_PATH . 'includes/config.inc.php';

define('HTTP_HOST', get_http_host());

/**
 * 初始化参数函数.
 *
 * @return array
 *               array('domain', 'db_name')
 */
function base_init($check_locked = '1')
{
    $domain = base_get_domain();

    $db_name = $GLOBALS['db_prefix'].$domain;
    $siteinfo = base_get_siteinfo($domain);
    if ($siteinfo == 'is_old') {
        maifou_showmsg('对不起，该独立网店已经停止营业。<br />');
    }
    if ($siteinfo == false) {
        maifou_showmsg('对不起，该独立网店不存在，请确认网址输入是否正确。<br /><a href="http://bbs.wdwd.com/forumdisplay.php?fid=24" title="">点此进入支持论坛</a>');
    }
//    if($siteinfo['shop_id']=='156716')
//    {
//         echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
//        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
//        <html xmlns="http://www.w3.org/1999/xhtml">
//        <head>
//        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
//        <title>WDWD&trade; 我的网店--独立网店(基于最大的开源商城软件ECSHOP)</title>
//        <style type="text/css">
//        body{margin:0px; padding:15% 0 0 0; font-size:12px; font-family:"宋体"; line-height:20px; background:url(/data/404Bg.gif);
//        font-size:14px; color:#2d2d2d;
//        }
//        div{margin:0 auto; padding:0px;}
//        .conter{width:659px; height:186px; background:url() no-repeat left top;
//        padding:20px 0 0 37px;
//        }
//        .f1{font-size:16px; font-weight:bold; color:#ff0000; margin:17px 0 20px 130px; *margin:38px 0 20px 130px;}
//        .conter a{color:#0b79cd; text-decoration:underline;}
//        </style>
//        </head>
//        <body>
//         <div class="conter">
//          <p class="f1"> </p>
//          对不起，该网店升级中，敬请期待<br />
//         </div>
//        </body>
//        </html>';
//        exit;
//    }


    if ($check_locked) {
        // 判断网店是否被锁定
        if (isset($siteinfo['is_locked']) && $siteinfo['is_locked'] == 1) {
            header('HTTP/1.0 403 Access forbidden');
            maifou_showmsg('对不起，该独立网店已经被关闭。<br /> 如需继续使用，请联系我们。<br /> <a href="http://bbs.wdwd.com/forumdisplay.php?fid=24" title="">点此进入支持论坛</a>');
        }
    }
    if (isset($siteinfo['is_locked']) && $siteinfo['is_locked'] == 2) {
        if ($siteinfo['shop_id'] > '315768') {
            header('HTTP/1.0 403 Access forbidden');
            maifou_showmsg('对不起，您未购买服务或者未激活您的网店。<br /> 请先购买服务，然后在用户中心激活您的网站<br /> <a href="http://www.wdwd.com/user.php?act=login" title="">点此进入官网登录</a>');
        } else {
            header('HTTP/1.0 403 Access forbidden');
            //var_dump($shop_id,$siteinfo);
            maifou_showmsg('对不起，该网店已经90天未登录，已经被系统删除。<br /> 如需继续使用，请登录后在用户中心重新激活。<br /> <a href="http://www.wdwd.com/user.php?act=login" title="">点此进入官网登录</a>');
        }
    }
    // 判断网店是否过期

    if ((empty($siteinfo['base_service']) || time() > ($siteinfo['base_service'] + 86400 * 7)) && $siteinfo['level_info']['id'] > 0) {
        /* 已经超过网店过期时间，停止访问，显示续费页面 */
        maifou_showmsg('对不起，该独立网店的服务期限已经终止。<br /> 如需继续使用，请到用户中心购买服务!<br /> <a href="http://www.wdwd.com/user.php" title="">点此进入用户中心</a>');
    }
    if ($siteinfo['level_info']['id'] == 0 && $siteinfo['shop_id'] > '153696' && $siteinfo['base_service'] < time()) {
        maifou_showmsg('对不起，免费用户使用期限是一年，该独立网店的服务期限已经终止。<br /> 如需继续使用，请到用户中心购买服务!<br /> <a href="http://www.wdwd.com/user.php" title="">点此进入用户中心</a>');
    }
    // 判断顶级域名是否能被访问
//    if (empty($siteinfo['top_level_domain']))
//    {
//        maifou_showmsg('对不起，该独立网店的增值服务期限已经终止。<br /> 如需继续使用，请到用户中心购买服务!<br /> <a href="http://www.wdwd.com/user.php" title="">点此进入用户中心</a>');
//    }
//    tmp_count_pv($siteinfo['shop_id'], $domain);
    return array($domain, $db_name, $siteinfo['shop_id'], $siteinfo['db_host'], $siteinfo);
}

/**
 * 临时网店PV统计函数.
 */
function get_count_pv($shop_id)
{
    global $S_CFG;
    $link = @mysql_connect($S_CFG['users_ecshop'][1]['db_host'], $S_CFG['users_ecshop'][1]['db_user'], $S_CFG['users_ecshop'][1]['db_pass']);
    if ($link) {
        @mysql_select_db('www_maifou_net', $link);
        $flag_re = mysql_query("SELECT lastmonth,lastmonth_pv FROM ecs_shop_pv WHERE shop_id='$shop_id' LIMIT 1");
        $flag = mysql_fetch_row($flag_re);
        $now_month = date('Ym');
        if (empty($flag[0]) || $now_month != $flag[0]) {
            return 1;
        } else {
            return $flag[1];
        }
    } else {
        return 1;
    }
}

/**
 * Client调用 初始化参数函数.
 *
 * @return array
 *               array('domain', 'db_name')
 */
function client_base_init($host)
{
    $result = array('errno' => 0);
    $domain = base_get_domain(true, $host);
    if ($domain === false) {
        $result['errno'] = 1; // 域名未通过绑定审核或备案信息不合法
        return $result;
    }
    $siteinfo = base_get_siteinfo($domain);
    if ($siteinfo === false) {
        $result['errno'] = 2; // 未获得任何网店信息
        return $result;
    }
    // 判断网店是否被锁定
    if (isset($siteinfo['is_locked']) && $siteinfo['is_locked'] == 1) {
        $result['errno'] = 3; // 该独立网店已经被关闭
        return $result;
    }
    // 判断网店是否过期
    if (empty($siteinfo['base_service']) || time() > $siteinfo['base_service']) {
        $result['errno'] = 4; // 该独立网店的服务期限已经终止
        return $result;
    }
    // 判断顶级域名是否能被访问
    if ((strpos($host, $GLOBALS['server_domain']) === false) && (empty($siteinfo['top_level_domain']) || time() > $siteinfo['top_level_domain'])) {
        $result['errno'] = 5; // 该独立网店的顶级域名服务期限已经终止
        return $result;
    }
    $siteinfo['domain'] = $domain;
    $siteinfo['db_name'] = $GLOBALS['db_prefix'].$domain;
    $result['siteinfo'] = $siteinfo;

    return $result;
}

/**
 * 返回用户存储路径.
 *
 * @param int $shop_id
 *
 * @return array
 */
function parse_user_dir($shop_id)
{
    $level_dir[] = ceil($shop_id / 3000);
    $level_dir[] = $shop_id % 3000;

    return $level_dir;
}

/**
 * 获取网店域.
 *
 * @param bool $client 是否客户端调用 (默认：否)
 *
 * @return string 域
 */
function base_get_domain($client = false, $host = '')
{
    $http_host = empty($host) ? HTTP_HOST : $host;
    $list = get_domain_suffix();
    foreach ($list as $suffix) {
        if (strpos($http_host, $suffix['suffix_domain']) !== false) {
            return $http_host;
            //return str_replace($GLOBALS['server_domain'], '', $http_host);
        }
    }
    $domain = base_get_top_domain($http_host);
    if ($domain === false) {
        if ($client === true) {
            return false;
        } else {
            // 未绑定该域名;
                header('HTTP/1.0 403 Access forbidden');
            maifou_showmsg('对不起，域名 '.$http_host.' 尚未通过绑定审核或备案信息不合法,请联系我们的管理员');
        }
    } else {
        return $domain;
    }
}

/**
 * 获取网店的基础信息.
 *
 * @param string $domain 域
 */
function base_get_siteinfo($domain)
{
    $key = md5('domain_'.$domain);
    $siteinfo = get_memcache_data($key);
    //$siteinfo = false;
    if ($siteinfo === false || empty($siteinfo)) {
        $args = array('domain' => $domain);
        $result = remote_procedure_call('manage', 'getinfo', 'getexpire', $args);

        if ((!empty($result) && $result['value'] === true && $result['type'] == 'array') || $result['content'] == 'is_old') {
            $siteinfo = $result['content'];
            set_memcache_data($key, json_encode($siteinfo));
        } else {
            $siteinfo = false;
        }
    } else {
        $siteinfo = json_decode($siteinfo, 1);
    }

    return $siteinfo;
}
/**
 * 获取域名后缀列表.
 */
function get_domain_suffix()
{
    $key = md5('domain_suffix_list_maifou');
    $list = get_memcache_data($key);
    if ($list == false || empty($list)) {
        $args = array();
        $result = remote_procedure_call('manage', 'getinfo', 'getsuffix', $args);
        if (!empty($result) && $result['value'] === true && $result['type'] == 'string') {
            $list = $result['content'];
            set_memcache_data($key, $list);
        } else {
            $list = false;
        }
    }

    return $list;
    //return json_decode($list,1);
}
/**
 * 获取域By顶级域名.
 *
 * @param string $top_domain 顶级域名
 *
 * @return string 域
 */
function base_get_top_domain($top_domain)
{
    $key = md5('top_level_domain_'.$top_domain);
    $domain = get_memcache_data($key);
    if ($domain === false || empty($domain)) {
        $args = array('bound_for' => $top_domain);
        $result = remote_procedure_call('manage', 'getinfo', 'getdomain', $args);
        if (!empty($result) && $result['value'] === true && $result['type'] == 'string') {
            $domain = $result['content'];
            set_memcache_data($key, $domain);
        } elseif (!empty($result) && $result['value'] === 1 && $result['type'] == 'string') {
            $domain = $result['content'];
            header("Location:http://$domain");
            exit();
        } else {
            $domain = false;
        }
    }

    return $domain;
}

/**
 * 获取HTTP_SERVER_NAME.
 *
 * @return string 当前域名
 */
function get_http_host()
{
    if (isset($_SERVER['HTTP_X_FORWARDED_HOST'])) {
        $domain = $_SERVER['HTTP_X_FORWARDED_HOST'];
    } elseif (isset($_SERVER['HTTP_HOST'])) {
        $domain = $_SERVER['HTTP_HOST'];
    } elseif (isset($_SERVER['SERVER_NAME'])) {
        $domain = $_SERVER['SERVER_NAME'];
    } else {
        $domain = '';
    }

    return $domain;
}

/**
 * 存储内容到内存.
 *
 * @author dolphin
 *
 * @param string $key    内容KEY
 * @param string $data   内容
 * @param int    $expire 过期时间
 *
 * @return string || FALSE  对应KEY内容 或 布尔值(假)
 */
function set_memcache_data($key, $data, $expire = 3600)
{
    static $memcache_object;
    if (!is_object($memcache_object) && function_exists('memcache_connect')) {
        $memcache_object = @memcache_connect($GLOBALS['memcache_server'], $GLOBALS['memcache_port']);
    }
    if (is_object($memcache_object)) {
        memcache_set($memcache_object, $key, $data, 0, $expire);

        return true;
    }

    return false;
}

/**
 * 从内存取内容.
 *
 * @author dolphin
 *
 * @param string $key 内容KEY
 *
 * @return string || FALSE  对应KEY内容 或 布尔值(假)
 */
function get_memcache_data($key)
{
    static $memcache_object;

    if (!is_object($memcache_object) && function_exists('memcache_connect')) {
        $memcache_object = memcache_connect($GLOBALS['memcache_server'], $GLOBALS['memcache_port']);
    }
    if (is_object($memcache_object)) {
        $data = memcache_get($memcache_object, $key);

        return $data;
    }

    return false;
}

/**
 * 删除内存内容.
 *
 * @author dolphin
 *
 * @param string $key 内容KEY
 *
 * @return boolean布尔值(真|假)
 */
function delete_memcache_data($key)
{
    if (function_exists('memcache_connect')) {
        $memcache_object1 = @memcache_connect($GLOBALS['memcache_server1'], $GLOBALS['memcache_port']);
        $memcache_object2 = @memcache_connect($GLOBALS['memcache_server2'], $GLOBALS['memcache_port']);
    }
    if (is_object($memcache_object1)) {
        memcache_delete($memcache_object1, $key);
    }
    if (is_object($memcache_object2)) {
        memcache_delete($memcache_object2, $key);
    }

    return true;
}

/**
 * 网店异常时显示信息.
 *
 * @param string $msg 显示信息
 */
function maifou_showmsg($msg)
{
    $notice_str =
<<<EOT
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Refresh" content="10; URL=http://www.wdwd.com" />

<title>WDWD&trade; 我的网店--独立网店(基于最大的开源商城软件ECSHOP)</title>
<style type="text/css">
body{margin:0px; padding:15% 0 0 0; font-size:12px; font-family:"宋体"; line-height:20px; background:url(/data/404Bg.gif);
font-size:14px; color:#2d2d2d;
}
div{margin:0 auto; padding:0px;}
.conter{width:659px; height:186px; background:url(/data/404Bg1.gif) no-repeat left top;
padding:20px 0 0 37px;
}
.f1{font-size:16px; font-weight:bold; color:#ff0000; margin:17px 0 20px 130px; *margin:38px 0 20px 130px;}
.conter a{color:#0b79cd; text-decoration:underline;}
</style>
</head>
<body>
 <div class="conter">
  <p class="f1"><!-- span id="sec">10秒后</span --> <a href="http://www.wdwd.com">返回我的网店首页</a></p>
  @@temp_str@@
  <p>需要更多帮助请与我们联系 <a href="http://bbs.wdwd.com" style="color:#000;">我的网店支持论坛</a></p>
 </div>
 <script>
/*
var sec = 10;
window.setInterval("if(sec != 0)document.getElementById('sec').innerHTML = --sec;", 1000);
*/
</script>
</body>
</html>
EOT;
    $notice_str = str_replace('@@temp_str@@', $msg, $notice_str);
    exit($notice_str);
}

// ==================== API通讯基础函数 ==============================
/**
 * 远程过程调用 (API的主调用函数).
 *
 * @param string $site   请求应用站点 (bbs | manage | www)
 * @param string $module 模块名
 * @param string $action 动作
 * @param array  $args   发送的数据
 *
 * @return array
 */
function remote_procedure_call($site, $module, $action, $args = array())
{
    //    global $shop_id;
    $s = $sep = '';
    $args['api_key'] = $GLOBALS['api_key'];
    foreach ($args as $k => $v) {
        if (is_array($v)) {
            $s2 = $sep2 = '';
            foreach ($v as $k2 => $v2) {
                $s2 .= "$sep2{$k}[$k2]=".urlencode(api_stripslashes($v2));
                $sep2 = '&';
            }
            $s .= $sep.$s2;
        } else {
            $s .= "$sep$k=".urlencode(api_stripslashes($v));
        }
        $sep = '&';
    }
    $api_ip = $GLOBALS['api_ip_addr'][$site];

    $postdata = remote_requestdata($module, $action, $s);
//    if($shop_id==33419)
//    {
//         var_dump('http://'.$GLOBALS['api_url'][$site].'/api/api.php?',$postdata);
//    }
      //if($action=='limit_login')
      //{
          //var_dump('http://'.$GLOBALS['api_url'][$site].'/api/api.php?',$postdata);
          //exit;
      //}
    $result = remote_fopen('http://'.$GLOBALS['api_url'][$site].'/api/api.php', 0, $postdata, '', true, $api_ip, 20);
    if (!empty($result)) {
        $result = json_decode($result, 1);
    }

    return $result;
}
function remote_procedure_call2($site, $module, $action, $args = array())
{
    $s = $sep = '';
    $args['api_key'] = $GLOBALS['api_key'];
    foreach ($args as $k => $v) {
        if (is_array($v)) {
            $s2 = $sep2 = '';
            foreach ($v as $k2 => $v2) {
                $s2 .= "$sep2{$k}[$k2]=".urlencode(api_stripslashes($v2));
                $sep2 = '&';
            }
            $s .= $sep.$s2;
        } else {
            $s .= "$sep$k=".urlencode(api_stripslashes($v));
        }
        $sep = '&';
    }
    $api_ip = maifou_gethostbyname($GLOBALS['api_url'][$site]);
    $postdata = remote_requestdata($module, $action, $s);//var_dump($postdata);
    $result = remote_fopen('http://'.$GLOBALS['api_url'][$site].'/api/api.php', 0, $postdata, '', true, $api_ip, 20);
    if (!empty($result)) {
        $result = json_decode($result, 1);
    }

    return $result;
}

/**
 * 字符串加密以及解密函数.
 *
 * @param string $string    原文或者密文
 * @param string $operation 操作(ENCODE | DECODE), 默认为 DECODE
 * @param string $key       密钥
 * @param int    $expiry    密文有效期, 加密时候有效， 单位 秒，0 为永久有效
 *
 * @return string 处理后的 原文或者 经过 base64_encode 处理后的密文
 *
 * @example
 *
 *      $a = authcode('abc', 'ENCODE', 'key');
 *      $b = authcode($a, 'DECODE', 'key');  // $b(abc)
 *
 *      $a = authcode('abc', 'ENCODE', 'key', 3600);
 *      $b = authcode('abc', 'DECODE', 'key'); // 在一个小时内，$b(abc)，否则 $b 为空
 */
function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0)
{
    $ckey_length = 4;       //note 随机密钥长度 取值 0-32;
                                //note 加入随机密钥，可以令密文无任何规律，即便是原文和密钥完全相同，加密结果也会每次不同，增大破解难度。
                                //note 取值越大，密文变动规律越大，密文变化 = 16 的 $ckey_length 次方
                                //note 当此值为 0 时，则不产生随机密钥

        $key = md5($key ? $key : CODE_KEY);
    $keya = md5(substr($key, 0, 16));
    $keyb = md5(substr($key, 16, 16));
    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';

    $cryptkey = $keya.md5($keya.$keyc);
    $key_length = strlen($cryptkey);

    $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
    $string_length = strlen($string);

    $result = '';
    $box = range(0, 255);

    $rndkey = array();
    for ($i = 0; $i <= 255; ++$i) {
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);
    }

    for ($j = $i = 0; $i < 256; ++$i) {
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }

    for ($a = $j = $i = 0; $i < $string_length; ++$i) {
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;
        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }

    if ($operation == 'DECODE') {
        if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
            return substr($result, 26);
        } else {
            return '';
        }
    } else {
        return $keyc.str_replace('=', '', base64_encode($result));
    }
}

/**
 *  远程打开URL.
 *
 *  @param string $url       打开的url，　如 http://www.baidu.com/123.htm
 *  @param int $limit        取返回的数据的长度
 *  @param string $post      要发送的 POST 数据，如uid=1&password=1234
 *  @param string $cookie    要模拟的 COOKIE 数据，如uid=123&auth=a2323sd2323
 *  @param bool $bysocket    TRUE/FALSE 是否通过SOCKET打开
 *  @param string $ip        IP地址
 *  @param int $timeout      连接超时时间
 *  @param bool $block       是否为阻塞模式
 *
 *  @return                  取到的字符串
 */
function remote_fopen($url, $limit = 0, $post = '', $cookie = '', $bysocket = false, $ip = '', $timeout = 15, $block = true)
{
    //    global $shop_id;
    $return = '';
    $matches = parse_url($url);
    $host = $matches['host'];
    $path = $matches['path'] ? $matches['path'].'?'.@$matches['query'].(@$matches['fragment'] ? '#'.@$matches['fragment'] : '') : '/';
    $port = !empty($matches['port']) ? $matches['port'] : 80;
    if ($post) {
        $out = "POST $path HTTP/1.0\r\n";
        $out .= "Accept: */*\r\n";
        //$out .= "Referer: $boardurl\r\n";
        $out .= "Accept-Language: zh-cn\r\n";
        $out .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
        $out .= "Host: $host\r\n";
        $out .= 'Content-Length: '.strlen($post)."\r\n";
        $out .= "Connection: Close\r\n";
        $out .= "Cache-Control: no-cache\r\n";
        $out .= "Cookie: $cookie\r\n\r\n";
        $out .= $post;
    } else {
        $out = "GET $path HTTP/1.0\r\n";
        $out .= "Accept: */*\r\n";
        //$out .= "Referer: $boardurl\r\n";
        $out .= "Accept-Language: zh-cn\r\n";
        $out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
        $out .= "Host: $host\r\n";
        $out .= "Connection: Close\r\n";
        $out .= "Cookie: $cookie\r\n\r\n";
    }
    $fp = @fsockopen(($ip ? $ip : $host), $port, $errno, $errstr, $timeout);

    if (!$fp) {
        return '';//note $errstr : $errno \r\n
    } else {
        stream_set_blocking($fp, $block);
        stream_set_timeout($fp, $timeout);
        @fwrite($fp, $out);
        $status = stream_get_meta_data($fp);

        if (!$status['timed_out']) {
            while (!feof($fp)) {
                if (($header = @fgets($fp)) && ($header == "\r\n" ||  $header == "\n")) {
                    break;
                }
            }

            $stop = false;
            while (!feof($fp) && !$stop) {
                $data = fread($fp, ($limit == 0 || $limit > 8192 ? 8192 : $limit));
                $return .= $data;
                if ($limit) {
                    $limit -= strlen($data);
                    $stop = $limit <= 0;
                }
            }
        }

        @fclose($fp);

        return $return;
    }
}

/**
 * 取IP地址.
 *
 * @param string $domain 域名
 *
 * @return string IP地址
 */
function maifou_gethostbyname($domain)
{
    static $iphosts = array();
    if (!isset($iphosts[$domain])) {
        $iphosts[$domain] = @gethostbyname($domain);
    }

    return $iphosts[$domain];
}

function remote_requestdata($module, $action, $data)
{
    $_SERVER['HTTP_USER_AGENT'] = empty($_SERVER['HTTP_USER_AGENT']) ? time().date('YddHis') : $_SERVER['HTTP_USER_AGENT'];
    $s = urlencode(authcode($data.'&agent='.md5($_SERVER['HTTP_USER_AGENT']).'&time='.time(), 'ENCODE', CODE_KEY));
    $post = "m=$module&a=$action&input=$s";

    return $post;
}

function api_stripslashes($string)
{
    !defined('MAGIC_QUOTES_GPC') && define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
    if (MAGIC_QUOTES_GPC) {
        return stripslashes($string);
    } else {
        return $string;
    }
}

/**
 * 从内存取商户自定义模板内容.
 *
 * @author dolphin
 *
 * @param int    $domain   商户域名
 * @param string $theme    模板风格
 * @param string $filename 模板文件
 * @param string $type     模板内容类型
 *
 * @return string 模板内容
 */
function get_memcache_custom_template($domain, $theme, $filename, $type)
{
    $hash_key = md5($domain.$theme.$filename.$type);
    $content = get_memcache_data($hash_key);
    if ($content !== false) {
        return $content;
    }

    $sql = 'SELECT content, mtime FROM '.$GLOBALS['ecs']->table('tpldata').' WHERE '.
           "theme = '$theme' AND filename = '$filename' AND lang = '".$GLOBALS['_CFG']['lang']."' AND
            type = '$type'";
    $result = $GLOBALS['db']->getRow($sql);
    $content = $result['content'];
    $mtime = $result['mtime'];
    if (!empty($content)) {
        set_memcache_custom_template($domain, $theme, $filename, $type, $content, $mtime);

        return $content;
    } else {
        $content = '';
        if ($type == 'html') {
            $file = ROOT_PATH.'themes/'.$theme.'/'.$filename.'.dwt';
            $content = file_get_contents($file);
        }

        return $content;
    }
}

/**
 * 设置商户自定义模板内容.
 *
 * @author dolphin
 *
 * @param string $theme    模板风格
 * @param string $filename 模板文件
 * @param string $type     模板内容类型
 * @param string $content  模板内容
 *
 * @return bool
 */
function set_custom_template($theme, $filename, $type, $content, $time)
{
    if (empty($theme) || empty($filename) || empty($type) || empty($content)) {
        return false;
    }
    global $db;
    $content = addslashes($content);
    $sql = 'SELECT tpl_id FROM '.$GLOBALS['ecs']->table('tpldata').' WHERE '.
    "theme = '$theme' AND filename = '$filename' AND lang = '".$GLOBALS['_CFG']['lang']."' AND
            type = '$type'";
    $row = $db->getOne($sql);
    if (!empty($row)) {
        $sql = 'UPDATE '.$GLOBALS['ecs']->table('tpldata')." SET content='$content', mtime=$time WHERE ".
        "tpl_id = '$row'";
    } else {
        $sql = 'INSERT INTO '.$GLOBALS['ecs']->table('tpldata').' (theme, filename, type, content,lang, mtime) '.
        " VALUES ('$theme', '$filename', '$type', '$content','".$GLOBALS['_CFG']['lang']."', $time)";
    }
    $db->query($sql);

    if ($db->affected_rows() > 0) {
        return true;
    } else {
        return false;
    }
}

/**
 * 存储商户自定义模板内容到内存.
 *
 * @author dolphin
 *
 * @param int    $domain   商户域名
 * @param string $theme    模板风格
 * @param string $filename 模板文件
 * @param string $type     模板内容类型
 * @param string $content  模板内容
 * @param int    $mtime    模板更新时间
 *
 * @return bool
 */
function set_memcache_custom_template($domain, $theme, $filename, $type, $content, $mtime)
{
    $hash_key = md5($domain.$theme.$filename.$type);
    $hash_time_key = md5($domain.$theme.$filename.$type.'_time');
    set_memcache_data($hash_key, $content, $expire = 0);
    set_memcache_data($hash_time_key, $mtime, $expire = 0);

    return true;
}

function api_construct()
{
    global $db;
    $module = isset($_REQUEST['m']) ? $_REQUEST['m'] : 'none_app';
    if ($module != 'none_app' && in_array($module, $GLOBALS['api_objs'])) {
        $module_file = ROOT_PATH.'api/module/'.$module.'.php';
        if (file_exists($module_file)) {
            /*数据解析*/
            $data = authcode($_REQUEST['input'], 'DECODE', CODE_KEY);
            parse_str($data, $data);
            $action = $_REQUEST['a'];
            //引用module文件
            include $module_file;
        } else {
            show_api_msg(false, 'string', 'api module not exsit');
        }
    } else {
        show_api_msg(false, 'string', 'api module not in use');
    }
}

/**
 * 显示api的结果信息.
 *
 * @param bool   $value 结果
 * @param string $type  结果类型
 * @param string $msg   信息
 */
function show_api_msg($result = false, $type = 'string', $msg)
{
    echo json_encode(array('value' => $result, 'type' => $type, 'content' => $msg));

    exit;
}

function api_order($action, $order)
{
    global $db, $shop_id;
    if ($action == 'insert') {
        if (!empty($order['order_info'])) {
            $result = remote_procedure_call('manage', 'order', 'insert', $order);
        }
    } elseif ($action == 'remove') {
        if (!empty($order['ids'])) {
            $result = remote_procedure_call('manage', 'order', 'remove', $order);
        }
    } elseif ($action == 'edit') {
        if (!empty($order['ids']) && !empty($order['order_info'])) {
            $request['ids'] = $order['ids'];
            $request['shop_id'] = $shop_id;
            $request['order_info'] = $order['order_info'];
            if (isset($order['add_goods'])) {
                $request['add_goods'] = 'true';
                $request['order_goods'] = $order['order_goods'];
            }
            if (isset($order['remove_goods'])) {
                $request['remove_goods'] = 'true';
                $request['goods_ids'] = $order['goods_ids'];
            }
            $result = remote_procedure_call('manage', 'order', 'edit', $request);
        }
    } elseif ($action == 'merge') {
        if (!empty($order['to_order_sn']) && !empty($order['order_info']) && !empty($order['from_order_sn'])) {
            $to_order_sn = $order['to_order_sn'];
            $order_info = $order['order_info'];
            if (empty($order_info)) {
                $get_query = 'SELECT * FROM '.$this->ecs->table('order_info')." WHERE order_sn='{$to_order_sn}' AND shop_id='{$this->shop_id}'";
                $order_info = $this->db->getRow($get_query);
            }
            $order['order_info'] = serialize($order_info);
            $request['to_order_sn'] = $order['to_order_sn'];
            $request['from_order_sn'] = $order['from_order_sn'];
            $request['order_info'] = $order['order_info'];
            $request['ids'] = $order['ids'];
            $request['shop_id'] = $shop_id;
            $result = remote_procedure_call('manage', 'order', 'merge', $request);
        }
    } elseif ($action == 'insert_goods') {
        if (!empty($order['order_goods'])) {
            $result = remote_procedure_call('manage', 'order', 'insert_goods', $order);
        }
    } elseif ($action == 'remove_goods') {
        if (!empty($order['goods_ids'])) {
            $result = remote_procedure_call('manage', 'order', 'remove_goods', $order);
        }
    } elseif ($action == 'insert_log') {
        $result = remote_procedure_call('manage', 'paylog', 'insert', $order);
    } elseif ($action == 'edit_log') {
        $result = remote_procedure_call('manage', 'paylog', 'edit', $order);
    }

    return $result;
}

/**
 * 获取用户自定义语言项.
 *
 * @author dolphin
 *
 * @return array 语言项数组
 */
function get_user_custom_languages($fn = null)
{
    $domain = $GLOBALS['domain'];
    $theme = $GLOBALS['_CFG']['template'];
    $filenames = array('user', 'common', 'shopping_flow');
    global $_LANG;
    if (!empty($fn) && in_array($fn, $filenames)) {
        $filenames = array($fn);
    }
    $type = 'lang';

    foreach ($filenames as $filename) {
        $lang = get_memcache_custom_template($domain, $theme, $filename, $type);
        if (!empty($lang)) {
            $lang = unserialize($lang);
            foreach ($lang as $key => $val) {
                $key = str_replace('$', '', $key);
                eval("\$$key = \"\$val\";");
            }
        }
    }
}

/**
 * 后台管理函数 - 检查商品数量.
 *
 * @param bool $isajax 是否Ajax方式调用
 *
 * @return true | false
 */
function check_goods_amount($isajax = false, $isclient = false)
{
    static $goods_amount = -1;
    if ($goods_amount == -1) {
        $goods_amount = $GLOBALS['db']->getOne('SELECT count(*) FROM '.$GLOBALS['ecs']->table('goods')." WHERE `is_delete`='0'");
    }
    if ((empty($GLOBALS['personal']['goods_amount']) || time() > $GLOBALS['personal']['goods_amount']) && ($goods_amount > $GLOBALS['base_goods_amount'])) {
        if ($isclient === true) {
            return false;
        }
        if ($isajax === false) {
            sys_msg(sprintf($GLOBALS['_LANG']['goods_amount_error'], $GLOBALS['base_goods_amount']), 0, array(), false);
        } else {
            header("Location: $isajax\n");
            exit;
        }
    }

    return true;
}

/**
 * 根据ID获取区域名称.
 *
 * @param       int     region_id   区域id
 *
 * @return array
 */
function get_region_name($region_id = 0, $parent)
{
    if (empty($region_id)) {
        return '';
    }
    $sql = 'SELECT region_name FROM '.$GLOBALS['ecs']->table('region').
            " WHERE region_id = '$region_id' AND parent_id = '$parent'";

    return $GLOBALS['db']->getOne($sql);
}

/**
 * 根据名称获取区域ID.
 *
 * @param       string     region_name    区域名称
 *
 * @return array
 */
function get_region_id($region_name = '', $parent)
{
    if (empty($region_name)) {
        return '';
    }
    $sql = 'SELECT region_id FROM '.$GLOBALS['ecs']->table('region').
            " WHERE region_name = '$region_name' AND parent_id = '$parent'";

    return $GLOBALS['db']->getOne($sql);
}

/**
 * 重新解释商品图片路径.
 *
 * @param string &$goods_img 商品图片
 */
function parse_goods_img(&$goods_img)
{
    if (strpos($goods_img, DATA_DIR) !== 0) {
        $goods_img = DATA_DIR.'/'.$goods_img;
    }
}

/**
 * 促销信息到卖否首页.
 *
 * @param array $data
 *
 * @return bool
 */
function sales_promotion($data = array())
{
    if (!empty($data) && $data['title'] != '' && $data['item_type'] != 'bonus' && $GLOBALS['personal']['is_commerce'] == 1) {
        $data['domain'] = $GLOBALS['domain'];
        $data['shop_name'] = $GLOBALS['_CFG']['shop_name'];
        $data['img_url'] = empty($data['img_url']) ? '' : $data['img_url'];
        $action = ($data['act_type'] == 'insert') ? ('add_sales_promotion') : ('update_sales_promotion');
        $result = remote_procedure_call('manage', 'shop', $action, $data);
        if ($result['value'] == true) {
            return true;
        } else {
            return $result;
        }
    } else {
        return false;
    }
}

/**
 * 促销信息到卖否首页.
 *
 * @param array $data
 *
 * @return bool
 */
function del_sales_promotion($item_id, $item_type)
{
    if (!empty($item_id) && $item_type != 'bonus' && $GLOBALS['personal']['is_commerce'] == 1) {
        $data['domain'] = $GLOBALS['domain'];
        $data['item_id'] = $item_id;
        $data['item_type'] = $item_type;
        $result = remote_procedure_call('manage', 'shop', 'del_sales_promotion', $data);
        if ($result['value'] == true) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

/**
 * 推荐到卖否首页.
 *
 * @param string $goods_id 商品编号
 *
 * @return bool
 */
function add_maifou_special($ids)
{
    if (!empty($ids)) {
        $sql = 'SELECT  `goods_id`, `goods_name`, `shop_price`, `goods_img`
                FROM '.$GLOBALS['ecs']->table('goods').' WHERE `goods_id` '.db_create_in($ids);
        $goods_info = $GLOBALS['db']->getAll($sql);
        foreach ($goods_info as $key => $val) {
            $goods_info[$key]['goods_thumb'] = get_image_path($val['goods_id'], '', true);
        }

        $args = array('info_code' => serialize($goods_info), 'domain' => $GLOBALS['domain']);
        $result = remote_procedure_call('manage', 'shop', 'add_special', $args);
        if ($result['value'] == true) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

/**
 * 推荐到卖否首页.
 *
 * @return string $goods_id   商品编号，可以为多个，用 ',' 隔开
 */
function get_maifou_special_list()
{
    $args = array('domain' => $GLOBALS['domain']);
    $result = remote_procedure_call('manage', 'shop', 'get_special_list', $args);
    if ($result['value'] == true) {
        if (is_array($result['content'])) {
            foreach ($result['content'] as $v) {
                $goods_id[] .= $v['goods_id'];
            }
        }

        return implode(',', $goods_id);
    }

    return false;
}

/**
 * 更改已推荐到卖否'店主推荐商品'的商品信息.
 *
 * @param string $goods_id 商品编号
 */
function edit_maifou_special($goods_id)
{
    if (!empty($goods_id)  && $GLOBALS['personal']['is_commerce'] == 1) {
        $goods_arr = explode(',', get_maifou_special_list());
        if (in_array($goods_id, $goods_arr)) {
            $sql = 'SELECT  `goods_id`, `goods_name`, `shop_price`, `goods_img`
                    FROM '.$GLOBALS['ecs']->table('goods').' WHERE `goods_id` = \''.$goods_id.'\'';
            $goods = $GLOBALS['db']->getRow($sql);

            if (!empty($goods)) {
                $goods['domain'] = $GLOBALS['domain'];
                $goods['goods_thumb'] = get_image_path($goods['goods_id'], '', true);
                $result = remote_procedure_call('manage', 'shop', 'edit_special', $goods);
            }
        }
    }
}

/**
 * 取消推荐到卖否首页.
 *
 * @param string $goods_id 商品编号，可以为多个，用 ',' 隔开
 *
 * @return bool
 */
function del_maifou_special($goods_id)
{
    if (!empty($goods_id)) {
        $args = array('goods_id' => $goods_id, 'domain' => $GLOBALS['domain']);
        $result = remote_procedure_call('manage', 'shop', 'del_special', $args);

        if ($result['value'] == true) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}
/**
 * 更新商店名称.
 *
 * @param string $goods_id 商品编号，可以为多个，用 ',' 隔开
 *
 * @return bool
 */
function update_shop_name($shop_name)
{
    global $shop_id;
    if (!empty($shop_name)) {
        $args = array('shop_id' => $shop_id, 'shop_name' => $shop_name);
        $result = remote_procedure_call('manage', 'getinfo', 'update_shop_name', $args);

        if ($result['value'] == true) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}
//关键字过滤
function check_censor()
{
    if (empty($_REQUEST)) {
        return false;
    }
    $key = md5('check_word_list');
    $check_word_list = get_memcache_data($key);
    if ($check_word_list === false || empty($check_word_list)) {
        $result = remote_procedure_call('manage', 'getinfo', 'get_check_word', array());
        if (!empty($result) && $result['value'] === true && $result['type'] == 'string') {
            $check_word_list = str_replace('/', '\/', $result['content']);
            set_memcache_data($key, $check_word_list, '14400');//缓存4小时
        } else {
            return false;
        }
    }
    $check_str = null;
    foreach ($_REQUEST as $v) {
        if (is_numeric($v) || empty($v)) {
            continue;
        }
        if (is_array($v)) {
            $check_str .= var_export($v, true).' ';
        } else {
            $check_str .= $v.' ';
        }
    }
    if (preg_match("/($check_word_list)/i", $check_str)) {
        preg_match_all("/($check_word_list)/i", $check_str, $check_words);
        $check_words = array_unique($check_words['1']);
        $check_words = implode('、', $check_words);

        return $check_words;
    }
}

/**
 * 设定用户文件目录地址.
 *
 * @param int    $shop_id 网店ID
 * @param string $domain  网店域名
 *
 * @return array('一级目录','二级目录')
 */
function parse_dir($shop_id, $domain)
{
    if ($shop_id > 34627) {
        $shop_id = $shop_id - 34627;//减掉基数34627
         $dir_arr[] = ceil($shop_id / 22000);
        $dir_arr[] = $shop_id % 22000;
        define('USER_PATH', 'user_files/'.$dir_arr[0].'/'.$domain.'/');
    } else {
        $dir_arr[] = 0;
        $dir_arr[] = $shop_id;
        define('USER_PATH', 'user_files/'.$domain.'/');
    }

    return $dir_arr;
}

//获取团购商品列表
function get_groupon_list()
{
    $get_date = date('Y-m-d');
    $get_time = strtotime($get_date);
    $key = md5('get_groupon_list'.$get_date);
    $result = get_memcache_data($key);
    if ($result == false || empty($result)) {
        $groupon = remote_procedure_call('manage', 'groupon', 'get_all_list', array('shop_id' => $GLOBALS['shop_id'], 'get_time' => $get_time));
        if ($groupon['value'] == true && $groupon['type'] == 'array') {
            $out = $groupon['content'];
        }
        set_memcache_data($key, serialize($out), $out['expire']);
    } else {
        $out = unserialize($result);
    }

    return $out;
}
