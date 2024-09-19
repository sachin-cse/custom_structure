<?php
if($data['captcha']) {
    
	$googleSiteKey           = $data['captcha']['googleSiteKey'];
	$googleSecretKey         = $data['captcha']['googleSecretKey'];
}
else {
    $googleSiteKey           = $this->_request['googleSiteKey'];
	$googleSecretKey         = $this->_request['googleSecretKey'];
}
?>
<div class="row page-titles">
    <div class="col-sm-5 align-self-center"><h3 class="text-primary">Google Recaptcha</h3></div>
    <div class="col-sm-7 align-self-center">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Settings</li>
            <li class="breadcrumb-item active">Google Recaptcha</li>
        </ol>
    </div>
</div>

<div class="container-fluid">
    <?php
    if(isset($data['act']['message']))
        echo (isset($data['act']['type']) && $data['act']['type'] == 1)? '<div class="alert alert-success">'.$data['act']['message'].'</div>':'<div class="alert alert-danger">'.$data['act']['message'].'</div>';
    ?>

    <div>
        <form name="modifycontent" action="" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-sm-12 contentL">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Google Recaptcha Site Key</label>
                                <input type="text" name="googleSiteKey" value="<?php echo $googleSiteKey;?>" class="form-control" />
                            </div>

                            <div class="form-group">
                                <label>Google Recaptcha Secret Key</label>
                                <input type="text" name="googleSecretKey" value="<?php echo $googleSecretKey;?>" class="form-control" placeholder="">
                            </div>
                            <?php if($this->session->read('UTYPE') == "A") {?>
                            <hr>
                            <div class="alert alert-info">
                                <strong>Script SRC</strong> (copy the highlighted text from <a href="https://developers.google.com/recaptcha/docs/v3" target="_blank" rel="nofollow noopener noreferrer">reCAPTCHA v3</a>)<br>
                                &lt;script type="text/javascript" <br>src="<mark><?php echo ($googleSiteKey) ? 'https://www.google.com/recaptcha/api.js?render='.$googleSiteKey : 'https://www.google.com/recaptcha/api.js?render=reCAPTCHA_site_key';?></mark>"&gt;&lt;/script&gt;
                                <hr>
                                <strong>Content Script</strong> (copy the highlighted text from <a href="https://developers.google.com/recaptcha/docs/v3" target="_blank" rel="nofollow noopener noreferrer">reCAPTCHA v3</a>)<br>
                                <p>Call <strong>grecaptcha.execute</strong> on each action you wish to protect.</p>
                                <mark><?php echo "grecaptcha.ready(function() {
                                                grecaptcha.execute('reCAPTCHA_site_key', {action: 'submit'}).then(function(token) {
                                                    // Add your code to submit to your backend server here.
                                                });
                                            });";?>
                                </mark>
                            </div>
                            <?php }?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <button type="button" name="Back" value="Back" onclick="location.href='index.php?pageType=<?php echo $this->_request['pageType'];?>&dtls=<?php echo $this->_request['dtls'];?>'" class="btn btn-default m-r-15">Back</button>

                            <input type="hidden" name="SourceForm" value="updateCaptcha" />
                            <button type="submit" name="Save" value="Save" class="btn btn-info login_btn">Save</button>

                            <button type="button" name="Cancel" value="Close" onclick="location.href='<?php echo SITE_ADMIN_PATH;?>'" class="btn btn-default m-l-15">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>