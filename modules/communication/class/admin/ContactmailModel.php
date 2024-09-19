<?php
defined('BASE') OR exit('No direct script access allowed.');
class ContactmailModel extends Site
{
    function contactMailById($contactId) {
		$ExtraQryStr = "contactId = ".addslashes($contactId);
		return $this->selectSingle(TBL_CONTACT, "*", $ExtraQryStr);
	}
    
    function getContactMail($ExtraQryStr, $start, $limit) {
		$ExtraQryStr .= " ORDER BY entryDate DESC";
		return $this->selectMulti(TBL_CONTACT, "*", $ExtraQryStr, $start, $limit);
	}
	
	function countContactMail($ExtraQryStr) {
        return $this->rowCount(TBL_CONTACT, 'contactId', $ExtraQryStr);
	}
    
    function contactMailUpdateById($params, $contactId) {
        $CLAUSE = "contactId = ".addslashes($contactId);
        return $this->updateQuery(TBL_CONTACT, $params, $CLAUSE);
    }
    
    function deleteContactMailById($contactId) {
        return $this->executeQuery("DELETE FROM ".TBL_CONTACT." WHERE contactId = ".addslashes($contactId));
    }
    
    function contactMailByIdAndSeen($contactId){
        $params         = array();
        $params['seen'] = date('Y-m-d H:i:s');
        
        $ExtraQryStr = "contactId = ".addslashes($contactId);
        
        $this->updateQuery(TBL_CONTACT, $params, $ExtraQryStr);
        
		return $this->selectSingle(TBL_CONTACT, "*", $ExtraQryStr);
    }
}
?>