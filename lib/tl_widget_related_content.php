<?php
/*
 * Widget name: Related Links Widget
 * Wdget URI: http://techliminal.com/
 * Description: Widget file for Tech Liminal Related Content Widget.  To use, 
 *   add to a lib folder in your theme and include in your functions.php file.
 *   Note the section for adding the metabox - you can include based on post type.
 * Requires:  tl_utilities.php
 * Version: 1.0
 * Author: Tech Liminal / David Doolin, Anca Mosoiu
 * Author URI: http://techliminal.com/
 */

if (!class_exists('tl_related_content_widget')) {

  class tl_related_content_widget extends WP_Widget {


    var $css_class        = 'related-content-widget';
    var $description      = 'Displays related content for a page or post';
    var $base_id          = 'related-content-widget';
    var $name             = 'Related Content Widget';
    var $default_title    = '';


    function tl_related_content_widget() {

      $widget_ops = array('classname' => $this->css_class, 
                          'description' => $this->description);
      $this->WP_Widget($this->base_id, $this->name, $widget_ops);
      // Any callback in scope echoing to output should work.
      add_action('display-related-content', array($this, 'display'));
    }


    /* This is the code that gets displayed on the UI side,
     * what readers see.
     */
    function widget($args, $instance) {

      extract($args, EXTR_SKIP);

      
      $title = (empty($instance['title'])) ? $this->default_title : apply_filters('widget_title', $instance['title']); 
      global $post; $post_id = $post->ID;
      $links = get_post_meta($post_id, '_tl_related_content', true);
      if (empty($links)) {
        return;
      } else {

        echo $before_widget;
        echo $before_title . $title . $after_title; 
        do_action('display-related-content', $links);
        echo $after_widget;
      }
    }


    function display($links) {
?>
  <div><?php echo $links; ?></div>
<?php
    }


    function update($new_instance, $old_instance) {
      
      $instance = $old_instance;
      $instance['title'] = strip_tags($new_instance['title']);
      $instance['tl_related-content'] = $new_instance['tl_related-content'];
      return $instance;
    }


    function form($instance) {
          
     $instance = wp_parse_args( (array) $instance, array( 'title' => '', 'demotext' => '' ) );
      echo "<p>";
      echo $this->text_input_instance($instance, 'title', 'Title');
      echo "</p>";
    }


    function text_input_instance($instance, $key, $label) {

      $value = esc_attr(strip_tags($instance[$key]));
      $id    = $this->get_field_id($key);
      $name  = $this->get_field_name($key);
      return $this->text_input($id, $name, $value, $label);
    }


    function text_input($id, $name, $value, $label) {

      $input = <<<EOI
      <label for="$id">$label:
        <input class="widefat" id="$id" name="$name" type="text" value="$value" />
      </label>
EOI;
      return $input;
    }

  } // Close class definition...



  function related_content_widget_init() {
    register_widget('tl_related_content_widget');
  }
  add_action('widgets_init', 'related_content_widget_init');

} // class_exists()...



class tl_related_content_metabox {

  var $keys;

  function tl_related_content_metabox() {
    $this->keys = array('_tl_related_content');
    add_action('add_meta_boxes', array($this,'init'));
    add_action('save_post',array($this,'save_metabox'));
  }


  function init() {

    add_meta_box(
     'related_content',
  __('Related Content', 'tl'),
     array($this, 'related_content_data_metabox'),
     'post',
     'side');

    add_meta_box(
     'related_content',
  __('Related Content', 'tl'),
     array($this, 'related_content_data_metabox'),
     'page',
     'side');
  }


  function related_content_data_metabox() {

    wp_nonce_field(plugin_basename(__FILE__), '_tl_related_content_noncename');

    $single = true;
    global $post;
    $post_id = $post->ID;

    echo '<p>';
    $value = 'related-content';
    // this is where we should be using make_field_name
    $fieldname = '_tl_related_content';
    $id = '_tl_related_content';
    $namevalue = get_post_meta($post_id, $fieldname, $single);
    echo '<label for="' . $id . '">Add your Related Content Here (HTML is okay)</label><br />';
    echo '<textarea id="related-content-text" cols="35" name="' . $fieldname . '" rows="7">';
    echo $namevalue;
    echo '</textarea>';
    echo '</p>';
  }


  function cannot_save_postmeta_for($postid) {

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return true; }
    if (!ISSET($_POST['_tl_related_content_noncename'])) { return true; }
    if (!wp_verify_nonce($_POST['_tl_related_content_noncename'], plugin_basename(__FILE__))) { return true; }
    if (!current_user_can('edit_post', $postid)) return true;
    
    return false;
  }


  function save_postmeta($post_id, $keys) {

    if ($this->cannot_save_postmeta_for($post_id)) {return;}
    
    foreach ($keys as $key => $value) {
      $fieldname = $value;
      $fieldvalue = isset($_POST[$fieldname]) ? $_POST[$fieldname] : null;
      if ($fieldvalue){
        update_post_meta($post_id, $fieldname, $fieldvalue);
      } else {
        delete_post_meta($post_id, $fieldname);
      }
    } 
  }


  function save_metabox($post_id) {

    if ($this->cannot_save_postmeta_for($post_id)) {return;}
    $this->save_postmeta($post_id, $this->keys);
  }

}


?>
