<?php 
defined('BASE') OR exit('No direct script access allowed.');
class SocialController  extends REST
{
	private    $model;
	protected  $response = array();
	
    public function __construct($model) {
    	parent::__construct();
        $this->model        = new $model;
    }
    
	function index() {
        
        if($this->_request['dtaction']) {
            
            $arr                                = array('socialLinks');
            $gvars                              = new GlobalArray($arr);
            $this->response['socialLinks']      = $gvars->_array['socialLinks'];
        }
        
        if(isset($this->_request['editid']) || isset($this->response['act']['editid'])) {
            
            $editid       = ($this->_request['editid'])? $this->_request['editid']:$this->response['act']['editid'];
            if($this->_request['dtaction'] == 'add')
                $this->response['socialSite']   = $this->model->socialSiteById($editid);
            
        }
        else {
            
            $ExtraQryStr             = 1;
            
            // SEARCH START --------------------------------------------------------------
            if(isset($this->_request['searchText']))
                $this->session->write('searchText', $this->_request['searchText']);

            if($this->session->read('searchText'))
                $ExtraQryStr        .= " AND (socialName LIKE '%".addslashes($this->session->read('searchText'))."%' OR socialLink LIKE '%".addslashes($this->session->read('searchText'))."%')";

            if(isset($this->_request['searchStatus']))
                $this->session->write('searchStatus', $this->_request['searchStatus']);

            if($this->session->read('searchStatus'))
                $ExtraQryStr        .= " AND status = '".$this->session->read('searchStatus')."'";

            if(isset($this->_request['Reset']) || isset($this->_request['Search'])) {
                
                if(isset($this->_request['Reset'])){
                    $ExtraQryStr     = 1;

                    $this->session->write('searchText',     '');
                    $this->session->write('searchStatus',   '');
                }
                
                $this->model->redirectToUrl(SITE_ADMIN_PATH.'/index.php?pageType='.$this->_request['pageType'].'&dtls='.$this->_request['dtls'].'&moduleId='.$this->_request['moduleId']);
            }
            // SEARCH END ----------------------------------------------------------------
            
            $this->response['rowCount']      = $this->model->socialSiteCount($ExtraQryStr);
            
            if($this->response['rowCount']) {
                $p                             = new Pager;
                $this->response['limit']       = VALUE_PER_PAGE;
                $start                         = $p->findStart($this->response['limit'], $this->_request['page']);
                $pages                         = $p->findPages($this->response['rowCount'], $this->response['limit']);

                $this->response['socialSites'] = $this->model->getSocialSite($ExtraQryStr, $start, $this->response['limit']);
                
                if($this->response['rowCount'] > 0 && ceil($this->response['rowCount'] / $this->response['limit']) > 1) {
                    $this->response['page']      = ($this->_request['page']) ? $this->_request['page'] : 1;
                    $this->response['totalPage'] = ceil($this->response['rowCount'] / $this->response['limit']);

                    $this->response['pageList']  = $p->pageList($this->response['page'], $_SERVER['REQUEST_URI'], $pages);
                }
            }
        }
        
        return $this->response;
    }
    
    function addSocialSite() {
        $actMsg['type']             = 0;
        $actMsg['message']          = '';
        
        $socialName                 = trim($this->_request['socialName']);
        $socialLink                 = trim($this->_request['socialLink']);
        $status                     = trim($this->_request['status']);
        
        if($socialName != '' && $socialLink != '') {

            $params = array();
            $params['socialName']           = $socialName;
            $params['socialLink']           = $socialLink;
            $params['status']               = $status;
            
            if($this->_request['IdToEdit'] != '') {
                $this->model->socialSiteUpdateById($params, $this->_request['IdToEdit']);
                
                $actMsg['editid']           = $this->_request['IdToEdit'];
                $actMsg['message']          = 'Data updated successfully.';
            } else {
                $params['displayOrder']     = '0';
                $params['entryDate']        = date('Y-m-d H:i:s');

                $actMsg['editid']           = $this->model->newSocialSite($params);
                $actMsg['message']          = 'Data inserted successfully.';
            }

            $actMsg['type']                 = 1;
        }
        else
            $actMsg['message'] = 'Fields marked with (*) are mandatory.';
        
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
                
                if($params['delete'] == 'Y')
                    $this->model->deleteSocialSiteById($val);
                else
                    $this->model->socialSiteUpdateById($params, $val);
                
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
            $this->model->socialSiteUpdateById($params, $recordID);
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