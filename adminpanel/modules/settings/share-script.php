<?php
if($data['sharescript']) {
    
	$socialSrc           = $data['sharescript']['socialSrc'];
	$socialClass         = $data['sharescript']['socialClass'];
}
else {
    $socialSrc           = $this->_request['socialSrc'];
	$socialClass         = $this->_request['socialClass'];
}
?>
<div class="row page-titles">
    <div class="col-sm-5 align-self-center"><h3 class="text-primary">Social Share Script</h3></div>
    <div class="col-sm-7 align-self-center">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Settings</li>
            <li class="breadcrumb-item active">Social Share Script</li>
        </ol>
    </div>
</div>

<div class="container-fluid">
    <?php
    if(isset($data['act']['message']))
        echo (isset($data['act']['type']) && $data['act']['type'] == 1)? '<div class="alert alert-success">'.$data['act']['message'].'</div>':'<div class="alert alert-danger">'.$data['act']['message'].'</div>';
    ?>

    <div>
        <form name="modifycontent" action="" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-sm-12 contentL">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Script SRC</label>
                                <input type="text" name="socialSrc" value="<?php echo $socialSrc;?>" class="form-control" />
                            </div>

                            <div class="form-group">
                                <label>Content Class</label>
                                <input type="text" name="socialClass" value="<?php echo $socialClass;?>" class="form-control" placeholder="">
                            </div>
                            <hr>
                            <div class="alert alert-info">
                                <strong>Script SRC</strong> (copy the highlighted text from <a href="https://www.addthis.com/" target="_blank" rel="nofollow noopener noreferrer">addthis.com</a>)<br>
                                &lt;script type="text/javascript" <br>src="<mark><?php echo ($socialSrc) ? $socialSrc : '//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-52942fbb6efd1dfe';?></mark>"&gt;&lt;/script&gt;
                                <hr>
                                <strong>Content Class</strong> (copy the highlighted text from <a href="https://www.addthis.com/" target="_blank" rel="nofollow noopener noreferrer">addthis.com</a>)<br>
                                &lt;div class="<mark><?php echo ($socialClass) ? $socialClass : 'addthis_inline_share_toolbox_c70f';?></mark>"&gt;&lt;/div&gt;
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <button type="button" name="Back" value="Back" onclick="location.href='index.php?pageType=<?php echo $this->_request['pageType'];?>&dtls=<?php echo $this->_request['dtls'];?>'" class="btn btn-default m-r-15">Back</button>

                            <input type="hidden" name="SourceForm" value="updateSharescript" />
                            <button type="submit" name="Save" value="Save" class="btn btn-info login_btn">Save</button>

                            <button type="button" name="Cancel" value="Close" onclick="location.href='<?php echo SITE_ADMIN_PATH;?>'" class="btn btn-default m-l-15">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>