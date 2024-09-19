<?php defined('BASE') OR exit('No direct script access allowed');

if(isset($data)) {
    
    echo '<div class="social">';
    
    foreach($data as $social)
    {
        $arr        = array('socialLinks');
        $gvars      = new GlobalArray($arr);
        $socialArr  = $gvars->_array['socialLinks'];

        $class      = 'class="sk_'.$socialArr[$social['socialName']]['icon'].'"';
        $icon       = '<i class="fa fa-'.$socialArr[$social['socialName']]['icon'].'"></i>';

        echo '<a href="'.$social['socialLink'].'" target="_blank" rel="noopener noreferrer nofollow" aria-label="'.$social['socialName'].'" '.$class.'>'.$icon.'</a>';
    }
    echo '</div>';
}
?>