<!DOCTYPE html>
<html lang="en">

<head>
    <!--[if IE 7]> <html class="ie7"> <![endif]-->
    <!--[if IE 8]> <html class="ie8"> <![endif]-->
    <!--[if IE 9]> <html class="ie9"> <![endif]-->
    
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo STYLE_FILES_SRC;?>/images/favicon.ico" sizes="16x16">
    <title><?php echo SITE_NAME;?> :: Admin Console</title>
    <!-- Bootstrap Core CSS -->
    <link rel="stylesheet" href="<?php echo ADMIN_TMPL_PATH;?>/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo ADMIN_TMPL_PATH;?>/css/helper.css">
    <link rel="stylesheet" href="<?php echo ADMIN_TMPL_PATH;?>/css/style.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:** -->
    <!--[if lt IE 9]>
        <script src="https:**oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https:**oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body class="fix-header fix-sidebar loginbody">
    <!-- Preloader - style you can find in spinners.css -->
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
			<circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
    </div>
    <!-- Main wrapper  -->
    <div id="main-wrapper">
        
        <div class="unix-login">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-md-4 col-sm-6">
                        <div class="login-content card" role="main" id="login_main">
                            <?php
                            if($this->_request['action'] == 'forgot-password') {
                                if($this->_request['pk'] && $data['verify'])
                                    include("reset-password.php");
                                else{
                                    include("forgot-password.php");
                                }
                            }
                            else {
                                ?>
                                <div class="login-form" id="login">
                                    <h4>Login</h4>
                                    <form name="login" method="post" action="">
                                        <?php if($_SESSION['PROTECT']<=10) {?>
                                            <div class="form-group">
                                                <label>Username</label>
                                                <input type="text" name="LoginName" class="form-control" placeholder="" autocomplete="off">
                                            </div>
                                            <div class="form-group">
                                                <label>Password</label>
                                                <input type="password" name="LoginPass" class="form-control" placeholder="" autocomplete="off">
                                            </div>
                                            <div class="checkbox">
                                                
                                                <label class="pull-right"><a href="index.php?action=forgot-password">Forgot Password?</a></label>
                                            </div>
                                            
                                            <button type="submit" name="CheckLogin" value="Login" class="btn btn-info btn-flat m-b-30 m-t-30 login_btn">Sign in</button>
                                        <?php }?>
                                            <span class="warning_login">
                                                <?php echo ($data['message'])? '<div class="alert alert-danger">'.$data['message'].'</div>':'';?>
                                            </span>
                                    </form>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- End Wrapper -->
    <!-- All Jquery -->
    <script src="<?php echo ADMIN_TMPL_PATH;?>/js/lib/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="<?php echo ADMIN_TMPL_PATH;?>/js/lib/popper.min.js"></script>
    <script src="<?php echo ADMIN_TMPL_PATH;?>/js/lib/bootstrap.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="<?php echo ADMIN_TMPL_PATH;?>/js/lib/jquery.slimscroll.js"></script>
    <!--Menu sidebar -->
    <script src="<?php echo ADMIN_TMPL_PATH;?>/js/lib/sidebarmenu.js"></script>
    <!--stickey kit -->
    <script src="<?php echo ADMIN_TMPL_PATH;?>/js/lib/sticky-kit.min.js"></script>
    <!--Custom JavaScript -->
    <script src="<?php echo ADMIN_TMPL_PATH;?>/js/scripts.js"></script>
    <!--<script src="js/custom.min.js"></script>-->

</body>

</html>