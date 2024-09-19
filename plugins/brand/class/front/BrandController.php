<?php defined('BASE') OR exit('No direct script access allowed.');

class BrandController extends REST
{
    private    $model;
    protected  $pageview;
    protected  $response = [];

    public function __construct(BrandModel $model = null) {
        parent::__construct();

        if ($model == null)
            $model = new BrandModel;

        $this->model = $model;
    }

    function index($pageData = '') {

        if (!$pageData)
            return;
            
        //Open this code only if dtls does not exist, so that 404 will be handled by Skeleton.
        /*if ($this->_request['dtls'])
            return;*/

        //Default routing (optional); 
        //$this->routing();

        $this->response['body']  = $this->pageview;
        return $this->response;
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