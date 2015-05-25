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

    /**
     * پرفروش ترین محصولات
     * best selling
     * 
     */
    function elementBestSelling (){
        
        $sql = "SELECT * FROM `products` ORDER BY `product_number_sold` DESC LIMIT 2";
        $result = dbQuery($sql);
        
        while (($row = mysql_fetch_assoc($result)) !== false ) {
            $bestSells[] = array(
                                    'id' => $row['id'],
                                    'product_name' => $row['product_name'],
                                    'product_price' => $row['product_price'],
                                    'product_description' => $row['product_description'],
                                    'product_picture_name' => $row['product_picture_name']
                                );
        }
        mysql_free_result($result);
        return array ('bestSells' => $bestSells);
        
    }

    /*
     * محصول تصادفی
     * Random Product
     * 
     */
    
    function elementRandomProducts (){
        $sql = "SELECT * FROM `products` ORDER BY RAND() LIMIT 1;";
        $result = dbQuery($sql);
        
        while (($row = mysql_fetch_assoc($result)) !== false) {
            $randomProducts[] = array (
                                        'id' => $row['id'],
                                        'product_name' => $row['product_name'],
                                        'product_price' => $row['product_price'],
                                        'product_description' => $row['product_description'],
                                        'product_picture_name' => $row['product_picture_name']             
                
                                    );
        }
        mysql_free_result($result);
        return array('randomProducts' => $randomProducts);
    }
    
    
    /*
     * اسلایدر بالای صفحه
     * Slider Number one
     * 
     */
    
    function elementSliderNumberOne (){
        $sql = "SELECT `id`,`product_name`,`product_picture_name` FROM `products` WHERE `product_is_saleable` = 1;";
        $result = dbQuery($sql);
        
        while (($row = mysql_fetch_assoc($result)) !== false) {
            $sliderNumberOne[] = array(
                                        'id' => $row['id'],
                                        'product_name' => $row['product_name'],
                                        'product_picture_name' => $row['product_picture_name']
                                    );
        }
        mysql_free_result($result);
        return array('sliderNumberOne' => $sliderNumberOne);
    }
    
    /*
     * اسلایدر پایین صفحه
     * Slider Nember Two
     */
    
    function elementSliderNumberTwo () {
        $sql = "SELECT `id`,`product_name`,`product_picture_name` FROM `products`;";
        $result = dbQuery($sql);
        
        while (($row = mysql_fetch_assoc($result)) !== false) {
            $sliderNumberTwo[] = array(
                                        'id' => $row['id'],
                                        'product_name' => $row['product_name'],
                                        'product_picture_name' => $row['product_picture_name']
                                    );
            
        }
        mysql_free_result($result);
        return array('sliderNumberTwo' => $sliderNumberTwo);
        
    }
    
    

function elementMessage ($data=array()) {
    
    $result = array ('messages' => readMessage());
    return $result;
}