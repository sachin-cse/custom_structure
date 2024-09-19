<?php
defined('BASE') OR exit('No direct script access allowed.');
class TestimoniallistModel extends Site
{
    function getDisplayOrder($orderBy = 'T'){
        $ENTITY         = TBL_TESTIMONIAL;
        $ExtraQryStr    = 1;
        
        if($orderBy == 'T')
            $str = 'MIN(displayOrder) displayOrder';
        elseif($orderBy == 'B')
            $str = 'MAX(displayOrder) displayOrder';
        else
            return;
        
		return $this->selectSingle($ENTITY, $str, $ExtraQryStr, $start, $limit); 
    }

    function getLinkedPages($parent_dir, $start, $limit){
        $ENTITY         = TBL_MENU_CATEGORY." mc JOIN ".TBL_MODULE." m ON (m.menu_id = mc.moduleId)";
        $ExtraQryStr    = "mc.status = 'Y' AND m.parent_dir = '".addslashes($parent_dir)."' AND m.child_dir = '' ORDER BY mc.displayOrder";
        
		return $this->selectMulti($ENTITY, "mc.categoryId, mc.categoryName, mc.permalink", $ExtraQryStr, $start, $limit); 
    }
    
    function checkExistence($ExtraQryStr) {
        return $this->selectSingle(TBL_TESTIMONIAL, "*", $ExtraQryStr);
    }
    
    function newReview($params) {
        return $this->insertQuery(TBL_TESTIMONIAL, $params);
	}
    
	function reviewByIdMenucategoryId($id) {
        $ENTITY      = TBL_TESTIMONIAL." ts JOIN ".TBL_MENU_CATEGORY." tmc ON (ts.menucategoryId = tmc.categoryId)";
		$ExtraQryStr = "ts.testimonialId = ".addslashes($id);
		return $this->selectSingle($ENTITY, "ts.*, tmc.categoryName menuName, tmc.permalink menuPermalink", $ExtraQryStr);
	}
    
	function reviewById($id) {
		$ExtraQryStr = "testimonialId = ".addslashes($id);
		return $this->selectSingle(TBL_TESTIMONIAL, "*", $ExtraQryStr);
	}
    
    function reviewCount($ExtraQryStr) {
        return $this->rowCount(TBL_TESTIMONIAL, "testimonialId", $ExtraQryStr);
	}
    
    function getReviewByLimit($ExtraQryStr, $start, $limit) {
		$ExtraQryStr .= " ORDER BY displayOrder";
		return $this->selectMulti(TBL_TESTIMONIAL, "*", $ExtraQryStr, $start, $limit); 	
	}
	
    function reviewUpdateById($params, $id){
        $CLAUSE = "testimonialId = ".addslashes($id);
        return $this->updateQuery(TBL_TESTIMONIAL, $params, $CLAUSE);
    }
    
    function deleteReview($id){
        return $this->executeQuery("DELETE FROM ".TBL_TESTIMONIAL." WHERE testimonialId = ".addslashes($id));
    }
    
    function executeQry($sql){
        return $this->executeQuery($sql);
    }
    
    function searchLinkedPages($mid, $parent_dir, $srch, $start, $limit) {
        
        if($mid == 0) {
            
            $ExtraQryStr    = "mc.categoryName like '%".addslashes($srch)."%'";
            $ENTITY         = TBL_MENU_CATEGORY." mc JOIN ".TBL_MODULE." m ON (m.menu_id = mc.moduleId)";
            $ExtraQryStr   .= " AND mc.status = 'Y' AND m.parent_dir = '".addslashes($parent_dir)."' AND m.child_dir = '' ORDER BY mc.displayOrder";
            
            $data = $this->selectMulti($ENTITY, "mc.id id, mc.categoryName page, mc.permalink", $ExtraQryStr, $start, $limit);
            
        } else {  
            
            $ExtraQryStr = " status = 'Y' AND authorName like '".addslashes($srch)."%' ORDER BY authorName ASC";
            $data        = $this->selectMulti(TBL_TESTIMONIAL, "testimonialId id, authorName page, permalink", $ExtraQryStr, $start, $limit);
            
        }

		return $data;
    }
    
    function settings($name) {
        $ExtraQryStr = "name = '".addslashes($name)."'";
		return $this->selectSingle(TBL_SETTINGS, "*", $ExtraQryStr);
    }
}
?>