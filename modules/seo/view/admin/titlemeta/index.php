<?php defined('BASE') OR exit('No direct script access allowed.');?>
<script type="text/javascript">
    $(document).ready(function (){
        $('.textlimit').each(function () {
            var elem         = $(this).find('textarea'),
                charCount    = $(this).find('.charcount');
            
            elem.keyup(function (e) {
                var textLength      = elem.val().length;
               
                charCount.html('(' + textLength + ' characters)');
            });
        });
    });
</script>

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
                                <input type="text" name="searchText" value="<?php echo $this->session->read('searchText');?>" placeholder="Search by URL" class="form-control">
                            </div>
                            
                            <div class="form-group">
                                <select name="searchRobots" class="form-control">
                                    <option value="">Robots</option>
                                    <option value="index, follow" <?php if ($this->session->read('searchRobots') == 'index, follow') echo 'selected';?>>index, follow</option>
                                    <option value="index, nofollow" <?php if ($this->session->read('searchRobots') == 'index, nofollow') echo 'selected';?>>index, nofollow</option>
                                    <option value="noindex, follow" <?php if ($this->session->read('searchRobots') == 'noindex, follow') echo 'selected';?>>noindex, follow</option>
                                    <option value="noindex, nofollow" <?php if ($this->session->read('searchRobots') == 'noindex, nofollow') echo 'selected';?>>noindex, nofollow</option>
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
                    <a href="<?php echo SITE_ADMIN_PATH.'/index.php?pageType='.$this->_request['pageType'].'&dtls='.$this->_request['dtls'].'&dtaction=add&moduleId='.$this->_request['moduleId'];?>" class="btn btn-info">Add Title Meta</a>
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
                                if($data['titlemeta']) {
                                    ?>
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th width="40"><input class="selectall" name="toggle" type="checkbox"></th>
                                                <th width="60">Sl.</th>
                                                <th>Page Url</th>
                                                <th width="250"><div class="alert alert-success">Records Found: <?php echo $data['rowCount'];?></div></th>
                                            </tr>
                                        </thead>
                                        
                                        <tbody>
                                            <?php
                                            $slNo = ($this->_request['page'] > 1) ? (($this->_request['page'] - 1) * $data['limit']) + 1 : 1;
                                            foreach($data['titlemeta'] as $tm) {
                                                if($tm['metaRobots'] == 'index, follow')
                                                    $robots  = '<span class="status statusRobot">'.$tm['metaRobots'].'</span>';
                                                elseif($tm['metaRobots'] == 'noindex, nofollow')
                                                    $robots  = '<span class="status statusRobot inactive">'.$tm['metaRobots'].'</span>';
                                                else
                                                    $robots  = '<span class="status statusRobot warning">'.$tm['metaRobots'].'</span>';
                                                ?>
                                                <tr id="<?php echo 'recordsArray_'.$msgId;?>">
                                                    <td width="40">
                                                        <input type="checkbox" name="selectMulti[]" value="<?php echo $tm['titleandMetaId'];?>" class="case" />
                                                    </td>
                                                    
                                                    <td width="60" scope="row"><?php echo $slNo;?></td>

                                                    <td>
                                                        <a href="index.php?pageType=<?php echo $this->_request['pageType'];?>&dtls=<?php echo $this->_request['dtls'];?>&dtaction=add&editid=<?php echo $tm['titleandMetaId'];?>&moduleId=<?php echo $this->_request['moduleId'];?>">
                                                            <?php echo SITE_LOC_PATH.$tm['titleandMetaUrl'];?>
                                                        </a>
                                                    </td>

                                                    <td width="250" class="last_li">
                                                        <div class="action_link">
                                                            <?php echo $robots;?>
                                                            <a href="<?php echo SITE_LOC_PATH.$tm['titleandMetaUrl'];?>" target="_blank" class="btn btn-sm"><i class="fa fa-link"></i> Visit Page</a>
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
                    
                    <?php if($data['titlemeta']) { ?>
                        <div class="card m-t-20">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-4 pull-right">
                                        <div class="last_li form-inline">
                                            <select name="multiAction" class="form-control multi_action">
                                                <option value="">Select</option>
                                                <?php if($this->session->read('UTYPE') == "A") {?>
                                                    <option value="1">Active</option>
                                                    <option value="2">Inactive</option>
                                                <?php }?>
                                                <option value="3">Delete</option>
                                            </select>  
                                            <input type="hidden" name="SourceForm" value="multiAction">
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