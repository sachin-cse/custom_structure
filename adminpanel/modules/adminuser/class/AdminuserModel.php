<?php 
class AdminuserModel extends Site
{
    function newUser($params) {
        return $this->insertQuery(TBL_USER, $params);
    }
    
    function userCount($ExtraQryStr) {
        $needle = 'id';
		return $this->rowCount(TBL_USER, $needle, $ExtraQryStr);
	}
	
	function userList($start, $limit) {
		$ExtraQryStr = "usertype != 'A'";
		return $this->selectMulti(TBL_USER, "*", $ExtraQryStr, $start, $limit);
	}
	
	function userById($id) {
		$ExtraQryStr = "id = ".addslashes($id);
		return $this->selectSingle(TBL_USER, "*", $ExtraQryStr);
	}

    function userUpdate($params, $id) {
        $CLAUSE = "id = ".addslashes($id);
        return $this->updateQuery(TBL_USER, $params, $CLAUSE);
    }
}
?>