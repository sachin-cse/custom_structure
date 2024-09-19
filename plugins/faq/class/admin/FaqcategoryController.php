<?php defined('BASE') OR exit('No direct script access allowed.');
class FaqcategoryController extends REST
{

    private    $model;
	protected  $response = array();
	
    public function __construct($model) {
    	parent::__construct();
        $this->model        = new $model;
    }

    function index($act = []) {
        if(isset($this->_request['editid']) || isset($act['editid']) ||  $this->_request['dtaction'] == 'add') {
            $editid = ($this->_request['editid'])? $this->_request['editid']:$act['editid'];
            
            if($editid)
                $this->response['faqCat']		= $this->model->faqCatbyId($editid);            
        }
        else {
        
            $ExtraQryStr = 1;

            // SEARCH START --------------------------------------------------------------
            if(isset($this->_request['searchText']))
                $this->session->write('searchText', $this->_request['searchText']);

            if($this->session->read('searchText'))
                $ExtraQryStr        .= " AND faq_cat_name LIKE '%".addslashes($this->session->read('searchText'))."%'";

            if(isset($this->_request['searchStatus']))
                $this->session->write('searchStatus', $this->_request['searchStatus']);

            if($this->session->read('searchStatus'))
                $ExtraQryStr        .= " AND faq_cat_status = '".addslashes($this->session->read('searchStatus'))."'";

            if(isset($this->_request['Reset']) || isset($this->_request['Search'])) {

                if(isset($this->_request['Reset'])){

                    $this->session->write('searchText',     '');
                    $this->session->write('searchStatus',   '');
                }

                $this->model->redirectToUrl(SITE_ADMIN_PATH.'/index.php?pageType='.$this->_request['pageType'].'&dtls='.$this->_request['dtls'].'&moduleId='.$this->_request['moduleId']);
            }
            // SEARCH END ----------------------------------------------------------------

            $this->response['rowCount']     = $this->model->faqCatCount($ExtraQryStr);

            if($this->response['rowCount']) {

                $p                          = new Pager;
                $this->response['limit']    = VALUE_PER_PAGE;
                $start                      = $p->findStart($this->response['limit'], $this->_request['page']);
                $pages                      = $p->findPages($this->response['rowCount'], $this->response['limit']);

                $this->response['faqs']    	 = $this->model->getFaqCatByLimit($ExtraQryStr, $start, $this->response['limit']);

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

    function addEditFaqCate(){
        $actMsg['type']             = 0;
        $actMsg['message']          = '';
        
        $faqcatName           		= trim($this->_request['faq_cat_title']);
        $catpermalink           		= trim($this->_request['permalink']);
        $faqTag =                       trim($this->_request['faq_tag']);
        $faqDescription    		= trim($this->_request['faq_cat_description']);
        $displayOrder              = trim($this->_request['displayOrder']);
        $status                	= trim($this->_request['status']);
             
        if($faqcatName != '' && $catpermalink != '' && $faqTag != '') {

            //permalink--------------
            $ENTITY = TBL_FAQ_CATEGORY;
            if(!$catpermalink)
                $catpermalink   = $faqcatName;	
            else
                $catpermalink   = str_replace('-',' ',$catpermalink);

            if($this->_request['IdToEdit'])
                $ExtraQryStr = 'faq_cat_id != '.$this->_request['IdToEdit'];	
            else
                $ExtraQryStr = 1;
                $catpermalink   = createPermalink($ENTITY, $catpermalink, $ExtraQryStr);
            //permalink---------------

            $params                         = array();
            $params['faq_cat_title']          	= $faqcatName;
            $params['permalink']          	= $catpermalink;
            $params['faq_cat_description']= $faqDescription;
            $params['faq_tag']          =  $faqTag;
            $params['faq_cat_status']               = $status;
            
            if($displayOrder == 'T' || $displayOrder == 'B'){
                $order          = $this->model->getDisplayOrder($displayOrder);
                $displayOrder   = ($displayOrder == 'T')? ($order['displayOrder'] - 1) : ($order['displayOrder'] + 1);
            }
            
            $params['displayOrder']         = $displayOrder;
            
            if($this->_request['IdToEdit'] != '') {
                $dataBeforeUpdate           = $this->model->faqCatbyId($this->_request['IdToEdit']);

                $this->model->faqCatUpdateById($params, $this->_request['IdToEdit']);

                $faqcatImage           = $dataBeforeUpdate['faq_cat_img'];

                $actMsg['editid']           = $this->_request['IdToEdit'];
                $actMsg['message']          = 'Data updated successfully.';
            }
            else {
                $params['entryDate']        = date('Y-m-d H:i:s');
                $actMsg['editid']           = $this->model->newFaqCat($params);

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
                
            $selData           = $this->model->faqCatbyId($actMsg['editid']);

            //Image ---------------
            if($_FILES['faq_cat_img']['name'] && substr($_FILES['faq_cat_img']['type'], 0, 5) == 'image') {

                $fObj           = new FileUpload;

                $TWH[0]         = $settings['avatarWidth'];     // thumb width
                $TWH[1]         = $settings['avatarHeight'];    // thumb height
                $LWH[0]         = $settings['avatarWidth'];     // large width
                $LWH[1]         = $settings['avatarHeight'];    // large height
                $option         = 'thumbnail';                  // upload, thumbnail, resize, all

                $fileName 		= $catpermalink."-avatar-".time();
                if($fileName = $fObj->uploadImage($_FILES['faq_cat_img'], $targetLocation, $fileName, $TWH, $LWH, $option)) {

                    // delete existing image
                    if($dataBeforeUpdate['faq_cat_img'] != $fileName) {
                        @unlink($targetLocation.'/normal/'.$dataBeforeUpdate['faq_cat_img']);
                        @unlink($targetLocation.'/thumb/'.$dataBeforeUpdate['faq_cat_img']);
                        @unlink($targetLocation.'/large/'.$dataBeforeUpdate['faq_cat_img']);
                    }
                    // update new image
                    $params                     = array();
                    $params['faq_cat_img']	= $fileName;
                    $faqcatImage           = $fileName;
                    $this->model->faqCatUpdateById($params, $actMsg['editid']);
                }
            }
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
                    default:
                        $this->response('', 406);
                } 
                
                if($params['delete'] == 'Y') {
                    $faq = $this->model->faqCatbyId($val);
                    
                    if($faq)
                        $this->model->deleteFaqCat($val);
                }
                else
                    $this->model->faqCatUpdateById($params, $val);
                
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
            $this->model->faqCatUpdateById($params, $recordID);
            $listingCounter = $listingCounter + 1;
        }
        
        if($listingCounter > 1){
            $actMsg['type']             = 1;
            $actMsg['message']          = 'Operation successful.';
        }
        
        return $actMsg;
    }
}