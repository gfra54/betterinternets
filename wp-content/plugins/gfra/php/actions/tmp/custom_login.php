<?php

function custom_login_header() {
	if(strstr($_SERVER['REQUEST_URI'], 'wp-admin')===false) {
		load_file('/css/login.css');
	}
}
add_action('login_head', 'custom_login_header');

function custom_login_footer() {
	if(strstr($_SERVER['REQUEST_URI'], 'wp-admin')===false) {
		wp_enqueue_script('jquery');
		wp_enqueue_script('login',freshurl('/js/login.js'));
	}
}
add_action('login_footer', 'custom_login_footer');


function login_actions($user_login, $user) {
	session_start();
	if($user->ID) {
		update_field('avancement',$_SESSION['user_avancement']['avancement'],'user_'.$user->ID);
		update_field('rang',$_SESSION['user_avancement']['rang'],'user_'.$user->ID);
		update_field('clicks',$_SESSION['user_avancement']['clicks'],'user_'.$user->ID);
		update_field('temps',$_SESSION['user_avancement']['temps'],'user_'.$user->ID);
		update_field('temps_bonus',$_SESSION['user_avancement']['temps_bonus'],'user_'.$user->ID);
	}
	if($_POST['redirect_to'] == '/')  {
	?>
	<script>
		if(window.opener) {
			window.opener._safe_click=true;
			window.opener.location.href='/';
			window.close();
		} else {
			document.location.href='/';
		}

	</script>
	<?php
	exit;
}
}
add_action('wp_login', 'login_actions',10,2);

