<?php defined('BASE') or exit('No direct script access allowed.');
class ThemesettingsController extends REST
{
    private    $model;
    protected  $response = array();

    public function __construct($model) {
        parent::__construct();
        $this->model        = new $model;
    }

    function index($act = []) {

        $settings                   = $this->model->settings($this->_request['pageType']);
        $this->response['settings'] = unserialize($settings['value']);

        return $this->response;
    }

    function addEditSettings() {
        $actMsg['type']                 = 0;
        $actMsg['message']              = '';

        $exist                          = $this->model->settings($this->_request['pageType']);
        if ($exist) {
            $settings                   = unserialize($exist['value']);
        }

        $isSlider                       = isset($this->_request['isSlider']) ? 1 : 0;
        $sliderNo                       = trim($this->_request['sliderNo']);
        $sliderWidth                    = trim($this->_request['sliderWidth']);
        $sliderHeight                   = trim($this->_request['sliderHeight']);

        $isBanner                       = isset($this->_request['isBanner']) ? 1 : 0;
        $bannerWidth                    = trim($this->_request['bannerWidth']);
        $bannerHeight                   = trim($this->_request['bannerHeight']);

        $isBannerCaption                = isset($this->_request['isBannerCaption']) ? 1 : 0;
        $bannerCaption                  = trim($this->_request['bannerCaption']);

        $error                          = 0;

        if ($isSlider == '1') {
            if (!$sliderNo || !$sliderWidth || !$sliderHeight) {
                $error                  = 1;
                $actMsg['message']      = 'Please provide width, height and number of items for Home Banner.';
            }
        }

        if ($isBanner == '1') {
            if (!$bannerWidth || !$bannerHeight) {
                $error                  = 1;
                $actMsg['message']      = 'Please provide width and height for Inner Banner.';
            }
        }

        if (!$error) {
            $paramsTheme                    = array();

            $paramsTheme['isSlider']        = $isSlider;
            $paramsTheme['sliderNo']        = $sliderNo;
            $paramsTheme['sliderWidth']     = $sliderWidth;
            $paramsTheme['sliderHeight']    = $sliderHeight;

            $paramsTheme['isBanner']        = $isBanner;
            $paramsTheme['bannerWidth']     = $bannerWidth;
            $paramsTheme['bannerHeight']    = $bannerHeight;
            $paramsTheme['isBannerCaption'] = $isBannerCaption;
            $paramsTheme['bannerCaption']   = $bannerCaption;

            if ($_FILES['innerBanner']['name'] && substr($_FILES['innerBanner']['type'], 0, 5) == 'image') {

                $fObj           = new FileUpload;

                $targetLocation = MEDIA_FILES_ROOT . DS . "banner";
                
                if (!file_exists($targetLocation) && !is_dir($targetLocation)) 
                    $this->createMedia($targetLocation);

                $TWH[0]         = $bannerWidth;     // thumb width
                $TWH[1]         = $bannerHeight;    // thumb height
                $LWH[0]         = $bannerWidth;     // large width
                $LWH[1]         = $bannerHeight;    // large height
                $option         = 'thumbnail';      // upload, thumbnail, resize, all

                $fileName        = "banner";
                if ($target_image = $fObj->uploadImage($_FILES['innerBanner'], $targetLocation, $fileName, $TWH, $LWH, $option)) {

                    // delete existing image
                    if ($settings['innerBanner'] != $target_image) {
                        @unlink($targetLocation . DS . 'normal' . DS . $settings['innerBanner']);
                        @unlink($targetLocation . DS . 'thumb' . DS . $settings['innerBanner']);
                        @unlink($targetLocation . DS . 'large' . DS . $settings['innerBanner']);
                    }

                    // update new image
                    $paramsTheme['innerBanner'] = $target_image;
                }
            } else
                $paramsTheme['innerBanner'] = $settings['innerBanner'];

            $params                         = [];
            $params['value']                = serialize($paramsTheme);

            if (!$exist) {

                $params['name']         = $this->_request['pageType'];
                $this->model->newSettings($params);
                $actMsg['message']      = 'Data inserted successfully.';
            } else {

                $this->model->updateSetting($this->_request['pageType'], $params);
                $actMsg['message']      = 'Data updated successfully.';
            }

            $actMsg['type']             = 1;
        }

        return $actMsg;
    }

    function deleteFile() {
        $actMsg['type']           = 0;
        $actMsg['message']        = '';

        if ($this->_request['DeleteFile'] == 'innerBanner') {

            $settings   = $this->model->settings($this->_request['pageType']);
            $settings   = unserialize($settings['value']);

            if ($settings['innerBanner']) {
                @unlink(MEDIA_FILES_ROOT . DS . 'banner' . DS . 'normal' . DS . $settings['innerBanner']);
                @unlink(MEDIA_FILES_ROOT . DS . 'banner' . DS . 'thumb' . DS . $settings['innerBanner']);
                @unlink(MEDIA_FILES_ROOT . DS . 'banner' . DS . 'large' . DS . $settings['innerBanner']);

                $settings['innerBanner']    = '';

                $params                     = array();
                $params['value']            = serialize($settings);
                $this->model->updateSetting($this->_request['pageType'], $params);
            }

            $actMsg['type']           = 1;
            $actMsg['message']        = 'Banner deleted successfully.';
        }

        return $actMsg;
    }

    function createMedia($targetLocation) {
        $indexingSource = MEDIA_FILES_ROOT.DS.'index.php';
        @mkdir($targetLocation, 0755); 
        copy($indexingSource, $targetLocation.DS.'index.php');

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