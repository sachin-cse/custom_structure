<?php 
defined('BASE') OR exit('No direct script access allowed.');
class ContentController  extends REST
{
	private    $model;
	protected  $pageview ;
	protected  $response = array();
	
    public function __construct($model) {
    	parent::__construct();
        $this->model        = new $model;
    }
    
	function index($pageData) {
        $this->response['pageData']         = $pageData;
           
        if($pageData['categoryId'])
    	   $this->response['pageContent']   = $this->content($pageData['categoryId']);
    	
    	$this->pageview                     = 'index.php';
    	$this->response['body']             = $this->pageview;
        
        return $this->response; 
    }
    
    function content($categoryId) {
            
        $rsArry 				            = [];

        $rsArry['contentCount']	            = $this->model->countContentbymenucategoryId($categoryId);

        if($rsArry['contentCount']) {

            $p                              = new Pager;
            $rsArry['contentLimit']         = VALUE_PER_PAGE;
            $start                          = $p->findStart($rsArry['contentLimit'], $this->_request['page']);
            $contentPages                   = $p->findPages($rsArry['contentCount'], $rsArry['contentLimit']);

            $rsArry['content']              = $this->model->getContentbymenucategoryId($categoryId, $start, $rsArry['contentLimit']);

            if($rsArry['contentCount'] > 0 && ceil($rsArry['contentCount'] / $rsArry['contentLimit']) > 1) {
                
                $rsArry['contentPage']      = ($this->_request['page']) ? $this->_request['page'] : 1;
                $rsArry['totalContentPage'] = ceil($rsArry['contentCount'] / $rsArry['contentLimit']);

                $rsArry['contentPageList']  = $p->pageList($rsArry['contentPage'], $_SERVER['REQUEST_URI'], $contentPages);
            }
    	    return $rsArry;
        }
    }

    // For API endpoint ...

    function api_action($pageData=[])
    {
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