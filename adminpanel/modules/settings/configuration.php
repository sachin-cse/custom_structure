<?php
if($data['site']) {
    
	$siteName           = $data['site']['siteName'];
	$tagline            = $data['site']['tagline'];
	$siteEmail          = $data['site']['siteEmail'];
	$sitePhone          = $data['site']['sitePhone'];
	$siteMobile         = $data['site']['siteMobile'];
	$siteFax            = $data['site']['siteFax'];
	$siteAddress        = $data['site']['siteAddress'];
	$siteOpeningHours   = $data['site']['siteOpeningHours'];
	$siteCurrency       = $data['site']['siteCurrency'];
	$siteCurrencySymbol = $data['site']['siteCurrencySymbol'];
	$status             = $data['site']['status'];

    $smtpHost           = $data['site']['smtpHost'];
	$smtpEncryption     = $data['site']['smtpEncryption'];
	$smtpPort           = $data['site']['smtpPort'];
	$smtpUserName       = $data['site']['smtpUserName'];
	$smtpUserPassword   = $data['site']['smtpUserPassword'];
}
else {
    
    $siteName           = $this->_request['siteName'];
	$tagline            = $this->_request['tagline'];
	$siteEmail          = $this->_request['siteEmail'];
	$sitePhone          = $this->_request['sitePhone'];
	$siteMobile         = $this->_request['siteMobile'];
	$siteFax            = $this->_request['siteFax'];
	$siteAddress        = $this->_request['siteAddress'];
	$siteOpeningHours   = $this->_request['siteOpeningHours'];
	$siteCurrency       = $this->_request['siteCurrency'];
	$siteCurrencySymbol = $this->_request['siteCurrencySymbol'];
	$status             = $this->_request['status'];

    $smtpHost           = $this->_request['smtpHost'];
	$smtpEncryption     = $this->_request['smtpEncryption'];
	$smtpPort           = $this->_request['smtpPort'];
	$smtpUserName       = $this->_request['smtpUserName'];
	$smtpUserPassword   = $this->_request['smtpUserPassword'];
}
?>
<div class="row page-titles">
    <div class="col-sm-5 align-self-center"><h3 class="text-primary">Configuration</h3></div>
    <div class="col-sm-7 align-self-center">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Settings</li>
            <li class="breadcrumb-item active">Configuration</li>
        </ol>
    </div>
</div>

<div class="container-fluid">
    <?php
    if(isset($data['act']['message']))
        echo (isset($data['act']['type']) && $data['act']['type'] == 1)? '<div class="alert alert-success">'.$data['act']['message'].'</div>':'<div class="alert alert-danger">'.$data['act']['message'].'</div>';
    ?>

    <div>
        <form name="modifycontent" action="" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-sm-8 contentL">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Business Name *</label>
                                <input type="text" name="siteName" value="<?php echo $siteName;?>" class="form-control" maxlength="60" />
                            </div>

                            <div class="form-group">
                                <label>Tagline</label>
                                <input type="text" name="tagline" value="<?php echo $tagline;?>" class="form-control" placeholder="">
                            </div>

                            <div class="form-group">
                                <label>Email</label>
                                <input type="text" name="siteEmail" value="<?php echo $siteEmail;?>" class="form-control" placeholder="">
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Phone</label>
                                            <input type="text" name="sitePhone" value="<?php echo $sitePhone;?>" class="form-control" placeholder="">
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Mobile</label>
                                            <input type="text" name="siteMobile" value="<?php echo $siteMobile;?>" class="form-control" placeholder="">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Fax</label>
                                            <input type="text" name="siteFax" value="<?php echo $siteFax;?>" class="form-control" placeholder="">
                                        </div>

                                        <div class="form-group">
                                            <label>Opening Hours</label>
                                            <textarea name="siteOpeningHours" class="form-control" style="height:125px;"><?php echo $siteOpeningHours;?></textarea>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Address</label>
                                            <textarea name="siteAddress" class="form-control" style="height:210px;"><?php echo $siteAddress;?></textarea>
                                        </div>
                                    </div>
                                </div>
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
                                    <option value="Y" <?php echo ($status == 'Y')? 'selected':'';?> >Publish</option>
                                    <option value="N" <?php echo ($status == 'N')? 'selected':'';?> >Under Construction</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <?php if($this->session->read('UTYPE') == "A") {?>
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Site Currency</label>
                                    <input type="text" name="siteCurrency" value="<?php echo $siteCurrency;?>" class="form-control" placeholder="">
                                </div>

                                <div class="form-group">
                                    <label>Site Currency Symbol</label>
                                    <input type="text" name="siteCurrencySymbol" value="<?php echo $siteCurrencySymbol;?>" class="form-control" placeholder="">
                                </div>
                            </div>
                        </div>


                        <div class="card">
                            <div class="card-title">
                                <h4>SMTP Configaration</h4>
                                <span class="f-s-14" style="line-height:20px;"><i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="SMTP Configaration"></i></span>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>SMTP Host</label>
                                    <input type="text" name="smtpHost" value="<?php echo $smtpHost;?>" class="form-control" placeholder="">
                                </div>

                                <div class="form-group">
                                    <label>Encryption</label>
                                    <select name="smtpEncryption" class="form-control">
                                        <option value="tls" <?php echo $smtpEncryption == 'tls' ? 'selected' :'';?>>TLS</option>
                                        <option value="ssl" <?php echo $smtpEncryption == 'ssl' ? 'selected' :'';?>>SSL</option>
                                        <option value="">None</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>SMTP Port</label>
                                    <input type="text" name="smtpPort" value="<?php echo $smtpPort;?>" class="form-control" placeholder="">
                                </div>

                                <div class="form-group">
                                    <label>User Name</label>
                                    <input type="text" name="smtpUserName" value="<?php echo $smtpUserName;?>" class="form-control" placeholder="">
                                </div>

                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="text" name="smtpUserPassword" value="<?php echo $smtpUserPassword;?>" class="form-control" placeholder="">
                                </div>
                            </div>
                        </div>


                    <?php }?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <button type="button" name="Back" value="Back" onclick="location.href='index.php?pageType=<?php echo $this->_request['pageType'];?>&dtls=<?php echo $this->_request['dtls'];?>'" class="btn btn-default m-r-15">Back</button>

                            <input type="hidden" name="SourceForm" value="updateConfiguration" />
                            <button type="submit" name="Save" value="Save" class="btn btn-info login_btn">Save</button>

                            <button type="button" name="Cancel" value="Close" onclick="location.href='<?php echo SITE_ADMIN_PATH;?>'" class="btn btn-default m-l-15">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>