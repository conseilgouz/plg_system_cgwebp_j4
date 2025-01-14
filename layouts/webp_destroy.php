<?php
/**
 * @version	    1.1.0
 * @package	    CGWebp system plugin
 * @author	    ConseilGouz
 * @copyright   Copyright (C) 2025 ConseilGouz. All rights reserved.
 * license      https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * From DJ-WEBP version 1.0.0
 **/

defined('_JEXEC') or die();

use Joomla\CMS\Language\Text;

?>
  <button class="btn btn-primary" type="button" id="webpdestroy" style="float:right;width:40%">
    <?php echo Text::_('PLG_SYSTEM_CGWEBP_DESTROY'); ?>
  </button>
  <div id="destroy_message" aria-live="polite"></div>
