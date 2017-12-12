<?php

// define custom functions

if (! function_exists('startsWith')) {
    function startsWith($haystack, $needle)
    {
        return $needle === "" || strrpos($haystack, $needle, - strlen($haystack)) !== false;
    }
}


if (! function_exists('strContains')) {
    function strContains($haystack, $needle)
    {
        if (strpos($haystack, $needle) !== false)
            return true;
        else
            return false;
    }
}

if (! function_exists('base_path')) {
    function base_path()
    {
        $temp_path = __DIR__;
        $base_path = str_replace("bootstrap", "", $temp_path);
        return $base_path;
    }
}
