<?php
function main ()
    {        
        $resp = array ('data' => array(1));
        $resp['data']['customers'] = array();
        
        $sql ="SELECT * FROM `customers` LIMIT 10;";
        $result = dbQuery($sql);
        
        while (($record = mysql_fetch_assoc($result)) !== false ){             // داده ها دقیق نیست چون جدول مشتریان رو ندارم پس همینجوری میزنم تا تو کلاس دقیقش رو ببینم
            $resp['data']['customers'][] = array('id'  => $record['id'],
                                                'first_name' => $record['first name'],
                                                'last_name' => $record['last name'],
                                                'password'    => $record['password'],
                                                'address'    => $record['address'],
                                                'date'    => $record['date']
                                               );            
        }
        
        mysql_free_result($result);

        return $resp;
        
    }

    
    
 
require dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR.'Engine.php';

