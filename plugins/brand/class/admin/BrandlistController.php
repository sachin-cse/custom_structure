<?php defined('BASE') OR exit('No direct script access allowed.');
class BrandlistController extends REST
{
    private    $model;
    protected  $response = [];

    public function __construct(BrandlistModel $model = null) {
        parent::__construct();

        if ($model == null)
            $model  = new BrandlistModel;

        $this->model = $model;
    }

    function index($act = []) {
        $this->response['linkedPages']          = $this->model->getLinkedPages($this->_request['pageType'], 0, 100);
            
        $settings                               = $this->model->settings($this->_request['pageType']);
        $this->response['settings']             = unserialize($settings['value']);
        
        if(isset($this->_request['editid']) || isset($act['editid']) || $this->_request['dtaction'] == 'add') {
            $editid = ($this->_request['editid'])? $this->_request['editid']:$act['editid'];
            if($editid) {
                $this->response['gallery']      = $this->model->galleryByIdMenucategoryId($editid);
                $this->response['galleryImage'] = $this->model->galleryById($this->response['gallery']['id'], 0, 100);
                // print_r($this->response['galleryImage']); exit;
                $titleandMetaUrl                = '/'.$this->response['gallery']['brand_url'].'/'.$this->response['gallery']['menuPermalink'].'/';
                $seoModel                       = new TitlemetaModel;
                $this->response['seoData']      = $seoModel->titleMetaByUrl($titleandMetaUrl);
            }
            // echo "Hii"; exit;
        }
        else {
            $ExtraQryStr = 1;

            // SEARCH START --------------------------------------------------------------
            if(isset($this->_request['searchText']))
                $this->session->write('searchText', $this->_request['searchText']);

            if($this->session->read('searchText'))
                $ExtraQryStr        .= " AND brand_name LIKE '%".addslashes($this->session->read('searchText'))."%'";

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

            $this->response['rowCount']     = $this->model->galleryCount($ExtraQryStr);
            if($this->response['rowCount']) {

                $p                          = new Pager;
                $this->response['limit']    = VALUE_PER_PAGE;
                $start                      = $p->findStart($this->response['limit'], $this->_request['page']);
                $pages                      = $p->findPages($this->response['rowCount'], $this->response['limit']);

                $this->response['gallerys']    	 = $this->model->getGalleryByLimit($ExtraQryStr, $start, $this->response['limit']);
                if($this->response['rowCount'] > 0 && ceil($this->response['rowCount'] / $this->response['limit']) > 1) {
                    $this->response['page']      = ($this->_request['page']) ? $this->_request['page'] : 1;
                    $this->response['totalPage'] = ceil($this->response['rowCount'] / $this->response['limit']);

                    $this->response['pageList']  = $p->pageList($this->response['page'], $_SERVER['REQUEST_URI'], $pages);
                }
            }
        }
        // echo "Hii"; exit;
        return $this->response;
    }

    function modPage() {
        $srch = trim($this->_request['srch']);

        if ($srch) {
            return $this->model->searchLinkedPages($this->_request['mid'], $this->_request['pageType'], $srch, 0, 10);
        }
    }

