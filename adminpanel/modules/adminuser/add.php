<?php
if($data) {
    
    $username                 = $data['user']['username'];
    $fullname                 = $data['user']['fullname'];
    $email                    = $data['user']['email'];
    $phone                    = $data['user']['phone'];
    $address                  = $data['user']['address'];
    $status                   = $data['user']['status'];
}
else {
    
    $username                 = $this->_request['username'];
    $fullname                 = $this->_request['fullname'];
    $email                    = $this->_request['email'];
    $phone                    = $this->_request['phone'];
    $address                  = $this->_request['address'];
    $status                   = $this->_request['status'];
}

if($data['act']['step'] == 1 || !isset($data['act']['step']))
    include("siteinformation.php");
elseif($data['act']['step'] == 2)
    include("modulepermissions.php");
?>