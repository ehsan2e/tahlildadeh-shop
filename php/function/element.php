<?php

function elementCart($data=array()){
	$cartItems = array();
	if(isset($_SESSION['cart']) && count($_SESSION['cart'])>0){
		$productIds=array_keys($_SESSION['cart']);
		$temp=implode(', ', $productIds);
		$sql="SELECT `id`, `name`, `price` FROM `products` WHERE `id` IN ($temp);";
		$result = dbQuery($sql);
        while(($row = mysql_fetch_assoc($result))!==false){
        	$cartItems[]=array(
        			'name'  => $row['name'],
        			'price' => (int) $row['price'],
        			'count' => $_SESSION['cart'][(int) $row['id']],
                                'id' => $row['id']
        		);
        }        
        mysql_free_result($result);
	}
	return array('cartItems' => $cartItems);
}


function elementShoppingcart($data=array()){
        //@ToDo اگر سبد خالی بود به صفحه اصلی ارسال شود
        
	$cartItems = array();
	if(isset($_SESSION['cart']) && count($_SESSION['cart'])>0){
		$productIds=array_keys($_SESSION['cart']);
		$temp=implode(', ', $productIds);
		$sql="SELECT `id`, `name`, `price` FROM `products` WHERE `id` IN ($temp);";
		$result = dbQuery($sql);
        while(($row = mysql_fetch_assoc($result))!==false){
        	$cartItems[]=array(
        			'name'  => $row['name'],
        			'price' => (int) $row['price'],
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