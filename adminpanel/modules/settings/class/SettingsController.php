<?php 
class SettingsController extends REST
{
    private    $model;
	protected  $response = array();
	
    public function __construct($model = 'SettingsModel') {
    	parent       :: __construct();
        $this->model = new $model;
    }
    
    function index() {
        if($this->_request['dtaction'] == 'cache'){
            $cache                          = $this->model->siteById($this->session->read('SITEID'));
            $this->response['cache']        = unserialize($cache['cache']);
        }
        elseif($this->_request['dtaction'] == 'captcha'){
            $settings                       = $this->model->settings('captcha');
            $this->response['captcha']      = unserialize($settings['value']);
        }
        elseif($this->_request['dtaction'] == 'share-script'){
            $settings                       = $this->model->settings('sharescript');
            $this->response['sharescript']  = unserialize($settings['value']);
        }
        elseif($this->_request['dtaction'] == 'configuration')
            $this->response['site']         = $this->model->siteById($this->session->read('SITEID'));
        else
            $this->response['user']         = $this->model->getUserById($this->session->read('UID'));

        return $this->response;
    }
    
    function changePassword() {
        $actMsg['type']     = 0;
        $actMsg['message']  = '';

        $CurrPassword       = trim($this->_request['CurrPassword']);
        $NewPassword        = trim($this->_request['NewPassword']);
        $ReNewPassword      = trim($this->_request['ReNewPassword']);

        if($CurrPassword && $NewPassword) {

            if($NewPassword == $ReNewPassword) {
                $uData      = $this->model->getUserByIdPassword($this->session->read('UID'), $CurrPassword);
                if($uData) {
                    
                    $params = array();

                    $params['password'] = md5($NewPassword);
                    $this->model->userUpdate($params, $this->session->read('UID'));

                    $actMsg['type']     = 1;
                    $actMsg['message']  = 'Password changed successfully.';
                }
                else
                    $actMsg['message']  = 'Please enter current password correctly.';
            }
            else
                $actMsg['message']      = 'New password does not match.';
        }
        else
            $actMsg['message']          = 'Fields marked with (*) are mandatory.';
        
        return $actMsg;
    }
    
    function updateMyAccount() {
        
        $actMsg['type']     = 0;
        $actMsg['message']  = '';

        $email              = trim($this->_request['email']);
        $fullname           = trim($this->_request['fullname']);
        $phone              = trim($this->_request['phone']);
        $address            = trim($this->_request['address']);
        
        if($fullname && $email)  {
            
            $exist          = $this->model->userCount("(username='".addslashes($email)."' OR email='".addslashes($email)."') AND id!=".addslashes($this->session->read('UID')));
            
            if(!$exist) {
                $params             = array();

                $params['email']    = $email;
                $params['fullname'] = $fullname;
                $params['phone']    = $phone;
                $params['address']  = $address;

                $this->model->userUpdate($params, $this->session->read('UID'));

                $actMsg['type']     = 1;
                $actMsg['message']  = 'Data updated successfully.';
            }
            else
                $actMsg['message']  = $email.' already exists.';
        }
        else
            $actMsg['message']      = 'Fields marked with (*) are mandatory.';
        
        return $actMsg;
    }
    
    function updateConfiguration() {
        
        $actMsg['type']                     = 0;
        $actMsg['message']                  = '';

        $siteName                           = trim($this->_request['siteName']);
        $tagline                            = trim($this->_request['tagline']);
        $siteEmail                          = trim($this->_request['siteEmail']);
        $sitePhone                          = trim($this->_request['sitePhone']);
        $siteMobile                         = trim($this->_request['siteMobile']);
        $siteFax                            = trim($this->_request['siteFax']);
        $siteAddress                        = trim($this->_request['siteAddress']);
        $siteOpeningHours                   = trim($this->_request['siteOpeningHours']);
        $status                             = trim($this->_request['status']);
        $siteCurrency                       = trim($this->_request['siteCurrency']);
        $siteCurrencySymbol                 = trim($this->_request['siteCurrencySymbol']);

        $smtpHost                           = trim($this->_request['smtpHost']);
        $smtpEncryption                     = trim($this->_request['smtpEncryption']);
        $smtpPort                           = trim($this->_request['smtpPort']);
        $smtpUserName                       = trim($this->_request['smtpUserName']);
        $smtpUserPassword                   = trim($this->_request['smtpUserPassword']);

        if($siteName && $status) {
            
            $params                         = array();

            $params['siteName']             = $siteName;
            $params['tagline']              = $tagline;
            $params['siteEmail']            = $siteEmail;
            $params['sitePhone']            = $sitePhone;
            $params['siteMobile']           = $siteMobile;
            $params['siteFax']              = $siteFax;
            $params['siteAddress']          = $siteAddress;
            $params['siteOpeningHours']     = $siteOpeningHours;
            $params['status']               = $status;

            if($this->session->read('UTYPE') == 'A') {
                $params['siteCurrency']         = $siteCurrency;
                $params['siteCurrencySymbol']   = $siteCurrencySymbol;
                $params['smtpHost']             = $smtpHost;
                $params['smtpEncryption']       = $smtpEncryption;
                $params['smtpPort']             = $smtpPort;
                $params['smtpUserName']         = $smtpUserName;
                $params['smtpUserPassword']     = $smtpUserPassword;
            }

            $this->model->siteUpdateById($params, $this->session->read('SITEID'));

            $actMsg['type']                 = 1;
            $actMsg['message']              = 'Data updated successfully.';
        }
        else
            $actMsg['message']              = 'Fields marked with (*) are mandatory.';
        
        return $actMsg;
    }
    
