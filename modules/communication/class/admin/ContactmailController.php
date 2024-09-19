<?php 
defined('BASE') OR exit('No direct script access allowed.');
class ContactmailController extends REST
{
	private    $model;
	protected  $response = array();
	
    public function __construct($model) {
    	parent::__construct();
        $this->model        = new $model;
    }
    
	function index() {
        
        if(isset($this->_request['editid']) || isset($this->response['act']['editid'])) {
            
            $editid       = ($this->_request['editid'])? $this->_request['editid']:$this->response['act']['editid'];
            
            if($this->_request['dtaction'] == 'add')
                $this->response['mail'] = $this->model->contactMailByIdAndSeen($editid);
        } else {
            
            $ExtraQryStr                 = "contactType = 'C'";
            
            // SEARCH START --------------------------------------------------------------
            if(isset($this->_request['searchText']))
                $this->session->write('searchText', $this->_request['searchText']);

            if($this->session->read('searchText'))
                $ExtraQryStr        .= " AND (name LIKE '%".addslashes($this->session->read('searchText'))."%' OR email LIKE '%".addslashes($this->session->read('searchText'))."%')";

            if(isset($this->_request['searchStatus']))
                $this->session->write('searchStatus', $this->_request['searchStatus']);

            if($this->session->read('searchStatus')) {
                if($this->session->read('searchStatus') == 'Y')
                    $ExtraQryStr        .= " AND seen != '0000-00-00 00:00:00'";
                else
                    $ExtraQryStr        .= " AND seen = '0000-00-00 00:00:00'";
            }

            if(isset($this->_request['Reset']) || isset($this->_request['Search'])) {
                
                if(isset($this->_request['Reset'])){
                    $ExtraQryStr     = "contactType = 'C'";

                    $this->session->write('searchText',     '');
                    $this->session->write('searchStatus',   '');
                }
                
                $this->model->redirectToUrl(SITE_ADMIN_PATH.'/index.php?pageType='.$this->_request['pageType'].'&dtls='.$this->_request['dtls'].'&moduleId='.$this->_request['moduleId']);
            }
            // SEARCH END ----------------------------------------------------------------
            
            $this->response['rowCount']     = $this->model->countContactMail($ExtraQryStr);
            
            if($this->response['rowCount']) {
                
                $p                          = new Pager;
                $this->response['limit']    = VALUE_PER_PAGE;
                $start                      = $p->findStart($this->response['limit'], $this->_request['page']);
                $pages                      = $p->findPages($this->response['rowCount'], $this->response['limit']);

                $this->response['mails']    = $this->model->getContactMail($ExtraQryStr, $start, $this->response['limit']);
            
                if($this->response['rowCount'] > 0 && ceil($this->response['rowCount'] / $this->response['limit']) > 1) {
                    $this->response['page']      = ($this->_request['page']) ? $this->_request['page'] : 1;
                    $this->response['totalPage'] = ceil($this->response['rowCount'] / $this->response['limit']);

                    $this->response['pageList']  = $p->pageList($this->response['page'], $_SERVER['REQUEST_URI'], $pages);
                }
            }
        }
        
        return $this->response;
    }
    
    function multiAction(){
        $actMsg['type']           = 0;
        $actMsg['message']        = '';
        
        if($this->_request['multiAction']){
            foreach($this->_request['selectMulti'] as $val) {
                
                $params = array();
                
                switch($this->_request['multiAction']) {
                    case "1":
                        $params['seen']   = date('Y-m-d H:i:s');
                        break;
                    case "2":
                        $params['seen']   = '';
                        break;
                    case "3":
                        $params['delete'] = 'Y';
                        break;
                    default:
                        $this->response('', 406);
                }
                
                if($params['delete'] == 'Y')
                    $this->model->deleteContactMailById($val);
                else
                    $this->model->contactMailUpdateById($params, $val);
                
                $actMsg['type']           = 1;
                $actMsg['message']        = 'Operation successful.';
            }
        }
        
        return $actMsg;
    }
    
    function quickView(){
        $act['message'] = '';
        $act['type']    = 0;
        
        if($this->_request['editid']){
            $act['mail'] = $this->model->contactMailByIdAndSeen($this->_request['editid']);
            $act['type'] = 1;
        }
        
        return $act;
    }
}
?>