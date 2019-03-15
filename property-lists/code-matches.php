<?php
/**
 * code-matches.php
 *
 * Meant to be used with the "values from url" feature of the
 * MediaWiki Semantic Forms extension.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * @author Nischay Nahata, for WikiWorks
 * @author Yaron Koren, for WikiWorks
 */

error_reporting( 0 );
ini_set( 'display_errors', 0 );

$project = $_GET['data-type'];
$language = $_GET['language'];
$substring = $_GET['substring'];
$pattern = '/' . preg_quote($substring, '/') . '/i';
//$file = 'osha.fr.json';

switch( $project ) {
        case 'osha':
                //$all=simplexml_load_file('MultilingualThesaurus.vdex');
                $all=simplexml_load_file('osha.vdex');
                break;
        case 'isco':
                $all=simplexml_load_file('isco.vdex');
                break;
        case 'nace':
                $all=simplexml_load_file('nace.vdex');
                break;
        default:
                echo '{"pfautocomplete":[]}';
                die();
}

$result = array();


function generate( $all, $projectStr ) {
        global $result, $language, $pattern;

	// We don't need more than 20 results.
	if ( count( $result['title'] ) >= 20 ) return;
        foreach ( $all->term as $term ) {
		$codeStr = (string)$term->termIdentifier;
                foreach(  $term->caption->langstring as $lang ) {
                         if( (string)$lang['language'] == $language ) {
			 	$langStr = (string)$lang;
				$fullStr = '(' . $projectStr . $codeStr . ') ' . $langStr;
//				print "$pattern, $fullStr"; die;
			 	if ( preg_match($pattern, $fullStr) ) {
					$result['title'][]= '{"title":"' . $fullStr . '"}';
				}
				if ( count( $result['title'] ) >= 20 ) return;
			}
                }
                if( isset($term->term) ) {
                        generate( $term, $projectStr . $codeStr . '-' );
                }
        }
}

generate( $all, strtoupper($project) . ' ' );

echo '{"pfautocomplete":[' . implode( $result['title'], ',' ) . ']}' ;

?>
