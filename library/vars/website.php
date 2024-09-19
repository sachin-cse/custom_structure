<?php
defined('BASE') OR exit('No direct script access allowed');
// Define variables ------------------------------------

define("SITE_NAME",             $website['siteName']);
define("SITE_TAGLINE",          $website['tagline']);
define("SITE_URL",              $website['siteUrl']);
define("SITE_EMAIL",            $website['siteEmail']);
define("SITE_PHONE",            $website['sitePhone']);
define("SITE_MOBILE",           $website['siteMobile']);
define("SITE_FAX",              $website['siteFax']);
define("SITE_ADDRESS",          $website['siteAddress']);
define("SITE_OPENING_HOURS",    $website['siteOpeningHours']);
define("SITE_CURRENCY",         $website['siteCurrency']);
define("SITE_CURRENCY_SYMBOL",  $website['siteCurrencySymbol']);
define("SITE_CACHE",            $website['cache']);

define("MAILHOST",              $website['smtpHost']);
define("MAILUSERNAME",          $website['smtpUserName']);
define("MAILPASSWORD",          $website['smtpUserPassword']);
define("SMTPSECURE",            $website['smtpEncryption']);
define("PORT",                  $website['smtpPort']);

define("GSITE_VERIFY_URL",      'https://www.google.com/recaptcha/api/siteverify');
define("FB_APPID",              "605677200556972");
define("FB_VERSION",            "v12.0");

define("COPY_RIGHT",            '&copy; '.date('Y').' <a href="'.SITE_LOC_PATH.'/">'.SITE_NAME.'</a>. All Rights Reserved.');
define("DEVELOPED_BY",          'Designed & Developed by <a href="http://www.eclicksoftwares.com/" rel="noopener noreferrer nofollow" target="_blank" title="Eclick Softwares & Solutions Pvt. Ltd.">Eclick Softwares & Solutions Pvt. Ltd.</a>');
?>