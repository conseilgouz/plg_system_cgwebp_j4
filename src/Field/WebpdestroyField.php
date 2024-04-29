<?php
/**
 * @version		1.1.0
 * @package		CGWebp system plugin
 * @author		ConseilGouz
 * @copyright	Copyright (C) 2024 ConseilGouz. All rights reserved.
 * license      https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * From DJ-WEBP version 1.0.0
 **/

namespace Conseilgouz\Plugin\System\Cgwebp\Field;

defined('_JEXEC') or die();

use Joomla\Registry\Registry;
use Joomla\CMS\Form\FormField;
use Joomla\CMS\Layout\FileLayout;

class WebpdestroyField extends FormField
{
    protected $type = 'webpdestroy';

    public function renderField($options = array())
    {
        $layout = new FileLayout('webp_destroy', JPATH_ROOT . '/plugins/system/cgwebp/layouts');
        return $layout->render();
    }
}
