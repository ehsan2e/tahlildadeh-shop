<?php

function main (){
    
    //حذف آیتم از سبد خرید
    
    $productId = (int) $_GET['p'];
    
    // بررسی وجود محصول در سبد خرید
    
    if(isset($_SESSION['cart'][$productId]))
        {
            unset($_SESSION['cart'][$productId]);
        }
        
     //  ارسال به صفحه سبد خرید
        
            $url = BASE_URL.'/cart';
        return array('redirect' => $url);
    
    
    
}

require dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'Engine.php';