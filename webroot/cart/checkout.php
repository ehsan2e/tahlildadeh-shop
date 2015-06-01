<?php

// checkuout Page Controler

global $dependencies;
$dependencies = array ('crud','checkInput','authHelper');

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
      $productIds =  array_keys($_SESSION['cart']);
      $temp = implode (',',$productIds);
      $products = listRecords ('products', sprintf('`id` IN (%s)', $temp),null,null,true);


        //2 چک کردن کفایت موجودی محصول
        
      if(count($products) !== count($_SESSION['cart'])) {
          // نمایش پیغام خطا برای برابر نبودن سبد خرید
          return;
      }
      $subtotal =0 ;
      $orderDetailsData=array();
      $productUpdateData=array();
      $stockData=array();
    $currentTimeStamp=date('Y-m-d H:i:s');
      foreach ($products as $product) {
          $stock = (int) $product['product_stock'];
          $id = $product['id'];
          if ($product['product_is_saleable']=='0' || $stock < $_SESSION['cart'][$id]) {
              //@ToDo نمایش پیغام خطا مقدار موجودی کمتر هست - ارسال به سبد خرید
              //@ToDo Advance : تمام آیتم ها بررسی شود بعد ارسال شود

              return;
          }
          $orderDetailsData[]=array(
              'order_id' => '',
              'product_id' => $id,
              'product_count' => $_SESSION['cart'][$id],
              'product_price' => $product['product_price']
              //'product_discount' => 0
              //'product_tax' => 0
          );
          $productUpdateData[$id]=array(
              'product_stock' => array(CRUD_MODIFY => (-$_SESSION['cart'][$id])),
              'product_number_sold' => array(CRUD_MODIFY => $_SESSION['cart'][$id]),
          );

          $newStockValue=$stock - $_SESSION['cart'][$id];
          $stockData[]=array(
              'product_id' => $id,
              'stock_date' => $currentTimeStamp,
              'stock_type' => 0, //@TODO standardize the stock update codes for now we assume 0 means reducing stock due to an order placement
              'stock_detail' => sprintf('کاهش %d واحد از موجودی. تغییر موجودی از %d به %d. بابت سفارش شماره:', $_SESSION['cart'][$id], $stock, $newStockValue),
              'stock_count' => $newStockValue
          );
          $subtotal += (((int) $product['product_price']) * ((int) $_SESSION['cart'][$id])); 
      }
      
      
        //3 ایجاد رکورد جدید در جدول Order
      $userId = 1; //GetUserId()
      $discount =0; // 
      $address = 'Noaddress'; //read from Form
      
      $order = array (
          'customer_id' => $userId,
          'order_date' => $currentTimeStamp,
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

    foreach($orderDetailsData as $key=>$item){
        $orderDetailsData[$key]['order_id']=$orderId;
    }
     foreach($stockData as $key=>$item){
         $stockData[$key]['stock_detail'].=" {$orderId}";
     }

    if(!massUpdate('products',array('product_stock', 'product_number_sold'), $productUpdateData)){
        // نمایش پیغام خطا - سفارش ثبت نشد

        dbQuery('ROLLBACK');
        return; // ارسال به صفحه کارت
    }

        if(!massCreate('stocks',
            array('product_id', 'stock_date', 'stock_type', 'stock_detail','stock_count'),
            $stockData
        )
        ){
            // نمایش پیغام خطا - سفارش ثبت نشد

            dbQuery('ROLLBACK');
            return; // ارسال به صفحه کارت
        }


        //5 ایجاد رکورد های متناظر در جدول order_detail      

        if(!massCreate('order_detail',
            array('order_id', 'product_id', 'product_count','product_price', /*'product_discount', 'product_tax'*/),
            $orderDetailsData
        )
        ){
            // نمایش پیغام خطا - سفارش ثبت نشد

            dbQuery('ROLLBACK');
            return; // ارسال به صفحه کارت
        }


      
    dbQuery('COMMIT');

        // خالی کردن سبد تولید پیغام مناسب فرستادن مشتری به صفحه مناسب

        unset($_SESSION['cart']);
        addMessage(sprintf('سفارش شما با موفقیت ایجاد شد، شماره سفارش: %d',$orderId), SUCSESS);

        // currently we redirect the user to the homepage but later we will send him/her to payment or order history page
        return array('redirect'=>BASE_URL);
    
   // } // isset submit
    }

require(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'Engine.php');