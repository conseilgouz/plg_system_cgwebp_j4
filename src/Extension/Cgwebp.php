<?php
/**
 * @version		1.2.2
 * @package		CGWebp system plugin
 * @author		ConseilGouz
 * @copyright	Copyright (C) 2024 ConseilGouz. All rights reserved.
 * license      https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * From DJ-WEBP version 1.0.0
 **/

namespace Conseilgouz\Plugin\System\Cgwebp\Extension;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Session\Session;
use Joomla\Event\SubscriberInterface;
use Joomla\Filesystem\File;
use Joomla\Filesystem\Folder;
use Conseilgouz\Plugin\System\Cgwebp\Helper\CgwebpHelper;

final class Cgwebp extends CMSPlugin implements SubscriberInterface
{
    protected $_webps;
    protected $debugData;
    public static function getSubscribedEvents(): array
    {
        return [
            'onAfterRender'   => 'onAfterRender',
            'onAjaxCgwebp' => 'onAjaxCgwebp'
        ];
    }

    public function onAfterRender()
    {
        $app = Factory::getApplication();
        $user = $app->getIdentity();


        if ($app->getDocument()->getType() !== 'html' || !$app->isClient('site')) {
            return;
        }

        $gdInfo = gd_info();
        if(!isset($gdInfo['WebP Support']) or !$gdInfo['WebP Support']) {
            return;
        }

        if($this->menuItemIsExcluded()) {
            return;
        }

        if (!CgwebpHelper::browserSupportWebp()) {
            return;
        }

        $sHtml = $app->getBody();

        $filters = (array) $this->params->get('filters');

        $this->debugData = array();

        if(is_countable($filters) && count($filters)) {
            foreach ($filters as $filter) {
                if (is_string($filter)) {
                    $filter = json_decode($filter);
                    foreach ($filter as $onefilter) {
                        if ($onefilter->directory) {
                            $sHtml = $this->gowebp($sHtml, $onefilter);
                        }
                    }
                } else {
                    if ($filter->directory) {
                        $sHtml = $this->gowebp($sHtml, $filter);
                    }
                }
            }
        }
        if($this->params->get('debug')) {
            $sHtml .= '<pre>' . print_r($this->debugData, true) . '</pre>';
        }
        $app->setBody($sHtml);
    }
    private function gowebp($sHtml, $onefilter)
    {
        $extensions     = $onefilter->extensions;
        $quality        = $onefilter->quality;
        $stored_time    = $onefilter->stored_time;
        $excluded       = $onefilter->excluded;
        $excludedArr    = strlen($excluded) ? explode(';', $excluded) : array();

        $this->debugData[$onefilter->directory] = array();
        $debugTarget =  &$this->debugData[$onefilter->directory];

        if (substr($onefilter->directory, 0, 1) === '/') {
            $onefilter->directory = substr($onefilter->directory, 1);
        }

        if (substr($onefilter->directory, -1) === '/') {
            $onefilter->directory = substr($onefilter->directory, 0, -1);
        }

        if(count($extensions)) {
            $regexPath = str_replace("/", "\/", $onefilter->directory);
            $sHtml = preg_replace_callback(
                '/' . $regexPath . '\/.*?(' . implode('|', $extensions) . ')(?=[\'"?#])|#joomlaImage.*?(' . implode('|', $extensions) . ').+?(?=\")\b/',
                function ($match) use ($quality, $stored_time, $excludedArr, &$debugTarget, $regexPath) {
                    $img = $match[0];
                    $newImg = $this->imgToWebp($img, $quality, $excludedArr, $stored_time, $regexPath, $match);
                    $debugTarget[] = array(
                        'source' => $img,
                        'target' => $newImg
                    );

                    return $newImg ? $newImg : $img;
                },
                $sHtml
            );
        }
        return $sHtml;
    }
    private function menuItemIsExcluded()
    {
        $app  = Factory::getApplication();


        $excludedMenuItems = $this->params->get('excludedMenus', array(), 'array');
        $activeMenu = $app->getMenu()->getActive();

        if(isset($activeMenu->id)) {
            return in_array($activeMenu->id, $excludedMenuItems);
        } else {
            return false;
        }
    }

    private function isExcludedDirectory($image, $excluded)
    {
        $exist = false;
        foreach ($excluded as $exclude) {

            if(strpos($image, $exclude) !== false) {
                $exist = true;
                break;
            }
        }
        return $exist;
    }

    private function imgToWebp($image, $quality = 100, $excluded = array(), $stored_time = 5, $regexPath = '', $fullRegex = '')
    {
        $imgPath = JPATH_ROOT . '/' . $image;
        $imgInfo = pathinfo($imgPath);
        $imgHash = md5($imgPath);

        if(!isset($imgInfo['extension']) || !$imgInfo['extension']) {
            return;
        }

        if(count($excluded)) {
            if(in_array($image, $excluded) || $this->isExcludedDirectory($image, $excluded)) {
                return;
            }
        }
        if (str_starts_with($image, '#')) { // media manager image part : ignore it
            return;
        }
        if(is_file($imgPath)) {
            if (!isset($this->_webps[$imgHash])) {
                if ($this->params->get('storage', 'same') == "same") { // same as original image
                    $newImagePath = $imgInfo['dirname'] . '/';
                } else { // in media/plg_system_webp/_cache directory
                    $newImagePath = JPATH_ROOT .'/media/plg_system_cgwebp/_cache/'.pathinfo($image)['dirname'].'/';
                }
                $newImage = $newImagePath . $imgInfo['filename'] . '.webp';

                // Delete webp image if is older than image
                if (is_file($newImage)) {
                    $imgCreated = filemtime($imgPath);
                    $fileCreated = filemtime($newImage);
                    if($fileCreated < $imgCreated) {
                        File::delete($newImage);
                    }
                }
                if (!is_file($newImage)) {
                    if (is_file($imgPath)) {
                        try {
                            switch (strtolower($imgInfo['extension'])) {
                                case 'png':
                                    $img = imagecreatefrompng($imgPath);
                                    break;
                                case 'jpg':
                                case 'jpeg':
                                    $img = imagecreatefromjpeg($imgPath);
                            }
                            if (!is_dir($newImagePath)) {
                                Folder::create($newImagePath);
                            }
                            imagepalettetotruecolor($img);
                            imagealphablending($img, true);
                            imagesavealpha($img, true);
                            imagewebp($img, $newImage, $quality);
                        } catch (\Throwable $throwable) {
                            return false; // conversion error :ignore image
                        }
                    }
                }
                $newFile = str_replace(JPATH_ROOT . '/', "", $newImage)."?ver=".$imgHash;
                $this->_webps[$imgHash] = $newFile;
            }
            return $this->_webps[$imgHash];
        }
    }
    public function onAjaxCgwebp()
    {
        if (!Session::checkToken()) {
            echo 'token error';
            return;
        }
        $user = Factory::getApplication()->getIdentity();
        if (!$user->authorise('core.edit', 'com_plugins')) {
            echo 'not authorized';
            return;
        }
        $input	= Factory::getApplication()->input;
        $task = $input->getString('task');
        if ($task != 'clean') {
            echo 'not clean';
            return;
        }
        if (is_dir(JPATH_ROOT . '/media/plg_system_cgwebp/_cache')) {
            Folder::delete(JPATH_ROOT . '/media/plg_system_cgwebp/_cache');
        }
        echo 'ok';
    }
}
