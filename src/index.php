<?php

/**
 * @package     Xclusive.Template
 * @subpackage  Templates.xclusive
 *
 * Child template of Cassiopeia. Same HTML structure and module positions as the
 * Joomla 3 template xclusive-joostrap (Joostrap "Portfolio", (C) 2005 - 2014 Joostrap).
 *
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

/** @var Joomla\CMS\Document\HtmlDocument $this */

$app    = Factory::getApplication();
$input  = $app->getInput();
$wa     = $this->getWebAssetManager();
$params = $this->params;

$view     = $input->getCmd('view', '');
$itemid   = $input->getCmd('Itemid', '');
$sitename = htmlspecialchars($app->get('sitename'), ENT_QUOTES, 'UTF-8');

// Frontpage check, as in xclusive-joostrap
$menu        = $app->getMenu();
$active      = $menu->getActive();
$default     = Multilanguage::isEnabled() ? $menu->getDefault($app->getLanguage()->getTag()) : $menu->getDefault();
$isFrontpage = $active !== null && $default !== null && (int) $active->id === (int) $default->id;

// Body classes, as in xclusive-joostrap
$pageclass = $active !== null ? $active->getParams()->get('pageclass_sfx', '') : '';
$parentId  = $active !== null && !empty($active->tree) ? $active->tree[0] : '';
$bodyClass = implode(' ', array_filter([
    $params->get('bodyclass', ''),
    $pageclass,
    'parentid-' . $parentId,
    $active !== null ? $active->alias : '',
    'view-' . $view,
    $isFrontpage ? 'frontpage' : '',
    'itemid-' . $itemid,
    $app->getIdentity()->guest ? 'loggedout' : 'loggedin',
    $this->direction === 'rtl' ? 'rtl' : 'no-rtl',
]));

// Column widths of the main area (Bootstrap grid, 12 columns)
$left  = (int) $params->get('sidebarLeftWidth', 4);
$right = (int) $params->get('sidebarRightWidth', 4);
$span  = 12;

if ($this->countModules('left')) {
    $span -= $left;
}

if ($this->countModules('right')) {
    $span -= $right;
}

$frontpageshow = (bool) $params->get('frontpageshow', 1);
$totop         = (bool) $params->get('toTop', 0);
$copytext      = $params->get('copyrightsText', '');

// Mobile menu slides in from: left, right, top or bottom
$slideMap   = ['left' => 'start', 'right' => 'end', 'top' => 'top', 'bottom' => 'bottom'];
$menuslide  = $slideMap[$params->get('menuslide', 'right')] ?? 'end';

// Logo file or site title
if ($params->get('logoFile')) {
    $logo = HTMLHelper::_('image', Uri::root(false) . htmlspecialchars($params->get('logoFile'), ENT_QUOTES), $sitename, ['loading' => 'eager', 'decoding' => 'async'], false, 0);
} elseif ($params->get('sitetitle')) {
    $logo = htmlspecialchars($params->get('sitetitle'), ENT_COMPAT, 'UTF-8');

    if ($params->get('sitedescription')) {
        $logo .= '<span class="site-description">' . htmlspecialchars($params->get('sitedescription'), ENT_COMPAT, 'UTF-8') . '</span>';
    }
} else {
    $logo = $sitename;
}

// Open Graph image on the frontpage
if ($isFrontpage && $params->get('ogImage')) {
    $ogImage = HTMLHelper::_('cleanImageURL', $params->get('ogImage'))->url;
    $this->setMetaData('og:image', Uri::root() . ltrim($ogImage, '/'), 'property');
}

// Favicon: the template option, or the Xclusive balloon of the old template
if ($params->get('templateFavicon')) {
    $favicon = HTMLHelper::_('cleanImageURL', $params->get('templateFavicon'))->url;
    $this->addHeadLink(Uri::root(true) . '/' . ltrim($favicon, '/'), 'icon');
} else {
    $this->addHeadLink(HTMLHelper::_('image', 'favicon.ico', '', [], true, 1), 'icon', 'rel', ['type' => 'image/vnd.microsoft.icon']);
}

// Google Tag Manager
$gtmId = preg_replace('/[^A-Z0-9-]/', '', strtoupper((string) $params->get('gtmId', '')));

