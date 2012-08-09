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

	// Name des Addons und Pfade
	unset($rxa_greybox);
	$rxa_greybox['name'] = 'greybox';

	$rxa_greybox['rexversion'] = isset($REX['VERSION']) ? $REX['VERSION'] . $REX['SUBVERSION'] : $REX['version'] . $REX['subversion'];
	
	$REX['ADDON']['version'][$rxa_greybox['name']] = '1.8.1';
	$REX['ADDON']['author'][$rxa_greybox['name']] = 'Andreas Eberhard';

	$rxa_greybox['path'] = $REX['INCLUDE_PATH'].'/addons/'.$rxa_greybox['name'];
	$rxa_greybox['basedir'] = dirname(__FILE__);
	$rxa_greybox['lang_path'] = $REX['INCLUDE_PATH']. '/addons/'. $rxa_greybox['name'] .'/lang';
	$rxa_greybox['sourcedir'] = $REX['INCLUDE_PATH']. '/addons/'. $rxa_greybox['name'] .'/'. $rxa_greybox['name'];
	$rxa_greybox['meldung'] = '';

	$rxa_greybox['filesdir'] = $REX['HTDOCS_PATH'].'files/'.$rxa_greybox['name'];
	if ( in_array($rxa_greybox['rexversion'], array('42', '43')) ) {
		$rxa_greybox['filesdir'] = $REX['HTDOCS_PATH'].'files/addons/'.$rxa_greybox['name'];
	}
	$rxa_greybox['htdocsfilesdir'] = $rxa_greybox['filesdir'];

	// für Kompatibilität REDAXO 3.1, 3.2.x, 4.0.x
	include($rxa_greybox['basedir'] . '/functions/functions.compat.inc.php');	
	
/**
 * --------------------------------------------------------------------
 * Nur im Backend
 * --------------------------------------------------------------------
 */
	if (!$REX['GG']) {
		// Sprachobjekt anlegen
		$rxa_greybox['i18n'] = new i18n($REX['LANG'],$rxa_greybox['lang_path']);

		// Anlegen eines Navigationspunktes im REDAXO Hauptmenu
		$REX['ADDON']['page'][$rxa_greybox['name']] = $rxa_greybox['name'];
		// Namensgebung für den Navigationspunkt
		$REX['ADDON']['name'][$rxa_greybox['name']] = $rxa_greybox['i18n']->msg('menu_link');

		// Berechtigung für das Addon
		$REX['ADDON']['perm'][$rxa_greybox['name']] = $rxa_greybox['name'].'[]';
		// Berechtigung in die Benutzerverwaltung einfügen
		$REX['PERM'][] = $rxa_greybox['name'].'[]';		
	}

