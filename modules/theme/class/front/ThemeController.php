<?php
defined('BASE') OR exit('No direct script access allowed.');
class ThemeController extends REST
{
    private    $model;
	protected  $pageview ;
	protected  $response       = [];
	protected  $responseHeader = [];
	protected  $responseFooter = [];
    
    private static $nav;
	
    public function __construct($model = 'ThemeModel') {
    	parent :: __construct();
        $this->model = new $model;
    }
	
	public function index($pageData = []) {
        if(!$pageData)
			$pageData = $this->model->getCategoryBypermalink('home');
        
        if($pageData['categoryId'])
            $this->response['pageContent']	= $this->content($pageData['categoryId']);
        
		$this->pageview             	= 'home.php';       
        $this->response['body']     	= $this->pageview;
        
        return $this->response;
	}
    
    function content($categoryId) {
    	$cObj 		= new ContentModel();
    	$content    = $cObj->getContentbymenucategoryId($categoryId, 0, 10);
		return $content;
    }
    
    function breadcrumb($status = 200) {
        if($status != 200)
            return;

		$pageTypeArray = (isset($this->_request['pageType']))? explode('/', $this->_request['pageType']) : array();
		$pageTypeArray = array_reverse($pageTypeArray);
        
		$breadcrumb    = '<div class="breadcrumb"><div class="container"><ul class="clearfix">';
		$breadcrumb   .= '<li><a href="'.SITE_LOC_PATH.'/">Home</a></li>';
		
		if(isset($this->_request['dtls'])) {
            $linkPrefix   = $this->_request['dtaction'].'/';
			$breadcrumb      .= '<li><a href="'.SITE_LOC_PATH.'/'.$linkPrefix.'">'.ucwords(str_replace('-', ' ', $this->_request['dtaction'])).'</a></li>';
            
			if($this->_request['dtls'] != 'item') {
                $linkPrefix = $this->_request['dtls'].'/'.$linkPrefix;
				$breadcrumb .= '<li><a href="'.SITE_LOC_PATH.'/'.$linkPrefix.'">'.ucwords(str_replace('-',' ',$this->_request['dtls'])).'</a></li>';
            }
             
			$loop = 1;            
			foreach($pageTypeArray as $page) {
                
                $linkPrefix = $page.'/'.$linkPrefix;

                if($loop == sizeof($pageTypeArray))
				    $breadcrumb .= '<li class="active">'.ucwords(str_replace('-',' ',$page)).'</li>';
                else
				    $breadcrumb .= '<li><a href="'.SITE_LOC_PATH.'/'.$linkPrefix.'">'.ucwords(str_replace('-',' ',$page)).'</a></li>';
                
                $loop++;
			}
		} elseif(isset($this->_request['dtaction'])) {
            
			$breadcrumb .= '<li><a href="'.SITE_LOC_PATH.'/'.$this->_request['pageType'].'/">'.ucwords(str_replace('-',' ',$this->_request['pageType'])).'</a></li>';
			$breadcrumb .= '<li class="breadcrumb-item active">'.ucwords(str_replace('-',' ',$this->_request['dtaction'])).'</li>';
		} else {

            if($this->_request['pageType'] == 'faq')
                $breadcrumb .= (isset($this->_request['pageType']))? '<li class="active">'.strtoupper(str_replace('-', ' ', $this->_request['pageType'])).'</li>' : '';
            else
                $breadcrumb .= (isset($this->_request['pageType']))? '<li class="active">'.ucwords(str_replace('-', ' ', $this->_request['pageType'])).'</li>' : '';
        }

		$breadcrumb .='</ul></div></div>';
        
		return $breadcrumb;
	}
    
