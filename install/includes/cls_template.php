<?php

/**
 * ECSHOP ģ����
 * ============================================================================
 * * ��Ȩ���� 2005-2012 �Ϻ���������Ƽ����޹�˾������������Ȩ����
 * ��վ��ַ: http://www.ecshop.com��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�
 * ʹ�ã�������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
 * ============================================================================
 * $Author: liubo $
 * $Id: cls_template.php 17217 2011-01-19 06:29:08Z liubo $.
 */
if (!defined('IN_ECS')) {
    die('Hacking attempt');
}

class template
{
    /*
    * �����洢�����Ŀռ�
    *
    * @access  private
    * @var     array      $vars
    */
    public $vars = array();

   /*
    * ģ���ŵ�Ŀ¼·��
    *
    * @access  private
    * @var     string      $path
    */
    public $path = '';

    /**
     * ���캯��.
     *
     * @param string $path
     */
    public function __construct($path)
    {
        $this->template($path);
    }

    /**
     * ���캯��.
     *
     * @param string $path
     */
    public function template($path)
    {
        $this->path = $path;
    }

    /**
     * ģ��smarty��assign����.
     *
     * @param string $name  ����������
     * @param mix    $value ������ֵ
     */
    public function assign($name, $value)
    {
        $this->vars[$name] = $value;
    }

    /**
     * ģ��smarty��fetch����.
     *
     * @param string $file �ļ����·��
     *
     * @return string ģ�������(�ı���ʽ)
     */
    public function fetch($file)
    {
        extract($this->vars);
        ob_start();
        include $this->path.$file;
        $contents = ob_get_contents();
        ob_end_clean();

        return $contents;
    }

    /**
     * ģ��smarty��display����.
     *
     * @param string $file �ļ����·��
     */
    public function display($file)
    {
        echo $this->fetch($file);
    }
}
