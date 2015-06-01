<?php
    global $dependencies;
    $dependencies=array('checkInput','authHelper');
function main()
{
    if (hasPrivilege('customer')){
        
    }
    else {
        
        $url = BASE_URL.'signup';
        return array('redirect'=>$url);
        
       }
}

require(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'Engine.php');