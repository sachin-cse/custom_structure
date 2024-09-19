<?php
defined('BASE') OR exit('No direct script access allowed.');
class PagesController extends REST
{    
    private    $model;
	protected  $response = array();
	
    public function __construct($model) {
    	parent::__construct();
        $this->model        = new $model;
    }
    
    function index($act = []){
        
        if(isset($this->_request['parentId']) && $this->_request['parentId']) {
            $parentId = ($this->_request['parentId'])? $this->_request['parentId']:$this->response['act']['parentId'];
            
            $this->response['parentData'] = $this->model->categoryById($parentId);
        }
        
        if(isset($this->_request['editid']) || isset($act['editid']) || $this->_request['dtaction'] == 'new') {
            
            $settings      = $this->model->settings('theme');
            $settings      = unserialize($settings['value']);
            
            $this->response['bannerWidth']  = $settings['bannerWidth'];
            $this->response['bannerHeight'] = $settings['bannerHeight'];
            
            
            $editid = ($this->_request['editid'])? $this->_request['editid']:$act['editid'];
            
            if($editid)
                $this->response['pageData'] = $this->model->categoryById($editid);
            
            if($this->response['pageData']['seoId']){
                $seoModel                   = new TitlemetaModel;
                $this->response['seoData']  = $seoModel->titleMetaById($this->response['pageData']['seoId']);
            }
        }
        else {
            
            $ExtraQryStr  = (!$this->_request['parentId']) ? "pm.parentId = 0" : "pm.parentId = ".addslashes($this->_request['parentId']);
            $ExtraQryStr .= ($this->session->read('UTYPE') != "A") ? " AND pm.hiddenMenu='N'" : '';
            
            // SEARCH START --------------------------------------------------------------
            if(isset($this->_request['searchText']))
                $this->session->write('searchText', $this->_request['searchText']);

            if($this->session->read('searchText'))
                $ExtraQryStr        .= " AND pm.categoryName LIKE '%".addslashes($this->session->read('searchText'))."%'";

            if(isset($this->_request['searchStatus']))
                $this->session->write('searchStatus', $this->_request['searchStatus']);

            if($this->session->read('searchStatus'))
                $ExtraQryStr        .= " AND pm.status = '".$this->session->read('searchStatus')."'";

            if(isset($this->_request['searchType']))
                $this->session->write('searchType', $this->_request['searchType']);

            if($this->session->read('searchType')) {
                if($this->session->read('searchType') == 'T')
                    $ExtraQryStr    .= " AND pm.isTopMenu = 'Y'";
                elseif($this->session->read('searchType') == 'F')
                    $ExtraQryStr    .= " AND pm.isFooterMenu = 'Y'";
                elseif($this->session->read('searchType') == 'H')
                    $ExtraQryStr    .= " AND pm.hiddenMenu = 'Y'";
            }

            if(isset($this->_request['Reset']) || isset($this->_request['Search'])) {
                
                if(isset($this->_request['Reset'])){
                    $ExtraQryStr  = (!$this->_request['parentId']) ? "pm.parentId = 0" : "pm.parentId = ".addslashes($this->_request['parentId']);
                    $ExtraQryStr .= ($this->session->read('UTYPE') != "A") ? " AND pm.hiddenMenu='N'" : '';

                    $this->session->write('searchText',     '');
                    $this->session->write('searchStatus',   '');
                    $this->session->write('searchType',     '');
                }
                
                $this->model->redirectToUrl(SITE_ADMIN_PATH.'/index.php?pageType='.$this->_request['pageType'].'&dtls='.$this->_request['dtls'].'&moduleId='.$this->_request['moduleId']);
            }
            // SEARCH END ----------------------------------------------------------------
            
            $this->response['rowCount']          = $this->model->categoryCount($ExtraQryStr);

            if($this->response['rowCount']) {
                $p                               = new Pager;
                $this->response['limit']         = VALUE_PER_PAGE;
                $start                           = $p->findStart($this->response['limit'], $this->_request['page']);
                $pages                           = $p->findPages($this->response['rowCount'], $this->response['limit']);

                $this->response['pages']         = $this->model->getCategory($ExtraQryStr, $start, $this->response['limit']);

                if($this->response['rowCount'] > 0 && ceil($this->response['rowCount'] / $this->response['limit']) > 1) {
                    $this->response['page']      = ($this->_request['page']) ? $this->_request['page'] : 1;
                    $this->response['totalPage'] = ceil($this->response['rowCount'] / $this->response['limit']);
                    
                    $this->response['pageList']  = $p->pageList($this->response['page'], $_SERVER['REQUEST_URI'], $pages);
                }
            }
        }
        
        return $this->response;
    }
    
