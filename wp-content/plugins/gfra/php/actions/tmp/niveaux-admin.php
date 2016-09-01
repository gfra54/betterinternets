<?php

add_filter('post_row_actions','niveau_action_row', 10, 2);

function niveau_action_row($actions, $post){
    //check for your post type
    if ($post->post_type =="niveau"){
        $actions['previsu'] = '<a href="/?pid='.$post->ID.'" target="_blank">Prévisualiser</a>';
    }
    return $actions;
}