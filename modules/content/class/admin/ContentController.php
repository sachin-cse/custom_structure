<?php
defined('BASE') OR exit('No direct script access allowed.');
class ContentController extends REST 
{    
    private    $model;
	protected  $response = array();
	
    public function __construct($model) {
    	parent::__construct();
        $this->model        = new $model;
    }
    
    function index($act = []) {

        if($this->_request['editid'] && $this->_request['editid'] != 'uncategorized' ){
            
            $this->response['pageData'] = $this->model->categoryById($this->_request['editid']);
            $this->response['subPages'] = $this->model->cmsCategoryByparentId($this->_request['editid']);	
        }
        
        if($this->_request['contentID'] || isset($act['contentID'])){
            
            $contentID = ($this->_request['contentID'])? $this->_request['contentID']:$act['contentID'];
            
            if($contentID)
                $this->response['content'] = $this->model->getContentBycontentID($contentID);
            
            /*if($this->response['content']['seoId']) {
                $seoModel                   = new TitlemetaModel;
                $this->response['seoData']  = $seoModel->titleMetaById($this->response['content']['seoId']);
            }*/
        }
        else{
            
            $ExtraQryStr    = 1;
            
            // SEARCH START --------------------------------------------------------------
            if(isset($this->_request['searchText']))
                $this->session->write('searchText', $this->_request['searchText']);

            if($this->session->read('searchText'))
                $ExtraQryStr        .= " AND (contentHeading LIKE '%".addslashes($this->session->read('searchText'))."%' OR subHeading LIKE '%".addslashes($this->session->read('searchText'))."%' OR contentDescription LIKE '%".addslashes($this->session->read('searchText'))."%')";

            if(isset($this->_request['searchStatus']))
                $this->session->write('searchStatus', $this->_request['searchStatus']);

            if($this->session->read('searchStatus'))
                $ExtraQryStr        .= " AND contentStatus = '".$this->session->read('searchStatus')."'";

            if(isset($this->_request['Reset']) || isset($this->_request['Search'])) {
                
                if(isset($this->_request['Reset'])){
                    $ExtraQryStr     = 1;

                    $this->session->write('searchText',     '');
                    $this->session->write('searchStatus',   '');
                }
                
                $this->model->redirectToUrl(SITE_ADMIN_PATH.'/index.php?pageType='.$this->_request['pageType'].'&dtls='.$this->_request['dtls'].'&editid='.$this->_request['editid']);
            }
            // SEARCH END ----------------------------------------------------------------
            
            $this->response['rowCount']      = $this->model->contentCountBymenucategoryId($this->_request['editid'], $ExtraQryStr);
 
            if($this->response['rowCount']) {
                
                $p                             = new Pager;
                $this->response['limit']       = VALUE_PER_PAGE;
                $start                         = $p->findStart($this->response['limit'], $this->_request['page']);
                $pages                         = $p->findPages($this->response['rowCount'], $this->response['limit']);

                $this->response['contentList'] = $this->model->getContentListBymenucategoryId($this->_request['editid'], $ExtraQryStr, $start, $this->response['limit']);
               
                if($this->response['rowCount'] > 0 && ceil($this->response['rowCount'] / $this->response['limit']) > 1) {
                    $this->response['page']      = ($this->_request['page']) ? $this->_request['page'] : 1;
                    $this->response['totalPage'] = ceil($this->response['rowCount'] / $this->response['limit']);

                    $this->response['pageList']  = $p->pageList($this->response['page'], $_SERVER['REQUEST_URI'], $pages);
                }
            }
        }
        
        return $this->response;
    }
    
