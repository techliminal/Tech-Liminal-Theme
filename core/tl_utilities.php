<?php


// TODO: Build this out in its own file.
class tl_formbuilder {
  
  // TODO: Add class to parameter list...?
  public static function text_input($id, $name, $value, $label) {
    
    $input = <<<EOI
    <label for="$id">$label:
      <input class="event-input" id="$id" name="$name" type="text" value="$value" />
    </label>
EOI;
    return $input;
  }
  //TODO: Add a drop-down menu form field (used on books screen in Box 10.C and 10.E))
  
  public static function text_area($id, $name, $value, $label) {
 
    $textarea = <<<EOTA
    <label for="$id">$label</label><br />
    <textarea cols="65" name="$name" rows="7">
    $value
    </textarea>
EOTA;
    return $textarea;
  }
  
  /*
   * selction_list. Returns HTML code for a selecton list, with the proper 
   * option selected.
   * @param string $id : the ID of the list
   * @param string $name:  name of the list
   * @param array $values: key-value pairs where the key is the option value field and the value is the display text
   * @param string $selected: the value of the selected item.
   * @param string $class: the name of the class name for this select box
   */
  public static function select_list($id, $name, $values, $label, $selected='', $class=''){
  
    $select = '<label for"' . $id . '">' . $label . '</label><br /><select id="'. $id . '" name="' . $name . '">';
    foreach ($values as $key=>$value){
      if ($selected == $key){
        $sel = 'selected';
      } else {
        $sel = '';
      }
      
      $select .= '<option ' . $sel . ' value="' . $key . '">' . $value . '</option>';
    }
    $select .= "</select>";
    return $select;
  }
  
  /*
   * single_checkbox. Returns HTML code for a single checkbox, which can be checked or unchecked.
   * @param string $id : the ID of the input
   * @param string $name:  name of the input
   * @param array $values: key-value pairs where the key is the option value field and the value is the display text
   * @param string $selected: the value of the selected item.
   * @param string $class: the name of the class name for this select box
   */
/*public static function single_checkbox($id, $name, $label, $checked='', $class='', $index='') {
  
    if ($index){
      $id .= "-" . $index;
    }
    
    $checkbox = <<<EOC
      <label for "$id">$label: <input type="checkbox" id="$id" name="$name" $checked ></label>
EOC;    
    return $checkbox;
  }*/

}



class tl_utilities {

  
  function __construct() {
    
  }
  
  public static function make_field_name($value) {
    return THEME_PREFIX . $value. '_field';
  }

  public static function make_id_name($value) {
    return THEME_PREFIX . $value;
  }

  public static function make_list_item($content, $class='') {
    return '<li class="' . $class .'">' . $content . '</li>';
  }
  
  public static function format_link($url, $anchor, $class = '', $title = '') {
    $link = <<<EOL
    <a href="$url" class="$class" title="$title">$anchor</a>
EOL;
    return $link;
  }


  // Needed in pre PHP 5.3 environments
  public static function make_week_date($date_in){
    
    if (function_exists ('date_create_from_format')) {
      $datetime = date_create_from_format('Y-m-d', $date_in, new DateTimeZone('PDT'));
      if ($datetime) {
        $date = $datetime->format('l M d Y'); 
      } else {
        $date = "0";
      }
    } else {
      $bits = split("-", $date_in);
      if (!isset($bits[2])){
        return "0";
      }
      $date = date("D M d Y", mktime(0,0,0,$bits[1], $bits[2], $bits[0]));
    }

    return $date;
  }


  public static function make_time_format($time_in) {

    if (function_exists ('date_create_from_format')) {
      $datetime = date_create_from_format('H:i:s', $time_in, new DateTimeZone('PDT'));
      if ($datetime) {
        $date = $datetime->format('g:i A'); 
      } else {
        $date = "0";
      }
    } else {
      $bits = split(":", $time_in);
      if (!isset($bits[0])){
        return "0";
      } 
      if (!isset ($bits[1])){
        $bits[1] = 0;
      }
      $date = date("g:i A", mktime($bits[0], $bits[1])); 
    }
    return $date;
  }

  // Needed in pre PHP 5.3 environments  
  public static function make_short_date($date_in){
    $bits = split("-", $date_in);
    if (!isset($bits[2])){
        return "0";
    }
    
    return date("M d Y", mktime(0,0,0,$bits[1], $bits[2], $bits[0])); 
  }
  
  // Needed in pre PHP 5.3 environments  
  public static function make_display_time($time_in){
    $bits = split(":", $time_in);
    if (!isset($bits[0])){
      return "0";
    } 
    if (!isset ($bits[1])){
        $bits[1] = 0;
    }
    return date("g:i A", mktime($bits[0], $bits[1])); 
  }

  public static function make_meta_info($postid, $meta_key, $class='', $wrapper='') {

    $meta_info = get_post_meta($postid, $meta_key, TRUE);

    $mi = '';
    if ($meta_info) {
      if ($wrapper) {
        $mi .= '<' . $wrapper;
        if ($class){
          $mi .= ' class="' . $class . '"';
        }
        $mi .= '>' . $meta_info . '</' . $wrapper . '>';
      } else if ($class){
        $mi .= '<span class="' . $class . '">' .  $meta_info . '</span>';
      } else {
        $mi .= $meta_info;
      }
    }
    return $mi;
  }

}


class tl_metabox {

  var $id        = '';
  var $keys      = '';
  var $callback  = '';
  var $title     = '';
  var $noncename = '';
  var $post_type = '';
  var $position  = ''; //normal, advanced, side
  var $namespace = '';

  function __construct() {
    add_action('add_meta_boxes', array($this,'initialize'));
    add_action('save_post',array($this,'save_metabox'));
  }

  function initialize() {
    add_meta_box(
      $this->id,
   __($this->title, $this->namespace),
      array($this, 'this_data_metabox'),
      $this->post_type,
      $this->position);
    add_action($this->callback, array($this, $this->callback));
  }


  function this_data_metabox() {
    wp_nonce_field(__FILE__, $this->noncename);
    do_action($this->callback);
  }


  function cannot_save_postmeta_for($postid) {

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return true; }

    //var_dump($_POST);
    if (!isset($_POST[$this->noncename])) { return true; }
    if (!wp_verify_nonce($_POST[$this->noncename], __FILE__)) { return true; }

    if (isset( $_POST['post_type']) && $this->post_type == $_POST['post_type']) {
      if (!current_user_can('edit_post', $postid)) return true;
    } else {
      return true;
    }
    return false;
  }


  function save_postmeta($post_id, $keys) {

    foreach ($keys as $key => $value) {
      $fieldname = tl_utilities::make_field_name($value);
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
