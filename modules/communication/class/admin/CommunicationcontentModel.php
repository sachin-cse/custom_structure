<?php
defined('BASE') OR exit('No direct script access allowed.');
class CommunicationcontentModel extends ContentModel
{
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
}
?>