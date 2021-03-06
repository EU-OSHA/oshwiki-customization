<?php

$wgSitename			= "OSHWiki";

## The URL base path to the directory containing the wiki;
## defaults for all runtime URL paths are based off of this.
## For more information on customizing the URLs please see:
## http://www.mediawiki.org/wiki/Manual:Short_URL
$wgScriptPath		= "";
$wgScriptExtension	= ".php";
$wgArticlePath      = "/wiki/$1";
$wgUsePathInfo      = true;

## The relative URL path to the skins directory
/* $wgStylePath		= "$wgScriptPath/skins"; */

## The relative URL path to the logo.  Make sure you change this from the default,
## or else you'll overwrite your logo when you upgrade!
$wgLogo				= "";

## UPO means: this is also a user preference option

$wgEnableEmail		= true;
$wgEnableUserEmail	= true; # UPO

$wgEnotifUserTalk = true; # UPO
$wgEnotifWatchlist = true; # UPO
$wgEmailAuthentication = true;


# MySQL specific settings
$wgDBprefix			= "";

# MySQL table options to use during installation or update
$wgDBTableOptions	= "ENGINE=InnoDB, DEFAULT CHARSET=binary";

# Experimental charset support for MySQL 4.1/5.0.
$wgDBmysql5 = true;

## Shared memory settings
$wgMainCacheType = CACHE_NONE;
$wgMemCachedServers = array();

## To enable image uploads, make sure the 'images' directory
## is writable, then set this to true:
$wgEnableUploads	   = true;
$wgUseImageMagick = true;

## If you use ImageMagick (or any other shell command) on a
## Linux server, this will need to be set to the name of an
## available UTF-8 locale
$wgShellLocale = "en_US.utf8";

## If you want to use image uploads under safe mode,
## create the directories images/archive, images/thumb and
## images/temp, and make them all writable. Then uncomment
## this, if it's not already uncommented:
# $wgHashedUploadDirectory = false;

## If you have the appropriate support software installed
## you can enable inline LaTeX equations:
$wgUseTeX			= false;

## Set $wgCacheDirectory to a writable directory on the web server
## to make your wiki go slightly faster. The directory should not
## be publically accessible from the web.
#$wgCacheDirectory = "$IP/cache";

/* $wgLocalInterwiki	= strtolower( $wgSitename ); */

$wgLanguageCode = "en";

## Default skin: you can change the default skin. Use the internal symbolic
## names, ie 'vector', 'monobook':

wfLoadSkin( 'Osha' );
$wgDefaultSkin = 'Osha';

## For attaching licensing metadata to pages, and displaying an
## appropriate copyright notice / icon. GNU Free Documentation
## License and Creative Commons licenses are supported so far.
# $wgEnableCreativeCommonsRdf = true;
$wgRightsPage = ""; # Set to the title of a wiki page that describes your license/copyright
$wgRightsUrl = "";
$wgRightsText = "";
$wgRightsIcon = "";
# $wgRightsCode = ""; # Not yet used


# When you make changes to this configuration file, this will make
# sure that cached pages are cleared.
$wgCacheEpoch = max( $wgCacheEpoch, gmdate( 'YmdHis', @filemtime( __FILE__ ) ) );
$wgUseSquid = true;
$wgUsePrivateIPs = true;
