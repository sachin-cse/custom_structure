<?php defined('BASE') OR exit('No direct script access allowed.');?>
<div class="container-fluid">
    <?php
    if($data['act']['message'])
        echo ($data['act']['type'] == 1)? '<div class="alert alert-success">'.$data['act']['message'].'</div>':'<div class="alert alert-danger">'.$data['act']['message'].'</div>';
    ?>
    
    <div>
        <form name="modifycontent" action="" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-sm-12 contentL">
                    
                    <div class="card">
                        <div class="card-title">
                            <h4>CSS File(s)</h4>
                        </div>
                        <div class="card-body">
                            <div class="resourceList nestable">
                                <span class="btn btn-default moreResource">Add</span>
                                <div class="dd" id="nestable1">
                                    <ol class="dd-list">
                                        <?php
                                        if($data['resource']['css']) {

                                            foreach($data['resource']['css'] as $key=>$resource) {
                                                ?>
                                                <li class="dd-item" data-id="<?php echo $key;?>">
                                                    <div class="dd-handle dd-handle"><i class="fa fa-arrows"></i></div>
                                                    <div class="dd-content">
                                                        <div class="form-group dd-item">
                                                            <div class="row">
                                                                <div class="col-sm-8">
                                                                    <input type="text" name="resourceCss[]" value="<?php echo $resource['file'];?>" class="form-control">
                                                                </div>
                                                                <div class="col-sm-2">
                                                                    <select name="resourceCssOpt[]" value="" class="form-control">
                                                                        <option value="" <?php echo ($resource['opt'] == '')? 'selected':'';?> >Default</option>
                                                                        <option value="preload" <?php echo ($resource['opt'] == 'preload')? 'selected':'';?>>Preload</option>
                                                                        <option value="prefetch" <?php echo ($resource['opt'] == 'prefetch')? 'selected':'';?>>Prefetch</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-sm-2 text-left p-l-0">
                                                                    <span class="fa fa-times removeResource" title="Delete Resource"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <li class="dd-item" data-id="0">
                                                <div class="dd-handle dd-handle"><i class="fa fa-arrows"></i></div>
                                                    <div class="dd-content">
                                                        <div class="form-group">
                                                            <div class="row">
                                                                <div class="col-sm-8">
                                                                    <input type="text" name="resourceCss[]" value="" class="form-control">
                                                                </div>
                                                                <div class="col-sm-2">
                                                                    <select name="resourceCssOpt[]" value="" class="form-control">
                                                                        <option value="">Default</option>
                                                                        <option value="preload">Preload</option>
                                                                        <option value="prefetch">Prefetch</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-sm-2 text-left p-l-0">
                                                                    <span class="fa fa-times removeResource" title="Delete Resource"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        <?php }?>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-title">
                            <h4>JS File(s)</h4>
                        </div>
                        <div class="card-body">
                            <div class="resourceList nestable">
                                <span class="btn btn-default moreResource">Add</span>
                                <div class="dd" id="nestable2">
                                    <ol class="dd-list">
                                        <?php
                                        if($data['resource']['js']) {

                                            foreach($data['resource']['js'] as $key=>$resource) {
                                                ?>
                                                <li class="dd-item" data-id="<?php echo $key;?>">
                                                    <div class="dd-handle dd-handle"><i class="fa fa-arrows"></i></div>
                                                    <div class="dd-content">
                                                        <div class="form-group">
                                                            <div class="row">
                                                                <div class="col-sm-4">
                                                                    <input type="text" name="resourceJs[]" value="<?php echo $resource['file'];?>" class="form-control">
                                                                </div>
                                                                <div class="col-sm-3">
                                                                    <select name="resourceJsOpt[]" value="" class="form-control">
                                                                        <option value="" <?php echo ($resource['opt'] == '')? 'selected':'';?>>Default</option>
                                                                        <option value="defer" <?php echo ($resource['opt'] == 'defer')? 'selected':'';?>>Defer</option>
                                                                        <option value="async" <?php echo ($resource['opt'] == 'async')? 'selected':'';?>>Async</option>
                                                                        <option value="wait" <?php echo ($resource['opt'] == 'wait')? 'selected':'';?>>Wait for Jquery</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-sm-3">
                                                                    <select name="resourceJsAppend[]" value="" class="form-control">
                                                                        <option value="head" <?php echo ($resource['append'] == 'head')? 'selected':'';?>>Append to Head</option>
                                                                        <option value="body" <?php echo ($resource['append'] == 'body')? 'selected':'';?>>Append to Body</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-sm-2 text-left p-l-0">
                                                                    <span class="fa fa-times removeResource" title="Delete Resource"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <li class="dd-item" data-id="0">
                                                <div class="dd-handle dd-handle"><i class="fa fa-arrows"></i></div>
                                                    <div class="dd-content">
                                                        <div class="form-group">
                                                            <div class="row">
                                                                <div class="col-sm-4">
                                                                    <input type="text" name="resourceJs[]" value="" class="form-control">
                                                                </div>
                                                                <div class="col-sm-3">
                                                                    <select name="resourceJsOpt[]" value="" class="form-control">
                                                                        <option value="">Default</option>
                                                                        <option value="defer">Defer</option>
                                                                        <option value="async">Async</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-sm-3">
                                                                    <select name="resourceJsAppend[]" value="" class="form-control">
                                                                        <option value="head">Append to Head</option>
                                                                        <option value="body">Append to Body</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-sm-2 text-left p-l-0">
                                                                    <span class="fa fa-times removeResource" title="Delete Resource"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        <?php }?>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-title">
                            <h4>Environment</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <select name="environment" class="form-control">
                                            <option value="0" <?php echo ($data['resource']['environment'] == 0)? 'selected':'';?>>Default</option>
                                            <option value="1" <?php echo ($data['resource']['environment'] == 1)? 'selected':'';?>>Cloudflare</option>
                                        </select>
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
                            <input type="hidden" name="SourceForm" value="addEditCssJs" />
                            <button type="submit" name="Save" value="Save" class="btn btn-info login_btn">Save</button>

                            <button type="button" name="Cancel" value="Close" onclick="location.href='<?php echo SITE_ADMIN_PATH;?>'" class="btn btn-default m-l-15">Close</button>   
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
.dd {position: relative; width: 100% !important;}
.dd-list {display: block; position: relative; margin: 0; padding: 0; list-style: none;}
.dd-item, .dd-empty, .dd-placeholder {display: block; position: relative; margin: 0 0 20px; padding: 0; min-height: 36px;}
.dd-item:last-child, .dd-empty:last-child, .dd-placeholder:last-child{margin:0;}
.dd-handle {
    position: absolute;
    margin: 0;
    left: 0;
    top: 0;
    cursor: move;
    width: 36px;
    height: 36px;
    text-align: center;
    line-height: 34px;
    overflow: hidden;
    border: 1px solid #e7e7e7;
    background: #ffffff;
    -webkit-border-radius: .25rem;
    border-radius: .25rem;
}
.dd-handle i{pointer-events: none;}
.dd-content{height: 36px; padding: 0 0 0 50px;}
.dd-placeholder, .dd-empty {background: #f2fbff; border: 1px dashed #b6bcbf; -webkit-border-radius: .25rem; border-radius: .25rem;}
.dd-empty {border: 1px dashed #bbb; min-height: 100px; background-color: #e5e5e5; background: 60px 60px; background-position: 0 0, 30px 30px;}
.dd-dragel { position: absolute; pointer-events: none; z-index: 9999;}
.dd-dragel > .dd-item .dd-handle { margin-top: 0;}
.dd-dragel .dd-handle {-webkit-box-shadow: 2px 4px 6px 0 rgba(0,0,0,0.1); box-shadow: 2px 4px 6px 0 rgba(0,0,0,0.1);}
</style>
<script src="<?php echo ADMIN_TMPL_PATH;?>/js/lib/jquery.nestable.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
    
        $(document).on('click', '.moreResource', function(){
            var more        = $(this),
                wrapper     = $(this).parents('.resourceList'),
                resourceRow = wrapper.find('.dd-list'),
                elem        = resourceRow.children('li:last-child'),
                rowItem     = elem.html(),
                elemId      = elem.attr('data-id');
                
            if(resourceRow.children('li:last-child').find('.removeResource').length == false)
                resourceRow.children('li:last-child').find('.col-sm-2.text-left').append('<span class="fa fa-times removeResource" title="Delete Resource"></span>');

            resourceRow.append('<li class="dd-item" data-id="'+(elemId + 1)+'">'+rowItem+'</li>');
            resourceRow.children('li:last-child').find('input').val('');
            resourceRow.children('li:last-child').find('select').each(function(){
                $(this).children('option').eq(0).prop('selected', true);
            });
        });

        $(document).on('click', '.removeResource', function(){
            var more        = $(this),
                rowList     = $(this).parents('.dd-list'),
                rowItem     = rowList.children('li:first-child').html();

            more.parents('.dd-item').remove();

            if(rowList.children().length < 1){
                rowList.append('<li class="dd-item" data-id="0">'+rowItem+'</li>');
                rowList.children('li:last-child').find('input').val('');
                rowList.children('li:last-child').find('select').each(function(){
                    $(this).children('option').eq(0).prop('selected', true);
                });
            }
        });
        
        $('#nestable1, #nestable2').nestable();
    });
</script>