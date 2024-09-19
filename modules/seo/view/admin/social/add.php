<?php
defined('BASE') OR exit('No direct script access allowed.');
if($data['socialSite']) {
    
	$IdToEdit                  = $data['socialSite']['id'];
	$socialName                = $data['socialSite']['socialName'];
	$socialLink                = $data['socialSite']['socialLink'];
	$status                    = $data['socialSite']['status'];
	$displayOrder              = $data['socialSite']['displayOrder'];
}
else {
    
    $socialName                = $this->_request['socialName'];
	$socialLink                = $this->_request['socialLink'];
	$status                    = $this->_request['status'];
	$displayOrder              = $this->_request['displayOrder'];
}
?>

<div class="container-fluid">
    <?php
    if($data['act']['message'])
        echo ($data['act']['type'] == 1)? '<div class="alert alert-success">'.$data['act']['message'].'</div>':'<div class="alert alert-danger">'.$data['act']['message'].'</div>';
    ?>
    
    <div>
        <form name="modifycontent" action="" method="post">
            <div class="row">
                <div class="col-sm-8 contentL">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Social Site Name *</label>
                                <select name="socialName" class="form-control">
                                <?php
                                foreach($data['socialLinks'] as $key=>$social) {
                                    if($socialName == $key)
                                        echo '<option value="'.$key.'" selected>'.$key.'</option>';
                                    else
                                        echo '<option value="'.$key.'">'.$key.'</option>';
                                }
                                ?>
                            </select>
                            </div>

                            <div class="form-group">
                                <label>Social Site Link *</label>
                                <input type="text" name="socialLink" value="<?php echo $socialLink;?>" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-sm-4 contentS">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="Y" <?php echo ($status == 'Y')? 'selected':''?>>Active</option>
                                    <option value="N" <?php echo ($status == 'N')? 'selected':''?>>Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <button type="button" name="Back" value="Back" onclick="location.href='index.php?pageType=<?php echo $this->_request['pageType'];?>&dtls=<?php echo $this->_request['dtls'];?>&moduleId=<?php echo $this->_request['moduleId'];?>'" class="btn btn-default m-r-15">Back</button>
                            
                            <input type="hidden" name="IdToEdit" value="<?php echo $IdToEdit;?>" />
                            <input type="hidden" name="SourceForm" value="addSocialSite" />
                            <button type="submit" name="Save" value="Save" class="btn btn-info login_btn">Save</button>

                            <button type="button" name="Cancel" value="Close" onclick="location.href='<?php echo SITE_ADMIN_PATH;?>'" class="btn btn-default m-l-15">Close</button>   
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>