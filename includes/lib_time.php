<?php

/**
 * ECSHOP ʱ�亯��
 * ============================================================================
 * * ��Ȩ���� 2005-2012 �Ϻ���������Ƽ����޹�˾������������Ȩ����
 * ��վ��ַ: http://www.ecshop.com��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�
 * ʹ�ã�������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
 * ============================================================================
 * $Author: liubo $
 * $Id: lib_time.php 17217 2011-01-19 06:29:08Z liubo $.
 */
if (!defined('IN_ECS')) {
    die('Hacking attempt');
}

/**
 * ��õ�ǰ��������ʱ���ʱ���.
 *
 * @return int
 */
function gmtime()
{
    return (time() - date('Z'));
}

/**
 * ��÷�������ʱ��.
 *
 * @return int
 */
function server_timezone()
{
    if (function_exists('date_default_timezone_get')) {
        return date_default_timezone_get();
    } else {
        return date('Z') / 3600;
    }
}

/**
 *  ����һ���û��Զ���ʱ�����ڵ�GMTʱ���.
 *
 * @param int $hour
 * @param int $minute
 * @param int $second
 * @param int $month
 * @param int $day
 * @param int $year
 */
function local_mktime($hour = null, $minute = null, $second = null,  $month = null,  $day = null,  $year = null)
{
    $timezone = isset($_SESSION['timezone']) ? $_SESSION['timezone'] : $GLOBALS['_CFG']['timezone'];

    /**
     * $time = mktime($hour, $minute, $second, $month, $day, $year) - date('Z') + (date('Z') - $timezone * 3600)
     * ����mktime����ʱ������ټ�ȥdate('Z')ת��ΪGMTʱ�䣬Ȼ������Ϊ�û��Զ���ʱ�䡣�����ǻ������.
     **/
    $time = mktime($hour, $minute, $second, $month, $day, $year) - $timezone * 3600;

    return $time;
}

/**
 * ��GMTʱ�����ʽ��Ϊ�û��Զ���ʱ������.
 *
 * @param string $format
 * @param int    $time   �ò���������һ��GMT��ʱ���
 *
 * @return string
 */
function local_date($format, $time = null)
{
    $timezone = isset($_SESSION['timezone']) ? $_SESSION['timezone'] : $GLOBALS['_CFG']['timezone'];

    if ($time === null) {
        $time = gmtime();
    } elseif ($time <= 0) {
        return '';
    }

    $time += ($timezone * 3600);

    return date($format, $time);
}

/**
 * ת���ַ�����ʽ��ʱ����ʽΪGMTʱ���.
 *
 * @param string $str
 *
 * @return int
 */
function gmstr2time($str)
{
    $time = strtotime($str);

    if ($time > 0) {
        $time -= date('Z');
    }

    return $time;
}

/**
 *  ��һ���û��Զ���ʱ��������תΪGMTʱ���.
 *
 * @param string $str
 *
 * @return int
 */
function local_strtotime($str)
{
    $timezone = isset($_SESSION['timezone']) ? $_SESSION['timezone'] : $GLOBALS['_CFG']['timezone'];

    /**
     * $time = mktime($hour, $minute, $second, $month, $day, $year) - date('Z') + (date('Z') - $timezone * 3600)
     * ����mktime����ʱ������ټ�ȥdate('Z')ת��ΪGMTʱ�䣬Ȼ������Ϊ�û��Զ���ʱ�䡣�����ǻ������.
     **/
    $time = strtotime($str) - $timezone * 3600;

    return $time;
}

/**
 * ����û�����ʱ��ָ����ʱ���.
 *
 * @param   $timestamp integer     ��ʱ���������һ�����������ص�ʱ���
 *
 * @return array
 */
function local_gettime($timestamp = null)
{
    $tmp = local_getdate($timestamp);

    return $tmp[0];
}

/**
 * ����û�����ʱ��ָ�������ں�ʱ����Ϣ.
 *
 * @param   $timestamp integer     ��ʱ���������һ�����������ص�ʱ���
 *
 * @return array
 */
function local_getdate($timestamp = null)
{
    $timezone = isset($_SESSION['timezone']) ? $_SESSION['timezone'] : $GLOBALS['_CFG']['timezone'];

    /* ���ʱ���Ϊ�գ����÷������ĵ�ǰʱ�� */
    if ($timestamp === null) {
        $timestamp = time();
    }

    $gmt = $timestamp - date('Z');       // �õ���ʱ��ĸ�������ʱ��
    $local_time = $gmt + ($timezone * 3600);    // ת��Ϊ�û�����ʱ����ʱ���

    return getdate($local_time);
}
