<?php

/**
 * ECSHOP Google sitemap ��
 * ============================================================================
 * * ��Ȩ���� 2005-2012 �Ϻ���������Ƽ����޹�˾������������Ȩ����
 * ��վ��ַ: http://www.ecshop.com��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�
 * ʹ�ã�������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
 * ============================================================================
 * $Author: liubo $
 * $Id: cls_google_sitemap.php 17217 2011-01-19 06:29:08Z liubo $.
 */
if (!defined('IN_ECS')) {
    die('Hacking attempt');
}

class google_sitemap
{
    public $header = "<\x3Fxml version=\"1.0\" encoding=\"UTF-8\"\x3F>\n\t<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">";
    public $charset = 'UTF-8';
    public $footer = "\t</urlset>\n";
    public $items = array();

    /**
     * ����һ���µ�����.
     *
     *@param    google_sitemap  item    $new_item
     */
    public function add_item($new_item)
    {
        $this->items[] = $new_item;
    }

    /**
     * ����XML�ĵ�.
     *
     *@param     string  $file_name  ����ṩ���ļ����������ļ������򷵻��ַ���.
     *
     *@return [void|string]
     */
    public function build($file_name = null)
    {
        $map = $this->header."\n";

        foreach ($this->items as $item) {
            $item->loc = htmlentities($item->loc, ENT_QUOTES);
            $map .= "\t\t<url>\n\t\t\t<loc>$item->loc</loc>\n";

            // lastmod
            if (!empty($item->lastmod)) {
                $map .= "\t\t\t<lastmod>$item->lastmod</lastmod>\n";
            }

            // changefreq
            if (!empty($item->changefreq)) {
                $map .= "\t\t\t<changefreq>$item->changefreq</changefreq>\n";
            }

            // priority
            if (!empty($item->priority)) {
                $map .= "\t\t\t<priority>$item->priority</priority>\n";
            }

            $map .= "\t\t</url>\n\n";
        }

        $map .= $this->footer."\n";

        if (!is_null($file_name)) {
            return file_put_contents($file_name, $map);
        } else {
            return $map;
        }
    }
}

class google_sitemap_item
{
    /**
     *@param    string  $loc        λ��
     *@param    string  $lastmod    ���ڸ�ʽ YYYY-MM-DD
     *@param    string  $changefreq ����Ƶ�ʵĵ�λ (always, hourly, daily, weekly, monthly, yearly, never)
     *@param    string  $priority   ����Ƶ�� 0-1
     */
    public function google_sitemap_item($loc, $lastmod = '', $changefreq = '', $priority = '')
    {
        $this->loc = $loc;
        $this->lastmod = $lastmod;
        $this->changefreq = $changefreq;
        $this->priority = $priority;
    }
}
