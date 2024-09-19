<?php 
class ModulesController extends REST
{
    private    $model;
	protected  $response = array();
	
    public function __construct($model = 'ModulesModel') {
    	parent       :: __construct();
        $this->model = new $model;
    }
    
    function index() {

        $ExtraQryStr                = 1;
        
        
        if($this->_request['editid'] || isset($act['editid']) || $this->_request['dtaction'] == 'add') {
            $this->response['modules']  = $this->model->getParentModules($ExtraQryStr, 0, 100);
            
            $editid = ($this->_request['editid'])? $this->_request['editid']:$act['editid'];
            
            if($editid)
                $this->response['module']           = $this->model->moduleByid($editid);
        } else {

            if(isset($this->_request['parent_id']) && $this->_request['parent_id']!='') {
                
                $this->response['parentModule']     = $this->model->moduleByid($this->_request['parent_id']);
                $this->response['modules']          = $this->model->getSubModules($this->_request['parent_id'], $ExtraQryStr, 0, 100);
            }
            else
                $this->response['modules']  = $this->model->getParentModules($ExtraQryStr, 0, 100);
        }
        
        return $this->response;
    }
    
    function addEditModule() {
        
        $actMsg['type']           = 0;
        $actMsg['message']        = '';
        
        $parent_id                = trim($this->_request['parent_id']);
        $parent_id                = ($parent_id) ? $parent_id : 0;
        $menu_name                = trim($this->_request['menu_name']);
        $displayOrder             = trim($this->_request['displayOrder']);
        $displayOrder             = ($displayOrder) ? $displayOrder : 0;
        
        if($menu_name != '') {

            if($this->_request['IdToEdit'] != '')
                $ExtraString = ' menu_id != '.addslashes($this->_request['IdToEdit']);
            else
                $ExtraString = 1;

            $exist  = $this->model->checkExistence("menu_name = '".addslashes($menu_name)."' AND parent_id = ".addslashes($parent_id)." AND ".$ExtraString);

            if(!$exist) {
                
                $params                     = array();
                $params['parent_id']        = $parent_id;
                $params['menu_name']        = $menu_name;
                $params['displayOrder']     = $displayOrder;
                $params['isDefault']        = 1;

                if($this->_request['IdToEdit'] != '') {
                    
                    $this->model->moduleUpdateBymoduleId($params, $this->_request['IdToEdit']);
                    
                    $actMsg['editid']         = $this->_request['IdToEdit'];
                    $actMsg['type']           = 1;
                    $actMsg['message']        = 'Data updated successfully.';
                    
                } 
                
                else {
                    
                    /*---------------------------------------------------------------------------------------------------------
                    Generating Module Path:
                    For sub module, parent_dir will be the 
                    path of the parent module and child_dir will be the
                    path of the sub module
                    ---------------------------------------------------------------------------------------------------------*/
                    if($parent_id) {
                        $mData              = $this->model->moduleByid($parent_id);
                        $parent_dir         = $mData['parent_dir'];
                        $child_dir          = $this->moduleDirFile($menu_name, $parent_dir);
                    }
                    else {
                        $parent_dir         = $this->moduleDirFile($menu_name);
                        $child_dir          = '';
                    }
                    
                    $params['parent_dir']   = $parent_dir;
                    $params['child_dir']    = $child_dir;
                    
                    $actMsg['editid']       = $this->model->newModule($params);
                    $actMsg['message']      = 'Data inserted successfully.';
                    $actMsg['type']         = 1;

                    /*---------------------------------------------------------------------------------------------------------
                    Update user permission
                    ---------------------------------------------------------------------------------------------------------*/
                    $this->permissionUpdate();
                }
                
                if($_FILES['ImageName']['name'] && substr($_FILES['ImageName']['type'], 0, 5) == 'image') {
                    $fObj = new FileUpload;
                    
                    $TWH[0]         = 20;       // thumb width
                    $TWH[1]         = 20;       // thumb height
                    $LWH[0]         = 80;       // large width
                    $LWH[1]         = 80;       // large height

                    $mData = $this->model->moduleByid($actMsg['editid']);

                    if($mData['menu_image']) {
                        @unlink(MEDIA_MODULE_ROOT.'/normal/'.$mData['menu_image']);
                        @unlink(MEDIA_MODULE_ROOT.'/large/'.$mData['menu_image']);
                        @unlink(MEDIA_MODULE_ROOT.'/thumb/'.$mData['menu_image']);
                    }

                    $fileName = str_replace(' ', '', strtolower(trim($menu_name)));

                    if($target_image = $fObj->uploadImage($_FILES['ImageName'], MEDIA_MODULE_ROOT, $fileName, $TWH, $LWH)) {
                        $params                 = array();
                        $params['menu_image']   = $target_image;
                        $this->model->moduleUpdateBymoduleId($params, $actMsg['editid']);
                    }
                }
            }
            else
                $actMsg['message'] = 'Module already exists.';
        }
        else
            $actMsg['message'] = 'Fields marked with (*) are mandatory.';
        
        return $actMsg;
    }
    
