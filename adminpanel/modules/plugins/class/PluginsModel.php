<?php 
class PluginsModel extends Site
{
    function checkExistence($ExtraQryStr) {
        return $this->rowCount(TBL_MODULE, 'menu_id', $ExtraQryStr);
    }
    
    function moduleUpdateBymoduleId($params, $menu_id) {
        $CLAUSE = "menu_id = ".$menu_id." AND isDefault='0'";
        return $this->updateQuery(TBL_MODULE, $params, $CLAUSE);
    }
    
    function newModule($params) {
        return $this->insertQuery(TBL_MODULE, $params);
    }
    
    function moduleCount($ExtraQryStr) {
        $needle = 'menu_id';
		return $this->rowCount(TBL_MODULE, $needle, $ExtraQryStr." AND isDefault='0'");
	}
	
	function getParentModules($ExtraQryStr, $start, $limit) {
        
        $ENTITY         = TBL_MODULE." pm LEFT JOIN ".TBL_MODULE." sm ON (pm.menu_id = sm.parent_id)";
        $fields         = "pm.menu_id, pm.menu_name, pm.menu_image, pm.status, count(sm.menu_id) subCount, pm.isDefault, pm.parent_dir";
        $ExtraQryStr   .= " AND pm.parent_id = 0 GROUP BY pm.menu_id HAVING isDefault='0' ORDER BY pm.displayOrder";
        
		return $this->selectMulti($ENTITY, $fields, $ExtraQryStr, $start, $limit);
	}
    
    function getSubModules($parent_id, $ExtraQryStr, $start, $limit) {
        $ExtraQryStr .= " AND parent_id = ".addslashes($parent_id)." AND isDefault='0' ORDER BY displayOrder";
		return $this->selectMulti(TBL_MODULE, "*", $ExtraQryStr, $start, $limit);
	}
    
    function getAllModuleIds(){
        return $this->selectAll(TBL_MODULE, "menu_id", "1", 0, 100);
    }
    
    function moduleByid($menu_id) {
		$ExtraQryStr = "menu_id = ".addslashes($menu_id)." AND isDefault='0'";
		return $this->selectSingle(TBL_MODULE, "*", $ExtraQryStr);
	}
    
    function deleteModuleBymoduleId($menu_id) {
        
        $modData = $this->moduleByid($menu_id);
        
        if($modData) {
        
            $this->executeQuery("DELETE FROM ".TBL_MODULE." WHERE parent_id = ".addslashes($menu_id)." AND isDefault='0'");
            $this->executeQuery("DELETE FROM ".TBL_MODULE." WHERE menu_id = ".addslashes($menu_id)." AND isDefault='0'");
            
            if($modData['parent_id'] == 0) {
                
                $pluginDir = PLUGINS.DIRECTORY_SEPARATOR.$modData['parent_dir'];
                
                if(file_exists($pluginDir.DIRECTORY_SEPARATOR.'config.php')) {
                    $dbArr = [];
                    include($pluginDir.DIRECTORY_SEPARATOR.'config.php');
                    
                    foreach($dbArr as $key=>$val){
                        $this->executeQuery("DROP TABLE ".$val);
                    }
                }
                //echo $pluginDir;
                $this->rrmdir($pluginDir);
            }
        }
        
        return;
    }
    
    function rrmdir($path) {
         // Open the source directory to read in files
            $i = new DirectoryIterator($path);
            foreach($i as $f) {
                if($f->isFile()) {
                    @unlink($f->getRealPath());
                } else if(!$f->isDot() && $f->isDir()) {
                    $this->rrmdir($f->getRealPath());
                }
            }
            @rmdir($path);
    }
    
    function deleteModuleByparentId($parent_Id) {
        return $this->executeQuery("DELETE FROM ".TBL_MODULE." WHERE parent_Id = ".addslashes($parent_Id)." AND isDefault='0'");
    }
    
    function userUpdate($params, $id) {
        $CLAUSE = "id = ".addslashes($id);
        return $this->updateQuery(TBL_USER, $params, $CLAUSE);
    }
    
    function getUserByid($id) {
        $ExtraQryStr    = "id = ".addslashes($id);
        return $this->selectSingle(TBL_USER, 'permission', $ExtraQryStr);
    }
    
    function importSql($query){
        return $this->executeQuery($query);
    }
}
?>