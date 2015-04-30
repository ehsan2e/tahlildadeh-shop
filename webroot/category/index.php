<?php
        // Category Page
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
            $resp['data']['products'][] = array('name'  => $record['name'],
                                                'price' => $record['price'],
                                                'id'    => $record['id'],
                                                'desc'    => $record['desc']
                                               );            
        }
        
        mysql_free_result($result);

        return $resp;
        
    }

    
    
    
require dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'Engine.php';

