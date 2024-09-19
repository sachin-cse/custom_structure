<?php
defined('BASE') OR exit('No direct script access allowed');
// Define variables ------------------------------------
define("VALUE_PER_PAGE",        40);
define("COOKIE_TIME_OUT",       1440); // 1440 => 1 day in minutes // 604800 => 1 week in seconds

//define("DS",                    DIRECTORY_SEPARATOR);
define("DS",                     '/');
define("TMPL_PATH",             'templates'.DS.'default');

define("CACHE",                 "cache");
define("LIB",                   "library");
define("MODULE_PATH",           "modules");
define("PLUGINS_PATH",          "plugins");
define("ADMIN_PATH",            "adminpanel");

define("FRONT_VIEW",            'view'.DS.'front');
define("ADMIN_VIEW",            'view'.DS.'admin');
define("CLASS_PATH",            'class'.DS.'front');
define("ADMIN_CLASS",           'class'.DS.'admin');

define("SESSION_PATH",          ROOT_PATH.DS.'sessions');

define('LIBRARY',               ROOT_PATH.DS.LIB);
define('MODULE',                ROOT_PATH.DS.MODULE_PATH);
define('PLUGINS',               ROOT_PATH.DS.PLUGINS_PATH);
define('VENDOR_PATH',           ROOT_PATH.DS.MODULE_PATH.DS.'vendor');

define('MEDIA',                 'media');

define('MEDIA_SRC',             SITE_LOC_PATH.DS.MEDIA);
define('MEDIA_ROOT',            ROOT_PATH.DS.MEDIA);

define('CACHE_SRC',             SITE_LOC_PATH.DS.MEDIA.DS.CACHE);
define('CACHE_ROOT',            ROOT_PATH.DS.MEDIA.DS.CACHE);

define('MEDIA_MODULE_ROOT',     MEDIA_ROOT.DS.'module');
define('MEDIA_MODULE_SRC',      MEDIA_SRC.DS.'module');

define('MEDIA_EDITOR_ROOT',     MEDIA_ROOT.DS.'library'.DS.'editor');
define('MEDIA_EDITOR_SRC',      MEDIA_SRC.DS.'library'.DS.'editor');

define('MEDIA_FILES_ROOT',      MEDIA_ROOT.DS.'library'.DS.'uploads');
define('MEDIA_FILES_SRC',       MEDIA_SRC.DS.'library'.DS.'uploads');

define('STYLE_FILES_SRC',       SITE_LOC_PATH.DS.TMPL_PATH);

define('SITE_ADMIN_PATH',       BASE.DOMAIN.DS.ADMIN_PATH); 
define('ADMIN_TMPL_PATH',       SITE_ADMIN_PATH.DS.TMPL_PATH); 
 
define('PLUGINS_REPO_LINK',     "aHR0cHM6Ly93d3cuZWNsaWNrcHJvamVjdHMuY29tL3NrZWxldG9uL3BsdWdpbnJlcG8vcGx1Z2lucw=="); // https://www.eclickprojects.com/skeleton/pluginrepo/plugins
define('PLUGINS_REPO_INFO',     "aHR0cHM6Ly93d3cuZWNsaWNrcHJvamVjdHMuY29tL3NrZWxldG9uL3BsdWdpbnJlcG8vcGx1Z2lucy5qc29u"); // https://www.eclickprojects.com/skeleton/pluginrepo/plugins.json
define('PLUGINS_REPO',          $_SERVER['DOCUMENT_ROOT'].DS.'skeletonv2'.DS.'pluginrepo'.DS);
?>