/*
 * --------------------------------------------------------------------
 * Outputfilter für das Frontend
 * --------------------------------------------------------------------
 */
	if ($REX['GG'])
	{
		rex_register_extension('OUTPUT_FILTER', 'greybox_opf');

		// Prüfen ob die aktuelle Kategorie mit der Auswahl übereinstimmt
		function greybox_check_cat($acat, $aart, $subcats, $greybox_cats)
		{

			// prüfen ob Kategorien ausgewählt
			if (!is_array($greybox_cats)) return false;

			// aktuelle Kategorie in den ausgewählten dabei?
			if (in_array($acat, $greybox_cats)) return true;

			// Prüfen ob Parent der aktuellen Kategorie ausgewählt wurde
			if ( ($acat > 0) and ($subcats == 1) )
			{
				$cat = OOCategory::getCategoryById($acat);
				while($cat = $cat->getParent())
				{
					if (in_array($cat->_id, $greybox_cats)) return true;
				}
			}

			// evtl. noch Root-Artikel prüfen
			if (strstr(implode('',$greybox_cats), 'r'))
			{
				if (in_array($aart.'r', $greybox_cats)) return true;
			}

			// ansonsten keine Ausgabe!
			return false;
		}

		// Output-Filter
		function greybox_opf($params)
		{
			global $REX, $REX_ARTICLE;
			global $rxa_greybox;

			// Für REDAXO < 4.2
			if (isset($REX_ARTICLE))
			{
				$REX['ARTICLE'] = $REX_ARTICLE;
			}
			
			$content = $params['subject'];
			
			if ( !strstr($content,'</head>') or !file_exists($rxa_greybox['path'].'/'.$rxa_greybox['name'].'.ini')
			 or ( strstr($content,'<script type="text/javascript" src="'.$rxa_greybox['htdocsfilesdir'].'/gb_scripts.js"></script>') and strstr($content,'<link href="'.$rxa_greybox['htdocsfilesdir'].'/gb_styles.css" rel="stylesheet" type="text/css" />') ) ) {
				return $content;
			}

			// Einstellungen aus ini-Datei laden
			if (($lines = file($rxa_greybox['path'].'/'.$rxa_greybox['name'].'.ini')) === FALSE) {
				return $content;
			} else {
				$va = explode(',', trim($lines[0]));
				$allcats = trim($va[0]);
				$subcats = trim($va[1]);
				if (isset($va[2])) {
					$footerout = trim($va[2]);
				} else {
					$footerout = 0;
				}				
				$greybox_cats = array();
				$greybox_cats = unserialize(trim($lines[1]));
				$rxa_greybox['excludeids'] = unserialize(trim($lines[2]));
			}

			// aktuellen Artikel ermitteln
			$artid = isset($_GET['article_id']) ? $_GET['article_id']+0 : 0;
			if ($artid==0) {
				$artid = $REX['ARTICLE']->getValue('article_id')+0;
			}
			if ($artid==0) { $artid = $REX['START_ARTICLE_ID']; }

			if (!$artid) { return $content; }

			$article = OOArticle::getArticleById($artid);
			if (!$article) { return $content; }

			// Exclude ID?
			if (in_array($artid, explode(',', $rxa_greybox['excludeids']))) { return $content; }

			// aktuelle Kategorie ermitteln
			if ( in_array($rxa_greybox['rexversion'], array('3.11')) ) {
				$acat = $article->getCategoryId();
			}
			if ( in_array($rxa_greybox['rexversion'], array('32', '40', '41', '42', '43')) ) {
				$cat = $article->getCategory();
				if ($cat) {
					$acat = $cat->getId();
				}
			}
			// Wenn keine Kategorie ermittelt wurde auf -1 setzen für Prüfung in greybox_check_cat, Prüfung auf Artikel im Root
			if (!isset($acat) or !$acat) { $acat = -1; }

			// Array anlegen falls keine Kategorien ausgewählt wurden
			if (!is_array($greybox_cats)) {
				$greybox_cats = array();
			}

			// Code für Greybox im head-Bereich ausgeben
			if ( ($allcats==1) or (greybox_check_cat($acat, $artid, $subcats, $greybox_cats) == true) )
			{
				$rxa_greybox['output'] = "\n".'	<!-- Addon Greybox '.$REX['ADDON']['version'][$rxa_greybox['name']].' -->'."\n";
				$rxa_greybox['output'] .= '	<link href="'.$rxa_greybox['htdocsfilesdir'].'/gb_styles.css" rel="stylesheet" type="text/css" />'."\n";
				$rxa_greybox['output'] .= '	<script type="text/javascript">'."\n";
				$rxa_greybox['output'] .= '		var GB_ROOT_DIR = "'.$rxa_greybox['htdocsfilesdir'].'/";'."\n";
				$rxa_greybox['output'] .= '	</script>'."\n";
				$rxa_greybox['output'] .= '	<script type="text/javascript" src="'.$rxa_greybox['htdocsfilesdir'].'/AJS.js"></script>'."\n";
				$rxa_greybox['output'] .= '	<script type="text/javascript" src="'.$rxa_greybox['htdocsfilesdir'].'/AJS_fx.js"></script>'."\n";
				$rxa_greybox['output'] .= '	<script type="text/javascript" src="'.$rxa_greybox['htdocsfilesdir'].'/gb_scripts.js"></script>'."\n";
				if ($footerout==1){
					$content = str_replace('</body>', $rxa_greybox['output'].'</body>', $content);
				} else {
					$content = str_replace('</head>', $rxa_greybox['output'].'</head>', $content);
				}				
			}

			return $content;
		}

	}
?>