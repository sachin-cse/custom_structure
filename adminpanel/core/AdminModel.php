<?php 
class AdminModel extends Site
{
    function lookup($EnteredUname, $EnteredPassword) {
        $ExtraQryStr = "(username = '".addslashes($EnteredUname)."' OR email = '".addslashes($EnteredUname)."') AND password = '".md5($EnteredPassword)."' AND status = 'Y'" ;
        return $this->selectSingle(TBL_USER, "id, fullname, usertype, siteId, permission, lastLogin, ipAddress", $ExtraQryStr);
    }

    function getUserByid($id) {
        $ExtraQryStr    = "id = ".addslashes($id);
        $params         = array();
        $params[]       = "*";
        return $this->selectSingle(TBL_USER, $params, $ExtraQryStr);
    }
    
    function getUserByemail($email) {
        $ExtraQryStr    = "(email = '".addslashes($email)."' OR username = '".addslashes($email)."') AND usertype != 'A'";
        
        $params         = array();
        $params[]       = "id, email, status";
        return $this->selectSingle(TBL_USER, $params, $ExtraQryStr);
    }

    function updateUserByid($params, $id) {
        $CLAUSE             = "id = ".addslashes($id);
        return $this->updateQuery(TBL_USER, $params, $CLAUSE);
    }
    
    function getEmailBodyById($id) {
    	$ExtraQryStr = " id = ".addslashes($id);
    	return $this->selectSingle(TBL_EMAIL_BODY, "*", $ExtraQryStr);
    }
    
    function checkPassKey($passwordKey) {
		$ExtraQryStr = " passwordKey = '".$passwordKey."' AND status = 'Y'";
        
		$userid = $this->selectSingle(TBL_USER, "id", $ExtraQryStr);
		
		if($userid['id'])
			return 1;
		else
			return 0;
		
		return ($result);
	}
    
    function userUpdateByPassKey($params, $passwordKey) {
		$CLAUSE = "passwordKey = '".$passwordKey."'";
		return $this->updateQuery(TBL_USER, $params, $CLAUSE);
	}
    
    function mainActivePageCount() {
        $needle         = 'categoryId';
        $ExtraQryStr    = "parentId = 0 AND status = 'Y' AND hiddenMenu = 'N'";
		return $this->rowCount(TBL_MENU_CATEGORY, $needle, $ExtraQryStr);
	}
    
    function activeContentCount() {
        $needle         = 'contentID';
        $ExtraQryStr    = "contentStatus = 'Y'";
		return $this->rowCount(TBL_CONTENT, $needle, $ExtraQryStr);
	}
}
?>