    function addEditBrand() {
        $actMsg['type']             = 0;
        $actMsg['message']          = '';
        
        $menucategoryId             = trim($this->_request['menucategoryId']);
        $galleryName                = trim($this->_request['galleryName']);
        $galleryImage =                $_FILES['galleryImage']['name'];
        $permalink                  = trim($this->_request['permalink']);
        $status                     = trim($this->_request['status']);
        $displayOrder               = trim($this->_request['displayOrder']);
        $isShowcase                 = trim($this->_request['isShowcase']);
        
        if($galleryName != '') {
            if($this->_request['IdToEdit']!= '')
                $sel_ContentDetails = $this->model->checkExistence("brand_name = '".addslashes($galleryName)."' AND id != ".$this->_request['IdToEdit']);
            else
                $sel_ContentDetails = $this->model->checkExistence("brand_name = '".addslashes($galleryName)."'");
                //permalink --------------
                $ENTITY          = TBL_BRAND;
                // if(!$permalink)
                //     $permalink   = $galleryName;
                // else
                //     $permalink   = str_replace('-', ' ', $permalink);

                if($this->_request['IdToEdit'])
                    $ExtraQryStr = 'id != '.$this->_request['IdToEdit'];
                else
                    $ExtraQryStr = 1;
                // $permalink       = createPermalink($ENTITY, $permalink, $ExtraQryStr);
                //permalink ---------------

                $params                             = array();
                $params['menucategoryId']           = $menucategoryId;
                $params['brand_name']              = $galleryName;
                $params['brand_url']                = $permalink;
                $params['status']                   = $status;
                $params['isShowcase']               = $isShowcase;
                
                if($displayOrder == 'T' || $displayOrder == 'B'){
                    $order          = $this->model->getDisplayOrder($displayOrder);
                    $displayOrder   = ($displayOrder == 'T')? ($order['displayOrder'] - 1) : ($order['displayOrder'] + 1);
                }
                
                $params['displayOrder']             = $displayOrder;
                
                if($this->_request['IdToEdit'] != '') {
                    $dataBeforeUpdate           = $this->model->galleryById($this->_request['IdToEdit']);

                    $this->model->galleryUpdateById($params, $this->_request['IdToEdit']);

                    $actMsg['editid']           = $this->_request['IdToEdit'];
                    $actMsg['message']          = 'Data updated successfully.';
                }
                else {
                    $params['entryDate']        = date('Y-m-d H:i:s');
                    $actMsg['editid']           = $this->model->newGallery($params);

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

                $selData      = $this->model->galleryByIdMenucategoryId($actMsg['editid']);
                $setCover     = $selData['brand_image'];



                //Image ---------------
                if($_FILES['galleryImage']['name'] && substr($_FILES['galleryImage']['type'], 0, 5) == 'image') {
                    $fObj           = new FileUpload;
    
                    $TWH[0]         = $settings['avatarWidth'];     // thumb width
                    $TWH[1]         = $settings['avatarHeight'];    // thumb height
                    $LWH[0]         = $settings['avatarWidth'];     // large width
                    $LWH[1]         = $settings['avatarHeight'];    // large height
                    $option         = 'thumbnail';                  // upload, thumbnail, resize, all

                    $imageName = explode('.', $_FILES['galleryImage']['name']);
                    $fileName 		= $imageName[0].time();
                    if($fileName = $fObj->uploadImage($_FILES['galleryImage'], $targetLocation, $fileName, $TWH, $LWH, $option)) {
    
                        // delete existing image
                        if($dataBeforeUpdate['brand_image'] != $fileName) {
                            @unlink($targetLocation.'/normal/'.$dataBeforeUpdate['brand_image']);
                            @unlink($targetLocation.'/thumb/'.$dataBeforeUpdate['brand_image']);
                            @unlink($targetLocation.'/large/'.$dataBeforeUpdate['brand_image']);
                        }
                        // update new image
                        $params                     = array();
                        $params['brand_image']	= $fileName;
                        $galleryImage           = $fileName;
                        $this->model->galleryUpdateById($params, $actMsg['editid']);
                    }
                }
               
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
                    $faq = $this->model->galleryById($val);
                    
                    if($faq)
                        $this->model->deletebrandbyid($val);
                }
                else
                    $this->model->galleryUpdateById($params, $val);
                
                $actMsg['type']           = 1;
                $actMsg['message']        = 'Operation successful.';
            }
        }
        
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
            $this->model->galleryUpdateById($params, $recordID);
            $listingCounter = $listingCounter + 1;
        }
        
        if($listingCounter > 1){
            $actMsg['type']             = 1;
            $actMsg['message']          = 'Operation successful.';
        }
        
        return $actMsg;
    }
    

}