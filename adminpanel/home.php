<div class="row page-titles">
    <div class="col-sm-5 align-self-center"><h3 class="text-primary">Dashboard</h3></div>
    <div class="col-sm-7 align-self-center">
        
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <div class="card hover p-0 unitCount">
                <a href="<?php echo SITE_ADMIN_PATH.'/index.php?pageType=sitepage&dtls=pages&moduleId=101';?>" class="p-30">
                    <div class="media">
                        <div class="media-left meida media-middle">
                            <span><i class="fa fa-files-o f-s-40 color-warning"></i></span>
                        </div>
                        <div class="media-body media-text-right">
                            <h2><?php echo $data['body']['pageCount'];?></h2>
                            <p class="m-b-0"><?php echo ($data['body']['pageCount'] > 1)? 'Main Pages' : 'Main Page';?></p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card hover p-0 unitCount">
                <div class="p-30">
                    <div class="media">
                        <div class="media-left meida media-middle">
                            <span><i class="fa fa-file-text-o f-s-40 color-info"></i></span>
                        </div>
                        <div class="media-body media-text-right">
                            <h2><?php echo $data['body']['contentCount'];?></h2>
                            <p class="m-b-0"><?php echo ($data['body']['contentCount'] > 1)? 'Contents' : 'Content';?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php 
        

        
        $moduleCount  = count($data['navigation']);?>
        <div class="col-md-3">
            <div class="card hover p-0 unitCount">
                <div class="p-30">
                    <div class="media">
                        <div class="media-left meida media-middle">
                            <span><i class="fa fa-cubes f-s-40 color-primary"></i></span>
                        </div>
                        <div class="media-body media-text-right">
                            <h2><?php echo $this->pluginCount;?></h2>
                            <p class="m-b-0"><?php echo ($this->pluginCount > 1)? 'Plugins' : 'Plugin';?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 lastlogin">
            <div class="card p-30 unitCount">
                <div class="media">
                    <div class="media-left meida media-middle">
                        <span><i class="fa fa-clock-o f-s-40 color-danger"></i></span>
                    </div>
                    <div class="media-body media-text-right">
                        <h2><?php
                            if(date('jS M, Y', strtotime($this->session->read('LASTLOGIN'))) == date('jS M, Y'))
                                echo 'Today';
                            elseif(date('jS M, Y', strtotime($this->session->read('LASTLOGIN'))) == date('jS M, Y', strtotime('-1 day')))
                                echo 'Yesterday';
                            else
                                echo date('jS M, Y', strtotime($this->session->read('LASTLOGIN')));
                            echo '<br>'.date('jS M, Y h:i A', strtotime($this->session->read('LASTLOGIN')));
                            ?></h2>
                        <p class="m-b-0">Last Login</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-8">
            <div class="card">
                <div class="card-body">
                    <iframe src="<?php echo SITE_LOC_PATH;?>" style="width:100%; border:none; height:350px;"></iframe>
                </div>
            </div>
        </div>
        
        <div class="col-sm-4">
            <div class="card">
                <div class="card-body">
                    <h2 class="text-center"><?php echo SITE_NAME;?></h2> 
                    <p class="text-center"><a href="<?php echo SITE_LOC_PATH;?>" target="_blank"><?php echo SITE_LOC_PATH;?></a></p>
                    <hr>
                    <p><?php echo nl2br(SITE_ADDRESS);?></p>
                    <a href="<?php echo SITE_ADMIN_PATH.'/index.php?pageType=modules&dtls=settings&dtaction=configuration';?>" class="btn btn-info btn-xs" style="color:#fff;">edit</a>
                </div>
            </div>
        </div>
    </div>
    
</div>