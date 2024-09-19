<?php defined('BASE') OR exit('No direct script access allowed.');
class BrandlistModel extends Site
{
    function searchLinkedPages($mid, $parent_dir, $srch, $start, $limit)
    {
        if ($mid == 0) {

            $ENTITY      = TBL_MENU_CATEGORY . " mc JOIN " . TBL_MODULE . " m ON (m.menu_id = mc.moduleId)";

            $ExtraQryStr = "mc.categoryName like '%" . addslashes($srch) . "%' AND mc.status = 'Y' AND m.parent_dir = '" . addslashes($parent_dir) . "' AND m.child_dir = '' ORDER BY mc.displayOrder";

            $data = $this -> selectMulti($ENTITY, "mc.categoryId id, mc.categoryName page, mc.permalink", $ExtraQryStr, $start, $limit);

        } else {

            $ENTITY      = TBL_CONTENT . " c JOIN " . TBL_MENU_CATEGORY . " mc ON (c.menucategoryId = mc.categoryId) JOIN " . TBL_MODULE . " m ON (m.menu_id = mc.moduleId)";
            $ExtraQryStr = "c.contentHeading like '%" . addslashes($srch) . "%' AND mc.status = 'Y' AND m.parent_dir = '" . addslashes($parent_dir) . "' AND m.child_dir = '' ORDER BY mc.displayOrder";


            $data = $this -> selectMulti($ENTITY, "c.contentID id, c.contentHeading page, c.permalink", $ExtraQryStr, $start, $limit);
        }

        return $data;
    }

    function getLinkedPages($parent_dir, $start, $limit){
        $ENTITY         = TBL_MENU_CATEGORY." mc JOIN ".TBL_MODULE." m ON (m.menu_id = mc.moduleId)";
        $ExtraQryStr    = "mc.status = 'Y' AND m.parent_dir = '".addslashes($parent_dir)."' AND m.child_dir = '' ORDER BY mc.displayOrder";
        
		return $this->selectMulti($ENTITY, "mc.categoryId, mc.categoryName, mc.permalink", $ExtraQryStr, $start, $limit); 
    }

    function galleryCount($ExtraQryStr) {
        return $this->rowCount(TBL_BRAND, "id", $ExtraQryStr);
	}

    function checkExistence($ExtraQryStr) {
        return $this->selectSingle(TBL_BRAND, "*", $ExtraQryStr);
    }

    function getDisplayOrder($orderBy = 'T'){
        $ENTITY         = TBL_BRAND;
        $ExtraQryStr    = 1;
        
        if($orderBy == 'T')
            $str = 'MIN(displayOrder) displayOrder';
        elseif($orderBy == 'B')
            $str = 'MAX(displayOrder) displayOrder';
        else
            return;
        
		return $this->selectSingle($ENTITY, $str, $ExtraQryStr, $start, $limit); 
    }

    function newGallery($params) {
        return $this->insertQuery(TBL_BRAND, $params);
	}

    function galleryById($id) {
		$ExtraQryStr = "id = ".addslashes($id);
		return $this->selectSingle(TBL_BRAND, "*", $ExtraQryStr);
	}

    function galleryUpdateById($params, $id){
        $CLAUSE = "id = ".addslashes($id);
        return $this->updateQuery(TBL_BRAND, $params, $CLAUSE);
    }

    function galleryGalleryUpdateById($params, $id){
        $CLAUSE = "id = ".addslashes($id);
        return $this->updateQuery(TBL_BRAND, $params, $CLAUSE);
    }

    function galleryByIdMenucategoryId($id) {
        $ENTITY      = TBL_BRAND." ts JOIN ".TBL_MENU_CATEGORY." tmc ON (ts.menucategoryId = tmc.categoryId)";
		$ExtraQryStr = "ts.id = ".addslashes($id);
		return $this->selectSingle($ENTITY, "ts.*, tmc.categoryName menuName, tmc.permalink menuPermalink", $ExtraQryStr);
	}

    function getGalleryByLimit($ExtraQryStr, $start, $limit) {
		$ExtraQryStr .= " ORDER BY displayOrder";
		return $this->selectMulti(TBL_BRAND, "*", $ExtraQryStr, $start, $limit); 	
	}

    function settings($name) {
        $ExtraQryStr = "name = '".addslashes($name)."'";
		return $this->selectSingle(TBL_SETTINGS, "*", $ExtraQryStr);
    }

    function deletebrandbyid($id){
        return $this->executeQuery("DELETE FROM ".TBL_BRAND." WHERE id = ".addslashes($id));
    }
    
}