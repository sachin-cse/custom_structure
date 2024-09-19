<?php 
defined('BASE') OR exit('No direct script access allowed.');
class SliderController  extends REST
{
	private    $model;
	protected  $response = array();
	
    public function __construct($model) {
    	parent::__construct();
        $this->model        = new $model;
    }
    
	function index($act = []) {
        
        if(isset($this->_request['editid']) || isset($act['editid']) || $this->_request['dtaction'] == 'add') {
            
            $settings      = $this->model->settings('theme');
            $settings      = unserialize($settings['value']);
            
            $this->response['sliderWidth']  = $settings['sliderWidth'];
            $this->response['sliderHeight'] = $settings['sliderHeight'];
            
            $editid       = ($this->_request['editid'])? $this->_request['editid']:$act['editid'];
            
            if($editid)
                $this->response['slider'] = $this->model->sliderById($editid);
        } else {
            
            $ExtraQryStr                    = 1;
            
            
            // SEARCH START --------------------------------------------------------------
            if(isset($this->_request['searchText']))
                $this->session->write('searchText', $this->_request['searchText']);

            if($this->session->read('searchText'))
                $ExtraQryStr        .= " AND (sliderName LIKE '%".addslashes($this->session->read('searchText'))."%' OR subHeading LIKE '%".addslashes($this->session->read('searchText'))."%' OR sliderDescription LIKE '%".addslashes($this->session->read('searchText'))."%')";

            if(isset($this->_request['searchStatus']))
                $this->session->write('searchStatus', $this->_request['searchStatus']);

            if($this->session->read('searchStatus'))
                    $ExtraQryStr        .= " AND status = '".addslashes($this->session->read('searchStatus'))."'";

            if(isset($this->_request['Reset']) || isset($this->_request['Search'])) {
                
                if(isset($this->_request['Reset'])){
                    $ExtraQryStr     = 1;

                    $this->session->write('searchText',     '');
                    $this->session->write('searchStatus',   '');
                }
                
                $this->model->redirectToUrl(SITE_ADMIN_PATH.'/index.php?pageType='.$this->_request['pageType'].'&dtls='.$this->_request['dtls'].'&moduleId='.$this->_request['moduleId']);
            }
            // SEARCH END ----------------------------------------------------------------
            
            $this->response['rowCount'] = $this->model->sliderCount($ExtraQryStr);
            
            if($this->response['rowCount']) {
                
                $p                          = new Pager;
                $this->response['limit']    = VALUE_PER_PAGE;
                $start                      = $p->findStart($this->response['limit'], $this->_request['page']);
                $pages                      = $p -> findPages($this->response['rowCount'], $this->response['limit']);
                
                $this->response['sliders']  = $this->model->getSlider($ExtraQryStr, $start, $this->response['limit']);
                
                if($this->response['rowCount'] > 0 && ceil($this->response['rowCount'] / $this->response['limit']) > 1) {
                    $this->response['page']      = ($this->_request['page']) ? $this->_request['page'] : 1;
                    $this->response['totalPage'] = ceil($this->response['rowCount'] / $this->response['limit']);

                    $this->response['pageList']  = $p -> pageList($this->response['page'], $_SERVER['REQUEST_URI'], $pages);
                }
            }
        }
        
        return $this->response;
    }
    
