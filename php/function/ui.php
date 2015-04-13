<?php

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