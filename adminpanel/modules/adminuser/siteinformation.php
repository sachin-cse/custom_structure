<div class="row page-titles">
    <?php $breadActive = ($this->_request['editid']) ? 'Edit User : '.strtoupper($username) : 'Add User > STEP - I' ;?>
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
                        <div class="card-title">
                            <h4>Login information</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Username *</label>
                                        <input type="text" name="username" value="<?php echo $username;?>" class="form-control" maxlength="60" />
                                    </div>

                                    <div class="form-group">
                                        <label>Password *</label>
                                        <input type="password" name="password" value="<?php echo $password;?>" class="form-control gen_pass" placeholder="" autocomplete="off">
                                    </div>

                                    <div class="form-group">
                                        <label>Confirm Password *</label>
                                        <input type="password" name="conpassword" value="<?php echo $conpassword;?>" class="form-control gen_pass" placeholder="" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group text-center m-t-30">
                                        <a href="#" class="btn btn-info generate">Generate Password</a>
                                        <div class="new_pass" style="display:none;">
                                            <input type="text" name="genpass" value="" class="form-control m-t-20 m-b-20">
                                            Copy the Password and keep it in a secure place.
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
                        <div class="card-title">
                            <h4>Personal information</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Full Name *</label>
                                <input type="text" name="fullname" value="<?php echo $fullname;?>" class="form-control" maxlength="60" />
                            </div>

                            <div class="form-group">
                                <label>Email *</label>
                                <input type="text" name="email" value="<?php echo $email;?>" class="form-control" placeholder="" autocomplete="off" maxlength="50">
                            </div>

                            <div class="form-group">
                                <label>Phone</label>
                                <input type="text" name="phone" value="<?php echo $phone;?>" class="form-control" placeholder="">
                            </div>

                            <div class="form-group">
                                <label>Address</label>
                                <textarea name="address" class="form-control"><?php echo $address;?></textarea>
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
                                    <option value="Y" <?php echo ($status == 'Y')? 'selected':'';?> >Active</option>
                                    <option value="N" <?php echo ($status == 'N')? 'selected':'';?> >Inactive</option>
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
                            <button type="button" name="Back" value="Back" onclick="location.href='index.php?pageType=<?php echo $this->_request['pageType'];?>&dtls=<?php echo $this->_request['dtls'];?>'" class="btn btn-default m-r-15">Back</button>

                            <input type="hidden" name="IdToEdit" value="<?php echo $this->_request['editid'];?>" />
                            <input type="hidden" name="SourceForm" value="addEditUser" />
                            <button type="submit" name="Save" value="Save" class="btn btn-info login_btn">Save</button>

                            <button type="button" name="Cancel" value="Close" onclick="location.href='<?php echo SITE_ADMIN_PATH;?>'" class="btn btn-default m-l-15">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>