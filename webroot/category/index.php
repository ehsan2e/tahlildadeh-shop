<?php
        // Category Page

    global $dependencies;
    $dependencies=array('checkInput','authHelper');
    
/*
 * Sample Dependency decleration
 *
 * global $dependencies;
 * $dependencies=array('yui\1');
 */


/**
 * URL: http://shop.loc/cart
 * 
 * 
 */
function main ()
    {
        
        // @TODO Read Product From db
        
        $category =!isset($_GET['cat']) ? 1 : (int) $_GET['cat'];
        
        $resp = array ('data' => array(1));
        $resp['data']['category']=$category;
        $resp['data']['products'] = array();
        
        $sql ="SELECT * FROM `products` LIMIT 10;";
        $result = dbQuery($sql);
        
        while (($record = mysql_fetch_assoc($result)) !== false ){
            $resp['data']['products'][] = array('product_name'  => $record['product_name'],
                                                'product_price' => $record['product_price'],
                                                'id'    => $record['id'],
                                                'product_picture_name'    => $record['product_picture_name'],
                                                'product_description'    => $record['product_description']
                                               );            
        }
        
        mysql_free_result($result);

        return $resp;
        
    }

    
    
    
require dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'Engine.php';