    function addEditPage() {

        $actMsg['type']             = 0;
        $actMsg['message']          = '';
        
        $categoryName               = trim($this->_request['categoryName']);
        $permalink                  = trim($this->_request['permalink']);
        $categoryUrl                = trim($this->_request['categoryUrl']);
        $isBanner                   = isset($this->_request['isBanner']) ? 1 : 0;
        $categoryUrlTarget          = trim($this->_request['categoryUrlTarget']);
        $isBannerCaption            = isset($this->_request['isBannerCaption']) ? 1 : 0;
        $bannerCaption              = trim($this->_request['bannerCaption']);
        
        $modulePackageId            = trim($this->_request['modulePackageId']);
        $modulePackageId            = ($modulePackageId)? $modulePackageId:0;
        
        $parentId                   = trim($this->_request['parentId']);
        $parentId                   = ($parentId)? $parentId:0;
        $hiddenMenu                 = trim($this->_request['hiddenMenu']);
        $hiddenMenu                 = ($hiddenMenu)? $hiddenMenu:'N';//12.12.2018
        
        $status                     = trim($this->_request['status']);
        
        $pageTitleText              = trim($this->_request['pageTitleText']);
        $titleandMetaUrl            = trim($this->_request['titleandMetaUrl']);
        
        $metaRobotsIndex            = trim($this->_request['metaRobotsIndex']);
        $metaRobotsFollow           = trim($this->_request['metaRobotsFollow']);
        
        $metaTag                    = trim($this->_request['metaTag']);
        $metaDescription            = trim($this->_request['metaDescription']);
        $others                     = trim($this->_request['others']);
        
        if($categoryName !='') {

            //permalink--------------
            if(!$permalink)
                $permalink  = $categoryName;	
            else
                $permalink  = str_replace('-',' ',$permalink);
            
            if($this->_request['IdToEdit'])
                $ExtraQryStr= 'categoryId!='.$this->_request['IdToEdit'].' and parentId='.$parentId;	
            else
                $ExtraQryStr= 'parentId='.$parentId;
            $permalink      = createPermalink(TBL_MENU_CATEGORY, $permalink, $ExtraQryStr);
            //permalink---------------

            if($this->_request['IdToEdit']!='')
                $exist = $this->model->checkExistence("categoryName = '".addslashes($categoryName)."' and parentId=".$parentId." and categoryId != ".$this->_request['IdToEdit']);
            else
                $exist = $this->model->checkExistence("categoryName = '".addslashes($categoryName)."' and parentId=".$parentId);

            if(!$exist) {
                
                $params 				    = array();
                $params['parentId']         = $parentId;
                $params['moduleId']         = $modulePackageId;
                $params['categoryName']     = $categoryName;
                
                $params['permalink']        = $permalink;
                $params['categoryUrl']      = $categoryUrl;
                $params['categoryUrlTarget']= $categoryUrlTarget;
                $params['isBanner']         = $isBanner;
                $params['isBannerCaption']  = $isBannerCaption;
                $params['bannerCaption']    = $bannerCaption;
                
                $params['status']           = $status;
                $params['hiddenMenu']       = $hiddenMenu;

                if($this->_request['IdToEdit']) {
                    
                    $dataBeforeUpdate           = $this->model-> categoryById($this->_request['IdToEdit']);
                    
                    $this->model->categoryUpdateBycategoryId($params, $this->_request['IdToEdit']);
                    
                    $actMsg['editid']           = $this->_request['IdToEdit'];
                    $actMsg['type']             = 1;
                    $actMsg['message']          = 'Data updated successfully.';
                } else {
                    
                    $actMsg['editid']           = $this->model->newCategory($params);
                    
                    $actMsg['type']             = 1;
                    $actMsg['message']          = 'Data inserted successfully.';
                }
                
                
                //Image ------------------------------------------------------------------------
                if($_FILES['ImageName']['name'] && substr($_FILES['ImageName']['type'], 0, 5) == 'image') {
                    
                    $settings      = $this->model->settings('theme');
                    $settings      = unserialize($settings['value']);
                        
                    $selData        = $this->model->categoryById($actMsg['editid']);

                    $fObj           = new FileUpload;
                    
                    $targetLocation = MEDIA_FILES_ROOT."/banner";
                    $targetFile     = MEDIA_FILES_SRC.'/banner';
                    $TWH[0]         = $settings['bannerWidth'];      // thumb width
                    $TWH[1]         = $settings['bannerHeight'];      // thumb height
                    $LWH[0]         = $settings['bannerWidth'];      // large width
                    $LWH[1]         = $settings['bannerHeight'];      // large height
                    $option         = 'thumbnail';    // upload, thumbnail, resize, all

                    $fileName = $permalink;
                    if($target_image = $fObj->uploadImage($_FILES['ImageName'], $targetLocation, $fileName, $TWH, $LWH, $option)) {

                        // delete existing image
                        if($selData['categoryImage'] != $target_image) {
                            @unlink($targetLocation.'/normal/'.$selData['categoryImage']);
                            @unlink($targetLocation.'/thumb/'.$selData['categoryImage']);
                            @unlink($targetLocation.'/large/'.$selData['categoryImage']);
                        }

                        // update new image
                        $params                 = array();
                        $params['categoryImage']   = $target_image;
                        $this->model->categoryUpdateBycategoryId($params, $actMsg['editid']);
                    }
                }
                //Image ------------------------------------------------------------------------

                
                //SEO --------------------------------------------------------------------------
                $ogImage                        = ($target_image != '') ? $targetFile."/thumb/".$target_image : '';
                $parentCatArray                 = [];
                if($parentId) {
                    $cparentId                  = $parentId;	
                    
                    while($cparentId) {
                        $cData                  = $this->model->categoryById($cparentId);	
                        $cparentId              = $cData['parentId'];
                        
                        $parentCatArray[]       = $cData['permalink'];
                    }
                }
                $parentCatArray[]               = $permalink;
                $parentCatArray                 = array_reverse($parentCatArray);
                
                $pageUrl                        = implode('/', $parentCatArray);
                
                $titleandMetaUrl                = '/'.$pageUrl.'/';

                if(!$pageTitleText)
                    $pageTitleText              = $contentHeading;
                
                $seoModel                       = new TitlemetaModel;

                if($this->_request['IdToEdit'] && $dataBeforeUpdate['permalink'] != $permalink)
                    $handler                    = str_replace('/'.$permalink.'/', '/'.$dataBeforeUpdate['permalink'].'/', $titleandMetaUrl);
                else {
                    $handler                    = $titleandMetaUrl;
                    
                    if($subHeading)
                        $metaDescription        = strip_tags($subHeading);
                }

                $seoData                        = $seoModel->titleMetaByUrl($handler);
                
                $params = array();
                $params['pageTitleText']        = $pageTitleText;
                $params['titleandMetaUrl']      = $titleandMetaUrl;
                $params['metaTag']              = $metaTag;
                $params['metaDescription']      = $metaDescription;
                if($metaRobotsIndex == 'default' && $metaRobotsFollow == 'nofollow')
                    $params['metaRobots']       = 'index, '.$metaRobotsFollow;
                else
                    $params['metaRobots']       = $metaRobotsIndex.', '.$metaRobotsFollow;
                $params['ogImage']              = $ogImage;
                $params['others']               = $others;

                if($seoData) {
                    $seoModel->titleMetaUpdateById($params, $seoData['titleandMetaId']);
                } else {
                    $params['siteId']           = $this->session->read('SITEID');
                    $params['titleandMetaType'] = 'O';

                    $seoId                      = $seoModel->newTitleMeta($params);
                    
                    $params                     = array();
                    $params['seoId']            = $seoId;
                    
                    $this->model->categoryUpdateBycategoryId($params, $actMsg['editid']);
                }
                //SEO --------------------------------------------------------------------------
            }
            else
                $actMsg['message'] = 'Category already exists.';
        }
        else
            $actMsg['message'] = 'Fields marked with (*) are mandatory.';
        
        return $actMsg;
    }
    
