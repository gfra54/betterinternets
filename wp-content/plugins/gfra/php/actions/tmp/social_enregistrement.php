<?php 

function social_enregistrement() {
    $user = wp_get_current_user();
    if($user->exists()) {
    	if(in_array( 'subscriber', (array) $user->roles)) {
    		$email = $user->data->user_email;
    		if(strstr($email, 'example.com')!==false) {
    			$email='@'.$user->data->user_nicename;
    		}
    		if($email) {
    			wp_logout();
	    		wp_redirect('/?email='.urlencode($email).'&nom='.urlencode($user->data->display_name));
	    		exit;
/*    			$niveaux = get_niveaux(); 
    			$data = enregistrement($user->data->display_name, false, $email,false, false, false, $niveaux[0]['id']);
	    		if($data['id']) {
	    			wp_logout();
	    			wp_redirect('/?social_enregistrement='.$data['id']);
	    			exit;
	    		} else {
	    			wp_logout();
	    			wp_redirect('/?window_message='.urlencode('VOUS AVEZ DÉJÀ JOUÉ CETTE SEMAINE.<br>RENDEZ-VOUS À LA PROCHAINE JOURNÉE DE PREMIER LEAGUE POUR DÉFIER UN NOUVEAU CONSULTANT !
'));
	    			exit;
	    		}*/
		    }
    	}
	}
}



add_action( 'init', 'social_enregistrement' );
