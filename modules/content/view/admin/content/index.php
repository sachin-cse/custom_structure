<?php
defined('BASE') OR exit('No direct script access allowed.');
if($this->_request['editid']){

    if($data['contentList'] || $this->session->read('searchText') || $this->session->read('searchStatus'))
        include("list.php"); 		// Content List
    else
        include("article.php");
} else {
    echo '<div class="row page-titles">
        <div class="col-sm-5 align-self-center"><h3 class="text-primary">'.$data['pageData']['categoryName'].'</h3></div>
        <div class="col-sm-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Content</li>
                <li class="breadcrumb-item active">'.$data['pageData']['categoryName'].'</li>
            </ol>
        </div>
    </div>
    
    <div class="container-fluid">
        <div class="norecord alert alert-warning text-center p-t-30 p-b-35">
            <div class="m-b-20">There is no page linked with this module.</div>
            <a href="'.SITE_ADMIN_PATH.'/index.php?pageType=sitepage&dtls=pages&dtaction=new&moduleId=100" class="btn btn-info btn-sm m-r-10">Add Page</a> or
            <a href="'.SITE_ADMIN_PATH.'/index.php?pageType=sitepage&dtls=pages&moduleId=101" class="btn btn-info btn-sm m-l-10">View Pages</a>
        </div>
    </div>';
}
?>