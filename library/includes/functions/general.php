<?php
/*************************************************************************************************
Word Bound Function
*************************************************************************************************/
function word_split($str,$words=15) {
	$arr = preg_split("/[\s]+/", $str,$words+1);
	$arr = array_slice($arr,0,$words);
	return join(' ',$arr);
}
/*************************************************************************************************
Word Bound Function End
*************************************************************************************************/

/*************************************************************************************************
In Array with Multidimention Function 
*************************************************************************************************/
function in_array_r($needle, $haystack, $strict = false) {
    foreach ($haystack as $item) {
        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
            return true;
        }
    }
    return false;
}

function searchForId($sField, $sValue, $array) {
   foreach ($array as $key => $val) {
       if ($val[$sField] === $sValue) {
           return $key;
       }
   }
   return null;
}
/*************************************************************************************************
In Array with Multidimention Function End
*************************************************************************************************/

/*************************************************************************************************
Permalink Function Start
*************************************************************************************************/
function createPermalinkFromArray($value, $existingArray = [], $needle = ''){
    $permalink = trim(strtolower($value));
	$permalink = preg_replace("/[^a-zA-Z0-9\s]/","", $permalink);
	$permalink = explode(" ",$permalink);
    
	$permalinkArray = array();
	foreach($permalink as $paVal) {
		if($paVal!='')
			$permalinkArray[]=$paVal;
	}
	$raw = $permalink = implode("-", $permalinkArray);
    
    
    if(is_array($existingArray) && sizeof($existingArray) > 0) {
        
        $extension = 1;
        for($key = 0; $key<sizeof($existingArray); $key++) {
            
            $existPermalink = $existingArray[$key];
            
            if($existPermalink == $permalink && $existPermalink != $needle) {
                $permalink = $raw.'-'.$extension;
                $extension++;
                
                $key = 0;
                break;
            }
        }
    }
    
    return $permalink;
}

function createPermalink($ENTITY, $permalink, $ExtraQryStr) {
	$permalink = trim(strtolower($permalink));
	$permalink = preg_replace("/[^a-zA-Z0-9\s]/","", $permalink);
	$permalink = explode(" ",$permalink);
	$permalinkArray = array();
    
	foreach($permalink as $paVal) {
		if($paVal!='')
			$permalinkArray[]=$paVal;
	}
	$permalink = implode("-",$permalinkArray);
    
    $siteObj = new Site;
    $CLAUSE = "permalink = '".$permalink."' AND ".$ExtraQryStr;
    $fetch_permalink = $siteObj->selectSingle($ENTITY, "*", $CLAUSE);
    
    while($fetch_permalink) {
        $extArray = explode('-', $permalink);
        $extArray = array_reverse($extArray);
        $ext = $extArray[0];
        if(is_numeric($ext)) {
            $ext = $ext+1;
            $extArray[0] = $ext;
            $extArray = array_reverse($extArray);
            $permalink = implode('-',$extArray);
        }
        else {
            $ext = 1;
            $extArray = array_reverse($extArray);
            $permalink = implode('-',$extArray).'-'.$ext;
        }
       // $sel_permalink = mysql_query("select * from ".$ENTITY." where permalink='".$permalink."' and ".$ExtraQryStr);
       // $fetch_permalink = mysql_fetch_array($sel_permalink);
        
        $CLAUSE = "permalink = '".$permalink."' AND ".$ExtraQryStr;
        $fetch_permalink = $siteObj->selectSingle($ENTITY, "*", $CLAUSE);
    }

	return $permalink;
}
/*************************************************************************************************
Permalink Function End
*************************************************************************************************/

function stringReplace($str) {
	return str_replace(' ', '-', strtolower($str));
}
function stringToUpper($str) {
	return str_replace('-', ' ', ucwords(strtolower($str)));
}

function headingModify($string, $style = 1){
	$str = '';
	if($string) {
        
		$title 	= explode(' ', $string);
		if(sizeof($title) > 1) {
            if(sizeof($title) > $style) {
				$str 	.= '<span>';
				for($a = 0; $a < $style; $a++)
					$str .= $title[$a].' ';
				$str 	.= '</span> ';
			}
            for ($i = $style; $i <= sizeof($title); $i++)
				$str .= $title[$i].' ';
		}
		else 
			$str 	.= $title[0];
	}
	return 	$str;
}

function stringModify($string, $start, $limit) {
	$str = trim(strip_tags($string));
	if($limit) {
		$str = trim(substr($str, $start, $limit));
		if(strlen($string)>$limit)
			$str .= '...';
	}
	return $str;
}

