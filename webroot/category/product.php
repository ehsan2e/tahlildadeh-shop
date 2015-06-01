<?php
    global $dependencies;
    $dependencies=array('checkInput','authHelper');
    
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
        $resp['data']['item'] = array(
                                        'id'    => $record['id'],
                                        'product_name'  => $record['product_name'],
                                        'product_price' => $record['product_price'],
                                        'product_stock'    => $record['product_stock'],
                                        'product_brand'    => $record['product_brand'],
                                        'product_color'    => $record['product_color'],
                                        'product_gender'    => $record['product_gender'],
                                        'product_cloth_type'    => $record['product_cloth_type'],
                                        'product_made_in'    => $record['product_made_in'],
                                        'product_visit_count'    => $record['product_visit_count'],
                                        'product_picture_name'    => $record['product_picture_name'],
                                        'product_description'    => $record['product_description']
                                       );            
    }

    mysql_free_result($result);

    return $resp;
    
    
    
    
    
    
}

require dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'Engine.php';