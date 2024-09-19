<?php
defined('BASE') OR exit('No direct script access allowed.');
class MenuModel extends Site
{
	function getModulePages() {
        
        $notPageModule = array(1, 3, 99, 339, 523); // CMS, SEO, SITEPAGE, THEME, PWA
        
		$ExtraQryStr = "status = 'Y' AND menu_id not in (".implode(',', $notPageModule).") ORDER BY displayOrder, menu_id";
		$modules = $this->selectMulti(TBL_MODULE, "menu_id, parent_id, menu_name, menu_image, parent_dir, child_dir, isDefault", $ExtraQryStr, 0, 100);
        
        $tree = $this->buildModuleTree($modules);

        return $tree;
	}
    
    function buildModuleTree(array $elements, $parentId = 0) {
        $branch = array();

        foreach ($elements as $element) {
            
            if ($element['parent_id'] == $parentId) {
                
                $children = $this->buildModuleTree($elements, $element['menu_id']);
                
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }

        return $branch;
    }
    
    function getCMSMenuByparentId($parentId) {
		$ExtraQryStr = "status = 'Y' AND parentId = ".addslashes($parentId)." AND moduleId = 0 ORDER BY displayOrder";
		return $this->selectMulti(TBL_MENU_CATEGORY, "*", $ExtraQryStr, 0, 100); 
	}
	
	function getMemuByModuleId($moduleId) {
		$ExtraQryStr = "moduleId = ".addslashes($moduleId)." AND status = 'Y' ORDER BY displayOrder";
		return $this->selectMulti(TBL_MENU_CATEGORY, "*", $ExtraQryStr, 0, 50);
	}
    
    function cmsMenuPages() {
        $ExtraQryStr = "status = 'Y' AND hiddenMenu = 'N' AND moduleId = 0 ORDER BY displayOrder";
        $menu = $this->selectMulti(TBL_MENU_CATEGORY, "*", $ExtraQryStr, 0, 100); 
        
        $tree = $this->buildTree($menu);
        
        return $tree;
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
    
    function getMenu() {
        $ENTITY         = TBL_MENU_CATEGORY." tmc LEFT JOIN ".TBL_MODULE." tm ON (tmc.moduleId = tm.menu_id)";
		$ExtraQryStr = "tmc.status = 'Y' AND tmc.hiddenMenu = 'N' ORDER BY tmc.displayOrder";
		$menu = $this->selectMulti($ENTITY, "tmc.*, tm.menu_name", $ExtraQryStr, 0, 100); 
        
        $tree = $this->buildTree($menu);
        
        return $tree;
	}
    
    function menuUpdateByCategoryId($params, $categoryId) {
        $CLAUSE = "categoryId = ".addslashes($categoryId);
        return $this->updateQuery(TBL_MENU_CATEGORY, $params, $CLAUSE);
    }
	
	function menuByCategoryId($categoryId) {
        $ENTITY         = TBL_MENU_CATEGORY." tmc LEFT JOIN ".TBL_MODULE." tm ON (tmc.moduleId = tm.menu_id)";
		$ExtraQryStr    = "tmc.categoryId = ".addslashes($categoryId)." AND tmc.status = 'Y' AND tmc.hiddenMenu != 'Y'";

		return $this->selectSingle($ENTITY, "tmc.*, tm.menu_name", $ExtraQryStr); 	
	}
    
    function checkNavExistance() {
        $ExtraQryStr = "name = 'nav'";
        return $this->rowCount(TBL_SETTINGS, 'id', $ExtraQryStr);
    }
    
    function checkPageExistence($ExtraQryStr) {        
        return $this->selectSingle(TBL_MENU_CATEGORY, "categoryId", $ExtraQryStr);
    }
    
    function newCategory($params) {
        return $this->insertQuery(TBL_MENU_CATEGORY, $params);
	}
    
    function getNav() {
        $ExtraQryStr = "name = 'nav'";
		return $this->selectSingle(TBL_SETTINGS, "*", $ExtraQryStr);
    }
    
    function newNav($params) {
        return $this->insertQuery(TBL_SETTINGS, $params);
    }
    
    function updateNav($params) {
        $CLAUSE = "name = 'nav'";
        return $this->updateQuery(TBL_SETTINGS, $params, $CLAUSE);
    }
    
    /*function deleteNav($id) {
        return $this->executeQuery("DELETE FROM ".TBL_SETTINGS." WHERE id = ".addslashes($id));
    }*/
}
?>