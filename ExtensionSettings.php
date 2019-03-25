<?php
# http://www.mediawiki.org/wiki/Manual:Wiki_family#Shared_Settings
#####
##### Extension Directory Variables
#####

# This is required for infoboxes to work
wfLoadExtension( 'ParserFunctions' );
$wgPFEnableStringFunctions = true;
$wgPFStringLengthLimit = 5000;

# This is required so that most filetypes can be uploaded. Some are still blacklisted though.
$wgStrictFileExtensions = false;

# Adding this in the SMW config to allow uploading of PDF files, this
# already works on the old version so I don't see why it is now
# required
$wgAllowExternalImages = true;
$wgFileExtensions[] = 'pdf';

# To list a category of pages
# https://www.mediawiki.org/wiki/Extension:DynamicPageList3
wfLoadExtension("DynamicPageList");

# http://www.mediawiki.org/wiki/Extension:AuthorInfo
#require_once("$IP/extensions/AuthorInfo/AuthorInfo.php");

# Enable CategoryTree: http://www.mediawiki.org/wiki/Extension:CategoryTree
$wgUseAjax = true;
wfLoadExtension( 'CategoryTree' );

# http://www.mediawiki.org/wiki/Extension:Cite/Cite.php
wfLoadExtension( 'Cite' );
wfLoadExtension( 'CiteThisPage' );


# LdapAuthentication
# http://www.mediawiki.org/wiki/Extension:LDAP_Authentication/Configuration
require_once( "$IP/extensions/LdapAuthentication/LdapAuthentication.php" );
$wgAuth = new LdapAuthenticationPlugin();
$wgLDAPDomainNames = array( "osha", "editors" );
#$wgLDAPServerNames = array( "osha" => "ldap.osha.europa.eu" );
$wgLDAPServerNames = array( "osha" => "194.30.35.128" );
#$wgLDAPEncryptionType = array("osha" => "ssl" );
$wgLDAPEncryptionType = array("osha" => "clear" );
$wgLDAPBaseDNs = array( "osha" => "ou=people,dc=osha,dc=europa,dc=eu");
$wgLDAPSearchAttributes = array( "osha" =>"mail" );
$wgLDAPLowerCaseUsername = array( "osha" => true );
$wgLDAPProxyAgent = array( "osha"=>"cn=Reader,dc=osha,dc=europa,dc=eu");
$wgLDAPPreferences = array( "osha"=>array( "email"=>"mail","realname"=>"cn","nickname"=>"cn") );

# Restrict to the OSHWIKIEditors group
$wgLDAPGroupNameAttribute = array( "osha"=>"uid" );
# NOTE: We have patch 
$wgLDAPGroupUseFullDN = array( "osha"=>true);
$wgLDAPGroupUseRetrievedUsername = array("osha" => true);
$wgLDAPGroupBaseDNs = array("osha"=>"ou=OSHWiki,ou=Sites,dc=osha,dc=europa,dc=eu");
$wgLDAPGroupObjectclass = array( "osha"=>"posixGroup" );
$wgLDAPGroupAttribute = array( "osha"=>"memberUid" );
$wgLDAPRequiredGroups = array( "osha"=>array("cn=Editors,ou=OSHWiki,ou=Sites,dc=osha,dc=europa,dc=eu"));

# Password reset
$wgLDAPMailPassword = array('osha' => true);
$wgLDAPUpdateLDAP = array('osha' => true);
$wgLDAPWriterDN = array('osha' => 'uid=oshwikiwriter,dc=osha,dc=europa,dc=eu');
$wgLDAPPasswordHash = array('osha' => 'crypt');

require_once("$IP/secrets.php");

$wgGroupPermissions['*']['createaccount'] = false;
$wgGroupPermissions['*']['edit'] = false;

$wgLDAPDebug = 0;
$wgDebugLogGroups['ldap'] = '/tmp/wiki-debug.log';
  
# http://www.mediawiki.org/wiki/Extension:ContributionCredits
require_once("$IP/extensions/ContributionCredits/ContributionCredits.php");

# optional: set this variable to the Section Header for the list of Contributors. Shown the default value.
$wgContributionCreditsHeader = "= Contributors =\n ";

require_once("$IP/extensions/UserContributions/UserContributions.php");

# Enable subcategories
$wgNamespacesWithSubpages[NS_CATEGORY] = true;

# http://www.mediawiki.org/wiki/Extension:AddThis
require_once("$IP/extensions/AddThis/AddThis.php");
$wgAddThispubid = 'osha';
$wgAddThisHeader = true;
$wgAddThisSidebar = false;

