<?php
/**
 * @version		1.1.0
 * @package		CGWebp system plugin
 * @author		ConseilGouz
 * @copyright	Copyright (C) 2024 ConseilGouz. All rights reserved.
 * @license		GNU/GPL v3; see LICENSE.php
 * From DJ-WEBP version 1.0.0
 **/

defined('_JEXEC') or die();

use Joomla\CMS\Language\Text;

?>
  <button class="btn btn-primary" type="button" id="webpdestroy" >
    <?php echo Text::_('PLG_SYSTEM_CGWEBP_DESTROY'); ?>
  </button>
  <div id="destroy_message" aria-live="polite"></div>
