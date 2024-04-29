<?php
/**
 * @version		1.2.0
 * @package		CGWebp system plugin
 * @author		ConseilGouz
 * @copyright	Copyright (C) 2024 ConseilGouz. All rights reserved.
 * license      https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * From DJ-WEBP version 1.0.0
 **/

namespace ConseilGouz\Plugin\System\Cgwebp\Helper;

// Prevent direct access
defined('_JEXEC') or die;

class CgwebpHelper {

    public static function browserSupportWebp() {
  
        $user_agent = $_SERVER['HTTP_USER_AGENT'];

        if(!isset($_SERVER['HTTP_USER_AGENT'])) return false;


        // If browser is Internet Explorer
        if (isset($_SERVER['HTTP_USER_AGENT']) &&
            (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)) return false;

        // If user browser is safari and is not Opera
        if(
            strpos($user_agent, 'Safari') &&
            !strpos( $_SERVER['HTTP_USER_AGENT'], ' Chrome/' ) &&
            !(strpos($user_agent, 'Edge'))
        ) return false;

        // If windows mobile
        if(strpos($_SERVER['HTTP_USER_AGENT'], 'Windows Phone 8.1')) return false;


        // If browser doesnt support webp and is not chrome/firefox/opera/edge
        if(
            !strpos( $_SERVER['HTTP_ACCEPT'], 'image/webp' ) &&
            !strpos( $_SERVER['HTTP_USER_AGENT'], ' Chrome/' ) &&
            !strpos($user_agent, 'Firefox') &&
            !(strpos($user_agent, 'Opera') || strpos($user_agent, 'OPR/')) &&
            !(strpos($user_agent, 'Edge'))
        ) return false;

        return true;
    }

}
