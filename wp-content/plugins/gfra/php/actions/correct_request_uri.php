<?php
/* Empecher la boucle de redirection lors de redirect_canonical si REQUEST_URI contient index.php */
remove_action('template_redirect', 'redirect_canonical');
add_action('template_redirect', 'correct_redirect_canonical');
function correct_redirect_canonical(){

  if(strstr($_SERVER['REQUEST_URI'],'/index.php')===false){
    redirect_canonical();
  }
}

