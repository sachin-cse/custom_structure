<?php
defined('BASE') OR exit('No direct script access allowed.'); 
class MenuController  extends REST
{
	private    $model;
	protected  $response = array();
    private static $nav;
	
    public function __construct($model) {
    	parent::__construct();
        $this->model        = new $model;
    }
    
	function index($act = []) {
        $menu                           = $this->model->cmsMenuPages();
        $this->response['navCMS']       = $this->nested2ul($menu);
        $this->response['navModules']   = $this->model->getModulePages();
        
        if(isset($this->_request['menuaction'])){
            
        }
        else {
            $this->response['navCount']     = $this->model->checkNavExistance();

            if($this->response['navCount']) {
                $navList                    = $this->model->getNav();
                $this->response['navList']  = unserialize($navList['value']);

                $editid = '';

                if(isset($this->_request['editid']) || isset($this->response['act']['editid']))
                    $editid = (isset($this->response['act']['editid'])) ? $this->response['act']['editid'] : $this->_request['editid'];
                elseif(sizeof($this->response['navList']) > 0)
                    $editid = key($this->response['navList']);

                if(isset($editid)){
                    $this->response['navData']      = $this->response['navList'][$editid];
                    $this->response['navData']['id']= $editid;
                    
                    if($this->response['navData']['pages']) {
                        $menuPages  = unserialize($this->response['navData']['pages']);
                        
                        $menuList   = [];

                        foreach($menuPages as $key=>$mt) {
                            if($mt->item_id != 'root') {
                                $menuData = $this->model->menuByCategoryId($mt->item_id);
                                
                                $menuArray = [];
                                $menuArray['categoryId']        = $mt->item_id;
                                $menuArray['parentId']          = $mt->parent_id;
                                $menuArray['moduleId']          = $menuData['moduleId'];
                                $menuArray['seoId']             = $menuData['seoId'];
                                $menuArray['categoryName']      = $menuData['categoryName'];
                                $menuArray['permalink']         = $menuData['permalink'];
                                $menuArray['categoryImage']     = $menuData['categoryImage'];
                                $menuArray['categoryUrl']       = $menuData['categoryUrl'];
                                $menuArray['categoryUrlTarget'] = $menuData['categoryUrlTarget'];
                                $menuArray['displayOrder']      = $key;
                                $menuArray['status']            = $menuData['status'];
                                $menuArray['menu_name']         = $menuData['menu_name'];
                                
                                array_push($menuList, $menuArray);
                            }
                        }
            
                        $menuTree                   = $this->model->buildTree($menuList);
                        $this->response['menu']     = $this->sortableNested2ul($menuTree, 1);
                    }
                }
            }
        }
        
        return $this->response;
    }
    
    function nested2ul($data) {
        $result = array();

        if (sizeof($data) > 0) {
            $result[] = '<ul>';
            foreach ($data as $entry) {
                $result[] = sprintf(
                    '<li><label><input type="checkbox" value="%s" name="categoryId[]"><span>%s</span></label>%s</li>',
                    $entry['categoryId'], $entry['categoryName'],
                    $this->nested2ul($entry['children'])
                );
            }
            
            $result[] = '</ul>';
        }

        return implode($result);
    }
    
    function sortableNested2ul($data, $first = 0) {
        
        $result     = array();

        if (sizeof($data) > 0) {
            
            $result[] = ($first) ? '<ul class="menuList sortable">' : '<ul class="">';
            foreach ($data as $entry) {
                $moduleName = ($entry['moduleId'] == 0) ? 'CMS' : $entry['menu_name'];
                $result[] = sprintf(
                    '<li data-act="ext" data-mod="%s" data-id="%s" id="list_%s"><span class="menuTitle"><i class="fa fa-arrows"></i><span class="node">%s</span> <em class="delete m-l-20"><i class="fa fa-trash"></i>delete</em> <em>%s</em> </span> %s</li>',
                    $entry['moduleId'], $entry['categoryId'], $entry['categoryId'], $entry['categoryName'], $moduleName,
                    $this->sortableNested2ul($entry['children'])
                );
            }
            
            $result[] = '</ul>';
        }

        return implode($result);
    }
    
