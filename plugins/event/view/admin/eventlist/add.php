<?php
defined('BASE') OR exit('No direct script access allowed.');

if($data['events']) {

    $IdToEdit                      = $data['events']['id'];
    $parentId                      = $data['events']['parentId'];
    $menucategoryId                = $data['events']['menucategoryId'];
    $menuPermalink                 = $data['events']['menuPermalink'];
    $eventTitle                   = $data['events']['event_title'];
    $eventCategory                = $data['events']['event_category'];
    $eventTag                     = $data['events']['event_tag'];
    $permalink                     = $data['events']['permalink'];
    $eventShortDescription       = $data['events']['event_short_description'];
    $eventDescription            = $data['events']['event_description'];
    $eventImage                  = $data['events']['event_image'];
    $eventBanner                 = $data['events']['event_banner'];
    $eventDate                    = $data['events']['event_date'];
    $displayOrder                  = $data['events']['displayOrder'];
    $status                        = $data['events']['status'];
    $isShowcase                    = $data['events']['isShowcase'];
	
	$qrystrPermalink			   = 'id != '.$IdToEdit;
}
else {

    $IdToEdit                      = $this->_request['id'];
    $menucategoryId                = $this->_request['menucategoryId'];
    $menuPermalink                 = $this->_request['permalink'];
    $eventTitle                 = $this->_request['event_title'];
    $eventTag                   = $this->_request['event_tag'];
    // $serviceHeading                = $this->_request['serviceHeading'];
    $permalink                     = $this->_request['permalink'];
    $eventShortDescription       = $this->_request['event_short_description'];
    $eventDescription            = $this->_request['event_description'];
    $eventImage                 = $this->_request['event_image'];
    $eventBanner                 = $this->_request['event_banner'];
    $eventDate                    = $this->_request['event_date'];
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
                                <input type="text" name="event_title" value="<?php echo $eventTitle;?>" class="form-control permalink copyToTitle" placeholder="" autocomplete="off" data-entity="<?php echo TBL_EVENT;?>" data-qrystr="<?php echo $qrystrPermalink;?>" maxlength="255">
                            </div>

                            <?php $genPermalink = (!$IdToEdit) ? 'gen_permalink' : ''; ?>
                            
                            <div class="form-group">
                                <label>Permalink*</label>
                            <?php $genPermalink = (!$IdToEdit) ? 'gen_permalink' : ''; ?>
                                <input type="text" name="permalink" value="<?php echo  $permalink;?>" class="form-control <?php echo $genPermalink; ?>" placeholder="" autocomplete="off" maxlength="255">
                            </div>

                            <div class="form-group">
                                <label>Category Name *</label>
                                <input type="text" name="event_category" value="<?php echo $eventCategory;?>" class="form-control copyToTitle" placeholder="" autocomplete="off">
                            </div>

                            <div class="form-group">
                                <label>Tag *</label>
                                <input type="text" name="event_tag" value="<?php echo $eventTag;?>" class="form-control copyToTitle" placeholder="" autocomplete="off">
                            </div>
                            
                            <div class="form-group">
                                <label>Short Description </label>
                                <div class="limitedtext">
                                    <textarea name="event_short_description" class="form-control" maxlength="80"><?php echo $eventShortDescription;?></textarea>
                                    <div class="charcount"></div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Description *</label>
                                <textarea name="event_description" class="form-control editor"><?php echo $eventDescription;?></textarea>
                            </div>

                            <div class="form-group">
                                <label>Event Date*</label>
                                <input type="datetime-local" name="event_date" value="<?php echo $eventDate;?>" class="form-control">
                            </div>

                        </div> 
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="form-group clearfix">
                            <label>Upload Image (Recommended Size: px * px)</label>
                                <input type="file" name="event_image" class="form-control" />
                                <?php
                                if($eventImage && file_exists(MEDIA_FILES_ROOT.'/'.$this->_request['pageType'].'/normal/'.$eventImage)) {
                                    echo '<div class="table_img m-t-10"><img src="'.MEDIA_FILES_SRC.'/'.$this->_request['pageType'].'/normal/'.$eventImage.'" alt="'.$eventImage.'"></div>';
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
                                <input type="file" name="event_banner" class="form-control" />
                                <?php
                                if($eventBanner && file_exists(MEDIA_FILES_ROOT.'/'.$this->_request['pageType'].'/normal/'.$eventBanner)) {
                                    echo '<div class="table_img m-t-10"><img src="'.MEDIA_FILES_SRC.'/'.$this->_request['pageType'].'/normal/'.$eventBanner.'" alt="'.$eventBanner.'"></div>';
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
                            <input type="hidden" name="SourceForm" value="addEditEvent" />
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