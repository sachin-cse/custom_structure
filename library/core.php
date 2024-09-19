<?php
defined('BASE') OR exit('No direct script access allowed.');

include(__DIR__."/core/session.class.php");
include(__DIR__."/core/site.class.php");
include(__DIR__."/core/file.class.php");
include(__DIR__."/includes/vars.php");
include(__DIR__."/includes/email_template.php");
include(__DIR__."/includes/functions.php");
include(__DIR__."/core/module.class.php");
include(__DIR__."/core/genl.class.php");
include(__DIR__."/core/security.class.php");
include(__DIR__."/core/rest.class.php");
include(__DIR__."/core/pagination.php");
include(__DIR__."/core/mobiledetect.php");
include(__DIR__."/purify/library/HTMLPurifier.auto.php");

if(!$inside)
    include(LIBRARY."/core/api.front.php");
?>