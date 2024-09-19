<?php
defined('BASE') OR exit('No direct script access allowed.');
class TestimonialcontentModel extends Site
{
    function getDisplayOrder($menucategoryId, $orderBy = 'T'){
        $ENTITY         = TBL_CONTENT;
        $ExtraQryStr    = "menucategoryId = ".addslashes($menucategoryId);
        
        if($orderBy == 'T')
            $str = 'MIN(displayOrder) displayOrder';
        elseif($orderBy == 'B')
            $str = 'MAX(displayOrder) displayOrder';
        else
            return;
        
		return $this->selectSingle($ENTITY, $str, $ExtraQryStr, $start, $limit); 
    }
    
    function getLinkedPages($parent_dir, $start, $limit){
        $ENTITY         = TBL_MENU_CATEGORY." mc JOIN ".TBL_MODULE." m ON (m.menu_id = mc.moduleId)";
        $ExtraQryStr    = "mc.status = 'Y' AND m.parent_dir = '".addslashes($parent_dir)."' AND m.child_dir = '' ORDER BY mc.displayOrder";
        
		return $this->selectMulti($ENTITY, "mc.categoryId, mc.categoryName, mc.permalink", $ExtraQryStr, $start, $limit); 
    }
    
    function getContentList($menucategoryIds, $ExtraQryStr, $start, $limit) {
        $ENTITY       = TBL_CONTENT." tc JOIN ".TBL_MENU_CATEGORY." tmc ON (tc.menucategoryId = tmc.categoryId)";
		$ExtraQryStr .= " AND tc.menucategoryId IN (".addslashes($menucategoryIds).") ORDER BY tc.displayOrder";
		return $this->selectMulti($ENTITY, "tc.*, tmc.categoryName, tmc.permalink pagePermalink", $ExtraQryStr, $start, $limit);
	}
    
    function contentCount($menucategoryIds, $ExtraQryStr){
		$ENTITY       = TBL_CONTENT." tc JOIN ".TBL_MENU_CATEGORY." tmc ON (tc.menucategoryId = tmc.categoryId)";
		$ExtraQryStr .= " AND tc.menucategoryId IN (".addslashes($menucategoryIds).")";
        return $this->rowCount($ENTITY, 'tc.contentId', $ExtraQryStr);
	}
    
    function searchLinkedPages($mid, $parent_dir, $srch, $start, $limit) {
        
        if($mid == 0) {
            $ExtraQryStr = "mc.categoryName like '%".addslashes($srch)."%'";
            $ENTITY         = TBL_MENU_CATEGORY." mc JOIN ".TBL_MODULE." m ON (m.menu_id = mc.moduleId)";
            $ExtraQryStr    .= " AND mc.status = 'Y' AND m.parent_dir = '".addslashes($parent_dir)."' AND m.child_dir = '' ORDER BY mc.displayOrder";
            
            $data = $this->selectMulti($ENTITY, "mc.categoryId id, mc.categoryName page, mc.permalink", $ExtraQryStr, $start, $limit);
        }
        elseif($mid == 324) {            
            
            $ExtraQryStr = "c.contentHeading like '%".addslashes($srch)."%'";
            $ENTITY         = TBL_CONTENT." c JOIN ".TBL_MENU_CATEGORY." mc ON (c.menucategoryId = mc.categoryId) JOIN ".TBL_MODULE." m ON (m.menu_id = mc.moduleId)";
            $ExtraQryStr    .= " AND mc.status = 'Y' AND m.parent_dir = '".addslashes($parent_dir)."' AND m.child_dir = '' ORDER BY mc.displayOrder";
            
            
            $data = $this->selectMulti($ENTITY, "c.contentID id, c.contentHeading page, c.permalink", $ExtraQryStr, $start, $limit);
        }

		return $data;
    }

    function getContentBycontentID($contentID) {
		$ExtraQryStr = "contentID=".addslashes($contentID);
		return $this->selectSingle(TBL_CONTENT, "*", $ExtraQryStr); 	
    }
    
    function checkExistence($ExtraQryStr) {        
        return $this->rowCount(TBL_CONTENT, "contentID", $ExtraQryStr);
    }

    function deleteContentByid($contentID) {
        return $this->executeQuery("delete from ".TBL_CONTENT." where contentID = ".addslashes($contentID));
    }
    
    function contentUpdateBycontentID($params, $contentID) {
        $CLAUSE = "contentID = ".addslashes($contentID);
        return $this->updateQuery(TBL_CONTENT, $params, $CLAUSE);
    }
    function newContent($params) {
        return $this->insertQuery(TBL_CONTENT, $params);
	}
    
    function categoryById($categoryId) {
		$ExtraQryStr = "categoryId=".addslashes($categoryId);
		return $this->selectSingle(TBL_MENU_CATEGORY, "*", $ExtraQryStr); 	
	}
}
?>