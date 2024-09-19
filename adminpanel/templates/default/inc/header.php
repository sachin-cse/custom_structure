<!DOCTYPE html>
<html lang="en">
<head>
    <?php echo $this->essentialHeader;?>
    <!--[if IE 7]> <html class="ie7"> <![endif]-->
    <!--[if IE 8]> <html class="ie8"> <![endif]-->
    <!--[if IE 9]> <html class="ie9"> <![endif]-->
    
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="Robots" content="noindex, nofollow"/>
    
    <!-- Favicon icon -->
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo STYLE_FILES_SRC;?>/images/favicon.ico" sizes="16x16">
    <title></title>
    <!-- Bootstrap Core CSS -->
    <link rel="stylesheet" href="<?php echo ADMIN_TMPL_PATH;?>/css/bootstrap.min.css">
    <!-- Custom CSS -->

    <link rel="stylesheet" href="<?php echo ADMIN_TMPL_PATH;?>/css/jquery-ui-1.12.1.css">
    <link rel="stylesheet" href="<?php echo ADMIN_TMPL_PATH;?>/css/jquery.mCustomScrollbar.css">
    <link rel="stylesheet" href="<?php echo ADMIN_TMPL_PATH;?>/css/helper.css">
    <link rel="stylesheet" href="<?php echo ADMIN_TMPL_PATH;?>/css/toastr.min.css">
    <link rel="stylesheet" href="<?php echo ADMIN_TMPL_PATH;?>/css/sweetalert.css">
    <link rel="stylesheet" href="<?php echo ADMIN_TMPL_PATH;?>/css/style.css">
    <link rel="stylesheet" href="<?php echo ADMIN_TMPL_PATH;?>/plugins/ckeditor5/ckeditor5.css">

    <style>
        .ck-content 
                {
                    font-family: 'Lato';
                    line-height: 1.6;
                    word-break: break-word;
                }
                .ck-editor__editable {
                    min-height: 150px;
                    /* max-height: 150px; */
                }
                .ck-powered-by-balloon{
                    display: none !important;
                }
    </style>
    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:** -->
    <!--[if lt IE 9]>
        <script src="https:**oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https:**oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <script src="<?php echo ADMIN_TMPL_PATH;?>/js/lib/jquery.min.js"></script>
    <script src="<?php echo ADMIN_TMPL_PATH;?>/js/lib/jquery-ui.min.js"></script>
    <?php if($this->_request['pageType'] == 'theme' && $this->_request['dtls'] == 'menu') {?>
        <script src="<?php echo ADMIN_TMPL_PATH;?>/js/lib/jquery.ui.nestedSortable.js"></script>
    <?php }?>
    <script src="<?php echo ADMIN_TMPL_PATH;?>/js/lib/date.format.js"></script>

    <script>
        var BASE_URL = '<?php echo SITE_ADMIN_PATH ?>'
    </script>
    
    <script type="text/javascript">
        $(document).ready(function(){
            if($('.table tbody.swap').length || $('ul.swap').length) {
                $(".table tbody.swap, ul.swap").sortable({
                    opacity: 0.6,
                    cursor: 'move',
                    update: function() {
                                var order = $(this).sortable("serialize") + '&ajx_action=swap';
                                $.post('<?php echo SITE_ADMIN_PATH."/index.php?pageType=".$this->_request['pageType']."&dtls=".$this->_request['dtls']."&moduleId=".$this->_request['moduleId'];?>', order, function(res){

                                    if(res.type == 1)
                                        window.location.reload();
                                });
                            }
                });
            }
        });
    </script>
    
    <!-- <script type="text/javascript" src="https://cloud.tinymce.com/stable/tinymce.min.js"></script> -->
    <!-- <script>
        tinymce.init({
            selector: "textarea.editor",
            theme: "modern",
            branding: false, 
            /*width: 500,*/
            height: 300,
            plugins: [
                 "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                 "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                 "save table contextmenu directionality emoticons template paste textcolor"
           ],
           /*content_css: "<?php echo STYLE_FILES_SRC;?>/css/style.css",*/
           toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | media fullpage | forecolor backcolor emoticons", 
           style_formats: [
                {title: 'Bold text', inline: 'b'},
                {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
                {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
                {title: 'Example 1', inline: 'span', classes: 'example1'},
                {title: 'Example 2', inline: 'span', classes: 'example2'},
                {title: 'Table styles'},
                {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
            ],
            // enable title field in the Image dialog
			image_title: true, 
			// enable automatic uploads of images represented by blob or data URIs
			automatic_uploads: false,
			// add custom filepicker only to Image dialog
			file_picker_types: 'image',
			images_upload_url: '<?php echo SITE_ADMIN_PATH.'/?ajx_action=uploadMedia';?>',		
			images_upload_base_path: "<?php echo SITE_LOC_PATH;?>",
			relative_urls : false,
			remove_script_host : false,
			document_base_url : "/",
			convert_urls : true
        }); 
        
        tinymce.init({
            selector: "textarea.editor_small",
            theme: "modern",
            branding: false, 
            contextmenu: false,
            /*width: 500,*/
            height: 100,
            plugins: [
                 "code",
           ],
           /*content_css: "<?php echo STYLE_FILES_SRC;?>/css/style.css",*/
           
           style_formats: [
                {title: 'Bold text', inline: 'b'},
                {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
                {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
                {title: 'Example 1', inline: 'span', classes: 'example1'},
                {title: 'Example 2', inline: 'span', classes: 'example2'},
                {title: 'Table styles'},
                {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
            ]
        });
    </script>  -->
    <style>.mce-notification {display:none!important;}</style>
</head>

<body class="fix-header fix-sidebar fix-footer">
    <!--[if lt IE 7]><p class=chromeframe>Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</p><![endif]-->
    <!-- Preloader - style you can find in spinners.css -->
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50"><circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
    </div>
    
    <!-- Main wrapper  -->
    <div id="main-wrapper">
        <!-- header header  -->
        <div class="header">
            <nav class="navbar top-navbar navbar-expand-md navbar-light">
                <!-- Logo -->
                <div class="navbar-header logo">
                    <a class="navbar-brand" href="index.php">
                        <?php
                        echo '<img src="'.STYLE_FILES_SRC.'/images/logo.png" alt="'.SITE_NAME.'" title="'.SITE_NAME.'" class="logoImg" />';
                        echo '<img src="'.STYLE_FILES_SRC.'/images/logo_icon.png" alt="'.SITE_NAME.'" title="'.SITE_NAME.'" class="logoIcon" />';
                        ?>
                    </a>
                </div>
                <!-- End Logo -->
                <div class="navbar-collapse">
                    <!-- toggle and nav items -->
                    <ul class="navbar-nav mr-auto mt-md-0">
                        <!-- This is  -->
                        <li class="nav-item"> <a class="nav-link nav-toggler hidden-md-up text-muted  " href="javascript:void(0)"><i class="mdi mdi-menu"></i></a> </li>
                        <li class="nav-item m-l-10"> <a class="nav-link sidebartoggler hidden-sm-down text-muted  " href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
                    </ul>
                    <!-- User profile and search -->
                    <ul class="navbar-nav my-lg-0">

                        <?php /*<!-- Search -->
                        <li class="nav-item hidden-sm-down search-box"> <a class="nav-link hidden-sm-down text-muted  " href="javascript:void(0)"><i class="ti-search"></i></a>
                            <form class="app-search">
                                <input type="text" class="form-control" placeholder="Search here"> <a class="srh-btn"><i class="ti-close"></i></a> </form>
                        </li>
                        <!-- Comment -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted text-muted  " href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="fa fa-bell"></i>
								<div class="notify"> <span class="heartbit"></span> <span class="point"></span> </div>
							</a>
                            <div class="dropdown-menu dropdown-menu-right mailbox animated zoomIn">
                                <ul>
                                    <li>
                                        <div class="drop-title">Notifications</div>
                                    </li>
                                    <li>
                                        <div class="message-center">
                                            <!-- Message -->
                                            <a href="#">
                                                <div class="btn btn-danger btn-circle m-r-10"><i class="fa fa-link"></i></div>
                                                <div class="mail-contnet">
                                                    <h5>This is title</h5> <span class="mail-desc">Just see the my new admin!</span> <span class="time">9:30 AM</span>
                                                </div>
                                            </a>
                                            <!-- Message -->
                                            <a href="#">
                                                <div class="btn btn-success btn-circle m-r-10"><i class="ti-calendar"></i></div>
                                                <div class="mail-contnet">
                                                    <h5>This is another title</h5> <span class="mail-desc">Just a reminder that you have event</span> <span class="time">9:10 AM</span>
                                                </div>
                                            </a>
                                            <!-- Message -->
                                            <a href="#">
                                                <div class="btn btn-info btn-circle m-r-10"><i class="ti-settings"></i></div>
                                                <div class="mail-contnet">
                                                    <h5>This is title</h5> <span class="mail-desc">You can customize this template as you want</span> <span class="time">9:08 AM</span>
                                                </div>
                                            </a>
                                            <!-- Message -->
                                            <a href="#">
                                                <div class="btn btn-info btn-circle m-r-10"><i class="ti-user"></i></div>
                                                <div class="mail-contnet">
                                                    <h5>This is another title</h5> <span class="mail-desc">Just see the my admin!</span> <span class="time">9:02 AM</span>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <a class="nav-link text-center" href="javascript:void(0);"> <strong>Check all notifications</strong> <i class="fa fa-angle-right"></i> </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <!-- End Comment -->
                        <!-- Messages -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted  " href="#" id="2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="fa fa-envelope"></i>
								<div class="notify"> <span class="heartbit"></span> <span class="point"></span> </div>
							</a>
                            <div class="dropdown-menu dropdown-menu-right mailbox animated zoomIn" aria-labelledby="2">
                                <ul>
                                    <li>
                                        <div class="drop-title">You have 4 new messages</div>
                                    </li>
                                    <li>
                                        <div class="message-center">
                                            <!-- Message -->
                                            <a href="#">
                                                <div class="user-img"> <img src="images/users/5.jpg" alt="user" class="img-circle"> <span class="profile-status online pull-right"></span> </div>
                                                <div class="mail-contnet">
                                                    <h5>Michael Qin</h5> <span class="mail-desc">Just see the my admin!</span> <span class="time">9:30 AM</span>
                                                </div>
                                            </a>
                                            <!-- Message -->
                                            <a href="#">
                                                <div class="user-img"> <img src="images/users/2.jpg" alt="user" class="img-circle"> <span class="profile-status busy pull-right"></span> </div>
                                                <div class="mail-contnet">
                                                    <h5>John Doe</h5> <span class="mail-desc">I've sung a song! See you at</span> <span class="time">9:10 AM</span>
                                                </div>
                                            </a>
                                            <!-- Message -->
                                            <a href="#">
                                                <div class="user-img"> <img src="images/users/3.jpg" alt="user" class="img-circle"> <span class="profile-status away pull-right"></span> </div>
                                                <div class="mail-contnet">
                                                    <h5>Mr. John</h5> <span class="mail-desc">I am a singer!</span> <span class="time">9:08 AM</span>
                                                </div>
                                            </a>
                                            <!-- Message -->
                                            <a href="#">
                                                <div class="user-img"> <img src="images/users/4.jpg" alt="user" class="img-circle"> <span class="profile-status offline pull-right"></span> </div>
                                                <div class="mail-contnet">
                                                    <h5>Michael Qin</h5> <span class="mail-desc">Just see the my admin!</span> <span class="time">9:02 AM</span>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <a class="nav-link text-center" href="javascript:void(0);"> <strong>See all e-Mails</strong> <i class="fa fa-angle-right"></i> </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <!-- End Messages -->*/?>
                        <li class="visitweb"><a href="<?php echo SITE_LOC_PATH;?>" target="_blank"><i class="fa fa-globe"></i> Visit Website</a></li>
                        <!-- Profile -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted  " href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="<?php echo ADMIN_TMPL_PATH;?>/images/profileImage.png" alt="user" class="profile-pic" /></a>
                            <div class="dropdown-menu dropdown-menu-right animated zoomIn">
                                <ul class="dropdown-user">
                                    <li><a href="index.php?pageType=modules&dtls=settings&dtaction=my-account"><i class="ti-user"></i> My Account</a></li>
                                    <li><a href="index.php?pageType=modules&dtls=settings&dtaction=configuration"><i class="ti-settings"></i> Configuration</a></li>
                                    <?php 
                                    if($this->session->read('UTYPE') == "A") {
                                        ?>
                                    <li><a href="index.php?pageType=modules&dtls=settings&dtaction=captcha"><i class="fa fa-bug"></i> Google Recaptcha</a></li>
                                    <li><a href="index.php?pageType=modules&dtls=settings&dtaction=share-script"><i class="fa fa-share-alt"></i> Share Script</a></li>
                                    <li><a href="index.php?pageType=modules&dtls=settings&dtaction=cache"><i class="fa fa-floppy-o"></i> Site Cache</a></li>
                                        <?php
                                    }
                                    ?>

                                    <li><a href="index.php?pageType=modules&dtls=settings&dtaction=change-password"><i class="fa fa-key"></i> Change Password</a></li>
                                    <li><a href="index.php?pageType=logout"><i class="fa fa-power-off"></i> Logout</a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        <!-- End header header -->
        
        <?php include("leftmenu.php");?>
        
        <!-- Page wrapper  -->
        <div class="page-wrapper">
            <?php echo $this->breadcrumb;?>