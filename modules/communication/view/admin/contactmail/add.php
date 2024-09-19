<?php
defined('BASE') OR exit('No direct script access allowed.');
if($data['mail']) {
    
	$IdToEdit                  = $data['mail']['contactId'];
	$name                      = $data['mail']['name'];
	$email                     = $data['mail']['email'];
	$phone                     = $data['mail']['phone'];
	$subject                   = $data['mail']['subject'];
	$comments                  = $data['mail']['comments'];
	$entryDate                 = $data['mail']['entryDate'];
}
?>

<div class="container-fluid">
    <?php
    if($data['act']['message'])
        echo ($data['act']['type'] == 1)? '<div class="alert alert-success">'.$data['act']['message'].'</div>':'<div class="alert alert-danger">'.$data['act']['message'].'</div>';
    ?>
    
    <div>
        <form name="modifycontent" action="" method="post">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="">
                                <div class="clearfix">
                                    <h2 class="contact_name"><?php echo $name;?></h2>
                                    <div class="contact_date"><?php echo date('jS M, Y h:i A', strtotime($entryDate));?></div>
                                </div>
                                <div class="form-group clearfix">
                                    <div class="iconDiv"><i class="fa fa-envelope"></i><?php echo $email;?></div>
                                    <?php
                                    if($phone)
                                        echo '<div class="iconDiv"><i class="fa fa-phone"></i> '.$phone.'</div>';
                                    if($subject)
                                        echo '<div class="iconDiv"><i class="fa fa-book"></i> '.$subject.'</div>';
                                    ?>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <?php echo $comments;?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <button type="button" name="Back" value="Back" onclick="location.href='index.php?pageType=<?php echo $this->_request['pageType'];?>&dtls=<?php echo $this->_request['dtls'];?>&moduleId=<?php echo $this->_request['moduleId'];?>'" class="btn btn-default m-r-15">Back</button>

                            <button type="button" name="Cancel" value="Close" onclick="location.href='<?php echo SITE_ADMIN_PATH;?>'" class="btn btn-default">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>