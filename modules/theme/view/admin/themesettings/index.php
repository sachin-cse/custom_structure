<?php
defined('BASE') OR exit('No direct script access allowed.');
if($data['settings']) {
    
	$IdToEdit                  = $data['settings']['id'];
	$isSlider                  = $data['settings']['isSlider'];
    $sliderNo                  = $data['settings']['sliderNo'];
    $sliderWidth               = $data['settings']['sliderWidth'];
    $sliderHeight              = $data['settings']['sliderHeight'];

	$isBanner                  = $data['settings']['isBanner'];
    $bannerWidth               = $data['settings']['bannerWidth'];
    $bannerHeight              = $data['settings']['bannerHeight'];
    
    $innerBanner               = $data['settings']['innerBanner'];
    $isBannerCaption           = $data['settings']['isBannerCaption'];
    $bannerCaption             = $data['settings']['bannerCaption'];
}
else {
    
    $isSlider                  = $this->_request['isSlider'];
    $sliderNo                  = $this->_request['sliderNo'];
    $sliderWidth               = $this->_request['sliderWidth'];
    $sliderHeight              = $this->_request['sliderHeight'];
    
    $isBanner                  = $this->_request['isBanner'];
    $bannerWidth               = $this->_request['bannerWidth'];
    $bannerHeight              = $this->_request['bannerHeight'];
    
    $innerBanner               = $this->_request['innerBanner'];
    $isBannerCaption           = $this->_request['isBannerCaption'];
    $bannerCaption             = $this->_request['bannerCaption'];
}
?>

<div class="container-fluid">
    <?php
    if($data['act']['message'])
        echo ($data['act']['type'] == 1)? '<div class="alert alert-success">'.$data['act']['message'].'</div>':'<div class="alert alert-danger">'.$data['act']['message'].'</div>';
    ?>
    
    <div>
        <form name="modifycontent" action="" method="post" enctype="multipart/form-data" id="form">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-title">
                            <h4 style="line-height:24px;margin:0;">Home Banner Slider</h4>
                            <label class="switch float-right">
                                <input type="checkbox" name="isSlider" <?php if($isSlider == '1') echo 'checked';?>>
                                <span></span>
                            </label>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group row">
                                        <label class="col-sm-6 p-t-8 m-b-0">Width [px]</label>
                                        <div class="col-sm-6">
                                            <input type="text" name="sliderWidth" value="<?php echo $sliderWidth;?>" class="form-control numbersOnly">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group row">
                                        <label class="col-sm-6 p-t-8 m-b-0">Height [px]</label>
                                        <div class="col-sm-6">
                                            <input type="text" name="sliderHeight" value="<?php echo $sliderHeight;?>" class="form-control numbersOnly">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group row">
                                        <label class="col-sm-6 p-t-8 m-b-0">Number of Items</label>
                                        <div class="col-sm-6">
                                            <input type="text" name="sliderNo" value="<?php echo $sliderNo;?>" class="form-control numbersOnly">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-title">
                            <h4 style="line-height:24px;margin:0;">Inner Banner</h4>
                            <label class="switch float-right">
                                <input type="checkbox" name="isBanner" <?php if($isBanner == '1') echo 'checked';?>>
                                <span></span>
                            </label>
                        </div>
                        <div class="card-body">
                            <div class="row m-b-20">
                                <div class="col-sm-3">
                                    <div class="form-group row">
                                        <label class="col-sm-6 p-t-8 m-b-0">Width [px]</label>
                                        <div class="col-sm-6">
                                            <input type="text" name="bannerWidth" value="<?php echo $bannerWidth;?>" class="form-control numbersOnly">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group row">
                                        <label class="col-sm-6 p-t-8 m-b-0">Height [px]</label>
                                        <div class="col-sm-6">
                                            <input type="text" name="bannerHeight" value="<?php echo $bannerHeight;?>" class="form-control numbersOnly">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label>Upload Banner</label>
                                <input type="file" name="innerBanner" class="form-control" />
                                <?php
                                if($innerBanner && file_exists(MEDIA_FILES_ROOT.'/banner/thumb/'.$innerBanner)) {
                                    echo '<div class="table_img m-t-10"><img src="'.MEDIA_FILES_SRC.'/banner/thumb/'.$innerBanner.'" alt="'.$innerBanner.'"></div>';
                                    ?>
                                    <label class="btn btn-sm btn-danger float-right m-t-10 deleteGallery">
                                        <input type="radio" name="DeleteFile" value="innerBanner" onclick="deleteConfirm('warning','Are you sure to delete?');" > <span>Delete Banner</span>
                                    </label>
                                    <?php
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
                                <input type="text" name="bannerCaption" value="<?php echo $bannerCaption;?>" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <button type="button" name="Back" value="Back" onclick="location.href='index.php?pageType=<?php echo $this->_request['pageType'];?>&dtls=<?php echo $this->_request['dtls'];?>&moduleId=<?php echo $this->_request['moduleId'];?>'" class="btn btn-default m-r-15">Back</button>
                            
                            <input type="hidden" name="IdToEdit" value="<?php echo $IdToEdit;?>" />
                            <input type="hidden" name="SourceForm" value="addEditSettings" />
                            <button type="submit" name="Save" value="Save" class="btn btn-info login_btn">Save</button>

                            <button type="button" name="Cancel" value="Close" onclick="location.href='<?php echo SITE_ADMIN_PATH;?>'" class="btn btn-default m-l-15">Close</button>
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