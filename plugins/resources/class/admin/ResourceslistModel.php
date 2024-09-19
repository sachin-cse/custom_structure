<?php defined('BASE') OR exit('No direct script access allowed.');
class ResourceslistModel extends Site
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

    function getDisplayOrder($orderBy = 'T'){
        $ENTITY         = TBL_RESOURCE;
        $ExtraQryStr    = 1;
        
        if($orderBy == 'T')
            $str = 'MIN(displayOrder) displayOrder';
        elseif($orderBy == 'B')
            $str = 'MAX(displayOrder) displayOrder';
        else
            return;
        
		return $this->selectSingle($ENTITY, $str, $ExtraQryStr, $start, $limit); 
    }

    function resourceByIdMenucategoryId($id) {
        $ENTITY      = TBL_RESOURCE." ts JOIN ".TBL_MENU_CATEGORY." tmc ON (ts.menucategoryId = tmc.categoryId)";
		$ExtraQryStr = "ts.id = ".addslashes($id);
		return $this->selectSingle($ENTITY, "ts.*, tmc.categoryName menuName, tmc.permalink menuPermalink", $ExtraQryStr);
	}

    function resourceById($id) {
		$ExtraQryStr = "id = ".addslashes($id);
		return $this->selectSingle(TBL_RESOURCE, "*", $ExtraQryStr);
	}

    function resourceCount($ExtraQryStr) {
        return $this->rowCount(TBL_RESOURCE, "id", $ExtraQryStr);
	}

    function checkExistence($ExtraQryStr) {
        return $this->selectSingle(TBL_RESOURCE, "*", $ExtraQryStr);
    }

    function getResourceGalleryByResourceId($id, $start, $limit) {
		$ExtraQryStr = "id = ".addslashes($id)." ORDER BY displayOrder";
		return $this->selectMulti(TBL_RESOURCE_GALLERY, "*", $ExtraQryStr, $start, $limit); 	
	}

    function newResource($params) {
        return $this->insertQuery(TBL_RESOURCE, $params);
	}

    function resourceUpdateById($params, $id){
        $CLAUSE = "id = ".addslashes($id);
        return $this->updateQuery(TBL_RESOURCE, $params, $CLAUSE);
    }

    function settings($name) {
        $ExtraQryStr = "name = '".addslashes($name)."'";
		return $this->selectSingle(TBL_SETTINGS, "*", $ExtraQryStr);
    }

    function getResourceByLimit($ExtraQryStr, $start, $limit) {
		$ExtraQryStr .= " ORDER BY displayOrder";
		return $this->selectMulti(TBL_RESOURCE, "*", $ExtraQryStr, $start, $limit); 	
	}

    function deleteResource($id){
        return $this->executeQuery("DELETE FROM ".TBL_RESOURCE." WHERE id = ".addslashes($id));
    }
}