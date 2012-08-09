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

	// Include Header and Navigation
	include $REX['INCLUDE_PATH'].'/layout/top.php';

	// Für REDAXO < 4.2
	if (isset($REX_USER))
	{
		$REX['USER'] = $REX_USER;
	}	

	// Addon-Subnavigation
	$subpages = array(
		array('',$rxa_greybox['i18n']->msg('menu_settings')),
		array('info',$rxa_greybox['i18n']->msg('menu_information')),
		array('log',$rxa_greybox['i18n']->msg('menu_changelog')),
		array('mod',$rxa_greybox['i18n']->msg('menu_modules')),
	);

	// Titel
	if ( in_array($rxa_greybox['rexversion'], array('3.11')) ) {
		title($rxa_greybox['i18n']->msg('title'), $subpages);
	} else {
		rex_title($rxa_greybox['i18n']->msg('title'), $subpages);
	}

	// Include der angeforderten Seite
	if (isset($_GET['subpage'])) {
		$subpage = $_GET['subpage'];
	} else {
		$subpage = '';
	}
	switch($subpage) {
		case 'info':
			include ($rxa_greybox['path'] .'/pages/help.inc.php');
		break;
		case 'log':
			include ($rxa_greybox['path'] .'/pages/changelog.inc.php');
		break;
		case 'mod':
			include ($rxa_greybox['path'] .'/pages/modules.inc.php');
		break;
		default:
			include ($rxa_greybox['path'] .'/pages/default_page.inc.php');
		break;
	}
 
	// Include Footer
	include $REX['INCLUDE_PATH'].'/layout/bottom.php';
?>