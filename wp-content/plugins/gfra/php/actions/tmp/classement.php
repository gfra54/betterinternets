<?php

function lien_joueur($actions, $post){
    if( is_admin() && $_GET['post_type'] == 'scores') {
	    $actions = array('joueur'=>'<a href="/wp-admin/user-edit.php?user_id='.get_field('id_joueur',$post->ID).'">Profil&nbsp;joueur</a>') + $actions;
	    unset($actions['inline hide-if-no-js']);
	    unset($actions['edit']);
	    unset($actions['view']);
	}
   return $actions;
}
add_filter('post_row_actions', 'lien_joueur', 10, 2);

function colonnes_score($columns) {
    if( is_admin() && $_GET['post_type'] == 'scores') {
		$columns['temps'] = 'Temps';
		$columns['temps_bonus'] = 'Bonus';
		$columns['temps_final'] = 'Score final';
		$columns['clicks'] = 'Clics';
		$columns['ip'] = 'Ip';
	}
	return $columns;
}
add_filter('manage_scores_posts_columns', 'colonnes_score');

function colonnes_score_sort( $columns ) {
    if( is_admin() && $_GET['post_type'] == 'scores') {
		$columns['temps'] = 'temps';
		$columns['temps_bonus'] = 'temps_bonus';
		$columns['temps_final'] = 'temps_final';
		$columns['clicks'] = 'clicks';
		$columns['ip'] = 'ip';
	}
    return $columns;
}
add_filter( 'manage_edit-scores_sortable_columns', 'colonnes_score_sort' );

function colonnes_score_valeur($column_name, $post_id) {
	if(in_array($column_name,array('temps','temps_bonus','temps_final','clicks','ip'))!==false) {
		echo get_field($column_name,$post_id);
	}
}
add_action('manage_scores_posts_custom_column',  'colonnes_score_valeur', 10, 3);

function colonnes_score_orderby( $vars ) {
    if( is_admin() && $_GET['post_type'] == 'scores') {

		if(in_array($vars['orderby'],array('temps','temps_bonus','temps_final','clicks','ip'))!==false) {
	        $vars = array_merge( $vars, array(
	            'meta_key' => $vars['orderby'],
	            'orderby' => 'meta_value_num'
	        ) );
	    }
	}
	if(is_admin() && $_GET['score_date']) {
		$score_date=$_GET['score_date'];
		if($score_date == 'today') {
			$d = date('d');
			$m = date('m');
			$y = date('Y');
		} else if($score_date == 'yesterday') {
			$d = date('d',time()-24*3600);
			$m = date('m',time()-24*3600);
			$y = date('Y',time()-24*3600);
		} else {
			list($d,$m,$y) = explode('-',$_GET['score_date']);
		}
        $vars = array_merge( $vars, array(
            'year' => $y,
            'month' => $m,
            'day' => $d,
		    'posts_per_page'=>-1
        ) );
	}
    return $vars;
}
add_filter( 'request', 'colonnes_score_orderby' );

/*
function classement_pre_user_query($user_search) {
    global $wpdb,$current_screen;

    if($_GET['role']=='subscriber') {
	    if ( 'users' != $current_screen->id ) 
	        return;

	    $vars = $user_search->query_vars;

		$user_search->query_from .= " INNER JOIN {$wpdb->usermeta} m1 ON {$wpdb->users}.ID=m1.user_id AND (m1.meta_key='score')"; 
		$user_search->query_orderby = ' ORDER BY CAST(m1.meta_value AS SIGNED) DESC';
	}
	return $user_search;
}
add_action('pre_user_query','classement_pre_user_query');

function colonne_score_final($columns) {
    if($_GET['role']=='subscriber') {
		$columns['score'] = 'Score final';
	}
	return $columns;
}
add_filter('manage_users_columns', 'colonne_score_final');


function colonne_score_final_valeur($value, $column_name, $user_id) {
	if ( $column_name  == 'score') {
		$value = get_field('score','user_'.$user_id);
	} 
	return $value;
}
add_action('manage_users_custom_column',  'colonne_score_final_valeur', 10, 3);*/