<?php

/**
 * Plugin Name: Contact Plugin
 * Description: A simple contact form plugin for WordPress.
 * Version: 1.0
 * Author: Pablo Millaquén
 * License: GPL2
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if(!class_exists('ContactPlugin')) {
    class ContactPlugin {

        public function __construct() {

            define( 'MY_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
            require_once (MY_PLUGIN_DIR . 'vendor/autoload.php');
        }

        public function initialize() {
            include_once MY_PLUGIN_DIR . 'includes/utilities.php';
            include_once MY_PLUGIN_DIR . 'includes/contact-form.php';
            include_once MY_PLUGIN_DIR . 'includes/options-page.php';
        }
    
    }

    $contact_plugin = new ContactPlugin();
    $contact_plugin->initialize();

}