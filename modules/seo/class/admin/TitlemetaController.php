<?php 
defined('BASE') OR exit('No direct script access allowed.');
class TitlemetaController  extends REST
{
	private    $model;
	protected  $response = array();
	
    public function __construct($model) {
    	parent::__construct();
        $this->model        = new $model;
    }
    
	function index($act = []) {
        
        if(isset($this->_request['editid']) || isset($act['editid']) || $this->_request['dtaction'] == 'add') {
            
            $editid       = ($this->_request['editid'])? $this->_request['editid']:$act['editid'];
            
            if($editid)
                $this->response['titlemeta'] = $this->model->titleMetaById($editid);
        }
        else {
            
            $ExtraQryStr                 = "titleandMetaType = 'O'";
            
            // SEARCH START --------------------------------------------------------------
            if(isset($this->_request['searchText']))
                $this->session->write('searchText', $this->_request['searchText']);

            if($this->session->read('searchText'))
                $ExtraQryStr        .= " AND titleandMetaUrl LIKE '%".addslashes($this->session->read('searchText'))."%'";

            if(isset($this->_request['searchRobots']))
                $this->session->write('searchRobots', $this->_request['searchRobots']);

            if($this->session->read('searchRobots'))
                $ExtraQryStr        .= " AND metaRobots = '".addslashes($this->session->read('searchRobots'))."'";

            if(isset($this->_request['Reset']) || isset($this->_request['Search'])) {
                
                if(isset($this->_request['Reset'])){
                    $ExtraQryStr     = "titleandMetaType = 'O'";

                    $this->session->write('searchText',     '');
                    $this->session->write('searchRobots',   '');
                }
                
                $this->model->redirectToUrl(SITE_ADMIN_PATH.'/index.php?pageType='.$this->_request['pageType'].'&dtls='.$this->_request['dtls'].'&moduleId='.$this->_request['moduleId']);
            }
            // SEARCH END ----------------------------------------------------------------
            
            $this->response['rowCount']  = $this->model->countPageTitleandMeta($this->session->read('SITEID'), $ExtraQryStr);
            
            if($this->response['rowCount']) {
                
                $p                           = new Pager;
                $this->response['limit']     = VALUE_PER_PAGE;
                $start                       = $p->findStart($this->response['limit'], $this->_request['page']);
                $pages                       = $p->findPages($this->response['rowCount'], $this->response['limit']);

                $this->response['titlemeta'] = $this->model->getPageTitleandMetaBysiteId($this->session->read('SITEID'), $ExtraQryStr, $start, $this->response['limit']);

                if($this->response['rowCount'] > 0 && ceil($this->response['rowCount'] / $this->response['limit']) > 1) {
                    $this->response['page']      = ($this->_request['page']) ? $this->_request['page'] : 1;
                    $this->response['totalPage'] = ceil($this->response['rowCount'] / $this->response['limit']);

                    $this->response['pageList']  = $p->pageList($this->response['page'], $_SERVER['REQUEST_URI'], $pages);
                }
            }
        }
        
        return $this->response;
    }
    
    function addTitleMeta() {
        $actMsg['type']             = 0;
        $actMsg['message']          = '';
        
        $pageTitleText              = trim($this->_request['pageTitleText']);
        $titleandMetaUrl            = trim($this->_request['titleandMetaUrl']);
        
        $metaRobotsIndex            = trim($this->_request['metaRobotsIndex']);
        $metaRobotsFollow           = trim($this->_request['metaRobotsFollow']);
        
        $metaTag                    = trim($this->_request['metaTag']);
        $metaDescription            = trim($this->_request['metaDescription']);
        
        $canonicalUrl               = trim($this->_request['canonicalUrl']);
        
        if($pageTitleText != '' && $titleandMetaUrl != '') {

            $params = array();
            $params['pageTitleText']        = $pageTitleText;
            $params['titleandMetaUrl']      = $titleandMetaUrl;
            $params['metaTag']              = $metaTag;
            $params['metaDescription']      = $metaDescription;
            if($metaRobotsIndex == 'default' && $metaRobotsFollow == 'nofollow')
                $params['metaRobots']       = 'index, '.$metaRobotsFollow;
            else
                $params['metaRobots']       = $metaRobotsIndex.', '.$metaRobotsFollow;
            
            $params['canonicalUrl']         = $canonicalUrl;

            $actMsg['type']                 = 1;
            
            if($this->_request['IdToEdit'] != '') {
                $this->model->titleMetaUpdateById($params, $this->_request['IdToEdit']);
                
                $actMsg['editid']           = $this->_request['IdToEdit'];
                $actMsg['message']          = 'Data updated successfully.';
            } else {
                $params['siteId']           = $this->session->read('SITEID');
                $params['titleandMetaType'] = 'O';

                $actMsg['editid']           = $this->model->newTitleMeta($params);
                $actMsg['message']          = 'Data inserted successfully.';
            }

            if($_FILES['ogImage']['name'] && substr($_FILES['ogImage']['type'], 0, 5) == 'image') {

                $fObj = new FileUpload;
                        
                $targetLocation = MEDIA_FILES_ROOT.DS.'ogimage';
                $ogUrl          = DS.'ogimage'.DS;

                if (!file_exists($targetLocation) && !is_dir($targetLocation)) 
                    $this->createMedia($targetLocation);

                $fileName      = $_FILES['ogImage']['name'];

                if($upload = $fObj->moveUploadedFile($_FILES['ogImage'], $targetLocation.DS.$fileName)){
                    
                    $selData = $this->model->titleMetaById($actMsg['editid']);
                    $ogImage = explode(DS, $selData['ogImage']);
                    
                    if($ogImage[1] == 'ogimage')
                        @unlink(MEDIA_FILES_ROOT.$selData['ogImage']);

                    $params = array();
                    $params['ogImage']        = $ogUrl.$fileName;
                    $this->model->titleMetaUpdateById($params, $actMsg['editid']);
                }
            }
        }
        else
            $actMsg['message'] = 'Fields marked with (*) are mandatory.';
        
        return $actMsg;
    }
    
    function createMedia($targetLocation) {
        $indexingSource = MEDIA_FILES_ROOT.DS.'index.php';
        @mkdir($targetLocation, 0755); 
        copy($indexingSource, $targetLocation.DS.'index.php');
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
                    $this->model->deleteTitleMetaById($val);
                else
                    $this->model->titleMetaUpdateById($params, $val);
                
                $actMsg['type']           = 1;
                $actMsg['message']        = 'Operation successful.';
            }
        }
        
        return $actMsg;
    }

    function deleteFile(){
        $actMsg['type']           = 0;
        $actMsg['message']        = '';

        if($this->_request['DeleteFile'] == 'ogImage'){
            $selData = $this->model->titleMetaById($this->_request['IdToEdit']);
            if($selData['ogImage']){
                $ogImage = explode(DS, $selData['ogImage']);
                
                if($ogImage[1] == 'ogimage')
                    @unlink(MEDIA_FILES_ROOT.$selData['ogImage']);

                // update image field to blank
                $params                = array();
                $params['ogImage']     = '';
                $this->model->titleMetaUpdateById($params, $this->_request['IdToEdit']);
            }

            $actMsg['type']           = 1;
            $actMsg['message']        = 'ogImage deleted successfully.';
        }

        return $actMsg;  
    }
}
?>