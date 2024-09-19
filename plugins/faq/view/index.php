<?php
defined('BASE') OR exit('No direct script access allowed');

include('content.php');
if($data['faqs']) {
	?>
	<div class="sk_toggle">
        <?php
        $i = 1;
        foreach ($data['faqs'] as $faq) {
            ?>
            <div class="sk_box <?php echo ($i == 1) ? 'opened' :'';?>">
                <div class="sk_ques"><h2 class="subheading"><?php echo $faq['faqName'];?></h2></div>
                <div class="sk_ans" <?php echo ($i == 1) ? 'style="display:block;"' :'';?>>
                    <div class="editor_text"><?php echo $faq['faqDescription'];?></div>
                </div>
            </div>
            <?php
            $i++;
        }
        ?>
    </div>
	<?php
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