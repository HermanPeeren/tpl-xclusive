<?php

/**
 * @package     Xclusive.Template
 * @subpackage  mod_menu
 *
 * Menu markup of xclusive-joostrap (Bootstrap 3 navbar): "nav menu" list, in the
 * position "menu" also "navbar-nav" with "dropdown" items and "dropdown-menu" sublists.
 * Based on the Joomla 6 core layout.
 *
 * @copyright   (C) 2009 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;

// For modules in the menu navigation add the Bootstrap 3 class for navbars
if ($module->position === 'menu') {
    $class_sfx .= ' navbar-nav';
}

$isNavbarNav = stripos($class_sfx, 'navbar-nav') !== false;
$id          = $params->get('tag_id') ? ' id="' . htmlspecialchars($params->get('tag_id'), ENT_QUOTES, 'UTF-8') . '"' : '';
?>
<ul class="nav menu<?php echo $class_sfx; ?>"<?php echo $id; ?>>
<?php foreach ($list as $i => &$item) {
    $itemParams = $item->getParams();
    $class      = 'item-' . $item->id;

    if ($item->id == $active_id || ($item->type === 'alias' && $itemParams->get('aliasoptions') == $active_id)) {
        $class .= ' current';
    }

    if (in_array($item->id, $path)) {
        $class .= ' active';
    } elseif ($item->type === 'alias') {
        $aliasToId = $itemParams->get('aliasoptions');

        if (count($path) > 0 && $aliasToId == $path[count($path) - 1]) {
            $class .= ' active';
        } elseif (in_array($aliasToId, $path)) {
            $class .= ' alias-parent-active';
        }
    }

    if ($item->type === 'separator') {
        $class .= ' divider';
    }

    if ($item->deeper) {
        $class .= ' deeper';
    }

    // Parent items in the navbar get a caret (see default_component.php and default_url.php)
    $item->xclusiveCaret = false;

    if ($item->parent) {
        $class .= ' parent';

        if ($isNavbarNav) {
            $class .= ' dropdown';
            $item->xclusiveCaret = true;

            if ($item->level > 1) {
                $class .= ' dropdown-submenu';
            }
        }
    }

    echo '<li class="' . $class . '">';

    switch ($item->type) :
        case 'separator':
        case 'component':
        case 'heading':
        case 'url':
            require ModuleHelper::getLayoutPath('mod_menu', 'default_' . $item->type);
            break;

        default:
            require ModuleHelper::getLayoutPath('mod_menu', 'default_url');
            break;
    endswitch;

    if ($item->deeper) {
        // The next item is deeper
        echo '<ul class="nav-child unstyled small' . ($isNavbarNav ? ' dropdown-menu' : '') . '">';
    } elseif ($item->shallower) {
        // The next item is shallower
        echo '</li>';
        echo str_repeat('</ul></li>', $item->level_diff);
    } else {
        // The next item is on the same level
        echo '</li>';
    }
}
?></ul>
