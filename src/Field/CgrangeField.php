<?php
/**
 * @package		CGWebp system plugin
 * @author		ConseilGouz
 * @copyright	Copyright (C) 2025 ConseilGouz. All rights reserved.
 * license      https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * From DJ-WEBP version 1.0.0
 **/

namespace Conseilgouz\Plugin\System\Cgwebp\Field;

defined('JPATH_PLATFORM') or die;
use Joomla\CMS\Form\Field\RangeField;

class CgrangeField extends RangeField
{
    public $type = 'Cgrange';

    /**
     * Name of the layout being used to render the field
     *
     * @var    string
     * @since  3.7
     */
    protected $layout = 'cgrange';

    /* plugin's information */
    public $_ext = "plg";
    public $_type = "system";
    public $_name = "cgwebp";

    protected function getLayoutPaths()
    {
        $paths = parent::getLayoutPaths();
        $paths[] = dirname(__DIR__).'/../layouts';
        return $paths;

    }

}
