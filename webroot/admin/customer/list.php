<?php
function main ()
    {        
        $resp = array ('data' => array(1));
        $resp['data']['customers'] = array();
        
        $sql ="SELECT * FROM `customers` LIMIT 10;";
        $result = dbQuery($sql);
        
        while (($record = mysql_fetch_assoc($result)) !== false ){             // داده ها دقیق نیست چون جدول مشتریان رو ندارم پس همینجوری میزنم تا تو کلاس دقیقش رو ببینم
            //persianDate('2015-05-23 23:13:02');
            $resp['data']['customers'][] = array(
                                                'id'  => $record['id'],
                                                'first_name' => $record['customer_name'],
                                                'last_name' => $record['customer_family'],
                                                'email'    => $record['customer_email'],
                                                'mobile'    => $record['customer_mobile'],
                                                'register_date'    => $record['customer_register_date']
                                               );            
        }
        
        mysql_free_result($result);

        return $resp;
        
    }

    
    
 
require dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR.'Engine.php';

