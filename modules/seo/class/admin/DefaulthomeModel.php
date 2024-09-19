<?php
defined('BASE') OR exit('No direct script access allowed.');
class DefaulthomeModel extends Site
{    
    function getDefaultTitleAndMeta($siteId) {
        $ExtraQryStr = "siteId = ".addslashes($siteId)." AND titleandMetaType = 'D'";
        return $this->selectSingle(TBL_TITLE_AND_META, "*", $ExtraQryStr);
	}
    
    public static function defaultMetaData($siteId) {
        $siteObj = new Site;
        $ExtraQryStr = "siteId = ".addslashes($siteId)." AND titleandMetaType = 'D'";
        return $siteObj->selectSingle(TBL_TITLE_AND_META, "*", $ExtraQryStr);
	}
    
    function getHomeTitleAndMeta($siteId) {
        $ExtraQryStr = "siteId = ".addslashes($siteId)." AND titleandMetaType = 'H'";
        return $this->selectSingle(TBL_TITLE_AND_META, "*", $ExtraQryStr);
	}
    
    function newTitleMeta($params) {
        return $this->insertQuery(TBL_TITLE_AND_META, $params);
	}
    
    function titleMetaUpdateById($params, $titleandMetaId) {
        $CLAUSE = "titleandMetaId = ".addslashes($titleandMetaId);
        return $this->updateQuery(TBL_TITLE_AND_META, $params, $CLAUSE);
    }
}
?>