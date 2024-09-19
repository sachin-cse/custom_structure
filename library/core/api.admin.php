<?php
class API extends REST
{
    private $essentialHeader = "";
    public  $headerView     = "templates/default/inc/header.php";
    public  $footerView     = "templates/default/inc/footer.php";

    private $_view          = true;

    public function __construct()
    {
        parent::__construct();

        $this->modPath        = MODULE_PATH;
        $this->moduleCount    = 0;
        $this->pluginCount    = 0;
    }

    public function processApi()
    {
        $pageType   = $this->_request['pageType'];
        $dtls       = $this->_request['dtls'];
        $dtaction   = $this->_request['dtaction'];

        $class      = 'Admin';

        $controller = $class . 'Controller';
        $model         = $class . 'Model';

        $module     = 'admin';
        $func         = 'index';

        if ($this->session->read('LOGIN') == 'YES') {

            //echo $this->session->read('PERMISSION');

            if ($pageType) {

                if ($pageType == 'logout')
                    $this->logout();

                if ($dtls) {
                    $class          = ucwords($dtls);

                    $controller     = $class . 'Controller';
                    $model             = $class . 'Model';

                    $module         = $pageType . DIRECTORY_SEPARATOR . $dtls;
                }

                $file               = ($dtaction) ? $dtaction . '.php' : 'index.php';
            } else
                $file               = 'home.php';

            if (isset($this->_request['ajx_action'])) {

                $this->_view        = false;
                $func               = $this->_request['ajx_action'];
            } else $this->_view     = true;
        } else $file                = 'login.php';

        if ((int)method_exists($controller, $func) > 0) {

            $modelObj               = new $model;
            $obj                    = new $controller($modelObj);

            if ($this->_view == true) {

                if (isset($file)) {
                    $this->_result['data'] = [];

                    if ($file == 'login.php') {
                        $this->_result['data']['body'] = $obj->$func();
                        $this->loadView($module, $file, $this->_result['data']['body']);
                    } else {

                        if (isset($this->_request['SourceForm'])) {

                            if (isset($this->_request['Delete']))
                                $action  = 'delete';
                            elseif (isset($this->_request['DeleteImg']))
                                $action  = 'deleteImg';
                            elseif (isset($this->_request['DeleteFile']))
                                $action  = 'deleteFile';
                            elseif (isset($this->_request['Save']) || isset($this->_request['SaveNext']))
                                $action  = $this->_request['SourceForm'];

                            if ((int)method_exists($controller, $action) > 0)
                                $act     = $obj->$action();
                            else return $this->response('Page not found', 404);
                        }

                        if ($func == 'index')
                            $this->_result['data']['body']              = $obj->$func($act);
                        else
                            $this->_result['data']['body']              = $obj->$func();

                        if (isset($act))
                            $this->_result['data']['body']['act']   = $act;

                        $this->headerView();

                        if ($file == 'home.php')
                            $this->loadView($module, $file, $this->_result['data']);
                        else $this->loadView($module, $file, $this->_result['data']['body']);

                        $this->footerView();
                    }
                } else $this->response('Page not found', 404);
            } else {
                $this->_result['data'] = $obj->$func();
                $this->response($this->json($this->_result['data']), 200);
            }
        } else $this->response('Page not found', 404);
    }

    public function logout()
    {

        $this->session->forget('LOGIN');
        $this->session->forget('PERMISSION');
        $this->session->forget('SITEID');
        $this->session->forget('UTYPE');
        $this->session->forget('UNAME');
        $this->session->forget('UID');
        $this->session->forget('LASTLOGIN');

        /*session_unset();
        session_destroy();*/

        /*setcookie("user_id", '', time()-60*60*24*COOKIE_TIME_OUT, "/");
        setcookie("user_name", '', time()-60*60*24*COOKIE_TIME_OUT, "/");
        setcookie("user_key", '', time()-60*60*24*COOKIE_TIME_OUT, "/");*/

        $siteObj = new Site;
        $siteObj->redirectToURL(SITE_ADMIN_PATH);
    }

