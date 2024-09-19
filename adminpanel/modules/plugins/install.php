<?php
if($data['module']) {
    
	$IdToEdit              = $data['module']['menu_id'];
	$parent_id             = $data['module']['parent_id'];
    $menu_name             = $data['module']['menu_name'];
	$displayOrder          = $data['module']['displayOrder'];
	$menu_image            = $data['module']['menu_image'];
}
else {
    
    $parent_id             = $this->_request['parent_id'];
    $menu_name             = $this->_request['menu_name'];
	$displayOrder          = $this->_request['displayOrder'];
	$menu_image            = $this->_request['menu_image'];
}
/*$proto  = (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')? 'https:':'http:';
$link   = $proto.base64_decode(PLUGINS_REPO_INFO);*/
$link           = base64_decode(PLUGINS_REPO_INFO);
$json           = file_get_contents($link);
$pluginsRepo    = json_decode($json, true);

?>
<div class="row page-titles">
    <?php $breadActive = ($this->_request['editid']!='') ? 'Edit Plugin' : 'Install Plugin' ;?>
    <div class="col-sm-5 align-self-center"><h3 class="text-primary"><?php echo $breadActive;?></h3></div>
    <div class="col-sm-7 align-self-center">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Modules</li>
            <li class="breadcrumb-item active"><?php echo $breadActive;?></li>
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
                <div class="col-sm-8 contentL">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Select Plugin</label>
                                <select name="plugin" class="form-control pluginsel">
                                    <option value="">--None--</option>
                                    <?php
                                    foreach($pluginsRepo['plugins'] as $plugin) {
                                            ?>
                                            <option value="<?php echo $plugin['path'];?>">
                                                <?php echo $plugin['name'];?>
                                            </option>
                                            <?php
                                    }
                                    ?>
                                </select>
                            </div>

                            
                        </div>
                    </div>
                    
                    <div class="avlplugins row"></div>
                </div>

                <div class="col-sm-4 contentS themepreview">
                    
                </div>
                
            </div>

            <div class="row">
                <div class="col-sm-8">
                    <div class="card">
                        <div class="card-body">
                            <button type="button" name="Back" value="Back" onclick="location.href='index.php?pageType=<?php echo $this->_request['pageType'];?>&dtls=<?php echo $this->_request['dtls'];?>'" class="btn btn-default m-r-15">Back</button>

                            <input type="hidden" name="SourceForm" value="uploadPlugin" />
                            <button type="submit" name="Save" value="Save" class="btn btn-info login_btn">Install</button>

                            <button type="button" name="Cancel" value="Close" onclick="location.href='<?php echo SITE_ADMIN_PATH;?>'" class="btn btn-default m-l-15">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('.pluginsel').on('change', function(){
            
            var	qrystr      = $(this).val();
            var respane     = $('.avlplugins');
            $('.themepreview').html('');
            respane.html('');
            
            if(qrystr != ''){
            
                $.ajax({
                    url:"./index.php?pageType=<?php echo $this->_request['pageType'];?>&dtls=<?php echo $this->_request['dtls'];?>",
                    type:'post',
                    data:{ajx_action:'getplugin', qrystr:qrystr},
                    success:function(res){
                        var iconPath = res["pluginPath"];
                        var transform = {'<>':'div', class:'col-sm-4 pageBoxWrap','html':[
                                            {'<>':'label', class:'card pageBox','html':[
                                                {'<>':'input', type:'radio', name:'theme', value:'${path}'},
                                                {'<>':'div', class:'pageContent', 'html':[
                                                    {'<>':'div', class:'pageIcon', html:'<img src="'+iconPath+'/${path}/${icon}" alt="">'},
                                                    {'<>':'div', class:'pageText', html:[
                                                        {'<>':'strong', html:'${name}'},
                                                        {'<>':'div', 'html':'${description}'},
                                                    ]}
                                                ]}
                                            ]}
                                        ]};

                        respane.json2html(res["plugins"]["plugins"], transform);
                        
                        if(respane.find('.pageBox').length){
                            respane.find('.pageBoxWrap').eq(0).find('input[type="radio"]').prop('checked',true);

                            var theme = respane.find('input[type="radio"]:checked').val();
                            
                            if(theme != ''){
                                var index = respane.find('input[type="radio"]:checked').parents('.pageBoxWrap').index();
                                
                                var transformTheme = {'<>':'div', class:'card pageBoxDetails','html':[
                                            {'<>':'div', class:'pageContent','html':[
                                                {'<>':'div', class:'pageImg imagePopup', html:'<img src="'+iconPath+'/${path}/${thumbnail}" alt="">'},
                                                {'<>':'div', class:'pageText', html:[
                                                    {'<>':'div', class:'pageIcon', html:'<img src="'+iconPath+'/${path}/${icon}" alt="">'},
                                                    {'<>':'strong', html:'${name}'},
                                                    {'<>':'div', 'html':'${description}'},
                                                    {'<>':'div', class:'m-t-5', 'html':[
                                                        {'<>':'div', 'html':'<strong>Size:</strong> ${size}'},
                                                        {'<>':'div', 'html':'<strong>Last update:</strong> ${lastUpdate}'},
                                                        {'<>':'div', class:'text-center m-t-10', 'html':[
                                                            {'<>':'a', href:'${preview}', target:'_blank', class:'btn btn-info btn-sm', 'html':'Preview'}
                                                        ]}
                                                    ]},
                                                ]}
                                            ]}
                                        ]};

                                $('.themepreview').json2html(res["plugins"]["plugins"][index], transformTheme);
                            }

                            $(document).on('change', '.pageBox input[name="theme"]', function (){
                                $('.themepreview').html('');
                                var theme = $(this).val();
                                
                                if(theme != ''){
                                    var index = respane.find('input[type="radio"]:checked').parents('.pageBoxWrap').index();
                                
                                    var transformTheme = {'<>':'div', class:'card pageBoxDetails','html':[
                                                {'<>':'div', class:'pageContent','html':[
                                                    {'<>':'div', class:'pageImg imagePopup', html:'<img src="'+iconPath+'/${path}/${thumbnail}" alt="">'},
                                                    {'<>':'div', class:'pageText', html:[
                                                        {'<>':'div', class:'pageIcon', html:'<img src="'+iconPath+'/${path}/${icon}" alt="">'},
                                                        {'<>':'strong', html:'${name}'},
                                                        {'<>':'div', 'html':'${description}'},
                                                        {'<>':'div', class:'m-t-5', 'html':[
                                                            {'<>':'div', 'html':'<strong>Size:</strong> ${size}'},
                                                            {'<>':'div', 'html':'<strong>Last update:</strong> ${lastUpdate}'},
                                                            {'<>':'div', class:'text-center m-t-10', 'html':[
                                                                {'<>':'a', href:'${preview}', target:'_blank', class:'btn btn-info btn-sm', 'html':'Preview'}
                                                            ]}
                                                        ]},
                                                    ]}
                                                ]}
                                            ]};

                                    $('.themepreview').json2html(res["plugins"]["plugins"][index], transformTheme);
                                }
                            });
                        }
                    }
                });
            }
            
        });
        
    });
</script>