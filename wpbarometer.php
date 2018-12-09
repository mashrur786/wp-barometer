<?php
/*
Plugin Name: WP Barometer
Plugin URI: https://wordpress.org/plugins/wpbarometer/
Description: WP Barometer is a simple wordpress plugins which uses a simple shortcode to display custom styled barometer on your WordPress site for your fund-raising activities.
Version: 1
Author: Mashrur Chwodhury
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
			'title'
			),
		);
	    register_post_type( 'wpbarometer', $args );
    }
    add_action( 'init', 'wp_barometer_register_post_type' );

    /*
    * load bootstrap stylesheet and js for admin page
    */
    function wp_barometer_admin_load_assets() {
         wp_register_style( 'bootstrap stylesheet',  plugin_dir_url( __FILE__ ) . 'admin/css/bootstrap.css', false, '4.1.3' );
         wp_enqueue_style( 'bootstrap stylesheet' );
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
    *
    * Provides the form controls necessary to select the color of the barometer as well as:
    */
    function wpbarometer_metabox_display($post){

        // Nonce field to validate form request came from current site
        wp_nonce_field(basename(__FILE__), 'wp_barometer_fields');
        // Get the location data if it's already been entered
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



    }

    function render_field($id, $label ,$fieldName, $fieldType = "text", $args = []){
        // Get the location data if it's already been entered
        $key_value = get_post_meta($id, $fieldName, true);
        // Output the field
        $output = "<label>$label</label>";
        switch ($fieldType) {

            case 'number' :
                $output .='<input type="number" name="'. $fieldName. '" value="' . $key_value . '" class="form-control">';
                break;

            case 'select' :
                $output .= '<br>';
                $output .= "<Select name='" .$fieldName. "' class='form-control'>";
                foreach($args as $arg){
                    if($key_value == $arg)
                        $output .='<option value="' .  $arg  . '" class="form-control" selected>'. ucfirst($arg) .'</option>';

                    $output .='<option value="' .  $arg  . '" class="form-control" selected>'. ucfirst($arg) .'</option>';
                }
                $output .= "</Select>";
                break;

            case 'checkbox' :

                $output .= '<div class="form-check">';
                $output .= '<input name="'. $fieldName .'" class="form-check-input" type="checkbox"' . ($key_value == 'on'? 'checked' : ' ') .'>';
                $output .= '</div>';
                break;

            default:
                $output .= '<input type="text" name="'. $fieldName. '" value="' . $key_value . '" class="form-control">';
                break;
          
        }
        
        $output .= '<br>';
        echo $output;

    }

    /*
    * Saves the meta box info for the post
     * - wp_barometer_meta_save
     * @param Post ID
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
            'wp_bar_display_total',
            ];

        //die(var_dump($_POST));
        foreach($_POST as $key => $value){
            if(in_array($key, $meta_keys)) {
                 if (isset($_POST[$key])) {
                     update_post_meta($post_id, $key, sanitize_text_field($value));
                 }
            }

        }

    }
    add_action( 'save_post', 'wp_barometer_meta_save', 10, 3);

    /**
    * Register all shortcodes
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
    * @param Array $atts
    *
    * @return string
    */
    function wp_barometer_shortcode($args) {


       echo 'hi786';

    }








