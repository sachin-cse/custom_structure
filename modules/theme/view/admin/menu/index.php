<?php defined('BASE') OR exit('No direct script access allowed.');?>
<div class="container-fluid">
    <?php
    if($data['act']['message'])
        echo ($data['act']['type'] == 1)? '<div class="alert alert-success">'.$data['act']['message'].'</div>':'<div class="alert alert-danger">'.$data['act']['message'].'</div>';
    
    if($data['navCount']) { ?>
        <div class="card">
            <div class="card-body">
                <form action="" method="post">
                    <div class="form-inline pull-left">
                        <div class="form-group">
                            <label>Select a menu to edit</label>
                            <select name="IdToEdit" class="form-control">
                                <?php
                                foreach($data['navList'] as $key=>$nav) {
                                    if($key == $data['navData']['id'])
                                        echo '<option value="'.$key.'" selected>'.$nav['name'].'</option>';
                                    else
                                        echo '<option value="'.$key.'">'.$nav['name'].'</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <input type="hidden" name="SourceForm" value="goToMenu" />
                        <button type="submit" name="Save" class="btn btn-info">Go</button>
                    </div>

                    <a href="<?php echo SITE_ADMIN_PATH.'/index.php?pageType='.$this->_request['pageType'].'&dtls='.$this->_request['dtls'].'&menuaction=add&moduleId='.$this->_request['moduleId'];?>" class="m-t-10 pull-right newMenu">Create New Menu</a>
                </form>
            </div>
        </div>
    <?php }?>

    <div class="row">        
        <div class="col-sm-3 contentS <?php echo (!$data['navCount']) ? 'disable' : '';?>">
            <div class="card p-0">
                <div class="card-body">
                    <form name="searchForm" action="" method="post">
                        <ul class="menuTree">
                            <?php
                            if($data['navCMS'])
                                echo '<li class="opened" data-mod="0"><span class="ques">CMS</span>'.$data['navCMS'].'<div class="text-right p-r-20 p-b-20"><span class="btn btn-default btn-sm addToMenu">Add to Menu</span></div></li>';
                            
                            foreach($data['navModules'] as $navPage) {
                                echo '<li class="opened" data-mod="'.$navPage['menu_id'].'"><span class="ques p-b-0" data-id="'.$navPage['menu_id'].'">'.$navPage['menu_name'].'</span>';

                                if($navPage['children']){
                                    ?>
                                    <div class="p-20 modpane">
                                        <div class="form-group m-b-0">
                                            <select name="module" class="mod form-control">
                                                <option data-pd="<?php echo $navPage['children'][0]['parent_dir'];?>" data-cd="<?php echo $navPage['children'][0]['child_dir'];?>" value="0">Linked Page(s)</option>
                                                <?php foreach($navPage['children'] as $child){?>
                                                    <option data-pd="<?php echo $child['parent_dir'];?>" data-cd="<?php echo $child['child_dir'];?>" value="<?php echo $child['menu_id'];?>"><?php echo $child['menu_name'];?></option>
                                                <?php }?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" value="" class="srchmod form-control brd-b-only" placeholder="Search..." autocomplete="off" maxlength="40">
                                        </div>

                                        <ul class="respane"></ul>
                                        <div class="text-right"><button class="btn btn-default btn-sm addToMenu">Add to Menu</button></div>
                                    </div>
                                    <?php
                                }
                                echo '</li>';
                            }
                            ?>
                        </ul>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-sm-9 contentL">

            <form name="modifycontent" action="" method="post" id="form">
                <div class="card">
                    <div class="card-body">
                        <div class="form-inline">
                            <div class="form-group">
                                <label>Menu Name</label>
                                <input type="text" name="menuName" value="<?php echo $data['navData']['name'];?>" placeholder="" class="form-control">
                            </div>
                            
                            <?php
                            if($data['navData']['name']) {
                                ?>
                                <div class="form-group">
                                    <em class="pull-right">Hook : <?php echo $data['navData']['permalink'];?>
                                        <span class="sweetBox" onclick="sAlert('Example', '$this-&gt;hook(\'theme\', \'nav\', array(\'permalink\'=&gt;\'<?php echo $data['navData']['permalink'];?>\', \'css\' =&gt; \'clearfix\'));', false);"><i class="fa fa-question-circle"></i></span>
                                    </em>
                                </div>
                                <?php
                            }
                            ?>

                            <input type="hidden" name="IdToEdit" value="<?php echo $data['navData']['id'];?>" />
                            <input type="hidden" name="SourceForm" value="createMenu" />
                            <?php if(!isset($data['navData']['id'])) { ?>
                                <button type="submit" name="Save" class="btn btn-info">Save</button>
                            <?php }?>
                        </div>
                    </div>
                </div>
                
                <?php
                if($data['navCount']) {
                    ?>
                    <div class="card">
                        <div class="card-body">
                            <?php
                            if($data['menu'])
                                echo $data['menu'];
                            else
                                echo '<ul class="menuList sortable"></ul><div class="alert alert-info m-t-15 noMenuAlert">Select and add menu items from left panel.</div>';
                            ?>
                        </div>
                    </div>
                
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>CSS for &lt;UL&gt; </label>
                                        <input type="text" name="ulCss" value="<?php echo $data['navData']['css'];?>" placeholder="clearfix" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <button type="button" name="Back" value="Back" onclick="location.href='index.php?pageType=<?php echo $this->_request['pageType'];?>&dtls=<?php echo $this->_request['dtls'];?>&moduleId=<?php echo $this->_request['moduleId'];?>'" class="btn btn-default m-r-15">Back</button>

                            <input type="hidden" name="menuTree" value="" class="mtree" />
                            <input type="hidden" name="menuHook" value="" class="<?php echo $data['navData']['permalink'];?>" />
                            <button type="submit" name="Save" value="Save" class="btn btn-info login_btn">Save</button>

                            <button type="button" name="Cancel" value="Close" onclick="location.href='<?php echo SITE_ADMIN_PATH;?>'" class="btn btn-default m-l-15">Close</button>
                            
                            <label class="btn btn-danger float-right deleteGallery">
                                <input type="radio" name="Delete" value="serviceCatalog" onclick="deleteConfirm('warning','Are you sure to delete?');" > <span>Delete Menu</span>
                            </label>
                        </div>
                    </div>
                    <?php 
                }
                elseif(!isset($this->_request['menuaction'])) {
                    echo '<div class="alert alert-info  m-t-15">There is no menu available for this theme. Please create one to setup website.</div>';
                }
                ?>
            </form>
        </div>
    </div>
</div>

<script>
    var id = -1;
    $(document).ready(function(){
        var itemArr = [];
        $('.menuTitle').each(function(){
            itemArr.push(parseInt($(this).parent('li').attr('data-id')));
        });

        $('.addToMenu').on('click', function (e){
            e.preventDefault();

            $(this).parents('li.opened').find('input[type="checkbox"]').each(function () {
                if($(this).is(':checked')){
                    var menuName = $(this).siblings('span').text(),
                        moduleName  = $(this).parents('li.opened').find('.ques').text(),
                        mod  = $(this).parents('li.opened').attr('data-mod');

                    if(itemArr.length)
                        var id = itemArr[itemArr.length-1] + 1;
                    else
                        var id = 1;

                    while($.inArray(id, itemArr) >= 0)
                        id = id + 1;

                    itemArr.push(id);

                    $('.menuList').append('<li data-act="new" data-mod="'+mod+'" data-id="'+id+'" id="list_'+id+'"><span class="menuTitle ui-sortable-handle"><i class="fa fa-arrows"></i> <span class="node">'+menuName+'</span> <em class="delete m-l-20"><i class="fa fa-trash"></i>delete</em> <em>'+moduleName+'</em></span> </li>');

                    var menuTree = $('ul.sortable').nestedSortable('toArray');
                    $('.mtree').val(JSON.stringify(menuTree));

                    toster(1,'Menu added successfully.','Success!');
                    $(this).prop('checked', false);

                    if($('.noMenuAlert').length) {
                        if($('.menuList').children('li').length)
                            $('.noMenuAlert').remove();
                    }
                }
            });
        });

        $('ul.sortable').nestedSortable({
            disableNesting: 'no-nest',
            forcePlaceholderSize: true,
            handle: 'span',
            helper:	'clone',
            items: 'li',
            maxLevels: 0,
            opacity: 0.6,
            placeholder: 'placeholder',
            revert: 250,
            tabSize: 25,
            listType: 'ul',
            tolerance: 'pointer',
            toleranceElement: '> span',
            create: function(event, ui) {
                var menuTree = $('ul.sortable').nestedSortable('toArray');
                $('.mtree').val(JSON.stringify(menuTree));
                //console.log(menuTree);
            },
            stop: function(event, ui) {
                var menuTree = $('ul.sortable').nestedSortable('toArray');
                $('.mtree').val(JSON.stringify(menuTree));
                //console.log(menuTree);
            }
        });

        $(document).on('click', '.delete', function(){
            var $this = $(this).parent('span');
            var $thisli = $(this).parent('span').parent('li');

            var id = $thisli.attr('data-id');
            
            $this.fadeOut(800);

            setTimeout(function(){
                $this.parent('li').children('ul').children('li').unwrap();
                $this.parent('li').children('li').unwrap();
                
                itemArr.pop(id);
                
                if (!$thisli.find('ul').length){
                    
                    if($thisli.siblings().length)
                        $thisli.remove();
                    else 
                    {
                        if($thisli.parent('ul').hasClass('menuList') == false)
                            $thisli.parent('ul').remove();
                        else
                            $thisli.remove();
                    }  
                }
                    
                $this.remove();
                
                setTimeout(function(){
                    var menuTree = $('ul.sortable').nestedSortable('toArray');
                    $('.mtree').val(JSON.stringify(menuTree));
                }, 200);
            }, 600);
        })
    });

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