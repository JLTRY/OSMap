<?php
/**
 * @package   OSMap
 * @copyright 2007-2014 XMap - Joomla! Vargas - Guillermo Vargas. All rights reserved.
 * @copyright 2016 Open Source Training, LLC. All rights reserved.
 * @contact   www.alledia.com, support@alledia.com
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

defined('_JEXEC') or die();

$printNodeCallback = function ($node) {
    $display = !$node->ignore
        && $node->published
        && !$node->duplicate
        && isset($node->images)
        && !empty($node->images);

    if (!$display) {
        return false;
    }


    echo '<url>';
    echo '<loc><![CDATA[' . $node->fullLink . ']]></loc>';

    foreach ($node->images as $image) {
        echo '<image:image>';
        echo '<image:loc><![CDATA[' . $image->src . ']]</image:loc>';
        echo '<image:title><![CDATA[' . $image->title . ']]></image:title>';

        if (isset($image->license)) {
            echo '<image:license><![CDATA[' . $image->license . ']]></image:license>';
        }

        echo '</image:image>';
    }

    echo '</url>';

    return true;
};

echo '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';

$this->sitemap->traverse($printNodeCallback);

echo '</urlset>';
