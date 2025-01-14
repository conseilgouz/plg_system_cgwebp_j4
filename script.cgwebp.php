<?php
/**
 * @package		CGWebp system plugin
 * @author		ConseilGouz
 * @copyright	Copyright (C) 2025 ConseilGouz. All rights reserved.
 * license      https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * From DJ-WEBP version 1.0.0
 **/
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Factory;

class plgSystemcgwebpInstallerScript
{
    public function postflight($type, $parent)
    {
        if (($type != 'install') && ($type != 'update')) {
            return true;
        }
        $db = Factory::getDbo();

        $query = $db->getQuery(true);
        $query->select(array(
            'e.manifest_cache',
            'e.params',
            'e.extension_id'
        ));
        $query->from('#__extensions AS e');
        $query->where('e.element = ' . $db->quote('cgwebp'));
        $query->where('e.type = ' . $db->quote('plugin'));
        $query->where('e.folder = ' . $db->quote('system'));
        $db->setQuery($query);

        $schema = $db->loadObject();
        $params = json_decode($schema->params);
        $newFilters = array();
        if (!($params->filters)) { // set default parameters
            $newFilters['__field0'] = array(
                    'directory' => 'images',
                    'extensions' => ["jpg|jpeg|JPG|JPEG","png|PNG"],
                    'quality' => 60,
                    'stored_time' => 0,
                    'excluded' => ""
            );
            $params->filters = json_encode($newFilters);
            $paramsJson = json_encode($params);
            $db->setQuery('UPDATE #__extensions SET params = ' . $db->quote($paramsJson) . ' WHERE extension_id = ' . $schema->extension_id);
            if (!$db->execute()) {
                $app = Factory::getApplication();
                $app->enqueueMessage('CG Webp : Update params error', 'error');
                return false;
           
           }
        }
        // wrong extension name
        $query = $db->getQuery(true)
               ->delete('#__update_sites')
              ->where($db->quoteName('location') . ' like '.$db->quote('%plg_system_cgwebp_update.xml%'))
              ->where($db->quoteName('name'). '='. $db->quote('cgchglog'));
        $db->setQuery($query);
        $result = $db->execute();

        // Enable plugin
        $query = $db->getQuery(true);
        $db->setQuery('UPDATE #__extensions SET enabled = 1 WHERE extension_id = ' . $schema->extension_id);
        if (!$db->execute()) {
            Factory::getApplication()->enqueueMessage('unable to enable CG Webp', 'error');
            return false;
        }
        return true;
    }
}
