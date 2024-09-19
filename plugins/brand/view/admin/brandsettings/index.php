<?php
defined('BASE') OR exit('No direct script access allowed.');
if($data['settings']) {
    
	$IdToEdit                   = $data['settings']['id'];
	$isForm                     = $data['settings']['isForm'];
    $formHeading                = $data['settings']['formHeading'];
    $successMsg                 = $data['settings']['successMsg'];
    
    $isCaptcha                  = $data['settings']['isCaptcha'];
    
	$toEmail                    = $data['settings']['toEmail'];
	$cc                         = $data['settings']['cc'];
	$bcc                        = $data['settings']['bcc'];
	$replyTo                    = $data['settings']['replyTo'];
	
	$emailSubject               = $data['settings']['emailSubject'];
	$emailBody                  = $data['settings']['emailBody'];
    
    $isBanner                   = $data['settings']['isBanner'];
    $bannerWidth                = $data['settings']['bannerWidth'];
    $bannerHeight               = $data['settings']['bannerHeight'];
    
    $isGallery                  = $data['settings']['isGallery'];
    $imageWidth                 = $data['settings']['imageWidth'];
    $imageHeight                = $data['settings']['imageHeight'];
    $imageThumbWidth            = $data['settings']['imageThumbWidth'];
    $imageThumbHeight           = $data['settings']['imageThumbHeight'];

    $isShortDesc                = $data['settings']['isShortDesc'];
    $isButton                   = $data['settings']['isButton'];
    $btnText                    = $data['settings']['btnText'];
    $limit                      = $data['settings']['limit'];
    
    $isShowcase                 = $data['settings']['isShowcase'];
    $showcaseTitle              = $data['settings']['showcaseTitle'];
    $showcaseNo                 = $data['settings']['showcaseNo'];
    $showcaseDescription        = $data['settings']['showcaseDescription'];
    
    $isSocial                   = $data['settings']['isSocial'];
}
else {
    
    $isForm                     = $this->_request['isForm'];
    $formHeading                = $this->_request['formHeading'];
    $successMsg                 = $this->_request['successMsg'];
    
    $isCaptcha                  = $this->_request['isCaptcha'];
    
    $emailSubject               = $this->_request['emailSubject'];
	$emailBody                  = $this->_request['emailBody'];
	$toEmail                    = $this->_request['toEmail'];
	$cc                         = $this->_request['cc'];
	$bcc                        = $this->_request['bcc'];
	$replyTo                    = $this->_request['replyTo'];
    
    $isBanner                   = $this->_request['isBanner'];
    $bannerWidth                = $this->_request['bannerWidth'];
    $bannerHeight               = $this->_request['bannerHeight'];
    
    $isGallery                  = $this->_request['isGallery'];
    $imageWidth                 = $this->_request['imageWidth'];
    $imageHeight                = $this->_request['imageHeight'];
    $imageThumbWidth            = $this->_request['imageThumbWidth'];
    $imageThumbHeight           = $this->_request['imageThumbHeight'];

    $isShortDesc                = $this->_request['isShortDesc'];
    $isButton                   = $this->_request['isButton'];
    $btnText                    = $this->_request['btnText'];
    $limit                      = $this->_request['limit'];
    
    $isShowcase                 = $this->_request['isShowcase'];
    $showcaseTitle              = $this->_request['showcaseTitle'];
    $showcaseNo                 = $this->_request['showcaseNo'];
    $showcaseDescription        = $this->_request['showcaseDescription'];
    
    $isSocial                   = $this->_request['isSocial'];
}
?>

