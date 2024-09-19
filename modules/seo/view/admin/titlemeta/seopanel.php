<?php
defined('BASE') OR exit('No direct script access allowed.');
if($data){
    
    $pageTitleText                 = $data['pageTitleText'];
    $metaTag                       = $data['metaTag'];
    $metaDescription               = $data['metaDescription'];
    $metaRobots                    = explode(', ', $data['metaRobots']);
    $metaRobotsIndex               = $metaRobots[0];
    $metaRobotsFollow              = $metaRobots[1];
    $others                        = $data['others'];
    
} else {
    if(isset($this->_request['SourceForm'])) {
        
        $pageTitleText                 = $this->_request['pageTitleText'];
        $metaTag                       = $this->_request['metaTag'];
        $metaDescription               = $this->_request['metaDescription'];
        $metaRobots                    = explode(', ', $this->_request['metaRobots']);
        $metaRobotsIndex               = $metaRobots[0];
        $metaRobotsFollow              = $metaRobots[1];
        $others                        = $this->_request['others'];
        
    } else {
        
        $default = DefaulthomeModel::defaultMetaData($this->session->read('SITEID'));
        
        $pageTitleText                 = $default['pageTitleText'];
        $metaTag                       = $default['metaTag'];
        $metaDescription               = $default['metaDescription'];
        $metaRobots                    = explode(', ', $default['metaRobots']);
        $metaRobotsIndex               = $metaRobots[0];
        $metaRobotsFollow              = $metaRobots[1];
        $others                        = $default['others'];
    }
}
?>
<div class="card slideWrap">
    <div class="card-title slideSwitch">
        <h4>SEO Data <i class="fa fa-angle-right pull-right"></i></h4>
        
    </div>
    <div class="card-body slideBox">
        <hr class="m-t-20 m-b-20">
        <div class="form-group">
            <label>Page Title</label>
            <div class="textlimit">
                <textarea name="pageTitleText" class="form-control"><?php echo $pageTitleText;?></textarea>
                <div class="charcount">(<?php echo strlen($pageTitleText);?> characters)</div>
            </div>
        </div>

        <div class="form-group">
            <label>Meta Keyword</label>
            <textarea name="metaTag" class="form-control" style="height:50px;"><?php echo $metaTag;?></textarea>
        </div>

        <div class="form-group">
            <label>Meta Description</label>
            <textarea name="metaDescription" class="form-control" style="height:50px;"><?php echo $metaDescription;?></textarea>
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Robots Index</label>
                        <select name="metaRobotsIndex" class="form-control">
                            <option value="default" <?php if($metaRobotsIndex == 'default') echo 'selected="selected"';?>>Default</option>
                            <option value="index" <?php if($metaRobotsIndex == 'index') echo 'selected="selected"';?>>index</option>
                            <option value="noindex" <?php if($metaRobotsIndex == 'noindex') echo 'selected="selected"';?>>noindex</option>
                        </select>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Robots Follow</label>
                        <select name="metaRobotsFollow" class="form-control">
                            <option value="follow" <?php if($metaRobotsFollow == 'follow') echo 'selected="selected"';?>>follow</option>
                            <option value="nofollow" <?php if($metaRobotsFollow == 'nofollow') echo 'selected="selected"';?>>nofollow</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <label>Others</label>
            <div class="alert alert-info">Anything, which will appear inside <strong>&lt;head&gt;</strong> tag. </div>
            <textarea name="others" class="form-control" style="height:50px;"><?php echo $others;?></textarea>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function (){
        
        $('.slideSwitch').on('click', function () {
            $(this).parents('.slideWrap').toggleClass('open');
            $('.slideBox').toggle();
        });
        
        $('.copyToTitle').bind('keyup blur', function () {
            var pageTitle = $(this).val();
			
			if($(this).parents('form').find('input[name="IdToEdit"]').val() == '' ) {
				$('[name="pageTitleText"]').val(pageTitle);
				
				if($('[name="pageTitleText"]').next('.charcount').length) {
					var textLength      = $('[name="pageTitleText"]').val().length;
					$('[name="pageTitleText"]').next('.charcount').html('(' + textLength + ' characters)');
				}
            }
        });
    });
</script>