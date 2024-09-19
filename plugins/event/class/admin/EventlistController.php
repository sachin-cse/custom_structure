<?php defined('BASE') OR exit('No direct script access allowed.');
class EventlistController extends REST
{
    private    $model;
    protected  $response = [];

    public function __construct(EventlistModel $model = null) {
        parent::__construct();

        if ($model == null)
            $model  = new EventlistModel;

        $this->model = $model;
    }

    function index($act = []) {
            
        $this->response['linkedPages']          = $this->model->getLinkedPages($this->_request['pageType'], 0, 100);
            
        $settings                               = $this->model->settings($this->_request['pageType']);
        $this->response['settings']             = unserialize($settings['value']);
        
        if(isset($this->_request['editid']) || isset($act['editid']) || $this->_request['dtaction'] == 'add') {
            
            $editid = ($this->_request['editid'])? $this->_request['editid']:$act['editid'];
            
            if($editid) {
                $this->response['events']      = $this->model->eventByIdMenucategoryId($editid);
                $this->response['image'] = $this->model->getEventGalleryByEventId($this->response['resource']['id'], 0, 100);

                $titleandMetaUrl                = '/'.$this->response['resource']['permalink'].'/'.$this->response['resource']['menuPermalink'].'/';
                $seoModel                       = new TitlemetaModel;
                $this->response['seoData']      = $seoModel->titleMetaByUrl($titleandMetaUrl);
            }
        }
        else {
        
            $ExtraQryStr = 1;

            // SEARCH START --------------------------------------------------------------
            if(isset($this->_request['searchText']))
                $this->session->write('searchText', $this->_request['searchText']);

            if($this->session->read('searchText'))
                $ExtraQryStr        .= " AND serviceName LIKE '%".addslashes($this->session->read('searchText'))."%'";

            if(isset($this->_request['searchStatus']))
                $this->session->write('searchStatus', $this->_request['searchStatus']);

            if($this->session->read('searchStatus'))
                $ExtraQryStr        .= " AND status = '".addslashes($this->session->read('searchStatus'))."'";

            if(isset($this->_request['searchShowcase']))
                $this->session->write('searchShowcase', $this->_request['searchShowcase']);

            if($this->session->read('searchShowcase'))
                $ExtraQryStr        .= " AND isShowcase = '".addslashes($this->session->read('searchShowcase'))."'";

            if(isset($this->_request['searchPage']))
                $this->session->write('searchPage', $this->_request['searchPage']);

            if($this->session->read('searchPage'))
                $ExtraQryStr        .= " AND menucategoryId = ".addslashes($this->session->read('searchPage'));

            if(isset($this->_request['Reset']) || isset($this->_request['Search'])) {

                if(isset($this->_request['Reset'])){

                    $this->session->write('searchText',     '');
                    $this->session->write('searchStatus',   '');
                    $this->session->write('searchShowcase', '');
                    $this->session->write('searchPage',     '');
                }

                $this->model->redirectToUrl(SITE_ADMIN_PATH.'/index.php?pageType='.$this->_request['pageType'].'&dtls='.$this->_request['dtls'].'&moduleId='.$this->_request['moduleId']);
            }
            // SEARCH END ----------------------------------------------------------------

            $this->response['rowCount']     = $this->model->eventCount($ExtraQryStr);

            if($this->response['rowCount']) {

                $p                          = new Pager;
                $this->response['limit']    = VALUE_PER_PAGE;
                $start                      = $p->findStart($this->response['limit'], $this->_request['page']);
                $pages                      = $p->findPages($this->response['rowCount'], $this->response['limit']);

                $this->response['events']    	 = $this->model->getEventByLimit($ExtraQryStr, $start, $this->response['limit']);

                if($this->response['rowCount'] > 0 && ceil($this->response['rowCount'] / $this->response['limit']) > 1) {
                    $this->response['page']      = ($this->_request['page']) ? $this->_request['page'] : 1;
                    $this->response['totalPage'] = ceil($this->response['rowCount'] / $this->response['limit']);

                    $this->response['pageList']  = $p->pageList($this->response['page'], $_SERVER['REQUEST_URI'], $pages);
                }
            }
        }
        
        return $this->response;
    }

