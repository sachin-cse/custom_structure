<?php
if($data['user']) {
    
	$email              = $data['user']['email'];
	$fullname           = $data['user']['fullname'];
	$phone              = $data['user']['phone'];
	$address            = $data['user']['address'];
}
else {
    
    $email              = $this->_request['email'];
	$fullname           = $this->_request['fullname'];
	$phone              = $this->_request['phone'];
	$address            = $this->_request['address'];
}
?>
<div class="row page-titles">
    <div class="col-sm-5 align-self-center"><h3 class="text-primary">My Account</h3></div>
    <div class="col-sm-7 align-self-center">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Settings</li>
            <li class="breadcrumb-item active">My Account</li>
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
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-title">
                            <h4>Personal Info</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Full Name *</label>
                                <input type="text" name="fullname" value="<?php echo $fullname;?>" class="form-control" maxlength="60" />
                            </div>

                            <div class="form-group">
                                <label>Email *</label>
                                <input type="text" name="email" value="<?php echo $email;?>" class="form-control" placeholder="" autocomplete="off" maxlength="50">
                            </div>

                            <div class="form-group">
                                <label>Phone</label>
                                <input type="text" name="phone" value="<?php echo $phone;?>" class="form-control" placeholder="">
                            </div>

                            <div class="form-group">
                                <label>Address</label>
                                <textarea name="address" class="form-control"><?php echo $address;?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <button type="button" name="Back" value="Back" onclick="location.href='index.php?pageType=<?php echo $this->_request['pageType'];?>&dtls=<?php echo $this->_request['dtls'];?>'" class="btn btn-default m-r-15">Back</button>

                            <input type="hidden" name="SourceForm" value="updateMyAccount" />
                            <button type="submit" name="Save" value="Save" class="btn btn-info login_btn">Save</button>

                            <button type="button" name="Cancel" value="Close" onclick="location.href='<?php echo SITE_ADMIN_PATH;?>'" class="btn btn-default m-l-15">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>