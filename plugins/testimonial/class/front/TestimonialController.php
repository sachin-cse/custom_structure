<?php
defined('BASE') OR exit('No direct script access allowed.');
class TestimonialController extends REST
{
	private    $model;
	private    $settings;
	protected  $pageview;
	protected  $response = array();
	
    public function __construct($model) {
    	parent::__construct();
        $this->model        = new $model;
    }
    
	function index($pageData = []) {
        
        if($this->_request['dtls'])
            return;
        
		if($pageData) {
            
            $this->pageData     = $pageData;

            $this->response['pageContent']          = $this->content($pageData['categoryId']);
                    
            $settings                               = $this->model->settings($pageData['parent_dir']);
            $settings                               = unserialize($settings['value']);
            $settings['name']                       = $pageData['parent_dir'];
            $this->settings                         = $settings;

            if($this->_request['dtaction']) {
                
                $this->item();
            }
            else {

                $ExtraQryStr                    	 = 1;
                $this->response['rowCount']			 = $this->model->reviewCount($ExtraQryStr);

                if($this->response['rowCount']) {

                    $p                               = new Pager;
                    $this->response['limit']         = ( $this->settings['limit'] )?  $this->settings['limit'] : VALUE_PER_PAGE;
                    $start                           = $p->findStart($this->response['limit'], $this->_request['page']);
                    $pages                           = $p->findPages($this->response['rowCount'], $this->response['limit']);

                    $records                         = $this->model->getReviewByLimit($ExtraQryStr, $start, $this->response['limit']);
                    
                    $itemList                        = $this->itemList($records);
                    $this->response['itemList']      = implode($itemList);

                    if(ceil($this->response['rowCount'] / $this->response['limit']) > 1) {
                        $this->response['page']      = ($this->_request['page']) ? $this->_request['page'] : 1;
                        $this->response['totalPage'] = ceil($this->response['rowCount'] / $this->response['limit']);

                        $this->response['pageList']  = $p->pageList($this->response['page'], $_SERVER['REQUEST_URI'], $pages);
                    }
                }
                
                $this->pageview         = 'index.php';
            }
            
            if($this->pageview) {

                $this->response['body']     = $this->pageview;
                $this->response['pageData'] = $pageData;
                return $this->response; 
            }

			$this->response['body'] = $this->pageview;
            
			return $this->response; 
		}
    }
    
