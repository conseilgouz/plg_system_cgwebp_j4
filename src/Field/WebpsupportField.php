<?php
/**
 * @version		1.0.0
 * @package		CGWebp system plugin
 * @author		ConseilGouz
 * @copyright	Copyright (C) 2025 ConseilGouz. All rights reserved.
 * license      https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * From DJ-WEBP version 1.0.0
 **/

namespace Conseilgouz\Plugin\System\Cgwebp\Field;

defined('_JEXEC') or die();

use Joomla\Registry\Registry;
use Joomla\CMS\Form\FormField;
use Joomla\CMS\Layout\FileLayout;

class WebpsupportField extends FormField
{
    protected $type = 'webpsupport';

    public function renderField($options = array())
    {
        $layout = new FileLayout('webp_support', JPATH_ROOT . '/plugins/system/cgwebp/layouts');
        return $layout->render();
    }
}
