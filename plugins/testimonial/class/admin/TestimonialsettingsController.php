<?php defined('BASE') OR exit('No direct script access allowed.');
class TestimonialsettingsController extends REST
{
    private    $model;
    protected  $response = array();

    public function __construct($model) {
        parent::__construct();
        $this->model        = new $model;
    }

    function index($act = []) {

        $settings     = $this->model->settings($this->_request['pageType']);
        
        $this->response['settings'] = unserialize($settings['value']);
        
        return $this->response;
    }

    function addEditSettings() {
        $actMsg['type']                 = 0;
        $actMsg['message']              = '';

        $isAvatar                       = isset($this->_request['isAvatar']) ? 1 : 0;
        $avatarWidth                    = trim($this->_request['avatarWidth']);
        $avatarHeight                   = trim($this->_request['avatarHeight']);

        $isShowcase                     = isset($this->_request['isShowcase']) ? 1 : 0;
        $showcaseTitle                  = trim($this->_request['showcaseTitle']);
        $showcaseNo                     = trim($this->_request['showcaseNo']);
        $showcaseDescription            = trim($this->_request['showcaseDescription']);

        $limit                          = trim($this->_request['limit']);

        $isReadMore                     = isset($this->_request['isReadMore']) ? 1 : 0;
        $buttonText                     = trim($this->_request['buttonText']);
        $buttonLimit                    = trim($this->_request['buttonLimit']);
        
        $error                          = 0;

        if($isAvatar == '1') {
            if(!$avatarWidth || !$avatarHeight){
                $error                  = 1;
                $actMsg['message']      = 'Please provide width and height for Avatar.';
            }
        }

        if($isShowcase == '1') {
            if(!$showcaseTitle || $showcaseNo < 1){
                $error                  = 1;
                $actMsg['message']      = 'Please provide title and number of item for Showcase.';
            }
        }

        if($isReadMore == '1') {
            if(!$buttonText || $buttonLimit < 1){
                $error                  = 1;
                $actMsg['message']      = 'Please provide button text and number of characters.';
            }
        }

        if(!$error){
            $paramsTestimonial                          = array();

            $paramsTestimonial['isAvatar']              = $isAvatar;
            $paramsTestimonial['avatarWidth']           = $avatarWidth;
            $paramsTestimonial['avatarHeight']          = $avatarHeight;

            $paramsTestimonial['isShowcase']            = $isShowcase;
            $paramsTestimonial['showcaseTitle']         = $showcaseTitle;
            $paramsTestimonial['showcaseNo']            = $showcaseNo;
            $paramsTestimonial['showcaseDescription']   = $showcaseDescription;

            $paramsTestimonial['limit']                 = $limit;

            $paramsTestimonial['isReadMore']            = $isReadMore;
            $paramsTestimonial['buttonText']            = $buttonText;
            $paramsTestimonial['buttonLimit']           = $buttonLimit;
            
            $params                     = [];
            $params['value']            = serialize($paramsTestimonial);
            
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