<?php
defined('BASE') OR exit('No direct script access allowed');

include('content.php');

if($data['itemList']) {
	echo ' <div class="testimonial_list">'.$data['itemList'].'</div>';
    
	if(isset($data['pageList'])) {
		echo '<div class="pagination">';
		echo '<p class="total">Page '.$data['page'].' of '.$data['totalPage'].'</p>';
		echo '<div>'.$data['pageList'].'</div>';
		echo '</div>';
	}
}
else
	echo '<div class="norecord">No record found!</div>';
?>