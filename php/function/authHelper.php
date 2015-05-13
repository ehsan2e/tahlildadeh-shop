<?php
/**
 * Created by f.Poormoammad
 */


function getSpKey(){
    return  'p'.ip2long($_SERVER['REMOTE_ADDR']);
}



function hasPrivilege($acl)
{

    switch ($acl){
        case 'customer':
            $key='customer';
            break;

        case 'admin':
            $key='admin';
            break;

        default:
            $key='guest';

    }

    return isset($_SESSION[getSpKey()][$key]);

}