    function item() {
        
        $record                                 = $this->model->reviewByPermalink($this->_request['pageType']);
        
        if($record) {
            $this->response['recordDetails']    = $record;

            if($this->settings['isAvatar']) {
                $avatar = $record['testimonialImage'];

                if($avatar && file_exists(MEDIA_FILES_ROOT.DS.$this->settings['name'].DS.'thumb'.DS.$avatar))
                    $this->response['testimonialImage'] = MEDIA_FILES_SRC.DS.$this->settings['name'].DS.'thumb'.DS.$avatar;
                else
                    $this->response['testimonialImage'] = STYLE_FILES_SRC.DS.'images'.DS.'male.png';
            }

            $ExtraQryStrCommon                  = "tt.testimonialId <> ".addslashes($record['testimonialId']);
            $ExtraQryStr                        = $ExtraQryStrCommon." AND tt.displayOrder >= ".addslashes($record['displayOrder']);
            $ExtraQryStrPrev                    = $ExtraQryStrCommon." AND tt.displayOrder <= ".addslashes($record['displayOrder']);
            $ExtraQryStrNext                    = $ExtraQryStrCommon." AND tt.displayOrder >= ".addslashes($record['displayOrder']);

            $this->response['prevRecord']       = $this->model->getPrevRecord($ExtraQryStrPrev);
            $this->response['nextRecord']       = $this->model->getNextRecord($ExtraQryStrNext);

            $this->response['rowCount']			= $this->model->reviewCount($ExtraQryStr);

            if($this->response['rowCount']) {
                
                $p                              = new Pager;
                $this->response['limit']        = ( $this->settings['limit'] )?  $this->settings['limit'] : VALUE_PER_PAGE;
                $start                          = $p->findStart($this->response['limit'], $this->_request['page']);
                $pages                          = $p->findPages($this->response['rowCount'], $this->response['limit']);

                $records                        = $this->model->getReviewByLimit($ExtraQryStr, $start, $this->response['limit']);
                
                $itemList                       = $this->itemList($records);
                $this->response['itemList']     = implode($itemList);

                if(ceil($this->response['rowCount'] / $this->response['limit']) > 1) {
                    $this->response['page']     = ($this->_request['page']) ? $this->_request['page'] : 1;
                    $this->response['totalPage']= ceil($this->response['rowCount'] / $this->response['limit']);

                    $this->response['pageList'] = $p->pageList($this->response['page'], $_SERVER['REQUEST_URI'], $pages);
                }
            }

            $this->pageview                     = 'details.php';
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
    
    function ajx_action(){
        
    }
    
    function showcase($opt = []) {
        
        $settings           = $this->model->settings($opt['module']);
        $settings           = unserialize($settings['value']);
        $settings['name']   = $opt['module'];
        $settings['invoker']= 'hook';
        $this->settings     = $settings;
        if($settings['isShowcase']) {
        
            $showcaseFile           = CACHE_ROOT.DS.'testimonial_showcase.html';
            if(file_exists($showcaseFile)) {
                include $showcaseFile;
                return;
            }

            $limit      = ($settings['showcaseNo'])? $settings['showcaseNo'] : 3;
            $records	= $this->model->getReviewByLimit("tt.isShowcase = 'Y'", 0, $limit);

            if($records) {

                $wrapcss    = ($opt['wrapcss']) ?   $opt['wrapcss'] : '';
                $css        = ($opt['css']) ?       $opt['css']     : '';
                $col        = ($opt['col'])?        $opt['col']     : 'col-sm-4 col-xs-6';
                $slider     = ($opt['slider'])?     $opt['slider']  : false;
                $withIcon   = ($settings['isIcon'])?'withIcon'      : '';

                $this->result[]   = '<section class="section '.$wrapcss.'">
                                        <div class="container">
                                            <h2 class="heading">'.headingModify($settings['showcaseTitle']).'</h2>';

                if(trim($settings['showcaseDescription'])) {
                    $this->result[]   = '<div class="sk_content_wrap mb30">
                                        <div class="sk_content">
                                            <div class="editor_text">
                                                '.$settings['showcaseDescription'].'
                                            </div>
                                        </div>
                                    </div>';
                }

                $this->result[]   = '<div class="'.$css.'">';
                
                $this->itemList($records, $col, $slider);
                
                /* $this->result[] = '<div class="btn_center"><a href="'.SITE_LOC_PATH.'/'.$this->settings['menuPermalink'].'/" class="btn">View Testimonials</a></div></div></div></section>'; */
                $this->result[] = '</div></div></section>';

                $html = implode($this->result);

                echo $html;
            }
        }
    }
    
    function itemList($records, $col = 'col-sm-12', $slider = false) {
        
        if($slider === true){
            $this->result[]     = '<div class="owl-carousel">';
            $itemElem           = '<div class="item">'; 
            $itemElemEnd        = '</div>'; 
            $itemElemWrapEnd    = '</div><script defer="" type="text/javascript">
                                        function loadOwlTesti(){
                                        if(window.jQuery){
                                            $(".testimonial_list .owl-carousel").owlCarousel({ 
                                                items: 2,
                                                loop: false,
                                                autoplay: false,
                                                autoplayHoverPause: true,
                                                autoplayTimeout: 3000,
                                                smartSpeed: 1000,
                                                margin: 30,
                                                dots: true,
                                                nav: false,
                                                navElement: \'div\',
                                                navText: ["<i class=\'fa fa-angle-left\'></i>", "<i class=\'fa fa-angle-right\'></i>"],
                                                lazyLoad: true,
                                                responsive: {
                                                    0: { items: 1 },
                                                    480: { items: 1 },
                                                    600: { items: 1 },
                                                    768: { items: 2 },
                                                    992: { items: 2 },
                                                    1600: { items: 2 }
                                                }, 
                                            });} else {setTimeout(function(){ loadOwlTesti();}, 50);}}loadOwlTesti();</script>'; 
            $lazyClass          = 'owl-lazy';
        } else {
            $this->result[]     = '<ul class="ul row">';
            $itemElem           = '<li class="'.$col.'">';
            $itemElemEnd        = '</li>'; 
            $itemElemWrapEnd    = '</ul>'; 
            $lazyClass          = 'lazy';
        }
        
        foreach($records as $data) {
            
            $link = SITE_LOC_PATH.'/'.$data['permalink'].'/'.$data['menuPermalink'].'/';

            $figureImg = $withIcon = '';

            if( $this->settings['isAvatar'] ) {

                if( $data['testimonialImage'] && file_exists(MEDIA_FILES_ROOT.DS.$this->settings['name'].DS.'thumb'.DS.$data['testimonialImage']) )
                    $figSrc = MEDIA_FILES_SRC.'/'.$this->settings['name'].'/thumb/'.$data['testimonialImage'];
                else
                    $figSrc = STYLE_FILES_SRC.'/images/male.png';

                $figureImg = '<figure>
                                <img class="'.$lazyClass.'" src="'.STYLE_FILES_SRC.'/images/blank.png" data-src="'.$figSrc.'" alt="'.$data['authorName'].'">
                            </figure>';
                $withIcon = 'withIcon';
            }
            
            $designation = ($data['designation']) ? ' <span>'.$data['designation'].'</span>' : '';

            if($this->settings['isReadMore']){
                if($this->settings['invoker'] == 'hook') {
                    $description = (strlen(trim(strip_tags($data['testimonialDescription']))) > $this->settings['buttonLimit']) ? '<div class="sk_para editor_text mCustomScrollbar">'.stringModify($data['testimonialDescription'], 0, $this->settings['buttonLimit']).'</div> <a href="'.$link.'" class="readmore">'.$this->settings['buttonText'].'</a>' : '<div class="sk_para editor_text">'.$data['testimonialDescription'].'</div>';
                }
                else {
                    $description = (strlen(trim(strip_tags($data['testimonialDescription']))) > $this->settings['buttonLimit']) ? '<div class="sk_para editor_text">'.stringModify($data['testimonialDescription'], 0, $this->settings['buttonLimit']).' <a href="'.$link.'" class="readmore">'.$this->settings['buttonText'].'</a></div>' : '<div class="sk_para editor_text">'.$data['testimonialDescription'].'</div>';
                }
            }
            else
                $description = '<div class="sk_para editor_text">'.$data['testimonialDescription'].'</div>';

            $this->result[] = sprintf('%s
                            <div class="sk_box %s">%s
                                <div class="sk_text">
                                    %s
                                    <div class="subheading">%s 
                                        %s
                                    </div>
                                </div>
                            </div>
                        %s', $itemElem, $withIcon, $figureImg, $description, $data['authorName'], $designation, $itemElemEnd);

            $this->settings['menuPermalink'] = $data['menuPermalink'];
        }
        
        $this->result[]   = $itemElemWrapEnd;

        return $this->result;
    }

    // API endpoint ...
    //-----------------
    function testimonial($pageData=[]) {
        $response['errorCode']      = 1;
        $response['errorMessage']   = '';
        $response['resourceName']   = '';
        $response['data']           = null;
        $response['seoData']        = null;

        $settings                   = $this->model->settings($pageData['parent_dir']);
        $settings                   = unserialize($settings['value']);
        $settings['name']           = $pageData['parent_dir'];
        $this->settings             = $settings;

        

        $currPage = ($this->_request['page'] ? trim($this->_request['page']) : 1);

        if($pageData) {
            $response['resourceName']            = $pageData['categoryName'];

            $ExtraQryStr                    	 = 1;
            $rowCount			                 = $this->model->reviewCount($ExtraQryStr);

            if($rowCount) {
                $p                               = new Pager;
                $limit                           = ( $this->settings['limit'] )?  $this->settings['limit'] : VALUE_PER_PAGE;
                $start                           = $p->findStart($limit, $currPage);
                $pages                           = $p->findPages($rowCount, $limit);
                $records                         = $this->model->getReviewByLimit($ExtraQryStr, $start, $limit);
                
                if(is_array($records) && count($records) > 0) {
                    $objContent                             = new stdClass;
                    foreach($records as $item) {
                        $objContent->authorName             = $item['authorName'];
                        $objContent->permalink              = $item['permalink'];
                        $objContent->designation            = $item['designation'];
                        $objContent->testimonialDescription = $item['testimonialDescription'];
                        $objContent->testimonialImage       = $item['testimonialImage'];
                        $objContent->status                 = $item['status'];
                        $objContent->entryDate              = $item['entryDate'];

                        $formattedData[]                    = $objContent;
                    }
                    $response['data']       = $formattedData;
                    $response['errorCode']  = 0;
                    $response['totalCount'] = $rowCount;
                }
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

    // End :: API endpoint ...
    //------------------------
}
?>