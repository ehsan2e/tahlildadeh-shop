<?php

    global $dependencies;
    $dependencies=array('checkInput','authHelper');
    
function main()
{
//    // محصول تصادفی در صفحه اول - کنار اسلایدر
//    $sql = "SELECT `id`,`name`,`price`,`desc` FROM `products` ORDER BY RAND() LIMIT 1;";
//    $result = dbQuery($sql);
//    $myresult = mysql_fetch_assoc($result);
//    //var_dump($myresult);
    

    
}

require(dirname(__FILE__).DIRECTORY_SEPARATOR.'Engine.php');