    function uploadPlugin() {
        $actMsg['type']         = 0;
        $actMsg['message']      = '';
        
        $plugin                 = trim($this->_request['plugin']);
        $theme                  = trim($this->_request['theme']);
        
        if($plugin != '') {
            
            $pluginLoc  = PLUGINS.DIRECTORY_SEPARATOR;
            $pluginDir  = PLUGINS.DIRECTORY_SEPARATOR.$plugin;
            
            $pluginFile = PLUGINS_REPO.$plugin.DIRECTORY_SEPARATOR.$theme.DIRECTORY_SEPARATOR.'archive.zip';
            
            if(file_exists($pluginFile)) {
                
                if(!is_dir($pluginDir)) {
                    
                    $zip = new ZipArchive;
                    $res = $zip->open($pluginFile);

                    if ($res === TRUE) {

                        $zip->extractTo($pluginLoc);
                        $zip->close();

                        $helperFile = $pluginDir.DIRECTORY_SEPARATOR.'helper.json';

                        if(file_exists($helperFile)) {

                            $json           = file_get_contents($helperFile);

                            if($this->isJSON($json)) {

                                $helperRepo = json_decode($json, true);

                                $dbFile     = $pluginDir.DIRECTORY_SEPARATOR.$helperRepo['db'];

                                if(file_exists($dbFile)) {

                                    $templine = '';
                                    // Read in entire file
                                    $lines = file($dbFile);
                                    // Loop through each line
                                    foreach ($lines as $line) {
                                        // Skip it if it's a comment
                                        if (substr($line, 0, 2) == '--' || $line == '')
                                            continue;

                                        // Add this line to the current segment
                                        $templine .= $line;
                                        // If it has a semicolon at the end, it's the end of the query
                                        if (substr(trim($line), -1, 1) == ';') {
                                            $runQuery = $this->model->importSql($templine);
                                            // Reset temp variable to empty
                                            $templine = '';
                                        }
                                    }

                                    @unlink($dbFile);
                                }

                                $params                     = [];
                                $params['parent_id']        = 0;
                                $params['menu_name']        = $helperRepo['name'];
                                $params['menu_image']       = ($helperRepo['icon'])? $helperRepo['icon']:'';
                                $params['parent_dir']       = $helperRepo['path'];
                                $params['menu_description'] = $helperRepo['description'];
                                $params['isDefault']        = 1;

                                $pId = $this->model->newModule($params);

                                foreach($helperRepo['sub'] as $helper) {

                                    $paramsSub                  = [];
                                    $paramsSub['parent_id']     = $pId;
                                    $paramsSub['menu_name']     = $helper['name'];
                                    $paramsSub['menu_image']    = ($helper['icon'])? $helper['icon']:'';
                                    $paramsSub['parent_dir']    = $helperRepo['path'];
                                    $paramsSub['child_dir']     = $helper['path'];
                                    $paramsSub['isDefault']     = 1;

                                    $sId = $this->model->newModule($paramsSub);
                                }

                                @unlink($helperFile);

                                $this->permissionUpdate();

                                $actMsg['type']           = 1;
                                $actMsg['message']        = 'Plugin installed successfully.';
                            }
                            else {
                                $this->model->rrmdir($pluginDir);
                                $actMsg['message']        = 'Invalid helper.json file.';
                            }
                        }
                        else {
                            $this->model->rrmdir($pluginDir);
                            $actMsg['message']        = 'Missing helper.json file.';
                        }

                    } 
                    else
                        $actMsg['message']        = 'Invalid archive file.';

                }
                else
                    $actMsg['message']        = 'Plugin already exists.';
            }
            else
                $actMsg['message']        = 'Plugin does not exist.';
                
        }
        else
            $actMsg['message']        = 'Please select a plugin to install.';
        
        return $actMsg;
    }
    
