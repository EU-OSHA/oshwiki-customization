<?php
/**
 * @author Jean-Lou Dupont
 * @package InterWikiLinkManager
 */
//<source lang=php>

class InterWikiLinkManager
{
	// constants.
	const thisName = 'InterWikiLinkManager';
	const thisType = 'other';

	const rRead    = "read";
	const rEdit    = "edit";
	const mPage    = "MediaWiki:Registry/InterWikiLinks";

	// preload wikitext
	// ================
	
	const mgword = 'iwl';
	
	const header = "
{| border='1'
! Prefix || URI || Local || Trans";
	const footer = "
|}";
	const sRow = "
|-";
	const sCol = "
| ";

	// Link Table	
	var $iwl;     // the table read from the database
	var $new_iwl; // the desired table elements
	  
	function __construct(  )
	{
		$this->iwl     = array();
		$this->new_iwl = array();
	}
	public function mg_iwl( &$parser, $prefix, $uri, $local, $trans, $dotableline = true )
	// magic word handler function
	{
		if ( $r = $this->checkElement( $prefix, $uri, $local, $trans, $errCode ) )
		{
			$el = $this->new_iwl[ $prefix ] = array(	'uri'    => $uri, 
														'local'  => $local, 
														'trans'  => $trans 	);
		}

		// was there an error?
		if ( !$r )
			return $this->getErrMessage( $errCode );
		
		if ( $dotableline )
			return $this->formatLine( $prefix, $el );
			
	}	
	public function hArticleSave( &$article, &$user, &$text, &$summary, $minor, $dontcare1, $dontcare2, &$flags )
	{
		// Paranoia: this should have already been checked.
		// does the user have the right to edit pages in this namespace?
		if (! $article->mTitle->userCan(self::rEdit) ) return true;  

		// Are we dealing with the page which contains the links to manage?
		if ( $article->mTitle->getFullText() != self::mPage ) return true;

		// Invoke the parser in order to retrieve the interwiki link data
		// composed through the magic word 'iwl'
		global $wgParser, $wgUser;
		$popts = new ParserOptions( $wgUser );
		$parserOutput = $wgParser->parse( $text, $article->mTitle, $popts, true, true, $article->mRevision );

		// Write the counts of deletes, inserts and updates.
		$summary = $this->updateIWL().$summary;
		
		return true; // continue hook-chain.
	}
	/**
		This hook is called to preload text upon initial page creation.
	 */
	public function hEditFormPreloadText( &$text, &$title )

	{
		//TODO use canonical index for namespace comparison => multi-language support
		// Are we dealing with the page which contains the links to manage?
		if ( $title->getFullText() != self::mPage ) return true;
		
		// Paranoia: Is the user allowed committing??
		// We shouldn't even get here if the 'edit' permission gets
		// verified adequately.
		if (! $title->userCan(self::rEdit) ) return true;		

		// start by reading the table from the database
		$this->getIWLtable();

		$text .= $this->getHeader();					// HEADER
		
		foreach( $this->iwl as $prefix => &$el )
			$text .= $this->formatMagicWordLine( $prefix, $el );
	
		$text .= $this->getFooter();					// FOOTER
	
		// stop hook chain.
		return false;
	}
	
	private function getIWLtable()
	// reads the 'interwiki' table into a local variable
	{
		$db =& wfGetDB(DB_SLAVE);
		$tbl = $db->tableName('interwiki');

		$result = $db->query("SELECT iw_prefix,iw_url,iw_local,iw_trans FROM  $tbl");
		
		while ( $row = $db->fetchRow($result) ) 
			$this->iwl[ $row[0] ] = array(	'uri'   => $row[1], 
											'local' => $row[2], 
											'trans' => $row[3] );
		$db->freeResult( $result );
		
		ksort( $this->iwl );
	}
	
	private function getHeader() { return self::header; }
	private function getFooter() { return self::footer; }
	
