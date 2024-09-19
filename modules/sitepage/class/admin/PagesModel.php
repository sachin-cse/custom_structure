<?php
defined('BASE') OR exit('No direct script access allowed.');
class PagesModel extends Site
{    
    function checkExistence($ExtraQryStr) {        
        return $this->rowCount(TBL_MENU_CATEGORY, "categoryId", $ExtraQryStr);
    }
    
    function newCategory($params) {
        return $this->insertQuery(TBL_MENU_CATEGORY, $params);
	}
	
	function categoryById($categoryId) {
		$ExtraQryStr = "categoryId = ".addslashes($categoryId);
		return $this->selectSingle(TBL_MENU_CATEGORY, "*", $ExtraQryStr); 	
	}
	
	function sublinksCountById($categoryId) {
        return $this->rowCount(TBL_MENU_CATEGORY, 'categoryId', "parentId = ".addslashes($categoryId)); 
	}
	
	function categoryCount($ExtraQryStr) {
        $ENTITY = TBL_MENU_CATEGORY." pm LEFT JOIN ".TBL_MENU_CATEGORY." sm ON (pm.categoryId = sm.parentId)";
        return $this->rowCount($ENTITY, 'pm.categoryId', $ExtraQryStr);
	}
    
	function getCategory($ExtraQryStr, $start, $limit) {
        $ENTITY = TBL_MENU_CATEGORY." pm LEFT JOIN ".TBL_MENU_CATEGORY." sm ON (pm.categoryId = sm.parentId) LEFT JOIN ".TBL_MODULE." m ON (m.menu_id = pm.moduleId)";
        $fields = "pm.*, COUNT(sm.categoryId) subCount, m.menu_name";
		$ExtraQryStr .= " GROUP BY pm.categoryId ORDER BY pm.displayOrder";
		return $this->selectMulti($ENTITY, $fields, $ExtraQryStr, $start, $limit); 	
	}
	
    function activeParentCMSpages() {
		$ExtraQryStr = "parentId = 0 AND moduleId = 0 AND status = 'Y' AND hiddenMenu = 'N' ORDER BY displayOrder";
		return $this->selectMulti(TBL_MENU_CATEGORY, "categoryId, categoryName, permalink", $ExtraQryStr, 0, 100); 	
	}
    
	function getAllCategory() {		
		return $this->selectMulti(TBL_MENU_CATEGORY, "*", "1", 0, 999); 
	}
	
	function getMenuByparentId($parentId) {
		$ExtraQryStr = "status = 'Y' AND parentId = ".addslashes($parentId)." ORDER BY displayOrder";
		return $this->selectMulti(TBL_MENU_CATEGORY, "*", $ExtraQryStr, 0, 100); 
	}
    
	function getCMSMenuByparentId($parentId) {
		$ExtraQryStr = "status = 'Y' AND parentId = ".addslashes($parentId)." AND moduleId = 0 ORDER BY displayOrder";
		return $this->selectMulti(TBL_MENU_CATEGORY, "*", $ExtraQryStr, 0, 100); 
	}
    
    function categoryUpdateBycategoryId($params, $categoryId) {
        $CLAUSE = "categoryId = ".addslashes($categoryId);
        return $this->updateQuery(TBL_MENU_CATEGORY, $params, $CLAUSE);
    }
    
    function deleteMenuBycategoryId($categoryId) {
        return $this->executeQuery("DELETE FROM ".TBL_MENU_CATEGORY." WHERE categoryId = ".addslashes($categoryId));
    }
    
    function deleteMenuByparentId($parentId) {
        return $this->executeQuery("DELETE FROM ".TBL_MENU_CATEGORY." WHERE parentId = ".addslashes($parentId));
    }
    
    function settings($name) {
        $ExtraQryStr = "name = '".addslashes($name)."'";
		return $this->selectSingle(TBL_SETTINGS, "value", $ExtraQryStr);
    }
}
?>