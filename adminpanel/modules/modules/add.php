<?php
if($data['module']) {
    
	$IdToEdit              = $data['module']['menu_id'];
	$parent_id             = $data['module']['parent_id'];
    $menu_name             = $data['module']['menu_name'];
	$displayOrder          = $data['module']['displayOrder'];
	$menu_image            = $data['module']['menu_image'];
}
else {
    
    $parent_id             = $this->_request['parent_id'];
    $menu_name             = $this->_request['menu_name'];
	$displayOrder          = $this->_request['displayOrder'];
	$menu_image            = $this->_request['menu_image'];
}
?>
<div class="row page-titles">
    <?php $breadActive = ($this->_request['editid']!='') ? 'Edit Module' : 'Add Module' ;?>
    <div class="col-sm-5 align-self-center"><h3 class="text-primary"><?php echo $breadActive;?></h3></div>
    <div class="col-sm-7 align-self-center">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Modules</li>
            <li class="breadcrumb-item active"><?php echo $breadActive;?></li>
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
                <div class="col-sm-8 contentL">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Parent Module</label>
                                <select name="parent_id" class="form-control">
                                    <option value="">--None--</option>
                                    <?php
                                    foreach($data['modules'] as $modNav)
                                    {
                                        if($modNav['menu_id'] != $this->_request['editid']){
                                        ?>
                                        <option value="<?php echo $modNav['menu_id'];?>" <?php echo ($parent_id == $modNav['menu_id'])? 'selected':'';?>>
                                            <?php echo $modNav['menu_name'];?>
                                        </option>
                                        <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Module Name *</label>
                                <input type="text" name="menu_name" value="<?php echo $menu_name;?>" class="form-control" maxlength="60" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-4 contentS">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Icon (Recommended size: 80px * 80px)</label>
                                <input type="file" name="ImageName" class="form-control">
                                <?php
                                if($menu_image && file_exists(MEDIA_MODULE_ROOT.'/thumb/'.$menu_image))
                                    echo '<div class="table_img m-t-10"><img src="'.MEDIA_MODULE_SRC.'/thumb/'.$menu_image.'" /></div>';
                                ?>
                            </div>

                            <div class="form-group">
                                <label>Display Order</label>
                                <input type="text" name="displayOrder" value="<?php echo $displayOrder;?>" class="form-control" id="displayOrder" maxlength="3" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <button type="button" name="Back" value="Back" onclick="location.href='index.php?pageType=<?php echo $this->_request['pageType'];?>&dtls=<?php echo $this->_request['dtls'];?>'" class="btn btn-default m-r-15">Back</button>

                            <input type="hidden" name="IdToEdit" value="<?php echo $IdToEdit;?>" />
                            <input type="hidden" name="SourceForm" value="addEditModule" />
                            <button type="submit" name="Save" value="Save" class="btn btn-info login_btn">Save</button>

                            <button type="button" name="Cancel" value="Close" onclick="location.href='<?php echo SITE_ADMIN_PATH;?>'" class="btn btn-default m-l-15">Close</button>   
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>