if ($gtmId !== '') {
    $wa->addInlineScript(
        "(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});"
        . "var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;"
        . "j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);"
        . "})(window,document,'script','dataLayer','" . $gtmId . "');",
        ['name' => 'template.xclusive.gtm'],
        [],
        []
    );
}

// Assets: Cassiopeia's CSS (Bootstrap 5) as the base, then the ported theme
$wa->usePreset('template.cassiopeia.' . ($this->direction === 'rtl' ? 'rtl' : 'ltr'))
    ->useStyle('template.active.language')
    ->registerAndUseStyle('theme.colors_standard', 'global/colors_standard.css')
    ->usePreset('template.xclusive.fonts')
    ->useStyle('template.xclusive')
    ->useStyle('template.user')
    ->useScript('template.user')
    ->useScript('bootstrap.offcanvas')
    ->addInlineStyle(':root {
		--hue: 0;
		--template-bg-light: #fff;
		--template-text-dark: #555;
		--template-text-light: #fff;
		--template-link-color: #a17f1a;
		--template-special-color: #6c0000;
		--cassiopeia-color-primary: #6c0000;
		--cassiopeia-color-link: #a17f1a;
		--cassiopeia-color-hover: #cfb53b;
		--link-color: #a17f1a;
		--link-color-rgb: 161, 127, 26;
		--link-hover-color: #cfb53b;
		--link-hover-color-rgb: 207, 181, 59;
	}');

if ($totop) {
    $wa->useScript('template.xclusive');
}

// Override 'template.active' asset to set correct ltr/rtl dependency
$wa->registerStyle('template.active', '', [], [], ['template.cassiopeia.' . ($this->direction === 'rtl' ? 'rtl' : 'ltr')]);

$this->setMetaData('viewport', 'width=device-width, initial-scale=1.0');
?>
<!DOCTYPE html>
<html class="no-js" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
    <jdoc:include type="metas" />
    <jdoc:include type="styles" />
    <jdoc:include type="scripts" />