	private function formatMagicWordLine( $prefix, &$el )
	{
		return '
{{#'.self::mgword.':'.
	$prefix.'|'.
	$el['uri']   .'|'.
	$el['local'] .'|'.
	$el['trans'] .'}}';
	}
	
	private function formatLine( $prefix, &$el )
	{
		$text = '';
		$text .= self::sRow;
		
		$text .= self::sCol;	$text .= $prefix;
		$text .= self::sCol;	$text .= $el['uri'];
		$text .= self::sCol;	$text .= $el['local'];
		$text .= self::sCol;	$text .= $el['trans'];

		return $text;				
	}
	
	private function updateIWL()
	{
		// The update process is fairly straightforward:
		// 0) Get the current list of entries from the database
		// 1) Compute the list of entries to delete
		// 2) Compute the list of entries to insert
		// 3) Compute the list of entries to update
	
		$this->getIWLtable();

		$dlist = $this->computeDeleteList();  $dc = count( $dlist );
		$ilist = $this->computeInsertList();  $ic = count( $ilist );
		$ulist = $this->computeUpdateList();  $uc = count( $ulist );

		$this->execute( $dlist, $ilist, $ulist );

		return "(d=$dc,i=$ic,u=$uc)";			
	}

	private function computeDeleteList()
	{
		// if it is in the database but not in the wanted list
		$dlist = null;
		foreach ( $this->iwl as $prefix => &$el )
			if ( ! in_array( $prefix, array_keys( $this->new_iwl ) ) )
				$dlist[] = $prefix;

		return $dlist;
	}
	private function computeInsertList()
	{
		// if it is not in the database but in the wanted list
		$ilist = null;
		foreach ( $this->new_iwl as $prefix => &$el )
			if ( ! in_array( $prefix, array_keys( $this->iwl ) ) )
				$ilist[] = $prefix;

		return $ilist;
	}
	private function computeUpdateList()
	{
		// if it is in the database but updated in the wanted list 
		$ulist = null;
		foreach ( $this->new_iwl as $prefix => &$el )
			if ( in_array( $prefix, array_keys( $this->iwl ) ) )
				if (($el['uri']   != $this->iwl[$prefix]['uri']  ) || 
					($el['local'] != $this->iwl[$prefix]['local']) ||
					($el['trans'] != $this->iwl[$prefix]['trans']) )
						$ulist[] = $prefix;

		return $ulist;
	}

	private function execute( &$dlist, &$ilist, &$ulist )
	// update the interwiki database table.
	{
		$db =& wfGetDB(DB_MASTER);
		$tbl = $db->tableName('interwiki');

		foreach ( $ilist as $prefix )
		{
			$uri   = $this->new_iwl[$prefix]['uri']; 
			$local = $this->new_iwl[$prefix]['local']; 
			$trans = $this->new_iwl[$prefix]['trans']; 
			$db->query("INSERT INTO $tbl (iw_prefix,iw_url,iw_local,iw_trans) VALUES('$prefix','$uri',$local,$trans )");
		}
												   
		foreach ( $ulist as $prefix ) 
		{
			$uri   = $this->new_iwl[$prefix]['uri']; 
			$local = $this->new_iwl[$prefix]['local']; 
			$trans = $this->new_iwl[$prefix]['trans']; 

			$db->query("UPDATE $tbl SET iw_url='$uri',iw_local=$local,iw_trans=$trans WHERE iw_prefix='$prefix'");
		}
		
		foreach ( $dlist as $prefix )
			$db->query("DELETE FROM $tbl WHERE iw_prefix = '$prefix'");
			
		$db->commit();			
	}
	
// TODO =================================================================================

	private function checkElement( &$prefix, &$uri, &$local, &$trans, &$errCode )
	{
		// no validation implemented at this moment.
		
		// everything is OK.
		return true;		
	}
	private function getErrMessage( $errCode )
	{
		// not much checking implemented at the moment...
		return '';	
	}

} // END CLASS DEFINITION
//</source>