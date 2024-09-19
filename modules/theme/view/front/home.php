<?php defined('BASE') OR exit('No direct script access allowed');

if($data['pageContent'][0]){

    $contentImg = '';
    if($data['pageContent'][0]['ImageName'] && file_exists(MEDIA_FILES_ROOT.DS.'content'.DS.'thumb'.DS.$data['pageContent'][0]['ImageName'])) {
        $contentImg = '<figure class="sk_img_right">
                            <img src="'.MEDIA_FILES_SRC.DS.'content'.DS.'thumb'.DS.$data['pageContent'][0]['ImageName'].'" alt="'.$data['pageContent'][0]['contentHeading'].'" />
                        </figure>';
    }

    if(($data['pageContent'][0]['displayHeading'] == 'Y' && $data['pageContent'][0]['contentHeading']) || $data['pageContent'][0]['subHeading'] || $data['pageContent'][0]['contentDescription'] || $contentImg){
        echo '<section class="section">
                <div class="container">
                    <div class="sk_content_wrap">
                        <div class="sk_content">
                            '.$contentImg.'<div class="editor_text">';
                                if($data['pageContent'][0]['displayHeading'] == 'Y' && $data['pageContent'][0]['contentHeading'])
                                    echo '<h1 class="heading">'.headingModify($data['pageContent'][0]['contentHeading'], 2).'</h1>';
                                if($data['pageContent'][0]['subHeading'])
                                    echo '<h2 class="subheading">'.$data['pageContent'][0]['subHeading'].'</h2>';
                                if($data['pageContent'][0]['contentDescription'])
                                    echo $data['pageContent'][0]['contentDescription'];
                        echo '</div>
                            <div class="btn_group btn_center"><a href="'.SITE_LOC_PATH.'/about-us/" class="btn">Read More</a></div>
                        </div>
                    </div>
                </div>
            </section>';
    }
}
?>

<section class="section skewSection">
    <div class="container">
        <div class="image_section">
            <figure class="image_section_inner" style="background-image: url(<?php echo STYLE_FILES_SRC;?>/images/sample/homeabt_img.jpg)"></figure>
        </div>
        <div class="content_section">
            <div class="content_section_inner"></div>
            <h2 class="heading w">Why <span>Choose Us</span></h2>
            <ul class="ul row">
                <li class="col-sm-6">
                    <div class="speciality_box">
                        <figure class="speciality_img">
                            <img class="lazy" src="<?php echo STYLE_FILES_SRC;?>/images/blank.png" data-src="<?php echo STYLE_FILES_SRC;?>/images/sample/why_choose_img1.png" alt="">
                        </figure>
                        <div class="speciality_text">
                            <h2 class="subheading"><span>Expert</span> Team</h2>
                            <div class="editor_text">
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec tempus enim id lectus ultricies.</p>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="col-sm-6">
                    <div class="speciality_box">
                        <div class="speciality_img">
                            <img class="lazy" src="<?php echo STYLE_FILES_SRC;?>/images/blank.png" data-src="<?php echo STYLE_FILES_SRC;?>/images/sample/why_choose_img2.png" alt="">
                        </div>
                        <div class="speciality_text">
                            <h2 class="subheading"><span>Expert</span> Team</h2>
                            <div class="editor_text">
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec tempus enim id lectus ultricies.</p>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="col-sm-6">
                    <div class="speciality_box">
                        <div class="speciality_img">
                            <img class="lazy" src="<?php echo STYLE_FILES_SRC;?>/images/blank.png" data-src="<?php echo STYLE_FILES_SRC;?>/images/sample/why_choose_img3.png" alt="">
                        </div>
                        <div class="speciality_text">
                            <h2 class="subheading"><span>Expert</span> Team</h2>
                            <div class="editor_text">
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec tempus enim id lectus ultricies.</p>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="col-sm-6">
                    <div class="speciality_box">
                        <div class="speciality_img">
                            <img class="lazy" src="<?php echo STYLE_FILES_SRC;?>/images/blank.png" data-src="<?php echo STYLE_FILES_SRC;?>/images/sample/why_choose_img4.png" alt="">
                        </div>
                        <div class="speciality_text">
                            <h2 class="subheading"><span>Expert</span> Team</h2>
                            <div class="editor_text">
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec tempus enim id lectus ultricies.</p>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</section>

<?php
$this->hook('service', 'showcase', array('css' => 'service_list sk_shadow_full', 'col' => 'col-sm-4 col-xs-6', 'slider' => true));

if($data['pageContent'][1]){

    if($data['pageContent'][1]['ImageName'] && file_exists(MEDIA_FILES_ROOT.DS.'content'.DS.'thumb'.DS.$data['pageContent'][1]['ImageName']))
        $contentImg = MEDIA_FILES_SRC.DS.'content'.DS.'thumb'.DS.$data['pageContent'][1]['ImageName'];
    else
        $contentImg = STYLE_FILES_SRC.DS.'images'.DS.'stayontop_bg.jpg';

    if(($data['pageContent'][1]['displayHeading'] == 'Y' && $data['pageContent'][1]['contentHeading']) || $data['pageContent'][1]['subHeading'] || $data['pageContent'][1]['contentDescription']){
        echo '<section class="section stay_on_top" style="background-image:url('.$contentImg.');">
                <div class="container">
                    <div class="sk_content_wrap">
                        <div class="sk_content">
                            <div class="editor_text">';
                                if($data['pageContent'][1]['displayHeading'] == 'Y' && $data['pageContent'][1]['contentHeading'])
                                    echo '<h1 class="heading text-center w noborder">'.$data['pageContent'][1]['contentHeading'].'</h1>';
                                if($data['pageContent'][1]['subHeading'])
                                    echo '<h2 class="subheading">'.$data['pageContent'][1]['subHeading'].'</h2>';
                                if($data['pageContent'][1]['contentDescription'])
                                    echo $data['pageContent'][1]['contentDescription'];
                        echo '</div>
                        </div>
                    </div>
                </div>
            </section>';
    }
}
?>

<section class="section skewSection hook_contact">
    <div class="container">
        <div class="image_section">
            
            <figure class="image_section_inner" style="background-image: url(<?php echo STYLE_FILES_SRC;?>/images/sample/homeabt_img.jpg)"></figure>
        </div>
        <div class="content_section">
            <div class="content_section_inner"></div>
            <h2 class="heading w">Get <span>in Touch</span></h2>
            <div class="about_sec mb30">
                <div class="sk_content">
                    <div class="editor_text">
                        <p>For more information, please call <?php echo SITE_PHONE;?> or fill out the form below and we will get back to you as soon as possible.</p>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
            
            <?php  $this->hook('communication', 'form');?>
        </div>
    </div>
</section>

<?php $this->hook('testimonial', 'showcase', array('wrapcss' => 'hook_testimonial', 'css' => 'testimonial_list', 'col' => 'col-sm-4'));

$this->hook('client', 'showcase', array('css'=>'sk_list sk_shadow_full', 'col' => 'col-sm-4 col-xs-6', 'slider' =>true));
?>

