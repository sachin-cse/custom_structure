<?php
if(isset($data['user']))
    $username                 = $data['user']['username'];
    $userpermission_array     = explode(',', $data['user']['permission']);	
?>

<div class="row page-titles">
    <?php $breadActive = ($this->_request['editid']) ? 'Edit Module Permission : '.strtoupper($username) : 'Add User : Module Permission > STEP - II' ;?>
    <div class="col-sm-5 align-self-center"><h3 class="text-primary"><?php echo $breadActive;?></h3></div>
    <div class="col-sm-7 align-self-center">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Administrators</li>
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
        <form name="modifycontent" action="" method="post">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <?php
                            foreach($this->_result['data']['navigation'] as $module) {
                                ?>
                                <fieldset>
                                    <legend>
                                        <span class="folder">
                                            <?php
                                            if($userpermission_array && in_array($module['menu_id'], $userpermission_array))
                                                $chk ='checked="checked"';
                                            else
                                                $chk ='';
                                            ?>

                                            <input type="checkbox" name="permission[]" value="<?php echo $module['menu_id'];?>" <?php echo $chk; ?> />
                                            <?php echo $module['menu_name'];?>
                                        </span>
                                    </legend>

                                    <?php
                                    if($module['menu_id'] == 1){
                                        $chk ='';
                                        if($userpermission_array && in_array(123, $userpermission_array))
                                            $chk ='checked="checked"';

                                        echo '<div style="width:180px; padding:10px; float:left;"><input type="checkbox" name="permission[]" value="123" '.$chk.' />&nbsp;Content</div>';
                                    }
                                    else{
                                        foreach($module['children'] as $children) {
                                            if($children['menu_name']){
                                                $chk = '';
                                                if($userpermission_array && in_array($children['menu_id'], $userpermission_array))
                                                    $chk = 'checked="checked"';

                                                echo '<div style="width:180px; padding:10px; float:left;"><input type="checkbox" name="permission[]" value="'.$children['menu_id'].'" '.$chk.' />&nbsp;'.$children['menu_name'].'</div>';
                                            }
                                        }
                                    }
                                    ?>
                                </fieldset>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <button type="button" name="Back" value="Back" onclick="location.href='index.php?pageType=<?php echo $this->_request['pageType'];?>&dtls=<?php echo $this->_request['dtls'];?>'" class="btn btn-default m-r-15">Back</button>

                            <input type="hidden" name="IdToEdit" value="<?php echo $this->_request['editid'];?>" />
                            <input type="hidden" name="SourceForm" value="permission" />
                            <button type="submit" name="Save" value="Save" class="btn btn-info login_btn">Save</button>

                            <button type="button" name="Cancel" value="Close" onclick="location.href='<?php echo SITE_ADMIN_PATH;?>'" class="btn btn-default m-l-15">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>