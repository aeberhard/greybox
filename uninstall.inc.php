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

	unset($rxa_greybox); 
	include('config.inc.php');
	
	if (!isset($rxa_greybox['name'])) {
		echo '<font color="#cc0000"><strong>Fehler! Eventuell wurde die Datei config.inc.php nicht gefunden!</strong></font>';
		return;
	}

	// Dateien aus dem Ordner files/greybox löschen
	if ( !in_array($rxa_greybox['rexversion'], array('42', '43')) ) {
		if (isset($rxa_greybox['filesdir']) and ($rxa_greybox['filesdir']<>'') and ($rxa_greybox['name']<>'') ) {
			if ($dh = @opendir($rxa_greybox['filesdir'])) {
				while ($el = readdir($dh)) {
					$path = $rxa_greybox['filesdir'].'/'.$el;
					if ($el != '.' && $el != '..' && is_file($path)) {
						@unlink($path);
					}
				}
			}
		}
		@closedir($dh);
		@rmdir($rxa_greybox['filesdir']);	
	}
	
	// Evtl Ausgabe einer Meldung
	// De-Installation nicht erfolgreich
	if ( $rxa_greybox['meldung']<>'' ) {
		$REX['ADDON']['installmsg'][$rxa_greybox['name']] = '<br /><br />'.$rxa_greybox['meldung'].'<br /><br />';
		$REX['ADDON']['install'][$rxa_greybox['name']] = 1;
	// De-Installation erfolgreich
	} else {
		$REX['ADDON']['install'][$rxa_greybox['name']] = 0;
	}
?>