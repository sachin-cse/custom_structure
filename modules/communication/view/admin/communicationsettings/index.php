<?php
defined('BASE') OR exit('No direct script access allowed.');
if($data['settings']) {
    
	$IdToEdit                  = $data['settings']['id'];
	$isForm                    = $data['settings']['isForm'];
    $formHeading               = $data['settings']['formHeading'];
    $successMsg                = $data['settings']['successMsg'];
    
    $isCaptcha                 = $data['settings']['isCaptcha'];
    
	$isMap                     = $data['settings']['isMap'];
	$mapAddress                = $data['settings']['mapAddress'];
	$toEmail                   = $data['settings']['toEmail'];
	$cc                        = $data['settings']['cc'];
	$bcc                       = $data['settings']['bcc'];
	$replyTo                   = $data['settings']['replyTo'];
	
	$emailSubject              = $data['settings']['emailSubject'];
	$emailBody                 = $data['settings']['emailBody'];
}
else {
    
    $isForm                    = $this->_request['isForm'];
    $formHeading               = $this->_request['formHeading'];
    $successMsg                = $this->_request['successMsg'];
    
    $isCaptcha                 = $this->_request['isCaptcha'];
    
    $isMap                     = $this->_request['isMap'];
	$mapAddress                = $this->_request['mapAddress'];
    
    $emailSubject              = $this->_request['emailSubject'];
	$emailBody                 = $this->_request['emailBody'];
	$toEmail                   = $this->_request['toEmail'];
	$cc                        = $this->_request['cc'];
	$bcc                       = $this->_request['bcc'];
	$replyTo                   = $this->_request['replyTo'];
    
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
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-title">
                            <h4 style="line-height:24px;margin:0;">Contact Form</h4>
                            <label class="switch float-right">
                                <input type="checkbox" name="isForm" <?php if($isForm == '1') echo 'checked';?>>
                                <span></span>
                            </label>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Form Heading </label>
                                <input type="text" name="formHeading" value="<?php echo $formHeading;?>" placeholder="Form Heading" class="form-control">
                            </div>
                            
                            <div class="form-group">
                                <label>Success Message </label>
                                <input type="text" name="successMsg" value="<?php echo $successMsg;?>" placeholder="Message" class="form-control">
                            </div>
                        </div>
                        <div class="card-title m-t-20 m-b-0">
                            <h4>Google Recaptcha</h4>
                            <label class="switch float-right">
                                <input type="checkbox" name="isCaptcha" <?php if($isCaptcha == '1') echo 'checked';?>>
                                <span></span>
                            </label>
                        </div>
                    </div>
                </div>
                
                
               
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-title">
                            <h4 style="line-height:24px;margin:0;">Google Map</h4>
                            <label class="switch float-right">
                                <input type="checkbox" name="isMap" <?php if($isMap == '1') echo 'checked';?>>
                                <span></span>
                            </label>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Address</label>
                                <textarea type="text" name="mapAddress" placeholder="Address to show on map" class="form-control" style="height:170px;"><?php echo $mapAddress;?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                
            <div class="row">
                <div class="col-sm-8 contentL">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Email Subject *</label>
                                <input type="text" name="emailSubject" value="<?php echo $emailSubject;?>" placeholder="" class="form-control">
                            </div>
                            
                            <div class="form-group">
                                <label>Email Template *</label>
                                <textarea name="emailBody" class="form-control editor_small"><?php echo $emailBody;?></textarea>
                            </div>
                            <div class="alert alert-info">Do not change these variables: {name}, {email}, {phone}, {comments}.</div>
                        </div>
                    </div>
                </div>
                
                <div class="col-sm-4 contentS">
                    <div class="card">
                        <div class="card-body">
                            
                            <div class="form-group">
                                <label>To *</label>
                                <input type="text" name="toEmail" value="<?php echo $toEmail;?>" placeholder="Email Address" class="form-control">
                            </div>
                            
                            <div class="form-group">
                                <label>Cc</label>
                                <input type="text" name="cc" value="<?php echo $cc;?>" placeholder="Email Address" class="form-control">
                            </div>
                            
                            <div class="form-group">
                                <label>Bcc</label>
                                <input type="text" name="bcc" value="<?php echo $bcc;?>" placeholder="Email Address" class="form-control">
                            </div>
                            
                            <div class="form-group">
                                <label>No-reply Email *</label>
                                <input type="text" name="replyTo" value="<?php echo $replyTo;?>" placeholder="Email Address" class="form-control">
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
                            <input type="hidden" name="SourceForm" value="addContactSettings" />
                            <button type="submit" name="Save" value="Save" class="btn btn-info login_btn">Save</button>

                            <button type="button" name="Cancel" value="Close" onclick="location.href='<?php echo SITE_ADMIN_PATH;?>'" class="btn btn-default m-l-15">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>