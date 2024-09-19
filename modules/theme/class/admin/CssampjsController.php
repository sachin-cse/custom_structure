<?php defined('BASE') OR exit('No direct script access allowed.');
class CssampjsController extends REST
{
    private    $model;
    protected  $response = array();

    public function __construct($model) {
        parent::__construct();
        $this->model        = new $model;
    }

    function index($act = []) {
        $settings                   = $this->model->settings();
        $this->response['resource'] = unserialize($settings['value']);
    
        return $this->response;
    }
    
    function addEditCssJs() {
        
        $actMsg['type']     = 0;
        $actMsg['message']  = '';

        $error = 0;
            
        if(!$error) {

            $codeHeader = $codeFooter = '';
            $paramsRes  = array();

            if($this->_request['resourceCss'][0]!='' || $this->_request['resourceJs'][0]!='' || $this->_request['environment']!='') {
                $noscript = [];
                foreach($this->_request['resourceCss'] as $key => $resourceCss) {

                    if($resourceCss != '') {

                        $res                = array('file' => $resourceCss, 'opt' =>$this->_request['resourceCssOpt'][$key]);
                        $paramsRes['css'][] = $res;

                        if(!$this->_request['resourceCssOpt'][$key])
                            $codeHeader .='<link rel="stylesheet" href="'.$resourceCss.'">';
                        else{
                            $codeHeader .='<link rel="'.$this->_request['resourceCssOpt'][$key].'" as="style" onload="this.rel = \'stylesheet\'" href="'.$resourceCss.'">';

                            $noscript[] = '<link rel="stylesheet" href="'.$resourceCss.'">';
                        }
                    }
                }

                if(sizeof($noscript) > 0){
                    $codeHeader .= '<noscript>';
                    $codeHeader .= implode('', $noscript);
                    $codeHeader .= '</noscript><script>var isSafari=/constructor/i.test(window.HTMLElement)||function(e){return"[object SafariRemoteNotification]"===e.toString()}(!window.safari||safari.pushNotification),isIE=!!document.documentMode,isEdge=!isIE&&!!window.StyleMedia;if(isSafari||isIE||isEdge)for(var a=document.getElementsByTagName("link"),n=0;n<a.length;n++){var o=a[n];"prefetch"==o.rel&&(o.setAttribute("rel","stylesheet"),o.setAttribute("onload","null"))}
                    !function(t){"use strict";t.loadCSS||(t.loadCSS=function(){});var e=loadCSS.relpreload={};if(e.support=function(){var e;try{e=t.document.createElement("link").relList.supports("preload")}catch(t){e=!1}return function(){return e}}(),e.bindMediaToggle=function(t){var e=t.media||"all";function a(){t.addEventListener?t.removeEventListener("load",a):t.attachEvent&&t.detachEvent("onload",a),t.setAttribute("onload",null),t.media=e}t.addEventListener?t.addEventListener("load",a):t.attachEvent&&t.attachEvent("onload",a),setTimeout(function(){t.rel="stylesheet",t.media="only x"}),setTimeout(a,3e3)},e.poly=function(){if(!e.support())for(var a=t.document.getElementsByTagName("link"),n=0;n<a.length;n++){var o=a[n];"preload"!==o.rel||"style"!==o.getAttribute("as")||o.getAttribute("data-loadcss")||(o.setAttribute("data-loadcss",!0),e.bindMediaToggle(o))}},!e.support()){e.poly();var a=t.setInterval(e.poly,500);t.addEventListener?t.addEventListener("load",function(){e.poly(),t.clearInterval(a)}):t.attachEvent&&t.attachEvent("onload",function(){e.poly(),t.clearInterval(a)})}"undefined"!=typeof exports?exports.loadCSS=loadCSS:t.loadCSS=loadCSS}("undefined"!=typeof global?global:this);</script>';
                }


                $delay      = [];
                $wait       = [];
                $appendToDelay   = [];
                $appendToWait   = [];

                $env    = ($this->_request['environment'] == 1) ? 'data-cfasync="false"' : '';
                $envAtt = ($this->_request['environment'] == 1) ? "scriptElem.setAttribute('data-cfasync', 'false');" : '';
                foreach($this->_request['resourceJs'] as $key => $resourceJs) {

                    if($resourceJs != '') {

                        $res                = array('file' => $resourceJs, 'opt' =>$this->_request['resourceJsOpt'][$key], 'append' =>$this->_request['resourceJsAppend'][$key]);
                        $paramsRes['js'][]  = $res;

                        if(!$this->_request['resourceJsOpt'][$key] || $this->_request['resourceJsOpt'][$key] == 'async')
                            $codeFooter .='<script '.$env.' '.$this->_request['resourceJsOpt'][$key].' type="text/javascript" src="'.$resourceJs.'"></script>';
                        elseif($this->_request['resourceJsOpt'][$key] == 'defer') {
                            $appendToDelay[]    = $this->_request['resourceJsAppend'][$key];
                            $delay[]            = $resourceJs;
                        }
                        elseif($this->_request['resourceJsOpt'][$key] == 'wait') {
                            $appendToWait[]     = $this->_request['resourceJsAppend'][$key];
                            $wait[]             = $resourceJs;
                        }
                        
                    }
                }

                if(sizeof($delay) > 0 || sizeof($wait) > 0){
                    $codeFooter .='<script '.$env.' type="text/javascript">';

                    if(sizeof($delay) > 0) {
                        $codeFooter .= 'function $import(src, appendTo){ var scriptElem = document.createElement(\'script\'); scriptElem.setAttribute(\'src\',src); scriptElem.setAttribute(\'type\',\'text/javascript\'); '.$envAtt.' document.getElementsByTagName(appendTo)[0].appendChild(scriptElem); } var delay = 1; setTimeout(function(){';

                        foreach($delay as $key => $js)
                            $codeFooter .= '$import("'.$js.'", "'.$appendToDelay[$key].'");';

                        $codeFooter .= '}, delay * 1000);';
                    }

                    if(sizeof($wait) > 0) {
                        $codeFooter .= 'function defer(method, src, appendTo) { if(window.jQuery){ method(src, appendTo); } else { setTimeout(function(){ defer(method, src, appendTo);  }, 50); } }';
                        foreach($wait as $key => $js)
                            $codeFooter .= 'defer($import, "'.$js.'", "'.$appendToWait[$key].'");';
                    }

                    $codeFooter .= '</script>';
                }

                $paramsRes['environment'] = $this->_request['environment'];
            }

            $params                         = [];
            $params['value']                = serialize($paramsRes);
            $exist                          = $this->model->settings();

            if(!$exist) {
                $params['name']             = 'CSSJS';
                $this->model->newSettings($params);
                $actMsg['message']          = 'Data inserted successfully.';
            } else {
                $this->model->updateSetting($params);
                $actMsg['message']          = 'Data updated successfully.';
            }

            $cacheHeader         = CACHE_ROOT.DS.'css.html';
            $cacheHeader         = fopen($cacheHeader, "w");

            fwrite($cacheHeader, $codeHeader);
            fclose($cacheHeader);

            $cacheFooter         = CACHE_ROOT.DS.'js.html';
            $cacheFooter         = fopen($cacheFooter, "w");

            fwrite($cacheFooter, $codeFooter);
            fclose($cacheFooter);

            $actMsg['type']      = 1;
        }
        
        return $actMsg;
    }
}