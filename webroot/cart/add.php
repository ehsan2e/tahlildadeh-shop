<?php


    function main ()
    {
        $productId = (int) $_GET['p'];
        $categoryId = (int) $_GET['c'];
        
        // بررسی وجود محصول
        
        $sql = "SELECT * FROM `products` WHERE `id` = {$productId};";
        $result = dbQuery($sql);
        
        if (mysql_num_rows($result) !== 1){
            die('وجود ندارد');
        }
        
        $product = mysql_fetch_assoc($result);
        mysql_free_result($result);
        
        if(!isset($_SESSION['cart'])){
            $_SESSION['cart'] = array();
        }
    
        if (!isset($_SESSION['cart'][$productId])){
            
            $count = 1;
            
        }else {
            
            $count = $_SESSION['cart'][$productId]+1;
            
        }
            
        /////
        
        if($count > ((int) $product['stock'])){
            
            // @TODo نمایش پیغام خطا
        }else {
            $_SESSION['cart'][$productId] = $count;
            // @ToDo ایجاد پیغام درست
        }
        
        $url = categoryUrl($categoryId);
        return array('redirect' => $url); 
    }

    
    
    
require dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'Engine.php';