<?php
defined('BASE') OR exit('No direct script access allowed.');
class SocialModel extends Site
{
    function checkExistence($ExtraQryStr) {
        return $this->selectSingle(TBL_SOCIAL, "*", $ExtraQryStr);
    }
    
    function newSocialSite($params){
        return $this->insertQuery(TBL_SOCIAL, $params);
	}
    
    function socialSiteById($id){
		$ExtraQryStr = " id = ".addslashes($id);
		return $this->selectSingle(TBL_SOCIAL, "*", $ExtraQryStr); 
	}
    
    function getSocialSite($ExtraQryStr, $start, $limit){
        $ExtraQryStr .= " ORDER BY displayOrder";
		return $this->selectMulti(TBL_SOCIAL, "*", $ExtraQryStr, $start, $limit);
	}
    
    function socialSiteCount($ExtraQryStr){
    	return $this->rowCount(TBL_SOCIAL, 'id', $ExtraQryStr);
    }
    
    function socialSiteUpdateById($params, $id){
    	$CLAUSE = "id = ".addslashes($id);
    	return $this->updateQuery(TBL_SOCIAL, $params, $CLAUSE);
    }
    
    function deleteSocialSiteById($id){
        return $this->executeQuery("DELETE FROM ".TBL_SOCIAL." WHERE id = ".($id));
    }
}
?>