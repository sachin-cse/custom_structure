<?php
defined('BASE') OR exit('No direct script access allowed.');
class TestimoniallistController extends REST
{    
	private    $model;
	protected  $response = array();
	
    public function __construct($model) {
    	parent::__construct();
        $this->model        = new $model;
    }
    
	function index($act = []) {
            
        $this->response['linkedPages']          = $this->model->getLinkedPages($this->_request['pageType'], 0, 100);
            
        $settings                   = $this->model->settings($this->_request['pageType']);
        $this->response['settings'] = unserialize($settings['value']);
        
        if(isset($this->_request['editid']) || isset($act['editid']) || $this->_request['dtaction'] == 'add') {
            
            $editid = ($this->_request['editid'])? $this->_request['editid']:$act['editid'];
            
            if($editid) {
                $this->response['review']	= $this->model->reviewByIdMenucategoryId($editid);
                
                $titleandMetaUrl                = '/'.$this->response['review']['permalink'].'/'.$this->response['review']['menuPermalink'].'/';
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
                $ExtraQryStr        .= " AND authorName LIKE '%".addslashes($this->session->read('searchText'))."%'";

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

            $this->response['rowCount']     = $this->model->reviewCount($ExtraQryStr);

            if($this->response['rowCount']) {

                $p                          = new Pager;
                $this->response['limit']    = VALUE_PER_PAGE;
                $start                      = $p->findStart($this->response['limit'], $this->_request['page']);
                $pages                      = $p->findPages($this->response['rowCount'], $this->response['limit']);

                $this->response['review']    	 = $this->model->getReviewByLimit($ExtraQryStr, $start, $this->response['limit']);

                if($this->response['rowCount'] > 0 && ceil($this->response['rowCount'] / $this->response['limit']) > 1) {
                    $this->response['page']      = ($this->_request['page']) ? $this->_request['page'] : 1;
                    $this->response['totalPage'] = ceil($this->response['rowCount'] / $this->response['limit']);

                    $this->response['pageList']  = $p->pageList($this->response['page'], $_SERVER['REQUEST_URI'], $pages);
                }
            }
        }
        
        return $this->response;
    }
    
    function addEditReview(){
        
        $actMsg['type']             = 0;
        $actMsg['message']          = '';
        
        $menucategoryId             = trim($this->_request['menucategoryId']);
        $authorName           		= trim($this->_request['authorName']);
        $permalink           		= trim($this->_request['permalink']);
        $testimonialDescription     = trim($this->_request['testimonialDescription']);
        $designation             	= trim($this->_request['designation']);
        $status                	    = trim($this->_request['status']);
        $displayOrder               = trim($this->_request['displayOrder']);
        $isShowcase             	= trim($this->_request['isShowcase']);

        $pageTitleText              = trim($this->_request['pageTitleText']);
        $metaRobotsIndex            = trim($this->_request['metaRobotsIndex']);
        $metaRobotsFollow           = trim($this->_request['metaRobotsFollow']);
        $metaTag                    = trim($this->_request['metaTag']);
        $metaDescription            = trim($this->_request['metaDescription']);
        $others                     = trim($this->_request['others']);
             
        if($menucategoryId != '' && $authorName != '' && $testimonialDescription != '') {

            //permalink--------------
            $ENTITY          = TBL_TESTIMONIAL;
            if(!$permalink)
                $permalink   = $authorName;	
            else
                $permalink   = str_replace('-',' ',$permalink);

            if($this->_request['IdToEdit'])
                $ExtraQryStr = 'testimonialId != '.$this->_request['IdToEdit'];	
            else
                $ExtraQryStr = 1;
            $permalink       = createPermalink($ENTITY, $permalink, $ExtraQryStr);
            //permalink---------------

            $params                         = array();
            $params['menucategoryId']       = $menucategoryId;
            $params['authorName']          	= $authorName;
            $params['permalink']          	= $permalink;
            $params['testimonialDescription']= $testimonialDescription;
            $params['designation']          = $designation;
            $params['status']               = $status;
            $params['isShowcase']         = $isShowcase;
            
            if($displayOrder == 'T' || $displayOrder == 'B'){
                $order          = $this->model->getDisplayOrder($displayOrder);
                $displayOrder   = ($displayOrder == 'T')? ($order['displayOrder'] - 1) : ($order['displayOrder'] + 1);
            }
            
            $params['displayOrder']         = $displayOrder;
            
            if($this->_request['IdToEdit'] != '') {
                $dataBeforeUpdate           = $this->model-> reviewByIdMenucategoryId($this->_request['IdToEdit']);

                $this->model->reviewUpdateById($params, $this->_request['IdToEdit']);

                $testimonialImage           = $dataBeforeUpdate['testimonialImage'];

                $actMsg['editid']           = $this->_request['IdToEdit'];
                $actMsg['message']          = 'Data updated successfully.';
            }
            else {
                $params['entryDate']        = date('Y-m-d H:i:s');
                $actMsg['editid']           = $this->model->newReview($params);

                $actMsg['message']          = 'Data inserted successfully.';
            }
            $actMsg['type']                 = 1;

            $settings = $this->model->settings($this->_request['pageType']);
            $settings = unserialize($settings['value']);

            $targetLocation = MEDIA_FILES_ROOT.DS.$this->_request['pageType'];
            $targetFile     = MEDIA_FILES_SRC.DS.$this->_request['pageType'];
            $ogUrl          = DS.$this->_request['pageType'];

            if (!file_exists($targetLocation) && !is_dir($targetLocation)) 
                $this->createMedia($targetLocation);
                
            $selData           = $this->model-> reviewByIdMenucategoryId($actMsg['editid']);

            //Image ---------------
            if($_FILES['testimonialImage']['name'] && substr($_FILES['testimonialImage']['type'], 0, 5) == 'image') {

                $fObj           = new FileUpload;

                $TWH[0]         = $settings['avatarWidth'];     // thumb width
                $TWH[1]         = $settings['avatarHeight'];    // thumb height
                $LWH[0]         = $settings['avatarWidth'];     // large width
                $LWH[1]         = $settings['avatarHeight'];    // large height
                $option         = 'thumbnail';                  // upload, thumbnail, resize, all

                $fileName 		= $permalink."-avatar";
                if($fileName = $fObj->uploadImage($_FILES['testimonialImage'], $targetLocation, $fileName, $TWH, $LWH, $option)) {

                    // delete existing image
                    if($dataBeforeUpdate['testimonialImage'] != $fileName) {
                        @unlink($targetLocation.'/normal/'.$dataBeforeUpdate['testimonialImage']);
                        @unlink($targetLocation.'/thumb/'.$dataBeforeUpdate['testimonialImage']);
                        @unlink($targetLocation.'/large/'.$dataBeforeUpdate['testimonialImage']);
                    }
                    // update new image
                    $params                     = array();
                    $params['testimonialImage']	= $fileName;
                    $testimonialImage           = $fileName;
                    $this->model->reviewUpdateById($params, $actMsg['editid']);
                }
            }
            //Image ---------------
                
                
            //SEO -----------------
            $ogImage                        = ($testimonialImage != '') ? $ogUrl."/large/".$testimonialImage : '';
            $pageUrl                        = $permalink.'/'.$selData['menuPermalink'];
            
            $titleandMetaUrl                = '/'.$pageUrl.'/';

            if(!$pageTitleText)
                $pageTitleText              = $authorName;
            
            $seoModel                       = new TitlemetaModel;

            if($this->_request['IdToEdit'] && $dataBeforeUpdate['permalink'] != $permalink)
                $handler                    = str_replace('/'.$permalink.'/', '/'.$dataBeforeUpdate['permalink'].'/', $titleandMetaUrl);
            else
                $handler                    = $titleandMetaUrl;

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
        else
           $actMsg['message']        = 'Fields marked with (*) are mandatory.';
        
		return $actMsg;
    }
    
    function createMedia($targetLocation) {
        $indexingSource = MEDIA_FILES_ROOT.DS.'index.php';
        @mkdir($targetLocation, 0755); 
        copy($indexingSource, $targetLocation.DS.'index.php');

        @mkdir($targetLocation.DS.'large',      0755); 
        copy($indexingSource, $targetLocation.DS.'large'.DS.'index.php');

        @mkdir($targetLocation.DS.'normal',     0755); 
        copy($indexingSource, $targetLocation.DS.'normal'.DS.'index.php');

        @mkdir($targetLocation.DS.'small',      0755);   
        copy($indexingSource, $targetLocation.DS.'small'.DS.'index.php');

        @mkdir($targetLocation.DS.'thumb',      0755); 
        copy($indexingSource, $targetLocation.DS.'thumb'.DS.'index.php');
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
                    $selData = $this->model->reviewById($val);
                    if($selData['testimonialImage']) {
                        @unlink(MEDIA_FILES_ROOT.'/'.$this->_request['pageType'].'/normal/'.$selData['testimonialImage']);
                        @unlink(MEDIA_FILES_ROOT.'/'.$this->_request['pageType'].'/thumb/'.$selData['testimonialImage']);
                        @unlink(MEDIA_FILES_ROOT.'/'.$this->_request['pageType'].'/large/'.$selData['testimonialImage']);
                    }
                                    
                    $this->model->deleteReview($val);
                }
                else
                    $this->model->reviewUpdateById($params, $val);
                
                $actMsg['type']           = 1;
                $actMsg['message']        = 'Operation successful.';
            }
        }
        
