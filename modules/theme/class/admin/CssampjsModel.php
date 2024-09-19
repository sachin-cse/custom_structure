<?php defined('BASE') OR exit('No direct script access allowed.');
class CssampjsModel extends Site
{
    function settings() {
		$ExtraQryStr = "name = 'CSSJS'";
		return $this->selectSingle(TBL_SETTINGS, "*", $ExtraQryStr);
	}
    
    function newSettings($params) {
        return $this->insertQuery(TBL_SETTINGS, $params);
    }
    
    function updateSetting($params) {
        $CLAUSE = "name = 'CSSJS'";
        return $this->updateQuery(TBL_SETTINGS, $params, $CLAUSE);
    }
}