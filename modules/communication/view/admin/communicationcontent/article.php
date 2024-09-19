<?php
defined('BASE') OR exit('No direct script access allowed.');
if($data['content']){
    
    $menucategoryId 			= $data['content']['menucategoryId'];
    $contentHeading 			= $data['content']['contentHeading'];
    $subHeading 				= $data['content']['subHeading'];
    $permalink                  = $data['content']['permalink'];

    $contentDescription 		= $data['content']['contentDescription'];
    $contentShortDescription 	= $data['content']['contentShortDescription'];

    $ImageName 	                = $data['content']['ImageName'];
    $displayOrder 	            = $data['content']['displayOrder'];
    $contentStatus 	            = $data['content']['contentStatus'];

    $unserilizedData            = unserialize($data['content']['serializedData']);
    $address 	                = $unserilizedData['address'];
    $email 	                    = $unserilizedData['email'];
    $contactNo 	                = $unserilizedData['contactno'];
    
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

    $address 	                = $this->_request['txtAddress'];
    $email 	                    = $this->_request['txtEmail'];
    $contactNo 	                = $this->_request['txtContactNo'];
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
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>For Page *</label>
                                            <select name="menucategoryId" class="form-control">
                                                <?php 
                                                foreach($data['linkedPages'] as $linkedPage){
                                                    if($linkedPage['categoryId'] == $menucategoryId)
                                                        echo '<option value="'.$linkedPage['categoryId'].'" selected>'.$linkedPage['categoryName'].'</option>';
                                                    else
                                                        echo '<option value="'.$linkedPage['categoryId'].'">'.$linkedPage['categoryName'].'</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Heading *</label>
                                            <input type="text" name="contentHeading" value="<?php echo $contentHeading;?>" class="form-control copyToTitle" id="categoryPermalink" maxlength="200" />
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
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
                                <label>Sub Heading</label>
                                <textarea name="subHeading" class="form-control editor_small"><?php echo $subHeading;?></textarea>
                            </div>
                        </div>
                    </div>
                            
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="contentDescription" class="form-control editor"><?php echo $contentDescription;?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-4 contentS">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Office/Business Address</label>
                                <input type="text" name="txtAddress" value="<?php echo $address;?>" placeholder="Address" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Office/Business Email</label>
                                <input type="text" name="txtEmail" value="<?php echo $email;?>" placeholder="Email" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Office/Business Contact Number</label>
                                <input type="text" name="txtContactNo" value="<?php echo $contactNo;?>" placeholder="Contact Number" class="form-control">
                            </div>
                        </div>
                    </div>

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