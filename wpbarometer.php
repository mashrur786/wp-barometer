<?php
/*
Plugin Name: WP Barometer
Plugin URI: https://wordpress.org/plugins/wpbarometer/
Description: WP Barometer is a simple wordpress plugins which uses a simple shortcode to display custom styled barometer on your WordPress site for your fund-raising activities.
Version: 1
Author: Mashrur Chowdhury
Author URI: http://wpbarometer.com
Text Domain: wpbarometer
Domain Path: /languages
*/

/*
/*
WP Barometer is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 1 of the License, or
any later version.

WP Barometer is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with WP Barometer. If not, see https://www.gnu.org/licenses/gpl-3.0.en.html.

Copyright 2018 Mashrur Chowdhury.
*/


     // Make sure we don't expose any info if called directly
    if ( !function_exists( 'add_action' ) ) {
        echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
        exit;
    }


    /*
    * Registers the wp-Barometer custom post type. One "post" for each bar.
    * It's not public (we'll give it a small UI wrapper in custom admin)
    *
    * public set to true for debugging
    */

    function wp_barometer_register_post_type() {
        $labels = array(
		'name' => __( 'WP Barometer', 'wpbarometer' ),
		'singular_name' => __( 'WP Barometer', 'wpbarometer' ),
		'add_new' => __( 'Add New Barometer', 'wpbarometer' ),
		'add_new_item' => __('Add New Barometer', 'wpbarometer' ),
		'edit_item' => __( 'Edit Barometer', 'wpbarometer' ),
		'new_item' => __( 'New Barometer', 'wpbarometer' ),
		'view_item' => __( 'View Barometer', 'wpbarometer' ),
		'search_items' => __( 'Search Barometer', 'wpbarometer' ),
		'not_found' => __( 'No Barometer Found', 'wpbarometer' ),
		'not_found_in_trash' => __( 'No Barometer Found in the Trash', 'wpbarometer' ),
	    );

        $args = array(
		'labels' => $labels,
		'public' => false,
		'show_ui' => true,
		'capability_type' => 'post',
		'show_in_nav_menus' => true,
		'supports' => array(
			'title',
            'editor'
			),
		);
	    register_post_type( 'wpbarometer', $args );
    }
    add_action( 'init', 'wp_barometer_register_post_type' );

    /*
    * load bootstrap stylesheet and js for admin page
    */
    function wp_barometer_admin_load_assets() {

         wp_register_style( 'Bootstrap',  plugin_dir_url( __FILE__ ) . 'admin/css/bootstrap.css', false, '4.1.3' );
         wp_enqueue_style( 'Bootstrap' );
         wp_register_style( 'jQuery Hex Colorpicker',  plugin_dir_url( __FILE__ ) . 'admin/css/jquery-hex-colorpicker.css', false, '1.1' );
         wp_enqueue_style( 'jQuery Hex Colorpicker' );
         wp_register_style( 'jquery ui',  'http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.0/themes/smoothness/jquery-ui.css', false, '1.11.0' );
         wp_enqueue_style( 'jquery ui' );
         wp_register_script( 'jquery ui',  'http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.0/jquery-ui.min.js', false, '1.12.1' );
         wp_enqueue_script( 'jquery ui' );
         wp_register_script( 'jQuery Hex Colorpicker',  plugin_dir_url( __FILE__ ) . 'admin/js/jquery-hex-colorpicker.min.js', false, '1.1' );
         wp_enqueue_script( 'jQuery Hex Colorpicker' );

    }
    add_action( 'admin_enqueue_scripts', 'wp_barometer_admin_load_assets' );


    /*
    * Setup custom meta boxes for the wp-barometer custom post type page
    */
    function wp_barometer_metabox_create() {
        add_meta_box(
                'wpbarometer_meta',
                __( 'WP Barometer Options' ),
                'wpbarometer_metabox_display',
                'wpbarometer'
        );
    }
    add_action( 'add_meta_boxes', 'wp_barometer_metabox_create' );


    /**
    * Meta box display for the barometer post type.
    * @param $post object
    * Provides the form controls necessary to select the color of the barometer as well as:
    * @return  null
    */
    function wpbarometer_metabox_display($post){

        // Nonce field to validate form request came from current site
        wp_nonce_field(basename(__FILE__), 'wp_barometer_fields');
        // Output the field
        render_field($post->ID,'Target Amount', 'wp_bar_target', 'number');
        render_field($post->ID, 'Amount Raised','wp_bar_raised', 'number');
        render_field($post->ID, 'Width','wp_bar_width', 'number');
        render_field($post->ID, 'Height','wp_bar_height', 'number');
        render_field($post->ID, 'Background Color','wp_bar_bgcolor');
        render_field($post->ID, 'Bar color','wp_bar_color');
        render_field($post->ID, 'Meter Orientation','wp_bar_orientation', 'select', array('horizontal', 'vertical'));
        render_field($post->ID, 'Animation Speed','wp_bar_animation_speed');
        render_field($post->ID, 'Counter Speed','wp_bar_counter_speed');
        render_field($post->ID, 'Display Total','wp_bar_display_total', 'checkbox');
        //display
        echo '<div class="alert alert-success" role="alert">
               Copy the Shortcodes to display Berometer<b> [wpbarometer id=' . $post->ID . ' ]</b>
               </div>';

        echo '<script> 
                	jQuery(".color-container").hexColorPicker({
		                    "container":"dialog",
		                    "colorModel":"hsv",
		                    "pickerWidth":300,
		                    "size":8,
		                    "style":"hex"
	                });
             </script>';

    }

    /**
    * Meta box display for the barometer post type.
    * @param $id, $label, $fieldName, $fieldType, $args array
    *
    * @return  string
    */
    function render_field($id, $label ,$fieldName, $fieldType = "text", $args = []){
        // Get the location data if it's already been entered
        $key_value = get_post_meta($id, $fieldName, true);

        // Output the field
        $output = "<label style='font-size: 1.3em'>$label</label>";
        switch ($fieldType) {

            case 'number' :
                $output .='<input type="number" name="'. $fieldName. '" value="' . $key_value . '" class="form-control">';
                break;

            case 'select' :
                $output .= '<br>';
                $output .= "<Select name='" .$fieldName. "' class='form-control'>";
                foreach($args as $arg){
                    if($key_value == $arg){
                        $output .='<option value="' .  $arg  . '" class="form-control" selected>'. ucfirst($arg) .'</option>';
                    }
                    $output .='<option value="' .  $arg  . '" class="form-control">'. ucfirst($arg) .'</option>';
                }
                $output .= "</Select>";
                break;

            case 'checkbox' :

                $output = '<div class=" form-group form-check">';
                $output .= '<input style="margin: 3px 0 0 -20px;"  id="'. $fieldName .'" name="'. $fieldName .'" class="form-check-input" type="checkbox" ' . ($key_value == 'on' ? 'checked' : '') .'>';
                $output .= "<label for='" . $fieldName . "' class='form-check-label' style='font-size: 1.3em'>$label</label>";
                $output .= '</div>';
                break;

            default:
                $extra_class = '';
                $styles = '';
                if(strpos($fieldName, 'color')){
                    $extra_class = 'color-container';
                    if(!empty($key_value)){
                        $styles = "background-color:".$key_value."; color: white;";
                    }
                }

                $output .= '<input style="' . $styles . '" type="text" name="'. $fieldName. '" value="' . $key_value . '" class="form-control ' . $extra_class . '">';
                break;
          
        }
        
        $output .= '<br>';
        echo $output;

    }

    /** Saves the meta box info for the post
     * - wp_barometer_meta_save
     * @param $post_id
     *
     * @return String
    */

    function wp_barometer_meta_save( $post_id) {
        //die(var_dump($_POST));
        //check if nonce filed is set
        if( !isset( $_POST['wp_barometer_fields'] )) return;

        //skip auto save
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }

        // If this isn't a 'wpbarometer' post, don't update it.
        $post_type = get_post_type($post_id);
        if ( "wpbarometer" != $post_type ) return;

        $meta_keys = [
            'wp_bar_target',
            'wp_bar_raised',
            'wp_bar_width',
            'wp_bar_height',
            'wp_bar_bgcolor',
            'wp_bar_color',
            'wp_bar_orientation',
            'wp_bar_animation_speed',
            'wp_bar_counter_speed',
            'wp_bar_display_total'
            ];

        $inserted_keys = [];
        //array to store the keys to be deleted after update


        //die(var_dump($_POST));
        //compare post metas in $_POST and $meta_keys and if found only update those post metas in the database
        foreach($_POST as $key => $value){

            if(in_array($key, $meta_keys)) {
                $inserted_keys[] = $key;
                 if (isset($_POST[$key]))
                     update_post_meta($post_id, $key, sanitize_text_field($value));

            }
        }

        //del keys in the db which weren't present in $_POST
        $del_keys = array_diff($meta_keys, $inserted_keys);

        foreach($del_keys as $key){
            if(metadata_exists('post', $post_id, $key))
                delete_post_meta($post_id, $key);

        }



    }
    add_action( 'save_post', 'wp_barometer_meta_save', 10, 3);

    /**
    * Register  WP Barometer shortcode
    *
    * @return null
    */
    function register_wp_barometer_shortcode() {
        add_shortcode( 'wpbarometer', 'wp_barometer_shortcode' );
    }
    add_action( 'init', 'register_wp_barometer_shortcode' );

    /**
    * WP Barometer Shortcode Callback
    * - WP Barometer
    *
    * @param Array $args
    *
    * @return string
    */
    function wp_barometer_shortcode($args) {
       //$output = '';
       $args = shortcode_atts( array(
            'id' => '',
       ), $args);

       $post_id = $args['id'];
       ob_start();
       do_action('wp_barometer_before_render');
       ?>
        <?php echo get_post_field('post_content', $post_id); ?>
        <br>
        <br>

        <div id="jqmeter-container-<?php echo  $post_id ?>"></div>
        <script>
            var $ = jQuery;
            $(document).ready(function(){

            var container =  $('#jqmeter-container-'+ <?php echo $post_id ?>);
            var goal = String(<?php echo get_post_meta($post_id, 'wp_bar_target', true); ?>);
            var raised = String(<?php echo get_post_meta($post_id, 'wp_bar_raised', true); ?>);
            var width = "<?php echo get_post_meta($post_id, 'wp_bar_width', true); ?>";
            var height = "<?php echo get_post_meta($post_id, 'wp_bar_height', true); ?>";
            var orientation = "<?php echo get_post_meta($post_id, 'wp_bar_orientation', true); ?>";
            var bgColor = "<?php echo get_post_meta($post_id, 'wp_bar_bgcolor', true); ?>";
            var barColor = "<?php echo get_post_meta($post_id, 'wp_bar_color', true); ?>";
            var counterSpeed = <?php echo get_post_meta($post_id, 'wp_bar_counter_speed', true); ?>;
            var animationSpeed = <?php echo get_post_meta($post_id, 'wp_bar_animation_speed', true); ?>;
            var displayTotal = "<?php echo get_post_meta($post_id, 'wp_bar_display_total', true); ?>";
                displayTotal = (displayTotal === "on");

            $(container).jQMeter(
                {
                    goal: goal ,
                    raised: raised ,
                    width: width + "%",
                    height: height + "px",
                    meterOrientation: orientation,
                    bgColor: bgColor,
                    barColor: barColor,
                    counterSpeed: counterSpeed,
                    animationSpeed: animationSpeed,
                    displayTotal: displayTotal
                })
            });
        </script>
        <?php

       return ob_get_clean();

  
    }

    function wp_barometer_enqueue_script() {
             wp_enqueue_script( 'bootstrap stylesheet',  plugin_dir_url( __FILE__ ) . 'public/jqmeter.js', false, '4.1.3' );
    }
    add_action( 'wp_barometer_before_render', 'wp_barometer_enqueue_script' );


     // Admin footer modification
    function wp_barometer_admin_footer ()
    {
        echo '<span id="footer-thankyou">WP Barometer | Developed by <a href="http://www.mashrur.co.uk" target="_blank">Mashrur Chowdhury</a></span>';
    }

    add_filter('admin_footer_text', 'wp_barometer_admin_footer');

    // Admin footer modification

    add_filter( 'admin_print_styles', 'insert_header_wpse_51023' );

    function insert_header_wpse_51023()
    {
        //echo '<div style="width:200px;"><img src="'. plugin_dir_url( __FILE__ ) . 'admin/imgs/wpbarometer.png" width="100%" /></div>';
    }










