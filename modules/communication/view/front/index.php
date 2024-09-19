<?php
defined('BASE') OR exit('No direct script access allowed');

include("content.php");

echo '<div class="sk_comm mt30"><i class="fa fa-share-alt"></i> ';
/* $this->loadView('seo', 'social.php', $data['socialLinks']); */
$this->loadView('template', 'inc/social.php', $data['socialLinks']);
echo '</div>';

if($data['settings']['isForm'])
    include 'contact-form.php';

if($data['settings']['isMap']) {
    echo '<div class="sk_map mt30">
            <iframe title="Google Map" scrolling="no" src="https://maps.google.it/maps?q='.$data['settings']['mapAddress'].'&output=embed"></iframe>
        </div>';
}
?>