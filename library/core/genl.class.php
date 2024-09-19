<?php
class genl {
    
    function validate_email($field) {
    	$address = trim($field);
        if (filter_var($address, FILTER_VALIDATE_EMAIL)) {
            
            if(domain_exists($address))
                return 1;
            else
                return 0;
        }
        else
            return 0;
    }	
    
    function validate_url($field) {
        if (preg_match('|^[a-z0-9-]+(\.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $field)) {
            return 1;
        } else {
            return 0;
        }
    }
    
    function validate_alnum_char($field) { //OK
        $strlen = strlen($field);
        if($strlen > 0) {
            if(preg_match("[0-9]", $field)) {
                return 1;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }		
    function validate_alpha_char($field) { //not OK
        $strlen = strlen($field);
        if($strlen > 0) {
            if(ereg("[^0-9]$", $field)) {
                return 1;
            } else {
                return 0;
            }
        }
    }		
    function validate_num_char($field) { // OK
        $strlen = strlen($field);
        if($strlen > 0) {
            if(ereg("[a-zA-Z]", $field)) {
                return 1;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }		
    function validate_special_char($field) { 
        $strlen = strlen($field);

        if($strlen > 0) {
            if(preg_match("-", $field)) {
                return 0;
            } elseif(preg_match("/", $field)) {
                return 0;
            } else
                return 1;
        } else {
            return 0;
        }
    }
    function validate_password($field) {
        $strlen = strlen($field);
        if ($strlen > 5) {
            return 1;
        } else {
            return 0;
        }
    }		
    function validate_name_algo($name) {
        $arr = explode(" ", trim($name));
        for ($i=0; $i < count($arr); $i++) {
            if (ereg("[a, e, i, o, u, y,]", $arr[$i])) {
                return 1;
            } else if (ereg("[A, E, I, O, U, Y,]", $arr[$i])) {
                return 1;
            }
        }
        return 0;
    }
    function format_data_for_insert($data) {
        $formated_data = addslashes(strip_tags(trim($data)));
        return $formated_data;
    }		
    function format_data_for_view($data) {
        return stripslashes($data);
    }
    function make_date($unformated_date) {
        list($month, $day, $year) = explode("/",$unformated_date);
        $date = $year . "-" . $month . "-" . $day;
        return($date);
    }		
    function format_date($unformated_date) {
        if (empty($unformated_date)) {
            return;
        } else {
            list($year, $month, $day) = explode("-",$unformated_date);
            $formatted_date = $day . "/" . $month . "/" . $year;
            return($formatted_date);
        }
    }		
    function count_table ($tab_name, $link) {
        $query = "SELECT * FROM $tab_name";
        $result = mysql_query($query, $link) or die("Can not execute count query" . mysql_error($link));
        return mysql_num_rows($result);
    }		
		
    function validate_alpha($text){
        if(preg_match("/^.*(?=.{8,})(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).*$/", $text) && strlen($text)<=15)
            return true;
        else
            return false;
    }
}
?>