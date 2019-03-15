<?php
/**
 * SidebarLanguagelinks extension #10430
 * @version 1.0
 *
 * @file SidebarLanguagelink.php
 * @ingroup Extensions
 * @package MediaWiki
 * @author Cillian de Róiste
 * @copyright © 2014 Cillian de Róiste
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 *
 */


if( !defined( 'MEDIAWIKI' ) ) {
	echo( "This file is an extension to the MediaWiki software and cannot be used standalone.\n" );
	die( 1 );
}

define ('SIDEBARLANGUAGELINK_VERSION','1.0', '2014-07-16');
$wgHooks['SkinBuildSidebar'][] = 'addSidebarLanguagelink';

$wgExtensionCredits['other'][] = array(
	'name' => 'SidebarLanguagelink',
	'version' => SIDEBARLANGUAGELINK_VERSION,
	'author' => array( 'Cillian de Róiste' ),
	'description' => 'Displays the available translations of the current document to the sidebar',
	'url' => ''
);


function getCanonical() {
	global $wgTitle;
	$masterPageProperty = new SMWDIProperty('Master_page');

	$title = Title::newFromText(  $wgTitle->mTextform );
	$currentArticle = SMWDIWikiPage::newFromTitle( $title );
	$semanticData = smwfGetStore()->getSemanticData( $currentArticle );

	$masterPagePropVals = $semanticData->getPropertyValues( $masterPageProperty );
	$masterPageProp = array_pop( $masterPagePropVals );
	if ( is_null( $masterPageProp ) )  return NULL;
	$masterPage	= $masterPageProp->getDBkey();

	$canonicalTitle = Title::newFromText( $masterPage );
	return	SMWDIWikiPage::newFromTitle( $canonicalTitle );
}

function getTranslations(&$canonicalTranslation) {
	$masterPageProperty = new SMWDIProperty('Master_page');

	$description = new SMWValueDescription( $canonicalTranslation );
	$property = new SMWSomeProperty( $masterPageProperty, $description );

	$query = new SMWQuery( $property );
	$query->sort = true;
	$query->sortkeys = array('Language_code' => 'ASC');

	$queryResult = smwfGetStore()->getQueryResult( $query );
	return $queryResult->getResults();
}

function getFormattedTranslations($translations) {
	$formattedTranslations = "";
	$langCodeProperty = new SMWDIProperty( '_LCODE' );
	$langNames = Language::fetchLanguageNames();

	foreach ($translations as &$translation) {
		$translationData = smwfGetStore()->getSemanticData( $translation );
		$translationPropVals = $translationData->getPropertyValues( $langCodeProperty );
		$translationProp = array_pop( $translationPropVals );
		/* if ( is_null( $translationProp ) )  return true; */
		$lang = $translationProp->getString();
		$title = $translation->getTitle()->mTextform;
		$langName = $langNames[ $lang ];
		$formattedTranslations .= "* [[" . $title . "|" . $langName . "]]\n";
	}
	return $formattedTranslations;
}

function addSidebarLanguagelink( $skin, &$bar ) {
	global $wgTitle,$wgOut;

	$NS = $wgTitle->getNamespace();

	if ($NS==NS_MAIN) {

		$canonicalTranslation = getCanonical();
		if ( is_null ($canonicalTranslation)) return true;

		$translations = getTranslations($canonicalTranslation);

		$formattedTranslations = getFormattedTranslations($translations);

		$bar["languages"] = $wgOut->parse( $formattedTranslations );
	}
	return true;
}
