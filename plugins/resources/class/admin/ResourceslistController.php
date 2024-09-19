<?php defined('BASE') OR exit('No direct script access allowed.');
class ResourceslistController extends REST
{
    private    $model;
	protected  $response = array();
	
    public function __construct($model) {
    	parent::__construct();
        $this->model        = new $model;
    }

    function index($act = []) {
            
        $this->response['linkedPages']          = $this->model->getLinkedPages($this->_request['pageType'], 0, 100);
            
        $settings                               = $this->model->settings($this->_request['pageType']);
        $this->response['settings']             = unserialize($settings['value']);
        
        if(isset($this->_request['editid']) || isset($act['editid']) || $this->_request['dtaction'] == 'add') {
            
            $editid = ($this->_request['editid'])? $this->_request['editid']:$act['editid'];
            
            if($editid) {
                $this->response['resource']      = $this->model->resourceByIdMenucategoryId($editid);
                $this->response['image'] = $this->model->getResourceGalleryByResourceId($this->response['resource']['id'], 0, 100);

                $titleandMetaUrl                = '/'.$this->response['resource']['permalink'].'/'.$this->response['resource']['menuPermalink'].'/';
                $seoModel                       = new TitlemetaModel;
                $this->response['seoData']      = $seoModel->titleMetaByUrl($titleandMetaUrl);
            }
        }
        else {
        
            $ExtraQryStr = 1;

            // SEARCH START --------------------------------------------------------------
            if(isset($this->_request['searchText']))
                $this->session->write('searchText', $this->_request['searchText']);

            if($this->session->read('searchText'))
                $ExtraQryStr        .= " AND serviceName LIKE '%".addslashes($this->session->read('searchText'))."%'";

            if(isset($this->_request['searchStatus']))
                $this->session->write('searchStatus', $this->_request['searchStatus']);

            if($this->session->read('searchStatus'))
                $ExtraQryStr        .= " AND status = '".addslashes($this->session->read('searchStatus'))."'";

            if(isset($this->_request['searchShowcase']))
                $this->session->write('searchShowcase', $this->_request['searchShowcase']);

            if($this->session->read('searchShowcase'))
                $ExtraQryStr        .= " AND isShowcase = '".addslashes($this->session->read('searchShowcase'))."'";

            if(isset($this->_request['searchPage']))
                $this->session->write('searchPage', $this->_request['searchPage']);

            if($this->session->read('searchPage'))
                $ExtraQryStr        .= " AND menucategoryId = ".addslashes($this->session->read('searchPage'));

            if(isset($this->_request['Reset']) || isset($this->_request['Search'])) {

                if(isset($this->_request['Reset'])){

                    $this->session->write('searchText',     '');
                    $this->session->write('searchStatus',   '');
                    $this->session->write('searchShowcase', '');
                    $this->session->write('searchPage',     '');
                }

                $this->model->redirectToUrl(SITE_ADMIN_PATH.'/index.php?pageType='.$this->_request['pageType'].'&dtls='.$this->_request['dtls'].'&moduleId='.$this->_request['moduleId']);
            }
            // SEARCH END ----------------------------------------------------------------

            $this->response['rowCount']     = $this->model->resourceCount($ExtraQryStr);

            if($this->response['rowCount']) {

                $p                          = new Pager;
                $this->response['limit']    = VALUE_PER_PAGE;
                $start                      = $p->findStart($this->response['limit'], $this->_request['page']);
                $pages                      = $p->findPages($this->response['rowCount'], $this->response['limit']);

                $this->response['resource']    	 = $this->model->getResourceByLimit($ExtraQryStr, $start, $this->response['limit']);

                if($this->response['rowCount'] > 0 && ceil($this->response['rowCount'] / $this->response['limit']) > 1) {
                    $this->response['page']      = ($this->_request['page']) ? $this->_request['page'] : 1;
                    $this->response['totalPage'] = ceil($this->response['rowCount'] / $this->response['limit']);

                    $this->response['pageList']  = $p->pageList($this->response['page'], $_SERVER['REQUEST_URI'], $pages);
                }
            }
        }
        
        return $this->response;
    }

