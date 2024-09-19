<?php
defined('BASE') OR exit('No direct script access allowed.');
if($data['slider']) {
    
	$IdToEdit                  = $data['slider']['id'];
	$sliderName                = $data['slider']['sliderName'];
	$displayHeading            = $data['slider']['displayHeading'];
    
	$subHeading                = $data['slider']['subHeading'];
	$sliderDescription         = $data['slider']['sliderDescription'];
	$imageName                 = $data['slider']['imageName'];
	$buttonName                = $data['slider']['buttonName'];
	$redirectUrl               = $data['slider']['redirectUrl'];
	$redirectUrlTarget         = $data['slider']['redirectUrlTarget'];
	$status                    = $data['slider']['status'];
}
else {

    $sliderName                = $this->_request['sliderName'];
    $displayHeading            = $this->_request['displayHeading'];
    
    $subHeading                = $this->_request['subHeading'];
	$sliderDescription         = $this->_request['sliderDescription'];
	$imageName                 = $this->_request['imageName'];
	$buttonName                = $this->_request['buttonName'];
	$redirectUrl               = $this->_request['redirectUrl'];
    $redirectUrlTarget         = $this->_request['redirectUrlTarget'];
	$status                    = $this->_request['status'];
}
?>

<div class="container-fluid">
    <?php
    if($data['act']['message'])
        echo ($data['act']['type'] == 1)? '<div class="alert alert-success">'.$data['act']['message'].'</div>':'<div class="alert alert-danger">'.$data['act']['message'].'</div>';
    ?>
    
    <div>
        <form name="modifycontent" action="" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-sm-8 contentL">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-8">
                                        <div class="form-group">
                                            <label>Heading *</label>
                                            <input type="text" name="sliderName" value="<?php echo $sliderName;?>" placeholder="" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Display Heading</label>
                                            <select name="displayHeading" class="form-control">
                                                <option value="Y" <?php if($displayHeading=='Y') echo 'selected';?>>Yes</option>
                                                <option value="N" <?php if($displayHeading=='N') echo 'selected';?>>No</option>
                                            </select>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label>Sub Heading</label>
                                <input type="text" name="subHeading" value="<?php echo $subHeading;?>" placeholder="" class="form-control">
                            </div>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Upload Image (Recommended Size: <?php echo $data['sliderWidth'].'px * '.$data['sliderHeight'];?> in JPG)</label>
                                <?php
                                if($IdToEdit)
                                    echo '<input type="file" name="imageName" accept="image/jpeg" class="form-control" />';
                                else
                                    echo '<input type="file" name="imageName[]" accept="image/jpeg" class="form-control" multiple />';
                                
                                if($imageName && file_exists(MEDIA_FILES_ROOT.'/slider/thumb/'.$imageName))
                                    echo '<div class="table_img m-t-10"><img src="'.MEDIA_FILES_SRC.'/slider/thumb/'.$imageName.'?t='.time().'" alt="'.$imageName.'"></div>';
                                ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="sliderDescription" class="form-control editor"><?php echo $sliderDescription;?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
               
                <div class="col-sm-4 contentS">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="Y" <?php if($status == 'Y') echo 'selected';?>>Active</option>
                                    <option value="N" <?php if($status == 'N') echo 'selected';?>>Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Button Label (Ex. Shop Now or Buy Now or Read More...)</label>
                                <input type="text" name="buttonName" value="<?php echo $buttonName;?>" class="form-control" />
                            </div>
                            
                            <div class="form-group">
                                <label>Redirect URL</label>
                                <input type="text" name="redirectUrl" value="<?php echo $redirectUrl;?>" class="form-control" />
                            </div>
                            
                            <div class="form-group">
                                <label>Target </label>
                                <select name="redirectUrlTarget" class="form-control">
                                    <option>Default</option>
                                    <option value="_blank" <?php if($redirectUrlTarget=='_blank') echo 'selected';?>>Open in new tab (_blank)</option>
                                    <option value="_self" <?php if($redirectUrlTarget=='_self') echo 'selected';?>>Open in same tab (_self)</option>
                                </select>
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
                            <input type="hidden" name="SourceForm" value="addSlider" />
                            <button type="submit" name="Save" value="Save" class="btn btn-info login_btn">Save</button>

                            <button type="button" name="Cancel" value="Close" onclick="location.href='<?php echo SITE_ADMIN_PATH;?>'" class="btn btn-default m-l-15">Close</button>   
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>