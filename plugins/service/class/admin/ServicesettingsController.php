<?php 
defined('BASE') OR exit('No direct script access allowed.');
class ServicesettingsController  extends REST
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

        $isForm                         = isset($this->_request['isForm']) ? 1 : 0;
        $formHeading                    = trim($this->_request['formHeading']);
        $successMsg                     = trim($this->_request['successMsg']);
        $isCaptcha                      = isset($this->_request['isCaptcha']) ? 1 : 0;
      
        $emailSubject                   = trim($this->_request['emailSubject']);
        $emailBody                      = trim($this->_request['emailBody']);
        $toEmail                        = trim($this->_request['toEmail']);
        $cc                             = trim($this->_request['cc']);
        $bcc                            = trim($this->_request['bcc']);
        $replyTo                        = trim($this->_request['replyTo']);
        
        $isBanner                       = isset($this->_request['isBanner']) ? 1 : 0;
        $bannerWidth                    = trim($this->_request['bannerWidth']);
        $bannerHeight                   = trim($this->_request['bannerHeight']);
        
        $isIcon                         = isset($this->_request['isIcon']) ? 1 : 0;
        $iconWidth                      = trim($this->_request['iconWidth']);
        $iconHeight                     = trim($this->_request['iconHeight']);
        
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
        $showcaseOtherTitle             = trim($this->_request['showcaseOtherTitle']);
        $showcaseNo                     = trim($this->_request['showcaseNo']);
        $showcaseDescription            = trim($this->_request['showcaseDescription']);

        $isSocial                       = isset($this->_request['isSocial']) ? 1 : 0;
       // $socialSrc                      = trim($this->_request['socialSrc']);
      //  $socialClass                    = trim($this->_request['socialClass']);
        
        $error                          = 0;
        if($isForm == '1') {
            if($toEmail || $replyTo){
                $gObj                   = new genl();
                if($gObj -> validate_email($toEmail)){
                    if($gObj -> validate_email($replyTo)){
                        if($cc){
                            if(!$gObj -> validate_email($cc)){
                                $error              = 1;
                                $actMsg['message']  = 'Cc email is invalid.';
                            }
                        }
                        if($bcc){
                            if(!$gObj -> validate_email($bcc)){
                                $error              = 1;
                                $actMsg['message']  = 'Bcc email is invalid.';
                            }
                        }
                    }
                    else{
                        $error              = 1;
                        $actMsg['message']  = 'No-reply email is invalid.';
                    }
                }
                else{
                    $error              = 1;
                    $actMsg['message']  = 'To email is invalid.';
                }
            }
            else{
                $error                  = 1;
                $actMsg['message']      = 'Fields marked with (*) are mandatory.';
            }

            if(!$successMsg){
                $error                  = 1;
                $actMsg['message']      = 'Please provide a Success Message for the form.';
            }
        }
        
        if($isMap == '1'){
            if(!$mapAddress){
                $error                  = 1;
                $actMsg['message']      = 'Please provide address for Map.';
            }
        }

        if($isBanner == '1') {
            if(!$bannerWidth || !$bannerHeight){
                $error                  = 1;
                $actMsg['message']      = 'Please provide width and height for Banner.';
            }
        }

        if($isIcon == '1') {
            if(!$iconWidth || !$iconHeight){
                $error                  = 1;
                $actMsg['message']      = 'Please provide width and height for Icon.';
            }
        }

        if($isGallery == '1') {
            if(!$imageWidth || !$imageHeight || !$imageThumbWidth || !$imageThumbHeight){
                $error                  = 1;
                $actMsg['message']      = 'Please provide width and height for Gallery.';
            }
        }

        if($isShowcase == '1') {
            if(!$showcaseTitle || $showcaseNo < 1){
                $error                  = 1;
                $actMsg['message']      = 'Please provide title and number of item for Showcase.';
            }
        }

       /*  if($isSocial == '1') {
            if(!$socialSrc || !$socialClass){
                $error                  = 1;
                $actMsg['message']      = 'Please provide share script src and  for Social buttons.';
            }
        } */
        
        if($isButton == '1'){
            if(!$btnText){
                $error                  = 1;
                $actMsg['message']      = 'Please provide button text.';
            }
        }
        
        if(!$error){
            $paramsContact                          = array();

            $paramsContact['isForm']                = $isForm;
            $paramsContact['formHeading']           = $formHeading;
            $paramsContact['successMsg']            = $successMsg;
            $paramsContact['isCaptcha']             = $isCaptcha;
            
            $paramsContact['emailSubject']          = $emailSubject;
            $paramsContact['emailBody']             = $emailBody;
            $paramsContact['toEmail']               = $toEmail;
            $paramsContact['cc']                    = $cc;
            $paramsContact['bcc']                   = $bcc;
            $paramsContact['replyTo']               = $replyTo;
            
            $paramsContact['isBanner']              = $isBanner;
            $paramsContact['bannerWidth']           = $bannerWidth;
            $paramsContact['bannerHeight']          = $bannerHeight;
            
            $paramsContact['isIcon']                = $isIcon;
            $paramsContact['iconWidth']             = $iconWidth;
            $paramsContact['iconHeight']            = $iconHeight;
            
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
            $paramsContact['showcaseOtherTitle']    = $showcaseOtherTitle;
            $paramsContact['showcaseNo']            = $showcaseNo;
            $paramsContact['showcaseDescription']   = $showcaseDescription;

            $paramsContact['isSocial']              = $isSocial;
           // $paramsContact['socialSrc']             = $socialSrc;
          //  $paramsContact['socialClass']           = $socialClass;
            
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
?>