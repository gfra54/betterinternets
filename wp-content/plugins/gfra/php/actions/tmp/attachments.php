<?php
define( 'ATTACHMENTS_SETTINGS_SCREEN', false ); // disable the Settings screen

define( 'ATTACHMENTS_DEFAULT_INSTANCE', false );

function my_attachments( $attachments )
{
  $fields         = array(
    array(
      'name'      => 'title',                         // unique field name
      'type'      => 'text',                          // registered field type
      'label'     => __( 'Title', 'attachments' ),    // label to display
      'default'   => 'title',                         // default value upon selection
    ),
    array(
      'name'      => 'caption',                       // unique field name
      'type'      => 'textarea',                      // registered field type
      'label'     => __( 'Caption', 'attachments' ),  // label to display
      'default'   => 'caption',                       // default value upon selection
    ),
  );

  $args = array(

    // title of the meta box (string)
    'label'         => 'Pièces jointes',

    // all post types to utilize (string|array)
    'post_type'     => array( 'post', 'page', 'livres' ),

    // meta box position (string) (normal, side or advanced)
    'position'      => 'normal',

    // meta box priority (string) (high, default, low, core)
    'priority'      => 'high',

    // allowed file type(s) (array) (image|video|text|audio|application)
    'filetype'      => null,  // no filetype limit

    // include a note within the meta box (string)
    'note'          => 'Ajoutez des pièces jointes',

    // by default new Attachments will be appended to the list
    // but you can have then prepend if you set this to false
    'append'        => true,

    // text for 'Attach' button in meta box (string)
    'button_text'   => __( 'Ajouter des fichiers', 'attachments' ),

    // text for modal 'Attach' button (string)
    'modal_text'    => __( 'Attacher', 'attachments' ),

    // which tab should be the default in the modal (string) (browse|upload)
    'router'        => 'browse',

    // fields array
    'fields'        => $fields,

  );

  $attachments->register( 'attachments', $args ); // unique instance name
}

add_action( 'attachments_register', 'my_attachments' );