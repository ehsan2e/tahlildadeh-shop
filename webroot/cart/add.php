<?php


    function main ()
    {
        // اضافه کردن محصول به سبد با کتگوری و محصول
        
        if(isset($_GET['p']) && isset($_GET['c'])){
         
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

            if($count > ((int) $product['product_stock'])){

                // @TODo نمایش پیغام خطا
                $_SESSION['cart'][$productId] = $count;
                addMessage('تعداد درخواستی شما بیش از موجودی فروشگاه می باشد، از قسمت ارتباط با ما درخواست خود را ثبت نمایید.', FAILURE);
            }else {
                $_SESSION['cart'][$productId] = $count;
                addMessage('محصول به درستی به سبد اضافه شد.', SUCSESS);
            }

            $url = categoryUrl($categoryId);
            return array('redirect' => $url);            
            
            
        } elseif (isset ($_GET['p'])) {
            // اگر از صفحه محصول آمده باشد
            //@ToDo المان کنترل تعداد اضافه به سبد کار نمیکند
            
            $productId = $_GET['p'];
            
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
            
            // بررسی موجود بودن در انبار
            //@ToDo بازبینی شود 
            
            if((int) $product['product_stock'] !== 0){
                // موجودی کافی در انبار هست  به سبد خرید اضافه گردد
                
                if (!isset($_SESSION['cart'][$productId])){
                    $count = 1;

                }else {

                    $count = $_SESSION['cart'][$productId]+1;

                }
                if($count > ((int) $product['product_stock'])){

                    // @TODo نمایش پیغام خطا

                    $_SESSION['cart'][$productId] = $count;
                    addMessage('تعداد درخواستی شما بیش از موجودی فروشگاه می باشد، از قسمت ارتباط با ما درخواست خود را ثبت نمایید.', FAILURE);

                }else {
                    $_SESSION['cart'][$productId] = $count;
                    // @ToDo ایجاد پیغام درست

                    $_SESSION['cart'][$productId] = $count;
                    addMessage('محصول به درستی به سبد اضافه شد.', SUCSESS);
                }

                
            }  else {
            
                addMessage('تعداد درخواستی شما بیش از موجودی فروشگاه می باشد، از قسمت ارتباط با ما درخواست خود را ثبت نمایید.', FAILURE);
            }


            $url = productUrl($productId);
            return array('redirect' => $url); 
            
            
        }

    } // Main

    
    
    
require dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'Engine.php';