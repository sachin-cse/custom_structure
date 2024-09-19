<?php
defined('BASE') OR exit('No direct script access allowed.');
if($data['pageData']) {

    $IdToEdit                      = $data['pageData']['categoryId'];
    $parentId                      = $data['pageData']['parentId'];
    $modulePackageId               = $data['pageData']['moduleId'];
    $categoryName                  = $data['pageData']['categoryName'];
    $permalink                     = $data['pageData']['permalink'];

    $categoryUrl                   = $data['pageData']['categoryUrl'];
    $categoryUrlTarget             = $data['pageData']['categoryUrlTarget'];

    $isBanner                      = $data['pageData']['isBanner'];
    $categoryImage                 = $data['pageData']['categoryImage'];
    $isBannerCaption               = $data['pageData']['isBannerCaption'];
    $bannerCaption                 = $data['pageData']['bannerCaption'];
    $displayOrder                  = $data['pageData']['displayOrder'];
    $hiddenMenu                    = $data['pageData']['hiddenMenu'];

    $status                        = $data['pageData']['status'];
    
    $qrystrPermalink               = 'categoryId!='.$IdToEdit.' and parentId='.$parentId;
}
else {

    $parentId                      = $this->_request['parentId'];
    $modulePackageId               = $this->_request['modulePackageId'];
    $categoryName                  = $this->_request['categoryName'];
    $permalink                     = $this->_request['permalink'];

    $categoryUrl                   = $this->_request['categoryUrl'];
    $categoryUrlTarget             = $this->_request['categoryUrlTarget'];

    $isBanner                      = $this->_request['isBanner'];
    $categoryImage                 = $this->_request['categoryImage'];
    $isBannerCaption               = $this->_request['isBannerCaption'];
    $bannerCaption                 = $this->_request['bannerCaption'];
    $displayOrder                  = $this->_request['displayOrder'];
    $hiddenMenu                    = $this->_request['hiddenMenu'];
    $status                        = $this->_request['status'];
    
    $qrystrPermalink               = ($parentId)? 'parentId='.$parentId : 1;
}
?>
<div class="container-fluid">
    
    <?php
    if(isset($data['act'])){
        
        if(isset($data['act']['message']))
            echo (isset($data['act']['type']) && $data['act']['type'] == 1)? '<div class="alert alert-success">'.$data['act']['message'].'</div>':'<div class="alert alert-danger">'.$data['act']['message'].'</div>';
        
        if(isset($data['act']['editid']))
            $IdToEdit = $data['act']['editid'];
    }
    ?>
    
    <div>
        <form name="modifycontent" action="" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-sm-8 contentL">
                    <div class="card">
                        
                        <div class="card-body">
                            
                            <?php if($data['parentData']) { ?>
                            <div class="alert alert-info">
                                <span>Parent: <?php echo $data['parentData']['categoryName'];?></span>
                            </div>
                            <?php }?>
                            
                            <div class="form-group">
                                <label>Page *</label>
                                <input type="text" name="categoryName" value="<?php echo $categoryName;?>" class="form-control permalink copyToTitle" autocomplete="off" data-entity="<?php echo TBL_MENU_CATEGORY;?>" data-qrystr="<?php echo $qrystrPermalink;?>" maxlength="255">
                            </div>
                            
                            <div class="form-group">
                                <label>Permalink</label>
                                <input type="text" name="permalink" value="<?php echo $permalink;?>" class="form-control gen_permalink" autocomplete="off" maxlength="255">
                            </div>
                            
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-8">
                                        <div class="form-group">
                                            <label>Redirect URL</label>
                                            <input type="text" name="categoryUrl" value="<?php echo $categoryUrl;?>" class="form-control" placeholder="">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Target </label>
                                            <select name="categoryUrlTarget" class="form-control">
                                                <option>Default</option>
                                                <option value="_blank" <?php if($categoryUrlTarget=='_blank') echo 'selected';?>>Open in new tab (_blank)</option>
                                                <option value="_self" <?php if($categoryUrlTarget=='_self') echo 'selected';?>>Open in same tab (_self)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        
                    </div>
                    
                    <?php include("banner.php");?>
                </div>
                
                <div class="col-sm-4 contentS">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="Y" <?php if($status=='Y') echo 'selected'?>>Active</option>
                                    <option value="N" <?php if($status=='N') echo 'selected'?>>Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <?php if($this->session->read('UTYPE') == "A") {?>
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Is it a Hidden Menu? </label>
                                    <select name="hiddenMenu" class="form-control">
                                        <option value="Y" <?php if($hiddenMenu=='Y') echo 'selected';?>>Yes</option>
                                        <option value="N" <?php if($hiddenMenu=='N' || !$hiddenMenu) echo 'selected';?>>No</option>
                                    </select>
                                </div>
                                <hr>
                                <div class="form-group">
                                    
                                    
                                    
                                    <label>Page Type *</label>
                                    <ul id="browser" class="filetree">
                                        <li>
                                            <label class="folder">
                                                <input type="radio" name="modulePackageId" value="0" <?php if(!$modulePackageId) echo 'checked="checked"';?> />CMS
                                            </label>
                                        </li>
                                        <?php
                                        if($this->_result['data']['navigation']) {
                                            $notPageModule = array(1, 3, 99, 339); // CMS, SEO, SITEPAGE, THEME
                                            foreach($this->_result['data']['navigation'] as $module) {
                                                if( !in_array($module['menu_id'], $notPageModule) )
                                                {
                                                    echo '<li>
                                                        <label class="folder">';
                                                            if($modulePackageId == $module['menu_id'])
                                                                echo '<input type="radio" name="modulePackageId" value="'.$module['menu_id'].'" checked="checked" />';
                                                            else
                                                                echo '<input type="radio" name="modulePackageId" value="'.$module['menu_id'].'" />';
                                                            echo $module['menu_name'];
                                                        echo '</label>';
                                                    echo '</li>';
                                                }
                                            }
                                        }
                                        ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <?php }?>
                    
                    <?php $this->loadView('seo/titlemeta', 'seopanel.php', $data['seoData']);?>
                </div>
            </div>
                
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <button type="button" name="Back" value="Back" onclick="history.back(-1);" class="btn btn-default m-r-15">Back</button>
                            
                            <?php if($this->_request['editid']!='' && $this->session->read('UTYPE')!='A') {?>
                                <input type="hidden" name="modulePackageId" value="<?php echo $modulePackageId;?>" />
                            <?php }?>
                            <input type="hidden" name="IdToEdit" value="<?php echo $IdToEdit;?>" />
                            <input type="hidden" name="SourceForm" value="addEditPage" />
                            <button type="submit" name="Save" value="Save" class="btn btn-info login_btn">Save</button>

                            <button type="button" name="Cancel" value="Close" onclick="location.href='index.php?pageType=<?php echo $this->_request['pageType'];?>&dtls=<?php echo $this->_request['dtls'];?>&moduleId=<?php echo $this->_request['moduleId'];?>'" class="btn btn-default m-l-15">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>