<?php

/**
 * The error page (404 and others) in the layout of the site: index.php, with the error message
 * (error-content.php) in the place of the component.
 *
 * @copyright   (C) 2026 Herman Peeren
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/** @var \Joomla\CMS\Document\ErrorDocument $this */

// Joomla's schema.org plugin adds the data of the requested item (the id in the request) to the
// head. When that item doesn't exist, which is why this page is shown, it throws again while the
// head is rendered, and Joomla falls back to its bare "Whoops" page. Without an id it adds nothing.
\Joomla\CMS\Factory::getApplication()->getInput()->set('id', 0);

require __DIR__ . '/index.php';
