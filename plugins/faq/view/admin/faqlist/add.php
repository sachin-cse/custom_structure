<?php
defined('BASE') OR exit('No direct script access allowed.');
if($data['faq']) {

    $IdToEdit                      = $data['faq']['faq_id'];
    $faqName                   	   = $data['faq']['faq_title'];
    $permalink                     = $data['faq']['permalink'];
    $faqDescription            	   = $data['faq']['faq_description'];
    $displayOrder                  = $data['faq']['displayOrder'];
    $status                        = $data['faq']['faq_status'];
	$faqCatId =                      $data['faq']['faq_cat_id'];
	$qrystrPermalink			   = 'faq_id != '.$IdToEdit;
}
else {
    
    $IdToEdit                      = $this->_request['faqId'];
    $faqName                   	   = $this->_request['faqName'];
    $permalink                     = $this->_request['permalink'];
    $faqDescription            	   = $this->_request['faqDescription'];
    $displayOrder                  = $this->_request['displayOrder'];
    $status                        = $this->_request['status'];
	$faqCatId =                      $this->_request['faq_cat_id'];
	$qrystrPermalink			   = 1;
}
?>
<div class="container-fluid">
    <?php
    if(isset($data['act']['message']))
        echo (isset($data['act']['type']) && $data['act']['type'] == 1)? '<div class="alert alert-success">'.$data['act']['message'].'</div>':'<div class="alert alert-danger">'.$data['act']['message'].'</div>';
    ?>
 
    <div>
        <form name="modifycontent" action="" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-sm-8 contentL">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Question *</label>
                                <input type="text" name="faqName" value="<?php echo $faqName;?>" class="form-control permalink copyToTitle" placeholder="" autocomplete="off" data-entity="<?php echo TBL_FAQ;?>" data-qrystr="<?php echo $qrystrPermalink;?>" maxlength="255">
                            </div>
                            
                            <div class="form-group">
                                <label>Permalink</label>
                                <input type="text" name="permalink" value="<?php echo  $permalink;?>" class="form-control gen_permalink" placeholder="" autocomplete="off" maxlength="255">
                            </div>

                            <div class="form-group">
                                <label>Select Category *</label>
                                <select name="faq_cat_id" class="form-control">
                                    <option value =''disabled selected>Select Category</option>
                                    <?php
                                    foreach($data['faqCat'] as $faqCat){
                                        ?>
                                            <option value="<?=$faqCat['faq_cat_id'];?>" <?php if($faqCat['faq_cat_id'] == $faqCatId) echo "selected";?>><?=$faqCat['faq_cat_title'];?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label>Answer *</label>
                                <textarea name="faqDescription" class="form-control editor"><?php echo $faqDescription;?></textarea>
                            </div>
                        </div> 
                    </div>
                </div>
                
                <div class="col-sm-4 contentS">
                    
                    <?php if($IdToEdit) {?>
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label class="m-b-0 w-100">
                                        
                                <a href="<?php echo 'index.php?pageType='.$this->_request['pageType'].'&dtls='.$this->_request['dtls'].'&dtaction='.$this->_request['dtaction'].'&moduleId='.$this->_request['moduleId'];?>" class="btn btn-default pull-right">Add New</a>
                                        </label>
                            </div>
                        </div>
                    </div>
                    <?php }?>
                    
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="Y" <?php if($status=='Y') echo 'selected'?>>Active</option>
                                    <option value="N" <?php if($status=='N') echo 'selected'?>>Inactive</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label>Display Priority</label>

                                <select name="displayOrder" class="form-control">
                                    <?php if($IdToEdit) {?>
                                    <option value="<?php echo $displayOrder;?>" >Stay as it is</option>
                                    <?php }?>
                                    <option value="T" <?php echo ($displayOrder == 'T')? 'selected':'';?>>Move to top</option>
                                    <option value="B" <?php echo ($displayOrder == 'B')? 'selected':'';?>>Move to bottom</option>
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
                            <button type="button" name="Back" value="Back" onclick="history.back(-1);" class="btn btn-default m-r-15">Back</button>
                            
                            <input type="hidden" name="IdToEdit" value="<?php echo $IdToEdit;?>" />
                            <input type="hidden" name="SourceForm" value="addEditFaq" />
                            <button type="submit" name="Save" value="Save" class="btn btn-info login_btn">Save</button>

                            <button type="button" name="Cancel" value="Close" onclick="location.href='index.php?pageType=<?php echo $this->_request['pageType'];?>&dtls=<?php echo $this->_request['dtls'];?>&moduleId=<?php echo $this->_request['moduleId'];?>'" class="btn btn-default m-l-15">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>