<?php

function acf_load_apres_niveau( $field ) {

    // reset choices
    $field['choices'] = array(0=>'');

    $args=array(
      'post_type' => 'niveau',
      'meta_key' => 'ordre',
      'orderby'   => 'meta_value_num',
      'order' => 'ASC',
    );
    $query_tmp = new WP_Query($args);


    if($query_tmp->posts) {
        foreach($query_tmp->posts as $post) {
            if(!get_field('bonus',$post->ID)) {
                $field['choices'][$post->ID]=$post->post_title;
            }
        }
    }
    return $field;
    
}

add_filter('acf/load_field/name=apres_niveau', 'acf_load_apres_niveau');

