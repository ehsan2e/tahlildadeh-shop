<?php

global $uses;
$uses=array('element');

function loadElement($elementName, $data=array()){

	// Creating function name from element name according to the convention

	$temp=str_replace('_', ' ', $elementName);
	$temp=ucwords($temp);
	$elementFunctionName='element'.str_replace(' ', '', $temp);
	

	//calling the function and passing the data to it

	$elementData=call_user_func_array($elementFunctionName, array($data));


	// creating variables from returning result provided that the function has returned a result and the result is in the form of an array

	if(is_array($elementData)){
		extract($elementData);
	}


	// creating and buffering the display of element

	ob_start();


	// loading the corresponding view for the element according to the convention

	require(VIEW_DIR . DS . 'elements' . DS . $elementName . '.phtml');


	$display=ob_get_contents();
	ob_end_clean();


	return $display;
}

function loadCSS($path)
{
    $url=SKIN_URL.'css/'.$path;
    return $url;
}

function loadJS($path)
{
    $url=SKIN_URL.'js/'.$path;
    return $url;
}