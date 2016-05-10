<?php
/**
 * @author Jean-Lou Dupont
 * @package JSKitRating
 * @version 1.4.0
 * @Id $Id: JSKitRating.php 850 2008-01-06 04:46:34Z jeanlou.dupont $
 */
//<source lang=php>
if (!class_exists('StubManager'))
	echo '[[Extension:JSKitRating]] <b>requires</b> [[Extension:StubManager]] version >= 1.1.0'."\n";
else
{
	$wgExtensionCredits['other'][] = array( 
		'name'    		=> 'JSKitRating',
		'version'		=> '1.4.0',
		'author'		=> 'Jean-Lou Dupont',
		'url'			=> 'http://www.mediawiki.org/wiki/Extension:JSKitRating',	
		'description' 	=> "Provides integration with JSKit Rating tool.", 
	);
	
	StubManager::createStub2(	array(	'class' 		=> 'JSKitRating', 
										'classfilename'	=> dirname(__FILE__).'/JSKitRating.body.php',
										'mgs'			=> array( 'jskitrating' )
									)
							);
}
//</source>
