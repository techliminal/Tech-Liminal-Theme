<?php
/* Events Category */


function tl_events_content(){

  the_content();
}

/* ----------------  Page Rendering ------------*/

remove_action('genesis_before_post_content', 'genesis_post_info');
remove_action('genesis_post_content', 'genesis_do_post_content');
add_action('genesis_after_post_content', 'genesis_post_info');
add_action('genesis_post_content', 'tl_events_content');

genesis();

?>