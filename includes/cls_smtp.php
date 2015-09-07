<?php

/**
 * ECSHOP SMTP �ʼ���
 * ============================================================================
 * * ��Ȩ���� 2005-2012 �Ϻ���������Ƽ����޹�˾������������Ȩ����
 * ��վ��ַ: http://www.ecshop.com��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�
 * ʹ�ã�������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
 * ============================================================================
 * $Author: liubo $
 * $Id: cls_smtp.php 17217 2011-01-19 06:29:08Z liubo $.
 */
if (!defined('IN_ECS')) {
    die('Hacking attempt');
}

define('SMTP_STATUS_NOT_CONNECTED', 1, true);
define('SMTP_STATUS_CONNECTED',     2, true);

class smtp
{
    public $connection;
    public $recipients;
    public $headers;
    public $timeout;
    public $errors;
    public $status;
    public $body;
    public $from;
    public $host;
    public $port;
    public $helo;
    public $auth;
    public $user;
    public $pass;

    /**
     *  ����Ϊһ������
     *  host        SMTP ������������       Ĭ�ϣ�localhost
     *  port        SMTP �������Ķ˿�       Ĭ�ϣ�25
     *  helo        ����HELO���������      Ĭ�ϣ�localhost
     *  user        SMTP ���������û���     Ĭ�ϣ���ֵ
     *  pass        SMTP �������ĵ�½����   Ĭ�ϣ���ֵ
     *  timeout     ���ӳ�ʱ��ʱ��          Ĭ�ϣ�5.
     *
     *  @return  bool
     */
    public function smtp($params = array())
    {
        if (!defined('CRLF')) {
            define('CRLF', "\r\n", true);
        }

        $this->timeout = 10;
        $this->status = SMTP_STATUS_NOT_CONNECTED;
        $this->host = 'localhost';
        $this->port = 25;
        $this->auth = false;
        $this->user = '';
        $this->pass = '';
        $this->errors = array();

        foreach ($params as $key => $value) {
            $this->$key = $value;
        }

        $this->helo = $this->host;

        //  ���û�������û�������֤
        $this->auth = ('' == $this->user) ? false : true;
    }

    public function connect($params = array())
    {
        if (!isset($this->status)) {
            $obj = new self($params);

            if ($obj->connect()) {
                $obj->status = SMTP_STATUS_CONNECTED;
            }

            return $obj;
        } else {
            if (!empty($GLOBALS['_CFG']['smtp_ssl'])) {
                $this->host = 'ssl://'.$this->host;
            }
            $this->connection = @fsockopen($this->host, $this->port, $errno, $errstr, $this->timeout);

            if ($this->connection === false) {
                $this->errors[] = 'Access is denied.';

                return false;
            }

            @socket_set_timeout($this->connection, 0, 250000);

            $greeting = $this->get_data();

            if (is_resource($this->connection)) {
                $this->status = 2;

                return $this->auth ? $this->ehlo() : $this->helo();
            } else {
                log_write($errstr, __FILE__, __LINE__);
                $this->errors[] = 'Failed to connect to server: '.$errstr;

                return false;
            }
        }
    }

    /**
     * ����Ϊ����
     * recipients      �����˵�����
     * from            �����˵ĵ�ַ��Ҳ����Ϊ�ظ���ַ
     * headers         ͷ����Ϣ������
     * body            �ʼ�������.
     */
    public function send($params = array())
    {
        foreach ($params as $key => $value) {
            $this->$key = $value;
        }

        if ($this->is_connected()) {
            //  �������Ƿ���Ҫ��֤
            if ($this->auth) {
                if (!$this->auth()) {
                    return false;
                }
            }

            $this->mail($this->from);

            if (is_array($this->recipients)) {
                foreach ($this->recipients as $value) {
                    $this->rcpt($value);
                }
            } else {
                $this->rcpt($this->recipients);
            }

            if (!$this->data()) {
                return false;
            }

            $headers = str_replace(CRLF.'.', CRLF.'..', trim(implode(CRLF, $this->headers)));
            $body = str_replace(CRLF.'.', CRLF.'..', $this->body);
            $body = substr($body, 0, 1) == '.' ? '.'.$body : $body;

            $this->send_data($headers);
            $this->send_data('');
            $this->send_data($body);
            $this->send_data('.');

            return (substr($this->get_data(), 0, 3) === '250');
        } else {
            $this->errors[] = 'Not connected!';

            return false;
        }
    }

    public function helo()
    {
        if (is_resource($this->connection)
                and $this->send_data('HELO '.$this->helo)
                and substr($error = $this->get_data(), 0, 3) === '250') {
            return true;
        } else {
            $this->errors[] = 'HELO command failed, output: '.trim(substr($error, 3));

            return false;
        }
    }

    public function ehlo()
    {
        if (is_resource($this->connection)
                and $this->send_data('EHLO '.$this->helo)
                and substr($error = $this->get_data(), 0, 3) === '250') {
            return true;
        } else {
            $this->errors[] = 'EHLO command failed, output: '.trim(substr($error, 3));

            return false;
        }
    }

    public function auth()
    {
        if (is_resource($this->connection)
                and $this->send_data('AUTH LOGIN')
                and substr($error = $this->get_data(), 0, 3) === '334'
                and $this->send_data(base64_encode($this->user))            // Send username
                and substr($error = $this->get_data(), 0, 3) === '334'
                and $this->send_data(base64_encode($this->pass))            // Send password
                and substr($error = $this->get_data(), 0, 3) === '235') {
            return true;
        } else {
            $this->errors[] = 'AUTH command failed: '.trim(substr($error, 3));

            return false;
        }
    }

    public function mail($from)
    {
        if ($this->is_connected()
            and $this->send_data('MAIL FROM:<'.$from.'>')
            and substr($this->get_data(), 0, 2) === '250') {
            return true;
        } else {
            return false;
        }
    }

    public function rcpt($to)
    {
        if ($this->is_connected()
            and $this->send_data('RCPT TO:<'.$to.'>')
            and substr($error = $this->get_data(), 0, 2) === '25') {
            return true;
        } else {
            $this->errors[] = trim(substr($error, 3));

            return false;
        }
    }

    public function data()
    {
        if ($this->is_connected()
            and $this->send_data('DATA')
            and substr($error = $this->get_data(), 0, 3) === '354') {
            return true;
        } else {
            $this->errors[] = trim(substr($error, 3));

            return false;
        }
    }

    public function is_connected()
    {
        return (is_resource($this->connection) and ($this->status === SMTP_STATUS_CONNECTED));
    }

    public function send_data($data)
    {
        if (is_resource($this->connection)) {
            return fwrite($this->connection, $data.CRLF, strlen($data) + 2);
        } else {
            return false;
        }
    }

    public function get_data()
    {
        $return = '';
        $line = '';

        if (is_resource($this->connection)) {
            while (strpos($return, CRLF) === false or $line{3} !== ' ') {
                $line = fgets($this->connection, 512);
                $return .= $line;
            }

            return trim($return);
        } else {
            return '';
        }
    }

    /**
     * ������һ��������Ϣ.
     *
     * @return string
     */
    public function error_msg()
    {
        if (!empty($this->errors)) {
            $len = count($this->errors) - 1;

            return $this->errors[$len];
        } else {
            return '';
        }
    }
}
