<?php

/**
 * @package     Xclusive.Template
 * @subpackage  Layout
 *
 * Module chrome "standard" of xclusive-joostrap (was modChrome_standard in html/modules.php).
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
$modClassSfx = $params->get('moduleclass_sfx') ? ' ' . trim($params->get('moduleclass_sfx')) : '';
?>
<<?php echo $moduleTag; ?> class="module<?php echo htmlspecialchars($modClassSfx, ENT_QUOTES, 'UTF-8'); ?>">
    <?php if ($module->showtitle) : ?>
        <div class="headline">
            <h4><?php echo $module->title; ?></h4>
        </div>
    <?php endif; ?>
    <div class="module-content">
        <?php echo $module->content; ?>
    </div>
</<?php echo $moduleTag; ?>>
