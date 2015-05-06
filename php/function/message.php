<?php

global $systemMessage;
$systemMessage = NULL;

define('SUCSESS', 'sucsess');
define('FAILURE', 'failure');
define('NOTICE', 'notice');
define('INFO', 'info');


function addMessage ($msg, $type) {
    // creat message
    global $systemMessage;
    initMessage ();
    
    if (!isset($systemMessage[$type])) {
        $systemMessage[$type] = array();
    }
    
    $systemMessage[$type][] = $msg;

    
} //addMessage

function readMessage ($clear = true) {
    // read message & clear session
    global $systemMessage;
    initMessage ();
    
    $temp = $systemMessage;
    if ($clear) {
        $systemMessage = array();
    }
    
    return $temp;
    
} //readMessage

function hasMessage ($type = null) {
    $msg = readMessage(false);
    if (!is_null($type)) {
    return (isset($msg [$type]) && (count($msg[$type]) >0 ));
    }
    
    $types = array (SUCSESS, FAILURE, INFO, NOTICE);
        foreach ($types as $type) {
            if (hasMessage($type)) {
                return true;
            }
        }
        
    return false;
}

function initMessage () {
    
    global $systemMessage;
    if(!is_null($systemMessage)) {
        return;
    }
    
    if (!isset($_SESSION['sm'])) {
        
        $systemMessage = array ();
        return;
    }
    
    $systemMessage = $_SESSION['sm'];
    unset($_SESSION['sm']);
}



function writeMessagesToSession () {
    // add message to sessions
    
    if (!hasMessage()) {
        return;
    }
    
    $_SESSION['sm'] = readMessage();
    
}