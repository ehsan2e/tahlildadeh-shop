<?php

global $dependencies;
$dependencies=array('authHelper');

function main()
{

unset($_SESSION[getSpKey()]);

header('location:../signup');


}

require(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'Engine.php');