    function modPage() {
        $srch = trim($this->_request['srch']);

        if ($srch) {
            return $this->model->searchLinkedPages($this->_request['mid'], $this->_request['pageType'], $srch, 0, 10);
        }
    }

    function addEditEvent() {
        $actMsg['type']             = 0;
        $actMsg['message']          = '';
        
        $menucategoryId             = trim($this->_request['menucategoryId']);
        $eventTitle                 = trim($this->_request['event_title']);
        $permalink                  = trim($this->_request['permalink']);
        $eventShortDescription      = trim($this->_request['event_short_description']);
        $eventDescription           = trim($this->_request['event_description']);
        $eventDate                  = trim($this->_request['event_date']);
        $status                     = trim($this->_request['status']);
        $eventCategory              = trim($this->_request['event_category']);
        $eventTag                   = trim($this->_request['event_tag']);
        $displayOrder               = trim($this->_request['displayOrder']);
        $isShowcase                 = trim($this->_request['isShowcase']);
        $pageTitleText              = trim($this->_request['pageTitleText']);
        $metaRobotsIndex            = trim($this->_request['metaRobotsIndex']);
        $metaRobotsFollow           = trim($this->_request['metaRobotsFollow']);
        $metaTag                    = trim($this->_request['metaTag']);
        $metaDescription            = trim($this->_request['metaDescription']);
        $others                     = trim($this->_request['others']);
        
        if($menucategoryId != '' && $eventTitle != '' && $eventDescription != '' &&  $eventDate != '' && $permalink != '' &&  $eventCategory != '' && $eventTag != '') {
            if($this->_request['IdToEdit']!= '')
                $sel_ContentDetails = $this->model->checkExistence("event_title = '".addslashes($resourceTitle)."' AND id != ".$this->_request['IdToEdit']);
            else
                $sel_ContentDetails = $this->model->checkExistence("event_title = '".addslashes($resourceTitle)."'");

            if(sizeof($sel_ContentDetails) < 1) {
                
                //permalink --------------
                $ENTITY          = TBL_EVENT;
                if(!$permalink)
                    $permalink   = $resourceTitle;
                else
                    $permalink   = str_replace('-', ' ', $permalink);

                if($this->_request['IdToEdit'])
                    $ExtraQryStr = 'id != '.$this->_request['IdToEdit'];
                else
                    $ExtraQryStr = 1;
                $permalink       = createPermalink($ENTITY, $permalink, $ExtraQryStr);
                //permalink ---------------

                $params                             = array();
                $params['menucategoryId']           = $menucategoryId;
                $params['event_title']              = $eventTitle;
                $params['permalink']                = $permalink;
                $params['event_short_description']  = $eventShortDescription;
                $params['event_description']       =  $eventDescription;
                $params['event_image'] = $eventImage;
                $params['event_banner'] = $eventBanner;
                $params['event_date'] = $eventDate;
                $params['event_tag'] = $eventTag;
                $params['event_category'] = $eventCategory;
                $params['status']                   = $status;
                $params['isShowcase']               = $isShowcase;
                
                if($displayOrder == 'T' || $displayOrder == 'B'){
                    $order          = $this->model->getDisplayOrder($displayOrder);
                    $displayOrder   = ($displayOrder == 'T')? ($order['displayOrder'] - 1) : ($order['displayOrder'] + 1);
                }
                
                $params['displayOrder']             = $displayOrder;
                
                if($this->_request['IdToEdit'] != '') {
                    $dataBeforeUpdate           = $this->model->eventUpdateById($this->_request['IdToEdit']);
                    $params['event_image'] = $dataBeforeUpdate['event_image'];
                    $params['event_banner'] = $dataBeforeUpdate['event_banner'];
                    $this->model->eventUpdateById($params, $this->_request['IdToEdit']);

                    $actMsg['editid']           = $this->_request['IdToEdit'];
                    $actMsg['message']          = 'Data updated successfully.';
                }
                else {
                    $params['entryDate']        = date('Y-m-d H:i:s');
                    $actMsg['editid']           = $this->model->newEvent($params);
                    $actMsg['message']          = 'Data inserted successfully.';
                }
                    $actMsg['type']             = 1;
                
                //Image ---------------
                $targetLocation = MEDIA_FILES_ROOT.DS.$this->_request['pageType'];
                $targetFile     = MEDIA_FILES_SRC.DS.$this->_request['pageType'];
                $ogUrl          = DS.$this->_request['pageType'];
                
                if (!file_exists($targetLocation) && !is_dir($targetLocation)) 
                    $this->createMedia($targetLocation);
                
                $settings = $this->model->settings($this->_request['pageType']);
                $settings = unserialize($settings['value']);

                $selData      = $this->model->eventByIdMenucategoryId($actMsg['editid']);
                $setCover     = $selData['event_banner'];

                //showArray($_FILES['serviceBanner']);die;
                if($_FILES['event_banner']['name'] && substr($_FILES['event_banner']['type'], 0, 5) == 'image') {

                    $fObj           = new FileUpload;

                    $TWH[0]         = $settings['bannerWidth'];       // thumb width
                    $TWH[1]         = $settings['bannerHeight'];      // thumb height
                    $LWH[0]         = $settings['bannerWidth'];       // large width
                    $LWH[1]         = $settings['bannerHeight'];      // large height
                    $option         = 'all';                  // upload, thumbnail, resize, all

                    $fileName 		= $permalink."-banner-".time();
                    if($target_image = $fObj->uploadImage($_FILES['event_banner'], $targetLocation, $fileName, $TWH, $LWH, $option)) {

                        // delete existing image
                        if($selData['event_banner'] != $target_image) {
                            @unlink($targetLocation.'/normal/'.$selData['event_banner']);
                            @unlink($targetLocation.'/thumb/'.$selData['event_banner']);
                            @unlink($targetLocation.'/large/'.$selData['event_banner']);
                        }

                        // update new image
                        $params                 = array();
                        $params['event_banner']	= $target_image;
                        $this->model->eventUpdateById($params, $actMsg['editid']);
                    }
                }

                // upload icon
                if($_FILES['event_image']['name'] && substr($_FILES['event_image']['type'], 0, 5) == 'image') {

                    $fObj           = new FileUpload;

                    $TWH[0]         = $settings['bannerWidth'];       // thumb width
                    $TWH[1]         = $settings['bannerHeight'];      // thumb height
                    $LWH[0]         = $settings['bannerWidth'];       // large width
                    $LWH[1]         = $settings['bannerHeight'];      // large height
                    $option         = 'all';                  // upload, thumbnail, resize, all

                    $fileName 		= $permalink."-image-".time();
                    if($target_image = $fObj->uploadImage($_FILES['event_image'], $targetLocation, $fileName, $TWH, $LWH, $option)) {

                        // delete existing image
                        if($selData['event_image'] != $target_image) {
                            @unlink($targetLocation.'/normal/'.$selData['event_image']);
                            @unlink($targetLocation.'/thumb/'.$selData['event_image']);
                            @unlink($targetLocation.'/large/'.$selData['event_image']);
                        }

                        // update new image
                        $params                 = array();
                        $params['event_image']	= $target_image;
                        $this->model->eventUpdateById($params, $actMsg['editid']);
                    }
                }


                //Image ---------------   
                if($actMsg['editid']) {
                    //Image ---------------

                    $newImgUploadArr    = $scsImgArr = $errImgArr = array();
                    $fObj 				= new FileUpload;
                    
                    $TWH[0]         = $settings['imageThumbWidth'];     // thumb width
                    $TWH[1]         = $settings['imageThumbHeight'];    // thumb height
                    $LWH[0]         = $settings['imageWidth'];          // large width
                    $LWH[1]         = $settings['imageHeight'];         // large height
                    $option         = 'all';                            // upload, thumbnail, resize, all


                    // foreach($_FILES['serviceImage']['name'] as $imgKey=>$nameValue) {
                        
                    //     $imgValue['name']      = $_FILES['serviceImage']['name'][$imgKey];
                    //     $imgValue['type']      = $_FILES['serviceImage']['type'][$imgKey];
                    //     $imgValue['tmp_name']  = $_FILES['serviceImage']['tmp_name'][$imgKey];
                    //     $imgValue['error']     = $_FILES['serviceImage']['error'][$imgKey];
                    //     $imgValue['size']      = $_FILES['serviceImage']['size'][$imgKey];
         

                    //     if($imgValue['name'] && substr($imgValue['type'], 0, 5) == 'image') {

                            
                    //         $fileName 			= $permalink.'-'.time().'-'.$imgKey;
                    //         if($target_image = $fObj->uploadImage($imgValue, $targetLocation, $fileName, $TWH, $LWH, $option)) {

                    //             $scsImgArr[] 				= $imgValue['name'];

                    //             $params                     = array();
                    //             $params['serviceId']   		= $actMsg['editid'];
                    //             $params['serviceImage']     = $target_image;
                    //             $params['status']           = 'Y';
                    //             $insertGal                  = $this->model->newServiceGallery($params);

                    //             if(!$setCover)
                    //                 $setCover               = $target_image;
                    //         }
                    //         else
                    //             $errImgArr[] = $imgValue['name'];
                    //     }
                    //     else
                    //         $errImgArr[] = $imgValue['name'];
                    // }

                    // if(isset($this->_request['coverImage'])) {
                    //     $sImage                 = $this->model->serviceGalleryById($this->_request['coverImage']);
                    //     $setCover               = $sImage['serviceImage'];
                    // }

                    // update new image for category
                    $params = array();
                    // $params['serviceImage'] 	= $setCover;
                    $this->model->eventUpdateById($params, $actMsg['editid']);

                    //Image ---------------
                
                
                    //SEO -----------------
                    $ogImage                        = ($setCover != '') ? $ogUrl."/large/".$setCover : '';
                    $pageUrl                        = $permalink.'/'.$selData['menuPermalink'];
                    
                    $titleandMetaUrl                = '/'.$pageUrl.'/';

                    if(!$pageTitleText)
                        $pageTitleText              = $eventTitle;
                    
                    $seoModel                       = new TitlemetaModel;

                    if($this->_request['IdToEdit'] && $dataBeforeUpdate['permalink'] != $permalink)
                        $handler                    = str_replace('/'.$permalink.'/', '/'.$dataBeforeUpdate['permalink'].'/', $titleandMetaUrl);
                    else {
                        $handler                    = $titleandMetaUrl;
                        
                        if($this->_request['IdToEdit'] == '' && $serviceShortDescription)
                            $metaDescription        = strip_tags($serviceShortDescription);
                    }

                    $seoData                        = $seoModel->titleMetaByUrl($handler);
                    
                    $params = array();
                    $params['pageTitleText']        = $pageTitleText;
                    $params['titleandMetaUrl']      = $titleandMetaUrl;
                    $params['metaTag']              = $metaTag;
                    $params['metaDescription']      = $metaDescription;
                    if($metaRobotsIndex == 'default' && $metaRobotsFollow == 'nofollow')
                        $params['metaRobots']       = 'index, '.$metaRobotsFollow;
                    else
                        $params['metaRobots']       = $metaRobotsIndex.', '.$metaRobotsFollow;
                    $params['ogImage']              = $ogImage;
                    $params['others']               = $others;

                    if($seoData) {
                        $seoModel->titleMetaUpdateById($params, $seoData['titleandMetaId']);
                    } else {
                        $params['siteId']           = $this->session->read('SITEID');
                        $params['titleandMetaType'] = 'O';

                        $seoId                      = $seoModel->newTitleMeta($params);
                    }
                    // ------------------
                }
            }
            else
                $actMsg['message']        = 'Event already exists.';   
        }
        else
        $actMsg['message']        = 'Fields marked with (*) are mandatory.';
        
		return $actMsg;
    }

    function createMedia($targetLocation) {
        $indexingSource = MEDIA_FILES_ROOT.DS.'index.php';
        @mkdir($targetLocation, 0755); 
        copy($indexingSource, $targetLocation.DS.'index.php');

        @mkdir($targetLocation.DS.'catalog',    0755); 
        copy($indexingSource, $targetLocation.DS.'catalog'.DS.'index.php');

        @mkdir($targetLocation.DS.'large',      0755); 
        copy($indexingSource, $targetLocation.DS.'large'.DS.'index.php');

        @mkdir($targetLocation.DS.'normal',     0755); 
        copy($indexingSource, $targetLocation.DS.'normal'.DS.'index.php');

        @mkdir($targetLocation.DS.'small',      0755);   
        copy($indexingSource, $targetLocation.DS.'small'.DS.'index.php');

        @mkdir($targetLocation.DS.'thumb',      0755); 
        copy($indexingSource, $targetLocation.DS.'thumb'.DS.'index.php');
    }
}