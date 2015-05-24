<?php

function elementCart($data=array()){
	$cartItems = array();
	if(isset($_SESSION['cart']) && count($_SESSION['cart'])>0){
		$productIds=array_keys($_SESSION['cart']);
		$temp=implode(', ', $productIds);
		$sql="SELECT `id`, `product_name`, `product_price` FROM `products` WHERE `id` IN ($temp);";
		$result = dbQuery($sql);
        while(($row = mysql_fetch_assoc($result))!==false){
        	$cartItems[]=array(
        			'product_name'  => $row['product_name'],
        			'product_price' => (int) $row['product_price'],
        			'count' => $_SESSION['cart'][(int) $row['id']],
                                'id' => $row['id']
        		);
        }        
        mysql_free_result($result);
	}
	return array('cartItems' => $cartItems);
}



    /**
     * آخرین محصولات
     * Latest Products
     * 
     *
     */

    function elementLatestProduct () {
        
        $sql = "SELECT * FROM `products` ORDER BY `product_add_date` DESC LIMIT 6";
        $result = dbQuery($sql);
        
        while (($row = mysql_fetch_assoc($result)) !== false) {
            $productBoxs[] = array(
                                    'id' => $row['id'],
                                    'product_name' => $row['product_name'],
                                    'product_price' => $row['product_price'],
                                    'product_description' => $row['product_description'],
                                    'product_picture_name' => $row['product_picture_name']
                                );
        }
        mysql_free_result($result); 
        
        return array('productBoxs' => $productBoxs);
    }




function elementShoppingcart($data=array()){
        //@ToDo اگر سبد خالی بود به صفحه اصلی ارسال شود
        
	$cartItems = array();
	if(isset($_SESSION['cart']) && count($_SESSION['cart'])>0){
		$productIds=array_keys($_SESSION['cart']);
		$temp=implode(', ', $productIds);
		$sql="SELECT `id`, `product_name`, `product_price` FROM `products` WHERE `id` IN ($temp);";
		$result = dbQuery($sql);
        while(($row = mysql_fetch_assoc($result))!==false){
        	$cartItems[]=array(
        			'product_name'  => $row['product_name'],
        			'product_price' => (int) $row['product_price'],
        			'count' => $_SESSION['cart'][(int) $row['id']],
                                'id' => $row['id']
        		);
        }        
        mysql_free_result($result);
	}
        
        
       
	return array('cartItems' => $cartItems);
}

function elementMessage ($data=array()) {
    
    $result = array ('messages' => readMessage());
    return $result;
}