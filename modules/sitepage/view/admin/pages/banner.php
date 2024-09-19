<?php defined('BASE') OR exit('No direct script access allowed.');?>
<div class="card">
    <div class="card-title">
        <h4 style="line-height:24px;margin:0;">Banner</h4>
        <label class="switch float-right">
            <input type="checkbox" name="isBanner" <?php if($isBanner == '1') echo 'checked';?>>
            <span></span>
        </label>
    </div>
    <div class="card-body">
        <div class="form-group">
            <label>Banner Image (Recommended Size: <?php echo $data['bannerWidth'].'px * '.$data['bannerHeight'].'px';?>)</label>
            <input type="file" name="ImageName" class="form-control" />
            <?php
            if($this->_request['editid']) {
                if($categoryImage && file_exists(MEDIA_FILES_ROOT.'/banner/thumb/'.$categoryImage)) {
                    echo '<div class="table_img m-t-10"><img src="'.MEDIA_FILES_SRC.'/banner/thumb/'.$categoryImage.'?t='.time().'" /></div>';
                    
                    echo '<button type="submit" name="DeleteImg" class="btn btn-sm btn-danger float-right m-t-10">Delete Banner</button>';
                }
            }
            ?>
        </div>
        <hr>
        <div class="form-group">
            <label class="switch float-right">
                <input type="checkbox" name="isBannerCaption" <?php if($isBannerCaption == '1') echo 'checked';?>>
                <span></span>
            </label>
            <label>Banner Caption</label>
            <input type="text" name="bannerCaption" value="<?php echo $bannerCaption;?>" class="form-control" />
        </div>
    </div>
</div>