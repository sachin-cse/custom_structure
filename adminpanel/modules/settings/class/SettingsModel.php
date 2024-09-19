<?php
class SettingsModel extends Site
{
    function getUserById($id) {
        $ExtraQryStr = "id = ".addslashes($id);
		return $this->selectSingle(TBL_USER, "email, username, fullname, phone, address", $ExtraQryStr);
	}
    
    function getUserByIdPassword($id, $password) {
        $ExtraQryStr = "id = ".addslashes($id)." AND password = '".md5($password)."'";
		return $this->selectSingle(TBL_USER, "email, username, fullname, phone, address", $ExtraQryStr);
	}
    
    function userUpdate($params, $id) {
        $CLAUSE = "id = ".addslashes($id);
        return $this->updateQuery(TBL_USER, $params, $CLAUSE);
    }
    
    function userCount($ExtraQryStr) {
        $needle = 'id';
		return $this->rowCount(TBL_USER, $needle, $ExtraQryStr);
	}
    
    function siteById($siteId) {
		$ExtraQryStr = "siteId = ".addslashes($siteId);
		return $this->selectSingle(TBL_SITE, "siteName, tagline, siteEmail, sitePhone, siteMobile, siteFax, siteAddress, siteOpeningHours, siteCurrency, siteCurrencySymbol, smtpHost, smtpEncryption, smtpPort, smtpUserName, smtpUserPassword, status, cache", $ExtraQryStr);
	}
    
    function siteUpdateById($params, $siteId) {
        $CLAUSE = "siteId = ".addslashes($siteId);
        return $this->updateQuery(TBL_SITE, $params, $CLAUSE);
    }
}
?>