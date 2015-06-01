<?php


function safeQuery($value)//make a safe sql query
{
    if(get_magic_quotes_gpc()){
        stripcslashes($value);
    }
    if(!is_numeric($value)){
        $value=mysql_real_escape_string($value);
    }

    return $value;
}


function isPhone($num){
    if(preg_match_all('/^(09)([0-9]{9})$/',$num)){
        return true;
    }
    return false;
}

function isEmail($email){
    if(filter_var($email ,FILTER_VALIDATE_EMAIL)){
        return true;
    }
    return false;
}
