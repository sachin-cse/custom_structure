<?php
defined('BASE') OR exit('No direct script access allowed.');
$this->_request['IdToEdit'] = $data['default']['titleandMetaId'];
$titleandMetaUrl            = $data['default']['titleandMetaUrl'];
$pageTitleText              = $data['default']['pageTitleText'];
$metaTag                    = $data['default']['metaTag'];
$metaDescription            = $data['default']['metaDescription'];
$metaRobots                 = explode(', ', $data['default']['metaRobots']);
$metaRobotsIndex            = $metaRobots[0];
$metaRobotsFollow           = $metaRobots[1];
$titleandMetaType           = $data['default']['titleandMetaType'];
$status                     = $data['default']['status'];

$homeIdToEdit               = $data['home']['titleandMetaId'];
$hometitleandMetaUrl        = $data['home']['titleandMetaUrl'];
$homePageTitleText          = $data['home']['pageTitleText'];
$homeMetaTag                = $data['home']['metaTag'];
$homeMetaDescription        = $data['home']['metaDescription'];
$homeMetaRobots             = explode(', ', $data['home']['metaRobots']);
$homeMetaRobotsIndex        = $homeMetaRobots[0];
$homeMetaRobotsFollow       = $homeMetaRobots[1];
$hometitleandMetaType       = $data['home']['titleandMetaType'];
$homestatus                 = $data['home']['status'];
?>

<div class="container-fluid">
    <?php
    if($data['act']['message'])
        echo ($data['act']['type'] == 1)? '<div class="alert alert-success">'.$data['act']['message'].'</div>':'<div class="alert alert-danger">'.$data['act']['message'].'</div>';
    ?>
    
    <div>
        <form name="modifycontent" action="" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-title">
                            <h4>Default</h4>
                        </div>
                        <div class="card-body">
                            <hr class="m-t-0 m-b-20">
                            <div class="row">
                                
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Default Page Title *</label>
                                        <div class="textlimit">
                                            <textarea name="pageTitleText" class="form-control"><?php echo $pageTitleText;?></textarea>
                                            <div class="charcount">(<?php echo strlen($pageTitleText);?> characters)</div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Robots Index</label>
                                                <select name="metaRobotsIndex" class="form-control">
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
                                
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Default Meta Keyword</label>
                                        <textarea name="metaTag" class="form-control" style="height:70px;"><?php echo $metaTag;?></textarea>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Default Meta Description</label>
                                        <textarea name="metaDescription" class="form-control" style="height:70px;"><?php echo $metaDescription;?></textarea>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-sm-12">
                    <div class="card <?php echo ($data['same']) ? 'slideWrap' : '';?>">
                        <div class="card-title">
                            <h4 class="pull-left">Home</h4>
                            <label class="sameCheck"><input type="checkbox" name="sameCheck" value="Yes" <?php echo ($data['same']) ? 'checked' : '';?>> Same as Default</label>
                        </div>
                        <div class="card-body slideBox" <?php echo ($data['same']) ? '' : 'style="display:block;"';?>>
                            <hr class="m-t-20 m-b-20">
                            <div class="row">
                                
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Home Page Title *</label>
                                        <div class="textlimit">
                                            <textarea name="homePageTitleText" class="form-control"><?php echo $homePageTitleText;?></textarea>
                                            <div class="charcount">(<?php echo strlen($homePageTitleText);?> characters)</div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Home Robots Index</label>
                                                <select name="homeMetaRobotsIndex" class="form-control">
                                                    <option value="index" <?php if($homeMetaRobotsIndex == 'index') echo 'selected="selected"';?>>index</option>
                                                    <option value="noindex" <?php if($homeMetaRobotsIndex == 'noindex') echo 'selected="selected"';?>>noindex</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Home Robots Follow</label>
                                                <select name="homeMetaRobotsFollow" class="form-control">
                                                    <option value="follow" <?php if($homeMetaRobotsFollow == 'follow') echo 'selected="selected"';?>>follow</option>
                                                    <option value="nofollow" <?php if($homeMetaRobotsFollow == 'nofollow') echo 'selected="selected"';?>>nofollow</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Home Meta Keyword</label>
                                        <textarea name="homeMetaTag" class="form-control" style="height:70px;"><?php echo $homeMetaTag;?></textarea>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Home Meta Description</label>
                                        <textarea name="homeMetaDescription" class="form-control" style="height:70px;"><?php echo $homeMetaDescription;?></textarea>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <input type="hidden" name="homeIdToEdit" value="<?php echo $homeIdToEdit;?>" />
                            <input type="hidden" name="IdToEdit" value="<?php echo $this->_request['IdToEdit'];?>" />
                            <input type="hidden" name="SourceForm" value="defaultTitleMeta" />
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
    $(document).ready(function (){
        $('.textlimit').each(function () {
            var elem         = $(this).find('textarea'),
                charCount    = $(this).find('.charcount');
            
            elem.keyup(function (e) {
                var textLength      = elem.val().length;
               
                charCount.html('(' + textLength + ' characters)');
            });
        });
        
        $('.sameCheck input[type="checkbox"]').on('change', function () {
            if ($(this).is(":checked"))
                $('.slideBox').slideUp();
            else
                $('.slideBox').slideDown();
        });
    });
</script>