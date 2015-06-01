<?php

// Cart Page Controler

    global $dependencies;
    $dependencies=array('checkInput','authHelper');
    

function main()
{
        if (hasPrivilege('customer')){
        //@ToDo اگر سبد خالی بود به صفحه اصلی ارسال شود
    
        $resp = array ('data' => array(1));
        $resp['data']['shopingCart'] = array();    
        
	$cartItems = array();
        
	if(isset($_SESSION['cart']) && count($_SESSION['cart'])>0){
            
            
		$productIds=array_keys($_SESSION['cart']);
		$temp=implode(', ', $productIds);
		$sql="SELECT `id`, `product_name`,`product_picture_name`, `product_price` FROM `products` WHERE `id` IN ($temp);";
		$result = dbQuery($sql);
                
            while(($row = mysql_fetch_assoc($result))!==false){
        	$resp['data']['cartItems'][]=array(
                                                    'id' => $row['id'],
                                                    'product_name'  => $row['product_name'],
                                                    'product_price' => (int) $row['product_price'],
                                                    'product_picture_name' => $row['product_picture_name'],
                                                    'count' => $_SESSION['cart'][(int) $row['id']],
                                                    
                                                );
            }        
        mysql_free_result($result);

        } else {
                $url = BASE_URL;
                return array('redirect' => $url);
        }
        

    
        return $resp;
        
    
    
    } // End Check customer Loged in
    else {
        addMessage('برای تسویه حساب وارد حساب کاربری خود شوید، چنانچه هنوز عضو نیستید ثبت نام کنید', NOTICE);
        $url = BASE_URL.'signup';
        return array('redirect'=>$url);
        
       }
    
}

require(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'Engine.php');