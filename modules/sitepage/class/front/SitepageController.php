<?php 
defined('BASE') OR exit('No direct script access allowed.');
class SitepageController extends REST
{   
    private    $model;
	protected  $response = array();
	
    public function __construct($model = '') {
    	parent :: __construct();
        if($model)
            $this->model = new $model;
    }
	
	
}
?>