<?php 
class AdminController extends REST
{
    private    $model;
	protected  $response = array();
	
    public function __construct($model = 'AdminModel') {
    	parent       :: __construct();
        $this->model = new $model;
    }
    
    function index() {

        if(isset($this->_request['CheckLogin']) && $this->_request['CheckLogin'] == 'Login')
            $this->response = $this->login();
        elseif(isset($this->_request['SourceForm']) && $this->_request['SourceForm'] == 'resetPassword') {
            
            $this->response    = $this->resetPassword();
            $this->response['verify']   = $this->model->checkPassKey($this->_request['pk']);
        }
        elseif(isset($this->_request['SourceForm']) && $this->_request['SourceForm'] == 'forgotPassword')
            $this->response = $this->forgotPassword();
        elseif($this->_request['action'] == 'forgot-password' && isset($this->_request['pk'])) {
            $this->response['verify'] = $this->model->checkPassKey($this->_request['pk']);
        }
        else {
            $this->response['pageCount']    = $this->model->mainActivePageCount();
            $this->response['contentCount'] = $this->model->activeContentCount();
        }
        
        return $this->response;
    }
    
    function login() {
        $this->session->write('LOGIN', 'NO');
        
        $actMsg['type']                 = 0;
        $actMsg['message']              = '';
        
        if(!$this->session->read('PROTECT'))
            $this->session->write('PROTECT', 1);
        else
            $this->session->write('PROTECT', ($this->session->read('PROTECT') + 1));

        if($this->session->read('PROTECT') < 10) {
            $EnteredUname       = trim($this->_request['LoginName']);
            $EnteredPassword    = trim($this->_request['LoginPass']);

            if($EnteredUname && $EnteredPassword) {
                $selData        = $this->model->lookup($EnteredUname, $EnteredPassword);
                
                if($selData) {
                    
                    $this->session->write('LOGIN',           'YES');
                    $this->session->write('UID',             $selData['id']);
                    $this->session->write('UNAME',           $selData['fullname']);
                    $this->session->write('UTYPE',           $selData['usertype']);
                    $this->session->write('SITEID',          $selData['siteId']);
                    $this->session->write('PERMISSION',      $selData['permission']);
                    
                    if(strtotime($selData['lastLogin']) > 0)
                        $this->session->write('LASTLOGIN',   $selData['lastLogin']);
                    else
                        $this->session->write('LASTLOGIN',   date("Y-m-d H:i:s"));
                    //User Session Variable Set End

                    $params					= array();
                    $params['ipAddress']    = $this->session->read('visitedFrom_Ip');
                    $params['lastLogin']    = date("Y-m-d H:i:s");
                    $updateUser             = $this->model->updateUserByid($params, $selData['id']);

                    $this->model->redirectToURL(SITE_ADMIN_PATH);
                    
                }
                else
                    $actMsg['message']  = 'Invalid entry!';
            }
            else
                $actMsg['message'] = 'All fields are mandatory!';
        }
        else
            $actMsg['message'] = 'Maximum login attempts exceeded!';
        
        return $actMsg;
    }
    
    function forgotPassword() {
        
        $actMsg['type']         = 0;
        $actMsg['message']      = '';
        
        $email                  = trim($this->_request['email']);

        if($email != '') {
            $selData = $this->model->getUserByemail($email);
            
            if($selData) {
                
                if($selData['status'] == 'Y') {
                    
                    $passwordKey 			= md5(time().rand());
                    $params                 = array();
                    $params['passwordKey']  = $passwordKey;

                    $this->model->updateUserByid($params, $selData['id']);
                    
                    /*****Sending mail for forgot password*****/
                    $msg_details = $this->model->getEmailBodyById(1);

                    $to      = $email;
                    $from    = "From: ".SITE_NAME."<".SITE_EMAIL.">";
                    $subject = $msg_details['emailSubject'];
                    $msg     = $msg_details['emailBody'];

                    $arr = array(
                            "{link}" => SITE_ADMIN_PATH . "/index.php?action=forgot-password&pk=".$passwordKey
                        );

                    $msg  = strtr($msg,$arr);

                    sendEmail($to, $from, $subject, $msg);

                    $actMsg['type']    = 1;
                    $actMsg['message'] = 'An email has been sent to your address to reset password. Please check your inbox / spam.';
                }
                else
                    $actMsg['message'] = 'It seems that your email address is blocked. Please contact administrator.';
            }
            else
                $actMsg['message'] = 'No match found for this email.';
        }
        else
            $actMsg['message'] = 'Please enter email address.';
        
        return $actMsg;
    }
    
