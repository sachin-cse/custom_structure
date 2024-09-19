<?php defined('BASE') OR exit('No direct script access allowed.');
class FaqlistController extends REST
{
    private    $model;
    protected  $response = [];

    public function __construct($model) {
        parent::__construct();
        $this->model        = new $model;
    }

    function index($act = []) {
        if(isset($this->_request['editid']) || isset($act['editid']) ||  $this->_request['dtaction'] == 'add') {
            $editid = ($this->_request['editid'])? $this->_request['editid']:$act['editid'];
            
            if($editid)
                $this->response['faq']		= $this->model->faqById($editid);            
        }
        else {
        
            $ExtraQryStr = 1;

            // SEARCH START --------------------------------------------------------------
            if(isset($this->_request['searchText']))
                $this->session->write('searchText', $this->_request['searchText']);

            if($this->session->read('searchText'))
                $ExtraQryStr        .= " AND faq_title LIKE '%".addslashes($this->session->read('searchText'))."%'";

            if(isset($this->_request['searchStatus']))
                $this->session->write('searchStatus', $this->_request['searchStatus']);

            if($this->session->read('searchStatus'))
                $ExtraQryStr        .= " AND faq_status = '".addslashes($this->session->read('searchStatus'))."'";

            if(isset($this->_request['Reset']) || isset($this->_request['Search'])) {

                if(isset($this->_request['Reset'])){

                    $this->session->write('searchText',     '');
                    $this->session->write('searchStatus',   '');
                }

                $this->model->redirectToUrl(SITE_ADMIN_PATH.'/index.php?pageType='.$this->_request['pageType'].'&dtls='.$this->_request['dtls'].'&moduleId='.$this->_request['moduleId']);
            }
            // SEARCH END ----------------------------------------------------------------

            $this->response['rowCount']     = $this->model->faqCount($ExtraQryStr);

            if($this->response['rowCount']) {

                $p                          = new Pager;
                $this->response['limit']    = VALUE_PER_PAGE;
                $start                      = $p->findStart($this->response['limit'], $this->_request['page']);
                $pages                      = $p->findPages($this->response['rowCount'], $this->response['limit']);

                $this->response['faqs']    	 = $this->model->getFaqByLimit($ExtraQryStr, $start, $this->response['limit']);

                if($this->response['rowCount'] > 0 && ceil($this->response['rowCount'] / $this->response['limit']) > 1) {
                    $this->response['page']      = ($this->_request['page']) ? $this->_request['page'] : 1;
                    $this->response['totalPage'] = ceil($this->response['rowCount'] / $this->response['limit']);

                    $this->response['pageList']  = $p->pageList($this->response['page'], $_SERVER['REQUEST_URI'], $pages);
                }
            }
        }
        
        $this->response['faqCat'] =     $this->model->getFaqcat();
        return $this->response;
    }

    function modPage() {
        $srch = trim($this->_request['srch']);

        if ($srch) {
            return $this->model->searchLinkedPages($this->_request['mid'], $this->_request['pageType'], $srch, 0, 10);
        }
    }

    function addEditFaq() {
        
        $actMsg['type']             = 0;
        $actMsg['message']          = '';
    
        $faqName           		= trim($this->_request['faqName']);
        $faqCatId               = trim($this->_request['faq_cat_id']);
        $permalink           		= trim($this->_request['permalink']);
        $faqDescription    		= trim($this->_request['faqDescription']);
        $displayOrder              = trim($this->_request['displayOrder']);
        $status                	= trim($this->_request['status']);
            
        if($faqName != '' && $permalink != '' && $faqCatId != '') {
            
            if($this->_request['IdToEdit']!= '')
                $sel_ContentDetails = $this->model->checkExistence("faq_title = '".addslashes($faqName)."' AND faq_id != ".$this->_request['IdToEdit']);
            else
                $sel_ContentDetails = $this->model->checkExistence("faq_title = '".addslashes($faqName)."'");

            if(sizeof($sel_ContentDetails) < 1) {
                //permalink--------------
                $ENTITY          = TBL_FAQ;
                if(!$permalink)
                    $permalink	 = $faqName;	
                else
                    $permalink   = str_replace('-',' ',$permalink);

                if($this->_request['IdToEdit'])
                    $ExtraQryStr = 'faq_id!='.$this->_request['IdToEdit'];	
                else
                    $ExtraQryStr = 1;
                $permalink       = createPermalink($ENTITY, $permalink, $ExtraQryStr);
                //permalink---------------

                $params                         = array();
                $params['faq_title']          	= $faqName;
                $params['faq_cat_id'] =            $faqCatId;
                $params['permalink']            = $permalink;
                $params['faq_description']   	= $faqDescription;
                $params['faq_status']             = $status;
                
                if($displayOrder == 'T' || $displayOrder == 'B'){
                    $order          = $this->model->getDisplayOrder($displayOrder);
                    $displayOrder   = ($displayOrder == 'T')? ($order['displayOrder'] - 1) : ($order['displayOrder'] + 1);
                }
                
                $params['displayOrder']             = $displayOrder;
                
                if($this->_request['IdToEdit'] != '') {
                    $this->model->faqUpdateById($params, $this->_request['IdToEdit']);

                    $actMsg['editid']           = $this->_request['IdToEdit'];
                    $actMsg['message']          = 'Data updated successfully.';
                }
                else {
                    $params['entryDate']        = date('Y-m-d H:i:s');
                    $actMsg['editid']           = $this->model->newFaq($params);

                    $actMsg['message']          = 'Data inserted successfully.';
                }
                    $actMsg['type']             = 1;
            }
            else
                $actMsg['message']        = 'Question already exists.';   
        }
        else
        $actMsg['message']        = 'Fields marked with (*) are mandatory.';
        
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
                $faq = $this->model->faqById($val);
                if($faq)
                    $this->model->deleteFaq($val);
            }
            else
                $this->model->faqUpdateById($params, $val);
            
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
        $this->model->faqUpdateById($params, $recordID);
        $listingCounter = $listingCounter + 1;
    }
    
    if($listingCounter > 1){
        $actMsg['type']             = 1;
        $actMsg['message']          = 'Operation successful.';
    }
    
    return $actMsg;
}


}