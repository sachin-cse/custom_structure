<?php
defined('BASE') OR exit('No direct script access allowed');
// Define variables ------------------------------------

include(ROOT_PATH."/library/vars/constants.php");
include(LIBRARY."/vars/db_tables.php");
include(LIBRARY."/vars/global_array.php");

class GlobalArray{
    public $_array = array();
    
    public function __construct($params){
        global $GLOBAL_ARRAY;
        if (is_array($params)) {
            foreach($params as $arr)
                $this->_array[$arr] =& $GLOBAL_ARRAY[$arr];
        }
        else
            $this->_array[$params] =& $GLOBAL_ARRAY[$params];
        
        return $this->_array;
    }
    
    public function get($arr){
        return $this->_array[$arr];
    }
}

$siteObj  = new Site;
//define site variables------------------------------
$website  = $siteObj -> getSiteBysiteURL(DOMAIN);
define("SITE_ID",             $website['siteId']);
$siteId         = $website['siteId'];
$siteStatus     = $website['status'];
include(LIBRARY."/vars/website.php");
?>