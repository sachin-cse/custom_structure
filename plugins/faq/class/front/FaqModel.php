<?php defined('BASE') OR exit('No direct script access allowed.');
class FaqModel extends ContentModel
{
    //Assuming  TBL_FAQ is the DB table name. 
    //Change the value of $ENTITY as per your DB.

    function recordCount($ExtraQryStr)
    {
        $ENTITY        = TBL_FAQ;
        $ExtraQryStr  .= " AND status='Y' ";
        return $this -> rowCount($ENTITY, 'id', $ExtraQryStr);
    }
    function getRecords($ExtraQryStr, $start, $limit)
    {
        $ExtraQryStr  .= " AND status='Y' ORDER BY displayOrder";
        $ENTITY        = TBL_FAQ;

        return $this -> selectMulti($ENTITY, "*", $ExtraQryStr, $start, $limit);
    }

    function getRecord($permalink)
    {
        $ExtraQryStr     = " permalink = '" . addslashes($permalink) . "' AND status='Y'";
        $ENTITY        = TBL_FAQ;
        return $this -> selectSingle($ENTITY, "*", $ExtraQryStr);
    }

    function getFaq($ExtraQryStr, $start, $limit) {
        $ENTITY = TBL_FAQ_CATEGORY . " fc JOIN " . TBL_FAQ . " f ON (f.faq_cat_id = fc.faq_cat_id)";
		$ExtraQryStr .= " AND fc.faq_status = 'Y' ORDER BY fc.displayOrder";
		return $this->selectMulti($ENTITY, "fc.*, f.*", $ExtraQryStr, $start, $limit); 	
	}

    function faqCount($ExtraQryStr) {
		$ExtraQryStr .= " AND faq_status = 'Y'";
        return $this->rowCount(TBL_FAQ, "faq_id", $ExtraQryStr);
	}
}