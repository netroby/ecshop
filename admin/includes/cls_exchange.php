<?php

/**
 * ECSHOP ��̨�Զ��������ݿ�����ļ�
 * ============================================================================
 * * ��Ȩ���� 2005-2012 �Ϻ���������Ƽ����޹�˾������������Ȩ����
 * ��վ��ַ: http://www.ecshop.com��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�
 * ʹ�ã�������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
 * ============================================================================
 * $Author: liubo $
 * $Id: cls_exchange.php 17217 2011-01-19 06:29:08Z liubo $.
 */
if (!defined('IN_ECS')) {
    die('Hacking attempt');
}

/*------------------------------------------------------ */
//-- �������������ݿ����ݽ��н���
/*------------------------------------------------------ */
class exchange
{
    public $table;
    public $db;
    public $id;
    public $name;
    public $error_msg;

    /**
     * ���캯��.
     *
     * @param string   $table ���ݿ����
     * @param dbobject $db    aodb�Ķ���
     * @param string   $id    ���ݱ������ֶ���
     * @param string   $name  ���ݱ���Ҫ����
     */
    public function exchange($table, &$db, $id, $name)
    {
        $this->table = $table;
        $this->db = &$db;
        $this->id = $id;
        $this->name = $name;
        $this->error_msg = '';
    }

    /**
     * �жϱ���ĳ�ֶ��Ƿ��ظ������ظ�����ֹ���򣬲�����������Ϣ.
     *
     * @param string $col  �ֶ���
     * @param string $name �ֶ�ֵ
     * @param int    $id
     */
    public function is_only($col, $name, $id = 0, $where = '')
    {
        $sql = 'SELECT COUNT(*) FROM '.$this->table." WHERE $col = '$name'";
        $sql .= empty($id) ? '' : ' AND '.$this->id." <> '$id'";
        $sql .= empty($where) ? '' : ' AND '.$where;

        return ($this->db->getOne($sql) == 0);
    }

    /**
     * ����ָ�����Ƽ�¼�����ݱ��м�¼����.
     *
     * @param string $col  �ֶ���
     * @param string $name �ֶ�����
     *
     * @return int ��¼����
     */
    public function num($col, $name, $id = 0)
    {
        $sql = 'SELECT COUNT(*) FROM '.$this->table." WHERE $col = '$name'";
        $sql .= empty($id) ? '' : ' AND '.$this->id." != '$id' ";

        return $this->db->getOne($sql);
    }

    /**
     * �༭ĳ���ֶ�.
     *
     * @param string $set Ҫ���¼�����" col = '$name', value = '$value'"
     * @param int    $id  Ҫ���µļ�¼���
     *
     * @return bool �ɹ���ʧ��
     */
    public function edit($set, $id)
    {
        $sql = 'UPDATE '.$this->table.' SET '.$set." WHERE $this->id = '$id'";

        if ($this->db->query($sql)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * ȡ��ĳ���ֶε�ֵ.
     *
     * @param int    $id ��¼���
     * @param string $id �ֶ���
     *
     * @return string ȡ��������
     */
    public function get_name($id, $name = '')
    {
        if (empty($name)) {
            $name = $this->name;
        }

        $sql = "SELECT `$name` FROM ".$this->table." WHERE $this->id = '$id'";

        return $this->db->getOne($sql);
    }

    /**
     * ɾ������¼.
     *
     * @param int $id ��¼���
     *
     * @return bool
     */
    public function drop($id)
    {
        $sql = 'DELETE FROM '.$this->table." WHERE $this->id = '$id'";

        return $this->db->query($sql);
    }
}
