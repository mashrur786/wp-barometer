<?php

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
		'not_found' => __( 'No Search Barometer Found', 'wpbarometer' ),
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
    add_meta_box( 'wpbarometer_meta', __( 'WP Barometer Options' ), 'wpbarometer_metabox_display', 'tdd_pb' );
    }
    add_action( 'add_meta_boxes', 'wp_barometer_metabox_create' );


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
         $width = '100%';
         $bgColor = "#eed7ca";
         $barColor = "#c37041";
         $counterSpeed = 3000;
         $animationSpeed = 3000;
         $displayTotal = true;

        ?>
        <table class="form-table">
            <thead>

            </thead>
            <tbody>
                <tr>
                   <td>hi</td>
                </tr>
            </tbody>


        </table>
    <?php

    }