    function modPage() {
        $srch = trim($this->_request['srch']);

        if ($srch) {
            return $this->model->searchLinkedPages($this->_request['mid'], $this->_request['pageType'], $srch, 0, 10);
        }
    }

    function addEditResource() {
        $actMsg['type']             = 0;
        $actMsg['message']          = '';
        
        $menucategoryId             = trim($this->_request['menucategoryId']);
        $resourceTitle              = trim($this->_request['title']);
        $permalink                  = trim($this->_request['permalink']);
        $resourceShortDescription    = trim($this->_request['short_description']);
        $resourceDescription         = trim($this->_request['description']);
        $status                     = trim($this->_request['status']);
        $displayOrder               = trim($this->_request['displayOrder']);
        $isShowcase                 = trim($this->_request['isShowcase']);
        $publishDate                = trim($this->_request['publishDate']);
        $pageTitleText              = trim($this->_request['pageTitleText']);
        $metaRobotsIndex            = trim($this->_request['metaRobotsIndex']);
        $metaRobotsFollow           = trim($this->_request['metaRobotsFollow']);
        $metaTag                    = trim($this->_request['metaTag']);
        $metaDescription            = trim($this->_request['metaDescription']);
        $others                     = trim($this->_request['others']);
        
        if($menucategoryId != '' && $resourceTitle != '' && $resourceDescription != '' &&  $publishDate != '' && $permalink != '') {
            if($this->_request['IdToEdit']!= '')
                $sel_ContentDetails = $this->model->checkExistence("title = '".addslashes($resourceTitle)."' AND id != ".$this->_request['IdToEdit']);
            else
                $sel_ContentDetails = $this->model->checkExistence("title = '".addslashes($resourceTitle)."'");

            if(sizeof($sel_ContentDetails) < 1) {
                
                //permalink --------------
                $ENTITY          = TBL_RESOURCE;
                if(!$permalink)
                    $permalink   = $resourceTitle;
                else
                    $permalink   = str_replace('-', ' ', $permalink);

                if($this->_request['IdToEdit'])
                    $ExtraQryStr = 'id != '.$this->_request['IdToEdit'];
                else
                    $ExtraQryStr = 1;
                $permalink       = createPermalink($ENTITY, $permalink, $ExtraQryStr);
                //permalink ---------------

                $params                             = array();
                $params['menucategoryId']           = $menucategoryId;
                $params['title']              = $resourceTitle;
                $params['permalink']                = $permalink;
                $params['short_description']  = $resourceShortDescription;
                $params['description']       =  $resourceDescription;
                $params['image'] = $resourceImage;
                $params['banner'] = $resourceBanner;
                $params['publishDate'] = $publishDate;
                $params['status']                   = $status;
                $params['isShowcase']               = $isShowcase;
                
                if($displayOrder == 'T' || $displayOrder == 'B'){
                    $order          = $this->model->getDisplayOrder($displayOrder);
                    $displayOrder   = ($displayOrder == 'T')? ($order['displayOrder'] - 1) : ($order['displayOrder'] + 1);
                }
                
                $params['displayOrder']             = $displayOrder;
                
                if($this->_request['IdToEdit'] != '') {
                    $dataBeforeUpdate           = $this->model->resourceById($this->_request['IdToEdit']);
                    $params['image'] = $dataBeforeUpdate['image'];
                    $params['banner'] = $dataBeforeUpdate['banner'];
                    $this->model->resourceUpdateById($params, $this->_request['IdToEdit']);

                    $actMsg['editid']           = $this->_request['IdToEdit'];
                    $actMsg['message']          = 'Data updated successfully.';
                }
                else {
                    $params['entryDate']        = date('Y-m-d H:i:s');
                    $actMsg['editid']           = $this->model->newResource($params);
                    $actMsg['message']          = 'Data inserted successfully.';
                }
                    $actMsg['type']             = 1;
                
                //Image ---------------
                $targetLocation = MEDIA_FILES_ROOT.DS.$this->_request['pageType'];
                $targetFile     = MEDIA_FILES_SRC.DS.$this->_request['pageType'];
                $ogUrl          = DS.$this->_request['pageType'];
                
                if (!file_exists($targetLocation) && !is_dir($targetLocation)) 
                    $this->createMedia($targetLocation);
                
                $settings = $this->model->settings($this->_request['pageType']);
                $settings = unserialize($settings['value']);

                $selData      = $this->model->resourceByIdMenucategoryId($actMsg['editid']);
                $setCover     = $selData['banner'];

                //showArray($_FILES['serviceBanner']);die;
                if($_FILES['banner']['name'] && substr($_FILES['banner']['type'], 0, 5) == 'image') {

                    $fObj           = new FileUpload;

                    $TWH[0]         = $settings['bannerWidth'];       // thumb width
                    $TWH[1]         = $settings['bannerHeight'];      // thumb height
                    $LWH[0]         = $settings['bannerWidth'];       // large width
                    $LWH[1]         = $settings['bannerHeight'];      // large height
                    $option         = 'all';                  // upload, thumbnail, resize, all

                    $fileName 		= $permalink."-banner-".time();
                    if($target_image = $fObj->uploadImage($_FILES['banner'], $targetLocation, $fileName, $TWH, $LWH, $option)) {

                        // delete existing image
                        if($selData['banner'] != $target_image) {
                            @unlink($targetLocation.'/normal/'.$selData['banner']);
                            @unlink($targetLocation.'/thumb/'.$selData['banner']);
                            @unlink($targetLocation.'/large/'.$selData['banner']);
                        }

                        // update new image
                        $params                 = array();
                        $params['banner']	= $target_image;
                        $this->model->resourceUpdateById($params, $actMsg['editid']);
                    }
                }

                // upload icon
                if($_FILES['image']['name'] && substr($_FILES['image']['type'], 0, 5) == 'image') {

                    $fObj           = new FileUpload;

                    $TWH[0]         = $settings['bannerWidth'];       // thumb width
                    $TWH[1]         = $settings['bannerHeight'];      // thumb height
                    $LWH[0]         = $settings['bannerWidth'];       // large width
                    $LWH[1]         = $settings['bannerHeight'];      // large height
                    $option         = 'all';                  // upload, thumbnail, resize, all

                    $fileName 		= $permalink."-image-".time();
                    if($target_image = $fObj->uploadImage($_FILES['image'], $targetLocation, $fileName, $TWH, $LWH, $option)) {

                        // delete existing image
                        if($selData['image'] != $target_image) {
                            @unlink($targetLocation.'/normal/'.$selData['image']);
                            @unlink($targetLocation.'/thumb/'.$selData['image']);
                            @unlink($targetLocation.'/large/'.$selData['image']);
                        }

                        // update new image
                        $params                 = array();
                        $params['image']	= $target_image;
                        $this->model->resourceUpdateById($params, $actMsg['editid']);
                    }
                }


                //Image ---------------   
                if($actMsg['editid']) {
                    //Image ---------------

                    $newImgUploadArr    = $scsImgArr = $errImgArr = array();
                    $fObj 				= new FileUpload;
                    
                    $TWH[0]         = $settings['imageThumbWidth'];     // thumb width
                    $TWH[1]         = $settings['imageThumbHeight'];    // thumb height
                    $LWH[0]         = $settings['imageWidth'];          // large width
                    $LWH[1]         = $settings['imageHeight'];         // large height
                    $option         = 'all';                            // upload, thumbnail, resize, all


                    // foreach($_FILES['serviceImage']['name'] as $imgKey=>$nameValue) {
                        
                    //     $imgValue['name']      = $_FILES['serviceImage']['name'][$imgKey];
                    //     $imgValue['type']      = $_FILES['serviceImage']['type'][$imgKey];
                    //     $imgValue['tmp_name']  = $_FILES['serviceImage']['tmp_name'][$imgKey];
                    //     $imgValue['error']     = $_FILES['serviceImage']['error'][$imgKey];
                    //     $imgValue['size']      = $_FILES['serviceImage']['size'][$imgKey];
         

                    //     if($imgValue['name'] && substr($imgValue['type'], 0, 5) == 'image') {

                            
                    //         $fileName 			= $permalink.'-'.time().'-'.$imgKey;
                    //         if($target_image = $fObj->uploadImage($imgValue, $targetLocation, $fileName, $TWH, $LWH, $option)) {

                    //             $scsImgArr[] 				= $imgValue['name'];

                    //             $params                     = array();
                    //             $params['serviceId']   		= $actMsg['editid'];
                    //             $params['serviceImage']     = $target_image;
                    //             $params['status']           = 'Y';
                    //             $insertGal                  = $this->model->newServiceGallery($params);

                    //             if(!$setCover)
                    //                 $setCover               = $target_image;
                    //         }
                    //         else
                    //             $errImgArr[] = $imgValue['name'];
                    //     }
                    //     else
                    //         $errImgArr[] = $imgValue['name'];
                    // }

                    // if(isset($this->_request['coverImage'])) {
                    //     $sImage                 = $this->model->serviceGalleryById($this->_request['coverImage']);
                    //     $setCover               = $sImage['serviceImage'];
                    // }

                    // update new image for category
                    $params = array();
                    // $params['serviceImage'] 	= $setCover;
                    $this->model->resourceUpdateById($params, $actMsg['editid']);

                    //Image ---------------
                
                
                    //SEO -----------------
                    $ogImage                        = ($setCover != '') ? $ogUrl."/large/".$setCover : '';
                    $pageUrl                        = $permalink.'/'.$selData['menuPermalink'];
                    
                    $titleandMetaUrl                = '/'.$pageUrl.'/';

                    if(!$pageTitleText)
                        $pageTitleText              = $serviceName;
                    
                    $seoModel                       = new TitlemetaModel;

                    if($this->_request['IdToEdit'] && $dataBeforeUpdate['permalink'] != $permalink)
                        $handler                    = str_replace('/'.$permalink.'/', '/'.$dataBeforeUpdate['permalink'].'/', $titleandMetaUrl);
                    else {
                        $handler                    = $titleandMetaUrl;
                        
                        if($this->_request['IdToEdit'] == '' && $serviceShortDescription)
                            $metaDescription        = strip_tags($serviceShortDescription);
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
                    }
                    // ------------------
                }
            }
            else
                $actMsg['message']        = 'Blog already exists.';   
        }
        else
        $actMsg['message']        = 'Fields marked with (*) are mandatory.';
        
		return $actMsg;
    }
    
