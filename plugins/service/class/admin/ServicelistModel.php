<?php
defined('BASE') OR exit('No direct script access allowed.');
class ServicelistModel extends Site
{
    function getDisplayOrder($orderBy = 'T'){
        $ENTITY         = TBL_SERVICE;
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
        return $this->selectSingle(TBL_SERVICE, "*", $ExtraQryStr);
    }
    
    function newService($params) {
        return $this->insertQuery(TBL_SERVICE, $params);
	}
    
	function serviceByIdMenucategoryId($id) {
        $ENTITY      = TBL_SERVICE." ts JOIN ".TBL_MENU_CATEGORY." tmc ON (ts.menucategoryId = tmc.categoryId)";
		$ExtraQryStr = "ts.id = ".addslashes($id);
		return $this->selectSingle($ENTITY, "ts.*, tmc.categoryName menuName, tmc.permalink menuPermalink", $ExtraQryStr);
	}
    
	function serviceById($id) {
		$ExtraQryStr = "id = ".addslashes($id);
		return $this->selectSingle(TBL_SERVICE, "*", $ExtraQryStr);
	}
    
    function serviceCount($ExtraQryStr) {
        return $this->rowCount(TBL_SERVICE, "id", $ExtraQryStr);
	}
    
    function getServiceByLimit($ExtraQryStr, $start, $limit) {
		$ExtraQryStr .= " ORDER BY displayOrder ";
		return $this->selectMulti(TBL_SERVICE, "*", $ExtraQryStr, $start, $limit); 	
	}
	
    function serviceUpdateById($params, $id){
        $CLAUSE = "id = ".addslashes($id);
        return $this->updateQuery(TBL_SERVICE, $params, $CLAUSE);
    }
    
    function deleteService($id){
        $this->executeQuery("UPDATE ".TBL_SERVICE." SET deleted_at = NOW() WHERE id = ".addslashes($id).""); exit;
    }
	
	/* ----------------------------------------- TBL_SERVICE_GALLERY ----------------------------------------- */
	function newServiceGallery($params) {
		return $this->insertQuery(TBL_SERVICE_GALLERY, $params);
	}
    
	function serviceGalleryById($id) {
		$ExtraQryStr = "id = ".addslashes($id);
		return $this->selectSingle(TBL_SERVICE_GALLERY, "*", $ExtraQryStr);
	}
		
	function getServiceGalleryByServiceId($serviceId, $start, $limit) {
		$ExtraQryStr = "serviceId = ".addslashes($serviceId)." ORDER BY displayOrder";
		return $this->selectMulti(TBL_SERVICE_GALLERY, "*", $ExtraQryStr, $start, $limit); 	
	}
    
    function deleteServiceGallery($id){
        return $this->executeQuery("DELETE FROM ".TBL_SERVICE_GALLERY." WHERE id = ".addslashes($id));
    }
	
	/* ----------------------------------------- FOR_MENU ----------------------------------------- */
    function searchLinkedPages($mid, $parent_dir, $srch, $start, $limit) {
        
        if($mid == 0) {
            
            $ExtraQryStr    = "mc.categoryName LIKE '%".addslashes($srch)."%'";
            $ENTITY         = TBL_MENU_CATEGORY." mc JOIN ".TBL_MODULE." m ON (m.menu_id = mc.moduleId)";
            $ExtraQryStr   .= " AND mc.status = 'Y' AND m.parent_dir = '".addslashes($parent_dir)."' AND m.child_dir = '' ORDER BY mc.displayOrder";
            
            $data = $this->selectMulti($ENTITY, "mc.categoryId id, mc.categoryName page, mc.permalink", $ExtraQryStr, $start, $limit);
            
        } else {
            
            $ExtraQryStr = " status = 'Y' AND serviceName LIKE '".addslashes($srch)."%' ORDER BY serviceName ASC";
            $data        = $this->selectMulti(TBL_SERVICE, "id id, serviceName page, permalink", $ExtraQryStr, $start, $limit);
            
        }

		return $data;
    }
    
    function settings($name) {
        $ExtraQryStr = "name = '".addslashes($name)."'";
		return $this->selectSingle(TBL_SETTINGS, "*", $ExtraQryStr);
    }
}
?>