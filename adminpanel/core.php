<?php
include(__DIR__."/core/AdminModel.php");
include(__DIR__."/core/AdminController.php");
include(__DIR__."/modules/classes.php");

$rqs            = explode('&redstr=', $_SERVER['QUERY_STRING']);
$redirectString = base64_encode($rqs[0]);

include(LIBRARY."/core/api.admin.php");
?>