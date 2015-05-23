<?php

global $dbLink;
$dbLink = null;

/**
 * ایجاد کانکشن دیتابیس و
 * جلوگیری از کانکت شدن تکراری
 * 
 * 
 */
function dbConnect (){
    global $dbLink;
    if (!is_resource($dbLink)){
        $dbLink = mysql_connect(DB_HOST, DB_USERNAME, DB_PASSWORD);
        
        if($dbLink === false){
            die('Not onnect');
        }
        
        mysql_set_charset('utf8');
        mysql_select_db(DB_DB);
    }
    return $dbLink;
}

/**
 * 
 * @param type $sql
 * 
 */
function dbQuery ($sql){
    $link = dbConnect();
    $result = mysql_query($sql);
    $isASelectQuery=stripos($sql, 'select');
    if(!is_resource($result) && $isASelectQuery!==false){
        //print_r(mysql_error());
        die('جوابی از دیتابیس نیامد !');
    }elseif ($isASelectQuery===false && !$result) {
        echo ("{$sql}< br/>");
        print_r(mysql_error());
        die('خطا در اجرای کوئری !');
    }
    
    return $result;
}

/**
 * برای بستن کانکشن دیتابیس
 * 
 */
function dbClose (){
    global $dbLink;
    
    if (is_resource($dbLink)){
        mysql_close($dbLink);
        $dbLink = null;
    }

}