        return $actMsg;
    }

    function delete() {
        $actMsg['type']           = 0;
        $actMsg['message']        = '';
        
        if($this->_request['IdToEdit']){
            $selData = $this->model->reviewById($this->_request['IdToEdit']);
            
            if($selData['testimonialImage']) {
                @unlink(MEDIA_FILES_ROOT.'/'.$this->_request['pageType'].'/normal/'.$selData['testimonialImage']);
                @unlink(MEDIA_FILES_ROOT.'/'.$this->_request['pageType'].'/thumb/'.$selData['testimonialImage']);
                @unlink(MEDIA_FILES_ROOT.'/'.$this->_request['pageType'].'/large/'.$selData['testimonialImage']);
            }

            $this->model->deleteReview($this->_request['IdToEdit']);
            
            $actMsg['type']           = 1;
            $actMsg['message']        = 'Operation successful.';
            
            $this->model->redirectToURL(SITE_ADMIN_PATH.'/index.php?pageType='.$this->_request['pageType'].'&dtls='.$this->_request['dtls'].'&editid='.$this->_request['editid']);
        }
        else{
            $actMsg['message']        = 'Something went wrong. Please close your browser window and try again.';
        }
        return $actMsg;  
    }
                      
    function deleteImg() {
        $actMsg['type']           = 0;
        $actMsg['message']        = '';
        
        if($this->_request['IdToEdit']){
            $selData = $this->model->reviewById($this->_request['IdToEdit']);
            //showarray($selData);exit;
            if($selData['testimonialImage']) {
                @unlink(MEDIA_FILES_ROOT.'/'.$this->_request['pageType'].'/normal/'.$selData['testimonialImage']);
                @unlink(MEDIA_FILES_ROOT.'/'.$this->_request['pageType'].'/thumb/'.$selData['testimonialImage']);
                @unlink(MEDIA_FILES_ROOT.'/'.$this->_request['pageType'].'/large/'.$selData['testimonialImage']);
            }

            $params                     = array();
            $params['testimonialImage']	    = '';
            $this->model->reviewUpdateById($params, $this->_request['IdToEdit']);
            
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
            $this->model->reviewUpdateById($params, $recordID);
            $listingCounter = $listingCounter + 1;
        }
        
        if($listingCounter > 1){
            $actMsg['type']             = 1;
            $actMsg['message']          = 'Operation successful.';
        }
        
        return $actMsg;
    }
    
    function modPage(){
        $srch = trim($this->_request['srch']);
        if($srch) {
            return $this->model->searchLinkedPages($this->_request['mid'], $this->_request['pageType'], $srch, 0, 10);
        }
    }
}
?>