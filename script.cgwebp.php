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
use Joomla\Database\DatabaseInterface;
use Joomla\Filesystem\File;
use Joomla\Filesystem\Folder;

class plgSystemcgwebpInstallerScript
{
    private $newlib_version	 = '';
    private $extname         = 'cgwebp';
    private $dir           = null;
    private $lang;

    public function __construct()
    {
        $this->dir = __DIR__;
        $this->lang = Factory::getApplication()->getLanguage();
        $this->lang->load($this->extname);
    }

    public function postflight($type, $parent)
    {
        if (($type != 'install') && ($type != 'update')) {
            return true;
        }
        if (!$this->checkLibrary('conseilgouz')) { // need library installation
            $ret = $this->installPackage('lib_conseilgouz');
            if ($ret) {
                Factory::getApplication()->enqueueMessage('ConseilGouz Library ' . $this->newlib_version . ' installed', 'notice');
            }
        }
        // delete obsolete version.php file
        $this->delete([
            JPATH_SITE . '/plugins/system/cgwebp/src/Field/VersionField.php',
            JPATH_SITE . '/plugins/system/cgwebp/src/Field/CgrangeField.php',
            JPATH_SITE . '/plugins/system//cgwebp/layouts/cgrange.php',
            JPATH_SITE . '/media/plg_system_cgwebp/css/cgrange.css',
            JPATH_SITE . '/media/plg_system_cgwebp/js/cgrange.js',
        ]);
        $db = Factory::getContainer()->get(DatabaseInterface::class);
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
    private function checkLibrary($library)
    {
        $file = $this->dir.'/lib_conseilgouz/conseilgouz.xml';
        if (!is_file($file)) {// library not installed
            return false;
        }
        $xml = simplexml_load_file($file);
        $this->newlib_version = $xml->version;
        $db = Factory::getContainer()->get(DatabaseInterface::class);
        $conditions = array(
             $db->qn('type') . ' = ' . $db->q('library'),
             $db->qn('element') . ' = ' . $db->quote($library)
            );
        $query = $db->getQuery(true)
                ->select('manifest_cache')
                ->from($db->quoteName('#__extensions'))
                ->where($conditions);
        $db->setQuery($query);
        $manif = $db->loadObject();
        if ($manif) {
            $manifest = json_decode($manif->manifest_cache);
            if ($manifest->version >= $this->newlib_version) { // compare versions
                return true; // library ok
            }
        }
        return false; // need library
    }
    private function installPackage($package)
    {
        $tmpInstaller = new Joomla\CMS\Installer\Installer();
        $db = Factory::getContainer()->get(DatabaseInterface::class);
        $tmpInstaller->setDatabase($db);
        $installed = $tmpInstaller->install($this->dir . '/' . $package);
        return $installed;
    }
    public function delete($files = [])
    {
        foreach ($files as $file) {
            if (is_dir($file)) {
                Folder::delete($file);
            }

            if (is_file($file)) {
                File::delete($file);
            }
        }
    }

}
