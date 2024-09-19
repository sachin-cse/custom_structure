<?php
defined('BASE') OR exit('No direct script access allowed.');
class CommunicationModel extends ContentModel
{    
    function newContact($params){
        return $this->insertQuery(TBL_CONTACT, $params);
	}
    
    function getEmailBodyById($id){
    	$ExtraQryStr = "id = ".addslashes($id);
    	return $this->selectSingle(TBL_EMAIL_BODY, "*", $ExtraQryStr);
    }
    
    function getSocialSite($ExtraQryStr, $start, $limit) {
        $ExtraQryStr .= " AND status = 'Y' ORDER BY displayOrder";
		return $this->selectMulti(TBL_SOCIAL, "*", $ExtraQryStr, $start, $limit);
	}
    
    function pageBymodule($parent_dir){
        $ENTITY         =  TBL_MENU_CATEGORY." mc JOIN ".TBL_MODULE." m ON (m.menu_id = mc.moduleId)";
        $ExtraQryStr    = "m.parent_dir = '".addslashes($parent_dir)."'";
		return $this->selectSingle($ENTITY, "mc.*", $ExtraQryStr);
    }
}
?>