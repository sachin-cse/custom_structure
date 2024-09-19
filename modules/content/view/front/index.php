<?php
defined('BASE') OR exit('No direct script access allowed');

if($data['pageContent']['content']) {

	echo '<div class="sk_content_wrap">';
		foreach($data['pageContent']['content'] as $pageContent) {

			$contentImg = '';
			
			echo '<div class="sk_content">';
				if($pageContent['displayHeading'] == 'Y' && $pageContent['contentHeading'])
					echo '<h1 class="heading">'.$pageContent['contentHeading'].'</h1>';

				if($pageContent['ImageName'] && file_exists(MEDIA_FILES_ROOT.DS.'content'.DS.'thumb'.DS.$pageContent['ImageName'])){
					$contentImg = '<figure class="sk_img_right">
									<img src="'.MEDIA_FILES_SRC.DS.'content'.DS.'thumb'.DS.$pageContent['ImageName'].'" alt="'.$pageContent['contentHeading'].'" />
								</figure>';
				}
					
				if($pageContent['contentDescription'] || $pageContent['subHeading'] || $contentImg){
					echo $contentImg.'<div class="editor_text">';
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