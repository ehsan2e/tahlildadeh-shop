<?php

function addToCartUrl($product){
	global $category;
	return BASE_URL.sprintf('cart/add.php?c=%d&p=%d', $category, $product['id']);
}

function productImageUrl($product){
	return MEDIA_URL.'images/products/'.$product['image'];
}