    function addSlider() {
        $actMsg['type']             = 0;
        $actMsg['message']          = '';
        
        $sliderName                 = trim($this->_request['sliderName']);
        $displayHeading             = trim($this->_request['displayHeading']);
        
        $subHeading                 = trim($this->_request['subHeading']);
        $sliderDescription          = trim($this->_request['sliderDescription']);
        
        $buttonName                 = trim($this->_request['buttonName']);
        $redirectUrl                = trim($this->_request['redirectUrl']);
        $redirectUrlTarget          = trim($this->_request['redirectUrlTarget']);
        $status                     = trim($this->_request['status']);
        
        if($sliderName){
            //permalink--------------
            if(!$permalink)
                $permalink   = $sliderName;	
            else
                $permalink   = str_replace('-',' ',$permalink);

            if($this->_request['IdToEdit'])
                $ExtraQryStr = 'id != '.$this->_request['IdToEdit'];	
            else
                $ExtraQryStr = 1;
            $permalink       = createPermalink(TBL_SLIDER, $permalink, $ExtraQryStr);
            //permalink---------------
            
            $settings      = $this->model->settings('theme');
            $settings      = unserialize($settings['value']);

            $fObj                   = new FileUpload;
            $targetLocation         = MEDIA_FILES_ROOT."/slider";
            
            if (!file_exists($targetLocation) && !is_dir($targetLocation)) 
                $this->createMedia($targetLocation);

            $TWH[0]                 = $settings['sliderWidth'];             // thumb width
            $TWH[1]                 = $settings['sliderHeight'];            // thumb height
            $LWH[0]                 = $settings['sliderWidth'];             // large width
            $LWH[1]                 = $settings['sliderHeight'];            // large height

            $option                 = 'thumbnail';                          // upload, thumbnail, resize, all
            
            if($this->_request['IdToEdit']) {
                $params = array();

                $params['sliderName']           = $sliderName;
                $params['permalink']            = $permalink;

                $params['displayHeading']       = $displayHeading;

                $params['subHeading']           = $subHeading;
                $params['sliderDescription']    = $sliderDescription;

                $params['buttonName']           = $buttonName;

                $params['redirectUrl']          = $redirectUrl;
                $params['redirectUrlTarget']    = $redirectUrlTarget;

                $params['status']               = $status;
                
                if($_FILES['imageName']['name'] && substr($_FILES['imageName']['type'], 0, 5) == 'image') {
                    
                    $fileName = $permalink;
                    
                    $fetch_Existing_Lg = $this->model->sliderById($this->_request['IdToEdit']);
                        
                    if($fetch_Existing_Lg['imageName']) {
                        
                        @unlink($targetLocation.'/normal/'.$fetch_Existing_Lg['imageName']);
                        @unlink($targetLocation.'/thumb/'.$fetch_Existing_Lg['imageName']);
                        @unlink($targetLocation.'/large/'.$fetch_Existing_Lg['imageName']);
                    }
                    
                    if($target_image = $fObj->uploadImage($_FILES['imageName'], $targetLocation, $fileName, $TWH, $LWH)) {
                        
                        $params['imageName']    = $target_image;
                    }
                }
                
                $this->model->sliderUpdateById($params, $this->_request['IdToEdit']);
                
                $actMsg['editid']               = $this->_request['IdToEdit'];

                $actMsg['type']                 = 1;
                $actMsg['message']              = 'Data updated successfully.';
            }
            else {
                for($i = 0; $i < count($_FILES['imageName']['name']); $i++) {

                    if($_FILES['imageName']['name'][$i] && substr($_FILES['imageName']['type'][$i], 0, 5) == 'image') {

                        $fileName               = ($i)? $permalink.'-'.$i:$permalink;

                        $imgArray               = [];
                        $imgArray['name']       = $_FILES['imageName']['name'][$i];
                        $imgArray['type']       = $_FILES['imageName']['type'][$i];
                        $imgArray['tmp_name']   = $_FILES['imageName']['tmp_name'][$i];
                        $imgArray['size']       = $_FILES['imageName']['size'][$i];

                        if($target_image = $fObj->uploadImage($imgArray, $targetLocation, $fileName, $TWH, $LWH)) {
                            $params = array();

                            $params['sliderName']           = $sliderName;
                            $params['permalink']            = $permalink.'-'.$i;

                            $params['displayHeading']       = $displayHeading;

                            $params['subHeading']           = $subHeading;
                            $params['sliderDescription']    = $sliderDescription;

                            $params['buttonName']           = $buttonName;

                            $params['redirectUrl']          = $redirectUrl;
                            $params['redirectUrlTarget']    = $redirectUrlTarget;

                            $params['imageName']            = $target_image;
                            $params['displayOrder']         = $i;
                            $params['status']               = $status;

                            $actMsg['editid']               = $this->model->newSlider($params);

                            $actMsg['type']                 = 1;
                            $actMsg['message']              = 'Data inserted successfully.';
                        }
                    }
                    /*else
                        $actMsg['message'] = 'Upload image first.';*/
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
                    $selData = $this->model->sliderById($val);
                    if($selData['imageName']) {
                        @unlink(MEDIA_FILES_ROOT.'/slider/normal/'.$selData['imageName']);
                        @unlink(MEDIA_FILES_ROOT.'/slider/thumb/'.$selData['imageName']);
                        @unlink(MEDIA_FILES_ROOT.'/slider/large/'.$selData['imageName']);
                    }

                    $this->model->deleteSliderById($val);
                }
                else
                    $this->model->sliderUpdateById($params, $val);
                
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
            $this->model->sliderUpdateById($params, $recordID);
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