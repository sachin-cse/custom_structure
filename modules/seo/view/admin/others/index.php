<?php
defined('BASE') OR exit('No direct script access allowed.');
if($data['others']) {
    
    $seoData                = $data['others']['seoData'];
    $googleAnalytics        = $data['others']['googleAnalytics'];
    $tagManager             = $data['others']['tagManager'];
    $tagManagerNoscript     = $data['others']['tagManagerNoscript'];
}
else {
    
    $seoData                = $this->_request['seoData'];
    $googleAnalytics        = $this->_request['googleAnalytics'];
    $tagManager             = $this->_request['tagManager'];
    $tagManagerNoscript     = $this->_request['tagManagerNoscript'];
}
?>
<div class="container-fluid">
    <?php
    if($data['act']['message'])
        echo ($data['act']['type'] == 1)? '<div class="alert alert-success">'.$data['act']['message'].'</div>':'<div class="alert alert-danger">'.$data['act']['message'].'</div>';
    ?>
    
    <div>
        <form name="modifycontent" action="" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-sm-8 contentL">
                    <div class="card">
                        <div class="card-body">
                            
                            <div class="form-group">
                                <label>Google Tag Manager (js)</label>
                                <textarea name="tagManager" class="form-control"><?php echo $tagManager;?></textarea>
                            </div>
                            
                            <hr>
                            <div class="form-group">
                                <label>Google Tag Manager (noscript)</label>
                                <textarea name="tagManagerNoscript" class="form-control"><?php echo $tagManagerNoscript;?></textarea>
                            </div>
                            
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label>SEO Data</label>
                                <div class="alert alert-info">Anything, which will appear inside <strong>&lt;head&gt;</strong> tag. </div>
                                <textarea name="seoData" class="form-control"><?php echo $seoData;?></textarea>
                            </div>
                        </div>
                    </div>
                            
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Google Analytics</label>
                                <textarea name="googleAnalytics" class="form-control"><?php echo $googleAnalytics;?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-sm-4 contentS">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Upload sitemap.xml</label>
                                <input type="file" name="SiteMapFile" class="form-control">
                                <?php if(file_exists('../sitemap.xml')) echo '<br><span class="alert alert-info"><strong>sitemap.xml</strong> file exists</span>'; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Upload robots.txt</label>
                                <input type="file" name="RobotFile" class="form-control">
                                <?php if(file_exists('../robots.txt')) echo '<br><span class="alert alert-info"><strong>robots.txt</strong> file exists</span>'; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <input type="hidden" name="SourceForm" value="addEditOther" />
                            <button type="submit" name="Save" value="Save" class="btn btn-info login_btn">Save</button>

                            <button type="button" name="Cancel" value="Close" onclick="location.href='<?php echo SITE_ADMIN_PATH;?>'" class="btn btn-default m-l-15">Close</button>   
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>