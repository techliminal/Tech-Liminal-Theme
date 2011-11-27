<?php
/* Template Name:  Home Page
 * Tech Liminal Homepage file
 */
 
function home_layout(){ 
  echo '<div id="home-top">';
  echo '<div id="home-top-left">';
    dynamic_sidebar( 'home-main-left' );
  echo '</div><!-- end #home-top-left -->';
  
  echo '<div id="home-top-right">';
    dynamic_sidebar( 'home-main-right' );
  echo '</div><!-- end #home-top-right -->';
  
  echo '</div><!-- end #home-top -->';
	
  echo '<div id="home-middle">';
    dynamic_sidebar( 'home-middle' );
  echo '</div><!-- end #home-middle -->';
		
}



/* ---------------------------  Page Rendering ---------------------------*/

add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

remove_action('genesis_loop', 'genesis_do_loop');
add_action( 'genesis_loop', 'home_layout' );

genesis();


?>