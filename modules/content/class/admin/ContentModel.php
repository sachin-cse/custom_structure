<?php
defined('BASE') OR exit('No direct script access allowed.');
class ContentModel extends Site 
{   
    function getDisplayOrder($menucategoryId = 0, $orderBy = 'T'){
        $ENTITY         = TBL_CONTENT;
        
        $ExtraQryStr    = ($menucategoryId)? "menucategoryId = ".addslashes($menucategoryId):'';
        
        if($orderBy == 'T')
            $str = 'MIN(displayOrder) displayOrder';
        elseif($orderBy == 'B')
            $str = 'MAX(displayOrder) displayOrder';
        else
            return;
        
		return $this->selectSingle($ENTITY, $str, $ExtraQryStr, $start, $limit); 
    }
    
    function checkExistence($ExtraQryStr) {        
        return $this->rowCount(TBL_CONTENT, "contentID", $ExtraQryStr);
    }
    
    function newContent($params) {
        return $this->insertQuery(TBL_CONTENT, $params);
	}
    
    function content_details($ExtraQryStr) {	
        return $this->selectMulti(TBL_CONTENT, "*", $ExtraQryStr, 0, 50); 
	}
	
	function categoryById($categoryId) {
		$ExtraQryStr = "categoryId=".addslashes($categoryId);
		return $this->selectSingle(TBL_MENU_CATEGORY, "*", $ExtraQryStr); 	
	}
	
	function categoryByparentId($parentId) {
		$ExtraQryStr = "parentId=".addslashes($parentId)." and status='Y'";
        return $this->selectMulti(TBL_MENU_CATEGORY, "*", $ExtraQryStr, 0, 50); 
	}
	
    function cmsCategoryByparentId($parentId) {
		$ExtraQryStr = "parentId=".addslashes($parentId)." and status='Y' and moduleId=0";
        return $this->selectMulti(TBL_MENU_CATEGORY, "*", $ExtraQryStr, 0, 50); 
	}
    
    function getCategory($ExtraQryStr){
		$ExtraQryStr .= " ORDER BY displayOrder";
		return $this->selectMulti(TBL_MENU_CATEGORY, "*", $ExtraQryStr, 0, 100); 	
	}
    
	function getContentBycontentID($contentID) {
		$ExtraQryStr = "contentID=".addslashes($contentID);
		return $this->selectSingle(TBL_CONTENT, "*", $ExtraQryStr); 	
	}
	
	function getContentBymenucategoryId($menucategoryId) {
		$ExtraQryStr = "menucategoryId=".addslashes($menucategoryId);
		return $this->selectSingle(TBL_CONTENT, "*", $ExtraQryStr);	
	}
    
    function contentCountBymenucategoryId($menucategoryId, $ExtraQryStr){
        
        if($menucategoryId == 'uncategorized')
            $ExtraQryStr .= " AND menucategoryId NOT IN (SELECT categoryId from ".TBL_MENU_CATEGORY.")";
        else
            $ExtraQryStr .= " AND menucategoryId=".addslashes($menucategoryId);
        
        return $this->rowCount(TBL_CONTENT, 'contentId', $ExtraQryStr);
	}
	
	function getContentListBymenucategoryId($menucategoryId, $ExtraQryStr, $start, $limit) {
        
        if($menucategoryId == 'uncategorized')
            $ExtraQryStr .= " AND menucategoryId NOT IN (SELECT categoryId from ".TBL_MENU_CATEGORY.")";
        else
            $ExtraQryStr .= " AND menucategoryId=".addslashes($menucategoryId)." ORDER BY displayOrder";
        
        return $this->selectMulti(TBL_CONTENT, "*", $ExtraQryStr, $start, $limit);
	}
	
	function getContentListBymenucategoryIds($menucategoryIds, $start = 0, $limit = VALUE_PER_PAGE) {
		$ExtraQryStr = "menucategoryId in (".addslashes($menucategoryIds).")";
		return $this->selectMulti(TBL_CONTENT, "*", $ExtraQryStr, $start, $limit);
	}
    
    function deleteGalleryByid($id) {
        return $this->executeQuery("delete from ".TBL_GALLERY." where id = ".addslashes($id));
    }
    
    function deleteContentByid($contentID) {
        return $this->executeQuery("delete from ".TBL_CONTENT." where contentID = ".addslashes($contentID));
    }
    
    function contentUpdateBycontentID($params, $contentID) {
        $CLAUSE = "contentID = ".addslashes($contentID);
        return $this->updateQuery(TBL_CONTENT, $params, $CLAUSE);
    }
}
?>