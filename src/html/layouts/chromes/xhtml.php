<?php

/**
 * @package     Xclusive.Template
 * @subpackage  Layout
 *
 * Module chrome "xhtml" as in Joomla 3 (removed from the core in Joomla 4), used by the
 * position "top-article".
 *
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$module = $displayData['module'];
$params = $displayData['params'];

if ($module->content === null || $module->content === '') {
    return;
}

$moduleTag   = htmlspecialchars($params->get('module_tag', 'div'), ENT_QUOTES, 'UTF-8');
$headerTag   = htmlspecialchars($params->get('header_tag', 'h3'), ENT_QUOTES, 'UTF-8');
$headerClass = htmlspecialchars($params->get('header_class', ''), ENT_QUOTES, 'UTF-8');
$sfx         = htmlspecialchars($params->get('moduleclass_sfx', ''), ENT_QUOTES, 'UTF-8');
?>
<<?php echo $moduleTag; ?> class="moduletable<?php echo $sfx; ?>">
    <?php if ($module->showtitle) : ?>
        <<?php echo $headerTag; ?><?php echo $headerClass !== '' ? ' class="' . $headerClass . '"' : ''; ?>><?php echo $module->title; ?></<?php echo $headerTag; ?>>
    <?php endif; ?>
    <?php echo $module->content; ?>
</<?php echo $moduleTag; ?>>
