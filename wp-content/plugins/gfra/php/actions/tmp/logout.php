<?php
function onlogout() {
	setcookie('avancement');
	session_start();
	$_SESSION['user_avancement']=false;
}
add_action('wp_logout', 'onlogout');