    public function headerView($status = 200)
    {
        if ($status == 404)
            $TitleofSite    = '404! Page not found.';
        else {
            $requestURI = strip_tags($_SERVER['REQUEST_URI']);
            $requestURI = trim($requestURI);

            $StringPath = str_replace(DOMAIN, '', $requestURI);

            $explodedTitle  = explode('/', $StringPath);
            $titleWords     = '';

            for ($k = 0; $k < sizeof($explodedTitle); $k++) {
                if ($k - 1 >= 0) {
                    if ($explodedTitle[$k - 1] != '' && !strstr($explodedTitle[$k - 1], '?'))
                        $titleWords .= ucwords(strtolower(str_replace('-', ' ', $explodedTitle[$k - 1]))) . ' | ';
                }
            }

            $TitleofSite    = $titleWords . SITE_NAME;
        }

        $this->essentialHeader       = '<meta charset="UTF-8">
                                        <meta name="viewport" content="width=device-width, initial-scale=1">
                                        <meta http-equiv="X-UA-Compatible" content="IE=edge">
                                        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

                                        <title>' . $TitleofSite . '</title>
                                        <meta name="keywords" content="" />
                                        <meta name="description" content="" />';

        $this->essentialHeader      .= '<meta name="Robots" content="noindex, nofollow"/>';

        $menu               = new Modules;

        if ($this->_request['moduleId']) {

            $exceptionalHeadings = array('postscategories', 'photos', 'videos', 'events', 'slideshows');


            if (in_array($this->_request['pageType'], $exceptionalHeadings)) {

                $parentData = $menu->menuByparent_dir($this->_request['pageType']);
                $parent_menu_name   = $parentData['menu_name'];

                $moduleClass = ucwords($this->_request['dtls']) . 'Model';

                $obj = new $moduleClass;
                $menudata = $obj->categoryById($this->_request['moduleId']);
                $menu_name = $menudata['categoryName'];
            } else {
                $menudata           = $menu->menuByid($this->_request['moduleId']);
                $menu_image         = $menudata['menu_image'];
                $menu_name          = $menudata['menu_name'];
                $parentMenuId       = $menudata['parent_id'];
                $parentmenudata     = $menu->menuByid($parentMenuId);
                $parent_menu_name   = $parentmenudata['menu_name'];
            }

            if ($menudata)
                $this->breadcrumb   = '<div class="row page-titles">
                                        <div class="col-sm-5 align-self-center"><h3 class="text-primary">' . $menu_name . '</h3></div>
                                        <div class="col-sm-7 align-self-center">
                                            <ol class="breadcrumb">
                                                <li class="breadcrumb-item">' . $parent_menu_name . '</li>
                                                <li class="breadcrumb-item active">' . $menu_name . '</li>
                                            </ol>
                                        </div>
                                    </div>';
        }

        $this->_result['data']['navigation']    = $menu->moduleNavigation($this->session->read('PERMISSION'));
        $this->loadView('admin', $this->headerView, $this->_result['data']['navigation']);
    }

    public function footerView()
    {
        /*$hmObj = new HomeController;        
        $this->_result['data'] = $hmObj->footer();*/
        $this->essestialFooter = '';
        include($this->footerView);
    }

    private function loadView($module, $file, $data = array())
    {

        if ($module && $file) {

            if ($module == 'admin')
                $viewFile = ROOT_PATH . DIRECTORY_SEPARATOR . ADMIN_PATH . DIRECTORY_SEPARATOR . $file;
            else {

                $modArray = explode(DIRECTORY_SEPARATOR, $module);

                if ($modArray[0] == MODULE_PATH)
                    $viewFile = ROOT_PATH . DIRECTORY_SEPARATOR . ADMIN_PATH . DIRECTORY_SEPARATOR . $modArray[0] . DIRECTORY_SEPARATOR . $modArray[1] . DIRECTORY_SEPARATOR . $file;
                else {

                    $arrayKey = array_search($modArray[0], array_column($this->_result['data']['navigation'], 'parent_dir'));

                    if ($this->_result['data']['navigation'][$arrayKey]['isDefault'] == '0')
                        $this->modPath = PLUGINS_PATH;
                    else
                        $this->modPath = MODULE_PATH;

                    $viewFile = ROOT_PATH . DIRECTORY_SEPARATOR . $this->modPath . DIRECTORY_SEPARATOR . $modArray[0] . DIRECTORY_SEPARATOR . ADMIN_VIEW . DIRECTORY_SEPARATOR . $modArray[1] . DIRECTORY_SEPARATOR . $file;
                }
            }

            if (file_exists($viewFile)) {
                include($viewFile);
            } else
                echo 'View file does not exist.';
        } else
            $this->response('Page not found.', 404);
    }

    private function json($data)
    {
        if (is_array($data)) {
            return json_encode($data);
        }
    }

    private function hook($module, $func, $opt = [], $classType = 'Controller')
    {
        if ($module && $func) {

            if ($classType == 'Controller') {
                $class              = ucwords(strtolower($module));
                $classController    = $class . $classType;
                $classModel         = $class . 'Model';
            } else {
                $class              = ucwords(strtolower($module));

                $classModel         = $class . 'Model';
            }

            if ($classController) {
                if ((int)method_exists($classController, $func) > 0) {
                    $modelObj = new $classModel;
                    $obj = new $classController($modelObj);
                }
            } else {
                if ((int)method_exists($classModel, $func) > 0)
                    $obj = new $classModel;
            }

            if ($obj) {
                $data = $obj->$func($opt);
                return $data;
            }
        }
    }

    private function clearGPR()
    {
        unset($_GET);
        unset($_REQUEST['pageType']);
        unset($_REQUEST['dtls']);
        unset($_REQUEST['dtaction']);
        unset($_POST);
    }
}
