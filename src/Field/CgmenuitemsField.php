<?php
/**
 * @package		CGWebp system plugin
 * @author		ConseilGouz
 * @copyright	Copyright (C) 2025 ConseilGouz. All rights reserved.
 * license      https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * From DJ-WEBP version 1.0.0
 **/

namespace Conseilgouz\Plugin\System\Cgwebp\Field;

// Prevent direct access
defined('_JEXEC') || die;

use Joomla\CMS\Factory;
use Joomla\CMS\Access\Access;
use Joomla\CMS\Helper\UserGroupsHelper;
use Joomla\CMS\Form\Field\ListField;
use Joomla\Registry\Registry;

class CgmenuItemsField extends ListField
{
    protected $type = 'cgmenuitems';
    /**
     * Cached array of the category items.
     *
     * @var    array
     * @since  3.2
     */
    protected static $options = array();

    /**
     * Method to attach a Form object to the field.
     *
     * @param   \SimpleXMLElement  $element  The SimpleXMLElement object representing the `<field>` tag for the form field object.
     * @param   mixed              $value    The form field value to validate.
     * @param   string             $group    The field name group control value. This acts as an array container for the field.
     *                                       For example if the field has name="foo" and the group value is set to "bar" then the
     *                                       full field name would end up being "bar[foo]".
     *
     * @return  boolean  True on success.
     *
     * @since   1.7.0
     */
    public function setup(\SimpleXMLElement $element, $value, $group = null)
    {
        if (\is_string($value) && strpos($value, ',') !== false) {
            $value = explode(',', $value);
        }

        return parent::setup($element, $value, $group);
    }


    protected function getOptions()
    {
        $options        = parent::getOptions();


        $db = Factory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select('m.id AS value, m.title AS text')
            ->from($db->quoteName('#__menu', 'm'))
            ->where('m.menutype <> ' . $db->quote('main'))
            ->where('m.published = 1')
            ->where('m.id > 1');

        $db->setQuery($query);
        static::$options = $db->loadObjectList();
        return array_merge($options, static::$options);
    }
}
