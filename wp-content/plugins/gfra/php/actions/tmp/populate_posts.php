<?php

function acf_load_items( $field ) {
    // reset choices
    $field['choices'] = array(0=>'');


    $posts = get_items(true);
    if($posts) {
        foreach($posts as $post) {
            $field['choices'][$post['id']]=$post['titre'];
        }
    }

    return $field;
    
}
add_filter('acf/load_field/name=items', 'acf_load_items');