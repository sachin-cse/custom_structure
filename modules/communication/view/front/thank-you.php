<?php defined('BASE') OR exit('No direct script access allowed');

if($data['msg'] && $data['msgShow']) {
	?>
    <section class="section">
		<div class="container">
			<div class="card text-center">
				<div class="thankyou_text">
					<h1 class="subheading"><?php echo $data['msg'];?></h1>
				</div>
				<div class="thankyou_img">
					<img src="<?php echo $this->selfLoc.'media'.DS.'success.png';?>" alt="Thank You" title="Thank You" />
				</div>
			</div>
		</div>
	</section>
	<?php 
}
else
    Site::redirectToURL(SITE_LOC_PATH);
?>