    function createMedia($targetLocation) {
        $indexingSource = MEDIA_FILES_ROOT.DS.'index.php';
        @mkdir($targetLocation, 0755); 
        copy($indexingSource, $targetLocation.DS.'index.php');

        @mkdir($targetLocation.DS.'catalog',    0755); 
        copy($indexingSource, $targetLocation.DS.'catalog'.DS.'index.php');

        @mkdir($targetLocation.DS.'large',      0755); 
        copy($indexingSource, $targetLocation.DS.'large'.DS.'index.php');

        @mkdir($targetLocation.DS.'normal',     0755); 
        copy($indexingSource, $targetLocation.DS.'normal'.DS.'index.php');

        @mkdir($targetLocation.DS.'small',      0755);   
        copy($indexingSource, $targetLocation.DS.'small'.DS.'index.php');

        @mkdir($targetLocation.DS.'thumb',      0755); 
        copy($indexingSource, $targetLocation.DS.'thumb'.DS.'index.php');
    }

    function swap() {
        $actMsg['type']             = 0;
        $actMsg['message']          = '';
        
        $listingCounter = 1;
        
        foreach ($this->_request['recordsArray'] as $recordID) {
            $params = array();
            $params['displayOrder'] = $listingCounter;
            $this->model->resourceUpdateById($params, $recordID);
            $listingCounter = $listingCounter + 1;
        }
        
        if($listingCounter > 1){
            $actMsg['type']             = 1;
            $actMsg['message']          = 'Operation successful.';
        }
        
        return $actMsg;
    }

