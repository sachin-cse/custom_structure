<?php 
defined('BASE') OR exit('No direct script access allowed.');
class ContentModel extends Site
{
	function getContentBycontentID($contentID) {
		return $this->selectSingle(TBL_CONTENT, "*", "contentID = ".addslashes($contentID)." AND contentStatus = 'Y'"); 	
	}
    
	function getBannerbyMenuCatId($categoryId) {
		return $this->selectSingle(TBL_MENU_CATEGORY, "*", "categoryId = '".addslashes($categoryId)."' AND status = 'Y'");
	}
    
	function getContentBycontentHeading($contentHeading) {
		$ExtraQryStr = "contentHeading = '".addslashes($contentHeading)."' AND contentStatus = 'Y'";
		return $this->selectSingle(TBL_CONTENT, "*", $ExtraQryStr); 
	}
    
	function getContentBycontentHeadingAndsiteId($contentHeading) {
		$ExtraQryStr = "contentHeading = '".addslashes($contentHeading)."' AND contentStatus = 'Y'";
		return $this->selectSingle(TBL_CONTENT, "*", $ExtraQryStr); 
	}
	
	function countContentbymenucategoryId($menucategoryId) {
        $needle = 'contentID';
        $CLAUSE = "menucategoryId = ".addslashes($menucategoryId)." AND contentStatus = 'Y'";
		return $this->rowCount(TBL_CONTENT, $needle, $CLAUSE); 
	}
    
    function getContentbymenucategoryIdAndcontentID($menucategoryId, $contentID){
        $ExtraQryStr = "menucategoryId = ".addslashes($menucategoryId)." AND contentID = ".addslashes($contentID)." AND contentStatus = 'Y' ORDER BY displayOrder DESC";
		return $this->selectSingle(TBL_CONTENT, "*", $ExtraQryStr);
    }

	function getContentbymenucategoryId($menucategoryId, $start, $limit) {
		$ExtraQryStr = "menucategoryId = ".addslashes($menucategoryId)." AND contentStatus = 'Y' ORDER BY displayOrder";
		return $this->selectMulti(TBL_CONTENT, "*", $ExtraQryStr, $start, $limit);
	}

	function countGallerybymenucategoryId($menucategoryId) {
        $needle = 'id';
        $CLAUSE = "menucategoryId = ".addslashes($menucategoryId)." AND galleryType = 'P' AND status = 'Y'";
		return $this->rowCount(TBL_GALLERY, $needle, $CLAUSE);
	}
	
	function getGallerybymenucategoryId($menucategoryId, $start, $limit) {
		$ExtraQryStr = "menucategoryId = ".addslashes($menucategoryId)." AND galleryType = 'P' AND status = 'Y' ORDER BY displayOrder";
        return $this->selectMulti(TBL_GALLERY, "*", $ExtraQryStr, $start, $limit);
	}	

	function getGallerybyimagepath($imagepath, $start, $limit) {
		$ExtraQryStr = "imagepath = '".addslashes($imagepath)."' AND status = 'Y' ORDER BY displayOrder";
        return $this->selectMulti(TBL_GALLERY, "*", $ExtraQryStr, $start, $limit);
	}

	function getGalleryCategoryBymenucategoryId($menucategoryId) {
		$ExtraQryStr = "menucategoryId = ".addslashes($menucategoryId)." AND status = 'Y'";
		return $this->selectSingle(TBL_GALLERY_CATEGORY, "*", $ExtraQryStr);
	}

	function getGallerybycategoryId($galleryCategoryId, $start, $limit) {
		$ExtraQryStr = "galleryCategoryId = ".addslashes($galleryCategoryId)." AND status = 'Y' ORDER BY displayOrder";
		return $this->selectMulti(TBL_GALLERY, "*", $ExtraQryStr, $start,$limit);
	}

	function getContentbymenucategoryIdAndPermalink($menucategoryId, $permalink) {
		$ExtraQryStr = "menucategoryId = ".addslashes($menucategoryId)." AND contentStatus = 'Y' AND permalink = '".addslashes($permalink)."' ORDER BY displayOrder";
        return $this->selectSingle(TBL_CONTENT, "*", $ExtraQryStr);
	}
	
	function getContentbyPermalink($permalink) {
		$ExtraQryStr = "contentStatus = 'Y' AND permalink = '".addslashes($permalink)."'";
        return $this->selectSingle(TBL_CONTENT, "*", $ExtraQryStr);
	}
	
	function gallery_details($id, $start, $limit) {
		$ExtraQryStr ='status = "Y" AND  contentId = '.$id;
		return $this->selectMulti(TBL_CONTENT_GALLERY, "*", $ExtraQryStr, $start, $limit);
	}
}
?>