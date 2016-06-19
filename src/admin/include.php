<?php
/**
 * @package   OSMap
 * @copyright 2007-2014 XMap - Joomla! Vargas - Guillermo Vargas. All rights reserved.
 * @copyright 2016 Open Source Training, LLC. All rights reserved.
 * @contact   www.alledia.com, support@alledia.com
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

defined('_JEXEC') or die();

use Alledia\Framework;
use Alledia\OSMap;

// Alledia Framework
if (!defined('ALLEDIA_FRAMEWORK_LOADED')) {
    $allediaFrameworkPath = JPATH_SITE . '/libraries/allediaframework/include.php';

    if (file_exists($allediaFrameworkPath)) {
        require_once $allediaFrameworkPath;
    } else {
        JFactory::getApplication()
            ->enqueueMessage('[OSMap] Alledia framework not found', 'error');
    }
}

if (!defined('OSMAP_LOADED')) {
    define('OSMAP_LOADED', 1);
    define('OSMAP_ADMIN_PATH', JPATH_ADMINISTRATOR . '/components/com_osmap');
    define('OSMAP_SITE_PATH', JPATH_SITE . '/components/com_osmap');
    define('OSMAP_LIBRARY_PATH', OSMAP_ADMIN_PATH . '/library');

    // Define the constant for the license
    define(
        'OSMAP_LICENSE',
        file_exists(OSMAP_LIBRARY_PATH . '/alledia/osmap/Services/Pro.php') ? 'pro' : 'free'
    );

    // Setup autoload libraries
    Framework\AutoLoader::register('Alledia\OSMap', OSMAP_LIBRARY_PATH . '/alledia/osmap');
    Framework\AutoLoader::register('Pimple', OSMAP_LIBRARY_PATH . '/pimple/pimple');

    // Load OSMap Plugins
    JPluginHelper::importPlugin('osmap');

    // Load the language files
    OSMap\Helper\General::loadOptionLanguage('com_osmap', OSMAP_ADMIN_PATH, OSMAP_SITE_PATH);

    JTable::addIncludePath(OSMAP_ADMIN_PATH . '/tables');
    JForm::addFieldPath(OSMAP_ADMIN_PATH . '/fields');
    JForm::addFormPath(OSMAP_ADMIN_PATH . '/form');
}
