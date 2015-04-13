<?php

// Starting the session

session_start();


// Loading config and constant in order

$dir=dirname(dirname(__FILE__));
require_once($dir.DIRECTORY_SEPARATOR.'config.php');
require_once($dir.DIRECTORY_SEPARATOR.'constants.php');


// Setting include path for functions and libraries

$defaultIncludePath=get_include_path();
set_include_path(implode(PATH_SEPARATOR, array(
		$defaultIncludePath,
		FUNC_DIR,
		LIB_DIR
	)
));


// Including common functions and libraries

require_once('url.php');


/* 
 * Including functions and libraries which are required to handle this request.
 * These files are determined in $dependencies global variable
 */

if(isset($dependencies)){
	foreach ($dependencies as $dependency) {
		$dependency = str_replace('/', DS, $dependency);
		require_once("{$dependency}.php");
		// If this dependency uses other functions, recursively load them
		if(isset($uses)){
			$temp=$uses;
			unset($uses);
			while(count($temp) > 0){
				$requirement=str_replace('/', DS, array_shift($temp));
				require_once("{$requirement}.php");
				if(isset($uses)){
					$temp=array_merge($temp, $uses);
					unset($uses);
					$temp=array_unique($temp);
				}
			}
			
		}
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
		

		//@TODO implement redirect logic
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


	// Including UI and URL helpers

	require_once('ui.php');


	// Buffering the view

	ob_start();
	require(VIEW_DIR.DS.$view);
	$content=ob_get_contents();
	ob_end_clean();


	// Including master view

	$masterView=implode(DS,array(VIEW_DIR,'masters','1-column.phtml'));
	require($masterView);
}


// Releasing resources and cleaning up