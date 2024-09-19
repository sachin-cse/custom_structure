<?php 
defined('BASE') OR exit('No direct script access allowed.');
class DefaulthomeController  extends REST
{
	private    $model;
	protected  $response = array();
	
    public function __construct($model) {
    	parent::__construct();
        $this->model        = new $model;
    }
    
	function index() {
        
        $this->response['default']   = $this->model->getDefaultTitleAndMeta($this->session->read('SITEID'));
        $this->response['home']      = $this->model->getHomeTitleAndMeta($this->session->read('SITEID'));
        
        $compare                     = array_diff($this->response['default'], $this->response['home']);
        if(sizeof($compare) == 2 && $compare['titleandMetaId'] && $compare['titleandMetaType'])
            $this->response['same']  = 1;
        else
            $this->response['same']  = 0;
        
        return $this->response;
    }
    
    function defaultTitleMeta() {
        
        $actMsg['type']             = 0;
        $actMsg['message']          = '';
        
        if(isset($this->_request['sameCheck'])) {
            
            $pageTitleText              = $homePageTitleText    = trim($this->_request['pageTitleText']);

            $metaTag                    = $homeMetaTag          = trim($this->_request['metaTag']);
            $metaDescription            = $homeMetaDescription  = trim($this->_request['metaDescription']);

            $metaRobotsIndex            = $homeMetaRobotsIndex  = trim($this->_request['metaRobotsIndex']);
            $metaRobotsFollow           = $homeMetaRobotsFollow = trim($this->_request['metaRobotsFollow']);
        }
        else {
            
            $pageTitleText              = trim($this->_request['pageTitleText']);

            $metaTag                    = trim($this->_request['metaTag']);
            $metaDescription            = trim($this->_request['metaDescription']);

            $metaRobotsIndex            = trim($this->_request['metaRobotsIndex']);
            $metaRobotsFollow           = trim($this->_request['metaRobotsFollow']);
        
            $homePageTitleText          = trim($this->_request['homePageTitleText']);

            $homeMetaTag                = trim($this->_request['homeMetaTag']);
            $homeMetaDescription        = trim($this->_request['homeMetaDescription']);

            $homeMetaRobotsIndex        = trim($this->_request['homeMetaRobotsIndex']);
            $homeMetaRobotsFollow       = trim($this->_request['homeMetaRobotsFollow']);
        }
        
        if($homePageTitleText != '' && $pageTitleText != '') {

            if($this->_request['homeIdToEdit'] != '' && $this->_request['IdToEdit'] != '') {
                
                $params = array();
                $params['pageTitleText']    = $pageTitleText;
                $params['metaTag']          = $metaTag;
                $params['metaDescription']  = $metaDescription;
                $params['metaRobots']       = $metaRobotsIndex.', '.$metaRobotsFollow;
                $this->model->titleMetaUpdateById($params, $this->_request['IdToEdit']);

                $params = array();
                $params['pageTitleText']    = $homePageTitleText;
                $params['metaTag']          = $homeMetaTag;
                $params['metaDescription']  = $homeMetaDescription;
                $params['metaRobots']       = $homeMetaRobotsIndex.', '.$homeMetaRobotsFollow;
                $this->model->titleMetaUpdateById($params, $this->_request['homeIdToEdit']);
                
                $actMsg['type']             = 1;
                $actMsg['message']          = 'Data updated successfully.';
                
            }
            else {
                
                $params = array();
                $params['siteId']           = $this->session->read('SITEID');
                $params['titleandMetaUrl']  = "/";
                $params['pageTitleText']    = $homePageTitleText;
                $params['metaTag']          = $homeMetaTag;
                $params['metaDescription']  = $homeMetaDescription;
                $params['metaRobots']       = $homeMetaRobotsIndex.', '.$homeMetaRobotsFollow;
                $params['titleandMetaType'] = 'H';
                $this->model->newTitleMeta($params);

                $params = array();
                $params['siteId']           = $this->session->read('SITEID');
                $params['titleandMetaUrl']  = "/";
                $params['pageTitleText']    = $pageTitleText;
                $params['metaTag']          = $metaTag;
                $params['metaDescription']  = $metaDescription;
                $params['metaRobots']       = $metaRobotsIndex.', '.$metaRobotsFollow;
                $params['titleandMetaType'] = 'D';
                $this->model->newTitleMeta($params);

                $actMsg['type']             = 1;
                $actMsg['message']          = 'Data inserted successfully.';
                
            }
        } else
            $actMsg['message'] = 'Fields marked with (*) are mandatory.';
        
        return $actMsg;
    }
}
?>