    function createMenu() {

        $actMsg['type']         = 0;
        $actMsg['message']      = '';
        
        $name                   = trim($this->_request['menuName']);
        
        if($name) {
            $nav        = $this->model->getNav();
            $navValue   = unserialize($nav['value']);
            
            //permalink--------------
            $navPermalinkArray = [];
            foreach($navValue as $key=>$val) {
                $navPermalinkArray[]    = $val['permalink'];
                
                if($key == $this->_request['IdToEdit'])
                    $permalink = $val['permalink'];
            }
            $permalink       = createPermalinkFromArray($name, $navPermalinkArray, $permalink);
            //permalink---------------
            
            $paramsNav                  = [];
            $paramsNav['name']          = $name;
            $paramsNav['permalink']     = $permalink;
            $paramsNav['css']           = $this->_request['ulCss'];
            
            $pagesArray                 = json_decode($this->_request['menuTree']);
            
            foreach($pagesArray as $key => $page) {
                if($page->item_id != 'root') {
                    

                    if($page->act == 'new'){
                        
                        $ExtraQryStr    = "categoryName = '".addslashes($page->node)."' AND moduleId = ".addslashes($page->module);
                        $exist          = $this->model->checkPageExistence($ExtraQryStr);
                        
                        if(!$exist){
                            //permalink--------------
                            $page_permalink  = $page->node;	
                            $ExtraQryStr     = 1;
                            $page_permalink  = createPermalink(TBL_MENU_CATEGORY, $page_permalink, $ExtraQryStr);
                            //permalink---------------

                            $params 				    = array();

                            $params['parentId']         = ($page->parent_id == 'root') ? '0' : $page->parent_id;
                            $params['moduleId']         = $page->module;
                            $params['categoryName']     = $page->node;

                            $params['permalink']        = $page_permalink;

                            $params['status']           = 'Y';
                            $params['hiddenMenu']       = 'N';

                            $page->item_id = $this->model->newCategory($params);
                        }
                        else
                            $page->item_id = $exist['categoryId'];
                    }
                }
            }
            
            $paramsNav['pages']         = serialize($pagesArray);
            
            $params                     = [];
            
            if(!$nav) {
                
                $value[] = $paramsNav;
                
                $params['name']             = 'nav';
                $params['value']            = serialize($value);

                $this->model->newNav($params);
                
                $actMsg['type']             = 1;
                $actMsg['message']          = 'Menu created successfully.';
            }
            else {
                    
                if(isset($this->_request['IdToEdit']) && $this->_request['IdToEdit']!='') 
                    $navValue[$this->_request['IdToEdit']] = $paramsNav;
                else
                    array_push($navValue, $paramsNav);
                
                $params['value']            = serialize($navValue);
                
                $this->model->updateNav($params);
                
                $actMsg['editid']  = array_search($permalink, array_column($navValue, 'permalink'));
                $actMsg['type']    = 1;
                $actMsg['message'] = (isset($this->_request['IdToEdit']))? 'Menu updated successfully.' : 'Menu created successfully.';
                
                //Caching----
                $navHtml           = $this->nav(array('permalink'=>$permalink, 'css' => $this->_request['ulCss']));
                $navFile           = CACHE_ROOT.DS.$permalink.'.html';
                $navFile           = fopen($navFile, "w");

                fwrite($navFile, $navHtml);
                fclose($navFile);
                //-----------
                
                $this->model->redirectToURL(SITE_ADMIN_PATH.'/index.php?pageType='.$this->_request['pageType'].'&dtls='.$this->_request['dtls'].'&editid='.$actMsg['editid'].'&moduleId='.$this->_request['moduleId']);
            }
        }
        else
             $actMsg['message'] = 'Please give the menu a name.';
        
        return $actMsg;
    }

    function delete(){
        $actMsg['type']           = 0;
        $actMsg['message']        = '';
        
        if($this->_request['IdToEdit'] != ''){

            $nav        = $this->model->getNav();
            
            $navValue   = unserialize($nav['value']);

            
            unset($navValue[$this->_request['IdToEdit']]);
            
            $params = [];
            $params['value']            = serialize($navValue);
                
            $this->model->updateNav($params);
            
            $actMsg['type']           = 1;
            $actMsg['message']        = 'Operation successful.';
            
            $this->model->redirectToURL(SITE_ADMIN_PATH.'/index.php?pageType='.$this->_request['pageType'].'&dtls='.$this->_request['dtls'].'&moduleId='.$this->_request['moduleId']); 
        }
        else{
            $actMsg['message']        = 'Something went wrong. Please close your browser window and try again.';
        }
        return $actMsg;  
    }
    
    function goToMenu(){
        $this->model->redirectToURL(SITE_ADMIN_PATH.'/index.php?pageType='.$this->_request['pageType'].'&dtls='.$this->_request['dtls'].'&editid='.$this->_request['IdToEdit'].'&moduleId='.$this->_request['moduleId']);
    }
    
    function addMenu() {
        $actMsg['type']             = 0;
        $actMsg['message']          = '';
        
        if($this->_request['menuTree']) {
            $menuTree   = json_decode($this->_request['menuTree']);
            
            foreach($menuTree as $key=>$mt) {
                if($mt->item_id != 'root') {
                    $menuData = $this->model->menuByCategoryId($mt->item_id);
                    
                    $params = array();
                    $params['parentId']     = ($mt->parent_id == 'root') ? '0' : $mt->parent_id;
                    $params['displayOrder'] = $key;
                    
                    $this->model->menuUpdateByCategoryId($params, $menuData['categoryId']);
                }
            }
            
            $actMsg['type']     = 1;
            $actMsg['message']  = 'Data updated successfully.';
        }
        else
            $actMsg['message'] = 'Fields marked with (*) are mandatory.';

        return $actMsg;
    }
    
