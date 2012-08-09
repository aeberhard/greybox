<?php
/**
 * --------------------------------------------------------------------
 *
 * Redaxo Addon: Greybox
 * Version: 1.8.1, 04.02.2010
 *
 * Autor: Andreas Eberhard, andreas.eberhard@gmail.com
 *        http://rex.andreaseberhard.de
 *
 * Verwendet wird das Script von Amir Salihefendic
 * http://orangoo.com/labs/GreyBox/
 *
 * --------------------------------------------------------------------
 */
?>

<?php echo $rxa_compat['backendprefix']; ?>

<div class="rex-addon-output">
<h2 class="rex-hl2"><?php echo $rxa_greybox['i18n']->msg('menu_information'); ?></h2>
<div class="rex-addon-content">

<?php
	include_once ($rxa_greybox['path'].'/help.inc.php');
?>

</div>
</div>

<?php echo $rxa_compat['backendsuffix']; ?>