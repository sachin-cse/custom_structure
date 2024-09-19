<?php
class API extends REST
{
    public  $data           = "";
    private $essentialHeader = "";
    public  $headerView     = "";
    public  $pageview       = "";
    public  $footerView     = "";
    public  $innerBanner    = "";
    public  $theme          = "";

    private $_view          = false;
    private $pageInfo;

    public function __construct()
    {
        parent::__construct();

        if ($this->_request['pageType'] == 'home' || $this->_request['dtaction'] == 'home')
            header('location:' . SITE_LOC_PATH);

        $this->theme          = new ThemeController;
        $this->seo            = new SeoModel;

        $this->headerView     = "inc" . DS . "header.php";
        $this->footerView     = "inc" . DS . "footer.php";
        $this->pageInfo       = [];

        $this->modPath        = MODULE_PATH;
        $this->selfLoc        = '';
        $this->selfPath       = '';

        $this->device         = new Mobile_Detect;

        if (SITE_CACHE) {
            $cache             = unserialize(SITE_CACHE);

            $this->caching        = $cache['caching'];

            if (!strstr($_SERVER['REQUEST_URI'], "?")) {

                $this->cacheDir       = ($_SERVER['REDIRECT_URL'] == $_SERVER['REQUEST_URI']) ? CACHE_ROOT . DS . 'site' . str_replace(DOMAIN, '', $_SERVER['REDIRECT_URL']) : CACHE_ROOT . DS . 'site' . DS;
                $this->cacheFile      = $this->cacheDir . 'index.html';
                $this->cacheRefresh   = $cache['cacheRefresh']; // refresh cache everyday (24 hrs = 86400 seconds)
            }
        } else
            $this->caching        = false;
    }

    /*
    * Public method to access API.
    * This method dynmically invokes model, controller and view based on /pageType/dtls/dtaction/
    */

    public function processApi()
    {

        if ($this->caching == 'true' && $this->_request['pageType'] != 'api') {
            if ($this->cacheFile && file_exists($this->cacheFile)) {
                $cached = $this->readCache();
                if ($cached) {
                    echo $cached;
                    return;
                }
            }
        }

        $dmObj      = new SitepageModel;
        $func       = 'index';
        $pageData   = array();

        if (isset($this->_request['dtls'])) {
            $pageData       = $dmObj->getPageBypermalink($this->_request['dtaction']);
            $module         = $pageData['parent_dir'];

            $this->_view    = true;
        } elseif (isset($this->_request['dtaction'])) {

            if ($this->_request['pageType'] == 'api') {

                //$pageData   = $dmObj->getPageBypermalink('api');
                //$modDir     = $pageData['parent_dir'];
                $pageData   = $dmObj->getPageBypermalink($this->_request['dtaction']);
                $modDir     = ($pageData['parent_dir']) ? $pageData['parent_dir'] : 'content';
                if($pageData == null) {
                    $pageData   = $dmObj->getPageBypermalink('home');
                    $modDir     = 'theme';
                }
            } else {
                $pageData   = $dmObj->getPageBypermalink($this->_request['pageType']);

                if (!$pageData)
                    $pageData   = $dmObj->getPageBypermalink($this->_request['dtaction']);

                if ($pageData)
                    $modDir     = ($pageData['parent_dir']) ? $pageData['parent_dir'] : 'content';
            }

            $module             = $modDir;

            if ($this->_request['pageType'] == 'api') {
                $this->_view    = false;
                $func           = ($modDir == 'content' ? 'api_action': $this->_request['dtaction']);
            } elseif ($this->_request['pageType'] == 'ajx_action') {
                $this->_view    = false;
                $func           = ($this->_request['SourceForm']) ? str_replace('-', '', $this->_request['SourceForm']) : $this->_request['pageType'];

                /* if (!(int)method_exists(ucwords($module) . 'Controller', $func))
                    $func      = 'ajx_action'; */
            } else
                $this->_view    = true;
        } elseif (isset($this->_request['pageType'])) {

            $pageData   = $dmObj->getPageBypermalink($this->_request['pageType']);
            $module     = ($pageData['parent_dir']) ? strtolower($pageData['parent_dir']) : 'content';

            $this->_view     = true;
        } else {
            $module         = 'theme';
            $this->_view    = true;
        }

        if (isset($this->_request['pageType'])) {

            $this->modPath  = ($pageData['isDefault'] == '1' || $pageData['moduleId'] == 0) ? MODULE_PATH : PLUGINS_PATH;
            $this->selfLoc  = SITE_LOC_PATH . DS . $this->modPath . DS . $pageData['parent_dir'] . DS;
            $this->selfPath = ROOT_PATH . DS . $this->modPath . DS . $pageData['parent_dir'] . DS;
        }

        $class          = ucwords($module);

        $controller     = $class . 'Controller';
        $model          = $class . 'Model';

        $func           = str_replace('-', '', $func);

        if ((int)method_exists($controller, $func) > 0) {

            $modelObj               = new $model;
            $obj                    = new $controller($modelObj);

            if ($pageData)
                $this->_result['data'] = $obj->$func($pageData);
            elseif (!$this->_request['pageType'])
                $this->_result['data'] = $obj->$func();

            //$this->clearGPR();

            if ($this->_view == true) {

                if ($this->_result['data']['body']) {

                    if (isset($this->_request['pageType'])) {
                        $this->pageInfo['pageName'] = (!isset($this->_request['dtaction'])) ? $pageData['categoryName'] : str_replace('-', ' ', $this->_request['pageType']);

                        $this->pageInfo['pageLink']   = $pageData['permalink'];
                        $this->pageInfo['categoryId'] = $pageData['categoryId'];
                        $this->pageInfo['innerBanner'] = $this->_result['data']['innerBanner'];
                    }


                    $this->_result['data']['parent_dir'] = $pageData['parent_dir'];

                    $this->headerView($this->pageInfo);
                    $this->loadView($module, $this->_result['data']['body'],  $this->_result['data']);
                    $this->footerView();
                } else
                    $this->response('Page not found', 404);
            } else {
                if(!is_array($this->_result['data'])) {
                    $this->_result['data'] = array('errorCode'=>0, 'errorMessage'=>'No route was found matching the URL and request method');
                }
                
                $this->response($this->json($this->_result['data']), 200);
            }
        } else
            $this->response('Page not found', 404);
    }

