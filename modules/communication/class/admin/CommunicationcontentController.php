<?php 
defined('BASE') OR exit('No direct script access allowed.');
class CommunicationcontentController  extends REST
{
	private    $model;
	protected  $response = array();
	
    public function __construct($model) {
    	parent::__construct();
        $this->model        = new $model;
    }
    
	function index($act = []) {
        
        $this->response['linkedPages'] = $this->model->getLinkedPages($this->_request['pageType'], 0, 100);
        
        if($this->_request['editid'] || isset($act)) {
            
            $editid = ($this->_request['editid'])? $this->_request['editid']:$act['editid'];
            
            if($editid)
                $this->response['content'] = $this->model->getContentBycontentID($editid);
            
            if($this->response['content']['seoId']) {
                $seoModel                   = new TitlemetaModel;
                $this->response['seoData']  = $seoModel->titleMetaById($this->response['content']['seoId']);
            }
        } else {
            
            $menucategoryIdArray = array();
            
            foreach($this->response['linkedPages'] as $linkedPage) {
                $menucategoryIdArray[]      = $linkedPage['categoryId'];
            }
            
            if(sizeof($menucategoryIdArray) > 0){
                
                $ExtraQryStr                = 1;
                
                // SEARCH START --------------------------------------------------------------
                if(isset($this->_request['searchText']))
                    $this->session->write('searchText', $this->_request['searchText']);

                if($this->session->read('searchText'))
                    $ExtraQryStr        .= " AND (tc.contentHeading LIKE '%".addslashes($this->session->read('searchText'))."%' OR tc.subHeading LIKE '%".addslashes($this->session->read('searchText'))."%' OR tc.contentDescription LIKE '%".addslashes($this->session->read('searchText'))."%')";

                if(isset($this->_request['searchPage']))
                    $this->session->write('searchPage', $this->_request['searchPage']);

                if($this->session->read('searchPage'))
                    $ExtraQryStr        .= " AND tmc.permalink = '".addslashes($this->session->read('searchPage'))."'";

                if(isset($this->_request['Reset']) || isset($this->_request['Search'])) {

                    if(isset($this->_request['Reset'])) {
                        $ExtraQryStr     = 1;

                        $this->session->write('searchText',     '');
                        $this->session->write('searchPage',     '');
                    }

                    $this->model->redirectToUrl(SITE_ADMIN_PATH.'/index.php?pageType='.$this->_request['pageType'].'&dtls='.$this->_request['dtls'].'&moduleId='.$this->_request['moduleId']);
                }
                // SEARCH END ----------------------------------------------------------------
                
                $this->response['rowCount']          = $this->model->contentCount(implode(',', $menucategoryIdArray), $ExtraQryStr);
                
                if($this->response['rowCount']) {
                    
                    $p                               = new Pager;
                    $this->response['limit']         = VALUE_PER_PAGE;
                    $start                           = $p->findStart($this->response['limit'], $this->_request['page']);
                    $pages                           = $p->findPages($this->response['rowCount'], $this->response['limit']);
                    
                    $this->response['contentList']   = $this->model->getContentList(implode(',', $menucategoryIdArray), $ExtraQryStr, $start, $this->response['limit']);
                    
                    if($this->response['rowCount'] > 0 && ceil($this->response['rowCount'] / $this->response['limit']) > 1) {
                        $this->response['page']      = ($this->_request['page']) ? $this->_request['page'] : 1;
                        $this->response['totalPage'] = ceil($this->response['rowCount'] / $this->response['limit']);

                        $this->response['pageList']  = $p->pageList($this->response['page'], $_SERVER['REQUEST_URI'], $pages);
                    }
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
        $displayHeading             = trim($this->_request['displayHeading']);
        $subHeading                 = trim($this->_request['subHeading']);
        $contentDescription         = trim($this->_request['contentDescription']);
        $contentShortDescription    = trim($this->_request['contentShortDescription']);
        $displayOrder               = trim($this->_request['displayOrder']);
        $contentStatus              = trim($this->_request['contentStatus']);
        
        $address                    = trim($this->_request['txtAddress']);
        $email                      = trim($this->_request['txtEmail']);
        $contactNo                  = trim($this->_request['txtContactNo']);
        $extraData                  = array('address'=>$address, 'email'=>$email, 'contactno'=>$contactNo);
    
        if($menucategoryId && $contentHeading) {

            //permalink--------------
            $ENTITY          = TBL_CONTENT;
            $permalink       = $contentHeading;

            if($this->_request['IdToEdit'])	
                $ExtraQryStr = 'contentID != '.addslashes($this->_request['IdToEdit']).' AND menucategoryId = '.addslashes($menucategoryId);
            else	
                $ExtraQryStr = 'menucategoryId = '.addslashes($menucategoryId);
            $permalink       = createPermalink($ENTITY, $permalink, $ExtraQryStr);
            //permalink---------------

            $ExtraString = "contentHeading = '".addslashes($contentHeading)."'";

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
                $params['serializedData']           = serialize($extraData);
                
                if($displayOrder == 'T' || $displayOrder == 'B'){
                    $order          = $this->model->getDisplayOrder($menucategoryId, $displayOrder);
                    $displayOrder   = ($displayOrder == 'T')? ($order['displayOrder'] - 1) : ($order['displayOrder'] + 1);
                }
                
                $params['displayOrder']             = $displayOrder;

                if($this->_request['IdToEdit']) {

                    $this->model->contentUpdateBycontentID($params, $this->_request['IdToEdit']);

                    $actMsg['editid']  = $this->_request['IdToEdit'];
                    $actMsg['type']    = 1;
                    $actMsg['message'] = 'Data updated successfully.';
                }
                else{
                    $actMsg['editid']  = $this->model->newContent($params);
                    
                    $actMsg['type']    = 1;
                    $actMsg['message'] = 'Data inserted successfully.';
                }
            }
            else
                $actMsg['message'] = 'Heading already exists.';
        }
        else
            $actMsg['message'] = 'Fields marked with (*) are mandatory.';

        
        return $actMsg;
    }
                      
    function delete() {
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
            
            $this->model->redirectToURL(SITE_ADMIN_PATH.'/index.php?pageType='.$this->_request['pageType'].'&dtls='.$this->_request['dtls'].'&moduleId='.$this->_request['moduleId']);
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
    
    function multiAction() {
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
    
    function modPage() {
        $srch   = trim($this->_request['srch']);
        
        if($srch) { 
            return $this->model->searchLinkedPages($this->_request['mid'], $this->_request['pageType'], $srch, 0, 10);
        }
    }
}
?>