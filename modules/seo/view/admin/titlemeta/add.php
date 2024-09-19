<?php
defined('BASE') OR exit('No direct script access allowed.');
if($data['titlemeta']) {

	$IdToEdit                  = $data['titlemeta']['titleandMetaId'];
	$pageTitleText             = $data['titlemeta']['pageTitleText'];
	$metaTag                   = $data['titlemeta']['metaTag'];
	$metaDescription           = $data['titlemeta']['metaDescription'];
    $metaRobots                = explode(', ', $data['titlemeta']['metaRobots']);
    $metaRobotsIndex           = $metaRobots[0];
    $metaRobotsFollow          = $metaRobots[1];
	$titleandMetaUrl           = $data['titlemeta']['titleandMetaUrl'];
	$canonicalUrl              = $data['titlemeta']['canonicalUrl'];
	$ogImage                   = $data['titlemeta']['ogImage'];
    
} else {
    $titleandMetaUrl           = $this->_request['titleandMetaUrl'];
    
    $pageTitleText             = $this->_request['pageTitleText'];
	$metaTag                   = $this->_request['metaTag'];
	$metaDescription           = $this->_request['metaDescription'];
	
    $canonicalUrl              = $this->_request['canonicalUrl'];
    
    $metaRobotsIndex           = $this->_request['metaRobotsIndex'];
    $metaRobotsFollow          = $this->_request['metaRobotsFollow'];
}
?>
<div class="container-fluid">
    <?php
    if($data['act']['message'])
        echo ($data['act']['type'] == 1)? '<div class="alert alert-success">'.$data['act']['message'].'</div>':'<div class="alert alert-danger">'.$data['act']['message'].'</div>';
    ?>
    
    <div>
        <form name="modifycontent" action="" method="post" enctype="multipart/form-data" id="form">
            <div class="row">
                <div class="col-sm-8 contentL">
                    <div class="card">
                        <div class="card-body">
                            
                            <div class="form-group">
                                <label>Page URL * [ex. /page-name/]</label>
                                <input type="text" name="titleandMetaUrl" value="<?php echo $titleandMetaUrl;?>" class="form-control">
                            </div>
                            
                            <div class="form-group">
                                <label>Page Title*</label>
                                <div class="textlimit">
                                    <textarea name="pageTitleText" class="form-control"><?php echo $pageTitleText;?></textarea>
                                    <div class="charcount">(<?php echo strlen($pageTitleText);?> characters)</div>
                                </div>
                            </div>
                            
                            
                            <div class="form-group">
                                <label>Meta Keyword</label>
                                <textarea name="metaTag" class="form-control" style="height:114px;"><?php echo $metaTag;?></textarea>
                            </div>

                            <div class="form-group">
                                <label>Meta Description</label>
                                <textarea name="metaDescription" class="form-control" style="height:114px;"><?php echo $metaDescription;?></textarea>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label>Canonical URL </label>
                                
                                <div class="alert alert-info">
                                    Leave blank if canonical URL is same as page URL else put full URL.
                                    <br>
                                    <em>Ex. http://www.coanonicalurl.domain/lorem/ipsum/</em>
                                </div>
                                
                                <input type="text" name="canonicalUrl" value="<?php echo $canonicalUrl;?>" class="form-control">
                            </div>
                                
                        </div>
                    </div>
                </div>
                
                <div class="col-sm-4 contentS">
                    <?php if($this->_request['editid']){?>
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="m-b-0 w-100">
                                        <a style="line-height:36px;" href="<?php echo SITE_LOC_PATH.'/'.$titleandMetaUrl;?>" target="_blank"><i class="fa fa-external-link"></i> Visit Page</a>

                                        <a href="index.php?pageType=<?php echo $this->_request['pageType'];?>&dtls=<?php echo $this->_request['dtls'];?>&dtaction=add&moduleId=<?php echo $this->_request['moduleId'];?>" class="btn btn-default pull-right">Add New</a>
                                    </label>
                                </div>
                            </div>
                        </div>
                    <?php }?>

                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Robots Index</label>
                                            <select name="metaRobotsIndex" class="form-control">
                                                <option value="default" <?php if($metaRobotsIndex == 'default') echo 'selected="selected"';?>>Default</option>
                                                <option value="index" <?php if($metaRobotsIndex == 'index') echo 'selected="selected"';?>>index</option>
                                                <option value="noindex" <?php if($metaRobotsIndex == 'noindex') echo 'selected="selected"';?>>noindex</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Robots Follow</label>
                                            <select name="metaRobotsFollow" class="form-control">
                                                <option value="follow" <?php if($metaRobotsFollow == 'follow') echo 'selected="selected"';?>>follow</option>
                                                <option value="nofollow" <?php if($metaRobotsFollow == 'nofollow') echo 'selected="selected"';?>>nofollow</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label>OG Image</label>
                                
                                <input type="file" name="ogImage" class="form-control">
                            
                                <?php if($ogImage && file_exists(MEDIA_FILES_ROOT.$ogImage)) {?>
                                <div class="table_img m-t-10">
                                    <img src="<?php echo MEDIA_FILES_SRC.$ogImage;?>">
                                </div>
                            
                                <label class="btn btn-sm btn-danger float-right m-t-10 deleteGallery">
                                    <input type="radio" name="DeleteFile" value="ogImage" onclick="deleteConfirm('warning','Are you sure to delete?');"> <span>Delete</span>
                                </label>
                                <?php }?>
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
                            
                            <input type="hidden" name="IdToEdit" value="<?php echo $IdToEdit;?>" />
                            <input type="hidden" name="SourceForm" value="addTitleMeta" />
                            <button type="submit" name="Save" value="Save" class="btn btn-info login_btn">Save</button>

                            <button type="button" name="Cancel" value="Close" onclick="location.href='<?php echo SITE_ADMIN_PATH;?>'" class="btn btn-default m-l-15">Close</button>   
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    function deleteConfirm(msgtype,title){
        swal({
            title: title,
            text: "",
            type: msgtype,
            showCancelButton: true,
            confirmButtonColor: "#ef5350",
            confirmButtonText: "Yes, delete it!!",
            closeOnConfirm: false
        },
        function(){
            $('#form').submit();
        });
    }
</script>