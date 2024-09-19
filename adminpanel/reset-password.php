<div class="login-form" id="login">
    <h4>Reset Password</h4>
    <form name="formpassword" method="post" action="" id="formPassReminder">
        <div class="form-group">
            <label>New Password</label>
            <input type="password" name="password" value="" class="form-control" placeholder="" autocomplete="off">
        </div>
        <div class="form-group">
            <label>Re-type Password</label>
            <input type="password" name="cnfrm_password" value="" class="form-control" placeholder="" autocomplete="off">
        </div>
        
        <input type="hidden" name="SourceForm" value="resetPassword" />
        <input type="hidden" name="captcha" value="" />
        <button type="submit" name="Submit" value="Submit" class="btn btn-info btn-flat m-b-30 m-t-30 login_btn" onclick="return ForgotPasswordValidate()">Submit</button>
        <div class="register-link m-t-15 text-center">
            <p><a href="index.php"> Back to Login</a></p>
        </div>
        
        <?php 
        if($data['message'])
            echo ($data['type'])? '<div class="alert alert-success">'.$data['message'].'</div>':'<div class="alert alert-danger">'.$data['message'].'</div>';
        elseif($this->_request['pk'] && $data['verify']==0)
            echo '<div class="alert alert-danger">The reset password token is invalid.</div>';
        ?>
    </form>
</div>