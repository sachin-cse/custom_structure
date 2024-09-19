<?php defined('BASE') OR exit('No direct script access allowed.');
class EventlistModel extends Site
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

    function settings($name) {
        $ExtraQryStr = "name = '".addslashes($name)."'";
		return $this->selectSingle(TBL_SETTINGS, "*", $ExtraQryStr);
    }

    function eventByIdMenucategoryId($id) {
        $ENTITY      = TBL_EVENT." ts JOIN ".TBL_MENU_CATEGORY." tmc ON (ts.menucategoryId = tmc.categoryId)";
		$ExtraQryStr = "ts.id = ".addslashes($id);
		return $this->selectSingle($ENTITY, "ts.*, tmc.categoryName menuName, tmc.permalink menuPermalink", $ExtraQryStr);
	}

    function getEventGalleryByEventId($id, $start, $limit) {
		$ExtraQryStr = "id = ".addslashes($id)." ORDER BY displayOrder";
		return $this->selectMulti(TBL_EVENT_GALLERY, "*", $ExtraQryStr, $start, $limit); 	
	}

    function eventCount($ExtraQryStr) {
        return $this->rowCount(TBL_EVENT, "id", $ExtraQryStr);
	}

    function getEventByLimit($ExtraQryStr, $start, $limit) {
		$ExtraQryStr .= " ORDER BY displayOrder";
		return $this->selectMulti(TBL_EVENT, "*", $ExtraQryStr, $start, $limit); 	
	}

    function checkExistence($ExtraQryStr) {
        return $this->selectSingle(TBL_EVENT, "*", $ExtraQryStr);
    }

    function eventById($id) {
		$ExtraQryStr = "id = ".addslashes($id);
		return $this->selectSingle(TBL_EVENT, "*", $ExtraQryStr);
	}

    function eventUpdateById($params, $id){
        $CLAUSE = "id = ".addslashes($id);
        return $this->updateQuery(TBL_EVENT, $params, $CLAUSE);
    }

    function newEvent($params) {
        return $this->insertQuery(TBL_EVENT, $params);
	}
}