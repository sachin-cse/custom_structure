<?php
class PluginsController extends REST
{
    private    $model;
    protected  $response = array();

    public function __construct($model = 'ModulesModel')
    {
        parent::__construct();
        $this->model = new $model;
    }

    function index($act = [])
    {
        $ExtraQryStr                = 1;

        if ($this->_request['editid'] || isset($act['editid']) || $this->_request['dtaction'] == 'add') {
            $this->response['modules']  = $this->model->getParentModules($ExtraQryStr, 0, 100);

            $editid = ($this->_request['editid']) ? $this->_request['editid'] : $act['editid'];

            if ($editid)
                $this->response['module']           = $this->model->moduleByid($editid);
        } else {

            if (isset($this->_request['parent_id']) && $this->_request['parent_id'] != '') {

                $this->response['parentModule']     = $this->model->moduleByid($this->_request['parent_id']);
                $this->response['modules']          = $this->model->getSubModules($this->_request['parent_id'], $ExtraQryStr, 0, 100);
            } else
                $this->response['modules']  = $this->model->getParentModules($ExtraQryStr, 0, 100);
        }

        return $this->response;
    }

    function addEditModule()
    {

        $actMsg['type']           = 0;
        $actMsg['message']        = '';

        $parent_id                = trim($this->_request['parent_id']);
        $parent_id                = ($parent_id) ? $parent_id : 0;
        $menu_name                = trim($this->_request['menu_name']);
        $displayOrder             = trim($this->_request['displayOrder']);
        $displayOrder             = ($displayOrder) ? $displayOrder : 0;

        if ($menu_name != '') {

            if ($this->_request['IdToEdit'] != '')
                $ExtraString = ' menu_id != ' . addslashes($this->_request['IdToEdit']);
            else
                $ExtraString = 1;

            $exist  = $this->model->checkExistence("menu_name = '" . addslashes($menu_name) . "' AND parent_id = " . addslashes($parent_id) . " AND " . $ExtraString);

            if (!$exist) {

                $params                     = array();
                $params['parent_id']        = $parent_id;
                $params['menu_name']        = $menu_name;
                $params['displayOrder']     = $displayOrder;

                if ($this->_request['IdToEdit'] != '') {

                    $this->model->moduleUpdateBymoduleId($params, $this->_request['IdToEdit']);

                    $actMsg['editid']         = $this->_request['IdToEdit'];
                    $actMsg['type']           = 1;
                    $actMsg['message']        = 'Data updated successfully.';
                } else {

                    /*---------------------------------------------------------------------------------------------------------
                    Generating Module Path:
                    For sub module, parent_dir will be the 
                    path of the parent module and child_dir will be the
                    path of the sub module
                    ---------------------------------------------------------------------------------------------------------*/
                    if ($parent_id) {
                        $mData              = $this->model->moduleByid($parent_id);
                        $parent_dir         = $mData['parent_dir'];
                        $child_dir          = $this->moduleDirFile($menu_name, $parent_dir);
                    } else {
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

                if ($_FILES['ImageName']['name'] && substr($_FILES['ImageName']['type'], 0, 5) == 'image') {
                    $fObj = new FileUpload;

                    $TWH[0]         = 20;       // thumb width
                    $TWH[1]         = 20;       // thumb height
                    $LWH[0]         = 80;       // large width
                    $LWH[1]         = 80;       // large height

                    $mData = $this->model->moduleByid($actMsg['editid']);

                    if ($mData['menu_image']) {
                        @unlink(MEDIA_MODULE_ROOT . '/normal/' . $mData['menu_image']);
                        @unlink(MEDIA_MODULE_ROOT . '/large/' . $mData['menu_image']);
                        @unlink(MEDIA_MODULE_ROOT . '/thumb/' . $mData['menu_image']);
                    }

                    $fileName = str_replace(' ', '', strtolower(trim($menu_name)));

                    if ($target_image = $fObj->uploadImage($_FILES['ImageName'], MEDIA_MODULE_ROOT, $fileName, $TWH, $LWH)) {
                        $params                 = array();
                        $params['menu_image']   = $target_image;
                        $this->model->moduleUpdateBymoduleId($params, $actMsg['editid']);
                    }
                }
            } else
                $actMsg['message'] = 'Module already exists.';
        } else
            $actMsg['message'] = 'Fields marked with (*) are mandatory.';

        return $actMsg;
    }

    function uploadPlugin()
    {
        $actMsg['type']         = 0;
        $actMsg['message']      = '';

        $plugin                 = trim($this->_request['plugin']);
        $theme                  = trim($this->_request['theme']);

        $theme = 'listview';

        if ($plugin != '') {

            $pluginLoc  = PLUGINS . DS;
            $pluginDir  = PLUGINS . DS . $plugin;

            $pluginFile = PLUGINS_REPO . $plugin . DS . $theme . DS . 'archive.zip';

            if (file_exists($pluginFile)) {

                if (!is_dir($pluginDir)) {

                    $zip = new ZipArchive;
                    $res = $zip->open($pluginFile);

                    if ($res === TRUE) {

                        $zip->extractTo($pluginLoc);
                        $zip->close();

                        $helperFile = $pluginDir . DS . 'helper.json';

                        if (file_exists($helperFile)) {

                            $json           = file_get_contents($helperFile);

                            if ($this->isJSON($json)) {

                                $helperRepo     = json_decode($json, true);

                                $dbFile     = $pluginDir . DS . $helperRepo['db'];

                                if (file_exists($dbFile)) {

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
                                $params['menu_image']       = ($helperRepo['icon']) ? $helperRepo['icon'] : '';
                                $params['parent_dir']       = $helperRepo['path'];
                                $params['menu_description'] = $helperRepo['description'];

                                $pId = $this->model->newModule($params);

                                foreach ($helperRepo['sub'] as $helper) {

                                    $paramsSub                  = [];
                                    $paramsSub['parent_id']     = $pId;
                                    $paramsSub['menu_name']     = $helper['name'];
                                    $paramsSub['menu_image']    = ($helper['icon']) ? $helper['icon'] : '';
                                    $paramsSub['parent_dir']    = $helperRepo['path'];
                                    $paramsSub['child_dir']     = $helper['path'];

                                    $sId = $this->model->newModule($paramsSub);
                                }

                                @unlink($helperFile);

                                $this->permissionUpdate();

                                $actMsg['type']           = 1;
                                $actMsg['message']        = 'Plugin installed successfully.';
                            } else {
                                $this->model->rrmdir($pluginDir);
                                $actMsg['message']        = 'Invalid helper.json file.';
                            }
                        } else {
                            $this->model->rrmdir($pluginDir);
                            $actMsg['message']        = 'Missing helper.json file.';
                        }
                    } else
                        $actMsg['message']        = 'Invalid archive file.';
                } else
                    $actMsg['message']        = 'Plugin already exists.';
            } else
                $actMsg['message']        = 'Plugin does not exist.';
        } else
            $actMsg['message']        = 'Please select a plugin to install.';

        return $actMsg;
    }

    function isJSON($string)
    {
        return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
    }

    function multiAction()
    {
        $actMsg['type']           = 0;
        $actMsg['message']        = '';

        foreach ($this->_request['selectMulti'] as $val) {

            $params = array();

            switch ($this->_request['multiAction']) {
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

            if ($params['delete'] == 'Y') {
                /*-- this function deletes child (if any) modules as well --*/
                $del = $this->model->deleteModuleBymoduleId($val);

                /*-- permission update --*/
                //$this->permissionUpdate();
            } else
                $this->model->moduleUpdateBymoduleId($params, $val);

            $actMsg['type']           = 1;
            $actMsg['message']        = 'Operation successful.';
        }
        return $actMsg;
    }

    function permissionUpdate()
    {

        $mids               = $this->model->getAllModuleIds();
        $permArray          = [];

        foreach ($mids as $pmid) {
            $permArray[]    = (int)trim($pmid['menu_id']);
        }

        $permString         = implode(',', $permArray);

        //$permString = filter_var($permString, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

        $this->session->write('PERMISSION', $permString);

        //$_SESSION['PERMISSION']         = $permString;

        $permissionParam                = [];
        $permissionParam['permission']  = $permString;

        $update = $this->model->userUpdate($permissionParam, $this->session->read('UID'));

        $selData = $this->model->getUserByid($this->session->read('UID'));
    }

    function swap()
    {
        $actMsg['type']             = 0;
        $actMsg['message']          = '';

        $listingCounter = 1;

        foreach ($this->_request['recordsArray'] as $recordID) {
            $params = array();
            $params['displayOrder'] = $listingCounter;
            $this->model->moduleUpdateBymoduleId($params, $recordID);
            $listingCounter = $listingCounter + 1;
        }

        if ($listingCounter > 1) {
            $actMsg['type']             = 1;
            $actMsg['message']          = 'Operation successful.';
        }

        return $actMsg;
    }

    function moduleDirFile($string, $parent = '')
    {
        $string     = strtolower($string);                              // Converts into lower case.
        $string     = str_replace(' ', '', $string);                    // Replaces all spaces with none.
        $string     = preg_replace('/[^A-Za-z0-9\-]/', '', $string);    // Removes special chars.

        $Model          = ucwords($string) . 'Model';
        $Controller     = ucwords($string) . 'Controller';

        if ($parent) {

            $dirClassPath   = PLUGINS . DS . $parent . DS . 'class' . DS . 'admin';
            $dirViewPath    = PLUGINS . DS . $parent . DS . 'view' . DS . 'admin';

            $ControllerFile = $Controller . '.php';

            if (!file_exists($dirClassPath . DS . $ControllerFile)) {
                $ControllerFile = fopen($dirClassPath . DS . $ControllerFile, "w");

                fwrite($ControllerFile, "<?php defined('BASE') OR exit('No direct script access allowed.');
class " . $Controller . " extends REST
{
    private    $" . "model;
    protected  $" . "response = [];

    public function __construct(" . $Model . " $" . "model = null) {
        parent::__construct();

        if ($" . "model == null)
            $" . "model  = new " . $Model . ";

        $" . "this->model = $" . "model;
    }

    function index($" . "act = []) {

    }

    function modPage() {
        $" . "srch = trim($" . "this->_request['srch']);

        if ($" . "srch) {
            return $" . "this->model->searchLinkedPages($" . "this->_request['mid'], $" . "this->_request['pageType'], $" . "srch, 0, 10);
        }
    }
}");
                fclose($ControllerFile);
            }

            $ModelFile  = $Model . '.php';
            if (!file_exists($dirClassPath . DS . $ModelFile)) {
                $ModelFile = fopen($dirClassPath . DS . $ModelFile, "w");


                fwrite($ModelFile, "<?php defined('BASE') OR exit('No direct script access allowed.');
class " . $Model . " extends ContentModel
{
    function searchLinkedPages($" . "mid, $" . "parent_dir, $" . "srch, $" . "start, $" . "limit)
    {
        if ($" . "mid == 0) {

            $" . "ENTITY      = TBL_MENU_CATEGORY . \" mc JOIN \" . TBL_MODULE . \" m ON (m.menu_id = mc.moduleId)\";

            $" . "ExtraQryStr = \"mc.categoryName like '%\" . addslashes($" . "srch) . \"%' AND mc.status = 'Y' AND m.parent_dir = '\" . addslashes($" . "parent_dir) . \"' AND m.child_dir = '' ORDER BY mc.displayOrder\";

            $" . "data = $" . "this -> selectMulti($" . "ENTITY, \"mc.categoryId id, mc.categoryName page, mc.permalink\", $" . "ExtraQryStr, $" . "start, $" . "limit);

        } else {

            $" . "ENTITY      = TBL_CONTENT . \" c JOIN \" . TBL_MENU_CATEGORY . \" mc ON (c.menucategoryId = mc.categoryId) JOIN \" . TBL_MODULE . \" m ON (m.menu_id = mc.moduleId)\";
            $" . "ExtraQryStr = \"c.contentHeading like '%\" . addslashes($" . "srch) . \"%' AND mc.status = 'Y' AND m.parent_dir = '\" . addslashes($" . "parent_dir) . \"' AND m.child_dir = '' ORDER BY mc.displayOrder\";


            $" . "data = $" . "this -> selectMulti($" . "ENTITY, \"c.contentID id, c.contentHeading page, c.permalink\", $" . "ExtraQryStr, $" . "start, $" . "limit);
        }

        return $" . "data;
    }
}");
                fclose($ModelFile);
            }

            if (!is_dir($dirViewPath . DS . $string)) {

                @mkdir($dirViewPath . DS . $string, 0755, true);

                $indexFile = fopen($dirViewPath . DS . $string . DS . 'index.php', "w");

                fwrite($indexFile, "<?php defined('BASE') OR exit('No direct script access allowed.');");
                fclose($indexFile);

                $addFile = fopen($dirViewPath . DS . $string . DS . 'add.php', "w");

                fwrite($addFile, "<?php defined('BASE') OR exit('No direct script access allowed.');");
                fclose($addFile);
            }
        } else {
            $dirPath        = PLUGINS . DS . $string;
            $indexSource    = PLUGINS . DS . 'index.php';

            if (!is_dir($dirPath)) {
                @mkdir($dirPath, 0755, true);
                @copy($indexSource, $dirPath . DS . 'index.php');

                @mkdir($dirPath . DS . 'class', 0755, true);
                @copy($indexSource, $dirPath . DS . 'class' . DS . 'index.php');

                @mkdir($dirPath . DS . 'class' . DS . 'admin', 0755, true);
                @copy($indexSource, $dirPath . DS . 'class' . DS . 'admin' . DS . 'index.php');

                @mkdir($dirPath . DS . 'class' . DS . 'front', 0755, true);
                @copy($indexSource, $dirPath . DS . 'class' . DS . 'front' . DS . 'index.php');


                $ControllerFile = $Controller . '.php';
                $ControllerFile = fopen($dirPath . DS . 'class' . DS . 'front' . DS . $ControllerFile, "w");

                fwrite($ControllerFile, "<?php defined('BASE') OR exit('No direct script access allowed.');

class " . $Controller . " extends REST
{
    private    $" . "model;
    protected  $" . "pageview;
    protected  $" . "response = [];

    public function __construct(" . $Model . " $" . "model = null) {
        parent::__construct();

        if ($" . "model == null)
            $" . "model = new " . $Model . ";

        $" . "this->model = $" . "model;
    }

    function index($" . "pageData = '') {

        if (!$" . "pageData)
            return;
            
        //Open this code only if dtls does not exist, so that 404 will be handled by Skeleton.
        /*if ($" . "this->_request['dtls'])
            return;*/

        //Default routing (optional); 
        //$" . "this->routing();

        $" . "this->response['body']  = $" . "this->pageview;
        return $" . "this->response;
    }

    function routing() {
        
        if ($" . "this->_request['dtaction']) {
            $" . "func  = str_replace('-', '', $" . "this->_request['dtaction']);

            if ((int)method_exists($" . "this, $" . "func) > 0) {
                $" . "this->$" . "func();
            }
        }
    }
}");
                fclose($ControllerFile);

                $ModelFile  = $Model . '.php';
                $ModelFile  = fopen($dirPath . DS . 'class' . DS . 'front' . DS . $ModelFile, "w");

                fwrite($ModelFile, "<?php defined('BASE') OR exit('No direct script access allowed.');
class " . $Model . " extends ContentModel
{
    //Assuming  TBL_" . strtoupper($string) . " is the DB table name. 
    //Change the value of $" . "ENTITY as per your DB.

    function recordCount($" . "ExtraQryStr)
    {
        $" . "ENTITY        = TBL_" . strtoupper($string) . ";
        $" . "ExtraQryStr  .= \" AND status='Y' \";
        return $" . "this -> rowCount($" . "ENTITY, 'id', $" . "ExtraQryStr);
    }
    function getRecords($" . "ExtraQryStr, $" . "start, $" . "limit)
    {
        $" . "ExtraQryStr  .= \" AND status='Y' ORDER BY displayOrder\";
        $" . "ENTITY        = TBL_" . strtoupper($string) . ";

        return $" . "this -> selectMulti($" . "ENTITY, \"*\", $" . "ExtraQryStr, $" . "start, $" . "limit);
    }

    function getRecord($" . "permalink)
    {
        $" . "ExtraQryStr     = \" permalink = '\" . addslashes($" . "permalink) . \"' AND status='Y'\";
        $" . "ENTITY        = TBL_" . strtoupper($string) . ";
        return $" . "this -> selectSingle($" . "ENTITY, \"*\", $" . "ExtraQryStr);
    }
}");
                fclose($ModelFile);

                @mkdir($dirPath . DS . 'view', 0755, true);
                @copy($indexSource, $dirPath . DS . 'view' . DS . 'index.php');

                @mkdir($dirPath . DS . 'view' . DS . 'admin', 0755, true);
                @copy($indexSource, $dirPath . DS . 'view' . DS . 'admin' . DS . 'index.php');

                @mkdir($dirPath . DS . 'view' . DS . 'front', 0755, true);
            }
        }

        return $string;
    }

    function getplugin()
    {
        $actMsg['type']         = 0;
        $actMsg['message']      = '';

        $plugin                 = trim($this->_request['qrystr']);

        if ($plugin) {

            /*$proto = (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')? 'https:':'http:';
            $link = $proto.base64_decode(PLUGINS_REPO_LINK);*/

            $link = base64_decode(PLUGINS_REPO_LINK);

            $pluginDir  = $link . DS . $plugin;
            $helperFile = $pluginDir . DS . 'themes.json';

            $json           = file_get_contents($helperFile);

            $helperRepo     = json_decode($json, true);

            $actMsg['pluginPath'] = $pluginDir;
            $actMsg['plugins'] = $helperRepo;
        }

        return $actMsg;
    }
}
