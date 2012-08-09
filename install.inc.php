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

	if (!function_exists("is__writable")) {
	function is__writable($path)
	{
		if ($path{strlen($path)-1}=='/') // recursively return a temporary file path
			return is__writable($path.uniqid(mt_rand()).'.tmp');
		else if (is_dir($path))
			return is__writable($path.'/'.uniqid(mt_rand()).'.tmp');
		// check tmp file for read/write capabilities
		$rm = file_exists($path);
		$f = @fopen($path, 'a');
		if ($f===false)
			return false;
		fclose($f);
		if (!$rm)
			unlink($path);
		return true;
	}
	} // End function_exists
	
	unset($rxa_greybox);
	include('config.inc.php');

	if (!isset($rxa_greybox['name'])) {
		echo '<font color="#cc0000"><strong>Fehler! Eventuell wurde die Datei config.inc.php nicht gefunden!</strong></font>';
		$REX['ADDON']['install'][$rxa_greybox['name']] = 0;
		return;
	}

	// Gültige REDAXO-Version abfragen
	if (!in_array($rxa_greybox['rexversion'], array('3.11', '32', '40', '41', '42', '43'))) {
		echo '<font color="#cc0000"><strong>Fehler! Ung&uuml;ltige REDAXO-Version - '.$rxa_greybox['rexversion'].'</strong></font>';
		$REX['ADDON']['installmsg'][$rxa_greybox['name']] = '<br /><br /><font color="#cc0000"><strong>Fehler! Ung&uuml;ltige REDAXO-Version - '.$rxa_greybox['rexversion'].'</strong></font>';
		$REX['ADDON']['install'][$rxa_greybox['name']] = 0;
		return;
	}

	// Schreibrechte für ini-Datei setzen
	@chmod($rxa_greybox['basedir'] . '/'. $rxa_greybox['name'] . '.ini', 0755);

	// Verzeichnis files/greybox anlegen
	if (!@is_dir($rxa_greybox['filesdir'])) {
		if ( !@mkdir($rxa_greybox['filesdir']) ) {
			$rxa_greybox['meldung'] .= $rxa_greybox['i18n']->msg('error_createdir', $rxa_greybox['filesdir']);
		}
	}
	@chmod($rxa_greybox['filesdir'], 0755);
	if (!is__writable($rxa_greybox['filesdir'].'/')) {
		$rxa_greybox['meldung'] .= $rxa_greybox['i18n']->msg('error_writedir', $rxa_greybox['filesdir']);
	}

	// Dateien ins Verzeichnis files/greybox kopieren
	if ($dh = opendir($rxa_greybox['sourcedir'])) {
		while ($el = readdir($dh)) {
			$rxa_greybox['file'] = $rxa_greybox['sourcedir'].'/'.$el;
			if ($el != '.' && $el != '..' && is_file($rxa_greybox['file'])) {
				if ( !@copy($rxa_greybox['file'], $rxa_greybox['filesdir'].'/'.$el) ) {
					$rxa_greybox['meldung'] .= $rxa_greybox['i18n']->msg('error_copyfile', $el, $rxa_greybox['filesdir'].'/');
				}
			}
		}
	} else {
		$rxa_greybox['meldung'] .= $rxa_greybox['i18n']->msg('error_readdir',$rxa_greybox['sourcedir']);
	}
	
	// Evtl Ausgabe einer Meldung
	// $rxa_greybox['meldung'] = 'Das Addon wurde nicht installiert, weil...';
	if ( $rxa_greybox['meldung']<>'' ) {
		$REX['ADDON']['installmsg'][$rxa_greybox['name']] = '<br /><br />'.$rxa_greybox['meldung'].'<br /><br />';
		$REX['ADDON']['install'][$rxa_greybox['name']] = 0;
	} else {
	// Installation erfolgreich
		$REX['ADDON']['install'][$rxa_greybox['name']] = 1;
	}
?>