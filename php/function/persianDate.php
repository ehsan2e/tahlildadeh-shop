<?php

    /*
     * تبدیل تاریخ میلادی به شمسی
     * 
     */
    
    
    function persianDate ($date){
        require_once LIB_DIR.DS.'jdf.php';
        
        $timestamp = strtotime($date);
        $persianDate = jdate('Y/n/j', $timestamp);
        
        return $persianDate;
        
    }
