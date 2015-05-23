<?php

// checkuout Page Controler

global $dependencies;
$dependencies = array ('crud');

//global $acl;
//$acl = 'customer';



function main()
    {
        //1 خواندن محصولات داخل سبد از پایگاه داده در حالت For Update

    
    
    //if(isset($_POST['submit'])){
     if (!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0){
         // خالی بودن سبد خرید
         
        $url = BASE_URL;
        return array('redirect' => $url);
     }   
    
        
    dbQuery('START TRANSACTION;');
      //$productIds = array ();
      //foreach ($_SESSION['cart'] as $productId => $count){
     //     $productIds[] = $productId;
     // }
      
      $productIds =  array_keys($_SESSION['cart']);
      $temp = implode (',',$productIds);
      $products = listRecords ('products', sprintf('`id` IN (%s)', $temp),null,null,true);
      
//      echo '<pre>';
//      print_r($products);
//      echo '</pre>';
      
      
        //2 چک کردن کفایت موجودی محصول
        
      if(count($products) !== count($_SESSION['cart'])) {
          // نمایش پیغام خطا برای برابر نبودن سبد خرید
          return;
      }
      $subtotal =0 ;
      foreach ($products as $product) {
          $stock = (int) $product['product_stock'];
          $id = $product['id'];
          if ($stock < $_SESSION['cart'][$id]) {
              //@ToDo نمایش پیغام خطا مقدار موجودی کمتر هست - ارسال به سبد خرید
              //@ToDo Advance : تمام آیتم ها بررسی شود بعد ارسال شود
              
              return;
          }
          $subtotal += (((int) $product['product_price']) * ((int) $_SESSION['cart'][$id]));
      }
      
      
        //3 ایجاد رکورد جدید در جدول Order
      $userId = 1; //GetUserId()
      $discount =0; // 
      $address = 'Noaddress'; //read from Form
      
      $order = array (
          'customer_id' => $userId,
          'order_date' => date('Y-m-d H:i:s'),
          'order_subtotal' => $subtotal,
          'order_discount' => $discount,
          'order_address' => $address
          
      );
      
      $orderId = create('orders', $order);
      if ($orderId === false){
          
          // نمایش پیغام خطا - سفارش ثبت نشد
          
          dbQuery('ROLLBACK');
          return; // ارسال به صفحه کارت
          
      }
      
        
        //4 کاهش موجودی و ایجاد رکورد جدید در جدول  stock
        //5 ایجاد رکورد های متناظر در جدول order_detail      
      
      
      
      
      
      
    dbQuery('COMMIT');
    
   // } // isset submit
    }

require(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'Engine.php');