    function resetPassword() {

        $actMsg 		= array();
        $actMsg['type'] = 0;
        $actMsg['goto'] = '';
        
        $captcha = $this->_request['captcha'];

        if($captcha == '') {
            if($this->_request['password'] && $this->_request['cnfrm_password'] && $this->_request['pk']) {
                $gObj = new genl();
                if($gObj -> validate_alpha($this->_request['password'])) {
                    if($this->_request['password'] == $this->_request['cnfrm_password']) {
                        $checkUserStatus  = $this->model->checkPassKey($this->_request['pk']);
                        if ($checkUserStatus == 1) {
                            $params 				= array();
                            $params['password']     = md5($this->_request['cnfrm_password']);
                            $params['passwordKey']  = '';
                            $updatePassword 		= $this->model->userUpdateByPassKey($params, $this->_request['pk']);

                            $actMsg['type']     = 1;
                            $actMsg['message']  = 'Password changed successfully.';
                            
                            $this->model->redirectToURL(SITE_ADMIN_PATH);
                        }
                        else
                            $actMsg['message'] = 'Password key mismatched!';
                    }
                    else
                        $actMsg['message'] = 'Passwords do not match!';
                }
                else
                    $actMsg['message'] = 'Passwords must be between 8 – 15 characters and include at least 1 capital letter, 1 non-capital letter and 1 number!';
            }
            else
                $actMsg['message'] = 'All fields are mandatory!';
        }
        else
            $actMsg['message'] = 'Error: Unauthorised attempt!';

        return $actMsg;
        
    }
    
    function permalink(){
        $data = [];
        $data['permalink'] = createPermalink($this->_request['ENTITY'], $this->_request['permalink'], $this->_request['qrystr']);
        return $data;
    }
    
    function uploadMedia(){
        if($this->session->read('LOGIN') == 'YES') {
            $imageFolder    = MEDIA_EDITOR_ROOT.'/';
            $imageView      = "/media/library/editor/";

            reset ($_FILES);
            $temp = current($_FILES);

            $extension	= pathinfo($temp['name'], PATHINFO_EXTENSION);
            $target_image = time().'.'.$extension;

            if (is_uploaded_file($temp['tmp_name'])) {
                /* If your script needs to receive cookies, set images_upload_credentials : true in
                  the configuration and enable the following two headers. */
                // header('Access-Control-Allow-Credentials: true');
                // header('P3P: CP="There is no P3P policy."');

                // Sanitize input
                if (preg_match("/([^\w\s\d\-_~,;:\[\]\(\).])|([\.]{2,})/", $temp['name'])) {
                    header("HTTP/1.1 400 Invalid file name.");
                    return;
                }

                // Verify extension
                if (!in_array(strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION)), array("gif", "jpg", "png"))) {
                    header("HTTP/1.1 400 Invalid extension.");
                    return;
                }

                // Accept upload if there was no origin, or if it is an accepted origin
                $filetowrite    = $imageFolder.$target_image;
                $filetoview     = $imageView.$target_image;
                move_uploaded_file($temp['tmp_name'], $filetowrite);
                //move_uploaded_file($temp['tmp_name'], $filetowrite);

                // Respond to the successful upload with JSON.
                // Use a location key to specify the path to the saved image resource.
                // { location : '/your/uploaded/image/file'}

                echo json_encode(array('url' => SITE_LOC_PATH.$filetoview));
            }
            else {
                // Notify editor that the upload failed
                header("HTTP/1.1 500 Server Error");
            }
        }
    }
}
?>