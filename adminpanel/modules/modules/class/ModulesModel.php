<?php 
class ModulesModel extends Site
{
    function checkExistence($ExtraQryStr) {
        return $this->rowCount(TBL_MODULE, 'menu_id', $ExtraQryStr);
    }
    
    function moduleUpdateBymoduleId($params, $menu_id) {
        $CLAUSE = "menu_id = ".$menu_id;
        return $this->updateQuery(TBL_MODULE, $params, $CLAUSE);
    }
    
    function newModule($params) {
        return $this->insertQuery(TBL_MODULE, $params);
    }
    
    function moduleCount($ExtraQryStr) {
        $needle = 'menu_id';
		return $this->rowCount(TBL_MODULE, $needle, $ExtraQryStr);
	}
	
	function getParentModules($ExtraQryStr, $start, $limit) {
        
        $ENTITY         = TBL_MODULE." pm LEFT JOIN ".TBL_MODULE." sm ON (pm.menu_id = sm.parent_id)";
        $fields         = "pm.menu_id, pm.menu_name, pm.menu_image, pm.status, count(sm.menu_id) subCount, pm.isDefault";
        $ExtraQryStr   .= " AND pm.parent_id = 0 GROUP BY pm.menu_id HAVING isDefault = '1' ORDER BY pm.displayOrder";
        
		return $this->selectMulti($ENTITY, $fields, $ExtraQryStr, $start, $limit);
	}
    
    function getSubModules($parent_id, $ExtraQryStr, $start, $limit) {
        $ExtraQryStr .= " AND parent_id = ".addslashes($parent_id)." ORDER BY displayOrder";
		return $this->selectMulti(TBL_MODULE, "*", $ExtraQryStr, $start, $limit);
	}
    
    function getAllModuleIds(){
        return $this->selectAll(TBL_MODULE, "menu_id", 1, 0, 100);
    }
    
    function moduleByid($menu_id) {
		$ExtraQryStr = "menu_id = ".addslashes($menu_id);
		return $this->selectSingle(TBL_MODULE, "*", $ExtraQryStr);
	}
    
    function deleteModuleBymoduleId($menu_id) {
        $this->executeQuery("DELETE FROM ".TBL_MODULE." WHERE parent_id = ".addslashes($menu_id));
        $this->executeQuery("DELETE FROM ".TBL_MODULE." WHERE menu_id = ".addslashes($menu_id));
        
        return;
    }
    
    function deleteModuleByparentId($parent_Id) {
        return $this->executeQuery("DELETE FROM ".TBL_MODULE." WHERE parent_Id = ".addslashes($parent_Id));
    }
    
    function userUpdate($params, $id) {
        $CLAUSE = "id = ".addslashes($id);
        return $this->updateQuery(TBL_USER, $params, $CLAUSE);
    }
    
    function getUserByid($id) {
        $ExtraQryStr    = "id = ".addslashes($id);
        return $this->selectSingle(TBL_USER, 'permission', $ExtraQryStr);
    }
}
?>