    function updateCaptcha() {
        
        $actMsg['type']                         = 0;
        $actMsg['message']                      = '';

        $googleSiteKey                          = trim($this->_request['googleSiteKey']);
        $googleSecretKey                        = trim($this->_request['googleSecretKey']);

        if($googleSiteKey && $googleSecretKey) {
            
            $paramsCaptcha                      = [];

            $paramsCaptcha['googleSiteKey']     = $googleSiteKey;
            $paramsCaptcha['googleSecretKey']   = $googleSecretKey;
            
            $params                     = [];
            $params['name']             = 'captcha';
            $params['value']            = serialize($paramsCaptcha);

            $exist                      = $this->model->settings($params['name']);
            
            if(!$exist) {
                
                $this->model->newSettings($params);
                $actMsg['message']      = 'Data inserted successfully.';
                
            } else {
                
                $this->model->updateSetting('captcha', $params);
                $actMsg['message']      = 'Data updated successfully.';
            }

            $actMsg['type']             = 1;
        }
        else
            $actMsg['message']          = 'All fields are mandatory.';
        
        return $actMsg;
    }
    
    function updateSharescript() {
        
        $actMsg['type']                         = 0;
        $actMsg['message']                      = '';

        $socialSrc                              = trim($this->_request['socialSrc']);
        $socialClass                            = trim($this->_request['socialClass']);

        if($socialSrc && $socialClass) {
            
            $paramsSharescript                  = [];

            $paramsSharescript['socialSrc']     = $socialSrc;
            $paramsSharescript['socialClass']   = $socialClass;
            
            $params                             = [];
            $params['name']                     = 'sharescript';
            $params['value']                    = serialize($paramsSharescript);

            $exist                              = $this->model->settings($params['name']);
            
            if(!$exist) {
                
                $this->model->newSettings($params);
                $actMsg['message']      = 'Data inserted successfully.';
                
            } else {
                
                $this->model->updateSetting('sharescript', $params);
                $actMsg['message']      = 'Data updated successfully.';
            }

            $actMsg['type']             = 1;
        }
        else
            $actMsg['message']          = 'All fields are mandatory.';
        
        return $actMsg;
    }
    
    function siteCache() {
        
        $actMsg['type']                 = 0;
        $actMsg['message']              = '';

        $caching                        = trim($this->_request['caching']);
        $cacheRefresh                   = trim($this->_request['cacheRefresh']);
        if($caching && $cacheRefresh) {
            
            $paramsCache                = [];
            $paramsCache['caching']     = $caching;
            $paramsCache['cacheRefresh']= $cacheRefresh;
            
            $params                     = [];
            $params['cache']            = serialize($paramsCache);

            $this->model->siteUpdateById($params, $this->session->read('SITEID'));

            $actMsg['type']                 = 1;
            $actMsg['message']              = 'Data updated successfully.';
        }

        if(isset($this->_request['clearCache'])){
            $this->deleteCache();
        }
        
        return $actMsg;
    }
    
    function deleteCache() {
        $dir = CACHE_ROOT.DS.'site';
        $iterator = new RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS), \RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($iterator as $filename => $fileInfo) {
            if ($fileInfo->isDir()) {
                rmdir($filename);
            } else {
                unlink($filename);
            }
        }
    }
}
?>