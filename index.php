<?php
include("include.php");
 
/*------------------------------------------------------------------
If the site is disabled
--------------------------------------------------------------------*/
if($siteStatus!='Y')
    $siteObj->underConstruction();
/*-------------------------------------------------------------------*/

$api = new API;
$api->processApi();
?>