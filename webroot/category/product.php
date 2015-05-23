<?php

function main (){
    /**
     * If ProductId not set or empty return to home page
     */
    if(!isset($_GET['p']) or $_GET['p']==='' or !is_numeric($_GET['p'])){
        
        $url = BASE_URL;
        return array('redirect' => $url);
    }
    
    
    $productId = (int) $_GET['p'];
    
    $resp = array ('data' => array(1));
    $resp['data']['productId']=$productId;
    //$resp['data']['products'] = array();

    $sql ="SELECT * FROM `products` WHERE `id` = $productId";
    $result = dbQuery($sql);

    while (($record = mysql_fetch_assoc($result)) !== false ){            
        $resp['data']['item'] = array('product_name'  => $record['product_name'],
                                            'product_price' => $record['product_price'],
                                            'product_stock'    => $record['product_stock'],
                                            'id'    => $record['id'],
                                            'product_description'    => $record['product_description']
                                           );            
    }

    mysql_free_result($result);

    return $resp;
    
    
    
    
    
    
}

require dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'Engine.php';