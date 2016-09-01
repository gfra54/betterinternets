<?php

$protocol = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";

/*if (strstr($_SERVER['HTTP_HOST'], 'www.')===false && strstr($_SERVER['HTTP_HOST'], '.local')===false && strstr($_SERVER['HTTP_HOST'], 'sfr.fr')!==false) {
    header('Location: '.$protocol.'www.'.$_SERVER['HTTP_HOST'].'/'.($_SERVER['REQUEST_URI'] == '/index.php' ? '' : $_SERVER['REQUEST_URI']));
    exit;
}*/

if (strstr($_SERVER['HTTP_HOST'], 'www.')!==false && strstr($_SERVER['HTTP_HOST'], '.local')===false) {
    header('Location: '.$protocol.''.str_replace('www.','',$_SERVER['HTTP_HOST']).'/'.($_SERVER['REQUEST_URI'] == '/index.php' ? '' : $_SERVER['REQUEST_URI']));
    exit;
}