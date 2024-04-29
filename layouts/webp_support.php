<?php
/**
 * @version		1.0.0
 * @package		CGWebp system plugin
 * @author		ConseilGouz
 * @copyright	Copyright (C) 2024 ConseilGouz. All rights reserved.
 * license      https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * From DJ-WEBP version 1.0.0
 **/

defined('_JEXEC') or die();

use Joomla\CMS\Language\Text;
use Conseilgouz\Plugin\System\Cgwebp\Helper\CgwebpHelper;

$gdInfo = gd_info();
$serverSupportWebp = (isset($gdInfo['WebP Support']) && $gdInfo['WebP Support']) ? true : false;
$browserSupportWebp = (!(strpos($_SERVER['HTTP_ACCEPT'], 'image/webp')) || strpos($_SERVER['HTTP_USER_AGENT'], ' Chrome/') !== false) ? true : false;

?>
  <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#webpSupportContainer" aria-expanded="false" aria-controls="webpSupportContainer" >
    Check WebP support
  </button>
<div id="webpSupportContainer" class="webpSupportContainer collapse">
 
    <h3><?php echo Text::_('PLG_SYSTEM_CGWEBP_SUPPORT_TABLE') ?></h3>
    <table>
        <thead>
        <th><?php echo Text::_('PLG_SYSTEM_CGWEBP_SUPPORT_ENDPOINT'); ?></th>
        <th><?php echo Text::_('PLG_SYSTEM_CGWEBP_SUPPORT_SUPPORTED'); ?></th>
        </thead>
        <tbody>
        <tr>
            <td><?php echo Text::_('PLG_SYSTEM_CGWEBP_SUPPORT_SERVER'); ?></td>
            <td>

                <?php if ($serverSupportWebp) : ?>
                    <span class="icon-publish">&nbsp;</span>
                <?php else : ?>
                    <span class="icon-unpublish">&nbsp;</span></td>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td><?php echo Text::_('PLG_SYSTEM_CGWEBP_SUPPORT_BROWSER'); ?></td>
            <td>
                <?php if (CgwebpHelper::browserSupportWebp()) : ?>
                    <span class="icon-publish">&nbsp;</span>
                <?php else : ?>
                <span class="icon-unpublish">&nbsp;</span></td>
            <?php endif; ?>
            </td>
        </tr>
        </tbody>
    </table>

</div>
<div style="clear: both"></div>
<style>
    .webpSupportContainer table {
        width: 200px;
        text-align: center;
    }

    .webpSupportContainer table th {
        width: 50%;
    }

    .webpSupportContainer table td,.webpSupportContainer table th {
        border: 1px solid black;
        width: 50%;
    }

    .webpSupportContainer {
        margin-bottom: 20px;
    }

</style>