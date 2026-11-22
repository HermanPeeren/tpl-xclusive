<?php

/**
 * @package     Xclusive.Template
 * @subpackage  mod_custom
 *
 * Joomla 6 core layout, plus the module class suffix on the "custom" div as in
 * Joomla 3 (the site CSS uses e.g. ".custom.headermodule"; with style="none" Joomla 6
 * would drop the suffix).
 *
 * @copyright   (C) 2009 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Uri\Uri;

$modId = 'mod-custom' . $module->id;

if ($params->get('backgroundimage')) {
    /** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
    $wa = Factory::getApplication()->getDocument()->getWebAssetManager();
    $wa->addInlineStyle('
#' . $modId . '{background-image: url("' . Uri::root(true) . '/' . HTMLHelper::_('cleanImageURL', $params->get('backgroundimage'))->url . '");}
', ['name' => $modId]);
}

$sfx = htmlspecialchars($params->get('moduleclass_sfx', ''), ENT_QUOTES, 'UTF-8');
?>

<div id="<?php echo $modId; ?>" class="mod-custom custom<?php echo $sfx; ?>">
    <?php echo $module->content; ?>
</div>
