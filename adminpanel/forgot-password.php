<script>
function ForgotPasswordValidate() {
	if(document.formpassword.Email.value=="") {
		alert("Please enter your email address.");
		document.formpassword.Email.focus();
		return false;
	}
	
	if(document.formpassword.Email.value!="") {
		if((/^[a-zA-Z0-9._-]+@([a-zA-Z0-9.-]+\.)+[a-zA-Z.]{2,5}$/).exec(document.formpassword.Email.value)==null && document.formpassword.Email.value.length>0) {
			alert("Email address is not valid.");
			document.formpassword.Email.focus();
			return false;
		}
	}
}
</script>

<div class="login-form" id="login">
    <h4>Password Recovery</h4>
    <form name="formpassword" method="post" action="" id="formPassReminder">
        <div class="form-group">
            <label>Email *</label>
            <input type="text" name="email" value="<?php echo $this->_request['email'];?>" class="form-control" placeholder="" autocomplete="off">
        </div>
        <div class="guide_text">Please enter your registered email address.</div>
        
        <input type="hidden" name="goto" value="<?php echo $goto?>"/>
        <input type="hidden" name="SourceForm" value="forgotPassword" />
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