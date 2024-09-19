<?php
defined('BASE') OR exit('No direct script access allowed.');
class CommunicationController extends REST
{
    private    $model;
	protected  $pageview ;
	protected  $response = array();
	
    public function __construct($model) {
        
    	parent::__construct();
        $this->model        = new $model;
    }
    
    function index($pageData) {
        
        if($this->_request['dtls'])
            return;
        
        if($pageData) {
            
            if($this->_request['dtaction']) {
                if($this->_request['dtaction'] == 'thank-you')
                    $this->thankYou();
                else
                    return;
            }
            else {

                $this->response['socialLinks']  = $this->social();
                $this->pageview                 = 'index.php';
            }
            
            $settings                           = $this->model->settings($pageData['parent_dir']);
            $this->response['settings']         = unserialize($settings['value']);
            
            if($this->response['settings']['isCaptcha']){
                $captcha                           = $this->model->settings('captcha');
                $this->response['captcha']         = unserialize($captcha['value']);
            }

            $this->response['pageData']         = $pageData;
            $this->response['pageContent']      = $this->content($pageData['categoryId']);


            $this->response['body']         	= $this->pageview;
            return $this->response; 
        }
	}
    
    function content($categoryId) {
            
        $rsArry 				            = [];

        $rsArry['contentCount']	            = $this->model->countContentbymenucategoryId($categoryId);

        if($rsArry['contentCount']) {

            $p                              = new Pager;
            $rsArry['contentLimit']         = VALUE_PER_PAGE;
            $start                          = $p->findStart($rsArry['contentLimit'], $this->_request['contentPage']);
            $contentPages                   = $p->findPages($rsArry['contentCount'], $rsArry['contentLimit']);

            $rsArry['content']              = $this->model->getContentbymenucategoryId($categoryId, $start, $rsArry['contentLimit']);

            if($rsArry['contentCount'] > 0 && ceil($rsArry['contentCount'] / $rsArry['contentLimit']) > 1) {
                
                $rsArry['contentPage']      = ($this->_request['contentPage']) ? $this->_request['contentPage'] : 1;
                $rsArry['totalContentPage'] = ceil($rsArry['contentCount'] / $rsArry['contentLimit']);

                $rsArry['contentPageList']  = $p->pageList($rsArry['contentPage'], $_SERVER['REQUEST_URI'], $contentPages);
            }
    	    return $rsArry;
        }
    }
	
	function thankYou(){
    	$this->response['msgShow'] 			= ($this->session->read('MSGSHOW')) ? $this->session->read('MSGSHOW') : '';
    	$this->response['msg'] 			    = ($this->session->read('MSG')) ? $this->session->read('MSG') : '';
		
    	$this->pageview 			        = 'thank-you.php';
        
        $this->session->write('MSGSHOW', '');
        $this->session->write('MSG', '');
    }
    
    public function social() {

        $socialLinks      = $this->model->getSocialSite(1, 0, 10);
        return $socialLinks;
    }
    
