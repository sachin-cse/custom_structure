<?php
defined('BASE') OR exit('No direct script access allowed.');
if($data['gallery']) {

    $IdToEdit                      = $data['gallery']['id'];
    $menucategoryId                = $data['gallery']['menucategoryId'];
    // $menuPermalink                 = $data['gallery']['menuPermalink'];
    $galleryName                   = $data['gallery']['brand_name'];
    // $gallerySubName                = $data['gallery']['gallerySubName'];
    // $galleryHeading                = $data['gallery']['galleryHeading'];
    $permalink                     = $data['gallery']['brand_url'];
    // $galleryShortDescription       = $data['gallery']['galleryShortDescription'];
    // $galleryDescription            = $data['gallery']['galleryDescription'];
    $galleryImage                  = $data['gallery']['brand_image'];
    //$galleryIcon                   = $data['gallery']['galleryIcon'];
    $displayOrder                  = $data['gallery']['displayOrder'];
    $status                        = $data['gallery']['status'];
    $isShowcase                    = $data['gallery']['isShowcase'];
	
	$qrystrPermalink			   = 'id != '.$IdToEdit;
}
else {

    $IdToEdit                      = $this->_request['id'];
    $menucategoryId                = $this->_request['menucategoryId'];
    $menuPermalink                 = $this->_request['menuPermalink'];
    $galleryName                   = $this->_request['galleryName'];
    $permalink                     = $this->_request['permalink'];
    $galleryImage                  = $this->_request['galleryImage'];
    //$galleryIcon                   = $this->_request['galleryIcon'];
    $displayOrder                  = $this->_request['displayOrder'];
    $status                        = $this->_request['status'];
    $isShowcase                    = $this->_request['isShowcase'];
	
	$qrystrPermalink			   = 1;
}