    function multiAction() {
        $actMsg['type']           = 0;
        $actMsg['message']        = '';
        
        foreach($this->_request['selectMulti'] as $val) {
            
            $params = array();
            
            switch($this->_request['multiAction']) {
                case "1":
                    $params['status'] = 'Y';
                    break;
                case "2":
                    $params['status'] = 'N';
                    break;
                case "3":
                    $params['delete'] = 'Y';
                    break;
                default:
                    $this->response('', 406);
            }
            
            if($params['delete'] == 'Y') {
                $del = $this->model->deleteModuleBymoduleId($val); // this function deletes child (if any) modules as well.
                
                /*---------------------------------------------------------------------------------------------------------
                permission update
                ---------------------------------------------------------------------------------------------------------*/
                $this->permissionUpdate();
            }
            else
                $this->model->moduleUpdateBymoduleId($params, $val);
            
            $actMsg['type']           = 1;
            $actMsg['message']        = 'Operation successful.';
        }
        return $actMsg;
    }
    
    function permissionUpdate() {
        
        $mids               = $this->model->getAllModuleIds();
        $permArray          = array();

        foreach($mids as $pmid) {
            $permArray[]    = (int)trim($pmid['menu_id']);
        }

        $permString         = implode(',', $permArray);
        
        $permissionParam                = array();
        $permissionParam['permission']  = $permString;

        $update = $this->model->userUpdate($permissionParam, $this->session->read('UID'));
        
        $selData = $this->model->getUserByid($this->session->read('UID'));
        
        
        $this->session->write('PERMISSION', $selData['permission']);
        
        
        //echo $selData['permission'].'<-from db<br>';
        //echo $this->session->read('PERMISSION').'<-from ses<br>';
    }
    
    function swap() {
        $actMsg['type']             = 0;
        $actMsg['message']          = '';
        
        $listingCounter = 1;
        
        foreach ($this->_request['recordsArray'] as $recordID) {
            $params = array();
            $params['displayOrder'] = $listingCounter;
            $this->model->moduleUpdateBymoduleId($params, $recordID);
            $listingCounter = $listingCounter + 1;
        }
        
        if($listingCounter > 1){
            $actMsg['type']             = 1;
            $actMsg['message']          = 'Operation successful.';
        }
        
        return $actMsg;
    }
    
