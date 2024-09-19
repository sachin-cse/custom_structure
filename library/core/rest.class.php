<?php
class REST {

    public $_allow          = array();
    public $_content_type   = "application/json";
    public $_request        = array();
    public $_result         = array();
    public $session         = "";

    private $_method        = "";      
    private $_code          = 200;

    public function __construct() {
        $this->inputs();
        $this->loadSession();
    }

    public function get_referer(){
        return $_SERVER['HTTP_REFERER'];
    }

    public function response($data, $status){
        $this->_code = ($status)? $status:200;
        $this->set_headers();
        
        if($status == 404)
            $this->pageNotFound($data);
        else
            echo $data.PHP_EOL;
        
        exit;
    }

    private function get_status_message(){
        $status = array(
                    100 => 'Continue',  
                    101 => 'Switching Protocols',  
                    200 => 'OK',
                    201 => 'Created',  
                    202 => 'Accepted',  
                    203 => 'Non-Authoritative Information',  
                    204 => 'No Content',  
                    205 => 'Reset Content',  
                    206 => 'Partial Content',  
                    300 => 'Multiple Choices',  
                    301 => 'Moved Permanently',  
                    302 => 'Found',  
                    303 => 'See Other',  
                    304 => 'Not Modified',  
                    305 => 'Use Proxy',  
                    306 => '(Unused)',  
                    307 => 'Temporary Redirect',  
                    400 => 'Bad Request',  
                    401 => 'Unauthorized',  
                    402 => 'Payment Required',  
                    403 => 'Forbidden',  
                    404 => 'Not Found',  
                    405 => 'Method Not Allowed',  
                    406 => 'Not Acceptable',  
                    407 => 'Proxy Authentication Required',  
                    408 => 'Request Timeout',  
                    409 => 'Conflict',  
                    410 => 'Gone',  
                    411 => 'Length Required',  
                    412 => 'Precondition Failed',  
                    413 => 'Request Entity Too Large',  
                    414 => 'Request-URI Too Long',  
                    415 => 'Unsupported Media Type',  
                    416 => 'Requested Range Not Satisfiable',  
                    417 => 'Expectation Failed',  
                    500 => 'Internal Server Error',  
                    501 => 'Not Implemented',  
                    502 => 'Bad Gateway',  
                    503 => 'Service Unavailable',  
                    504 => 'Gateway Timeout',  
                    505 => 'HTTP Version Not Supported');
        return ($status[$this->_code])?$status[$this->_code]:$status[500];
    }

    public function get_request_method(){
        
        if(isset($_POST['pageType']) &&  $_POST['pageType'] == 'api'){
            $this->_request['pageType'] = $_POST['pageType'];
            $this->_request['dtaction'] = $_POST['dtaction'];
            
            return 'PUT';
        }
        else
            return $_SERVER['REQUEST_METHOD'];
    }

    private function inputs(){
        
        switch($this->get_request_method()){
            case "POST":
                $_POST = $_REQUEST;
                $this->_request = $this->cleanInputs($_POST);
                break;
            case "GET":
            case "DELETE": //DELETE=>delete a resource
                $this->_request = $this->cleanInputs($_GET);
                break;
            case "PUT":		// PUT=>replace the state of some data already existing 
                /*parse_str(file_get_contents("php://input"),$this->_request);
                $this->_request = $this->cleanInputs($this->_request);*/
                $postdata = file_get_contents("php://input");
                $data = json_decode(file_get_contents('php://input'), true);
                $this->_request['form'] = $this->cleanInputs($data);
                
                break;
            default:
                $this->response('', 406);
               // break;
        }
    }       

    private function cleanInputs($data){
        
        $clean_input = array();
        if(is_array($data)){
            foreach($data as $k => $v){
                $clean_input[$k] = $this->cleanInputs($v);
            }
        } else {
            /*if(get_magic_quotes_gpc()){
                $data = trim(stripslashes($data));
            }
            $data           = strip_tags($data);
            $clean_input    = trim($data);*/
            
            $sanitize = HTMLPurifier_Config::createDefault();
            $sanitize->set('Core.Encoding', 'UTF-8');
            $sanitize->set('HTML.Doctype', 'HTML 4.01 Transitional');
            $sanitize->set('HTML.SafeIframe', true);
            $sanitize->set('URI.SafeIframeRegexp', '%^(https?:)?//(www\.youtube(?:-nocookie)?\.com/embed/|player\.vimeo\.com/video/)%'); 
            
           

            if (defined('PURIFIER_CACHE')) {
                $sanitize->set('Cache.SerializerPath', PURIFIER_CACHE);
            } else {
                # Disable the cache entirely
                $sanitize->set('Cache.DefinitionImpl', null);
            }

            $def = $sanitize->getHTMLDefinition(true);
            $def->addAttribute('div', 'data-configid', 'CDATA'); // for issue.com slider
            
            # Help out the Purifier a bit, until it develops this functionality
            while (($cleaner = preg_replace('!<(em|strong)>(\s*)</\1>!', '$2', $data)) != $data) {
                $data = $cleaner;
            }

            $filter = new HTMLPurifier($sanitize);
            
            
            
            $clean_input = $filter->purify($data);
        }
       
        return $clean_input;
    }       

    private function set_headers(){
        header("HTTP/1.1 ".$this->_code." ".$this->get_status_message());
        if($this->_code != 404)
            header("Content-Type:".$this->_content_type);
    }
    
    private function pageNotFound($data){
        $api = new API;
        $api->headerView($data, 404);
        echo '<section class="section"><div class="container"><div class="text-center"><h1>Error 404</h1><div class="mb15">'.$data.'</div><a href="'.SITE_LOC_PATH.'/">Return to Home Page</a></div></div></section>'.PHP_EOL;
        $api->footerView(404);
    }
    
    public function loadSession(){
        $this->session = new Session(SITE_PUBLIC_KEY);
        
        if (session_status() == PHP_SESSION_NONE){
            ini_set('session.save_handler', 'files');
            session_set_save_handler($this->session, true);
            //session_save_path(SESSION_PATH);
        }
        
        $this->session();
    }
    
    public function session() {
        $this->session->start();
        
        if (!$this->session->isValid(COOKIE_TIME_OUT)) {
            $this->session->forget();
        }
    }
}   
?>