</head>
<body class="<?php echo htmlspecialchars($bodyClass, ENT_QUOTES, 'UTF-8'); ?>">
<?php if ($gtmId !== '') : ?>
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo $gtmId; ?>" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<?php endif; ?>
<div class="body-wrapper">
    <div class="navbar">
        <div class="navbar-header">
            <div class="container">
                <div class="basic-wrapper">
                    <div class="navbar-brand">
                        <a href="<?php echo $this->baseurl; ?>/" class="navbar-brand"><?php echo $logo; ?></a>
                    </div>
                </div>
                <a href="#sidebar" class="navbar-toggle-link" data-bs-toggle="offcanvas" data-bs-target="#sidebar" aria-controls="sidebar">
                    <span class="navbar-toggle visible-sm visible-xs">
                        <span class="icon-menu" aria-hidden="true"></span>
                        Menu
                    </span>
                </a>
                <nav class="collapse navbar-collapse pull-right" id="mainnav" aria-label="<?php echo Text::_('TPL_XCLUSIVE_MAIN_MENU'); ?>">
                    <?php if ($this->countModules('menu')) : ?>
                        <jdoc:include type="modules" name="menu" style="none" />
                    <?php endif; ?>
                </nav>
            </div>
        </div>
    </div>
    <?php if ($this->countModules('carousel')) : ?>
        <div class="fullscreenbanner-container revolution">
            <div class="fullscreenbanner revslider-initialised tp-simpleresponsive">
                <jdoc:include type="modules" name="carousel" style="standard" />
            </div>
        </div>
    <?php endif; ?>
    <?php if ($this->countModules('strapline')) : ?>
        <div class="strapline">
            <div class="strapline">
                <jdoc:include type="modules" name="strapline" style="standard" />
            </div>
        </div>
    <?php endif; ?>
    <?php if ($this->countModules('top')) : ?>
        <div class="light-wrapper">
            <div class="container inner">
                <jdoc:include type="modules" name="top" style="none" />
            </div>
        </div>
    <?php endif; ?>
    <div class="container inner">
        <div class="row">
            <?php if ($this->countModules('left')) : ?>
                <aside class="col-md-<?php echo $left; ?>">
                    <section class="sidebar left-sidebar">
                        <jdoc:include type="modules" name="left" style="standard" />
                    </section>
                </aside>
            <?php endif; ?>
            <?php if (!$isFrontpage || $frontpageshow) : ?>
                <main class="col-md-<?php echo $span; ?>">
                    <jdoc:include type="modules" name="top-article" style="xhtml" />
                    <jdoc:include type="message" />
                    <jdoc:include type="component" />
                </main>
            <?php endif; ?>
            <?php if ($this->countModules('right')) : ?>
                <aside class="col-md-<?php echo $right; ?>">
                    <section class="sidebar right-sidebar">
                        <jdoc:include type="modules" name="right" style="standard" />
                    </section>
                </aside>
            <?php endif; ?>
        </div>
    </div>
    <?php if ($this->countModules('middle')) : ?>
        <div class="black-wrapper inner">
            <div class="container">
                <jdoc:include type="modules" name="middle" style="none" />
            </div>
        </div>
    <?php endif; ?>
    <?php if ($this->countModules('bottom1')) : ?>
        <div class="light-wrapper">
            <div class="container inner">
                <jdoc:include type="modules" name="bottom1" style="none" />
            </div>
        </div>
    <?php endif; ?>
    <?php if ($this->countModules('bottom2')) : ?>
        <div class="black-wrapper">
            <div class="inner">
                <jdoc:include type="modules" name="bottom2" style="none" />
            </div>
        </div>
    <?php endif; ?>
    <?php if ($this->countModules('footer1')) : ?>
        <div class="light-wrapper">
            <div class="container inner">
                <jdoc:include type="modules" name="footer1" style="none" />
            </div>
        </div>
        <div class="clear"></div>
    <?php endif; ?>

    <?php if ($this->countModules('ondermenu1') || $this->countModules('ondermenu2') || $this->countModules('ondermenu3') || $this->countModules('ondermenu4')) : ?>
        <!-- footer menu -->
        <div class="dark-wrapper">
            <div class="container inner ondermenu">
                <div class="col-xs-6 col-md-3">
                    <jdoc:include type="modules" name="ondermenu1" style="none" />
                </div>
                <div class="col-xs-6 col-md-3">
                    <jdoc:include type="modules" name="ondermenu2" style="none" />
                </div>
                <div class="col-xs-6 col-md-3">
                    <jdoc:include type="modules" name="ondermenu3" style="none" />
                </div>
                <div class="col-xs-6 col-md-3">
                    <jdoc:include type="modules" name="ondermenu4" style="none" />
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="clear"></div>
    <?php if ($this->countModules('footer2') || $copytext) : ?>
        <footer class="footer black-wrapper">
            <?php if ($this->countModules('footer2')) : ?>
                <div class="container inner">
                    <jdoc:include type="modules" name="footer2" style="none" />
                </div>
            <?php endif; ?>
            <?php if ($copytext) : ?>
                <div class="container">
                    <?php echo $copytext; ?>
                </div>
            <?php endif; ?>
        </footer>
    <?php endif; ?>
    <?php if ($totop) : ?>
        <a href="#" class="go-top"><?php echo Text::_('TPL_XCLUSIVE_BACKTOTOP'); ?> <span class="fa fa-arrow-circle-up" aria-hidden="true"></span></a>
    <?php endif; ?>
</div>
<div class="offcanvas offcanvas-<?php echo $menuslide; ?> xcl-offcanvas" tabindex="-1" id="sidebar" aria-label="<?php echo Text::_('TPL_XCLUSIVE_MAIN_MENU'); ?>">
    <div class="offcanvas-header" id="panel-overview">
        <a href="<?php echo $this->baseurl; ?>/" class="icon-home" aria-label="<?php echo Text::_('TPL_XCLUSIVE_HOME'); ?>"></a>
        <button type="button" class="icon-times" data-bs-dismiss="offcanvas" aria-label="<?php echo Text::_('JCLOSE'); ?>"></button>
    </div>
    <div class="offcanvas-body">
        <?php if ($this->countModules('mob-menu-above')) : ?>
            <div class="mob-menu-above">
                <jdoc:include type="modules" name="mob-menu-above" style="standard" />
            </div>
        <?php endif; ?>
        <?php if ($this->countModules('menu')) : ?>
            <jdoc:include type="modules" name="menu" style="none" />
        <?php endif; ?>
        <?php if ($this->countModules('mob-menu-below')) : ?>
            <div class="mob-menu-below">
                <jdoc:include type="modules" name="mob-menu-below" style="standard" />
            </div>
        <?php endif; ?>
    </div>
</div>
<jdoc:include type="modules" name="debug" style="none" />
</body>
</html>
