<?php
/* functions.php - functions file for Tech Liminal Theme
 * By Anca Mosoiu
 */
 
/** Start the engine */
require_once( get_template_directory() . '/lib/init.php' );

/** Child theme (do not remove) */
define( 'CHILD_THEME_NAME', 'Tech Limial Child Theme' );
define( 'CHILD_THEME_URL', 'http://techliminal.com' );

/** Add new image sizes */
add_image_size( 'home-bottom', 170, 90, TRUE );
add_image_size( 'home-middle', 265, 150, TRUE );
add_image_size( 'home-mini', 50, 50, TRUE );
 
 
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
  'description' => 'This is the right columns of the homepage',
  'before_title'=>'<h2 class="widgettitle">','after_title'=>'</h2>'
)); 

genesis_register_sidebar(array(
  'id' => 'home-middle', 
  'name'=>'Home Middle Section',
  'description' => 'Middle section of the homepage, appears above footer',
  'before_title'=>'<h3 class="widgettitle">','after_title'=>'</h3>'
)); 

/** Add support for 4 columns of footer widgets */
add_theme_support( 'genesis-footer-widgets', 4 );

function tl_header(){
?>
  <div id="logo"><a href="/"><img src="/images/logo-web.png" height=72 width=198 alt="Homepage" title="Tech Liminal" align="left"/></a>
	<img src="/images/tagline.png" height=18 width=198 alt="Technology Hotspot and Salon"/>
	</div>
	
	<div id="cinfo">268 14th Street<br/>Oakland, CA 94612<br/>info@techliminal.com<br/>(510) 832-3401</div>
<?
}

?>