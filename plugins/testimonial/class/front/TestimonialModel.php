<?php
defined('BASE') OR exit('No direct script access allowed.');
class TestimonialModel extends ContentModel
{
    function reviewById($id) {
		$ENTITY      = TBL_TESTIMONIAL." tt LEFT JOIN ".TBL_MENU_CATEGORY." tmc ON (tt.menucategoryId = tmc.categoryId)";
		$ExtraQryStr = "tt.testimonialId = ".addslashes($id);
		return $this->selectSingle($ENTITY, "tt.*", $ExtraQryStr);
	}
	
    function reviewByPermalink($permalink) {
		$ENTITY      = TBL_TESTIMONIAL." tt LEFT JOIN ".TBL_MENU_CATEGORY." tmc ON (tt.menucategoryId = tmc.categoryId)";
		$ExtraQryStr = "tt.permalink = '".addslashes($permalink)."'";
		return $this->selectSingle($ENTITY, "tt.*", $ExtraQryStr);
	}
	
    function reviewForHome($ExtraQryStr) {
		$ENTITY       = TBL_TESTIMONIAL." tt LEFT JOIN ".TBL_MENU_CATEGORY." tmc ON (tt.menucategoryId = tmc.categoryId)";
		$ExtraQryStr .= " AND tt.status = 'Y'";
		return $this->selectSingle($ENTITY, "tt.*", $ExtraQryStr);
	}
    
    function reviewCount($ExtraQryStr) {
		$ENTITY       = TBL_TESTIMONIAL." tt LEFT JOIN ".TBL_MENU_CATEGORY." tmc ON (tt.menucategoryId = tmc.categoryId)";
		$ExtraQryStr .= " AND tt.status = 'Y'";
        return $this->rowCount($ENTITY, "tt.testimonialId", $ExtraQryStr);
	}
    
    function getReviewByLimit($ExtraQryStr, $start, $limit) {
		$ENTITY       = TBL_TESTIMONIAL." tt LEFT JOIN ".TBL_MENU_CATEGORY." tmc ON (tt.menucategoryId = tmc.categoryId)";
		$ExtraQryStr .= " AND tt.status = 'Y' ORDER BY tt.displayOrder";
		return $this->selectMulti($ENTITY, "tt.*, tmc.categoryName menuName, tmc.permalink menuPermalink", $ExtraQryStr, $start, $limit); 	
	}
    
    function getPrevRecord($ExtraQryStr) {
        $ENTITY 	  = TBL_TESTIMONIAL." tt LEFT JOIN ".TBL_MENU_CATEGORY." tmc ON (tt.menucategoryId = tmc.categoryId)";
        $fields 	  = "tt.*, tmc.categoryName menuName, tmc.permalink menuPermalink";
        $ExtraQryStr .= " AND tt.status = 'Y' ORDER BY tt.displayOrder DESC";
        return $this->selectSingle($ENTITY, $fields, $ExtraQryStr);
	}
    
    function getNextRecord($ExtraQryStr) {
        $ENTITY 	  = TBL_TESTIMONIAL." tt LEFT JOIN ".TBL_MENU_CATEGORY." tmc ON (tt.menucategoryId = tmc.categoryId)";
        $fields 	  = "tt.*, tmc.categoryName menuName, tmc.permalink menuPermalink";
        $ExtraQryStr .= " AND tt.status = 'Y' ORDER BY tt.displayOrder";
        return $this->selectSingle($ENTITY, $fields, $ExtraQryStr);
	}
	
	/* ----------------------------------------- TBL_SETTINGS ------------------------------------------------ */
	function settings($name) {
        $ExtraQryStr = "name = '".addslashes($name)."'";
		$settings = $this->selectSingle(TBL_SETTINGS, "value", $ExtraQryStr);
        
        return $settings;
    }
}
?>