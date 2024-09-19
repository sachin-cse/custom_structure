<?php
defined('BASE') OR exit('No direct script access allowed.');
class OthersModel extends Site
{
    function settings() {
		$ExtraQryStr = "name = 'SEO'";
		return $this->selectSingle(TBL_SETTINGS, "*", $ExtraQryStr);
	}
    
    function newSettings($params) {
        return $this->insertQuery(TBL_SETTINGS, $params);
    }
    
    function updateSetting($params) {
        $CLAUSE = "name = 'SEO'";
        return $this->updateQuery(TBL_SETTINGS, $params, $CLAUSE);
    }
}
?>