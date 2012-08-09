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
<h2 class="rex-hl2"><?php echo $rxa_greybox['i18n']->msg('menu_changelog'); ?></h2>
<div class="rex-addon-content">

<p>
<?php
	if (strstr($REX['LANG'],'utf8'))
	{
		echo utf8_encode(nl2br(htmlspecialchars(file_get_contents($rxa_greybox['path'].'/changelog.txt'))));
	}
	else
	{
		echo nl2br(htmlspecialchars(file_get_contents($rxa_greybox['path'].'/changelog.txt')));
	}
?>
</p>

</div>
</div>

<?php echo $rxa_compat['backendsuffix']; ?>