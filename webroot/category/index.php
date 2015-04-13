<?php

/*
 * Sample Dependency decleration
 *
 * global $dependencies;
 * $dependencies=array('yui/1');
 */

function main()
{
	$category= isset($_GET['cat'])?((int) $_GET['cat']):1;
	//@TODO read from DB
	$resp=array(
		'data' => array(
			'category' => $category,
			'products' => array()
		)
	);
	$resp['data']['products'][]=array(
		'id' => 1,
		'name' => 'محصول اول',
		'price' => 100000,
		'image' => '#',
	);
	$resp['data']['products'][]=array(
		'id' => 2,
		'name' => 'محصول دوم',
		'price' => 200000,
		'image' => '#',
	);
	$resp['data']['products'][]=array(
		'id' => 3,
		'name' => 'محصول سوم',
		'price' => 300000,
		'image' => '#',
	);
	$resp['data']['products'][]=array(
		'id' => 4,
		'name' => 'محصول چهارم',
		'price' => 400000,
		'image' => '#',
	);
	$resp['data']['products'][]=array(
		'id' => 5,
		'name' => 'محصول پنج',
		'price' => 500000,
		'image' => '#',
	);
	$resp['data']['products'][]=array(
		'id' => 6,
		'name' => 'محصول ششم',
		'price' => 600000,
		'image' => '#',
	);
	return $resp;
}

require(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'Engine.php');