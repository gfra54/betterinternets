<?php
add_filter('body_class', 'add_body_class');

function add_body_class($classes){
    if(is_user_logged_in()){
    	$classes[]='admin-connected';
    } else {
    	$classes[]='admin-not-connected';
    }
    return $classes;
}