<?php
defined('BASE') OR exit('No direct script access allowed.');
class SliderModel extends Site
{
    function newSlider($params) {
        return $this->insertQuery(TBL_SLIDER, $params);
    }
    
    function sliderUpdateById($params, $id) {
        $CLAUSE = "id = ".addslashes($id);
        return $this->updateQuery(TBL_SLIDER, $params, $CLAUSE);
    }
    
    function sliderById($id) {
        $ExtraQryStr = "id = ".addslashes($id);
		return $this->selectSingle(TBL_SLIDER, "*", $ExtraQryStr);
	}
    
    function sliderCount($ExtraQryStr) {
		return $this->rowCount(TBL_SLIDER, 'id', $ExtraQryStr); 
	}
    
    function getSlider($ExtraQryStr, $start, $limit) {
        $ExtraQryStr .= " ORDER BY displayOrder";
		return $this->selectMulti(TBL_SLIDER, "*", $ExtraQryStr, $start, $limit);
	}
    
    function deleteSliderById($id) {
        return $this->executeQuery("DELETE FROM ".TBL_SLIDER." WHERE id = ".addslashes($id));
    }
    
    function settings($name) {
        $ExtraQryStr = "name = '".addslashes($name)."'";
		return $this->selectSingle(TBL_SETTINGS, "value", $ExtraQryStr);
    }
}
?>