    function addEditContent() {
        $actMsg['type']             = 0;
        $actMsg['message']          = '';
        
        $menucategoryId             = trim($this->_request['menucategoryId']);
        $contentHeading             = trim($this->_request['contentHeading']);
        $subHeading                 = trim($this->_request['subHeading']);
        $displayHeading             = trim($this->_request['displayHeading']);
        $contentDescription         = trim($this->_request['contentDescription']);
        $contentShortDescription    = trim($this->_request['contentShortDescription']);
        $displayOrder               = trim($this->_request['displayOrder']);
        $contentStatus              = trim($this->_request['contentStatus']);
        
        /*$pageTitleText              = trim($this->_request['pageTitleText']);
        $titleandMetaUrl            = trim($this->_request['titleandMetaUrl']);
        $metaRobotsIndex            = trim($this->_request['metaRobotsIndex']);
        $metaRobotsFollow           = trim($this->_request['metaRobotsFollow']);
        $metaTag                    = trim($this->_request['metaTag']);
        $metaDescription            = trim($this->_request['metaDescription']);
        $others                     = trim($this->_request['others']);*/

        if($contentHeading && $contentDescription) {

            //permalink--------------
            $ENTITY          = TBL_CONTENT;
            $permalink       = $contentHeading;

            if($this->_request['IdToEdit'])	
                $ExtraQryStr = "contentID != ".addslashes($this->_request['IdToEdit'])." AND menucategoryId = '".addslashes($menucategoryId)."'";
            else	
                $ExtraQryStr = "menucategoryId = '".addslashes($menucategoryId)."'";
            $permalink       = createPermalink($ENTITY, $permalink, $ExtraQryStr);
            //permalink---------------

            $ExtraString = "contentHeading = '".addslashes($contentHeading)."' AND menucategoryId = '".addslashes($menucategoryId)."'";

            if($this->_request['IdToEdit']!='')
                $ExtraString .= " AND contentID != ".addslashes($this->_request['IdToEdit']);

            $sel_ContentDetails = $this->model->checkExistence($ExtraString);

            if(!$sel_ContentDetails) {
                
                $params                             = array();
                
                $params['menucategoryId']           = $menucategoryId;
                $params['contentHeading']           = $contentHeading;
                $params['permalink']                = $permalink;
                $params['subHeading']               = $subHeading;
                $params['displayHeading']           = $displayHeading;
                $params['contentDescription']       = $contentDescription;
                $params['contentShortDescription']  = $contentShortDescription;
                $params['contentStatus']            = $contentStatus;
                
                if($displayOrder == 'T' || $displayOrder == 'B'){
                    $order          = $this->model->getDisplayOrder($menucategoryId, $displayOrder);
                    $displayOrder   = ($displayOrder == 'T')? ($order['displayOrder'] - 1) : ($order['displayOrder'] + 1);
                }
                
                $params['displayOrder']             = $displayOrder;

                if($this->_request['IdToEdit']) {
                    //$dataBeforeUpdate   = $this->model-> getContentBycontentID($this->_request['IdToEdit']);

                    $this->model->contentUpdateBycontentID($params, $this->_request['IdToEdit']);

                    $actMsg['contentID']  = $this->_request['IdToEdit'];
                    $actMsg['type']    = 1;
                    $actMsg['message'] = 'Data updated successfully.';
                }
                else{
                    $actMsg['contentID']  = $this->model->newContent($params);
                    
                    $actMsg['type']    = 1;
                    $actMsg['message'] = 'Data inserted successfully.';
                }

                //Image ------------------------------------------------------------------------
                if($_FILES['ImageName']['name'] && substr($_FILES['ImageName']['type'], 0, 5) == 'image') {
                    
                    $selData        = $this->model->getContentBycontentID($actMsg['contentID']);
                    $existingImage  = $selData['ImageName'];
                    
                    $fObj           = new FileUpload;
                    $targetLocation = MEDIA_FILES_ROOT.'/content';
                    $TWH[0]         = 630;     // thumb width
                    $TWH[1]         = 425;     // thumb height
                    $LWH[0]         = 630;     // large width
                    $LWH[1]         = 425;     // large height

                    $fileName = $permalink;
                    
                    if($target_image = $fObj->uploadImage($_FILES['ImageName'], $targetLocation, $fileName, $TWH, $LWH)) {
                            
                        if($existingImage != $target_image) {
                            @unlink($targetLocation.'/normal/'.$existingImage);
                            @unlink($targetLocation.'/thumb/'.$existingImage);
                            @unlink($targetLocation.'/large/'.$existingImage);
                        }

                        $params               = array();
                        $params['ImageName']  = $target_image;
                        $this->model->contentUpdateBycontentID($params, $actMsg['contentID']);
                    }
                }
                //Image ------------------------------------------------------------------------
                
                
                /*SEO --------------------------------------------------------------------------
                $ogImage                        = ($target_image != '') ? $targetFile."/thumb/".$target_image : '';
                $parentCatArray                 = [];
                if($menucategoryId) {
                    $cparentId                  = $menucategoryId;	
                    
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
                    
                    $this->model->contentUpdateBycontentID($params, $actMsg['contentID']);
                }
                // ---------------------------------------------------------------------------*/
            }
            else
                $actMsg['message'] = 'Heading already exists.';
        }
        else
            $actMsg['message'] = 'Fields marked with (*) are mandatory.';

        
        return $actMsg;
    }
                      
