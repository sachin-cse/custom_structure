<div class="row page-titles">
    <div class="col-sm-5 align-self-center"><h3 class="text-primary">Admin User</h3></div>
    <div class="col-sm-7 align-self-center">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Administrators</li>
            <li class="breadcrumb-item active">Admin User</li>
        </ol>
    </div>
</div>

<div class="container-fluid">
    <?php
    if(isset($data['act']['message']))
        echo (isset($data['act']['type']) && $data['act']['type'] == 1)? '<div class="alert alert-success">'.$data['act']['message'].'</div>':'<div class="alert alert-danger">'.$data['act']['message'].'</div>';
    ?>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body text-right">
                    <a href="<?php echo SITE_ADMIN_PATH.'/index.php?pageType='.$this->_request['pageType'].'&dtls='.$this->_request['dtls'].'&dtaction=add' ;?>" class="btn btn-info">Add User</a>
                </div>
            </div>
        </div>
    </div>

    <div>
        <form action="" name="myForm" method="POST">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card p-0">
                        <div class="card-body">

                            <div class="table-responsive">
                                <?php
                                if($data['users']) {
                                    ?>
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th width="40"><input type="checkbox" class="selectall" name="toggle"></th>
                                                <th width="60">Sl.</th>
                                                <th>User</th>
                                                <th width="250"></th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php
                                            $slNo = 1;
                                            foreach($data['users'] as $ud) {
                                                if($ud['status'] == 'Y')
                                                    $conStatus  = '<span class="status"><i class="fa fa-check" title="Active"></i> Active</span>';
                                                else
                                                    $conStatus  = '<span class="status inactive"><i class="fa fa-times" title="Inactive"></i> Inactive</span>';
                                                ?>
                                                <tr id="<?php echo 'recordsArray_'.$msgId;?>">
                                                    <td width="40">
                                                        <input type="checkbox" name="selectMulti[]" value="<?php echo $ud['id'];?>" class="case" />
                                                    </td>

                                                    <td width="60" scope="row"><?php echo $slNo;?></td>

                                                    <td>
                                                        <a title="Edit" href="index.php?pageType=<?php echo $this->_request['pageType'];?>&dtls=<?php echo $this->_request['dtls'];?>&dtaction=add&editid=<?php echo $ud['id'];?>">
                                                            <?php echo $ud['username'];?>
                                                        </a>
                                                    </td>

                                                    <td width="250" class="last_li">
                                                        <div class="action_link">
                                                            <?php echo $conStatus;?>

                                                            <a href="index.php?pageType=<?php echo $this->_request['pageType'];?>&dtls=<?php echo $this->_request['dtls'];?>&dtaction=modulepermissions&editid=<?php echo $ud['id'];?>">
                                                                <img src="<?php echo ADMIN_TMPL_PATH;?>/images/access.png" alt="access" width="16" height="16" /> Permission
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php
                                                $slNo++;
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                    <?php
                                }
                                else
                                    echo '<div class="norecord text-center">No Record Present</div>';
                                ?>
                            </div>

                        </div>
                    </div>

                    <?php if($data['users']) {?>
                        <div class="card m-t-20">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-4 pull-right">
                                        <div class="last_li form-inline">
                                            <select name="multiAction" class="form-control multi_action">
                                                <option value="">Select</option>
                                                <option value="1">Active</option>
                                                <option value="2">Inactive</option>
                                            </select>  
                                            <input type="hidden" name="SourceForm" value="multiAction" />
                                            <button type="submit" name="Save" value="Apply" class="btn btn-info m-l-10">Apply</button>
                                        </div>
                                    </div>
                                    <?php
                                    /*if($data['pageList']){
                                        echo '<div class="col-sm-8">';
                                        echo '<div class="pagination">';
                                        echo '<p class="total">Page '.$data['page'].' of '.$data['totalPage'].'</p>';
                                        echo '<div>'.$data['pageList'].'</div>';
                                        echo '</div>';
                                        echo '</div>';
                                    }*/
                                    ?>
                                </div>
                            </div>
                        </div>
                    <?php }?>
                </div>
            </div>
        </form>
    </div>
</div>