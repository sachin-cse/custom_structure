<?php defined('BASE') OR exit('No direct script access allowed.');
class FaqlistModel extends Site
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

    function getDisplayOrder($orderBy = 'T'){
        $ENTITY         = TBL_FAQ;
        $ExtraQryStr    = 1;
        
        if($orderBy == 'T')
            $str = 'MIN(displayOrder) displayOrder';
        elseif($orderBy == 'B')
            $str = 'MAX(displayOrder) displayOrder';
        else
            return;
        
		return $this->selectSingle($ENTITY, $str, $ExtraQryStr, $start, $limit); 
    }

    function newFaq($params) {
        return $this->insertQuery(TBL_FAQ, $params);
	}

    function checkExistence($ExtraQryStr) {
        return $this->selectSingle(TBL_FAQ, "*", $ExtraQryStr);
    }

    function faqUpdateById($params, $id){
        $CLAUSE = "faq_id = ".addslashes($id);
        return $this->updateQuery(TBL_FAQ, $params, $CLAUSE);
    }

    function faqById($id) {
		$ExtraQryStr = "faq_id = ".addslashes($id);
		return $this->selectSingle(TBL_FAQ, "*", $ExtraQryStr);
	}

    function getFaqByLimit($ExtraQryStr, $start, $limit) {
		$ExtraQryStr .= " ORDER BY displayOrder";
		return $this->selectMulti(TBL_FAQ, "*", $ExtraQryStr, $start, $limit); 	
	}

    function faqCount($ExtraQryStr) {
        return $this->rowCount(TBL_FAQ, "faq_id", $ExtraQryStr);
	}

    function deleteFaq($id){
        return $this->executeQuery("DELETE FROM ".TBL_FAQ." WHERE faq_id = ".addslashes($id));
    }

    function getFaqcat(){
		return $this->selectAll(TBL_FAQ_CATEGORY, "faq_cat_id, faq_cat_title", 1); 	
    }
}