<?php
defined('BASE') OR exit('No direct script access allowed.');
if($data['content']) {
    
    $menucategoryId 			= $data['content']['menucategoryId'];
    $contentHeading 			= $data['content']['contentHeading'];
    $subHeading 				= $data['content']['subHeading'];
    $permalink                  = $data['content']['permalink'];

    $contentDescription 		= $data['content']['contentDescription'];
    $contentShortDescription 	= $data['content']['contentShortDescription'];

    $ImageName 	                = $data['content']['ImageName'];
    $displayOrder 	            = $data['content']['displayOrder'];
    $contentStatus 	            = $data['content']['contentStatus'];
    
} else {
    
    $menucategoryId 			= $this->_request['menucategoryId'];
    $contentHeading 			= $this->_request['contentHeading'];
    $subHeading 				= $this->_request['subHeading'];
    $permalink                  = $this->_request['permalink'];

    $contentDescription 		= $this->_request['contentDescription'];
    $contentShortDescription 	= $this->_request['contentShortDescription'];

    $ImageName 	                = $this->_request['ImageName'];
    $displayOrder 	            = $this->_request['displayOrder'];
    $contentStatus 	            = $this->_request['contentStatus'];
    
}
?>
<div class="row page-titles">
    <div class="col-sm-5 align-self-center"><h3 class="text-primary"><?php echo $data['pageData']['categoryName'];?></h3></div>
    <div class="col-sm-7 align-self-center">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Content</li>
            <li class="breadcrumb-item active"><?php echo $data['pageData']['categoryName'];?></li>
        </ol>
    </div>
</div>

<div class="container-fluid">
    <?php
    if($data['act']['message'])
        echo ($data['act']['type'] == 1)? '<div class="alert alert-success">'.$data['act']['message'].'</div>':'<div class="alert alert-danger">'.$data['act']['message'].'</div>';

    if($data['subPages']) {
        ?>
        <div class="row">
            <div class="col-sm-12">
                <form name="subPage" action="" method="post">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group form-inline m-b-0">
                                <label>Content(s) Under</label>
                                <select name="editid" class="form-control m-l-10" style="width:400px;">
                                    <option value="<?php echo $data['pageData']['categoryId'];?>"><?php echo $data['pageData']['categoryName'];?></option>
                                    <?php
                                    foreach($data['subPages'] as $subPage) {
                                        echo '<option value="'.$subPage['categoryId'].'">'.$subPage['categoryName'].'</option>';
                                    }
                                    ?>
                                </select>
                                <input type="hidden" name="SourceForm" value="showContent" />
                                <button type="submit" name="Save" value="Go" class="btn btn-info m-l-10">Go</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <?php
    }
    ?>
    <div>
        <form name="modifycontent" action="" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-8">
                                        <div class="form-group">
                                            <label>Heading *</label>
                                            <input type="text" name="contentHeading" value="<?php echo $contentHeading;?>" class="form-control copyToTitle" id="categoryPermalink" maxlength="200" />
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Display Heading</label>
                                            <select name="displayHeading" class="form-control">
                                                <option value="Y" <?php if($data['content']['displayHeading']=='Y') echo 'selected';?>>Yes</option>
                                                <option value="N" <?php if($data['content']['displayHeading']=='N') echo 'selected';?>>No</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-8 contentL">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Sub Heading / Short Description</label>
                                <textarea name="subHeading" class="form-control editor_small"><?php echo $subHeading;?></textarea>
                            </div>
                        </div>
                    </div>
                            
                    <div class="card">
                        <div class="card-body">

                            <div class="form-group">
                                <label>Description *</label>
                                
                                <textarea name="contentDescription" class="form-control editor"><?php echo $contentDescription;?></textarea>
                                
                                
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-4 contentS">
                    
                    <?php if($this->_request['editid'] == 'uncategorized') { 

                    $pageKey = array_search(1, array_column($this->_result['data']['navigation'], 'menu_id'));

                    ?>
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Assign Under</label>
                                <select name="menucategoryId" class="form-control">
                                    <option value="0" <?php echo ($menucategoryId == 0 || ($this->_request['editid'] == 'uncategorized' && $menucategoryId != 0))? 'selected':''?>>Uncategorized</option>
                                    <?php
                                    foreach($this->_result['data']['navigation'][$pageKey]['children'] as $child){
                                        if($menucategoryId == $child['categoryId'])
                                            echo '<option value="'.$child['categoryId'].'" selected>'.$child['categoryName'].'</option>';
                                        else
                                            echo '<option value="'.$child['categoryId'].'">'.$child['categoryName'].'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <?php } else {echo '<input type="hidden" name="menucategoryId" value="'.$this->_request['editid'].'" />';}?>
                    
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Status</label>
                                <select name="contentStatus" class="form-control">
                                    <option value="Y" <?php echo ($contentStatus == 'Y')? 'selected':''?>>Active</option>
                                    <option value="N" <?php echo ($contentStatus == 'N')? 'selected':''?>>Inactive</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Display Priority</label>
                                
                                <select name="displayOrder" class="form-control">
                                    <?php if($data['content']['contentID']) {?>
                                    <option value="<?php echo $displayOrder;?>" >Stay as it is</option>
                                    <?php }?>
                                    <option value="T" <?php echo ($displayOrder == 'T')? 'selected':'';?>>Move to top</option>
                                    <option value="B" <?php echo ($displayOrder == 'B')? 'selected':'';?>>Move to bottom</option>
                                </select>
                                
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Upload Image (Recommended Size: 630px * 425px)</label>
                                <input type="file" name="ImageName" class="form-control" />
                                <?php
                                if($ImageName && file_exists(MEDIA_FILES_ROOT.'/content/thumb/'.$ImageName)) {
                                    echo '<div class="table_img m-t-10"><img src="'.MEDIA_FILES_SRC.'/content/thumb/'.$ImageName.'?ts='.time().'" alt="'.$ImageName.'"></div>';
                                    echo '<button type="submit" name="DeleteImg" class="btn btn-sm btn-danger float-right m-t-10">Delete Image</button>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    
                    <?php //$this->loadView('seo/titlemeta', 'seopanel.php', $data['seoData']);?>
                    
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            
                            <input type="hidden" name="IdToEdit" value="<?php echo $data['content']['contentID'];?>" />
                            <input type="hidden" name="SourceForm" value="addEditContent" />
                            <button type="submit" name="Save" value="Save" class="btn btn-info login_btn">Save</button>

                            <button type="button" name="Cancel" value="Close" onclick="location.href='<?php echo SITE_ADMIN_PATH;?>'" class="btn btn-default m-l-15">Close</button>
                            <?php 
                            if($data['content'])
                                echo '<button type="submit" name="Delete" class="btn btn-danger float-right">Delete Content</button>';
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>