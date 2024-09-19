<?php defined('BASE') OR exit('No direct script access allowed.');
class BrandsettingsController extends REST
{
	private    $model;
	protected  $response = array();
	
    public function __construct($model) {
    	parent::__construct();
        $this->model        = new $model;
    }
    
	function index($act = []) {
        
        $settings     = $this->model->settings($this->_request['pageType']);
        
        if(!$settings) 
            $settings = $this->model->settings('communication');
        
        $this->response['settings'] = unserialize($settings['value']);
        
        return $this->response;
    }
    
    function addEditSettings() {
        $actMsg['type']                 = 0;
        $actMsg['message']              = '';
        
        $isBanner                       = isset($this->_request['isBanner']) ? 1 : 0;
        $bannerWidth                    = trim($this->_request['bannerWidth']);
        $bannerHeight                   = trim($this->_request['bannerHeight']);
 
        $isGallery                      = isset($this->_request['isGallery']) ? 1 : 0;
        $imageWidth                     = trim($this->_request['imageWidth']);
        $imageHeight                    = trim($this->_request['imageHeight']);
        $imageThumbWidth                = trim($this->_request['imageThumbWidth']);
        $imageThumbHeight               = trim($this->_request['imageThumbHeight']);

        $isShortDesc                    = isset($this->_request['isShortDesc']) ? 1 : 0;
        $isButton                       = isset($this->_request['isButton']) ? 1 : 0;
        $btnText                        = trim($this->_request['btnText']);
        $limit                          = trim($this->_request['limit']);

        $isShowcase                     = isset($this->_request['isShowcase']) ? 1 : 0;
        $showcaseTitle                  = trim($this->_request['showcaseTitle']);
        $showcaseNo                     = trim($this->_request['showcaseNo']);
        $showcaseDescription            = trim($this->_request['showcaseDescription']);

        $isSocial                       = isset($this->_request['isSocial']) ? 1 : 0;
        
        $error                          = 0;
      
        if($isBanner == '1') {
            if(!$bannerWidth || !$bannerHeight){
                $error                  = 1;
                $actMsg['message']      = 'Please provide width and height for Banner.';
            }
        }

        if($isGallery == '1') {
            if(!$imageWidth || !$imageHeight || !$imageThumbWidth || !$imageThumbHeight){
                $error                  = 1;
                $actMsg['message']      = 'Please provide width and height for Gallery.';
            }
        }

        if($isShowcase == '1') {
            if($showcaseNo < 1){
                $error                  = 1;
                $actMsg['message']      = 'Please provide number of item for Showcase.';
            }
        }
        
        if($isButton == '1'){
            if(!$btnText){
                $error                  = 1;
                $actMsg['message']      = 'Please provide button text.';
            }
        }
        
        if(!$error){
            $paramsContact                          = array();
            
            $paramsContact['isBanner']              = $isBanner;
            $paramsContact['bannerWidth']           = $bannerWidth;
            $paramsContact['bannerHeight']          = $bannerHeight;
            
            $paramsContact['isGallery']             = $isGallery;
            $paramsContact['imageWidth']            = $imageWidth;
            $paramsContact['imageHeight']           = $imageHeight;
            $paramsContact['imageThumbWidth']       = $imageThumbWidth;
            $paramsContact['imageThumbHeight']      = $imageThumbHeight;

            $paramsContact['isShortDesc']           = $isShortDesc;
            $paramsContact['isButton']              = $isButton;
            $paramsContact['btnText']               = $btnText;
            $paramsContact['limit']                 = $limit;

            $paramsContact['isShowcase']            = $isShowcase;
            $paramsContact['showcaseTitle']         = $showcaseTitle;
            $paramsContact['showcaseNo']            = $showcaseNo;
            $paramsContact['showcaseDescription']   = $showcaseDescription;

            $paramsContact['isSocial']              = $isSocial;
            
            $params                                 = [];
            $params['value']                        = serialize($paramsContact);
            
            $exist                      = $this->model->settings($this->_request['pageType']);
            
            if(!$exist) {
                
                $params['name']         = $this->_request['pageType'];
                $this->model->newSettings($params);
                $actMsg['message']      = 'Data inserted successfully.';
                
            } else {
                
                $this->model->updateSetting($this->_request['pageType'], $params);
                $actMsg['message']      = 'Data updated successfully.';
            }

            $actMsg['type']             = 1;
        }
        
        return $actMsg;
    }
}