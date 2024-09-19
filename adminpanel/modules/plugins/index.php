<div class="row page-titles">
    <div class="col-sm-5 align-self-center"><h3 class="text-primary"><?php echo ($data['parentModule']) ? $data['parentModule']['menu_name'] : 'Plugins';?></h3></div>
    <div class="col-sm-7 align-self-center">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Modules</li>
            <li class="breadcrumb-item active">Plugins</li>
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
                    <?php if($data['parentModule']){ ?>
                        <button type="button" name="Back" value="Back" onclick="location.href='index.php?pageType=<?php echo $this->_request['pageType'];?>&dtls=<?php echo $this->_request['dtls'];?>'" class="btn btn-default m-r-15 pull-left">Back</button>
                    <?php } ?>
                    <a href="<?php echo SITE_ADMIN_PATH;?>/index.php?pageType=<?php echo $this->_request['pageType'];?>&dtls=<?php echo $this->_request['dtls'];?>&dtaction=add<?php echo ($this->_request['parent_id'])? '&parent_id='.$this->_request['parent_id']:'';?>" class="btn btn-info">Create Plugin</a>
                    
                    <a href="<?php echo SITE_ADMIN_PATH;?>/index.php?pageType=<?php echo $this->_request['pageType'];?>&dtls=<?php echo $this->_request['dtls'];?>&dtaction=install" class="btn btn-success">Install Plugin</a>
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

                                if($data['modules']) {
                                    ?>
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th width="40"><input type="checkbox" class="selectall" name="toggle"></th>
                                                <th width="60">Sl.</th>
                                                <th>Module</th>
                                                <th>Icon</th>
                                                <?php if(!$this->_request['parent_id']){ echo '<th>Sub Module</th>'; }?>
                                                <th width="150"></th>
                                            </tr>
                                        </thead>

                                        <tbody class="swap">
                                            <?php
                                            $slNo = 1;

                                            foreach($data['modules'] as $md) {
                                                if($md['status'] == 'Y')
                                                    $conStatus  = '<span class="status"><i class="fa fa-check" title="Active"></i> Active</span>';
                                                else
                                                    $conStatus  = '<span class="status inactive"><i class="fa fa-times" title="Inactive"></i> Inactive</span>';
                                                ?>
                                                <tr id="<?php echo 'recordsArray_'.$md['menu_id'];?>">
                                                    <td width="40">
                                                        <input type="checkbox" name="selectMulti[]" value="<?php echo $md['menu_id'];?>" class="case" />
                                                    </td>

                                                    <td width="60" scope="row"><?php echo $slNo;?></td>

                                                    <td>
                                                        <a href="index.php?pageType=<?php echo $this->_request['pageType'];?>&dtls=<?php echo $this->_request['dtls'];?>&dtaction=add&parent_id=<?php echo $this->_request['parent_id'];?>&editid=<?php echo $md['menu_id'];?>">
                                                            <?php echo $md['menu_name'];?>
                                                        </a>
                                                    </td>

                                                    <td class="table_img">
                                                        <?php
                                                        if(file_exists(MEDIA_MODULE_ROOT.'/thumb/'.$md['menu_image']) && $md['menu_image'])
                                                            echo '<img src="'.MEDIA_MODULE_SRC.'/thumb/'.$md['menu_image'].'" alt="'.$md['menu_name'].'" width="32" height="32" />';
                                                        elseif(file_exists(PLUGINS.DIRECTORY_SEPARATOR.$md['parent_dir'].DIRECTORY_SEPARATOR.$md['menu_image']) && $md['menu_image'])
                                                            echo '<img src="'.SITE_LOC_PATH.DIRECTORY_SEPARATOR.PLUGINS_PATH.DIRECTORY_SEPARATOR.$md['parent_dir'].DIRECTORY_SEPARATOR.$md['menu_image'].'" alt="'.$md['menu_name'].'" width="32" height="32"/>';
                                                        else
                                                            echo '<img src="'.ADMIN_TMPL_PATH.'/images/noicon.png" alt="'.$md['menu_name'].'" width="32" height="32" />';
                                                        ?>
                                                    </td>

                                                    <?php if($md['parent_id'] == 0){?>
                                                        <td>
                                                            <a href="index.php?pageType=<?php echo $this->_request['pageType'];?>&dtls=<?php echo $this->_request['dtls'];?>&parent_id=<?php echo $md['menu_id'];?>">
                                                                <img src="<?php echo ADMIN_TMPL_PATH;?>/images/mainmenu.png" alt="Sub Module" width="16" />
                                                                <span>[<?php echo $md['subCount']; ?>]</span>
                                                            </a>
                                                        </td>
                                                    <?php }?>

                                                    <td width="150" class="last_li">
                                                        <div class="action_link">
                                                            <?php echo $conStatus;?>
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

                    <?php if($data['modules']) {?>
                        <div class="card m-t-20">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-4 pull-right">
                                        <div class="last_li form-inline">
                                            <select name="multiAction" class="form-control multi_action">
                                                <option value="">Select</option>
                                                <option value="1">Active</option>
                                                <option value="2">Inactive</option>
                                                <option value="3">Delete</option>
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