    function form($opt = []){
        
        $settings           = $this->model->settings($opt['module']);
        $settings           = unserialize($settings['value']);
            
        if($settings['isCaptcha']){
            $captcha        = $this->model->settings('captcha');
            $captcha        = unserialize($captcha['value']);
        }
        
        $pageData           = $this->model->pageBymodule($opt['module']);
        
        if($pageData) { 
            $css        = ($opt['css']) ?       $opt['css']     : '';
            $form = '<div class="form_wrap '.$css.'">
                        <form name="contact" method="POST">
                            <ul class="row">
                                <li class="col-sm-12">
                                    <label class="labelWrap">
                                        <span class="hideLabel">Name</span>
                                        <input type="text" name="name" placeholder="Name">
                                    </label>
                                </li>

                                <li class="col-sm-12">
                                    <label class="labelWrap">
                                        <span class="hideLabel">Email</span>
                                        <input type="text" name="email" placeholder="Email">
                                    </label>
                                </li>

                                <li class="col-sm-12">
                                    <label class="labelWrap">
                                        <span class="hideLabel">Phone</span>
                                        <input type="text" name="phone" placeholder="Phone">
                                    </label>
                                </li>
                                <li class="col-sm-12">
                                    <label class="labelWrap">
                                        <span class="hideLabel">Message</span>
                                        <textarea name="message" placeholder="Message"></textarea>
                                    </label>
                                </li>
                                <li class="col-sm-12">
                                    <div class="labelWrap">';

                                        // if($settings['isCaptcha'])
                                        //     $form .=  '<div class="captcha_img"><div data-sitekey="'.$captcha['googleSiteKey'].'" class="g-recaptcha"></div></div>';

                                $form .= '<div class="btn_wr">
                                            <button type="submit" data-action="submit" onclick="submitForm(this, event);">Submit</button>
                                            <input type="hidden" name="SourceForm" value="contact">
                                            <input type="hidden" name="goto" value="'.SITE_LOC_PATH.'/'.$pageData['permalink'].'/thank-you/">
                                        </div>
                                    </div>
                                </li>
                            </ul>
                            <div class="ErrInqMsg"></div>
                        </form>
                    </div>
                    <script defer type="text/javascript">
                        function submitForm(obj, event) { 
                            event.preventDefault();

                            grecaptcha.ready(function() {
                                grecaptcha.execute("'.$captcha['googleSiteKey'].'", {action: \'submit\'}).then(function(token) {
                                    var btn      = $(obj), 
                                        form     = btn.parents(\'form\'), 
                                        formData = form.serialize()+\'&g-recaptcha-response=\'+token,
                                        msg      = form.find(\'.ErrInqMsg\'), 
                                        url      = "'.SITE_LOC_PATH.'/ajx_action/'.$pageData['permalink'].'/";
                                    ajaxFormSubmit(form, formData, btn, msg, url, false);
                                });
                            });
                        }
                    </script>';
            
            echo $form;
        }
    }
    
    function contact($pageData) {
            
        $actMsg['type']     = 0;
        
        if($this->_request['name'] && $this->_request['email'] && $this->_request['phone'] && $this->_request['message']) {
            
            $settings         = $this->model->settings($pageData['parent_dir']);
            $settings         = unserialize($settings['value']);
            
            $gObj = new genl();
            if($gObj->validate_email($this->_request['email'])) {
                
                $error = 0;
                
                if($settings['isCaptcha'] == '1') {
                    if($this->_request['g-recaptcha-response']) {

                        $responseData = validateGoogleCapcha($this->_request['g-recaptcha-response']);

                        //var_dump($responseData);

                        if($responseData['success'] && $responseData['score'] > 0.5 ) {
                            $error = 0;
                        } else {
                            $error = 1;
                            $actMsg['message'] = 'Captcha verification failed!';
                        }
                    } else {
                        $error = 1;
                        $actMsg['message'] = 'Please verify that you are not a robot!';
                    }
                }
                
                if(!$error) {
                    
                    if(!$settings['emailBody'])
                        $msg_details                = $this->model->getEmailBodyById(11);
                    else{
                        $msg_details['emailBody']       = $settings['emailBody'];
                        $msg_details['emailSubject']    = $settings['emailSubject'];
                    }

                    $params                     = array();
                    $params['name']             = $this->_request['name'];
                    $params['email']            = $this->_request['email'];
                    $params['phone']            = $this->_request['phone'];
                    $params['subject']          = $msg_details['emailSubject'];
                    $params['comments'] 		= $this->_request['message'];
                    $params['contactType']  	= 'C';
                    $params['status']  			= 'Y';
                    $params['serializedData']	= '';
                    $params['entryDate']        = date('Y-m-d H:i:s');

                    $insId                      = $this->model->newContact($params);

                    if($insId) {

                        /********************* Send Email *******************/
                        $to         = $settings['toEmail'];
                        $from       = "From: ".SITE_NAME."<".$settings['replyTo'].">";

                        $opt['cc']  = $settings['cc'];
                        $opt['bcc'] = $settings['bcc'];

                        $subject    = $msg_details['emailSubject'];
                        $msg        = $msg_details['emailBody'];

                        $name		= $this->_request['name'];
                        $email		= $this->_request['email'];
                        $phone		= $this->_request['phone'];
                        $message	= $this->_request['message'];
                        $arr = array(
                                "{siteName}"		=> SITE_NAME,
                                "{name}" 			=> $name,
                                "{email}" 			=> $email,
                                "{phone}" 			=> $phone,
                                "{comments}"        => $message,
                                "{link}" 			=> SITE_ADMIN_PATH
                        );

                        $msg    = strtr($msg,$arr);
                        $mail   = sendEmail($to, $from, $subject, $msg, $opt);
                        /********************* Send Email *******************/
                        
                        if($mail == 1) {

                            $this->session->write('MSGSHOW', 'Y');
                            $this->session->write('MSG', $settings['successMsg']);

                            $actMsg['type']           = 1;
                            $actMsg['message']        = 'Please wait...';
                            $actMsg['goto']           = $this->_request['goto'];
                        }
                        else
                            $actMsg['message']  = $mail;
                    }
                    else
                        $actMsg['message']  = 'Oops! Something went wrong. Please close your browser window and try again.';
                }
            }
            else
                $actMsg['message'] = 'Email ID is invalid!';
        }
        else
            $actMsg['message'] = 'All fields are mandatory!';
        
        return $actMsg;
    }

    // For API endpoint ...
    function contactus($pageData) {
        $response['errorCode']      = 1;
        $response['errorMessage']   = '';
        $response['resourceName']   = '';
        $response['data']           = null;
        $response['seoData']        = null;

        if($pageData) {
            $response['resourceName']   = $pageData['categoryName'];
            $pageContent                = $this->content($pageData['categoryId']);
            $formattedData              = [];

            if($pageContent) {
                $objContent             = new stdClass;
                foreach($pageContent['content'] as $item) {
                    $objContent->contentHeading             = $item['contentHeading'];
                    $objContent->permalink                  = $item['permalink'];
                    $objContent->displayHeading             = $item['displayHeading'];
                    $objContent->contentDescription         = $item['contentDescription'];
                    $objContent->contentShortDescription    = $item['contentShortDescription'];
                    $objContent->ImageName                  = $item['ImageName'];
                    $objContent->contentStatus              = $item['contentStatus'];
                    $objContent->contactDetails             = ($item['serializedData'] != null ? unserialize($item['serializedData']) : null);

                    $formattedData[]    = $objContent;
                }
                $response['data']       = $formattedData;
                $response['errorCode']  = 0;
            }

            $seoID                  = $pageData['seoId'];
            $smodel                 = new SeoModel;
            $seoRecords             = $smodel->getSeoDataById($seoID);
            if(is_array($seoRecords) && count($seoRecords) > 0) {
                if( $seoRecords['ogImage'] && file_exists(MEDIA_FILES_ROOT.$seoRecords['ogImage']) )
                    $ogImgSrc = MEDIA_FILES_SRC.$seoRecords['ogImage'];
                else 
                    $ogImgSrc = '';

                $seoContent                             = new stdClass;
                $seoContent->titleandMetaUrl            = SITE_LOC_PATH.$seoRecords['titleandMetaUrl'];
                $seoContent->canonicalUrl               = $seoRecords['canonicalUrl'];
                $seoContent->pageTitleText              = $seoRecords['pageTitleText'];
                $seoContent->metaTag                    = $seoRecords['metaTag'];
                $seoContent->metaDescription            = $seoRecords['metaDescription'];
                $seoContent->ogImage                    = $ogImgSrc;
                $seoContent->others                     = $seoRecords['others'];
                $seoContent->status                     = $seoRecords['status'];

                $response['seoData']                    = $seoContent;
            }
        }
        return $response;
    }

    // End :: For API endpoint ...
}
?>