    // function deleteImg(){
    //     $actMsg['type']           = 0;
    //     $actMsg['message']        = '';
        
    //     if($this->_request['DeleteImg']){
    //         $selData = $this->model->serviceGalleryById($this->_request['DeleteImg']);
            
    //         if($selData['serviceImage']) {
    //             @unlink(MEDIA_FILES_ROOT.'/'.$this->_request['pageType'].'/normal/'.$selData['serviceImage']);
    //             @unlink(MEDIA_FILES_ROOT.'/'.$this->_request['pageType'].'/thumb/'.$selData['serviceImage']);
    //             @unlink(MEDIA_FILES_ROOT.'/'.$this->_request['pageType'].'/large/'.$selData['serviceImage']);
                
    //             // delete image
    //             $this->model->deleteServiceGallery($this->_request['DeleteImg']);
    //         }
            
    //         $actMsg['type']           = 1;
    //         $actMsg['message']        = 'Image deleted successfully.';
    //     }
    //     else{
    //         $actMsg['message']        = 'Something went wrong. Please close your browser window and try again.';
    //     }
    //     return $actMsg;  
    // }

    function deleteFile(){
        $actMsg['type']           = 0;
        $actMsg['message']        = '';

        if($this->_request['DeleteFile'] == 'banner'){
            $selData = $this->model->resourceById($this->_request['IdToEdit']);
            if($selData['banner']){
                @unlink(MEDIA_FILES_ROOT.'/'.$this->_request['pageType'].'/normal/'.$selData['banner']);
                @unlink(MEDIA_FILES_ROOT.'/'.$this->_request['pageType'].'/thumb/'.$selData['banner']);
                @unlink(MEDIA_FILES_ROOT.'/'.$this->_request['pageType'].'/large/'.$selData['banner']);

                // update image field to blank
                $params                     = array();
                $params['banner']    = '';
                $this->model->resourceUpdateById($params, $this->_request['IdToEdit']);
            }

            $actMsg['type']           = 1;
            $actMsg['message']        = 'Banner deleted successfully.';
        }
        
        if($this->_request['DeleteFile'] == 'image'){
            $selData = $this->model->resourceById($this->_request['IdToEdit']);
            if($selData['image']){
                @unlink(MEDIA_FILES_ROOT.'/'.$this->_request['pageType'].'/normal/'.$selData['image']);
                @unlink(MEDIA_FILES_ROOT.'/'.$this->_request['pageType'].'/thumb/'.$selData['image']);
                @unlink(MEDIA_FILES_ROOT.'/'.$this->_request['pageType'].'/large/'.$selData['image']);

                // update image field to blank
                $params                     = array();
                $params['image']      = '';
                $this->model->resourceUpdateById($params, $this->_request['IdToEdit']);
            }

            $actMsg['type']           = 1;
            $actMsg['message']        = 'Image deleted successfully.';
        }
        // elseif($this->_request['DeleteFile'] == 'serviceCatalog'){
        //     $selData = $this->model->serviceById($this->_request['IdToEdit']);
        //     if($selData['serviceCatalog']){
        //         @unlink(MEDIA_FILES_ROOT.'/'.$this->_request['pageType'].'/catalog/'.$selData['serviceCatalog']);

        //         // update image field to blank
        //         $params                     = array();
        //         $params['serviceCatalog']   = '';
        //         $this->model->serviceUpdateById($params, $this->_request['IdToEdit']);
        //     }

        //     $actMsg['type']           = 1;
        //     $actMsg['message']        = 'Catalog deleted successfully.';
        // }

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
                        $params['status']       = 'Y';
                        break;
                    case "2":
                        $params['status']       = 'N';
                        break;
                    case "3":
                        $params['delete']       = 'Y';
                        break;
                    case "4":
                        $params['isShowcase']   = 'Y';
                        break;
                    case "5":
                        $params['isShowcase']   = 'N';
                        break;
                    default:
                        $this->response('', 406);
                } 
                
