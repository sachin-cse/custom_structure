<?php defined('BASE') OR exit('No direct script access allowed.');

class FaqController extends REST
{
    private    $model;
    protected  $pageview;
    protected  $response = [];

    public function __construct(FaqModel $model = null) {
        parent::__construct();

        if ($model == null)
            $model = new FaqModel;

        $this->model = $model;
    }

    function index($pageData = '') {
        if(isset($this->_request['dtaction']))
            return;
		
        if($pageData) {
            // $this->response['pageContent']       = $this->content($pageData['categoryId']);
        
            $ExtraQryStr                    	 = 1;
            $this->response['rowCount']			 = $this->model->faqCount($ExtraQryStr);
            if($this->response['rowCount'] > 0) {
                $p                               = new Pager;
                $this->response['limit']         = VALUE_PER_PAGE;
                $start                           = $p->findStart($this->response['limit'], $this->_request['page']);
                $pages                           = $p->findPages($this->response['rowCount'], $this->response['limit']);

                $this->response['faqs']  	 	 = $this->model->getFaq($ExtraQryStr, $start, $this->response['limit']);
                if($this->response['rowCount'] > 0 && ceil($this->response['rowCount'] / $this->response['limit']) > 1) {
                    
                    $this->response['page']      = ($this->_request['page']) ? $this->_request['page'] : 1;
                    $this->response['totalPage'] = ceil($this->response['rowCount'] / $this->response['limit']);

                    $this->response['pageList']  = $p->pageList($this->response['page'], $_SERVER['REQUEST_URI'], $pages);
                }
            }

            $this->pageview         = 'index.php';
            $this->response['body'] = $this->pageview;
            return $this->response; 
        }
    }

    function routing() {
        
        if ($this->_request['dtaction']) {
            $func  = str_replace('-', '', $this->_request['dtaction']);

            if ((int)method_exists($this, $func) > 0) {
                $this->$func();
            }
        }
    }
}