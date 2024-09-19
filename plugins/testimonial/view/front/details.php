<?php
defined('BASE') OR exit('No direct script access allowed');

if($data['recordDetails']) {//showArray($data);

    $recordImg = ($data['testimonialImage']) ? '<figure class="sk_img_left"><img src="'.$data['testimonialImage'].'" alt="'.$data['recordDetails']['authorName'].'"></figure>' : '';

    $designation = ($data['recordDetails']['designation']) ? ' <span>'.$data['recordDetails']['designation'].'</span>' : '';

    echo '<div class="sk_content_wrap">
            <div class="sk_content">'.$recordImg.'<div class="editor_text">'.$data['recordDetails']['testimonialDescription'].'</div>
            <div class="subheading mt20">'.$data['recordDetails']['authorName'].$designation.'</div>
            </div>
        </div>';

    if($data['prevRecord']){
        $prevLink    = SITE_LOC_PATH.'/'.$data['prevRecord']['permalink'].'/'.$data['prevRecord']['menuPermalink'].'/';
        $prevDisable = '';
    }
    else{
        $prevLink    = 'javascript:void(0)';
        $prevDisable = 'disabled';
    }

    if($data['nextRecord']){
        $nextLink    = SITE_LOC_PATH.'/'.$data['nextRecord']['permalink'].'/'.$data['nextRecord']['menuPermalink'].'/';
        $nextDisable = '';
    }
    else{
        $nextLink    = 'javascript:void(0)';
        $nextDisable = 'disabled';
    }

    echo '<div class="clearfix mt30">
            <a href="'.$prevLink.'" class="sk_prev pull-left '.$prevDisable.'"><i class="fa fa-angle-left"></i> Previous</a>
            <a href="'.$nextLink.'" class="sk_next pull-right '.$nextDisable.'">Next <i class="fa fa-angle-right"></i></a>
        </div>';

    if($data['itemList']) {
        echo ' <div class="testimonial_list mt30">'.$data['itemList'].'</div>';
        
        if(isset($data['pageList'])) {
            echo '<div class="pagination">';
            echo '<p class="total">Page '.$data['page'].' of '.$data['totalPage'].'</p>';
            echo '<div>'.$data['pageList'].'</div>';
            echo '</div>';
        }
    }
}
?>