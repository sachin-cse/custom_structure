<?php defined('BASE') OR exit('No direct script access allowed.');
class FaqcategoryModel extends Site
{
    function getDisplayOrder($orderBy = 'T'){
        $ENTITY         = TBL_FAQ_CATEGORY;
        $ExtraQryStr    = 1;
        
        if($orderBy == 'T')
            $str = 'MIN(displayOrder) displayOrder';
        elseif($orderBy == 'B')
            $str = 'MAX(displayOrder) displayOrder';
        else
            return;
        
		return $this->selectSingle($ENTITY, $str, $ExtraQryStr, $start, $limit); 
    }

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

    function newFaqCat($params) {
        return $this->insertQuery(TBL_FAQ_CATEGORY, $params);
	}

    function checkExistence($ExtraQryStr) {
        return $this->selectSingle(TBL_FAQ_CATEGORY, "*", $ExtraQryStr);
    }

    function faqCatUpdateById($params, $id){
        $CLAUSE = "faq_cat_id = ".addslashes($id);
        return $this->updateQuery(TBL_FAQ_CATEGORY, $params, $CLAUSE);
    }

    function settings($name) {
        $ExtraQryStr = "name = '".addslashes($name)."'";
		return $this->selectSingle(TBL_SETTINGS, "*", $ExtraQryStr);
    }

    // faq category by id
    function faqCatbyId($id) {
		$ExtraQryStr = "faq_cat_id = ".addslashes($id);
		return $this->selectSingle(TBL_FAQ_CATEGORY, "*", $ExtraQryStr);
	}

    function getFaqCatByLimit($ExtraQryStr, $start, $limit) {
		$ExtraQryStr .= " ORDER BY displayOrder";
		return $this->selectMulti(TBL_FAQ_CATEGORY, "*", $ExtraQryStr, $start, $limit); 	
	}

    function faqCatCount($ExtraQryStr) {
        return $this->rowCount(TBL_FAQ_CATEGORY, "faq_cat_id", $ExtraQryStr);
	}

    function deleteFaqCat($id){
        return $this->executeQuery("DELETE FROM ".TBL_FAQ_CATEGORY." WHERE faq_cat_id = ".addslashes($id));
    }

}