<?php

function gest_partage_social() {
	if(!empty($_GET['ps']) && !isSocialSite()) { 
	  update_post_meta($_GET['ps'], 'partage_social', 1);
	  wp_redirect(remove_query_arg('ps',$_SERVER['REQUEST_URI']));
	  exit;
	}
}
add_action( 'init', 'gest_partage_social' );