<?php

define('DS',DIRECTORY_SEPARATOR);
define('BASE_DIR',dirname(__FILE__) );
define('VIEW_DIR',BASE_DIR.DS.'view' );
define('FUNC_DIR',implode(DS,array(BASE_DIR,'php','function')));
define('LIB_DIR',implode(DIRECTORY_SEPARATOR,array(BASE_DIR,'php','lib')));
define('WEBROOT',BASE_DIR.DS.'webroot');
define('SKIN_URL',BASE_URL.'skin/');
define('MEDIA_URL',BASE_URL.'media/');