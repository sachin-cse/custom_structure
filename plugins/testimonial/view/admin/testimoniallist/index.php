<?php
defined('BASE') OR exit('No direct script access allowed.');

if($data['linkedPages']) {
    ?>
    <div class="container-fluid">
        <?php
        if($data['act']['message'])
            echo ($data['act']['type'] == 1)? '<div class="alert alert-success">'.$data['act']['message'].'</div>':'<div class="alert alert-danger">'.$data['act']['message'].'</div>';
        ?>

        <div class="row">
            <div class="col-sm-9">
                <div class="card">
                    <div class="card-body">
                        <form name="searchForm" action="" method="post">
                            <div class="form-inline">
                                <div class="form-group">
                                    <input type="text" name="searchText" value="<?php echo $this->session->read('searchText');?>" placeholder="Search" class="form-control">
                                </div>
                                
                                <div class="form-group">
                                    <select name="searchStatus" class="form-control">
                                        <option value="">Status</option>
                                        <option value="Y" <?php if ($this->session->read('searchStatus') == 'Y') echo 'selected';?>>Active</option>
                                        <option value="N" <?php if ($this->session->read('searchStatus') == 'N') echo 'selected';?>>Inactive</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <select name="searchShowcase" class="form-control" style="width:110px;">
                                        <option value="">All</option>
                                        <option value="Y" <?php if ($this->session->read('searchShowcase') == 'Y') echo 'selected';?>>Showcase Items</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <select name="searchPage" class="form-control" style="width:95px">
                                        <option value="">All Pages</option>
                                        <?php
                                        foreach($data['linkedPages'] as $linkedPage) {
                                            echo '<option value="'.$linkedPage['categoryId'].'" '.(($this->session->read('searchPage') == $linkedPage['categoryId']) ? 'selected' : '').'>'.$linkedPage['categoryName'].'</option>';
                                        }
                                        ?>
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

            <div class="col-sm-3">
                <div class="card">
                    <div class="card-body text-center">
                        <a href="<?php echo SITE_ADMIN_PATH.'/index.php?pageType='.$this->_request['pageType'].'&dtls='.$this->_request['dtls'].'&dtaction=add&moduleId='.$this->_request['moduleId'];?>" class="btn btn-info">Add Testimonial</a>
                    </div>
                </div>
            </div>
        </div>
            
        <div>
            <form action="" method="post">
                <div class="row">
                
                    <div class="col-sm-12">
                        <div class="card p-0">
                            <div class="card-body">
                                
                                <div class="table-responsive">
                                    <?php
                                    if($data['review']) {
                                        ?>
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th width="40"><input class="selectall" name="toggle" type="checkbox"></th>
                                                    <th width="40">Sl.</th>
                                                    <th width="30"></th>
                                                    <th colspan="2">Testimonial</th>
                                                    <th width="250"><div class="alert alert-success">Records Found: <?php echo $data['rowCount'];?></div></th>
                                                </tr>
                                            </thead>
                                            
                                            <tbody class="swap">
                                                <?php
                                                $slNo = ($this->_request['page'] > 1) ? (($this->_request['page'] - 1) * $data['limit']) + 1 : 1;
                                                foreach($data['review'] as $review) {
                                                    
                                                    if($review['status'] == 'Y')
                                                        $status  = '<span class="status"><i class="fa fa-check" title="Active"></i> Active</span>';
                                                    else
                                                        $status  = '<span class="status inactive"><i class="fa fa-times" title="Inactive"></i> Inactive</span>';
                                                    
                                                    ?>
                                                    <tr id="<?php echo 'recordsArray_'.$review['testimonialId'];?>">
                                                        <td width="40">
                                                            <input type="checkbox" name="selectMulti[]" value="<?php echo $review['testimonialId'];?>" class="case" />
                                                        </td>
                                                        
                                                        <td width="40" scope="row"><?php echo $slNo;?></td>
                                                        
                                                        <td width="30" scope="row"><?php echo ($review['isShowcase'] == 'Y') ? '<i class="fa fa-star" title="Showcased"></i>' : '';?></td>

                                                        
                                                        <td width="40" class="table_img">
                                                            <?php
                                                            if($review['testimonialImage'] && file_exists(MEDIA_FILES_ROOT.DS.$this->_request['pageType'].DS.'thumb'.DS.$review['testimonialImage'])) 
                                                                echo '<img src="'.MEDIA_FILES_SRC.DS.$this->_request['pageType'].DS.'thumb'.DS.$review['testimonialImage'].'" alt="'.$data['authorName'].'"/>';
                                                            else 
                                                                echo '<img src="..'.DS.PLUGINS_PATH.DS.$this->_request['pageType'].DS.'media'.DS.'noimage.png" alt="'.$review['authorName'].'" >';
                                                            ?>
                                                        </td>
                                                        

                                                        <td>
                                                            <a href="index.php?pageType=<?php echo $this->_request['pageType'];?>&dtls=<?php echo $this->_request['dtls'];?>&dtaction=add&editid=<?php echo $review['testimonialId'];?>&moduleId=<?php echo $this->_request['moduleId'];?>">
                                                                <?php echo $review['authorName'];?>
                                                            </a>
                                                        </td>
                                                        
                                                        <td width="250" class="last_li">
                                                            <div class="action_link">
                                                                <?php echo $status;?>
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
                        
                        <?php if($data['review']) { ?>
                            <div class="card m-t-20">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-5 pull-right">
                                            <div class="last_li form-inline">
                                                <select name="multiAction" class="form-control multi_action">
                                                    <option value="">Select</option>
                                                    <option value="1">Active</option>
                                                    <option value="2">Inactive</option>
                                                    <option value="3">Delete</option>
                                                    <option value="4">Add to Showcase</option>
                                                    <option value="5">Remove from Showcase</option>
                                                </select>  
                                                <input type="hidden" name="SourceForm" value="multiAction">
                                                <button type="submit" name="Save" value="Apply" class="btn btn-info m-l-10">Apply</button>
                                            </div>
                                        </div>
                                        <?php
                                        if($data['pageList']){
                                            echo '<div class="col-sm-7">';
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
    </div>
    <?php
}
else{
    echo '<div class="container-fluid">
        <div class="norecord alert alert-warning text-center p-t-30 p-b-35">
            <div class="m-b-20">There is no page linked with this module.</div>
            <a href="'.SITE_ADMIN_PATH.'/index.php?pageType=sitepage&dtls=pages&dtaction=new&moduleId=100" class="btn btn-info btn-sm m-r-10">Add Page</a> or
            <a href="'.SITE_ADMIN_PATH.'/index.php?pageType=sitepage&dtls=pages&moduleId=101" class="btn btn-info btn-sm m-l-10">View Pages</a>
        </div>
    </div>';
}
?>