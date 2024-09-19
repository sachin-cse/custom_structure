<?php 
defined('BASE') OR exit('No direct script access allowed.');
class CommunicationsettingsController  extends REST
{
	private    $model;
	protected  $response = array();
	
    public function __construct($model) {
    	parent::__construct();
        $this->model        = new $model;
    }
    
	function index($act = []) {
        
        $settings                   = $this->model->settings($this->_request['pageType']);
        
        $this->response['settings'] = unserialize($settings['value']);
        
        if($this->response['settings']['emailBody'] == '') {
            $emailTemplate = $this->model->emailTemplateById(11);
            
            $this->response['settings']['emailSubject'] = $emailTemplate['emailSubject'];
            $this->response['settings']['emailBody']    = $emailTemplate['emailBody'];
        }

        
        return $this->response;
    }
    
    function addContactSettings() {
        $actMsg['type']                 = 0;
        $actMsg['message']              = '';

        $isForm                         = isset($this->_request['isForm']) ? 1 : 0;
        $formHeading                    = trim($this->_request['formHeading']);
        $successMsg                     = trim($this->_request['successMsg']);
        $isCaptcha                      = isset($this->_request['isCaptcha']) ? 1 : 0;
        
        $isMap                          = isset($this->_request['isMap']) ? 1 : 0;
        $mapAddress                     = trim($this->_request['mapAddress']);
        
        $emailSubject                   = trim($this->_request['emailSubject']);
        $emailBody                      = trim($this->_request['emailBody']);
        $toEmail                        = trim($this->_request['toEmail']);
        $cc                             = trim($this->_request['cc']);
        $bcc                            = trim($this->_request['bcc']);
        $replyTo                        = trim($this->_request['replyTo']);
        
        $error                      = 0;
        if($isForm == '1'){
            if($toEmail || $replyTo){
                $gObj               = new genl();
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
                $error              = 1;
                $actMsg['message']  = 'Fields marked with (*) are mandatory.';
            }

            if(!$successMsg){
                $error              = 1;
                $actMsg['message']  = 'Please provide a Success Message for the form.';
            }
        }
        
        if($isMap == '1'){
            if(!$mapAddress){
                $error              = 1;
                $actMsg['message']  = 'Please provide address for Map.';
            }
        }
        
        if(!$error){
            $paramsContact                     = array();

            $paramsContact['isForm']           = $isForm;
            $paramsContact['formHeading']      = $formHeading;
            $paramsContact['successMsg']       = $successMsg;
            $paramsContact['isCaptcha']        = $isCaptcha;
            
            $paramsContact['isMap']            = $isMap;
            $paramsContact['mapAddress']       = $mapAddress;
            
            $paramsContact['emailSubject']     = $emailSubject;
            $paramsContact['emailBody']        = $emailBody;
            $paramsContact['toEmail']          = $toEmail;
            $paramsContact['cc']               = $cc;
            $paramsContact['bcc']              = $bcc;
            $paramsContact['replyTo']          = $replyTo;
            
            $params                     = [];
            
            $params['value']            = serialize($paramsContact);
            
            $exist     = $this->model->settings($this->_request['pageType']);
            
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