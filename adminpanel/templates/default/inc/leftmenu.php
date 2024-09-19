<!-- Left Sidebar  -->
<div class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <?php
                if($this->session->read('UTYPE') == "A") {
                    ?>
                    <li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-tachometer"></i><span class="hide-menu">Modules</span></a>
                        <ul aria-expanded="false" class="collapse">
                            <li><a <?php if($this->_request['pageType'] == 'modules' && $this->_request['dtls'] == 'modules'){ echo 'class="active"';}?> href="index.php?pageType=modules&dtls=modules">Core Modules</a></li>
                            <li><a <?php if($this->_request['pageType'] == 'modules' && $this->_request['dtls'] == 'plugins'){ echo 'class="active"';}?> href="index.php?pageType=modules&dtls=plugins">Plugins</a></li>
                        </ul>
                    </li>
                    <li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-users"></i><span class="hide-menu">Administrators</span></a>
                        <ul aria-expanded="false" class="collapse">
                            <li><a href="index.php?pageType=modules&dtls=adminuser">Admin User</a></li>
                        </ul>
                    </li>
                    <?php 
                }

                if(is_array($data) && sizeof($data) > 0) {
                    foreach($data as $key=>$md) {
                    
                        if($md['isDefault'] != $data[$key-1]['isDefault'] && $key > 0){
                            echo '<li class="nav-label">Plugins</li>';
                        }
                        
                        if($md['isDefault'])
                            $this->moduleCount++;
                        else
                            $this->pluginCount++;
                        
                        if($md['menu_id']==1) { // CMS pages
                            ?>
                            <li <?php if($this->_request['pageType'] == $md['parent_dir']) echo 'class="active"';?>>
                                <a class="has-arrow  " href="#" aria-expanded="false">
                                    <?php 
                                    if(file_exists(MEDIA_MODULE_ROOT.'/thumb/'.$md['menu_image']) && $md['menu_image'])
                                        echo '<img src="'.MEDIA_MODULE_SRC.'/thumb/'.$md['menu_image'].'" alt="'.$md['menu_name'].'"/>';
                                    elseif(file_exists(PLUGINS.DS.$md['parent_dir'].DS.$md['menu_image']) && $md['menu_image'])
                                        echo '<img src="'.SITE_LOC_PATH.DS.PLUGINS_PATH.DS.$md['parent_dir'].DS.$md['menu_image'].'" alt="'.$md['menu_name'].'"/>';
                                        
                                    echo '<span class="hide-menu">'.$md['menu_name'].'</span>';
                                    ?>
                                </a>
                                <ul aria-expanded="false" class="collapse">
                                    <?php	
                                    foreach($md['children'] as $child) {
                                        ?>
                                        <li>
                                            <a href="index.php?pageType=content&dtls=content&editid=<?php echo $child['categoryId'];?>" <?php if($this->_request['pageType'] == 'content' && $this->_request['editid'] == $child['categoryId']){ echo 'class="active"';}?> title="<?php echo $child['categoryName'];?>">
                                                <?php echo $child['categoryName'];?>
                                            </a>
                                        </li>
                                        <?php
                                    }
                                    ?>
                                    <li><a href="index.php?pageType=content&dtls=content&editid=uncategorized" <?php if($this->_request['pageType'] == 'content' && $this->_request['editid'] == 'uncategorized'){ echo 'class="active"';}?>>Uncategorized</a></li>
                                </ul>
                            </li>				
                            <?php
                        }
                        else {
                            ?>				
                            <li <?php echo ($this->_request['pageType'] == $md['parent_dir'])? 'class="active"':'';?>>
                                <a class="has-arrow  " href="javascript:void(0);" aria-expanded="false">
                                    <?php 
                                    if(file_exists(MEDIA_MODULE_ROOT.'/thumb/'.$md['menu_image']) && $md['menu_image'])
                                        echo '<img src="'.MEDIA_MODULE_SRC.'/thumb/'.$md['menu_image'].'" alt="'.$md['menu_name'].'"/>';
                                    elseif(file_exists(PLUGINS.DS.$md['parent_dir'].DS.$md['menu_image']) && $md['menu_image'])
                                        echo '<img src="'.SITE_LOC_PATH.DS.PLUGINS_PATH.DS.$md['parent_dir'].DS.$md['menu_image'].'" alt="'.$md['menu_name'].'"/>';
                            
                                    echo '<span class="hide-menu">'.$md['menu_name'].'</span>';
                                    ?>
                                </a>
                                <ul aria-expanded="false" class="collapse">			
                                    <?php 
                                    foreach($md['children'] as $child) {
    
                                        if($child['menu_id'] == 100) {
                                            ?>
                                            <li>
                                                <a href="index.php?pageType=<?php echo $child['parent_dir'];?>&dtls=pages&dtaction=new&moduleId=<?php echo $child['menu_id'];?>" <?php echo ($this->_request['moduleId'] == $child['menu_id'])? 'class="active"':'';?>>
                                                    <?php echo $child['menu_name'];?>
                                                </a>
                                            </li>
                                            <?php
                                        }
                                        else {
                                            ?>
                                            <li>
                                                <a href="index.php?pageType=<?php echo $child['parent_dir'];?>&dtls=<?php echo $child['child_dir'];?>&moduleId=<?php echo $child['menu_id'];?>" <?php echo ($this->_request['moduleId'] == $child['menu_id'])? 'class="active"':'';?>>
                                                    <?php echo $child['menu_name'];?>
                                                </a>
                                            </li>
                                            <?php
                                        }
                                    }?>
                                </ul>
                            </li>					
                            <?php				 
                        }
                    }
                }
                
                ?>
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</div>
<!-- End Left Sidebar  -->