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
		'show_in_nav_menus' => true,
		'supports' => array(
			'title'
			),
		);
	    register_post_type( 'wpbarometer', $args );
    }

    add_action( 'init', 'wp_barometer_register_post_type' );

    /*
    * Setup custom meta boxes for the wp-barometer custom post type page
    */
    function wp_barometer_metabox_create() {
        add_meta_box( 'wpbarometer_meta', __( 'WP Barometer Options' ), 'wpbarometer_metabox_display', 'wpbarometer' );
    }
    add_action( 'add_meta_boxes', 'wp_barometer_metabox_create' );

    /*
    * load bootstrap stylesheet and js for admin page
    */
    function wp_barometer_admin_load_assets() {
         wp_register_style( 'bootstrap stylesheet',  plugin_dir_url( __FILE__ ) . 'admin/css/bootstrap.css', false, '4.1.3' );
         wp_enqueue_style( 'bootstrap stylesheet' );
    }
    add_action( 'admin_enqueue_scripts', 'wp_barometer_admin_load_assets' );



    /**
    * Meta box display for the barometer post type.
    *
    * Provides the form controls necessary to select the color of the barometer as well as:
    * Percentage input
    * X of Y input
    * Label Display mode (only applicable if global show percentages option is on):
    *  - None
    *  - Percentage only
    *  - Text label
    *  - Percentage (text label)
    *  - Text label (percentage)
    */
    function wpbarometer_metabox_display( $post ){


         $goal = '60000';
         $raised =  '2000';
         $orientation = 'vertical';
         $width = '100%';
         $bgColor = "#eed7ca";
         $barColor = "#c37041";
         $counterSpeed = 3000;
         $animationSpeed = 3000;
         $displayTotal = true;

        ?>
        <table class="table table-striped table-borderless">
            <thead>
                <tr>
                    <th>Filed</th>
                    <th>Value</th>
                </tr>
            </thead>
            <tbody>
                <tr class="form-group">
                    <td>
                        <label  for="">
                            Target:
                        </label>
                    </td>
                    <td>
                        <input class="form-control" id="" name="__wp_barometer_target" type="text">
                    </td>
                </tr>
                <tr class="form-group">
                    <td>
                        <label for="">
                            Raised:
                        </label>
                    </td>
                    <td>
                        <input class="form-control" id="" name="__wp_barometer_raised" type="text">
                    </td>
                </tr>
                 <tr class="form-group">
                    <td>
                        <label for="">
                            Orientation:
                        </label>
                    </td>
                    <td>
                        <input class="form-control" id="" name="__wp_barometer_orientation" type="text">
                    </td>
                </tr>
                <tr class="form-group">
                    <td>
                        <label for="">
                            Width:
                        </label>
                    </td>
                    <td>
                        <input class="form-control" id="" name="__wp_barometer_width" type="text">
                    </td>
                </tr>
                <tr class="form-group">
                    <td>
                        <label for="">
                            Background Color:
                        </label>
                    </td>
                    <td>
                        <input class="form-control" id="" name="__wp_barometer_bgcolor" type="text">
                    </td>
                </tr>
                <tr class="form-group">
                    <td>
                        <label for="">
                            Bar Color:
                        </label>
                    </td>
                    <td>
                        <input class="form-control" id="" name="__wp_barometer_barcolor" type="text">
                    </td>
                </tr>
                 <tr class="form-group">
                    <td>
                        <label for="">
                            Counter Speed:
                        </label>
                    </td>
                    <td>
                        <input class="form-control" id="" name="__wp_barometer_cspeed" type="text">
                    </td>
                </tr>
                 <tr class="form-group">
                    <td>
                        <label for="">
                            Animation Speed:
                        </label>
                    </td>
                    <td>
                        <input class="form-control" id="" name="__wp_barometer_aspeed" type="text">
                    </td>
                </tr>
                 <tr class="form-group">
                    <td>
                        <label for="">
                            Display Total:
                        </label>
                    </td>
                    <td>
                        <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="__wp_barometer_display_total" value="" id="defaultCheck1">
                        </div>
                    </td>
                </tr>

            </tbody>
        </table>
    <?php


    /*
    * Saves the meta box info for the post
    */
    function wp_barometer_metabox_save( $post_id ) {
        if ( isset( $_POST['tdd_pb_color'] ) )
        update_post_meta( $post_id, '_tdd_pb_color', sanitize_html_class( $_POST['tdd_pb_color'] ) );

        if ( isset( $_POST['tdd_pb_custom_color'] ) )
        update_post_meta( $post_id, '_tdd_pb_custom_color', tdd_pb_sanitize_color_hex_raw( $_POST['tdd_pb_custom_color'] ) );
        else
        delete_post_meta( $post_id, '_tdd_pb_custom_color' );

        if ( isset( $_POST['tdd_pb_percentage'] ) ) {
        update_post_meta( $post_id, '_tdd_pb_percentage', abs( floatval( $_POST['tdd_pb_percentage'] ) ) );
        }
        if ( isset( $_POST['tdd_pb_start'] ) ) {
        update_post_meta( $post_id, '_tdd_pb_start', floatval( $_POST['tdd_pb_start'] ) );
        }
        if ( isset( $_POST['tdd_pb_end'] ) ) {
        update_post_meta( $post_id, '_tdd_pb_end', floatval( $_POST['tdd_pb_end'] ) );
        }
        if ( isset( $_POST['tdd_pb_input_method'] ) ){
        switch ( $_POST['tdd_pb_input_method'] ){
            case 'xofy':
                update_post_meta( $post_id, '_tdd_pb_input_method', 'xofy' );
                break;
            default:
                update_post_meta( $post_id, '_tdd_pb_input_method',  'percentage' );
        }
        }
        if ( isset( $_POST['tdd_pb_percentage_display'] ) ){
        update_post_meta( $post_id, '_tdd_pb_percentage_display', 'on' );
        } else {
        update_post_meta( $post_id, '_tdd_pb_percentage_display', 'off' );
        }
        if ( isset( $_POST['tdd_pb_xofy_display'] ) ){
        update_post_meta( $post_id, '_tdd_pb_xofy_display', 'on' );
        } else {
        update_post_meta( $post_id, '_tdd_pb_xofy_display', 'off' );
        }
    }
    add_action( 'save_post', 'tdd_pb_metabox_save' );
}








