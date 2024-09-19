<?php 
class AdminuserController extends REST 
{
    private    $model;
	protected  $response = array();
	
    public function __construct($model = 'AdminuserModel') {
    	parent       :: __construct();
        $this->model = new $model;
    }
    
    function index() {
        
        if($this->_request['editid'])
            $this->response['user'] = $this->model->userById($this->_request['editid']);
        else
            $this->response['users'] = $this->model->userList(0, 100);
        
        return $this->response;
    }
    
    function addEditUser() {
        $actMsg['type']           = 0;
        $actMsg['message']        = '';
        
        $username       = trim($this->_request['username']);
        $password       = trim($this->_request['password']);
        $conpassword    = trim($this->_request['conpassword']);
        $fullname       = trim($this->_request['fullname']);
        $phone          = trim($this->_request['phone']);
        $address        = trim($this->_request['address']);
        $status         = trim($this->_request['status']);
        
        if($username && $password && $fullname) {
            
            if($password == $conpassword) {

                if(isset($this->_request['IdToEdit'])) {
                    
                    if($this->_request['IdToEdit'] != 2) {
                        $exist     = $this->model->userCount("(username='".addslashes($username)."' OR email='".addslashes($username)."') AND id!=".addslashes($this->_request['IdToEdit']));

                        if($exist < 1) {
                            
                            $exist     = $this->model->userCount("(username='".addslashes($email)."' OR email='".addslashes($email)."') AND id!=".addslashes($this->_request['IdToEdit']));

                            if($exist < 1) {

                                $params = array();
                                
                                $params['username']     = $username;
                                $params['email']        = $email;
                                $params['password']     = md5($password);
                                $params['fullname']     = $fullname;
                                $params['phone']        = $phone;
                                $params['address']      = $address;
                                $params['status']       = $status;

                                $this->model->userUpdate($params, $this->_request['IdToEdit']);

                                $actMsg['message']      = 'Data updated successfully.';
                                $actMsg['type']         = 1;
                            }
                            else 
                                $actMsg['message'] = $email.' already exists.';

                        }
                        else
                            $actMsg['message'] = $username.' already exists.';
                    }
                    else
                        $actMsg['message'] = 'Something went wrong! Please close your browser window and try again.';
                    
                }
                else {
                    $exist     = $this->model->userCount("(username='".addslashes($username)."' OR email='".addslashes($username)."')");

                    if($exist < 1) {
                        
                        $params = array();
                        $params['siteId']       = $this->session->read('SITEID');
                        $params['usertype']     = 'M';
                        $params['username']     = $username;
                        $params['email']        = $username;
                        $params['password']     = md5(trim($password));
                        
                        $params['fullname']     = $fullname;
                        $params['phone']        = $phone;
                        $params['address']      = $address;
                        $params['createDate']   = date('Y-m-d H:i:s');
                        $params['status']       = $status;
                        
                        $this->_request['editid'] = $this->model->newUser($params);

                        $actMsg['type']         = 1;
                        $actMsg['message']      = 'Data inserted successfully.';
                        $actMsg['step']         = 2;
                        
                        $this->model->redirectToURL(SITE_ADMIN_PATH.'/index.php?pageType='.$this->_request['pageType'].'&dtls='.$this->_request['dtls'].'&dtaction=modulepermissions&editid='.$this->_request['editid']);
                        
                    }
                    else
                        $actMsg['message'] = $username.' already exists.';
                }
            }
            else
                $actMsg['message'] = 'Confirm Password missmatched.';
        }
        else
            $actMsg['message'] = 'Fields marked with (*) are mandatory.';
        
        return $actMsg;
    }
    
    function permission() {
        $actMsg['type']         = 1;
        $actMsg['message']      = '';
        
        $compactpermission = implode(',', $this->_request['permission']);
  
        $params                 = array();
        $params['permission']   = $compactpermission;
        
        $this->model->userUpdate($params, $this->_request['IdToEdit']);

        $actMsg['message'] = 'Data updated successfully.';
        
        return $actMsg;
    }
    
    function multiAction(){
        $actMsg['type']           = 0;
        $actMsg['message']        = '';
        
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
                
            }
            else
                $this->model->userUpdate($params, $val);
            
            $actMsg['type']           = 1;
            $actMsg['message']        = 'Operation successful.';
        }
        
        return $actMsg;
    }
}
?>