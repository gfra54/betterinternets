<?php
add_action( 'wp_head', 'load_css',200);
function load_css() {
	foreach(glob(dirname(__FILE__).'/../../css/*.css') as $file){
	  if(file_exists($file)){
	  	$file = realpath($file);
	  	$file = str_replace('\\', '/', $file);
	  	$time = filemtime($file);
	  	list(,$file) = explode('wp-content/',$file);
	    echo '<link rel="stylesheet" href="'.site_url().'/wp-content/'.$file.'?'.$time.'" />'.PHP_EOL;
	  }
	}
	
}

add_action( 'wp_footer', 'load_js' ,200);
function load_js() {
	foreach(glob(dirname(__FILE__).'/../../js/*.js') as $file){
	  if(file_exists($file)){
	  	$file = realpath($file);
	  	$file = str_replace('\\', '/', $file);
	  	$time = filemtime($file);
	  	list(,$file) = explode('wp-content/',$file);
	    echo '<script type="text/javascript" src="'.site_url().'/wp-content/'.$file.'?'.$time.'"></script>'.PHP_EOL;
	  }
	}
	
}
