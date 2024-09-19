<?php
defined('BASE') OR exit('No direct script access allowed.');
class ThemeModel extends Site
{
    function getCategoryBypermalink($permalink) {
        return $this->selectSingle(TBL_MENU_CATEGORY, "*", "permalink='".addslashes($permalink)."'");
	}
    
    function getSocialSite($ExtraQryStr, $start, $limit) {
        $ExtraQryStr .= " AND status = 'Y' ORDER BY displayOrder";
		return $this->selectMulti(TBL_SOCIAL, "*", $ExtraQryStr, $start, $limit);
	}
    
    function homeSlider($start, $limit) {
		$ExtraQryStr = "status='Y' ORDER BY displayOrder";
        return $this->selectMulti(TBL_SLIDER, "*", $ExtraQryStr, $start, $limit);
	}
    
    function menuByCategoryId($categoryId) {
        $ENTITY         = TBL_MENU_CATEGORY." tmc LEFT JOIN ".TBL_MODULE." tm ON (tmc.moduleId = tm.menu_id)";
		$ExtraQryStr    = "tmc.categoryId = ".addslashes($categoryId)." AND tmc.status = 'Y' AND tmc.hiddenMenu = 'N'";

		return $this->selectSingle($ENTITY, "tmc.*, tm.menu_name", $ExtraQryStr); 	
	}
    
    function getNav() {
        $ExtraQryStr = "name = 'nav'";
		return $this->selectSingle(TBL_SETTINGS, "*", $ExtraQryStr);
    }
    
    function buildTree(array $elements, $parentId = 0) {
        $branch = array();

        foreach ($elements as $element) {
            
            if ($element['parentId'] == $parentId) {
                
                $children = $this->buildTree($elements, $element['categoryId']);
                
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }

        return $branch;
    }

    function settings($name) {
        $ExtraQryStr = "name = '".addslashes($name)."'";
		return $this->selectSingle(TBL_SETTINGS, "value", $ExtraQryStr);
    }
}
?>