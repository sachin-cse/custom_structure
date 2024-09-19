<?php
defined('BASE') OR exit('No direct script access allowed.');
class SeoModel extends Site
{
	function getSiteBysiteURL($siteURL) {
        return $this->selectSingle(TBL_SITE, "*", "siteUrl='".addslashes($siteURL)."'");
	}
    
    function settings() {
        $ExtraQryStr = "name = 'SEO'";
		return $this->selectSingle(TBL_SETTINGS, "value", $ExtraQryStr);
    }
    
    function pwa() {
        $ExtraQryStr = "name = 'PWA'";
		return $this->selectSingle(TBL_SETTINGS, "value", $ExtraQryStr);
    }
    
	function getDefaultTitleMeta() {
        return $this->selectSingle(TBL_TITLE_AND_META, "*", "titleandMetaUrl ='/' AND titleandMetaType ='D' AND status = 'Y'");
	}
    
	function getTitleMetaByURL($url) {
		return $this->selectSingle(TBL_TITLE_AND_META, "*", "titleandMetaUrl='".addslashes($url)."' AND status = 'Y'");
	}
    
    function redirectTo404($SITE_LOC_PATH) {
		header("HTTP/1.0 404 Not Found");
		echo '<center class="st_404" style="margin:20px;"><h1>404</h1><h2>Page Not Found</h2>';
		echo 'The URL that you have requested could not be found.</center>';
        //header('location:'.$SITE_LOC_PATH.'/404/');
        //exit();
    }

    function getSeoDataById($id) {
        return $this->selectSingle(TBL_TITLE_AND_META, "*", "titleandMetaId='".addslashes($id)."' AND status = 'Y'");
    }
    
    function redirectToURL($URL) {
        header('location:'.$URL);
        exit();
    }
}
?>