    public function header($status = 200, $pageInfo = []) {
        
        $settings      = $this->model->settings(strtolower(str_replace('Controller', '', get_class($this))));
        $settings      = unserialize($settings['value']);
        
        
        if(isset($this->_request['pageType'])) {
            
            if($pageInfo['innerBanner'])
               $this->responseHeader['innerBanner']  = $pageInfo['innerBanner'];
            else  {

                $siteObj = new SitepageModel;

                if(isset($this->_request['dtaction']))
                    $innerBanner  = $siteObj -> getCategoryBypermalink($this->_request['dtaction']);
                else
                    $innerBanner  = $siteObj -> getCategoryBypermalink($this->_request['pageType']);

                if($innerBanner['isBanner']) {
                
                    if($innerBanner['categoryImage'] && file_exists(MEDIA_FILES_ROOT.DS.'banner'.DS.'thumb'.DS.$innerBanner['categoryImage'])) {
                        $innerBanner['src']     = MEDIA_FILES_SRC.DS.'banner'.DS.'thumb'.DS.$innerBanner['categoryImage'];
                        $innerBanner['alt']     = $innerBanner['categoryName'];
                        $innerBanner['caption'] = ($innerBanner['isBannerCaption'])? $innerBanner['bannerCaption'] : '';
                    }
                    else { 
                        if($settings['isBanner']) {
                            if(file_exists(MEDIA_FILES_ROOT.DS.'banner'.DS.'thumb'.DS.$innerBanner['innerBanner'])) {
                                $innerBanner['src']     = MEDIA_FILES_SRC.DS.'banner'.DS.'thumb'.DS.$settings['innerBanner'];
                                $innerBanner['alt']     = $settings['innerBanner'];
                                $innerBanner['caption'] = ($settings['isBannerCaption'])? $settings['bannerCaption'] : '';
                            }
                        }
                        else
                            $innerBanner['src']     = '';
                    }
                }

                $this->responseHeader['innerBanner']  = $innerBanner;
            }

        }
        elseif($settings['isSlider'])
            $this->responseHeader['homeSlider']   = $this->model->homeSlider(0, $settings['sliderNo']);
    	
        if(isset($this->_request['pageType']))
            $this->responseHeader['breadcrumb']   = $this->breadcrumb($status);
        
        $this->responseHeader['socialLinks']      = $this->social();
     	
    	return $this->responseHeader;
    }
    
    public function footer($pageData = []) {
        
        $this->responseFooter['pageData']       = $pageData;
        $this->responseFooter['socialLinks']    = $this->social();
        return $this->responseFooter;
    }
    
    public function social() {

        $socialLinks      = $this->model->getSocialSite(1, 0, 10);
        return $socialLinks;
    }
    
    function nav($opt = []) {
        
        $navFile           = CACHE_ROOT.DS.$opt['permalink'].'.html';
        if(file_exists($navFile)) {
            include $navFile;
            return;
        }
        
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
                
                echo $this->nested2ul($menuTree, '', 1, $opt['css']);
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
    
    function nested2ul($data, $permalink = '', $first = 0, $css = '') {
        
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
                    $this->nested2ul($entry['children'], $new_permalink)
                );
            }
            
            $result[] = '</ul>';
        }

        return implode($result);
    }

    // API endpoint ...
    //-----------------
    function banner($pageData = []) {
        $response['errorCode']      = 1;
        $response['errorMessage']   = '';
        $response['resourceName']   = '';
        $response['data']           = null;

        $settings                   = $this->model->settings(strtolower(str_replace('Controller', '', get_class($this))));
        $settings                   = unserialize($settings['value']);

        if($pageData) {
            $homeSlider                     = $this->model->homeSlider(0, $settings['sliderNo']);
            if(is_array($homeSlider) && count($homeSlider) > 0) {
                $response['data']           = $homeSlider;
                $response['resourceName']   = 'Banner';
                $response['errorCode']      = 0;
            }
        }
        
        return $response;
    }

    function menu($pageData = []) {
        $response['errorCode']      = 1;
        $response['errorMessage']   = '';
        $response['resourceName']   = '';
        $response['data']           = null;

        if($pageData) {
            if(!self::$nav){
                $navList            = $this->model->getNav();
                self::$nav          = unserialize($navList['value']);
            }
            $res['navList']         = self::$nav;
            $navKey                 = array_search('main-menu', array_column($res['navList'], 'permalink'));

            if(isset($navKey)) {
                $res['navData']     = $res['navList'][$navKey];
                if($res['navData']['pages']) {
                    $menuPages      = unserialize($res['navData']['pages']);
                    $menuList       = [];
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
                    $response['data']           = $menuList;
                    $response['resourceName']   = $res['navList'][$navKey]['name'];
                    $response['errorCode']      = 0;
                }
            }
        }
        
        return $response;
    }

    // End:: API endpoint ...
    //-----------------------
}
?>