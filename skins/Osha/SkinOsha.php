<?php
/**
 * MonoBook nouveau
 *
 * Translated from gwicke's previous TAL template version to remove
 * dependency on PHPTAL.
 *
 * @todo document
 * @file
 * @ingroup Skins
 *
 * Inherit main code from SkinTemplate, set the CSS and template filter.
 */
class SkinOsha extends SkinTemplate {
	/** Using monobook. */
	public $skinname = 'osha';
	public $stylename = 'osha';
	public $template = 'OshaTemplate';

	/**
	 * @param OutputPage $out
	 */
	function setupSkinUserCss( OutputPage $out ) {
		global $wgHandheldStyle;

		parent::setupSkinUserCss( $out );

		$out->addModuleStyles( array(
			'mediawiki.skinning.interface',
			'mediawiki.skinning.content.externallinks',
			'skins.osha.styles'
		) );

		// Append to the default screen common & print styles...
		if( $wgHandheldStyle ) {
			// Currently in testing... try 'chick/main.css'
			$out->addStyle( $wgHandheldStyle, 'handheld' );
		}

		$out->addStyle( $this->stylename . '/IE50Fixes.css', 'screen', 'lt IE 5.5000' );
		$out->addStyle( $this->stylename . '/IE55Fixes.css', 'screen', 'IE 5.5000' );
		$out->addStyle( $this->stylename . '/IE60Fixes.css', 'screen', 'IE 6' );
		$out->addStyle( $this->stylename . '/IE70Fixes.css', 'screen', 'IE 7' );

		$out->addStyle( 'rtl.css', 'screen', '', 'rtl' );

	}
}
