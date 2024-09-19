<?php
defined('BASE') OR exit('No direct script access allowed.');

if($data['service']) {

    $IdToEdit                      = $data['service']['id'];
    $parentId                      = $data['service']['parentId'];
    $menucategoryId                = $data['service']['menucategoryId'];
    $menuPermalink                 = $data['service']['menuPermalink'];
    $serviceName                   = $data['service']['serviceName'];
    $serviceHeading                = $data['service']['serviceHeading'];
    $permalink                     = $data['service']['permalink'];
    $serviceUnicode                = $data['service']['service_unicode'];
    $serviceShortDescription       = $data['service']['serviceShortDescription'];
    $serviceDescription            = $data['service']['serviceDescription'];
    $serviceImage                  = $data['service']['serviceImage'];
    $serviceIcon                   = $data['service']['serviceIcon'];
    $serviceBanner                 = $data['service']['serviceBanner'];
    $displayOrder                  = $data['service']['displayOrder'];
    $status                        = $data['service']['status'];
    $isShowcase                    = $data['service']['isShowcase'];
	$serviceAttributes = json_decode($data['service']['serviceAttribute']??'', true);
	$qrystrPermalink			   = 'id != '.$IdToEdit;
}
else {

    $IdToEdit                      = $this->_request['id'];
    $menucategoryId                = $this->_request['menucategoryId'];
    $menuPermalink                 = $this->_request['menuPermalink'];
    $serviceName                   = $this->_request['serviceName'];
    $serviceHeading                = $this->_request['serviceHeading'];
    $permalink                     = $this->_request['permalink'];
    $serviceUnicode                = $this->_request['serviceUnicode'];
    $serviceShortDescription       = $this->_request['serviceShortDescription'];
    $serviceDescription            = $this->_request['serviceDescription'];
    $serviceImage                  = $this->_request['serviceImage'];
    $serviceIcon                   = $this->_request['serviceIcon'];
    $serviceBanner                 = $this->_request['serviceBanner'];
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
                                <label>Service Name *</label>
                                <input type="text" name="serviceName" value="<?php echo $serviceName;?>" class="form-control permalink copyToTitle service_unicode" placeholder="" autocomplete="off" data-entity="<?php echo TBL_SERVICE;?>" data-qrystr="<?php echo $qrystrPermalink;?>" maxlength="255" id="service_name">
                            </div>

                            <?php $genPermalink = (!$IdToEdit) ? 'gen_permalink' : ''; ?>
                            
                            <div class="form-group">
                                <label>Permalink</label>
                            <?php $genPermalink = (!$IdToEdit) ? 'gen_permalink' : ''; ?>
                                <input type="text" name="permalink" value="<?php echo  $permalink;?>" class="form-control <?php echo $genPermalink; ?>" placeholder="" autocomplete="off" maxlength="255">
                            </div>

                            <div class="form-group">
                                <label>Heading</label>
                                <input type="text" id="service_heading" name="serviceHeading" value="<?php echo $serviceHeading;?>" class="form-control service_unicode">
                            </div>

                            <div class="form-group">
                                <label>Service Unicode</label>
                                <input type="text" name="serviceUnicode" value="<?php echo $serviceUnicode;?>" class="form-control" id="service_unicode">
                            </div>
                            
                            <div class="form-group">
                                <label>Short Description </label>
                                <div class="limitedtext">
                                    <textarea name="serviceShortDescription" class="form-control" maxlength="80"><?php echo $serviceShortDescription;?></textarea>
                                    <div class="charcount"></div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Description *</label>
                                <textarea name="serviceDescription" class="form-control editor"><?php echo $serviceDescription;?></textarea>
                            </div>
                        </div> 
                    </div>

                    <!-- service attribute -->
                    <div class="card">
                        <div class="card-body">

                            <div class="form-group">
                                <label>Service Color</label>
                                <br>
                                <input type="checkbox" name="service_color[]" <?php if (in_array('red', $serviceAttributes['serviceColor']??[])) echo 'checked="checked"'; ?> value="red" class="">Red
                                <br>
                                <input type="checkbox" name="service_color[]" <?php if (in_array('blue', $serviceAttributes['serviceColor']??[])) echo 'checked="checked"'; ?> value="blue" class="">Blue
                                <br>
                                <input type="checkbox" name="service_color[]" <?php if (in_array('yellow', $serviceAttributes['serviceColor']??[])) echo 'checked="checked"'; ?> value="yellow" class="">Yellow
                            </div>

                            <div class="form-group">
                                <label>Service Location</label>
                                <br>
                                <input type="checkbox" name="service_location[]" value="mumbai" <?php if (in_array('mumbai', $serviceAttributes['serviceLocation']??[])) echo 'checked="checked"'; ?> class="">Mumbai
                                <br>
                                <input type="checkbox" name="service_location[]" value="kolkata" <?php if (in_array('kolkata', $serviceAttributes['serviceLocation']??[])) echo 'checked="checked"'; ?> class="">Kolkata
                                <br>
                                <input type="checkbox" name="service_location[]" value="delhi" <?php if (in_array('delhi', $serviceAttributes['serviceLocation']??[])) echo 'checked="checked"'; ?> class="">Delhi
                            </div>

                        </div> 
                    </div>

                    <!-- <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Upload Gallery Images (Recommended Size: <?php echo $data['settings']['imageWidth'].'px * '.$data['settings']['imageHeight'].'px';?>)</label>
                                <input type="file" name="serviceImage[]" multiple class="form-control" />
                                <?php
                                if($data['serviceImage']) {
                                    echo '<div class="gallery_wrap col3">';
                                    foreach($data['serviceImage'] as $sImage) {
                                        if($sImage['status'] == 'Y')
                                            $status  = '<span class="status"><i class="fa fa-check" title="Active"></i> Active</span>';
                                        else
                                            $status  = '<span class="status inactive"><i class="fa fa-times" title="Inactive"></i> Inactive</span>';

                                        if($sImage['serviceImage'] && file_exists(MEDIA_FILES_ROOT.'/'.$this->_request['pageType'].'/thumb/'.$sImage['serviceImage'])) {
                                            ?>
                                            <div class="gal_list <?php echo ($serviceImage == $sImage['serviceImage']) ? 'checked' : '' ;?>" id="<?php echo 'recordsArray_'.$sImage['id']; ?>">
                                                <div class="gal_list_inner">
                                                    <div class="gal_img">
                                                        <?php echo '<img src="'.MEDIA_FILES_SRC.'/'.$this->_request['pageType'].'/thumb/'.$sImage['serviceImage'].'" alt="'.$sImage['serviceName'].'" />';?>
                                                    </div>
                                                    <div class="gal_action">
                                                        <label class="coverImage withradio float-left" data-serviceImage="<?php echo $sImage['serviceImage'];?>" data-editid="<?php echo $IdToEdit;?>" data-id="<?php echo $sImage['id'];?>" data-action="primary"><input type="radio" name="coverImage" <?php if($serviceImage == $sImage['serviceImage']) echo 'checked';?> value="<?php echo $sImage['id'];?>" > <span>Cover Image</span></label>
                                                        <?php if($serviceImage != $sImage['serviceImage']){ ?>
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
                            <label>Upload Icon (Recommended Size: px * px)</label>
                                <input type="file" name="serviceIcon" class="form-control" />
                                <?php
                                if($serviceIcon && file_exists(MEDIA_FILES_ROOT.'/'.$this->_request['pageType'].'/normal/'.$serviceIcon)) {
                                    echo '<div class="table_img m-t-10"><img src="'.MEDIA_FILES_SRC.'/'.$this->_request['pageType'].'/normal/'.$serviceIcon.'" alt="'.$serviceIcon.'"></div>';
                                    ?>
                                    <label class="btn btn-sm btn-danger float-right m-t-10 deleteGallery">
                                        <input type="radio" name="DeleteFile" value="serviceBanner" onclick="deleteConfirm('warning','Are you sure to delete?');" > <span>Delete Icon</span>
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
                                <input type="file" name="serviceBanner" class="form-control" />
                                <?php
                                if($serviceBanner && file_exists(MEDIA_FILES_ROOT.'/'.$this->_request['pageType'].'/normal/'.$serviceBanner)) {
                                    echo '<div class="table_img m-t-10"><img src="'.MEDIA_FILES_SRC.'/'.$this->_request['pageType'].'/normal/'.$serviceBanner.'" alt="'.$serviceBanner.'"></div>';
                                    ?>
                                    <label class="btn btn-sm btn-danger float-right m-t-10 deleteGallery">
                                        <input type="radio" name="DeleteFile" value="serviceBanner" onclick="deleteConfirm('warning','Are you sure to delete?');" > <span>Delete Banner</span>
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
                            <input type="hidden" name="SourceForm" value="addEditService" />
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

    $(document).ready(function(){
        var ajax_url = "./index.php?pageType=<?php echo $this->_request['pageType']; ?>" + 
            "&dtls=<?php echo $this->_request['dtls']; ?>" + 
            "&moduleId=<?php echo $this->_request['moduleId']; ?>";

        $(document).on('keyup','.service_unicode', function(){
            var serviceName = $('#service_name').val();
            var serviceheading = $('#service_heading').val();

            $('#service_unicode').val('');

            if(typeof serviceName != 'undefined' && typeof serviceheading != 'undefined'){
                $.ajax({
                    url:ajax_url,
                    type:'POST',
                    dataType:'json',
                    data:{serviceName:serviceName,serviceheading:serviceheading, ajx_action:'getUniqueName'},
                    success:function(response){
                        $('#service_unicode').val(response.unicode);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log('AJAX error: ' + textStatus + ' : ' + errorThrown);
                    }
                });
            }
        });
    });
</script>