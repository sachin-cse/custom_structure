<?php defined('BASE') OR exit('No direct script access allowed.');?>
<div class="container-fluid">
    <?php
    if($data['act']['message'])
        echo ($data['act']['type'] == 1)? '<div class="alert alert-success">'.$data['act']['message'].'</div>':'<div class="alert alert-danger">'.$data['act']['message'].'</div>';
    ?>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <form name="searchForm" action="" method="post">
                        <div class="form-inline">
                            <div class="form-group">
                                <input type="text" name="searchText" value="<?php echo $this->session->read('searchText');?>" placeholder="Search by Name / Email" class="form-control">
                            </div>
                            
                            <div class="form-group">
                                <select name="searchStatus" class="form-control">
                                    <option value="">Status</option>
                                    <option value="Y" <?php if ($this->session->read('searchStatus') == 'Y') echo 'selected';?>>Read</option>
                                    <option value="N" <?php if ($this->session->read('searchStatus') == 'N') echo 'selected';?>>Unread</option>
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
    </div>
    
    <div>
        <form action="" method="post">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card p-0">
                        <div class="card-body">
                            <div class="table-responsive">
                                <?php
                                if($data['mails']) {
                                    ?>
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th width="50"><input type="checkbox" class="selectall" name="toggle"></th>
                                                <th width="60">Sl.</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th class="text-right"></th>
                                                <th width="175"><div class="alert alert-success">Records Found: <?php echo $data['rowCount'];?></div></th>
                                            </tr>
                                        </thead>
                                        
                                        <tbody>
                                            <?php
                                            $slNo = ($this->_request['page'] > 1) ? (($this->_request['page'] - 1) * $data['limit']) + 1 : 1;
                                            foreach($data['mails'] as $mail) {
                                                if($mail['seen'] == '0000-00-00 00:00:00')
                                                    $seenStatus  = '<span class="status alert">Unread</span>';
                                                else
                                                    $seenStatus  = '';
                                                ?>
                                                <tr id="<?php echo 'recordsArray_'.$mail['contactId'];?>">
                                                    <td width="50">
                                                        <input type="checkbox" name="selectMulti[]" value="<?php echo $mail['contactId'];?>" class="case" />
                                                    </td>
                                                    
                                                    <td width="60" scope="row"><?php echo $slNo;?></td>

                                                    <td>
                                                        <a href="index.php?pageType=<?php echo $this->_request['pageType'];?>&dtls=<?php echo $this->_request['dtls'];?>&dtaction=add&editid=<?php echo $mail['contactId'];?>&moduleId=<?php echo $this->_request['moduleId'];?>">
                                                            <?php echo $mail['name'];?>
                                                        </a>
                                                    </td>
                                                    
                                                    <td><?php echo $mail['email'];?></td>
                                                    
                                                    <td align="right"><?php echo date('jS M, Y h:i A', strtotime($mail['entryDate']));?></td>

                                                    <td width="175">
                                                        <div class="action_link">
                                                            <?php echo $seenStatus;?>
                                                            
                                                            <a data-editid="<?php echo $mail['contactId'];?>" href="javascript:void(0);" target="_blank" class="btn btn-sm qv"><i class="fa fa-eye"></i> View</a>
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
                    
                    <?php if($data['mails']) {?>
                        <div class="card m-t-20">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-4 pull-right">
                                        <div class="last_li form-inline">
                                            <select name="multiAction" class="form-control multi_action">
                                                <option value="">Select</option>
                                                <option value="1">Mark as read</option>
                                                <option value="2">Mark as unread</option>
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

<script type="text/javascript">
    $(document).ready(function(){
        $('.qv').on('click', function(e){
            e.preventDefault();
            
            var link    = $(this);
            var editid  = link.attr('data-editid');
            $.ajax({
                url: '<?php echo SITE_ADMIN_PATH."/index.php?pageType=".$this->_request['pageType']."&dtls=".$this->_request['dtls']."&moduleId=".$this->_request['moduleId'];?>',
                type: 'post',
                data: { ajx_action: 'quickView', editid: editid },
                success: function (res) {
                    if(res['type'] == 1) {
                        var html = '';
                        
                        var dt    = new Date(res['mail'].entryDate);
                        dt        = dateFormat(dt, "dS mmm, yyyy h:MM TT");
                        
                        html += '<div class="mailView">';
                            html += '<span class="closeView"><i class="fa fa-times"></i> Close</span>';
                            html += '<div class="">';
                                html += '<div class="clearfix">';
                                    html += '<h2 class="contact_name">'+res['mail'].name+'</h2>';
                                    html += '<div class="contact_date">'+dt+'</div>';
                                html += '</div>';
                                html += '<div class="form-group clearfix">';
                                    html += '<div class="iconDiv"><i class="fa fa-envelope"></i>'+res['mail'].email+'</div>';
                                    if(res['mail'].phone)
                                        html += '<div class="iconDiv"><i class="fa fa-phone"></i> '+res['mail'].phone+'</div>';
                                    if(res['mail'].subject)
                                        html += '<div class="iconDiv"><i class="fa fa-book"></i> '+res['mail'].subject+'</div>';
                                html += '</div>';
                            html += '</div><hr>';
                            html += '<div class="form-group">'+res['mail'].comments+'</div>';
                        html += '</div>';

                        link.closest('tr').addClass('trSelected').siblings().removeClass('trSelected');
                        link.closest('tr').siblings('.trView').remove();

                        link.closest('tr').after('<tr class="trView"><td colspan="6">'+html+'</td></tr>');

                        $("html, body").animate({scrollTop:$('.trSelected').offset().top-60}, 800);
                    }
                }
            });
        });
        
        $(document).on('click', '.closeView', function(e){
            $(this).closest('tr').siblings().removeClass('trSelected');
            $(this).closest('tr').remove();
        });
    });
</script>