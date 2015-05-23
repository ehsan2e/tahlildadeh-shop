<?php

// Starting the session

session_start();


// Loading config and constant respectively

$dir=dirname(dirname(__FILE__));
require_once($dir.DIRECTORY_SEPARATOR.'config.php');
require_once($dir.DIRECTORY_SEPARATOR.'constants.php');


// Setting include path for functions and libraries

$defaultIncludePath=get_include_path();
set_include_path(implode(PS, array(
		$defaultIncludePath,
		FUNC_DIR,
		LIB_DIR
	)
));


/**
  * This function uses a global variable named $view to automatically load necessary functions and libraries for other functions or libraries
  *
  * @param string|array $name the function or library we need to load
  * @return void
 */

function autoLoadingManager($name){
	global $uses;


	// If more than one file name is given then use autoLoadingManager function for each of them

	if(is_array($name)){
		foreach($name as $singleName){
			autoLoadingManager($singleName);
		} 
		return;
	}


	// Requiring the desired function or library. Remember to add '.php' as the extension

	$name=str_replace('\\', DS, $name).'.php';
	require_once($name);


	// Check whether the library has any dependency itself. Function and libraries dependencies are stored in a global variable named $uses in their corresponding file

	if(isset($uses)){	
		$tempUses=$uses;
		unset($GLOBALS['uses']);
		autoLoadingManager($tempUses);		
	}

	return;
}




// Loading common functions and libraries using our autoLoadingManager function

autoLoadingManager(array('dbf', 'url', 'message'));


/* 
 * Including functions and libraries which are required to handle this request.
 * These files are determined in $dependencies global variable
 */

if(isset($dependencies)){
	autoLoadingManager($dependencies);
	unset($dependencies);
}

if (isset($acl)) {
    autoLoadingManager('authHelper');
    if (!hasPrivilege ($acl)) {
        die ('Access Denied');
    }
}

// Calling main function and processing the response

$response=main();
$loadView=true;
if(!is_null($response)){
	$data=isset($response['data'])?$response['data']:array();
	extract($data);

	// Check if we should redirect to another URL

	if(isset($response['redirect'])){
		// View should not be loaded

		$loadView=false;
		

		//redirect logic

		header(sprintf('location: %s', $response['redirect']));
	}
}


// Check whether we should load view

if($loadView){
	// Determine corresponding view

	if(!isset($view)) {
	    $view = str_replace(array('/', '.php'), array(DS, '.phtml'), $_SERVER['SCRIPT_NAME']);
	    $view = substr($view,1);
	}else{
		$view = str_replace('/', DS, $view);
	}


	// Including UI helper using our autoLoadingManager

	autoLoadingManager('ui');


	// Buffering the view

	ob_start();
	require(VIEW_DIR.DS.$view);
	$content=ob_get_contents();
	ob_end_clean();


	// Including master view

    if(!isset($masterName)) {
        if(preg_match('$/admin/$',$_SERVER['SCRIPT_NAME'])){
            $masterName = 'admin.phtml';
        }else {
            $masterName = '2-column-right.phtml';
        }
    }
    $masterView = implode(DS, array(VIEW_DIR, 'masters', $masterName));
	require($masterView);
}


// Releasing resources and cleaning up

dbClose(); // To close DB connection if presents
writeMessagesToSession (); // Storing generated messages to session so they can be retrieved later