    function multiAction() {
        $actMsg['type']           = 0;
        $actMsg['message']        = '';
        
        if($this->_request['multiAction']){
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
                    $this->model->deleteMenuBycategoryId($val);
                }
                else
                    $this->model->categoryUpdateBycategoryId($params, $val);
                
                $actMsg['type']           = 1;
                $actMsg['message']        = 'Operation successful.';
            }
        }
        
        return $actMsg;
    }
    
    function deleteImg(){
        $actMsg['type']           = 0;
        $actMsg['message']        = '';
        
        if($this->_request['IdToEdit']){
            $selData = $this->model->categoryById($this->_request['IdToEdit']);
            
            if($selData['categoryImage']) {
                @unlink(MEDIA_FILES_ROOT.'/banner/normal/'.$selData['categoryImage']);
                @unlink(MEDIA_FILES_ROOT.'/banner/thumb/'.$selData['categoryImage']);
                @unlink(MEDIA_FILES_ROOT.'/banner/large/'.$selData['categoryImage']);
                
                // update image field to blank
                $params                 = array();
                $params['categoryImage']   = '';
                $this->model->categoryUpdateBycategoryId($params, $actMsg['editid']);
            }
            
            $actMsg['type']           = 1;
            $actMsg['message']        = 'Image deleted successfully.';
        }
        else{
            $actMsg['message']        = 'Something went wrong. Please close your browser window and try again.';
        }
        return $actMsg;  
    }
    
    function swap() {
        $actMsg['type']             = 0;
        $actMsg['message']          = '';
        
        $listingCounter = 1;
        
        foreach ($this->_request['recordsArray'] as $recordID) {
            $params = array();
            $params['displayOrder'] = $listingCounter;
            $this->model->categoryUpdateBycategoryId($params, $recordID);
            $listingCounter = $listingCounter + 1;
        }
        
        if($listingCounter > 1){
            $actMsg['type']             = 1;
            $actMsg['message']          = 'Operation successful.';
        }
        
        return $actMsg;
    }
}
?>