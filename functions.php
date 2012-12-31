<?php
/* functions.php - functions file for Tech Liminal Theme
 * By Anca Mosoiu
 */
 
/** Start the engine */
require_once( get_template_directory() . '/lib/init.php' );

/** Child theme (do not remove) */
define( 'CHILD_THEME_NAME', 'Tech Liminal Child Theme' );
define( 'CHILD_THEME_URL', 'http://techliminal.com' );

/** Add new image sizes */
add_image_size( 'home-middle', 300, 120, TRUE );
add_image_size( 'home-mini', 60, 60, TRUE );
add_image_size( 'tiny-feature', 24, 48, TRUE);
  
/* --------------------  Widget Areas ------------------------ */

genesis_register_sidebar(array(
  'id' => 'home-main-left', 
  'name'=>'Home Main Left',
  'description' => 'This is the left main feature of the homepage',
  'before_title'=>'<h2 class="widgettitle">','after_title'=>'</h2>'
));

genesis_register_sidebar(array(
  'id' => 'home-main-right', 
  'name'=>'Home Main Right',
  'description' => 'This is the left main feature of the homepage',
  'before_title'=>'<h2 class="widgettitle">','after_title'=>'</h2>'
)); 

genesis_register_sidebar(array(
  'id' => 'home-middle', 
  'name'=>'Home Middle Section',
  'description' => 'Middle section of the homepage, appears above footer',
  'before_title'=>'<h3 class="widgettitle">','after_title'=>'</h3>'
)); 

/** Add support for 4 columns of footer widgets */
add_theme_support( 'genesis-footer-widgets', 3 );


remove_action('genesis_header', 'genesis_do_header');
add_action('genesis_header', 'tl_header');

function tl_header(){
?>
  <div id="logo">
  	<a href="/"><img src="/wp-content/themes/techliminal/assets/img/logo-web.png" height=72 width=198 alt="Homepage" title="Tech Liminal" align="left"/>
		<img src="/wp-content/themes/techliminal/assets/img/tagline.png" height=18 width=198 alt="Technology Hotspot and Salon"/></a>
	</div>
  <div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress" id="contact_info">
  	<h4 class="widgettitle">Find Us</h4>
    <span itemprop="streetAddress"><a href="/find-us" title="Get a map and directions">555 12th St. #110</a></span><br/>
    <span itemprop="addressLocality">Oakland</span>,
    <span itemprop="addressRegion">CA</span>
    <span itemprop="postalCode">94607</span><br/>
    <span itemprop="telephone">(510) 832-3401</span><br/>
  	<span itemprop="email"><a href="/contact-us" >info@techliminal.com</a></span>

  </div>
<?
}

/* ---------------------------  Event Posts --------------------- */

// Remove byline from events posts
add_filter('genesis_post_info', 'tl_custom_post_info');
function tl_custom_post_info($post_info) {
  global $post;
  if ( $post->post_type === 'ai1ec_event' ) {
    return;
  } else {
    return $post_info;
  }
}

// Display event post meta with event categories and tags
add_filter('genesis_post_meta', 'event_post_meta');
function event_post_meta($post_meta) {
  global $post;
	if ( $post->post_type === 'ai1ec_event' ) {
	
  	$before_category = __( 'Filed Under: ', 'genesis' );
  	$before_tag = __( 'Tagged With: ', 'genesis' );
  	
  	$event_categories = get_the_term_list( $post->ID, 'events_categories', $before_category, ', ' );
  	$event_tags = get_the_term_list( $post->ID, 'events_tags', $before_tag, ', ' );
  	
  	$post_meta = sprintf( '<span class="categories">%s</span><span class="tags">%s</span>', $event_categories, $event_tags );
  	
  	return $post_meta;
  	
	} else {
  	return $post_meta;
	}
}

/* ---------------------------  JQuery --------------------- */

add_action('wp_enqueue_scripts', 'tl_load_scripts');
function tl_load_scripts(){
	wp_enqueue_script('tl-stripe', get_stylesheet_directory_uri() .'/assets/js/stripe.js',  array('jquery'), false, true );
}