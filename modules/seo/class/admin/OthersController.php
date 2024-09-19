<?php 
defined('BASE') OR exit('No direct script access allowed.');
class OthersController  extends REST
{
	private    $model;
	protected  $response = array();
	
    public function __construct($model) {
    	parent::__construct();
        $this->model        = new $model;
    }
    
	function index() {
        
    	$settings                   = $this->model->settings();
        $this->response['others']   = unserialize($settings['value']);
        
        return $this->response;
    }
    
    function addEditOther() {
        
        $actMsg['type']     = 0;
        $actMsg['message']  = '';
        
        $seoData            = trim($this->_request['seoData']);
        $googleAnalytics    = trim($this->_request['googleAnalytics']);
        $tagManager         = trim($this->_request['tagManager']);
        $tagManagerNoscript = trim($this->_request['tagManagerNoscript']);
        

        $paramsSeo                       = array();
        $paramsSeo['seoData']            = $seoData;
        $paramsSeo['googleAnalytics']    = $googleAnalytics;
        $paramsSeo['tagManager']         = $tagManager;
        $paramsSeo['tagManagerNoscript'] = $tagManagerNoscript;
        
        $params                          = [];
        $params['value']                 = serialize($paramsSeo);
        
        $exist                           = $this->model->settings();
                
        if(!$exist) {
                    
            $params['name']         = 'SEO';
            $this->model->newSettings($params);
            $actMsg['message']      = 'Data inserted successfully.';

        } else {

            $this->model->updateSetting($params);
            $actMsg['message']      = 'Data updated successfully.';
        }

        $actMsg['type']     = 1;
        

        if($_FILES['RobotFile']['name']) {

            if(strtolower($_FILES['RobotFile']['name']) == 'robots.txt') {
                $RobotFileName      = $_FILES['RobotFile']['name'];
                $target_path_robot  = ROOT_PATH.'/'.$RobotFileName;

                if(file_exists($target_path_robot)) {
                    @unlink($target_path_robot);
                }
                @move_uploaded_file($_FILES['RobotFile']['tmp_name'], $target_path_robot);
            }
            else
                $actMsg['message'] .= 'Invalid file type!';
        }

        if($_FILES['SiteMapFile']['name']) {

            if(strtolower($_FILES['SiteMapFile']['name']) == 'sitemap.xml') {
                $SiteMapFileName        = $_FILES['SiteMapFile']['name'];
                $target_path_SiteMapt   = ROOT_PATH.'/'.$SiteMapFileName;

                if(file_exists($target_path_SiteMapt)) {
                    @unlink($target_path_SiteMapt);
                }

                @move_uploaded_file($_FILES['SiteMapFile']['tmp_name'], $target_path_SiteMapt);
            }
            else
                $actMsg['message'] .= 'Invalid sitemap file!';
        }
        
        return $actMsg;
    }
}
?>