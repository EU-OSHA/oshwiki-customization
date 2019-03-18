<?php
		/**
		* UserContributions extension #9036
		* @version 1.0
		*
		* @file UserContributions.php
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

		define ('USERCONTRIBUTIONS_VERSION','0.1', '2014-02-04');

		$wgHooks['OutputPageBeforeHTML'][] = 'addContributionsFooter';
		$wgHooks['sfHTMLBeforeForm'][] = 'addContributionsFooter';

		$wgExtensionCredits['other'][] = array(
				'name' => 'User Contributions',
				'version' => USERCONTRIBUTIONS_VERSION,
				'author' => array( 'Cillian de Róiste' ),
				'description' => 'Embeds a List of User Contributions on User Namespace Pages',
				'url' => ''
		);
 
		function addContributionsFooter (&$articleTitle, &$text) {
				global $wgTitle,$wgOut,$wgRequest;

				$NS = $wgTitle->getNamespace();
				$action = $wgRequest->getVal('action');

				if (($NS==NS_USER) or ($NS==NS_SPECIAL) and ($action == null)) {
					$context = RequestContext::getMain();
					if ($NS==NS_USER) {
						$user_id = explode(":", $wgOut->getPageTitle())[1];
					}
					else {
						$user_id = explode("FormEdit/User/User:", $wgTitle)[1];
					};

					$dbr = wfGetDB( DB_SLAVE );
					$user_res = $dbr->select(array( 'user' ),
										array( 'user_real_name' ),
										array( 'user_id' => $user_id),
										__METHOD__
										);
					$row = $dbr->fetchObject( $user_res );
					$user_real_name = $row->user_real_name;

					$pager = new ContribsPager($context,
											   array('target' => $user_id,
													 'contribs' => 'user',
													 'namespace' => '0',
													 'year' => '',
													 'month' => '',
													 'deletedOnly' => false,
													 'topOnly' => false, 
													 'nsInvert' => false,
													 'associated' => false,
													 ) );
					$contributions = "";
					if ( $pager->getNumRows() ) {
						$nav_bar = '<p>' . $pager->getNavigationBar() . '</p>';
						$contributions = $wgOut->parse("== Contributions ==\n") . $nav_bar . $pager->getBody() . $nav_bar;
					}

					$text = $wgOut->parse("= " . $user_real_name . " =\n") . $text . $contributions;
						
				}
				return true;
		}
