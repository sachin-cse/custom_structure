<div class="row page-titles">
    <div class="col-sm-5 align-self-center"><h3 class="text-primary">Change Password</h3></div>
    <div class="col-sm-7 align-self-center">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Settings</li>
            <li class="breadcrumb-item active">Change Password</li>
        </ol>
    </div>
</div>

<div class="unix-login">
    <div class="container-fluid">
        <?php
        if(isset($data['act']['message']))
            echo (isset($data['act']['type']) && $data['act']['type'] == 1)? '<div class="alert alert-success">'.$data['act']['message'].'</div>':'<div class="alert alert-danger">'.$data['act']['message'].'</div>';
        ?>
        <div class="row justify-content-center">
            <div class="col-sm-12">
                <div class="card">
                    <div class="login-form" id="login">
                        <form name="modifycontent" method="POST" action="">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Current Password *</label>
                                        <input type="password" name="CurrPassword" class="form-control" placeholder="" autocomplete="off">
                                    </div>
                                    <div class="form-group">
                                        <label>New Password *</label>
                                        <input type="password" name="NewPassword" class="form-control gen_pass" placeholder="" autocomplete="off">
                                    </div>
                                    <div class="form-group">
                                        <label>Retype New Password *</label>
                                        <input type="password" name="ReNewPassword" class="form-control gen_pass" placeholder="" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group text-center m-t-30">
                                        <a href="#" class="btn btn-info generate">Generate Password</a>
                                        <div class="new_pass" style="display:none;">
                                            <input type="text" name="genpass" value="" class="form-control m-t-20 m-b-20"/>
                                            Copy the Password and keep it in a secure place.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <button type="button" name="Back" value="Back" onclick="history.back(-1);" class="btn btn-default m-b-30 m-t-30 m-r-15">Back</button>
                                
                                <input type="hidden" name="SourceForm" value="changePassword" />
                                <button type="submit" name="Save" value="Save" class="btn btn-info m-b-30 m-t-30 login_btn">Save</button>

                                <button type="button" name="Cancel" value="Close" onclick="window.location.href='index.php'" class="btn btn-default m-b-30 m-t-30 m-l-15">Close</button>
                            </div>
                            
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>