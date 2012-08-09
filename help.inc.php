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

	include('config.inc.php');
	if (!isset($rxa_greybox['name'])) {
		echo '<font color="#cc0000"><strong>Fehler! Eventuell wurde die Datei config.inc.php nicht gefunden!</strong></font>';
		return;
	}
		
	echo $rxa_greybox['i18n']->msg('text_help_title', $REX['ADDON']['version'][$rxa_greybox['name']]);
	$i=1;
	while ($rxa_greybox['i18n']->msg('text_help_'.$i)<>'[translate:text_help_'.$i.']') {
		echo $rxa_greybox['i18n']->msg('text_help_'.$i);
		$i++;
		if ($i>10) { break; }
	}
?>
