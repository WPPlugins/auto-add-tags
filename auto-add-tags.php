<?php
/*
Plugin Name: Auto Add Tags
Plugin URI: http://dev.coziplace.com/free-wordpress-plugins/auto-add-tags
Description:  If any pre-existing tags are found in the post content, this plugin will automatically assign the tags to the posts when saved. Please <a href="http://wordpress.org/extend/plugins/auto-add-tags">[Rate]</a> this plugin or <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=TQBF5W4FHCS56">[Donate]</a>.
Version: 4.1.1
Author: Narin Olankijanan
Author URI: http://dev.coziplace.com
License: GPLv2
*/
add_action('save_post', 'dk_save_post');

function dk_save_post(){

        $tags = get_tags( array('hide_empty' => false) );
        $post_id = get_the_ID();
        $post_content = get_post($post_id)->post_content;
        $post_title = get_post($post_id)->post_title;
        $d = strtolower($post_title.' '.$post_content);     
        $options = get_option('aat_options');
        $whole = $options['whole'];
        if ($whole=='checked') { 
           $x = array(',','.','?','"');
           $dummy = str_ireplace($x,'',$d);
           $m = explode(' ',$dummy);       
           if ($tags) {
            foreach ( $tags as $tag ) {      
              foreach($m as $n) {
                if (strtolower($tag->name)==$n) wp_set_post_tags( $post_id, $tag->name, true );
              }
            }
           } 
     } else {
           if ($tags) {
            foreach ( $tags as $tag ) { 
             if ( strpos($d, strtolower($tag->name))) wp_set_post_tags( $post_id, $tag->name, true );
            }
           }
    }
        
  }


add_action( 'admin_menu', 'aat_create_menu');

function aat_create_menu() {
   
         add_options_page( 'Auto-add-tags', 'Auto-add-tags','manage_options', 'gus', 'aat_menu_page');
}

function aat_menu_page() {

        ?>
        <div class="wrap">
        <?php screen_icon(); ?>
<h2>Auto Add Tags Plugin</h2>
<form action="options.php" method="post">
<?php settings_fields('aat_options'); ?>
<?php do_settings_sections('aat_plugin'); ?>
<input name="Submit" type="submit" value="Save Options" />
</form>
</div>
<?php
}

add_action('admin_init','aat_admin_init');

function aat_admin_init(){
 
     register_setting('aat_options', 'aat_options');
     add_settings_section('aat_main','Plugin Settings', 'aat_section_text','aat_plugin');
     add_settings_field( 'aat_whole', 'Whole Words only?', 'aat_setting_input','aat_plugin', 'aat_main');
}

function aat_section_text() {
     
     
}

function aat_setting_input(){
     $options = get_option('aat_options');
     $whole = $options['whole'];
     echo "<br><input type='checkbox' id='whole' value='checked' name='aat_options[whole]' ".$whole."> Yes"  ; 
     
}

/* EOF */