function showRating($rating) {
	$str = '';
    for($i = 1; $i <= $rating; $i++)
        $str .= '<i class="fa fa-star"></i>';
    if($rating < 5) {
        for($i = $rating; $i < 5; $i++)
            $str .= '<i class="fa fa-star-o"></i>';
    }
	return $str;
}

function cardNumber($str, $first, $last) {
    $strlen = strlen($str);
    $extra  = $strlen - ($first + $last);
    $star   = '';
    for($s=1; $s<=$extra; $s++)
        $star .= '*';

    if($strlen > ($first + $last))
        $str = substr($str, 0, $first).$star.substr($str, '-'.$last);
        
    return $str;
}

function showArray($array){
	if(is_array($array)){
		echo '<pre>'; print_r($array);echo '</pre>';
    }
	else
		echo 'Not an array!'.$array;
}

function showError() {
    ini_set('display_errors', 1); error_reporting(E_ALL);
}

function loadSession(){
    return $session = new Session(SITE_PRIVATE_KEY);
}

function domain_exists($email, $record = 'MX'){
	$emailData = explode('@', $email);
	return checkdnsrr($emailData[1], $record);
}

function getRealIP() {
    // check for shared internet/ISP IP
    if (!empty($_SERVER['HTTP_CLIENT_IP']) && validate_ip($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    }

    // check for IPs passing through proxies
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        // check if multiple ips exist in var
        if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',') !== false) {
            $iplist = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            foreach ($iplist as $ip) {
                if (validate_ip($ip))
                    return $ip;
            }
        } else {
            if (validate_ip($_SERVER['HTTP_X_FORWARDED_FOR']))
                return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
    }
    if (!empty($_SERVER['HTTP_X_FORWARDED']) && validate_ip($_SERVER['HTTP_X_FORWARDED']))
        return $_SERVER['HTTP_X_FORWARDED'];
    if (!empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']) && validate_ip($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
        return $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
    if (!empty($_SERVER['HTTP_FORWARDED_FOR']) && validate_ip($_SERVER['HTTP_FORWARDED_FOR']))
        return $_SERVER['HTTP_FORWARDED_FOR'];
    if (!empty($_SERVER['HTTP_FORWARDED']) && validate_ip($_SERVER['HTTP_FORWARDED']))
        return $_SERVER['HTTP_FORWARDED'];

    // return unreliable ip since all else failed
    return $_SERVER['REMOTE_ADDR'];
}

/**
 * Ensures an ip address is both a valid IP and does not fall within
 * a private network range.
 */
function validate_ip($ip) {
    if (strtolower($ip) === 'unknown')
        return false;

    // generate ipv4 network address
    $ip = ip2long($ip);

    // if the ip is set and not equivalent to 255.255.255.255
    if ($ip !== false && $ip !== -1) {
        // make sure to get unsigned long representation of ip
        // due to discrepancies between 32 and 64 bit OSes and
        // signed numbers (ints default to signed in PHP)
        $ip = sprintf('%u', $ip);
        // do private network range checking
        if ($ip >= 0 && $ip <= 50331647) return false;
        if ($ip >= 167772160 && $ip <= 184549375) return false;
        if ($ip >= 2130706432 && $ip <= 2147483647) return false;
        if ($ip >= 2851995648 && $ip <= 2852061183) return false;
        if ($ip >= 2886729728 && $ip <= 2887778303) return false;
        if ($ip >= 3221225984 && $ip <= 3221226239) return false;
        if ($ip >= 3232235520 && $ip <= 3232301055) return false;
        if ($ip >= 4294967040) return false;
    }
    return true;
}

function validateGoogleCapcha($token) {
    $google_url		= GSITE_VERIFY_URL;
    $ip				= $_SERVER['REMOTE_ADDR'];

    $siteObj        = new Site;
    $ExtraQryStr    = "name = 'captcha'";
    $settings       = $siteObj->selectSingle(TBL_SETTINGS, "value", $ExtraQryStr);
    
    if(!$settings){
        $ExtraQryStr    = "name = 'communication'";
        $settings       = $siteObj->selectSingle(TBL_SETTINGS, "value", $ExtraQryStr);
    }

    $captcha            = unserialize($settings['value']);

    $ch                 = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $google_url,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => [
            'secret'    => $captcha['googleSecretKey'],
            'response'  => $token,
            'remoteip'  => $ip
        ],
        CURLOPT_RETURNTRANSFER => true
    ]);
    $output         = curl_exec($ch);
    curl_close($ch);

    $responseData   = json_decode($output, true);

    /*$url			= file_get_contents($google_url."?secret=".$secret."&response=".$this->_request['g-recaptcha-response']."&remoteip=".$ip);
    $responseData 	= json_decode($url);*/

    return $responseData;
}
?>