<?php defined('BASE') OR exit('No direct script access allowed.');?>
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
    
    <div class="row">
        <div class="col-sm-9">
            <div class="card">
                <div class="card-body">
                    <form name="searchForm" action="" method="post">
                        <div class="form-inline">
                            <div class="form-group">
                                <input type="text" name="searchText" value="<?php echo $this->session->read('searchText');?>" placeholder="Search by Heading" class="form-control">
                            </div>
                            
                            <?php /*if($data['subPages']) {?>
                                <div class="form-group">
                                    <select name="searchPage" class="form-control">
                                        <option value="">For Page</option>
                                        <option value="<?php echo $data['pageData']['categoryId'];?>"><?php echo $data['pageData']['categoryName'];?></option>
                                        <?php
                                        foreach($data['subPages'] as $subPage) {
                                            if($this->session->read('searchPage') == $subPage['categoryId'])
                                                echo '<option value="'.$subPage['categoryId'].'" selected>'.$subPage['categoryName'].'</option>';
                                            else
                                                echo '<option value="'.$subPage['categoryId'].'">'.$subPage['categoryName'].'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            <?php }*/?>
                            
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
                    <a href="<?php echo SITE_ADMIN_PATH.'/index.php?pageType='.$this->_request['pageType'].'&dtls='.$this->_request['dtls'].'&dtaction=article&editid='.$this->_request['editid'];?>" class="btn btn-info">Add Content</a>
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
                                <?php if($data['contentList']) {?>
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th width="40"><input type="checkbox" class="selectall" name="toggle"></th>
                                            <th width="60">Sl.</th>
                                            <th>Heading</th>
                                            <th width="175"><div class="alert alert-success">Records Found: <?php echo $data['rowCount'];?></div></th>
                                        </tr>
                                    </thead>

                                    <tbody class="swap">
                                        <?php
                                        $slNo = ($this->_request['page'] > 1) ? (($this->_request['page'] - 1) * $data['limit']) + 1 : 1;

                                        foreach($data['contentList'] as $content) {
                                            if($content['contentStatus'] == 'Y')
                                                $conStatus  = '<span class="status"><i class="fa fa-check" title="Active"></i> Active</span>';
                                            else
                                                $conStatus  = '<span class="status inactive"><i class="fa fa-times" title="Inactive"></i> Inactive</span>';
                                            ?>
                                            <tr id="<?php echo 'recordsArray_'.$content['contentID'];?>">
                                                <td width="40">
                                                    <input type="checkbox" name="selectMulti[]" value="<?php echo $content['contentID'];?>" class="case" />
                                                </td>

                                                <td width="60" scope="row"><?php echo $slNo;?></td>

                                                <td>
                                                    <a href="index.php?pageType=<?php echo $this->_request['pageType'];?>&dtls=<?php echo $this->_request['dtls'];?>&dtaction=article&editid=<?php echo $this->_request['editid'];?>&contentID=<?php echo $content['contentID'];?>">
                                                        <?php echo $content['contentHeading'];?>
                                                    </a>
                                                </td>

                                                <td width="175" class="last_li">
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
                                <?php } else echo '<div class="norecord text-center">No Record Present</div>';?>
                            </div>

                        </div>
                    </div>

                    <?php if($data['contentList']) {?>
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
</div>