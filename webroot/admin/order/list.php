<?php
function main ()
    {        
        $resp = array ('data' => array(1));
        $resp['data']['orders'] = array(); // فعلا جدول سفارشات رو ندارم پس خطا میدهد همچنین هیچ کدوم از فیلد هایی که جدول سفارشات باید تو این صفحه نشان دهد رو نمیدونم
        
        $sql ="SELECT * FROM `orders` LIMIT 10;";
        $result = dbQuery($sql);
        
        while (($record = mysql_fetch_assoc($result)) !== false ){             // چون جدول رو ندارم همین دوتا رو میزارم
            $resp['data']['orders'][] = array(
                                                'id'  => $record['id'], 
                                                'order_date'    => $record['order_date']
                                               );            
        }
        
        mysql_free_result($result);

        return $resp;
        
    }

    
    
 
require dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR.'Engine.php';

