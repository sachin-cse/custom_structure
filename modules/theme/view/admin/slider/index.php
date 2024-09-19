<?php defined('BASE') OR exit('No direct script access allowed.');?>
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
                                <input type="text" name="searchText" value="<?php echo $this->session->read('searchText');?>" placeholder="Search by Name" class="form-control">
                            </div>
                            
                            <div class="form-group">
                                <select name="searchStatus" class="form-control">
                                    <option value="">Status</option>
                                    <option value="Y" <?php if ($this->session->read('searchStatus') == 'Y') echo 'selected';?>>Active</option>
                                    <option value="N" <?php if ($this->session->read('searchStatus') == 'N') echo 'selected';?>>Inactive</option>
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
                    <a href="<?php echo SITE_ADMIN_PATH.'/index.php?pageType='.$this->_request['pageType'].'&dtls='.$this->_request['dtls'].'&dtaction=add&moduleId='.$this->_request['moduleId'];?>" class="btn btn-info">Add New</a>
                </div>
            </div>
        </div>
    </div>
    
    <div>
        <form action="" method="post">
            <div class="row">
                <div class="col-sm-12">
                    
                    <?php
                    if($data['sliders']) {
                        ?>
                        <ul class="row swap">
                            <?php
                            $slNo = ($this->_request['page'] > 1) ? (($this->_request['page'] - 1) * $data['limit']) + 1 : 1;
                            foreach($data['sliders'] as $slider) {
                                if($slider['status'] == 'Y')
                                    $conStatus  = '<span class="status"><i class="fa fa-check" title="Active"></i> Active</span>';
                                else
                                    $conStatus  = '<span class="status inactive"><i class="fa fa-times" title="Inactive"></i> Inactive</span>';
                                ?>
                                <li class="col-sm-3" id="<?php echo 'recordsArray_'.$slider['id'];?>">
                                    <div class="card p-0">
                                        <div class="card-body">
                                            <div class="sliderBox gallerySliderBox bannerImage">
                                                <label class="sliderCheck"><input type="checkbox" name="selectMulti[]" value="<?php echo $slider['id'];?>" class="case" /><span></span></label>
                                                <a href="index.php?pageType=<?php echo $this->_request['pageType'];?>&dtls=<?php echo $this->_request['dtls'];?>&dtaction=add&editid=<?php echo $slider['id'];?>&moduleId=<?php echo $this->_request['moduleId'];?>">
                                                    <div class="sliderImg">
                                                        <?php
                                                        if($slider['imageName'] && file_exists(MEDIA_FILES_ROOT.'/slider/thumb/'.$slider['imageName']))
                                                            echo '<img src="'.MEDIA_FILES_SRC.'/slider/thumb/'.$slider['imageName'].'?t='.time().'" alt="'.$slider['sliderName'].'">';
                                                        /*else
                                                            echo '<img src="'.MEDIA_FILES_SRC.'/slider/thumb/'.$slider['imageName'].'" alt="'.$slider['sliderName'].'">';*/
                                                        ?>
                                                    </div>
                                                    <div class="sliderText"><?php echo $slider['sliderName'];?></div>
                                                </a>
                                                <div class="sliderbtm">
                                                    <?php echo $conStatus;?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <?php
                                $slNo++;
                            }
                            ?>
                        </ul>
                        <?php
                    }
                    else
                        echo '<div class="card"><div class="card-body"><div class="norecord text-center">No Record Present</div></div></div>';
                    
                    if($data['sliders']) {?>
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
                                            <input name="SourceForm" value="multiAction" type="hidden">
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
</div>