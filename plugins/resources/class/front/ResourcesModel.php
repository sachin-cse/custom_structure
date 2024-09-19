<?php defined('BASE') OR exit('No direct script access allowed.');
class ResourcesModel extends ContentModel
{
    //Assuming  TBL_RESOURCES is the DB table name. 
    //Change the value of $ENTITY as per your DB.

    function recordCount($ExtraQryStr)
    {
        $ENTITY        = TBL_RESOURCES;
        $ExtraQryStr  .= " AND status='Y' ";
        return $this -> rowCount($ENTITY, 'id', $ExtraQryStr);
    }
    function getRecords($ExtraQryStr, $start, $limit)
    {
        $ExtraQryStr  .= " AND status='Y' ORDER BY displayOrder";
        $ENTITY        = TBL_RESOURCES;

        return $this -> selectMulti($ENTITY, "*", $ExtraQryStr, $start, $limit);
    }

    function getRecord($permalink)
    {
        $ExtraQryStr     = " permalink = '" . addslashes($permalink) . "' AND status='Y'";
        $ENTITY        = TBL_RESOURCES;
        return $this -> selectSingle($ENTITY, "*", $ExtraQryStr);
    }
}