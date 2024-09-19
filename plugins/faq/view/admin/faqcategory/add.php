<?php defined('BASE') OR exit('No direct script access allowed.');
if($data['faqCat']) {

    $IdToEdit                      = $data['faqCat']['faq_cat_id'];
    $faqcatName                     = $data['faqCat']['faq_cat_title'];
    $faqTag =                           $data['faqCat']['faq_tag'];
    $catpermalink                     = $data['faqCat']['permalink'];
    $catfaqDescription            	   = $data['faqCat']['faq_cat_description'];
    $displayOrder                  = $data['faqCat']['displayOrder'];
    $status                        = $data['faqCat']['faq_cat_status'];
	$faqcatImage               = $data['faqCat']['faq_cat_img'];
	$qrystrPermalink			   = 'faq_cat_id != '.$IdToEdit;
}
else {
    
    $IdToEdit                      = $this->_request['faq_cat_id'];
    $faqcatName                   = $this->_request['faq_cat_title'];
    $catpermalink                     = $this->_request['permalink'];
    $faqTag =                           $this->_request['faq_tag'];
    $catfaqDescription            	   = $this->_request['faq_cat_description'];
    $displayOrder                  = $this->_request['displayOrder'];
    $status                        = $this->_request['faq_cat_status'];
	$faqcatImage                   = $this->_request['faq_cat_image'];
	$qrystrPermalink			   = 1;
}
// echo $faqcatName;
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
                                <label>Title *</label>
                                <input type="text" name="faq_cat_title" value="<?php echo $faqcatName;?>" class="form-control permalink copyToTitle" placeholder="" autocomplete="off" data-entity="<?php echo TBL_FAQ_CATEGORY;?>" data-qrystr="<?php echo $qrystrPermalink;?>" maxlength="255">
                            </div>

                            <div class="form-group">
                                <label>Tag *</label>
                                <input type="text" name="faq_tag" value="<?php echo $faqTag;?>" class="form-control copyToTitle" placeholder="" autocomplete="off" maxlength="255">
                            </div>
                            
                            <div class="form-group">
                                <label>Permalink *</label>
                                <input type="text" name="permalink" value="<?php echo $catpermalink;?>" class="form-control gen_permalink" placeholder="" autocomplete="off" maxlength="255">
                            </div>
                            
                            <div class="form-group">
                                <label>Category Description</label>
                                <textarea name="faq_cat_description" class="form-control editor"><?php echo $faqDescription;?></textarea>
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

                    <div class="card">
                        <div class="card-body">
                            <div class="form-group clearfix">
                                <label>Upload Image</label>
                                <input type="file" name="faq_cat_img" class="form-control" />
                                <?php
                                if($faqcatImage && file_exists(MEDIA_FILES_ROOT.'/faq/thumb/'.$faqcatImage)) {
                                    echo '<div class="table_img m-t-10"><img src="'.MEDIA_FILES_SRC.'/faq/thumb/'.$faqcatImage.'" alt="'.$faqcatImage.'"></div>';
                                    echo '<button type="submit" name="DeleteImg" class="btn btn-sm btn-danger float-right m-t-10">Delete Image</button>';
                                }
                                ?>
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
                            <input type="hidden" name="SourceForm" value="addEditFaqCate" />
                            <button type="submit" name="Save" value="Save" class="btn btn-info login_btn">Save</button>

                            <button type="button" name="Cancel" value="Close" onclick="location.href='index.php?pageType=<?php echo $this->_request['pageType'];?>&dtls=<?php echo $this->_request['dtls'];?>&moduleId=<?php echo $this->_request['moduleId'];?>'" class="btn btn-default m-l-15">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>