    public function headerView($data = [], $status = 200)
    {

        if ($status == 404)
            $TitleofSite    = '404! Page not found.';
        else {

            $requestURI     = strip_tags($_SERVER['REQUEST_URI']);
            $requestURI     = trim($requestURI);

            $StringPath     = str_replace(DOMAIN, '', $requestURI);

            $explodedTitle  = explode('/', $StringPath);
            $titleWords     = '';

            for ($k = 0; $k < sizeof($explodedTitle); $k++) {
                if ($k - 1 >= 0) {
                    if ($explodedTitle[$k - 1] != '' && !strstr($explodedTitle[$k - 1], '?'))
                        $titleWords .= ucwords(strtolower(str_replace('-', ' ', $explodedTitle[$k - 1]))) . ' | ';
                }
            }

            $seoDefault = $this->seo->getDefaultTitleMeta();

            if ($seoDefault) {
                $TitleofSite       = $seoDefault['pageTitleText'];
                $MetaKeyOfSite     = $seoDefault['metaTag'];
                $MetaDescOfSite    = $seoDefault['metaDescription'];
                $MetaRobots        = $seoDefault['metaRobots'];
                $canonical         = $seoDefault['canonicalUrl'];
            }

            $seoUrlData = $this->seo->getTitleMetaByURL($StringPath);

            if ($seoUrlData) {
                if (strip_tags($seoUrlData['pageTitleText']) != '')
                    $TitleofSite = strip_tags($seoUrlData['pageTitleText']);
                else
                    $TitleofSite .= $titleWords;
                if (strip_tags($seoUrlData['metaTag']) != '')
                    $MetaKeyOfSite = strip_tags($seoUrlData['metaTag']);

                if (strip_tags($seoUrlData['metaDescription']) != '')
                    $MetaDescOfSite = strip_tags($seoUrlData['metaDescription']);

                $MetaRobots     = $seoUrlData['metaRobots'];
                $canonical      = $seoUrlData['canonicalUrl'];

                if ($seoUrlData['ogImage']) {
                    $ogData = '<meta property="og:title" content="' . $TitleofSite . '" /><meta property="og:type" content="website" /><meta property="og:url" content="' . SITE_LOC_PATH . $seoUrlData['titleandMetaUrl'] . '" /><meta property="og:image" content="' . MEDIA_FILES_SRC . $seoUrlData['ogImage'] . '" /><meta property="og:description" content="' . $MetaDescOfSite . '" />';
                }
            } else
                $TitleofSite = $titleWords . $TitleofSite;

            $StringPathForCanonical = explode('?', $StringPath);
            $canonical = ($canonical) ? $canonical : SITE_LOC_PATH . $StringPathForCanonical[0];
        }

        $this->essentialHeader = '<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta http-equiv="X-UA-Compatible" content="IE=edge"><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>' . $TitleofSite . '</title><meta name="keywords" content="' . $MetaKeyOfSite . '" /><meta name="description" content="' . $MetaDescOfSite . '" />' . $ogData;

        $this->essentialHeader .= (isset($seoUrlData['others'])) ? $seoUrlData['others'] : '';

        $MetaRobotsEx   = explode(', ', $MetaRobots);
        if ($MetaRobotsEx[0] != 'default')
            $this->essentialHeader .= (isset($MetaRobots)) ? '<meta name="Robots" content="' . $MetaRobots . '"/>' : '';
        $this->essentialHeader .= (isset($canonical)) ? '<link rel="canonical" href="' . $canonical . '" />' : '';


        $seo                    = $this->seo->settings();
        $seo                    = unserialize($seo['value']);
        $this->essentialHeader .= $seo['seoData'];

        $pwa                    = $this->seo->pwa();
        $pwa                    = unserialize($pwa['value']);

        $this->essentialHeader .= ($pwa['status'] == 1) ? '<meta name="theme-color" content="' . $pwa['themeColor'] . '"><link rel="manifest" href="' . SITE_LOC_PATH . DS . 'manifest.json" />' : '';

        $this->essentialFooter .= ($pwa['status'] == 1) ? '<script defer type="text/javascript"> if (\'serviceWorker\' in navigator) {
          navigator.serviceWorker.register(\'' . SITE_LOC_PATH . DS . 'sw.js\');}</script>' : '';

        $this->tm_js            = $seo['tagManager'];
        $this->tm_ns            = $seo['tagManagerNoscript'];

        define('GOOGLE_ANALYTICS', $seo['googleAnalytics']);


        $header = (is_array($data)) ? array_merge($data, $this->theme->header($status, $data)) : $this->theme->header($status, $data);

        $this->loadView('template', $this->headerView, $header);
    }

    function essentialHeader()
    {
        echo $this->essentialHeader; // essentialHeader = Mandatory Meta tags

        $cacheCss           = CACHE_ROOT . DS . 'css.html';
        if (file_exists($cacheCss))
            include($cacheCss);

        echo $this->tm_js;
    }

    function essentialFooter()
    {
        $cacheJs           = CACHE_ROOT . DS . 'js.html';
        if (file_exists($cacheJs))
            include($cacheJs);

        echo $this->essentialFooter;
    }

    public function footerView()
    {

        $this->essentialFooter       .= GOOGLE_ANALYTICS;
        $this->loadView('template', $this->footerView, $this->theme->footer($this->pageInfo));
    }

    private function loadView($module, $file, $data = array())
    {
        if ($module && $file) {

            if ($module == 'template')
                $viewFile = ROOT_PATH . DS . TMPL_PATH . DS . $file;
            else
                $viewFile = ROOT_PATH . DS . $this->modPath . DS . $module . DS . FRONT_VIEW . DS . $file;

            if (file_exists($viewFile))
                include($viewFile);
            else
                echo 'No template found.';
        } else
            $this->response('Page not found.', 404);
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
                $opt['module']      = $module;
                $data = $obj->$func($opt);
                return $data;
            }
        }
    }

    /* Encode array into JSON*/
    private function json($data)
    {
        if (is_array($data)) {
            return json_encode($data);
        } else 
            return null;
    }

    private function clearGPR()
    {
        unset($_GET);
        unset($_REQUEST['pageType']);
        unset($_REQUEST['dtls']);
        unset($_REQUEST['dtaction']);
        unset($_POST);
    }

    public function __destruct()
    {
        if ($GLOBALS['siteObj'])
            $GLOBALS['siteObj']->destroy();

        if ($this->caching)
            $this->cache();
    }

    function cache()
    {

        if (file_exists($this->cacheFile)) {
            $timedif = (time() - filemtime($this->cacheFile)); // how old is the file?
            if ($timedif > $this->cacheRefresh)
                $this->generateCache();
        } else
            $this->generateCache();
    }

    // a function to receive and write some data into a file
    function generateCache()
    {
        if ($this->cacheFile) {
            if (!file_exists($this->cacheFile))
                mkdir($this->cacheDir, 0755, true);

            $fp = fopen($this->cacheFile, 'w');
            fwrite($fp, ob_get_contents());
            fclose($fp);
            ob_end_flush();
        }
    }

    // a function that opens and and puts the data into a single var
    function readCache()
    {
        $timedif = (time() - filemtime($this->cacheFile)); // how old is the file?

        if ($timedif < $this->cacheRefresh) { // update everyday (86400 seconds = 24 hrs)
            $f = fopen($this->cacheFile, 'r');
            $buffer = '';
            while (!feof($f)) {
                $buffer .= fread($f, 2048);
            }
            fclose($f);
            return $buffer;
        } else
            return;
    }
}