    function swap() {
        $actMsg['type']             = 0;
        $actMsg['message']          = '';
        
        $listingCounter = 1;
        
        foreach ($this->_request['recordsArray'] as $recordID) {
            $params = array();
            $params['displayOrder'] = $listingCounter;
            $this->model->menuUpdateByCategoryId($params, $recordID);
            $listingCounter = $listingCounter + 1;
        }
        
        if($listingCounter > 1){
            $actMsg['type']             = 1;
            $actMsg['message']          = 'Operation successful.';
        }
        
        return $actMsg;
    }
    
    function nav($opt = []) {
        
        if(!self::$nav){
            $navList                    = $this->model->getNav();
            self::$nav = unserialize($navList['value']);
        }

        $this->response['navList']  = self::$nav;
        $navKey                     = array_search($opt['permalink'], array_column($this->response['navList'], 'permalink'));

        if(isset($navKey)) {
            
            $this->response['navData']      = $this->response['navList'][$navKey];

            if($this->response['navData']['pages']) {
                $menuPages  = unserialize($this->response['navData']['pages']);
                $menuList   = [];

                foreach($menuPages as $key=>$mt) {
                    
                    if($mt->item_id != 'root') {
                        $menuData                       = $this->model->menuByCategoryId($mt->item_id);

                        $menuArray = $urlArray = [];
                        $menuArray['categoryId']        = $mt->item_id;
                        $menuArray['parentId']          = $mt->parent_id;
                        $menuArray['moduleId']          = $menuData['moduleId'];
                        $menuArray['seoId']             = $menuData['seoId'];
                        $menuArray['categoryName']      = $menuData['categoryName'];
                        $menuArray['permalink']         = $menuData['permalink'];
                        $menuArray['categoryImage']     = $menuData['categoryImage'];
                        $menuArray['categoryUrl']       = $menuData['categoryUrl'];
                        $menuArray['categoryUrlTarget'] = $menuData['categoryUrlTarget'];
                        $menuArray['displayOrder']      = $key;
                        $menuArray['status']            = $menuData['status'];
                        $menuArray['menu_name']         = $menuData['menu_name'];
                        
                        $urlArray[]                     = $menuData['permalink'];
                        $menuArray['link']              = $this->menuUrl($menuData['parentId'], $urlArray);

                        array_push($menuList, $menuArray);
                    }
                }

                $menuTree                   = $this->model->buildTree($menuList);
                
                return $this->nested2ulFront($menuTree, '', 1, $opt['css']);
            }
        }
    }
    
    function menuUrl($parentId, $urlArray = []){
        
        if($parentId && $parentId != 'root'){
            $menuData  = $this->model->menuByCategoryId($parentId);
            $urlArray[] = $menuData['permalink'];
            
            $parentId = $menuData['parentId'];
            $this->menuUrl($parentId, $urlArray);
        }
        
        return $url = implode('/', $urlArray);
    }
    
    function nested2ulFront($data, $permalink = '', $first = 0, $css = '') {
        
        $result     = array();

        if (sizeof($data) > 0) {
            
            $css        = ($first) ? $css : 'sub-menu';
            $subarrow   = ($css == 'sub-menu')? '<span class="fa fa-caret-down subarrow"></span>':'';
            
            $result[]   = $subarrow.'<ul class="'.$css.'">';
            
            foreach ($data as $entry) {
                //$this_permalink = ($entry['parentId'] == 'root')? '':$permalink;
                
                $passed_permalink = $permalink;
                $this_permalink   = $entry['permalink'].'/'.$passed_permalink;
                $new_permalink    = (sizeof($entry['children']) > 0)? $this_permalink : $permalink;
                
                
                if($entry['categoryUrl']){
                    $href         = ($entry['categoryUrl'] == '/')? SITE_LOC_PATH.'/':$entry['categoryUrl'];
                    $target       = ($entry['categoryUrlTarget']!='_self')? 'target="'.$entry['categoryUrlTarget'].'"':'';
                    
                } /*elseif($entry['link']) {
                    $href         = SITE_LOC_PATH.'/'.$entry['link'].'/';
                    $target       = '';
                } */else {

                    $href         = SITE_LOC_PATH.'/'.$this_permalink;
                    $target       = '';
                }
                
                $result[] = sprintf(
                    '<li><a href="%s" %s><span>%s</span></a> %s</li>',
                    $href, $target, $entry['categoryName'], 
                    $this->nested2ulFront($entry['children'], $new_permalink)
                );
            }
            
            $result[] = '</ul>';
        }

        return implode($result);
    }
}
?>