    function delete(){
        $actMsg['type']           = 0;
        $actMsg['message']        = '';
        
        if($this->_request['IdToEdit']){
            $selData = $this->model->getContentBycontentID($this->_request['IdToEdit']);
            if($selData['ImageName']) {
                @unlink(MEDIA_FILES_ROOT.'/content/normal/'.$selData['ImageName']);
                @unlink(MEDIA_FILES_ROOT.'/content/thumb/'.$selData['ImageName']);
                @unlink(MEDIA_FILES_ROOT.'/content/large/'.$selData['ImageName']);
            }

            $this->model->deleteContentByid($this->_request['IdToEdit']);
            
            $actMsg['type']           = 1;
            $actMsg['message']        = 'Operation successful.';
            
            $this->model->redirectToURL(SITE_ADMIN_PATH.'/index.php?pageType='.$this->_request['pageType'].'&dtls='.$this->_request['dtls'].'&editid='.$this->_request['editid']);
        }
        else{
            $actMsg['message']        = 'Something went wrong. Please close your browser window and try again.';
        }
        return $actMsg;  
    }
                      
    function deleteImg(){
        $actMsg['type']           = 0;
        $actMsg['message']        = '';
        
        if($this->_request['IdToEdit']){
            $selData = $this->model->getContentBycontentID($this->_request['IdToEdit']);
            if($selData['ImageName']) {
                @unlink(MEDIA_FILES_ROOT.'/content/normal/'.$selData['ImageName']);
                @unlink(MEDIA_FILES_ROOT.'/content/thumb/'.$selData['ImageName']);
                @unlink(MEDIA_FILES_ROOT.'/content/large/'.$selData['ImageName']);
            }
            
            $actMsg['type']           = 1;
            $actMsg['message']        = 'Image deleted successfully.';
        }
        else{
            $actMsg['message']        = 'Something went wrong. Please close your browser window and try again.';
        }
        return $actMsg;  
    }
    
    function multiAction(){
        $actMsg['type']           = 0;
        $actMsg['message']        = '';
        
        if($this->_request['multiAction']){
            foreach($this->_request['selectMulti'] as $val) {
                
                $params = array();  
                
                switch($this->_request['multiAction']) {
                    case "1":
                        $params['contentStatus'] = 'Y';
                        break;
                    case "2":
                        $params['contentStatus'] = 'N';
                        break;
                    case "3":
                        $params['delete'] = 'Y';
                        break;
                    default:
                        $this->response('', 406);
                } 
                
                if($params['delete'] == 'Y') {
                    $selData = $this->model->getContentBycontentID($val);
                    if($selData['ImageName']) {
                        @unlink(MEDIA_FILES_ROOT.'/content/normal/'.$selData['ImageName']);
                        @unlink(MEDIA_FILES_ROOT.'/content/thumb/'.$selData['ImageName']);
                        @unlink(MEDIA_FILES_ROOT.'/content/large/'.$selData['ImageName']);
                    }

                    $this->model->deleteContentByid($val);
                }
                else
                    $this->model->contentUpdateBycontentID($params, $val);
                
                $actMsg['type']           = 1;
                $actMsg['message']        = 'Operation successful.';
            }
        }
        
        return $actMsg;
    }
    
    function showContent(){
        if($this->_request['editid'])
            $this->model->redirectToURL(SITE_ADMIN_PATH.'/index.php?pageType='.$this->_request['pageType'].'&dtls='.$this->_request['dtls'].'&editid='.$this->_request['editid']);
    }
    
    function swap() {
        $actMsg['type']             = 0;
        $actMsg['message']          = '';
        
        $listingCounter = 1;
        
        foreach ($this->_request['recordsArray'] as $recordID) {
            $params = array();
            $params['displayOrder'] = $listingCounter;
            $this->model->contentUpdateBycontentID($params, $recordID);
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