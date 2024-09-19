<?php
defined('BASE') OR exit('No direct script access allowed.');

if($data['resource']) {

    $IdToEdit                      = $data['resource']['id'];
    $parentId                      = $data['resource']['parentId'];
    $menucategoryId                = $data['resource']['menucategoryId'];
    $menuPermalink                 = $data['resource']['menuPermalink'];
    $resourceTitle                   = $data['resource']['title'];
    $permalink                     = $data['resource']['permalink'];
    $resourceShortDescription       = $data['resource']['short_description'];
    $resourceDescription            = $data['resource']['description'];
    $resourceImage                  = $data['resource']['image'];
    $resourceBanner                 = $data['resource']['banner'];
    $publishDate                    = $data['resource']['publishDate'];
    $displayOrder                  = $data['resource']['displayOrder'];
    $status                        = $data['resource']['status'];
    $isShowcase                    = $data['resource']['isShowcase'];
	
	$qrystrPermalink			   = 'id != '.$IdToEdit;
}
else {

    $IdToEdit                      = $this->_request['id'];
    $menucategoryId                = $this->_request['menucategoryId'];
    $menuPermalink                 = $this->_request['permalink'];
    $resourceTitle                 = $this->_request['title'];
    // $serviceHeading                = $this->_request['serviceHeading'];
    $permalink                     = $this->_request['permalink'];
    $resourceShortDescription       = $this->_request['short_description'];
    $resourceDescription            = $this->_request['description'];
    $resourceImage                 = $this->_request['image'];
    $resourceBanner                 = $this->_request['banner'];
    $publishDate                    = $this->_request['publishDate'];
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
                                <label>Title *</label>
                                <input type="text" name="title" value="<?php echo $resourceTitle;?>" class="form-control permalink copyToTitle" placeholder="" autocomplete="off" data-entity="<?php echo TBL_RESOURCE;?>" data-qrystr="<?php echo $qrystrPermalink;?>" maxlength="255">
                            </div>

                            <?php $genPermalink = (!$IdToEdit) ? 'gen_permalink' : ''; ?>
                            
                            <div class="form-group">
                                <label>Permalink*</label>
                            <?php $genPermalink = (!$IdToEdit) ? 'gen_permalink' : ''; ?>
                                <input type="text" name="permalink" value="<?php echo  $permalink;?>" class="form-control <?php echo $genPermalink; ?>" placeholder="" autocomplete="off" maxlength="255">
                            </div>
                            
                            <div class="form-group">
                                <label>Short Description </label>
                                <div class="limitedtext">
                                    <textarea name="short_description" class="form-control" maxlength="80"><?php echo $resourceShortDescription;?></textarea>
                                    <div class="charcount"></div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Description *</label>
                                <textarea name="description" class="form-control editor"><?php echo $resourceDescription;?></textarea>
                            </div>

                            <div class="form-group">
                                <label>Publish Date*</label>
                                <input type="datetime-local" name="publishDate" value="<?php echo $publishDate;?>" class="form-control">
                            </div>

                        </div> 
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="form-group clearfix">
                            <label>Upload Image (Recommended Size: px * px)</label>
                                <input type="file" name="image" class="form-control" />
                                <?php
                                if($resourceImage && file_exists(MEDIA_FILES_ROOT.'/'.$this->_request['pageType'].'/normal/'.$resourceImage)) {
                                    echo '<div class="table_img m-t-10"><img src="'.MEDIA_FILES_SRC.'/'.$this->_request['pageType'].'/normal/'.$resourceImage.'" alt="'.$resourceImage.'"></div>';
                                    ?>
                                    <label class="btn btn-sm btn-danger float-right m-t-10 deleteGallery">
                                        <input type="radio" name="DeleteFile" value="image" onclick="deleteConfirm('warning','Are you sure to delete?');" > <span>Delete Image</span>
                                    </label>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="form-group clearfix">
                            <label>Banner Image (Recommended Size: 1600px * 310px)</label>
                                <input type="file" name="banner" class="form-control" />
                                <?php
                                if($resourceBanner && file_exists(MEDIA_FILES_ROOT.'/'.$this->_request['pageType'].'/normal/'.$resourceBanner)) {
                                    echo '<div class="table_img m-t-10"><img src="'.MEDIA_FILES_SRC.'/'.$this->_request['pageType'].'/normal/'.$resourceBanner.'" alt="'.$resourceBanner.'"></div>';
                                    ?>
                                    <label class="btn btn-sm btn-danger float-right m-t-10 deleteGallery">
                                        <input type="radio" name="DeleteFile" value="banner" onclick="deleteConfirm('warning','Are you sure to delete?');" > <span>Delete Banner</span>
                                    </label>
                                    <?php
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
                                <input type="file" name="serviceIcon" class="form-control" />
                                <?php
                                if($serviceIcon && file_exists(MEDIA_FILES_ROOT.'/'.$this->_request['pageType'].'/thumb/'.$serviceIcon)) {
                                    echo '<div class="table_img m-t-10"><img src="'.MEDIA_FILES_SRC.'/'.$this->_request['pageType'].'/thumb/'.$serviceIcon.'" alt="'.$serviceIcon.'"></div>';
                                    ?>
                                    <label class="btn btn-sm btn-danger float-right m-t-10 deleteGallery">
                                        <input type="radio" name="DeleteFile" value="serviceIcon" onclick="deleteConfirm('warning','Are you sure to delete?');" > <span>Delete Icon</span>
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
                            <input type="hidden" name="SourceForm" value="addEditResource" />
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