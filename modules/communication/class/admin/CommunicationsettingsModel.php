<?php
defined('BASE') OR exit('No direct script access allowed.');
class CommunicationsettingsModel extends Site
{    
    function emailTemplateById($id) {
        $ExtraQryStr = "id = ".addslashes($id);
		return $this->selectSingle(TBL_EMAIL_BODY, "*", $ExtraQryStr);
    }
}
?>