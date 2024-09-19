<?php
defined('BASE') OR exit('No direct script access allowed.');
if($data['settings']) {
    
	$IdToEdit                   = $data['settings']['id'];
	$isAvatar                   = $data['settings']['isAvatar'];
    $avatarWidth                = $data['settings']['avatarWidth'];
    $avatarHeight               = $data['settings']['avatarHeight'];
    
    $isShowcase                 = $data['settings']['isShowcase'];
    $showcaseTitle              = $data['settings']['showcaseTitle'];
    $showcaseNo                 = $data['settings']['showcaseNo'];
    $showcaseDescription        = $data['settings']['showcaseDescription'];

    $limit                      = $data['settings']['limit'];
    
    $isReadMore                 = $data['settings']['isReadMore'];
    $buttonText                 = $data['settings']['buttonText'];
    $buttonLimit                = $data['settings']['buttonLimit'];
}
else {
    
    $isAvatar                   = $this->_request['isAvatar'];
    $avatarWidth                = $this->_request['avatarWidth'];
    $avatarHeight               = $this->_request['avatarHeight'];
    
    $isShowcase                 = $this->_request['isShowcase'];
    $showcaseTitle              = $this->_request['showcaseTitle'];
    $showcaseNo                 = $this->_request['showcaseNo'];
    $showcaseDescription        = $this->_request['showcaseDescription'];

    $limit                      = $this->_request['limit'];
    
    $isReadMore                 = $this->_request['isReadMore'];
    $buttonText                 = $this->_request['buttonText'];
    $buttonLimit                = $this->_request['buttonLimit'];
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
                <div class="col-sm-9 contentL">
                    <div class="card">
                        <div class="card-title">
                            <h4 style="line-height:24px;margin:0;">Showcase</h4>
                            <label class="switch float-right">
                                <input type="checkbox" name="isShowcase" <?php if($isShowcase == '1') echo 'checked';?>>
                                <span></span>
                            </label>
                            <span class="f-s-14 m-l-30" style="line-height:20px;">
                                Hook: showcase <span class="sweetBox" onclick="sAlert('Example', '<div>$this-&gt;hook(\'<?php echo $this->_request['pageType'];?>\', \'showcase\', array(\'css\'=&gt;\'testimonial_list\', \'col\' =&gt; \'col-sm-4\'));</div><hr><h3>Options</h3><ul style=\'text-align:left\'><li>\'wrapcss\' =&gt; CSS to wrap the section</li><li>\'css\' =&gt; CSS to wrap all items</li><li>\'col\' =&gt; CSS to indicate columns per row</li><li>\'slider\' =&gt; true (default value false)</li></ul>', true);"><i class="fa fa-question-circle"></i></span>
                            </span>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-8">
                                        <div class="form-group">
                                            <label>Title</label>
                                            <input type="text" name="showcaseTitle" value="<?php echo $showcaseTitle;?>" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Number of Items</label>
                                            <input type="text" name="showcaseNo" value="<?php echo $showcaseNo;?>" class="form-control numbersOnly">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="showcaseDescription" class="form-control editor_small"><?php echo $showcaseDescription;?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-sm-3 contentS">
                    <div class="card">
                        <div class="card-title">
                            <h4 style="line-height:24px;margin:0;">Avatar</h4>
                            <label class="switch float-right">
                                <input type="checkbox" name="isAvatar" <?php if($isAvatar == '1') echo 'checked';?>>
                                <span></span>
                            </label>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-sm-6 p-t-8 p-r-0 m-b-0">Width [px]</label>
                                <div class="col-sm-6">
                                    <input type="text" name="avatarWidth" value="<?php echo $avatarWidth;?>" class="form-control numbersOnly">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-6 p-t-8 p-r-0 m-b-0">Height [px]</label>
                                <div class="col-sm-6">
                                    <input type="text" name="avatarHeight" value="<?php echo $avatarHeight;?>" class="form-control numbersOnly">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-title">
                            <h4 style="line-height:24px;margin:0;">Items Per Page</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <input type="text" name="limit" value="<?php echo ($limit) ? $limit : VALUE_PER_PAGE;?>" class="form-control numbersOnly">
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-title">
                            <h4 style="line-height:24px;margin:0;">Read More Button</h4>
                            <label class="switch float-right">
                                <input type="checkbox" name="isReadMore" <?php if($isReadMore == '1') echo 'checked';?>>
                                <span></span>
                            </label>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-sm-6 p-t-8 p-r-0 m-b-0">Button Text</label>
                                <div class="col-sm-6">
                                    <input type="text" name="buttonText" value="<?php echo ($buttonText) ? $buttonText : 'Read More';?>" class="form-control">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-6 p-t-8 p-r-0 m-b-0">Character Trim</label>
                                <div class="col-sm-6">
                                    <input type="text" name="buttonLimit" value="<?php echo $buttonLimit;?>" class="form-control numbersOnly">
                                </div>
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
                            <input type="hidden" name="SourceForm" value="addEditSettings" />
                            <button type="submit" name="Save" value="Save" class="btn btn-info login_btn">Save</button>

                            <button type="button" name="Cancel" value="Close" onclick="location.href='<?php echo SITE_ADMIN_PATH;?>'" class="btn btn-default m-l-15">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>