<?php
class Modules extends Site
{
    private $loadIn;
    
    public function __construct($loadIn = '') {
        $this->loadIn = $loadIn;
    }
    
    function menuByid($menu_id) {
		$ExtraQryStr = "menu_id = ".addslashes($menu_id);
		return $this->selectSingle(TBL_MODULE, "*", $ExtraQryStr); 	
	}
	
	function getModules($parent_id = 0) {		
		$ExtraQryStr = "status = 'Y' AND parent_id = ".addslashes($parent_id)." ORDER BY menu_id";
		return $this->selectMulti(TBL_MODULE, "menu_id, menu_name, menu_image, parent_dir, child_dir, isDefault", $ExtraQryStr, 0, 100);
	}
    
    function getPermittedModules($permittedIds) {		
		$ExtraQryStr = "status = 'Y' AND parent_id = 0 AND menu_id in (".$permittedIds.") AND menu_id != 82 ORDER BY isDefault DESC, displayOrder";
		return $this->selectMulti(TBL_MODULE, "menu_id, menu_name, menu_image, parent_dir, child_dir, isDefault", $ExtraQryStr, 0, 100);
	}
    
    function getPermittedSubModules($parent_id, $permittedIds) {		
		$ExtraQryStr = "status = 'Y' AND parent_id = ".addslashes($parent_id)." AND menu_id IN (".$permittedIds.") ORDER BY displayOrder";
		return $this->selectMulti(TBL_MODULE, "menu_id, menu_name, menu_image, parent_dir, child_dir, isDefault", $ExtraQryStr, 0, 100);
	}
    
    function moduleNavigation($permission){
        $navigationArray    = array();
        $navigationArray    = $this->getPermittedModules($permission);
        
        $permissionArray    = explode(',', $permission);
        
        if(in_array(1, $permissionArray)) // 1 => CMS
            $pagesModel = new PagesModel;
        
        foreach($navigationArray as $key => $module) {
            if($module['menu_id'] == 1){
                $navigationArray[$key]['children']   = $pagesModel->activeParentCMSpages();
            }
            else
                $navigationArray[$key]['children']   = $this->getPermittedSubModules($module['menu_id'], $permission); 
            
        }
        
        return $navigationArray;
    }
    
    function getAllModules() {		
		$ExtraQryStr = "status = 'Y' ORDER BY menu_id, displayOrder";
		$modules = $this->selectAll(TBL_MODULE, "menu_id, parent_id, menu_name, menu_image, parent_dir, child_dir, isDefault", $ExtraQryStr);
        
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
    
    function autoloadClasses() {
        $modules    = $this->getAllModules();
        
        foreach($modules as $module) {
            $dbArr = [];
            
            
            $modDir      = $module['parent_dir'];
            $classDir    = (isset($this->loadIn) && $this->loadIn == 'admin')? ADMIN_CLASS:CLASS_PATH; 
            
            $modPath     = ($module['isDefault'])? MODULE_PATH:PLUGINS_PATH;
            
            $modBasePath = ROOT_PATH . DIRECTORY_SEPARATOR . $modPath . DIRECTORY_SEPARATOR . $modDir . DIRECTORY_SEPARATOR;
            
            if(file_exists($modBasePath.'config.php')){
                include($modBasePath.'config.php');
                
                foreach($dbArr as $key=>$val){
                    define($key, $val);
                }
            }
            
            $path        = $modBasePath . $classDir . DIRECTORY_SEPARATOR;
            
            $ClassController    = ucwords(strtolower($modDir)).'Controller.php';
                    
            if(file_exists($path.$ClassController))
                include($path.$ClassController);
            
            $ClassModel    = ucwords(strtolower($modDir)).'Model.php';
                    
            if(file_exists($path.$ClassModel))
                include($path.$ClassModel);
            
            $childModules       = $module['children'];
            foreach($childModules as $child) {
                    
                if(isset($child['child_dir']) && trim($child['child_dir'])) {
                    $ClassController = ucwords(strtolower($child['child_dir'])).'Controller.php';
                    
                    if(file_exists($path.$ClassController))
                        include($path.$ClassController);
                    
                    
                    $ClassModel = ucwords(strtolower($child['child_dir'])).'Model.php';
                    
                    if(file_exists($path.$ClassModel))
                        include($path.$ClassModel);
                }
            }
        }
    }
}
?>