    function moduleDirFile($string, $parent = '') {
        
        $string     = strtolower($string);                              // Converts into lower case.
        $string     = str_replace(' ', '', $string);                    // Replaces all spaces with none.
        $string     = preg_replace('/[^A-Za-z0-9\-]/', '', $string);    // Removes special chars.
        
        $model      = 'model';
        $act        = 'act';
        $pageview   = 'pageview';
        $response   = 'response';
        $pageData   = 'pageData';
        
        if($parent) {
            $dirClassPath   = MODULE.DIRECTORY_SEPARATOR.$parent.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'admin';
            $dirViewPath    = MODULE.DIRECTORY_SEPARATOR.$parent.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.'admin';
            
            $Controller     = ucwords($string).'Controller';
            $ControllerFile = $Controller.'.php';
            
            if(!file_exists($dirClassPath.DIRECTORY_SEPARATOR.$ControllerFile)) {
                $ControllerFile = fopen($dirClassPath.DIRECTORY_SEPARATOR.$ControllerFile, "w");
                
                fwrite($ControllerFile, "<?php defined('BASE') OR exit('No direct script access allowed.');
class ".$Controller." extends REST
{
    private    $".$model.";
    protected  $".$response." = array();

    public function __construct($".$model.") {
        parent::__construct();
        $"."this->model        = new $".$model.";
    }

    function index($".$act." = []) {

    }
}");
                fclose($ControllerFile);
            }
            
            $Model      = ucwords($string).'Model';
            $ModelFile  = $Model.'.php';
            if(!file_exists($dirClassPath.DIRECTORY_SEPARATOR.$ModelFile)) {
                $ModelFile = fopen($dirClassPath.DIRECTORY_SEPARATOR.$ModelFile, "w");

                fwrite($ModelFile,"<?php defined('BASE') OR exit('No direct script access allowed.');
class ".$Model." extends Site
{

}");
                fclose($ModelFile);
            }
            
            if(!is_dir($dirViewPath.DIRECTORY_SEPARATOR.$string)) {
                
                @mkdir($dirViewPath.DIRECTORY_SEPARATOR.$string, 0755, true);
                
                $indexFile = fopen($dirViewPath.DIRECTORY_SEPARATOR.$string.DIRECTORY_SEPARATOR.'index.php', "w");
                
                fwrite($indexFile, "<?php defined('BASE') OR exit('No direct script access allowed.');");
                fclose($indexFile);
                
                $addFile = fopen($dirViewPath.DIRECTORY_SEPARATOR.$string.DIRECTORY_SEPARATOR.'add.php', "w");
                
                fwrite($addFile, "<?php defined('BASE') OR exit('No direct script access allowed.');");
                fclose($addFile);
            }
            
        }
        else {
            $dirPath        = MODULE.DIRECTORY_SEPARATOR.$string;
            $indexSource    = MODULE.DIRECTORY_SEPARATOR.'index.php';
            
            if(!is_dir($dirPath)) {
                @mkdir($dirPath, 0755, true);
                @copy($indexSource, $dirPath.DIRECTORY_SEPARATOR.'index.php');
                
                @mkdir($dirPath.DIRECTORY_SEPARATOR.'class', 0755, true);
                @copy($indexSource, $dirPath.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'index.php');
                
                @mkdir($dirPath.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'admin', 0755, true);
                @copy($indexSource, $dirPath.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'admin'.DIRECTORY_SEPARATOR.'index.php');
                
                @mkdir($dirPath.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'front', 0755, true);
                @copy($indexSource, $dirPath.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'front'.DIRECTORY_SEPARATOR.'index.php');
                
                $Controller     = ucwords($string).'Controller';
                $ControllerFile = $Controller.'.php';
                $ControllerFile = fopen($dirPath.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'front'.DIRECTORY_SEPARATOR.$ControllerFile, "w");
                
                
                
fwrite($ControllerFile, "<?php defined('BASE') OR exit('No direct script access allowed.');
class ".$Controller." extends REST
{
    private    $".$model.";
    protected  $".$pageview.";
    protected  $".$response." = array();

    public function __construct($".$model.") {
        parent::__construct();
        $"."this->model        = new $".$model.";
    }

    function index($".$pageData." = '') {

    }
}");
                fclose($ControllerFile);
                
                $Model      = ucwords($string).'Model';
                $ModelFile  = $Model.'.php';
                $ModelFile  = fopen($dirPath.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'front'.DIRECTORY_SEPARATOR.$ModelFile, "w");
                
fwrite($ModelFile,"<?php defined('BASE') OR exit('No direct script access allowed.');
class ".$Model." extends Site
{

}");
                fclose($ModelFile);
                
                @mkdir($dirPath.DIRECTORY_SEPARATOR.'view', 0755, true);
                @copy($indexSource, $dirPath.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.'index.php');
                
                @mkdir($dirPath.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.'admin', 0755, true);
                @copy($indexSource, $dirPath.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.'admin'.DIRECTORY_SEPARATOR.'index.php');
                
                @mkdir($dirPath.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.'front', 0755, true);
            }
        }
        
        return $string;
    }
}
?>