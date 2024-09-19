<?php
defined('BASE') OR exit('No direct script access allowed');

if($data['pageContent']['content']) {

	echo '<div class="sk_content_wrap mb40">';
		foreach($data['pageContent']['content'] as $pageContent) {

			echo '<div class="sk_content">';
				if($pageContent['displayHeading'] == 'Y' && $pageContent['contentHeading'])
					echo '<h1 class="heading">'.headingModify($pageContent['contentHeading']).'</h1>';
					
				if($pageContent['contentDescription'] || $pageContent['subHeading']){
					echo '<div class="editor_text">';
							if($pageContent['subHeading'])
								echo '<h2 class="subheading">'.$pageContent['subHeading'].'</h2>';
							if($pageContent['contentDescription'])
								echo $pageContent['contentDescription'];
						echo '</div>';
				}
			echo '</div>';
		}
		if(isset($data['pageContent']['contentPageList'])) {
			echo '<div class="pagination">';
			echo '<p class="total">Page '.$data['pageContent']['contentPage'].' of '.$data['pageContent']['totalContentPage'].'</p>';
			echo '<div>'.$data['pageContent']['contentPageList'].'</div>';
			echo '</div>';
		}	
	echo '</div>';
}
?>