# http://www.mediawiki.org/wiki/Extension:Variables
require_once("$IP/extensions/Variables/Variables.php");

enableSemantics('oshwiki.eu');

wfLoadExtension( 'PageForms' );

# Note the URL must be accessible to the local Apache, without auth
$wgPageFormsAutocompletionURLs['osha-property-list'] = "$wgServer$wgScriptPath/property-lists/osha.json";
$wgPageFormsAutocompletionURLs['isco-property-list'] = "$wgServer$wgScriptPath/property-lists/isco.json";
$wgPageFormsAutocompletionURLs['nace-property-list'] = "$wgServer$wgScriptPath/property-lists/nace.json";

# http://www.mediawiki.org/wiki/Extension:Semantic_Internal_Objects
include_once("$IP/extensions/SemanticInternalObjects/SemanticInternalObjects.php");

wfLoadExtension( 'ReplaceText' );

# $smwgQMaxInlineLimit = 5;
# $smwgQPrintoutLimit = 5;

###
# Sets whether or not the 'printouts' textarea should have autocompletion
# on property names.
##
$smwgAutocompleteInSpecialAsk = false;

$smwgPageSpecialProperties = array( '_MDAT', '_CDAT' );

# ##
# Whether to autocomplete on all characters in a string, not just the
# beginning of words - this is especially important for Unicode strings,
# since the use of the '\b' regexp character to match on the beginnings
# of words fails for them.
# ##
$sfgAutocompleteOnAllChars = true;

##
# The NACE properties currently have the largest amount of values (just under 3300) 
##
$sfgMaxAutocompleteValues = 3300;
$codeMatchesURL = "/property-lists/code-matches.php";

$codeMatchesDataTypes = array( 'osha', 'isco', 'nace' );
$codeMatchesLanguages = array( 'en', 'bg', 'bs',  'cs', 'da', 'de', 'el', 'es', 'et', 'fi', 'fr', 'ga', 'he', 'hr', 'hu', 'is', 'it', 'ko', 'lt', 'lv', 'mk', 'mt', 'nl', 'no', 'pl', 'pt', 'ro', 'sh', 'sk', 'sl', 'sq', 'sr', 'sv', 'tr');
 
foreach ( $codeMatchesDataTypes as $dataType ) {
	foreach ( $codeMatchesLanguages as $language ) {
		$wgPageFormsAutocompletionURLs["code-matches-$dataType-$language"] =
			"$codeMatchesURL?data-type=$dataType&language=$language&substring=<substr>";
	}
}

# FIXME
# Jos WikiWorks
# Display properties just below the category box
$wgHooks['SkinAfterContent'][] = 'PropSubCat';


function PropSubCat (&$data, $skin) {
		global $wgOut;
		$wTemplate = '{{DisplayProperties}}';
		$wikiOutput=$wgOut->parse($wTemplate);
		$data = $wikiOutput;
return true;
}

##
# Ratings extension
# https://www.mediawiki.org/wiki/Extension:VoteNY
##
wfLoadExtension( 'VoteNY' );
$wgGroupPermissions['*']['vote'] = true; // Anonymous users can vote

wfLoadExtension( 'UserMerge' ); 
// By default nobody can use this function, enable for bureaucrat?
$wgGroupPermissions['bureaucrat']['usermerge'] = true;
 
// optional: default is array( 'sysop' )
// $wgUserMergeProtectedGroups = array( 'groupname' );

$wgShowExceptionDetails = true;

require_once("$IP/extensions/SidebarLanguagelink/SidebarLanguagelink.php");

require_once "$IP/extensions/WikiSEO/WikiSEO.php";


/* $wgDefaultUserOptions['wikieditor-preview'] = 1; */

/* ini_set('display_errors', 'on'); */
/* error_reporting(E_ALL); */

$wgShowExceptionDetails = false; 

$wgSpecialVersionShowHooks = true;



/* $wgUserrightsInterwikiDelimiter = '#'; */


require_once "$IP/extensions/VisualEditor/VisualEditor.php";

// Enable by default for everybody
$wgDefaultUserOptions['visualeditor-enable'] = 1;

// Don't allow users to disable it
$wgHiddenPrefs[] = 'visualeditor-enable';

$wgVisualEditorSupportedSkins = array( 'osha' );

require_once "$IP/extensions/ExternalData/ExternalData.php";
$edgStringReplacements['PIWIK_AUTH'] = 'bd16bd5296c7d89708e65149d5125302';
$edgCacheTable = 'ed_url_cache';
$edgCacheExpireTime = 24 * 60 * 60;
$wgHTTPTimeout = 60;

#$mwgQMaxInlineLimit = 2000;

