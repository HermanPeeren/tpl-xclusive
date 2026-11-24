<?php

/**
 * The message of the error page, in the place of the component (see error.php).
 *
 * @copyright   (C) 2026 Herman Peeren
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/** @var \Joomla\CMS\Document\ErrorDocument $this */

$errorCode = (int) $this->error->getCode();
$home      = $this->baseurl . '/';
?>
<div class="item-page error-page error-<?php echo $errorCode; ?>">
    <?php if ($errorCode === 404) : ?>
        <h1>Deze pagina bestaat niet (meer)</h1>
        <p>De pagina die u zocht is er niet. Misschien is ze verhuisd, of zit er een tikfout in het adres.</p>
    <?php else : ?>
        <h1>Er ging iets mis</h1>
        <p>Deze pagina kan op dit moment niet worden getoond (foutcode <?php echo $errorCode; ?>). Probeer het later nog eens.</p>
    <?php endif; ?>
    <p>Kijk in het menu, in de <a href="<?php echo $home; ?>sitemap">sitemap</a> of ga naar de <a href="<?php echo $home; ?>">startpagina</a>.</p>
    <?php if ($this->debug) : ?>
        <p><span class="badge bg-secondary"><?php echo $errorCode; ?></span> <?php echo htmlspecialchars($this->error->getMessage(), ENT_QUOTES, 'UTF-8'); ?></p>
        <?php echo $this->renderBacktrace(); ?>
    <?php endif; ?>
</div>
