<?php

function main ()
    {
        
        $category =!isset($_GET['cat']) ? 1 : (int) $_GET['cat'];
        
        $resp = array ('data' => array(1));
        $resp['data']['category']=$category;
        $resp['data']['products'] = array();
        
        $sql ="SELECT `products`.*,`categories`.`name` AS `cname` FROM `products` JOIN `categories` ON `products`.`category_id`=`categories`.`id`LIMIT 10;";
        $result = dbQuery($sql);
        
        while (($record = mysql_fetch_assoc($result)) !== false ){            
            $resp['data']['products'][] = array('name'  => $record['name'],
                                                'price' => $record['price'],
                                                'id'    => $record['id'],
                                                'desc'    => $record['desc'],
												'category_id'    => $record['category_id'],
												'cname'    => $record['cname'],
												'stock'    => $record['stock']
                                               );            
        }
        
        mysql_free_result($result);

        return $resp;
        
    }

    
    
 
require dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR.'Engine.php';

