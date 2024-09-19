<?php
defined('BASE') OR exit('No direct script access allowed.');
class TitlemetaModel extends Site
{
    function titleMetaById($titleandMetaId) {
		$ExtraQryStr = "titleandMetaId = ".addslashes($titleandMetaId);
		return $this->selectSingle(TBL_TITLE_AND_META, "*", $ExtraQryStr);
	}
    
    function titleMetaByUrl($titleandMetaUrl) {
		$ExtraQryStr = "titleandMetaUrl = '".addslashes($titleandMetaUrl)."'";
		return $this->selectSingle(TBL_TITLE_AND_META, "*", $ExtraQryStr);
	}
    
    function getPageTitleandMetaBysiteId($siteId, $ExtraQryStr, $start, $limit) {
		$ExtraQryStr .= " AND siteId = ".addslashes($siteId);
		return $this->selectMulti(TBL_TITLE_AND_META, "*", $ExtraQryStr, $start, $limit);
	}
    
    function countPageTitleandMeta($siteId, $ExtraQryStr) {
        $ExtraQryStr .= " AND siteId = ".addslashes($siteId);
        return $this->rowCount(TBL_TITLE_AND_META, 'titleandMetaId', $ExtraQryStr);
    }
    
    function newTitleMeta($params) {
        return $this->insertQuery(TBL_TITLE_AND_META, $params);
	}
    
    function titleMetaUpdateById($params, $titleandMetaId) {
        $CLAUSE = "titleandMetaId = ".addslashes($titleandMetaId);
        return $this->updateQuery(TBL_TITLE_AND_META, $params, $CLAUSE);
    }
    
    function deleteTitleMetaById($titleandMetaId) {
        return $this->executeQuery("DELETE FROM ".TBL_TITLE_AND_META." WHERE titleandMetaId = ".$titleandMetaId);
    }
}
?>