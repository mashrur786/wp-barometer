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

    register_activation_hook( __FILE__, 'wpbarometer_init' );

    if(!function_exists(wpbarometer_init)){
        function wpbarometer_init(){
    }


}