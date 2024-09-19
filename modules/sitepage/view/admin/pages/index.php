<?php defined('BASE') OR exit('No direct script access allowed.');?>
<div class="container-fluid">
    <?php
    if(isset($data['act']['message']))
        echo (isset($data['act']['type']) && $data['act']['type'] == 1)? '<div class="alert alert-success">'.$data['act']['message'].'</div>':'<div class="alert alert-danger">'.$data['act']['message'].'</div>';
    ?>
    
    <div class="row">

        <div class="<?php echo ($this->session->read('UTYPE') == "A") ? 'col-sm-9' : 'col-sm-12';?>">
            <div class="card">
                <div class="card-body">
                    <form name="searchForm" action="" method="post">
                        <div class="form-inline">
                            <div class="form-group">
                                <input type="text" name="searchText" value="<?php echo $this->session->read('searchText');?>" placeholder="Search by Page" class="form-control">
                            </div>
                            
                            <div class="form-group">
                                <select name="searchStatus" class="form-control">
                                    <option value="">Status</option>
                                    <option value="Y" <?php if ($this->session->read('searchStatus') == 'Y') echo 'selected';?>>Active</option>
                                    <option value="N" <?php if ($this->session->read('searchStatus') == 'N') echo 'selected';?>>Inactive</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <select name="searchType" class="form-control">
                                    <option value="">Menu Type</option>
                                    <option value="T" <?php if ($this->session->read('searchType') == 'T') echo 'selected';?>>Header Menu</option>
                                    <option value="F" <?php if ($this->session->read('searchType') == 'F') echo 'selected';?>>Footer Menu</option>
                                    <?php if($this->session->read('UTYPE') == "A") {?>
                                        <option value="H" <?php if ($this->session->read('searchType') == 'H') echo 'selected';?>>Hidden Menu</option>
                                    <?php }?>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <button type="submit" name="Search" class="btn btn-info width-auto"><i class="fa fa-search"></i></button>
                                <button type="submit" name="Reset" class="btn btn-dark width-auto m-l-10"><i class="fa fa-refresh"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
           
        <?php 
        if($this->session->read('UTYPE') == "A") {
            ?>
            <div class="col-sm-3">
                <div class="card">
                    <div class="card-body text-center">
                        <a href="<?php echo SITE_ADMIN_PATH.'/index.php?pageType='.$this->_request['pageType'].'&dtls='.$this->_request['dtls'].'&dtaction=new&parentId='.$this->_request['parentId'].'&moduleId='.$this->_request['moduleId'];?>" class="btn btn-info">Add New</a>
                    </div>
                </div>
            </div>
            <?php 
        }
        ?>

    </div>

    <form action="" name="pageForm" method="POST">
        <div class="row">
            <div class="col-sm-12">
                <div class="card p-0">
                    <div class="card-body">
                        
                        <div class="table-responsive">
                            <?php
                            if($data['pages']) {
                                ?>
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th width="50"><input type="checkbox" class="selectall" name="toggle"></th>
                                            <th width="60">Sl.</th>
                                            <th><?php echo ($data['parentData'])? 'Pages under '.$data['parentData']['categoryName']:'Page';?></th>
                                            <th>Module</th>
                                            <th>Sub Page</th>
                                            <th>Icon</th>
                                            <th width="175"><div class="alert alert-success">Records Found: <?php echo $data['rowCount'];?></div></th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody class="swap">
                                        <?php
                                        $slNo = ($this->_request['page'] > 1) ? (($this->_request['page'] - 1) * $data['limit']) + 1 : 1;

                                        foreach($data['pages'] as $sitepage) {
                                            if($sitepage['status'] == 'Y')
                                                $conStatus  = '<span class="status"><i class="fa fa-check" title="Active"></i> Active</span>';
                                            else
                                                $conStatus  = '<span class="status inactive"><i class="fa fa-times" title="Inactive"></i> Inactive</span>';

                                            if($sitepage['isTopMenu'] == 'Y') {
                                                $isTopMenuImg   = '<img src="'.ADMIN_TMPL_PATH.'/images/t.png" alt="Active" width="15" border="0" />';
                                                $isTopMenu      = "N";
                                            }
                                            else {
                                                $isTopMenuImg   = '<img src="'.ADMIN_TMPL_PATH.'/images/t1.png" alt="Inactive" width="15" border="0" />';
                                                $isTopMenu      = "Y";
                                            }

                                            $moduleName    = $sitepage['menu_name'];
                                            $categoryName  = $sitepage['categoryName'];
                                            $sub           = $sitepage['subCount'];
                                            $msgId         = $sitepage['categoryId'];
                                            ?>
                                            <tr id="<?php echo 'recordsArray_'.$msgId;?>">
                                                <td>
                                                    <input type="checkbox" name="selectMulti[]" value="<?php echo $sitepage['categoryId'];?>" class="case" />
                                                </td>
                                                
                                                <td width="60" scope="row"><?php echo $slNo;?></td>

                                                <td>
                                                    <?php
                                                    echo '<a href="index.php?pageType='.$this->_request['pageType'].'&dtls='.$this->_request['dtls'].'&dtaction=new&editid='.$sitepage['categoryId'].'&parentId='.$this->_request['parentId'].'&moduleId='.$this->_request['moduleId'].'">';
                                                        echo $categoryName;
                                                    echo '</a>'; 
                                                    ?>
                                                </td>
                                                <td><?php echo ($moduleName)? $moduleName : 'CMS';?></td>
                                                <td>
                                                    <?php
                                                    if($sub > 0)
                                                        echo '<a href="index.php?pageType='.$this->_request['pageType'].'&dtls='.$this->_request['dtls'].'&parentId='.$sitepage['categoryId'].'&moduleId='.$this->_request['moduleId'].'">
                                                            <img src="'.ADMIN_TMPL_PATH.'/images/showpage.png" alt="Show" title="Show Sub Pages" height="32" width="32" border="0" /> 
                                                            <span>['.$sub.']</span>
                                                        </a>';
                                                    else
                                                        echo '<a href="index.php?pageType='.$this->_request['pageType'].'&dtls='.$this->_request['dtls'].'&dtaction=new&moduleId='.$this->_request['moduleId'].'&parentId='.$sitepage['categoryId'].'">
                                                            <img src="'.ADMIN_TMPL_PATH.'/images/addpage.png" alt="Add" title="Add Sub Page" height="32" width="32" border="0" />
                                                        </a>';
                                                    ?>
                                                </td>

                                                <td class="table_img">
                                                    <?php
                                                    if(file_exists(MEDIA_FILES_ROOT.'/banner/thumb/'.$sitepage['categoryImage']) && $sitepage['categoryImage'])
                                                        echo '<img src="'.MEDIA_FILES_SRC.'/banner/thumb/'.$sitepage['categoryImage'].'" alt="'.$categoryName.'" title="'.$categoryName.'" height="32" width="32"  />';
                                                    else
                                                        echo '<img src="'.ADMIN_TMPL_PATH.'/images/noicon.png" alt="'.$categoryName.'" title="No Icon" height="32" width="32"  />';
                                                    ?>
                                                </td>

                                                <td width="195" class="last_li">
                                                    <div class="action_link">
                                                        <?php
                                                        if($this->session->read('UTYPE') == "A" && $sitepage['hiddenMenu'] == 'Y')
                                                            echo '<span class="status inactive"><i class="fa fa-times" title="Hidden"></i> Hidden</span>';
                                                            
                                                        echo $conStatus;
                                                        ?>
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
                
                <?php if($data['pages']) {?>
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
                                if($data['pageList']){
                                    echo '<div class="col-sm-8">';
                                    echo '<div class="pagination">';
                                    echo '<p class="total">Page '.$data['page'].' of '.$data['totalPage'].'</p>';
                                    echo '<div>'.$data['pageList'].'</div>';
                                    echo '</div>';
                                    echo '</div>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                <?php }?>
            </div>
            
        </div>
    </form>
</div>