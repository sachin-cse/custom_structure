<?php 
defined('BASE') OR exit('No direct script access allowed.');
class SitepageModel extends Site
{
    function getMenu() {
		$ExtraQryStr = "status = 'Y' AND hiddenMenu='N' AND parentId=0 ORDER BY displayOrder";
		return $this->selectMulti(TBL_MENU_CATEGORY, "*", $ExtraQryStr, 0, 100); 
	}
	
	function getMenuByparentId($parentId)
	{
        return $this->selectMulti(TBL_MENU_CATEGORY, "*", "status = 'Y' AND hiddenMenu='N' AND parentId=".addslashes($parentId)." ORDER BY displayOrder", 0, 50); 
	}

	function getSubTopMenuByparentId($parentId)
	{
        return $this->selectMulti(TBL_MENU_CATEGORY, "*", "status = 'Y' AND hiddenMenu='N' AND parentId=".addslashes($parentId)." AND isTopMenu='Y' ORDER BY displayOrder", 0, 50); 
	}

	function getMenuByparentIdAndsiteId($parentId)
	{
        return $this->selectMulti(TBL_MENU_CATEGORY, "*", "status = 'Y' AND hiddenMenu='N' AND parentId=".addslashes($parentId)." ORDER BY displayOrder", 0, 50);
	}

	function  getCategoryBycategoryId($categoryId)
	{
        return $this->selectSingle(TBL_MENU_CATEGORY, "*", "categoryId='".addslashes($categoryId)."' AND status='Y' AND hiddenMenu='N'");
	}

	function  getCategoryBycategoryName($categoryName)
	{
        return $this->selectSingle(TBL_MENU_CATEGORY, "*", "categoryName='".addslashes($categoryName)."' AND status='Y' AND hiddenMenu='N'");
	}

	function  getCategoryBycategoryNameAndparentId($categoryName, $parentId)
	{
        return $this->selectSingle(TBL_MENU_CATEGORY, "*", "categoryName='".addslashes($categoryName)."' AND parentId=".addslashes($parentId)." AND status='Y' AND hiddenMenu='N'", 0, 50);
	}

	function getCategoryBypermalink($permalink)
	{
        return $this->selectSingle(TBL_MENU_CATEGORY, "*", "permalink='".addslashes($permalink)."'");
	}
	/*****/
	function getCategoryIdByModuleId($moduleId)
	{
		return $this->selectSingle(TBL_MENU_CATEGORY, "*", "moduleId='".addslashes($moduleId)."'");
	}

	function  getCategoryByPermalinkAndparentId($permalink, $parentId) {
        return $this->selectSingle(TBL_MENU_CATEGORY, "*", "permalink='".addslashes($permalink)."' AND parentId=".addslashes($parentId)." AND status='Y' AND hiddenMenu='N'", 0, 50);
	}
	
	function getPageBypermalink($permalink) {
		$ENTITY = TBL_MENU_CATEGORY." mc LEFT JOIN ".TBL_MODULE." m ON (mc.moduleId = m.menu_id AND m.status = 'Y')";
		$fieldList = "mc.*, m.parent_dir, m.isDefault";
	
		$CLAUSE = "mc.permalink = BINARY '".addslashes($permalink)."' AND mc.status = 'Y'";
	
		return $this->selectSingle($ENTITY, $fieldList, $CLAUSE);
	}
}
?>