<div class="container-fluid">
    <?php
    if($data['act']['message'])
        echo ($data['act']['type'] == 1)? '<div class="alert alert-success">'.$data['act']['message'].'</div>':'<div class="alert alert-danger">'.$data['act']['message'].'</div>';
    ?>
    
    <div>
        <form name="modifycontent" action="" method="post">
            <div class="row">
                <div class="col-sm-8 contentL">
                    <div class="card">
                        <div class="card-title">
                            <h4 style="line-height:24px;margin:0;">Showcase</h4>
                            <label class="switch float-right">
                                <input type="checkbox" name="isShowcase" <?php if($isShowcase == '1') echo 'checked';?>>
                                <span></span>
                            </label>
                            <span class="f-s-14 m-l-30" style="line-height:20px;">
                                Hook: showcase <span class="sweetBox" onclick="sAlert('Example', '<div>$this-&gt;hook(\'<?php echo $this->_request['pageType'];?>\', \'showcase\', array(\'css\'=&gt;\'gallery_list sk_shadow_full\', \'col\' =&gt; \'col-sm-4 col-xs-6\'));</div><hr><h3>Options</h3><ul style=\'text-align:left\'><li>\'wrapcss\' =&gt; CSS to wrap the section</li><li>\'css\' =&gt; CSS to wrap all items</li><li>\'col\' =&gt; CSS to indicate columns per row</li><li>\'slider\' =&gt; true (default value false)</li></ul>', true);"><i class="fa fa-question-circle"></i></span>
                            </span>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-8">
                                        <div class="form-group">
                                            <label>Title</label>
                                            <input type="text" name="showcaseTitle" value="<?php echo $showcaseTitle;?>" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Number of Items</label>
                                            <input type="text" name="showcaseNo" value="<?php echo $showcaseNo;?>" class="form-control numbersOnly">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="showcaseDescription" class="form-control editor_small"><?php echo $showcaseDescription;?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-4 contentS">
                    <div class="card">
                        <div class="card-title">
                            <h4 style="line-height:24px;margin:0;">Banner</h4>
                            <label class="switch float-right">
                                <input type="checkbox" name="isBanner" <?php if($isBanner == '1') echo 'checked';?>>
                                <span></span>
                            </label>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-sm-6 p-t-8 p-r-0 m-b-0">Width [px]</label>
                                <div class="col-sm-6">
                                    <input type="text" name="bannerWidth" value="<?php echo $bannerWidth;?>" class="form-control numbersOnly">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-6 p-t-8 p-r-0 m-b-0">Height [px]</label>
                                <div class="col-sm-6">
                                    <input type="text" name="bannerHeight" value="<?php echo $bannerHeight;?>" class="form-control numbersOnly">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-title">
                            <h4 style="line-height:24px;margin:0;">Brand Image</h4>
                            <label class="switch float-right">
                                <input type="checkbox" name="isGallery" <?php if($isGallery == '1') echo 'checked';?>>
                                <span></span>
                            </label>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-sm-7 p-t-8 m-b-0">Large Width [px]</label>
                                <div class="col-sm-5">
                                    <input type="text" name="imageWidth" value="<?php echo $imageWidth;?>" class="form-control numbersOnly">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-7 p-t-8 m-b-0">Large Height [px]</label>
                                <div class="col-sm-5">
                                    <input type="text" name="imageHeight" value="<?php echo $imageHeight;?>" class="form-control numbersOnly">
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <label class="col-sm-7 p-t-8 m-b-0">Thumb Width [px]</label>
                                <div class="col-sm-5">
                                    <input type="text" name="imageThumbWidth" value="<?php echo $imageThumbWidth;?>" class="form-control numbersOnly">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-7 p-t-8 m-b-0">Thumb Height [px]</label>
                                <div class="col-sm-5">
                                    <input type="text" name="imageThumbHeight" value="<?php echo $imageThumbHeight;?>" class="form-control numbersOnly">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-title">
                            <h4 style="line-height:24px;margin:0;">Item List</h4>
                        </div>
                        <div class="card-body">
                            
                            <div class="form-group row">
                                <label class="col-sm-7 p-t-8 m-b-0">Short Description</label>
                                <div class="col-sm-5">
                                    <label class="switch float-right">
                                        <input type="checkbox" name="isShortDesc" <?php if($isShortDesc == '1') echo 'checked';?>>
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-sm-7 p-t-8 m-b-0">Button</label>
                                <div class="col-sm-5">
                                    <label class="switch float-right">
                                        <input type="checkbox" name="isButton" <?php if($isButton == '1') echo 'checked';?>>
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-sm-7 p-t-8 m-b-0">Button Text</label>
                                <div class="col-sm-5">
                                    
                                    <input type="text" name="btnText" value="<?php echo $btnText;?>" class="form-control">
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-sm-7 p-t-8 m-b-0">Items per Page</label>
                                <div class="col-sm-5">
                                    
                                    <input type="text" name="limit" value="<?php echo ($limit) ? $limit : VALUE_PER_PAGE;?>" class="form-control numbersOnly">
                                </div>
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