?>
<div class="container-fluid">
    <?php
    if(isset($data['act']['message']))
        echo (isset($data['act']['type']) && $data['act']['type'] == 1)? '<div class="alert alert-success">'.$data['act']['message'].'</div>':'<div class="alert alert-danger">'.$data['act']['message'].'</div>';
    ?>
 
    <div>
        <form name="modifycontent" action="" method="post" enctype="multipart/form-data" id="form">
            <div class="row">
                <div class="col-sm-8 contentL">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Brand Name *</label>
                                <input type="text" name="galleryName" value="<?php echo $galleryName;?>" class="form-control copyToTitle" placeholder="" autocomplete="off" data-entity="<?php echo TBL_BRAND;?>" data-qrystr="<?php echo $qrystrPermalink;?>" maxlength="255">
                            </div>
                            
                            <div class="form-group">
                                <label>Brand Url</label>
                                <input type="text" name="permalink" value="<?php echo  $permalink;?>" class="form-control" placeholder="" autocomplete="off" maxlength="255">
                            </div>
                            
                        </div> 
                    </div>

                    <!-- <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Upload Brand Images (Recommended Size: <?php echo $data['settings']['imageWidth'].'px * '.$data['settings']['imageHeight'].'px';?>)</label>
                                <input type="file" name="galleryImage[]" multiple class="form-control" />
                                <?php
                                if($data['galleryImage']) {
                                    echo '<div class="gallery_wrap swap col3">';
                                    foreach($data['galleryImage'] as $sImage) {
                                        if($sImage['status'] == 'Y')
                                            $status  = '<span class="status"><i class="fa fa-check" title="Active"></i> Active</span>';
                                        else
                                            $status  = '<span class="status inactive"><i class="fa fa-times" title="Inactive"></i> Inactive</span>';

                                        if($sImage['brand_image'] && file_exists(MEDIA_FILES_ROOT.'/'.$this->_request['pageType'].'/thumb/'.$sImage['brand_image'])) {
                                            ?>
                                            <div class="gal_list <?php echo ($galleryImage == $sImage['brand_image']) ? 'checked' : '' ;?>" id="<?php echo 'recordsArray_'.$sImage['id']; ?>">
                                                <div class="gal_list_inner">
                                                    <div class="gal_img">
                                                        <?php echo '<img src="'.MEDIA_FILES_SRC.'/'.$this->_request['pageType'].'/thumb/'.$sImage['brand_image'].'" alt="'.$sImage['brand_name'].'" />';?>
                                                    </div>
                                                    <div class="gal_action">
                                                        <label class="coverImage withradio float-left" data-galleryImage="<?php echo $sImage['brand_image'];?>" data-editid="<?php echo $IdToEdit;?>" data-id="<?php echo $sImage['id'];?>" data-action="primary"><input type="radio" name="coverImage" <?php if($galleryImage == $sImage['brand_image']) echo 'checked';?> value="<?php echo $sImage['id'];?>" > <span>Cover Image</span></label>
                                                        <?php if($galleryImage != $sImage['brand_image']){ ?>
                                                            <label class="m-l-5 deleteGallery float-right">
                                                                <input type="radio" name="DeleteImg" value="<?php echo $sImage['id'];?>" onclick="deleteConfirm('warning','Are you sure to delete?');" > <span class="btn btn-sm btn-danger width-auto">Delete</span>
                                                            </label>
                                                        <?php }?>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                    }
                                    echo '</div>';
                                }
                                ?>
                            </div>
                        </div>
                    </div> -->
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group clearfix">
                                <label>Upload Image</label>
                                <input type="file" name="galleryImage" class="form-control" />
                                <?php
                                if($galleryImage && file_exists(MEDIA_FILES_ROOT.'/brand/normal/'.$galleryImage)) {
                                    echo '<div class="table_img m-t-10"><img src="'.MEDIA_FILES_SRC.'/brand/normal/'.$galleryImage.'" alt="'.$galleryImage.'" width="100" height="100"></div>';
                                    echo '<button type="submit" name="DeleteImg" class="btn btn-sm btn-danger float-right m-t-10">Delete Image</button>'; 
                                }
                                ?>
                            </div>
                        </div>
                    </div>
    
                </div>
                
                <div class="col-sm-4 contentS">
                    <?php if($IdToEdit){?>
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="m-b-0 w-100">
                                        <a style="line-height:36px;" href="<?php echo SITE_LOC_PATH.'/'.$menuPermalink.'/'.$permalink.'/';?>" target="_blank"><i class="fa fa-external-link"></i> Visit Page</a>

                                        <a href="index.php?pageType=<?php echo $this->_request['pageType'];?>&dtls=<?php echo $this->_request['dtls'];?>&dtaction=add&moduleId=<?php echo $this->_request['moduleId'];?>" class="btn btn-default pull-right">Add New</a>
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
                                    <option value="Y" <?php if($isShowcase=='Y') echo 'selected';?>>Yes</option>
                                    <option value="N" <?php if($isShowcase=='N') echo 'selected';?>>No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                  
                    <?php /*
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group clearfix">
                                <label>Upload Icon (Recommended Size: <?php echo $data['settings']['iconWidth'].'px * '.$data['settings']['iconHeight'].'px';?>)</label>
                                <input type="file" name="galleryIcon" class="form-control" />
                                <?php
                                if($galleryIcon && file_exists(MEDIA_FILES_ROOT.'/'.$this->_request['pageType'].'/thumb/'.$galleryIcon)) {
                                    echo '<div class="table_img m-t-10"><img src="'.MEDIA_FILES_SRC.'/'.$this->_request['pageType'].'/thumb/'.$galleryIcon.'" alt="'.$galleryIcon.'"></div>';
                                    ?>
                                    <label class="btn btn-sm btn-danger float-right m-t-10 deleteGallery">
                                        <input type="radio" name="DeleteFile" value="galleryIcon" onclick="deleteConfirm('warning','Are you sure to delete?');" > <span>Delete Icon</span>
                                    </label>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    */ ?>
  
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
                            <input type="hidden" name="SourceForm" value="addEditBrand" />
                            <button type="submit" name="Save" value="Save" class="btn btn-info login_btn">Save</button>

                            <button type="button" name="Cancel" value="Close" onclick="location.href='index.php?pageType=<?php echo $this->_request['pageType'];?>&dtls=<?php echo $this->_request['dtls'];?>&moduleId=<?php echo $this->_request['moduleId'];?>'" class="btn btn-default m-l-15">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    function deleteConfirm(msgtype,title){
        swal({
            title: title,
            text: "",
            type: msgtype,
            showCancelButton: true,
            confirmButtonColor: "#ef5350",
            confirmButtonText: "Yes, delete it!!",
            closeOnConfirm: false
        },
        function(){
            $('#form').submit();
        });
    }
</script>