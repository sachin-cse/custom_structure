<?php
defined('BASE') OR exit('No direct script access allowed.');
if($data['review']) {

    $IdToEdit                      	= $data['review']['testimonialId'];
    $menucategoryId                 = $data['review']['menucategoryId'];
    $menuPermalink                  = $data['review']['menuPermalink'];
    $authorName                   	= $data['review']['authorName'];
    $permalink                   	= $data['review']['permalink'];
    $testimonialDescription         = $data['review']['testimonialDescription'];
    $designation                    = $data['review']['designation'];
    $displayOrder                  	= $data['review']['displayOrder'];
    $testimonialImage               = $data['review']['testimonialImage'];
    $status                        	= $data['review']['status'];
    $isShowcase                  	= $data['review']['isShowcase'];
	
	$qrystrPermalink			   	= 'testimonialId != '.$IdToEdit;
}
else {

    $IdToEdit                      	= $this->_request['testimonialId'];
    $menucategoryId                 = $this->_request['menucategoryId'];
    $menuPermalink                  = $this->_request['menuPermalink'];
    $authorName                   	= $this->_request['authorName'];
    $permalink                   	= $this->_request['permalink'];
    $testimonialDescription         = $this->_request['testimonialDescription'];
    $designation                    = $this->_request['designation'];
    $displayOrder                  	= $this->_request['displayOrder'];
    $testimonialImage               = $this->_request['testimonialImage'];
    $status                        	= $this->_request['status'];
    $isShowcase                  	= $this->_request['isShowcase'];
	
	$qrystrPermalink			   	= 1;
}

?>
<div class="container-fluid">
    <?php
    if(isset($data['act']['message']))
        echo (isset($data['act']['type']) && $data['act']['type'] == 1)? '<div class="alert alert-success">'.$data['act']['message'].'</div>':'<div class="alert alert-danger">'.$data['act']['message'].'</div>';
    ?>
 
    <div>
        <form name="modifycontent" action="" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-sm-8 contentL">
                    <div class="card">
                        <div class="card-body">
                    
                            <div class="form-group">
                                <label>Testimonial By *</label>
                                <input type="text" name="authorName" value="<?php echo $authorName;?>" class="form-control permalink copyToTitle" placeholder="" autocomplete="off" data-entity="<?php echo TBL_TESTIMONIAL;?>" data-qrystr="<?php echo $qrystrPermalink;?>" maxlength="255">
                            </div>
                            
                            <div class="form-group">
                                <label>Permalink</label>
                                <input type="text" name="permalink" value="<?php echo  $permalink;?>" class="form-control gen_permalink" placeholder="" autocomplete="off" maxlength="255">
                            </div>

                            <div class="form-group">
                                <label>Designation</label>
                                <input type="text" name="designation" value="<?php echo $designation;?>" class="form-control" placeholder="" autocomplete="off">
                            </div> 

                            <div class="form-group">
                                <label>Testimonial *</label>
                                <textarea name="testimonialDescription" class="form-control editor"><?php echo $testimonialDescription;?></textarea>
                            </div>
                        </div> 
                    </div>
                </div>
                
                <div class="col-sm-4 contentS">
                    
                    <?php if($IdToEdit) {?>
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="m-b-0 w-100">
                                        <a style="line-height:36px;" href="<?php echo SITE_LOC_PATH.'/'.$permalink.'/'.$menuPermalink.'/';?>" target="_blank"><i class="fa fa-external-link"></i> Visit Page</a>

                                        <a href="<?php echo 'index.php?pageType='.$this->_request['pageType'].'&dtls='.$this->_request['dtls'].'&dtaction='.$this->_request['dtaction'].'&moduleId='.$this->_request['moduleId'];?>" class="btn btn-default pull-right">Add New</a>
                                    </label>
                                </div>
                            </div>
                        </div>
                    <?php }?>
                    
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="Y" <?php if($status=='Y') echo 'selected'?>>Active</option>
                                    <option value="N" <?php if($status=='N') echo 'selected'?>>Inactive</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label>Display Priority</label>

                                <select name="displayOrder" class="form-control">
                                    <?php if($IdToEdit) {?>
                                    <option value="<?php echo $displayOrder;?>" >Stay as it is</option>
                                    <?php }?>
                                    <option value="T" <?php echo ($displayOrder == 'T')? 'selected':'';?>>Move to top</option>
                                    <option value="B" <?php echo ($displayOrder == 'B')? 'selected':'';?>>Move to bottom</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label><i class="fa fa-star"></i> Show on Showcase</label>
                                <select name="isShowcase" class="form-control">
                                    <option value="N" <?php if($isShowcase=='N') echo 'selected';?>>No</option>
                                    <option value="Y" <?php if($isShowcase=='Y') echo 'selected';?>>Yes</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group clearfix">
                                <label>Upload Image (Recommended Size: <?php echo $data['settings']['avatarWidth'].'px * '.$data['settings']['avatarHeight'].'px';?>)</label>
                                <input type="file" name="testimonialImage" class="form-control" />
                                <?php
                                if($testimonialImage && file_exists(MEDIA_FILES_ROOT.'/testimonial/thumb/'.$testimonialImage)) {
                                    echo '<div class="table_img m-t-10"><img src="'.MEDIA_FILES_SRC.'/testimonial/thumb/'.$testimonialImage.'" alt="'.$testimonialImage.'"></div>';
                                    echo '<button type="submit" name="DeleteImg" class="btn btn-sm btn-danger float-right m-t-10">Delete Image</button>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Assign Under</label>
                                <select name="menucategoryId" class="form-control">
                                    <?php
                                    foreach($data['linkedPages'] as $linkedPage){
                                        echo '<option value="'.$linkedPage['categoryId'].'" '.(($menucategoryId == $linkedPage['categoryId']) ? 'selected' : '').'>'.$linkedPage['categoryName'].'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <?php $this->loadView('seo/titlemeta', 'seopanel.php', $data['seoData']);?>
                </div>
            </div>
                
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <button type="button" name="Back" value="Back" onclick="history.back(-1);" class="btn btn-default m-r-15">Back</button>
                            
                            <input type="hidden" name="IdToEdit" value="<?php echo $IdToEdit;?>" />
                            <input type="hidden" name="SourceForm" value="addEditReview" />
                            <button type="submit" name="Save" value="Save" class="btn btn-info login_btn">Save</button>

                            <button type="button" name="Cancel" value="Close" onclick="location.href='index.php?pageType=<?php echo $this->_request['pageType'];?>&dtls=<?php echo $this->_request['dtls'];?>&moduleId=<?php echo $this->_request['moduleId'];?>'" class="btn btn-default m-l-15">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>