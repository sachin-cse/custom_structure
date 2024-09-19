<?php
if($data['cache']) {
    
	$caching           = $data['cache']['caching'];
	$cacheRefresh      = $data['cache']['cacheRefresh'];
    
} else {
    
    $caching           = $this->_request['caching'];
	$cacheRefresh      = $this->_request['cacheRefresh'];
    
}
?>
<div class="row page-titles">
    <div class="col-sm-5 align-self-center"><h3 class="text-primary">Site Cache</h3></div>
    <div class="col-sm-7 align-self-center">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Settings</li>
            <li class="breadcrumb-item active">Site Cache</li>
        </ol>
    </div>
</div>

<div class="container-fluid">
    <?php
    if(isset($data['act']['message']))
        echo (isset($data['act']['type']) && $data['act']['type'] == 1)? '<div class="alert alert-success">'.$data['act']['message'].'</div>':'<div class="alert alert-danger">'.$data['act']['message'].'</div>';
    ?>

    <div>
        <form name="modifycontent" action="" method="post" enctype="multipart/form-data">
            <div class="row">
                
                <div class="col-sm-4 contentS">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Cache</label>
                                <select name="caching" class="form-control">
                                    <option value="false" <?php echo ($caching == 'false')? 'selected':'';?> >Disabled</option>
                                    <option value="true" <?php echo ($caching == 'true')? 'selected':'';?> >Enabled</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Refresh cache</label>
                                <select name="cacheRefresh" class="form-control">
                                    <option value="86400" <?php echo ($cacheRefresh == 86400)? 'selected':'';?> >Everyday</option>
                                    <option value="43200" <?php echo ($cacheRefresh == 43200)? 'selected':'';?> >Twice a day</option>
                                    <option value="28800" <?php echo ($cacheRefresh == 28800)? 'selected':'';?> >Thrice a day</option>
                                    <option value="3600" <?php echo ($cacheRefresh == 3600)? 'selected':'';?> >Every hour</option>
                                    <option value="1800" <?php echo ($cacheRefresh == 1800)? 'selected':'';?> >Every 30 minutes</option>
                                    <option value="600" <?php echo ($cacheRefresh == 600)? 'selected':'';?> >Every 10 minutes</option>
                                    <option value="300" <?php echo ($cacheRefresh == 300)? 'selected':'';?> >Every 5 minutes</option>
                                    <option value="60" <?php echo ($cacheRefresh == 60)? 'selected':'';?> >Every minute</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="form-group m-t-10">
                                <label><input type="checkbox" name="clearCache"> Clear cache</label>
                            </div>
                        </div>
                    </div>
                    
                </div>
                
                <?php /*
                <div class="col-sm-8 contentL">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Cached Pages</label>
                                <input type="text" name="siteName" value="<?php echo $siteName;?>" class="form-control" maxlength="60" />
                            </div>
                            
                        </div>
                    </div>
                </div>*/?>

                
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <button type="button" name="Back" value="Back" onclick="location.href='index.php?pageType=<?php echo $this->_request['pageType'];?>&dtls=<?php echo $this->_request['dtls'];?>'" class="btn btn-default m-r-15">Back</button>

                            <input type="hidden" name="SourceForm" value="siteCache" />
                            <button type="submit" name="Save" value="Save" class="btn btn-info login_btn">Save</button>

                            <button type="button" name="Cancel" value="Close" onclick="location.href='<?php echo SITE_ADMIN_PATH;?>'" class="btn btn-default m-l-15">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>