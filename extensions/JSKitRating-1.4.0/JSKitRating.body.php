<?php
/**
 * @author Jean-Lou Dupont
 * @package JSKitRating
 * @version 1.4.0
 * @Id $Id: JSKitRating.body.php 888 2008-01-14 01:51:05Z jeanlou.dupont $
 */
//<source lang=php>
class JSKitRating
{
	const thisType = 'other';
	const thisName = 'JSKitRating';

	// make sure we only include the script once per page!
	var $scriptIncluded = false;
	
	/*
	 * m: mandatory parameter
	 * s: sanitization required
	 * l: which parameters to pick from list
	 * d: default value
	 */
	static $parameters = array(
		// not part of JS-Kit parameters
		'noscript'	=>array( 'm' => false, 's' => false, 'l' => false, 'd' => false ),
		// JS-Kit parameters:
		'notop'		=>array( 'm' => false, 's' => true, 'l' => true, 'd' => null ),	//from the Navigator service
		'title'		=>array( 'm' => false, 's' => true, 'l' => true, 'd' => null ),
		'permalink'	=>array( 'm' => false, 's' => true, 'l' => true, 'd' => null ),
		'imageurl'	=>array( 'm' => false, 's' => true, 'l' => true, 'd' => null ),
		'imagesize'	=>array( 'm' => false, 's' => true, 'l' => true, 'd' => null ),
		'path'		=>array( 'm' => false, 's' => true, 'l' => true, 'd' => null ),
		'starColor'	=>array( 'm' => false, 's' => true, 'l' => true, 'd' => null ),
		'userColor'	=>array( 'm' => false, 's' => true, 'l' => true, 'd' => null ),
		'view'		=>array( 'm' => false, 's' => true, 'l' => true, 'd' => null ),
	);
	/**
	 * {{#jskitrating: [optional parameters] }}
	 */
	public function mg_jskitrating( &$parser )
	{
		$params = func_get_args();
		$liste = StubManager::processArgList( $params, true );		
		
		// check for ''noscript'' parameter
		$noscript = false;
		if ( isset( $liste['noscript'] ) )
		{
			$r = strtolower( $liste['noscript'] );
			if ( ($r == '1') || ($r=='true') )
				$noscript = true;
		}
		
		$output = $this->renderEntry( $liste );

		if ( !$noscript )
			if (!$this->scriptIncluded)
			{
				$this->scriptIncluded = true;
	
				$output .= <<<EOT
	<script src="http://js-kit.com/ratings.js"></script>
EOT;
			}

		return array( $output, 'noparse' => true, 'isHTML' => true );
	}
	/**
	 * Returns 1 fully rendered DIV section
	 */
	protected function renderEntry( &$liste )
	{
		// all parameters are optional	
		$sliste= ExtHelper::doListSanitization( $liste, self::$parameters );
		$attrListe = null;
		if (is_array( $sliste ))
		{		
			$r = ExtHelper::doSanitization( $sliste, self::$parameters );
			$attrListe = ExtHelper::buildList( $liste, self::$parameters );
		}

		$output = <<<EOT
<div class="js-kit-rating" {$attrListe}></div>
EOT;
		return $output;
	}
} // end class
//</source>
