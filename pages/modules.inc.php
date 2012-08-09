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
<h2 class="rex-hl2"><?php echo $rxa_greybox['i18n']->msg('menu_modules'); ?></h2>
<div class="rex-addon-content">

<p>
<strong>Modul-Input:</strong><br />
<textarea cols="50" rows="12" style="width:80%;">
<?php 
	echo htmlspecialchars(file_get_contents($rxa_greybox['path'].'/modul-input.txt'));
?>
</textarea>
<br /><br />
<strong>Modul-Output:</strong><br />
<textarea cols="50" rows="12" style="width:80%;">
<?php 
	echo htmlspecialchars(file_get_contents($rxa_greybox['path'].'/modul-output.txt'));
?>
</textarea>
</p>

</div>
</div>

<?php echo $rxa_compat['backendsuffix']; ?>