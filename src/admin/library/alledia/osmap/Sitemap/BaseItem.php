<?php
/**
 * @package   OSMap
 * @contact   www.joomlashack.com, help@joomlashack.com
 * @copyright 2007-2014 XMap - Joomla! Vargas - Guillermo Vargas. All rights reserved.
 * @copyright 2016-2021 Joomlashack.com. All rights reserved.
 * @license   https://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 * This file is part of OSMap.
 *
 * OSMap is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * OSMap is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OSMap.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace Alledia\OSMap\Sitemap;

use Alledia\OSMap\Factory;
use Alledia\OSMap\Helper\General;
use Joomla\CMS\Date\Date;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Language\Text;
use Joomla\Registry\Registry;

defined('_JEXEC') or die();

/**
 * Sitemap item
 */
class BaseItem extends \JObject
{
    /**
     * @var int;
     */
    public $id = null;

    /**
     * @var string;
     */
    public $uid = null;

    /**
     * Item's link, which can be relative or un-routed
     *
     * @var string
     */
    public $link = null;

    /**
     * Routed full link, sanitized and without any hash segment
     *
     * @var string
     */
    public $fullLink = null;

    /**
     * Routed fulll link, sanitized but can contains a hash segment
     *
     * @var string
     */
    public $rawLink = null;

    /**
     * @var Registry
     */
    public $params = null;

    /**
     * @var string
     */
    public $priority = null;

    /**
     * @var string
     */
    public $changefreq = null;

    /**
     * @var string
     */
    public $created = null;

    /**
     * @var string
     */
    public $modified = null;

    /**
     * @var string
     */
    public $publishUp = null;

    /**
     * The component associated to the option URL param
     *
     * @var string
     */
    public $component = null;

    /**
     * @var bool
     */
    public $ignore = false;

    /**
     * @var bool
     */
    public $duplicate = false;

    /**
     * @var int
     */
    public $browserNav = null;

    /**
     * @var bool
     */
    public $isInternal = true;

    /**
     * @var bool
     */
    public $home = false;

    /**
     * @var string
     */
    public $type = null;

    /**
     * @var bool
     */
    public $expandible = false;

    /**
     * @var bool
     */
    public $secure = false;

    /**
     * @var int
     */
    public $isMenuItem = 0;

    /**
     * @var bool
     */
    public $published = 1;

    /**
     * @var string
     */
    public $name = '';

    /**
     * @var array
     */
    public $images = array();

    /**
     * @var string
     */
    public $settingsHash = null;

    /**
     * @var int
     */
    public $level = null;

    /**
     * @var string
     */
    public $adapterName = 'Generic';

    /**
     * @var object
     */
    public $adapter = null;

    /**
     * If true, says the item is visible for robots
     *
     * @var bool
     */
    public $visibleForRobots = true;

    /**
     * If true, says the item's parent is visible for robots
     *
     * @var bool
     */
    public $parentIsVisibleForRobots = true;

    /**
     * Stores a list of notes generated by the collector to display in the admin
     *
     * @var array
     */
    public $adminNotes = null;

    /**
     * @var bool
     */
    public $visibleForXML = true;

    /**
     * @var bool
     */
    public $visibleForHTML = true;

    /**
     * @var int
     */
    public $menuItemId = 0;

    /**
     * @var string
     */
    public $menuItemName = null;

    /**
     * @var string
     */
    public $menuItemType = null;

    /**
     * @var array
     */
    public $subnodes = null;

    /**
     * @var string
     */
    public $slug = null;

    /**
     * The constructor
     *
     * @param array $itemData
     *
     * @return void
     */
    public function __construct($itemData)
    {
        parent::__construct($itemData);

        if (class_exists('\\Alledia\\OSMap\\Sitemap\\ItemAdapter\\GenericPro')) {
            $this->adapterName = 'GenericPro';
        }
    }

    /**
     * Extract the option from the link, to identify the component called by
     * the link.
     *
     * @return void
     */
    protected function extractComponentFromLink()
    {
        $this->component = null;

        if (preg_match('#^/?index.php.*option=(com_[^&]+)#', $this->link, $matches)) {
            $this->component = $matches[1];
        }
    }

    /**
     * Adds the note to the admin note attribute and initialize the variable
     * if needed
     *
     * @param string $note
     *
     * @return void
     */
    public function addAdminNote($note)
    {
        if (!is_array($this->adminNotes)) {
            $this->adminNotes = array();
        }

        $this->adminNotes[] = Text::_($note);
    }

    /**
     * Returns the admin notes as a string.
     *
     * @return string
     */
    public function getAdminNotesString()
    {
        if (!empty($this->adminNotes)) {
            return implode("\n", $this->adminNotes);
        }

        return '';
    }

    /**
     * Check if the current link is an internal link.
     *
     * @return bool
     */
    protected function checkLinkIsInternal()
    {
        $container = Factory::getPimpleContainer();

        return $container->router->isInternalURL($this->link)
            || in_array(
                $this->type,
                array(
                    'separator',
                    'heading'
                )
            );
    }

    /**
     * Set the correct modification date.
     *
     * @return void
     */
    public function setModificationDate()
    {
        if (General::isEmptyDate($this->modified)) {
            $this->modified = null;
        }

        if (!General::isEmptyDate($this->modified)) {
            if (!is_numeric($this->modified)) {
                $date           = new Date($this->modified);
                $this->modified = $date->toUnix();
            }

            // Convert dates from UTC
            if (intval($this->modified)) {
                if ($this->modified < 0) {
                    $this->modified = null;
                } else {
                    $date           = new \JDate($this->modified);
                    $this->modified = $date->toISO8601();
                }
            }
        }
    }

    /**
     * Check if the item's language has compatible language with
     * the current language.
     *
     * @return bool
     */
    public function hasCompatibleLanguage()
    {
        // Check the language
        if (Multilanguage::isEnabled() && isset($this->language)) {
            if ($this->language === '*' || $this->language === Factory::getLanguage()->getTag()) {
                return true;
            }

            return false;
        }

        return true;
    }
}
