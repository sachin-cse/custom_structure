<?php defined('BASE') OR exit('No direct script access allowed.');
class EventsettingsController extends REST
{
    private    $model;
    protected  $response = [];

    public function __construct(EventsettingsModel $model = null) {
        parent::__construct();

        if ($model == null)
            $model  = new EventsettingsModel;

        $this->model = $model;
    }

    function index($act = []) {

    }

    function modPage() {
        $srch = trim($this->_request['srch']);

        if ($srch) {
            return $this->model->searchLinkedPages($this->_request['mid'], $this->_request['pageType'], $srch, 0, 10);
        }
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
            $paramsFaq                          = array();

            $paramsFaq['isAvatar']              = $isAvatar;
            $paramsFaq['avatarWidth']           = $avatarWidth;
            $paramsFaq['avatarHeight']          = $avatarHeight;

            $paramsFaq['isShowcase']            = $isShowcase;
            $paramsFaq['showcaseTitle']         = $showcaseTitle;
            $paramsFaq['showcaseNo']            = $showcaseNo;
            $paramsFaq['showcaseDescription']   = $showcaseDescription;

            $paramsFaq['limit']                 = $limit;

            $paramsFaq['isReadMore']            = $isReadMore;
            $paramsFaq['buttonText']            = $buttonText;
            $paramsFaq['buttonLimit']           = $buttonLimit;
            
            $params                     = [];
            $params['value']            = serialize($paramsFaq);
            
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