<?php
/**
 * @package		CGWebp system plugin
 * @author		ConseilGouz
 * @copyright	Copyright (C) 2025 ConseilGouz. All rights reserved.
 * license      https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * From DJ-WEBP version 1.0.0
 **/

namespace ConseilGouz\Plugin\System\Cgwebp\Helper;

// Prevent direct access
defined('_JEXEC') or die;

class CgwebpHelper {

    public static function browserSupportWebp() {
  
        if(!isset($_SERVER['HTTP_USER_AGENT'])) return false;

        $user_agent = $_SERVER['HTTP_USER_AGENT'];

        // If browser is Internet Explorer
        if (strpos($user_agent, 'MSIE') !== false) return false;

        // If user browser is safari and is not Opera
        if(
            strpos($user_agent, 'Safari') &&
            !strpos($user_agent, ' Chrome/' ) &&
            !(strpos($user_agent, 'Edge'))
        ) return false;

        // If windows mobile
        if(strpos($user_agent, 'Windows Phone 8.1')) return false;

        // If browser doesnt support webp
        if (isset($_SERVER['HTTP_ACCEPT'])) {
            if (!strpos($_SERVER['HTTP_ACCEPT'], 'image/webp' )) {
                return false;
            }
        }

        // If browser doesnt support webp and is not chrome/firefox/opera/edge
        if(
            !strpos($user_agent, ' Chrome/' ) &&
            !strpos($user_agent, 'Firefox') &&
            !(strpos($user_agent, 'Opera') || strpos($user_agent, 'OPR/')) &&
            !(strpos($user_agent, 'Edge'))
        ) return false;

        return true;
    }

}