                if($params['delete'] == 'Y') {
                    $resource = $this->model->resourceById($val);
                    
                    if($resource){
                        if($resource['image'] && file_exists(MEDIA_FILES_ROOT.'/'.$this->_request['pageType'].'/normal/'.$resource['image'])){
                            @unlink(MEDIA_FILES_ROOT.'/'.$this->_request['pageType'].'/thumb/'.$service['image']);
                            @unlink(MEDIA_FILES_ROOT.'/'.$this->_request['pageType'].'/normal/'.$service['image']);
                            @unlink(MEDIA_FILES_ROOT.'/'.$this->_request['pageType'].'/large/'.$service['image']);
                        }
                        if($resource['image'] && file_exists(MEDIA_FILES_ROOT.'/'.$this->_request['pageType'].'/normal/'.$resource['banner'])){
                            @unlink(MEDIA_FILES_ROOT.'/'.$this->_request['pageType'].'/thumb/'.$service['banner']);
                            @unlink(MEDIA_FILES_ROOT.'/'.$this->_request['pageType'].'/normal/'.$service['banner']);
                            @unlink(MEDIA_FILES_ROOT.'/'.$this->_request['pageType'].'/large/'.$service['banner']);
                        }
                        // if($service['serviceBanner'] && file_exists(MEDIA_FILES_ROOT.'/'.$this->_request['pageType'].'/thumb/'.$service['serviceBanner'])){
                        //     @unlink(MEDIA_FILES_ROOT.'/'.$this->_request['pageType'].'/thumb/'.$service['serviceBanner']);
                        //     @unlink(MEDIA_FILES_ROOT.'/'.$this->_request['pageType'].'/normal/'.$service['serviceBanner']);
                        //     @unlink(MEDIA_FILES_ROOT.'/'.$this->_request['pageType'].'/large/'.$service['serviceBanner']);
                        // }
                        // if($service['serviceCatalog'] && file_exists(MEDIA_FILES_ROOT.'/'.$this->_request['pageType'].'/catalog/'.$service['serviceCatalog'])){
                        //     @unlink(MEDIA_FILES_ROOT.'/'.$this->_request['pageType'].'/catalog/'.$service['serviceCatalog']);
                        // }

                        // $serviceGallery = $this->model->getServiceGalleryByServiceId($val, 0, 999);
                        // foreach($serviceGallery as $gallery){
                        //     if($gallery['serviceImage'] && file_exists(MEDIA_FILES_ROOT.'/'.$this->_request['pageType'].'/thumb/'.$gallery['serviceImage'])){
                        //         @unlink(MEDIA_FILES_ROOT.'/'.$this->_request['pageType'].'/thumb/'.$gallery['serviceImage']);
                        //         @unlink(MEDIA_FILES_ROOT.'/'.$this->_request['pageType'].'/normal/'.$gallery['serviceImage']);
                        //         @unlink(MEDIA_FILES_ROOT.'/'.$this->_request['pageType'].'/large/'.$gallery['serviceImage']);
                        //     }
                        //     $this->model->deleteServiceGallery($gallery['id']);
                        // }
                        
                        $this->model->deleteResource($val);
                    }
                }
                else
                    $this->model->resourceUpdateById($params, $val);
                
                $actMsg['type']           = 1;
                $actMsg['message']        = 'Operation successful.';
            }
        }
        
        return $actMsg;
    }
}