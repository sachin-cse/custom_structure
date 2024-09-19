<?php defined('BASE') OR exit('No direct script access allowed');?>
<!DOCTYPE HTML>
<html lang="en">
<head>
    <link rel="prefetch" as="style" onload="this.rel = 'stylesheet'" href="https://fonts.googleapis.com/css?family=Roboto:300,400,400i,500,700">
    <style>
body.clicked{position:fixed;width:100%;height:100%}body.clicked:before{background:#fff;z-index:999}body.clicked:after{width:100px;height:100px;margin:-50px 0 0 -50px;z-index:999}.loader.clicked{position:fixed;top:0;left:0;right:0;bottom:0;z-index:1000}.loader.clicked:before{-webkit-border-radius:0;border-radius:0;background:rgba(0,0,0,.8)}.loader.clicked:after{width:70px;height:70px;margin:-35px 0 0 -35px;border-color:#fff transparent #fff #fff}.noloader.loader.clicked:after{display:none}.loader.clicked>div{position:absolute;left:50%;top:50%;-webkit-transform:translate(-50%,-50%);transform:translate(-50%,-50%);font-size:18px;line-height:35px;color:#fff;z-index:1;margin:40px 0 0;padding:30px;width:100%;text-align:center}.loader.clicked>div span{text-transform:uppercase;margin-top:20px}.clicked{position:relative;pointer-events:none}.clicked:after,.clicked:before{position:absolute;content:"";display:block;z-index:1}.clicked:before{top:0;left:0;right:0;bottom:0;background:rgba(255,255,255,.8);-webkit-border-radius:0;border-radius:0;opacity:1}.btn.clicked:before,[type=submit].clicked:before,[type=reset].clicked:before,button.clicked:before{-webkit-border-radius:3px;border-radius:3px;top:-1px;left:-1px;right:-1px;bottom:-1px}.clicked:after{top:50%;left:50%;margin:-12px 0 0 -12px;width:24px;height:24px;border:3px solid #3799FE;border-right-color:transparent;-webkit-border-radius:50%;border-radius:50%;-webkit-animation-duration:.75s;-moz-animation-duration:.75s;animation-duration:.75s;-webkit-animation-iteration-count:infinite;-moz-animation-iteration-count:infinite;animation-iteration-count:infinite;-webkit-animation-name:rotate-forever;-moz-animation-name:rotate-forever;animation-name:rotate-forever;-webkit-animation-timing-function:linear;-moz-animation-timing-function:linear;animation-timing-function:linear}@-webkit-keyframes rotate-forever{0%{-webkit-transform:rotate(0);-moz-transform:rotate(0);-ms-transform:rotate(0);-o-transform:rotate(0);transform:rotate(0)}100%{-webkit-transform:rotate(360deg);-moz-transform:rotate(360deg);-ms-transform:rotate(360deg);-o-transform:rotate(360deg);transform:rotate(360deg)}}@-moz-keyframes rotate-forever{0%{-webkit-transform:rotate(0);-moz-transform:rotate(0);-ms-transform:rotate(0);-o-transform:rotate(0);transform:rotate(0)}100%{-webkit-transform:rotate(360deg);-moz-transform:rotate(360deg);-ms-transform:rotate(360deg);-o-transform:rotate(360deg);transform:rotate(360deg)}}@keyframes rotate-forever{0%{-webkit-transform:rotate(0);-moz-transform:rotate(0);-ms-transform:rotate(0);-o-transform:rotate(0);transform:rotate(0)}100%{-webkit-transform:rotate(360deg);-moz-transform:rotate(360deg);-ms-transform:rotate(360deg);-o-transform:rotate(360deg);transform:rotate(360deg)}}

/* .common_banner,.container,.header_main,.nav_wrapper{position:relative}body{margin:0;padding:0}*,:after,:before{box-sizing:border-box}.responsive_nav,.scrollup{display:none}.header_main{top:0;left:0;right:0;z-index:5;border-top:5px solid #262525;border-bottom:5px solid #22942a;height:162px}.homebanner{height:450px;min-height:450px;overflow:hidden;background:#ccc}.container{width:1170px;padding:0 15px;margin:0 auto}.logo{width:128px;float:left;padding:10px 0}.hright{float:right;text-align:right;height:152px}.htop{color:#fff;background:#171817;padding:0 0 5px;float:right;height:35px}.hmiddle{padding:20px 0;clear:both;height:76px}.nav_wrapper{float:left;background:#22942a;padding:5px 0 0 30px;height:41px}
@media only screen and (max-width: 991px) {.homebanner{height: auto;min-height:inherit}} */
    </style>
    
    <?php  echo $this->essentialHeader(); 
    ?>

    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-5CP7PD');</script>
    <!-- End Google Tag Manager -->

    <link type="image/x-icon" rel="shortcut icon" href="<?php echo STYLE_FILES_SRC;?>/images/favicon.ico">
    <!--[if IE 7]> <html class="ie7"> <![endif]-->
    <!--[if IE 8]> <html class="ie8"> <![endif]-->
    <!--[if IE 9]> <html class="ie9"> <![endif]--> 
    
    <link rel="preload" as="image" href='<?php echo STYLE_FILES_SRC;?>/images/logo.png'>

    <meta name="p:domain_verify" content="f5c42235a68089db12bff22281ebec60"/>

    <script defer type="text/javascript">
        var SITE_LOC_PATH = '<?php echo SITE_LOC_PATH;?>/';
    </script>
</head>

<body class="clicked"><?php echo $this->tm_ns;?>

    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5CP7PD"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) --> 

    <div class="bodyOverlay"></div>
    <div class="responsive_nav">
        <div class="htop-responsive">
            <?php $this->loadView('template', 'inc/social.php', $data['socialLinks']);?>   
            <ul class="ml-auto hinfo d-flex align-items-center"> 
                <li> 
                    <a class="hmail" href="mailto:<?php echo SITE_EMAIL;?>"><i class="fa fa-envelope"></i></a> 
                </li>
                <li>  
                    <a class="hphone" href="tel:<?php echo SITE_PHONE;?>">
                        <i class="fa fa-phone"></i>
                    </a>  
                </li>
            </ul>
        </div>
    </div>
    <a class="scrollup" href="javascript:void(0);" aria-label="Scroll to top"><i class="fa fa-arrow-up" aria-hidden="true"></i></a>
    <div class="floating-btn">
        <a href="<?php echo SITE_LOC_PATH; ?>/contact-us" class="btn" title="Contact Us">Make an Enquiry</a>
    </div>
    <header class="mainHeader"> 
        <section class="header_main">
            <div class="htop">
                <div class="container">
                    <div class="d-flex align-items-center">
                        <?php $this->loadView('template', 'inc/social.php', $data['socialLinks']);?>   
                        <ul class="ml-auto hinfo d-flex align-items-center"> 
                            <li>
                                <div> 
                                    <a href="mailto:<?php echo SITE_EMAIL;?>"><i class="fa fa-envelope" id="mail"></i><?php echo SITE_EMAIL;?></a>
                                </div>
                            </li>
                            <li>
                                <!-- <a href="http://www.eclickprojects.com/perth-carpet-master/code/" class="brouch">Request a brochure</a> -->
                                <div> 
                                    <a class="callback" href="tel:<?php echo SITE_PHONE;?>">
                                        <strong>call</strong> 
                                        <?php echo SITE_PHONE;?>
                                    </a> 
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="hbottom">
                <div class="container">
                    <div class="d-flex align-items-center">
                        <div class="logo">
                            <a aria-label="<?php echo SITE_NAME;?>" href="<?php echo SITE_LOC_PATH.'/';?>" style="background-image:url(<?php echo STYLE_FILES_SRC;?>/images/lozgo.png);"></a>
                        </div>
                        <span class="responsive_btn"><span></span></span>
                        <div class="nav_wrapper">
                            <nav class="nav_menu">
                                <?php $this->hook('theme', 'nav', array('permalink'=>'main-menu'));?>
                            </nav> 
                            
                            <?php if ($this->device->isMobile()) {?>
                                <a href="<?php echo SITE_LOC_PATH.'/';?>" class="homeBtn" aria-label="<?php echo SITE_NAME;?>"><i class="fa fa-home"></i></a>
                                <div class="widget_block">
                                    <div class="widget_links">
                                        <a href="tel:<?php echo SITE_PHONE;?>" class="wphone" aria-label="Phone"><i class="fa fa-phone"></i></a>
                                        <a href="mailto:<?php echo SITE_EMAIL;?>" class="wemail" aria-label="Email"><i class="fa fa-envelope"></i></a>
                                        <?php 
                                            $browser = strpos($_SERVER['HTTP_USER_AGENT'],'iPhone') || strstr($_SERVER['HTTP_USER_AGENT'],'iPod');
                                            if ($browser == true) {
                                            ?>
                                        <span class="wmap" data-href="http://maps.google.com/maps?q=<?php echo strip_tags(SITE_ADDRESS);?>"><i class="fa fa-globe"></i></span>
                                            <?php
                                            } else {
                                            ?>
                                        <span class="wmap" data-href="geo:<?php echo strip_tags(SITE_ADDRESS);?>?q=<?php echo strip_tags(SITE_ADDRESS);?>"><i class="fa fa-globe"></i></span>
                                            <?php
                                            }
                                            ?>
                                        <span class="wform"><i class="fa fa-th-list"></i></span>
                                    </div>
                                    <div class="widget_form">
                                        <?php $this->hook('communication', 'form');?>
                                    </div>
                                </div>
                            <?php }?>
                            
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="common_banner <?php echo (!isset($this->_request['pageType']))? 'homebanner':'';?>">
            <?php 
            if(!isset($this->_request['pageType'])) {
                if($data['homeSlider']) {
                    ?>
                    <div class="homeslider owl-carousel owl-theme">
                        <?php foreach($data['homeSlider'] as $slider) {
                        
                            if($slider['imageName'] && file_exists(MEDIA_FILES_ROOT.DS.'slider'.DS.'thumb'.DS.$slider['imageName'])) {
                                ?> 
                                <div class="item">
                                    <div class="bannerbox">
                                        <figure class="bannerimg">
                                            <img class="owl-lazy" src="<?php echo MEDIA_FILES_SRC.DS.'slider'.DS.'thumb'.DS.$slider['imageName'];?>" data-src="<?php echo MEDIA_FILES_SRC.DS.'slider'.DS.'thumb'.DS.$slider['imageName'];?>" alt="<?php echo $slider['sliderName'];?>">
                                        </figure>
                                        <?php
                                        if(($slider['displayHeading'] == 'Y' && $slider['sliderName']) || $slider['subHeading'] || $slider['sliderDescription'] || ($slider['redirectUrl'] && $slider['buttonName'])) {

                                            echo '<div class="bannertext">';
                                                echo ($slider['displayHeading'] == 'Y' && $slider['sliderName']) ? '<div class="heading">'.$slider['sliderName'].'</div>' : '';
                                                echo ($slider['subHeading']) ? '<div class="subheading">'.$slider['subHeading'].'</div>' : '';
                                                echo ($slider['sliderDescription']) ? '<p class="text-center">'.$slider['sliderDescription'].'</p>' : '';
                                                echo ($slider['redirectUrl'] && $slider['buttonName']) ? '<a href="'.$slider['redirectUrl'].'" target="'.$slider['redirectUrlTarget'].'" class="btn">'.$slider['buttonName'].'</a>' : '';
                                            echo '</div>';
                                        }
                                        ?>
                                    </div>
                                </div>
                            <?php }
                        }
                        ?>
                    </div>
                    <?php 
                }
            } elseif(isset($data['innerBanner']['src']) && $data['innerBanner']['src'] != '') {
                ?>
                <div class="innerbanner">
                    <div class="bannerbox">
                        <figure class="bannerimg">
                            <img class="lazy" src="<?php echo STYLE_FILES_SRC;?>/images/blank.png" data-src="<?php echo $data['innerBanner']['src'];?>" alt="<?php echo $data['innerBanner']['alt'];?>">
                        </figure>
                        <?php
                        if($data['innerBanner']['caption']) {

                            echo '<div class="bannertext">
                                    <div class="heading">'.$data['innerBanner']['caption'].'</div>
                                </div>';
                        }
                        ?>
                    </div>
                </div>
            <?php }?>
        </section>

        <?php echo ($this->_request['pageType']) ? $data['breadcrumb'] : ''; ?>
    </header>
    
    <!--MAIN CONTAINER START-->
    <main class="mainContainer">
        <?php if(isset($this->_request['pageType'])){?>
        <div class="section">
            <div class="container">
                <?php }?>